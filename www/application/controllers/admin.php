<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('BaseController.php');

class Admin extends BaseController
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        
        // All methods of the Admin controller require authentication.
        $this->load->library('tank_auth');
        if (!$this->tank_auth->is_logged_in())
            redirect(config_item('base_url') . 'auth/login/');
    }

    /********************
    * ROUTES
    ********************/

    public function index($success = null)
    {
        $projectObj = new Project();
        $projectObj->get();
        $data = array('projects' => $projectObj->all);
        $data['message'] = $success == 'success' ? 'Project saved successfully' : '';
        $this->load->view('admin_index', $data);
    }

    public function view_project($projectId = null, Project $projectObj = null, $errors = array())
    {
        if (!$projectObj)
        {
            $projectObj = new Project();
            if (is_numeric($projectId) && $projectId > 0)
            {
                $projectObj = new Project($projectId);

                // If a ID was passed but the ID doesn't match a project, throw a 404 error.
                if (!count($projectObj->all))
                    show_404();
            }
            elseif ($projectId == 'create')
            {
                $projectObj = new Project();
            }
            else
            {
                // Invalid ID.
                show_404();
            }
        }

        $data['project'] = $projectObj;
        $data['saveBtnText'] = $projectId == 'create' ? 'Create Project' : 'Save Project';
        $data['errors'] = $errors;
        $this->load->view('admin_view_project', $data);
    }

    private function refresh_tags()
    {
        // Get all unique tags for all projects.
        $projectObj = new Project();
        $projectTags = $projectObj->select('tags')->get_where(array('enabled' => true));
        $tags = array();
        foreach ($projectTags as $pTag)
            $tags = array_merge($tags, array_map('strtolower', array_map('trim', preg_split('/,/', $pTag->tags))));
        $uniqueTags = array_unique($tags, SORT_STRING);

        // Now truncate the tags table and insert all unique tags.
        $tagObj = new Tag();
        if (!$tagObj->truncate())
            throw new Exception('Unable to truncate tags table');
        foreach ($uniqueTags as $uTag)
        {
            $tagObj = new Tag();
            $tagObj->text = $uTag;
            $tagObj->created = DateHelper::formatDateForDB(); // Current datatime.
            $tagObj->save();
            $tagObj = null;
        }
    }
    
    public function save_project()
    {
        $errors = array();
        $projectObj = null;

        try
        {
            // Make sure request method is POST.
            if ($_SERVER['REQUEST_METHOD'] !== 'POST')
                throw new Exception('This request must be a POST!');

            // See if this is an existing project.
            if (is_numeric($this->input->post('id')) && $this->input->post('id') > 0)
            {
                // Load the project data.
                $projectObj = new Project($this->input->post('id'));

                if (!$projectObj)
                    throw new Exception('No project found for ID: ' . $this->input->post('id'));
                
                $projectObj->created = $this->input->post('created');
            }
            else
            {
                // This is a new project.
                $projectObj = new Project();
                $projectObj->created = DateHelper::formatDateForDB(); // Current datatime.
            }

            // TODO: Parse RTF for intro, quote and description: http://webcheatsheet.com/php/reading_the_clean_text_from_rtf.php
            
            // Fill project object with POST data.
            $projectObj->name = $this->input->post('name');
            $projectObj->url_key = $this->input->post('url_key');
            $projectObj->tags = $this->input->post('tags');
            $projectObj->intro = $this->input->post('intro');
            $projectObj->description = $this->input->post('description');
            $projectObj->quote = $this->input->post('quote');
            $projectObj->video_screenshot_url = ($this->input->post('video_screenshot_url'));
            $projectObj->video_url = $this->input->post('video_url');
            $projectObj->flickr_photo_set_id = $this->input->post('flickr_photo_set_id');
            $projectObj->resource_links = $this->input->post('resource_links');
            $projectObj->enabled = $this->input->post('enabled') ? 1 : 0; // Convert to boolean.
            $projectObj->updated = DateHelper::formatDateForDB(); // Current datatime.
            
            // Save the project.
            if ($projectObj->save())
            {
                // Once the project has been saved, we can use the generated ID to save the uploaded files.
                $smallImageUrl = $this->save_image($projectObj, 'small_image_url');
                $largeImageUrl = $this->save_image($projectObj, 'large_image_url');
                $saveImages = false;
                if (strlen($smallImageUrl))
                {
                    $projectObj->small_image_url = $smallImageUrl;
                    $saveImages = true;
                }
                if (strlen($largeImageUrl))
                {
                    $projectObj->large_image_url = $largeImageUrl;
                    $saveImages = true;
                }
                if ($saveImages)
                {
                    if (!$projectObj->save())
                        throw new Exception('After saving project data, unable to save project images!');
                }
                
                // When saving a project, need to refresh ALL tags based on ALL projects.
                $this->refresh_tags();
                
                // If we are successful, redirect to project list page and show success message.
                redirect(config_item('base_url') . 'admin/success');
                die;
            }
            else
            {
                // There were errors so display them on the project page.
                foreach ($projectObj->error->all as $error)
                    array_push($errors, $error);
                $this->view_project($projectObj->id, $projectObj, $errors);
            }
        }
        catch (Exception $ex)
        {
            // If there's an exception, store it in the errors array and pass that to the edit project page.
            $this->view_project(null, $projectObj, array("An error occured: " . $ex->getMessage()));
        }
    }

    public function delete_project()
    {
        // FIXME: Add JS confirmation for delete!
        try
        {
            // Make sure request method is POST.
            if ($_SERVER['REQUEST_METHOD'] !== 'POST')
                throw new Exception('This request must be a POST!');

            // Get project ID from POST.
            $projectId = $this->input->post('id');
            
            if (!is_numeric($projectId) &&$projectId < 1)
                throw new Exception('Invalid ID passed to delete_project(): ' . $projectId);

            // Load the project and then delete it.
            $projectObj = new Project($projectId);
            if (!$projectObj)
                throw new Exception('No project found for ID: ' . $projectId);
            if (!$projectObj->delete())
                throw new Exception('Unable to delete project with ID = ' . $projectId . '. Error: ' . $projectObj->error->all[0]);

            // Delete the images associated with this project.
            $files = glob(config_item('file_upload_path') . $this->build_project_image_prefix($projectId) . '*');
            foreach ($files as $file)
                if (is_file($file))
                    if (!unlink($file))
                        throw new Exception('Unable to delete file: ' . $file);

            // When deleting a project, need to refresh ALL tags based on ALL projects.
            $this->refresh_tags();
            
            // If we are successful, redirect to project list page and show success message.
            redirect(config_item('base_url') . 'admin/delete_success');
            die;
        }
        catch (Exception $ex)
        {
            // If there's an exception, store it in the errors array and pass that to the edit project page.
            $this->view_project(null, $projectObj, array("An error occured: " . $ex->getMessage()));
        }
    }

    private function build_project_image_prefix($projectId)
    {
        return $projectId . '_';
    }
    
    private function save_image(Project &$project, $field_name)
    {
        if (!is_numeric($project->id) || $project->id < 1)
            throw new Exception('Invalid project ID: ' . $project->id);
        if (!strlen($field_name))
            throw new Exception('Field_name cannot be blank!');
        
        // Check to see if this image was included in the post.
        if (!$_FILES[$field_name] || !$_FILES[$field_name]['name'])
            return; // Nothing to do.

        if ($_FILES[$field_name]['error'] > 0)
            throw new Exception('Unable to save image: ' . $field_name);

        // Get the temp location where the uploaded image was stored.
        $tmpName = $_FILES[$field_name]['tmp_name']; 

        // Check the mime-type to make sure the image is a png, jpg or gif.
        $mimeType = $_FILES[$field_name]['type'];
        $ext = null;
        if (preg_match("/(jpeg|jpg)/", $mimeType))
            $ext = '.jpg';
        elseif (preg_match("/png/", $mimeType))
            $ext = '.png';
        elseif (preg_match("/gif/", $mimeType))
            $ext = '.gif';
        else
            throw new Exception('Unable to save image for: ' . $field_name . '. Only JPEG, PNG and GIF are supported. Got: ' . $mimeType);

        // Build destination path and destination URL part.
        $fileName = $this->build_project_image_prefix($project->id) . $field_name . $ext;
        $destPath = config_item('file_upload_path') . $fileName;
        $destUrl = config_item('file_upload_base_url') . $fileName;
        
        // Move the image from the tmp location to the correct folder. Note: If the destination file already exists, it will be overwritten.
        if (!move_uploaded_file($tmpName, $destPath))
            throw new Exception('Unable to save image: ' . $field_name);

        return $destUrl;
    }
    
    public function logout()
    {
        $this->tank_auth->logout();
        redirect(config_item('base_url') . 'admin/');
    }
    
    public function show_phpinfo()
    {
        echo phpinfo();
    }
}

/* End of file admin.php */
/* Location: ./application/controllers/admin.php */

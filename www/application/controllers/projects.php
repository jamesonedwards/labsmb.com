<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('BaseController.php');

class Projects extends BaseController
{
    const FLICKR_PHOTO_SET_CACHE_ID_BASE = 'projects_page_flickr_photo_set_';

    function __construct()
    {
        parent::__construct();
    }

    /**
     * Index Page for this controller.
     */
    public function index($projectUrl = null, $lastSearch = null, $preview = null)
    {
        $where = array('url_key' => $projectUrl);
        $data['previewMode'] = false;
        $data['lastSearch'] = $lastSearch;
        
        // If we came from the search page, show lastSearch link, else show referrer if the referrer is from our domain.
        if (strlen($lastSearch))
        {
            $data['referrer'] = '/search/' . $lastSearch;
        }
        elseif (isset($_SERVER['HTTP_REFERER']))
        {
            $parts = parse_url($_SERVER['HTTP_REFERER']);
            if (isset($parts['host']) && strpos(strtolower($parts['host']), strtolower(config_item('base_url'))) == 0)
            {
                $data['referrer'] = $_SERVER['HTTP_REFERER'];
            }
        }
        else
        {
            $data['referrer'] = null;
        }

        // If we're in preview mode, make sure the user is logged into the CMS, and then remove the "enabled" requirement.
        if ($preview == 'preview')
        {
            $this->load->library('tank_auth');
            if (!$this->tank_auth->is_logged_in())
                redirect(config_item('base_url') . 'auth/login/');
            $data['previewMode'] = true;
        }
        else
        {
            $where['enabled'] = true;
        }
        
        // This determines which page we're on.
        $data['page'] = 'projects_index';
        
        // Throw a 404 error if no URL is given.
        if (!strlen($projectUrl))
            show_404();
        
        $projectObj = new Project();
        $projectObj->order_by('sort_order', 'ASC')->order_by('created', 'DESC')->get_where($where);

        // Throw a 404 error if no project is found for the given URL.
        if (!count($projectObj->all))
            show_404();

        $data['project'] = $projectObj;

        // If there is no Flickr ID, we can't get the images. This prevents an error when we try to iterate over the array.
        $data['flickrImages'] = array();
        
        if (strlen($projectObj->flickr_photo_set_id))
        {
            // Get the Flickr photo set for this project.
            $flickrApiUrl = 'http://api.flickr.com/services/feeds/photoset.gne?nsid=76088715@N06&lang=en-us&format=json&set=' . $projectObj->flickr_photo_set_id;
            $flickrJson = $this->make_cached_request($flickrApiUrl, Projects::get_flickr_photo_set_cache_id($projectObj->id), config_item('flickr_photo_set_cache_seconds'));
            $photoSet = json_decode(Projects::format_flickr_json($flickrJson));
            
            // Add the small and big image URLs with some string manipulation.
            foreach ($photoSet->items as $item)
            {
                $item->media->s = preg_replace('/m.jpg$/', 's.jpg', $item->media->m);
                $item->media->b = preg_replace('/m.jpg$/', 'b.jpg', $item->media->m);
            }
            $data['flickrImages'] = $photoSet->items;
        }
        
        // Load the view, passing in the data array.
        $this->load->view('projects_index', $data);
    }
    
    /**
    * HACK: Flickr returns JavaScript rather than plain JSON, so convert it.
    * 
    * @param mixed $json
    */
    public static function format_flickr_json($json)
    {
        $json = str_replace('jsonFlickrFeed(', '', $json);
        $json = substr($json, 0, strlen($json) - 1); //strip out last paren
        return $json;
    }

    private static function get_flickr_photo_set_cache_id($projectId)
    {
        return Projects::FLICKR_PHOTO_SET_CACHE_ID_BASE . $projectId;
    }
}

/* End of file projects.php */
/* Location: ./application/controllers/projects.php */
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('BaseController.php');

class Search extends BaseController
{
    /**
     * Index Page for this controller.
     */
    public function index($lastSearch = '')
    {
        // This determines which page we're on.
        $data['page'] = 'search_index';
        
        // This is the most recent search, for the back feature.
        $data['lastSearch'] = $lastSearch;
        
        $this->load->view('search_index', $data);
    }
    
    /**
    * This is the search service. It performs a full text search of the projects table and returns JSON.
    * 
    * @param mixed $str
    */
    public function do_search($str = null, $fts = false)
    {
        $this->setContentTypeHeader(Search::OUTPUT_FORMAT_JSON);

        try
        {
            // Check type for FTS param.
            try
            {
                $fts = toBoolean($fts);
            }
            catch (Exception $ex)
            {
                // Throw a meaningful exception instead of the one thrown by toBoolean().
                throw new Exception('When using the fts parameter, the value must be either true or false. Given: ' . $fts, null, $ex);
            }
            
            $projects = array();
            $projectCnt = 0;

            //if (strlen(trim($str)))
            //{
            $projectObj = new Project();
            $results = $projectObj->search($str, $fts);

            // Don't return all fields.
            foreach ($results as $result)
            {
                array_push($projects, array(
                    'name' => $result->name,
                    'small_image_url' => $result->small_image_url,
                    'created' => $result->created,
                    'intro' => $result->intro,
                    'tags' => $result->tags,
                    'url_key' => $result->url_key,
                    'url' => 'http://' . $_SERVER['HTTP_HOST'] . '/projects/' . $result->url_key . '/' . $str
                ));
                $projectCnt++;
            }
            //}
            
            // Return projects array as JSON.
            echo json_encode(array('do_search' => array('projects' => $projects, 'count' => $projectCnt)));
        }
        catch (Exception $ex)
        {
            // Log error and return error as JSON.
            log_message('error', $ex->getMessage());
            echo SubmissionResponse::fromException($ex)->asJson();
        }
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
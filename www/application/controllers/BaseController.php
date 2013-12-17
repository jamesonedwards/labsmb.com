<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

abstract class BaseController extends CI_Controller
{
    const OUTPUT_FORMAT_JSON = 'json';
    const OUTPUT_FORMAT_XML = 'xml';
    const OUTPUT_FORMAT_CSV_DOWNLOAD = 'csv';
    const OUTPUT_FORMAT_EXCEL_DOWNLOAD = 'excel';
    const CACHED_REQUEST_USER_AGENT = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.8.0.9) Gecko/20061206 Firefox/1.5.0.9';
    
	function __construct()
	{
		parent::__construct();
        $this->load->driver('cache', array('adapter' => 'file'));
	}
    
    
    /**
    * Wrap an API call in CI caching.
    * 
    * @param mixed $url
    */
    protected function make_cached_request($url, $cacheId, $ttl)
    {
        /*
        Note: Caching docs are here:
        http://ellislab.com/codeigniter/user-guide/libraries/caching.html
        */
        $cache = $this->cache->get($cacheId);
        
        /*
        Array
        (
            [0] => HTTP/1.0 400 Bad Request
            [1] => Cache-Control: no-cache
            [2] => X-RateLimit-Limit: 150
            [3] => X-RateLimit-Remaining: 0
            [4] => X-RateLimit-Reset: 1362436108
            [5] => X-RateLimit-Class: api
            [6] => Content-Type: application/json;charset=utf-8
            [7] => X-Transaction: 21aa81aec9d4e1d2
            [8] => X-Frame-Options: SAMEORIGIN
            [9] => Status: 400 Bad Request
            [10] => Date: Mon, 04 Mar 2013 22:01:21 GMT
            [11] => Content-Length: 159
            [12] => Server: tfe
            [13] => Set-Cookie: guest_id=v1%3A136243448137163571; Domain=.twitter.com; Path=/; Expires=Wed, 04-Mar-2015 22:01:21 UTC
        )
        */
        
        if (!$cache)
        {
            // Set the user agent header so that this request looks legit.
            $opts = array(
              'http'=>array(
                'method'=>"GET",
                'header'=>"User-agent: " . BaseController::CACHED_REQUEST_USER_AGENT . "\r\n"
              )
            );

            // Create a stream.
            $context = stream_context_create($opts);

            // Open the file using the HTTP headers set above.
            $cache = file_get_contents($url, false, $context);
            
            // Only save the cache if we get a valid ("200") response.
            if (strpos($http_response_header[0], '200') !== FALSE)
                $this->cache->save($cacheId, $cache, $ttl);
        }
        
        return $cache;
    }
    
    /**
    * Set content-type header for the requested output format.
    * 
    * @param mixed $format
    */
    public function setContentTypeHeader($format = BaseController::OUTPUT_FORMAT_JSON)
    {
        switch ($format)
        {
            case BaseController::OUTPUT_FORMAT_JSON:
                header('Content-Type: application/json');
                break;
            case BaseController::OUTPUT_FORMAT_XML:
                header('Content-Type: text/xml');
                break;
            case BaseController::OUTPUT_FORMAT_CSV_DOWNLOAD:
				header("Pragma: public");
				header("Expires: 0");
				header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
				header("Cache-Control: private",false);
				header("Content-Type: application/octet-stream");
				header("Content-Transfer-Encoding: binary");
                break;
            case BaseController::OUTPUT_FORMAT_EXCEL_DOWNLOAD:
				header('Pragma: public');
				header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");                  // Date in the past   
				header('Last-Modified: '.gmdate('D, d M Y H:i:s') . ' GMT');
				header('Cache-Control: no-store, no-cache, must-revalidate');     // HTTP/1.1
				header('Cache-Control: pre-check=0, post-check=0, max-age=0');    // HTTP/1.1
				header ("Pragma: no-cache");
				header("Expires: 0");
				header('Content-Transfer-Encoding: none');
				header('Content-Type: application/vnd.ms-excel;');                 // This should work for IE & Opera
				header("Content-type: application/x-msexcel");                    // This should work for the rest
				//header("Content-Transfer-Encoding: binary");
                break;
            default:
                // Do nothing.
        }
    }
}

/* End of file BaseController.php */
/* Location: ./application/controllers/BaseController.php */
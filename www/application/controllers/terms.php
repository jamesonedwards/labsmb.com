<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('BaseController.php');

class Terms extends BaseController
{
	/**
	 * Index Page for this controller.
	 */
	public function index()
	{
        // This determines which page we're on.
        $data['page'] = 'terms_index';
        
		$this->load->view('terms_index', $data);
	}
}

/* End of file terms.php */
/* Location: ./application/controllers/terms.php */
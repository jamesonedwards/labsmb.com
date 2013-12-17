<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('BaseController.php');

class Info extends BaseController {

	/**
	 * Index Page for this controller.
	 */
	public function index()
	{
        // This determines which page we're on.
        $data['page'] = 'info_index';
        
		$this->load->view('info_index', $data);
	}
}

/* End of file info.php */
/* Location: ./application/controllers/info.php */
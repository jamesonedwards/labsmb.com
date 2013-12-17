<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('BaseController.php');

class Tags extends BaseController
{
    /**
     * Index Page for this controller.
     */
    public function index()
    {
        // TODO: Moving this to phase 2.
        show_404(null, false);
        
        // This determines which page we're on.
        $data['page'] = 'tags_index';
        
        $this->load->view('tags_index', $data);
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
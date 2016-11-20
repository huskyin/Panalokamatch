<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pages extends CI_Controller 
{

     function __construct()
     {
        parent::__construct();

        // load model
        $this->load->model('M_users');
        $this->load->model('M_manager');
        $this->load->model('M_outlet') ;

        // session control
        if ($this->session->userdata('member') == '' )
         {
            $this->session->set_flashdata('Denied', 'You have no access!!') ;
            redirect('') ;
         }
    }




    public function index()
    {
      
      if ( IS_MAINTENANCE() == 1 )
           redirect('maintenance');

      $data = array('title'=> 'Panaloka Match - Trully Live Matching') ;
      $this->load->view('vd_pages/v_play',$data);
    }

}

/* End of file pages.php */
/* Location: ./application/controllers/pages.php */

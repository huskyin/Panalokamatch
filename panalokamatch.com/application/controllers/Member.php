<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Member extends CI_Controller 
{

     function __construct()
     {
        parent::__construct();

        // load model
        

        // session control
    /*    if ($this->session->userdata('member') == '' )
         {
            $this->session->set_flashdata('Denied', 'You have no access!!') ;
            redirect('') ;
         } */
    }




    public function index()
    {
      
      if ( IS_MAINTENANCE() == 1 )
           redirect('maintenance');

      $data = array('title'=> 'Panaloka Match - Trully Live Matching') ;
      $this->load->view('vd_pages/v_play',$data);
    }

    function Matches()
    {
      $ctrl_data['title'] = 'Matches' ;
      $this->load->view('vd_member/v_matches',$ctrl_data) ;
    }

}

/* End of file pages.php */
/* Location: ./application/controllers/pages.php */

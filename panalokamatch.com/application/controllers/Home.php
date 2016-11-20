<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller 
{

/*
  Setiap kali mengajukan pertemuan akan ditanya:
  "Mohon Anda menjawab dengan JUJUR apa adanya:
    Apa NIAT ANDA SEBENARNYA untuk menemui pria/wanita ini?"

    - Saya hanya sekedar iseng
    - Saya hanya ingin mencari kenalan / teman
    - Saya ingin mencari pacar
    - Saya memang berniat serius mencari suami / istri ke arah pernikahan 







*/







    public function index()
    {
      
      if ( IS_MAINTENANCE() == 1 )
           redirect('maintenance');

      /*
      if ($this->session->userdata('is_login'))
      {
        $gate = $this->load->view('vd_pages/v_logout','',true);
        $username = $this->session->userdata['username'];
      }
      else 
      {
        $gate = $this->load->view('vd_pages/v_formlogin','',true);
        $username = 'Panaloka Match';
      }
      */

      //Ambil content Halaman
      //
     // $this->data['formlogin'] =  $this->load->view('vd_pages/v_formlogin',"",TRUE);
      //$content = $this->load->view('vd_pages/v_home',$gate,TRUE);
      
     // $registrasi = $this->load->view('vd_pages/v_registrasi',"",TRUE);
      $data = array('title'=> 'Panaloka Match - Trully Live Matching') ;
      $this->load->view('vd_pages/v_play',$data);
    }

}

/* End of file pages.php */
/* Location: ./application/controllers/pages.php */

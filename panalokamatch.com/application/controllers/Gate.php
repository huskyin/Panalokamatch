<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gate extends CI_Controller
{
  function __construct(){
        parent::__construct();  

        if ( IS_MAINTENANCE() == 1 )
           redirect('maintenance');
          /*  
         $this->load->model('M_users');
        $this->load->model('M_pkt');
        $this->load->model('M_membership');
        $this->load->model('M_pin') ;
        */

        /*
        Khusus untuk Gate Tidak dikunci karena untuk login 
        */
        
       
        

  }

  function TESTFUNCTION($stockis)
  {
    $pin = $this->M_pin->MAmbilPinBaru($stockis) ;
    echo $pin['pin'] ;
  }


   public function register()
    {
      $ctrl_data['title'] = 'Register Member Baru' ;
      if ($this->session->userdata('is_login') )  //or 'userlevel'
        {
            redirect('');
            
        }


      $this->load->view('vd_pages/v_register',$ctrl_data);
    }








/* Fungsi Login, diselesaikan oleh Rian, dengan dilengkapi Errors Handler , dibuat oleh Rian
===============================================================================================================================
*/


  function verrify()
  {
       if(isset($_GET['email']) && !empty($_GET['email']) AND isset($_GET['hash']) && !empty($_GET['hash']))
        {
        // Verify data
          $email = mysql_escape_string($_GET['email']); // Set email variable
          $hash = mysql_escape_string($_GET['hash']); // Set hash variable

          $check = $this->M_verrify->M_CheckVerificationEmail($email,$hash) ;
          if ($check['s'] = 'SUCCESS')
          {
             $this->M_verrify->DelVerrify($email) ;
            $this->session->set_flashdata('verrify','Email '.$email.' telah Ter-verifikasi, kini Anda bisa login ke Panaloka dengan email dan password Anda.') ;
            $this->load->view('vd_pages/v_login') ;
          }
          else{
            $this->session->set_flashdata('verrify','Email GAGAL, silahkan check lagi') ;
            $this->load->view('vd_pages/v_login') ;

          }

        }
  }


   public function login()
  {  
      if ($this->session->userdata('is_login') )  //or 'userlevel'
        {
            redirect('');
        }

      $data = array('username' => $this->input->post('username', TRUE),
                    'password' => ($this->input->post('password', TRUE)));

	// huskyon.com - Inisiasi sementara
      $data['captcha'] = TRUE;

    $this->load->model('M_users'); // load model_user
    $hasil = $this->M_users->validate($data);
    if ($hasil['s'] == 'SUCCESS') 
    {
      	$user_id = $hasil['user_id'] ;        
        $check = $this->M_membership->M_Check_Bonus_by_Id($user_id);
        $tgl_pasif = $check['deadline_tutup_poin'] ;
        $status = $check['status'] ;

        // if registration successful, create a session

      	//Set ke Session (semua informasi dari tabel t_user dipindahkan ke sini)
					$session_data = array(
								        'is_login' => TRUE,
								      'user_id' 	=> $user_id,
        					   'username'   => $hasil['username'],
        					   'userlevel'  => $hasil['userlevel'],
        					    'password'   =>  $hasil['password'],
        					    'sponsor_id' => $hasil['sponsor_id'],
        					    'nama'       => $hasil['nama'],
        					    'tgl_pasif'  => $tgl_pasif ,
        					    'pin' 		=>  $hasil['pin'],
        					    'token_login' => '1@mGeniusMan!'.$hasil['username'],
                      'match_ip' => $_SERVER['REMOTE_ADDR'].$hasil['user_id'],
                      'last_ip' => $hasil['last_ip'],
                      'last_login' => $hasil['last_login'],
        					   'status'  	=>	$status) ;
        					  
       			       $this->session->set_userdata($session_data);

                   $this->M_users->set_lastlogin($this->session->userdata('user_id'),$_SERVER['REMOTE_ADDR']) ;
                
                   $this->M_membership->M_B4_CheckUserAktif($hasil['user_id']) ;

             if ($this->session->userdata('userlevel')=='PROADMIN') 
              {
                 $this->M_pin->hapus_pin_kadaluarsa() ;
                 redirect('cd_proadmin/C_dashboard');
               }
              elseif ($this->session->userdata('userlevel')=='ADMIN') 
              {
                redirect('cd_admin/C_dashboard');
              } 
              elseif ($this->session->userdata('userlevel')=='MEMBER') 
              {
                redirect('cd_member/C_dashboard');
               }        
    }
    else 
    {
      echo "<script>alert('Gagal login: Cek username, password!');history.go(-1);</script>";
    }
  }
/* END Fungsi Login, diselesaikan oleh Rian, dengan dilengkapi Errors Handler , dibuat oleh Rian
===============================================================================================================================
*/



    public function logout()
     {
            $this->load->library('session');
            //Set ke Session (semua informasi dari tabel t_user dipindahkan ke sini)
          $session_data = array(
                'is_login' => $this->session->userdata('is_login'),
                'user_id'   => $this->session->userdata('user_id'),
                     'username'   => $this->session->userdata('username'),
                     'userlevel'  => $this->session->userdata('userlevel'),
                      'password'   =>  $this->session->userdata('password'),
                      'sponsor_id' => $this->session->userdata('sponsor_id'),
                      'nama'       => $this->session->userdata('nama'),
                      'tgl_pasif'  => $this->session->userdata('tgl_pasif'),
                      'token_login' =>   '$2y$10$E7jKGZDnADugmAcF3Q1qWOf9aV6SlOFx3ybukudNG4kp3KPIZCQU2'.$this->session->userdata('username'),
                     'status'   => $this->session->userdata('status'));
            $this->session->unset_userdata($session_data);         
            $this->session->sess_destroy();
            redirect(base_url());
     }

   



public function email_check()
    {
        // allow only Ajax request    
        if($this->input->is_ajax_request()) 
        {
        // grab the email value from the post variable.
        $email = $this->input->post('email');
        // check in database - table name : tbl_users  , Field name in the table : email
        $pattern = '/^admin/' ;
        if (preg_match($pattern,$email) )
        {
          // set the json object as output                 
         $this->output->set_content_type('application/json')->set_output(json_encode(array('message' => '<b><font style="color:red;">Email Not Available</font></b>','submitbutton' => '<b><font style="color:red;">Off , Plese FIX Error First!</font></b>')));
        }
        $this->form_validation->set_rules('email', 'email', 'trim|required|valid_emails|min_length[5]|max_length[40]');
        if($this->form_validation->run() == false) 
        {
            $this->output->set_content_type('application/json')->set_output(json_encode(array('message' => '<b><font style="color:red;">Wrong Email!</font></b>','submitbutton' => '<b><font style="color:red;">Off , Plese FIX Error First!</font></b>')));

        }


        if(!$this->form_validation->is_unique($email, 't_users.email')) 
          {
        // set the json object as output                 
         $this->output->set_content_type('application/json')->set_output(json_encode(array('message' => '<b><font style="color:red;">Email Not Available</font></b>','submitbutton' => '<b><font style="color:red;">Off , Plese FIX Error First!</font></b>')));
          }
        }
    }

/* check username in table database */

public function username_check()
    {
         // allow only Ajax request    
        if($this->input->is_ajax_request()) 
        {
        // grab thevalue from the post variable.
        $username = $this->input->post('username');
        // check in database - table name : tbl_users  , Field name in the table : username
        $pattern = '/^admin/' ;
        if (preg_match($pattern,$username) )
        {
          // set the json object as output                 
         $this->output->set_content_type('application/json')->set_output(json_encode(array('message' => '<b><font style="color:red;">Username Not Available</font></b>','submitbutton' => '<b><font style="color:red;">Off , Plese FIX Error First!</font></b>')));
        }


        $this->form_validation->set_rules('username', 'username', 'trim|required|alpha_dash|min_length[5]|max_length[40]');
        if($this->form_validation->run() == false) 
        {
            $this->output->set_content_type('application/json')->set_output(json_encode(array('message' => '<b><font style="color:red;">Wrong character, Username Min.5 - 40 length</font></b>','submitbutton' => '<b><font style="color:red;">Off , Plese FIX Error First!</font></b>')));

        }


        if(!$this->form_validation->is_unique($username, 't_users.username')) 
          {
        // set the json object as output                 
         $this->output->set_content_type('application/json')->set_output(json_encode(array('message' => '<b><font style="color:red;">Username Not Available</font></b>','submitbutton' => '<b><font style="color:red;">Off , Plese FIX Error First!</font></b>')));
          }
        }
    }


public function hp_check()
    {
         // allow only Ajax request    
        if($this->input->is_ajax_request()) 
        {
        // grab thevalue from the post variable.
        $hp = $this->input->post('hp');
        // check in database - table name : tbl_users  , Field name in the table : hp
       

         $this->form_validation->set_rules('hp', 'hp', 'trim|required|numeric|min_length[2]|max_length[20]');
        if($this->form_validation->run() == false) 
        {
            $this->output->set_content_type('application/json')->set_output(json_encode(array('message' => '<b><font style="color:red;">No. HP salah!</font></b>','submitbutton' => '<b><font style="color:red;">Off , Plese FIX Error First!</font></b>')));

        }


        if(!$this->form_validation->is_unique($hp, 't_users.hp')) 
          {
        // set the json object as output                 
         $this->output->set_content_type('application/json')->set_output(json_encode(array('message' => '<b><font style="color:red;">No.hp Not Available</font></b>','submitbutton' => '<b><font style="color:red;">Off , Plese FIX Error First!</font></b>')));
          }
        }
    }


public function pin_check()
    {
         // allow only Ajax request    
        if($this->input->is_ajax_request()) 
        {
        // grab thevalue from the post variable.
        $pin = $this->input->post('pin');
        // check in database - table name : tbl_users  , Field name in the table : pin
       

         $this->form_validation->set_rules('pin', 'pin', 'trim|required|numeric|min_length[2]|max_length[18]');
        if($this->form_validation->run() == false) 
        {
            $this->output->set_content_type('application/json')->set_output(json_encode(array('message' => '<b><font style="color:red;">No. pin salah!</font></b>','submitbutton' => '<b><font style="color:red;">Off , Plese FIX Error First!</font></b>')));

        }


        if($this->form_validation->is_unique($pin, 't_pin.pin')) 
        {
             $this->output->set_content_type('application/json')->set_output(json_encode(array('message' => '<b><font style="color:red;">PIN TIDAK ADA!</font></b>','submitbutton' => '<b><font style="color:red;">Off , Plese FIX Error First!</font></b>')));

        }
        else
        {
         // di database PIN harus ADA dan TERVALIDASI ( 'status' = 1)
        $sql = "SELECT * FROM t_pin WHERE pin=?";   

        $condition = array($pin)  ;
        $query = $this->db->query($sql,$condition);  
       
        if ($query->num_rows() == 1)
        {
          $result = $query->row_array();
          $status_pin = $result['status'] ;
          if ($status_pin == 1)
          {
            $s = 'SUCCESS' ;
            $stockis = $result['stockis']; 
          }
          if ($status_pin == 0)
          {
            $s = 'PIN BELUM TERVALIDASI!';
             $this->output->set_content_type('application/json')->set_output(json_encode(array('message' => '<b><font style="color:red;">'.$s.'</font></b>','submitbutton' => '<b><font style="color:red;">Off , Plese FIX Error First!</font></b>')));

          }
          if ($status_pin == 2)
          {
            $s = 'PIN SUDAH DIGUNAKAN!' ;
             $this->output->set_content_type('application/json')->set_output(json_encode(array('message' => '<b><font style="color:red;">'.$s.'</font></b>','submitbutton' => '<b><font style="color:red;">Off , Plese FIX Error First!</font></b>')));
          }

        }

        }


        }
    }




public function sponsor_id_check()
    {
         


         // allow only Ajax request    
        if($this->input->is_ajax_request()) 
        {
        // grab thevalue from the post variable.
        $sponsor_id = $this->input->post('sponsor_id');
        // check in database - table name : tbl_users  , Field name in the table : sponsor_id
        $pattern = '/^admin/' ;
        if (preg_match($pattern,$sponsor_id) )
        {
          // set the json object as output                 
         $this->output->set_content_type('application/json')->set_output(json_encode(array('message' => '<b><font style="color:red;">sponsor_id Not Available</font></b>','submitbutton' => '<b><font style="color:red;">Off , Plese FIX Error First!</font></b>')));
        }


        $sql ="SELECT * FROM t_users WHERE user_id=?" ;
        $query = $this->db->query($sql,array($sponsor_id)) ;
        $result =  $query->row_array() ; $username_sponsor = $result['username'] ;

        $sql1 ="SELECT * FROM t_users_profile WHERE user_id=?" ;
        $query1 = $this->db->query($sql1,array($result['user_id'])) ;
        $result1 =  $query1->row_array() ;
        $nama_sponsor = $result1['nama'] ;
       
        if($query->num_rows() != 1) 
          {
        // set the json object as output                 
         $this->output->set_content_type('application/json')->set_output(json_encode(array('message' => '<b><font style="color:red;">Sponsor Not Exist</font></b>','submitbutton' => '<b><font style="color:red;">Off , Plese FIX Error First!</font></b>')));
          }
          else
          {
               $this->output->set_content_type('application/json')->set_output(json_encode(array('kenali_sponsor_anda' => '<b><font style="color:lightblue;"><h3>Kenali sponsor Anda. Betulkah ini? '.$nama_sponsor.' ('.$username_sponsor.') <br>USER ID: '.$sponsor_id.'<br><h3></font><br>
                  <img src="'.lihatfoto($sponsor_id).'">                       ')));

          }
         
        }
    }

public function conf_password_check()
    {
         // allow only Ajax request    
        if($this->input->is_ajax_request()) 
        {
        // grab thevalue from the post variable.
        $conf_password = $this->input->post('conf_password');
        $password = $this->input->post('password') ;


        $this->form_validation->set_rules('password', 'password', 'trim|required|min_length[5]|max_length[40]');
        if($this->form_validation->run() == false) 
        {
            $this->output->set_content_type('application/json')->set_output(json_encode(array('message' => '<b><font style="color:red;">Password Min.5 - 40 length</font></b>','submitbutton' => '<b><font style="color:red;">Off , Plese FIX Error First!</font></b>')));

        }


        if ($conf_password != $password)
          {
        // set the json object as output                 
         $this->output->set_content_type('application/json')->set_output(json_encode(array('message' => '<b><font style="color:red;">Password NOT Match!</font></b>','submitbutton' => '<b><font style="color:red;">Off , Plese FIX Error First!</font></b>')));
          }
        }
    }





    function registersuccess()
    {
      
      $this->load->view('vd_pages/v_registersuccess') ;

    }



 public function create()
    {
        $this->session->set_userdata('posting_register' , TRUE);
      $this->load->view('vd_pages/v_register');
    }


/*======Fungsi Register (Post Create) , diselesaikan oleh Rian ===============================================================
*/
   function post_create()
    {
   
         $PASS_00 = FALSE ;
        
        
        /*  huskyin.com - Membuat session data untuk mencegah data terkirim dua kali pada koneksi lambat
        sumber: 
        --http://blog.rosihanari.net/script-php-untuk-mencegah-submit-form-berulang-kali/
        --http://www.kurungkurawal.com/2013/05/14/cegah-double-post-dengan-http-postredirectget/
        */
        if($this->session->userdata('posting_register') == FALSE)
          die();
        else
        {
          $this->session->set_userdata('posting_register', FALSE);
          date_default_timezone_set('Asia/Jakarta');  
           $password_jadi = password_hash($_POST['password'],PASSWORD_DEFAULT);
        $data = array(
        'password'=>$password_jadi,
        'email'=>$_POST['email'],
        'username' => $_POST['email'],
        'hp'=>$_POST['hp'],
        
        'sponsor_id'=>$_POST['sponsor_id'],
        'last_login' => 'now()');
          $PASS_00 = TRUE ;
          $is_admin = $_POST['is_admin'] ;
        }
        
        if($PASS_00)
        {
            $message = 'REGISTRASI GAGAL! ';
            $set_flashdata = 'error' ;
            $check_setuju = $this->input->post('check_setuju') ;

            $PASS = TRUE ;
            if ($data['sponsor_id'] == 'proadmin')
            {
                 $PASS = FALSE ;
                  $set_flashdata = 'error';
                  $message = $message.'- Pro Admin TIDAK BOLEH MEREFERENSIKAN!' ;
            }
            if ($check_setuju != 'setuju')
            {
                 $PASS = FALSE ;
                  $set_flashdata = 'error';
                  $message = $message.'- Anda HARUS menyetujui Persyaratan dan Kondisi!' ;
            }
        }
        


        if ($PASS)
        {
            if ($is_admin == 'YES') // Admin mendaftar dengan Form Khusus
              {
                $data['stockis'] == '999' ;
                $data['level'] = 'ADMIN' ;
              }
             else {
              $data['stockis'] = $stockis = substr($data['sponsor_id'],2,3) ; ; // di-Substract PM10110 
              $data['level'] = 'MEMBER' ;
             }
          /* 
            //check pin di database 
           $datapin = $data['pin'] ;
           $cek_pin = $this->M_pin->checkpin($datapin);
          */
           $pin = $this->M_pin->MAmbilPinBaru($stockis) ;
               
           if ($pin['s'] != 'SUCCESS') 
              redirect('google.com') ;
            else 
               $data['pin'] = $datapin = $pin['pin'] ;
            //  BEGIN if Cek_pin 'SUCCESS'
       //   if ( $cek_pin['s'] == 'SUCCESS')
       //   {
                 
                $data['ver'] = 1 ;

            // Buat ID-nya 
            $inputmember = $this->M_membership->M_A_inputmember($data['stockis']) ;

            
             
            if ($inputmember['s'] == 'SUCCESS')
            { //  BEGIN if inputmember 'SUCCESS'
                $data['user_id'] = $inputmember['user_id'] ;

                //1.  input database t_users
                $input_t_users = $this->db->insert('t_users',$data) ;

                $set_flashdata = 'success';
                $message = '<h3>Haloo, <b>'.$data['username'].'</b>, berikut adalah data Anda:</h3><br><h1><b>--ID Anda: '.$data['user_id'].'-- PIN Anda: (BELUM DIBERITAHU) <br></b> </h1>-- , untuk selanjutnya Anda dapat login dengan  username(email) dan password Anda.' ;

                  //1. A. jika dia Admin / merchant masukkan ke tabel t_paket
                  if ($data['level'] == 'ADMIN')
                  {
                     $datapaket['user_id'] = $data['user_id'];
                     $datapaket['nama_merchant'] = 'MERCHANT AFILIASI PANALOKA' ;
                     $datapaket['profil_pkt'] = 'Dapatkan diskon cashback untuk nominal minimal belanja tertentu.Merchant ini merupakan salah satu merchant yang terafiliasi dengan Panaloka Nusantara Community';
                     $datapaket['diskon_pkt'] = 10 ;
                     $datapaket['tgl_pkt'] = date('Y-m-d') ;
                     $datapaket['operator1'] = 'operator_1' ;
                     $datapaket['password_opr1'] = password_hash('panaloka',PASSWORD_DEFAULT);
                     $datapaket['password_opr2'] = password_hash('panaloka',PASSWORD_DEFAULT);
                     $datapaket['operator2'] = 'operator_2' ;
                      $this->db->insert('t_paket',$datapaket) ;
                 }
               
                //2. input database ke t_users_profile
                $data_profile['user_id'] = $data['user_id'];
                $data_profile['username'] = $data['username'];
                $data_profile['pin'] = '' ;
                $input_t_users_profile = $this->db->insert('t_users_profile',$data_profile) ;

                //3. input database t_users_downline
                $data_downline['user_id'] = $data['user_id'];
              //  $data_downline['tgl_registrasi'] = $data['tgl_registrasi'] = time();
                $data_downline['list_downline'] = 'LIST:' ;
                $input_t_users_downline = $this->db->insert('t_users_downline',$data_downline) ;

                //4.  input database t_users_bonus
                $data_bonus['user_id'] = $data['user_id'] ;
                $data_bonus['status'] = 2;
                $deadline = 30 ;
                $data_bonus['deadline_tutup_poin'] =  date('Y-m-d',strtotime(date("Y-m-d", time()) . " + ".$deadline." day")) ;
                $input_t_users_bonus = $this->db->insert('t_users_bonus',$data_bonus) ;

               // $this->M_pin->pinterpakai($data,$data['user_id']) ;

                //  BEGIN if INputuser 'SUCCESS'
                if ($input_t_users)
                {
                   
                       // tambahkan daftar downline buat sponsornya
                      $list_downline = $this->M_membership->M_A5_inputlistdownline($data['sponsor_id'],$data['user_id']) ;
                       if ($list_downline['s'] == 'SUCCESS')
                       {
                           // $set_flashdata = 'success' ; 
                           $message = $message.'<br>Listing Added -'.$list_downline['s'] ;
                          
                            // Kemudian daftarkan downline buat uplinenya...untuk upline level 1,2,3

                            
                                     $user_id = $data['user_id'] ;
                                     for ($i = 0; $i < batas_lv() ; $i++)
                                      {
                                        $user_id = $get_sponsor = $this->M_membership->M_A2_getsponsor($user_id)['id'] ; 
                                         // echo 'User ID Generasi ke '.$i.' adalah '.$user_id.'<br>' ;
                                          $tambah_downline = $this->M_membership->M_A3_inputjaringan($user_id) ;

                                            if($tambah_downline['s'] == 'SUCCESS')
                                               $message1 = '-Downline Added for Upline' ;
                                            else
                                                $message1 = '-Downline Failed' ;
                                      }

                              if ($message1 == '-Downline Added for Upline' )
                              {
                                    $message = $message.$message1 ;
                                     $set_flashdata = 'success' ;  
                      
                                   $message = $message.'<br>Listing Add-'.$list_downline['s'] ;
                                   $message2 = '<h2>Verifikasi TELAH DIKIRIM ke email: '.$data['email'].' .Harap
                                                login ke PASTI dan check email Anda untuk mendapatkan Nomor PIN</h2>' ; 
                                   $message = 'REGISTRASI SUKSES! -- '.$message.$message2 ;
                                    $this->session->set_flashdata('success',$message) ;

                                    //Langsung kirim verifikasi email
                                    $this->load->model('M_verrify') ;
                                   $this->M_verrify->MSend_Mail($data['pin'],$data['email'],$data['user_id']) ;
                                      
                                    redirect('Gate/registersuccess') ;
                                  //  header('HTTP/1.1 302 Found');
                                  //  header('Location: '.base_url().'Gate/registersuccess');
                                    
                              }

                       }

                       else
                       {
                          $set_flashdata = 'error' ;
                          $message = $message.'LIST'.$input_list_downline['s'].'<br>List ID: '.$input_list_downline['list_id'];
                       }                         
                   
                }
                else
                {
                    $message = 'REGISTRASI GAGAL! LIST downline error' ;
                }       
             } //  END if inputmember 'SUCCESS'  
             else 
             {
                   $message = 'REGISTRASI GAGAL! '.$inputmember['s'] ;
             }        
     //    } //  END if Cek_pin 'SUCCESS' 
     //    else
     //    {
    //        $message = 'REGISTRASI GAGAL! '.$cek_pin['s'].$cek_pin['stockis'] ;
     //       $_POST['pin'] = '' ;
    //     }

        }// END FUNGSI jika id != proadmin

          $message = $message.'<br>HARAP PERBAIKI DATA ANDA!' ;  
           $this->session->set_flashdata($set_flashdata,$message) ;
           $this->session->set_flashdata('email',$_POST['email']);
           $this->session->set_flashdata('username',$_POST['username']);
           $this->session->set_flashdata('hp',$_POST['hp']);
           $this->session->set_flashdata('pin',$_POST['pin']);
          redirect("Gate/create") ;
    } 
    /*  END  Fungsi Post Create diselesaikan oleh Rian */ 


    


  
}
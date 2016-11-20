<?php 

    function IS_MAINTENANCE()
    {
       $ci =& get_instance() ;       
       $ci->load->model('M_general') ;
      return  $ci->M_general->M_ismaintenance();

    }

    function CEK_NAMA($user_id)
    {
        $ci =& get_instance();
        $sql = "SELECT nama FROM t_users WHERE user_id=? ";
        $query = $ci->db->query($sql,array($user_id));
        $hasil = $query->row_array();
        if ($hasil['nama'] == '')
          $hasil['nama'] = 'Belum Ada Nama';
        return $hasil['nama'] ;
    }


    function basechecklogin() {
    	$ci =& get_instance();
        $ci->load->library('session');
        if($ci->session->userdata('username'))
            return true;
        else return false;
    }

    function baseCheckUserdata()
    {
        $ci =& get_instance() ;
        $ci->load->library('session');
        $datauser['username'] = $ci->session->userdata('username') ;
        $datauser['password'] = $ci->session->userdata('password') ;

        return $datauser ;
    }
    
    function basecheckproadmin() {
    	$ci =& get_instance();
        $ci->load->library('session');
        if($ci->session->userdata('username') == 'proadmin')
            return true;
        else return false;
    }

   function web_prus()
   {
        $ci =& get_instance() ;
        $ci->load->model('M_general') ;
        $id = 1 ;
        $query = $ci->M_general->index($id);
        $hasil = $query['web_prus'] ;
        return $hasil ;
   }

   function nama_prus()
   {
        $ci =& get_instance() ;
        $ci->load->model('M_general') ;
        $id = 1 ;
        $query = $ci->M_general->index($id);
        $hasil = $query['nama_prus'] ;
        return $hasil ;
   }
  
   function alamat_prus()
   {
        $ci =& get_instance() ;
        $ci->load->model('M_general') ;
        $id = 1 ;
        $query = $ci->M_general->index($id);
        $hasil = $query['alamat_prus'] ;
        return $hasil ;
   }

   function email_prus()
   {
        $ci =& get_instance() ;
        $ci->load->model('M_general') ;
        $id = 1 ;
        $query = $ci->M_general->index($id);
        $hasil = $query['email_prus'] ;
        return $hasil ;
   }
   
   function copyright_prus()
   {
        $ci =& get_instance() ;
        $ci->load->model('M_general') ;
        $id = 1 ;
        $query = $ci->M_general->index($id);
        $hasil = $query['copyright_prus'] ;
        return $hasil ;
   }


   function prefix_member()
   {
        $ci =& get_instance() ;
        $ci->load->model('M_general') ;
        $id = 1 ;
        $query = $ci->M_general->index($id);
        $hasil = $query['prefix_member'] ;
        return $hasil ;
   }

   
   function batas_lv()
   {
        $ci =& get_instance() ;
        $ci->load->model('M_general') ;
        $id = 1 ;
        $query = $ci->M_general->index($id);
        return $query['batas_lv'] ;
      
   }


   function cost_pin()
   {
        $ci =& get_instance() ;
        $ci->load->model('M_general') ;
        $id = 1 ;
        $query = $ci->M_general->index($id);
        return $query['cost_pin'] ;
      
   }


   function is_newmember($user_id)
   {
      $newmember = FALSE ;
      $ci =& get_instance() ;
        $ci->load->model('M_membership') ;
        $cek_nama = $ci->M_users-> get_users($user_id);
        $cek_profile = $ci->M_membership->M_CheckUsersProfile($user_id) ;
        $cek_rekening = $ci->M_users->M_editrekening($user_id,'','GET') ;
        if ($cek_nama['nama'] == '')
        $newmember = TRUE ;
        if ($cek_profile['income'] == '')
        $newmember = TRUE ;
        elseif
        ($cek_rekening['bank'] == '')
        $newmember = TRUE ;
        elseif
        ($cek_rekening['no_rek'] == '')
        $newmember = TRUE ;

      return $newmember ;
   }

   function is_no_token($user_id)
   {
      $no_token = FALSE;
       $ci =& get_instance() ;
       $sql = "SELECT `token` FROM t_users_bonus  WHERE user_id=?  " ;
       $query = $ci->db->query($sql,array($user_id));
       $hasil = $query->row_array() ;
       if ($hasil['token'] == '')
       $no_token = TRUE ;

       return $no_token ;   
   }



    function is_active($user_id)
   {
      
      $ci =& get_instance() ;
        $ci->load->model('M_membership') ;
        $cek_active = $ci->M_membership->M_B4_CheckUserAktif($user_id) ;
        return $cek_active ;
       

   }

/* huskyin.com , programcerdas.com 
Ini adalah sebuah 'Security Issue' di MySql dimana , MySql akan memblok IP tertentu, sehingga hanya IP localhost yang mampu mengakses database 
Source: 
- http://stackoverflow.com/questions/16629237/mysql-server-ip-does-not-work-the-same-way-as-localhost
- http://stackoverflow.com/questions/21777200/getting-ip-address-using-mysql-query
- http://dev.mysql.com/doc/refman/5.6/en/connecting.html
- http://stackoverflow.com/questions/26712867/how-to-get-the-ip-address-mysql
- http://stackoverflow.com/questions/17621710/how-do-i-manually-block-and-then-unblock-a-specific-ip-hostname-in-mysql
- http://dba.stackexchange.com/questions/68957/block-user-access-to-certain-tables
- http://dba.stackexchange.com/questions/112128/how-to-know-which-user-update-insert-or-delete-in-mysql-database
- http://stackoverflow.com/questions/17095430/mysql-root-user-always-shows-access-denined-from-any-operations
- https://www.digitalocean.com/community/tutorials/how-to-use-mysql-query-profiling
- http://stackoverflow.com/questions/6296313/mysql-trigger-after-update-only-if-row-has-changed
- http://codespatter.com/2008/05/06/how-to-use-triggers-to-track-changes-in-mysql/

IF THE ELSE TRIGGER
- http://dba.stackexchange.com/questions/48567/how-to-structure-if-condition-in-mysql-trigger
- http://stackoverflow.com/questions/13686025/trigger-update-on-column-change
- http://stackoverflow.com/questions/17329969/mysql-detecting-database-change
- https://www.sitepoint.com/action-automation-with-mysql-triggers/
*/

/* programcerdas.com 
Nantinya di setiap query db ada fungsi gate_open() dan diakhiri gate_close() , tanpa adanya dua fungsi ini, MySql dengan syntax condition IF-THEN-ELSE akan Men-TRIGGER untuk merubah token yg mengakibatkan sistem MATI TOTAL, dengan kata lain, jika ada query 'manual' dari database akan segera terdeteksi karena token berubah.
*/

   function gate_open($user_id)
   {
      
      $this->table = 't_users' ;

       $ci =& get_instance() ;
       $sql = "UPDATE $this->table SET `gate` =? WHERE user_id=?  " ;
       $ci->db->query($sql,array(9,$user_id));

        $gate_open = $ci->db->query($sql,array($user_id));
        
   }


   function gate_close($user_id)
   {
       $this->table = 't_users' ;

       $ci =& get_instance() ;
       $sql = "UPDATE $this->table SET `gate` =? WHERE user_id=?  " ;
       $ci->db->query($sql,array(6,$user_id));

        $gate_close = $ci->db->query($sql,array($user_id));

   }


   function check_token($username)
   {
         $ci =& get_instance() ;
        $ci->load->model('M_general') ;
        $id = 1 ;

         $check_token = $ci->M_general->index($id);
         $check_token = $check_token['token'].$username ;
      //  $check_token = password_hash($check_token['token'],PASSWORD_DEFAULT) ;

         if ($ci->session->userdata('token_login') !=  $check_token ) //token(session).username = token.session(username)
            {
                 report_intruder(date()) ;
                 return FALSE ;
            }
          else return TRUE ;

       
   }

   function match_session_id($user_id)
   {
   		$ci =& get_instance() ;
   		 $session_id = $ci->session->userdata('session_id');

   		 $sql = " SELECT * FROM ci_sessions WHERE session_id = ? " ;
   		 $query = $ci->db->query($sql,array($session_id)) ;
   		 $hasil = $query->row_array() ;

						   		
   }





   function match_ip()
   {
   		$ci =& get_instance();

   
   		 	if($ci->session->userdata('is_login'))
		   		{
		   			if ( ($_SERVER['REMOTE_ADDR'].$ci->session->userdata('user_id') == $ci->session->userdata('match_ip'))) 
		   			return TRUE ;
		   			else
		   			return FALSE ;
		   		}
		   		else 
		   		return FALSE;
   		
   }


   function report_intruder($waktu = null)
   {
      if ($waktu != null )
      $report = 'AWAS!! Pada: '.$waktu.'seseorang mencoba masuk lewat pintu belakang!' ;
      

      return $report ;
   }


 function jumlahtransaksi($user_id)
 {
     $ci =& get_instance() ;
     $ci->load->model('M_users') ;
     $ci->load->model('M_membership') ;

     $ci->db->where('merchant_id',$user_id) ;
     $ci->db->select_sum('nilai_transaksi') ;
     $query =  $ci->db->get('t_valtrade');


     $result = $query->row_array() ;
     return $result['nilai_transaksi'] ;
 }




   function lihatfoto($user_id)
   {
        $ci =& get_instance() ;
        $ci->load->model('M_users') ;
         $ci->load->model('M_membership') ;

         //ditambahkan untuk mencegah ID kecil
         $user_id = strtoupper($user_id);

        $cek_aktif = $ci->M_membership->M_B4_CheckUserAktif($user_id) ;
        if (!$cek_aktif)
          return base_url().'upload/images/users_images/user_pasif.jpg' ;
        else
        {
            $foto_lama = $ci->M_users->M_fotoprofil($user_id, $params='',$order = 'READ') ;
        $foto_lama = strtolower($foto_lama) ;
        if ($foto_lama == '')
          return base_url().'upload/images/users_images/no_image.jpg' ;
        if ( strpos($foto_lama,'.jpg'))
          return  base_url().'upload/images/users_images/foto_'.$user_id.'.jpg?'.time() ;
        if ( strpos($foto_lama,'.gif'))
          return  base_url().'upload/images/users_images/foto_'.$user_id.'.gif?'.time() ;
        if ( strpos($foto_lama,'.png'))
          return base_url().'upload/images/users_images/foto_'.$user_id.'.png?'.time() ;



        }

        

        
   }













?>
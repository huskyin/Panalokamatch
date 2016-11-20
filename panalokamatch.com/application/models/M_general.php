<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class M_general extends CI_Model 
{
var $table = 't_general' ;
	 public function index($id)
	 {
	$sql = "SELECT * FROM $this->table WHERE id=? LIMIT 1 " ; 
	$query = $this->db->query($sql, array($id)) ;


	$hasil = $query->row_array();
				
		return $data = array(
				'id' 				=> $hasil['id'],
        		'web_prus' 			=> $hasil['web_prus'],
        		'nama_prus'  		=> $hasil['nama_prus'],
        		'alamat_prus'   	=> $hasil['alamat_prus'],
        		'email_prus' 		=> $hasil['email_prus'],
        		'copyright_prus' 	=> $hasil['copyright_prus'],
        		'batas_lv'  		=> $hasil['batas_lv'],
        		'token'				=> $hasil['token'],
        		'cost_pin'  		=> $hasil['cost_pin'], 
        		'prefix_member'		=> $hasil['prefix_member']);
	 } 


	 function M_ismaintenance()
	 {
	 	$sql = "SELECT value FROM t_setting WHERE setting=? " ;
      $query = $this->db->query($sql,array('maintenance')) ;
       $hasil = $query->row_array() ;
       return $hasil['value'] ;

	 }


	 function M_setmaintenance($password,$order) 
	 {
	 	if ($order == 'active_order') //sedang maintenance
	 		$sql = "UPDATE  t_setting SET value=0 WHERE setting=? ";
	 	elseif($order == 'passive_order')
	 		$sql = "UPDATE  t_setting SET value=1 WHERE setting=? ";

      $query = $this->db->query($sql,array('maintenance')) ;


	 }






}
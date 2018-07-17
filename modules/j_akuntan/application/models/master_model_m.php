<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_model_m extends CI_Model
{
	function __construct() {
		  parent::__construct();
		  $this->load->database();
	}

	function get_user_info_old($id_user){
		$sql = "
		SELECT a.*, IFNULL(b.NAMA_PERUSAHAAN, '-') AS NAMA_PERUSAHAAN FROM ak_user a 
		LEFT JOIN ak_profil_usaha b ON a.ID_KLIEN = b.ID_KLIEN
		WHERE a.ID = $id_user
		";

		return $this->db->query($sql)->row();
	}

	function get_user_info($id_user){
		$sql = "
		SELECT a.*, b.NAMA AS JABATAN, c.NAMA_DEP , d.NAMA_DIV FROM kepeg_pegawai a 
		LEFT JOIN kepeg_kel_jabatan b ON a.ID_JABATAN = b.ID 
		LEFT JOIN kepeg_departemen c ON a.ID_DEPARTEMEN = c.ID 
		LEFT JOIN kepeg_divisi d ON a.ID_DIVISI = d.ID 
		WHERE a.ID = $id_user
		ORDER BY a.ID ASC
		";

		return $this->db->query($sql)->row();
	}




	function data_usaha($id_klien){
		$sql = "
		SELECT * FROM ak_profil_usaha WHERE ID_KLIEN = $id_klien
		";

		return $this->db->query($sql)->row();
	}



}

?>
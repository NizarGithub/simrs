<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admum_setup_jenis_laborat_m extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function data_jenis_laborat($keyword){ 
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND JENIS_LABORAT LIKE '%$keyword%'";
		}

		$sql = "SELECT * FROM admum_setup_jenis_laborat WHERE $where";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_jenis_laborat_id($id){
		$sql = "SELECT * FROM admum_setup_jenis_laborat WHERE ID = '$id'";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function simpan($jenis_laborat){
		$sql = "INSERT INTO admum_setup_jenis_laborat(JENIS_LABORAT) VALUES ('$jenis_laborat')";
		$this->db->query($sql);
	}

	function ubah($id,$jenis_laborat){
		$sql = "UPDATE admum_setup_jenis_laborat SET JENIS_LABORAT = '$jenis_laborat' WHERE ID = '$id'";
		$this->db->query($sql);
	}

	function hapus($id){
		$sql = "DELETE FROM admum_setup_jenis_laborat WHERE ID = '$id'";
		$this->db->query($sql);
	} 

}
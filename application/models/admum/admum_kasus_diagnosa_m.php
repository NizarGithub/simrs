<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admum_kasus_diagnosa_m extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function data_kasus($keyword){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND NAMA_KASUS LIKE '%$keyword%'";
		}

		$sql = "SELECT * FROM admum_setup_kasus_diagnosa WHERE $where";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_kasus_id($id){
		$sql = "SELECT * FROM admum_setup_kasus_diagnosa WHERE ID = '$id'";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function simpan($kode,$nama_kasus){
		$sql = "INSERT INTO admum_setup_kasus_diagnosa(KODE,NAMA_KASUS) VALUES ('$kode','$nama_kasus')";
		$this->db->query($sql);
	}

	function ubah($id,$nama_kasus){
		$sql = "UPDATE admum_setup_kasus_diagnosa SET NAMA_KASUS = '$nama_kasus' WHERE ID = '$id'";
		$this->db->query($sql);
	}

	function hapus($id){
		$sql = "DELETE FROM admum_setup_kasus_diagnosa WHERE ID = '$id'";
		$this->db->query($sql);
	}

}
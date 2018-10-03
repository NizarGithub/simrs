<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admum_setup_kategori_m extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->load->database(); 
	}

	function get_data_kategori($keyword){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND NAMA_KATEGORI LIKE '%$keyword%'";
		}

		$sql = "
			SELECT * FROM log_kategori WHERE $where ORDER BY ID ASC
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_kategori_id($id){
		$sql = "SELECT * FROM log_kategori WHERE ID = '$id'";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function simpan($nama_kategori){
		$sql = "INSERT INTO log_kategori(NAMA_KATEGORI) VALUES ('$nama_kategori')";
		$this->db->query($sql);
	}

	function ubah($id,$nama_kategori){
		$sql = "UPDATE log_kategori SET NAMA_KATEGORI = '$nama_kategori' WHERE ID = '$id'";
		$this->db->query($sql);
	}

	function hapus($id){
		$sql = "DELETE FROM log_kategori WHERE ID = '$id'";
		$this->db->query($sql);
	}

}
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admum_setup_jenis_obat_m extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->load->database(); 
	}

	function data_jenis($keyword){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND NAMA_JENIS LIKE '%$keyword'";
		}

		$sql = "
			SELECT * FROM obat_jenis WHERE $where ORDER BY ID DESC
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_jenis_id($id){
		$sql = "SELECT * FROM obat_jenis WHERE ID = '$id'";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function simpan($nama_jenis){
		$sql = "INSERT INTO obat_jenis(NAMA_JENIS) VALUES ('$nama_jenis')";
		$this->db->query($sql);
	}

	function ubah($id,$nama_jenis){
		$sql = "UPDATE obat_jenis SET NAMA_JENIS = '$nama_jenis' WHERE ID = '$id'";
		$this->db->query($sql);
	}

	function hapus($id){
		$sql = "DELETE FROM obat_jenis WHERE ID = '$id'";
		$this->db->query($sql);
	}

}
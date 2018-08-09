<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admum_spesialistik_m extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function data_spesialistik($keyword){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND NAMA_SPESIALISTIK LIKE '%$keyword%'";
		}

		$sql = "SELECT * FROM admum_setup_spesialistik WHERE $where";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_spesialistik_id($id){
		$sql = "SELECT * FROM admum_setup_spesialistik WHERE ID = '$id'";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function simpan($kode,$nama_spesialistik){
		$sql = "INSERT INTO admum_setup_spesialistik(KODE,NAMA_SPESIALISTIK) VALUES ('$kode','$nama_spesialistik')";
		$this->db->query($sql);
	}

	function ubah($id,$nama_spesialistik){
		$sql = "UPDATE admum_setup_spesialistik SET NAMA_SPESIALISTIK = '$nama_spesialistik' WHERE ID = '$id'";
		$this->db->query($sql);
	}

	function hapus($id){
		$sql = "DELETE FROM admum_setup_spesialistik WHERE ID = '$id'";
		$this->db->query($sql);
	}

}
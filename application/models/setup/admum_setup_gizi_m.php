<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admum_setup_gizi_m extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function data_gizi($keyword){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND NAMA_GIZI LIKE '%$keyword%'";
		}

		$sql = "SELECT * FROM admum_setup_gizi WHERE $where";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_gizi_id($id){
		$sql = "SELECT * FROM admum_setup_gizi WHERE ID = '$id'";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function simpan($kode,$nama_gizi,$tarif){
		$sql = "INSERT INTO admum_setup_gizi(KODE,NAMA_GIZI,TARIF) VALUES ('$kode','$nama_gizi','$tarif')";
		$this->db->query($sql);
	}

	function ubah($id,$kode,$nama_gizi,$tarif){
		$sql = "UPDATE admum_setup_gizi SET KODE = '$kode', NAMA_GIZI = '$nama_gizi', TARIF = '$tarif' WHERE ID = '$id'";
		$this->db->query($sql);
	}

	function hapus($id){
		$sql = "DELETE FROM admum_setup_gizi WHERE ID = '$id'";
		$this->db->query($sql);
	}

}
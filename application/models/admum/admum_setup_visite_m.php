<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admum_setup_visite_m extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function data_visite($keyword){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND NAMA_VISITE LIKE '%$keyword%'";
		}

		$sql = "SELECT * FROM admum_setup_visite WHERE $where";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_visite_id($id){
		$sql = "SELECT * FROM admum_setup_visite WHERE ID = '$id'";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function simpan($kode,$nama_visite,$tarif){
		$sql = "INSERT INTO admum_setup_visite(KODE,NAMA_VISITE,TARIF) VALUES ('$kode','$nama_visite','$tarif')";
		$this->db->query($sql);
	}

	function ubah($id,$nama_visite,$tarif){
		$sql = "UPDATE admum_setup_visite SET NAMA_VISITE = '$nama_visite', TARIF = '$tarif' WHERE ID = '$id'";
		$this->db->query($sql);
	}

	function hapus($id){
		$sql = "DELETE FROM admum_setup_visite WHERE ID = '$id'";
		$this->db->query($sql);
	}

}
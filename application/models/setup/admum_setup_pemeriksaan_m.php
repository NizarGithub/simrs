<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admum_setup_pemeriksaan_m extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function data_pemeriksaan($keyword){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND NAMA_PEMERIKSAAN LIKE '%$keyword%'";
		}

		$sql = "SELECT * FROM admum_setup_pemeriksaan WHERE $where ORDER BY ID DESC";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_pemeriksaan_id($id){
		$sql = "SELECT * FROM admum_setup_pemeriksaan WHERE ID = '$id'";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function simpan($kode,$nama_pemeriksaan,$tarif){
		$sql = "INSERT INTO admum_setup_pemeriksaan(KODE,NAMA_PEMERIKSAAN,TARIF) VALUES ('$kode','$nama_pemeriksaan','$tarif')";
		$this->db->query($sql);
	}

	function ubah($id,$nama_pemeriksaan,$tarif){
		$sql = "UPDATE admum_setup_pemeriksaan SET NAMA_PEMERIKSAAN = '$nama_pemeriksaan', TARIF = '$tarif' WHERE ID = '$id'";
		$this->db->query($sql);
	}

	function hapus($id){
		$sql = "DELETE FROM admum_setup_pemeriksaan WHERE ID = '$id'";
		$this->db->query($sql);
	}

}
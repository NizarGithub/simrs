<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admum_jasa_perawat_m extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function data_jasa($keyword){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND PERAWATAN LIKE '%$keyword%'";
		}

		$sql = "SELECT * FROM admum_jasa_perawat WHERE $where";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_jasa_id($id){
		$sql = "SELECT * FROM admum_jasa_perawat WHERE ID = '$id'";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function simpan($kode,$perawatan,$tarif){
		$d = date('d-m-Y');
		$m = date('n');
		$y = date('Y');

		$sql = "INSERT INTO admum_jasa_perawat(KODE,PERAWATAN,TARIF,TANGGAL,BULAN,TAHUN) VALUES ('$kode','$perawatan','$tarif','$d','$m','$y')";
		$this->db->query($sql);
	}

	function ubah($id,$perawatan,$tarif){
		$sql = "UPDATE admum_jasa_perawat SET PERAWATAN = '$perawatan', TARIF = '$tarif' WHERE ID = '$id'";
		$this->db->query($sql);
	}

	function hapus($id){
		$sql = "DELETE FROM admum_jasa_perawat WHERE ID = '$id'";
		$this->db->query($sql);
	}

}
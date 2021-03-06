<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admum_setup_satuan_obat_m extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->load->database(); 
	}

	function data_kode_satuan($keyword){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND (KODE_SATUAN LIKE '%$keyword%' OR NAMA_SATUAN LIKE '%$keyword%')";
		}

		$sql = "
			SELECT * FROM obat_satuan WHERE $where ORDER BY ID DESC
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_kode_satuan_id($id){
		$sql = "SELECT * FROM obat_satuan WHERE ID = '$id'";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function simpan($kode_satuan,$nama_satuan){
		$sql = "INSERT INTO obat_satuan(KODE_SATUAN,NAMA_SATUAN,AKTIF) VALUES ('$kode_satuan','$nama_satuan',1)";
		$this->db->query($sql);
	}

	function ubah($id,$kode_satuan,$nama_satuan){
		$sql = "UPDATE obat_satuan SET KODE_SATUAN = '$kode_satuan', NAMA_SATUAN = '$nama_satuan' WHERE ID = '$id'";
		$this->db->query($sql);
	}

	function hapus($id){
		$sql = "DELETE FROM obat_satuan WHERE ID = '$id'";
		$this->db->query($sql);
	}

}
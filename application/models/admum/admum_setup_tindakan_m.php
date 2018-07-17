<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admum_setup_tindakan_m extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function data_tindakan($keyword){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND (NAMA_TINDAKAN LIKE '%$keyword%' OR KODE LIKE '%$keyword%')";
		}else{
			$where = $where;
		}

		$sql = "SELECT * FROM admum_setup_tindakan WHERE $where ORDER BY ID DESC";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_tindakan_id($id){
		$sql = "SELECT * FROM admum_setup_tindakan WHERE ID = '$id'";
		$query = $this->db->query($sql);
		return $query->row();
	} 

	function simpan($kode,$nama_tindakan,$tarif){
		$sql = "INSERT INTO admum_setup_tindakan(KODE,NAMA_TINDAKAN,TARIF) VALUES('$kode','$nama_tindakan','$tarif')";
		$this->db->query($sql);
	}

	function ubah($id,$nama_tindakan,$tarif){
		$sql = "UPDATE admum_setup_tindakan SET NAMA_TINDAKAN = '$nama_tindakan', TARIF = '$tarif' WHERE ID = '$id'";
		$this->db->query($sql);
	}

	function hapus($id){
		$sql = "DELETE FROM admum_setup_tindakan WHERE ID = '$id'";
		$this->db->query($sql);
	}

}
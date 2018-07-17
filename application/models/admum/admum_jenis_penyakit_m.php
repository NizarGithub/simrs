<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admum_jenis_penyakit_m extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function data_jenis_penyakit($keyword){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND (URAIAN LIKE '%$keyword%' OR KODE LIKE '%$keyword')";
		}else{
			$where = $where;
		}

		$sql = "SELECT * FROM admum_jenis_penyakit WHERE $where ORDER BY ID DESC";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_jenis_penyakit_id($id){
		$sql = "SELECT * FROM admum_jenis_penyakit WHERE ID = '$id'";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function simpan($kode,$uraian,$tanggal,$bulan,$tahun){
		$sql = "
			INSERT INTO admum_jenis_penyakit(
				KODE,
				URAIAN,
				TANGGAL,
				BULAN,
				TAHUN
			) VALUES(
				'$kode',
				'$uraian',
				'$tanggal',
				'$bulan',
				'$tahun'
			)
		";
		$this->db->query($sql);
	}

	function ubah($id,$uraian){
		$sql = "UPDATE admum_jenis_penyakit SET URAIAN = '$uraian' WHERE ID = '$id'";
		$this->db->query($sql);
	}

	function hapus($id){
		$sql = "DELETE FROM admum_jenis_penyakit WHERE ID = '$id'";
		$this->db->query($sql);
	}

}
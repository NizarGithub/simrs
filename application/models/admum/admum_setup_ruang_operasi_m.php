<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admum_setup_ruang_operasi_m extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function data_ruangan($keyword){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND NAMA_RUANG LIKE '%$keyword'";
		}

		$sql = "SELECT * FROM admum_setup_ruang_operasi WHERE $where";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_ruangan_id($id){
		$sql = "SELECT * FROM admum_setup_ruang_operasi WHERE ID = '$id'";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function simpan($kode,$nama_ruang,$keterangan,$tanggal,$bulan,$tahun,$status_pakai){
		$sql = "
			INSERT INTO admum_setup_ruang_operasi(
				KODE,
				NAMA_RUANG,
				KETERANGAN,
				TANGGAL,
				BULAN,
				TAHUN,
				STATUS_PAKAI
			) VALUES (
				'$kode',
				'$nama_ruang',
				'$keterangan',
				'$tanggal',
				'$bulan',
				'$tahun',
				'$status_pakai'
			)
		";
		$this->db->query($sql);
	}

	function ubah($id,$nama_ruang,$keterangan){
		$sql = "
			UPDATE admum_setup_ruang_operasi SET
				NAMA_RUANG = '$nama_ruang',
				KETERANGAN = '$keterangan'
			WHERE ID = '$id'
		";
		$this->db->query($sql);
	}

	function hapus($id){
		$sql = "DELETE FROM admum_setup_ruang_operasi WHERE ID = '$id'";
		$this->db->query($sql);
	}

}
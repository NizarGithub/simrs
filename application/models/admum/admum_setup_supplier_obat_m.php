<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admum_setup_supplier_obat_m extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function data_supplier($keyword){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND (KODE_SUPPLIER LIKE '%$keyword%' OR NAMA_SUPPLIER LIKE '%$keyword%')";
		}

		$sql = "
			SELECT * FROM obat_supplier WHERE $where ORDER BY ID DESC
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_rujukan($keyword){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND (KODE_SUPPLIER LIKE '%$keyword%' OR NAMA_SUPPLIER LIKE '%$keyword%')";
		}

		$sql = "
			SELECT * FROM master_rujukan WHERE $where ORDER BY ID DESC
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_supplier_id($id){
		$sql = "SELECT * FROM obat_supplier WHERE ID = '$id'";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function data_rujukan_id($id){
		$sql = "SELECT * FROM master_rujukan WHERE ID = '$id'";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function simpan($kode_supplier,$nama_supplier,$merk,$alamat,$email,$telepon,$tanggal_daftar,$bulan,$tahun){
		$sql = "
			INSERT INTO obat_supplier(
				KODE_SUPPLIER,
				NAMA_SUPPLIER,
				MERK,
				ALAMAT,
				EMAIL,
				TELEPON,
				TANGGAL_DAFTAR,
				BULAN,
				TAHUN
			) VALUES (
				'$kode_supplier',
				'$nama_supplier',
				'$merk',
				'$alamat',
				'$email',
				'$telepon',
				'$tanggal_daftar',
				'$bulan',
				'$tahun'
			)";
		$this->db->query($sql);
	}

	function ubah($id,$nama_supplier,$merk,$alamat,$email,$telepon){
		$sql = "
			UPDATE obat_supplier SET 
				NAMA_SUPPLIER = '$nama_supplier',
				MERK = '$merk',
				ALAMAT = '$alamat',
				EMAIL = '$email',
				TELEPON = '$telepon'
			WHERE ID = '$id'
		";
		$this->db->query($sql);
	}

	function hapus($id){
		$sql = "DELETE FROM obat_supplier WHERE ID = '$id'";
		$this->db->query($sql);
	} 

}
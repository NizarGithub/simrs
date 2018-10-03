<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admum_setup_supplier_barang_m extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->load->database(); 
	}

	function data_supplier($keyword){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND (KODE_SUPPLIER LIKE '%$keyword%' OR NAMA_SUPPLIER LIKE '%$keyword%' OR ALAMAT LIKE '%$keyword%')";
		}

		$sql = "SELECT * FROM admum_supplier_barang WHERE $where ORDER BY ID DESC";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_supplier_id($id){
		$sql = "SELECT * FROM admum_supplier_barang WHERE ID = '$id'";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function simpan($kode_supplier,$nama_supplier,$alamat,$email,$telepon,$tanggal_daftar,$bulan,$tahun){
		$sql = "
			INSERT INTO admum_supplier_barang(
				KODE_SUPPLIER,
				NAMA_SUPPLIER,
				ALAMAT,
				EMAIL,
				TELEPON,
				TANGGAL_DAFTAR,
				BULAN,
				TAHUN
			) VALUES (
				'$kode_supplier',
				'$nama_supplier',
				'$alamat',
				'$email',
				'$telepon',
				'$tanggal_daftar',
				'$bulan',
				'$tahun'
			)";
		$this->db->query($sql);
	}

	function ubah($id,$nama_supplier,$merk,$alamat,$email,$telepon,$jenis_barang){
		$sql = "
			UPDATE admum_supplier_barang SET 
				NAMA_SUPPLIER = '$nama_supplier',
				MERK = '$merk',
				ALAMAT = '$alamat',
				EMAIL = '$email',
				TELEPON = '$telepon',
				JENIS_BARANG = '$jenis_barang'
			WHERE ID = '$id'
		";
		$this->db->query($sql);
	}

	function hapus($id){
		$sql = "DELETE FROM admum_supplier_barang WHERE ID = '$id'";
		$this->db->query($sql);
	}

}
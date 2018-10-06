<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Permintaan_po_m extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->load->database(); 
	}

	function data_permintaan_barang($bulan,$tahun){
		$sql = "SELECT * FROM log_permintaan_barang WHERE BULAN = '$bulan' AND TAHUN = '$tahun' ORDER BY ID DESC";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function detail_barang_permintaan($id_permintaan){
		$sql = "
			SELECT
				a.*,
				b.KODE_ALAT,
				b.NAMA_ALAT,
				b.NAMA_KATEGORI,
				c.ID_DEPARTEMEN,
				d.NAMA_DEP,
				c.ID_DIVISI,
				e.NAMA_DIV
			FROM log_permintaan_barang_detail a
			JOIN (
				SELECT
					a.ID,
					a.ID_BARANG,
					b.KODE_ALAT,
					b.NAMA_ALAT,
					c.NAMA_KATEGORI
				FROM log_gudang_barang a
				JOIN admum_setup_peralatan_medis b ON b.ID = a.ID_BARANG
				JOIN log_kategori c ON c.ID = b.ID_KATEGORI
			) b ON b.ID = a.ID_BARANG_GUDANG
			JOIN log_permintaan_barang c ON a.ID_PERMINTAAN_BARANG = c.ID
			LEFT JOIN kepeg_departemen d ON d.ID = c.ID_DEPARTEMEN
			LEFT JOIN kepeg_divisi e ON e.ID = c.ID_DIVISI
			WHERE a.ID_PERMINTAAN_BARANG = '$id_permintaan'
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_permintaan_barang_id($id){
		$sql = "SELECT * FROM log_permintaan_barang WHERE ID = '$id'";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function diproses($id,$tanggal,$waktu){
		$sql = "
			UPDATE log_permintaan_barang SET 
				STATUS_PROSES = '1', 
				TANGGAL_PROSES = '$tanggal', 
				WAKTU_PROSES = '$waktu'
			WHERE ID = '$id'
		";
		$this->db->query($sql);
	}

	function update_stok_barang($id_barang_gudang,$jumlah){
		$sql = "UPDATE log_gudang_barang SET TOTAL = TOTAL - $jumlah WHERE ID = '$id_barang_gudang'";
		$this->db->query($sql);
	}

	function dibatalkan($id,$tanggal,$waktu,$id_pegawai){
		$sql = "
			UPDATE log_permintaan_barang SET 
				STATUS_BATAL = '1', 
				TANGGAL_BATAL = '$tanggal', 
				WAKTU_BATAL = '$waktu',
				ID_PEGAWAI_BATAL = '$id_pegawai'
			WHERE ID = '$id'
		";
		$this->db->query($sql);
	}

}
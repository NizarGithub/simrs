<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pembelian_aqua_m extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->load->database(); 
	}

	function data_pembelian($bulan,$tahun){
		$sql = "
			SELECT
				a.ID,
				a.KODE_PEMBELIAN,
				a.TANGGAL,
				a.BULAN,
				a.TAHUN,
				a.ID_BARANG_GUDANG,
				a.HARGA,
				a.JUMLAH,
				a.TOTAL,
				a.JENIS,
				b.ID_BARANG,
				c.NAMA_ALAT
			FROM log_pembelian_barang a
			JOIN log_gudang_barang b ON b.ID = a.ID_BARANG_GUDANG
			JOIN admum_setup_peralatan_medis c ON b.ID_BARANG = c.ID
			WHERE a.BULAN = '$bulan'
			AND a.TAHUN = '$tahun'
			AND a.JENIS = 'Aqua'
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_pembelian_id($id){
		$sql = "
			SELECT
				a.ID,
				a.KODE_PEMBELIAN,
				a.TANGGAL,
				a.BULAN,
				a.TAHUN,
				a.ID_BARANG_GUDANG,
				a.HARGA,
				a.JUMLAH,
				a.TOTAL,
				a.JENIS,
				b.ID_BARANG,
				c.KODE_ALAT,
				c.NAMA_ALAT
			FROM log_pembelian_barang a
			JOIN log_gudang_barang b ON b.ID = a.ID_BARANG_GUDANG
			JOIN admum_setup_peralatan_medis c ON b.ID_BARANG = c.ID
			WHERE a.ID = '$id'
		";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function data_barang($keyword){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND (b.NAMA_ALAT LIKE '%$keyword%' OR b.NAMA_KATEGORI LIKE '%$keyword%')";
		}

		$sql = "
			SELECT
				a.ID,
				a.ID_BARANG,
				b.KODE_ALAT,
				b.NAMA_ALAT,
				b.NAMA_KATEGORI,
				a.HARGA_BELI
			FROM log_gudang_barang a
			LEFT JOIN (
				SELECT
					a.ID,
					a.KODE_ALAT,
					a.NAMA_ALAT,
					b.NAMA_KATEGORI
				FROM admum_setup_peralatan_medis a
				JOIN log_kategori b ON b.ID = a.ID_KATEGORI
			) b ON b.ID = a.ID_BARANG
			WHERE $where
			AND (b.NAMA_ALAT LIKE '%Aqua%' OR b.NAMA_ALAT LIKE '%aqua%')
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_barang_id($id){
		$sql = "
			SELECT
				a.ID,
				a.ID_BARANG,
				b.KODE_ALAT,
				b.NAMA_ALAT,
				b.NAMA_KATEGORI,
				a.HARGA_BELI
			FROM log_gudang_barang a
			LEFT JOIN (
				SELECT
					a.ID,
					a.KODE_ALAT,
					a.NAMA_ALAT,
					b.NAMA_KATEGORI
				FROM admum_setup_peralatan_medis a
				JOIN log_kategori b ON b.ID = a.ID_KATEGORI
			) b ON b.ID = a.ID_BARANG
			WHERE a.ID = '$id'
		";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function simpan($kode,$tanggal,$bulan,$tahun,$id_barang_gudang,$harga,$jumlah,$total){
		$sql = "
			INSERT INTO log_pembelian_barang(
				KODE_PEMBELIAN,
				TANGGAL,
				BULAN,
				TAHUN,
				ID_BARANG_GUDANG,
				HARGA,
				JUMLAH,
				TOTAL,
				JENIS
			) VALUES (
				'$kode',
				'$tanggal',
				'$bulan',
				'$tahun',
				'$id_barang_gudang',
				'$harga',
				'$jumlah',
				'$total',
				'Aqua'
			)
		";
		$this->db->query($sql);
	}

	function ubah($id,$tanggal,$jumlah,$total){
		$sql = "
			UPDATE log_pembelian_barang SET
				TANGGAL = '$tanggal',
				JUMLAH = '$jumlah',
				TOTAL = '$total'
			WHERE ID = '$id'
		";
		$this->db->query($sql);
	}

	function hapus($id){
		$sql = "DELETE FROM log_pembelian_barang WHERE ID = '$id'";
		$this->db->query($sql);
	}

}
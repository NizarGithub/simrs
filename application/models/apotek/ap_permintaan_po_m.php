<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ap_permintaan_po_m extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function data_permintaan_barang($id_departemen,$bulan,$tahun){
		$sql = "SELECT * FROM log_permintaan_barang WHERE ID_DEPARTEMEN = '$id_departemen' AND BULAN = '$bulan' AND TAHUN = '$tahun' ORDER BY ID DESC";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function detail_barang_permintaan($id_permintaan){
		$sql = "
			SELECT
				a.*,
				b.KODE_ALAT,
				b.NAMA_ALAT,
				b.NAMA_KATEGORI
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

	function data_peralatan($keyword){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND b.NAMA_ALAT LIKE '%$keyword%' OR b.NAMA_KATEGORI LIKE '%$keyword%'";
		}

		$sql = "
			SELECT
				a.ID,
				a.ID_BARANG,
				b.KODE_ALAT,
				b.NAMA_ALAT,
				b.NAMA_KATEGORI,
				a.TOTAL,
				a.HARGA_BELI,
				a.KETERANGAN
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
			ORDER BY a.ID ASC
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_peralatan_id($id){
		$sql = "
			SELECT
				a.ID,
				a.ID_BARANG,
				b.KODE_ALAT,
				b.NAMA_ALAT,
				b.NAMA_KATEGORI,
				a.MERK,
				a.GOLONGAN,
				a.JUMLAH,
				a.ISI,
				a.TOTAL,
				a.HARGA_BELI,
				a.ID_DEPARTEMEN,
				a.ID_DIVISI,
				a.ID_SATUAN,
				a.GAMBAR,
				a.KETERANGAN,
				c.NAMA_DEP,
				d.NAMA_DIV,
				e.NAMA_SATUAN
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
			LEFT JOIN kepeg_departemen c ON c.ID = a.ID_DEPARTEMEN
			LEFT JOIN kepeg_divisi d ON d.ID = a.ID_DIVISI
			LEFT JOIN admum_satuan_barang e ON e.ID = a.ID_SATUAN
			WHERE a.ID = '$id'
		";
		$qry = $this->db->query($sql);
		return $qry->result();
	}

	function simpan($kode_po,$id_dep,$id_div,$id_pegawai,$tanggal,$bulan,$tahun,$waktu,$total_barang){
		$sql = "
			INSERT INTO log_permintaan_barang(
				KODE_PO,
				ID_DEPARTEMEN,
				ID_DIVISI,
				ID_PEGAWAI,
				TANGGAL,
				BULAN,
				TAHUN,
				WAKTU,
				TOTAL_BARANG
			) VALUES (
				'$kode_po',
				'$id_dep',
				'$id_div',
				'$id_pegawai',
				'$tanggal',
				'$bulan',
				'$tahun',
				'$waktu',
				'$total_barang'
			)
		";
		$this->db->query($sql);
	}

	function simpan_det($id_permintaan,$id_barang,$jumlah,$tanggal,$bulan,$tahun,$waktu){
		$sql = "
			INSERT INTO log_permintaan_barang_detail(
				ID_PERMINTAAN_BARANG,
				ID_BARANG_GUDANG,
				JUMLAH_PERMINTAAN,
				TANGGAL,
				BULAN,
				TAHUN,
				WAKTU
			) VALUES (
				'$id_permintaan',
				'$id_barang',
				'$jumlah',
				'$tanggal',
				'$bulan',
				'$tahun',
				'$waktu'
			)
		";
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
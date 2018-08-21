<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Log_laporan_barang_m extends CI_Model {

	function __construct(){
		parent::__construct();
		$this->load->database();
	}
	function data_peralatan(){
    $tahun = date('Y');
		$sql = "
			SELECT
				MEDIS.ID,
				ALAT.KODE_ALAT,
				ALAT.BARCODE,
				ALAT.NAMA_ALAT,
				ALAT.JENIS_ALAT,
				SAT.NAMA_SATUAN,
				MEDIS.PEMAKAIAN,
				MEDIS.JUMLAH,
				MEDIS.ISI,
				MEDIS.TOTAL,
				MEDIS.SATUAN_ISI,
				MEDIS.HARGA_BELI,
				MEDIS.TANGGAL_MASUK,
				MEDIS.WAKTU_MASUK,
				MEDIS.AKTIF,
				MEDIS.FIRST_OUT,
				MEDIS.URUT_BARANG,
				MEDIS.GAMBAR
			FROM log_peralatan_medis MEDIS
			LEFT JOIN admum_setup_peralatan_medis ALAT ON ALAT.ID = MEDIS.ID_SETUP_NAMA_ALAT
			LEFT JOIN obat_satuan SAT ON SAT.ID = MEDIS.ID_SATUAN_ALAT
			WHERE TAHUN = '$tahun'
		";
		$query = $this->db->query($sql);
		return $query->result();
	}
  function range_tanggal($tanggal_sekarang, $tanggal_sampai){
    $sql = "SELECT
      				MEDIS.ID,
      				ALAT.KODE_ALAT,
      				ALAT.BARCODE,
      				ALAT.NAMA_ALAT,
      				ALAT.JENIS_ALAT,
      				SAT.NAMA_SATUAN,
      				MEDIS.PEMAKAIAN,
      				MEDIS.JUMLAH,
      				MEDIS.ISI,
      				MEDIS.TOTAL,
      				MEDIS.SATUAN_ISI,
      				MEDIS.HARGA_BELI,
      				MEDIS.TANGGAL_MASUK,
      				MEDIS.WAKTU_MASUK,
      				MEDIS.AKTIF,
      				MEDIS.FIRST_OUT,
      				MEDIS.URUT_BARANG,
      				MEDIS.GAMBAR
      			FROM log_peralatan_medis MEDIS
      			LEFT JOIN admum_setup_peralatan_medis ALAT ON ALAT.ID = MEDIS.ID_SETUP_NAMA_ALAT
      			LEFT JOIN obat_satuan SAT ON SAT.ID = MEDIS.ID_SATUAN_ALAT
      			WHERE STR_TO_DATE(MEDIS.TANGGAL_MASUK,'%d-%m-%Y') >= STR_TO_DATE('$tanggal_sekarang','%d-%m-%Y')
            AND STR_TO_DATE(MEDIS.TANGGAL_MASUK,'%d-%m-%Y') <= STR_TO_DATE('$tanggal_sampai','%d-%m-%Y')
      		";
		$query = $this->db->query($sql);
		return $query->result();
  }
  function range_bulan($bulan){
    $sql = "SELECT
      				MEDIS.ID,
      				ALAT.KODE_ALAT,
      				ALAT.BARCODE,
      				ALAT.NAMA_ALAT,
      				ALAT.JENIS_ALAT,
      				SAT.NAMA_SATUAN,
      				MEDIS.PEMAKAIAN,
      				MEDIS.JUMLAH,
      				MEDIS.ISI,
      				MEDIS.TOTAL,
      				MEDIS.SATUAN_ISI,
      				MEDIS.HARGA_BELI,
      				MEDIS.TANGGAL_MASUK,
      				MEDIS.WAKTU_MASUK,
      				MEDIS.AKTIF,
      				MEDIS.FIRST_OUT,
      				MEDIS.URUT_BARANG,
      				MEDIS.GAMBAR
      			FROM log_peralatan_medis MEDIS
      			LEFT JOIN admum_setup_peralatan_medis ALAT ON ALAT.ID = MEDIS.ID_SETUP_NAMA_ALAT
      			LEFT JOIN obat_satuan SAT ON SAT.ID = MEDIS.ID_SATUAN_ALAT
      			WHERE MEDIS.BULAN = '$bulan'
      		";
		$query = $this->db->query($sql);
		return $query->result();
  }
  function data_departemen(){
		$sql = "SELECT * FROM kepeg_departemen ORDER BY ID DESC";
		$query = $this->db->query($sql);
		return $query->result();
	}
  function klik_departemen($id_departemen){
		$sql = "SELECT * FROM kepeg_departemen WHERE ID = '$id_departemen'";
		$query = $this->db->query($sql);
		return $query->row();
	}
  function data_divisi($id_departemen){
		$sql = "SELECT * FROM kepeg_divisi WHERE ID_DEPARTEMEN = '$id_departemen' ORDER BY ID DESC";
		$query = $this->db->query($sql);
		return $query->result();
	}
	function klik_divisi($id_divisi){
		$sql = "SELECT * FROM kepeg_divisi WHERE ID = '$id_divisi'";
		$query = $this->db->query($sql);
		return $query->row();
	}
  function search_divisi($id_divisi){
    $sql = "SELECT
      				MEDIS.ID,
      				ALAT.KODE_ALAT,
      				ALAT.BARCODE,
      				ALAT.NAMA_ALAT,
      				ALAT.JENIS_ALAT,
      				SAT.NAMA_SATUAN,
      				MEDIS.PEMAKAIAN,
      				MEDIS.JUMLAH,
      				MEDIS.ISI,
      				MEDIS.TOTAL,
      				MEDIS.SATUAN_ISI,
      				MEDIS.HARGA_BELI,
      				MEDIS.TANGGAL_MASUK,
      				MEDIS.WAKTU_MASUK,
      				MEDIS.AKTIF,
      				MEDIS.FIRST_OUT,
      				MEDIS.URUT_BARANG,
      				MEDIS.GAMBAR
      			FROM log_peralatan_medis MEDIS
      			LEFT JOIN admum_setup_peralatan_medis ALAT ON ALAT.ID = MEDIS.ID_SETUP_NAMA_ALAT
      			LEFT JOIN obat_satuan SAT ON SAT.ID = MEDIS.ID_SATUAN_ALAT
      			WHERE MEDIS.ID_DIVISI = '$id_divisi'
      		";
		$query = $this->db->query($sql);
		return $query->result();
  }
	function semua_pdf(
		$by,
    $tanggal_sekarang,
    $tanggal_sampai,
    $select_bulan,
    $id_departemen,
    $id_divisi
	){
		$where = '1=1';
		if ($by == 'Semua') {
			$tahun = date('Y');
			$where = $where." AND MEDIS.TAHUN = '$tahun' ";
		}elseif ($by == 'Tanggal') {
			$where = $where."  AND STR_TO_DATE(MEDIS.TANGGAL_MASUK,'%d-%m-%Y') >= STR_TO_DATE('$tanggal_sekarang','%d-%m-%Y')
											 AND STR_TO_DATE(MEDIS.TANGGAL_MASUK,'%d-%m-%Y') <= STR_TO_DATE('$tanggal_sampai','%d-%m-%Y') ";
		}elseif ($by == 'Bulan') {
			$where = $where." AND MEDIS.BULAN = '$select_bulan' ";
		}elseif ($by == 'Divisi') {
			$where = $where." AND MEDIS.ID_DIVISI = '$id_divisi' ";
		}
		$sql = "SELECT
							MEDIS.ID,
							ALAT.KODE_ALAT,
							ALAT.BARCODE,
							ALAT.NAMA_ALAT,
							ALAT.JENIS_ALAT,
							SAT.NAMA_SATUAN,
							MEDIS.PEMAKAIAN,
							MEDIS.JUMLAH,
							MEDIS.ISI,
							MEDIS.TOTAL,
							MEDIS.SATUAN_ISI,
							MEDIS.HARGA_BELI,
							MEDIS.TANGGAL_MASUK,
							MEDIS.WAKTU_MASUK,
							MEDIS.AKTIF,
							MEDIS.FIRST_OUT,
							MEDIS.URUT_BARANG,
							MEDIS.GAMBAR
						FROM log_peralatan_medis MEDIS
						LEFT JOIN admum_setup_peralatan_medis ALAT ON ALAT.ID = MEDIS.ID_SETUP_NAMA_ALAT
						LEFT JOIN obat_satuan SAT ON SAT.ID = MEDIS.ID_SATUAN_ALAT
						WHERE $where
					";
		$query = $this->db->query($sql);
		return $query->result();
	}
	function semua_pdf_row(
		$by,
    $tanggal_sekarang,
    $tanggal_sampai,
    $select_bulan,
    $id_departemen,
    $id_divisi
	){
		$where = '1=1';
		if ($by == 'Semua') {
			$tahun = date('Y');
			$where = $where." AND MEDIS.TAHUN = '$tahun' ";
		}elseif ($by == 'Tanggal') {
			$where = $where."  AND STR_TO_DATE(MEDIS.TANGGAL_MASUK,'%d-%m-%Y') >= STR_TO_DATE('$tanggal_sekarang','%d-%m-%Y')
											 AND STR_TO_DATE(MEDIS.TANGGAL_MASUK,'%d-%m-%Y') <= STR_TO_DATE('$tanggal_sampai','%d-%m-%Y') ";
		}elseif ($by == 'Bulan') {
			$where = $where." AND MEDIS.BULAN = '$select_bulan' ";
		}elseif ($by == 'Divisi') {
			$where = $where." AND MEDIS.ID_DIVISI = '$id_divisi' ";
		}
		$sql = "SELECT
							MEDIS.ID,
							ALAT.KODE_ALAT,
							ALAT.BARCODE,
							ALAT.NAMA_ALAT,
							ALAT.JENIS_ALAT,
							SAT.NAMA_SATUAN,
							MEDIS.PEMAKAIAN,
							MEDIS.JUMLAH,
							MEDIS.ISI,
							MEDIS.TOTAL,
							MEDIS.SATUAN_ISI,
							MEDIS.HARGA_BELI,
							MEDIS.TANGGAL_MASUK,
							MEDIS.WAKTU_MASUK,
							MEDIS.AKTIF,
							MEDIS.FIRST_OUT,
							MEDIS.URUT_BARANG,
							MEDIS.GAMBAR
						FROM log_peralatan_medis MEDIS
						LEFT JOIN admum_setup_peralatan_medis ALAT ON ALAT.ID = MEDIS.ID_SETUP_NAMA_ALAT
						LEFT JOIN obat_satuan SAT ON SAT.ID = MEDIS.ID_SATUAN_ALAT
						WHERE $where
					";
		$query = $this->db->query($sql);
		return $query->row_array();
	}
	function semua_excel(
		$by,
    $tanggal_sekarang,
    $tanggal_sampai,
    $select_bulan,
    $id_departemen,
    $id_divisi
	){
		$where = '1=1';
		if ($by == 'Semua') {
			$tahun = date('Y');
			$where = $where." AND MEDIS.TAHUN = '$tahun' ";
		}elseif ($by == 'Tanggal') {
			$where = $where."  AND STR_TO_DATE(MEDIS.TANGGAL_MASUK,'%d-%m-%Y') >= STR_TO_DATE('$tanggal_sekarang','%d-%m-%Y')
											 AND STR_TO_DATE(MEDIS.TANGGAL_MASUK,'%d-%m-%Y') <= STR_TO_DATE('$tanggal_sampai','%d-%m-%Y') ";
		}elseif ($by == 'Bulan') {
			$where = $where." AND MEDIS.BULAN = '$select_bulan' ";
		}elseif ($by == 'Divisi') {
			$where = $where." AND MEDIS.ID_DIVISI = '$id_divisi' ";
		}
		$sql = "SELECT
							MEDIS.ID,
							ALAT.KODE_ALAT,
							ALAT.BARCODE,
							ALAT.NAMA_ALAT,
							ALAT.JENIS_ALAT,
							SAT.NAMA_SATUAN,
							MEDIS.PEMAKAIAN,
							MEDIS.JUMLAH,
							MEDIS.ISI,
							MEDIS.TOTAL,
							MEDIS.SATUAN_ISI,
							MEDIS.HARGA_BELI,
							MEDIS.TANGGAL_MASUK,
							MEDIS.WAKTU_MASUK,
							MEDIS.AKTIF,
							MEDIS.FIRST_OUT,
							MEDIS.URUT_BARANG,
							MEDIS.GAMBAR
						FROM log_peralatan_medis MEDIS
						LEFT JOIN admum_setup_peralatan_medis ALAT ON ALAT.ID = MEDIS.ID_SETUP_NAMA_ALAT
						LEFT JOIN obat_satuan SAT ON SAT.ID = MEDIS.ID_SATUAN_ALAT
						WHERE $where
					";
		$query = $this->db->query($sql);
		return $query->result();
	}
	function semua_excel_row(
		$by,
    $tanggal_sekarang,
    $tanggal_sampai,
    $select_bulan,
    $id_departemen,
    $id_divisi
	){
		$where = '1=1';
		if ($by == 'Semua') {
			$tahun = date('Y');
			$where = $where." AND MEDIS.TAHUN = '$tahun' ";
		}elseif ($by == 'Tanggal') {
			$where = $where."  AND STR_TO_DATE(MEDIS.TANGGAL_MASUK,'%d-%m-%Y') >= STR_TO_DATE('$tanggal_sekarang','%d-%m-%Y')
											 AND STR_TO_DATE(MEDIS.TANGGAL_MASUK,'%d-%m-%Y') <= STR_TO_DATE('$tanggal_sampai','%d-%m-%Y') ";
		}elseif ($by == 'Bulan') {
			$where = $where." AND MEDIS.BULAN = '$select_bulan' ";
		}elseif ($by == 'Divisi') {
			$where = $where." AND MEDIS.ID_DIVISI = '$id_divisi' ";
		}
		$sql = "SELECT
							MEDIS.ID,
							ALAT.KODE_ALAT,
							ALAT.BARCODE,
							ALAT.NAMA_ALAT,
							ALAT.JENIS_ALAT,
							SAT.NAMA_SATUAN,
							MEDIS.PEMAKAIAN,
							MEDIS.JUMLAH,
							MEDIS.ISI,
							MEDIS.TOTAL,
							MEDIS.SATUAN_ISI,
							MEDIS.HARGA_BELI,
							MEDIS.TANGGAL_MASUK,
							MEDIS.WAKTU_MASUK,
							MEDIS.AKTIF,
							MEDIS.FIRST_OUT,
							MEDIS.URUT_BARANG,
							MEDIS.GAMBAR
						FROM log_peralatan_medis MEDIS
						LEFT JOIN admum_setup_peralatan_medis ALAT ON ALAT.ID = MEDIS.ID_SETUP_NAMA_ALAT
						LEFT JOIN obat_satuan SAT ON SAT.ID = MEDIS.ID_SATUAN_ALAT
						WHERE $where
					";
		$query = $this->db->query($sql);
		return $query->row_array();
	}
}

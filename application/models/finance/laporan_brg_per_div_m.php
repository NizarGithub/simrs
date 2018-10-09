<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Laporan_brg_per_div_m extends CI_Model {

	function __construct(){
		parent::__construct();
		$this->load->database();
	}

	function data_peralatan($id_departemen,$id_divisi,$by,$tanggal_sekarang,$tanggal_sampai,$bulan,$tahun){
    	$where = "1 = 1";

    	if($by == 'Semua'){
    		$where = "1 = 1";
    	}else if($by == 'Tanggal'){
    		$where = $where."
    			AND STR_TO_DATE(a.TANGGAL,'%d-%m-%Y') >= STR_TO_DATE('$tanggal_sekarang','%d-%m-%Y')
    			AND STR_TO_DATE(a.TANGGAL,'%d-%m-%Y') <= STR_TO_DATE('$tanggal_sampai','%d-%m-%Y')
    		";
    	}else if($by == 'Bulan'){
    		$where = $where." AND a.BULAN = '$bulan' AND a.TAHUN = '$tahun'";
    	}

		$sql = "
			SELECT
				a.ID,
				a.ID_BARANG_GUDANG,
				a.JUMLAH_PERMINTAAN AS TOTAL,
				a.TANGGAL,
				a.BULAN,
				a.TAHUN,
				b.ID_DEPARTEMEN,
				c.NAMA_DEP,
				b.ID_DIVISI,
				d.NAMA_DIV,
				b.STATUS_PROSES,
				e.KODE_ALAT,
				e.NAMA_ALAT,
				e.NAMA_KATEGORI,
				e.HARGA_BELI,
				e.KETERANGAN
			FROM log_permintaan_barang_detail a
			JOIN log_permintaan_barang b ON a.ID_PERMINTAAN_BARANG = b.ID
			LEFT JOIN kepeg_departemen c ON b.ID_DEPARTEMEN = c.ID
			LEFT JOIN kepeg_divisi d ON b.ID_DIVISI = d.ID
			LEFT JOIN (
				SELECT
					MEDIS.ID,
					ALAT.KODE_ALAT,
					ALAT.NAMA_ALAT,
					ALAT.NAMA_KATEGORI,
					MEDIS.HARGA_BELI,
					MEDIS.KETERANGAN
				FROM log_gudang_barang MEDIS
				LEFT JOIN (
					SELECT
						a.ID,
						a.KODE_ALAT,
						a.NAMA_ALAT,
						b.NAMA_KATEGORI
					FROM admum_setup_peralatan_medis a
					LEFT JOIN log_kategori b ON a.ID_KATEGORI = b.ID
				) ALAT ON ALAT.ID = MEDIS.ID_BARANG
			) e ON e.ID = a.ID_BARANG_GUDANG
			WHERE $where 
			AND b.STATUS_PROSES = '1'
			AND b.ID_DEPARTEMEN = '$id_departemen'
			AND b.ID_DIVISI = '$id_divisi'
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

  	function data_departemen($keyword){
  		$where = "1 = 1";

  		if($keyword != ""){
  			$where = $where." AND NAMA_DEP LIKE '%$keyword%'";
  		}

		$sql = "SELECT * FROM kepeg_departemen WHERE $where ORDER BY ID DESC";
		$query = $this->db->query($sql);
		return $query->result();
	}

  	function klik_departemen($id_departemen){
		$sql = "SELECT * FROM kepeg_departemen WHERE ID = '$id_departemen'";
		$query = $this->db->query($sql);
		return $query->row();
	}

  	function data_divisi($id_departemen,$keyword){
  		$where = "1 = 1";

  		if($keyword != ""){
  			$where = $where." AND NAMA_DIV LIKE '%$keyword%'";
  		}

		$sql = "SELECT * FROM kepeg_divisi WHERE $where AND ID_DEPARTEMEN = '$id_departemen' ORDER BY ID DESC";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function klik_divisi($id_divisi){
		$sql = "SELECT * FROM kepeg_divisi WHERE ID = '$id_divisi'";
		$query = $this->db->query($sql);
		return $query->row();
	}
	
}

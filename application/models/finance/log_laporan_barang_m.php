<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Log_laporan_barang_m extends CI_Model {

	function __construct(){
		parent::__construct();
		$this->load->database();
	}

	function data_peralatan($by,$tanggal_sekarang,$tanggal_sampai,$bulan,$tahun){
    	$where = "1 = 1";

    	if($by == 'Semua'){
    		$where = "1 = 1";
    	}else if($by == 'Tanggal'){
    		$where = $where."
    			AND STR_TO_DATE(MEDIS.TANGGAL_MASUK,'%d-%m-%Y') >= STR_TO_DATE('$tanggal_sekarang','%d-%m-%Y')
    			AND STR_TO_DATE(MEDIS.TANGGAL_MASUK,'%d-%m-%Y') <= STR_TO_DATE('$tanggal_sampai','%d-%m-%Y')
    		";
    	}else if($by == 'Bulan'){
    		$where = $where." AND MEDIS.BULAN = '$bulan' AND MEDIS.TAHUN = '$tahun'";
    	}

		$sql = "
			SELECT
				MEDIS.ID,
				ALAT.KODE_ALAT,
				ALAT.NAMA_ALAT,
				ALAT.NAMA_KATEGORI,
				MEDIS.JUMLAH,
				MEDIS.ISI,
				MEDIS.TOTAL,
				MEDIS.HARGA_BELI,
				MEDIS.TANGGAL_MASUK,
				MEDIS.WAKTU_MASUK,
				MEDIS.GAMBAR,
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
			WHERE $where
			ORDER BY MEDIS.ID ASC
		";
		$query = $this->db->query($sql);
		return $query->result();
	}
  
}

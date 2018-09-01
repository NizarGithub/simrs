<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Laporan_tagihan_m extends CI_Model { 

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function dt_setup_gaji(){
		$sql = " 
		SELECT * FROM abs_setup_gaji
		ORDER BY ID ASC
		";

		return $this->db->query($sql)->result(); 
	}

	function getAllPembayaran($bulan, $tahun,$type){ 
		$sql = "
		SELECT a.* FROM (
		SELECT a.ID, a.STS_BAYAR, a.NAMA, a.STATUS, a.KODE_PASIEN, a.ALAMAT, a.TANGGAL_DAFTAR, b.ASAL_RUJUKAN, b.SISTEM_BAYAR FROM rk_pasien a 
		JOIN admum_rawat_jalan b ON a.ID = b.ID_PASIEN 
		WHERE a.STATUS = 'RJ' AND (b.STATUS_PINDAH IS NULL OR b.STATUS_PINDAH = '')

		UNION ALL

		SELECT a.ID, a.STS_BAYAR, a.NAMA, a.STATUS, a.KODE_PASIEN, a.ALAMAT, a.TANGGAL_DAFTAR, b.ASAL_RUJUKAN, b.SISTEM_BAYAR FROM rk_pasien a 
		JOIN admum_rawat_inap b ON a.ID = b.ID_PASIEN 
		WHERE a.STATUS = 'RI'

		UNION ALL 

		SELECT a.ID, a.STS_BAYAR, a.NAMA, a.STATUS, a.KODE_PASIEN, a.ALAMAT, a.TANGGAL_DAFTAR, b.ASAL_RUJUKAN, b.SISTEM_BAYAR FROM rk_pasien a 
		JOIN admum_igd b ON a.ID = b.ID_PASIEN 
		WHERE a.STATUS = 'IGD'
		) a 
		WHERE a.STATUS = '$type' AND a.TANGGAL_DAFTAR LIKE '%-$bulan-$tahun%'
		ORDER BY a.ID
		";
		
		return $this->db->query($sql)->result();
	}




}
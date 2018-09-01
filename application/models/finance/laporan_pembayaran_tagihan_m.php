<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Laporan_pembayaran_tagihan_m extends CI_Model { 

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

	function getAllPembayaran($bulan, $tahun){ 
		$sql = "
		SELECT a.*, b.NAMA, b.KODE_PASIEN FROM bill_pembayaran_pasien a 
		JOIN rk_pasien b ON a.ID_PASIEN = b.ID 
		WHERE a.TANGGAL LIKE '%-$bulan-$tahun%'
		";

		return $this->db->query($sql)->result();
	}




}
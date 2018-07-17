<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lap_gaji_pegawai_m extends CI_Model { 

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

	function get_all_pegawai($bulan, $tahun){
		$sql = "
		SELECT a.*, b.NAMA AS JABATAN, c.NAMA_DEP, d.NAMA_DIV, IFNULL(e.GAPOK,0) AS GAPOK FROM kepeg_pegawai a
		LEFT JOIN kepeg_kel_jabatan b ON a.ID_JABATAN = b.ID
		LEFT JOIN kepeg_departemen  c ON a.ID_DEPARTEMEN = c.ID
		LEFT JOIN kepeg_divisi      d ON a.ID_DIVISI = d.ID
		LEFT JOIN (
			SELECT ID_PEGAWAI, IFNULL(NILAI,0) AS GAPOK FROM abs_gaji_pegawai
			WHERE BULAN = $bulan AND TAHUN = $tahun AND NAMA_GAJI = 'GAPOK'
		) e ON a.ID = e.ID_PEGAWAI
		ORDER BY ID
		";

		return $this->db->query($sql)->result();
	}

	function getGajiDetail($id_gaji, $id_pegawai, $bulan, $tahun){

		$sql = "
		SELECT IFNULL(a.NILAI,0) AS NILAI FROM (
			SELECT IFNULL(NILAI,0) AS NILAI FROM abs_gaji_pegawai
			WHERE ID_PEGAWAI = $id_pegawai AND BULAN = $bulan AND TAHUN = $tahun AND ID_GAJI = $id_gaji
		) a
		";

		return $this->db->query($sql)->row();
	}

	function getGajiDetailTHR($id_pegawai, $bulan, $tahun){
		$sql = "
		SELECT IFNULL(a.NILAI,0) AS NILAI FROM (
			SELECT IFNULL(a.NILAI,0) AS NILAI FROM (
				SELECT IFNULL(NILAI,0) AS NILAI FROM abs_gaji_pegawai
				WHERE ID_PEGAWAI = $id_pegawai AND BULAN = $bulan AND TAHUN = $tahun AND NAMA_GAJI = 'THR' 
			) a
		) a
		";

		return $this->db->query($sql)->row();
	}

	function cek_thr($bln, $thn){
		$sql = "
		SELECT * FROM kepeg_setup_thr
		WHERE TAHUN = $thn AND BULAN = $bln
		";

		return $this->db->query($sql)->result();
	}



}
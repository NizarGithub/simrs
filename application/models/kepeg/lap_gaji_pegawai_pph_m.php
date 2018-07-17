<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lap_gaji_pegawai_pph_m extends CI_Model { 

	function __construct()
	{
		parent::__construct(); 
		$this->load->database();
	}

	function dt_PKPRange(){
		$sql = " 
		SELECT * FROM kepeg_range_pkp 
		ORDER BY ID ASC 
		";

		return $this->db->query($sql)->result();
	}

	function get_all_pegawai($tahun){
		$sql = "
		SELECT a.*, b.GAPOK, b.THR, c.KODE_GOLONGAN, c.PTKP FROM kepeg_pegawai a
		LEFT JOIN kepeg_pangkat b ON a.ID_PANGKAT = b.ID
		LEFT JOIN kepeg_gol_pajak c ON a.ID_GOL_PAJAK = c.ID
		ORDER BY a.ID
		";

		return $this->db->query($sql)->result();
	}

	function getGajiDetail($id_pegawai, $bulan, $tahun){

		$sql = "
		SELECT IFNULL(a.NILAI,0) AS NILAI FROM (
			SELECT IFNULL(NILAI,0) AS NILAI FROM abs_gaji_pegawai
			WHERE ID_PEGAWAI = $id_pegawai AND BULAN = $bulan AND TAHUN = $tahun AND NAMA_GAJI != 'GAPOK' 
			ORDER BY ID_GAJI ASC
		) a
		";

		return $this->db->query($sql)->result();
	}



}
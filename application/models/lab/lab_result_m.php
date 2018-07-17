<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lab_result_m extends CI_Model {

	function __construct()
	{
		parent::__construct(); 
		$this->load->database();
	}

	function getRequestLab(){ 
		$sql = "
		SELECT a.*, b.TGL, b.NOMOR_PERIKSA, c.NAMA FROM lab_pemeriksaan_detail a 
		LEFT JOIN lab_pemeriksaan b ON a.ID_PEMERIKSAAN = b.ID
		LEFT JOIN rk_pasien c ON a.KODE_PASIEN = c.KODE_PASIEN
		WHERE a.STS = 1
		"; 

		return $this->db->query($sql)->result();
	}

	function hapus_pemeriksaan($id_hapus){
		$sql = "
		DELETE FROM lab_pemeriksaan_detail WHERE ID = $id_hapus
		";

		$this->db->query($sql);
	}

}

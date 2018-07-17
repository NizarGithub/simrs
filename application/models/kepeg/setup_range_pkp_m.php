<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setup_range_pkp_m extends CI_Model {

	function __construct()
	{
		parent::__construct(); 
		$this->load->database();
	}

	function getRangePKP(){
		$sql = " 
		SELECT * FROM kepeg_range_pkp 
		ORDER BY ID ASC
		";

		return $this->db->query($sql)->result();
	}

	function deleteAllPKP(){
		$sql = "
		DELETE FROM kepeg_range_pkp
		";

		$this->db->query($sql);
	}

	function simpanPKP($prosen, $nilai_awal, $nilai_akhir){

		$prosen      = str_replace(',', '', $prosen);
		$nilai_awal  = str_replace(',', '', $nilai_awal);
		$nilai_akhir = str_replace(',', '', $nilai_akhir);

		$sql = "
		INSERT INTO kepeg_range_pkp
		(PROSEN, NILAI_AWAL, NILAI_AKHIR)
		VALUES
		($prosen, $nilai_awal, $nilai_akhir)
		";

		$this->db->query($sql);
	}

}
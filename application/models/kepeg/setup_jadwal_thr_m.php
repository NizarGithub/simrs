<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setup_jadwal_thr_m extends CI_Model {

	function __construct()
	{
		parent::__construct(); 
		$this->load->database();
	}

	function get_setup_thr($thn){
		$sql = " 
		SELECT * FROM kepeg_setup_thr 
		WHERE TAHUN = $thn
		";

		return $this->db->query($sql)->row();
	}

	function simpanTHR($thn, $tgl_thr){

		$piece = explode('-', $tgl_thr);
		$bln   = $piece[1];

		$sql_1 = "
		DELETE FROM kepeg_setup_thr
		WHERE TAHUN = $thn
		";

		$this->db->query($sql_1);

		$sql_2 = "
		INSERT INTO kepeg_setup_thr
		(TAHUN, BULAN, TANGGAL)
		VALUES 
		($thn, $bln, '$tgl_thr')
		";

		$this->db->query($sql_2);
	}

	function get_setup_thrAll(){
		$sql = " 
		SELECT * FROM kepeg_setup_thr 
		ORDER BY TAHUN DESC
		";

		return $this->db->query($sql)->result();
	}

}
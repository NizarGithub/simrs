<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admum_kode_antrian_m extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function cek_kode_antrian($val){
		$sql = "
		SELECT * FROM kepeg_setup_antrian
		WHERE KODE = '$val'
		";

		return $this->db->query($sql)->result();
	}

	function simpan_antrian($kode, $untuk, $antrian_max){
		$sql = "
		INSERT INTO kepeg_setup_antrian
		(KODE, UNTUK, ANTRIAN_MAX)
		VALUES 
		('$kode', '$untuk', $antrian_max)
		";

		$this->db->query($sql);
	}

	function data_antrian($keyword){
		$where = "1=1";
		if($keyword != ""){
			$where = $where." AND (KODE = '$keyword' OR UNTUK = '$keyword' OR ANTRIAN_MAX = '$keyword')";
		}

		$sql = "
		SELECT * FROM kepeg_setup_antrian
		WHERE $where 
		ORDER BY ID ASC
		";

		return $this->db->query($sql)->result();
	}

	function hapusAntrian($id_hapus){
		$sql = "
		DELETE FROM kepeg_setup_antrian WHERE ID = $id_hapus
		";		
		$this->db->query($sql);
	}
}
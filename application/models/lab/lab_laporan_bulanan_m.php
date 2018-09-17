<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lab_laporan_bulanan_m extends CI_Model {

	function __construct()
	{
		parent::__construct(); 
		$this->load->database();
	}

	function load_laborat($keyword){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND JENIS_LABORAT LIKE '%$keyword%'";
		}else{
			$where = $where;
		}

		$sql = "SELECT * FROM admum_setup_jenis_laborat WHERE $where ORDER BY ID ASC";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function klik_laborat($id){
		$sql = "SELECT * FROM admum_setup_jenis_laborat WHERE ID = '$id'";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function get_jenis_lab_bulanan($bulan,$tahun,$filter,$id_lab){
		$where = "1 = 1";

		if($filter == 'Semua'){
			$where = $where;
		}else{
			$where = $where." AND a.ID = '$id_lab'";
		}

		$sql = "
			SELECT
				a.ID,
				a.JENIS_LABORAT,
				COUNT(b.ID_PASIEN) AS JUMLAH_PASIEN,
				b.BULAN,
				b.TAHUN
			FROM admum_setup_jenis_laborat a
			LEFT JOIN rk_laborat_rj b ON b.JENIS_LABORAT = a.ID
			WHERE $where 
			AND b.BULAN = '$bulan'
			AND b.TAHUN = '$tahun'
			GROUP BY a.ID
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

}
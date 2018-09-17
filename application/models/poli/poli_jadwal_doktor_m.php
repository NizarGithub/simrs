<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Poli_jadwal_doktor_m extends CI_Model {

	function __construct()
	{
		parent::__construct(); 
		$this->load->database();
	}

	function get_dokter($keyword){
		$where = "1 = 1";

		if($keyword != "" || $keyword != null){
			$where = $where." AND NAMA LIKE '%$keyword%'";
		}

		$sql = "
			SELECT * FROM kepeg_pegawai WHERE $where AND STATUS LIKE '%DOKTER%'
			ORDER BY ID ASC
		";

		$dt = $this->db->query($sql);
		return $dt->result();
	}

	function get_jadwal_dokter($id_dokter,$hari){
		$sql = "
			SELECT a.*, b.NAMA AS POLI FROM kepeg_jadwal_dokter a
			JOIN admum_poli b ON a.ID_POLI = b.ID
			WHERE a.ID_DOKTER = $id_doktor AND a.HARI = '$hari'
			ORDER BY a.ID ASC
		";

		$dt = $this->db->query($sql);
		return $dt->result();
	}

	function get_poli(){
		$sql = "
		SELECT * FROM admum_poli
		ORDER BY ID ASC
		";

		return $this->db->query($sql)->result();
	}

	function simpan_jadwal($id_pegawai, $id_poli, $hari, $waktu_awal, $waktu_akhir){
		$sql = "
		INSERT INTO kepeg_jadwal_dokter
		(ID_DOKTER, ID_POLI, HARI, WAKTU_AWAL, WAKTU_AKHIR)
		VALUES 
		($id_pegawai, $id_poli, '$hari', '$waktu_awal', '$waktu_akhir')
		";

		 $this->db->query($sql);
	}

	function hapus_jadwal_all($id_pegawai){
		$sql = "DELETE FROM kepeg_jadwal_dokter WHERE ID_DOKTER = $id_pegawai";
		$this->db->query($sql);
	}

	function getListDoktor(){
		$sql = "
		SELECT * FROM kepeg_pegawai WHERE STATUS LIKE '%DOKTER%'
		ORDER BY ID ASC
		";

		return $this->db->query($sql)->result();
	}

	function getJadwalDokterbyHari($id_dokter, $hari){
		$sql = "
		SELECT a.*, b.NAMA AS POLI FROM kepeg_jadwal_dokter a
		JOIN admum_poli b ON a.ID_POLI = b.ID
		WHERE a.ID_DOKTER = $id_dokter AND a.HARI = '$hari'
		ORDER BY a.ID ASC
		";

		return $this->db->query($sql)->result();
	}

}
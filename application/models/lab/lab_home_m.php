<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lab_home_m extends CI_Model {

	function __construct()
	{
		parent::__construct(); 
		$this->load->database();
	}

	function data_pasien($keyword,$posisi,$now){
		$where = "1 = 1";
		$order = "";

		if($keyword != ""){
			$where = $where." AND (
				PSN.KODE_PASIEN LIKE '%$keyword%' 
				OR PSN.NAMA LIKE '%$keyword%' 
				OR PSN.UMUR LIKE '%$keyword%'
				OR PSN.ALAMAT LIKE '%$keyword%'
			)";
		}else{
			$where = $where;
		}

		$sql = "
			SELECT 
				PSN.*,
				b.ID AS ID_RJ,
				b.ID_POLI
			FROM rk_pasien PSN
			LEFT JOIN admum_rawat_jalan b ON b.ID_PASIEN = PSN.ID
			WHERE $where
			AND PSN.STS_POSISI = '$posisi'
			AND PSN.TANGGAL_DAFTAR = '$now'
			AND PSN.STS_TERIMA = '1'
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function terima_pasien($id){
		$sql = "UPDATE rk_pasien SET STS_TERIMA = '2' WHERE ID = '$id'";
		$this->db->query($sql);
	}

	function data_pasien_terima($keyword,$posisi,$now,$id_divisi,$level){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND (
				PSN.KODE_PASIEN LIKE '%$keyword%' 
				OR PSN.NAMA LIKE '%$keyword%' 
				OR PSN.UMUR LIKE '%$keyword%'
				OR PSN.ALAMAT LIKE '%$keyword%'
			)";
		}else{
			$where = $where;
		}

		if($level == null || $level ==""){
			$where = $where;
		}else{
			$where = $where."
				AND PSN.STS_POSISI = '$posisi'
				AND PSN.TANGGAL_DAFTAR = '$now'
			";
		}

		$sql = "
			SELECT 
				PSN.*,
				b.ID AS ID_RJ,
				b.ID_POLI
			FROM rk_pasien PSN
			LEFT JOIN admum_rawat_jalan b ON b.ID_PASIEN = PSN.ID
			WHERE $where
			AND PSN.STS_TERIMA = '2'
			ORDER BY PSN.ID DESC
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_pasien_id($id){
		$sql = "
			SELECT 
				PSN.*,
				b.ID AS ID_RJ,
				b.ID_POLI
			FROM rk_pasien PSN
			LEFT JOIN admum_rawat_jalan b ON b.ID_PASIEN = PSN.ID
			WHERE PSN.ID = '$id'
		";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function data_rawat_jalan_id($id){
		$sql = "
			SELECT
				PASIEN.ID,
				PASIEN.KODE_PASIEN,
				PASIEN.NAMA AS NAMA_PASIEN,
				PASIEN.JENIS_KELAMIN,
				PASIEN.UMUR,
				PASIEN.STATUS,
				PASIEN.PEKERJAAN,
				PASIEN.ALAMAT,
				PASIEN.KECAMATAN,
				PASIEN.KELURAHAN,
				PASIEN.KOTA,
				RJ.ID AS ID_RJ,
				RJ.ASAL_RUJUKAN,
				RJ.ID_POLI AS ID_POLI,
				POLI.NAMA AS NAMA_POLI,
				PEG.ID AS ID_DOKTER,
				PEG.NAMA AS NAMA_DOKTER,
				RJ.HARI,
				RJ.TANGGAL,
				RJ.SISTEM_BAYAR
			FROM admum_rawat_jalan RJ
			LEFT JOIN rk_pasien PASIEN ON RJ.ID_PASIEN = PASIEN.ID
			LEFT JOIN admum_poli POLI ON RJ.ID_POLI = POLI.ID
			LEFT JOIN kepeg_pegawai PEG ON PEG.ID = POLI.ID_PEG_DOKTER
			WHERE RJ.ID = '$id'
		";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function load_laborat($keyword){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND JENIS_LABORAT LIKE '%$keyword%'";
		}else{
			$where = $where;
		}

		$sql = "SELECT * FROM admum_setup_jenis_laborat WHERE $where ORDER BY ID DESC";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function klik_laborat($id){
		$sql = "SELECT * FROM admum_setup_jenis_laborat WHERE ID = '$id'";
		$query = $this->db->query($sql);
		return $query->row();
	}

}

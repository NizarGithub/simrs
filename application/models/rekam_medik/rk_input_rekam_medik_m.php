<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rk_input_rekam_medik_m extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function get_notif_pasien_baru($now){
		$sql = "
			SELECT
				b.*
			FROM(
				SELECT
					b.ID,
					b.TANGGAL,
					b.WAKTU,
					b.ID_POLI,
					c.ID_DIVISI,
					c.NAMA AS NAMA_POLI,
					b.STS_APPROVE_RM,
					PSN.KODE_PASIEN,
					PSN.NAMA,
					PSN.JENIS_KELAMIN,
					'1' AS STS,
					'RJ' AS TIPE,
					b.STS_LIHAT
				FROM admum_rawat_jalan b
				JOIN rk_pasien PSN ON b.ID_PASIEN = PSN.ID
				JOIN admum_poli c ON c.ID = b.ID_POLI

				UNION ALL

				SELECT
					b.ID,
					b.TANGGAL_MASUK AS TANGGAL,
					b.WAKTU,
					'' AS ID_POLI,
					'' AS ID_DIVISI,
					'' AS NAMA_POLI,
					'' AS STS_APPROVE_RM,
					PSN.KODE_PASIEN,
					PSN.NAMA,
					PSN.JENIS_KELAMIN,
					'2' AS STS,
					'RI' AS TIPE,
					b.STS_LIHAT
				FROM admum_rawat_inap b
				JOIN rk_pasien PSN ON b.ID_PASIEN = PSN.ID
			) b
			WHERE $where
			AND b.TANGGAL = '$now'
			AND b.STS_LIHAT = '0'
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function get_data_rm($keyword,$now){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND (b.KODE_PASIEN LIKE '%$keyword%' OR b.NAMA LIKE '%$keyword%')";
		}else{
			$where = $where;
		}

		$sql = "
			SELECT
				b.*
			FROM(
				SELECT
					b.ID,
					b.TANGGAL,
					b.WAKTU,
					b.ID_POLI,
					c.ID_DIVISI,
					c.NAMA AS NAMA_POLI,
					b.STS_APPROVE_RM,
					PSN.KODE_PASIEN,
					PSN.NAMA,
					PSN.JENIS_KELAMIN,
					'1' AS STS,
					'RJ' AS TIPE,
					b.STS_LIHAT
				FROM admum_rawat_jalan b
				JOIN rk_pasien PSN ON b.ID_PASIEN = PSN.ID
				JOIN admum_poli c ON c.ID = b.ID_POLI

				UNION ALL

				SELECT
					b.ID,
					b.TANGGAL_MASUK AS TANGGAL,
					b.WAKTU,
					'' AS ID_POLI,
					'' AS ID_DIVISI,
					'' AS NAMA_POLI,
					'' AS STS_APPROVE_RM,
					PSN.KODE_PASIEN,
					PSN.NAMA,
					PSN.JENIS_KELAMIN,
					'2' AS STS,
					'RI' AS TIPE,
					b.STS_LIHAT
				FROM admum_rawat_inap b
				JOIN rk_pasien PSN ON b.ID_PASIEN = PSN.ID
			) b
			WHERE $where
			AND b.TANGGAL = '$now'
			AND b.STS_LIHAT = '1'
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function load_data_pasien($keyword){ 
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND (NAMA LIKE '%$keyword%' OR NIK LIKE '%$keyword%' OR KODE_PASIEN LIKE '%$keyword%')";
		}else{
			$where = $where;
		}

		$sql = "SELECT * FROM rk_pasien WHERE $where";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function klik_pasien($id){
		$sql = "SELECT * FROM rk_pasien WHERE ID = '$id'";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function load_data_poli($keyword){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND (POLI.NAMA LIKE '%$keyword%' OR NIK LIKE '%$keyword%' OR PEG.NAMA LIKE '%$keyword%')";
		}else{
			$where = $where;
		}

		$sql = "
			SELECT
				POLI.ID,
				POLI.NAMA AS NAMA_POLI,
				POLI.INITIAL_POLI,
				PEG.ID AS ID_DOKTER,
				PEG.NAMA AS NAMA_DOKTER
			FROM admum_poli POLI
			LEFT JOIN kepeg_pegawai PEG ON PEG.ID = POLI.ID_PEG_DOKTER
			WHERE $where 
			AND POLI.AKTIF = '1'
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function klik_poli($id){
		$sql = "
			SELECT
				POLI.ID,
				POLI.NAMA AS NAMA_POLI,
				POLI.INITIAL_POLI,
				PEG.ID AS ID_DOKTER,
				PEG.NAMA AS NAMA_DOKTER
			FROM admum_poli POLI
			LEFT JOIN kepeg_pegawai PEG ON PEG.ID = POLI.ID_PEG_DOKTER
			WHERE POLI.ID = '$id'
		";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function simpan($no_rekam_medik,$id_pasien,$sakit,$id_jenis_penyakit,$tingkatan,$tanggal,$status_operasi,$nama_wali){
		$sql = "
			INSERT INTO rk_input(
				NO_REKAM_MEDIK,
				ID_PASIEN,
				SAKIT,
				ID_JENIS_PENYAKIT,
				TINGKATAN,
				TANGGAL,
				STATUS_OPERASI,
				NAMA_WALI
			) VALUES (
				'$no_rekam_medik',
				'$id_pasien',
				'$sakit',
				'$id_jenis_penyakit',
				'$tingkatan',
				'$tanggal',
				'$status_operasi',
				'$nama_wali'
			)
		";
		$this->db->query($sql);
	}

	function data_jenis_penyakit($keyword){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND (URAIAN LIKE '%$keyword%' OR KODE LIKE '%$keyword%')";
		}

		$sql = "SELECT * FROM admum_jenis_penyakit WHERE $where ORDER BY ID DESC";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_jenis_penyakit_id($id){
		$sql = "SELECT * FROM admum_jenis_penyakit WHERE ID = '$id'";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function get_data_rekam_medik(){
		$sql = "
			SELECT
				RK.ID,
				RK.NO_REKAM_MEDIK,
				PASIEN.NAMA AS NAMA_PASIEN,
				RK.SAKIT,
				JP.URAIAN AS JENIS_PENYAKIT,
				RK.TINGKATAN,
				RK.TANGGAL,
				RK.STATUS_OPERASI,
				RK.NAMA_WALI
			FROM rk_input RK
			LEFT JOIN rk_pasien PASIEN ON PASIEN.ID = RK.ID_PASIEN
			LEFT JOIN admum_jenis_penyakit JP ON JP.ID = RK.ID_JENIS_PENYAKIT
			WHERE RK.STATUS = '0'
			ORDER BY RK.ID DESC
			LIMIT 1
		";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function ubah_sts_approve_rj($id){
		$sql = "UPDATE admum_rawat_jalan SET STS_APPROVE_RM = '1' WHERE ID = '$id'";
		$this->db->query($sql);
	}

	function ubah_sts_approve_ri($id){
		$sql = "UPDATE admum_rawat_inap SET STS_APPROVE_RM = '1' WHERE ID = '$id'";
		$this->db->query($sql);
	}

}
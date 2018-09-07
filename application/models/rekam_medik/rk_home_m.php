<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rk_home_m extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function notif_pasien_baru_rj($now){
		$sql = "
			SELECT
				COUNT(*) AS TOTAL
			FROM(
				SELECT 
					b.ID,
					b.STS_APPROVE_RM,
					b.STS_LIHAT,
					b.TANGGAL
				FROM admum_rawat_jalan b

				UNION ALL

				SELECT 
					b.ID,
					b.STS_APPROVE_RM,
					b.STS_LIHAT,
					b.TANGGAL_MASUK AS TANGGAL
				FROM admum_rawat_inap b
			) b
			WHERE b.STS_APPROVE_RM = '0'
			AND b.STS_LIHAT = '0'
			AND b.TANGGAL = '$now'
		";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function data_pasien_baru_rj($now){
		$sql = "
			SELECT
				*
			FROM(
				SELECT
					b.ID,
					b.TANGGAL,
					b.WAKTU,
					b.ID_POLI,
					PSN.KODE_PASIEN,
					PSN.NAMA,
					PSN.JENIS_KELAMIN,
					PSN.UMUR,
					c.NAMA AS NAMA_POLI,
					b.STS_APPROVE_RM,
					b.STS_LIHAT,
					'RJ' AS TIPE
				FROM admum_rawat_jalan b
				JOIN rk_pasien PSN ON b.ID_PASIEN = PSN.ID
				JOIN admum_poli c ON c.ID = b.ID_POLI

				UNION ALL

				SELECT
					a.ID,
					a.TANGGAL_MASUK AS TANGGAL,
					a.WAKTU,
					'' AS ID_POLI,
					PSN.KODE_PASIEN,
					PSN.NAMA,
					PSN.JENIS_KELAMIN,
					PSN.UMUR,
					'' AS NAMA_POLI,
					a.STS_APPROVE_RM,
					a.STS_LIHAT,
					'RI' AS TIPE
				FROM admum_rawat_inap a
				JOIN rk_pasien PSN ON a.ID_PASIEN = PSN.ID
			) b
			WHERE b.STS_APPROVE_RM = '0'
			AND b.STS_LIHAT = '0'
			AND b.TANGGAL = '$now'
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function get_data_rm($keyword,$now){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND (b.NAMA_POLI LIKE '%$keyword%' OR b.KODE_PASIEN LIKE '%$keyword%' OR b.NAMA LIKE '%$keyword%')";
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
					c.NAMA AS NAMA_POLI,
					b.STS_APPROVE_RM,
					b.STS_LIHAT,
					PSN.KODE_PASIEN,
					PSN.NAMA,
					PSN.JENIS_KELAMIN,
					'1' AS TIPE
				FROM admum_rawat_jalan b
				JOIN rk_pasien PSN ON b.ID_PASIEN = PSN.ID
				JOIN admum_poli c ON c.ID = b.ID_POLI

				UNION ALL

				SELECT
					a.ID,
					a.TANGGAL_MASUK AS TANGGAL,
					a.WAKTU,
					'-' AS NAMA_POLI,
					a.STS_APPROVE_RM,
					a.STS_LIHAT,
					PSN.KODE_PASIEN,
					PSN.NAMA,
					PSN.JENIS_KELAMIN,
					'2' AS TIPE
				FROM admum_rawat_inap a
				JOIN rk_pasien PSN ON a.ID_PASIEN = PSN.ID
			) b
			WHERE $where
			AND b.STS_LIHAT = '1'
			AND b.TANGGAL = '$now'
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function ubah_sts_approve($id){
		$sql = "UPDATE admum_rawat_jalan SET STS_APPROVE_RM = '1' WHERE ID = '$id'";
		$this->db->query($sql);
	}

	function ubah_sts_approve_ri($id){
		$sql = "UPDATE admum_rawat_inap SET STS_APPROVE_RM = '1' WHERE ID = '$id'";
		$this->db->query($sql);
	}

}
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rk_pengembalian_rj_m extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function data_pasien_kembali($keyword){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND (PASIEN.NAMA LIKE '%$keyword%' OR PASIEN.KODE_PASIEN LIKE '%$keyword%')";
		}else{
			$where = $where;
		}

		$sql = "
			SELECT
				PASIEN.ID,
				PASIEN.KODE_PASIEN,
				PASIEN.NAMA AS NAMA_PASIEN,
				PASIEN.JENIS_KELAMIN,
				RJ.ID AS ID_RJ,
				RJ.ASAL_RUJUKAN,
				POLI.NAMA AS NAMA_POLI,
				RJ.TANGGAL,
				RJ.SISTEM_BAYAR,
				RJ.STATUS_PINDAH,
				KARJ.KONDISI_AKHIR
			FROM admum_rawat_jalan RJ
			LEFT JOIN rk_pasien PASIEN ON RJ.ID_PASIEN = PASIEN.ID
			LEFT JOIN admum_poli POLI ON RJ.ID_POLI = POLI.ID
			LEFT JOIN rk_kondisi_akhir_rj KARJ ON KARJ.ID_PELAYANAN = RJ.ID
			WHERE $where
			AND RJ.STATUS_SUDAH = '1'
			ORDER BY RJ.ID DESC
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

}
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rk_pengembalian_ri_m extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function data_pasien_kembali($keyword){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND (PASIEN.NAMA LIKE '%$keyword%')";
		}else{
			$where = $where;
		}

		$sql = "
			SELECT
				RI.ID,
				RI.ID_PASIEN,
				PASIEN.KODE_PASIEN,
				PASIEN.NAMA AS NAMA_PASIEN,
				RI.TANGGAL_MASUK,
				STR_TO_DATE(RI.TANGGAL_MASUK,'%d-%m-%Y') AS TANGGAL_MASUK_BALIK,
				RI.ASAL_RUJUKAN,
				RI.NAMA_PENANGGUNGJAWAB,
				RI.SISTEM_BAYAR,
				RI.ID_KAMAR,
				KRI.KODE_KAMAR,
				KRI.NAMA_KAMAR,
				RI.ID_BED,
				BED.NOMOR_BED,
				KA.KONDISI_AKHIR,
				KA.DIRAWAT_SELAMA
			FROM admum_rawat_inap RI
			LEFT JOIN rk_pasien PASIEN ON PASIEN.ID = RI.ID_PASIEN
			LEFT JOIN admum_kamar_rawat_inap KRI ON KRI.ID = RI.ID_KAMAR
			LEFT JOIN admum_bed_rawat_inap BED ON BED.ID = RI.ID_BED
			LEFT JOIN rk_ri_kondisi_akhir KA ON KA.ID_PELAYANAN = RI.ID
			WHERE $where
			AND RI.STATUS_SUDAH = '1'
			ORDER BY RI.ID DESC
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

}
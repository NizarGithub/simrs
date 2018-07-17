<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rk_indeks_catatan_medis_m extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function data_rawat_jalan($tanggal){
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
				KARJ.KONDISI_AKHIR,
				RJ.STATUS_SUDAH,
				SUBSTR(RJ.TANGGAL,4,2) AS BULAN,
				SUBSTR(PASIEN.KODE_PASIEN,4,3) AS KODE
			FROM admum_rawat_jalan RJ
			LEFT JOIN rk_pasien PASIEN ON RJ.ID_PASIEN = PASIEN.ID
			LEFT JOIN admum_poli POLI ON RJ.ID_POLI = POLI.ID
			LEFT JOIN rk_kondisi_akhir_rj KARJ ON KARJ.ID_PELAYANAN = RJ.ID
			WHERE STR_TO_DATE(RJ.TANGGAL,'%d-%m-%Y') = '$tanggal'
			ORDER BY
				BULAN DESC,
				KODE DESC
		";
		$query = $this->db->query($sql);
		return $query->result();
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

	function data_rawat_inap($tanggal){
		$sql = "
			SELECT
				RI.ID,
				RI.ID_PASIEN,
				PASIEN.KODE_PASIEN,
				PASIEN.NAMA AS NAMA_PASIEN,
				RI.TANGGAL_MASUK,
				RI.ASAL_RUJUKAN,
				RI.NAMA_PENANGGUNGJAWAB,
				RI.SISTEM_BAYAR,
				RI.ID_KAMAR,
				KRI.KODE_KAMAR,
				KRI.NAMA_KAMAR,
				RI.ID_BED,
				BED.NOMOR_BED,
				RI.STATUS_SUDAH,
				SUBSTR(RI.TANGGAL_MASUK,4,2) AS BULAN,
				SUBSTR(PASIEN.KODE_PASIEN,4,3) AS KODE
			FROM admum_rawat_inap RI
			LEFT JOIN rk_pasien PASIEN ON PASIEN.ID = RI.ID_PASIEN
			LEFT JOIN admum_kamar_rawat_inap KRI ON KRI.ID = RI.ID_KAMAR
			LEFT JOIN admum_bed_rawat_inap BED ON BED.ID = RI.ID_BED
			WHERE STR_TO_DATE(RI.TANGGAL_MASUK,'%d-%m-%Y') = '$tanggal'
			ORDER BY 
				BULAN DESC,
				KODE DESC
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	//IGD

	function data_igd($tanggal){
		$sql = "
			SELECT
				IGD.ID,
				IGD.ID_PASIEN,
				PASIEN.KODE_PASIEN,
				PASIEN.NAMA AS NAMA_PASIEN,
				IGD.TANGGAL,
				IGD.ASAL_RUJUKAN,
				IGD.SISTEM_BAYAR,
				IGD.STATUS_SUDAH,
				SUBSTR(IGD.TANGGAL,4,2) AS BULAN,
				SUBSTR(PASIEN.KODE_PASIEN,4,3) AS KODE
			FROM admum_igd IGD
			LEFT JOIN rk_pasien PASIEN ON PASIEN.ID = IGD.ID_PASIEN
			WHERE STR_TO_DATE(IGD.TANGGAL,'%d-%m-%Y') = '$tanggal'
			ORDER BY 
				BULAN DESC,
				KODE DESC
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	//TINDAKAN

	function load_tindakan($keyword){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND (NAMA_TINDAKAN LIKE '%$keyword%' OR KODE LIKE '%$keyword%')";
		}else{
			$where = $where; 
		}

		$sql = "SELECT * FROM admum_setup_tindakan WHERE $where ORDER BY ID DESC";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function klik_tindakan($id){
		$sql = "SELECT * FROM admum_setup_tindakan WHERE ID = '$id'";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_poli_perawat($id_poli){
		$sql = "
			SELECT
				PRWT.ID,
				PRWT.ID_POLI,
				PEG.NAMA AS NAMA_PEGAWAI,
				PEG.NIP
			FROM admum_poli_perawat PRWT
			LEFT JOIN kepeg_pegawai PEG ON PEG.ID = PRWT.ID_PEG_PERAWAT
			WHERE PRWT.ID_POLI = '$id_poli'
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_tindakan($id_pelayanan){
		$sql = "
			SELECT
				DET.ID,
				RJ.ID_PELAYANAN,
				POLI.NAMA AS NAMA_POLI,
				PEG.NAMA AS NAMA_DOKTER,
				TDK.KODE,
				TDK.NAMA_TINDAKAN,
				DET.TANGGAL,
				DET.WAKTU,
				DET.BULAN,
				DET.TAHUN,
				DET.JUMLAH,
				TDK.TARIF,
				DET.SUBTOTAL
			FROM rk_tindakan_rj_detail DET
			LEFT JOIN rk_tindakan_rj RJ ON RJ.ID = DET.ID_TINDAKAN_RJ
			LEFT JOIN admum_setup_tindakan TDK ON TDK.ID = DET.TINDAKAN
			LEFT JOIN admum_poli POLI ON POLI.ID = RJ.ID_POLI
			LEFT JOIN kepeg_pegawai PEG ON PEG.ID = POLI.ID_PEG_DOKTER
			WHERE RJ.ID_PELAYANAN = '$id_pelayanan'
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_tindakan_id($id){
		$sql = "
			SELECT
				DET.ID,
				RJ.ID_PELAYANAN,
				POLI.NAMA AS NAMA_POLI,
				PEG.NAMA AS NAMA_DOKTER,
				DET.TINDAKAN,
				TDK.KODE,
				TDK.NAMA_TINDAKAN,
				DET.TANGGAL,
				DET.BULAN,
				DET.TAHUN,
				DET.JUMLAH,
				TDK.TARIF,
				DET.SUBTOTAL
			FROM rk_tindakan_rj_detail DET
			LEFT JOIN rk_tindakan_rj RJ ON RJ.ID = DET.ID_TINDAKAN_RJ
			LEFT JOIN admum_setup_tindakan TDK ON TDK.ID = DET.TINDAKAN
			LEFT JOIN admum_poli POLI ON POLI.ID = RJ.ID_POLI
			LEFT JOIN kepeg_pegawai PEG ON PEG.ID = POLI.ID_PEG_DOKTER
			WHERE DET.ID = '$id'
		";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function tindakan_id($id){
		$sql = "SELECT TDK.* FROM admum_setup_tindakan TDK WHERE ID = '$id'";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function ubah_tindakan($id,$tindakan,$jumlah,$subtotal){
		$sql = "
			UPDATE rk_tindakan_rj_detail SET 
				TINDAKAN = '$tindakan',
				JUMLAH = '$jumlah',
				SUBTOTAL = '$subtotal' 
			WHERE ID = '$id'
		";
		$this->db->query($sql);
	}

	//DIAGNOSA

	function data_kasus($keyword){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND NAMA_KASUS LIKE '%$keyword%'";
		}

		$sql = "SELECT * FROM admum_setup_kasus_diagnosa WHERE $where";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_kasus_id($id){
		$sql = "SELECT * FROM admum_setup_kasus_diagnosa WHERE ID = '$id'";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function data_spesialistik($keyword){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND NAMA_SPESIALISTIK LIKE '%$keyword%'";
		}

		$sql = "SELECT * FROM admum_setup_spesialistik WHERE $where";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_spesialistik_id($id){
		$sql = "SELECT * FROM admum_setup_spesialistik WHERE ID = '$id'";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function data_diagnosa($id_pelayanan){
		$sql = "
			SELECT
				DG.ID,
				DG.TANGGAL,
				DG.DIAGNOSA,
				DG.TINDAKAN,
				KA.NAMA_KASUS,
				SP.NAMA_SPESIALISTIK
			FROM rk_diagnosa_rj DG
			LEFT JOIN admum_setup_kasus_diagnosa KA ON KA.ID = DG.ID_KASUS
			LEFT JOIN admum_setup_spesialistik SP ON SP.ID = DG.ID_SPESIALISTIK
			WHERE DG.ID_PELAYANAN = '$id_pelayanan'
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_diagnosa_id($id,$id_pelayanan){
		$sql = "
			SELECT
				DG.ID,
				DG.TANGGAL,
				DG.DIAGNOSA,
				DG.TINDAKAN,
				DG.ID_KASUS,
				KA.NAMA_KASUS,
				DG.ID_SPESIALISTIK,
				SP.NAMA_SPESIALISTIK
			FROM rk_diagnosa_rj DG
			LEFT JOIN admum_setup_kasus_diagnosa KA ON KA.ID = DG.ID_KASUS
			LEFT JOIN admum_setup_spesialistik SP ON SP.ID = DG.ID_SPESIALISTIK
			WHERE DG.ID = '$id'
			AND DG.ID_PELAYANAN = '$id_pelayanan'
		";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function ubah_diagnosa($id,$diagnosa,$tindakan,$kasus,$spesialistik){
		$sql = "
			UPDATE rk_diagnosa_rj SET
				DIAGNOSA = '$diagnosa',
				TINDAKAN = '$tindakan',
				ID_KASUS = '$kasus',
				ID_SPESIALISTIK = '$spesialistik'
			WHERE ID = '$id'
		";
		$this->db->query($sql);
	}

}
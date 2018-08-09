<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Poli_home_m extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function data_pasien($keyword,$posisi,$now,$id_divisi,$level){
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

		if($level == null){
			$where = $where;
		}else{
			$where = $where." AND c.ID_DIVISI = '$id_divisi' AND b.STS_POSISI = '$posisi' AND b.TANGGAL = '$now'";
		}

		$sql = "
			SELECT 
				PSN.*,
				b.ID AS ID_RJ,
				b.ID_POLI,
				c.ID_DIVISI,
				b.STS_TERIMA
			FROM admum_rawat_jalan b
			LEFT JOIN rk_pasien PSN ON b.ID_PASIEN = PSN.ID
			LEFT JOIN admum_poli c ON c.ID = b.ID_POLI
			LEFT JOIN kepeg_divisi d ON d.ID = c.ID_DIVISI
			WHERE $where
			AND b.STS_TERIMA = '0'
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

	function data_pasien_terima($keyword,$posisi,$now,$id_divisi,$poli,$level,$hasil_cek,$tanggal_awal,$tanggal_akhir,$bulan,$tahun){
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

		if($hasil_cek == 'True'){
			$where = $where." AND c.ID_DIVISI = '$id_divisi' AND b.STS_POSISI = '$posisi'";
		}else{
			if($level == null){
				if($poli == 'Semua'){
					$where = $where;
				}else{
					$where = $where." AND b.ID_POLI = '$poli'";
				}

				if($tanggal_awal != "" && $tanggal_akhir != ""){
					$where = $where." AND STR_TO_DATE(b.TANGGAL, '%d-%m-%Y') > '$tanggal_awal' AND STR_TO_DATE(b.TANGGAL, '%d-%m-%Y') < '$tanggal_akhir'";
				}else if($bulan != 0 && $tahun != ""){
					$where = $where." AND b.BULAN = '$bulan' AND b.TAHUN = '$tahun'";
				}
			}else{
				$where = $where." AND c.ID_DIVISI = '$id_divisi' AND b.STS_POSISI = '$posisi' AND b.TANGGAL = '$now' AND b.STS_TERIMA = '1'";
			}
		}

		$sql = "
			SELECT 
				PSN.*,
				b.ID AS ID_RJ,
				b.TANGGAL,
				b.BULAN,
				b.TAHUN,
				b.ID_POLI,
				c.ID_DIVISI
			FROM admum_rawat_jalan b
			LEFT JOIN rk_pasien PSN ON b.ID_PASIEN = PSN.ID
			LEFT JOIN admum_poli c ON c.ID = b.ID_POLI
			LEFT JOIN kepeg_divisi d ON d.ID = c.ID_DIVISI
			WHERE $where
			ORDER BY PSN.ID DESC
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function terima_pasien($id){
		$sql = "UPDATE admum_rawat_jalan SET STS_TERIMA = '1' WHERE ID = '$id'";
		$this->db->query($sql);
	}

	function get_poli(){
		$sql = "SELECT * FROM admum_poli WHERE AKTIF = '1'";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function get_rekam_medik($id_pasien,$tanggal){
		$sql = "
			SELECT
				RJ.ID,
				RJ.ID_PASIEN,
				RJ.TANGGAL,
				RJ.ID_POLI,
				P.NAMA AS NAMA_POLI,
				P.BIAYA,
				PEG.NAMA AS NAMA_DOKTER
			FROM admum_rawat_jalan RJ
			LEFT JOIN admum_poli P ON P.ID = RJ.ID_POLI
			LEFT JOIN kepeg_pegawai PEG ON PEG.ID = P.ID_PEG_DOKTER
			WHERE RJ.ID_PASIEN = '$id_pasien';
		";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function get_tindakan_det($id_tindakan){
		$sql = "
			SELECT 
				DET.*,
				TD.NAMA_TINDAKAN,
				TD.TARIF
			FROM rk_tindakan_rj_detail DET
			LEFT JOIN admum_setup_tindakan TD ON TD.ID = DET.TINDAKAN
			WHERE DET.ID_TINDAKAN_RJ = '$id_tindakan'
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function get_diagnosa($id_pasien){
		$sql = "
			SELECT 
				DG.*,
				PK.URAIAN AS NAMA_PENYAKIT
			FROM rk_diagnosa_rj DG
			LEFT JOIN admum_jenis_penyakit PK ON PK.ID = DG.ID_PENYAKIT
			WHERE DG.ID_PASIEN = '$id_pasien'
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function get_resep($id_resep){
		$sql = "
			SELECT
				DET.ID,
				NM_OBT.KODE_OBAT,
				NM_OBT.NAMA_OBAT,
				DET.TAKARAN,
				DET.ATURAN_MINUM,
				DET.HARGA,
				DET.SUBTOTAL
			FROM rk_resep_detail_rj DET
			LEFT JOIN apotek_gudang_obat GD ON GD.ID = DET.ID_OBAT
			LEFT JOIN admum_setup_nama_obat NM_OBT ON NM_OBT.ID = GD.ID_SETUP_NAMA_OBAT
			WHERE DET.ID_RESEP = '$id_resep'
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

}
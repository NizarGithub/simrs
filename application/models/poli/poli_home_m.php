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
					$where = $where." 
						AND STR_TO_DATE(b.TANGGAL, '%d-%m-%Y') >= STR_TO_DATE('$tanggal_awal', '%d-%m-%Y') 
						AND STR_TO_DATE(b.TANGGAL, '%d-%m-%Y') <= STR_TO_DATE('$tanggal_akhir', '%d-%m-%Y') 
						AND b.STS_POSISI = '$posisi'
					";
				}else if($bulan != 0 && $tahun != ""){
					$where = $where." 
						AND b.BULAN = '$bulan' 
						AND b.TAHUN = '$tahun'
						AND b.STS_POSISI = '$posisi'
					";
				}
			}else{
				if($tanggal_awal != "" && $tanggal_akhir != ""){
					$where = $where." 
						AND STR_TO_DATE(b.TANGGAL, '%d-%m-%Y') >= STR_TO_DATE('$tanggal_awal', '%d-%m-%Y') 
						AND STR_TO_DATE(b.TANGGAL, '%d-%m-%Y') <= STR_TO_DATE('$tanggal_akhir', '%d-%m-%Y') 
						AND b.STS_POSISI = '$posisi'
						AND b.STS_TERIMA = '1'
					";
				}else if($bulan != 0 && $tahun != ""){
					$where = $where." 
						AND b.BULAN = '$bulan' 
						AND b.TAHUN = '$tahun'
						AND b.STS_POSISI = '$posisi'
						AND b.STS_TERIMA = '1'
					";
				}else{
					$where = $where." 
						AND b.TANGGAL = '$now'
						AND c.ID_DIVISI = '$id_divisi' 
						AND b.STS_POSISI = '$posisi' 
						AND b.STS_TERIMA = '1'
					";
				}
			}
		}

		$sql = "
			SELECT 
				PSN.*,
				b.ID AS ID_RJ,
				b.TANGGAL,
				b.WAKTU,
				b.BULAN,
				b.TAHUN,
				b.ID_POLI,
				b.STATUS_SUDAH,
				c.ID_DIVISI,
				e.KODE_ANTRIAN,
				e.NOMOR_ANTRIAN
			FROM admum_rawat_jalan b
			LEFT JOIN rk_pasien PSN ON b.ID_PASIEN = PSN.ID
			LEFT JOIN admum_poli c ON c.ID = b.ID_POLI
			LEFT JOIN kepeg_divisi d ON d.ID = c.ID_DIVISI
			JOIN rk_antrian_pasien e ON e.ID_PASIEN = PSN.ID
			WHERE $where
			ORDER BY b.ID ASC
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function terima_pasien($id){
		$sql = "UPDATE admum_rawat_jalan SET STS_TERIMA = '1' WHERE ID = '$id'";
		$this->db->query($sql);
	}

	function ubah_stt_panggil($id_rj,$tanggal){
		$sql = "UPDATE rk_antrian_pasien SET STATUS_PANGGIL = '1' WHERE ID_PELAYANAN = '$id_rj' AND TANGGAL = '$tanggal'";
		$this->db->query($sql);
	}

	function ubah_jenis_pasien($id_pasien){
		$sql = "UPDATE rk_pasien SET JENIS_PASIEN = 'Lama' WHERE ID = '$id_pasien'";
		$this->db->query($sql);
	}

	function get_poli(){
		$sql = "SELECT * FROM admum_poli WHERE AKTIF = '1'";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function get_rekam_medik($id_rj,$tanggal){
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
			WHERE RJ.ID = '$id_rj';
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

	function get_diagnosa($id_rj,$tanggal){
		$sql = "
			SELECT 
				DG.*,
				PK.URAIAN AS NAMA_PENYAKIT
			FROM rk_diagnosa_rj DG
			LEFT JOIN admum_jenis_penyakit PK ON PK.ID = DG.ID_PENYAKIT
			WHERE DG.ID_PELAYANAN = '$id_rj'
			AND DG.TANGGAL = '$tanggal'
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

	function get_lab($id_pasien,$tanggal){
		$sql = "
			SELECT
				a.ID,
				a.KODE_LAB,
				a.TANGGAL,
				b.JENIS_LABORAT,
				a.TOTAL_TARIF
			FROM rk_laborat_rj a
			JOIN admum_setup_jenis_laborat b ON b.ID = a.JENIS_LABORAT
			WHERE a.ID_PASIEN = '$id_pasien'
			AND a.TANGGAL = '$tanggal'
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function get_lab_det($id_lab){
		$sql = "
			SELECT
				a.*,
				b.NAMA_PEMERIKSAAN
			FROM rk_laborat_rj_detail a
			JOIN admum_setup_pemeriksaan b ON b.ID = a.PEMERIKSAAN
			WHERE a.ID_PEMERIKSAAN_RJ = '$id_lab'
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function panggil_pasien($id_rj){
		$sql = "
			SELECT
				a.*,
				b.NAMA_POLI,
				c.NAMA
			FROM rk_antrian_pasien a
			JOIN (
				SELECT
					a.*,
					b.NAMA AS NAMA_POLI
				FROM admum_rawat_jalan a
				JOIN admum_poli b ON b.ID = a.ID_POLI
			) b ON b.ID = a.ID_PELAYANAN
			JOIN rk_pasien c ON c.ID = a.ID_PASIEN
			WHERE a.ID_PELAYANAN = '$id_rj'
		";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function notif_pasien_baru($level,$id_divisi,$posisi,$now){
		$where = "1 = 1";

		if($level == null){
			$where = $where;
		}else{
			$where = $where." AND c.ID_DIVISI = '$id_divisi' AND b.STS_POSISI = '$posisi' AND b.TANGGAL = '$now'";
		}

		$sql = "
			SELECT 
				COUNT(*) AS TOTAL
			FROM admum_rawat_jalan b
			JOIN rk_pasien PSN ON b.ID_PASIEN = PSN.ID
			JOIN admum_poli c ON c.ID = b.ID_POLI
			JOIN rk_antrian_pasien e ON e.ID_PASIEN = PSN.ID
			WHERE $where
			AND b.STS_TERIMA = '0'
		";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function data_pasien_baru($level,$id_divisi,$posisi,$now){
		$where = "1 = 1";

		if($level == null){
			$where = $where;
		}else{
			$where = $where." AND c.ID_DIVISI = '$id_divisi' AND b.STS_POSISI = '$posisi' AND b.TANGGAL = '$now'";
		}

		$sql = "
			SELECT 
				b.ID,
				b.ID_POLI,
				c.ID_DIVISI,
				b.TANGGAL,
				b.WAKTU,
				b.STS_TERIMA,
				e.KODE_ANTRIAN,
				e.NOMOR_ANTRIAN,
				PSN.KODE_PASIEN,
				PSN.NAMA,
				PSN.JENIS_KELAMIN
			FROM admum_rawat_jalan b
			JOIN rk_pasien PSN ON b.ID_PASIEN = PSN.ID
			JOIN admum_poli c ON c.ID = b.ID_POLI
			JOIN rk_antrian_pasien e ON e.ID_PASIEN = PSN.ID
			WHERE $where
			AND b.STS_TERIMA = '0'
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function get_antrian_pasien(){
		$sql = "
			SELECT
				a.ID,
				a.TANGGAL,
				a.WAKTU,
				a.ID_PASIEN,
				a.ID_PELAYANAN,
				a.BARCODE,
				a.WAKTU,
				a.ID_LOKET,
				b.NAMA_LOKET,
				a.KODE_ANTRIAN,
				a.NOMOR_ANTRIAN,
				a.STATUS_PANGGIL
			FROM rk_antrian_pasien a
			JOIN kepeg_loket b ON b.ID = a.ID_LOKET
			WHERE a.STATUS_PANGGIL = '1'
			AND a.STATUS_CLOSING = '0'
			ORDER BY a.NOMOR_ANTRIAN DESC
			LIMIT 1
		";
		$query = $this->db->query($sql);
		return $query->row();
	}

}
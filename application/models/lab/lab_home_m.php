<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lab_home_m extends CI_Model {

	function __construct()
	{
		parent::__construct(); 
		$this->load->database();
	}

	function default_lokasi(){
		$sql = "SELECT * FROM admum_lokasi ORDER BY ID DESC LIMIT 1";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function kota_kab(){
		$sql = "
			SELECT
				LKS.lokasi_ID,
				LKS.lokasi_kode,
				LKS.lokasi_propinsi AS ID_PROV,
				LKS.lokasi_nama AS PROV,
				LKS2.lokasi_nama AS KOTA,
				LKS2.lokasi_propinsi,
				LKS2.lokasi_kabupatenkota
			FROM lokasi LKS
			JOIN(
				SELECT * FROM lokasi 
				WHERE lokasi_kabupatenkota != '0'
				AND lokasi_kecamatan = '0'
			) LKS2 ON LKS.lokasi_propinsi = LKS2.lokasi_propinsi
			WHERE LKS.lokasi_kabupatenkota = '0'
			ORDER BY 
			LKS.lokasi_propinsi ASC,
			KOTA ASC
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function provinsi($id_kota_kab){
		$sql = "
			SELECT
				LKS.lokasi_ID,
				LKS.lokasi_kode,
				LKS.lokasi_propinsi AS ID_PROV,
				LKS.lokasi_nama AS PROV,
				LKS2.lokasi_nama AS KOTA,
				LKS2.lokasi_propinsi,
				LKS2.lokasi_kabupatenkota
			FROM lokasi LKS
			JOIN(
				SELECT * FROM lokasi 
				WHERE lokasi_kabupatenkota != '0'
				AND lokasi_kecamatan = '0'
			) LKS2 ON LKS.lokasi_propinsi = LKS2.lokasi_propinsi
			WHERE LKS.lokasi_kabupatenkota = '0'
			AND LKS2.lokasi_nama = '$id_kota_kab'
			ORDER BY 
			LKS.lokasi_propinsi ASC,
			KOTA ASC
		";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function load_data_pasien($keyword){ 
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND (NAMA LIKE '%$keyword%' OR NIK LIKE '%$keyword%' OR KODE_PASIEN LIKE '%$keyword%')";
		}else{
			$where = $where;
		}

		$sql = "
			SELECT 
				ID,
				KODE_PASIEN,
				NAMA,
				JENIS_KELAMIN,
				UMUR,
				UMUR_BULAN,
				NAMA_ORTU,
				SUBSTR(KODE_PASIEN,4,3) AS KODE,
				SUBSTR(TANGGAL_DAFTAR,4,2) AS BULAN
			FROM rk_pasien WHERE $where
			ORDER BY
				BULAN ASC,
				KODE ASC
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function klik_pasien($id){
		$sql = "SELECT * FROM rk_pasien WHERE ID = '$id'";
		$query = $this->db->query($sql);
		return $query->row();
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
				b.TANGGAL,
				b.WAKTU AS WAKTU_RJ,
				b.STS_POSISI,
				b.STS_TERIMA,
				c.TIPE
			FROM admum_rawat_jalan b
			JOIN rk_pasien PSN ON b.ID_PASIEN = PSN.ID
			JOIN rk_laborat_rj c ON c.ID_PELAYANAN = b.ID
			WHERE $where
			AND b.STS_POSISI = '2'
			AND b.STS_TERIMA = '0'
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function tot_pasien_terima($tanggal,$tipe){
		$sql = "
			SELECT
				COUNT(*) AS TOTAL
			FROM admum_rawat_jalan a
			JOIN rk_laborat_rj b ON b.ID_PELAYANAN = a.ID
			WHERE a.TANGGAL = '$tanggal'
			AND b.TIPE = '$tipe'
			AND a.STS_POSISI = '2'
			AND a.STS_TERIMA = '2'
		";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function terima_pasien($id){
		$sql = "UPDATE admum_rawat_jalan SET STS_TERIMA = '2' WHERE ID = '$id'";
		$this->db->query($sql);
	}

	function data_pasien_terima($keyword,$posisi,$now,$level,$hasil_cek,$tanggal_awal,$tanggal_akhir,$bulan,$tahun){
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

		if($hasil_cek == 'True'){
			$where = $where." AND b.STS_POSISI = '$posisi'";
		}else{
			if($level == null){
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
						AND b.STS_TERIMA = '2'
					";
				}else if($bulan != 0 && $tahun != ""){
					$where = $where." 
						AND b.BULAN = '$bulan' 
						AND b.TAHUN = '$tahun' 
						AND b.STS_POSISI = '$posisi' 
						AND b.STS_TERIMA = '2'
					";
				}else{
					$where = $where." AND b.STS_POSISI = '$posisi' AND b.TANGGAL = '$now' AND b.STS_TERIMA = '2'";
				}
			}
		}

		$sql = "
			SELECT 
				PSN.*,
				b.ID AS ID_RJ,
				b.TANGGAL,
				b.BULAN,
				b.TAHUN,
				b.WAKTU AS WAKTU_RJ,
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

	function get_pasien_dr_poli($tanggal,$keyword){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND (d.NAMA LIKE '%$keyword%' OR d.NAMA_ORTU LIKE '%$keyword%')";
		}

		$sql = "
			SELECT
				a.ID,
				a.ID_PASIEN,
				d.KODE_PASIEN,
				d.NAMA,
				d.NAMA_ORTU,
				a.ASAL_RUJUKAN,
				b.ID AS ID_LAB,
				b.KODE_LAB,
				e.JENIS_LABORAT,
				b.TOTAL_TARIF,
				a.HARI,
				a.TANGGAL,
				a.BULAN,
				a.TAHUN,
				a.WAKTU,
				a.ID_POLI,
				c.NAMA AS NAMA_POLI,
				f.NAMA AS NAMA_DOKTER,
				b.TIPE,
				b.STATUS_PENANGANAN,
				b.STATUS_LIHAT
			FROM admum_rawat_jalan a
			JOIN rk_laborat_rj b ON b.ID_PELAYANAN = a.ID
			JOIN admum_poli c ON c.ID = b.ID_POLI
			JOIN rk_pasien d ON d.ID = a.ID_PASIEN
			JOIN admum_setup_jenis_laborat e ON e.ID = b.JENIS_LABORAT
			JOIN kepeg_pegawai f ON f.ID = b.ID_PEG_DOKTER
			WHERE $where
			AND b.TIPE = 'Dari Poli'
			AND b.STATUS_PENANGANAN = '0'
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function get_pasien_poli_id($id){
		$sql = "
			SELECT
				a.ID,
				a.ID_PASIEN,
				d.KODE_PASIEN,
				d.NAMA,
				d.NAMA_ORTU,
				a.ASAL_RUJUKAN,
				b.ID AS ID_LAB,
				b.KODE_LAB,
				e.JENIS_LABORAT,
				b.TOTAL_TARIF,
				a.HARI,
				a.TANGGAL,
				a.BULAN,
				a.TAHUN,
				a.WAKTU,
				a.ID_POLI,
				c.NAMA AS NAMA_POLI,
				f.NAMA AS NAMA_DOKTER,
				b.TIPE,
				b.STATUS_PENANGANAN,
				b.STATUS_LIHAT
			FROM admum_rawat_jalan a
			JOIN rk_laborat_rj b ON b.ID_PELAYANAN = a.ID
			JOIN admum_poli c ON c.ID = b.ID_POLI
			JOIN rk_pasien d ON d.ID = a.ID_PASIEN
			JOIN admum_setup_jenis_laborat e ON e.ID = b.JENIS_LABORAT
			JOIN kepeg_pegawai f ON f.ID = b.ID_PEG_DOKTER
			WHERE b.ID = '$id'
		";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function get_detail_lab_poli($id_rj){
		$sql = "
			SELECT
				a.ID,
				a.ID_PEMERIKSAAN_RJ,
				a.PEMERIKSAAN,
				b.NAMA_PEMERIKSAAN,
				a.HASIL,
				a.NILAI_RUJUKAN,
				a.SUBTOTAL,
				a.TANGGAL,
				a.WAKTU
			FROM rk_laborat_rj_detail a
			JOIN admum_setup_pemeriksaan b ON b.ID = a.PEMERIKSAAN
			WHERE a.ID_PEMERIKSAAN_RJ = '$id_rj'
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function ubah_status_lihat($tanggal){
		$sql = "UPDATE rk_laborat_rj SET STATUS_LIHAT = '1' WHERE TIPE = 'Dari Poli' AND STATUS_LIHAT = '0' AND TANGGAL = '$tanggal'";
		$this->db->query($sql);
	}

	function ubah_laborat_detail($id,$hasil,$nilai_rujukan){
		$sql = "
			UPDATE rk_laborat_rj_detail SET
				HASIL = '$hasil',
				NILAI_RUJUKAN = '$nilai_rujukan'
			WHERE ID = '$id'
		";
		$this->db->query($sql);
	}

	function data_rawat_jalan_id($id){
		$sql = "
			SELECT
				PASIEN.*,
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

	function data_laborat($id){
		$sql = "
			SELECT
				LAB.ID,
				LAB.KODE_LAB,
				SET_LAB.JENIS_LABORAT,
				LAB.CITO,
				LAB.TOTAL_TARIF,
				LAB.TANGGAL,
				LAB.BULAN,
				LAB.TAHUN,
				LAB.WAKTU
			FROM rk_laborat_rj LAB
			LEFT JOIN admum_setup_jenis_laborat SET_LAB ON SET_LAB.ID = LAB.JENIS_LABORAT
			WHERE LAB.ID_PELAYANAN = '$id'
			ORDER BY LAB.ID DESC
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_laborat_id($id){
		$sql = "
			SELECT
				LAB.ID,
				LAB.ID_PELAYANAN,
				LAB.KODE_LAB,
				SET_LAB.JENIS_LABORAT,
				LAB.CITO,
				LAB.TOTAL_TARIF,
				LAB.TANGGAL,
				LAB.BULAN,
				LAB.TAHUN
			FROM rk_laborat_rj LAB
			LEFT JOIN admum_setup_jenis_laborat SET_LAB ON SET_LAB.ID = LAB.JENIS_LABORAT
			WHERE LAB.ID_PELAYANAN = '$id'
			ORDER BY LAB.ID DESC
		";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function get_data_lab_id($id_lab){
		$sql = "
			SELECT
				a.ID,
				a.KODE_LAB,
				b.JENIS_LABORAT,
				a.CITO,
				a.TOTAL_TARIF
			FROM rk_laborat_rj a
			JOIN admum_setup_jenis_laborat b ON b.ID = a.JENIS_LABORAT
			WHERE a.ID = '$id_lab'
		";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function get_data_lab_det($id_lab){
		$sql = "
			SELECT
				DET.ID,
				LAB.ID_PELAYANAN,
				PRK.ID AS ID_PEMERIKSAAN,
				PRK.KODE,
				PRK.NAMA_PEMERIKSAAN,
				DET.HASIL,
				DET.NILAI_RUJUKAN,
				PRK.TARIF,
				DET.SUBTOTAL,
				DET.TANGGAL,
				DET.BULAN,
				DET.TAHUN
			FROM rk_laborat_rj_detail DET
			LEFT JOIN rk_laborat_rj LAB ON LAB.ID = DET.ID_PEMERIKSAAN_RJ
			LEFT JOIN admum_setup_pemeriksaan PRK ON PRK.ID = DET.PEMERIKSAAN
			WHERE DET.ID_PEMERIKSAAN_RJ = '$id_lab'
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_hasil_pemeriksaan($id_pemeriksaan){
		$sql = "
			SELECT
				DET.ID,
				LAB.ID_PELAYANAN,
				PRK.KODE,
				PRK.NAMA_PEMERIKSAAN,
				DET.HASIL,
				DET.NILAI_RUJUKAN,
				PRK.TARIF,
				DET.SUBTOTAL,
				DET.TANGGAL,
				DET.BULAN,
				DET.TAHUN
			FROM rk_laborat_rj_detail DET
			LEFT JOIN rk_laborat_rj LAB ON LAB.ID = DET.ID_PEMERIKSAAN_RJ
			LEFT JOIN admum_setup_pemeriksaan PRK ON PRK.ID = DET.PEMERIKSAAN
			WHERE LAB.ID_PELAYANAN = '$id_pemeriksaan'
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function hapus_laborat($id){
		$sql = "DELETE FROM rk_laborat_rj WHERE ID = '$id'";
		$this->db->query($sql);
	}

	function hapus_laborat_detail($id){
		$sql = "DELETE FROM rk_laborat_rj_detail WHERE ID_PEMERIKSAAN_RJ = '$id'";
		$this->db->query($sql);
	}

	//APS

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

	function load_pemeriksaan($keyword){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND (NAMA_PEMERIKSAAN LIKE '%$keyword%' OR KODE LIKE '%$keyword%')";
		}else{
			$where = $where;
		}

		$sql = "SELECT * FROM admum_setup_pemeriksaan WHERE $where ORDER BY ID DESC";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function klik_pemeriksaan($id){
		$sql = "SELECT * FROM admum_setup_pemeriksaan WHERE ID = '$id'";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function hasil_lab_per_pasien($id,$tanggal){
		$sql = "
			SELECT 
				a.*,
				b.KODE_PASIEN,
				b.NAMA,
				b.JENIS_KELAMIN,
				b.UMUR,
				b.TANGGAL_LAHIR
			FROM rk_laborat_rj a
			JOIN rk_pasien b ON a.ID_PASIEN = b.ID
			WHERE a.ID_PASIEN = '$id'
			AND a.TANGGAL = '$tanggal'
		";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function hasil_lab_det_per_pasien($id_pasien,$tanggal){
		$sql = "
			SELECT
				a.*,
				c.NAMA_PEMERIKSAAN,
				c.TARIF
			FROM rk_laborat_rj_detail a
			JOIN rk_laborat_rj b ON a.ID_PEMERIKSAAN_RJ = b.ID
			JOIN admum_setup_pemeriksaan c ON a.PEMERIKSAAN = c.ID
			WHERE b.ID_PASIEN = '$id_pasien'
			AND b.TANGGAL = '$tanggal'
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function hasil_lab_per_pasien_id($id_pasien,$tanggal){
		$sql = "SELECT * FROM rk_laborat_rj WHERE ID_PASIEN = '$id_pasien' AND TANGGAL = '$tanggal'";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function simpan_rj($id_pasien,$asal_rujukan,$hari,$tanggal,$bulan,$tahun,$waktu,$id_poli,$posisi){
		$sql = "
			INSERT INTO admum_rawat_jalan(
				ID_PASIEN,
				ASAL_RUJUKAN,
				HARI,
				TANGGAL,
				BULAN,
				TAHUN,
				WAKTU,
				ID_POLI,
				STS_POSISI
			) VALUES(
				'$id_pasien',
				'$asal_rujukan',
				'$hari',
				'$tanggal',
				'$bulan',
				'$tahun',
				'$waktu',
				'$id_poli',
				'$posisi'
			)
		";
		$this->db->query($sql);
	}

	function simpan_pemeriksaan($kode_lab,$id_pelayanan,$id_poli,$id_peg_dokter,$id_pasien,$jenis_laborat,$total_tarif,$cito,$tanggal,$bulan,$tahun,$waktu,$tipe){
		$sql = "
			INSERT INTO rk_laborat_rj(
				KODE_LAB,
				ID_PELAYANAN,
				ID_POLI,
				ID_PEG_DOKTER,
				ID_PASIEN,
				JENIS_LABORAT,
				TOTAL_TARIF,
				CITO,
				TANGGAL,
				BULAN,
				TAHUN,
				WAKTU,
				TIPE,
				STATUS_PENANGANAN
			) VALUES (
				'$kode_lab',
				'$id_pelayanan',
				'$id_poli',
				'$id_peg_dokter',
				'$id_pasien',
				'$jenis_laborat',
				'$total_tarif',
				'$cito',
				'$tanggal',
				'$bulan',
				'$tahun',
				'$waktu',
				'$tipe',
				'1'
			)
		";
		$this->db->query($sql);
	}

	function simpan_pemeriksaan_detail($id_pemeriksaan_rj,$pemeriksaan,$hasil,$nilai_rujukan,$tanggal,$bulan,$tahun,$subtotal,$waktu){
		$sql = "
			INSERT INTO rk_laborat_rj_detail(
				ID_PEMERIKSAAN_RJ,
				PEMERIKSAAN,
				HASIL,
				NILAI_RUJUKAN,
				TANGGAL,
				BULAN,
				TAHUN,
				SUBTOTAL,
				WAKTU
			) VALUES (
				'$id_pemeriksaan_rj',
				'$pemeriksaan',
				'$hasil',
				'$nilai_rujukan',
				'$tanggal',
				'$bulan',
				'$tahun',
				'$subtotal',
				'$waktu'
			)
		";
		$this->db->query($sql);
	}

	function hapus_lab_det($id){
		$sql = "DELETE FROM rk_laborat_rj_detail WHERE ID = '$id'";
		$this->db->query($sql);
	}

}

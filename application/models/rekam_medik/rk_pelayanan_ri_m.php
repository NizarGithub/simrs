<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rk_pelayanan_ri_m extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function data_rawat_inap($keyword){
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
				RI.ASAL_RUJUKAN,
				RI.NAMA_PENANGGUNGJAWAB,
				RI.SISTEM_BAYAR,
				RI.ID_KAMAR,
				KRI.KODE_KAMAR,
				KRI.NAMA_KAMAR,
				RI.ID_BED,
				BED.NOMOR_BED
			FROM admum_rawat_inap RI
			LEFT JOIN rk_pasien PASIEN ON PASIEN.ID = RI.ID_PASIEN
			LEFT JOIN admum_kamar_rawat_inap KRI ON KRI.ID = RI.ID_KAMAR
			LEFT JOIN admum_bed_rawat_inap BED ON BED.ID = RI.ID_BED
			WHERE $where
			AND RI.STATUS_SUDAH = '0'
			ORDER BY RI.ID DESC
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_pasien_sudah($keyword){
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

	function data_rawat_inap_id($id){
		$sql = "
			SELECT
				RI.ID,
				RI.ID_PASIEN,
				PASIEN.KODE_PASIEN,
				PASIEN.NAMA AS NAMA_PASIEN,
				PASIEN.JENIS_KELAMIN,
				PASIEN.UMUR,
				RI.TANGGAL_MASUK,
				RI.ASAL_RUJUKAN,
				RI.NAMA_PENANGGUNGJAWAB,
				RI.SISTEM_BAYAR,
				RI.KELAS,
				RI.ID_KAMAR,
				KRI.KODE_KAMAR,
				KRI.NAMA_KAMAR,
				RI.ID_BED,
				BED.NO,
				BED.NOMOR_BED,
				PASIEN.STATUS,
				PASIEN.ALAMAT,
				PASIEN.PEKERJAAN
			FROM admum_rawat_inap RI
			LEFT JOIN rk_pasien PASIEN ON PASIEN.ID = RI.ID_PASIEN
			LEFT JOIN admum_kamar_rawat_inap KRI ON KRI.ID = RI.ID_KAMAR
			LEFT JOIN admum_bed_rawat_inap BED ON BED.ID = RI.ID_BED
			WHERE RI.ID = '$id'
		";
		$query = $this->db->query($sql);
		return $query->row();
	}

	// TINDAKAN

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

	function load_pelaksana($keyword){
		$where = "1  = 1";

		if($keyword != ""){
			$where = $where." AND (PEG.NIP LIKE '%$keyword%' OR PEG.NAMA LIKE '%$keyword%')";
		}

		$sql = "
			SELECT
				PEG.ID,
				PEG.NIP,
				PEG.NAMA,
				JAB.NAMA AS JABATAN,
				DEP.KODE AS KODE_DEP,
				DEP.NAMA_DEP,
				DV.KODE_DIV,
				DV.NAMA_DIV,
				POLI.NAMA AS NAMA_POLI
			FROM kepeg_pegawai PEG
			LEFT JOIN (
				SELECT 
					ID,
					KODE,
					NAMA_DEP
				FROM kepeg_departemen
				WHERE STS = 0
			) DEP ON DEP.ID = PEG.ID_DEPARTEMEN
			LEFT JOIN (
				SELECT
					ID,
					KODE_DIV,
					NAMA_DIV
				FROM kepeg_divisi
				WHERE STS = 0
			) DV ON DV.ID = PEG.ID_DIVISI
			LEFT JOIN admum_poli POLI ON POLI.ID_PEG_DOKTER = PEG.ID
			LEFT JOIN kepeg_kel_jabatan JAB ON JAB.ID = PEG.ID_JABATAN
			WHERE $where
			ORDER BY PEG.ID DESC
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function klik_pelaksana($id){
		$sql = "
			SELECT
				PEG.ID,
				PEG.NIP,
				PEG.NAMA,
				DEP_DIV.KODE AS KODE_DEP,
				DEP_DIV.NAMA_DEP,
				DEP_DIV.KODE_DIV,
				DEP_DIV.NAMA_DIV
			FROM kepeg_pegawai PEG
			LEFT JOIN (
				SELECT 
					DP.ID,
					DP.KODE,
					DP.NAMA_DEP,
					DV.ID AS ID_DIVISI,
					DV.KODE_DIV,
					DV.NAMA_DIV
				FROM kepeg_departemen DP
				LEFT JOIN kepeg_divisi DV ON DV.ID_DEPARTEMEN = DP.ID
				WHERE DP.STS = 0
				AND DV.STS = 0
			) DEP_DIV ON DEP_DIV.ID = PEG.ID_DEPARTEMEN
			LEFT JOIN admum_poli POLI ON POLI.ID_PEG_DOKTER = PEG.ID
			WHERE PEG.ID = '$id'
		";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function data_tindakan_ri($id_tindakan){
		$sql = "
			SELECT
				DET.ID,
				DET.TANGGAL,
				DET.ID_TINDAKAN,
				TDK.NAMA_TINDAKAN,
				TDK.TARIF,
				DET.JUMLAH,
				DET.SUBTOTAL
			FROM rk_ri_tindakan_detail DET
			LEFT JOIN rk_ri_tindakan RI ON RI.ID = DET.ID_TINDAKAN
			LEFT JOIN admum_setup_tindakan TDK ON TDK.ID = DET.ID_SETUP_TINDAKAN
			WHERE RI.ID_PELAYANAN = '$id_tindakan'
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_tindakan_ri_id($id){
		$sql = "
			SELECT
				DET.ID,
				DET.ID_TINDAKAN,
				DET.TANGGAL,
				DET.ID_SETUP_TINDAKAN,
				TDK.NAMA_TINDAKAN,
				TDK.TARIF,
				DET.JUMLAH,
				DET.SUBTOTAL
			FROM rk_ri_tindakan_detail DET
			LEFT JOIN admum_setup_tindakan TDK ON TDK.ID = DET.ID_SETUP_TINDAKAN
			WHERE DET.ID = '$id'
		";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function simpan_tindakan($id_pelayanan,$id_pasien,$tanggal,$bulan,$tahun,$id_pelaksana,$total){
		$sql = "
			INSERT INTO rk_ri_tindakan(
				ID_PELAYANAN,
				ID_PASIEN,
				TANGGAL,
				BULAN,
				TAHUN,
				ID_PELAKSANA,
				TOTAL
			) VALUES (
				'$id_pelayanan',
				'$id_pasien',
				'$tanggal',
				'$bulan',
				'$tahun',
				'$id_pelaksana',
				'$total'
			)
		";
		$this->db->query($sql);
	}

	function simpan_det_tindakan($id_tindakan,$id_setup_tindakan,$tanggal,$bulan,$tahun,$jumlah,$subtotal){
		$sql = "
			INSERT INTO rk_ri_tindakan_detail(
				ID_TINDAKAN,
				ID_SETUP_TINDAKAN,
				TANGGAL,
				BULAN,
				TAHUN,
				JUMLAH,
				SUBTOTAL
			) VALUES (
				'$id_tindakan',
				'$id_setup_tindakan',
				'$tanggal',
				'$bulan',
				'$tahun',
				'$jumlah',
				'$subtotal'
			)
		";
		$this->db->query($sql); 
	}

	function ubah_tindakan($id,$id_setup_tindakan,$jumlah,$subtotal){
		$sql = "
			UPDATE rk_ri_tindakan_detail SET
				ID_SETUP_TINDAKAN = '$id_setup_tindakan',
				JUMLAH = '$jumlah',
				SUBTOTAL = '$subtotal'
			WHERE ID = '$id'
		";
		$this->db->query($sql);
	}

	function hapus_tindakan($id){
		$sql = "DELETE FROM rk_ri_tindakan_detail WHERE ID = '$id'";
		$this->db->query($sql);
	}

	//VISITE

	function load_visite($keyword){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND NAMA_VISITE LIKE '%$keyword%'";
		}

		$sql = "SELECT * FROM admum_setup_visite WHERE $where";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function klik_visite($id){
		$sql = "SELECT * FROM admum_setup_visite WHERE ID = '$id'";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function load_dokter($keyword){
		$where = "1  = 1";

		if($keyword != ""){
			$where = $where." AND (PEG.NIP LIKE '%$keyword%' OR PEG.NAMA LIKE '%$keyword%')";
		}

		$sql = "
			SELECT
				PEG.ID,
				PEG.NIP,
				PEG.NAMA,
				JAB.NAMA AS JABATAN,
				DEP.KODE AS KODE_DEP,
				DEP.NAMA_DEP,
				DV.KODE_DIV,
				DV.NAMA_DIV,
				POLI.NAMA AS NAMA_POLI
			FROM kepeg_pegawai PEG
			LEFT JOIN (
				SELECT 
					ID,
					KODE,
					NAMA_DEP
				FROM kepeg_departemen
				WHERE STS = 0
			) DEP ON DEP.ID = PEG.ID_DEPARTEMEN
			LEFT JOIN (
				SELECT
					ID,
					KODE_DIV,
					NAMA_DIV
				FROM kepeg_divisi
				WHERE STS = 0
			) DV ON DV.ID = PEG.ID_DIVISI
			LEFT JOIN admum_poli POLI ON POLI.ID_PEG_DOKTER = PEG.ID
			LEFT JOIN kepeg_kel_jabatan JAB ON JAB.ID = PEG.ID_JABATAN
			WHERE $where
			ORDER BY PEG.ID DESC
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function klik_dokter($id){
		$sql = "
			SELECT
				PEG.ID,
				PEG.NIP,
				PEG.NAMA,
				DEP_DIV.KODE AS KODE_DEP,
				DEP_DIV.NAMA_DEP,
				DEP_DIV.KODE_DIV,
				DEP_DIV.NAMA_DIV
			FROM kepeg_pegawai PEG
			LEFT JOIN (
				SELECT 
					DP.ID,
					DP.KODE,
					DP.NAMA_DEP,
					DV.ID AS ID_DIVISI,
					DV.KODE_DIV,
					DV.NAMA_DIV
				FROM kepeg_departemen DP
				LEFT JOIN kepeg_divisi DV ON DV.ID_DEPARTEMEN = DP.ID
				WHERE DP.STS = 0
				AND DV.STS = 0
			) DEP_DIV ON DEP_DIV.ID = PEG.ID_DEPARTEMEN
			LEFT JOIN admum_poli POLI ON POLI.ID_PEG_DOKTER = PEG.ID
			WHERE PEG.ID = '$id'
		";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function data_visite($id_pelayanan){
		$sql = "
			SELECT 
				RIVST.ID,
				RIVST.TANGGAL,
				VST.KODE,
				VST.NAMA_VISITE,
				VST.TARIF,
				PEG.NAMA AS NAMA_DOKTER
			FROM rk_ri_visite RIVST
			LEFT JOIN admum_setup_visite VST ON VST.ID = RIVST.ID_VISITE
			LEFT JOIN kepeg_pegawai PEG ON PEG.ID = RIVST.ID_DOKTER
			WHERE RIVST.ID_PELAYANAN = '$id_pelayanan'
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_visite_id($id,$id_pelayanan){
		$sql = "
			SELECT 
				RIVST.ID,
				RIVST.TANGGAL,
				RIVST.ID_VISITE,
				VST.KODE,
				VST.NAMA_VISITE,
				VST.TARIF,
				RIVST.ID_DOKTER,
				PEG.NAMA AS NAMA_DOKTER
			FROM rk_ri_visite RIVST
			LEFT JOIN admum_setup_visite VST ON VST.ID = RIVST.ID_VISITE
			LEFT JOIN kepeg_pegawai PEG ON PEG.ID = RIVST.ID_DOKTER
			WHERE RIVST.ID = '$id'
			AND RIVST.ID_PELAYANAN = '$id_pelayanan'
		";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function simpan_visite($id_pelayanan,$id_pasien,$tanggal,$bulan,$tahun,$id_visite,$id_dokter){
		$sql = "
			INSERT INTO rk_ri_visite(
				ID_PELAYANAN,
				ID_PASIEN,
				TANGGAL,
				BULAN,
				TAHUN,
				ID_VISITE,
				ID_DOKTER
			) VALUES (
				'$id_pelayanan',
				'$id_pasien',
				'$tanggal',
				'$bulan',
				'$tahun',
				'$id_visite',
				'$id_dokter'
			)
		";
		$this->db->query($sql);
	}

	function ubah_visite($id,$id_visite,$id_dokter){
		$sql = "
			UPDATE rk_ri_visite SET
				ID_VISITE = '$id_visite',
				ID_DOKTER = '$id_dokter'
			WHERE ID = '$id'
		";
		$this->db->query($sql);
	}

	function hapus_visite($id,$id_pelayanan){
		$sql = "DELETE FROM rk_ri_visite WHERE ID = '$id' AND ID_PELAYANAN = '$id_pelayanan'";
		$this->db->query($sql);
	}

	//GIZI

	function load_gizi($keyword){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND NAMA_GIZI LIKE '%$keyword%'";
		}

		$sql = "SELECT * FROM admum_setup_gizi WHERE $where";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function klik_gizi($id){
		$sql = "SELECT * FROM admum_setup_gizi WHERE ID = '$id'";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function data_gizi($id_pelayanan,$id_pasien){
		$sql = "
			SELECT
				RIGZ.ID,
				RIGZ.ID_PELAYANAN,
				RIGZ.ID_PASIEN,
				RIGZ.TANGGAL,
				GZ.KODE,
				GZ.NAMA_GIZI,
				GZ.TARIF
			FROM rk_ri_gizi RIGZ
			LEFT JOIN admum_setup_gizi GZ ON GZ.ID = RIGZ.ID_GIZI
			WHERE RIGZ.ID_PELAYANAN = '$id_pelayanan'
			AND RIGZ.ID_PASIEN = '$id_pasien'
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_gizi_id($id,$id_pelayanan,$id_pasien){
		$sql = "
			SELECT
				RIGZ.ID,
				RIGZ.ID_PELAYANAN,
				RIGZ.ID_PASIEN,
				RIGZ.TANGGAL,
				RIGZ.ID_GIZI,
				GZ.KODE,
				GZ.NAMA_GIZI,
				GZ.TARIF
			FROM rk_ri_gizi RIGZ
			LEFT JOIN admum_setup_gizi GZ ON GZ.ID = RIGZ.ID_GIZI
			WHERE RIGZ.ID = '$id'
			AND RIGZ.ID_PELAYANAN = '$id_pelayanan'
			AND RIGZ.ID_PASIEN = '$id_pasien'
		";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function simpan_gizi($id_pelayanan,$id_pasien,$tanggal,$bulan,$tahun,$id_gizi){
		$sql = "
			INSERT INTO rk_ri_gizi(
				ID_PELAYANAN,
				ID_PASIEN,
				TANGGAL,
				BULAN,
				TAHUN,
				ID_GIZI
			) VALUES (
				'$id_pelayanan',
				'$id_pasien',
				'$tanggal',
				'$bulan',
				'$tahun',
				'$id_gizi'
			)
		";
		$this->db->query($sql);
	}

	function ubah_gizi($id,$id_gizi){
		$sql = "
			UPDATE rk_ri_gizi SET
				ID_GIZI = '$id_gizi'
			WHERE ID = '$id'
		";
		$this->db->query($sql);
	}

	function hapus_gizi($id){
		$sql = "DELETE FROM rk_ri_gizi WHERE ID = '$id'";
		$this->db->query($sql);
	}

	//OKSIGEN

	function data_oksigen($id_pelayanan,$id_pasien){
		$sql = "SELECT * FROM rk_ri_oksigen WHERE ID_PELAYANAN = '$id_pelayanan' AND ID_PASIEN = '$id_pasien'";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_oksigen_id($id,$id_pelayanan,$id_pasien){
		$sql = "SELECT * FROM rk_ri_oksigen WHERE ID = '$id' AND ID_PELAYANAN = '$id_pelayanan' AND ID_PASIEN = '$id_pasien'";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function simpan_oksigen($id_pelayanan,$id_pasien,$tanggal,$bulan,$tahun,$keterangan,$jumlah,$tarif,$total,$pemakaian){
		$sql = "
			INSERT INTO rk_ri_oksigen(
				ID_PELAYANAN,
				ID_PASIEN,
				TANGGAL,
				BULAN,
				TAHUN,
				KETERANGAN,
				JUMLAH,
				TARIF,
				TOTAL,
				PEMAKAIAN_SELAMA
			) VALUES (
				'$id_pelayanan',
				'$id_pasien',
				'$tanggal',
				'$bulan',
				'$tahun',
				'$keterangan',
				'$jumlah',
				'$tarif',
				'$total',
				'$pemakaian'
			)
		";
		$this->db->query($sql);
	}

	function ubah_oksigen($id,$keterangan,$jumlah,$tarif){
		$sql = "
			UPDATE rk_ri_oksigen SET
				KETERANGAN = '$keterangan',
				JUMLAH = '$jumlah',
				TARIF = '$tarif'
			WHERE ID = '$id'
		";
		$this->db->query($sql);
	}

	function hapus_oksigen($id){
		$sql = "DELETE FROM rk_ri_oksigen WHERE ID = '$id'";
		$this->db->query($sql);
	}

	//INFUS

	function data_infus($id_pelayanan){
		$sql = "SELECT * FROM rk_ri_infus WHERE ID_PELAYANAN = '$id_pelayanan' ORDER BY ID DESC";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_infus_id($id,$id_pelayanan,$id_pasien){
		$sql = "SELECT * FROM rk_ri_infus WHERE ID = '$id' AND ID_PELAYANAN = '$id_pelayanan' AND ID_PASIEN = '$id_pasien'";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function simpan_infus($id_pelayanan,$id_pasien,$kode,$jumlah,$tarif,$total,$pemakaian,$tanggal,$bulan,$tahun){
		$sql = "
			INSERT INTO rk_ri_infus(
				ID_PELAYANAN,
				ID_PASIEN,
				KODE,
				JUMLAH,
				TARIF,
				TOTAL,
				PEMAKAIAN_SELAMA,
				TANGGAL,
				BULAN,
				TAHUN
			) VALUES (
				'$id_pelayanan',
				'$id_pasien',
				'$kode',
				'$jumlah',
				'$tarif',
				'$total',
				'$pemakaian',
				'$tanggal',
				'$bulan',
				'$tahun'
			)
		";
		$this->db->query($sql);
	}

	function ubah_infus($id,$jumlah,$tarif,$total,$pemakaian){
		$sql = "
			UPDATE rk_ri_infus SET
				JUMLAH = '$jumlah',
				TARIF = '$tarif',
				TOTAL = '$total',
				PEMAKAIAN_SELAMA = '$pemakaian'
			WHERE ID = '$id'
		";
		$this->db->query($sql);
	}

	function hapus_infus($id,$id_pelayanan,$id_pasien){
		$sql = "DELETE FROM rk_ri_infus WHERE ID = '$id' AND ID_PELAYANAN = '$id_pelayanan' AND ID_PASIEN = '$id_pasien'";
		$this->db->query($sql);
	}

	//JASA PERAWAT

	function data_jasa($id_pelayanan,$id_pasien){
		$sql = "
			SELECT 
				RI.ID,
				RI.ID_PELAYANAN,
				RI.ID_PASIEN,
				RI.KODE,
				RI.ID_JASA_PERAWAT,
				JS.PERAWATAN,
				JS.TARIF,
				RI.JUMLAH,
				RI.TOTAL,
				RI.PERAWATAN_SELAMA,
				RI.TOTAL_SEMUA
			FROM rk_ri_jasa_perawat RI
			LEFT JOIN admum_jasa_perawat JS ON JS.ID = RI.ID_JASA_PERAWAT
			WHERE RI.ID_PELAYANAN = '$id_pelayanan' 
			AND RI.ID_PASIEN = '$id_pasien'
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_jasa_id($id,$id_pelayanan,$id_pasien){
		$sql = "
			SELECT 
				RI.ID,
				RI.ID_PELAYANAN,
				RI.ID_PASIEN,
				RI.KODE,
				RI.ID_JASA_PERAWAT,
				JS.PERAWATAN,
				JS.TARIF,
				RI.JUMLAH,
				RI.TOTAL,
				RI.PERAWATAN_SELAMA,
				RI.TOTAL_SEMUA
			FROM rk_ri_jasa_perawat RI
			LEFT JOIN admum_jasa_perawat JS ON JS.ID = RI.ID_JASA_PERAWAT
			WHERE RI.ID = '$id'
			AND RI.ID_PELAYANAN = '$id_pelayanan' 
			AND RI.ID_PASIEN = '$id_pasien'
		";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function load_jasa($keyword){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND PERAWATAN LIKE '%$keyword%'";
		}

		$sql = "SELECT * FROM admum_jasa_perawat WHERE $where";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function klik_jasa($id){
		$sql = "SELECT * FROM admum_jasa_perawat WHERE ID = '$id'";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function simpan_jasa($id_pelayanan,$id_pasien,$kode,$id_jasa_perawat,$jumlah,$total,$perawatan_selama,$total_semua,$tanggal,$bulan,$tahun){
		$sql = "
			INSERT INTO rk_ri_jasa_perawat(
				ID_PELAYANAN,
				ID_PASIEN,
				KODE,
				ID_JASA_PERAWAT,
				JUMLAH,
				TOTAL,
				PERAWATAN_SELAMA,
				TOTAL_SEMUA,
				TANGGAL,
				BULAN,
				TAHUN
			) VALUES (
				'$id_pelayanan',
				'$id_pasien',
				'$kode',
				'$id_jasa_perawat',
				'$jumlah',
				'$total',
				'$perawatan_selama',
				'$total_semua',
				'$tanggal',
				'$bulan',
				'$tahun'
			)
		";
		$this->db->query($sql);
	}

	function hapus_jasa($id,$id_pelayanan,$id_pasien){
		$sql = "DELETE FROM rk_ri_jasa_perawat WHERE ID = '$id' AND ID_PELAYANAN = '$id_pelayanan' AND ID_PASIEN = '$id_pasien'";
		$this->db->query($sql);
	}

	// DIAGNOSA

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
			FROM rk_ri_diagnosa DG
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
			FROM rk_ri_diagnosa DG
			LEFT JOIN admum_setup_kasus_diagnosa KA ON KA.ID = DG.ID_KASUS
			LEFT JOIN admum_setup_spesialistik SP ON SP.ID = DG.ID_SPESIALISTIK
			WHERE DG.ID = '$id'
			AND DG.ID_PELAYANAN = '$id_pelayanan'
		";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function simpan_diagnosa($id_pelayanan,$id_pasien,$tanggal,$bulan,$tahun,$diagnosa,$tindakan,$kasus,$spesialistik){
		$sql = "
			INSERT INTO rk_ri_diagnosa(
				ID_PELAYANAN,
				ID_PASIEN,
				TANGGAL,
				BULAN,
				TAHUN,
				DIAGNOSA,
				TINDAKAN,
				ID_KASUS,
				ID_SPESIALISTIK
			) VALUES (
				'$id_pelayanan',
				'$id_pasien',
				'$tanggal',
				'$bulan',
				'$tahun',
				'$diagnosa',
				'$tindakan',
				'$kasus',
				'$spesialistik'
			)
		";
		$this->db->query($sql);
	}

	function ubah_diagnosa($id,$diagnosa,$tindakan,$kasus,$spesialistik){
		$sql = "
			UPDATE rk_ri_diagnosa SET
				DIAGNOSA = '$diagnosa',
				TINDAKAN = '$tindakan',
				ID_KASUS = '$kasus',
				ID_SPESIALISTIK = '$spesialistik'
			WHERE ID = '$id'
		";
		$this->db->query($sql);
	}

	function hapus_diagnosa($id,$id_pelayanan){
		$sql = "DELETE FROM rk_ri_diagnosa WHERE ID = '$id' AND ID_PELAYANAN = '$id_pelayanan'";
		$this->db->query($sql);
	}

	//RESEP

	function load_obat($keyword){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND (NM_OBT.BARCODE LIKE '%$keyword%' OR NM_OBT.NAMA_OBAT LIKE '%$keyword%')";
		}

		$sql = "
			SELECT 
				OBAT.ID,
				NM_OBT.KODE_OBAT,
				NM_OBT.BARCODE,
				NM_OBT.NAMA_OBAT,
				OBAT.JUMLAH,
				OBAT.ISI,
				OBAT.TOTAL,
				OBAT.SATUAN_ISI,
				OBAT.JUMLAH_BUTIR,
				OBAT.SATUAN_BUTIR,
				OBAT.HARGA_BELI,
				OBAT.HARGA_JUAL,
				OBAT.KADALUARSA,
				OBAT.TANGGAL_MASUK,
				OBAT.WAKTU_MASUK,
				OBAT.AKTIF,
				OBAT.URUT_BARANG,
				OBAT.STATUS_RACIK,
				OBAT.GAMBAR
			FROM apotek_gudang_obat OBAT
			LEFT JOIN admum_setup_nama_obat NM_OBT ON NM_OBT.ID = OBAT.ID_SETUP_NAMA_OBAT
			WHERE $where
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function klik_obat($id){
		$sql = "
			SELECT 
				OBAT.ID,
				NM_OBT.KODE_OBAT,
				NM_OBT.BARCODE,
				NM_OBT.NAMA_OBAT,
				OBAT.JUMLAH,
				OBAT.ISI,
				OBAT.TOTAL,
				OBAT.SATUAN_ISI,
				OBAT.JUMLAH_BUTIR,
				OBAT.SATUAN_BUTIR,
				OBAT.HARGA_BELI,
				OBAT.HARGA_JUAL,
				OBAT.KADALUARSA,
				OBAT.TANGGAL_MASUK,
				OBAT.WAKTU_MASUK,
				OBAT.AKTIF,
				OBAT.URUT_BARANG,
				OBAT.STATUS_RACIK,
				OBAT.GAMBAR
			FROM apotek_gudang_obat OBAT
			LEFT JOIN admum_setup_nama_obat NM_OBT ON NM_OBT.ID = OBAT.ID_SETUP_NAMA_OBAT
			WHERE OBAT.ID = '$id'
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_resep($id_pelayanan){
		$sql = "SELECT * FROM rk_ri_resep WHERE ID_PELAYANAN = '$id_pelayanan'";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_resep_id($id){
		$sql = "SELECT * FROM rk_ri_resep WHERE ID = '$id'";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function data_resep_det($id_resep){
		$sql = "
			SELECT
				DET.ID,
				NM_OBT.KODE_OBAT,
				NM_OBT.NAMA_OBAT,
				DET.TAKARAN,
				DET.ATURAN_MINUM
			FROM rk_ri_resep_detail DET
			LEFT JOIN apotek_gudang_obat GD ON GD.ID = DET.ID_OBAT
			LEFT JOIN admum_setup_nama_obat NM_OBT ON NM_OBT.ID = GD.ID_SETUP_NAMA_OBAT
			WHERE DET.ID_RESEP = '$id_resep'
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function simpan_resep($id_pelayanan,$id_pasien,$kode_resep,$diminum_selama,$tanggal,$bulan,$tahun){
		$sql = "
			INSERT INTO rk_ri_resep(
				ID_PELAYANAN,
				ID_PASIEN,
				KODE_RESEP,
				DIMINUM_SELAMA,
				TANGGAL,
				BULAN,
				TAHUN
			) VALUES (
				'$id_pelayanan',
				'$id_pasien',
				'$kode_resep',
				'$diminum_selama',
				'$tanggal',
				'$bulan',
				'$tahun'
			)
		";
		$this->db->query($sql);
	}

	function simpan_resep_det($id_resep,$id_obat,$harga,$jumlah_beli,$subtotal,$takaran,$aturan_umum){
		$sql = "
			INSERT INTO rk_ri_resep_detail(
				ID_RESEP,
				ID_OBAT,
				HARGA,
				JUMLAH_BELI,
				SUBTOTAL,
				TAKARAN,
				ATURAN_MINUM
			) VALUES (
				'$id_resep',
				'$id_obat',
				'$harga',
				'$jumlah_beli',
				'$subtotal',
				'$takaran',
				'$aturan_umum'
			)
		";
		$this->db->query($sql);
	}

	function hapus_resep($id,$id_pelayanan){
		$this->db->query("DELETE FROM rk_ri_resep WHERE ID = '$id' AND ID_PELAYANAN = '$id_pelayanan'");
	}

	function hapus_det_resep($id_resep){
		$this->db->query("DELETE FROM rk_ri_resep_detail WHERE ID_RESEP = '$id_resep'");
	}

	//KONDISI AKHIR

	function simpan_ka($id_pelayanan,$id_pasien,$tanggal,$bulan,$tahun,$dirawat,$kondisi_akhir){
		$sql = "
			INSERT INTO rk_ri_kondisi_akhir(
				ID_PELAYANAN,
				ID_PASIEN,
				TANGGAL,
				BULAN,
				TAHUN,
				DIRAWAT_SELAMA,
				KONDISI_AKHIR
			) VALUES (
				'$id_pelayanan',
				'$id_pasien',
				'$tanggal',
				'$bulan',
				'$tahun',
				'$dirawat',
				'$kondisi_akhir'
			)
		";
		$this->db->query($sql);
	}

	//ICU

	function load_ruang_icu($keyword){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND NAMA_RUANG LIKE '%$keyword'";
		}

		$sql = "SELECT * FROM admum_setup_ruang_icu WHERE $where";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function klik_ruang_icu($id){
		$sql = "SELECT * FROM admum_setup_ruang_icu WHERE ID = '$id'";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function simpan_icu($id_pelayanan,$id_pasien,$id_ruang_icu,$tarif,$tanggal,$bulan,$tahun){
		$sql = "
			INSERT INTO rk_ri_icu(
				ID_PELAYANAN,
				ID_PASIEN,
				ID_RUANG_ICU,
				TARIF,
				TANGGAL,
				BULAN,
				TAHUN
			) VALUES (
				'$id_pelayanan',
				'$id_pasien',
				'$id_ruang_icu',
				'$tarif',
				'$tanggal',
				'$bulan',
				'$tahun'
			)
		";
		$this->db->query($sql); 
	}

	function load_ruang_operasi($keyword){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND NAMA_RUANG LIKE '%$keyword'";
		}

		$sql = "SELECT * FROM admum_setup_ruang_operasi WHERE $where";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function klik_ruang_operasi($id){
		$sql = "SELECT * FROM admum_setup_ruang_operasi WHERE ID = '$id'";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function simpan_operasi($id_pelayanan,$id_pasien,$id_ruang_operasi,$tarif,$tanggal,$bulan,$tahun){
		$sql = "
			INSERT INTO rk_ri_operasi(
				ID_PELAYANAN,
				ID_PASIEN,
				ID_RUANG_OPERASI,
				TARIF,
				TANGGAL,
				BULAN,
				TAHUN
			) VALUES (
				'$id_pelayanan',
				'$id_pasien',
				'$id_ruang_operasi',
				'$tarif',
				'$tanggal',
				'$bulan',
				'$tahun'
			)
		";
		$this->db->query($sql);
	}

	//MENINGGAL

	function load_kamar_jenazah($keyword){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND (NAMA_KAMAR LIKE '%$keyword' OR KODE_KAMAR LIKE '%$keyword%')";
		}

		$sql = "SELECT * FROM admum_kamar_jenazah WHERE $where";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function klik_kamar_jenazah($id){
		$sql = "SELECT * FROM admum_kamar_jenazah WHERE ID = '$id'";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function load_lemari_jenazah($id_kamar){
		$sql = "SELECT * FROM admum_lemari_jenazah WHERE ID_KAMAR_JENAZAH = '$id_kamar' AND STATUS_HAPUS = '0'";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function klik_lemari_jenazah($id){
		$sql = "SELECT * FROM admum_lemari_jenazah WHERE ID = '$id' AND STATUS_HAPUS = '0'";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function simpan_meninggal($id_pelayanan,$id_pasien,$id_kamar_jenazah,$id_lemari_jenazah,$tanggal,$bulan,$tahun){
		$sql = "
			INSERT INTO rk_ri_meninggal(
				ID_PELAYANAN,
				ID_PASIEN,
				ID_KAMAR_JENAZAH,
				ID_LEMARI_JENAZAH,
				TANGGAL,
				BULAN,
				TAHUN
			) VALUES (
				'$id_pelayanan',
				'$id_pasien',
				'$id_kamar_jenazah',
				'$id_lemari_jenazah',
				'$tanggal',
				'$bulan',
				'$tahun'
			)
		";
		$this->db->query($sql);
	}

	// SURAT DOKTER

	function data_surat_dokter($id_pelayanan){
		$sql = "
			SELECT
				SD.ID,
				SD.ID_PELAYANAN,
				SD.TANGGAL,
				SD.ID_PASIEN,
				PSN.NAMA,
				PSN.JENIS_KELAMIN,
				PSN.UMUR
			FROM rk_ri_surat_dokter SD
			LEFT JOIN rk_pasien PSN ON PSN.ID = SD.ID_PASIEN
			WHERE SD.ID_PELAYANAN = '$id_pelayanan'
			ORDER BY SD.ID DESC
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_surat_dokter_id($id){
		$sql = "
			SELECT
				SD.ID,
				SD.ID_PELAYANAN,
				SD.TANGGAL,
				SD.ID_PASIEN,
				PSN.NAMA,
				PSN.JENIS_KELAMIN,
				PSN.UMUR,
				PSN.PEKERJAAN,
				PSN.ALAMAT,
				SD.WAKTU_ISTIRAHAT,
				SD.MULAI_TANGGAL,
				SD.SAMPAI_TANGGAL
			FROM rk_ri_surat_dokter SD
			LEFT JOIN rk_pasien PSN ON PSN.ID = SD.ID_PASIEN
			WHERE SD.ID = '$id'
		";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function simpan_surat_dokter($id_pelayanan,$id_pasien,$tanggal,$bulan,$tahun,$waktu_istirahat,$mulai_tanggal,$sampai_tanggal){
		$sql = "
			INSERT INTO rk_ri_surat_dokter(
				ID_PELAYANAN,
				ID_PASIEN,
				TANGGAL,
				BULAN,
				TAHUN,
				WAKTU_ISTIRAHAT,
				MULAI_TANGGAL,
				SAMPAI_TANGGAL
			) VALUES (
				'$id_pelayanan',
				'$id_pasien',
				'$tanggal',
				'$bulan',
				'$tahun',
				'$waktu_istirahat',
				'$mulai_tanggal',
				'$sampai_tanggal'
			)
		";
		$this->db->query($sql);
	}

	function hapus_surat_dokter($id){
		$sql = "DELETE FROM rk_ri_surat_dokter WHERE ID = '$id'";
		$this->db->query($sql);
	}

}
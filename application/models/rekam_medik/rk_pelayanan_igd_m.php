<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rk_pelayanan_igd_m extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function data_pasien_belum($keyword){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND PASIEN.NAMA LIKE '%$keyword%'";
		}

		$sql = "
			SELECT
				IGD.ID,
				IGD.ID_PASIEN,
				PASIEN.KODE_PASIEN,
				PASIEN.NAMA AS NAMA_PASIEN,
				IGD.TANGGAL,
				IGD.ASAL_RUJUKAN,
				IGD.SISTEM_BAYAR
			FROM admum_igd IGD
			LEFT JOIN rk_pasien PASIEN ON PASIEN.ID = IGD.ID_PASIEN
			WHERE $where
			AND IGD.STATUS_SUDAH = '0'
			ORDER BY IGD.ID DESC
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_pasien_sudah($keyword){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND PASIEN.NAMA LIKE '%$keyword%'";
		}

		$sql = "
			SELECT
				IGD.ID,
				IGD.ID_PASIEN,
				PASIEN.KODE_PASIEN,
				PASIEN.NAMA AS NAMA_PASIEN,
				IGD.TANGGAL,
				IGD.ASAL_RUJUKAN,
				IGD.SISTEM_BAYAR,
				KA.KONDISI_AKHIR
			FROM admum_igd IGD
			LEFT JOIN rk_pasien PASIEN ON PASIEN.ID = IGD.ID_PASIEN
			LEFT JOIN rk_igd_kondisi_akhir KA ON KA.ID_PELAYANAN = IGD.ID
			WHERE $where
			AND IGD.STATUS_SUDAH = '1'
			ORDER BY IGD.ID DESC
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_igd_id($id){
		$sql = "
			SELECT
				IGD.ID,
				LAB.KODE_LAB,
				IGD.ID_PASIEN,
				PASIEN.KODE_PASIEN,
				PASIEN.NAMA AS NAMA_PASIEN,
				PASIEN.JENIS_KELAMIN,
				PASIEN.UMUR,
				PASIEN.AGAMA,
				IGD.TANGGAL,
				IGD.ASAL_RUJUKAN,
				IGD.SISTEM_BAYAR
			FROM admum_igd IGD
			LEFT JOIN rk_pasien PASIEN ON PASIEN.ID = IGD.ID_PASIEN
			LEFT JOIN rk_igd_laborat LAB ON LAB.ID_PELAYANAN = IGD.ID
			WHERE IGD.ID = '$id'
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

	function data_tindakan($id_pelayanan){
		$sql = "
			SELECT
				TDK.ID,
				TDK.ID_PELAYANAN,
				TDK.ID_PASIEN,
				TDK.TANGGAL,
				DET.ID AS ID_DET,
				DET.WAKTU,
				STDK.KODE,
				STDK.NAMA_TINDAKAN,
				STDK.TARIF,
				DET.JUMLAH,
				DET.SUBTOTAL
			FROM rk_igd_tindakan TDK
			LEFT JOIN rk_igd_tindakan_detail DET ON DET.ID_TINDAKAN_IGD = TDK.ID
			LEFT JOIN admum_setup_tindakan STDK ON STDK.ID = DET.TINDAKAN
			WHERE TDK.ID_PELAYANAN = '$id_pelayanan'
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_tindakan_id($id,$id_pelayanan){
		$sql = "
			SELECT
				TDK.ID,
				TDK.ID_PELAYANAN,
				TDK.ID_PASIEN,
				TDK.TANGGAL,
				DET.WAKTU,
				STDK.KODE,
				STDK.NAMA_TINDAKAN,
				STDK.TARIF,
				DET.JUMLAH,
				DET.SUBTOTAL
			FROM rk_igd_tindakan TDK
			LEFT JOIN rk_igd_tindakan_detail DET ON DET.ID_TINDAKAN_IGD = TDK.ID
			LEFT JOIN admum_setup_tindakan STDK ON STDK.ID = DET.TINDAKAN
			WHERE DET.ID = '$id'
			AND TDK.ID_PELAYANAN = '$id_pelayanan'
		";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function simpan_tindakan($id_pelayanan,$id_pasien,$tanggal,$bulan,$tahun,$waktu,$total){
		$sql = "
			INSERT INTO rk_igd_tindakan(
				ID_PELAYANAN,
				ID_PASIEN,
				TANGGAL,
				BULAN,
				TAHUN,
				WAKTU,
				TOTAL
			) VALUES(
				'$id_pelayanan',
				'$id_pasien',
				'$tanggal',
				'$bulan',
				'$tahun',
				'$waktu',
				'$total'
			)
		";
		$this->db->query($sql);
	}

	function simpan_det_tindakan($id_tindakan_rj,$tindakan,$tanggal,$bulan,$tahun,$jumlah,$subtotal,$waktu){
		$sql = "
			INSERT INTO rk_igd_tindakan_detail(
				ID_TINDAKAN_IGD,
				TINDAKAN,
				TANGGAL,
				BULAN,
				TAHUN,
				JUMLAH,
				SUBTOTAL,
				WAKTU
			) VALUES (
				'$id_tindakan_rj',
				'$tindakan',
				'$tanggal',
				'$bulan',
				'$tahun',
				'$jumlah',
				'$subtotal',
				'$waktu'
			)
		";
		$this->db->query($sql);
	}

	function ubah_tindakan($id,$tindakan,$jumlah,$subtotal,$waktu){
		$sql = "
			UPDATE rk_igd_tindakan_detail SET
				TINDAKAN = '$tindakan',
				JUMLAH = '$jumlah',
				SUBTOTAL = '$subtotal',
				WAKTU = '$waktu'
			WHERE ID = '$id'
		";
		$this->db->query($sql);
	}

	function hapus_tindakan($id){
		$sql = "DELETE FROM rk_igd_tindakan_detail WHERE ID = '$id'";
		$this->db->query($sql);
	}

	// LABORAT

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

	function simpan_laborat($kode_lab,$id_pelayanan,$id_pasien,$jenis_laborat,$total_tarif,$cito,$tanggal,$bulan,$tahun,$waktu){
		$sql = "
			INSERT INTO rk_igd_laborat(
				KODE_LAB,
				ID_PELAYANAN,
				ID_PASIEN,
				JENIS_LABORAT,
				TOTAL_TARIF,
				CITO,
				TANGGAL,
				BULAN,
				TAHUN,
				WAKTU
			) VALUES (
				'$kode_lab',
				'$id_pelayanan',
				'$id_pasien',
				'$jenis_laborat',
				'$total_tarif',
				'$cito',
				'$tanggal',
				'$bulan',
				'$tahun',
				'$waktu'
			)
		";
		$this->db->query($sql);
	}

	function simpan_det_laborat($id_pemeriksaan_igd,$pemeriksaan,$hasil,$nilai_rujukan,$tanggal,$bulan,$tahun,$subtotal,$waktu){
		$sql = "
			INSERT INTO rk_igd_laborat_detail(
				ID_PEMERIKSAAN_IGD,
				PEMERIKSAAN,
				HASIL,
				NILAI_RUJUKAN,
				TANGGAL,
				BULAN,
				TAHUN,
				SUBTOTAL,
				WAKTU
			) VALUES (
				'$id_pemeriksaan_igd',
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

	function data_laborat($id_pelayanan){
		$sql = "
			SELECT
				LAB.ID,
				SET_LAB.JENIS_LABORAT,
				LAB.CITO,
				LAB.TOTAL_TARIF,
				LAB.TANGGAL,
				LAB.BULAN,
				LAB.TAHUN,
				LAB.WAKTU
			FROM rk_igd_laborat LAB
			LEFT JOIN admum_setup_jenis_laborat SET_LAB ON SET_LAB.ID = LAB.JENIS_LABORAT
			WHERE LAB.ID_PELAYANAN = '$id_pelayanan'
			ORDER BY LAB.ID DESC
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_laborat_id($id){
		$sql = "
			SELECT
				LAB.ID,
				LAB.KODE_LAB,
				SET_LAB.JENIS_LABORAT,
				LAB.CITO,
				LAB.TOTAL_TARIF,
				LAB.TANGGAL,
				LAB.BULAN,
				LAB.TAHUN
			FROM rk_igd_laborat LAB
			LEFT JOIN admum_setup_jenis_laborat SET_LAB ON SET_LAB.ID = LAB.JENIS_LABORAT
			WHERE LAB.ID = '$id'
		";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function data_hasil_pemeriksaan($id_pemeriksaan){
		$sql = "
			SELECT
				DET.ID,
				PRK.KODE,
				PRK.NAMA_PEMERIKSAAN,
				DET.HASIL,
				DET.NILAI_RUJUKAN,
				PRK.TARIF,
				DET.SUBTOTAL,
				DET.TANGGAL,
				DET.BULAN,
				DET.TAHUN
			FROM rk_igd_laborat_detail DET
			LEFT JOIN admum_setup_pemeriksaan PRK ON PRK.ID = DET.PEMERIKSAAN
			WHERE DET.ID_PEMERIKSAAN_IGD = '$id_pemeriksaan'
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function hapus_laborat($id){
		$sql = "DELETE FROM rk_igd_laborat WHERE ID = '$id'";
		$this->db->query($sql);
	}

	function hapus_laborat_detail($id){
		$sql = "DELETE FROM rk_igd_laborat_detail WHERE ID_PEMERIKSAAN_RJ = '$id'";
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
			FROM rk_igd_diagnosa DG
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
			FROM rk_igd_diagnosa DG
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
			INSERT INTO rk_igd_diagnosa(
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
			UPDATE rk_igd_diagnosa SET
				DIAGNOSA = '$diagnosa',
				TINDAKAN = '$tindakan',
				ID_KASUS = '$kasus',
				ID_SPESIALISTIK = '$spesialistik'
			WHERE ID = '$id'
		";
		$this->db->query($sql);
	}

	function hapus_diagnosa($id,$id_pelayanan){
		$sql = "DELETE FROM rk_igd_diagnosa WHERE ID = '$id' AND ID_PELAYANAN = '$id_pelayanan'";
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
		$sql = "SELECT * FROM rk_igd_resep WHERE ID_PELAYANAN = '$id_pelayanan'";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_resep_id($id){
		$sql = "SELECT * FROM rk_igd_resep WHERE ID = '$id'";
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
			FROM rk_igd_resep_detail DET
			LEFT JOIN apotek_gudang_obat GD ON GD.ID = DET.ID_OBAT
			LEFT JOIN admum_setup_nama_obat NM_OBT ON NM_OBT.ID = GD.ID_SETUP_NAMA_OBAT
			WHERE DET.ID_RESEP = '$id_resep'
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function simpan_resep($id_pelayanan,$id_pasien,$kode_resep,$tanggal,$bulan,$tahun){
		$sql = "
			INSERT INTO rk_igd_resep(
				ID_PELAYANAN,
				ID_PASIEN,
				KODE_RESEP,
				TANGGAL,
				BULAN,
				TAHUN
			) VALUES (
				'$id_pelayanan',
				'$id_pasien',
				'$kode_resep',
				'$tanggal',
				'$bulan',
				'$tahun'
			)
		";
		$this->db->query($sql);
	}

	function simpan_resep_det($id_resep,$id_obat,$takaran,$aturan_umum){
		$sql = "
			INSERT INTO rk_igd_resep_detail(
				ID_RESEP,
				ID_OBAT,
				TAKARAN,
				ATURAN_MINUM
			) VALUES (
				'$id_resep',
				'$id_obat',
				'$takaran',
				'$aturan_umum'
			)
		";
		$this->db->query($sql);
	}

	function hapus_resep($id,$id_pelayanan){
		$this->db->query("DELETE FROM rk_igd_resep WHERE ID = '$id' AND ID_PELAYANAN = '$id_pelayanan'");
	}

	function hapus_det_resep($id_resep){
		$this->db->query("DELETE FROM rk_igd_resep_detail WHERE ID_RESEP = '$id_resep'");
	}

	// KONDISI AKHIR

	function load_ruangan($keyword,$kelas){
		$where = "1 = 1";

		if($kelas == 'Semua'){
			$where = $where;
		}else{
			$where = $where." AND KELAS = '$kelas'";
		}

		if($keyword != ""){
			$where = $where." AND (NAMA_KAMAR LIKE '%$keyword%' OR KODE_KAMAR LIKE '%$keyword%')";
		}else{
			$where = $where;
		}

		$sql = "SELECT * FROM admum_kamar_rawat_inap WHERE $where";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function klik_ruangan($id){
		$sql = "SELECT * FROM admum_kamar_rawat_inap WHERE ID = '$id'";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function load_bed($keyword,$id_kamar){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND NOMOR_BED LIKE '%$keyword%'";
		}else{
			$where = $where;
		}

		$sql = "SELECT * FROM admum_bed_rawat_inap WHERE $where AND ID_KAMAR_RAWAT_INAP = '$id_kamar'";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function klik_bed($id){
		$sql = "SELECT * FROM admum_bed_rawat_inap WHERE ID = '$id'";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function simpan_kondisi($id_pelayanan,$id_pasien,$tanggal,$bulan,$tahun,$kondisi_akhir){
		$sql = "
			INSERT INTO rk_igd_kondisi_akhir(
				ID_PELAYANAN,
				ID_PASIEN,
				TANGGAL,
				BULAN,
				TAHUN,
				KONDISI_AKHIR
			) VALUES (
				'$id_pelayanan',
				'$id_pasien',
				'$tanggal',
				'$bulan',
				'$tahun',
				'$kondisi_akhir'
			)
		";
		$this->db->query($sql);
	}

	function simpan_rawat_inap($id_pasien,$tanggal_masuk,$bulan,$tahun,$asal_rujukan,$nama_penanggungjawab,$telepon,$sistem_bayar,$kelas,$id_kamar,$id_bed){
		$sql = "
			INSERT INTO admum_rawat_inap(
				ID_PASIEN,
				TANGGAL_MASUK,
				BULAN,
				TAHUN,
				ASAL_RUJUKAN,
				NAMA_PENANGGUNGJAWAB,
				TELEPON,
				SISTEM_BAYAR,
				KELAS,
				ID_KAMAR,
				ID_BED
			) VALUES (
				'$id_pasien',
				'$tanggal_masuk',
				'$bulan',
				'$tahun',
				'$asal_rujukan',
				'$nama_penanggungjawab',
				'$telepon',
				'$sistem_bayar',
				'$kelas',
				'$id_kamar',
				'$id_bed'
			)
		";
		$this->db->query($sql);
	}

	//OPERASI

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
			INSERT INTO rk_igd_operasi(
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
			INSERT INTO rk_igd_icu(
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
			INSERT INTO rk_igd_meninggal(
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

}
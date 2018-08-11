<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rk_pelayanan_rj_m extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function data_rawat_jalan($keyword){
		$now = date('d-m-Y');
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
			AND RJ.STATUS_SUDAH = '0'
			AND RJ.TANGGAL = '$now'
			ORDER BY RJ.ID DESC
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
				PASIEN.KECAMATAN,
				PASIEN.KELURAHAN,
				PASIEN.KOTA,
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

	function simpan($id_pelayanan,$id_poli,$id_peg_dokter,$id_pasien,$tanggal,$bulan,$tahun,$waktu,$total){
		$sql = "
			INSERT INTO rk_tindakan_rj(
				ID_PELAYANAN,
				ID_POLI,
				ID_PEG_DOKTER,
				ID_PASIEN,
				TANGGAL,
				BULAN,
				TAHUN,
				WAKTU,
				TOTAL
			) VALUES(
				'$id_pelayanan',
				'$id_poli',
				'$id_peg_dokter',
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

	function simpan_detail($id_tindakan_rj,$tindakan,$tanggal,$bulan,$tahun,$jumlah,$subtotal,$waktu){
		$sql = "
			INSERT INTO rk_tindakan_rj_detail(
				ID_TINDAKAN_RJ,
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

	function hapus_tindakan($id){
		$sql = "DELETE FROM rk_tindakan_rj_detail WHERE ID = '$id'";
		$this->db->query($sql);
	}

	// DIAGNOSA

	function data_penyakit($keyword){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND URAIAN LIKE '%$keyword%'";
		}

		$sql = "SELECT * FROM admum_jenis_penyakit WHERE $where";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_penyakit_id($id){
		$sql = "SELECT * FROM admum_jenis_penyakit WHERE ID = '$id'";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function simpan_diagnosa($id_pelayanan,$id_poli,$id_peg_dokter,$id_pasien,$tanggal,$bulan,$tahun,$diagnosa,$tindakan,$id_penyakit){
		$sql = "
			INSERT INTO rk_diagnosa_rj(
				ID_PELAYANAN,
				ID_POLI,
				ID_PEG_DOKTER,
				ID_PASIEN,
				TANGGAL,
				BULAN,
				TAHUN,
				DIAGNOSA,
				TINDAKAN,
				ID_PENYAKIT
			) VALUES (
				'$id_pelayanan',
				'$id_poli',
				'$id_peg_dokter',
				'$id_pasien',
				'$tanggal',
				'$bulan',
				'$tahun',
				'$diagnosa',
				'$tindakan',
				'$id_penyakit'
			)
		";
		$this->db->query($sql);
	}

	function data_diagnosa($id_pelayanan){
		$sql = "
			SELECT
				DG.ID,
				DG.TANGGAL,
				DG.DIAGNOSA,
				DG.TINDAKAN,
				PY.URAIAN
			FROM rk_diagnosa_rj DG
			LEFT JOIN admum_jenis_penyakit PY ON PY.ID = DG.ID_PENYAKIT
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
				DG.ID_PENYAKIT,
				PY.URAIAN
			FROM rk_diagnosa_rj DG
			LEFT JOIN admum_jenis_penyakit PY ON PY.ID = DG.ID_PENYAKIT
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

	function hapus_diagnosa($id,$id_pelayanan){
		$sql = "DELETE FROM rk_diagnosa_rj WHERE ID = '$id' AND ID_PELAYANAN = '$id_pelayanan'";
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
		$sql = "SELECT * FROM rk_resep_rj WHERE ID_PELAYANAN = '$id_pelayanan'";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_resep_id($id){
		$sql = "SELECT * FROM rk_resep_rj WHERE ID = '$id'";
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
				DET.ATURAN_MINUM,
				DET.HARGA
			FROM rk_resep_detail_rj DET
			LEFT JOIN apotek_gudang_obat GD ON GD.ID = DET.ID_OBAT
			LEFT JOIN admum_setup_nama_obat NM_OBT ON NM_OBT.ID = GD.ID_SETUP_NAMA_OBAT
			WHERE DET.ID_RESEP = '$id_resep'
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function simpan_resep($id_pelayanan,$id_poli,$id_peg_dokter,$id_pasien,$alergi,$kode_resep,$diminum_selama,$tanggal,$bulan,$tahun,$total){
		$sql = "
			INSERT INTO rk_resep_rj(
				ID_PELAYANAN,
				ID_POLI,
				ID_PEG_DOKTER,
				ID_PASIEN,
				ALERGI_OBAT,
				KODE_RESEP,
				DIMINUM_SELAMA,
				TANGGAL,
				BULAN,
				TAHUN,
				TOTAL
			) VALUES (
				'$id_pelayanan',
				'$id_poli',
				'$id_peg_dokter',
				'$id_pasien',
				'$alergi',
				'$kode_resep',
				'$diminum_selama',
				'$tanggal',
				'$bulan',
				'$tahun',
				'$total'
			)
		";
		$this->db->query($sql);
	}

	function simpan_resep_det($id_resep,$id_obat,$harga,$jumlah,$subtotal,$takaran,$aturan_umum){
		$sql = "
			INSERT INTO rk_resep_detail_rj(
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
				'$jumlah',
				'$subtotal',
				'$takaran',
				'$aturan_umum'
			)
		";
		$this->db->query($sql);
	}

	function ubah_stok_obat($id,$jumlah){
		$sql = "UPDATE apotek_gudang_obat SET TOTAL = TOTAL - $jumlah WHERE ID = '$id'";
		$this->db->query($sql);
	}

	function hapus_resep($id,$id_pelayanan){
		$this->db->query("DELETE FROM rk_resep_rj WHERE ID = '$id' AND ID_PELAYANAN = '$id_pelayanan'");
	}

	function hapus_det_resep($id_resep){
		$this->db->query("DELETE FROM rk_resep_detail_rj WHERE ID_RESEP = '$id_resep'");
	}

	//KONDISI AKHIR

	function cek_kondisi_akhir($id_pelayanan,$id_poli,$id_pasien,$tanggal){
		$sql = "
			SELECT * FROM rk_kondisi_akhir_rj 
			WHERE ID_PELAYANAN = '$id_pelayanan' 
			AND ID_POLI = '$id_poli' 
			AND ID_PASIEN = '$id_pasien'
			AND TANGGAL = '$tanggal'
		";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function simpan_kondisi($id_pelayanan,$id_poli,$id_peg_dokter,$id_pasien,$tanggal,$bulan,$tahun,$kondisi_akhir){
		$sql = "
			INSERT INTO rk_kondisi_akhir_rj(
				ID_PELAYANAN,
				ID_POLI,
				ID_PEG_DOKTER,
				ID_PASIEN,
				TANGGAL,
				BULAN,
				TAHUN,
				KONDISI_AKHIR
			) VALUES (
				'$id_pelayanan',
				'$id_poli',
				'$id_peg_dokter',
				'$id_pasien',
				'$tanggal',
				'$bulan',
				'$tahun',
				'$kondisi_akhir'
			)
		";
		$this->db->query($sql);
	}

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

	function simpan_icu($id_pelayanan,$id_poli,$id_peg_dokter,$id_pasien,$id_ruang_icu,$tarif,$tanggal,$bulan,$tahun){
		$sql = "
			INSERT INTO rk_icu_rj(
				ID_PELAYANAN,
				ID_POLI,
				ID_PEG_DOKTER,
				ID_PASIEN,
				ID_RUANG_ICU,
				TARIF,
				TANGGAL,
				BULAN,
				TAHUN
			) VALUES (
				'$id_pelayanan',
				'$id_poli',
				'$id_peg_dokter',
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

	function simpan_operasi($id_pelayanan,$id_poli,$id_peg_dokter,$id_pasien,$id_ruang_operasi,$tarif,$tanggal,$bulan,$tahun){
		$sql = "
			INSERT INTO rk_operasi_rj(
				ID_PELAYANAN,
				ID_POLI,
				ID_PEG_DOKTER,
				ID_PASIEN,
				ID_RUANG_OPERASI,
				TARIF,
				TANGGAL,
				BULAN,
				TAHUN
			) VALUES (
				'$id_pelayanan',
				'$id_poli',
				'$id_peg_dokter',
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

	function simpan_meninggal($id_pelayanan,$id_poli,$id_peg_dokter,$id_pasien,$id_kamar_jenazah,$id_lemari_jenazah,$tanggal,$bulan,$tahun){
		$sql = "
			INSERT INTO rk_meninggal_rj(
				ID_PELAYANAN,
				ID_POLI,
				ID_PEG_DOKTER,
				ID_PASIEN,
				ID_KAMAR_JENAZAH,
				ID_LEMARI_JENAZAH,
				TANGGAL,
				BULAN,
				TAHUN
			) VALUES (
				'$id_pelayanan',
				'$id_poli',
				'$id_peg_dokter',
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
			FROM rk_surat_dokter_rj SD
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
				PSN.UMUR_BULAN,
				PSN.PEKERJAAN,
				PSN.ALAMAT,
				SD.WAKTU_ISTIRAHAT,
				SD.MULAI_TANGGAL,
				SD.SAMPAI_TANGGAL,
				SD.ID_PEG_DOKTER,
				PEG.NAMA AS NAMA_PEGAWAI,
				PEG.NIP
			FROM rk_surat_dokter_rj SD
			LEFT JOIN rk_pasien PSN ON PSN.ID = SD.ID_PASIEN
			LEFT JOIN kepeg_pegawai PEG ON PEG.ID = SD.ID_PEG_DOKTER
			WHERE SD.ID_PASIEN = '$id'
		";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function simpan_surat_dokter($id_pelayanan,$id_poli,$id_peg_dokter,$id_pasien,$tanggal,$bulan,$tahun,$waktu_istirahat,$mulai_tanggal,$sampai_tanggal){
		$sql = "
			INSERT INTO rk_surat_dokter_rj(
				ID_PELAYANAN,
				ID_POLI,
				ID_PEG_DOKTER,
				ID_PASIEN,
				TANGGAL,
				BULAN,
				TAHUN,
				WAKTU_ISTIRAHAT,
				MULAI_TANGGAL,
				SAMPAI_TANGGAL
			) VALUES (
				'$id_pelayanan',
				'$id_poli',
				'$id_peg_dokter',
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
		$sql = "DELETE FROM rk_surat_dokter_rj WHERE ID = '$id'";
		$this->db->query($sql);
	}

	//PASIEN SUDAH

	function data_pasien_sudah($keyword){
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

	function update_ke_lab($id_pasien){
		$sql = "UPDATE rk_pasien SET STS_POSISI = '2' WHERE ID = '$id_pasien'";
		$this->db->query($sql);
	}

}
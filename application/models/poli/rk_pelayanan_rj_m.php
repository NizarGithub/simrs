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
				PASIEN.TANGGAL_LAHIR,
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
				RJ.TANGGAL AS TANGGAL_MASUK,
				RJ.SISTEM_BAYAR,
				AP.KODE_ANTRIAN,
				AP.NOMOR_ANTRIAN
			FROM admum_rawat_jalan RJ
			LEFT JOIN rk_pasien PASIEN ON RJ.ID_PASIEN = PASIEN.ID
			LEFT JOIN admum_poli POLI ON RJ.ID_POLI = POLI.ID
			LEFT JOIN kepeg_pegawai PEG ON PEG.ID = POLI.ID_PEG_DOKTER
			JOIN rk_antrian_pasien AP ON AP.ID_PELAYANAN = RJ.ID
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

		$sql = "SELECT * FROM admum_setup_tindakan WHERE $where AND AKTIF = '1' ORDER BY ID DESC";
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

	function simpan_diagnosa($id_pelayanan,$id_poli,$id_peg_dokter,$id_pasien,$tanggal,$bulan,$tahun,$diagnosa,$id_penyakit){
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

	function ubah_diagnosa($id,$diagnosa,$id_penyakit){
		$sql = "
			UPDATE rk_diagnosa_rj SET
				DIAGNOSA = '$diagnosa',
				ID_PENYAKIT = '$id_penyakit'
			WHERE ID = '$id'
		";
		$this->db->query($sql);
	}

	function hapus_diagnosa($id,$id_pelayanan){
		$sql = "DELETE FROM rk_diagnosa_rj WHERE ID = '$id' AND ID_PELAYANAN = '$id_pelayanan'";
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

		$sql = "SELECT * FROM admum_setup_jenis_laborat WHERE $where ORDER BY ID ASC";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function klik_laborat($id){
		$sql = "SELECT * FROM admum_setup_jenis_laborat WHERE ID = '$id'";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function load_pemeriksaan($id_jenis_lab,$keyword){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND (a.NAMA_PEMERIKSAAN LIKE '%$keyword%')";
		}else{
			$where = $where;
		}

		$sql = "
			SELECT 
				a.ID,
				a.NAMA_PEMERIKSAAN,
				b.ID AS ID_NILAI,
				b.NILAI_NORMAL,
				a.TARIF
			FROM admum_setup_pemeriksaan a
			JOIN admum_setup_pemeriksaan_nilai b ON b.ID_PEMERIKSAAN = a.ID
			WHERE $where 
			AND a.ID_JENIS_LAB = '$id_jenis_lab'
			ORDER BY a.ID ASC
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function klik_pemeriksaan_manual($id_nilai){
		$sql = "
			SELECT 
				b.ID AS ID_NILAI,
				a.ID,
				a.NAMA_PEMERIKSAAN,
				b.NILAI_NORMAL,
				a.TARIF
			FROM admum_setup_pemeriksaan a
			JOIN admum_setup_pemeriksaan_nilai b ON b.ID_PEMERIKSAAN = a.ID
			WHERE b.ID = '$id_nilai'
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function klik_pemeriksaan($id_jenis_lab){
		$sql = "
			SELECT 
				a.ID,
				a.NAMA_PEMERIKSAAN,
				b.NILAI_NORMAL,
				a.TARIF
			FROM admum_setup_pemeriksaan a
			JOIN admum_setup_pemeriksaan_nilai b ON b.ID_PEMERIKSAAN = a.ID
			WHERE a.ID_JENIS_LAB = '$id_jenis_lab'
			ORDER BY a.ID ASC
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function simpan_pemeriksaan($kode_lab,$id_pelayanan,$id_poli,$id_peg_dokter,$id_pasien,$jenis_laborat,$total_tarif,$cito,$tanggal,$bulan,$tahun,$waktu){
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
				TIPE
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
				'Dari Poli'
			)
		";
		$this->db->query($sql);
	}

	function simpan_pemeriksaan_detail($id_pemeriksaan_rj,$pemeriksaan,$nilai_rujukan,$tanggal,$bulan,$tahun,$subtotal,$waktu){
		$sql = "
			INSERT INTO rk_laborat_rj_detail(
				ID_PEMERIKSAAN_RJ,
				PEMERIKSAAN,
				NILAI_RUJUKAN,
				TANGGAL,
				BULAN,
				TAHUN,
				SUBTOTAL,
				WAKTU
			) VALUES (
				'$id_pemeriksaan_rj',
				'$pemeriksaan',
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

	function data_laborat($id){
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
				LAB.KODE_LAB,
				SET_LAB.JENIS_LABORAT,
				LAB.CITO,
				LAB.TOTAL_TARIF,
				LAB.TANGGAL,
				LAB.BULAN,
				LAB.TAHUN
			FROM rk_laborat_rj LAB
			LEFT JOIN admum_setup_jenis_laborat SET_LAB ON SET_LAB.ID = LAB.JENIS_LABORAT
			WHERE LAB.ID = '$id'
			ORDER BY LAB.ID DESC
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
			FROM rk_laborat_rj_detail DET
			LEFT JOIN admum_setup_pemeriksaan PRK ON PRK.ID = DET.PEMERIKSAAN
			WHERE DET.ID_PEMERIKSAAN_RJ = '$id_pemeriksaan'
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

	//RESEP

	function load_obat($keyword){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND (a.NAMA_OBAT LIKE '%$keyword%')";
		}

		$sql = "
			SELECT
				a.ID,
				a.KODE_OBAT,
				a.NAMA_OBAT,
				IFNULL(b.STOK,0) AS STOK,
				IFNULL(b.HARGA_BULAT,0) AS HARGA_JUAL,
				a.SERVICE
			FROM admum_setup_nama_obat a
			LEFT JOIN apotek_gudang_obat b ON b.ID_SETUP_NAMA_OBAT = a.ID
			LEFT JOIN faktur_detail c ON c.ID_SETUP_NAMA_OBAT = a.ID
			WHERE $where
			GROUP BY a.ID
			ORDER BY a.ID DESC
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function klik_obat($id){
		$sql = "
			SELECT
				a.ID,
				a.KODE_OBAT,
				a.NAMA_OBAT,
				a.ID_JENIS_OBAT,
				IFNULL(b.STOK,0) AS STOK,
				IFNULL(b.HARGA_BULAT,0) AS HARGA_JUAL,
				a.SERVICE
			FROM admum_setup_nama_obat a
			LEFT JOIN apotek_gudang_obat b ON b.ID_SETUP_NAMA_OBAT = a.ID
			LEFT JOIN faktur_detail c ON c.ID_SETUP_NAMA_OBAT = a.ID
			WHERE a.ID = '$id'
			GROUP BY a.ID
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
				b.NAMA_OBAT,
				b.GOLONGAN_OBAT,
				DET.JUMLAH_BELI,
				DET.SUBTOTAL,
				DET.TAKARAN,
				DET.ATURAN_MINUM,
				DET.HARGA
			FROM rk_resep_detail_rj DET
			LEFT JOIN admum_setup_nama_obat b ON b.ID = DET.ID_OBAT
			WHERE DET.ID_RESEP = '$id_resep'
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function simpan_resep($id_pelayanan,$id_poli,$id_peg_dokter,$id_pasien,$kode_resep,$alergi,$uraian,$banyaknya_resep,$tanggal,$bulan,$tahun,$total,$total_dgn_service,$iter){
		$sql = "
			INSERT INTO rk_resep_rj(
				ID_PELAYANAN,
				ID_POLI,
				ID_PEG_DOKTER,
				ID_PASIEN,
				KODE_RESEP,
				ALERGI_OBAT,
				URAIAN,
				BANYAKNYA_RESEP,
				TANGGAL,
				BULAN,
				TAHUN,
				TOTAL,
				TOTAL_DGN_SERVICE,
				ITER
			) VALUES (
				'$id_pelayanan',
				'$id_poli',
				'$id_peg_dokter',
				'$id_pasien',
				'$kode_resep',
				'$alergi',
				'$uraian',
				'$banyaknya_resep',
				'$tanggal',
				'$bulan',
				'$tahun',
				'$total',
				'$total_dgn_service',
				'$iter'
			)
		";
		$this->db->query($sql);
	}

	function simpan_resep_det($id_resep,$id_obat,$harga,$service,$jumlah,$subtotal,$aturan_umum,$diminum_selama,$tanggal,$tahun,$bulan){
		$sql = "
			INSERT INTO rk_resep_detail_rj(
				ID_RESEP,
				ID_OBAT,
				HARGA,
				SERVICE,
				JUMLAH_BELI,
				SUBTOTAL,
				ATURAN_MINUM,
				DIMINUM_SELAMA,
				TANGGAL,
				TAHUN,
				BULAN
			) VALUES (
				'$id_resep',
				'$id_obat',
				'$harga',
				'$service',
				'$jumlah',
				'$subtotal',
				'$aturan_umum',
				'$diminum_selama',
				'$tanggal',
				'$tahun',
				'$bulan'
			)
		";
		$this->db->query($sql);
	}

	function ubah_stok_obat($id,$jumlah){
		$sql = "UPDATE apotek_gudang_obat SET STOK = STOK - $jumlah WHERE ID = '$id'";
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

	function simpan_rawat_inap($id_rj,$id_pasien,$tanggal_masuk,$waktu,$bulan,$tahun,$asal_rujukan,$id_poli,$id_dokter){
		$sql = "
			INSERT INTO admum_rawat_inap(
				ID_RAWAT_JALAN,
				ID_PASIEN,
				TANGGAL_MASUK,
				WAKTU,
				BULAN,
				TAHUN,
				ASAL_RUJUKAN,
				ID_POLI,
				ID_DOKTER
			) VALUES (
				'$id_rj',
				'$id_pasien',
				'$tanggal_masuk',
				'$waktu',
				'$bulan',
				'$tahun',
				'$asal_rujukan',
				'$id_poli',
				'$id_dokter'
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

	function data_surat_dokter($id_pelayanan,$tanggal){
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
			AND SD.KETERANGAN = 'Surat Keterangan Dokter'
		";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function get_surat_dokter_id($id_rj){
		$sql = "SELECT * FROM rk_surat_dokter_rj WHERE ID_PELAYANAN = '$id_rj'";
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
				SAMPAI_TANGGAL',
				KETERANGAN
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
				'$sampai_tanggal',
				'Surat Keterangan Dokter'
			)
		";
		$this->db->query($sql);
	}

	function hapus_surat_dokter($id){
		$sql = "DELETE FROM rk_surat_dokter_rj WHERE ID = '$id'";
		$this->db->query($sql);
	}

	function get_diagnosa_by_idrj($id_rj){
		$sql = "
			SELECT
				a.*,
				b.URAIAN AS PENYAKIT
			FROM rk_diagnosa_rj a
			JOIN admum_jenis_penyakit b ON a.ID_PENYAKIT = b.ID
			WHERE a.ID_PELAYANAN = '$id_rj'
		";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function simpan_surat_pengantar_ri($id_pelayanan,$id_poli,$id_dokter,$id_pasien,$waktu,$tanggal,$bulan,$tahun,$kode_surat,$tinggi_badan,$berat_badan,$diagnosa,$terapi){
		$sql = "
			INSERT INTO rk_surat_dokter_rj(
				ID_PELAYANAN,
				ID_POLI,
				ID_PEG_DOKTER,
				ID_PASIEN,
				WAKTU,
				TANGGAL,
				BULAN,
				TAHUN,
				KODE_SURAT_PENGANTAR_RI,
				TINGGI_BADAN,
				BERAT_BADAN,
				DIAGNOSA,
				TERAPI,
				KETERANGAN
			) VALUES (
				'$id_pelayanan',
				'$id_poli',
				'$id_dokter',
				'$id_pasien',
				'$waktu',
				'$tanggal',
				'$bulan',
				'$tahun',
				'$kode_surat',
				'$tinggi_badan',
				'$berat_badan',
				'$diagnosa',
				'$terapi',
				'Surat Pengantar RI'
			)
		";
		$this->db->query($sql);
	}

	function cetak_data_surat_pengantar_ri($id_rj){
		$sql = "
			SELECT
				a.ID,
				a.ID_PELAYANAN,
				a.ID_POLI,
				a.ID_PEG_DOKTER,
				a.ID_PASIEN,
				a.TANGGAL,
				a.BULAN,
				a.TAHUN,
				a.KODE_SURAT_PENGANTAR_RI,
				a.TINGGI_BADAN,
				a.BERAT_BADAN,
				a.DIAGNOSA,
				a.TERAPI,
				a.KETERANGAN,
				b.NAMA,
				b.UMUR,
				b.JENIS_KELAMIN,
				b.ALAMAT,
				c.NAMA AS NAMA_DOKTER
			FROM rk_surat_dokter_rj a
			JOIN rk_pasien b ON b.ID = a.ID_PASIEN
			JOIN kepeg_pegawai c ON c.ID = a.ID_PEG_DOKTER
			WHERE a.ID_PELAYANAN = '$id_rj'
			AND a.KETERANGAN = 'Surat Pengantar RI'
		";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function simpan_surat_ket_ri($id_pelayanan,$id_poli,$id_dokter,$id_pasien,$waktu,$tanggal,$bulan,$tahun,$mulai_tanggal,$sampai_tanggal,$diagnosa){
		$sql = "
			INSERT INTO rk_surat_dokter_rj(
				ID_PELAYANAN,
				ID_POLI,
				ID_PEG_DOKTER,
				ID_PASIEN,
				WAKTU,
				TANGGAL,
				BULAN,
				TAHUN,
				MULAI_TANGGAL,
				SAMPAI_TANGGAL,
				ID_PENYAKIT,
				KETERANGAN
			) VALUES (
				'$id_pelayanan',
				'$id_poli',
				'$id_dokter',
				'$id_pasien',
				'$waktu',
				'$tanggal',
				'$bulan',
				'$tahun',
				'$mulai_tanggal',
				'$sampai_tanggal',
				'$diagnosa',
				'Surat Keterangan RI'
			)
		";
		$this->db->query($sql);
	}

	function cetak_data_surat_keterangan_ri($id_rj){
		$sql = "
			SELECT
				a.ID,
				a.ID_PELAYANAN,
				a.ID_POLI,
				a.ID_PEG_DOKTER,
				a.ID_PASIEN,
				a.TANGGAL,
				a.BULAN,
				a.TAHUN,
				a.MULAI_TANGGAL,
				a.SAMPAI_TANGGAL,
				a.KETERANGAN,
				b.NAMA,
				b.UMUR,
				b.JENIS_KELAMIN,
				b.ALAMAT,
				c.NAMA AS NAMA_DOKTER,
				d.URAIAN AS PENYAKIT
			FROM rk_surat_dokter_rj a
			JOIN rk_pasien b ON b.ID = a.ID_PASIEN
			JOIN kepeg_pegawai c ON c.ID = a.ID_PEG_DOKTER
			JOIN admum_jenis_penyakit d ON d.ID = a.ID_PENYAKIT
			WHERE a.ID_PELAYANAN = '$id_rj'
			AND a.KETERANGAN = 'Surat Keterangan RI'
		";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function simpan_surat_keterangan_sehat(
		$id_pelayanan,
		$id_poli,
		$id_dokter,
		$id_pasien,
		$waktu,
		$tanggal,
		$bulan,
		$tahun,
		$tinggi_badan,
		$berat_badan,
		$pakai_kacamata,
		$tidak_pakai_kacamata,
		$buta_warna,
		$pendengaran,
		$tensi,
		$nadi,
		$dinyatakan,
		$untuk_keperluan){

		$sql = "
			INSERT INTO rk_surat_dokter_rj(
				ID_PELAYANAN,
				ID_POLI,
				ID_PEG_DOKTER,
				ID_PASIEN,
				WAKTU,
				TANGGAL,
				BULAN,
				TAHUN,
				TINGGI_BADAN,
				BERAT_BADAN,
				PAKAI_KACA_MATA,
				TIDAK_PAKAI_KACA_MATA,
				BUTA_WARNA,
				PENDENGARAN,
				TENSI,
				NADI,
				DINYATAKAN,
				UNTUK_KEPERLUAN,
				KETERANGAN
			) VALUES (
				'$id_pelayanan',
				'$id_poli',
				'$id_dokter',
				'$id_pasien',
				'$waktu',
				'$tanggal',
				'$bulan',
				'$tahun',
				'$tinggi_badan',
				'$berat_badan',
				'$pakai_kacamata',
				'$tidak_pakai_kacamata',
				'$buta_warna',
				'$pendengaran',
				'$tensi',
				'$nadi',
				'$dinyatakan',
				'$untuk_keperluan',
				'Surat Keterangan Sehat'
			)
		";
		$this->db->query($sql);
	}

	function cetak_data_surat_keterangan_sehat($id_rj){
		$sql = "
			SELECT
				a.ID,
				a.ID_PELAYANAN,
				a.ID_POLI,
				a.ID_PEG_DOKTER,
				a.ID_PASIEN,
				a.TANGGAL,
				a.BULAN,
				a.TAHUN,
				a.TINGGI_BADAN,
				a.BERAT_BADAN,
				a.PAKAI_KACA_MATA,
				a.TIDAK_PAKAI_KACA_MATA,
				a.BUTA_WARNA,
				a.PENDENGARAN,
				a.TENSI,
				a.NADI,
				a.DINYATAKAN,
				a.UNTUK_KEPERLUAN,
				a.KETERANGAN,
				b.NAMA,
				b.UMUR,
				b.ALAMAT,
				c.NAMA AS NAMA_DOKTER
			FROM rk_surat_dokter_rj a
			JOIN rk_pasien b ON b.ID = a.ID_PASIEN
			JOIN kepeg_pegawai c ON c.ID = a.ID_PEG_DOKTER
			WHERE a.ID_PELAYANAN = '$id_rj'
			AND a.KETERANGAN = 'Surat Keterangan Sehat'
		";
		$query = $this->db->query($sql);
		return $query->row();
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
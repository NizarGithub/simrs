<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admum_pasien_rj_m extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function simpan(
		$kode_pasien,
		$tanggal_daftar,
		$nama,
		$jenis_kelamin,
		$pendidikan,
		$agama,
		$alamat,
		$golongan_darah,
		$tempat_lahir,
		$tanggal_lahir,
		$umur,
		$kelurahan,
		$kecamatan,
		$kota,
		$provinsi){

		$sql = "
			INSERT INTO rk_pasien(
				KODE_PASIEN,
				TANGGAL_DAFTAR,
				NAMA,
				JENIS_KELAMIN,
				PENDIDIKAN,
				AGAMA,
				ALAMAT,
				GOLONGAN_DARAH,
				TEMPAT_LAHIR,
				TANGGAL_LAHIR,
				UMUR,
				KELURAHAN,
				KECAMATAN,
				KOTA,
				PROVINSI,
				STATUS
			) VALUES (
				'$kode_pasien',
				'$tanggal_daftar',
				'$nama',
				'$jenis_kelamin',
				'$pendidikan',
				'$agama',
				'$alamat',
				'$golongan_darah',
				'$tempat_lahir',
				'$tanggal_lahir',
				'$umur', 
				'$kelurahan',
				'$kecamatan',
				'$kota',
				'$provinsi',
				'RJ'
			)
		";
		$this->db->query($sql);
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
			$where = $where." AND (
				NAMA LIKE '%$keyword%' OR 
				NIK LIKE '%$keyword%' OR 
				KODE_PASIEN LIKE '%$keyword%' OR 
				NAMA_ORTU LIKE '%$keyword%' OR 
				ALAMAT LIKE '%$keyword%'
			)
			";
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
				NAMA_ORTU,
				SUBSTR(KODE_PASIEN,4,3) AS KODE,
				SUBSTR(TANGGAL_DAFTAR,4,2) AS BULAN
			FROM rk_pasien WHERE $where
			ORDER BY ID DESC
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function klik_pasien($id){
		$sql = "SELECT * FROM rk_pasien WHERE ID = '$id'";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function load_poli($keyword){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND (POLI.NAMA LIKE '%$keyword%' OR PEG.NAMA_DOKTER LIKE '%$keyword%')";
		}

		$sql = "
			SELECT
				POLI.ID,
				POLI.NAMA AS NAMA_POLI,
				POLI.INITIAL_POLI,
				POLI.ID_PEG_DOKTER,
				POLI.BIAYA,
				PEG.NAMA_DOKTER
			FROM admum_poli POLI
			LEFT JOIN(
				SELECT 
					a.ID,
					a.NAMA AS NAMA_DOKTER
				FROM kepeg_pegawai a
			) PEG ON PEG.ID = POLI.ID_PEG_DOKTER
			WHERE $where
			AND POLI.AKTIF = '1'
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function klik_poli($id){
		$sql = "
			SELECT
				POLI.ID,
				POLI.NAMA AS NAMA_POLI,
				POLI.INITIAL_POLI,
				POLI.ID_PEG_DOKTER,
				POLI.BIAYA,
				PEG.NAMA_DOKTER
			FROM admum_poli POLI
			LEFT JOIN(
				SELECT 
					a.ID,
					a.NAMA AS NAMA_DOKTER
				FROM kepeg_pegawai a
			) PEG ON PEG.ID = POLI.ID_PEG_DOKTER
			WHERE POLI.ID = '$id'
		";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function simpan_rj($id_pasien,$asal_rujukan,$hari,$tanggal,$bulan,$tahun,$waktu,$id_poli,$posisi,$barcode,$nomor_antrian,$biaya_reg){
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
				STS_POSISI,
				BARCODE,
				NOMOR_ANTRIAN,
				BIAYA_REG
			) VALUES(
				'$id_pasien',
				'$asal_rujukan',
				'$hari',
				'$tanggal',
				'$bulan',
				'$tahun',
				'$waktu',
				'$id_poli',
				'$posisi',
				'$barcode',
				'$nomor_antrian',
				'$biaya_reg'
			)
		";
		$this->db->query($sql);
	}

	function default_lokasi(){
		$sql = "SELECT * FROM admum_lokasi ORDER BY ID DESC LIMIT 1";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function simpan_antrian($tanggal,$waktu,$id_pasien,$id_rj,$barcode,$id_loket,$kode_antrian,$nomor_antrian){
		$sql = "
			INSERT INTO rk_antrian_pasien(
				TANGGAL,
				WAKTU,
				ID_PASIEN,
				ID_PELAYANAN,
				BARCODE,
				ID_LOKET,
				KODE_ANTRIAN,
				NOMOR_ANTRIAN
			)VALUES(
				'$tanggal',
				'$waktu',
				'$id_pasien',
				'$id_rj',
				'$barcode',
				'$id_loket',
				'$kode_antrian',
				'$nomor_antrian'
			)
		";
		$this->db->query($sql);
	}

	//LABORAT

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

	function get_biaya_lab($jenis){
		$sql = "SELECT * FROM admum_poli WHERE JENIS = '$jenis'";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function simpan_lab_rj($id_pasien,$asal_rujukan,$hari,$tanggal,$bulan,$tahun,$waktu,$id_poli,$posisi,$biaya_reg){
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
				STS_POSISI,
				BIAYA_REG
			) VALUES(
				'$id_pasien',
				'$asal_rujukan',
				'$hari',
				'$tanggal',
				'$bulan',
				'$tahun',
				'$waktu',
				'$id_poli',
				'$posisi',
				'$biaya_reg'
			)
		";
		$this->db->query($sql);
	}

	function simpan_pemeriksaan($kode_lab,$id_pelayanan,$id_poli,$id_peg_dokter,$id_pasien,$jenis_laborat,$total_tarif,$tanggal,$bulan,$tahun,$waktu,$tipe){
		$sql = "
			INSERT INTO rk_laborat_rj(
				KODE_LAB,
				ID_PELAYANAN,
				ID_POLI,
				ID_PEG_DOKTER,
				ID_PASIEN,
				JENIS_LABORAT,
				TOTAL_TARIF,
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

	function simpan_pemeriksaan_detail($id_pemeriksaan_rj,$pemeriksaan,$tanggal,$bulan,$tahun,$subtotal,$waktu){
		$sql = "
			INSERT INTO rk_laborat_rj_detail(
				ID_PEMERIKSAAN_RJ,
				PEMERIKSAAN,
				TANGGAL,
				BULAN,
				TAHUN,
				SUBTOTAL,
				WAKTU
			) VALUES (
				'$id_pemeriksaan_rj',
				'$pemeriksaan',
				'$tanggal',
				'$bulan',
				'$tahun',
				'$subtotal',
				'$waktu'
			)
		";
		$this->db->query($sql);
	}


}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
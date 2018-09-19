<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admum_pasien_rj_m extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->load->database();
		date_default_timezone_set('Asia/Jakarta');
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
			$where = $where." AND (
				NAMA LIKE '%$keyword%' OR
				KODE_PASIEN LIKE '%$keyword%' OR 
				NAMA_AYAH LIKE '%$keyword%' OR 
				NAMA_IBU LIKE '%$keyword%' OR 
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
				TANGGAL_LAHIR,
				UMUR,
				NAMA_AYAH,
				NAMA_IBU,
				ALAMAT,
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
		$tz_object = new DateTimeZone('Asia/Jakarta');
		$datetime = new DateTime();
		$format = $datetime->setTimezone($tz_object);
		$waktu = $format->format('H:i');
		$and = '';

		if($waktu > '21:00'){
			$and = " AND POLI.`STATUS` != 'Normal'";
		}else{
			$and = " AND POLI.`STATUS` != 'Malam'";
		}

		if($keyword != ""){
			$where = $where." AND (POLI.NAMA LIKE '%$keyword%' OR PEG.NAMA_DOKTER LIKE '%$keyword%')";
		}

		$sql = "
			SELECT
				POLI.ID,
				POLI.NAMA AS NAMA_POLI,
				POLI.JENIS,
				POLI.`STATUS`,
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
			$and
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

	function load_dokter($keyword){
		$where = "1  = 1";

		if($keyword != ""){
			$where = $where." AND PEG.NAMA LIKE '%$keyword%'";
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
			AND JAB.NAMA LIKE '%Dokter%'
			ORDER BY PEG.ID DESC
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function klik_dokter($id_pegawai){
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
			WHERE PEG.ID = '$id_pegawai'
		";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function load_asuransi($keyword){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND NAMA_ASURANSI LIKE '%$keyword%'";
		}

		$sql = "SELECT * FROM asr_setup_asuransi WHERE $where";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function klik_asuransi($id){
		$sql = "SELECT * FROM asr_setup_asuransi WHERE ID = '$id'";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function simpan_rj(
		$id_pasien,
		$asal_rujukan,
		$keterangan,
		$hari,
		$tanggal,
		$bulan,
		$tahun,
		$waktu,
		$id_poli,
		$id_dokter,
		$sistem_bayar,
		$asuransi,
		$posisi,
		$barcode,
		$nomor_antrian,
		$biaya_reg,
		$biaya_adm,
		$id_loket,
		$kd_antrian){

		$sql = "
			INSERT INTO admum_rawat_jalan(
				ID_PASIEN,
				ASAL_RUJUKAN,
				KETERANGAN,
				HARI,
				TANGGAL,
				BULAN,
				TAHUN,
				WAKTU,
				ID_POLI,
				ID_DOKTER,
				SISTEM_BAYAR,
				NAMA_ASURANSI,
				STS_POSISI,
				BARCODE,
				NOMOR_ANTRIAN,
				BIAYA_REG,
				BIAYA_ADMIN,
				ID_LOKET,
				KD_ANTRIAN
			) VALUES(
				'$id_pasien',
				'$asal_rujukan',
				'$keterangan',
				'$hari',
				'$tanggal',
				'$bulan',
				'$tahun',
				'$waktu',
				'$id_poli',
				'$id_dokter',
				'$sistem_bayar',
				'$asuransi',
				'$posisi',
				'$barcode',
				'$nomor_antrian',
				'$biaya_reg',
				'$biaya_adm',
				'$id_loket',
				'$kd_antrian'
			)
		";
		$this->db->query($sql);
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

	function get_biaya_lab($jenis){
		$sql = "SELECT * FROM admum_poli WHERE JENIS = '$jenis'";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function simpan_lab_rj($id_pasien,$asal_rujukan,$hari,$tanggal,$bulan,$tahun,$waktu,$id_poli,$sistem_bayar,$asuransi,$posisi,$biaya_reg,$biaya_adm,$dari,$id_loket,$kd_antrian){
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
				SISTEM_BAYAR,
				NAMA_ASURANSI,
				STS_POSISI,
				BIAYA_REG,
				BIAYA_ADMIN,
				PASIEN_DARI,
				ID_LOKET,
				KD_ANTRIAN
			) VALUES(
				'$id_pasien',
				'$asal_rujukan',
				'$hari',
				'$tanggal',
				'$bulan',
				'$tahun',
				'$waktu',
				'$id_poli',
				'$sistem_bayar',
				'$asuransi',
				'$posisi',
				'$biaya_reg',
				'$biaya_adm',
				'$dari',
				'$id_loket',
				'$kd_antrian'
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
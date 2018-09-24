<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admum_pasien_ri_m extends CI_Model {

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
				'RI'
			)
		";
		$this->db->query($sql);
	}

	function load_data_pasien($keyword){ 
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND (NAMA LIKE '%$keyword%' OR NAMA_AYAH LIKE '%$keyword%' OR NAMA_IBU LIKE '%$keyword%')";
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
				JENIS_KELAMIN,
				ALAMAT,
				NAMA_AYAH,
				NAMA_IBU
			FROM rk_pasien 
			WHERE $where
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

	function load_kamar($keyword,$kelas){
		$where = "1 = 1";

		if($kelas == 'Semua'){
			$where = $where;
		}else{
			$where = $where." AND KELAS = '$kelas'";
		}

		if($keyword != ""){
			$where = $where." AND (KODE_KAMAR LIKE '%$keyword%')";
		}else{
			$where = $where;
		}

		$sql = "SELECT * FROM admum_kamar_rawat_inap WHERE $where";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function klik_kamar($id){
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

	function load_dokter($keyword){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND a.NAMA LIKE '%$keyword%'";
		}

		$sql = "
			SELECT
				a.ID,
				a.NAMA
			FROM kepeg_pegawai a
			WHERE $where
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function klik_dokter($id){
		$sql = "SELECT ID,NAMA FROM kepeg_pegawai WHERE ID = '$id'";
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

	function simpan_ri(
		$id_pasien,
		$tanggal_masuk,
		$waktu,
		$bulan,
		$tahun,
		$nama_pjawab,
		$telepon,
		$sistem_bayar,
		$asal_rujukan,
		$id_dokter,
		$id_asuransi,
		$kelas,
		$id_kamar,
		$id_bed,
		$biaya_kamar,
		$biaya_charge,
		$biaya_adm){
		
		$sql = "
			INSERT INTO admum_rawat_inap(
				ID_PASIEN,
				TANGGAL_MASUK,
				WAKTU,
				BULAN,
				TAHUN,
				NAMA_PENANGGUNGJAWAB,
				TELEPON,
				SISTEM_BAYAR,
				ASAL_RUJUKAN,
				ID_DOKTER,
				ID_ASURANSI,
				KELAS,
				ID_KAMAR,
				ID_BED,
				BIAYA_KAMAR_FIX,
				BIAYA_CHARGE_KAMAR,
				BIAYA_REG
			) VALUES(
				'$id_pasien',
				'$tanggal_masuk',
				'$waktu',
				'$bulan',
				'$tahun',
				'$nama_pjawab',
				'$telepon',
				'$sistem_bayar',
				'$asal_rujukan',
				'$id_dokter',
				'$id_asuransi',
				'$kelas',
				'$id_kamar',
				'$id_bed',
				'$biaya_kamar',
				'$biaya_charge',
				'$biaya_adm'
			)
		";
		$this->db->query($sql);
	}

	function simpan_asuransi($id_ri,$id_asuransi,$no_polis,$no_peserta,$nama,$status_pasien){
		$sql = "
			INSERT INTO asr_asuransi(
				ID_RAWAT_INAP,
				ID_ASURANSI,
				NO_POLIS,
				NO_PESERTA,
				NAMA,
				STATUS_PASIEN
			) VALUES (
				'$id_ri',
				'$id_asuransi',
				'$no_polis',
				'$no_peserta',
				'$nama',
				'$status_pasien'
			)
		";
		$this->db->query($sql);
	}

	function update_stt_pakai($id){
		$sql = "UPDATE admum_bed_rawat_inap SET STATUS_PAKAI = '1' WHERE ID = '$id'";
		$this->db->query($sql);
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

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
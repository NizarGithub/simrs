<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setup_jadwal_perawat_m extends CI_Model {

	function __construct()
	{
		parent::__construct(); 
		$this->load->database();
	}

	function getDataTim(){
		$sql = "
		SELECT a.*, b.NAMA AS KETUA FROM kepeg_tim_perawat a 
		JOIN kepeg_pegawai b ON a.ID_KETUA = b.ID
		";

		return $this->db->query($sql)->result();
	}

	function getAnggotaTim($id){
		$sql = "
		SELECT b.* FROM kepeg_tim_perawat_anggota a 
		JOIN kepeg_pegawai b ON a.ID_ANGGOTA = b.ID
		WHERE a.ID_TIM = $id
		ORDER BY a.ID
		";

		return $this->db->query($sql)->result();
	}

	function getKamarTim($id){
		$sql = "
		SELECT b.* FROM kepeg_tim_perawat_kamar a 
		JOIN admum_kamar_rawat_inap b ON a.ID_KAMAR = b.ID
		WHERE a.ID_TIM = $id
		ORDER BY a.ID
		";

		return $this->db->query($sql)->result();
	}

	function simpanTim($nama_tim, $id_ketua){
		$sql = "
		INSERT INTO kepeg_tim_perawat
		(NAMA_TIM, ID_KETUA)
		VALUES 
		('$nama_tim', '$id_ketua')
		";

		$this->db->query($sql);
	}

	function getIDTim(){
		$sql = "
		SELECT * FROM kepeg_tim_perawat
		ORDER BY ID DESC LIMIT 1
		";

		return $this->db->query($sql)->row();
	}

	function simpanAnggotaTim($id_tim, $anggota){
		$sql = "
		INSERT INTO kepeg_tim_perawat_anggota
		(ID_TIM, ID_ANGGOTA)
		VALUES 
		($id_tim, $anggota)
		";

		$this->db->query($sql);
	}

	function simpanKamar($id_tim, $kamar){
		$sql = "
		INSERT INTO kepeg_tim_perawat_kamar
		(ID_TIM, ID_KAMAR)
		VALUES 
		($id_tim, $kamar)
		";

		$this->db->query($sql);
	}

	function get_data_kamar($keyword){

		$where = "1=1";
		if($keyword != ""){
			$where = $where." AND (KODE_KAMAR = '$keyword' OR NAMA_KAMAR = '$keyword' OR KELAS = '$keyword') ";
		}

		$sql = "
		SELECT * FROM admum_kamar_rawat_inap
		WHERE $where
		";

		return $this->db->query($sql)->result();
	}

	function get_data_kamar_id($id){
		$sql = "
		SELECT * FROM admum_kamar_rawat_inap
		WHERE ID = $id
		";

		return $this->db->query($sql)->result();
	}

	function HapusAllJadwal(){
		$sql = "
		DELETE FROM kepeg_tim_perawat_jadwal
		";

		$this->db->query($sql);
	}

	function simpanJadwal($id_perawat, $id_tim, $hari, $waktu_awal, $waktu_akhir){
		$sql = "
		INSERT INTO kepeg_tim_perawat_jadwal
		(ID_TIM, ID_ANGGOTA, HARI, WAKTU_AWAL, WAKTU_AKHIR)
		VALUES 
		($id_tim, $id_perawat, '$hari', '$waktu_awal', '$waktu_akhir')
		";

		$this->db->query($sql);
	}

	function getJadwal($id_tim, $id_perawat, $hari){
		$sql = "
		SELECT * FROM kepeg_tim_perawat_jadwal
		WHERE ID_TIM = $id_tim AND ID_ANGGOTA = $id_perawat AND HARI = '$hari'
		ORDER BY ID ASC
		";

		return $this->db->query($sql)->result();
	}

	function getJadwalTugas($id_kamar){
		$sql = "
		SELECT b.*, c.NAMA AS KETUA FROM kepeg_tim_perawat_kamar a 
		JOIN kepeg_tim_perawat b ON a.ID_TIM = b.ID
		JOIN kepeg_pegawai c ON b.ID_KETUA = c.ID
		WHERE a.ID_KAMAR = $id_kamar
		";

		return $this->db->query($sql)->result();
	}

	function getJadwalTugasDetail($id_tim, $hari){
		$sql = "
		SELECT a.*, b.NAMA FROM kepeg_tim_perawat_jadwal a 
		JOIN kepeg_pegawai b ON a.ID_ANGGOTA = b.ID
		WHERE a.ID_TIM = $id_tim AND a.HARI = '$hari'
		";

		return $this->db->query($sql)->result();
	}

}
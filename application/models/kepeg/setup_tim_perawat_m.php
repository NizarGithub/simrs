<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setup_tim_perawat_m extends CI_Model {

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

	function getDataTimbyID($id_tim){
		$sql = "
		SELECT a.*, b.NAMA AS KETUA FROM kepeg_tim_perawat a 
		JOIN kepeg_pegawai b ON a.ID_KETUA = b.ID
		WHERE a.ID = $id_tim
		";

		return $this->db->query($sql)->row();
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

	function UbahTim($id_tim, $nama_tim, $id_ketua){
		$sql = "
		UPDATE kepeg_tim_perawat SET 
			NAMA_TIM = '$nama_tim',
			ID_KETUA = $id_ketua
		WHERE ID = $id_tim
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

	function hapusTim($id){
		$sql_1 = "
		DELETE FROM kepeg_tim_perawat WHERE ID = $id
		";

		$this->db->query($sql_1);

		$sql_2 = "
		DELETE FROM kepeg_tim_perawat_anggota WHERE ID_TIM = $id
		";

		$this->db->query($sql_2);

		$sql_3 = "
		DELETE FROM kepeg_tim_perawat_kamar WHERE ID_TIM = $id
		";

		$this->db->query($sql_3);
	}

	function HapusAnggotaTim($id_tim){
		$sql = "
		DELETE FROM kepeg_tim_perawat_anggota WHERE ID_TIM = $id_tim
		";

		$this->db->query($sql);
	}

	function HapusKamarTim($id_tim){
		$sql = "
		DELETE FROM kepeg_tim_perawat_kamar WHERE ID_TIM = $id_tim
		";

		$this->db->query($sql);
	}

}
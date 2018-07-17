<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Data_asuransi_m extends CI_Model {

	function __construct() 
	{
		parent::__construct(); 
		$this->load->database();
	}

	function cek_kode_asr($kode_asr){
		$sql = " 
		SELECT * FROM asr_setup_asuransi WHERE KODE = '$kode_asr'
		";

		return $this->db->query($sql)->result();
	}

	function simpan_asuransi($kode_asr, $nama_asr, $uraian){
		$sql = "
			INSERT INTO asr_setup_asuransi 
			(KODE, NAMA_ASURANSI, URAIAN, LOGO)
			VALUES 
			('$kode_asr', '$nama_asr', '$uraian', 'default.png')
		";

		$this->db->query($sql);
	}

	function get_data_asuransi(){
		$sql = "
		SELECT * FROM asr_setup_asuransi
		ORDER BY ID DESC
		";

		return $this->db->query($sql)->result();
	}

	function hapus_departemen($id_hapus){
		$sql = "
		DELETE FROM asr_setup_asuransi WHERE ID = $id_hapus
		";

		$this->db->query($sql);
	}

	function get_data_asr_by_id($id){
		$sql = "
		SELECT * FROM asr_setup_asuransi WHERE ID = $id
		";

		return $this->db->query($sql)->row();
	}

	function ubah_asuransi($id_asuransi, $ed_nama_asr, $ed_uraian){
		$sql = "
		UPDATE asr_setup_asuransi SET NAMA_ASURANSI = '$ed_nama_asr', URAIAN = '$ed_uraian'
		WHERE ID = $id_asuransi
		";

		$this->db->query($sql);
	}

	function simpanLogoAsuransi($kode_asr, $foto){
		$sql = "
		UPDATE asr_setup_asuransi SET LOGO = '$foto'
		WHERE KODE = '$kode_asr'
		";

		$this->db->query($sql);
	}

}
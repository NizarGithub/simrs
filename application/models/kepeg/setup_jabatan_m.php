<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setup_jabatan_m extends CI_Model { 

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function cek_kode_jabatan($kode_jab){
		$sql = "
		SELECT * FROM kepeg_jabatan WHERE KODE_JABATAN = '$kode_jab' 
		";

		return $this->db->query($sql)->result();
	} 

	function get_data_jabatan(){
		$sql = "
		SELECT * FROM kepeg_jabatan
		ORDER BY ID ASC
		";

		return $this->db->query($sql)->result();
	}

	function simpan_jabatan($kode_jab, $nama_jab, $uraian){
		$sql = "
			INSERT INTO kepeg_jabatan 
			(KODE_JABATAN, NAMA, URAIAN)
			VALUES 
			('$kode_jab', '$nama_jab', '$uraian')
		";

		$this->db->query($sql);
	}

	function hapus_jabatan($id){
		$sql = "
		DELETE FROM kepeg_jabatan WHERE ID = $id
		";

		$this->db->query($sql);
	}

	function get_data_jab_by_id($id){
		$sql = "
		SELECT * FROM kepeg_jabatan WHERE ID = $id
		";

		return $this->db->query($sql)->row();
	}

	function ubah_jabatan($id_jabatan, $ed_nama_jab, $ed_uraian){
		$sql = "
		UPDATE kepeg_jabatan SET NAMA = '$ed_nama_jab', URAIAN = '$ed_uraian'
		WHERE ID = $id_jabatan
		";

		$this->db->query($sql);
	}

}
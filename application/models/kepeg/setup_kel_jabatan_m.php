<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setup_kel_jabatan_m extends CI_Model { 

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function cek_kode_kel_jabatan($kode_kel_jab){
		$sql = "
		SELECT * FROM kepeg_kel_jabatan WHERE KODE_KEL_JAB = '$kode_kel_jab'
		";

		return $this->db->query($sql)->result();
	}

	function get_data_kel_jabatan(){
		$sql = "
		SELECT * FROM kepeg_kel_jabatan
		ORDER BY ID ASC
		";

		return $this->db->query($sql)->result();
	}

	function simpan_kel_jabatan($kode_kel_jab, $nama_kel_jab, $jenis){
		$sql = "
			INSERT INTO kepeg_kel_jabatan 
			(KODE_KEL_JAB, NAMA, JENIS)
			VALUES 
			('$kode_kel_jab', '$nama_kel_jab', '$jenis')
		";

		$this->db->query($sql);
	}

	function hapus_kel_jabatan($id){
		$sql = "
		DELETE FROM kepeg_kel_jabatan WHERE ID = $id
		";

		$this->db->query($sql);
	}

	function get_data_jab_by_id($id){
		$sql = "
		SELECT * FROM kepeg_kel_jabatan WHERE ID = $id
		";

		return $this->db->query($sql)->row();
	}

	function ubah_jabatan($id_kel_jabatan, $ed_nama_kel_jab, $ed_jenis){
		$sql = "
		UPDATE kepeg_kel_jabatan SET NAMA = '$ed_nama_kel_jab', JENIS = '$ed_jenis'
		WHERE ID = $id_kel_jabatan
		";

		$this->db->query($sql);
	}

}
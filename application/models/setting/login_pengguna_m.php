<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login_pengguna_m extends CI_Model {

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

	function cek_username($id_peg, $username){
		$sql = "
		SELECT * FROM kepeg_pegawai WHERE USERNAME = '$username' AND ID != $id_peg
		";

		return $this->db->query($sql)->result();
	}

	function simpan_login_user($id_pegawai, $status, $username, $level){
		$sql = "
		UPDATE kepeg_pegawai SET STS_AKUN = $status, USERNAME = '$username', LEVEL = '$level'
		WHERE ID = $id_pegawai
		";

		$this->db->query($sql);
	}

	function simpan_password_user($id_pegawai, $pass){

		$pass = md5(md5($pass));

		$sql = "
		UPDATE kepeg_pegawai SET PASSWORD = '$pass'
		WHERE ID = $id_pegawai
		";

		$this->db->query($sql);
	}

}
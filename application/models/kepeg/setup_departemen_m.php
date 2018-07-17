<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setup_departemen_m extends CI_Model {

	function __construct()
	{
		parent::__construct(); 
		$this->load->database();
	}

	function cek_kode_dep($kode_dep){
		$sql = " 
		SELECT * FROM kepeg_departemen WHERE KODE = '$kode_dep' AND STS = 0
		";

		return $this->db->query($sql)->result();
	}

	function simpan_departemen($kode_dep, $nama_dep, $uraian){
		$sql = "
			INSERT INTO kepeg_departemen 
			(KODE, NAMA_DEP, URAIAN, STS)
			VALUES 
			('$kode_dep', '$nama_dep', '$uraian', 0)
		";

		$this->db->query($sql);
	}

	function get_data_departemen(){
		$sql = "
		SELECT * FROM kepeg_departemen WHERE STS = 0
		ORDER BY ID DESC
		";

		return $this->db->query($sql)->result();
	}

	function hapus_departemen($id_hapus){
		$sql = "
		UPDATE kepeg_departemen SET STS = 1
		WHERE ID = $id_hapus
		";

		$this->db->query($sql);
	}

	function get_data_dep_by_id($id){
		$sql = "
		SELECT * FROM kepeg_departemen WHERE ID = $id
		";

		return $this->db->query($sql)->row();
	}

	function ubah_departemen($id_departemen, $ed_nama_dep, $ed_uraian){
		$sql = "
		UPDATE kepeg_departemen SET NAMA_DEP = '$ed_nama_dep', URAIAN = '$ed_uraian'
		WHERE ID = $id_departemen
		";

		$this->db->query($sql);
	}

}
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setup_divisi_m extends CI_Model { 
	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function cek_kode_divisi($kode_div){
		$sql = "
		SELECT * FROM kepeg_divisi WHERE KODE_DIV = '$kode_div' AND STS = 0
		";

		return $this->db->query($sql)->result();
	}


	function simpan_divisi($id_dep, $kode_div, $nama_div, $uraian){
		$sql = "
			INSERT INTO kepeg_divisi 
			(ID_DEPARTEMEN, KODE_DIV, NAMA_DIV, URAIAN, STS)
			VALUES 
			($id_dep, '$kode_div', '$nama_div', '$uraian', 0)
		";

		$this->db->query($sql);
	}

	function get_data_departemen(){
		$sql = "
		SELECT a.*, b.NAMA_DEP AS DEPARTEMEN FROM kepeg_divisi a 
		JOIN kepeg_departemen b ON a.ID_DEPARTEMEN = b.ID
		WHERE a.STS = 0
		ORDER BY a.ID DESC
		";

		return $this->db->query($sql)->result();
	}

	function hapus_divisi($id_hapus){
		$sql = "
		UPDATE kepeg_divisi SET STS = 1
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


	function get_data_div_by_id($id){
		$sql = "
		SELECT a.*, b.NAMA_DEP AS DEPARTEMEN 
		FROM kepeg_divisi a 
		JOIN kepeg_departemen b ON a.ID_DEPARTEMEN = b.ID
		WHERE a.ID = $id
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

	function ubah_divisi($id_divisi, $id_departemen, $ed_nama_div, $ed_uraian){
		$sql = "
		UPDATE kepeg_divisi SET ID_DEPARTEMEN = $id_departemen, NAMA_DIV = '$ed_nama_div', URAIAN = '$ed_uraian'
		WHERE ID = $id_divisi
		";

		$this->db->query($sql);
	}

}
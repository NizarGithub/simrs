<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setup_pangkat_m extends CI_Model { 

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	} 

	function cek_nama_pangkat($golongan, $ruang){ 
		$sql = "
		SELECT * FROM kepeg_pangkat WHERE GOLONGAN = '$golongan' AND RUANG = '$ruang'
		";

		return $this->db->query($sql)->result();
	}

	function simpan_pangkat($nama_pangkat, $golongan, $ruang){
		$sql = "
			INSERT INTO kepeg_pangkat 
			(NAMA, GOLONGAN, RUANG)
			VALUES 
			('$nama_pangkat', '$golongan', '$ruang')
		";

		$this->db->query($sql);
	}

	function get_data_pangkat(){
		$sql = "
		SELECT * FROM kepeg_pangkat
		WHERE GOLONGAN != 'CPNS'
		ORDER BY ID DESC
		";

		return $this->db->query($sql)->result();
	}

	function hapus_pangkat($id_hapus){
		$sql = "
		DELETE FROM kepeg_pangkat WHERE ID = $id_hapus
		";

		$this->db->query($sql);
	}

	function get_data_pang_by_id($id){
		$sql = "
		SELECT * FROM kepeg_pangkat WHERE ID = $id
		";

		return $this->db->query($sql)->row();
	}

	function ubah_pangkat($id_pangkat, $ed_nama_pangkat){
		$sql = "
		UPDATE kepeg_pangkat SET NAMA = '$ed_nama_pangkat'
		WHERE ID = $id_pangkat
		";

		$this->db->query($sql);
	}

}
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setup_golongan_pajak_m extends CI_Model { 

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	} 
 
	function cek_golongan($kode_golongan){
		$sql = "
		SELECT * FROM kepeg_gol_pajak WHERE KODE_GOLONGAN = '$kode_golongan'
		";

		return $this->db->query($sql)->result();
	}

	function simpan_golongan($kode_golongan, $nama_golongan, $nilai_ptkp){

		$nilai_ptkp = str_replace(',', '', $nilai_ptkp);
		$sql = "
			INSERT INTO kepeg_gol_pajak 
			(KODE_GOLONGAN, NAMA_GOLONGAN, PTKP)
			VALUES 
			('$kode_golongan', '$nama_golongan', $nilai_ptkp)
		";

		$this->db->query($sql);
	}

	function get_data_golongan_pajak(){ 
		$sql = "
		SELECT * FROM kepeg_gol_pajak
		ORDER BY ID ASC
		"; 

		return $this->db->query($sql)->result();
	}

	function hapus_golongan_pajak($id_hapus){
		$sql = "
		DELETE FROM kepeg_gol_pajak WHERE ID = $id_hapus
		";

		$this->db->query($sql);
	}

	function get_data_gol_by_id($id){
		$sql = "
		SELECT * FROM kepeg_gol_pajak WHERE ID = $id
		";

		return $this->db->query($sql)->row();
	}

	function ubah_golongan($id_gol, $ed_kode_golongan, $ed_nama_golongan, $ed_nilai_ptkp){
		$sql = "
		UPDATE kepeg_gol_pajak SET NAMA_GOLONGAN = '$ed_nama_golongan', PTKP = $ed_nilai_ptkp
		WHERE ID = $id_gol
		";

		$this->db->query($sql);
	}

}
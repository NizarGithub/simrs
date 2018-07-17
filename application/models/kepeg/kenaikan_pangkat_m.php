<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kenaikan_pangkat_m extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}


	function get_all_pegawai(){

		$tgl = date('d-m-Y');
		$sql = "
		SELECT a.*, b.NAMA AS JABATAN, c.GOLONGAN, c.RUANG, C.NAMA AS NAMA_PANGKAT FROM kepeg_pegawai a
		LEFT JOIN kepeg_kel_jabatan b ON a.ID_JABATAN = b.ID
		LEFT JOIN kepeg_pangkat c ON a.ID_PANGKAT = c.ID
		WHERE STR_TO_DATE(a.TGL_AKHIR_PANGKAT, '%d-%c-%Y') <= STR_TO_DATE('$tgl' , '%d-%c-%Y')
		ORDER BY ID
		";

		return $this->db->query($sql)->result();
	}

	function get_next_pangkat($id_pangkat){
		$sql = "
		SELECT * FROM kepeg_pangkat WHERE ID > $id_pangkat
		ORDER BY ID ASC LIMIT 1
		";
		return $this->db->query($sql)->row();
	}

	function get_peg_detail($id_peg){
		$sql = "
		SELECT * FROM kepeg_pegawai
		WHERE ID = $id_peg
		";
		return $this->db->query($sql)->row();
	}

	function simpanHistory($id_pegawai, $id_pangkat_skrg, $sk_pangkat, $tgl_sk_pangkat, $tgl_awal, $tgl_akhir){
		$sql = "
		INSERT INTO kepeg_pangkat_history
		(ID_PEGAWAI, ID_PANGKAT, NO_SK, TGL_SK, TGL_AWAL, TGL_AKHIR)
		VALUES 
		($id_pegawai, $id_pangkat_skrg, '$sk_pangkat', '$tgl_sk_pangkat', '$tgl_awal', '$tgl_akhir')
		";

		$this->db->query($sql);
	}

	function update_pangkat($id_pegawai, $id_pangkat_baru, $no_sk, $tgl_sk, $tgl_awal_pangkat, $tgl_akhir_pangkat){
		$sql = "
		UPDATE kepeg_pegawai SET 
			ID_PANGKAT = $id_pangkat_baru,
			SK_PANGKAT = '$no_sk',
			TGL_SK_PANGKAT = '$tgl_sk',
			TGL_AWAL_PANGKAT = '$tgl_awal_pangkat',
			TGL_AKHIR_PANGKAT = '$tgl_akhir_pangkat'
		WHERE ID = $id_pegawai
		";

		$this->db->query($sql);
	}

}
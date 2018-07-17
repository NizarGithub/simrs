<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mutasi_pegawai_m extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->load->database(); 
	}


	function get_all_pegawai(){
		$sql = " 
		SELECT a.*, b.NAMA AS JABATAN, c.NAMA_DEP, d.NAMA_DIV FROM kepeg_pegawai a
		LEFT JOIN kepeg_kel_jabatan b ON a.ID_JABATAN = b.ID
		LEFT JOIN kepeg_departemen  c ON a.ID_DEPARTEMEN = c.ID
		LEFT JOIN kepeg_divisi      d ON a.ID_DIVISI = d.ID
		ORDER BY ID
		";

		return $this->db->query($sql)->result();
	}


	function get_departemen(){
		$sql = "
		SELECT * FROM kepeg_departemen WHERE STS = 0
		ORDER BY ID ASC
		";

		return $this->db->query($sql)->result();
	}

	function get_jabatan(){
		$sql = "
		SELECT * FROM kepeg_kel_jabatan WHERE JENIS = 'S'
		ORDER BY ID ASC
		";

		return $this->db->query($sql)->result();
	}

	function simpanMutasi($id_pegawai, $jabatan_lama, $sk_jabatan_lama, $departemen_lama, $divisi_lama, $tgl_akhir_jabatan){
		$sql = "
		INSERT INTO kepeg_mutasi_history
		(ID_PEGAWAI, JABATAN, SK_JABATAN, DEPARTEMEN, DIVISI, TGL)
		VALUES 
		($id_pegawai, '$jabatan_lama', '$sk_jabatan_lama', '$departemen_lama', '$divisi_lama', '$tgl_akhir_jabatan')
		";

		$this->db->query($sql);
	}

	function ubahDataPeg($id_pegawai, $sk_jabatan, $tgl_sk_jabatan, $status, $jabatan_baru, $departemen_baru, $divisi_baru, $tgl_awal_jab_baru, $tgl_akhir_jab_baru){
		$sql = "
		UPDATE kepeg_pegawai SET 
			SK_JABATAN = '$sk_jabatan',
			TGL_SK_JABATAN = '$tgl_sk_jabatan',
			STATUS = '$status',
			ID_JABATAN = $jabatan_baru,
			ID_DEPARTEMEN = $departemen_baru,
			ID_DIVISI = $divisi_baru,
			TGL_AWAL_JABATAN = '$tgl_awal_jab_baru',
			TGL_AKHIR_JABATAN = '$tgl_akhir_jab_baru'
		WHERE ID = $id_pegawai
		";

		$this->db->query($sql);
	}

}
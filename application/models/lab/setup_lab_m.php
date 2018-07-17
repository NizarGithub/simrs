<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setup_lab_m extends CI_Model {

	function __construct()
	{ 
		parent::__construct(); 
		$this->load->database();
	}

	function cek_kode_lap($kode_lab){
		$sql = " 
		SELECT * FROM lab_setup_laboratorium 
		WHERE KODE_LAB = '$kode_lab'
		";

		return $this->db->query($sql)->result();
	}

	function simpan_lab($kode_lab, $jenis_lab, $biaya, $uraian){
		$biaya = str_replace(',', '', $biaya);
		$sql = "
			INSERT INTO lab_setup_laboratorium 
			(KODE_LAB, JENIS_LAB, BIAYA, URAIAN)
			VALUES 
			('$kode_lab', '$jenis_lab', $biaya, '$uraian')
		";

		$this->db->query($sql);
	}

	function get_data_laboratorium(){
		$sql = "
		SELECT * FROM lab_setup_laboratorium
		ORDER BY ID DESC
		";

		return $this->db->query($sql)->result();
	}

	function hapus_lab($id_hapus){
		$sql = "
		DELETE FROM lab_setup_laboratorium
		WHERE ID = $id_hapus
		";

		$this->db->query($sql);
	}

	function get_data_lab_by_id($id){
		$sql = "
		SELECT * FROM lab_setup_laboratorium WHERE ID = $id
		";

		return $this->db->query($sql)->row();
	}

	function ubah_lab($id_lab, $ed_jenis_lab, $ed_biaya, $ed_uraian){
		$sql = " 
		UPDATE lab_setup_laboratorium 
		SET JENIS_LAB = '$ed_jenis_lab', BIAYA = $ed_biaya, URAIAN = '$ed_uraian'
		WHERE ID = $id_lab
		";

		$this->db->query($sql);
	}

}
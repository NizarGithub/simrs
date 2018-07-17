<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class List_asuransi_m extends CI_Model {

	function __construct()
	{
		parent::__construct(); 
		$this->load->database(); 
	}

	function get_data_asuransi($id){
		$sql = " 
		SELECT * FROM asr_setup_asuransi
		WHERE ID = $id
		";

		return $this->db->query($sql)->row();
	}

	function getDataPasien($keyword){

		$where = "1=1";
		if($keyword != "" || $keyword != null){
			$where = $where." AND (KODE_PASIEN LIKE '%$keyword%' OR NAMA LIKE '%$keyword%' OR ALAMAT LIKE '%$keyword%') ";
		}

		$sql = "
		SELECT * FROM rk_pasien WHERE $where
		";

		return $this->db->query($sql)->result();
	}

	function get_data_pasien_by_id($id_pasien){
		$sql = "
		SELECT * FROM rk_pasien WHERE ID = $id_pasien
		";

		return $this->db->query($sql)->row();
	}

	function simpan_asuransi($id, $no_polis, $nama_pemegang_polis, $id_pasien, $jml_klaim){
		$sql = "
		INSERT INTO asr_asuransi
		(ID_ASURANSI, NO_POLIS, NAMA_POLIS, UNTUK, JML_KLAIM)
		VALUES 
		($id, '$no_polis', '$nama_pemegang_polis', $id_pasien, $jml_klaim)
		";

		$this->db->query($sql);
	}

	function getDataPolis($id){
		$sql = "
		SELECT a.* , b.NAMA, b.KODE_PASIEN FROM asr_asuransi a 
		JOIN rk_pasien b ON a.UNTUK = b.ID 
		WHERE a.ID_ASURANSI = $id
		ORDER BY ID DESC
		";

		return $this->db->query($sql)->result();
	}

	function hapus_asuransi($id_hapus){
		$sql = "
		DELETE FROM asr_asuransi WHERE ID = $id_hapus
		";

		$this->db->query($sql);
	}
}
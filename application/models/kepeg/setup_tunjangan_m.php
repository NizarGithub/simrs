<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setup_tunjangan_m extends CI_Model {

	function __construct()
	{ 
		parent::__construct();
		$this->load->database();
	}

	function cek_kode_tunj($kode_tunj){ 
		$sql = "
		SELECT * FROM abs_setup_gaji WHERE KODE_GAJI = '$kode_tunj'
		";
 
		return $this->db->query($sql)->result();
	}

	function simpan_tunjangan($kode_tunj, $nama_tunj, $uraian){
		$sql = "
			INSERT INTO abs_setup_gaji 
			(KODE_GAJI, NAMA_GAJI, URAIAN)
			VALUES 
			('$kode_tunj', '$nama_tunj', '$uraian')
		";

		$this->db->query($sql);
	}

	function get_data_tunjangan(){
		$sql = "
		SELECT * FROM abs_setup_gaji WHERE ID > 2
		ORDER BY ID ASC
		";

		return $this->db->query($sql)->result();
	}

	function hapus_tunjangan($id_hapus){
		$sql = "
		   DELETE FROM abs_setup_gaji WHERE ID = $id_hapus
		";

		$this->db->query($sql);
	}

	function get_data_tunjangan_by_id($id){
		$sql = "
		SELECT * FROM abs_setup_gaji WHERE ID = $id
		";

		return $this->db->query($sql)->row();
	}

	function ubah_tunjangan($id_tunjangan, $ed_nama_tunj, $ed_uraian){
		$sql = "
		UPDATE abs_setup_gaji SET NAMA_GAJI = '$ed_nama_tunj', URAIAN = '$ed_uraian'
		WHERE ID = $id_tunjangan
		";

		$this->db->query($sql);
	}

}
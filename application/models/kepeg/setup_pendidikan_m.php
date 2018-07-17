<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setup_pendidikan_m extends CI_Model { 

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function cek_pendidikan($kode_pendidikan){ 
		$sql = "
		SELECT * FROM kepeg_pendidikan WHERE KODE = '$kode_pendidikan'
		";

		return $this->db->query($sql)->result();
	}

	function simpan_pendidikan($kode_pendidikan, $jenjang, $bidang, $nama_pendidikan, $pangkat_min, $pangkat_max){

		$sql = "
			INSERT INTO kepeg_pendidikan 
			(KODE, JENJANG, BIDANG, NAMA, MIN_PANGKAT, MAX_PANGKAT)
			VALUES 
			('$kode_pendidikan', '$jenjang', '$bidang', '$nama_pendidikan', $pangkat_min, $pangkat_max)
		";

		$this->db->query($sql);
	}

	function get_data_pendidikan(){
		$sql = "
		SELECT a.*, b.NAMA_JENJANG, c.NAMA_BIDANG, CONCAT(MIN.GOLONGAN, '/', MIN.RUANG) AS MIN_PKT, CONCAT(MAX.GOLONGAN, '/', MAX.RUANG) AS MAX_PKT  FROM kepeg_pendidikan a 
		LEFT JOIN kepeg_pendidikan_jenjang b ON a.JENJANG = b.ID
		LEFT JOIN kepeg_pendidikan_bidang c ON a.BIDANG = c.ID

		LEFT JOIN kepeg_pangkat MIN ON a.MIN_PANGKAT = MIN.ID
		LEFT JOIN kepeg_pangkat MAX ON a.MAX_PANGKAT = MAX.ID
		ORDER BY ID ASC
		";

		return $this->db->query($sql)->result();
	}

	function hapus_pendidikan($id_hapus){
		$sql = "
		DELETE FROM kepeg_pendidikan WHERE ID = $id_hapus
		";

		$this->db->query($sql);
	}

	function get_data_pend_by_id($id){
		$sql = "
		SELECT * FROM kepeg_pendidikan WHERE ID = $id
		";

		return $this->db->query($sql)->row();
	}

	function ubah_pendidikan($id_pen, $ed_jenjang, $ed_bidang, $ed_nama_pendidikan){
		$sql = "
		UPDATE kepeg_pendidikan SET JENJANG = '$ed_jenjang', BIDANG = '$ed_bidang', NAMA = '$ed_nama_pendidikan'
		WHERE ID = $id_pen
		";

		$this->db->query($sql);
	}

	function get_data_jenjang(){
		$sql = "
		SELECT * FROM kepeg_pendidikan_jenjang ORDER BY ID
		";

		return $this->db->query($sql)->result();
	}

	function get_data_bidang(){
		$sql = "
		SELECT * FROM kepeg_pendidikan_bidang ORDER BY ID
		";

		return $this->db->query($sql)->result();
	}

	function add_jenjang($add_nama_jenjang){
		$sql = "
			INSERT INTO kepeg_pendidikan_jenjang 
			(NAMA_JENJANG)
			VALUES 
			('$add_nama_jenjang')
		";

		$this->db->query($sql);
	}

	function hapus_jenjang($id_jenjang){
		$sql = "
		DELETE FROM kepeg_pendidikan_jenjang WHERE ID = $id_jenjang
		";
		$this->db->query($sql);
	}

	function add_bidang($add_nama_bidang){
		$sql = "
			INSERT INTO kepeg_pendidikan_bidang 
			(NAMA_BIDANG)
			VALUES 
			('$add_nama_bidang')
		";

		$this->db->query($sql);
	}

	function hapus_bidang($id_bidang){
		$sql = "
		DELETE FROM kepeg_pendidikan_bidang WHERE ID = $id_bidang
		";
		$this->db->query($sql);
	}

	function get_pangkat(){
		$sql = "
		SELECT * FROM kepeg_pangkat
		ORDER BY ID ASC
		";

		return $this->db->query($sql)->result();
	}

	function get_pangkat_max($id_pangkat_min){
		$sql = "
		SELECT * FROM kepeg_pangkat WHERE ID >= $id_pangkat_min
		ORDER BY ID ASC
		";
		return $this->db->query($sql)->result();
	}

}
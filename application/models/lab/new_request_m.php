<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class New_request_m extends CI_Model {

	function __construct()
	{
		parent::__construct(); 
		$this->load->database();
	}

	function ajax_rm($keyword){
		$where = "1=1";
		if($keyword != "" || $keyword != null){
			$where = $where." AND (KODE_PASIEN LIKE '%$keyword%' OR NAMA LIKE '%$keyword%' OR ALAMAT LIKE '%$keyword%')";
		}

		$sql = "
		SELECT * FROM rk_pasien
		WHERE $where
		ORDER BY ID DESC
		LIMIT 10
		"; 

		return $this->db->query($sql)->result();
	}

	function get_data_rm_by_id($id){ 
		$sql = "
		SELECT * FROM rk_pasien
		WHERE ID = $id
		";

		return $this->db->query($sql)->row();
	}

	function getJenisLab(){
		$sql = "
		SELECT * FROM lab_setup_laboratorium
		ORDER BY ID ASC
		";

		return $this->db->query($sql)->result();
	}

	function get_data_lab_by_id($id){
		$sql = "
		SELECT * FROM lab_setup_laboratorium WHERE ID = $id
		";
		return $this->db->query($sql)->row();
	}

	function simpan_pemeriksaan($nomor_periksa, $kode_pasien, $jml_biaya, $sts){
		$tgl = date('d-m-Y');
		$sql = "
		INSERT INTO lab_pemeriksaan
		(NOMOR_PERIKSA, KODE_PASIEN, TOTAL_BIAYA, STS, TGL)
		VALUES 
		('$nomor_periksa','$kode_pasien', $jml_biaya, $sts, '$tgl')
		";

		$this->db->query($sql);
	}

	function getIDPemeriksaan($nomor_periksa){
		$sql = "
		SELECT * FROM lab_pemeriksaan 
		WHERE NOMOR_PERIKSA = '$nomor_periksa'
		ORDER BY ID DESC LIMIT 1
		";

		return $this->db->query($sql)->row();
	}

	function simpan_pemeriksaan_detail($id_pemeriksaan, $kode_pasien, $id_lab, $biaya, $jenis_lab, $catatan){
		$catatan = addslashes($catatan);
		$jenis_lab = addslashes($jenis_lab);

		$sql = "
		INSERT INTO lab_pemeriksaan_detail
		(ID_PEMERIKSAAN, KODE_PASIEN, ID_SETUP_LAB, JENIS_LAB, BIAYA, CATATAN)
		VALUES 
		($id_pemeriksaan, '$kode_pasien', $id_lab, '$jenis_lab', $biaya, '$catatan')
		";

		$this->db->query($sql);
	}

	function cek_nomor_lab($bln, $thn){
		$sql = "
		SELECT * FROM lab_nomor 
		WHERE BULAN = $bln AND TAHUN = $thn
		";

		return $this->db->query($sql)->result();
	}

	function save_nomor_lab($bln, $thn){
		$sql = "
		INSERT INTO lab_nomor
			(NOMOR, BULAN, TAHUN)
		VALUES 
			(1, $bln, $thn)
		";

		$this->db->query($sql);
	}

	function get_nomor_lab($bln, $thn){
		$sql = "
		SELECT * FROM lab_nomor 
		WHERE BULAN = $bln AND TAHUN = $thn
		";

		return $this->db->query($sql)->row();
	}

	function update_nomor_lab($bln, $thn){
		$sql = "
		UPDATE lab_nomor SET NOMOR = NOMOR+1
		WHERE BULAN = $bln AND TAHUN = $thn
		";

		$this->db->query($sql);
	}

}

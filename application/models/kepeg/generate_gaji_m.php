<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Generate_gaji_m extends CI_Model {

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

	function get_jabatan(){
		$sql = "
		SELECT * FROM kepeg_jabatan
		ORDER BY ID ASC
		";

		return $this->db->query($sql)->result();
	}

	function get_data_tunjangan_by_jabatan($id_jabatan){
		$sql = "
		SELECT a.ID, a.NAMA_GAJI, IFNULL(a.NILAI, 0) AS NILAI FROM (
			SELECT a.ID, a.NAMA_GAJI, IFNULL(b.NILAI, 0) AS NILAI FROM abs_setup_gaji a
			LEFT JOIN (
				SELECT ID_GAJI, NILAI FROM abs_nilai_gaji_jabatan
				WHERE ID_JABATAN = $id_jabatan
			) b ON a.ID = b.ID_GAJI
		) a
		";

		return $this->db->query($sql)->result();
	}

	function get_data_tunjangan_by_pegawai($id_pegawai){
		$sql = "
		SELECT a.ID, a.NAMA_GAJI, IFNULL(a.NILAI, 0) AS NILAI FROM (
			SELECT a.ID, a.NAMA_GAJI, IFNULL(b.NILAI, 0) AS NILAI FROM abs_setup_gaji a
			LEFT JOIN (
				SELECT ID_GAJI, NILAI FROM abs_nilai_gaji_pegawai
				WHERE ID_PEGAWAI = $id_pegawai
			) b ON a.ID = b.ID_GAJI
		) a
		";

		return $this->db->query($sql)->result();
	}

	function delete_gaji_all($id_jabatan){
		$sql = "
		DELETE FROM abs_nilai_gaji_jabatan WHERE ID_JABATAN = $id_jabatan
		";

		$this->db->query($sql);
	}

	function delete_gaji_all_peg($id_pegawai){
		$sql = "
		DELETE FROM abs_nilai_gaji_pegawai WHERE ID_PEGAWAI = $id_pegawai
		";

		$this->db->query($sql);
	}

	function simpan_nilai_jab($id_jabatan, $id_gaji, $nilai){

		$nilai = str_replace(',', '', $nilai);

		$sql = "
		INSERT INTO abs_nilai_gaji_jabatan
		(ID_JABATAN, ID_GAJI, NILAI)
		VALUES 
		($id_jabatan, $id_gaji, $nilai)
		";

		$this->db->query($sql);
	}

	function simpan_nilai_peg($id_pegawai, $id_gaji, $nilai){
		$nilai = str_replace(',', '', $nilai);

		$sql = "
		INSERT INTO abs_nilai_gaji_pegawai
		(ID_PEGAWAI, ID_GAJI, NILAI)
		VALUES 
		($id_pegawai, $id_gaji, $nilai)
		";

		$this->db->query($sql);
	}

	function cek_data_tunj_peg($id_pegawai){
		$sql = "
		SELECT * FROM abs_nilai_gaji_pegawai WHERE ID_PEGAWAI = $id_pegawai
		";

		return $this->db->query($sql)->result();
	}

	function get_all_pegawai(){
		$sql = "
		SELECT * FROM kepeg_pegawai
		ORDER BY ID
		";

		return $this->db->query($sql)->result();
	}

	function cek_gaji_di_peg($id_pegawai){
		$sql = "
		SELECT a.*, b.NAMA_GAJI FROM abs_nilai_gaji_pegawai a 
		JOIN abs_setup_gaji b ON a.ID_GAJI = b.ID
		WHERE a.ID_PEGAWAI = $id_pegawai
		";

		return $this->db->query($sql)->result();
	}

	function cek_gaji_di_jab($id_jabatan){
		$sql = "
		SELECT a.*, b.NAMA_GAJI FROM abs_nilai_gaji_jabatan a 
		JOIN abs_setup_gaji b ON a.ID_GAJI = b.ID
		WHERE a.ID_JABATAN = $id_jabatan
		";

		return $this->db->query($sql)->result();
	}

	function simpan_gaji($id_gaji, $id_pegawai, $nama_gaji, $nilai, $bln, $thn){
		$sql = "
		INSERT INTO abs_gaji_pegawai
		(ID_GAJI, ID_PEGAWAI, NAMA_GAJI, NILAI, BULAN, TAHUN)
		VALUES 
		($id_gaji, $id_pegawai, '$nama_gaji', $nilai, $bln, $thn)
		";

		$this->db->query($sql);
	}

	function delete_gaji_pegawai_all($bln, $thn){
		$sql = "
		DELETE FROM abs_gaji_pegawai WHERE BULAN = $bln AND TAHUN = $thn
		";

		$this->db->query($sql);
	}

	function get_gapok_pegawai($id_pangkat){
		$sql = "
		SELECT * FROM kepeg_pangkat
		WHERE ID = '$id_pangkat'
		";

		return $this->db->query($sql)->row();
	}

	function cek_thr($bln, $thn){
		$sql = "
		SELECT * FROM kepeg_setup_thr
		WHERE TAHUN = $thn AND BULAN = $bln
		";

		return $this->db->query($sql)->result();
	}

}
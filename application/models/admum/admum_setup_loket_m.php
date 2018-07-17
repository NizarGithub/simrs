<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admum_setup_loket_m extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function getKodeAntrian(){
		$sql = "
		SELECT * FROM kepeg_setup_antrian
		ORDER BY ID ASC
		";

		return $this->db->query($sql)->result();
	}

	function cek_kode_antrian($val){
		$sql = "
		SELECT * FROM kepeg_setup_antrian
		WHERE KODE = '$val'
		";

		return $this->db->query($sql)->result();
	}

	function simpan_antrian($kode, $untuk, $antrian_max){
		$sql = "
		INSERT INTO kepeg_setup_antrian
		(KODE, UNTUK, ANTRIAN_MAX)
		VALUES 
		('$kode', '$untuk', $antrian_max)
		";

		$this->db->query($sql);
	}

	function data_antrian($keyword){
		$where = "1=1";
		if($keyword != ""){
			$where = $where." AND (KODE = '$keyword' OR UNTUK = '$keyword' OR ANTRIAN_MAX = '$keyword')";
		}

		$sql = "
		SELECT * FROM kepeg_setup_antrian
		WHERE $where 
		ORDER BY ID ASC
		";

		return $this->db->query($sql)->result();
	}

	function hapusLoket($id_hapus){
		$sql = "
		DELETE FROM kepeg_loket WHERE ID = $id_hapus
		";		
		$this->db->query($sql);
	}

	function simpan_loket($nama_loket, $kode_antrian){
		$sql = "
		INSERT INTO kepeg_loket
		(NAMA_LOKET, KODE_ANTRIAN)
		VALUES 
		('$nama_loket', '$kode_antrian')
		";

		$this->db->query($sql);
	}

	function getIDLoket(){
		$sql = "
		SELECT * FROM kepeg_loket
		ORDER BY ID DESC LIMIT 1
		";
		return $this->db->query($sql)->row();
	}

	function simpanOperator($id_loket, $id_opr){
		$sql = "
		INSERT INTO kepeg_loket_operator
		(ID_LOKET, ID_PEGAWAI)
		VALUES 
		($id_loket, $id_opr)
		";

		$this->db->query($sql);
	}

	function simpanAkses($id_loket, $akses){
		$sql = "
		INSERT INTO kepeg_loket_akses
		(ID_LOKET, AKSES)
		VALUES 
		($id_loket, '$akses')
		";

		$this->db->query($sql);
	}

	function getDataLoket(){
		$sql = "
		SELECT a.*, b.KODE, b.UNTUK FROM kepeg_loket a 
		JOIN kepeg_setup_antrian b ON a.KODE_ANTRIAN = b.ID
		ORDER BY a.ID ASC
		";

		return $this->db->query($sql)->result();
	}

	function getAksesMenu($id_loket){
		$sql = "
		SELECT * FROM kepeg_loket_akses
		WHERE ID_LOKET = $id_loket
		";

		return $this->db->query($sql)->result();
	}

	function data_loket_id($id){
		$sql = "
		SELECT * FROM kepeg_loket WHERE ID = $id
		";

		return $this->db->query($sql)->row();
	}

	function data_detail_operator($id_loket){
		$sql = "
			SELECT 
				a.ID_PEGAWAI,
				b.NIP,
				b.NAMA AS NAMA_OPERATOR,
				c.NAMA_DEP AS NAMA_DEP2,
				d.NAMA_DIV AS NAMA_DIV2,
				e.NAMA AS JABATAN
			FROM kepeg_loket_operator a
			LEFT JOIN kepeg_pegawai b ON b.ID = a.ID_PEGAWAI
			LEFT JOIN kepeg_departemen c ON c.ID = b.ID_DEPARTEMEN
			LEFT JOIN kepeg_divisi d ON d.ID = b.ID_DIVISI AND d.ID_DEPARTEMEN = c.ID
			LEFT JOIN kepeg_jabatan e ON e.ID = b.ID_JABATAN
			WHERE a.ID_LOKET = '$id_loket'
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function deleteAllOperator($id_loket){
		$sql = "
		DELETE FROM kepeg_loket_operator WHERE ID_LOKET = $id_loket
		";

		$this->db->query($sql);
	}

	function deleteAllAkses($id_loket){
		$sql = "
		DELETE FROM kepeg_loket_akses WHERE ID_LOKET = $id_loket
		";

		$this->db->query($sql);
	}

	function UpdateLoket($id, $nama_loket, $kode_antrian){
		$sql = "
		UPDATE kepeg_loket SET 
			NAMA_LOKET = '$nama_loket',
			KODE_ANTRIAN = '$kode_antrian'
		WHERE ID = $id
		";

		$this->db->query($sql);
	}
}
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admum_setup_nama_barang_m extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->load->database(); 
	}

	function data_kategori(){
		$sql = "SELECT * FROM log_kategori";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_peralatan($keyword){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND (a.NAMA_ALAT LIKE '%$keyword' OR a.KODE_ALAT LIKE '%$keyword%' OR b.NAMA_KATEGORI LIKE '%$keyword%')";
		}

		$sql = "
			SELECT 
				a.ID,
				a.KODE_ALAT,
				a.NAMA_ALAT,
				b.NAMA_KATEGORI
			FROM admum_setup_peralatan_medis a
			LEFT JOIN log_kategori b ON b.ID = a.ID_KATEGORI
			WHERE $where 
			ORDER BY a.ID DESC
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_peralatan_id($id){
		$sql = "
			SELECT 
				a.ID,
				a.KODE_ALAT,
				a.NAMA_ALAT,
				a.ID_KATEGORI,
				b.NAMA_KATEGORI
			FROM admum_setup_peralatan_medis a
			LEFT JOIN log_kategori b ON b.ID = a.ID_KATEGORI
			WHERE a.ID = '$id'
		";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function simpan($kode_alat,$nama_alat,$id_kategori){
		$sql = "
			INSERT INTO admum_setup_peralatan_medis(
				KODE_ALAT,
				NAMA_ALAT,
				ID_KATEGORI
			) VALUES (
				'$kode_alat',
				'$nama_alat',
				'$id_kategori'
			)
		";
		$this->db->query($sql);
	}

	function ubah($id,$nama_alat,$id_kategori){
		$sql = "
			UPDATE admum_setup_peralatan_medis SET 
				NAMA_ALAT = '$nama_alat',
				ID_KATEGORI = '$id_kategori'
			WHERE ID = '$id'";
		$this->db->query($sql);
	}

	function hapus($id){
		$sql = "DELETE FROM admum_setup_peralatan_medis WHERE ID = '$id'";
		$this->db->query($sql);
	}

	function cek_barcode($barcode){
		$sql = "SELECT COUNT(*) AS TOTAL FROM admum_setup_peralatan_medis WHERE BARCODE = '$barcode'";
		$query = $this->db->query($sql);
		return $query->row();
	}

}
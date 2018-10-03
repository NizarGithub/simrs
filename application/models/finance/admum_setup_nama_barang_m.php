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

	function data_merk($keyword){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND (MERK LIKE '%$keyword%' OR NAMA_SUPPLIER LIKE '%$keyword%' OR KODE_SUPPLIER LIKE '%$keyword%')";
		}else{
			$where = $where;
		}

		$sql = "SELECT * FROM admum_supplier_barang WHERE $where AND JENIS_BARANG = 'Peralatan Medis' ORDER BY ID DESC";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function klik_merk($id_merk){
		$sql = "SELECT * FROM admum_supplier_barang WHERE ID = '$id_merk'";
		$query = $this->db->query($sql);
		return $query->row();
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
				ALAT.ID,
				ALAT.KODE_ALAT,
				ALAT.BARCODE,
				ALAT.NAMA_ALAT,
				ALAT.ID_MERK,
				SUP.MERK,
				ALAT.JENIS_ALAT
			FROM admum_setup_peralatan_medis ALAT
			LEFT JOIN admum_supplier_barang SUP ON SUP.ID = ALAT.ID_MERK
			WHERE ALAT.ID = '$id'
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

	function ubah($id,$barcode,$nama_alat,$id_merk,$jenis_alat){
		$sql = "
			UPDATE admum_setup_peralatan_medis SET 
				BARCODE = '$barcode',
				NAMA_ALAT = '$nama_alat',
				ID_MERK = '$id_merk',
				JENIS_ALAT = '$jenis_alat'
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
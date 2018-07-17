<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admum_setup_peralatan_medis_m extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->load->database(); 
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
			$where = $where." AND (ALAT.NAMA_ALAT LIKE '%$keyword' OR ALAT.BARCODE LIKE '%$keyword%' OR ALAT.KODE_ALAT LIKE '%$keyword%')";
		}

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
			WHERE $where 
			ORDER BY ALAT.ID DESC
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

	function simpan($kode_alat,$barcode,$nama_alat,$merk,$jenis_alat){
		$sql = "
			INSERT INTO admum_setup_peralatan_medis(
				KODE_ALAT,
				BARCODE,
				NAMA_ALAT,
				ID_MERK,
				JENIS_ALAT
			) VALUES (
				'$kode_alat',
				'$barcode',
				'$nama_alat',
				'$merk',
				'$jenis_alat'
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
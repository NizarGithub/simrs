<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admum_setup_obat_m extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function data_merk($keyword){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND MERK LIKE '%$keyword%'";
		}else{
			$where = $where;
		}

		$sql = "SELECT * FROM obat_supplier WHERE $where ORDER BY ID DESC";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function klik_merk($id_merk){
		$sql = "SELECT * FROM obat_supplier WHERE ID = '$id_merk'";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function klik_jenis($id_jenis){
		$sql = "SELECT * FROM obat_jenis WHERE ID = '$id_jenis'";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function data_satuan(){
		$sql = "SELECT * FROM obat_satuan ORDER BY ID DESC";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function klik_satuan($id_satuan){
		$sql = "SELECT * FROM obat_satuan WHERE ID = '$id_satuan'";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function data_obat($keyword){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND (OBAT.NAMA_OBAT LIKE '%$keyword' OR OBAT.BARCODE LIKE '%$keyword%' OR OBAT.KODE_OBAT LIKE '%$keyword%')";
		}

		$sql = "
			SELECT
				OBAT.ID,
				OBAT.KODE_OBAT,
				OBAT.BARCODE,
				OBAT.NAMA_OBAT,
				SUP.MERK
			FROM admum_setup_nama_obat OBAT
			LEFT JOIN obat_supplier SUP ON SUP.ID = OBAT.ID_MERK
			WHERE $where
			ORDER BY OBAT.ID DESC
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_obat_id($id){
		$sql = "
			SELECT
				OBAT.ID,
				OBAT.KODE_OBAT,
				OBAT.BARCODE,
				OBAT.NAMA_OBAT,
				OBAT.ID_MERK,
				OBAT.ID_JENIS_OBAT,
				OBAT.EXPIRED,
				OBAT.GOLONGAN_OBAT,
				OBAT.KATEGORI_OBAT,
				OBAT.STATUS_OBAT,
				OBAT.SERVICE,
				OBAT.NO_BATCH,
				SUP.MERK,
				JENIS.NAMA_JENIS
			FROM admum_setup_nama_obat OBAT
			LEFT JOIN obat_supplier SUP ON SUP.ID = OBAT.ID_MERK
			LEFT JOIN obat_jenis JENIS ON JENIS.ID = OBAT.ID_JENIS_OBAT
			WHERE OBAT.ID = '$id'
		";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function simpan($kode_obat,$barcode,$nama_obat,$id_jenis,$expired,$golongan,$kategori,$status_obat,$service,$no_batch){
		$sql = "
			INSERT INTO admum_setup_nama_obat(
				KODE_OBAT,
				BARCODE,
				NAMA_OBAT,
				ID_JENIS_OBAT,
				EXPIRED,
				GOLONGAN_OBAT,
				KATEGORI_OBAT,
				STATUS_OBAT,
				SERVICE,
				NO_BATCH
			) VALUES (
				'$kode_obat',
				'$barcode',
				'$nama_obat',
				'$id_jenis',
				'$expired',
				'$golongan',
				'$kategori',
				'$status_obat',
				'$service',
				'$no_batch'
			)
		";
		$this->db->query($sql);
	}

	function ubah($id,$barcode,$nama_obat,$jenis,$expired,$golongan,$kategori,$status_racik,$service,$no_batch){
		$sql = "
			UPDATE admum_setup_nama_obat SET
				BARCODE = '$barcode',
				NAMA_OBAT = '$nama_obat',
				ID_JENIS_OBAT = '$jenis',
				EXPIRED = '$expired',
				GOLONGAN_OBAT = '$golongan',
				KATEGORI_OBAT = '$kategori',
				STATUS_OBAT = '$status_racik',
				SERVICE = '$service',
				NO_BATCH = '$no_batch'
			WHERE ID = '$id'";
		$this->db->query($sql);
	}

	function hapus($id){
		$sql = "DELETE FROM admum_setup_nama_obat WHERE ID = '$id'";
		$this->db->query($sql);
	}

	function cek_barcode($barcode){
		$sql = "SELECT COUNT(*) AS TOTAL FROM admum_setup_nama_obat WHERE BARCODE = '$barcode'";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function data_jenis_obat($keyword){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND NAMA_JENIS LIKE '%$keyword%'";
		}

		$sql = "SELECT * FROM obat_jenis WHERE $where ORDER BY ID DESC";
		$query = $this->db->query($sql);
		return $query->result();
	}
}

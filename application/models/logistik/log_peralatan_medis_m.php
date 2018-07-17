<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Log_peralatan_medis_m extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function load_nama_alat($keyword){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND (MEDIS.KODE_ALAT LIKE '%$keyword%' OR MEDIS.BARCODE LIKE '%$keyword%' OR MEDIS.NAMA_ALAT LIKE '%$keyword%')";
		}

		$sql = "
			SELECT
				MEDIS.ID,
				MEDIS.KODE_ALAT,
				MEDIS.BARCODE,
				MEDIS.NAMA_ALAT,
				SUP.MERK,
				MEDIS.JENIS_ALAT
			FROM admum_setup_peralatan_medis MEDIS
			LEFT JOIN admum_supplier_barang SUP ON SUP.ID = MEDIS.ID_MERK
			WHERE $where
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function klik_nama_alat($id){
		$sql = "
			SELECT
				MEDIS.ID,
				MEDIS.KODE_ALAT,
				MEDIS.BARCODE,
				MEDIS.NAMA_ALAT,
				SUP.MERK,
				MEDIS.JENIS_ALAT,
				ALAT.ID AS ID_ALAT,
				SAT.ID AS ID_SATUAN,
				SAT.NAMA_SATUAN,
				ALAT.PEMAKAIAN,
				ALAT.JUMLAH,
				ALAT.ISI,
				ALAT.TOTAL,
				ALAT.HARGA_BELI,
				ALAT.GAMBAR
			FROM admum_setup_peralatan_medis MEDIS
			LEFT JOIN admum_supplier_barang SUP ON SUP.ID = MEDIS.ID_MERK
			LEFT JOIN (
				SELECT * FROM log_peralatan_medis WHERE AKTIF = '1'
			) ALAT ON ALAT.ID_SETUP_NAMA_ALAT = MEDIS.ID
			LEFT JOIN obat_satuan SAT ON SAT.ID = ALAT.ID_SATUAN_ALAT
			WHERE MEDIS.ID = '$id'
		";
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

	function data_peralatan($keyword,$urutkan,$urutkan_stok){
		$where = "1 = 1";
		$order = "";

		if($urutkan == 'Default'){
			$order = "ORDER BY MEDIS.URUT_BARANG ASC";
		}else if($urutkan == 'Nama Alat'){
			$order = "ORDER BY ALAT.NAMA_ALAT ASC";
		}else if($urutkan == 'Stok'){
			if($urutkan_stok == 'Rendah'){
				$order = "ORDER BY MEDIS.TOTAL ASC";
			}else if($urutkan_stok == 'Tinggi'){
				$order = "ORDER BY MEDIS.TOTAL DESC";
			}
		}

		if($keyword != ""){
			$where = $where." AND (ALAT.KODE_ALAT LIKE '%$keyword%' OR ALAT.BARCODE LIKE '%$keyword%' OR ALAT.NAMA_ALAT LIKE '%$keyword')";
		}

		$sql = "
			SELECT
				MEDIS.ID,
				ALAT.KODE_ALAT,
				ALAT.BARCODE,
				ALAT.NAMA_ALAT,
				ALAT.JENIS_ALAT,
				SAT.NAMA_SATUAN,
				MEDIS.PEMAKAIAN,
				MEDIS.JUMLAH,
				MEDIS.ISI,
				MEDIS.TOTAL,
				MEDIS.SATUAN_ISI,
				MEDIS.HARGA_BELI,
				MEDIS.TANGGAL_MASUK,
				MEDIS.WAKTU_MASUK,
				MEDIS.AKTIF,
				MEDIS.FIRST_OUT,
				MEDIS.URUT_BARANG,
				MEDIS.GAMBAR
			FROM log_peralatan_medis MEDIS
			LEFT JOIN admum_setup_peralatan_medis ALAT ON ALAT.ID = MEDIS.ID_SETUP_NAMA_ALAT
			LEFT JOIN obat_satuan SAT ON SAT.ID = MEDIS.ID_SATUAN_ALAT
			WHERE $where
			$order
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function simpan(
		$id_setup_nama_alat,
		$id_satuan,
		$pemakaian,
		$jumlah,
		$isi,
		$total,
		$satuan_isi,
		$harga_beli,
		$tanggal_masuk,
		$waktu_masuk,
		$aktif,
		$first_out,
		$urut_barang,
		$gambar){

		$sql = "
			INSERT INTO log_peralatan_medis(
				ID_SETUP_NAMA_ALAT,
				ID_SATUAN_ALAT,
				PEMAKAIAN,
				JUMLAH,
				ISI,
				TOTAL,
				SATUAN_ISI,
				HARGA_BELI,
				TANGGAL_MASUK,
				WAKTU_MASUK,
				AKTIF,
				FIRST_OUT,
				URUT_BARANG,
				GAMBAR
			) VALUES(
				'$id_setup_nama_alat',
				'$id_satuan',
				'$pemakaian',
				'$jumlah',
				'$isi',
				'$total',
				'$satuan_isi',
				'$harga_beli',
				'$tanggal_masuk',
				'$waktu_masuk',
				'$aktif',
				'$first_out',
				'$urut_barang',
				'$gambar'
			)";
		$this->db->query($sql);
	}

}
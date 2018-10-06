<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stok_awal_barang_m extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->load->database(); 
	}

	function data_barang(){
		$sql = "
			SELECT
				a.ID,
				a.ID_BARANG,
				b.KODE_ALAT,
				b.NAMA_ALAT,
				b.NAMA_KATEGORI,
				a.TOTAL,
				a.HARGA_BELI,
				a.KETERANGAN
			FROM log_gudang_barang a
			LEFT JOIN (
				SELECT
					a.ID,
					a.KODE_ALAT,
					a.NAMA_ALAT,
					b.NAMA_KATEGORI
				FROM admum_setup_peralatan_medis a
				JOIN log_kategori b ON b.ID = a.ID_KATEGORI
			) b ON b.ID = a.ID_BARANG
			ORDER BY a.ID ASC
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function ubah($id_gudang,$stok,$keterangan){
		$sql = "UPDATE log_gudang_barang SET JUMLAH = '$stok', ISI = '1', TOTAL = '$stok', KETERANGAN = '$keterangan' WHERE ID = '$id_gudang'";
		$this->db->query($sql);
	}

}
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ap_laporan_m extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	//LAPORAN PENJUALAN

	function data_laporan($pilihan,$bulan,$now){
		$where = "1 = 1";

		if($pilihan == 'bulanan'){
			$where = $where." AND BULAN = '$bulan'";
		}else{
			$where = $where." AND STR_TO_DATE(TANGGAL,'%d-%m-%Y') = '$now'";
		}

		$sql = "
			SELECT
				*
			FROM apotek_transaksi
			WHERE $where
			ORDER BY ID DESC
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_trx_obat($id_transaksi){
		$sql = "
			SELECT
				TRX.ID,
				TRX.ID_TRANSAKSI,
				TRX.ID_OBAT,
				STP.KODE_OBAT,
				STP.NAMA_OBAT,
				TRX.HARGA,
				TRX.JUMLAH_BELI,
				TRX.SUBTOTAL
			FROM apotek_transaksi_detail TRX
			LEFT JOIN apotek_gudang_obat OBAT ON OBAT.ID = TRX.ID_OBAT
			LEFT JOIN admum_setup_nama_obat STP ON STP.ID = OBAT.ID_SETUP_NAMA_OBAT
			WHERE TRX.ID_TRANSAKSI = '$id_transaksi'
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	// STOK OBAT

	function data_obat_cetak($keyword,$urutkan,$urutkan_stok){
		$where = "1 = 1";
		$order = "";

		if($urutkan == 'Default'){
			$order = "ORDER BY OBAT.ID ASC, STR_TO_DATE('%d-%m-%Y',OBAT.EXPIRED) ASC";
		}else if($urutkan == 'Nama Obat'){
			$order = "ORDER BY NM_OBT.NAMA_OBAT ASC";
		}else if($urutkan == 'Stok'){
			if($urutkan_stok == 'Rendah'){
				$order = "ORDER BY OBAT.STOK ASC";
			}else{
				$order = "ORDER BY OBAT.STOK DESC";
			}
		}else if($urutkan == 'Expired'){
			$order = "ORDER BY STR_TO_DATE('%d-%m-%Y',OBAT.EXPIRED) ASC";
		}

		if($keyword != ""){
			$where = $where." AND (NM_OBT.NAMA_OBAT LIKE '%$keyword' OR NM_OBT.BARCODE LIKE '%$keyword%' OR NM_OBT.KODE_OBAT LIKE '%$keyword%')";
		}

		$sql = "SELECT
							OBAT.ID,
							NM_OBT.KODE_OBAT,
							NM_OBT.BARCODE,
							NM_OBT.NAMA_OBAT,
							NM_OBT.ID_JENIS_OBAT,
							OBAT.EXPIRED AS KADALUARSA,
							OBAT.STOK AS TOTAL,
							STR_TO_DATE(OBAT.EXPIRED,'%d-%m-%Y') AS KADALUARSA_BALIK
						FROM apotek_gudang_obat OBAT
						LEFT JOIN admum_setup_nama_obat NM_OBT ON NM_OBT.ID = OBAT.ID_SETUP_NAMA_OBAT
						LEFT JOIN obat_supplier SUP ON SUP.ID = NM_OBT.ID_MERK
						WHERE $where
						$order
					";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_obat($keyword,$urutkan,$urutkan_stok){
		$where = "1 = 1";
		$order = "";

		if($urutkan == 'Default'){
			$order = "ORDER BY OBAT.ID ASC, STR_TO_DATE('%d-%m-%Y',OBAT.EXPIRED) ASC";
		}else if($urutkan == 'Nama Obat'){
			$order = "ORDER BY NM_OBT.NAMA_OBAT ASC";
		}else if($urutkan == 'Stok'){
			if($urutkan_stok == 'Rendah'){
				$order = "ORDER BY OBAT.STOK ASC";
			}else{
				$order = "ORDER BY OBAT.STOK DESC";
			}
		}else if($urutkan == 'Expired'){
			$order = "ORDER BY STR_TO_DATE('%d-%m-%Y',OBAT.EXPIRED) ASC";
		}

		if($keyword != ""){
			$where = $where." AND (NM_OBT.NAMA_OBAT LIKE '%$keyword%' OR NM_OBT.BARCODE LIKE '%$keyword%' OR NM_OBT.KODE_OBAT LIKE '%$keyword%')";
		}

		$sql = "SELECT
							OBAT.ID,
							NM_OBT.KODE_OBAT,
							NM_OBT.BARCODE,
							NM_OBT.NAMA_OBAT,
							NM_OBT.ID_JENIS_OBAT,
							OBAT.EXPIRED,
							STR_TO_DATE(OBAT.EXPIRED,'%d-%m-%Y') AS KADALUARSA_BALIK,
							SUP.NAMA_SUPPLIER,
							OBAT.STOK AS TOTAL
						FROM apotek_gudang_obat OBAT
						LEFT JOIN admum_setup_nama_obat NM_OBT ON NM_OBT.ID = OBAT.ID_SETUP_NAMA_OBAT
						LEFT JOIN obat_supplier SUP ON SUP.ID = NM_OBT.ID_MERK
						WHERE $where
						$order
					";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_gudang_obat($keyword,$urutkan,$urutkan_stok){
		$where = "1 = 1";
		$order = "";
		if($urutkan == 'Default'){
			$order = "ORDER BY OBAT.ID ASC, STR_TO_DATE('%d-%m-%Y',OBAT.EXPIRED) ASC";
		}else if($urutkan == 'Nama Obat'){
			$order = "ORDER BY NM_OBT.NAMA_OBAT ASC";
		}else if($urutkan == 'Stok'){
			if($urutkan_stok == 'Rendah'){
				$order = "ORDER BY OBAT.STOK ASC";
			}else{
				$order = "ORDER BY OBAT.STOK DESC";
			}
		}else if($urutkan == 'Expired'){
			$order = "ORDER BY STR_TO_DATE('%d-%m-%Y',OBAT.EXPIRED) ASC";
		}
		if($keyword != ""){
			$where = $where." AND (NM_OBT.NAMA_OBAT LIKE '%$keyword' OR NM_OBT.BARCODE LIKE '%$keyword%' OR NM_OBT.KODE_OBAT LIKE '%$keyword%')";
		}
		$sql = "SELECT
							OBAT.ID,
							NM_OBT.KODE_OBAT,
							NM_OBT.BARCODE,
							NM_OBT.NAMA_OBAT,
							NM_OBT.ID_JENIS_OBAT AS NAMA_JENIS,
							NM_OBT.SERVICE,
							OBAT.EXPIRED AS KADALUARSA,
							NM_OBT.STATUS_OBAT AS STATUS_RACIK,
							NM_OBT.GOLONGAN_OBAT,
							NM_OBT.KATEGORI_OBAT,
							STR_TO_DATE(OBAT.EXPIRED,'%d-%m-%Y') AS KADALUARSA_BALIK,
							OBAT.STOK AS TOTAL,
							OBAT.HARGA_BELI,
							OBAT.HARGA_BULAT,
							OBAT.TANGGAL_MASUK,
							( OBAT.HARGA_BULAT + NM_OBT.SERVICE ) AS TOTAL_HARGA
						FROM apotek_gudang_obat OBAT
						LEFT JOIN admum_setup_nama_obat NM_OBT ON NM_OBT.ID = OBAT.ID_SETUP_NAMA_OBAT
						LEFT JOIN obat_supplier SUP ON SUP.ID = NM_OBT.ID_MERK
						WHERE $where
						$order
					";
		$query = $this->db->query($sql);
		return $query->result();
	}
	function data_gudang_obat_row($keyword,$urutkan,$urutkan_stok){
		$where = "1 = 1";
		$order = "";
		if($urutkan == 'Default'){
			$order = "ORDER BY OBAT.ID ASC, STR_TO_DATE('%d-%m-%Y',OBAT.EXPIRED) ASC";
		}else if($urutkan == 'Nama Obat'){
			$order = "ORDER BY NM_OBT.NAMA_OBAT ASC";
		}else if($urutkan == 'Stok'){
			if($urutkan_stok == 'Rendah'){
				$order = "ORDER BY OBAT.STOK ASC";
			}else{
				$order = "ORDER BY OBAT.STOK DESC";
			}
		}else if($urutkan == 'Expired'){
			$order = "ORDER BY STR_TO_DATE('%d-%m-%Y',OBAT.EXPIRED) ASC";
		}
		if($keyword != ""){
			$where = $where." AND (NM_OBT.NAMA_OBAT LIKE '%$keyword' OR NM_OBT.BARCODE LIKE '%$keyword%' OR NM_OBT.KODE_OBAT LIKE '%$keyword%')";
		}
		$sql = "SELECT
							OBAT.ID,
							NM_OBT.KODE_OBAT,
							NM_OBT.BARCODE,
							NM_OBT.NAMA_OBAT,
							NM_OBT.ID_JENIS_OBAT AS NAMA_JENIS,
							NM_OBT.SERVICE,
							OBAT.EXPIRED AS KADALUARSA,
							STR_TO_DATE(OBAT.EXPIRED,'%d-%m-%Y') AS KADALUARSA_BALIK,
							OBAT.STOK AS TOTAL,
							OBAT.HARGA_BELI,
							OBAT.HARGA_BULAT,
							( OBAT.HARGA_BULAT + NM_OBT.SERVICE ) AS TOTAL_HARGA
						FROM apotek_gudang_obat OBAT
						LEFT JOIN admum_setup_nama_obat NM_OBT ON NM_OBT.ID = OBAT.ID_SETUP_NAMA_OBAT
						LEFT JOIN obat_supplier SUP ON SUP.ID = NM_OBT.ID_MERK
						WHERE $where
						$order
						";
		$query = $this->db->query($sql);
		return $query->result();
	}

}

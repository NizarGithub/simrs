<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ap_gudang_obat_m extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function data_nama_obat($keyword){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND (NM_OBT.KODE_OBAT LIKE '%$keyword%' OR NM_OBT.NAMA_OBAT LIKE '%$keyword%' OR NM_OBT.BARCODE LIKE '%$keyword%')";
		}else{
			$where = $where;
		}

		$sql = "
			SELECT
				NM_OBT.ID,
				NM_OBT.KODE_OBAT,
				NM_OBT.BARCODE,
				NM_OBT.NAMA_OBAT,
				NM_OBT.ID_MERK,
				SUP.MERK
			FROM admum_setup_nama_obat NM_OBT
			LEFT JOIN obat_supplier SUP ON SUP.ID = NM_OBT.ID_MERK
			WHERE $where
			ORDER BY NM_OBT.ID DESC
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function klik_nama_obat($id){
		$sql = "SELECT
						NM_OBT.ID,
						NM_OBT.KODE_OBAT,
						NM_OBT.BARCODE,
						NM_OBT.NAMA_OBAT,
						NM_OBT.EXPIRED,
						NM_OBT.GOLONGAN_OBAT,
						NM_OBT.KATEGORI_OBAT,
						NM_OBT.SERVICE,
						JENIS.NAMA_JENIS
					FROM admum_setup_nama_obat NM_OBT
					LEFT JOIN obat_jenis JENIS ON JENIS.ID = NM_OBT.ID_JENIS_OBAT
					WHERE NM_OBT.ID = '$id'
		";
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

	function data_obat($keyword,$urutkan,$urutkan_stok){
		$where = "1 = 1";
		$order = "";

		if($urutkan == 'Default'){
			$order = "ORDER BY OBAT.ID ASC, STR_TO_DATE('%d-%m-%Y',OBAT.KADALUARSA) ASC";
		}else if($urutkan == 'Nama Obat'){
			$order = "ORDER BY NM_OBT.NAMA_OBAT ASC";
		}else if($urutkan == 'Stok'){
			if($urutkan_stok == 'Rendah'){
				$order = "ORDER BY OBAT.TOTAL ASC";
			}else{
				$order = "ORDER BY OBAT.TOTAL DESC";
			}
		}else if($urutkan == 'Expired'){
			$order = "ORDER BY STR_TO_DATE('%d-%m-%Y',OBAT.KADALUARSA) ASC";
		}

		if($keyword != ""){
			$where = $where." AND (NM_OBT.NAMA_OBAT LIKE '%$keyword' OR NM_OBT.BARCODE LIKE '%$keyword%' OR NM_OBT.KODE_OBAT LIKE '%$keyword%')";
		}

		$sql = "
			SELECT
				OBAT.ID,
				NM_OBT.KODE_OBAT,
				NM_OBT.BARCODE,
				NM_OBT.NAMA_OBAT,
				SUP.MERK,
				JENIS.NAMA_JENIS,
				SAT.NAMA_SATUAN,
				OBAT.JUMLAH,
				OBAT.ISI,
				OBAT.TOTAL,
				OBAT.SATUAN_ISI,
				OBAT.JUMLAH_BUTIR,
				OBAT.SATUAN_BUTIR,
				OBAT.HARGA_BELI,
				OBAT.HARGA_JUAL,
				OBAT.KADALUARSA,
				OBAT.TANGGAL_MASUK,
				OBAT.WAKTU_MASUK,
				OBAT.AKTIF,
				OBAT.URUT_BARANG,
				OBAT.STATUS_RACIK,
				OBAT.GAMBAR
			FROM apotek_gudang_obat OBAT
			LEFT JOIN admum_setup_nama_obat NM_OBT ON NM_OBT.ID = OBAT.ID_SETUP_NAMA_OBAT
			LEFT JOIN obat_supplier SUP ON SUP.ID = NM_OBT.ID_MERK
			LEFT JOIN obat_jenis JENIS ON JENIS.ID = OBAT.ID_JENIS_OBAT
			LEFT JOIN obat_satuan SAT ON SAT.ID = OBAT.ID_SATUAN_OBAT
			WHERE $where
			$order
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_obat_xls(){


		$sql = "
			SELECT
				OBAT.ID,
				NM_OBT.KODE_OBAT,
				NM_OBT.BARCODE,
				NM_OBT.NAMA_OBAT,
				SUP.MERK,
				JENIS.NAMA_JENIS,
				SAT.NAMA_SATUAN,
				OBAT.JUMLAH,
				OBAT.ISI,
				OBAT.TOTAL,
				OBAT.SATUAN_ISI,
				OBAT.JUMLAH_BUTIR,
				OBAT.SATUAN_BUTIR,
				OBAT.HARGA_BELI,
				OBAT.HARGA_JUAL,
				OBAT.KADALUARSA,
				OBAT.TANGGAL_MASUK,
				OBAT.WAKTU_MASUK,
				OBAT.AKTIF,
				OBAT.URUT_BARANG,
				OBAT.STATUS_RACIK,
				OBAT.GAMBAR
			FROM apotek_gudang_obat OBAT
			LEFT JOIN admum_setup_nama_obat NM_OBT ON NM_OBT.ID = OBAT.ID_SETUP_NAMA_OBAT
			LEFT JOIN obat_supplier SUP ON SUP.ID = NM_OBT.ID_MERK
			LEFT JOIN obat_jenis JENIS ON JENIS.ID = OBAT.ID_JENIS_OBAT
			LEFT JOIN obat_satuan SAT ON SAT.ID = OBAT.ID_SATUAN_OBAT
			ORDER BY OBAT.ID ASC, STR_TO_DATE('%d-%m-%Y',OBAT.KADALUARSA) ASC
		";
		return $this->db->query($sql)->result();
	}

	function data_obat_id($id){
		$sql = "
			SELECT
				OBAT.ID,
				OBAT.ID_SETUP_NAMA_OBAT,
				NM_OBT.KODE_OBAT,
				NM_OBT.BARCODE,
				NM_OBT.NAMA_OBAT,
				NM_OBT.ID_MERK,
				SUP.MERK,
				OBAT.ID_JENIS_OBAT,
				JENIS.NAMA_JENIS,
				OBAT.ID_SATUAN_OBAT,
				SAT.NAMA_SATUAN,
				OBAT.JUMLAH,
				OBAT.ISI,
				OBAT.TOTAL,
				OBAT.SATUAN_ISI,
				OBAT.JUMLAH_BUTIR,
				OBAT.SATUAN_BUTIR,
				OBAT.HARGA_BELI,
				OBAT.HARGA_JUAL,
				OBAT.KADALUARSA,
				OBAT.TANGGAL_MASUK,
				OBAT.WAKTU_MASUK,
				OBAT.STATUS_RACIK,
				OBAT.GAMBAR,
				OBAT.ID_GOLONGAN,
				OBAT.ID_KATEGORI
			FROM apotek_gudang_obat OBAT
			LEFT JOIN admum_setup_nama_obat NM_OBT ON NM_OBT.ID = OBAT.ID_SETUP_NAMA_OBAT
			LEFT JOIN obat_supplier SUP ON SUP.ID = NM_OBT.ID_MERK
			LEFT JOIN obat_jenis JENIS ON JENIS.ID = OBAT.ID_JENIS_OBAT
			LEFT JOIN obat_satuan SAT ON SAT.ID = OBAT.ID_SATUAN_OBAT
			WHERE OBAT.ID = '$id'
		";
		$query = $this->db->query($sql);
		return $query->row();
	}



	function simpan(
		$value,
		$jumlah,
		$isi,
		$total,
		$jumlah_butir,
		$harga_pertablet,
		$harga_beli,
		$harga_jual,
		$tanggal_masuk,
		$waktu_masuk,
		$aktif,
		$first_out
	){

		$sql = "
			INSERT INTO apotek_gudang_obat(
				ID_SETUP_NAMA_OBAT,
				JUMLAH,
				ISI,
				TOTAL,
				JUMLAH_BUTIR,
				HARGA_PERTABLET,
				HARGA_BELI,
				HARGA_JUAL,
				TANGGAL_MASUK,
				WAKTU_MASUK,
				AKTIF,
				FIRST_OUT
			) VALUES (
				'$value',
				'$jumlah',
				'$isi',
				'$total',
				'$jumlah_butir',
				'$harga_pertablet',
				'$harga_beli',
				'$harga_jual',
				'$tanggal_masuk',
				'$waktu_masuk',
				'$aktif',
				'$first_out'
			)
		";
		$this->db->query($sql);
	}

	function ubah($id,$id_nama_obat,$id_jenis,$id_satuan,$jumlah,$isi,$total,$jumlah_butir,$harga_beli,$harga_jual,$kadaluarsa,$status_racik,$gambar,$id_golongan,$id_kategori){
		$sql = "
			UPDATE apotek_gudang_obat SET
				ID_SETUP_NAMA_OBAT = '$id_nama_obat',
				ID_JENIS_OBAT = '$id_jenis',
				ID_SATUAN_OBAT = '$id_satuan',
				JUMLAH = '$jumlah',
				ISI = '$isi',
				TOTAL = '$total',
				JUMLAH_BUTIR = '$jumlah_butir',
				HARGA_BELI = '$harga_beli',
				HARGA_JUAL = '$harga_jual',
				KADALUARSA = '$kadaluarsa',
				STATUS_RACIK = '$status_racik',
				GAMBAR = '$gambar',
				ID_GOLONGAN = '$id_golongan',
				ID_KATEGORI = '$id_kategori'
			WHERE ID = '$id'
		";
		$this->db->query($sql);
	}

	function hapus($id){
		$sql = "DELETE FROM apotek_gudang_obat WHERE ID = '$id'";
		$this->db->query($sql);
	}

	function data_nama_supplier($keyword){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND (a.KODE_SUPPLIER LIKE '%$keyword%' OR a.NAMA_SUPPLIER LIKE '%$keyword%')";
		}else{
			$where = $where;
		}

		$sql = "SELECT
							a.ID,
							a.KODE_SUPPLIER,
							a.NAMA_SUPPLIER
						FROM obat_supplier a
						WHERE $where
						ORDER BY a.ID DESC
					";
		$query = $this->db->query($sql);
		return $query->result();
	}
}

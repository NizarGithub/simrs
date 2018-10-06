<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data_barang_m extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function load_nama_alat($keyword){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND (a.KODE_ALAT LIKE '%$keyword%' OR a.NAMA_ALAT LIKE '%$keyword%' OR b.NAMA_KATEGORI LIKE '%$keyword%')";
		}

		$sql = "
			SELECT
				a.ID,
				a.KODE_ALAT,
				a.NAMA_ALAT,
				b.NAMA_KATEGORI
			FROM admum_setup_peralatan_medis a
			JOIN log_kategori b ON b.ID = a.ID_KATEGORI
			WHERE $where
			ORDER BY a.ID ASC
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function klik_nama_alat($id){
		$sql = "
			SELECT
				MEDIS.ID,
				MEDIS.KODE_ALAT,
				MEDIS.NAMA_ALAT,
				a.NAMA_KATEGORI
			FROM admum_setup_peralatan_medis MEDIS
			LEFT JOIN log_kategori a ON MEDIS.ID_KATEGORI = a.ID
			WHERE MEDIS.ID = '$id'
		";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function data_satuan($keyword){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND NAMA_SATUAN LIKE '%$keyword%'";
		}

		$sql = "SELECT * FROM admum_satuan_barang WHERE $where ORDER BY ID DESC";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function klik_satuan($id_satuan){
		$sql = "SELECT * FROM admum_satuan_barang WHERE ID = '$id_satuan'";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function data_departemen($keyword){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND NAMA_DEP LIKE '%$keyword%'";
		}

		$sql = "SELECT * FROM kepeg_departemen WHERE $where ORDER BY ID DESC";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function klik_departemen($id_departemen){
		$sql = "SELECT * FROM kepeg_departemen WHERE ID = '$id_departemen'";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function data_divisi($keyword,$id_departemen){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND NAMA_DIV LIKE '%$keyword%'";
		}

		$sql = "SELECT * FROM kepeg_divisi WHERE $where AND ID_DEPARTEMEN = '$id_departemen' ORDER BY ID DESC";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function klik_divisi($id_divisi){
		$sql = "SELECT * FROM kepeg_divisi WHERE ID = '$id_divisi'";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function data_peralatan($keyword,$urutkan,$urutkan_stok){
		$where = "1 = 1";
		$order = "";

		if($urutkan == 'Default'){
			$order = "ORDER BY a.ID DESC";
		}else if($urutkan == 'Nama Alat'){
			$order = "ORDER BY b.NAMA_ALAT ASC";
		}else if($urutkan == 'Stok'){
			if($urutkan_stok == 'Rendah'){
				$order = "ORDER BY a.TOTAL ASC";
			}else{
				$order = "ORDER BY a.TOTAL DESC";
			}
		}

		if($keyword != ""){
			$where = $where." AND b.NAMA_ALAT LIKE '%$keyword%' OR b.NAMA_KATEGORI LIKE '%$keyword%'";
		}

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
			WHERE $where
			$order
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_peralatan_id($id){
		$sql = "
			SELECT
				a.ID,
				a.ID_BARANG,
				b.KODE_ALAT,
				b.NAMA_ALAT,
				b.NAMA_KATEGORI,
				a.MERK,
				a.GOLONGAN,
				a.JUMLAH,
				a.ISI,
				a.TOTAL,
				a.HARGA_BELI,
				a.ID_DEPARTEMEN,
				a.ID_DIVISI,
				a.ID_SATUAN,
				a.GAMBAR,
				a.KETERANGAN,
				c.NAMA_DEP,
				d.NAMA_DIV,
				e.NAMA_SATUAN
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
			LEFT JOIN kepeg_departemen c ON c.ID = a.ID_DEPARTEMEN
			LEFT JOIN kepeg_divisi d ON d.ID = a.ID_DIVISI
			LEFT JOIN admum_satuan_barang e ON e.ID = a.ID_SATUAN
			WHERE a.ID = '$id'
		";
		$qry = $this->db->query($sql);
		return $qry->row();
	}

	function simpan(
		$id_barang,
		$id_satuan,
		$golongan,
		$merk,
		$jumlah,
		$isi,
		$total,
		$harga_beli,
		$total_harga,
		$tanggal_masuk,
		$waktu_masuk,
		$bulan,
		$tahun,
		$gambar){
		
		$sql = "
			INSERT INTO log_gudang_barang(
				ID_BARANG,
				ID_SATUAN,
				GOLONGAN,
				MERK,
				JUMLAH,
				ISI,
				TOTAL,
				HARGA_BELI,
				TOTAL_HARGA,
				TANGGAL_MASUK,
				WAKTU_MASUK,
				BULAN,
				TAHUN,
				GAMBAR,
				AKTIF
			) VALUES(
				'$id_barang',
				'$id_satuan',
				'$golongan',
				'$merk',
				'$jumlah',
				'$isi',
				'$total',
				'$harga_beli',
				'$total_harga',
				'$tanggal_masuk',
				'$waktu_masuk',
				'$bulan',
				'$tahun',
				'$gambar',
				'1'
		)";
		$this->db->query($sql);
	}

	function ubah($id,$id_barang,$id_satuan,$golongan,$merk,$jumlah,$isi,$total,$harga_beli,$total_harga,$gambar,$keterangan){
		$sql = "
			UPDATE log_gudang_barang SET
				ID_BARANG = '$id_barang',
				ID_SATUAN = '$id_satuan',
				GOLONGAN = '$golongan',
				MERK = '$merk',
				JUMLAH = '$jumlah',
				ISI = '$isi',
				TOTAL = '$total',
				HARGA_BELI = '$harga_beli',
				TOTAL_HARGA = '$total_harga',
				GAMBAR = '$gambar',
				KETERANGAN = '$keterangan'
			WHERE ID = '$id'
		";
		$this->db->query($sql);	
	}

	function hapus($id){
		$sql = "DELETE FROM log_gudang_barang WHERE ID = '$id'";
		$this->db->query($sql);
	}

}

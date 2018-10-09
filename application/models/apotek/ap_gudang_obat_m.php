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
						NM_OBT.GOLONGAN_OBAT,
						NM_OBT.KATEGORI_OBAT,
						NM_OBT.SERVICE,
						NM_OBT.ID_JENIS_OBAT
						-- JENIS.NAMA_JENIS
					FROM admum_setup_nama_obat NM_OBT
					-- LEFT JOIN obat_jenis JENIS ON JENIS.ID = NM_OBT.ID_JENIS_OBAT
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
			$order = "ORDER BY OBAT.ID ASC, STR_TO_DATE('%d-%m-%Y',NM_OBT.EXPIRED) ASC";
		}else if($urutkan == 'Nama Obat'){
			$order = "ORDER BY NM_OBT.NAMA_OBAT ASC";
		}else if($urutkan == 'Stok'){
			if($urutkan_stok == 'Rendah'){
				$order = "ORDER BY OBAT.TOTAL ASC";
			}else{
				$order = "ORDER BY OBAT.TOTAL DESC";
			}
		}else if($urutkan == 'Expired'){
			$order = "ORDER BY STR_TO_DATE('%d-%m-%Y',NM_OBT.EXPIRED) ASC";
		}

		if($keyword != ""){
			$where = $where." AND (SUPLY.NAMA_SUPPLIER LIKE '%$keyword' OR FAKTUR.NO_FAKTUR LIKE '%$keyword%')";
		}

		$sql = "SELECT
							OBAT.ID,
							NM_OBT.KODE_OBAT,
							NM_OBT.BARCODE,
							NM_OBT.NAMA_OBAT,
							JENIS.NAMA_JENIS,
							OBAT.JUMLAH,
							OBAT.ISI,
							OBAT.TOTAL,
							OBAT.JUMLAH_BUTIR,
							OBAT.HARGA_BELI,
							OBAT.HARGA_JUAL,
							OBAT.HARGA_PERTABLET,
							OBAT.TANGGAL_MASUK,
							OBAT.WAKTU_MASUK,
							FAKTUR.ID AS ID_FAKTUR,
							FAKTUR.NO_FAKTUR,
							FAKTUR.TANGGAL AS TANGGAL_FAKTUR,
							FAKTUR.WAKTU AS WAKTU_FAKTUR,
							FAKTUR.TOTAL AS TOTAL_FAKTUR,
							SUPLY.NAMA_SUPPLIER
						FROM faktur_detail OBAT
						LEFT JOIN admum_setup_nama_obat NM_OBT ON NM_OBT.ID = OBAT.ID_SETUP_NAMA_OBAT
						LEFT JOIN obat_jenis JENIS ON JENIS.ID = NM_OBT.ID_JENIS_OBAT
						LEFT JOIN faktur FAKTUR ON FAKTUR.ID = OBAT.ID_FAKTUR
						LEFT JOIN obat_supplier SUPLY ON SUPLY.ID = FAKTUR.ID_SUPPLIER
						WHERE $where
						GROUP BY FAKTUR.ID
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
			FROM faktur_detail OBAT
			LEFT JOIN admum_setup_nama_obat NM_OBT ON NM_OBT.ID = OBAT.ID_SETUP_NAMA_OBAT
			LEFT JOIN obat_supplier SUP ON SUP.ID = NM_OBT.ID_MERK
			LEFT JOIN obat_jenis JENIS ON JENIS.ID = OBAT.ID_JENIS_OBAT
			LEFT JOIN obat_satuan SAT ON SAT.ID = OBAT.ID_SATUAN_OBAT
			ORDER BY OBAT.ID ASC, STR_TO_DATE('%d-%m-%Y',OBAT.KADALUARSA) ASC
		";
		return $this->db->query($sql)->result();
	}

	function data_obat_id($id){
		$sql = "SELECT
							OBAT.ID,
							OBAT.ID_SETUP_NAMA_OBAT,
							NM_OBT.KODE_OBAT,
							NM_OBT.BARCODE,
							NM_OBT.NAMA_OBAT,
							NM_OBT.ID_JENIS_OBAT,
							NM_OBT.GOLONGAN_OBAT,
							NM_OBT.KATEGORI_OBAT,
							NM_OBT.SERVICE,
							JENIS.NAMA_JENIS,
							OBAT.JUMLAH,
							OBAT.ISI,
							OBAT.TOTAL,
							OBAT.JUMLAH_BUTIR,
							OBAT.HARGA_BELI,
							OBAT.HARGA_JUAL,
							OBAT.HARGA_PERTABLET,
							OBAT.TANGGAL_MASUK,
							OBAT.WAKTU_MASUK,
							OBAT.ID_SUPPLIER,
							SUP.NAMA_SUPPLIER
						FROM faktur_detail OBAT
						LEFT JOIN admum_setup_nama_obat NM_OBT ON NM_OBT.ID = OBAT.ID_SETUP_NAMA_OBAT
						LEFT JOIN obat_supplier SUP ON SUP.ID = OBAT.ID_SUPPLIER
						LEFT JOIN obat_jenis JENIS ON JENIS.ID = NM_OBT.ID_JENIS_OBAT
						WHERE OBAT.ID = '$id'
					";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function data_faktur_id($id){
		$sql = "SELECT
						a.ID AS ID_FAKTUR_UTAMA,
						a.NO_FAKTUR,
						a.TOTAL AS TOTAL_FAKTUR,
						a.ID_SUPPLIER,
						b.*,
						c.NAMA_OBAT,
						d.NAMA_SUPPLIER
						FROM
						faktur a
						LEFT JOIN faktur_detail b ON a.ID = b.ID_FAKTUR
						LEFT JOIN admum_setup_nama_obat c ON c.iD = b.ID_SETUP_NAMA_OBAT
						LEFT JOIN obat_supplier d ON d.ID = a.ID_SUPPLIER
						WHERE b.ID_FAKTUR = '$id'
					";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_faktur_id_row($id){
		$sql = "SELECT
						a.ID AS ID_FAKTUR_UTAMA,
						a.NO_FAKTUR,
						a.TOTAL AS TOTAL_FAKTUR,
						a.ID_SUPPLIER,
						b.*,
						c.NAMA_OBAT,
						d.NAMA_SUPPLIER
						FROM
						faktur a
						LEFT JOIN faktur_detail b ON a.ID = b.ID_FAKTUR
						LEFT JOIN admum_setup_nama_obat c ON c.iD = b.ID_SETUP_NAMA_OBAT
						LEFT JOIN obat_supplier d ON d.ID = a.ID_SUPPLIER
						WHERE b.ID_FAKTUR = '$id'
					";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function simpan(
		$insert_id,
		$value,
		$jumlah,
		$isi,
		$total,
		$jumlah_butir,
		$harga_pertablet,
		$harga_beli,
		$harga_jual,
		$harga_bulat,
		$no_batch,
		$kadaluarsa,
		$diskon,
		$tanggal_masuk,
		$waktu_masuk
	){

		$data_faktur_detail = array(
			'ID_FAKTUR' => $insert_id,
			'ID_SETUP_NAMA_OBAT' => $value,
			'JUMLAH' => $jumlah,
			'ISI' => $isi,
			'TOTAL' => $total,
			'JUMLAH_BUTIR' => $jumlah_butir,
			'HARGA_PERTABLET' => $harga_pertablet,
			'HARGA_BELI' => $harga_beli,
			'HARGA_JUAL' => $harga_jual,
			'HARGA_BULAT' => $harga_bulat,
			'DISKON' => $diskon,
			'NO_BATCH' => $no_batch,
			'EXPIRED' => $kadaluarsa,
			'TANGGAL_MASUK' => $tanggal_masuk,
			'WAKTU_MASUK' => $waktu_masuk
		);
		$this->db->insert('faktur_detail', $data_faktur_detail);
		$id_faktur = $this->db->insert_id();

		$sql_cek = "SELECT COUNT(*) AS TOTAL FROM apotek_gudang_obat WHERE ID_SETUP_NAMA_OBAT = '$value'";
		$qry_cek = $this->db->query($sql_cek)->row();
		$total_data = $qry_cek->TOTAL;

		if ($total_data == 0) {
			$data_gudang = array(
				'ID_SETUP_NAMA_OBAT' => $value,
				'TANGGAL_MASUK' => $tanggal_masuk,
				'WAKTU_MASUK' => $waktu_masuk,
				'AKTIF' => '1',
				'STOK' => $total,
				'HARGA_JUAL' => $harga_jual,
				'HARGA_BELI' => $harga_beli,
				'HARGA_BULAT' => $harga_bulat,
				'EXPIRED' => $kadaluarsa,
				'DISKON' => $diskon
			);
			$this->db->insert('apotek_gudang_obat', $data_gudang);
		}else {
			$sql_stok = $this->db->query("SELECT * FROM apotek_gudang_obat WHERE ID_SETUP_NAMA_OBAT = '$value'")->row_array();
			$id_gudang = $sql_stok['ID'];
			$stok = $sql_stok['STOK'];

			$jumlah_stok = $total + $stok;
			$data_gudang = array(
				'STOK' => $jumlah_stok,
				'HARGA_JUAL' => $harga_jual,
				'HARGA_BELI' => $harga_beli,
				'HARGA_BULAT' => $harga_bulat,
				'EXPIRED' => $kadaluarsa,
				'DISKON' => $diskon
			);
			$this->db->where('ID', $id_gudang);
      $this->db->update('apotek_gudang_obat', $data_gudang);
		}
	}

	function simpan_obat(
		$id_faktur,
		$value,
		$jumlah,
		$isi,
		$total,
		$jumlah_butir,
		$harga_pertablet,
		$harga_beli,
		$harga_jual,
		$harga_bulat,
		$no_batch,
		$diskon,
		$kadaluarsa,
		$tanggal_masuk,
		$waktu_masuk
	){

		// print_r($harga_beli);
		// die();
		$data_faktur_detail = array(
			'ID_FAKTUR' => $id_faktur,
			'ID_SETUP_NAMA_OBAT' => $value,
			'JUMLAH' => $jumlah,
			'ISI' => $isi,
			'TOTAL' => $total,
			'JUMLAH_BUTIR' => $jumlah_butir,
			'HARGA_PERTABLET' => $harga_pertablet,
			'HARGA_BELI' => $harga_beli,
			'HARGA_JUAL' => $harga_jual,
			'HARGA_BULAT' => $harga_bulat,
			'DISKON' => $diskon,
			'NO_BATCH' => $no_batch,
			'EXPIRED' => $kadaluarsa,
			'TANGGAL_MASUK' => $tanggal_masuk,
			'WAKTU_MASUK' => $waktu_masuk
		);
		$this->db->insert('faktur_detail', $data_faktur_detail);
		$id_faktur = $this->db->insert_id();

		$sql_cek = "SELECT COUNT(*) AS TOTAL FROM apotek_gudang_obat WHERE ID_SETUP_NAMA_OBAT = '$value'";
		$qry_cek = $this->db->query($sql_cek)->row();
		$total_data = $qry_cek->TOTAL;

		if ($total_data == 0) {
			$data_gudang = array(
				'ID_SETUP_NAMA_OBAT' => $value,
				'TANGGAL_MASUK' => $tanggal_masuk,
				'WAKTU_MASUK' => $waktu_masuk,
				'AKTIF' => '1',
				'STOK' => $total,
				'HARGA_BELI' => $harga_beli,
				'HARGA_JUAL' => $harga_jual,
				'HARGA_BULAT' => $harga_bulat,
				'EXPIRED' => $kadaluarsa,
				'DISKON' => $diskon
			);
			$this->db->insert('apotek_gudang_obat', $data_gudang);
		}else {
			$sql_stok = $this->db->query("SELECT * FROM apotek_gudang_obat WHERE ID_SETUP_NAMA_OBAT = '$value'")->row_array();
			$id_gudang = $sql_stok['ID'];
			$stok = $sql_stok['STOK'];

			$jumlah_stok = $total + $stok;
			$data_gudang = array(
				'STOK' => $jumlah_stok,
				'HARGA_BELI' => $harga_beli,
				'HARGA_JUAL' => $harga_jual,
				'HARGA_BULAT' => $harga_bulat,
				'EXPIRED' => $kadaluarsa,
				'DISKON' => $diskon
			);
			$this->db->where('ID', $id_gudang);
      $this->db->update('apotek_gudang_obat', $data_gudang);
		}
	}

	function ubah(
		$id,
		$id_supplier,
		$id_nama_obat,
		$jumlah,
		$isi,
		$total,
		$jumlah_butir,
		$harga_pertablet,
		$harga_beli,
		$harga_jual
	){
		$sql = "
			UPDATE faktur_detail SET
				ID_SUPPLIER = '$id_supplier',
				ID_SETUP_NAMA_OBAT = '$id_nama_obat',
				JUMLAH = '$jumlah',
				ISI = '$isi',
				TOTAL = '$total',
				JUMLAH_BUTIR = '$jumlah_butir',
				HARGA_PERTABLET = '$harga_pertablet',
				HARGA_BELI = '$harga_beli',
				HARGA_JUAL = '$harga_jual'
			WHERE ID = '$id'
		";
		$this->db->query($sql);
	}

	function hapus($id){
		$sql = "DELETE FROM faktur_detail WHERE ID = '$id'";
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
	function klik_nama_supplier($id){
		$sql = "SELECT
							a.ID,
							a.KODE_SUPPLIER,
							a.NAMA_SUPPLIER
						FROM obat_supplier a
						WHERE a.ID = '$id'
		";
		$query = $this->db->query($sql);
		return $query->row();
	}
}

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ap_obat_hv_m extends CI_Model {

	function __construct(){
		parent::__construct();
		$this->load->database();
	}

	function get_user_detail($id_user){
		$sql = "SELECT
      				a.*
      			FROM kepeg_pegawai a
      			WHERE a.ID = '$id_user'
      		";

		return $this->db->query($sql)->row();
	}
  function data_obat($keyword){
    $where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND (NM_OBT.NAMA_OBAT LIKE '%$keyword%' OR NM_OBT.BARCODE LIKE '%$keyword%' OR NM_OBT.KODE_OBAT LIKE '%$keyword%')";
		}

		$sql = "SELECT
						GUDANG.ID,
						NM_OBT.KODE_OBAT,
						NM_OBT.BARCODE,
						NM_OBT.NAMA_OBAT,
						NM_OBT.EXPIRED,
						NM_OBT.STATUS_OBAT,
						NM_OBT.SERVICE,
						JENIS.NAMA_JENIS,
						OBAT.JUMLAH,
						OBAT.ISI,
						OBAT.TOTAL,
						OBAT.JUMLAH_BUTIR,
						OBAT.HARGA_BELI,
						OBAT.HARGA_JUAL,
						OBAT.TANGGAL_MASUK,
						OBAT.WAKTU_MASUK,
						( OBAT.HARGA_JUAL + NM_OBT.SERVICE ) AS TOTAL_JUAL,
						GUDANG.STOK AS TOTAL_STOK_OBAT
						FROM
						faktur_detail OBAT
						LEFT JOIN faktur FAKTUR ON FAKTUR.ID = OBAT.ID_FAKTUR
						LEFT JOIN admum_setup_nama_obat NM_OBT ON NM_OBT.ID = OBAT.ID_SETUP_NAMA_OBAT
						LEFT JOIN obat_supplier SUP ON SUP.ID = FAKTUR.ID_SUPPLIER
						LEFT JOIN obat_jenis JENIS ON JENIS.ID = NM_OBT.ID_JENIS_OBAT
						LEFT JOIN apotek_gudang_obat GUDANG ON NM_OBT.ID = GUDANG.ID_SETUP_NAMA_OBAT
						WHERE $where
						GROUP BY OBAT.ID_SETUP_NAMA_OBAT
					";
		$query = $this->db->query($sql);
		return $query->result();
  }

	function data_keranjang(){
		$sql = $this->db->query("SELECT
														a.*,
														b.ID AS ID_GUDANG_OBAT,
														c.BARCODE,
														c.NAMA_OBAT
														FROM
														keranjang_beli_hv a
														LEFT JOIN apotek_gudang_obat b ON a.ID_GUDANG_OBAT = b.ID
														LEFT JOIN admum_setup_nama_obat c ON b.ID_SETUP_NAMA_OBAT = c.ID
														");
		return $sql->result_array();
	}

	function simpan_keranjang($id_gudang, $harga_beli){
		$tanggal = date('d-m-Y');
		$bulan = date('m');
		$tahun = date('Y');
		$data = array(
			'ID_GUDANG_OBAT' => $id_gudang,
			'TANGGAL' => $tanggal,
			'BULAN' => $bulan,
			'TAHUN' => $tahun,
			'HARGA_OBAT' => $harga_beli
		);

		$this->db->insert('keranjang_beli_hv', $data);
	}

	function hapus_keranjang($id){
		$this->db->where('ID', $id);
    $this->db->delete('keranjang_beli_hv');
	}

	function simpan_proses($value, $total_keranjang_name, $jumlah_beli, $harga_obat, $id_penjualan_obat_hv){
		$tanggal = date('d-m-Y');
		$tahun = date('Y');
		$bulan = date('m');

		$data_detail = array(
			'ID_PENJUALAN_OBAT_HV' => $id_penjualan_obat_hv,
			'ID_GUDANG_OBAT' => $value,
			'JUMLAH_BELI' => $jumlah_beli,
			'TANGGAL' => $tanggal,
			'TAHUN' => $tahun,
			'BULAN' => $bulan,
			'TOTAL' => $total_keranjang_name,
			'HARGA_OBAT' => $harga_obat
		);
		$this->db->insert('ap_penjualan_obat_hv_detail', $data_detail);
		$this->db->empty_table('keranjang_beli_hv');

		$this->db->query("UPDATE apotek_gudang_obat SET STOK = STOK - $jumlah_beli WHERE ID = '$value'");
	}
}

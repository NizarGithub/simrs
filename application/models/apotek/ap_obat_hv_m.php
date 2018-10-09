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
			$where = $where." AND (b.NAMA_OBAT LIKE '%$keyword%' OR b.BARCODE LIKE '%$keyword%' OR b.KODE_OBAT LIKE '%$keyword%')";
		}

		$sql = "SELECT
						a.ID,
						b.KODE_OBAT,
						b.BARCODE,
						b.NAMA_OBAT,
						a.EXPIRED,
						b.STATUS_OBAT,
						b.SERVICE,
						b.ID_JENIS_OBAT,
						a.HARGA_BELI,
						a.HARGA_JUAL,
						a.HARGA_BULAT,
						a.TANGGAL_MASUK,
						a.WAKTU_MASUK,
						( a.HARGA_BULAT + b.SERVICE ) AS TOTAL_JUAL,
						a.STOK AS TOTAL_STOK_OBAT
						FROM
						apotek_gudang_obat a
						LEFT JOIN admum_setup_nama_obat b ON a.ID_SETUP_NAMA_OBAT = b.ID
						WHERE $where
						-- GROUP BY OBAT.ID_SETUP_NAMA_OBAT
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

	function simpan_keranjang($id_gudang, $harga_beli, $service){
		$tanggal = date('d-m-Y');
		$bulan = date('m');
		$tahun = date('Y');
		$data = array(
			'ID_GUDANG_OBAT' => $id_gudang,
			'TANGGAL' => $tanggal,
			'BULAN' => $bulan,
			'TAHUN' => $tahun,
			'HARGA_OBAT' => $harga_beli,
			'SERVICE' => $service
		);

		$this->db->insert('keranjang_beli_hv', $data);
	}

	function hapus_keranjang($id){
		$this->db->where('ID', $id);
    $this->db->delete('keranjang_beli_hv');
	}

	function simpan_proses($value, $total_keranjang_name, $jumlah_beli, $harga_obat, $service, $id_penjualan_obat_hv){
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
			'HARGA_OBAT' => $harga_obat,
			'SERVICE' => $service
		);
		$this->db->insert('ap_penjualan_obat_hv_detail', $data_detail);
		$this->db->empty_table('keranjang_beli_hv');

		// $this->db->query("UPDATE apotek_gudang_obat SET STOK = STOK - $jumlah_beli WHERE ID = '$value'");
	}
}

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ap_entry_paket_m extends CI_Model {

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

	function get_data_pasien($keyword){
    $where = '1 = 1';
    if ($keyword != '') {
      $where = $where." AND NAMA LIKE '%$keyword%'";
    }

    $sql = $this->db->query("SELECT * FROM rk_pasien WHERE $where");

    return $sql->result();
  }

	function klik_pasien($id){
    $this->db->select('*');
    $this->db->from('rk_pasien');
    $this->db->where('ID', $id);
    $sql = $this->db->get();

    return $sql->row_array();
  }

  function get_data_paket(){
    $this->db->select('*');
    $this->db->from('ap_paket');
    $sql = $this->db->get();

    return $sql->result_array();
  }

  function klik_paket($id){
    $this->db->select('*');
    $this->db->from('ap_paket');
    $this->db->where('ID', $id);
    $sql = $this->db->get();

    return $sql->row_array();
  }

  function get_data_dokter($keyword){
    $where = '1 = 1';
    if ($keyword != '') {
      $where = $where." AND NAMA LIKE '%$keyword%' OR NIP LIKE '%$keyword%' AND STATUS LIKE 'DOKTER'";
    }

    $sql = $this->db->query("SELECT * FROM kepeg_pegawai WHERE $where AND STATUS = 'DOKTER' ");

    return $sql->result();
  }

  function klik_dokter($id){
    $this->db->select('*');
    $this->db->from('kepeg_pegawai');
    $this->db->where('ID', $id);
    $this->db->where('STATUS', 'DOKTER');
    $sql = $this->db->get();

    return $sql->row_array();
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
						b.EXPIRED,
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
                            keranjang_beli_paket a
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

    $this->db->insert('keranjang_beli_paket', $data);
  }

  function hapus_keranjang($id){
    $this->db->where('ID', $id);
    $this->db->delete('keranjang_beli_paket');
  }

  function simpan_proses($value, $total_keranjang_name, $jumlah_beli, $harga_obat, $id_penjualan_obat_paket, $service){
    $tanggal = date('d-m-Y');
    $tahun = date('Y');
    $bulan = date('m');

    $data_detail = array(
      'ID_PENJUALAN_OBAT_PAKET' => $id_penjualan_obat_paket,
      'ID_GUDANG_OBAT' => $value,
      'JUMLAH_BELI' => $jumlah_beli,
      'TANGGAL' => $tanggal,
      'TAHUN' => $tahun,
      'BULAN' => $bulan,
      'TOTAL' => $total_keranjang_name,
      'HARGA_OBAT' => $harga_obat,
			'SERVICE' => $service
    );
    $this->db->insert('ap_penjualan_obat_paket_detail', $data_detail);
    $this->db->empty_table('keranjang_beli_paket');
    // $this->db->query("UPDATE apotek_gudang_obat SET STOK = STOK - $jumlah_beli WHERE ID = '$value'");
  }
}

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ap_stok_opname_m extends CI_Model {

	function __construct(){
		parent::__construct();
		$this->load->database();
	}

	function get_data_preview(){
		$sql = $this->db->query("SELECT
															a.ID,
															a.TANGGAL,
															a.WAKTU,
															COUNT(a.TANGGAL) AS JUMLAH_OBAT
															FROM
															ap_stock_opname a
															LEFT JOIN admum_setup_nama_obat b ON a.ID_SETUP_NAMA_OBAT = b.ID
															LEFT JOIN apotek_gudang_obat c ON a.ID_GUDANG_OBAT = c.ID
															GROUP BY a.TANGGAL
														");

		return $sql->result_array();
	}

  function get_data_stok(){
    $sql = $this->db->query("SELECT
                              a.ID,
                              a.ID_SETUP_NAMA_OBAT,
                              a.STOK,
                              b.NAMA_OBAT
                              FROM
                              apotek_gudang_obat a
                              LEFT JOIN admum_setup_nama_obat b ON a.ID_SETUP_NAMA_OBAT = b.ID
                            ");

    return $sql->result_array();
  }

  function simpan_stok_opname($value, $id_setup_nama_obat, $stok_sistem, $stok_fisik){
    $selisih = $stok_sistem - $stok_fisik;
    $tanggal = date('d-m-Y');
    $bulan = date('m');
    $tahun = date('Y');
    $waktu = date("h:i:sa");

    $data = array(
      'ID_GUDANG_OBAT' => $value,
      'ID_SETUP_NAMA_OBAT' => $id_setup_nama_obat,
      'STOK_SISTEM' => $stok_sistem,
      'STOK_FISIK' => $stok_fisik,
      'SELISIH' => $selisih,
      'TANGGAL' => $tanggal,
      'BULAN' => $bulan,
      'TAHUN' => $tahun,
      'WAKTU' => $waktu
    );

    $this->db->insert('ap_stock_opname', $data);
  }

	function data_detail_opname($keyword, $tanggal){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND b.NAMA_OBAT LIKE '%$keyword%' AND a.TANGGAL = '$tanggal'";
		}else {
			$where = $where." AND a.TANGGAL = '$tanggal'";
		}

		$sql = $this->db->query("SELECT
															a.ID,
															a.TANGGAL,
															a.WAKTU,
															b.NAMA_OBAT,
															a.STOK_SISTEM,
															a.STOK_FISIK,
															a.SELISIH
															FROM
															ap_stock_opname a
															LEFT JOIN admum_setup_nama_obat b ON a.ID_SETUP_NAMA_OBAT = b.ID
															LEFT JOIN apotek_gudang_obat c ON a.ID_GUDANG_OBAT = c.ID
															WHERE $where
														");

		return $sql->result_array();
	}
}

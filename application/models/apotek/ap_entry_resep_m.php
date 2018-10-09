<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ap_entry_resep_m extends CI_Model {

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

	function data_pasien_iter($keyword){
    $where = "1 = 1";

		// if($keyword != ""){
		// 	$where = $where." AND (b.NAMA_OBAT LIKE '%$keyword%' OR b.BARCODE LIKE '%$keyword%' OR b.KODE_OBAT LIKE '%$keyword%')";
		// }

		$sql = "SELECT
						a.ID AS ID_RESEP,
						a.KODE_RESEP,
						a.TANGGAL,
						a.ID_PEG_DOKTER,
						a.ID_PASIEN,
						a.TOTAL_DGN_SERVICE,
						a.ITER,
						a.ITER_KURANG,
						c.NAMA AS NAMA_PASIEN,
						d.NAMA AS NAMA_DOKTER,
						c.JENIS_KELAMIN
						FROM
						rk_resep_rj a
						LEFT JOIN admum_rawat_jalan b ON a.ID_PELAYANAN = b.ID
						LEFT JOIN rk_pasien c ON a.ID_PASIEN = c.ID
						LEFT JOIN kepeg_pegawai d ON a.ID_PEG_DOKTER = d.ID
						WHERE $where
						AND a.STS_ITER = '1'
						AND b.STS_BAYAR = '1'
						AND a.ITER_KURANG != '0'
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
														c.NAMA_OBAT,
														c.ID_JENIS_OBAT
														FROM
														keranjang_beli a
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

		$this->db->insert('keranjang_beli', $data);
	}

	function hapus_keranjang($id){
		$this->db->where('ID', $id);
    $this->db->delete('keranjang_beli');
	}

	function simpan_proses($value, $total_keranjang_name, $jumlah_beli, $harga_obat, $service, $aturan_minum, $diminum_selama, $id_iter, $harga_obat_normal, $total_normal, $id_resep){
		$tanggal = date('d-m-Y');
		$tahun = date('Y');
		$bulan = date('m');

		$data_detail = array(
			'ID_ITER' => $id_iter,
			'ID_OBAT' => $value,
			'JUMLAH_BELI' => $jumlah_beli,
			'TANGGAL' => $tanggal,
			'TAHUN' => $tahun,
			'BULAN' => $bulan,
			'TOTAL' => $total_keranjang_name,
			'HARGA_OBAT' => $harga_obat,
			'SERVICE' => $service,
			'ATURAN_MINUM' => $aturan_minum,
			'DIMINUM_SELAMA' => $diminum_selama,
			'HARGA_OBAT_NORMAL' => $harga_obat_normal,
			'TOTAL_NORMAL' => $total_normal
		);
		$this->db->insert('ap_iter_detail', $data_detail);
		// $this->db->empty_table('keranjang_beli');

		// $this->db->query("UPDATE apotek_gudang_obat SET STOK = STOK - $jumlah_beli WHERE ID = '$value'");
	}

	function simpan_proses_resep($value, $total_keranjang_name, $jumlah_beli, $harga_obat, $service, $aturan_minum, $diminum_selama, $id_iter, $harga_obat_normal, $total_normal, $id_resep){
		$tanggal = date('d-m-Y');
		$tahun = date('Y');
		$bulan = date('m');

		$data_detail_resep = array(
			'ID_RESEP' => $id_resep,
			'ID_OBAT' => $value,
			'HARGA' => $harga_obat_normal,
			'SERVICE' => $service,
			'JUMLAH_BELI' => $jumlah_beli,
			'SUBTOTAL' => $total_normal,
			'TAKARAN' => '',
			'ATURAN_MINUM' => $aturan_minum,
			'DIMINUM_SELAMA' => $diminum_selama,
			'TANGGAL' => $tanggal,
			'TAHUN' => $tahun,
			'BULAN' => $bulan
		);
		$this->db->insert('rk_resep_detail_rj', $data_detail_resep);
	}

	function get_pasien_iter($id_resep){
		$sql = $this->db->query("SELECT
															a.*,
															( a.HARGA + a.SERVICE ) AS HARGA_SERVICE,
															( (a.HARGA + a.SERVICE) * a.JUMLAH_BELI ) AS TOTAL_SERVICE,
															( a.HARGA * a.JUMLAH_BELI ) AS SUBTOTAL,
															b.NAMA_OBAT,
															b.ID_JENIS_OBAT
															FROM
															rk_resep_detail_rj a
															LEFT JOIN admum_setup_nama_obat b ON a.ID_OBAT = b.ID
															WHERE a.ID_RESEP = '18'
															");
		return $sql->result_array();
	}

	function get_obat($id){
		$sql = $this->db->query("SELECT
															a.*,
															b.NAMA_OBAT,
															b.SERVICE,
															b.ID_JENIS_OBAT,
															a.HARGA_BULAT,
															( a.HARGA_BULAT + b.SERVICE ) AS SUBTOTAL,
															a.ID_SETUP_NAMA_OBAT AS ID_OBAT
															FROM
															apotek_gudang_obat a
															LEFT JOIN admum_setup_nama_obat b ON a.ID_SETUP_NAMA_OBAT = b.ID
															WHERE a.ID = '$id'
															");
		return $sql->result_array();
	}
}

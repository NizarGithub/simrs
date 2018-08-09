<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admum_setup_kamar_jenazah_m extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->load->database(); 
	}

	function data_kamar($keyword,$urutkan){
		$where = "1 = 1";
		$order = "";

		if($urutkan == 'Default'){
			$order = "ORDER BY JZ.ID DESC";
		}else if($urutkan == 'Kode Kamar'){
			$order = "ORDER BY JZ.KODE_KAMAR ASC";
		}else if($urutkan == 'Nama Kamar'){
			$order = "ORDER BY JZ.NAMA_KAMAR ASC";
		}

		if($keyword != ""){
			$where = $where." AND (JZ.NAMA_KAMAR LIKE '%$keyword%' OR JZ.KODE_KAMAR LIKE '%$keyword%')";
		}else{
			$where = $where;
		}

		$sql = "
			SELECT 
				JZ.ID,
				JZ.KODE_KAMAR,
				JZ.NAMA_KAMAR,
				JZ.BIAYA,
				JZ.JUMLAH_LEMARI,
				JZ.STATUS_PENUH,
				IFNULL(LM.TOTAL,0) AS TOTAL
			FROM admum_kamar_jenazah JZ
			LEFT JOIN(
				SELECT ID_KAMAR_JENAZAH,COUNT(*) AS TOTAL FROM admum_lemari_jenazah
			) LM ON LM.ID_KAMAR_JENAZAH = JZ.ID
			WHERE $where
			$order
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_kamar_id($id){
		$sql = "
			SELECT 
				JZ.ID,
				JZ.KODE_KAMAR,
				JZ.NAMA_KAMAR,
				JZ.BIAYA,
				JZ.JUMLAH_LEMARI,
				JZ.STATUS_PENUH,
				IFNULL(LM.TOTAL,0) AS TOTAL
			FROM admum_kamar_jenazah JZ
			LEFT JOIN(
				SELECT ID_KAMAR_JENAZAH,COUNT(*) AS TOTAL FROM admum_lemari_jenazah
			) LM ON LM.ID_KAMAR_JENAZAH = JZ.ID
			WHERE JZ.ID = '$id'
		";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function data_lemari($id_kamar_jenazah){
		$sql = "SELECT * FROM admum_lemari_jenazah WHERE ID_KAMAR_JENAZAH = '$id_kamar_jenazah'";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_lemari_id($id){
		$sql = "SELECT * FROM admum_lemari_jenazah WHERE ID = '$id'";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function simpan($kode_kamar,$nama_kamar,$biaya,$jumlah_lemari,$tanggal,$bulan,$tahun){
		$sql = "
			INSERT INTO admum_kamar_jenazah(
				KODE_KAMAR,
				NAMA_KAMAR,
				BIAYA,
				JUMLAH_LEMARI,
				TANGGAL,
				BULAN,
				TAHUN
			) VALUES (
				'$kode_kamar',
				'$nama_kamar',
				'$biaya',
				'$jumlah_lemari',
				'$tanggal',
				'$bulan',
				'$tahun'
			)
		";
		$this->db->query($sql);
	}

	function simpan_lemari($id_kamar_jenazah,$no,$nomor_lemari,$jumlah){
		$sql = "
			INSERT INTO admum_lemari_jenazah(
				ID_KAMAR_JENAZAH,
				NO,
				NOMOR_LEMARI,
				JUMLAH,
				STATUS_PAKAI
			) VALUES (
				'$id_kamar_jenazah',
				'$no',
				'$nomor_lemari',
				'$jumlah',
				'0'
			)
		";
		$this->db->query($sql);
	}

	function ubah($id,$nama_kamar,$biaya,$jumlah_lemari){
		$sql = "
			UPDATE admum_kamar_jenazah SET
				NAMA_KAMAR = '$nama_kamar',
				BIAYA = '$biaya',
				JUMLAH_LEMARI = '$jumlah_lemari'
			WHERE ID = '$id'
		";
		$this->db->query($sql);
	}

	function hapus($id){
		$sql = "DELETE FROM admum_kamar_jenazah WHERE ID = '$id'";
		$this->db->query($sql);
	}

	function hapus_lemari($id){
		$sql = "DELETE FROM admum_lemari_jenazah WHERE ID = '$id'";
		$this->db->query($sql);
	}

}
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admum_setup_kamar_rawat_inap_m extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->load->database(); 
	}

	function data_kamar($keyword,$urutkan,$cari_berdasarkan,$pilih_kelas){
		$where = "1 = 1";
		$order = "";

		if($urutkan == 'Default'){
			$order = "ORDER BY RI.ID DESC";
		}else if($urutkan == 'Nama Kamar'){
			$order = "ORDER BY RI.NAMA_KAMAR ASC";
		}else if($urutkan == 'Kelas Kamar'){
			$order = "ORDER BY RI.KELAS ASC";
		}

		if($cari_berdasarkan == 'Nama Kamar'){
			$where = $where." AND (RI.NAMA_KAMAR LIKE '%$keyword%' OR RI.KODE_KAMAR LIKE '%$keyword%')";
		}else if($cari_berdasarkan == 'Kelas Kamar'){
			$where = $where." AND RI.KELAS = '$pilih_kelas'";
		}else{
			$where = $where;
		}

		if($keyword != ""){
			$where = $where." AND (RI.NAMA_KAMAR LIKE '%$keyword%' OR RI.KODE_KAMAR LIKE '%$keyword%')";
		}else{
			$where = $where;
		}

		$sql = "
			SELECT 
				RI.ID,
				RI.KODE_KAMAR,
				RI.NAMA_KAMAR,
				RI.KATEGORI,
				RI.KELAS,
				RI.BIAYA,
				RI.JUMLAH_BED,
				RI.FASILITAS,
				RI.STATUS_KAMAR,
				RI.STATUS_PENUH,
				IFNULL(BED.TOTAL,0) AS TOTAL
			FROM admum_kamar_rawat_inap RI
			LEFT JOIN(
				SELECT ID_KAMAR_RAWAT_INAP,COUNT(*) AS TOTAL FROM admum_bed_rawat_inap
				GROUP BY ID_KAMAR_RAWAT_INAP
			) BED ON BED.ID_KAMAR_RAWAT_INAP = RI.ID
			WHERE $where
			$order
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_kamar_id($id){
		$sql = "
			SELECT 
				RI.ID,
				RI.KODE_KAMAR,
				RI.NAMA_KAMAR,
				RI.KATEGORI,
				RI.KELAS,
				RI.BIAYA,
				RI.JUMLAH_BED,
				RI.FASILITAS,
				RI.STATUS_KAMAR,
				RI.STATUS_PENUH,
				IFNULL(BED.TOTAL,0) AS TOTAL
			FROM admum_kamar_rawat_inap RI
			LEFT JOIN(
				SELECT ID_KAMAR_RAWAT_INAP,COUNT(*) AS TOTAL FROM admum_bed_rawat_inap
			) BED ON BED.ID_KAMAR_RAWAT_INAP = RI.ID
			WHERE RI.ID = '$id'
		";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function data_bed($id_kamar_rawat_inap){
		$sql = "SELECT * FROM admum_bed_rawat_inap WHERE ID_KAMAR_RAWAT_INAP = '$id_kamar_rawat_inap'";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_bed_id($id){
		$sql = "SELECT * FROM admum_bed_rawat_inap WHERE ID = '$id'";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function simpan($kode_kamar,$nama_kamar,$kategori,$kelas,$biaya,$jumlah_bed,$fasilitas){
		$sql = "
			INSERT INTO admum_kamar_rawat_inap(
				KODE_KAMAR,
				NAMA_KAMAR,
				KATEGORI,
				KELAS,
				BIAYA,
				JUMLAH_BED,
				FASILITAS,
				STATUS_KAMAR,
				STATUS_PENUH
			) VALUES (
				'$kode_kamar',
				'$nama_kamar',
				'$kategori',
				'$kelas',
				'$biaya',
				'$jumlah_bed',
				'$fasilitas',
				'READY',
				'0'
			)
		";
		$this->db->query($sql);
	}

	function simpan_bed($id_kamar_rawat_inap,$no,$nomor_bed,$jumlah){
		$sql = "
			INSERT INTO admum_bed_rawat_inap(
				ID_KAMAR_RAWAT_INAP,
				NO,
				NOMOR_BED,
				JUMLAH,
				STATUS_PAKAI
			) VALUES (
				'$id_kamar_rawat_inap',
				'$no',
				'$nomor_bed',
				'$jumlah',
				'0'
			)
		";
		$this->db->query($sql);
	}

	function ubah($id,$nama_kamar,$kategori,$kelas,$biaya,$jumlah_bed,$fasilitas){
		$sql = "
			UPDATE admum_kamar_rawat_inap SET
				NAMA_KAMAR = '$nama_kamar',
				KATEGORI = '$kategori',
				KELAS = '$kelas',
				BIAYA = '$biaya',
				JUMLAH_BED = '$jumlah_bed',
				FASILITAS = '$fasilitas'
			WHERE ID = '$id'
		";
		$this->db->query($sql);
	}

	function hapus($id){
		$sql = "DELETE FROM admum_kamar_rawat_inap WHERE ID = '$id'";
		$this->db->query($sql);
	}

	function hapus_bed($id){
		$sql = "DELETE FROM admum_bed_rawat_inap WHERE ID = '$id'";
		$this->db->query($sql);
	}

}
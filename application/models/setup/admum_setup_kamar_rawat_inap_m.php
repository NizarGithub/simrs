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
			$order = "ORDER BY RI.ID ASC";
		}else if($urutkan == 'Kode Kamar'){
			$order = "ORDER BY RI.KODE_KAMAR ASC";
		}else if($urutkan == 'Kelas Kamar'){
			$order = "ORDER BY RI.KELAS ASC";
		}

		if($cari_berdasarkan == 'Kode Kamar'){
			$where = $where." AND (RI.KODE_KAMAR LIKE '%$keyword%')";
		}else if($cari_berdasarkan == 'Kelas Kamar'){
			$where = $where." AND RI.KELAS = '$pilih_kelas'";
		}else{
			$where = $where;
		}

		if($keyword != ""){
			$where = $where." AND (RI.KODE_KAMAR LIKE '%$keyword%')";
		}else{
			$where = $where;
		}

		$sql = "
			SELECT 
				RI.*,
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
				RI.*,
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

	function simpan($kode_kamar,$kelas,$biaya,$visite_dokter,$biaya_visite,$jasa_sarana,$peruntukan_kamar,$jumlah_bed,$tanggal,$bulan,$tahun){
		$sql = "
			INSERT INTO admum_kamar_rawat_inap(
				KODE_KAMAR,
				KELAS,
				BIAYA,
				VISITE_DOKTER,
				BIAYA_VISITE,
				JASA_SARANA,
				PERUNTUKAN_KAMAR,
				JUMLAH_BED,
				STATUS_KAMAR,
				STATUS_PENUH,
				TANGGAL,
				BULAN,
				TAHUN
			) VALUES (
				'$kode_kamar',
				'$kelas',
				'$biaya',
				'$visite_dokter',
				'$biaya_visite',
				'$jasa_sarana',
				'$peruntukan_kamar',
				'$jumlah_bed',
				'READY',
				'0',
				'$tanggal',
				'$bulan',
				'$tahun'
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

	function ubah($id,$kelas,$biaya,$visite_dokter,$biaya_visite,$jasa_sarana,$peruntukan_kamar,$jumlah_bed){
		$sql = "
			UPDATE admum_kamar_rawat_inap SET
				KELAS = '$kelas',
				BIAYA = '$biaya',
				VISITE_DOKTER = '$visite_dokter',
				BIAYA_VISITE = '$biaya_visite',
				JASA_SARANA = '$jasa_sarana',
				PERUNTUKAN_KAMAR = '$peruntukan_kamar',
				JUMLAH_BED = '$jumlah_bed'
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
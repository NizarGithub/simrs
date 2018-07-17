<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admum_data_pasien_m extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function data_pasien($id_klien,$keyword,$urutkan,$pilih_umur,$status){
		$where = "1 = 1";
		$order = "";

		if($keyword != ""){
			$where = $where." AND (
				PSN.KODE_PASIEN LIKE '%$keyword%' 
				OR PSN.NAMA LIKE '%$keyword%' 
				OR PSN.UMUR LIKE '%$keyword%'
				OR PSN.ALAMAT LIKE '%$keyword%'
			)";
		}else{
			$where = $where;
		}

		if($urutkan == 'Default'){
			$order = "ORDER BY PSN.ID DESC"; 
		}else if($urutkan == 'Nama Pasien'){
			$order = "ORDER BY PSN.NAMA ASC";
		}else if($urutkan == 'Umur'){
			if($pilih_umur == 'Balita'){
				$where = $where." AND PSN.UMUR >= 0 AND PSN.UMUR <= 5";
			}else if($pilih_umur == 'Anak'){
				$where = $where." AND PSN.UMUR >= 6 AND PSN.UMUR <= 16";
			}else if($pilih_umur == 'Remaja'){
				$where = $where." AND PSN.UMUR >= 17 AND PSN.UMUR <= 25";
			}else if($pilih_umur == 'Dewasa'){
				$where = $where." AND PSN.UMUR >= 26 AND PSN.UMUR <= 50";
			}else if($pilih_umur == 'Tua'){
				$where = $where." AND PSN.UMUR > 50";
			}
			$order = "ORDER BY PSN.UMUR ASC";
		}

		$sql = "SELECT PSN.* FROM rk_pasien PSN WHERE $where AND PSN.STATUS = '$status' $order";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_pasien_id($id,$id_klien){
		$sql = "SELECT * FROM rk_pasien WHERE ID = '$id'";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function ubah_pasien(
		$id,
		$nama,
		$jenis_kelamin,
		$pendidikan,
		$agama,
		$alamat,
		$golongan_darah,
		$tempat_lahir,
		$tanggal_lahir,
		$umur,
		$kelurahan,
		$kecamatan,
		$kota,
		$provinsi,
		$status){

		$sql = "
			UPDATE rk_pasien SET
				NAMA = '$nama',
				JENIS_KELAMIN = '$jenis_kelamin',
				PENDIDIKAN = '$pendidikan',
				AGAMA = '$agama',
				ALAMAT = '$alamat',
				GOLONGAN_DARAH = '$golongan_darah',
				TEMPAT_LAHIR = '$tempat_lahir',
				TANGGAL_LAHIR = '$tanggal_lahir',
				UMUR = '$umur',
				KELURAHAN = '$kelurahan',
				KECAMATAN = '$kecamatan',
				KOTA = '$kota',
				PROVINSI = '$provinsi',
				STATUS = '$status'
			WHERE ID = '$id'
		";
		$this->db->query($sql);
	}

	function hapus_pasien($id){
		$sql = "DELETE FROM rk_pasien WHERE ID = '$id'";
		$this->db->query($sql);
	}

	function kota_kab(){
		$sql = "
			SELECT
				LKS.lokasi_ID,
				LKS.lokasi_kode,
				LKS.lokasi_propinsi AS ID_PROV,
				LKS.lokasi_nama AS PROV,
				LKS2.lokasi_nama AS KOTA,
				LKS2.lokasi_propinsi,
				LKS2.lokasi_kabupatenkota
			FROM lokasi LKS
			JOIN(
				SELECT * FROM lokasi 
				WHERE lokasi_kabupatenkota != '0'
				AND lokasi_kecamatan = '0'
			) LKS2 ON LKS.lokasi_propinsi = LKS2.lokasi_propinsi
			WHERE LKS.lokasi_kabupatenkota = '0'
			ORDER BY 
			LKS.lokasi_propinsi ASC,
			KOTA ASC
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function provinsi($id_kota_kab){
		$sql = "
			SELECT
				LKS.lokasi_ID,
				LKS.lokasi_kode,
				LKS.lokasi_propinsi AS ID_PROV,
				LKS.lokasi_nama AS PROV,
				LKS2.lokasi_nama AS KOTA,
				LKS2.lokasi_propinsi,
				LKS2.lokasi_kabupatenkota
			FROM lokasi LKS
			JOIN(
				SELECT * FROM lokasi 
				WHERE lokasi_kabupatenkota != '0'
				AND lokasi_kecamatan = '0'
			) LKS2 ON LKS.lokasi_propinsi = LKS2.lokasi_propinsi
			WHERE LKS.lokasi_kabupatenkota = '0'
			AND LKS2.lokasi_nama = '$id_kota_kab'
			ORDER BY 
			LKS.lokasi_propinsi ASC,
			KOTA ASC
		";
		$query = $this->db->query($sql);
		return $query->row();
	}

}
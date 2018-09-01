<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admum_pindah_kamari_ri_m extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function load_data_pasien($keyword){ 
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND (b.NAMA LIKE '%$keyword%' OR b.KODE_PASIEN LIKE '%$keyword%')";
		}else{
			$where = $where;
		}

		$sql = "
			SELECT
				a.ID,
				a.ID_PASIEN,
				a.TANGGAL_MASUK,
				a.ID_KAMAR,
				a.ID_BED,
				b.NAMA,
				b.KODE_PASIEN,
				b.UMUR,
				b.JENIS_KELAMIN
			FROM admum_rawat_inap a
			LEFT JOIN rk_pasien b ON b.ID = a.ID_PASIEN
			WHERE $where
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function klik_pasien($id){
		$sql = "
			SELECT 
				a.ID, 
				a.ID_PASIEN, 
				a.ID_KAMAR,
				a.ID_BED,
				c.NAMA, 
				c.UMUR, 
				c.JENIS_KELAMIN, 
				c.ALAMAT,
				b.KELAS,
				b.BIAYA,
				b.VISITE_DOKTER
			FROM admum_rawat_inap a
			LEFT JOIN admum_kamar_rawat_inap b ON b.ID = a.ID_KAMAR
			LEFT JOIN rk_pasien c ON a.ID_PASIEN = c.ID
			WHERE a.ID = '$id'
		";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function load_kamar($keyword,$kelas){
		$where = "1 = 1";

		if($kelas == 'Semua'){
			$where = $where;
		}else{
			$where = $where." AND KELAS = '$kelas'";
		}

		if($keyword != ""){
			$where = $where." AND (KODE_KAMAR LIKE '%$keyword%')";
		}else{
			$where = $where;
		}

		$sql = "SELECT * FROM admum_kamar_rawat_inap WHERE $where";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function klik_kamar($id){
		$sql = "SELECT * FROM admum_kamar_rawat_inap WHERE ID = '$id'";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function load_bed($keyword,$id_kamar){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND NOMOR_BED LIKE '%$keyword%'";
		}else{
			$where = $where;
		}

		$sql = "SELECT * FROM admum_bed_rawat_inap WHERE $where AND ID_KAMAR_RAWAT_INAP = '$id_kamar'";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function klik_bed($id){
		$sql = "SELECT * FROM admum_bed_rawat_inap WHERE ID = '$id'";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function simpan($id_ri,$tanggal,$bulan,$tahun,$waktu,$nama,$jenis_kelamin,$umur,$alamat,$id_pasien,$hubungan,$id_kamar_lama,$id_kamar_baru,$id_bed){
		$sql = "
			INSERT INTO admum_pindah_kamar(
				ID_RAWAT_INAP,
				TANGGAL,
				BULAN,
				TAHUN,
				WAKTU,
				NAMA,
				JENIS_KELAMIN,
				UMUR,
				ALAMAT,
				ID_PASIEN,
				HUB_DGN_PASIEN,
				ID_KAMAR_LAMA,
				ID_KAMAR_BARU,
				ID_BED
			) VALUES(
				'$id_ri',
				'$tanggal',
				'$bulan',
				'$tahun',
				'$waktu',
				'$nama',
				'$jenis_kelamin',
				'$umur',
				'$alamat',
				'$id_pasien',
				'$hubungan',
				'$id_kamar_lama',
				'$id_kamar_baru',
				'$id_bed'
			)
		";
		$this->db->query($sql);
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
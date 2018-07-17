<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admum_ambulance_m extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function data_pegawai($keyword){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND (
				PEG.NIP LIKE '%$keyword%' 
				OR PEG.NAMA LIKE '%$keyword%'
				OR DEP.NAMA_DEP LIKE '%$keyword%'
				OR DV.NAMA_DIV LIKE '%$keyword%'
			)";
		}else{
			$where = $where;
		}

		$sql = "
			SELECT 
				PEG.ID,
				PEG.NIP,
				PEG.NAMA AS NAMA_PEGAWAI,
				DEP.NAMA_DEP,
				DV.NAMA_DIV,
				JBT.NAMA AS JABATAN
			FROM kepeg_pegawai PEG
			LEFT JOIN kepeg_departemen DEP ON PEG.ID_DEPARTEMEN = DEP.ID
			LEFT JOIN kepeg_divisi DV ON PEG.ID_DIVISI = DV.ID
			LEFT JOIN kepeg_kel_jabatan JBT ON PEG.ID_JABATAN = JBT.ID
			WHERE $where
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function klik_pegawai($id_pegawai){
		$sql = "
			SELECT
				PEG.ID,
				PEG.NIP,
				PEG.NAMA AS NAMA_PEGAWAI,
				a.NAMA_DEP,
				a.NAMA_DIV,
				JBT.NAMA AS JABATAN
			FROM kepeg_pegawai PEG
			LEFT JOIN (
				SELECT 
					DEP.ID,
					DEP.NAMA_DEP,
					DV.NAMA_DIV
				FROM kepeg_departemen DEP
				LEFT JOIN kepeg_divisi DV ON DV.ID_DEPARTEMEN = DEP.ID
				WHERE DEP.STS = 0
				AND DV.STS = 0
			) a ON PEG.ID_DEPARTEMEN = a.ID
			LEFT JOIN kepeg_jabatan JBT ON PEG.ID_JABATAN = JBT.ID
			WHERE PEG.ID = '$id_pegawai'
		";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function klik_perawat($id_pegawai){
		$sql = "
			SELECT
				PEG.ID,
				PEG.NIP,
				PEG.NAMA AS NAMA_PEGAWAI,
				a.NAMA_DEP,
				a.NAMA_DIV,
				JBT.NAMA AS JABATAN
			FROM kepeg_pegawai PEG
			LEFT JOIN (
				SELECT 
					DEP.ID,
					DEP.NAMA_DEP,
					DV.NAMA_DIV
				FROM kepeg_departemen DEP
				LEFT JOIN kepeg_divisi DV ON DV.ID_DEPARTEMEN = DEP.ID
				WHERE DEP.STS = 0
				AND DV.STS = 0
			) a ON PEG.ID_DEPARTEMEN = a.ID
			LEFT JOIN kepeg_jabatan JBT ON PEG.ID_JABATAN = JBT.ID
			WHERE PEG.ID = '$id_pegawai'
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_ambulance($keyword){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND 
				(
					AMB.KODE LIKE '%$keyword%' 
					OR AMB.NOMOR_PLAT LIKE '%$keyword%'
					OR PEG.NIP LIKE '%$keyword%'
					OR PEG.NAMA LIKE '%$keyword%'
				)
			";
		}

		$sql = "
			SELECT
				AMB.ID,
				AMB.KODE,
				AMB.NOMOR_PLAT,
				PEG.NIP,
				PEG.NAMA AS NAMA_SOPIR,
				DEP.NAMA_DEP,
				DV.NAMA_DIV
			FROM admum_ambulance AMB
			LEFT JOIN kepeg_pegawai PEG ON PEG.ID = AMB.ID_SOPIR
			LEFT JOIN kepeg_departemen DEP ON DEP.ID = PEG.ID_DEPARTEMEN
			LEFT JOIN kepeg_divisi DV ON DV.ID = PEG.ID_DIVISI AND DV.ID_DEPARTEMEN = DEP.ID
			WHERE $where
			ORDER BY AMB.ID DESC
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_ambulance_id($id){
		$sql = "
			SELECT
				AMB.ID,
				AMB.KODE,
				AMB.NOMOR_PLAT,
				AMB.ID_SOPIR,
				PEG.NIP,
				PEG.NAMA AS NAMA_SOPIR,
				DEP.NAMA_DEP,
				DV.NAMA_DIV,
				PRW.NIP_PERAWAT,
				PRW.NAMA_PERAWAT,
				PRW.NAMA_DEP2,
				PRW.NAMA_DIV2
			FROM admum_ambulance AMB
			LEFT JOIN kepeg_pegawai PEG ON PEG.ID = AMB.ID_SOPIR
			LEFT JOIN kepeg_departemen DEP ON DEP.ID = PEG.ID_DEPARTEMEN
			LEFT JOIN kepeg_divisi DV ON DV.ID = PEG.ID_DIVISI AND DV.ID_DEPARTEMEN = DEP.ID
			LEFT JOIN(
				SELECT 
					a.ID,
					a.ID_AMBULANCE,
					b.NIP AS NIP_PERAWAT,
					b.NAMA AS NAMA_PERAWAT,
					c.NAMA_DEP AS NAMA_DEP2,
					d.NAMA_DIV AS NAMA_DIV2
				FROM admum_ambulance_perawat a
				LEFT JOIN kepeg_pegawai b ON b.ID = a.ID_PERAWAT
				LEFT JOIN kepeg_departemen c ON c.ID = b.ID_DEPARTEMEN
				LEFT JOIN kepeg_divisi d ON d.ID = b.ID_DIVISI AND d.ID_DEPARTEMEN = c.ID
			) PRW ON PRW.ID_AMBULANCE = AMB.ID
			WHERE AMB.ID = '$id'
		";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function data_detail_perawat($id_ambulance){
		$sql = "
			SELECT 
				a.ID,
				a.ID_AMBULANCE,
				a.ID_PERAWAT,
				b.NIP AS NIP_PERAWAT,
				b.NAMA AS NAMA_PERAWAT,
				c.NAMA_DEP AS NAMA_DEP2,
				d.NAMA_DIV AS NAMA_DIV2,
				e.NAMA AS JABATAN
			FROM admum_ambulance_perawat a
			LEFT JOIN kepeg_pegawai b ON b.ID = a.ID_PERAWAT
			LEFT JOIN kepeg_departemen c ON c.ID = b.ID_DEPARTEMEN
			LEFT JOIN kepeg_divisi d ON d.ID = b.ID_DIVISI AND d.ID_DEPARTEMEN = c.ID
			LEFT JOIN kepeg_kel_jabatan e ON e.ID = b.ID_JABATAN
			WHERE a.ID_AMBULANCE = '$id_ambulance'
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_perawat_id($id){
		$sql = "
			SELECT 
				a.ID,
				a.ID_AMBULANCE,
				a.ID_PERAWAT,
				b.NIP AS NIP_PERAWAT,
				b.NAMA AS NAMA_PERAWAT,
				c.NAMA_DEP AS NAMA_DEP2,
				d.NAMA_DIV AS NAMA_DIV2,
				e.NAMA AS JABATAN
			FROM admum_ambulance_perawat a
			LEFT JOIN kepeg_pegawai b ON b.ID = a.ID_PERAWAT
			LEFT JOIN kepeg_departemen c ON c.ID = b.ID_DEPARTEMEN
			LEFT JOIN kepeg_divisi d ON d.ID = b.ID_DIVISI AND d.ID_DEPARTEMEN = c.ID
			LEFT JOIN kepeg_jabatan e ON e.ID = b.ID_JABATAN
			WHERE a.ID = '$id'
		";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function simpan($kode,$nomor_plat,$id_sopir){
		$sql = "
			INSERT INTO admum_ambulance(
				KODE,
				NOMOR_PLAT,
				ID_SOPIR
			) VALUES(
				'$kode',
				'$nomor_plat',
				'$id_sopir'
			)
		";
		$this->db->query($sql);
	}

	function simpan_perawat($id_ambulance,$id_perawat){
		$sql = "INSERT INTO admum_ambulance_perawat(ID_AMBULANCE,ID_PERAWAT) VALUES('$id_ambulance','$id_perawat')";
		$this->db->query($sql);
	}

	function ubah($id,$nomor_plat,$id_sopir){
		$sql = "UPDATE admum_ambulance SET NOMOR_PLAT = '$nomor_plat', ID_SOPIR = '$id_sopir' WHERE ID = '$id'";
		$this->db->query($sql);
	}

	function ubah_perawat($id,$id_perawat){
		$sql = "UPDATE admum_ambulance_perawat SET ID_PERAWAT = '$id_perawat' WHERE ID = '$id'";
		$this->db->query($sql);
	}

	function hapus($id){
		$sql = "DELETE FROM admum_ambulance WHERE ID = '$id'";
		$this->db->query($sql);
	}

	function hapus_perawat($id_ambulance){
		$sql = "DELETE FROM admum_ambulance_perawat WHERE ID_AMBULANCE = '$id_ambulance'";
		$this->db->query($sql);
	}

}
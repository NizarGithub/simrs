<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admum_poli_m extends CI_Model { 

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function data_jenis_poli(){
		$sql = "SELECT * FROM admum_poli_jenis ORDER BY ID ASC";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_nama_poli(){
		$sql = "SELECT a.NAMA FROM admum_poli a GROUP BY a.NAMA ORDER BY a.ID ASC";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_cetak_poli($nama){
		$sql = "
			SELECT 
				POLI.ID,
				POLI.NAMA,
				POLI.JENIS,
				POLI.STATUS,
				POLI.KETERANGAN,
				POLI.BIAYA,
				PEG.NAMA_DOKTER,
				POLI.AKTIF,
				COUNT(PRWT.ID) AS JUMLAH_PERAWAT
			FROM admum_poli POLI
			LEFT JOIN (
				SELECT
					PEG.ID,
					PEG.NIP,
					PEG.NAMA AS NAMA_DOKTER
				FROM kepeg_pegawai PEG
			) PEG ON PEG.ID = POLI.ID_PEG_DOKTER
			LEFT JOIN admum_poli_perawat PRWT ON PRWT.ID_POLI = POLI.ID
			WHERE POLI.NAMA = '$nama'
			GROUP BY POLI.ID
			ORDER BY POLI.ID ASC
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_poli($keyword,$urutkan,$pilih_jenis,$cari){
		$where = "1 = 1";
		$order = "";

		if($keyword != ""){
			$where = $where." AND (POLI.NAMA LIKE '%$keyword%' OR POLI.INITIAL_POLI LIKE '%$keyword%')";
		}else{
			$where = $where;
		}

		if($urutkan == 'Default'){
			$order = "ORDER BY POLI.ID ASC";
		}else if($urutkan == 'Nama Poli'){
			$order = "ORDER BY POLI.NAMA ASC";
		}

		if($cari == 'Nama Poli'){
			$where = $where;
		}else if($cari == 'Jenis'){
			$where = $where." AND POLI.JENIS = '$pilih_jenis'";
		}

		$sql = "
			SELECT 
				POLI.ID,
				POLI.NAMA,
				POLI.JENIS,
				POLI.STATUS,
				POLI.KETERANGAN,
				POLI.BIAYA,
				PEG.NAMA_DOKTER,
				POLI.AKTIF,
				COUNT(PRWT.ID) AS JUMLAH_PERAWAT
			FROM admum_poli POLI
			LEFT JOIN (
				SELECT
					PEG.ID,
					PEG.NIP,
					PEG.NAMA AS NAMA_DOKTER
				FROM kepeg_pegawai PEG
			) PEG ON PEG.ID = POLI.ID_PEG_DOKTER
			LEFT JOIN admum_poli_perawat PRWT ON PRWT.ID_POLI = POLI.ID
			WHERE $where
			GROUP BY POLI.ID
			$order
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_poli_id($id){
		$sql = "
			SELECT 
				POLI.*,
				PEG.NAMA_DOKTER,
				a.NAMA_DEP,
				b.NAMA_DIV
			FROM admum_poli POLI
			LEFT JOIN (
				SELECT
					PEG.ID,
					PEG.NIP,
					PEG.NAMA AS NAMA_DOKTER
				FROM kepeg_pegawai PEG
			) PEG ON PEG.ID = POLI.ID_PEG_DOKTER
			LEFT JOIN kepeg_departemen a ON POLI.ID_DEPARTEMEN = a.ID
			LEFT JOIN kepeg_divisi b ON POLI.ID_DIVISI = b.ID
			WHERE POLI.ID = '$id'
		";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function get_departemen($keyword){
		$where = "1 = 1";
		if($keyword != ""){
			$where = $where." AND NAMA_DEP LIKE '%$keyword%'";
		}else{
			$where = $where;
		}
		$sql = "SELECT * FROM kepeg_departemen WHERE $where";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function get_departemen_id($id){
		$sql = "SELECT * FROM kepeg_departemen WHERE ID = '$id'";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function get_divisi($id_dep,$keyword){
		$where = "1 = 1";
		if($keyword != ""){
			$where = $where." AND NAMA_DIV LIKE '%$keyword%'";
		}else{
			$where = $where;
		}
		$sql = "SELECT * FROM kepeg_divisi WHERE ID_DEPARTEMEN = '$id_dep'";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function get_divisi_id($id){
		$sql = "SELECT * FROM kepeg_divisi WHERE ID = '$id'";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function data_peg_dokter($keyword){
		$where = "1  = 1";

		if($keyword != ""){
			$where = $where." AND (PEG.NIP LIKE '%$keyword%' OR PEG.NAMA LIKE '%$keyword%')";
		}

		$sql = "
			SELECT
				PEG.ID,
				PEG.NIP,
				PEG.NAMA,
				PEG.`STATUS` AS JABATAN,
				DEP.KODE AS KODE_DEP,
				DEP.NAMA_DEP,
				DV.KODE_DIV,
				DV.NAMA_DIV,
				POLI.NAMA AS NAMA_POLI
			FROM kepeg_pegawai PEG
			LEFT JOIN (
				SELECT 
					ID,
					KODE,
					NAMA_DEP
				FROM kepeg_departemen
				WHERE STS = 0
			) DEP ON DEP.ID = PEG.ID_DEPARTEMEN
			LEFT JOIN (
				SELECT
					ID,
					KODE_DIV,
					NAMA_DIV
				FROM kepeg_divisi
				WHERE STS = 0
			) DV ON DV.ID = PEG.ID_DIVISI
			LEFT JOIN admum_poli POLI ON POLI.ID_PEG_DOKTER = PEG.ID
			LEFT JOIN kepeg_kel_jabatan JAB ON JAB.ID = PEG.ID_JABATAN
			WHERE $where
			AND POLI.ID IS NULL
			ORDER BY PEG.ID DESC
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_peg_dokter_id($id_pegawai){
		$sql = "
			SELECT
				PEG.ID,
				PEG.NIP,
				PEG.NAMA,
				DEP_DIV.KODE AS KODE_DEP,
				DEP_DIV.NAMA_DEP,
				DEP_DIV.KODE_DIV,
				DEP_DIV.NAMA_DIV
			FROM kepeg_pegawai PEG
			LEFT JOIN (
				SELECT 
					DP.ID,
					DP.KODE,
					DP.NAMA_DEP,
					DV.ID AS ID_DIVISI,
					DV.KODE_DIV,
					DV.NAMA_DIV
				FROM kepeg_departemen DP
				LEFT JOIN kepeg_divisi DV ON DV.ID_DEPARTEMEN = DP.ID
				WHERE DP.STS = 0
				AND DV.STS = 0
			) DEP_DIV ON DEP_DIV.ID = PEG.ID_DEPARTEMEN
			LEFT JOIN admum_poli POLI ON POLI.ID_PEG_DOKTER = PEG.ID
			WHERE PEG.ID = '$id_pegawai'
		";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function load_perawat($keyword){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND (PEG.NAMA LIKE '%$keyword%' OR PEG.NIP LIKE '%$keyword')";
		}else{
			$where = $where;
		}

		$sql = "
			SELECT
				PEG.ID,
				PEG.NIP,
				PEG.NAMA,
				JAB.NAMA AS JABATAN,
				DEP.KODE AS KODE_DEP,
				DEP.NAMA_DEP,
				DV.KODE_DIV,
				DV.NAMA_DIV,
				POLI.NAMA AS NAMA_POLI
			FROM kepeg_pegawai PEG
			LEFT JOIN (
				SELECT 
					ID,
					KODE,
					NAMA_DEP
				FROM kepeg_departemen
				WHERE STS = 0
			) DEP ON DEP.ID = PEG.ID_DEPARTEMEN
			LEFT JOIN (
				SELECT
					ID,
					KODE_DIV,
					NAMA_DIV
				FROM kepeg_divisi
				WHERE STS = 0
			) DV ON DV.ID = PEG.ID_DIVISI
			LEFT JOIN kepeg_kel_jabatan JAB ON JAB.ID = PEG.ID_JABATAN
			LEFT JOIN admum_poli_perawat PRWT ON PRWT.ID_PEG_PERAWAT = PEG.ID
			LEFT JOIN admum_poli POLI ON POLI.ID = PRWT.ID_POLI
			WHERE $where
			AND POLI.ID IS NULL
			ORDER BY PEG.ID DESC
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function klik_perawat($id){
		$sql = "
			SELECT
				PEG.ID,
				PEG.NIP,
				PEG.NAMA,
				JAB.NAMA AS JABATAN,
				DEP.KODE AS KODE_DEP,
				DEP.NAMA_DEP,
				DV.KODE_DIV,
				DV.NAMA_DIV,
				POLI.NAMA AS NAMA_POLI
			FROM kepeg_pegawai PEG
			LEFT JOIN (
				SELECT 
					ID,
					KODE,
					NAMA_DEP
				FROM kepeg_departemen
				WHERE STS = 0
			) DEP ON DEP.ID = PEG.ID_DEPARTEMEN
			LEFT JOIN (
				SELECT
					ID,
					KODE_DIV,
					NAMA_DIV
				FROM kepeg_divisi
				WHERE STS = 0
			) DV ON DV.ID = PEG.ID_DIVISI
			LEFT JOIN admum_poli POLI ON POLI.ID_PEG_DOKTER = PEG.ID
			LEFT JOIN kepeg_kel_jabatan JAB ON JAB.ID = PEG.ID_JABATAN
			WHERE PEG.ID = '$id'
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_poli_perawat($id_poli){
		$sql = "
			SELECT
				PRWT.ID,
				PRWT.ID_POLI,
				PRWT.ID_PEG_PERAWAT,
				PEG.NIP,
				PEG.NAMA_PEGAWAI,
				PEG.JABATAN,
				POLI.NAMA AS NAMA_POLI
			FROM admum_poli_perawat PRWT
			LEFT JOIN(
				SELECT
					a.ID,
					a.NIP,
					a.NAMA AS NAMA_PEGAWAI,
					b.NAMA AS JABATAN
				FROM kepeg_pegawai a
				LEFT JOIN kepeg_kel_jabatan b ON a.ID_JABATAN = b.ID
			) PEG ON PEG.ID = PRWT.ID_PEG_PERAWAT
			LEFT JOIN admum_poli POLI ON POLI.ID = PRWT.ID_POLI
			WHERE PRWT.ID_POLI = '$id_poli'
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function simpan($id_dep,$id_div,$jenis,$nama,$status,$keterangan,$biaya,$id_peg_dokter){
		$sql = "
			INSERT INTO admum_poli(
				ID_DEPARTEMEN,
				ID_DIVISI,
				JENIS,
				NAMA,
				STATUS,
				KETERANGAN,
				BIAYA,
				ID_PEG_DOKTER,
				AKTIF
			) VALUES (
				'$id_dep',
				'$id_div',
				'$jenis',
				'$nama',
				'$status',
				'$keterangan',
				'$biaya',
				'$id_peg_dokter',
				'1'
			)
		";
		$this->db->query($sql);
	}

	function ubah($id,$id_dep,$id_div,$jenis,$nama,$status,$keterangan,$biaya,$id_peg_dokter){
		$sql = "
			UPDATE admum_poli SET
				ID_DEPARTEMEN = '$id_dep',
				ID_DIVISI = '$id_div',
				JENIS = '$jenis',
				NAMA = '$nama',
				STATUS = '$status',
				KETERANGAN = '$keterangan',
				BIAYA = '$biaya',
				ID_PEG_DOKTER = '$id_peg_dokter'
			WHERE ID = '$id'
		";
		$this->db->query($sql);
	}

	function hapus($id){
		$sql = "DELETE FROM admum_poli WHERE ID = '$id'";
		$this->db->query($sql);
	}

	function simpan_perawat($id_poli,$id_peg_perawat){
		$sql = "INSERT INTO admum_poli_perawat(ID_POLI,ID_PEG_PERAWAT) VALUES('$id_poli','$id_peg_perawat')";
		$this->db->query($sql);
	}

	function hapus_perawat($id){
		$sql = "DELETE FROM admum_poli_perawat WHERE ID = '$id'";
		$this->db->query($sql);
	}

}
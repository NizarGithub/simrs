<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ap_obat_racik_m extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function data_apoteker($keyword){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND (PEG.NIP LIKE '%$keyword%' OR PEG.NAMA LIKE '%$keyword%')";
		}else{
			$where = $where;
		}

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
			WHERE $where
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function klik_apoteker($id_apoteker){
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
			WHERE PEG.ID = '$id_apoteker'
		";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function data_bahan_racik($keyword){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND (NM_OBT.KODE_OBAT LIKE '%$keyword%' OR NM_OBT.NAMA_OBAT LIKE '%$keyword%')";
		}

		$sql = "
			SELECT
				OBT.ID,
				NM_OBT.KODE_OBAT,
				NM_OBT.BARCODE,
				NM_OBT.NAMA_OBAT,
				OBT.TOTAL,
				SAT.NAMA_SATUAN,
				OBT.ISI,
				OBT.SATUAN_ISI,
				OBT.JUMLAH_BUTIR,
				OBT.SATUAN_BUTIR
			FROM apotek_gudang_obat OBT
			LEFT JOIN admum_setup_nama_obat NM_OBT ON NM_OBT.ID = OBT.ID_SETUP_NAMA_OBAT
			LEFT JOIN obat_satuan SAT ON SAT.ID = OBT.ID_SATUAN_OBAT
			WHERE $where
			AND OBT.STATUS_RACIK = '1'
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function klik_racikan($id){
		$sql = "
			SELECT
				OBT.ID,
				NM_OBT.ID AS ID_NAMA_OBAT,
				NM_OBT.KODE_OBAT,
				NM_OBT.BARCODE,
				NM_OBT.NAMA_OBAT,
				OBT.TOTAL,
				SAT.ID AS ID_SATUAN,
				SAT.NAMA_SATUAN,
				OBT.ISI,
				OBT.SATUAN_ISI,
				OBT.JUMLAH_BUTIR,
				OBT.SATUAN_BUTIR
			FROM apotek_gudang_obat OBT
			LEFT JOIN admum_setup_nama_obat NM_OBT ON NM_OBT.ID = OBT.ID_SETUP_NAMA_OBAT
			LEFT JOIN obat_satuan SAT ON SAT.ID = OBT.ID_SATUAN_OBAT
			WHERE OBT.ID = '$id'
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function simpan_apoteker($sip,$id_apoteker){
		$sql = "INSERT INTO apotek_apoteker(SIP,ID_APOTEKER) VALUES ('$sip','$id_apoteker')";
		$this->db->query($sql);
	}

	function simpan($id_apoteker,$id_nama_obat,$id_satuan,$jumlah_pakai){
		$sql = "INSERT INTO apotek_obat_racik(ID_APOTEKER,ID_NAMA_OBAT,ID_SATUAN,JUMLAH_PAKAI) VALUES ('$id_apoteker','$id_nama_obat','$id_satuan','$jumlah_pakai')";
		$this->db->query($sql);
	}

	function update_stok($id,$jumlah_pakai){
		$sql = "UPDATE apotek_gudang_obat SET TOTAL = TOTAL - $jumlah_pakai WHERE ID = '$id'";
		$this->db->query($sql);
	}

}
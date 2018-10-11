<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setup_nama_paket_m extends CI_Model {

	function __construct(){
		parent::__construct();
		$this->load->database();
	}

	function get_paket($keyword){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND NAMA_PAKET LIKE '%$keyword%'";
		}

		$sql = "SELECT * FROM setup_nama_paket WHERE $where ORDER BY ID ASC";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function get_paket_id($id){
		$sql = "SELECT * FROM setup_nama_paket WHERE ID = '$id'";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function simpan($nama_paket,$hari){
		$sql = "INSERT INTO setup_nama_paket(NAMA_PAKET,HARI) VALUES ('$nama_paket','$hari')";
		$this->db->query($sql);
	}

	function ubah($id,$nama_paket,$hari){
		$sql = "
			UPDATE setup_nama_paket SET
				NAMA_PAKET = '$nama_paket',
				HARI = '$hari'
			WHERE ID = '$id'
		";
		$this->db->query($sql);
	}

	function hapus($id){
		$sql = "DELETE FROM setup_nama_paket WHERE ID = '$id'";
		$this->db->query($sql);
	}

	function get_kamar_paket($id_paket){
		$where = "1 = 1";

		$sql = "
			SELECT
				a.ID,
				a.ID_PAKET,
				a.KELAS,
				a.BIAYA_KAMAR_BERSALIN,
				a.BIAYA_KAMAR_PERAWATAN,
				a.BIAYA_KAMAR_NEO,
				(a.BIAYA_PELAYANAN+a.BIAYA_PAKET_OBAT+a.BUKU_PASPOR+a.TINDAKAN+a.JASA_OPERATOR+a.VISITE_DOKTER+a.VISITE_PROF+a.JASA_ANASTESI+a.JASA_PENATA_ANASTESI) AS LAINNYA
			FROM(
				SELECT
					a.ID,
					a.ID_PAKET,
					a.KELAS,
					a.BIAYA_KAMAR_BERSALIN,
					a.BIAYA_KAMAR_PERAWATAN,
					a.BIAYA_KAMAR_NEO,
					a.BIAYA_PELAYANAN,
					a.BIAYA_PAKET_OBAT,
					a.BUKU_PASPOR,
					a.JASA_OPERATOR,
					a.VISITE_DOKTER,
					a.VISITE_PROF,
					a.JASA_ANASTESI,
					a.JASA_PENATA_ANASTESI,
					SUM(b.TARIF) AS TINDAKAN
				FROM setup_kamar_paket a
				JOIN(
					SELECT
						a.*,
						b.NAMA_TINDAKAN,
						b.TARIF
					FROM setup_tindakan_paket a
					JOIN admum_setup_tindakan b ON b.ID = a.ID_TINDAKAN
				) b ON b.ID_KAMAR_PAKET = a.ID
				WHERE a.ID_PAKET = '$id_paket'
				GROUP BY a.ID
			) a
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function get_kamar_paket_id($id){
		$sql = "
			SELECT
				a.ID,
				a.ID_PAKET,
				a.KELAS,
				a.BIAYA_KAMAR_BERSALIN,
				a.BIAYA_KAMAR_PERAWATAN,
				a.BIAYA_KAMAR_NEO,
				a.BIAYA_PELAYANAN,
				a.BIAYA_PAKET_OBAT,
				a.BUKU_PASPOR,
				a.JASA_OPERATOR,
				a.VISITE_DOKTER,
				a.VISITE_PROF,
				a.JASA_ANASTESI,
				a.JASA_PENATA_ANASTESI
			FROM setup_kamar_paket a
			WHERE a.ID = '$id'
		";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function get_tindakan_paket($id_kamar_paket){
		$sql = "
			SELECT
				a.*,
				b.NAMA_TINDAKAN,
				b.TARIF
			FROM setup_tindakan_paket a
			JOIN admum_setup_tindakan b ON b.ID = a.ID_TINDAKAN
			WHERE a.ID_KAMAR_PAKET = '$id_kamar_paket'
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function load_tindakan($keyword){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND (NAMA_TINDAKAN LIKE '%$keyword%' OR KODE LIKE '%$keyword%')";
		}else{
			$where = $where;
		}

		$sql = "SELECT * FROM admum_setup_tindakan WHERE $where ORDER BY ID DESC";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function klik_tindakan($id){
		$sql = "SELECT * FROM admum_setup_tindakan WHERE ID = '$id'";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function simpan_kamar(
		$id_paket,
		$kelas,
		$biaya_kamar_bersalin,
		$biaya_kamar_perawatan,
		$biaya_kamar_neo,
		$biaya_pelayanan,
		$biaya_obat,
		$buku_paspor,
		$jasa_operator,
		$visite_dokter,
		$visite_prof,
		$jasa_anastesi,
		$jasa_penata_anastesi,
		$tanggal,
		$bulan,
		$tahun){

		$sql = "
			INSERT INTO setup_kamar_paket(
				ID_PAKET,
				KELAS,
				BIAYA_KAMAR_BERSALIN,
				BIAYA_KAMAR_PERAWATAN,
				BIAYA_KAMAR_NEO,
				BIAYA_PELAYANAN,
				BIAYA_PAKET_OBAT,
				BUKU_PASPOR,
				JASA_OPERATOR,
				VISITE_DOKTER,
				VISITE_PROF,
				JASA_ANASTESI,
				JASA_PENATA_ANASTESI,
				TANGGAL,
				BULAN,
				TAHUN
			) VALUES (
				'$id_paket',
				'$kelas',
				'$biaya_kamar_bersalin',
				'$biaya_kamar_perawatan',
				'$biaya_kamar_neo',
				'$biaya_pelayanan',
				'$biaya_obat',
				'$buku_paspor',
				'$jasa_operator',
				'$visite_dokter',
				'$visite_prof',
				'$jasa_anastesi',
				'$jasa_penata_anastesi',
				'$tanggal',
				'$bulan',
				'$tahun'
			)
		";
		$this->db->query($sql);
	}

	function simpan_tindakan($id_kamar_paket,$id_tindakan){
		$sql = "INSERT INTO setup_tindakan_paket(ID_KAMAR_PAKET,ID_TINDAKAN) VALUES ('$id_kamar_paket','$id_tindakan')";
		$this->db->query($sql);
	}

}
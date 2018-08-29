<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Antrian_home_m extends CI_Model
{
	function __construct() {
		  parent::__construct();
		  $this->load->database();
	}

	function getNomorBilling(){
		$sql = "
		SELECT * FROM bill_nomor_trx 
		";

		return $this->db->query($sql)->row();
	}

	function getDataAntrian(){
		$tgl = date('d-m-Y');
		$sql =  "
		SELECT a.KODE, MAX(a.URUT) AS URUT, 'Admission' AS NAMA_LOKET FROM kepeg_antrian a
		WHERE a.TGL LIKE '%$tgl%'
		GROUP BY a.KODE
		";

		return $this->db->query($sql)->result();
	}

	function getDataAntrian_last(){
		$tgl = date('d-m-Y');
		$sql =  "
		SELECT a.KODE, MAX(a.URUT) AS URUT, 'Admission' AS NAMA_LOKET FROM kepeg_antrian a
		WHERE a.TGL LIKE '%$tgl%'
		GROUP BY a.KODE
		ORDER BY a.ID DESC
		";

		return $this->db->query($sql)->row();
	}

	function get_antrian_pasien(){
		$sql = "
			SELECT
				a.ID,
				a.TANGGAL,
				a.WAKTU,
				a.ID_PASIEN,
				a.ID_PELAYANAN,
				a.BARCODE,
				a.WAKTU,
				a.ID_LOKET,
				b.NAMA_LOKET,
				c.NAMA_POLI,
				a.KODE_ANTRIAN,
				a.NOMOR_ANTRIAN,
				a.STATUS_PANGGIL
			FROM rk_antrian_pasien a
			JOIN kepeg_loket b ON b.ID = a.ID_LOKET
			JOIN (
				SELECT 
					a.ID,
					a.ID_POLI,
					b.NAMA AS NAMA_POLI
				FROM admum_rawat_jalan a
				JOIN admum_poli b ON b.ID = a.ID_POLI
			) c ON c.ID = a.ID_PELAYANAN
			WHERE a.STATUS_PANGGIL = '1'
			AND a.STATUS_CLOSING = '0'
			ORDER BY a.NOMOR_ANTRIAN DESC
			LIMIT 1
		";
		$query = $this->db->query($sql);
		return $query->result();
	}
}

?>
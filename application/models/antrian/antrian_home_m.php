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
		SELECT a.KODE, MAX(a.URUT) AS URUT, b.NAMA_LOKET FROM kepeg_antrian a
		JOIN kepeg_loket b ON a.ID_KODE = b.KODE_ANTRIAN		
		WHERE a.TGL LIKE '%$tgl%'
		GROUP BY a.KODE, b.NAMA_LOKET
		";

		return $this->db->query($sql)->result();
	}

	function getDataAntrian_last(){
		$tgl = date('d-m-Y');
		$sql =  "
		SELECT a.KODE, MAX(a.URUT) AS URUT, b.NAMA_LOKET FROM kepeg_antrian a
		JOIN kepeg_loket b ON a.ID_KODE = b.KODE_ANTRIAN		
		WHERE a.TGL LIKE '%$tgl%'
		GROUP BY a.KODE, b.NAMA_LOKET
		ORDER BY a.ID DESC
		";

		return $this->db->query($sql)->row();
	}
}

?>
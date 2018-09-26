<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Waiting_rawat_inap_m extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function get_data_pasien_poli($keyword){ 
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND (
								b.NAMA LIKE '%$keyword%' OR
								b.NAMA_AYAH LIKE '%$keyword%' OR
								b.NAMA_IBU LIKE '%$keyword%'
							)";
		}else{
			$where = $where;
		}

		$sql = "
			SELECT
				a.ID,
				a.ID_PASIEN,
				a.TANGGAL_MASUK,
				b.KODE_PASIEN,
				b.NAMA,
				b.JENIS_KELAMIN,
				b.TANGGAL_LAHIR,
				b.UMUR,
				b.UMUR_BULAN,
				b.NAMA_AYAH,
				b.NAMA_IBU,
				c.NAMA AS NAMA_POLI
			FROM admum_rawat_inap a
			JOIN rk_pasien b ON b.ID = a.ID_PASIEN
			JOIN admum_poli c ON c.ID = a.ID_POLI
			WHERE $where
			AND a.ASAL_RUJUKAN = 'Dari Poli'
			ORDER BY a.ID DESC
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


}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
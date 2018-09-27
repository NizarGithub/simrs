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
				c.NAMA AS NAMA_POLI,
				a.STS_WAITING
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

	function klik_pasien_poli($id){
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
				b.TELEPON,
				c.NAMA AS NAMA_POLI
			FROM admum_rawat_inap a
			JOIN rk_pasien b ON b.ID = a.ID_PASIEN
			JOIN admum_poli c ON c.ID = a.ID_POLI
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

	function update_rawat_inap($id,$pjawab,$telepon,$sistem_bayar,$kelas,$id_kamar,$id_bed,$biaya_kamar,$biaya_charge,$biaya_reg){
		$sql = "
			UPDATE admum_rawat_inap SET
				NAMA_PENANGGUNGJAWAB = '$pjawab',
				TELEPON = '$telepon',
				SISTEM_BAYAR = '$sistem_bayar',
				KELAS = '$kelas',
				ID_KAMAR = '$id_kamar',
				ID_BED = '$id_bed',
				BIAYA_KAMAR_FIX = '$biaya_kamar',
				BIAYA_CHARGE_KAMAR = '$biaya_charge',
				BIAYA_REG = '$biaya_reg',
				STS_WAITING = '1'
			WHERE ID = '$id'
		";
		$this->db->query($sql);
	}

	function update_stt_pakai($id){
		$sql = "UPDATE admum_bed_rawat_inap SET STATUS_PAKAI = '1' WHERE ID = '$id'";
		$this->db->query($sql);
	}

	function simpan_asuransi($id_ri,$id_asuransi,$no_polis,$no_peserta,$nama,$status_pasien){
		$sql = "
			INSERT INTO asr_asuransi(
				ID_RAWAT_INAP,
				ID_ASURANSI,
				NO_POLIS,
				NO_PESERTA,
				NAMA,
				STATUS_PASIEN
			) VALUES (
				'$id_ri',
				'$id_asuransi',
				'$no_polis',
				'$no_peserta',
				'$nama',
				'$status_pasien'
			)
		";
		$this->db->query($sql);
	}


}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
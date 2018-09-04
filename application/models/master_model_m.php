<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_model_m extends CI_Model
{
	function __construct() {
		  parent::__construct();
		  $this->load->database();
	}

	function simpan_log($id_pegawai,$tanggal,$waktu,$keterangan){
		$sql = "
			INSERT INTO kepeg_log_aktifitas(
				ID_PEGAWAI,
				TANGGAL,
				WAKTU,
				KETERANGAN
			) VALUES (
				'$id_pegawai',
				'$tanggal',
				'$waktu',
				'$keterangan'
			)
		";
		$this->db->query($sql);
	}

	function simpan_log2($id_pegawai,$id_pasien,$tanggal,$waktu,$keterangan){
		$sql = "
			INSERT INTO kepeg_log_aktifitas(
				ID_PEGAWAI,
				ID_PASIEN,
				TANGGAL,
				WAKTU,
				KETERANGAN
			) VALUES (
				'$id_pegawai',
				'$id_pasien',
				'$tanggal',
				'$waktu',
				'$keterangan'
			)
		";
		$this->db->query($sql);
	}

	function get_user_info($id_user){
		$sql = "
		SELECT a.*, b.NAMA AS JABATAN, c.NAMA_DEP , d.NAMA_DIV FROM kepeg_pegawai a 
		LEFT JOIN kepeg_kel_jabatan b ON a.ID_JABATAN = b.ID 
		LEFT JOIN kepeg_departemen c ON a.ID_DEPARTEMEN = c.ID 
		LEFT JOIN kepeg_divisi d ON a.ID_DIVISI = d.ID 
		WHERE a.ID = '$id_user'
		ORDER BY a.ID ASC
		";

		return $this->db->query($sql)->row();
	}

	function cek_user_info($id_user,$akses,$status){
		$sql = "
			SELECT 
				a.*,
				d.STS AS STATUS
			FROM kepeg_loket_operator a 
			JOIN kepeg_loket_akses b ON a.ID_LOKET = b.ID_LOKET 
			JOIN kepeg_loket c ON c.ID = b.ID_LOKET
			JOIN kepeg_setup_antrian d ON d.ID = c.KODE_ANTRIAN
			WHERE a.ID_PEGAWAI = '$id_user' 
			AND b.AKSES = '$akses'
			AND d.STS = '$status'
		";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function is_operator($id_user, $menu){
		$sql = "
		SELECT a.* FROM kepeg_loket_operator a
		JOIN kepeg_loket_akses b ON a.ID_LOKET = b.ID_LOKET
		WHERE a.ID_PEGAWAI = '$id_user' AND b.AKSES = '$menu'
		";

		return $this->db->query($sql)->result();
	}

	function getLoket($id_user, $akses, $status){
		$sql = "
			SELECT
				a.ID,
				a.ID_LOKET,
				a.ID_PEGAWAI,
				b.*, 
				c.KODE,
				c.STS AS STATUS
			FROM kepeg_loket_operator a
			JOIN kepeg_loket b ON a.ID_LOKET = b.ID
			JOIN kepeg_setup_antrian c ON b.KODE_ANTRIAN = c.ID
			JOIN kepeg_loket_akses d ON b.ID = d.ID_LOKET
			WHERE a.ID_PEGAWAI = '$id_user'
			AND d.AKSES = '$akses'
			AND c.STS = '$status'
		";

		return $this->db->query($sql)->row();
	}

	function getJmlAntrian($id_kode_antrian,$status,$id_user){
		$tgl = date('d-m-Y');

		$sql = "
			SELECT
				a.*, 
				c.ID_KODE,
				c.KODE,
				c.URUT,
				c.TGL
			FROM kepeg_loket a
			LEFT JOIN kepeg_setup_antrian b ON b.ID = a.KODE_ANTRIAN
			JOIN kepeg_antrian c ON c.ID_KODE = a.KODE_ANTRIAN
			WHERE 1 = 1
			AND c.ID_KODE = '$id_kode_antrian'
			AND c.STATUS_CLOSING = '0'
		";

		return $this->db->query($sql)->result();
	}

	function getJmlAntrianStruk($id_kode_antrian,$status,$id_user){
		$tgl = date('d-m-Y');

		$sql = "
			SELECT
				a.*,
				c.ID_KODE,
				c.KODE,
				c.URUT,
				c.TGL,
				b.STS AS STATUS
			FROM kepeg_loket a
			LEFT JOIN kepeg_setup_antrian b ON b.ID = a.KODE_ANTRIAN
			LEFT JOIN kepeg_antrian c ON c.ID_KODE = a.KODE_ANTRIAN
			WHERE 1 = 1
			AND c.ID_KODE = '$id_kode_antrian'
			AND c.STATUS_CLOSING = '0'
		";

		return $this->db->query($sql)->result();
	}

	function get_menu_2($id_pegawai, $id_menu1){
		$sql = "
		SELECT a.* FROM kepeg_menu_2 a 
		JOIN (
			SELECT ID_MENU FROM kepeg_hak_akses
			WHERE ID_PEGAWAI = $id_pegawai AND KET = 'MENU_2'
		) b ON a.ID = b.ID_MENU
		WHERE a.ID_MENU_1 = $id_menu1
        ORDER BY a.URUT ASC
		";

		return $this->db->query($sql)->result();
	}

	function get_menu_3($id_pegawai, $id_menu2){
		$sql = "
		SELECT a.* FROM kepeg_menu_3 a 
		JOIN (
			SELECT ID_MENU FROM kepeg_hak_akses
			WHERE ID_PEGAWAI = $id_pegawai AND KET = 'MENU_3'
		) b ON a.ID = b.ID_MENU
		WHERE a.ID_MENU_2 = $id_menu2
        ORDER BY a.URUT ASC
		";

		return $this->db->query($sql)->result();
	}

	function get_data_asuransi(){
		$sql = "
		SELECT * FROM asr_setup_asuransi
		ORDER BY ID ASC
		";

		return $this->db->query($sql)->result();
	}

	function simpanAntrian($id_antrian,$kode_antrian,$jml_antrian,$tgl,$status){
		$sql = "
			INSERT INTO kepeg_antrian(
				ID_KODE, 
				KODE, 
				URUT, 
				TGL,
				STS
			) VALUES (
				'$id_antrian', 
				'$kode_antrian', 
				'$jml_antrian', 
				'$tgl',
				'$status'
			)
		";
		$this->db->query($sql);
	}

	function ubahAntrian($urut,$tgl,$status){
		$sql = "
			UPDATE kepeg_antrian SET
				URUT = '$urut'
			WHERE TGL = '$tgl' AND STS = '$status'
		";
		$this->db->query($sql);
	}

	function get_dokter(){
		$sql = "
			SELECT 
				a.*,
				b.NAMA_DIV
			FROM kepeg_pegawai a
			LEFT JOIN kepeg_divisi b ON b.ID = a.ID_DIVISI
			WHERE a.STS_LOGIN = '1'
			ORDER BY a.ID DESC
			LIMIT 10
		";
		return $this->db->query($sql)->result();
	}

	function get_tracking_pasien($tanggal){
		$sql = "
			SELECT
				a.*,
				b.NAMA AS NAMA_PASIEN,
				b.JENIS_KELAMIN,
				c.NAMA AS NAMA_POLI
			FROM admum_rawat_jalan a
			LEFT JOIN rk_pasien b ON b.ID = a.ID_PASIEN
			LEFT JOIN admum_poli c ON c.ID = a.ID_POLI
			WHERE a.TANGGAL = '$tanggal'
			ORDER BY a.ID DESC
			LIMIT 5
		";
		return $this->db->query($sql)->result();
	}

	function get_data_dokter(){
		$sql = "
			SELECT
				a.*
			FROM kepeg_pegawai a
		";
		return $this->db->query($sql)->result();
	}

	function get_dokter_id($id){
		$sql = "
			SELECT
				a.*
			FROM kepeg_pegawai a
			WHERE a.ID = '$id' 
		";
		return $this->db->query($sql)->row();
	}

	function get_total_all_pasien($tanggal){
		$sql = "
			SELECT
				a.*
			FROM admum_rawat_jalan a
			WHERE a.TANGGAL = '$tanggal'
		";
		return $this->db->query($sql)->result();
	}

	function get_total_pasien_poli($tanggal){
		$sql = "
			SELECT
				a.*
			FROM admum_rawat_jalan a
			WHERE a.TANGGAL = '$tanggal'
			AND a.STS_POSISI = '1'
		";
		return $this->db->query($sql)->result();
	}

	function get_total_pasien_lab($tanggal){
		$sql = "
			SELECT
				a.*
			FROM admum_rawat_jalan a
			WHERE a.TANGGAL = '$tanggal'
			AND a.STS_POSISI = '2'
		";
		return $this->db->query($sql)->result();
	}

	function get_akses_antrian($akses){
		$sql = "
			SELECT
				a.*,
				b.KODE_ANTRIAN,
				b.NAMA_LOKET,
				c.KODE,
				c.STS AS STS_LOKET
			FROM kepeg_loket_akses a
			JOIN kepeg_loket b ON b.ID = a.ID_LOKET
			LEFT JOIN kepeg_setup_antrian c ON c.ID = b.KODE_ANTRIAN
			WHERE a.AKSES = '$akses'
			ORDER BY a.ID_LOKET ASC
		";
		return $this->db->query($sql)->result();
	}

}

?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_model_m extends CI_Model
{
	function __construct() {
		  parent::__construct();
		  $this->load->database();
	}

	function get_user_info($id_user){
		$sql = "
		SELECT a.*, b.NAMA AS JABATAN, c.NAMA_DEP , d.NAMA_DIV FROM kepeg_pegawai a 
		LEFT JOIN kepeg_kel_jabatan b ON a.ID_JABATAN = b.ID 
		LEFT JOIN kepeg_departemen c ON a.ID_DEPARTEMEN = c.ID 
		LEFT JOIN kepeg_divisi d ON a.ID_DIVISI = d.ID 
		WHERE a.ID = $id_user
		ORDER BY a.ID ASC
		";

		return $this->db->query($sql)->row();
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

	function is_operator($id_user, $menu){
		$sql = "
		SELECT a.* FROM kepeg_loket_operator a
		JOIN kepeg_loket_akses b ON a.ID_LOKET = b.ID_LOKET
		WHERE a.ID_PEGAWAI = $id_user AND b.AKSES = '$menu'
		";

		return $this->db->query($sql)->result();
	}

	function getLoket($id_user, $akses){
		$sql = "
		SELECT b.*, c.KODE FROM kepeg_loket_operator a
		JOIN kepeg_loket b ON a.ID_LOKET = b.ID
		JOIN kepeg_setup_antrian c ON b.KODE_ANTRIAN = c.ID
		JOIN kepeg_loket_akses d ON b.ID = d.ID_LOKET
		WHERE a.ID_PEGAWAI = $id_user AND d.AKSES = '$akses'
		";

		return $this->db->query($sql)->row();
	}

	function getJmlAntrian($id_kode_antrian){
		$tgl = date('d-m-Y');

		$sql = "
		SELECT * FROM kepeg_antrian
		WHERE ID_KODE = $id_kode_antrian AND TGL = '$tgl'
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

}

?>
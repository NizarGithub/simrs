<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hak_akses_m extends CI_Model {  

	function __construct()
	{   
		parent::__construct();
		$this->load->database();  
	}
  
	function cek_kode_jabatan($kode_jab){  
		$sql = " 
		SELECT * FROM kepeg_jabatan WHERE KODE_JABATAN = '$kode_jab'
		"; 
 
		return $this->db->query($sql)->result();
	} 

	function get_data_pegawai($id_pegawai){
		$sql = "
		SELECT * FROM kepeg_pegawai WHERE ID = $id_pegawai
		";

		return $this->db->query($sql)->row();
	}

	function get_data_menu_1($id_pegawai){

		if($id_pegawai == ""){
			$sql = "
				SELECT a.*, 0 AS STS FROM kepeg_menu_1 a
				ORDER BY a.URUT ASC
			"; 
		} else {
			$sql = "
			SELECT a.ID, a.NAMA, a.LINK, a.ICON, a.URUT, IFNULL(a.STS, 0) AS STS FROM (
				SELECT a.ID, a.NAMA, a.LINK, a.ICON, a.URUT, IFNULL(a.STS, 0) AS STS FROM (
					SELECT a.*, IFNULL(b.ID_MENU, 0) AS STS FROM kepeg_menu_1 a 
					LEFT JOIN (
						SELECT ID_MENU FROM kepeg_hak_akses
						WHERE ID_PEGAWAI = $id_pegawai AND KET = 'MENU_PORTAL'
					) b ON a.ID = b.ID_MENU
				) a 				
			) a
            ORDER BY a.URUT ASC
			";
		}		

		return $this->db->query($sql)->result();
	}

	function get_data_menu_2($id_menu1, $id_pegawai){
		

		if($id_pegawai == ""){
			$sql = "
				SELECT a.*, 0 AS STS FROM kepeg_menu_2 a WHERE a.ID_MENU_1 = $id_menu1
				ORDER BY a.URUT ASC
				";
		} else {
			$sql = "
			SELECT a.ID, a.NAMA, a.LINK, a.ICON, a.URUT, IFNULL(a.STS, 0) AS STS FROM (
				SELECT a.ID, a.NAMA, a.LINK, a.ICON, a.URUT, IFNULL(a.STS, 0) AS STS FROM (
					SELECT a.*, IFNULL(b.ID_MENU, 0) AS STS FROM kepeg_menu_2 a 
					LEFT JOIN (
						SELECT ID_MENU FROM kepeg_hak_akses
						WHERE ID_PEGAWAI = $id_pegawai AND KET = 'MENU_2'
					) b ON a.ID = b.ID_MENU
					WHERE a.ID_MENU_1 = $id_menu1
				) a 				
			) a
            ORDER BY a.URUT ASC
			";
		}

		return $this->db->query($sql)->result();
	}

	function get_data_menu_3($id_menu2, $id_pegawai){
		

		if($id_pegawai == ""){
			$sql = "
			SELECT a.*, 0 AS STS FROM kepeg_menu_3 a WHERE a.ID_MENU_2 = $id_menu2
			ORDER BY a.URUT ASC
			";
		} else {
			$sql = "
			SELECT a.ID, a.NAMA, a.LINK, a.ICON, a.URUT, IFNULL(a.STS, 0) AS STS FROM (
				SELECT a.ID, a.NAMA, a.LINK, a.ICON, a.URUT, IFNULL(a.STS, 0) AS STS FROM (
					SELECT a.*, IFNULL(b.ID_MENU, 0) AS STS FROM kepeg_menu_3 a 
					LEFT JOIN (
						SELECT ID_MENU FROM kepeg_hak_akses
						WHERE ID_PEGAWAI = $id_pegawai AND KET = 'MENU_3'
					) b ON a.ID = b.ID_MENU
					WHERE a.ID_MENU_2 = $id_menu2
				) a 				
			) a
            ORDER BY a.URUT ASC
			";
		}

		return $this->db->query($sql)->result();
	}

	function hapus_all_akses($id_pegawai){
		$sql = "
		DELETE FROM kepeg_hak_akses WHERE ID_PEGAWAI = $id_pegawai
		";

		$this->db->query($sql);
	}

	function simpan_hak_akses_menu_portal($id_pegawai, $id_menu, $ket){
		$sql = "
		INSERT INTO kepeg_hak_akses 
		(ID_PEGAWAI, ID_MENU, KET)
		VALUES 
		($id_pegawai, $id_menu, '$ket')
		"; 

		$this->db->query($sql);
	}

}
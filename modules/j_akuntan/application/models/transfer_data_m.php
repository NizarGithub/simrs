<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Transfer_data_m extends CI_Model
{
	function __construct() {
		  parent::__construct();
		  $this->load->database(); 
	}

    function get_data_laporan($id_klien){

        $sql = "
        SELECT * FROM ak_profil_usaha WHERE ID_KLIEN = $id_klien
        ";

        return $this->db->query($sql)->row();
    }

    function ubah_nama($id_klien, $nama_lengkap){

        $sql = "
        UPDATE ak_user SET NAMA = '$nama_lengkap' WHERE ID_KLIEN = $id_klien
        ";

        $this->db->query($sql);
    }

    function edit_laporan_header($id_klien, $foto){
        $sql = "
        UPDATE ak_profil_usaha SET HEADER_LAPORAN = '$foto' 
        WHERE ID_KLIEN = $id_klien
        ";

        $this->db->query($sql);
    }

    function ganti_password($id_klien, $password){
        $sql = "
        UPDATE ak_user SET PASSWORD = '$password' WHERE ID = $id_klien
        ";

        $this->db->query($sql);
    }

    function ganti_judul_laporan($id_klien, $nama_laporan){
        $sql = "
        UPDATE ak_profil_usaha SET NAMA_LAPORAN = '$nama_laporan' 
        WHERE ID_KLIEN = $id_klien
        ";

        $this->db->query($sql);
    }


}

?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pengaturan_akun_m extends CI_Model
{
	function __construct() {
		  parent::__construct();
		  $this->load->database();
	}


    function get_data_akun($id_user){
        $sql = "
        SELECT a.*, b.NAMA AS JABATAN, c.NAMA_DEP , d.NAMA_DIV FROM kepeg_pegawai a 
        LEFT JOIN kepeg_jabatan b ON a.ID_JABATAN = b.ID 
        LEFT JOIN kepeg_departemen c ON a.ID_DEPARTEMEN = c.ID 
        LEFT JOIN kepeg_divisi d ON a.ID_DIVISI = d.ID 
        WHERE a.ID = $id_user
        ORDER BY a.ID ASC
        ";

        return $this->db->query($sql)->row();
    }


    function get_data_akun_lama($id_klien){

        $sql = "
        SELECT * FROM ak_user WHERE ID_KLIEN = $id_klien
        ";

        return $this->db->query($sql)->row();
    }

    function ubah_nama($id_klien, $nama_lengkap){

        $sql = "
        UPDATE ak_user SET NAMA = '$nama_lengkap' WHERE ID_KLIEN = $id_klien
        ";

        $this->db->query($sql);
    }

    function edit_ava_user($id_klien, $foto){
        $sql = "
        UPDATE ak_user SET FOTO = '$foto' 
        WHERE ID_KLIEN = $id_klien
        ";

        $this->db->query($sql);
    }

    function ganti_password($id_klien, $password){

        $password = md5(md5($password));

        $sql = "
        UPDATE ak_user SET PASSWORD = '$password' WHERE ID_KLIEN = $id_klien
        ";

        $this->db->query($sql);
    }


}

?>
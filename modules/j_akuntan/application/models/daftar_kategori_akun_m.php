<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Daftar_kategori_akun_m extends CI_Model
{
	function __construct() {
		  parent::__construct();
		  $this->load->database();
	}

    function get_data_kategori($keyword, $id_klien){
        $where = "1=1";
        if($keyword != "" || $keyword != null){
            $where = $where." AND (NAMA_KATEGORI LIKE '%$keyword%') ";
        }

        $sql = "
        SELECT * FROM ak_kategori_akun
        WHERE $where AND ID_KLIEN = $id_klien
        ORDER BY ID ASC
        ";

        return $this->db->query($sql)->result();
    }


    function hapus_kategori($id){
        $sql = "
        DELETE FROM ak_kategori_akun WHERE ID = $id
        ";

        $this->db->query($sql);
    }

    function cari_kat_by_id($id){
        $sql = "
        SELECT * FROM ak_kategori_akun WHERE ID = $id
        ";

        return $this->db->query($sql)->row();
    }


    function simpan_kat($id_klien, $nama_kat, $deskripsi){

        $sql = "
        INSERT INTO ak_kategori_akun
        (ID_KLIEN, NAMA_KATEGORI, DESKRIPSI)
        VALUES 
        ($id_klien, '$nama_kat', '$deskripsi')
        ";

        $this->db->query($sql);
    }

    function edit_kat($id_kat, $nama_kat_ed, $deskripsi_ed){

        $sql = "
        UPDATE ak_kategori_akun SET 
        NAMA_KATEGORI = '$nama_kat_ed', DESKRIPSI = '$deskripsi_ed'
        WHERE ID = $id_kat
        ";

        $this->db->query($sql);
    }

}

?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Daftar_kode_akun_m extends CI_Model
{
	function __construct() {
		  parent::__construct();
		  $this->load->database();
	}

    function get_no_akun($keyword, $id_klien){
        $where = "1=1";
        if($keyword != "" || $keyword != null){
            $where = $where." AND ( (KODE_AKUN LIKE '%$keyword%') OR (NAMA_AKUN LIKE '%$keyword%') OR (KATEGORI LIKE '%$keyword%')) ";
        }

        $sql = "
        SELECT * FROM ak_kode_akuntansi
        WHERE $where AND ID_KLIEN = $id_klien
        ORDER BY KODE_AKUN ASC
        ";

        return $this->db->query($sql)->result();
    }

    function simpan_akun($id_klien, $nama_akun, $nomor_akun, $deskripsi, $kategori){
        $sql = "
            INSERT INTO ak_kode_akuntansi
            (ID_KLIEN, KODE_AKUN, NAMA_AKUN, KATEGORI, DESKRIPSI)
            VALUES 
            ($id_klien, '$nomor_akun', '$nama_akun', '$kategori', '$deskripsi')
        ";

        $this->db->query($sql);
    }

    function hapus_akun($id){
        $sql = "
        DELETE FROM ak_kode_akuntansi WHERE ID = $id
        ";

        $this->db->query($sql);
    }

    function cari_kode_by_id($id){
        $sql = "
        SELECT * FROM ak_kode_akuntansi WHERE ID = $id
        ";

        return $this->db->query($sql)->row();
    }

    function edit_akun($id_akun_ed, $nama_akun_ed, $nomor_akun_ed, $deskripsi_ed, $kategori_ed){
        $sql = "
            UPDATE ak_kode_akuntansi SET 
            NAMA_AKUN = '$nama_akun_ed', DESKRIPSI = '$deskripsi_ed', KATEGORI = '$kategori_ed'
            WHERE ID = $id_akun_ed
        ";

        $this->db->query($sql);
    }

}

?>
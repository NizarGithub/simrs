<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Produk_m extends CI_Model
{
	function __construct() {
		  parent::__construct();
		  $this->load->database();
	}

    function get_data_produk($keyword, $id_klien){
        $where = "1=1";
        if($keyword != "" || $keyword != null){
            $where = $where." AND (NAMA_PRODUK LIKE '%$keyword%' OR KODE_PRODUK LIKE '%$keyword%' ) ";
        }

        $sql = "
        SELECT * FROM ak_produk
        WHERE $where AND ID_KLIEN = $id_klien
        ORDER BY ID ASC
        ";

        return $this->db->query($sql)->result();
    }


    function hapus_produk($id){
        $sql = "
        DELETE FROM ak_produk WHERE ID = $id
        ";

        $this->db->query($sql);
    }

    function cari_produk_by_id($id){
        $sql = "
        SELECT * FROM ak_produk WHERE ID = $id
        ";

        return $this->db->query($sql)->row();
    }


    function simpan_produk($id_klien, $kode_produk, $nama_produk, $satuan, $deskripsi){

        if($satuan == "" || $satuan == null){
            $satuan = "-";
        }

        if($deskripsi == "" || $deskripsi == null){
            $deskripsi = "-";
        }

        $sql = "
        INSERT INTO ak_produk
        (ID_KLIEN, KODE_PRODUK, NAMA_PRODUK, SATUAN, DESKRIPSI)
        VALUES 
        ($id_klien, '$kode_produk', '$nama_produk', '$satuan', '$deskripsi')
        ";

        $this->db->query($sql);
    }

    function edit_produk($id_produk, $kode_produk_ed, $nama_produk_ed, $satuan_ed, $deskripsi_ed){

        $sql = "
        UPDATE ak_produk SET 
        KODE_PRODUK = '$kode_produk_ed', NAMA_PRODUK = '$nama_produk_ed', SATUAN = '$satuan_ed', DESKRIPSI = '$deskripsi_ed'
        WHERE ID = $id_produk
        ";

        $this->db->query($sql);
    }

}

?>
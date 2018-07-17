<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Supplier_m extends CI_Model
{
	function __construct() {
		  parent::__construct();
		  $this->load->database();
	}

    function get_data_supplier($keyword, $id_klien){
        $where = "1=1";
        if($keyword != "" || $keyword != null){
            $where = $where." AND NAMA_SUPPLIER LIKE '%$keyword%' ";
        }

        $sql = "
        SELECT * FROM ak_supplier
        WHERE $where AND ID_KLIEN = $id_klien
        ORDER BY ID ASC
        ";

        return $this->db->query($sql)->result();
    }


    function hapus_supplier($id){
        $sql = "
        DELETE FROM ak_supplier WHERE ID = $id
        ";

        $this->db->query($sql);
    }

    function cari_supplier_by_id($id){
        $sql = "
        SELECT * FROM ak_supplier WHERE ID = $id
        ";

        return $this->db->query($sql)->row();
    }


    function simpan_supplier($id_klien, $nama_supplier, $npwp, $alamat_tagih, $no_telp, $no_hp, $email, $tipe, $nama_usaha, $tdp, $siup){

        if($npwp == "" || $npwp == null){
            $npwp = "-";
        }

        if($no_telp == "" || $no_telp == null){
            $no_telp = "-";
        }

        if($no_hp == "" || $no_hp == null){
            $no_hp = "-";
        }

        if($email == "" || $email == null){
            $email = "-";
        }



        $tgl = date('d-m-Y');
        $jam = date('H:i');
        $waktu = $tgl.", ".$jam;

        $sql = "
        INSERT INTO ak_supplier
        (ID_KLIEN, NAMA_SUPPLIER, NPWP, ALAMAT_TAGIH, NO_TELP, NO_HP, EMAIL, WAKTU, WAKTU_EDIT, TIPE, NAMA_USAHA, TDP, SIUP)
        VALUES 
        ($id_klien, '$nama_supplier', '$npwp', '$alamat_tagih', '$no_telp', '$no_hp', '$email', '$waktu', '-', '$tipe', '$nama_usaha', '$tdp', '$siup')
        ";

        $this->db->query($sql);
    }

    function edit_supplier($id_supplier, $nama_supplier_ed, $npwp_ed, $alamat_tagih_ed, $no_telp_ed, $no_hp_ed, $email_ed, $tipe_ed, $nama_usaha_ed, $tdp_ed, $siup_ed){

        if($npwp_ed == "" || $npwp_ed == null){
            $npwp_ed = "-";
        }

        if($no_telp_ed == "" || $no_telp_ed == null){
            $no_telp_ed = "-";
        }

        if($no_hp_ed == "" || $no_hp_ed == null){
            $no_hp_ed = "-";
        }

        if($email_ed == "" || $email_ed == null){
            $email_ed = "-";
        }

        $tgl = date('d-m-Y');
        $jam = date('H:i');
        $waktu = $tgl.", ".$jam;

        $sql = "
        UPDATE ak_supplier SET 
        NAMA_SUPPLIER = '$nama_supplier_ed', NPWP = '$npwp_ed', ALAMAT_TAGIH = '$alamat_tagih_ed', NO_TELP = '$no_telp_ed',
        NO_HP = '$no_hp_ed', EMAIL = '$email_ed', WAKTU_EDIT = '$waktu', TIPE = '$tipe_ed', NAMA_USAHA = '$nama_usaha_ed', TDP = '$tdp_ed', SIUP = '$siup_ed'
        WHERE ID = $id_supplier
        ";

        $this->db->query($sql);
    }

}

?>
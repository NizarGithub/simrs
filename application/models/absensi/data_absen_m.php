<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data_absen_m extends CI_Model { 

    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    function cek_kode_dep($kode_dep){
        $sql = "
        SELECT * FROM kepeg_departemen WHERE KODE = '$kode_dep' AND STS = 0
        ";

        return $this->db->query($sql)->result();
    }

    function simpan_alasan($id_edit, $ed_alasan, $ed_ket_alasan, $ed_denda){

        $ed_alasan      = addslashes($ed_alasan);
        $ed_ket_alasan  = addslashes($ed_ket_alasan);
        $ed_denda       = str_replace(',', '', $ed_denda);

        $sql = "
         UPDATE abs_absensi SET 
         STS = 1, KET_STS = '$ed_alasan', KET_ALASAN = '$ed_ket_alasan', DENDA_RP = $ed_denda
         WHERE ID = $id_edit
        ";

        $this->db->query($sql);
    }

    function get_data_absensi($bln, $thn){
        $sql = "
        SELECT MIN(ID) AS ID, NIP, NAMA_PEGAWAI, TANGGAL, JAM, BULAN, TAHUN, DENDA_RP, STS, KET_STS, KET_ALASAN FROM abs_absensi 
        WHERE BULAN = $bln AND TAHUN = $thn AND TANGGAL NOT IN 
        (SELECT TANGGAL FROM abs_setup_libur WHERE BULAN = $bln AND TAHUN = $thn)
        GROUP BY NIP, NAMA_PEGAWAI, TANGGAL, BULAN, TAHUN
        ORDER BY TANGGAL, ID
        ";

        return $this->db->query($sql)->result();
    }

    function get_jam_masuk(){
        $sql = "
        SELECT * FROM abs_jam_denda WHERE STATUS = 'MASUK'
        ";

        return $this->db->query($sql)->row();
    }

    function get_jam_denda_only(){
        $sql = "
        SELECT * FROM abs_jam_denda 
        WHERE STATUS = 'DENDA'
        ORDER BY ID
        ";

        return $this->db->query($sql)->result();
    }


}
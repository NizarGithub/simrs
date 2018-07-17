<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profil_perusahaan_m extends CI_Model
{
	function __construct() {
		  parent::__construct();
		  $this->load->database();
	}

    function get_data_profil_usaha($id_klien){

        $sql = "
        SELECT * FROM ak_profil_usaha WHERE ID_KLIEN = $id_klien
        ";

        return $this->db->query($sql)->row();
    }

    function simpam_profil($id_klien, $nama_perusahaan, $alamat_perusahaan, $telepon, $fax, $npwp, $website, $email, $nama_bank, 
                            $cabang_bank, $no_akun_bank, $atas_nama){

        $sql = "
        UPDATE ak_profil_usaha SET 
            NAMA_PERUSAHAAN = '$nama_perusahaan', ALAMAT = '$alamat_perusahaan', TELEPON = '$telepon', FAX = '$fax', NPWP = '$npwp', WEBSITE = '$website',
            EMAIL = '$email', NAMA_BANK = '$nama_bank', CABANG_BANK = '$cabang_bank', NO_AKUN_BANK = '$no_akun_bank', ATAS_NAMA = '$atas_nama', NAMA_LAPORAN = '$nama_perusahaan'
        WHERE ID_KLIEN = $id_klien
        ";

        $this->db->query($sql);
    }


}

?>
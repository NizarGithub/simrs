<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setup_hari_libur_m extends CI_Model {

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

	function simpan_tgl_libur($tgl_libur, $bln_pecah, $thn_pecah, $ket){

		$ket = addslashes($ket);
        
        $sql_hapus = "
        DELETE FROM abs_setup_libur WHERE TANGGAL = '$tgl_libur'
        ";
        $this->db->query($sql_hapus);

        $sql = "
        INSERT INTO abs_setup_libur
        (TANGGAL, BULAN, TAHUN, KET)
        VALUES
        ('$tgl_libur', $bln_pecah, $thn_pecah, '$ket')
        ";

        $this->db->query($sql);
    }

    function get_data_libur($thn, $bln){
        $sql = "
        SELECT * FROM abs_setup_libur WHERE TAHUN = $thn AND BULAN = $bln
        ORDER BY TAHUN DESC, STR_TO_DATE(`TANGGAL`, '%d-%c-%Y') ASC
        ";

        return $this->db->query($sql)->result();
    }

    function ubah_libur($id_libur, $ed_ket){

    	$ed_ket = addslashes($ed_ket);
    	
    	$sql = "
    	UPDATE abs_setup_libur SET KET = '$ed_ket'
    	WHERE ID = $id_libur
    	";

    	$this->db->query($sql);
    }

    function hapus_libur($id_hapus){
    	$sql = "
    	DELETE FROM abs_setup_libur WHERE ID = $id_hapus
    	";

    	$this->db->query($sql);
    }


}
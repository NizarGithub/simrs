<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setup_jam_denda_m extends CI_Model {

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

	function get_jam_denda(){
        $sql = "
        SELECT * FROM abs_jam_denda ORDER BY ID
        ";

        return $this->db->query($sql)->result();
    }

    function get_jam_masuk(){
        $sql = "
        SELECT * FROM abs_jam_denda WHERE STATUS = 'MASUK'
        ";

        return $this->db->query($sql)->row();
    }

    function delete_all_denda(){
        $sql = "
        DELETE FROM abs_jam_denda WHERE ID != 'asd'
        ";

        $this->db->query($sql);
    }

    function simpan_jam_masuk($jam_masuk){
        $sql = "
            INSERT INTO abs_jam_denda
            (JAM, STATUS)
            VALUES 
            ('$jam_masuk', 'MASUK')
        ";
        $this->db->query($sql);
    }

    function simpan_denda_telat($jam_awal, $jam_akhir, $denda){
        $denda = str_replace(',', '', $denda);

        $sql = "
            INSERT INTO abs_jam_denda
            (JAM, JAM2, STATUS, DENDA)
            VALUES 
            ('$jam_awal', '$jam_akhir', 'DENDA', $denda)
        ";
        $this->db->query($sql);
    }




}
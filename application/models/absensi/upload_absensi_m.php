<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Upload_absensi_m extends CI_Model {
 
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

    function delete_all_absensi($bln, $thn){
        $sql = "
            DELETE FROM abs_absensi WHERE BULAN = $bln AND TAHUN = $thn
        ";

        $this->db->query($sql);
    }

    function get_nik_pegawai(){
        $sql = "
        SELECT a.NIP, a.NAMA
        FROM kepeg_pegawai a 
        ORDER BY a.ID ASC
        ";

        return $this->db->query($sql)->result();
    }

    function simpan_default_tgl($nik, $nama, $tgl, $bln, $thn, $denda){
        if($denda == null || $denda == ""){
            $denda = 0;
        }
        $sql = "
            INSERT INTO abs_absensi
            (NIP, NAMA_PEGAWAI, TANGGAL, JAM, BULAN, TAHUN, STS, KET_STS, KET_ALASAN, DENDA_RP)
            VALUES
            ('$nik', '$nama', '$tgl', '-', $bln, $thn, 5, 'Tidak Absen', 'Tidak Absen', $denda)
        ";

        $this->db->query($sql);
    }

    function simpan_absensi($dataarray, $bln, $thn)
    {
        for($i=0;$i<count($dataarray);$i++){

                $NIK = $dataarray[$i]['nik'];
                $TANGGAL = $dataarray[$i]['tanggal'];
                $JAM = $dataarray[$i]['jam'];

            $sql = "
            UPDATE abs_absensi SET 
                JAM = '$JAM',
                STS = 0,
                DENDA_RP = 0
            WHERE NIP = '$NIK' AND TANGGAL = '$TANGGAL'
            ";

            $this->db->query($sql);
        }
    }





}
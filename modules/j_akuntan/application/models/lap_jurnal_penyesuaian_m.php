<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lap_jurnal_penyesuaian_m extends CI_Model
{
	function __construct() {
		  parent::__construct();
		  $this->load->database();
	}

	function get_list_akun_all($id_klien){
        $sql = "
        SELECT * FROM ak_kode_akuntansi WHERE ID_KLIEN = $id_klien
        ORDER BY KODE_AKUN
        ";

        return $this->db->query($sql)->result();
    }

    function get_lap_jurnal_umum($id_klien, $tgl_awal, $tgl_akhir){
        $sql = "      
        SELECT a.NO_VOUCHER, a.NO_BUKTI, a.URAIAN, a.TGL, b.KODE_AKUN, b.DEBET, b.KREDIT, c.NAMA_AKUN FROM ak_jurnal_penye a 
        JOIN ak_jurnal_penye_detail b ON a.ID_KLIEN = b.ID_KLIEN AND a.NO_BUKTI = b.NO_BUKTI
        JOIN ak_kode_akuntansi c ON b.KODE_AKUN = c.KODE_AKUN AND c.ID_KLIEN = a.ID_KLIEN
        WHERE a.ID_KLIEN = $id_klien AND STR_TO_DATE(a.TGL, '%d-%c-%Y') <= STR_TO_DATE('$tgl_akhir' , '%d-%c-%Y') AND STR_TO_DATE(a.TGL, '%d-%c-%Y') >= STR_TO_DATE('$tgl_awal' , '%d-%c-%Y')
        ORDER BY a.NO_BUKTI
        ";

        return $this->db->query($sql)->result();
    }

    function get_lap_jurnal_penyesuaian_bulanan($id_klien, $bulan, $tahun){
        $sql = "      
        SELECT a.NO_VOUCHER, a.NO_BUKTI, a.URAIAN, a.TGL, b.KODE_AKUN, b.DEBET, b.KREDIT, c.NAMA_AKUN FROM ak_jurnal_penye a 
        JOIN ak_jurnal_penye_detail b ON a.ID_KLIEN = b.ID_KLIEN AND a.NO_BUKTI = b.NO_BUKTI
        JOIN ak_kode_akuntansi c ON b.KODE_AKUN = c.KODE_AKUN AND c.ID_KLIEN = a.ID_KLIEN
        WHERE a.ID_KLIEN = $id_klien AND a.TGL LIKE '%-$bulan-$tahun%'
        ORDER BY a.NO_BUKTI
        ";

        return $this->db->query($sql)->result();
    }
}

?>
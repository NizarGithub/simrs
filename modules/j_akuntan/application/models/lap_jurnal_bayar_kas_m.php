<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lap_jurnal_bayar_kas_m extends CI_Model
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

    function cetak_kas_bank_harian($id_klien, $tgl_awal, $tgl_akhir){
        $sql = "
        SELECT a.*, b.TGL AS TGL_VOUCHER, b.DEBET AS DEBET_VOUCHER FROM (
            SELECT a.ID_KLIEN, a.NO_VOUCHER, a.CEK_GIRO, MAX(a.KODE_AKUN) AS KODE_AKUN, a.TGL_CEK, a.URAIAN, MAX(a.DEBET) AS DEBET, MAX(a.KREDIT) AS KREDIT, MAX(a.SISA_HUTANG) AS SISA_HUTANG FROM (
                SELECT ID_KLIEN, NO_VOUCHER, CEK_GIRO, '' AS KODE_AKUN, TGL_CEK, URAIAN, 0 AS DEBET, KREDIT, 0 AS SISA_HUTANG
                FROM ak_jurnal_kas_bank a 
                WHERE ID_KLIEN = $id_klien AND KREDIT > 0

                UNION ALL 

                SELECT ID_KLIEN, NO_VOUCHER, CEK_GIRO, KODE_AKUN, TGL_CEK, URAIAN, DEBET, 0 AS KREDIT, SISA_HUTANG
                FROM ak_jurnal_kas_bank a 
                WHERE ID_KLIEN = $id_klien AND DEBET > 0
            ) a
            GROUP BY a.ID_KLIEN, a.NO_VOUCHER, a.CEK_GIRO, a.TGL_CEK, a.URAIAN
        ) a JOIN ak_input_voucher b ON a.ID_KLIEN = b.ID_KLIEN AND a.NO_VOUCHER = b.NO_VOUCHER
        WHERE STR_TO_DATE(a.TGL_CEK, '%d-%c-%Y') <= STR_TO_DATE('$tgl_akhir' , '%d-%c-%Y') AND STR_TO_DATE(a.TGL_CEK, '%d-%c-%Y') >= STR_TO_DATE('$tgl_awal' , '%d-%c-%Y')
        ORDER BY a.TGL_CEK, b.TGL
        ";

        return $this->db->query($sql)->result();
    }

    function cetak_kas_bank_bulanan($id_klien, $bulan, $tahun){
        $sql = "
        SELECT a.*, b.TGL AS TGL_VOUCHER, b.DEBET AS DEBET_VOUCHER FROM (
            SELECT a.ID_KLIEN, a.NO_VOUCHER, a.CEK_GIRO, MAX(a.KODE_AKUN) AS KODE_AKUN, a.TGL_CEK, a.URAIAN, MAX(a.DEBET) AS DEBET, MAX(a.KREDIT) AS KREDIT, MAX(a.SISA_HUTANG) AS SISA_HUTANG FROM (
                SELECT ID_KLIEN, NO_VOUCHER, CEK_GIRO, '' AS KODE_AKUN, TGL_CEK, URAIAN, 0 AS DEBET, KREDIT, 0 AS SISA_HUTANG
                FROM ak_jurnal_kas_bank a 
                WHERE ID_KLIEN = $id_klien AND KREDIT > 0

                UNION ALL 

                SELECT ID_KLIEN, NO_VOUCHER, CEK_GIRO, KODE_AKUN, TGL_CEK, URAIAN, DEBET, 0 AS KREDIT, SISA_HUTANG
                FROM ak_jurnal_kas_bank a 
                WHERE ID_KLIEN = $id_klien AND DEBET > 0
            ) a
            GROUP BY a.ID_KLIEN, a.NO_VOUCHER, a.CEK_GIRO, a.TGL_CEK, a.URAIAN
        ) a JOIN ak_input_voucher b ON a.ID_KLIEN = b.ID_KLIEN AND a.NO_VOUCHER = b.NO_VOUCHER
        WHERE a.TGL_CEK LIKE '%-$bulan-$tahun%'
        ORDER BY a.TGL_CEK, b.TGL
        ";

        return $this->db->query($sql)->result();
    }

}

?>
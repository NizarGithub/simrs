<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lap_buku_besar_m extends CI_Model
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

    function get_lap_buku_besar($id_klien, $tgl_awal, $tgl_akhir){
        $sql = "      
        SELECT a.*, b.NAMA_AKUN FROM (

            SELECT a.ID_KLIEN, a.NO_BUKTI, a.TGL, a.URAIAN, a.KONTAK, a.KODE_AKUN, 
            CASE 
                WHEN (a.DEBET - a.KREDIT) > 0 THEN
                    (a.DEBET - a.KREDIT) ELSE 0 
            END AS DEBET, 

            CASE 
                WHEN (DEBET - KREDIT) < 0 THEN
                    abs(DEBET - KREDIT) ELSE 0 
            END AS KREDIT FROM (

            SELECT ID_KLIEN, 'SALDO AWAL' AS NO_BUKTI, '-' AS TGL, 'Saldo Awal' AS URAIAN, '' AS KONTAK, KODE_AKUN, SUM(DEBET) AS DEBET, SUM(KREDIT) AS KREDIT
            FROM ak_saldo_buku_besar_vw
            WHERE STR_TO_DATE(TGL, '%d-%c-%Y') <= STR_TO_DATE('$tgl_awal' , '%d-%c-%Y')
            GROUP BY ID_KLIEN,  KODE_AKUN
            ) a

            UNION ALL

            SELECT a.ID_KLIEN, a.NO_VOUCHER_DETAIL AS NO_BUKTI, b.TGL, b.URAIAN, b.KONTAK, a.KODE_AKUN, SUM(a.DEBET) AS DEBET, SUM(a.KREDIT) AS KREDIT
            FROM ak_input_voucher_detail a
            JOIN ak_input_voucher b ON a.NO_VOUCHER_DETAIL = b.NO_VOUCHER AND a.ID_KLIEN = b.ID_KLIEN
            WHERE STR_TO_DATE(b.TGL, '%d-%c-%Y') <= STR_TO_DATE('$tgl_akhir' , '%d-%c-%Y') AND STR_TO_DATE(b.TGL, '%d-%c-%Y') >= STR_TO_DATE('$tgl_awal' , '%d-%c-%Y')
            AND b.NO_JP IS NULL 
            GROUP BY a.NO_VOUCHER_DETAIL, b.TGL, a.KODE_AKUN, b.URAIAN, b.KONTAK, a.ID_KLIEN

            UNION ALL 

            SELECT ID_KLIEN, NO_VOUCHER AS NO_BUKTI, TGL_CEK AS TGL, URAIAN, KONTAK, KODE_AKUN, SUM(DEBET) AS DEBET, SUM(KREDIT) AS KREDIT
            FROM ak_jurnal_kas_bank
            WHERE STR_TO_DATE(TGL_CEK, '%d-%c-%Y') <= STR_TO_DATE('$tgl_akhir' , '%d-%c-%Y') AND STR_TO_DATE(TGL_CEK, '%d-%c-%Y') >= STR_TO_DATE('$tgl_awal' , '%d-%c-%Y')
            GROUP BY NO_VOUCHER, TGL_CEK, KODE_AKUN, URAIAN, KONTAK, ID_KLIEN
            
            UNION ALL 

            SELECT ID_KLIEN, NO_BUKTI, TGL, DESKRIPSI AS URAIAN, '' AS KONTAK, KODE_AKUN, SUM(DEBET) AS DEBET, SUM(KREDIT) AS KREDIT
            FROM ak_penerimaan_kas_bank
            WHERE STR_TO_DATE(TGL, '%d-%c-%Y') <= STR_TO_DATE('$tgl_akhir' , '%d-%c-%Y') AND STR_TO_DATE(TGL, '%d-%c-%Y') >= STR_TO_DATE('$tgl_awal' , '%d-%c-%Y')
            GROUP BY NO_BUKTI, TGL, KODE_AKUN, DESKRIPSI, ID_KLIEN

            UNION ALL

            SELECT a.ID_KLIEN, a.NO_BUKTI AS NO_BUKTI, b.TGL, b.URAIAN, '' AS KONTAK, a.KODE_AKUN, SUM(a.DEBET) AS DEBET, SUM(a.KREDIT) AS KREDIT
            FROM ak_jurnal_penye_detail a
            JOIN ak_jurnal_penye b ON a.NO_BUKTI = b.NO_BUKTI AND a.ID_KLIEN = b.ID_KLIEN
            WHERE STR_TO_DATE(b.TGL, '%d-%c-%Y') <= STR_TO_DATE('$tgl_akhir' , '%d-%c-%Y') AND STR_TO_DATE(b.TGL, '%d-%c-%Y') >= STR_TO_DATE('$tgl_awal' , '%d-%c-%Y')
            GROUP BY a.NO_BUKTI, b.TGL, a.KODE_AKUN, b.URAIAN, a.ID_KLIEN

        ) a JOIN ak_kode_akuntansi b ON a.KODE_AKUN = b.KODE_AKUN 
        
        WHERE a.ID_KLIEN = $id_klien
        ORDER BY a.KODE_AKUN ASC, , a.DEBET DESC
        ";

        return $this->db->query($sql)->result();
    }

    function get_lap_buku_besar_bulanan($id_klien, $bulan, $tahun){
        $tgl_awal = "01-".$bulan."-".$tahun;
        $sql = "      
        SELECT a.*, b.NAMA_AKUN FROM (

            SELECT ID_KLIEN, 'SALDO AWAL' AS NO_BUKTI, '-' AS TGL, 'Saldo Awal' AS URAIAN, '' AS KONTAK, KODE_AKUN, SUM(DEBET) AS DEBET, SUM(KREDIT) AS KREDIT
            FROM ak_saldo_buku_besar_vw 
            WHERE STR_TO_DATE(TGL, '%d-%c-%Y') <= STR_TO_DATE('$tgl_awal' , '%d-%c-%Y')
            GROUP BY ID_KLIEN,  KODE_AKUN

            UNION ALL

            SELECT a.ID_KLIEN, a.NO_VOUCHER_DETAIL AS NO_BUKTI, b.TGL, b.URAIAN, b.KONTAK, a.KODE_AKUN, SUM(a.DEBET) AS DEBET, SUM(a.KREDIT) AS KREDIT
            FROM ak_input_voucher_detail a
            JOIN ak_input_voucher b ON a.NO_VOUCHER_DETAIL = b.NO_VOUCHER AND a.ID_KLIEN = b.ID_KLIEN
            WHERE b.TGL LIKE '%-$bulan-$tahun%'  AND b.NO_JP IS NULL 
            GROUP BY a.NO_VOUCHER_DETAIL, b.TGL, a.KODE_AKUN, b.URAIAN, b.KONTAK, a.ID_KLIEN

            UNION ALL 

            SELECT ID_KLIEN, NO_VOUCHER AS NO_BUKTI, TGL_CEK AS TGL, URAIAN, KONTAK, KODE_AKUN, SUM(DEBET) AS DEBET, SUM(KREDIT) AS KREDIT
            FROM ak_jurnal_kas_bank
            WHERE TGL_CEK LIKE '%-$bulan-$tahun%'   
            GROUP BY NO_VOUCHER, TGL_CEK, KODE_AKUN, URAIAN, KONTAK, ID_KLIEN
            
            UNION ALL 

            SELECT ID_KLIEN, NO_BUKTI, TGL, DESKRIPSI AS URAIAN, '' AS KONTAK, KODE_AKUN, SUM(DEBET) AS DEBET, SUM(KREDIT) AS KREDIT
            FROM ak_penerimaan_kas_bank
            WHERE TGL LIKE '%-$bulan-$tahun%'   
            GROUP BY NO_BUKTI, TGL, KODE_AKUN, DESKRIPSI, ID_KLIEN

            UNION ALL

            SELECT a.ID_KLIEN, a.NO_BUKTI AS NO_BUKTI, b.TGL, b.URAIAN, '' AS KONTAK, a.KODE_AKUN, SUM(a.DEBET) AS DEBET, SUM(a.KREDIT) AS KREDIT
            FROM ak_jurnal_penye_detail a
            JOIN ak_jurnal_penye b ON a.NO_BUKTI = b.NO_BUKTI AND a.ID_KLIEN = b.ID_KLIEN
            WHERE b.TGL LIKE '%-$bulan-$tahun%'   
            GROUP BY a.NO_BUKTI, b.TGL, a.KODE_AKUN, b.URAIAN, a.ID_KLIEN

        ) a JOIN ak_kode_akuntansi b ON a.KODE_AKUN = b.KODE_AKUN 
        
        WHERE a.ID_KLIEN = $id_klien
        ORDER BY a.KODE_AKUN ASC, a.TGL,  a.DEBET DESC
        ";

        return $this->db->query($sql)->result();
    }
    

}

?>
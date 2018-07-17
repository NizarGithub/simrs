<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lap_laba_rugi_m extends CI_Model
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

    function cetak_laba_rugi($id_klien, $tgl_awal, $tgl_akhir){
        $sql = "
        SELECT a.ID_KLIEN, a.TIPE, a.KODE_AKUN, a.NAMA_AKUN, a.KATEGORI, a.URUT, a.WARNA, (a.DEBET + a.KREDIT) AS JML FROM (
            SELECT a.ID_KLIEN, a.TIPE, a.KODE_AKUN, a.NAMA_AKUN, a.KATEGORI, c.URUT, c.WARNA, IFNULL(SUM(b.DEBET), 0) AS DEBET, IFNULL(SUM(b.KREDIT), 0) AS KREDIT FROM ak_kode_akuntansi a 
            LEFT JOIN (
                SELECT a.ID_KLIEN, a.KODE_AKUN, IFNULL(a.DEBET, 0) AS DEBET, IFNULL(a.KREDIT, 0) AS KREDIT FROM(
                    SELECT VOUCHER.ID_KLIEN, DETAIL.KODE_AKUN, SUM(DETAIL.DEBET) AS DEBET, SUM(DETAIL.KREDIT) AS KREDIT
                    FROM ak_input_voucher VOUCHER
                    JOIN ak_input_voucher_detail DETAIL ON VOUCHER.NO_VOUCHER = DETAIL.NO_VOUCHER_DETAIL AND VOUCHER.ID_KLIEN = DETAIL.ID_KLIEN
                    WHERE VOUCHER.ID_KLIEN = $id_klien
                    AND STR_TO_DATE(VOUCHER.TGL, '%d-%c-%Y') <= STR_TO_DATE('$tgl_akhir' , '%d-%c-%Y') AND STR_TO_DATE(VOUCHER.TGL, '%d-%c-%Y') >= STR_TO_DATE('$tgl_awal' , '%d-%c-%Y')
                    GROUP BY VOUCHER.ID_KLIEN, DETAIL.KODE_AKUN

                    UNION ALL 

                    SELECT a.ID_KLIEN, b.KODE_AKUN, SUM(b.DEBET) AS DEBET, SUM(b.KREDIT) AS KREDIT FROM ak_jurnal_penye a 
                    JOIN ak_jurnal_penye_detail b ON a.NO_BUKTI = b.NO_BUKTI AND a.ID_KLIEN = b.ID_KLIEN
                    WHERE a.ID_KLIEN = $id_klien
                    AND STR_TO_DATE(a.TGL, '%d-%c-%Y') <= STR_TO_DATE('$tgl_akhir' , '%d-%c-%Y') AND STR_TO_DATE(a.TGL, '%d-%c-%Y') >= STR_TO_DATE('$tgl_awal' , '%d-%c-%Y')
                    GROUP BY a.ID_KLIEN, b.KODE_AKUN
                ) a
            ) b ON a.KODE_AKUN = b.KODE_AKUN
            JOIN ak_setup_urut_labarugi c ON a.KATEGORI = c.KATEGORI
            GROUP BY a.ID_KLIEN, a.TIPE, a.KODE_AKUN, a.NAMA_AKUN, a.KATEGORI, c.URUT, c.WARNA
        ) a 
        WHERE a.ID_KLIEN = $id_klien
        ORDER BY a.URUT, a.KODE_AKUN
        ";

        return $this->db->query($sql)->result();
    }

    function cetak_laba_rugi_bulanan($id_klien, $bulan, $tahun){
        $sql = "
        SELECT a.ID_KLIEN, a.TIPE, a.KODE_AKUN, a.NAMA_AKUN, a.KATEGORI, a.URUT, a.WARNA, (a.DEBET + a.KREDIT) AS JML FROM (
            SELECT a.ID_KLIEN, a.TIPE, a.KODE_AKUN, a.NAMA_AKUN, a.KATEGORI, c.URUT, c.WARNA, IFNULL(SUM(b.DEBET), 0) AS DEBET, IFNULL(SUM(b.KREDIT), 0) AS KREDIT FROM ak_kode_akuntansi a 
            LEFT JOIN (
                SELECT a.ID_KLIEN, a.KODE_AKUN, IFNULL(a.DEBET, 0) AS DEBET, IFNULL(a.KREDIT, 0) AS KREDIT FROM(
                    SELECT VOUCHER.ID_KLIEN, DETAIL.KODE_AKUN, SUM(DETAIL.DEBET) AS DEBET, SUM(DETAIL.KREDIT) AS KREDIT
                    FROM ak_input_voucher VOUCHER
                    JOIN ak_input_voucher_detail DETAIL ON VOUCHER.NO_VOUCHER = DETAIL.NO_VOUCHER_DETAIL AND VOUCHER.ID_KLIEN = DETAIL.ID_KLIEN
                    WHERE VOUCHER.ID_KLIEN = $id_klien
                    AND VOUCHER.TGL LIKE '%-$bulan-$tahun%'
                    GROUP BY VOUCHER.ID_KLIEN, DETAIL.KODE_AKUN

                    UNION ALL 

                    SELECT a.ID_KLIEN, b.KODE_AKUN, SUM(b.DEBET) AS DEBET, SUM(b.KREDIT) AS KREDIT FROM ak_jurnal_penye a 
                    JOIN ak_jurnal_penye_detail b ON a.NO_BUKTI = b.NO_BUKTI AND a.ID_KLIEN = b.ID_KLIEN
                    WHERE a.ID_KLIEN = $id_klien AND a.TGL LIKE '%-$bulan-$tahun%'
                    GROUP BY a.ID_KLIEN, b.KODE_AKUN
                ) a
            ) b ON a.KODE_AKUN = b.KODE_AKUN
            JOIN ak_setup_urut_labarugi c ON a.KATEGORI = c.KATEGORI
            GROUP BY a.ID_KLIEN, a.TIPE, a.KODE_AKUN, a.NAMA_AKUN, a.KATEGORI, c.URUT, c.WARNA
        ) a 
        WHERE a.ID_KLIEN = $id_klien
        ORDER BY a.URUT, a.KODE_AKUN
        ";

        return $this->db->query($sql)->result();
    }

    function get_detail_laba_rugi_bulanan($kode_akun, $bulan, $tahun){
        $sess_user = $this->session->userdata('masuk_akuntansi');
        $id_klien = $sess_user['id_klien'];

        $sql = "
        SELECT a.KODE_AKUN, b.NAMA_PRODUK, SUM(b.QTY) AS QTY, b.HARGA_SATUAN, b.SATUAN FROM ak_input_voucher_detail a 
        JOIN (
          SELECT a.NO_BUKTI, a.TGL_TRX, b.NAMA_PRODUK, b.QTY, b.HARGA_SATUAN, b.SATUAN FROM ak_penjualan a
          JOIN ak_penjualan_detail b ON a.ID = b.ID_PENJUALAN AND a.ID_KLIEN = b.ID_KLIEN
          WHERE a.ID_KLIEN = $id_klien AND a.TGL_TRX LIKE '%-$bulan-$tahun%'
            
          UNION ALL
            
          SELECT a.NO_BUKTI, a.TGL_TRX, b.NAMA_PRODUK, b.QTY, b.HARGA_SATUAN, b.SATUAN FROM ak_pembelian a
          JOIN ak_pembelian_detail b ON a.ID = b.ID_PENJUALAN AND a.ID_KLIEN = b.ID_KLIEN
          WHERE a.ID_KLIEN = $id_klien AND a.TGL_TRX LIKE '%-$bulan-$tahun%'

        )b ON a.NO_BUKTI = b.NO_BUKTI

        JOIN ak_input_voucher c ON a.NO_VOUCHER_DETAIL = c.NO_VOUCHER AND a.ID_KLIEN = c.ID_KLIEN
        WHERE a.KODE_AKUN = '$kode_akun'
        GROUP BY a.KODE_AKUN, b.NAMA_PRODUK, b.HARGA_SATUAN, b.SATUAN
        ";

        return $this->db->query($sql)->result();
    }

    function get_detail_laba_rugi_harian($kode_akun, $tgl_awal, $tgl_akhir){
        $sess_user = $this->session->userdata('masuk_akuntansi');
        $id_klien = $sess_user['id_klien'];

        $sql = "
        SELECT a.KODE_AKUN, b.NAMA_PRODUK, SUM(b.QTY) AS QTY, b.HARGA_SATUAN, b.SATUAN FROM ak_input_voucher_detail a 
        JOIN (
          SELECT a.NO_BUKTI, a.TGL_TRX, b.NAMA_PRODUK, b.QTY, b.HARGA_SATUAN, b.SATUAN FROM ak_penjualan a
          JOIN ak_penjualan_detail b ON a.ID = b.ID_PENJUALAN AND a.ID_KLIEN = b.ID_KLIEN
          WHERE a.ID_KLIEN = $id_klien 
            
          UNION ALL
            
          SELECT a.NO_BUKTI, a.TGL_TRX, b.NAMA_PRODUK, b.QTY, b.HARGA_SATUAN, b.SATUAN FROM ak_pembelian a
          JOIN ak_pembelian_detail b ON a.ID = b.ID_PENJUALAN AND a.ID_KLIEN = b.ID_KLIEN
          WHERE a.ID_KLIEN = $id_klien 

        )b ON a.NO_BUKTI = b.NO_BUKTI

        JOIN ak_input_voucher c ON a.NO_VOUCHER_DETAIL = c.NO_VOUCHER AND a.ID_KLIEN = c.ID_KLIEN
        WHERE a.KODE_AKUN = '$kode_akun'
        AND STR_TO_DATE(c.TGL, '%d-%c-%Y') <= STR_TO_DATE('$tgl_akhir' , '%d-%c-%Y') AND STR_TO_DATE(c.TGL, '%d-%c-%Y') >= STR_TO_DATE('$tgl_awal' , '%d-%c-%Y')
        GROUP BY a.KODE_AKUN, b.NAMA_PRODUK, b.HARGA_SATUAN, b.SATUAN
        ";

        return $this->db->query($sql)->result();
    }
}

?>
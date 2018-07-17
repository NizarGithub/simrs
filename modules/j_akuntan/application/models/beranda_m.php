<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Beranda_m extends CI_Model
{
	function __construct() {
		  parent::__construct();
		  $this->load->database();
	}

    function penjualan_bulan_ini($id_klien){
        $bulan = date('m');
        $thn   = date('Y');

        $sql = "
        SELECT SUM(TOTAL) AS TOTAL FROM ak_penjualan WHERE ID_KLIEN = $id_klien AND TGL_TRX LIKE '%-$bulan-$thn%' AND NO_TRX_AKUN IS NOT NULL
        ";

        return $this->db->query($sql)->row();
    }

    function penjualan_grafik_harian($id_klien, $tgl_1){

        $newDate = date("d M", strtotime($tgl_1));

        $sql = "
        SELECT '$newDate' AS TGL, IFNULL(a.TOTAL, 0) AS TOTAL FROM (
            SELECT SUM(TOTAL) AS TOTAL FROM ak_penjualan WHERE ID_KLIEN = $id_klien AND TGL_TRX = '$tgl_1' AND NO_TRX_AKUN IS NOT NULL
        )a
        ";

        return $this->db->query($sql)->row();
    }

    function pembelian_bulan_ini($id_klien){
        $bulan = date('m');
        $thn   = date('Y');

        $sql = "
        SELECT SUM(TOTAL) AS TOTAL FROM ak_pembelian WHERE ID_KLIEN = $id_klien AND TGL_TRX LIKE '%-$bulan-$thn%' AND NO_TRX_AKUN IS NOT NULL
        ";

        return $this->db->query($sql)->row();
    }

    function pembelian_grafik_harian($id_klien, $tgl_1){
        $newDate = date("d M", strtotime($tgl_1));

        $sql = "
        SELECT '$newDate' AS TGL, IFNULL(a.TOTAL, 0) AS TOTAL FROM (
            SELECT SUM(TOTAL) AS TOTAL FROM ak_pembelian WHERE ID_KLIEN = $id_klien AND TGL_TRX = '$tgl_1' AND NO_TRX_AKUN IS NOT NULL
        )a
        ";

        return $this->db->query($sql)->row();
    }

    function cetak_laba_rugi_bulanan($id_klien){

        $bulan = date('m');
        $tahun   = date('Y');

        $sql = "
        SELECT (a.JML1 - a.JML2) AS JML FROM (
            SELECT SUM(a.JML1) AS JML1, SUM(a.JML2) AS JML2 FROM (
                SELECT SUM(a.JML) AS JML1, 0 AS JML2 FROM (
                    SELECT a.KODE_AKUN, SUM(a.JML) AS JML FROM (
                        SELECT a.KODE_AKUN, 
                               CASE 
                                WHEN a.TIPE = 'MINUS' THEN
                                (a.JML * -1) ELSE a.JML
                               END AS JML
                        FROM (
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
                            WHERE a.ID_KLIEN = $id_klien AND (a.KATEGORI = 'Pendapatan' OR a.KATEGORI = 'Pendapatan Lainnya')
                        ) a
                    ) a
                    GROUP BY a.KODE_AKUN
                ) a

                UNION ALL 

                SELECT 0 AS JML1, SUM(a.JML) AS JML2 FROM (
                    SELECT a.KODE_AKUN, SUM(a.JML) AS JML FROM (
                        SELECT a.KODE_AKUN, 
                               CASE 
                                WHEN a.TIPE = 'MINUS' THEN
                                (a.JML * -1) ELSE a.JML
                               END AS JML
                        FROM (
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
                            WHERE a.ID_KLIEN = $id_klien AND (a.KATEGORI = 'Harga Pokok Penjualan' OR a.KATEGORI = 'Beban' OR a.KATEGORI = 'Beban Lainnya')
                        ) a
                    ) a
                    GROUP BY a.KODE_AKUN
                ) a
            ) a
        ) a
        ";

        return $this->db->query($sql)->row();
    }

    function grafik_laba_rugi_harian($id_klien, $tgl_1){

        $newDate = date("d M", strtotime($tgl_1));

        $sql = "
        SELECT '$newDate' AS TGL, IFNULL(a.JML, 0) AS TOTAL FROM (
            SELECT (a.JML1 - a.JML2) AS JML FROM (
                SELECT SUM(a.JML1) AS JML1, SUM(a.JML2) AS JML2 FROM (
                    SELECT SUM(a.JML) AS JML1, 0 AS JML2 FROM (
                        SELECT a.KODE_AKUN, SUM(a.JML) AS JML FROM (
                            SELECT a.KODE_AKUN, 
                                   CASE 
                                    WHEN a.TIPE = 'MINUS' THEN
                                    (a.JML * -1) ELSE a.JML
                                   END AS JML
                            FROM (
                                SELECT a.ID_KLIEN, a.TIPE, a.KODE_AKUN, a.NAMA_AKUN, a.KATEGORI, a.URUT, a.WARNA, (a.DEBET + a.KREDIT) AS JML FROM (
                                    SELECT a.ID_KLIEN, a.TIPE, a.KODE_AKUN, a.NAMA_AKUN, a.KATEGORI, c.URUT, c.WARNA, IFNULL(SUM(b.DEBET), 0) AS DEBET, IFNULL(SUM(b.KREDIT), 0) AS KREDIT FROM ak_kode_akuntansi a 
                                    LEFT JOIN (
                                        SELECT a.ID_KLIEN, a.KODE_AKUN, IFNULL(a.DEBET, 0) AS DEBET, IFNULL(a.KREDIT, 0) AS KREDIT FROM(
                                            SELECT VOUCHER.ID_KLIEN, DETAIL.KODE_AKUN, SUM(DETAIL.DEBET) AS DEBET, SUM(DETAIL.KREDIT) AS KREDIT
                                            FROM ak_input_voucher VOUCHER
                                            JOIN ak_input_voucher_detail DETAIL ON VOUCHER.NO_VOUCHER = DETAIL.NO_VOUCHER_DETAIL AND VOUCHER.ID_KLIEN = DETAIL.ID_KLIEN
                                            WHERE VOUCHER.ID_KLIEN = $id_klien
                                            AND VOUCHER.TGL = '$tgl_1'
                                            GROUP BY VOUCHER.ID_KLIEN, DETAIL.KODE_AKUN

                                            UNION ALL 

                                            SELECT a.ID_KLIEN, b.KODE_AKUN, SUM(b.DEBET) AS DEBET, SUM(b.KREDIT) AS KREDIT FROM ak_jurnal_penye a 
                                            JOIN ak_jurnal_penye_detail b ON a.NO_BUKTI = b.NO_BUKTI AND a.ID_KLIEN = b.ID_KLIEN
                                            WHERE a.ID_KLIEN = $id_klien AND a.TGL = '$tgl_1'
                                            GROUP BY a.ID_KLIEN, b.KODE_AKUN
                                        ) a
                                    ) b ON a.KODE_AKUN = b.KODE_AKUN
                                    JOIN ak_setup_urut_labarugi c ON a.KATEGORI = c.KATEGORI
                                    GROUP BY a.ID_KLIEN, a.TIPE, a.KODE_AKUN, a.NAMA_AKUN, a.KATEGORI, c.URUT, c.WARNA
                                ) a 
                                WHERE a.ID_KLIEN = $id_klien AND (a.KATEGORI = 'Pendapatan' OR a.KATEGORI = 'Pendapatan Lainnya')
                            ) a
                        ) a
                        GROUP BY a.KODE_AKUN
                    ) a

                    UNION ALL 

                    SELECT 0 AS JML1, SUM(a.JML) AS JML2 FROM (
                        SELECT a.KODE_AKUN, SUM(a.JML) AS JML FROM (
                            SELECT a.KODE_AKUN, 
                                   CASE 
                                    WHEN a.TIPE = 'MINUS' THEN
                                    (a.JML * -1) ELSE a.JML
                                   END AS JML
                            FROM (
                                SELECT a.ID_KLIEN, a.TIPE, a.KODE_AKUN, a.NAMA_AKUN, a.KATEGORI, a.URUT, a.WARNA, (a.DEBET + a.KREDIT) AS JML FROM (
                                    SELECT a.ID_KLIEN, a.TIPE, a.KODE_AKUN, a.NAMA_AKUN, a.KATEGORI, c.URUT, c.WARNA, IFNULL(SUM(b.DEBET), 0) AS DEBET, IFNULL(SUM(b.KREDIT), 0) AS KREDIT FROM ak_kode_akuntansi a 
                                    LEFT JOIN (
                                        SELECT a.ID_KLIEN, a.KODE_AKUN, IFNULL(a.DEBET, 0) AS DEBET, IFNULL(a.KREDIT, 0) AS KREDIT FROM(
                                            SELECT VOUCHER.ID_KLIEN, DETAIL.KODE_AKUN, SUM(DETAIL.DEBET) AS DEBET, SUM(DETAIL.KREDIT) AS KREDIT
                                            FROM ak_input_voucher VOUCHER
                                            JOIN ak_input_voucher_detail DETAIL ON VOUCHER.NO_VOUCHER = DETAIL.NO_VOUCHER_DETAIL AND VOUCHER.ID_KLIEN = DETAIL.ID_KLIEN
                                            WHERE VOUCHER.ID_KLIEN = $id_klien
                                            AND VOUCHER.TGL = '$tgl_1'
                                            GROUP BY VOUCHER.ID_KLIEN, DETAIL.KODE_AKUN

                                            UNION ALL 

                                            SELECT a.ID_KLIEN, b.KODE_AKUN, SUM(b.DEBET) AS DEBET, SUM(b.KREDIT) AS KREDIT FROM ak_jurnal_penye a 
                                            JOIN ak_jurnal_penye_detail b ON a.NO_BUKTI = b.NO_BUKTI AND a.ID_KLIEN = b.ID_KLIEN
                                            WHERE a.ID_KLIEN = $id_klien AND a.TGL = '$tgl_1'
                                            GROUP BY a.ID_KLIEN, b.KODE_AKUN
                                        ) a
                                    ) b ON a.KODE_AKUN = b.KODE_AKUN
                                    JOIN ak_setup_urut_labarugi c ON a.KATEGORI = c.KATEGORI
                                    GROUP BY a.ID_KLIEN, a.TIPE, a.KODE_AKUN, a.NAMA_AKUN, a.KATEGORI, c.URUT, c.WARNA
                                ) a 
                                WHERE a.ID_KLIEN = $id_klien AND (a.KATEGORI = 'Harga Pokok Penjualan' OR a.KATEGORI = 'Beban' OR a.KATEGORI = 'Beban Lainnya')
                            ) a
                        ) a
                        GROUP BY a.KODE_AKUN
                    ) a
                ) a
            ) a
        ) a
        ";

        return $this->db->query($sql)->row();
    }

    function grafik_laba_rugi_bulanan($id_klien, $tgl_1){

        $tgl_ori = "01-".$tgl_1;
        
        $newDate = date("M y", strtotime($tgl_ori));

        $sql = "
        SELECT '$newDate' AS TGL, IFNULL(a.JML, 0) AS TOTAL FROM (
            SELECT (a.JML1 - a.JML2) AS JML FROM (
                SELECT SUM(a.JML1) AS JML1, SUM(a.JML2) AS JML2 FROM (
                    SELECT SUM(a.JML) AS JML1, 0 AS JML2 FROM (
                        SELECT a.KODE_AKUN, SUM(a.JML) AS JML FROM (
                            SELECT a.KODE_AKUN, 
                                   CASE 
                                    WHEN a.TIPE = 'MINUS' THEN
                                    (a.JML * -1) ELSE a.JML
                                   END AS JML
                            FROM (
                                SELECT a.ID_KLIEN, a.TIPE, a.KODE_AKUN, a.NAMA_AKUN, a.KATEGORI, a.URUT, a.WARNA, (a.DEBET + a.KREDIT) AS JML FROM (
                                    SELECT a.ID_KLIEN, a.TIPE, a.KODE_AKUN, a.NAMA_AKUN, a.KATEGORI, c.URUT, c.WARNA, IFNULL(SUM(b.DEBET), 0) AS DEBET, IFNULL(SUM(b.KREDIT), 0) AS KREDIT FROM ak_kode_akuntansi a 
                                    LEFT JOIN (
                                        SELECT a.ID_KLIEN, a.KODE_AKUN, IFNULL(a.DEBET, 0) AS DEBET, IFNULL(a.KREDIT, 0) AS KREDIT FROM(
                                            SELECT VOUCHER.ID_KLIEN, DETAIL.KODE_AKUN, SUM(DETAIL.DEBET) AS DEBET, SUM(DETAIL.KREDIT) AS KREDIT
                                            FROM ak_input_voucher VOUCHER
                                            JOIN ak_input_voucher_detail DETAIL ON VOUCHER.NO_VOUCHER = DETAIL.NO_VOUCHER_DETAIL AND VOUCHER.ID_KLIEN = DETAIL.ID_KLIEN
                                            WHERE VOUCHER.ID_KLIEN = $id_klien
                                            AND VOUCHER.TGL LIKE '%-$tgl_1%'
                                            GROUP BY VOUCHER.ID_KLIEN, DETAIL.KODE_AKUN

                                            UNION ALL 

                                            SELECT a.ID_KLIEN, b.KODE_AKUN, SUM(b.DEBET) AS DEBET, SUM(b.KREDIT) AS KREDIT FROM ak_jurnal_penye a 
                                            JOIN ak_jurnal_penye_detail b ON a.NO_BUKTI = b.NO_BUKTI AND a.ID_KLIEN = b.ID_KLIEN
                                            WHERE a.ID_KLIEN = $id_klien AND a.TGL LIKE '%-$tgl_1%'
                                            GROUP BY a.ID_KLIEN, b.KODE_AKUN
                                        ) a
                                    ) b ON a.KODE_AKUN = b.KODE_AKUN
                                    JOIN ak_setup_urut_labarugi c ON a.KATEGORI = c.KATEGORI
                                    GROUP BY a.ID_KLIEN, a.TIPE, a.KODE_AKUN, a.NAMA_AKUN, a.KATEGORI, c.URUT, c.WARNA
                                ) a 
                                WHERE a.ID_KLIEN = $id_klien AND (a.KATEGORI = 'Pendapatan' OR a.KATEGORI = 'Pendapatan Lainnya')
                            ) a
                        ) a
                        GROUP BY a.KODE_AKUN
                    ) a

                    UNION ALL 

                    SELECT 0 AS JML1, SUM(a.JML) AS JML2 FROM (
                        SELECT a.KODE_AKUN, SUM(a.JML) AS JML FROM (
                            SELECT a.KODE_AKUN, 
                                   CASE 
                                    WHEN a.TIPE = 'MINUS' THEN
                                    (a.JML * -1) ELSE a.JML
                                   END AS JML
                            FROM (
                                SELECT a.ID_KLIEN, a.TIPE, a.KODE_AKUN, a.NAMA_AKUN, a.KATEGORI, a.URUT, a.WARNA, (a.DEBET + a.KREDIT) AS JML FROM (
                                    SELECT a.ID_KLIEN, a.TIPE, a.KODE_AKUN, a.NAMA_AKUN, a.KATEGORI, c.URUT, c.WARNA, IFNULL(SUM(b.DEBET), 0) AS DEBET, IFNULL(SUM(b.KREDIT), 0) AS KREDIT FROM ak_kode_akuntansi a 
                                    LEFT JOIN (
                                        SELECT a.ID_KLIEN, a.KODE_AKUN, IFNULL(a.DEBET, 0) AS DEBET, IFNULL(a.KREDIT, 0) AS KREDIT FROM(
                                            SELECT VOUCHER.ID_KLIEN, DETAIL.KODE_AKUN, SUM(DETAIL.DEBET) AS DEBET, SUM(DETAIL.KREDIT) AS KREDIT
                                            FROM ak_input_voucher VOUCHER
                                            JOIN ak_input_voucher_detail DETAIL ON VOUCHER.NO_VOUCHER = DETAIL.NO_VOUCHER_DETAIL AND VOUCHER.ID_KLIEN = DETAIL.ID_KLIEN
                                            WHERE VOUCHER.ID_KLIEN = $id_klien
                                            AND VOUCHER.TGL LIKE '%-$tgl_1%'
                                            GROUP BY VOUCHER.ID_KLIEN, DETAIL.KODE_AKUN

                                            UNION ALL 

                                            SELECT a.ID_KLIEN, b.KODE_AKUN, SUM(b.DEBET) AS DEBET, SUM(b.KREDIT) AS KREDIT FROM ak_jurnal_penye a 
                                            JOIN ak_jurnal_penye_detail b ON a.NO_BUKTI = b.NO_BUKTI AND a.ID_KLIEN = b.ID_KLIEN
                                            WHERE a.ID_KLIEN = $id_klien AND a.TGL LIKE '%-$tgl_1%'
                                            GROUP BY a.ID_KLIEN, b.KODE_AKUN
                                        ) a
                                    ) b ON a.KODE_AKUN = b.KODE_AKUN
                                    JOIN ak_setup_urut_labarugi c ON a.KATEGORI = c.KATEGORI
                                    GROUP BY a.ID_KLIEN, a.TIPE, a.KODE_AKUN, a.NAMA_AKUN, a.KATEGORI, c.URUT, c.WARNA
                                ) a 
                                WHERE a.ID_KLIEN = $id_klien AND (a.KATEGORI = 'Harga Pokok Penjualan' OR a.KATEGORI = 'Beban' OR a.KATEGORI = 'Beban Lainnya')
                            ) a
                        ) a
                        GROUP BY a.KODE_AKUN
                    ) a
                ) a
            ) a
        ) a
        ";

        return $this->db->query($sql)->row();
    }

}

?>
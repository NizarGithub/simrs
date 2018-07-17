<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lap_neraca_m extends CI_Model
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

    function get_lap_neraca_bulanan($id_klien, $bulan, $tahun, $jenis){

        $tgl_awal = "01-".$bulan."-".$tahun;

        $bulan_lalu = $bulan - 1;
        $tahun_lalu = $tahun;

        if($bulan_lalu == 0){
            $bulan_lalu = 12;
            $tahun_lalu = $tahun_lalu - 1;
        }

        $bulan_lalu = str_pad($bulan_lalu, 2, '0', STR_PAD_LEFT);

        if($jenis == "AKTIVA"){
            $n = "-";
        } else {
            $n = "-";
        }

        $tgl_awal_lalu = "01-".$bulan_lalu."-".$tahun_lalu;

        $sql = "
        SELECT a.* FROM (
        SELECT SETUP.URUT, KOPER.KODE_AKUN, KOPER.NAMA_AKUN, SETUP.KELOMPOK, SETUP.JUDUL, SETUP.NAMA_KATEGORI, SUM(IFNULL(NERACA.SALDO, 0)) AS SALDO, SUM(IFNULL(NERACA_LALU.SALDO, 0)) AS SALDO_LALU FROM ak_kode_akuntansi KOPER
        JOIN ak_setup_neraca SETUP ON KOPER.KATEGORI = SETUP.NAMA_KATEGORI
        LEFT JOIN (
            SELECT a.KODE_AKUN, SUM(a.SALDO) AS SALDO FROM (



                SELECT KODE_AKUN, (SUM(DEBET) $n SUM(KREDIT)) AS SALDO FROM ak_jurnal_umum_vw
                WHERE ID_KLIEN = $id_klien AND TGL LIKE '%-$bulan-$tahun%'
                GROUP BY KODE_AKUN

                UNION ALL 

                SELECT KODE_AKUN, (SUM(DEBET) $n SUM(KREDIT)) AS SALDO FROM ak_jurnal_penyesuaian_vw
                WHERE ID_KLIEN = $id_klien AND TGL LIKE '%-$bulan-$tahun%'
                GROUP BY KODE_AKUN

                UNION ALL 

                SELECT KODE_AKUN, (SUM(DEBET) $n SUM(KREDIT)) AS SALDO FROM ak_jurnal_kas_bank
                WHERE ID_KLIEN = $id_klien AND TGL_CEK LIKE '%-$bulan-$tahun%'
                GROUP BY KODE_AKUN

                UNION ALL 

                SELECT KODE_AKUN, (SUM(DEBET) $n SUM(KREDIT)) AS SALDO FROM ak_penerimaan_kas_bank
                WHERE ID_KLIEN = $id_klien AND TGL LIKE '%-$bulan-$tahun%'   
                GROUP BY KODE_AKUN

            ) a
        GROUP BY a.KODE_AKUN
        ) NERACA ON KOPER.KODE_AKUN = NERACA.KODE_AKUN

        LEFT JOIN (
            SELECT a.KODE_AKUN, SUM(a.SALDO) AS SALDO FROM (


                SELECT KODE_AKUN, (SUM(DEBET) $n SUM(KREDIT)) AS SALDO FROM ak_jurnal_umum_vw
                WHERE ID_KLIEN = $id_klien AND TGL LIKE '%-$bulan_lalu-$tahun_lalu%'
                GROUP BY KODE_AKUN

                UNION ALL 

                SELECT KODE_AKUN, (SUM(DEBET) $n SUM(KREDIT)) AS SALDO FROM ak_jurnal_penyesuaian_vw
                WHERE ID_KLIEN = $id_klien AND TGL LIKE '%-$bulan_lalu-$tahun_lalu%'
                GROUP BY KODE_AKUN

                UNION ALL 

                SELECT KODE_AKUN, (SUM(DEBET) $n SUM(KREDIT)) AS SALDO FROM ak_jurnal_kas_bank
                WHERE ID_KLIEN = $id_klien AND TGL_CEK LIKE '%-$bulan_lalu-$tahun_lalu%'
                GROUP BY KODE_AKUN

                UNION ALL 

                SELECT KODE_AKUN, (SUM(DEBET) $n SUM(KREDIT)) AS SALDO FROM ak_penerimaan_kas_bank
                WHERE ID_KLIEN = $id_klien AND TGL LIKE '%-$bulan_lalu-$tahun_lalu%'   
                GROUP BY KODE_AKUN

            ) a
        GROUP BY a.KODE_AKUN
        ) NERACA_LALU ON KOPER.KODE_AKUN = NERACA_LALU.KODE_AKUN

        WHERE KOPER.ID_KLIEN = $id_klien
        GROUP BY SETUP.URUT, KOPER.KODE_AKUN, KOPER.NAMA_AKUN, SETUP.KELOMPOK, SETUP.JUDUL, SETUP.NAMA_KATEGORI
        ) a
        WHERE a.KELOMPOK = '$jenis'
        ORDER BY a.URUT, a.KODE_AKUN
        ";

        return $this->db->query($sql)->result();
    }

    function cetak_laba_rugi_bulanan($id_klien, $bulan, $tahun, $jenis){

        if($jenis == "LALU"){
            $bulan = $bulan - 1;
            $tahun = $tahun;

            if($bulan == 0){
                $bulan = 12;
                $tahun = $tahun - 1;
            }
        }

        $bulan = str_pad($bulan, 2, '0', STR_PAD_LEFT);

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
                                        AND VOUCHER.TGL LIKE '%-$bulan-$tahun%' AND VOUCHER.NO_JP IS NULL
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
                                        AND VOUCHER.TGL LIKE '%-$bulan-$tahun%' AND VOUCHER.NO_JP IS NULL
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

    function get_lap_neraca_tahunan($id_klien, $bulan, $tahun, $jenis){

        if($jenis == "AKTIVA"){
            $n = "-";
        } else {
            $n = "-";
        }

        $tahun_lalu = $tahun-1;

        $sql = "
        SELECT a.* FROM (
        SELECT SETUP.URUT, KOPER.KODE_AKUN, KOPER.NAMA_AKUN, SETUP.KELOMPOK, SETUP.JUDUL, SETUP.NAMA_KATEGORI, SUM(IFNULL(NERACA.SALDO, 0)) AS SALDO, SUM(IFNULL(NERACA_LALU.SALDO, 0)) AS SALDO_LALU FROM ak_kode_akuntansi KOPER
        JOIN ak_setup_neraca SETUP ON KOPER.KATEGORI = SETUP.NAMA_KATEGORI
        LEFT JOIN (
            SELECT a.KODE_AKUN, SUM(a.SALDO) AS SALDO FROM (
                SELECT KODE_AKUN, (SUM(DEBET) $n SUM(KREDIT)) AS SALDO FROM ak_jurnal_umum_vw
                WHERE ID_KLIEN = $id_klien AND TGL LIKE '%-$tahun%'
                GROUP BY KODE_AKUN

                UNION ALL 

                SELECT KODE_AKUN, (SUM(DEBET) $n SUM(KREDIT)) AS SALDO FROM ak_jurnal_penyesuaian_vw
                WHERE ID_KLIEN = $id_klien AND TGL LIKE '%-$tahun%'
                GROUP BY KODE_AKUN

                UNION ALL 

                SELECT KODE_AKUN, (SUM(DEBET) $n SUM(KREDIT)) AS SALDO FROM ak_jurnal_kas_bank
                WHERE ID_KLIEN = $id_klien AND TGL_CEK LIKE '%-$tahun%'
                GROUP BY KODE_AKUN

                UNION ALL 

                SELECT KODE_AKUN, (SUM(DEBET) $n SUM(KREDIT)) AS SALDO FROM ak_penerimaan_kas_bank
                WHERE ID_KLIEN = $id_klien AND TGL LIKE '%-$tahun%'   
                GROUP BY KODE_AKUN

            ) a
        GROUP BY a.KODE_AKUN
        ) NERACA ON KOPER.KODE_AKUN = NERACA.KODE_AKUN

        LEFT JOIN (
            SELECT a.KODE_AKUN, SUM(a.SALDO) AS SALDO FROM (
                SELECT KODE_AKUN, (SUM(DEBET) $n SUM(KREDIT)) AS SALDO FROM ak_jurnal_umum_vw
                WHERE ID_KLIEN = $id_klien AND TGL LIKE '%-$tahun_lalu%'
                GROUP BY KODE_AKUN

                UNION ALL 

                SELECT KODE_AKUN, (SUM(DEBET) $n SUM(KREDIT)) AS SALDO FROM ak_jurnal_penyesuaian_vw
                WHERE ID_KLIEN = $id_klien AND TGL LIKE '%-$tahun_lalu%'
                GROUP BY KODE_AKUN

                UNION ALL 

                SELECT KODE_AKUN, (SUM(DEBET) $n SUM(KREDIT)) AS SALDO FROM ak_jurnal_kas_bank
                WHERE ID_KLIEN = $id_klien AND TGL_CEK LIKE '%-$tahun_lalu%'
                GROUP BY KODE_AKUN

                UNION ALL 

                SELECT KODE_AKUN, (SUM(DEBET) $n SUM(KREDIT)) AS SALDO FROM ak_penerimaan_kas_bank
                WHERE ID_KLIEN = $id_klien AND TGL LIKE '%-$tahun_lalu%'   
                GROUP BY KODE_AKUN

            ) a
        GROUP BY a.KODE_AKUN
        ) NERACA_LALU ON KOPER.KODE_AKUN = NERACA_LALU.KODE_AKUN

        WHERE KOPER.ID_KLIEN = $id_klien
        GROUP BY SETUP.URUT, KOPER.KODE_AKUN, KOPER.NAMA_AKUN, SETUP.KELOMPOK, SETUP.JUDUL, SETUP.NAMA_KATEGORI
        ) a
        WHERE a.KELOMPOK = '$jenis'
        ORDER BY a.URUT
        ";

        return $this->db->query($sql)->result();
    }

    function cetak_laba_rugi_tahunan($id_klien, $bulan, $tahun, $jenis){

        if($jenis == "LALU"){
            $tahun = $tahun - 1;
        }

        //$bulan = str_pad($bulan, 2, '0', STR_PAD_LEFT);

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
                                        AND VOUCHER.TGL LIKE '%-$tahun%' AND VOUCHER.NO_JP IS NULL
                                        GROUP BY VOUCHER.ID_KLIEN, DETAIL.KODE_AKUN

                                        UNION ALL 

                                        SELECT a.ID_KLIEN, b.KODE_AKUN, SUM(b.DEBET) AS DEBET, SUM(b.KREDIT) AS KREDIT FROM ak_jurnal_penye a 
                                        JOIN ak_jurnal_penye_detail b ON a.NO_BUKTI = b.NO_BUKTI AND a.ID_KLIEN = b.ID_KLIEN
                                        WHERE a.ID_KLIEN = $id_klien AND a.TGL LIKE '%-$tahun%'
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
                                        AND VOUCHER.TGL LIKE '%-$tahun%' AND VOUCHER.NO_JP IS NULL
                                        GROUP BY VOUCHER.ID_KLIEN, DETAIL.KODE_AKUN

                                        UNION ALL 

                                        SELECT a.ID_KLIEN, b.KODE_AKUN, SUM(b.DEBET) AS DEBET, SUM(b.KREDIT) AS KREDIT FROM ak_jurnal_penye a 
                                        JOIN ak_jurnal_penye_detail b ON a.NO_BUKTI = b.NO_BUKTI AND a.ID_KLIEN = b.ID_KLIEN
                                        WHERE a.ID_KLIEN = $id_klien AND a.TGL LIKE '%-$tahun%'
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
}

?>
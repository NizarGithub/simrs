<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lap_arus_kas_m extends CI_Model
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

    function cek_saldo_awal_bulan_lalu($id_klien, $bulan, $tahun){
        $sql = "
        SELECT * FROM ak_saldo_awal_arus_kas WHERE ID_KLIEN = $id_klien AND BULAN = $bulan AND TAHUN = $tahun AND TIPE = 'BULANAN'
        ";

        return $this->db->query($sql)->result();
    }

    function simpan_saldo_awal_arus_kas($id_klien, $bulan, $tahun, $nilai, $tipe){
        $sql = "
        INSERT INTO ak_saldo_awal_arus_kas 
        (ID_KLIEN, BULAN, TAHUN, NILAI, TIPE)
        VALUES 
        ($id_klien, $bulan, $tahun, $nilai, '$tipe')
        ";

        $this->db->query($sql);
    }

    function get_saldo_awal_sebelumnya($id_klien, $bulan, $tahun, $tipe){
        $sql = "
        SELECT * FROM ak_saldo_awal_arus_kas WHERE ID_KLIEN = $id_klien AND BULAN = $bulan AND TAHUN = $tahun AND TIPE = '$tipe'
        ";

        return $this->db->query($sql)->row();
    }

    function update_saldo_skrg($id_klien, $bulan, $tahun, $nilai_saldo_skrg, $tipe){
        $sql_del = "
            DELETE FROM ak_saldo_awal_arus_kas WHERE ID_KLIEN = $id_klien AND BULAN = $bulan AND TAHUN = $tahun AND TIPE = '$tipe' 
        ";
        $this->db->query($sql_del);

        $sql = "
        INSERT INTO ak_saldo_awal_arus_kas 
        (ID_KLIEN, BULAN, TAHUN, NILAI, TIPE)
        VALUES 
        ($id_klien, $bulan, $tahun, $nilai_saldo_skrg, '$tipe')
        ";

        $this->db->query($sql);
    }

    function get_sa_tahunan($id_klien, $tahun_sel){
        $sql = "
        SELECT IFNULL(a.TOTAL_SA, 0) AS TOTAL_SA FROM (
            SELECT ((a.SALDO_AWAL_1 - a.SALDO_AWAL_2) + a.SALDO_AWAL_3) AS TOTAL_SA FROM (
                SELECT IFNULL(SUM(a.JML_1), 0) AS SALDO_AWAL_1, IFNULL(SUM(a.JML_2), 0) AS SALDO_AWAL_2, IFNULL(SUM(a.JML_3), 0) AS SALDO_AWAL_3 FROM (
                    SELECT a.ID_KLIEN, a.KODE_AKUN, a.NAMA_AKUN, a.TIPE, SUM(a.JML_1) AS JML_1, SUM(a.JML_2) AS JML_2, SUM(a.JML_3) AS JML_3 FROM (
                        SELECT a.ID_KLIEN, b.KODE_AKUN, c.NAMA_AKUN, c.TIPE, 
                        CASE
                            WHEN c.TIPE = 'MINUS' THEN
                            ((b.DEBET + b.KREDIT) * -1) ELSE (b.DEBET + b.KREDIT) 
                        END AS JML_1, 0 AS JML_2, 0 AS JML_3
                        FROM ak_input_voucher a 
                        JOIN (
                            SELECT ID_KLIEN, NO_VOUCHER_DETAIL, KODE_AKUN, SUM(DEBET) AS DEBET, SUM(KREDIT) AS KREDIT FROM ak_input_voucher_detail
                            GROUP BY ID_KLIEN, NO_VOUCHER_DETAIL, KODE_AKUN
                        ) b ON a.ID_KLIEN = b.ID_KLIEN AND a.NO_VOUCHER = b.NO_VOUCHER_DETAIL
                        JOIN ak_kode_akuntansi c ON a.ID_KLIEN = c.ID_KLIEN AND b.KODE_AKUN = c.KODE_AKUN
                        WHERE (c.KATEGORI = 'Pendapatan' OR c.KATEGORI = 'Pendapatan Lainnya') AND a.TGL LIKE '%$tahun_sel%'

                        UNION ALL 

                        SELECT a.ID_KLIEN, b.KODE_AKUN, c.NAMA_AKUN, c.TIPE, 0 AS JML_1,
                        CASE
                            WHEN c.TIPE = 'MINUS' THEN
                            ((b.DEBET + b.KREDIT) * -1) ELSE (b.DEBET + b.KREDIT) 
                        END AS JML_2, 0 AS JML_3
                        FROM ak_input_voucher a 
                        JOIN (
                            SELECT ID_KLIEN, NO_VOUCHER_DETAIL, KODE_AKUN, SUM(DEBET) AS DEBET, SUM(KREDIT) AS KREDIT FROM ak_input_voucher_detail
                            GROUP BY ID_KLIEN, NO_VOUCHER_DETAIL, KODE_AKUN
                        ) b ON a.ID_KLIEN = b.ID_KLIEN AND a.NO_VOUCHER = b.NO_VOUCHER_DETAIL
                        JOIN ak_kode_akuntansi c ON a.ID_KLIEN = c.ID_KLIEN AND b.KODE_AKUN = c.KODE_AKUN
                        WHERE (c.KATEGORI = 'Beban' OR c.KATEGORI = 'Beban Lainnya' OR c.KATEGORI = 'Harga Pokok Penjualan') AND a.TGL LIKE '%$tahun_sel%'

                        UNION ALL 

                        SELECT a.ID_KLIEN, b.KODE_AKUN, c.NAMA_AKUN, c.TIPE, 0 AS JML_1, 0 AS JML_2,
                        CASE
                            WHEN c.TIPE = 'MINUS' THEN
                            ((b.DEBET + b.KREDIT) * -1) ELSE (b.DEBET + b.KREDIT) 
                        END AS JML_3
                        FROM ak_input_voucher a 
                        JOIN (
                            SELECT ID_KLIEN, NO_VOUCHER_DETAIL, KODE_AKUN, SUM(DEBET) AS DEBET, SUM(KREDIT) AS KREDIT FROM ak_input_voucher_detail
                            GROUP BY ID_KLIEN, NO_VOUCHER_DETAIL, KODE_AKUN
                        ) b ON a.ID_KLIEN = b.ID_KLIEN AND a.NO_VOUCHER = b.NO_VOUCHER_DETAIL
                        JOIN ak_kode_akuntansi c ON a.ID_KLIEN = c.ID_KLIEN AND b.KODE_AKUN = c.KODE_AKUN
                        WHERE (c.KATEGORI != 'Beban' AND c.KATEGORI != 'Beban Lainnya' AND c.KATEGORI != 'Harga Pokok Penjualan'
                        AND c.KATEGORI != 'Pendapatan' AND c.KATEGORI != 'Pendapatan Lainnya' AND c.KATEGORI != 'Kas & Bank') AND a.TGL LIKE '%$tahun_sel%'
                        

                        UNION ALL 

                        SELECT a.ID_KLIEN, a.KODE_AKUN, b.NAMA_AKUN, b.TIPE, 0 AS JML_1, 0 AS JML_2, (a.DEBET + a.KREDIT) AS JML_3 FROM ak_penerimaan_kas_bank a 
                        JOIN ak_kode_akuntansi b ON a.ID_KLIEN = b.ID_KLIEN AND a.KODE_AKUN = b.KODE_AKUN
                        WHERE a.TIPE = 'EK' AND a.TGL LIKE '%$tahun_sel%'
                        ) a
                        WHERE a.ID_KLIEN = $id_klien
                        GROUP BY a.ID_KLIEN, a.KODE_AKUN, a.NAMA_AKUN, a.TIPE
                ) a
            ) a
        ) a

        ";

        return $this->db->query($sql)->row();
    }

    function get_sa_tahun_lalu($id_klien, $bulan, $tahun){
        $sql = "
        SELECT IFNULL(a.TOTAL_SA, 0) AS TOTAL_SA FROM (
            SELECT ((a.SALDO_AWAL_1 - a.SALDO_AWAL_2) + a.SALDO_AWAL_3) AS TOTAL_SA FROM (
                SELECT IFNULL(SUM(a.JML_1), 0) AS SALDO_AWAL_1, IFNULL(SUM(a.JML_2), 0) AS SALDO_AWAL_2, IFNULL(SUM(a.JML_3), 0) AS SALDO_AWAL_3 FROM (
                    SELECT a.ID_KLIEN, a.KODE_AKUN, a.NAMA_AKUN, a.TIPE, SUM(a.JML_1) AS JML_1, SUM(a.JML_2) AS JML_2, SUM(a.JML_3) AS JML_3 FROM (
                        SELECT a.ID_KLIEN, b.KODE_AKUN, c.NAMA_AKUN, c.TIPE, 
                        CASE
                            WHEN c.TIPE = 'MINUS' THEN
                            ((b.DEBET + b.KREDIT) * -1) ELSE (b.DEBET + b.KREDIT) 
                        END AS JML_1, 0 AS JML_2, 0 AS JML_3
                        FROM ak_input_voucher a 
                        JOIN (
                            SELECT ID_KLIEN, NO_VOUCHER_DETAIL, KODE_AKUN, SUM(DEBET) AS DEBET, SUM(KREDIT) AS KREDIT FROM ak_input_voucher_detail
                            GROUP BY ID_KLIEN, NO_VOUCHER_DETAIL, KODE_AKUN
                        ) b ON a.ID_KLIEN = b.ID_KLIEN AND a.NO_VOUCHER = b.NO_VOUCHER_DETAIL
                        JOIN ak_kode_akuntansi c ON a.ID_KLIEN = c.ID_KLIEN AND b.KODE_AKUN = c.KODE_AKUN
                        WHERE (c.KATEGORI = 'Pendapatan' OR c.KATEGORI = 'Pendapatan Lainnya') AND a.TGL LIKE '%-$tahun%'

                        UNION ALL 

                        SELECT a.ID_KLIEN, b.KODE_AKUN, c.NAMA_AKUN, c.TIPE, 0 AS JML_1,
                        CASE
                            WHEN c.TIPE = 'MINUS' THEN
                            ((b.DEBET + b.KREDIT) * -1) ELSE (b.DEBET + b.KREDIT) 
                        END AS JML_2, 0 AS JML_3
                        FROM ak_input_voucher a 
                        JOIN (
                            SELECT ID_KLIEN, NO_VOUCHER_DETAIL, KODE_AKUN, SUM(DEBET) AS DEBET, SUM(KREDIT) AS KREDIT FROM ak_input_voucher_detail
                            GROUP BY ID_KLIEN, NO_VOUCHER_DETAIL, KODE_AKUN
                        ) b ON a.ID_KLIEN = b.ID_KLIEN AND a.NO_VOUCHER = b.NO_VOUCHER_DETAIL
                        JOIN ak_kode_akuntansi c ON a.ID_KLIEN = c.ID_KLIEN AND b.KODE_AKUN = c.KODE_AKUN
                        WHERE (c.KATEGORI = 'Beban' OR c.KATEGORI = 'Beban Lainnya' OR c.KATEGORI = 'Harga Pokok Penjualan') AND a.TGL LIKE '%-$tahun%'

                        UNION ALL 

                        SELECT a.ID_KLIEN, b.KODE_AKUN, c.NAMA_AKUN, c.TIPE, 0 AS JML_1, 0 AS JML_2,
                        CASE
                            WHEN c.TIPE = 'MINUS' THEN
                            ((b.DEBET + b.KREDIT) * -1) ELSE (b.DEBET + b.KREDIT) 
                        END AS JML_3
                        FROM ak_input_voucher a 
                        JOIN (
                            SELECT ID_KLIEN, NO_VOUCHER_DETAIL, KODE_AKUN, SUM(DEBET) AS DEBET, SUM(KREDIT) AS KREDIT FROM ak_input_voucher_detail
                            GROUP BY ID_KLIEN, NO_VOUCHER_DETAIL, KODE_AKUN
                        ) b ON a.ID_KLIEN = b.ID_KLIEN AND a.NO_VOUCHER = b.NO_VOUCHER_DETAIL
                        JOIN ak_kode_akuntansi c ON a.ID_KLIEN = c.ID_KLIEN AND b.KODE_AKUN = c.KODE_AKUN
                        WHERE (c.KATEGORI != 'Beban' AND c.KATEGORI != 'Beban Lainnya' AND c.KATEGORI != 'Harga Pokok Penjualan'
                        AND c.KATEGORI != 'Pendapatan' AND c.KATEGORI != 'Pendapatan Lainnya' AND c.KATEGORI != 'Kas & Bank') AND a.TGL LIKE '%-$tahun%'
                        

                        UNION ALL 

                        SELECT a.ID_KLIEN, a.KODE_AKUN, b.NAMA_AKUN, b.TIPE, 0 AS JML_1, 0 AS JML_2, (a.DEBET + a.KREDIT) AS JML_3 FROM ak_penerimaan_kas_bank a 
                        JOIN ak_kode_akuntansi b ON a.ID_KLIEN = b.ID_KLIEN AND a.KODE_AKUN = b.KODE_AKUN
                        WHERE a.TIPE = 'EK' AND a.TGL LIKE '%-$tahun%'
                        ) a
                        WHERE a.ID_KLIEN = $id_klien
                        GROUP BY a.ID_KLIEN, a.KODE_AKUN, a.NAMA_AKUN, a.TIPE
                ) a
            ) a
        ) a

        ";

        return $this->db->query($sql)->row();
    }

    function get_sa_tahun_skrg($id_klien, $bulan, $tahun){
        $sql = "
        SELECT IFNULL(a.TOTAL_SA, 0) AS TOTAL_SA FROM (
            SELECT ((a.SALDO_AWAL_1 - a.SALDO_AWAL_2) + a.SALDO_AWAL_3) AS TOTAL_SA FROM (
                SELECT IFNULL(SUM(a.JML_1), 0) AS SALDO_AWAL_1, IFNULL(SUM(a.JML_2), 0) AS SALDO_AWAL_2, IFNULL(SUM(a.JML_3), 0) AS SALDO_AWAL_3 FROM (
                    SELECT a.ID_KLIEN, a.KODE_AKUN, a.NAMA_AKUN, a.TIPE, SUM(a.JML_1) AS JML_1, SUM(a.JML_2) AS JML_2, SUM(a.JML_3) AS JML_3 FROM (
                        SELECT a.ID_KLIEN, b.KODE_AKUN, c.NAMA_AKUN, c.TIPE, 
                        CASE
                            WHEN c.TIPE = 'MINUS' THEN
                            ((b.DEBET + b.KREDIT) * -1) ELSE (b.DEBET + b.KREDIT) 
                        END AS JML_1, 0 AS JML_2, 0 AS JML_3
                        FROM ak_input_voucher a 
                        JOIN (
                            SELECT ID_KLIEN, NO_VOUCHER_DETAIL, KODE_AKUN, SUM(DEBET) AS DEBET, SUM(KREDIT) AS KREDIT FROM ak_input_voucher_detail
                            GROUP BY ID_KLIEN, NO_VOUCHER_DETAIL, KODE_AKUN
                        ) b ON a.ID_KLIEN = b.ID_KLIEN AND a.NO_VOUCHER = b.NO_VOUCHER_DETAIL
                        JOIN ak_kode_akuntansi c ON a.ID_KLIEN = c.ID_KLIEN AND b.KODE_AKUN = c.KODE_AKUN
                        WHERE (c.KATEGORI = 'Pendapatan' OR c.KATEGORI = 'Pendapatan Lainnya') AND a.TGL LIKE '%-$tahun%'

                        UNION ALL 

                        SELECT a.ID_KLIEN, b.KODE_AKUN, c.NAMA_AKUN, c.TIPE, 0 AS JML_1,
                        CASE
                            WHEN c.TIPE = 'MINUS' THEN
                            ((b.DEBET + b.KREDIT) * -1) ELSE (b.DEBET + b.KREDIT) 
                        END AS JML_2, 0 AS JML_3
                        FROM ak_input_voucher a 
                        JOIN (
                            SELECT ID_KLIEN, NO_VOUCHER_DETAIL, KODE_AKUN, SUM(DEBET) AS DEBET, SUM(KREDIT) AS KREDIT FROM ak_input_voucher_detail
                            GROUP BY ID_KLIEN, NO_VOUCHER_DETAIL, KODE_AKUN
                        ) b ON a.ID_KLIEN = b.ID_KLIEN AND a.NO_VOUCHER = b.NO_VOUCHER_DETAIL
                        JOIN ak_kode_akuntansi c ON a.ID_KLIEN = c.ID_KLIEN AND b.KODE_AKUN = c.KODE_AKUN
                        WHERE (c.KATEGORI = 'Beban' OR c.KATEGORI = 'Beban Lainnya' OR c.KATEGORI = 'Harga Pokok Penjualan') AND a.TGL LIKE '%-$tahun%'

                        UNION ALL 

                        SELECT a.ID_KLIEN, b.KODE_AKUN, c.NAMA_AKUN, c.TIPE, 0 AS JML_1, 0 AS JML_2,
                        CASE
                            WHEN c.TIPE = 'MINUS' THEN
                            ((b.DEBET + b.KREDIT) * -1) ELSE (b.DEBET + b.KREDIT) 
                        END AS JML_3
                        FROM ak_input_voucher a 
                        JOIN (
                            SELECT ID_KLIEN, NO_VOUCHER_DETAIL, KODE_AKUN, SUM(DEBET) AS DEBET, SUM(KREDIT) AS KREDIT FROM ak_input_voucher_detail
                            GROUP BY ID_KLIEN, NO_VOUCHER_DETAIL, KODE_AKUN
                        ) b ON a.ID_KLIEN = b.ID_KLIEN AND a.NO_VOUCHER = b.NO_VOUCHER_DETAIL
                        JOIN ak_kode_akuntansi c ON a.ID_KLIEN = c.ID_KLIEN AND b.KODE_AKUN = c.KODE_AKUN
                        WHERE (c.KATEGORI != 'Beban' AND c.KATEGORI != 'Beban Lainnya' AND c.KATEGORI != 'Harga Pokok Penjualan'
                        AND c.KATEGORI != 'Pendapatan' AND c.KATEGORI != 'Pendapatan Lainnya' AND c.KATEGORI != 'Kas & Bank') AND a.TGL LIKE '%-$tahun%'
                        

                        UNION ALL 

                        SELECT a.ID_KLIEN, a.KODE_AKUN, b.NAMA_AKUN, b.TIPE, 0 AS JML_1, 0 AS JML_2, (a.DEBET + a.KREDIT) AS JML_3 FROM ak_penerimaan_kas_bank a 
                        JOIN ak_kode_akuntansi b ON a.ID_KLIEN = b.ID_KLIEN AND a.KODE_AKUN = b.KODE_AKUN
                        WHERE a.TIPE = 'EK' AND a.TGL LIKE '%-$tahun%'
                        ) a
                        WHERE a.ID_KLIEN = $id_klien
                        GROUP BY a.ID_KLIEN, a.KODE_AKUN, a.NAMA_AKUN, a.TIPE
                ) a
            ) a
        ) a

        ";

        return $this->db->query($sql)->row();
    }


    function cetak_arus_kas_tahunan($id_klien, $tahun_sel){
        $sql = "
        SELECT a.ID_KLIEN, a.KODE_AKUN, a.NAMA_AKUN, a.TIPE, SUM(a.JML) AS JML, a.JENIS, a.URUT FROM (
            SELECT a.ID_KLIEN, b.KODE_AKUN, c.NAMA_AKUN, c.TIPE, (b.DEBET + b.KREDIT) AS JML, 'Pendapatan Operasional' AS JENIS, 1 AS URUT FROM ak_input_voucher a 
            JOIN (
                SELECT ID_KLIEN, NO_VOUCHER_DETAIL, KODE_AKUN, SUM(DEBET) AS DEBET, SUM(KREDIT) AS KREDIT FROM ak_input_voucher_detail
                GROUP BY ID_KLIEN, NO_VOUCHER_DETAIL, KODE_AKUN
            ) b ON a.ID_KLIEN = b.ID_KLIEN AND a.NO_VOUCHER = b.NO_VOUCHER_DETAIL
            JOIN ak_kode_akuntansi c ON a.ID_KLIEN = c.ID_KLIEN AND b.KODE_AKUN = c.KODE_AKUN
            WHERE (c.KATEGORI = 'Pendapatan' OR c.KATEGORI = 'Pendapatan Lainnya') AND a.TGL LIKE '%$tahun_sel%'

            UNION ALL 

            SELECT a.ID_KLIEN, b.KODE_AKUN, c.NAMA_AKUN, c.TIPE, (b.DEBET + b.KREDIT) AS JML, 'Biaya Operasional' AS JENIS, 2 AS URUT FROM ak_input_voucher a 
            JOIN (
                SELECT ID_KLIEN, NO_VOUCHER_DETAIL, KODE_AKUN, SUM(DEBET) AS DEBET, SUM(KREDIT) AS KREDIT FROM ak_input_voucher_detail
                GROUP BY ID_KLIEN, NO_VOUCHER_DETAIL, KODE_AKUN
            ) b ON a.ID_KLIEN = b.ID_KLIEN AND a.NO_VOUCHER = b.NO_VOUCHER_DETAIL
            JOIN ak_kode_akuntansi c ON a.ID_KLIEN = c.ID_KLIEN AND b.KODE_AKUN = c.KODE_AKUN
            WHERE (c.KATEGORI = 'Beban' OR c.KATEGORI = 'Beban Lainnya' OR c.KATEGORI = 'Harga Pokok Penjualan') AND a.TGL LIKE '%$tahun_sel%'

            UNION ALL 

            SELECT a.ID_KLIEN, b.KODE_AKUN, c.NAMA_AKUN, c.TIPE, (b.DEBET + b.KREDIT) AS JML, 'Aktivitas Non Operasional' AS JENIS, 3 AS URUT FROM ak_input_voucher a 
            JOIN (
                SELECT ID_KLIEN, NO_VOUCHER_DETAIL, KODE_AKUN, SUM(DEBET) AS DEBET, SUM(KREDIT) AS KREDIT FROM ak_input_voucher_detail
                GROUP BY ID_KLIEN, NO_VOUCHER_DETAIL, KODE_AKUN
            ) b ON a.ID_KLIEN = b.ID_KLIEN AND a.NO_VOUCHER = b.NO_VOUCHER_DETAIL
            JOIN ak_kode_akuntansi c ON a.ID_KLIEN = c.ID_KLIEN AND b.KODE_AKUN = c.KODE_AKUN
            WHERE (c.KATEGORI != 'Beban' AND c.KATEGORI != 'Beban Lainnya' AND c.KATEGORI != 'Harga Pokok Penjualan'
            AND c.KATEGORI != 'Pendapatan' AND c.KATEGORI != 'Pendapatan Lainnya' AND c.KATEGORI != 'Kas & Bank')
            AND a.TGL LIKE '%$tahun_sel%'

            UNION ALL 

            SELECT a.ID_KLIEN, a.KODE_AKUN, b.NAMA_AKUN, b.TIPE, (a.DEBET + a.KREDIT) AS JML, 'Aktivitas Non Operasional' AS JENIS, 3 AS URUT FROM ak_penerimaan_kas_bank a 
            JOIN ak_kode_akuntansi b ON a.ID_KLIEN = b.ID_KLIEN AND a.KODE_AKUN = b.KODE_AKUN
            WHERE a.TIPE = 'EK' AND a.TGL LIKE '%$tahun_sel%'
            ) a
            WHERE a.ID_KLIEN = $id_klien
            GROUP BY a.ID_KLIEN, a.KODE_AKUN, a.NAMA_AKUN, a.TIPE, a.JENIS, a.URUT
        ORDER BY a.URUT

        ";

        return $this->db->query($sql)->result();
    }

    function get_sa_bulanan_lalu($id_klien, $bulan, $tahun){
        $sql = "
        SELECT IFNULL(a.TOTAL_SA, 0) AS TOTAL_SA FROM (
            SELECT ((a.SALDO_AWAL_1 - a.SALDO_AWAL_2) + a.SALDO_AWAL_3) AS TOTAL_SA FROM (
                SELECT IFNULL(SUM(a.JML_1), 0) AS SALDO_AWAL_1, IFNULL(SUM(a.JML_2), 0) AS SALDO_AWAL_2, IFNULL(SUM(a.JML_3), 0) AS SALDO_AWAL_3 FROM (
                    SELECT a.ID_KLIEN, a.KODE_AKUN, a.NAMA_AKUN, a.TIPE, SUM(a.JML_1) AS JML_1, SUM(a.JML_2) AS JML_2, SUM(a.JML_3) AS JML_3 FROM (
                        SELECT a.ID_KLIEN, b.KODE_AKUN, c.NAMA_AKUN, c.TIPE, 
                        CASE
                            WHEN c.TIPE = 'MINUS' THEN
                            ((b.DEBET + b.KREDIT) * -1) ELSE (b.DEBET + b.KREDIT) 
                        END AS JML_1, 0 AS JML_2, 0 AS JML_3
                        FROM ak_input_voucher a 
                        JOIN (
                            SELECT ID_KLIEN, NO_VOUCHER_DETAIL, KODE_AKUN, SUM(DEBET) AS DEBET, SUM(KREDIT) AS KREDIT FROM ak_input_voucher_detail
                            GROUP BY ID_KLIEN, NO_VOUCHER_DETAIL, KODE_AKUN
                        ) b ON a.ID_KLIEN = b.ID_KLIEN AND a.NO_VOUCHER = b.NO_VOUCHER_DETAIL
                        JOIN ak_kode_akuntansi c ON a.ID_KLIEN = c.ID_KLIEN AND b.KODE_AKUN = c.KODE_AKUN
                        WHERE (c.KATEGORI = 'Pendapatan' OR c.KATEGORI = 'Pendapatan Lainnya') AND a.TGL LIKE '%-$bulan-$tahun%'

                        UNION ALL 

                        SELECT a.ID_KLIEN, b.KODE_AKUN, c.NAMA_AKUN, c.TIPE, 0 AS JML_1,
                        CASE
                            WHEN c.TIPE = 'MINUS' THEN
                            ((b.DEBET + b.KREDIT) * -1) ELSE (b.DEBET + b.KREDIT) 
                        END AS JML_2, 0 AS JML_3
                        FROM ak_input_voucher a 
                        JOIN (
                            SELECT ID_KLIEN, NO_VOUCHER_DETAIL, KODE_AKUN, SUM(DEBET) AS DEBET, SUM(KREDIT) AS KREDIT FROM ak_input_voucher_detail
                            GROUP BY ID_KLIEN, NO_VOUCHER_DETAIL, KODE_AKUN
                        ) b ON a.ID_KLIEN = b.ID_KLIEN AND a.NO_VOUCHER = b.NO_VOUCHER_DETAIL
                        JOIN ak_kode_akuntansi c ON a.ID_KLIEN = c.ID_KLIEN AND b.KODE_AKUN = c.KODE_AKUN
                        WHERE (c.KATEGORI = 'Beban' OR c.KATEGORI = 'Beban Lainnya' OR c.KATEGORI = 'Harga Pokok Penjualan') AND a.TGL LIKE '%-$bulan-$tahun%'

                        UNION ALL 

                        SELECT a.ID_KLIEN, b.KODE_AKUN, c.NAMA_AKUN, c.TIPE, 0 AS JML_1, 0 AS JML_2,
                        CASE
                            WHEN c.TIPE = 'MINUS' THEN
                            ((b.DEBET + b.KREDIT) * -1) ELSE (b.DEBET + b.KREDIT) 
                        END AS JML_3
                        FROM ak_input_voucher a 
                        JOIN (
                            SELECT ID_KLIEN, NO_VOUCHER_DETAIL, KODE_AKUN, SUM(DEBET) AS DEBET, SUM(KREDIT) AS KREDIT FROM ak_input_voucher_detail
                            GROUP BY ID_KLIEN, NO_VOUCHER_DETAIL, KODE_AKUN
                        ) b ON a.ID_KLIEN = b.ID_KLIEN AND a.NO_VOUCHER = b.NO_VOUCHER_DETAIL
                        JOIN ak_kode_akuntansi c ON a.ID_KLIEN = c.ID_KLIEN AND b.KODE_AKUN = c.KODE_AKUN
                        WHERE (c.KATEGORI != 'Beban' AND c.KATEGORI != 'Beban Lainnya' AND c.KATEGORI != 'Harga Pokok Penjualan'
                        AND c.KATEGORI != 'Pendapatan' AND c.KATEGORI != 'Pendapatan Lainnya' AND c.KATEGORI != 'Kas & Bank') AND a.TGL LIKE '%-$bulan-$tahun%'
                        

                        UNION ALL 

                        SELECT a.ID_KLIEN, a.KODE_AKUN, b.NAMA_AKUN, b.TIPE, 0 AS JML_1, 0 AS JML_2, (a.DEBET + a.KREDIT) AS JML_3 FROM ak_penerimaan_kas_bank a 
                        JOIN ak_kode_akuntansi b ON a.ID_KLIEN = b.ID_KLIEN AND a.KODE_AKUN = b.KODE_AKUN
                        WHERE a.TIPE = 'EK' AND a.TGL LIKE '%-$bulan-$tahun%'
                        ) a
                        WHERE a.ID_KLIEN = $id_klien
                        GROUP BY a.ID_KLIEN, a.KODE_AKUN, a.NAMA_AKUN, a.TIPE
                ) a
            ) a
        ) a

        ";

        return $this->db->query($sql)->row();
    }

    function get_sa_bulanan_skrg($id_klien, $bulan, $tahun){
        $sql = "
        SELECT IFNULL(a.TOTAL_SA, 0) AS TOTAL_SA FROM (
            SELECT ((a.SALDO_AWAL_1 - a.SALDO_AWAL_2) + a.SALDO_AWAL_3) AS TOTAL_SA FROM (
                SELECT IFNULL(SUM(a.JML_1), 0) AS SALDO_AWAL_1, IFNULL(SUM(a.JML_2), 0) AS SALDO_AWAL_2, IFNULL(SUM(a.JML_3), 0) AS SALDO_AWAL_3 FROM (
                    SELECT a.ID_KLIEN, a.KODE_AKUN, a.NAMA_AKUN, a.TIPE, SUM(a.JML_1) AS JML_1, SUM(a.JML_2) AS JML_2, SUM(a.JML_3) AS JML_3 FROM (
                        SELECT a.ID_KLIEN, b.KODE_AKUN, c.NAMA_AKUN, c.TIPE, 
                        CASE
                            WHEN c.TIPE = 'MINUS' THEN
                            ((b.DEBET + b.KREDIT) * -1) ELSE (b.DEBET + b.KREDIT) 
                        END AS JML_1, 0 AS JML_2, 0 AS JML_3
                        FROM ak_input_voucher a 
                        JOIN (
                            SELECT ID_KLIEN, NO_VOUCHER_DETAIL, KODE_AKUN, SUM(DEBET) AS DEBET, SUM(KREDIT) AS KREDIT FROM ak_input_voucher_detail
                            GROUP BY ID_KLIEN, NO_VOUCHER_DETAIL, KODE_AKUN
                        ) b ON a.ID_KLIEN = b.ID_KLIEN AND a.NO_VOUCHER = b.NO_VOUCHER_DETAIL
                        JOIN ak_kode_akuntansi c ON a.ID_KLIEN = c.ID_KLIEN AND b.KODE_AKUN = c.KODE_AKUN
                        WHERE (c.KATEGORI = 'Pendapatan' OR c.KATEGORI = 'Pendapatan Lainnya') AND a.TGL LIKE '%-$bulan-$tahun%'

                        UNION ALL 

                        SELECT a.ID_KLIEN, b.KODE_AKUN, c.NAMA_AKUN, c.TIPE, 0 AS JML_1,
                        CASE
                            WHEN c.TIPE = 'MINUS' THEN
                            ((b.DEBET + b.KREDIT) * -1) ELSE (b.DEBET + b.KREDIT) 
                        END AS JML_2, 0 AS JML_3
                        FROM ak_input_voucher a 
                        JOIN (
                            SELECT ID_KLIEN, NO_VOUCHER_DETAIL, KODE_AKUN, SUM(DEBET) AS DEBET, SUM(KREDIT) AS KREDIT FROM ak_input_voucher_detail
                            GROUP BY ID_KLIEN, NO_VOUCHER_DETAIL, KODE_AKUN
                        ) b ON a.ID_KLIEN = b.ID_KLIEN AND a.NO_VOUCHER = b.NO_VOUCHER_DETAIL
                        JOIN ak_kode_akuntansi c ON a.ID_KLIEN = c.ID_KLIEN AND b.KODE_AKUN = c.KODE_AKUN
                        WHERE (c.KATEGORI = 'Beban' OR c.KATEGORI = 'Beban Lainnya' OR c.KATEGORI = 'Harga Pokok Penjualan') AND a.TGL LIKE '%-$bulan-$tahun%'

                        UNION ALL 

                        SELECT a.ID_KLIEN, b.KODE_AKUN, c.NAMA_AKUN, c.TIPE, 0 AS JML_1, 0 AS JML_2,
                        CASE
                            WHEN c.TIPE = 'MINUS' THEN
                            ((b.DEBET + b.KREDIT) * -1) ELSE (b.DEBET + b.KREDIT) 
                        END AS JML_3
                        FROM ak_input_voucher a 
                        JOIN (
                            SELECT ID_KLIEN, NO_VOUCHER_DETAIL, KODE_AKUN, SUM(DEBET) AS DEBET, SUM(KREDIT) AS KREDIT FROM ak_input_voucher_detail
                            GROUP BY ID_KLIEN, NO_VOUCHER_DETAIL, KODE_AKUN
                        ) b ON a.ID_KLIEN = b.ID_KLIEN AND a.NO_VOUCHER = b.NO_VOUCHER_DETAIL
                        JOIN ak_kode_akuntansi c ON a.ID_KLIEN = c.ID_KLIEN AND b.KODE_AKUN = c.KODE_AKUN
                        WHERE (c.KATEGORI != 'Beban' AND c.KATEGORI != 'Beban Lainnya' AND c.KATEGORI != 'Harga Pokok Penjualan'
                        AND c.KATEGORI != 'Pendapatan' AND c.KATEGORI != 'Pendapatan Lainnya' AND c.KATEGORI != 'Kas & Bank') AND a.TGL LIKE '%-$bulan-$tahun%'
                        

                        UNION ALL 

                        SELECT a.ID_KLIEN, a.KODE_AKUN, b.NAMA_AKUN, b.TIPE, 0 AS JML_1, 0 AS JML_2, (a.DEBET + a.KREDIT) AS JML_3 FROM ak_penerimaan_kas_bank a 
                        JOIN ak_kode_akuntansi b ON a.ID_KLIEN = b.ID_KLIEN AND a.KODE_AKUN = b.KODE_AKUN
                        WHERE a.TIPE = 'EK' AND a.TGL LIKE '%-$bulan-$tahun%'
                        ) a
                        WHERE a.ID_KLIEN = $id_klien
                        GROUP BY a.ID_KLIEN, a.KODE_AKUN, a.NAMA_AKUN, a.TIPE
                ) a
            ) a
        ) a

        ";

        return $this->db->query($sql)->row();
    }

    function cetak_arus_kas_bulanan($id_klien, $bulan, $tahun){
        $sql = "
        SELECT a.ID_KLIEN, a.KODE_AKUN, a.NAMA_AKUN, a.TIPE, SUM(a.JML) AS JML, a.JENIS, a.URUT FROM (
            SELECT a.ID_KLIEN, b.KODE_AKUN, c.NAMA_AKUN, c.TIPE, (b.DEBET + b.KREDIT) AS JML, 'Pendapatan Operasional' AS JENIS, 1 AS URUT FROM ak_input_voucher a 
            JOIN (
                SELECT ID_KLIEN, NO_VOUCHER_DETAIL, KODE_AKUN, SUM(DEBET) AS DEBET, SUM(KREDIT) AS KREDIT FROM ak_input_voucher_detail
                GROUP BY ID_KLIEN, NO_VOUCHER_DETAIL, KODE_AKUN
            ) b ON a.ID_KLIEN = b.ID_KLIEN AND a.NO_VOUCHER = b.NO_VOUCHER_DETAIL
            JOIN ak_kode_akuntansi c ON a.ID_KLIEN = c.ID_KLIEN AND b.KODE_AKUN = c.KODE_AKUN
            WHERE (c.KATEGORI = 'Pendapatan' OR c.KATEGORI = 'Pendapatan Lainnya') AND a.TGL LIKE '%-$bulan-$tahun%'

            UNION ALL 

            SELECT a.ID_KLIEN, b.KODE_AKUN, c.NAMA_AKUN, c.TIPE, (b.DEBET + b.KREDIT) AS JML, 'Biaya Operasional' AS JENIS, 2 AS URUT FROM ak_input_voucher a 
            JOIN (
                SELECT ID_KLIEN, NO_VOUCHER_DETAIL, KODE_AKUN, SUM(DEBET) AS DEBET, SUM(KREDIT) AS KREDIT FROM ak_input_voucher_detail
                GROUP BY ID_KLIEN, NO_VOUCHER_DETAIL, KODE_AKUN
            ) b ON a.ID_KLIEN = b.ID_KLIEN AND a.NO_VOUCHER = b.NO_VOUCHER_DETAIL
            JOIN ak_kode_akuntansi c ON a.ID_KLIEN = c.ID_KLIEN AND b.KODE_AKUN = c.KODE_AKUN
            WHERE (c.KATEGORI = 'Beban' OR c.KATEGORI = 'Beban Lainnya' OR c.KATEGORI = 'Harga Pokok Penjualan') AND a.TGL LIKE '%-$bulan-$tahun%'

            UNION ALL 

            SELECT a.ID_KLIEN, b.KODE_AKUN, c.NAMA_AKUN, c.TIPE, (b.DEBET + b.KREDIT) AS JML, 'Aktivitas Non Operasional' AS JENIS, 3 AS URUT FROM ak_input_voucher a 
            JOIN (
                SELECT ID_KLIEN, NO_VOUCHER_DETAIL, KODE_AKUN, SUM(DEBET) AS DEBET, SUM(KREDIT) AS KREDIT FROM ak_input_voucher_detail
                GROUP BY ID_KLIEN, NO_VOUCHER_DETAIL, KODE_AKUN
            ) b ON a.ID_KLIEN = b.ID_KLIEN AND a.NO_VOUCHER = b.NO_VOUCHER_DETAIL
            JOIN ak_kode_akuntansi c ON a.ID_KLIEN = c.ID_KLIEN AND b.KODE_AKUN = c.KODE_AKUN
            WHERE (c.KATEGORI != 'Beban' AND c.KATEGORI != 'Beban Lainnya' AND c.KATEGORI != 'Harga Pokok Penjualan'
            AND c.KATEGORI != 'Pendapatan' AND c.KATEGORI != 'Pendapatan Lainnya' AND c.KATEGORI != 'Kas & Bank')
            AND a.TGL LIKE '%-$bulan-$tahun%'

            UNION ALL 

            SELECT a.ID_KLIEN, a.KODE_AKUN, b.NAMA_AKUN, b.TIPE, (a.DEBET + a.KREDIT) AS JML, 'Aktivitas Non Operasional' AS JENIS, 3 AS URUT FROM ak_penerimaan_kas_bank a 
            JOIN ak_kode_akuntansi b ON a.ID_KLIEN = b.ID_KLIEN AND a.KODE_AKUN = b.KODE_AKUN
            WHERE a.TIPE = 'EK' AND a.TGL LIKE '%-$bulan-$tahun%'
            ) a
            WHERE a.ID_KLIEN = $id_klien
            GROUP BY a.ID_KLIEN, a.KODE_AKUN, a.NAMA_AKUN, a.TIPE, a.JENIS, a.URUT
        ORDER BY a.URUT

        ";

        return $this->db->query($sql)->result();
    }

}

?>
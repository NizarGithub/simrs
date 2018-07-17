<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kas_bank_m extends CI_Model
{
	function __construct() {
		  parent::__construct();
		  $this->load->database();
	}

    function get_kas_bank($keyword, $id_klien){
        $where = "1=1";
        if($keyword != "" || $keyword != null){
            $where = $where." AND (a.KODE_AKUN LIKE '%$keyword%' OR a.NAMA_AKUN LIKE '%$keyword%' ) ";
        }

        $sql = "
        SELECT a.*, IFNULL(b.TOTAL, 0) AS TOTAL FROM ak_kode_akuntansi a
        LEFT JOIN (
            SELECT a.KODE_AKUN, (a.DEBET - a.KREDIT) AS TOTAL FROM (
                SELECT KODE_AKUN, SUM(DEBET) AS DEBET, SUM(KREDIT) AS KREDIT
                FROM ak_penerimaan_kas_bank WHERE ID_KLIEN = $id_klien
                GROUP BY KODE_AKUN
            ) a
        ) b ON a.KODE_AKUN = b.KODE_AKUN
        WHERE $where AND a.ID_KLIEN = $id_klien AND a.KATEGORI = 'Kas & Bank'
        ORDER BY a.ID ASC
        ";

        return $this->db->query($sql)->result();
    }

    function get_kas_bank2($keyword, $id_klien){
        $where = "1=1";
        if($keyword != "" || $keyword != null){
            $where = $where." AND (KODE_AKUN LIKE '%$keyword%' OR NAMA_AKUN LIKE '%$keyword%' ) ";
        }

        $sql = "
        SELECT a.*, IFNULL(b.TOTAL, 0) AS TOTAL FROM ak_kode_akuntansi a
        LEFT JOIN (
            SELECT a.KODE_AKUN, (a.DEBET - a.KREDIT) AS TOTAL FROM (
                SELECT KODE_AKUN, SUM(DEBET) AS DEBET, SUM(KREDIT) AS KREDIT
                FROM ak_penerimaan_kas_bank WHERE ID_KLIEN = $id_klien
                GROUP BY KODE_AKUN
            ) a
        ) b ON a.KODE_AKUN = b.KODE_AKUN
        WHERE $where AND a.ID_KLIEN = $id_klien AND a.KATEGORI = 'Credit Card'
        ORDER BY a.ID ASC
        ";

        return $this->db->query($sql)->result();
    }

    function get_jml_kas($id_klien){
        $sql = "
        SELECT a.*, IFNULL(b.TOTAL, 0) AS TOTAL FROM ak_kode_akuntansi a
        LEFT JOIN (
            SELECT a.KODE_AKUN, (a.DEBET - a.KREDIT) AS TOTAL FROM (
                SELECT KODE_AKUN, SUM(DEBET) AS DEBET, SUM(KREDIT) AS KREDIT
                FROM ak_penerimaan_kas_bank WHERE ID_KLIEN = $id_klien
                GROUP BY KODE_AKUN
            ) a
        ) b ON a.KODE_AKUN = b.KODE_AKUN
        WHERE a.ID_KLIEN = $id_klien AND a.KATEGORI = 'Kas & Bank'
        ORDER BY a.ID ASC
        ";

        return $this->db->query($sql)->result();
    }


    function hapus_produk($id){
        $sql = "
        DELETE FROM ak_produk WHERE ID = $id
        ";

        $this->db->query($sql);
    }

    function cari_produk_by_id($id){
        $sql = "
        SELECT * FROM ak_produk WHERE ID = $id
        ";

        return $this->db->query($sql)->row();
    }


    function simpan_akun($id_klien, $nama_akun, $nomor_akun, $deskripsi, $kategori){
        $sql = "
            INSERT INTO ak_kode_akuntansi
            (ID_KLIEN, KODE_AKUN, NAMA_AKUN, KATEGORI, DESKRIPSI)
            VALUES 
            ($id_klien, '$nomor_akun', '$nama_akun', '$kategori', '$deskripsi')
        ";

        $this->db->query($sql);
    }

    function get_last_kas_bank($id_klien){
        $sql = "
        SELECT * FROM ak_kode_akuntansi WHERE ID_KLIEN = $id_klien AND KATEGORI = 'Kas & Bank'
        ORDER BY ID DESC LIMIT 1
        ";

        return $this->db->query($sql)->row();
    }

    function get_last_cc($id_klien){
        $sql = "
        SELECT * FROM ak_kode_akuntansi WHERE ID_KLIEN = $id_klien AND KATEGORI = 'Credit Card'
        ORDER BY ID DESC LIMIT 1
        ";

        return $this->db->query($sql)->row();
    }

    function get_list_akun($id_klien){
        $sql = "
        SELECT * FROM ak_kode_akuntansi WHERE ID_KLIEN = $id_klien AND (KATEGORI = 'Kas & Bank' OR KATEGORI = 'Credit Card')
        ";

        return $this->db->query($sql)->result();
    }

    function get_list_akun_all($id_klien){
        $sql = "
        SELECT * FROM ak_kode_akuntansi WHERE ID_KLIEN = $id_klien
        ";

        return $this->db->query($sql)->result();
    }

    function get_pel_sup($id_klien){
        $sql = "
        SELECT NAMA_PELANGGAN AS NAMA, 'Pelanggan' AS STS FROM ak_pelanggan
        WHERE ID_KLIEN = $id_klien

        UNION ALL 

        SELECT NAMA_SUPPLIER AS NAMA, 'Supplier' AS STS FROM ak_supplier
        WHERE ID_KLIEN = $id_klien
        ";

        return $this->db->query($sql)->result();
    }

    function get_no_trx($id_klien){
        $sql = "
        SELECT * FROM ak_nomor WHERE ID_KLIEN = $id_klien AND TIPE = 'Terima Uang'
        ";

        return $this->db->query($sql)->row();
    }

    function get_no_trx_trfuang($id_klien){
        $sql = "
        SELECT * FROM ak_nomor WHERE ID_KLIEN = $id_klien AND TIPE = 'Trf Uang'
        ";

        return $this->db->query($sql)->row();
    }

    function get_no_trx_kirim_uang($id_klien){
        $sql = "
        SELECT * FROM ak_nomor WHERE ID_KLIEN = $id_klien AND TIPE = 'Kirim Uang'
        ";

        return $this->db->query($sql)->row();
    }

    function save_next_nomor($id_klien, $tipe, $no_trx){
        $sql_del = "
        DELETE FROM ak_nomor WHERE TIPE = '$tipe' AND ID_KLIEN = $id_klien
        ";

        $this->db->query($sql_del);

        $sql = "
        INSERT INTO ak_nomor 
        (ID_KLIEN, TIPE, NEXT)
        VALUES 
        ($id_klien, '$tipe', $no_trx)
        ";

        $this->db->query($sql);
    }

    function simpan_kas_bank($id_klien, $kode_akun_setor, $yang_membayar, $tgl_trx, $no_trx, $total_all){
        $sql = "
        INSERT INTO ak_penerimaan_kas_bank
        (ID_KLIEN, KODE_AKUN, NO_BUKTI, TGL, DEBET, KREDIT, TIPE, KONTAK)
        VALUES 
        ($id_klien, '$kode_akun_setor', '$no_trx', '$tgl_trx', $total_all, 0, 'PENERIMAAN', '$yang_membayar')
        ";

        $this->db->query($sql);
    }


    function get_id_kas_bank($id_klien, $no_trx){
        $sql  = "
        SELECT * FROM ak_penerimaan_kas_bank WHERE ID_KLIEN = $id_klien AND NO_BUKTI = '$no_trx'
        ";

        return $this->db->query($sql)->row();
    }

    function simpan_kas_bank_detail($id_klien, $id_kas_bank, $no_trx, $kode_akun, $deskripsi, $nilai){
        
        $nilai = str_replace(',', '', $nilai);
        $deskripsi = addslashes($deskripsi);

        $sql = "
        INSERT INTO ak_penerimaan_kas_bank_detail
        (ID_KAS_BANK, ID_KLIEN, NO_BUKTI, KODE_AKUN, DESKRIPSI, NILAI)
        VALUES 
        ($id_kas_bank, $id_klien, '$no_trx', '$kode_akun', '$deskripsi', $nilai)
        ";

        $this->db->query($sql);
    }

    function proses_trf_bank_1($id_klien, $trf_dari, $no_trx, $tgl_trx, $nilai_trf, $memo){
        $sql = "
        INSERT INTO ak_penerimaan_kas_bank 
        (ID_KLIEN, KODE_AKUN, NO_BUKTI, TGL, DEBET, KREDIT, DESKRIPSI, TIPE)
        VALUES 
        ($id_klien, '$trf_dari', '$no_trx', '$tgl_trx', 0, $nilai_trf, '$memo', 'TRANSFER UANG')
        ";

        $this->db->query($sql);
    }

    function proses_trf_bank_2($id_klien, $trf_dari, $no_trx, $tgl_trx, $nilai_trf, $memo){
        $sql = "
        INSERT INTO ak_penerimaan_kas_bank 
        (ID_KLIEN, KODE_AKUN, NO_BUKTI, TGL, DEBET, KREDIT, DESKRIPSI, TIPE)
        VALUES 
        ($id_klien, '$trf_dari', '$no_trx', '$tgl_trx', $nilai_trf, 0, '$memo', 'TRANSFER UANG')
        ";

        $this->db->query($sql);
    }

    function simpan_kas_bank_kirim_uang($id_klien, $kode_akun_setor, $yang_membayar, $tgl_trx, $no_trx, $total_all){
        $sql = "
        INSERT INTO ak_penerimaan_kas_bank
        (ID_KLIEN, KODE_AKUN, NO_BUKTI, TGL, DEBET, KREDIT, TIPE, KONTAK)
        VALUES 
        ($id_klien, '$kode_akun_setor', '$no_trx', '$tgl_trx', 0, $total_all, 'PENERIMAAN', '$yang_membayar')
        ";

        $this->db->query($sql);
    }

    function simpan_saldo_awal($id_klien, $nomor_akun, $tgl, $saldo_awal, $des){
        $sql = "
        INSERT INTO ak_penerimaan_kas_bank
        (ID_KLIEN, KODE_AKUN, NO_BUKTI, TGL, DEBET, KREDIT, DESKRIPSI, TIPE)
        VALUES 
        ($id_klien, '$nomor_akun', 'Saldo Awal', '$tgl', $saldo_awal, 0, '$des', 'SA')
        ";

        $this->db->query($sql);
    }

    function simpan_ekuitas_saldo($id_klien, $nomor_akun, $tgl, $saldo_awal, $des_ekuitas){
        $sql = "
        INSERT INTO ak_penerimaan_kas_bank
        (ID_KLIEN, KODE_AKUN, NO_BUKTI, TGL, DEBET, KREDIT, DESKRIPSI, TIPE)
        VALUES 
        ($id_klien, '3-3900', 'Saldo Awal', '$tgl', 0, $saldo_awal, '$des_ekuitas', 'EK')
        ";

        $this->db->query($sql);
    }


}

?>
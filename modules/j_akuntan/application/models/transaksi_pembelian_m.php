<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Transaksi_pembelian_m extends CI_Model
{
	function __construct() {
		  parent::__construct();
		  $this->load->database();
	}

    function get_pembelian($keyword, $id_klien){
        $where = "1=1";
        // if($keyword != "" || $keyword != null){
        //     $where = $where." AND (a.KODE_AKUN LIKE '%$keyword%' OR a.NAMA_AKUN LIKE '%$keyword%' ) ";
        // }

        $sql = "
        SELECT * FROM ak_pembelian WHERE ID_KLIEN = $id_klien
        ORDER BY ID ASC
        ";

        return $this->db->query($sql)->result();
    }

    function get_data_trx($id){
        $sql = "
        SELECT * FROM ak_pembelian WHERE ID = $id
        ";

        return $this->db->query($sql)->row();
    }

    function get_data_trx_detail($id){
        $sql = "
        SELECT * FROM ak_pembelian_detail WHERE ID_PENJUALAN = $id
        ";

        return $this->db->query($sql)->result();
    }

    function hapus_voucher($id_klien, $no_voc){
        $sql_1 = "
        DELETE FROM ak_input_voucher WHERE NO_VOUCHER = '$no_voc' AND ID_KLIEN = $id_klien
        ";

        $this->db->query($sql_1);

        $sql_2 = "
        DELETE FROM ak_input_voucher_detail WHERE NO_VOUCHER_DETAIL = '$no_voc' AND ID_KLIEN = $id_klien
        ";

        $this->db->query($sql_2);

        $sql_3 = "
        DELETE FROM ak_jurnal_kas_bank WHERE NO_VOUCHER = '$no_voc' AND ID_KLIEN = $id_klien
        ";

        $this->db->query($sql_3);
    }

    function hapus_trx_pembelian($id_hapus){
        $sql_1 = "
        DELETE FROM ak_pembelian WHERE ID = $id_hapus
        ";

        $this->db->query($sql_1);
    }

    function get_pembelian_filter($keyword, $id_klien, $tgl_awal, $tgl_akhir){

        $where = "1 = 1";
        $where = $where." AND STR_TO_DATE(`TGL_TRX`, '%d-%c-%Y') >= STR_TO_DATE('$tgl_awal' , '%d-%c-%Y') AND STR_TO_DATE(`TGL_TRX`, '%d-%c-%Y') <= STR_TO_DATE('$tgl_akhir' , '%d-%c-%Y') ";


        $sql = "
        SELECT * FROM ak_pembelian WHERE ID_KLIEN = $id_klien AND $where
        ORDER BY ID ASC
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
        SELECT ID, NAMA_SUPPLIER AS NAMA FROM ak_supplier
        WHERE ID_KLIEN = $id_klien
        ";

        return $this->db->query($sql)->result();
    }

    function get_no_trx_pembelian($id_klien){
        $sql = "
        SELECT * FROM ak_nomor WHERE ID_KLIEN = $id_klien AND TIPE = 'Pembelian'
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

    function get_supplier_detail($id_supplier){
        $sql = "
        SELECT * FROM admum_supplier_barang WHERE ID = $id_supplier
        ";

        return $this->db->query($sql)->row();
    }

    function get_all_produk($id_klien){
        $sql = "
        SELECT * FROM ak_produk WHERE ID_KLIEN = $id_klien
        ";

        return $this->db->query($sql)->result();
    }

    function get_produk_detail($id_produk){
        $sql = "
        SELECT * FROM admum_setup_peralatan_medis WHERE ID = $id_produk
        ";

        return $this->db->query($sql)->row();
    }

    function get_pajak($id_klien){
        $sql = "
        SELECT * FROM ak_setup_pajak WHERE ID_KLIEN = $id_klien ORDER BY ID
        ";

        return $this->db->query($sql)->result();
    }

    function get_pajak_prosen($id_pajak){
        $sql = "
        SELECT * FROM ak_setup_pajak WHERE ID = $id_pajak
        ";

        return $this->db->query($sql)->row();
    }

    function simpan_pembelian($id_klien, $no_trx, $id_pelanggan, $pelanggan, $alamat_tagih, $tgl_trx, $tgl_jatuh_tempo, $id_pajak, $sub_total, $pajak_total, 
                              $total_all, $sts_lunas, $memo_lunas, $akun_hutang, $kode_akun_pajak)
    {

        if($pajak_total == ""){
            $pajak_total = 0;
        }

        $sql = "
        INSERT INTO ak_pembelian 
        (ID_KLIEN, NO_BUKTI, ID_PELANGGAN, PELANGGAN, ALAMAT, TGL_TRX, TGL_JATUH_TEMPO, ID_PAJAK, SUB_TOTAL, NILAI_PAJAK, TOTAL, LUNAS, MEMO, KODE_AKUN_HUTANG, KODE_AKUN_PAJAK)
        VALUES 
        ($id_klien, '$no_trx', $id_pelanggan, '$pelanggan', '$alamat_tagih', '$tgl_trx', '$tgl_jatuh_tempo', $id_pajak, $sub_total, $pajak_total, $total_all, $sts_lunas, '$memo_lunas', '$akun_hutang', '$kode_akun_pajak')
        ";

        $this->db->query($sql);
    }

    function get_id_pembelian($id_klien, $no_trx){
        $sql = "
        SELECT * FROM ak_pembelian WHERE ID_KLIEN = $id_klien AND NO_BUKTI = '$no_trx'
        ORDER BY ID DESC LIMIT 1
        ";

        return $this->db->query($sql)->row();
    }

    function simpan_detail_pembelian($id_penjualan, $id_klien, $nama_produk, $qty, $satuan, $harga_satuan, $jumlah){
        
        $harga_satuan = str_replace(',', '', $harga_satuan);
        $jumlah       = str_replace(',', '', $jumlah);

        $sql = "
        INSERT INTO ak_pembelian_detail 
        (ID_KLIEN, ID_PENJUALAN, NAMA_PRODUK, QTY, SATUAN, HARGA_SATUAN, TOTAL)
        VALUES 
        ($id_klien, $id_penjualan, '$nama_produk', $qty, '$satuan', $harga_satuan, $jumlah)
        ";

        $this->db->query($sql);
    }

    function ubah_penjualan($id, $no_trx, $id_pelanggan, $pelanggan, $alamat_tagih, $tgl_trx, $tgl_jatuh_tempo, $id_pajak, $sub_total, $pajak_total, $total_all, $sts_lunas, $memo_lunas, $akun_hutang, $kode_akun_pajak){
        $sql = "
            UPDATE ak_pembelian SET ID_PELANGGAN = $id_pelanggan, PELANGGAN = '$pelanggan', ALAMAT = '$alamat_tagih', TGL_TRX = '$tgl_trx', ID_PAJAK = $id_pajak, SUB_TOTAL = $sub_total, NILAI_PAJAK = $pajak_total,
            TOTAL = $total_all, LUNAS = $sts_lunas, MEMO = '$memo_lunas', KODE_AKUN_HUTANG = '$akun_hutang', KODE_AKUN_PAJAK = '$kode_akun_pajak'    
            WHERE ID = $id

        ";

        $this->db->query($sql);
    }

    function simpan_hutang($id_klien, $no_trx, $tgl_trx, $total_all, $tipe){

        $total_all       = str_replace(',', '', $total_all);
        $sql = "
        INSERT INTO ak_hutang_piutang 
        (ID_KLIEN, NO_BUKTI, TGL, KODE_AKUN, DEBET, KREDIT, TIPE)
        VALUES 
        ($id_klien, '$no_trx', '$tgl_trx', '2-2000', 0, $total_all, '$tipe')
        ";

        $this->db->query($sql);
    }

    function simpan_ke_trx_akuntansi_detail($id_klien, $kode_akun, $sub_total, $no_trx){
        $sub_total       = str_replace(',', '', $sub_total);
        $sql = "
        INSERT INTO ak_input_voucher_detail 
        (ID_KLIEN, KODE_AKUN, DEBET, KREDIT, NO_BUKTI)
        VALUES 
        ($id_klien, '$kode_akun', $sub_total, 0, '$no_trx')
        ";

        $this->db->query($sql);
    }

    function hapus_detail_trx($id){
        $sql = "
        DELETE FROM ak_pembelian_detail WHERE ID_PENJUALAN = $id
        ";

        $this->db->query($sql);
    }

    function update_stok($id_klien, $id_produk, $qty){
        $sql = "
        UPDATE ak_produk SET STOK = STOK + $qty
        WHERE ID = $id_produk
        ";

        $this->db->query($sql);
    }


}

?>
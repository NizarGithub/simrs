<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Input_jurnal_penyesuaian_m extends CI_Model
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

    function get_no_trx_akun($id_klien){

        $bln = date('m');
        $thn = date('Y');

        $sql = "
        SELECT * FROM ak_nomor WHERE ID_KLIEN = $id_klien AND TIPE = 'JURNAL_PENYESUAIAN' AND BULAN = $bln AND TAHUN = $thn
        ";

        return $this->db->query($sql)->row();
    }

    function simpan_trx_akuntansi($id_klien, $no_trx_akun, $no_bukti, $total_debet_all, $total_kredit_all, $tgl_trx, $tipe, $kontak, $uraian){
        $total_debet_all   = str_replace(',', '', $total_debet_all);
        $total_kredit_all  = str_replace(',', '', $total_kredit_all);

        $sql = "
        INSERT INTO ak_input_voucher 
            (ID_KLIEN, NO_VOUCHER, NO_BUKTI, TGL, DEBET, KREDIT, URAIAN, KONTAK, TIPE)
        VALUES 
            ($id_klien, '$no_trx_akun', '$no_bukti', '$tgl_trx', $total_debet_all, $total_kredit_all, '$uraian', '$kontak', '$tipe')
        ";

         $this->db->query($sql);

    }

    function simpan_trx_akuntansi_detail($id_klien, $no_trx_akun, $kode_akun, $debet_row, $kredit_row, $no_bukti){
        $debet_row   = str_replace(',', '', $debet_row);
        $kredit_row  = str_replace(',', '', $kredit_row);

        $sql = "
        INSERT INTO ak_input_voucher_detail
            (ID_KLIEN, NO_VOUCHER_DETAIL, KODE_AKUN, DEBET, KREDIT, NO_BUKTI)
        VALUES 
            ($id_klien, '$no_trx_akun', '$kode_akun', $debet_row, $kredit_row, '$no_bukti')
        ";

         $this->db->query($sql);
    }

    function repair_detail_voucher($id_klien, $no_trx_akun, $no_bukti){
        $sql = "
        UPDATE ak_input_voucher_detail SET NO_VOUCHER_DETAIL = '$no_trx_akun'
        WHERE ID_KLIEN = $id_klien AND NO_BUKTI = '$no_bukti'
        ";

        $this->db->query($sql);
    }

    function save_next_nomor($id_klien, $tipe, $no_trx){

        $bln = date('m');
        $thn = date('Y');

        $sql_del = "
        DELETE FROM ak_nomor WHERE TIPE = '$tipe' AND ID_KLIEN = $id_klien AND BULAN = $bln AND TAHUN = $thn
        ";

        $this->db->query($sql_del);

        $sql = "
        INSERT INTO ak_nomor 
        (ID_KLIEN, TIPE, NEXT, BULAN, TAHUN)
        VALUES 
        ($id_klien, '$tipe', $no_trx, $bln, $thn)
        ";

        $this->db->query($sql);
    }

    function update_penjualan_pembelian($id_klien, $no_bukti, $no_trx_akun){
        $sql = "
        UPDATE ak_pembelian SET NO_TRX_AKUN = '$no_trx_akun'
        WHERE ID_KLIEN = $id_klien AND NO_BUKTI = '$no_bukti'
        ";

        $this->db->query($sql);

        $sql_2 = "
        UPDATE ak_penjualan SET NO_TRX_AKUN = '$no_trx_akun'
        WHERE ID_KLIEN = $id_klien AND NO_BUKTI = '$no_bukti'
        ";

        $this->db->query($sql_2);
    }

    function simpan_piutang($id_klien, $no_trx, $tgl_trx, $total_all, $tipe){

        $total_all       = str_replace(',', '', $total_all);
        $sql = "
        INSERT INTO ak_hutang_piutang 
        (ID_KLIEN, NO_BUKTI, TGL, KODE_AKUN, DEBET, KREDIT, TIPE)
        VALUES 
        ($id_klien, '$no_trx', '$tgl_trx', '1-1200', 0, $total_all, '$tipe')
        ";

        $this->db->query($sql);
    }

    function simpan_hutang($id_klien, $no_trx, $tgl_trx, $total_all, $tipe){

        $total_all       = str_replace(',', '', $total_all);
        $sql = "
        INSERT INTO ak_hutang_piutang 
        (ID_KLIEN, NO_BUKTI, TGL, KODE_AKUN, DEBET, KREDIT, TIPE)
        VALUES 
        ($id_klien, '$no_trx', '$tgl_trx', '2-2000', $total_all, 0, '$tipe')
        ";

        $this->db->query($sql);
    }

    function get_nilai_hutang_piutang($id_klien, $no_bukti, $tipe){
        $sql = "
        SELECT * FROM ak_hutang_piutang WHERE ID_KLIEN = $id_klien AND NO_BUKTI = '$no_bukti' AND TIPE = '$tipe'
        ";

        return $this->db->query($sql)->row();
    }

    function simpan_jp($id_klien, $no_jp, $tgl_trx, $no_trx_akun, $uraian, $tgl_trx){
        $sql = "
        INSERT INTO ak_jurnal_penye
        (ID_KLIEN, NO_BUKTI, NO_VOUCHER, TGL, URAIAN)
        VALUES 
        ($id_klien, '$no_jp', '$no_trx_akun', '$tgl_trx', '$uraian')
        ";

        $this->db->query($sql);

        $sql_2 = "
        UPDATE ak_input_voucher SET NO_JP = '$no_jp' 
        WHERE ID_KLIEN = $id_klien AND NO_VOUCHER = '$no_trx_akun'
        ";

        $this->db->query($sql_2);
    }

    function simpan_jp_detail($id_klien, $no_jp, $no_trx_akun, $kode_akun, $debet_row, $kredit_row){

        $debet_row   = str_replace(',', '', $debet_row);
        $kredit_row  = str_replace(',', '', $kredit_row);

        $sql = "
        INSERT INTO ak_jurnal_penye_detail
        (ID_KLIEN, NO_BUKTI, NO_VOUCHER, KODE_AKUN, DEBET, KREDIT)
        VALUES 
        ($id_klien, '$no_jp', '$no_trx_akun', '$kode_akun', $debet_row, $kredit_row)
        ";

        $this->db->query($sql);
    }

    
}

?>
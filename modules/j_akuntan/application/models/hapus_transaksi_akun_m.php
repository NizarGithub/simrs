<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hapus_transaksi_akun_m extends CI_Model
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
    
    function delete_ju($id_klien, $no_trx_akun){
        $sql_1 = "
        DELETE FROM ak_input_voucher WHERE NO_VOUCHER = '$no_trx_akun' AND ID_KLIEN = $id_klien
        ";

        $this->db->query($sql_1);

        $sql_2 = "
        DELETE FROM ak_input_voucher_detail WHERE NO_VOUCHER_DETAIL = '$no_trx_akun' AND ID_KLIEN = $id_klien
        ";

        $this->db->query($sql_2);

        $sql_3 = "
        DELETE FROM ak_jurnal_penye WHERE NO_VOUCHER = '$no_trx_akun' AND ID_KLIEN = $id_klien
        ";

        $this->db->query($sql_3);

        $sql_4 = "
        DELETE FROM ak_jurnal_penye_detail WHERE NO_VOUCHER = '$no_trx_akun' AND ID_KLIEN = $id_klien
        ";

        $this->db->query($sql_4);

        $sql_5 = "
        DELETE FROM ak_jurnal_kas_bank WHERE NO_VOUCHER = '$no_trx_akun' AND ID_KLIEN = $id_klien
        ";

        $this->db->query($sql_5);

        $sql_6 = "
        UPDATE ak_pembelian SET NO_TRX_AKUN = NULL WHERE ID_KLIEN = $id_klien AND NO_TRX_AKUN = '$no_trx_akun'
        ";

        $this->db->query($sql_6);

        $sql_7 = "
        UPDATE ak_penjualan SET NO_TRX_AKUN = NULL WHERE ID_KLIEN = $id_klien AND NO_TRX_AKUN = '$no_trx_akun'
        ";

        $this->db->query($sql_7);
    }

    function delete_jp($id_klien, $no_trx_akun){
        $sql_3 = "
        DELETE FROM ak_jurnal_penye WHERE NO_VOUCHER = '$no_trx_akun' AND ID_KLIEN = $id_klien
        ";

        $this->db->query($sql_3);

        $sql_4 = "
        DELETE FROM ak_jurnal_penye_detail WHERE NO_VOUCHER = '$no_trx_akun' AND ID_KLIEN = $id_klien
        ";

        $this->db->query($sql_4);
    }

    function delete_jbk($id_klien, $no_trx_akun){
        $sql_5 = "
        DELETE FROM ak_jurnal_kas_bank WHERE NO_VOUCHER = '$no_trx_akun' AND ID_KLIEN = $id_klien
        ";

        $this->db->query($sql_5);
    }
}

?>
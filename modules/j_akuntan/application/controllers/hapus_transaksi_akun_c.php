<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hapus_transaksi_akun_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		error_reporting(0);
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){
	        redirect('../../'); 
	    }
	    $this->load->model('hapus_transaksi_akun_m','model');

	}

	function index()
	{
		$keyword = "";
		$msg = "";
		$tgl_full = "";
		$kode_produk = "";
		$sess_user = $this->session->userdata('masuk_rs');
		$id_klien = $sess_user['id_klien'];

		if($this->input->post('no_trx_akun')){
			$msg = 1;
			$no_trx_akun = $this->input->post('no_trx_akun');
			$jenis = $this->input->post('jenis');
			
			if($jenis == "ju"){
				$this->model->delete_ju($id_klien, $no_trx_akun);
			} else if($jenis == "jp"){
				$this->model->delete_jp($id_klien, $no_trx_akun);
			} else if($jenis == "jbk"){
				$this->model->delete_jbk($id_klien, $no_trx_akun);
			}
		}

		$dt = "";

		$data =  array(
			'page' => "hapus_transaksi_akun_v", 
			'title' => "Hapus Transaksi Akuntansi",  
			'master' => "input_akuntansi", 
			'view' => "hapus_trx_akun", 
			'dt' => $dt, 
			'msg' => $msg, 
			'post_url' => 'hapus_transaksi_akun_c', 
		);
		
		$this->load->view('beranda_v', $data);
	}

	function get_no_bukti(){
		$sess_user = $this->session->userdata('masuk_rs');
		$id_klien = $sess_user['id_klien'];
		$where = "1=1";

		$keyword = $this->input->post('keyword');
		if($keyword != "" || $keyword != null){
			$where = $where." AND (NO_VOUCHER LIKE '%$keyword%' OR URAIAN LIKE '%$keyword%')";
		}

		$sql = "
		SELECT * FROM ak_input_voucher WHERE ID_KLIEN = $id_klien AND $where
		";

		$dt = $this->db->query($sql)->result();

		echo json_encode($dt);
	}

	function get_detail_vocer(){
		$voc   = $this->input->post('voc');
		$sess_user = $this->session->userdata('masuk_rs');
		$id_klien = $sess_user['id_klien'];


		$sql = "
		SELECT * FROM ak_input_voucher WHERE NO_VOUCHER = '$voc' AND ID_KLIEN = $id_klien
		";

		$dt = $this->db->query($sql)->row();
		echo json_encode($dt);
	}

	function get_detail_vocer_akun(){
		$voc   = $this->input->post('voc');
		$sess_user = $this->session->userdata('masuk_rs');
		$id_klien = $sess_user['id_klien'];


		$sql = "
		SELECT a.*, b.NAMA_AKUN 
		FROM ak_input_voucher_detail a 
		JOIN ak_kode_akuntansi b ON a.KODE_AKUN = b.KODE_AKUN AND a.ID_KLIEN = b.ID_KLIEN
		WHERE a.NO_VOUCHER_DETAIL = '$voc' AND a.ID_KLIEN = $id_klien
		ORDER BY a.DEBET DESC
		";

		$dt = $this->db->query($sql)->result();
		echo json_encode($dt);
	}

	function get_no_bukti_jp(){
		$sess_user = $this->session->userdata('masuk_rs');
		$id_klien = $sess_user['id_klien'];
		$where = "1=1";

		$keyword = $this->input->post('keyword');
		if($keyword != "" || $keyword != null){
			$where = $where." AND (NO_BUKTI LIKE '%$keyword%' OR URAIAN LIKE '%$keyword%' OR NO_VOUCHER LIKE '%$keyword%')";
		}

		$sql = "
		SELECT * FROM ak_jurnal_penye WHERE ID_KLIEN = $id_klien AND $where
		";

		$dt = $this->db->query($sql)->result();

		echo json_encode($dt);
	}

	function get_detail_vocer_jp(){
		$voc   = $this->input->post('voc');
		$sess_user = $this->session->userdata('masuk_rs');
		$id_klien = $sess_user['id_klien'];


		$sql = "
		SELECT * FROM ak_jurnal_penye WHERE NO_BUKTI = '$voc' AND ID_KLIEN = $id_klien
		";

		$dt = $this->db->query($sql)->row();
		echo json_encode($dt);
	}

	function get_detail_vocer_akun_jp(){
		$voc   = $this->input->post('voc');
		$sess_user = $this->session->userdata('masuk_rs');
		$id_klien = $sess_user['id_klien'];


		$sql = "
		SELECT a.*, b.NAMA_AKUN 
		FROM ak_jurnal_penye_detail a 
		JOIN ak_kode_akuntansi b ON a.KODE_AKUN = b.KODE_AKUN AND a.ID_KLIEN = b.ID_KLIEN
		WHERE a.NO_BUKTI = '$voc' AND a.ID_KLIEN = $id_klien
		ORDER BY a.DEBET DESC
		";

		$dt = $this->db->query($sql)->result();
		echo json_encode($dt);
	}

	function get_no_bukti_jbk(){
		$sess_user = $this->session->userdata('masuk_rs');
		$id_klien = $sess_user['id_klien'];
		$where = "1=1";

		$keyword = $this->input->post('keyword');
		if($keyword != "" || $keyword != null){
			$where = $where." AND (CEK_GIRO LIKE '%$keyword%' OR URAIAN LIKE '%$keyword%' OR NO_VOUCHER LIKE '%$keyword%')";
		}

		$sql = "
		SELECT NO_VOUCHER, CEK_GIRO, URAIAN, TGL_CEK FROM ak_jurnal_kas_bank WHERE ID_KLIEN = $id_klien AND $where
		GROUP BY NO_VOUCHER, CEK_GIRO, URAIAN, TGL_CEK
		";

		$dt = $this->db->query($sql)->result();

		echo json_encode($dt);
	}

	function get_detail_vocer_jbk(){
		$voc   = $this->input->post('voc');
		$cek_giro   = $this->input->post('cek_giro');
		$sess_user = $this->session->userdata('masuk_rs');
		$id_klien = $sess_user['id_klien'];


		$sql = "
		SELECT * FROM ak_jurnal_kas_bank WHERE NO_VOUCHER = '$voc' AND CEK_GIRO = '$cek_giro' AND ID_KLIEN = $id_klien
		";

		$dt = $this->db->query($sql)->row();
		echo json_encode($dt);
	}

	function get_detail_vocer_akun_jbk(){
		$voc   = $this->input->post('voc');
		$cek_giro   = $this->input->post('cek_giro');
		$sess_user = $this->session->userdata('masuk_rs');
		$id_klien = $sess_user['id_klien'];


		$sql = "
		SELECT a.*, b.NAMA_AKUN 
		FROM ak_jurnal_kas_bank a 
		JOIN ak_kode_akuntansi b ON a.KODE_AKUN = b.KODE_AKUN AND a.ID_KLIEN = b.ID_KLIEN
		WHERE a.NO_VOUCHER = '$voc' AND a.ID_KLIEN = $id_klien AND a.CEK_GIRO = '$cek_giro'
		ORDER BY a.DEBET DESC
		";

		$dt = $this->db->query($sql)->result();
		echo json_encode($dt);
	}

	function cek_no_bukti_ju(){
		$voc   = $this->input->post('voc');
		$sess_user = $this->session->userdata('masuk_rs');
		$id_klien = $sess_user['id_klien'];

		$aman = "aman";

		$sql_jp = "
		SELECT * FROM ak_jurnal_penye WHERE NO_VOUCHER = '$voc' AND ID_KLIEN = $id_klien
		";

		$dt_jp = $this->db->query($sql_jp)->result();

		$sql_jbk = "
		SELECT * FROM ak_jurnal_kas_bank WHERE NO_VOUCHER = '$voc' AND ID_KLIEN = $id_klien
		";

		$dt_jbk = $this->db->query($sql_jbk)->result();

		if(count($dt_jp) > 0 ){
			$aman = "tidak_jp";
		} else if(count($dt_jbk) > 0 ){
			$aman = "tidak_jbk";
		} else if(  count($dt_jp) > 0 && count($dt_jbk) > 0 ){
			$aman = "tidak_jp_jbk";
		}

		echo json_encode($aman);

	}

}
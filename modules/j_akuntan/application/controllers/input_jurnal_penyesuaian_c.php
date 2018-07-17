<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Input_jurnal_penyesuaian_c extends CI_Controller {

	function __construct()
	{
		parent::__construct(); 
		error_reporting(0);
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){
	        redirect('../../');
	    }
	    $this->load->model('input_jurnal_penyesuaian_m','model');

	}

	function index()
	{
		$keyword = "";
		$msg = "";
		$tgl_full = "";
		$kode_produk = "";
		$sess_user = $this->session->userdata('masuk_rs');
		$id_klien = $sess_user['id_klien'];

		if($this->input->post('simpan')){
			$msg = 1;
			
			$no_jp      	  = $this->input->post('no_jp');
			$no_jp2     	  = $this->input->post('no_jp2');
			$tgl_trx          = $this->input->post('tgl_trx');
			$no_trx_akun      = $this->input->post('no_trx_akun');
			$uraian           = addslashes($this->input->post('uraian'));

			$this->model->simpan_jp($id_klien, $no_jp, $tgl_trx, $no_trx_akun, $uraian, $tgl_trx);

			$kode_akun_row = $this->input->post('kode_akun_row');
			$debet_row 	   = $this->input->post('debet_row');
			$kredit_row    = $this->input->post('kredit_row');

			foreach ($kode_akun_row as $key => $val) {
				$this->model->simpan_jp_detail($id_klien, $no_jp, $no_trx_akun, $val, $debet_row[$key], $kredit_row[$key]);
			}

			$this->model->save_next_nomor($id_klien, 'JURNAL_PENYESUAIAN', $no_jp2);
		}

		$dt = "";
		$get_list_akun_all = $this->model->get_list_akun_all($id_klien);

		$get_no_trx_akun = $this->model->get_no_trx_akun($id_klien);

		$data =  array(
			'page' => "input_jurnal_penyesuaian_v", 
			'title' => "Input Jurnal Penyesuaian",  
			'master' => "input_akuntansi", 
			'view' => "jp", 
			'dt' => $dt, 
			'msg' => $msg, 
			'get_list_akun_all' => $get_list_akun_all, 
			'get_no_trx_akun' => $get_no_trx_akun, 
			'post_url' => 'input_jurnal_penyesuaian_c', 
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

	function get_pajak_row(){
		$sess_user = $this->session->userdata('masuk_rs');
		$id_klien = $sess_user['id_klien'];
		$no_akun = $this->input->post('no_akun');

		$sql = "
		SELECT * FROM ak_kode_akuntansi WHERE ID_KLIEN = $id_klien AND KODE_AKUN = '$no_akun'
		";

		$dt = $this->db->query($sql)->row();
		echo json_encode($dt);
	}

	function get_kode_akun_rinci(){
		$sess_user = $this->session->userdata('masuk_rs');
		$id_klien = $sess_user['id_klien'];
		$no_akun = $this->input->post('no_akun');

		$sql = "
		SELECT * FROM ak_kode_akuntansi WHERE ID_KLIEN = $id_klien AND KODE_AKUN = '$no_akun'
		";

		$dt = $this->db->query($sql)->row();
		echo json_encode($dt);
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
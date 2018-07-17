<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Beranda_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){
	        redirect('../../');
	    }

	    $this->load->model('beranda_m','model');
	}

	function index()
	{
		$sess_user = $this->session->userdata('masuk_rs');
		$id_klien = $sess_user['id_klien'];

		$tgl_1 = date('d-m-Y'); 
		$tgl_2 = date('d-m-Y', strtotime('-1 days', strtotime($tgl_1)));
		$tgl_3 = date('d-m-Y', strtotime('-2 days', strtotime($tgl_1)));
		$tgl_4 = date('d-m-Y', strtotime('-3 days', strtotime($tgl_1)));
		$tgl_5 = date('d-m-Y', strtotime('-4 days', strtotime($tgl_1)));

		$bulan_1 = date('m-Y');
		$bulan_2 = date('m-Y', strtotime('-1 month', strtotime($tgl_1)));
		$bulan_3 = date('m-Y', strtotime('-2 month', strtotime($tgl_1)));
		$bulan_4 = date('m-Y', strtotime('-3 month', strtotime($tgl_1)));
		$bulan_5 = date('m-Y', strtotime('-4 month', strtotime($tgl_1)));

		$data =  array(
			'page' => "", 
			'title' => "Simaku - Sistem Informasi Managemen Akuntansi", 
			'msg' => "", 
			'master' => "", 
			'view' => "", 
			'penjualan_bulan_ini' => $this->model->penjualan_bulan_ini($id_klien),
			'pembelian_bulan_ini' => $this->model->pembelian_bulan_ini($id_klien),
			'laba_rugi_bulan_ini' => $this->model->cetak_laba_rugi_bulanan($id_klien),
			'penjualan_grafik_harian_1' => $this->model->penjualan_grafik_harian($id_klien, $tgl_1),
			'penjualan_grafik_harian_2' => $this->model->penjualan_grafik_harian($id_klien, $tgl_2),
			'penjualan_grafik_harian_3' => $this->model->penjualan_grafik_harian($id_klien, $tgl_3),
			'penjualan_grafik_harian_4' => $this->model->penjualan_grafik_harian($id_klien, $tgl_4),
			'penjualan_grafik_harian_5' => $this->model->penjualan_grafik_harian($id_klien, $tgl_5),

			'pembelian_grafik_harian_1' => $this->model->pembelian_grafik_harian($id_klien, $tgl_1),
			'pembelian_grafik_harian_2' => $this->model->pembelian_grafik_harian($id_klien, $tgl_2),
			'pembelian_grafik_harian_3' => $this->model->pembelian_grafik_harian($id_klien, $tgl_3),
			'pembelian_grafik_harian_4' => $this->model->pembelian_grafik_harian($id_klien, $tgl_4),
			'pembelian_grafik_harian_5' => $this->model->pembelian_grafik_harian($id_klien, $tgl_5),

			'laba_rugi_harian_1' => $this->model->grafik_laba_rugi_harian($id_klien, $tgl_1),
			'laba_rugi_harian_2' => $this->model->grafik_laba_rugi_harian($id_klien, $tgl_2),
			'laba_rugi_harian_3' => $this->model->grafik_laba_rugi_harian($id_klien, $tgl_3),
			'laba_rugi_harian_4' => $this->model->grafik_laba_rugi_harian($id_klien, $tgl_4),
			'laba_rugi_harian_5' => $this->model->grafik_laba_rugi_harian($id_klien, $tgl_5),

			'laba_rugi_bulanan_1' => $this->model->grafik_laba_rugi_bulanan($id_klien, $bulan_1),
			'laba_rugi_bulanan_2' => $this->model->grafik_laba_rugi_bulanan($id_klien, $bulan_2),
			'laba_rugi_bulanan_3' => $this->model->grafik_laba_rugi_bulanan($id_klien, $bulan_3),
			'laba_rugi_bulanan_4' => $this->model->grafik_laba_rugi_bulanan($id_klien, $bulan_4),
			'laba_rugi_bulanan_5' => $this->model->grafik_laba_rugi_bulanan($id_klien, $bulan_5),
		);

		$this->load->view('beranda_v', $data);
	}

	public function sign_out(){

		$this->session->unset_userdata('masuk_rs');
		$this->session->sess_destroy();
		redirect(base_url());
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
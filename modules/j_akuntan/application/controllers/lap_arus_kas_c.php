<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lap_arus_kas_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id']; 
		if($id_user == "" || $id_user == null){
	        redirect('../../');
	    }
	    $this->load->model('lap_arus_kas_m','model');
	    $this->load->helper('url');
		$this->load->library('fpdf/HTML2PDF');
	}

	function index()
	{
		$keyword = "";
		$msg = "";
		$nomor_akun = "";
		$sess_user = $this->session->userdata('masuk_rs');
		$id_klien = $sess_user['id_klien'];

		if($this->input->post('pdf')){
			$this->cetak_arus_kas();
		} else if($this->input->post('excel')){
			$this->cetak_arus_kas_xls();
		} 

		//$dt = $this->model->get_no_akun($keyword, $id_klien);

		$data =  array(
			'page' => "lap_arus_kas_v", 
			'title' => "Laporan Arus Kas", 
			'msg' => "", 
			'master' => "laporan", 
			'view' => "arus_kas", 
			//'dt' => $dt, 
			'msg' => $msg, 
			'post_url' => 'lap_arus_kas_c', 
		);
		
		$this->load->view('beranda_v', $data);
	}

	function cetak_arus_kas(){
		$sess_user = $this->session->userdata('masuk_rs');
		$id_klien = $sess_user['id_klien'];

		//$tgl   = $this->input->post('tgl');
		
		$filter = $this->input->post('filter');
		$view = "pdf/report_arus_kas_pdf";
		$dt = "";
		$sa = "";
		$judul = "";

		

		if($filter == "Tahunan"){
			$tahun_sel = $this->input->post('tahun_sel');
			$tahun = $this->input->post('tahun_sel');
			$judul = "Tahun $tahun_sel";

			if($tahun_sel == ""){
				$tahun_sel = date('Y');
			}

			$tahun_sel_saldo = $tahun_sel - 1;
			$tahun_saldo = $tahun_sel - 1;

			//$sa = $this->model->get_sa_tahunan($id_klien, $tahun_sel_saldo)->TOTAL_SA;
			//$dt = $this->model->cetak_arus_kas_tahunan($id_klien, $tahun_sel);

			$sa_lalu = $this->model->get_sa_tahun_lalu($id_klien, 0, $tahun_saldo)->TOTAL_SA;
			$sa_skrg = $this->model->get_sa_tahun_skrg($id_klien, 0, $tahun)->TOTAL_SA;

			$cek_saldo_lalu = $this->model->cek_saldo_awal_bulan_lalu($id_klien, 0, $tahun_saldo);
			if(count($cek_saldo_lalu) == 0){
				$this->model->simpan_saldo_awal_arus_kas($id_klien, 0, $tahun_saldo, $sa_lalu, 'TAHUNAN');
			}

			$get_saldo_awal_sebelumnya = $this->model->get_saldo_awal_sebelumnya($id_klien, 0, $tahun_saldo, 'TAHUNAN')->NILAI;
			$nilai_saldo_skrg = $get_saldo_awal_sebelumnya + $sa_skrg;
			$this->model->update_saldo_skrg($id_klien, 0, $tahun, $nilai_saldo_skrg, 'TAHUNAN');

			$sa = $this->model->get_saldo_awal_sebelumnya($id_klien, 0, $tahun_saldo, 'TAHUNAN')->NILAI;
			$dt = $this->model->cetak_arus_kas_tahunan($id_klien, $tahun_sel);
		
		} else if($filter == "Bulanan"){
			$tahun = $this->input->post('tahun');
			$bulan = $this->input->post('bulan');
			$bln_txt = $this->datetostr($bulan);
			$judul = "Bulan $bln_txt Tahun $tahun";

			$bulan_saldo = $bulan - 1;
			$tahun_saldo = $tahun;

			if($bulan_saldo == 0){
				$bulan_saldo = 12;
				$tahun_saldo = $tahun - 1;
			}

			$bulan_saldo = str_pad($bulan_saldo, 2, '0', STR_PAD_LEFT);

			$sa_lalu = $this->model->get_sa_bulanan_lalu($id_klien, $bulan_saldo, $tahun_saldo)->TOTAL_SA;
			$sa_skrg = $this->model->get_sa_bulanan_skrg($id_klien, $bulan, $tahun)->TOTAL_SA;

			$cek_saldo_lalu = $this->model->cek_saldo_awal_bulan_lalu($id_klien, $bulan_saldo, $tahun_saldo);
			if(count($cek_saldo_lalu) == 0){
				$this->model->simpan_saldo_awal_arus_kas($id_klien, $bulan_saldo, $tahun_saldo, $sa_lalu, 'BULANAN');
			}

			$get_saldo_awal_sebelumnya = $this->model->get_saldo_awal_sebelumnya($id_klien, $bulan_saldo, $tahun_saldo, 'BULANAN')->NILAI;
			$nilai_saldo_skrg = $get_saldo_awal_sebelumnya + $sa_skrg;
			$this->model->update_saldo_skrg($id_klien, $bulan, $tahun, $nilai_saldo_skrg, 'BULANAN');

			$sa = $this->model->get_saldo_awal_sebelumnya($id_klien, $bulan_saldo, $tahun_saldo, 'BULANAN')->NILAI;
			$dt = $this->model->cetak_arus_kas_bulanan($id_klien, $bulan, $tahun);
		} 



		$data = array(
			'title' 		=> 'LAPORAN LABA RUGI ',
			'title2'		=> 'SEMUA BAGIAN',
			'data'			=> $dt,
			'sa'			=> $sa,
			'judul'			=> $judul,
			'data_usaha'    => $this->master_model_m->data_usaha($id_klien),
		);
		$this->load->view($view,$data);
	}

	function cetak_arus_kas_xls(){
		$sess_user = $this->session->userdata('masuk_rs');
		$id_klien = $sess_user['id_klien'];

		//$tgl   = $this->input->post('tgl');
		
		$filter = $this->input->post('filter');
		$view = "xls/report_arus_kas_xls";
		$dt = "";
		$sa = "";
		$judul = "";

		

		if($filter == "Tahunan"){
			$tahun_sel = $this->input->post('tahun_sel');
			$tahun = $this->input->post('tahun_sel');
			$judul = "Tahun $tahun_sel";

			if($tahun_sel == ""){
				$tahun_sel = date('Y');
			}

			$tahun_sel_saldo = $tahun_sel - 1;
			$tahun_saldo = $tahun_sel - 1;

			//$sa = $this->model->get_sa_tahunan($id_klien, $tahun_sel_saldo)->TOTAL_SA;
			//$dt = $this->model->cetak_arus_kas_tahunan($id_klien, $tahun_sel);

			$sa_lalu = $this->model->get_sa_tahun_lalu($id_klien, 0, $tahun_saldo)->TOTAL_SA;
			$sa_skrg = $this->model->get_sa_tahun_skrg($id_klien, 0, $tahun)->TOTAL_SA;

			$cek_saldo_lalu = $this->model->cek_saldo_awal_bulan_lalu($id_klien, 0, $tahun_saldo);
			if(count($cek_saldo_lalu) == 0){
				$this->model->simpan_saldo_awal_arus_kas($id_klien, 0, $tahun_saldo, $sa_lalu, 'TAHUNAN');
			}

			$get_saldo_awal_sebelumnya = $this->model->get_saldo_awal_sebelumnya($id_klien, 0, $tahun_saldo, 'TAHUNAN')->NILAI;
			$nilai_saldo_skrg = $get_saldo_awal_sebelumnya + $sa_skrg;
			$this->model->update_saldo_skrg($id_klien, 0, $tahun, $nilai_saldo_skrg, 'TAHUNAN');

			$sa = $this->model->get_saldo_awal_sebelumnya($id_klien, 0, $tahun_saldo, 'TAHUNAN')->NILAI;
			$dt = $this->model->cetak_arus_kas_tahunan($id_klien, $tahun_sel);
		
		} else if($filter == "Bulanan"){
			$tahun = $this->input->post('tahun');
			$bulan = $this->input->post('bulan');
			$bln_txt = $this->datetostr($bulan);
			$judul = "Bulan $bln_txt Tahun $tahun";

			$bulan_saldo = $bulan - 1;
			$tahun_saldo = $tahun;

			if($bulan_saldo == 0){
				$bulan_saldo = 12;
				$tahun_saldo = $tahun - 1;
			}

			$bulan_saldo = str_pad($bulan_saldo, 2, '0', STR_PAD_LEFT);

			$sa_lalu = $this->model->get_sa_bulanan_lalu($id_klien, $bulan_saldo, $tahun_saldo)->TOTAL_SA;
			$sa_skrg = $this->model->get_sa_bulanan_skrg($id_klien, $bulan, $tahun)->TOTAL_SA;

			$cek_saldo_lalu = $this->model->cek_saldo_awal_bulan_lalu($id_klien, $bulan_saldo, $tahun_saldo);
			if(count($cek_saldo_lalu) == 0){
				$this->model->simpan_saldo_awal_arus_kas($id_klien, $bulan_saldo, $tahun_saldo, $sa_lalu, 'BULANAN');
			}

			$get_saldo_awal_sebelumnya = $this->model->get_saldo_awal_sebelumnya($id_klien, $bulan_saldo, $tahun_saldo, 'BULANAN')->NILAI;
			$nilai_saldo_skrg = $get_saldo_awal_sebelumnya + $sa_skrg;
			$this->model->update_saldo_skrg($id_klien, $bulan, $tahun, $nilai_saldo_skrg, 'BULANAN');

			$sa = $this->model->get_saldo_awal_sebelumnya($id_klien, $bulan_saldo, $tahun_saldo, 'BULANAN')->NILAI;
			$dt = $this->model->cetak_arus_kas_bulanan($id_klien, $bulan, $tahun);
		} 



		$data = array(
			'title' 		=> 'LAPORAN LABA RUGI ',
			'title2'		=> 'SEMUA BAGIAN',
			'data'			=> $dt,
			'sa'			=> $sa,
			'judul'			=> $judul,
			'data_usaha'    => $this->master_model_m->data_usaha($id_klien),
		);
		$this->load->view($view,$data);
	}


	function cari_kode(){
		$sess_user = $this->session->userdata('masuk_rs');
		$id_klien = $sess_user['id_klien'];

		$keyword = $this->input->get('keyword');
		$dt = $this->model->get_no_akun($keyword, $id_klien);

		echo json_encode($dt);
	}

	function cari_kode_by_id(){
		$id = $this->input->get('id');
		$dt = $this->model->cari_kode_by_id($id);

		echo json_encode($dt);
	}

	function datetostr($var){

		 if($var == "01"){
		 	$var = "Januari";
		 } else if($var == "02"){
		 	$var = "Februari";
		 } else if($var == "03"){
		 	$var = "Maret";
		 } else if($var == "04"){
		 	$var = "April";
		 } else if($var == "05"){
		 	$var = "Mei";
		 } else if($var == "06"){
		 	$var = "Juni";
		 } else if($var == "07"){
		 	$var = "Juli";
		 } else if($var == "08"){
		 	$var = "Agustus";
		 } else if($var == "09"){
		 	$var = "September";
		 } else if($var == "10"){
		 	$var = "Oktober";
		 } else if($var == "11"){
		 	$var = "November";
		 } else if($var == "12"){
		 	$var = "Desember";
		 }

		 return $var;

	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
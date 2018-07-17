<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lap_laba_rugi_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){ 
	        redirect('../../');
	    }
	    $this->load->model('lap_laba_rugi_m','model');
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
			$tipe_laporan = $this->input->post('tipe_laporan');
			if($tipe_laporan == "Tdk_Rinci"){
				$this->cetak_laba_rugi_tdk_rinci('pdf');
			} else if($tipe_laporan == "Rinci"){
				$this->cetak_laba_rugi_rinci('pdf');
			}
		} else if($this->input->post('excel')){
			$tipe_laporan = $this->input->post('tipe_laporan');
			if($tipe_laporan == "Tdk_Rinci"){
				$this->cetak_laba_rugi_tdk_rinci('xls');
			} else if($tipe_laporan == "Rinci"){
				$this->cetak_laba_rugi_rinci('xls');
			}
		} 

		//$dt = $this->model->get_no_akun($keyword, $id_klien);

		$data =  array(
			'page' => "lap_laba_rugi_v", 
			'title' => "Laporan Laba Rugi", 
			'msg' => "", 
			'master' => "laporan", 
			'view' => "laba_rugi", 
			//'dt' => $dt, 
			'msg' => $msg, 
			'post_url' => 'lap_laba_rugi_c', 
		);
		
		$this->load->view('beranda_v', $data);
	}

	function cetak_laba_rugi_tdk_rinci($tipe_laporan){
		$sess_user = $this->session->userdata('masuk_rs');
		$id_klien = $sess_user['id_klien'];

		//$tgl   = $this->input->post('tgl');
		
		$filter = $this->input->post('filter');
		if($tipe_laporan == "pdf"){
			$view = "pdf/report_laba_rugi_pdf";
		} else if($tipe_laporan == "xls"){
			$view = "xls/report_laba_rugi_xls";
		}
		$dt = "";
		$judul = "";

		

		if($filter == "Harian"){
			$tgl_full = $this->input->post('tgl');
			if($tgl_full == ""){
				$tgl_full = date('d-m-Y')." sampai ".date('d-m-Y');
			}
			
			$tgl = explode(' sampai ', $tgl_full);
			$tgl_awal = $tgl[0];
			$tgl_akhir = $tgl[1];
			$judul = "Tanggal $tgl_awal s/d $tgl_akhir";

			$dt = $this->model->cetak_laba_rugi($id_klien, $tgl_awal, $tgl_akhir);
		
		} else if($filter == "Bulanan"){
			$tahun = $this->input->post('tahun');
			$bulan = $this->input->post('bulan');
			$bln_txt = $this->datetostr($bulan);
			$judul = "Bulan $bln_txt Tahun $tahun";

			$dt = $this->model->cetak_laba_rugi_bulanan($id_klien, $bulan, $tahun);
		} 



		$data = array(
			'title' 		=> 'LAPORAN LABA RUGI ',
			'title2'		=> 'SEMUA BAGIAN',
			'data'			=> $dt,
			'judul'			=> $judul,
			'data_usaha'    => $this->master_model_m->data_usaha($id_klien),
		);
		$this->load->view($view,$data);
	}

	function cetak_laba_rugi_rinci($tipe_laporan){
		$sess_user = $this->session->userdata('masuk_rs');
		$id_klien = $sess_user['id_klien'];

		//$tgl   = $this->input->post('tgl');
		
		$filter = $this->input->post('filter');
		if($tipe_laporan == "pdf"){
			$view = "pdf/report_laba_rugi_rinci_pdf";
		} else if($tipe_laporan == "xls"){
			$view = "xls/report_laba_rugi_rinci_xls";
		}

		$dt = "";
		$judul = "";
		$tgl_awal = "";
		$tgl_akhir = "";
		$bulan = "";
		$tahun = "";		

		if($filter == "Harian"){
			$tgl_full = $this->input->post('tgl');
			if($tgl_full == ""){
				$tgl_full = date('d-m-Y')." sampai ".date('d-m-Y');
			}
			
			$tgl = explode(' sampai ', $tgl_full);
			$tgl_awal = $tgl[0];
			$tgl_akhir = $tgl[1];
			$judul = "Tanggal $tgl_awal s/d $tgl_akhir";

			$dt = $this->model->cetak_laba_rugi($id_klien, $tgl_awal, $tgl_akhir);
		
		} else if($filter == "Bulanan"){
			$tahun = $this->input->post('tahun');
			$bulan = $this->input->post('bulan');
			$bln_txt = $this->datetostr($bulan);
			$judul = "Bulan $bln_txt Tahun $tahun";

			$dt = $this->model->cetak_laba_rugi_bulanan($id_klien, $bulan, $tahun);
		} 

		$data = array(
			'title' 		=> 'LAPORAN LABA RUGI ',
			'title2'		=> 'SEMUA BAGIAN',
			'data'			=> $dt,
			'filter'		=> $filter,
			'judul'			=> $judul,
			'tgl_awal'		=> $tgl_awal,
			'tgl_akhir'		=> $tgl_akhir,
			'bulan'			=> $bulan,
			'tahun'			=> $tahun,
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
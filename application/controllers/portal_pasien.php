<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Portal_pasien extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->library("Uploader");
		$this->load->library("excel_reader2");
	}

	function index()
	{
		$data = array(
			'title' => 'Sistem Informasi Rumah Sakit',
			'master_menu' => 'dashboard',
			'subtitle' => 'Dashboard'
		);

		$this->load->view('portal_pasien_v',$data);
	}

	function import(){
		$excel = new Spreadsheet_Excel_Reader($_FILES['userfile']['tmp_name']);
		$hasildata = $excel->rowcount($sheet_index=0);
		// default nilai 
		$sql = "";
		for ($i=8; $i<=$hasildata; $i++){
			$isi_a = $excel->val($i,8);
			$isi_b = $excel->val($i,9);
			$isi_c = $excel->val($i,10);
			$isi_d = $excel->val($i,11);
			$isi_e = $excel->val($i,12);
			$id_offline = $excel->val($i,15);
			$this->model->update_offline($tahun_offline,$isi_a,$isi_b,$isi_c,$isi_d,$isi_e,$id_offline);
		}
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
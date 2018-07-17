<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Upload_absensi_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){
	        redirect(base_url());
	    }
		$this->load->model('absensi/upload_absensi_m', 'model');
		$this->load->helper('url');
        $this->load->helper('form');
        $this->load->helper('file');

        error_reporting(0);
	}

	function index()
	{

		$msg = 0;
		$warning = 0;
		$alert = 0;
		$thn = date('Y');
		$tahun_aktif = date('Y');
		$bln = date('m');

		if($this->input->post('simpan')){
			
			$bln = $this->input->post('bulan');
			$thn = $this->input->post('tahun');
			$config['upload_path'] = './temp_upload/';
		    $config['allowed_types'] = 'xls';
                
		    $this->load->library('upload', $config);
                

			if ( ! $this->upload->do_upload()) {
				$alert = 1;
				
			} else {
				$msg = 1;

				$this->model->delete_all_absensi($bln, $thn);
				$last = date('t', strtotime('15-$bln-$thn'));
				$tgl_1 = "";

				

	            $data = array('error' => false);
				$upload_data = $this->upload->data();

	            $this->load->library('excel_reader');
				$this->excel_reader->setOutputEncoding('CP1251');

				$file =  $upload_data['full_path'];
				$this->excel_reader->read($file);
				error_reporting(E_ALL ^ E_NOTICE);

				$nik_peg = $this->model->get_nik_pegawai();

				for ($i=1; $i < $last + 1 ; $i++) { 
				 	$tgl_1 = $this->get_nol($i)."/".$this->get_nol($bln)."/".$thn;
				 	$day1  = $this->get_nol($bln)."/".$this->get_nol($i)."/".$thn;

					$day = date('l', strtotime($day1));
					
					if($day != 'Saturday' && $day != 'Sunday'){
					 	foreach ($nik_peg as $key => $nik_row) {
					 		$this->model->simpan_default_tgl($nik_row->NIP, $nik_row->NAMA, $tgl_1, $bln, $thn, 0);
					 	}
				 	}
				}

				// Sheet 1
				$data = $this->excel_reader->sheets[0] ;
	            $dataexcel = Array();


				for ($i = 1; $i <= $data['numRows']; $i++) {

                    if($data['cells'][$i][1] == '') break;
                    $dataexcel[$i-2]['nik'] = $data['cells'][$i][1];
                    $dataexcel[$i-2]['nama_pegawai'] = $data['cells'][$i][2];
                    $dataexcel[$i-2]['tanggal'] = $data['cells'][$i][3];
                    $dataexcel[$i-2]['jam'] = $data['cells'][$i][4];

				}
	                        
	                        
	            delete_files($upload_data['file_path']);
	            $this->model->simpan_absensi($dataexcel, $bln, $thn);
			}
		} 

		$dt = "";

		$data = array(
			'page' => 'absensi/upload_absensi_v',
			'title' => 'Upload Absensi',
			'subtitle' => 'Upload Absensi',
			'master_menu' => 'absen',
			'view' => 'upload_absen',
			'warning' => $warning,
			'dt' => $dt,
			'msg' => $msg,
			'tahun_aktif' => $tahun_aktif,
			'bln' => $bln,
			'post_url' => 'absensi/upload_absensi_c',
		);

		$this->load->view('absensi/absensi_master_home_v',$data);
	}

	function get_nol($val){
		if($val == 1){
			$val = '01';
		} else if($val == 2){
			$val = '02';
		} else if($val == 3){
			$val = '03';
		} else if($val == 4){
			$val = '04';
		} else if($val == 5){
			$val = '05';
		} else if($val == 6){
			$val = '06';
		} else if($val == 7){
			$val = '07';
		} else if($val == 8){
			$val = '08';
		} else if($val == 9){
			$val = '09';
		}

		return $val;
	}



}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
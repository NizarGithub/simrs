<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Generate_gaji_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$sess_user = $this->session->userdata('masuk_rs'); 
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){
	        redirect(base_url());
	    }
		$this->load->model('kepeg/generate_gaji_m', 'model');
	} 

	function index()
	{

		$msg = 0;
		$warning = 0;

		if($this->input->post('simpan')){
			
			$kode_tunj = addslashes($this->input->post('kode_tunj'));


		} else if($this->input->post('ubah')){

			$msg = 2;
			$id_tunjangan = $this->input->post('id_tunjangan');
			$ed_nama_tunj   = addslashes($this->input->post('ed_nama_tunj'));
			$ed_uraian     = addslashes($this->input->post('ed_uraian'));

			$this->model->ubah_tunjangan($id_tunjangan, $ed_nama_tunj, $ed_uraian);

		}

		$dt = $this->model->get_data_tunjangan();

		$data = array(
			'page' => 'kepeg/generate_gaji_v',
			'title' => 'Generate Gaji Pegawai',
			'subtitle' => 'Generate Gaji Pegawai',
			'master_menu' => 'gaji',
			'view' => 'generate_gaji',
			'warning' => $warning,
			'dt' => $dt,
			'msg' => $msg,
			'post_url' => 'kepeg/generate_gaji_c', 
		);

		$this->load->view('kepeg/kepeg_master_home_v',$data);
	}

	function generate_gaji(){
		$bln = $this->input->post('bln');
		$thn = $this->input->post('thn');

		$this->model->delete_gaji_pegawai_all($bln, $thn);
		$get_all_pegawai = $this->model->get_all_pegawai();

		$cek_thr = $this->model->cek_thr($bln, $thn);

		foreach ($get_all_pegawai as $key => $peg) {
			$cek_gaji_di_peg = $this->model->cek_gaji_di_peg($peg->ID);
			$cek_gaji_di_jab = $this->model->cek_gaji_di_jab($peg->ID_JABATAN);
			
			$gapok = 0;			
			$thr = 0;			

			if($peg->ID_PANGKAT == null || $peg->ID_PANGKAT == "" || $peg->ID_PANGKAT == 0){
				$gapok = 0;
				$thr = 0;
			} else {
				$gapok = $this->model->get_gapok_pegawai($peg->ID_PANGKAT)->GAPOK;
				$thr   = $this->model->get_gapok_pegawai($peg->ID_PANGKAT)->THR;
			}

			$this->model->simpan_gaji(1, $peg->ID, 'GAPOK', $gapok, $bln, $thn);
			
			if(count($cek_thr) > 0){
				$this->model->simpan_gaji(1, $peg->ID, 'THR', $thr, $bln, $thn);
			}

			foreach ($cek_gaji_di_jab as $key => $gaji_1) {
				$this->model->simpan_gaji($gaji_1->ID_GAJI, $peg->ID, $gaji_1->NAMA_GAJI, $gaji_1->NILAI, $bln, $thn);	
			}
			
		}

		echo json_encode('OK');
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
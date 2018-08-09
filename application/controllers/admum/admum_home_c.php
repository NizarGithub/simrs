<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admum_home_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){
	        redirect(base_url());
	    }
	}

	function index()
	{
		$data = array(
			'page' => 'admum/admum_beranda_v',
			'title' => 'Administrasi Umum',
			'subtitle' => 'Administrasi Umum',
			'childtitle' => '',
			'master_menu' => 'home',
			'view' => 'home',
		);

		$this->load->view('admum/admum_home_v',$data);
	}

	function get_user_info(){
		$id_user = $this->input->post('id_user');
		$data = $this->master_model_m->get_user_info($id_user);
		echo json_encode($data);
	}

	function is_antrian(){
		$id_user = $this->input->post('id_user');
		$data['loket'] = $this->master_model_m->getLoket($id_user, 'admission');

		echo json_encode($data);
	}

	function is_jumlah_antri(){
		$tanggal = date('d-m-Y');
		$sql = "SELECT COUNT(*) AS TOTAL FROM kepeg_antrian WHERE TGL = '$tanggal'";
		$qry = $this->db->query($sql);
		$total = $qry->row()->TOTAL;
		$urut[] = '';

		if($total != 0){
			$s = "SELECT * FROM kepeg_antrian WHERE TGL = '$tanggal'";
			$q = $this->db->query($s);
			$data = $q->row();
			$nomor = $data->URUT+1;
			$kode = $data->KODE;
			$urut['no'] = $nomor;
			$urut['kode'] = $kode;
			$urut['tampil'] = $kode.'-'.$nomor;
		}else{
			$urut['no'] = '1';
			$urut['kode'] = 'A';
			$urut['tampil'] = 'A-1';
		}
		echo json_encode($urut);
	}

	function next_antri(){
		$kode_antrian = $this->input->post('kode_antrian');
		$jml_antrian  = $this->input->post('jml_antrian');
		$id_antrian   = $this->input->post('id_antrian');

		$this->model->simpanAntrian($kode_antrian, $jml_antrian, $id_antrian);

		echo json_encode(1);
	}

} 

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
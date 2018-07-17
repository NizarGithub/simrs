<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setup_jam_denda_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){
	        redirect(base_url());
	    }
		$this->load->model('absensi/setup_jam_denda_m', 'model');
	}

	function index()
	{

		$msg = 0;
		$warning = 0;
		$thn = date('Y');
		$tahun_aktif = date('Y');
		$bln = date('m');

		if($this->input->post('simpan')){
			
			$msg = 1;

			$jam_masuk = $this->input->post('jam_masuk');
			$jam_awal  = $this->input->post('jam_awal');
			$jam_akhir = $this->input->post('jam_akhir');
			$denda     = $this->input->post('denda');

			$this->model->delete_all_denda();

			$this->model->simpan_jam_masuk($jam_masuk);

			foreach ($jam_awal as $key => $val) {
				$this->model->simpan_denda_telat($val, $jam_akhir[$key], $denda[$key]);
			}
		} 

		$dt = $this->model->get_jam_denda();
		$jam_masuk = $this->model->get_jam_masuk();

		$data = array(
			'page' => 'absensi/setup_jam_denda_v',
			'title' => 'Setup Jam Masuk & Denda',
			'subtitle' => 'Setup Jam Masuk & Denda',
			'master_menu' => 'master_setup',
			'view' => 'jam_denda',
			'warning' => $warning,
			'dt' => $dt,
			'jam_masuk' => $jam_masuk,
			'msg' => $msg,
			'tahun_aktif' => $tahun_aktif,
			'bln' => $bln,
			'post_url' => 'absensi/setup_jam_denda_c',
		);

		$this->load->view('absensi/absensi_master_home_v',$data);
	}

	function date_range($first, $last, $step = '+1 day', $output_format = 'd/m/Y' ) {

	    $dates = array();
	    $current = strtotime($first);
	    $last = strtotime($last);

	    while( $current <= $last ) {

	        $dates[] = date($output_format, $current);
	        $current = strtotime($step, $current);
	    }

	    return $dates;
	}

	function get_data_libur(){
		$id = $this->input->post('id');
		$sql = "
        SELECT * FROM abs_setup_libur WHERE ID = $id
        ";

        $data =  $this->db->query($sql)->row();

        echo json_encode($data);
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
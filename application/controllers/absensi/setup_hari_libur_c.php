<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setup_hari_libur_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){
	        redirect(base_url());
	    }
		$this->load->model('absensi/setup_hari_libur_m', 'model');
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

			$tgl_libur = $this->input->post('tgl_libur');

			$tgl_full = explode(" sampai ", $tgl_libur);
			$tgl_awal  = $tgl_full[0]; // piece1
			$tgl_akhir = $tgl_full[1]; // piece2


			$ket = addslashes($this->input->post('keterangan'));
			$tgl_arr = $this->date_range($tgl_awal, $tgl_akhir);			

			for ($x = 0; $x < count($tgl_arr); $x++) {
				$tgl_pecah  = $tgl_arr[$x];
				$pecah1 = explode("/", $tgl_pecah);
			    $bln_pecah = $pecah1[1];
			    $thn_pecah  = $pecah1[2];
				$this->model->simpan_tgl_libur($tgl_arr[$x], $bln_pecah, $thn_pecah, $ket);
			}

		} else if($this->input->post('ubah')){

			$msg = 2;
			$id_libur = $this->input->post('id_libur');
			$bln = $this->input->post('ed_bln');
			$thn = $this->input->post('ed_thn');
			$ed_ket     = addslashes($this->input->post('ed_ket'));

			$this->model->ubah_libur($id_libur, $ed_ket);

		} else if($this->input->post('id_hapus')){

			$msg = 3;
			$id_hapus   = $this->input->post('id_hapus');
			$this->model->hapus_libur($id_hapus);
		}

		$dt = $this->model->get_data_libur($thn, $bln);
		
		if($this->input->post('cari')){
			$bln = $this->input->post('bulan');
			$tahun_aktif = $this->input->post('tahun');

			$dt = $this->model->get_data_libur($tahun_aktif, $bln);
		}

		$data = array(
			'page' => 'absensi/setup_hari_libur_v',
			'title' => 'Setup Hari Libur',
			'subtitle' => 'Setup Hari Libur',
			'master_menu' => 'master_setup',
			'view' => 'hari_libur',
			'warning' => $warning,
			'dt' => $dt,
			'msg' => $msg,
			'tahun_aktif' => $tahun_aktif,
			'bln' => $bln,
			'post_url' => 'absensi/setup_hari_libur_c',
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
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mutasi_pegawai_c extends CI_Controller { 

	function __construct()
	{
		parent::__construct();
		$sess_user = $this->session->userdata('masuk_rs'); 
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){
	        redirect(base_url());
	    }
		$this->load->model('kepeg/mutasi_pegawai_m', 'model');
	}
 
	function index()
	{

		$msg = 0;
		$warning = 0;
		$bln = date('m');
		$thn = date('Y');

		if($this->input->post('simpan')){
			$msg = 1;

			// SIMPAH HISTORY MUTASI
			$id_pegawai        = $this->input->post('id_pegawai');
			$jabatan_lama      = $this->input->post('jabatan_lama');
			$sk_jabatan_lama   = $this->input->post('sk_jabatan_lama');
			$departemen_lama   = $this->input->post('departemen_lama');
			$divisi_lama       = $this->input->post('divisi_lama');
			$tgl_akhir_jabatan = $this->input->post('tgl_akhir_jabatan');
			$this->model->simpanMutasi($id_pegawai, $jabatan_lama, $sk_jabatan_lama, $departemen_lama, $divisi_lama, $tgl_akhir_jabatan);

			//UBAH TABEL PEGAWAI
			$sk_jabatan         = $this->input->post('sk_jabatan');
			$tgl_sk_jabatan     = $this->input->post('tgl_sk_jabatan');
			$status             = $this->input->post('status');
			$jabatan_baru       = $this->input->post('jabatan_baru');
			$departemen_baru    = $this->input->post('departemen_baru');
			$divisi_baru        = $this->input->post('divisi_baru');
			$tgl_awal_jab_baru  = $this->input->post('tgl_awal_jab_baru');
			$tgl_akhir_jab_baru = $this->input->post('tgl_akhir_jab_baru');

			$this->model->ubahDataPeg($id_pegawai, $sk_jabatan, $tgl_sk_jabatan, $status, $jabatan_baru, $departemen_baru, $divisi_baru, $tgl_awal_jab_baru, $tgl_akhir_jab_baru);

		}
		
		$dt = "";

		$data = array(
			'page' => 'kepeg/mutasi_pegawai_v',
			'title' => 'Mutasi Pegawai',
			'subtitle' => 'Mutasi Pegawai',
			'master_menu' => 'pegawai_menu',
			'view' => 'mutasi_pegawai',
			'warning' => $warning,
			'dt' => $dt,
			'bln' => $bln,
			'tahun_aktif' => $thn,
			'msg' => $msg,
			'get_departemen' => $this->model->get_departemen(),
			'get_jabatan' => $this->model->get_jabatan(),
			'post_url' => 'kepeg/mutasi_pegawai_c', 
		);

		$this->load->view('kepeg/kepeg_master_home_v',$data);
	}

	function get_detail_gaji(){
		$id_pegawai = $this->input->post('id_pegawai');
		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');
		$data = $this->model->get_gaji_pegawai_detail($id_pegawai, $bulan, $tahun);

		echo json_encode($data);
	}

	function get_data_pegawai(){
		$id = $this->input->post('id');

		$sql = "
		SELECT a.*, b.NAMA AS JABATAN, c.NAMA_DEP, d.NAMA_DIV FROM kepeg_pegawai a 
		LEFT JOIN kepeg_kel_jabatan b ON a.ID_JABATAN = b.ID
		LEFT JOIN kepeg_departemen c ON a.ID_DEPARTEMEN = c.ID
		LEFT JOIN kepeg_divisi d ON a.ID_DIVISI = d.ID
		WHERE a.ID = $id
		";

		$dt = $this->db->query($sql)->row();

		echo json_encode($dt);
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
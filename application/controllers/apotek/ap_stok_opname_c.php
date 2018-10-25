<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ap_stok_opname_c extends CI_Controller {

	function __construct()
	{
		//1 kg = 1000 ml
		parent::__construct();
		$this->load->model('apotek/ap_stok_opname_m','model');
		$sess_user = $this->session->userdata('masuk_rs');
    	$id_user = $sess_user['id'];
	    if($id_user == "" || $id_user == null){
	        redirect('portal');
	    }
	}

	function index(){

		$data = array(
			'page' => 'apotek/ap_stok_opname_v',
			'title' => 'Stok Opname',
			'subtitle' => 'Stok Opname',
			'master_menu' => 'stok_opname',
			'view' => 'stok_opname',
			'url_simpan' => base_url().'apotek/ap_stok_opname_c/simpan',
			'url_ubah' => base_url().'apotek/ap_stok_opname_c/ubah',
			'url_hapus' => base_url().'apotek/ap_stok_opname_c/hapus',
			'url_cetak' => base_url().'apotek/ap_stok_opname_c/cetak_excel',
			'url_simpan_obat' => base_url().'apotek/ap_stok_opname_c/simpan_obat',
		);

		$this->load->view('apotek/ap_home_v',$data);
	}

  function get_data_stok(){
    $data = $this->model->get_data_stok();

    echo json_encode($data);
  }

	function get_data_preview(){
    $data = $this->model->get_data_preview();

    echo json_encode($data);
  }

	function data_detail_opname(){
		$keyword = $this->input->get('keyword');
		$tanggal = $this->input->get('tanggal');
		$data = $this->model->data_detail_opname($keyword, $tanggal);

		echo json_encode($data);
	}

  function simpan_stok_opname(){
    $id_gudang_obat = $this->input->post('id_gudang_obat');
    $id_setup_nama_obat = $this->input->post('id_setup_nama_obat');
    $stok_sistem = $this->input->post('stok_sistem');
    $stok_fisik = $this->input->post('stok_fisik');

    foreach ($id_gudang_obat as $key => $value) {
      $this->model->simpan_stok_opname($value, $id_setup_nama_obat[$key], $stok_sistem[$key], $stok_fisik[$key]);
    }

    echo '1';
  }
}

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Billing_home_c extends CI_Controller {

	function __construct() 
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->library('fpdf/HTML2PDF');
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){
	        redirect(base_url());
	    }

	    $this->load->model("billing/billing_home_m", "model");
	} 

	function index()
	{

		$dt = $this->model->getBillingPasien();
		$dt_rj = $this->model->getBillingPasien_2('RJ');
		$dt_ri = $this->model->getBillingPasien_2('RI');
		$dt_igd = $this->model->getBillingPasien_2('IGD');
		$filter_by = "semua";
 
		if($this->input->post('filter_by')){
			$filter_by = $this->input->post('filter_by');
			$dt = $this->model->getBillingPasien_filter($filter_by);
		} 

		$data = array(
			'page' => 'billing/billing_home_v',
			'title' => 'Billing / Info Pembayaran',
			'subtitle' => 'Billing / Info Pembayaran',
			'master_menu' => 'home',
			'view' => 'home',
			'msg' => '',
			'dt'  => $dt,
			'dt_rj'  => $dt_rj,
			'dt_ri'  => $dt_ri,
			'dt_igd'  => $dt_igd,
			'filter_by'  => $filter_by,
		);

		$this->load->view('billing/billing_master_home_v',$data);
	}

	function bayar($sts, $id_pasien){

		if($sts == "RJ"){
			$dt  = $this->model->getDetailPasien_RJ($id_pasien);
			$dt2 = "";
			$url_cetak = base_url().'billing/billing_home_c/cetakBillingRJ';
			$ket = "Rawat Jalan";
		} else if ($sts == "RI"){
			$dt  = $this->model->getDetailPasien_RI($id_pasien);
			$dt2 = $this->model->dataDetPasien_RI($id_pasien);
			$url_cetak = base_url().'billing/billing_home_c/cetakBillingRI';
			$ket = "Rawat Inap";
		} else if ($sts == "IGD"){
			$dt  = $this->model->getDetailPasien_IGD($id_pasien);
			$dt2 = "";
			$url_cetak = base_url().'billing/billing_home_c/cetakBillingIGD';
			$ket = "IGD";
		}
		

		$data = array(
			'page' => 'billing/billing_bayar_v',
			'title' => 'Pembayaran',
			'subtitle' => 'Pembayaran',
			'post_url' => 'billing/billing_home_c/proses_bayar/'.$sts.'/'.$id_pasien,
			'msg' => '',
			'dt'  => $dt,
			'dt2'  => $dt2,
			'url_cetak'  => $url_cetak,
			'sts'  => $sts,
			'ket'  => $ket,
			'id_pasien'  => $id_pasien,
			'nomor_bill' => $this->model->getNomorBilling(),
		);

		$this->load->view('billing/billing_bayar_v',$data);
	}

	function detail($sts, $id_pasien){
		$ket = "";
		$view = "";

		if($sts == "RJ"){
			$dt = $this->model->getDetailPasien_RJ($id_pasien);
			$dt2 = "";
			$ket = "Rawat Jalan";
			$view = "billing/billing_detail_v";
			$url_cetak = base_url().'billing/billing_home_c/cetakBillingRJ';
		} else if ($sts == "RI"){
			$dt = $this->model->getDetailPasien_RI($id_pasien);
			$dt2 = $this->model->dataDetPasien_RI($id_pasien);
			$ket = "Rawat Inap";
			$view = "billing/billing_detail_ri_v";
			$url_cetak = base_url().'billing/billing_home_c/cetakBillingRI';
		} else if ($sts == "IGD"){
			$dt = $this->model->getDetailPasien_IGD($id_pasien);
			$dt2 = "";
			$ket = "IGD";
			$view = "billing/billing_detail_igd_v";
			$url_cetak = base_url().'billing/billing_home_c/cetakBillingIGD';
		}

		$data = array(
			'page' => 'billing/billing_detail_v',
			'title' => 'Detail Pembayaran',
			'subtitle' => 'Detail Pembayaran',
			'post_url' => 'billing/billing_home_c/proses_bayar/'.$sts.'/'.$id_pasien,
			'msg' => '',
			'dt'  => $dt,
			'dt2' => $dt2,
			'sts'  => $sts,
			'ket' => $ket,
			'id_pasien'  => $id_pasien,
			'nomor_bill' => $this->model->getNomorBilling(),
			'url_cetak' => $url_cetak,
		);

		$this->load->view($view,$data);
	}

	function proses_bayar($sts, $id_pasien){
		$id_pasien    = $this->input->post('id_pasien');
		$nomor_bill   = $this->input->post('nomor_bill');
		$sts_layanan  = $this->input->post('sts_layanan');
		$sistem_bayar = $this->input->post('sistem_bayar');
		$total_biaya  = $this->input->post('total_biaya');
		$bayar        = str_replace(',', '', $this->input->post('bayar'));
		$kembali      = $this->input->post('kembali');
		
		$this->model->simpan_pembayaran($id_pasien, $nomor_bill, $sts_layanan, $sistem_bayar, $total_biaya, $bayar, $kembali);
		$this->model->roll_no_billing();
		$this->model->update_sts_bayar_pasien($id_pasien);

		if($sts == "RJ"){
			$dt  = $this->model->getDetailPasien_RJ($id_pasien);
			$dt2 = "";
		} else if ($sts == "RI"){
			$dt  = $this->model->getDetailPasien_RI($id_pasien);
			$dt2 = $this->model->dataDetPasien_RI($id_pasien);
		} else if ($sts == "IGD"){
			$dt  = $this->model->getDetailPasien_IGD($id_pasien);
			$dt2 = "";
		}

		$data = array(
			'title' => 'Cetak Pembayaran',
			'subtitle' => 'Cetak Pembayaran',
			'msg' => '',
			'dt'  => $dt,
			'sts'  => $sts,
			'id_pasien'  => $id_pasien,
			'nomor_bill' => $this->model->getNomorBilling(),
		);

		$this->load->view('billing/billing_bayar_print_v',$data);
	}

	function next_antri(){
		$kode_antrian = $this->input->post('kode_antrian');
		$jml_antrian  = $this->input->post('jml_antrian');
		$id_antrian   = $this->input->post('id_antrian');

		$this->model->simpanAntrian($kode_antrian, $jml_antrian, $id_antrian);

		echo json_encode(1);
	}

	function cetakBillingRI(){
		$id_pasien = $this->input->post('id_pasien_ri');
		$sql = "SELECT KODE_PASIEN FROM rk_pasien WHERE ID = '$id_pasien'";
		$qry = $this->db->query($sql)->row();
		$no_rm = $qry->KODE_PASIEN;

		$data = array(
			'settitle' => 'Cetak Billing Rawat Inap',
			'filename' => 'cetakBillingRI_'.date('Y-m-d').'_'.$no_rm,
			'id_pasien'  => $id_pasien,
			'nomor_bill' => $this->model->getNomorBilling(),
			'dt' => $this->model->getDetailPasien_RI($id_pasien),
			'dt2' => $this->model->dataDetPasien_RI($id_pasien),
		);

		$this->load->view('billing/pdf/billing_cetak_ri_pdf_v',$data);
	}

	function cetakBillingRJ(){
		$id_pasien = $this->input->post('id_pasien_rj');
		$sql = "SELECT KODE_PASIEN FROM rk_pasien WHERE ID = '$id_pasien'";
		$qry = $this->db->query($sql)->row();
		$no_rm = $qry->KODE_PASIEN; 

		$data = array(
			'settitle' => 'Cetak Billing Rawat Jalan',
			'filename' => 'cetakBillingRJ_'.date('Y-m-d').'_'.$no_rm,
			'id_pasien'  => $id_pasien,
			'nomor_bill' => $this->model->getNomorBilling(),
			'dt' => $this->model->getDetailPasien_RJ($id_pasien),
		);

		$this->load->view('billing/pdf/billing_cetak_rj_pdf_v',$data);
	}

	function cetakBillingIGD(){
		$id_pasien = $this->input->post('id_pasien_igd');
		$sql = "SELECT KODE_PASIEN FROM rk_pasien WHERE ID = '$id_pasien'";
		$qry = $this->db->query($sql)->row();
		$no_rm = $qry->KODE_PASIEN; 

		$data = array(
			'settitle' => 'Cetak Billing IGD',
			'filename' => 'cetakBilling_IGD_'.date('Y-m-d').'_'.$no_rm,
			'id_pasien'  => $id_pasien,
			'nomor_bill' => $this->model->getNomorBilling(),
			'dt' => $this->model->getDetailPasien_IGD($id_pasien),
		);

		$this->load->view('billing/pdf/billing_cetak_igd_pdf_v',$data);
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
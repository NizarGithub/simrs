<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ap_entry_resep_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->model('apotek/Ap_entry_resep_m','model');
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){
	        redirect('login_c');
	    }
	}

	function index(){
		$data = array(
			'title' => 'Sistem Informasi Rumah Sakit',
      'page' => 'apotek/Ap_entry_resep_v',
      'master_menu' => 'kasir_aa',
			'view' => 'entry_resep',
      'subtitle' => ''
		);

		$this->load->view('apotek/ap_entry_resep_v',$data);
	}

	function data_obat(){
		$keyword = $this->input->get('keyword');
		$data = $this->model->data_obat($keyword);
		echo json_encode($data);
	}

	function data_pasien_iter(){
		$keyword = $this->input->get('keyword');
		$data = $this->model->data_pasien_iter($keyword);
		echo json_encode($data);
	}

	function data_keranjang(){
		$data = $this->model->data_keranjang();
		echo json_encode($data);
	}

	function simpan_keranjang(){
		$id_gudang = $this->input->post('id');
		$harga_beli = $this->input->post('harga_beli');
		$service = $this->input->post('service');

		$sql_check = $this->db->query("SELECT COUNT(*) AS TOTAL FROM keranjang_beli WHERE ID_GUDANG_OBAT = '$id_gudang'")->row();
		if ($sql_check->TOTAL == '0') {
			$this->model->simpan_keranjang($id_gudang, $harga_beli, $service);
		}else {
		}
		echo '1';
	}

	function hapus_keranjang(){
		$id = $this->input->post('id');
		$data = $this->model->hapus_keranjang($id);
		echo json_encode($data);
	}

	function simpan_proses(){
		$id_gudang_obat = $this->input->post('id_gudang_obat');
		$total_keranjang_name = $this->input->post('total_keranjang_name');
		$jumlah_beli = $this->input->post('jumlah_beli');
		$harga_obat = $this->input->post('harga_obat');
		$service = $this->input->post('service');
		$aturan_minum = $this->input->post('aturan_minum');
		$diminum_selama = $this->input->post('diminum_selama');

		$harga_obat_normal = $this->input->post('harga_normal');
		$total_normal = $this->input->post('total_keranjang_normal');
		$total_tagihan_normal = str_replace(',','',  $this->input->post('total_tagihan_normal'));

		$invoice = $this->input->post('invoice');
		$shift = $this->input->post('shift');
		$id_pegawai = $this->input->post('id_pegawai');
		$total = str_replace(',','',  $this->input->post('total_tagihan'));
		$tanggal = date('d-m-Y');
    $tahun = date('Y');
    $bulan = date('m');
		$waktu = date('H:i:s');
		$id_resep = $this->input->post('id_resep');
		$id_pasien = $this->input->post('id_pasien');
		$id_dokter = $this->input->post('id_dokter');

		$data = array(
			'INVOICE' => $invoice,
			'SHIFT' => $shift,
			'ID_PEGAWAI' => $id_pegawai,
			'TOTAL' => $total,
			'STATUS' => 'Entry Resep',
			'TANGGAL' => $tanggal,
			'BULAN' => $bulan,
			'TAHUN' => $tahun,
			'WAKTU' => $waktu,
			'TOTAL_NORMAL' => $total_tagihan_normal,
			'ID_RESEP' => $id_resep,
			'ID_PASIEN' => $id_pasien,
			'ID_DOKTER' => $id_dokter
		);
		$this->db->insert('ap_iter', $data);
		$id_iter = $this->db->insert_id();

		$data_update = array(
			'TOTAL' => $total_tagihan_normal,
			'TOTAL_DGN_SERVICE' => $total
		);
		$this->db->where('ID', $id_resep);
		$this->db->update('rk_resep_rj', $data_update);

		foreach ($id_gudang_obat as $key => $value) {
			$this->db->where('ID_RESEP', $id_resep);
			$this->db->delete('rk_resep_detail_rj');

			$this->model->simpan_proses($value, $total_keranjang_name[$key], $jumlah_beli[$key], $harga_obat[$key], $service[$key], $aturan_minum[$key], $diminum_selama[$key], $id_iter, $harga_obat_normal[$key], $total_normal[$key], $id_resep);
		}

		foreach ($id_gudang_obat as $key => $value) {

			$this->model->simpan_proses_resep($value, $total_keranjang_name[$key], $jumlah_beli[$key], $harga_obat[$key], $service[$key], $aturan_minum[$key], $diminum_selama[$key], $id_iter, $harga_obat_normal[$key], $total_normal[$key], $id_resep);
		}

		$sql = $this->db->query("SELECT * FROM ap_iter WHERE ID = '$id_iter'");
		$back = $sql->row_array();
		echo json_encode($back);

		$this->insert_kode();
	}

	function get_invoice(){
		$keterangan = 'KODE-TRX-OBAT';
		$tanggal = date('d');
		$bulan = date('n');
		$tahun = date('Y');

		$sql = "
			SELECT
				COUNT(*) AS TOTAL
			FROM nomor
			WHERE KETERANGAN = '$keterangan'
			AND TAHUN = '$tahun'
		";
		$qry = $this->db->query($sql);
		$total = $qry->row()->TOTAL;
		$kode = "";

		if($total == 0){
			$no = $this->add_leading_zero(1,3);
			$kode = $tahun.$bulan.$tanggal.$no;
		}else{
			$s = "SELECT * FROM nomor WHERE KETERANGAN = '$keterangan' AND TAHUN = '$tahun'";
			$q = $this->db->query($s)->row();
			$next = $q->NEXT+1;
			$no = $this->add_leading_zero($next,3);
			$kode = $tahun.$bulan.$tanggal.$no;
		}

		echo json_encode($kode);
	}

	function insert_kode(){
	    $keterangan = 'KODE-TRX-OBAT';
		$tahun = date('Y');

		$sql_cek = "
			SELECT
				COUNT(*) AS TOTAL
			FROM nomor
			WHERE KETERANGAN = '$keterangan'
			AND TAHUN = '$tahun'
		";
		$total = $this->db->query($sql_cek)->row()->TOTAL;

		if($total == 0){
			$this->db->query("INSERT INTO nomor(NEXT,KETERANGAN,TAHUN) VALUES ('1','$keterangan','$tahun')");
		}else{
			$sql = "SELECT * FROM nomor WHERE TAHUN = '$tahun' AND KETERANGAN = '$keterangan'";
			$query = $this->db->query($sql)->row();
			$next = $query->NEXT+1;
			$id = $query->ID;
			$this->db->query("UPDATE nomor SET NEXT = '$next' WHERE ID = '$id' AND KETERANGAN = '$keterangan'");
		}
	}

	function get_pasien_iter(){
		$id_resep = $this->input->post('id_resep');
		$data = $this->model->get_pasien_iter($id_resep);

		echo json_encode($data);
	}

	function get_obat(){
		$id = $this->input->post('id');
		$harga_beli = $this->input->post('harga_beli');
		$service = $this->input->post('service');
		$data = $this->model->get_obat($id);

		echo json_encode($data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */

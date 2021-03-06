<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ap_retur_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->model('apotek/ap_retur_m','model');
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){
	        redirect('login_c');
	    }
	}

	function index(){
		$data = array(
			'title' => 'Sistem Informasi Rumah Sakit',
      'page' => 'apotek/ap_retur_v',
      'master_menu' => 'kasir_aa',
			'view' => 'retur',
      'subtitle' => ''
		);

		$this->load->view('apotek/ap_retur_v',$data);
	}

	function data_obat(){
		$keyword = $this->input->get('keyword');
		$data = $this->model->data_obat($keyword);
		echo json_encode($data);
	}

	function data_keranjang(){
		$data = $this->model->data_keranjang();
		echo json_encode($data);
	}

	function simpan_keranjang(){
		$id_gudang = $this->input->post('id');
		$harga_beli = $this->input->post('harga_beli');

		$sql_check = $this->db->query("SELECT COUNT(*) AS TOTAL FROM keranjang_beli_hv WHERE ID_GUDANG_OBAT = '$id_gudang'")->row();
		if ($sql_check->TOTAL == '0') {
			$this->model->simpan_keranjang($id_gudang, $harga_beli);
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

		$sum_beli = array_sum($jumlah_beli);

		$shift = $this->input->post('shift');
		$id_resep = $this->input->post('id_resep');
		$id_pegawai = $this->input->post('id_pegawai');
		$kode_resep = $this->input->post('kode_resep');
		$total = str_replace(',','',  $this->input->post('total_tagihan'));
		$tanggal = date('d-m-Y');
		$bulan = date('m');
		$tahun = date('Y');

		$data = array(
			'SHIFT' => $shift,
			'ID_PEGAWAI' => $id_pegawai,
			'ID_RESEP' => $id_resep,
			'KODE_RESEP' => $kode_resep,
			'TOTAL_BIAYA' => $total,
			'TANGGAL' => $tanggal,
			'BULAN' => $bulan,
			'TAHUN' => $tahun,
			'TOTAL_JUMLAH_RETUR' => $sum_beli
		);
		$this->db->insert('retur_pasien', $data);
		$id_retur = $this->db->insert_id();

		foreach ($id_gudang_obat as $key => $value) {
			$this->model->simpan_proses($value, $total_keranjang_name[$key], $jumlah_beli[$key], $harga_obat[$key], $id_resep, $id_retur, $tanggal, $bulan, $tahun);
		}

		$sql = $this->db->query("SELECT * FROM retur_pasien WHERE ID = '$id_retur'");
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

	function get_data_resep(){
		$keyword = $this->input->get('keyword');
		$data = $this->model->get_data_resep($keyword);
		echo json_encode($data);
	}

	function klik_resep(){
		$id = $this->input->post('id');
		$data['res'] = $this->model->klik_resep($id);
		$data['row'] = $this->model->klik_resep_row($id);

		echo json_encode($data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Log_inventaris_bidan_m extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function load_nama_alat($keyword){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND (MEDIS.KODE_ALAT LIKE '%$keyword%' OR MEDIS.NAMA_ALAT LIKE '%$keyword%')";
		}

		$sql = "
			SELECT
				MEDIS.ID,
				MEDIS.KODE_ALAT,
				MEDIS.NAMA_ALAT
			FROM admum_setup_peralatan_medis MEDIS
			WHERE $where
			ORDER BY MEDIS.ID ASC
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function klik_nama_alat($id){
		$sql = "
			SELECT
				MEDIS.ID,
				MEDIS.KODE_ALAT,
				MEDIS.BARCODE,
				MEDIS.NAMA_ALAT,
				SUP.MERK,
				MEDIS.JENIS_ALAT,
				ALAT.ID AS ID_ALAT,
				SAT.ID AS ID_SATUAN,
				SAT.NAMA_SATUAN,
				ALAT.PEMAKAIAN,
				ALAT.JUMLAH,
				ALAT.ISI,
				ALAT.TOTAL,
				ALAT.HARGA_BELI,
				ALAT.GAMBAR
			FROM admum_setup_peralatan_medis MEDIS
			LEFT JOIN admum_supplier_barang SUP ON SUP.ID = MEDIS.ID_MERK
			LEFT JOIN (
				SELECT * FROM log_peralatan_medis WHERE AKTIF = '1'
			) ALAT ON ALAT.ID_SETUP_NAMA_ALAT = MEDIS.ID
			LEFT JOIN obat_satuan SAT ON SAT.ID = ALAT.ID_SATUAN_ALAT
			WHERE MEDIS.ID = '$id'
		";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function get_edit_data($id){
		$sql = $this->db->query("SELECT
									*,
									a.ID AS ID_LOG
									FROM log_peralatan_medis a
									LEFT JOIN admum_setup_peralatan_medis b ON a.ID_SETUP_NAMA_ALAT=b.ID
									LEFT JOIN admum_supplier_barang SUP ON b.ID_MERK=SUP.ID
									LEFT JOIN kepeg_departemen c ON a.ID_DEPARTEMEN=c.ID
									LEFT JOIN kepeg_divisi d ON a.ID_DIVISI=d.ID
									LEFT JOIN obat_satuan e ON a.ID_SATUAN_ALAT=e.ID
									WHERE a.ID = '$id'
								");
		return $sql->row();
	}

	function data_satuan(){
		$sql = "SELECT * FROM obat_satuan ORDER BY ID DESC";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function klik_satuan($id_satuan){
		$sql = "SELECT * FROM obat_satuan WHERE ID = '$id_satuan'";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function data_departemen($keyword){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND NAMA_DEP LIKE '%$keyword%'";
		}

		$sql = "SELECT * FROM kepeg_departemen WHERE $where ORDER BY ID DESC";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function klik_departemen($id_departemen){
		$sql = "SELECT * FROM kepeg_departemen WHERE ID = '$id_departemen'";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function data_divisi($keyword,$id_departemen){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND NAMA_DIV LIKE '%$keyword%'";
		}

		$sql = "SELECT * FROM kepeg_divisi WHERE $where AND ID_DEPARTEMEN = '$id_departemen' ORDER BY ID DESC";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function klik_divisi($id_divisi){
		$sql = "SELECT * FROM kepeg_divisi WHERE ID = '$id_divisi'";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function data_peralatan($keyword,$urutkan,$urutkan_stok){
		$where = "1 = 1";
		$order = "";

		if($urutkan == 'Default'){
			$order = "ORDER BY MEDIS.URUT_BARANG ASC";
		}else if($urutkan == 'Nama Alat'){
			$order = "ORDER BY ALAT.NAMA_ALAT ASC";
		}else if($urutkan == 'Stok'){
			if($urutkan_stok == 'Rendah'){
				$order = "ORDER BY MEDIS.TOTAL ASC";
			}else if($urutkan_stok == 'Tinggi'){
				$order = "ORDER BY MEDIS.TOTAL DESC";
			}
		}

		if($keyword != ""){
			$where = $where." AND (ALAT.KODE_ALAT LIKE '%$keyword%' OR ALAT.BARCODE LIKE '%$keyword%' OR ALAT.NAMA_ALAT LIKE '%$keyword')";
		}

		$sql = "
			SELECT
				MEDIS.ID,
				ALAT.KODE_ALAT,
				ALAT.NAMA_ALAT,
				SAT.NAMA_SATUAN,
				MEDIS.PEMAKAIAN,
				MEDIS.JUMLAH,
				MEDIS.ISI,
				MEDIS.TOTAL,
				MEDIS.SATUAN_ISI,
				MEDIS.HARGA_BELI,
				MEDIS.TANGGAL_MASUK,
				MEDIS.WAKTU_MASUK,
				MEDIS.AKTIF,
				MEDIS.FIRST_OUT,
				MEDIS.URUT_BARANG,
				MEDIS.GAMBAR
			FROM log_peralatan_medis MEDIS
			LEFT JOIN admum_setup_peralatan_medis ALAT ON ALAT.ID = MEDIS.ID_SETUP_NAMA_ALAT
			LEFT JOIN obat_satuan SAT ON SAT.ID = MEDIS.ID_SATUAN_ALAT
			WHERE $where
			$order
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function simpan(
		$id_setup_nama_alat,
		$id_satuan,
		$pemakaian,
		$jumlah,
		$isi,
		$total,
		$satuan_isi,
		$harga_beli,
		$tanggal_masuk,
		$waktu_masuk,
		$aktif,
		$first_out,
		$urut_barang,
		$gambar,
		$id_departemen,
		$id_divisi,
		$golongan,
		$total_harga
	){
		$bulan = date('m');
		$tahun = date('Y');
		$sql = "
			INSERT INTO log_peralatan_medis(
				ID_SETUP_NAMA_ALAT,
				ID_SATUAN_ALAT,
				PEMAKAIAN,
				JUMLAH,
				ISI,
				TOTAL,
				SATUAN_ISI,
				HARGA_BELI,
				TANGGAL_MASUK,
				WAKTU_MASUK,
				AKTIF,
				FIRST_OUT,
				URUT_BARANG,
				GAMBAR,
				ID_DEPARTEMEN,
				ID_DIVISI,
				GOLONGAN,
				TOTAL_HARGA,
				BULAN,
				TAHUN
			) VALUES(
				'$id_setup_nama_alat',
				'$id_satuan',
				'$pemakaian',
				'$jumlah',
				'$isi',
				'$total',
				'$satuan_isi',
				'$harga_beli',
				'$tanggal_masuk',
				'$waktu_masuk',
				'$aktif',
				'$first_out',
				'$urut_barang',
				'$gambar',
				'$id_departemen',
				'$id_divisi',
				'$golongan',
				'$total_harga',
				'$bulan',
				'$tahun'
			)";
		$this->db->query($sql);
	}
	public function ubah(
		$id_log,
		$id_setup_nama_alat,
		$id_satuan,
		$pemakaian,
		$jumlah,
		$isi,
		$total,
		$satuan_isi,
		$harga_beli,
		$tanggal_masuk,
		$waktu_masuk,
		$aktif,
		$first_out,
		$urut_barang,
		$gambar,
		$id_departemen,
		$id_divisi,
		$golongan,
		$total_harga
	){
			$bulan = date('m');
			$tahun = date('Y');
	    $data = array(
	        'ID_SETUP_NAMA_ALAT' => $id_setup_nama_alat,
					'ID_SATUAN_ALAT' => $id_satuan,
					'PEMAKAIAN' => $pemakaian,
					'JUMLAH' => $jumlah,
					'ISI' => $isi,
					'TOTAL' => $total,
					'SATUAN_ISI' => $satuan_isi,
					'HARGA_BELI' => $harga_beli,
					'TANGGAL_MASUK' => $tanggal_masuk,
					'WAKTU_MASUK' => $waktu_masuk,
					'AKTIF' => $aktif,
					'FIRST_OUT' => $first_out,
					'URUT_BARANG' => $urut_barang,
					'GAMBAR' => $gambar,
					'ID_DEPARTEMEN' => $id_departemen,
					'ID_DIVISI' => $id_divisi,
					'GOLONGAN' => $golongan,
					'TOTAL_HARGA' => $total_harga,
					'BULAN' => $bulan,
					'TAHUN' => $tahun,
	      );

	      $this->db->where('ID', $id_log);
	      $this->db->update('log_peralatan_medis', $data);
	  }
	function data_peralatan_id($id){
		$sql = "SELECT * FROM log_peralatan_medis a INNER JOIN admum_setup_peralatan_medis b ON a.ID_SETUP_NAMA_ALAT=b.ID WHERE a.ID = '$id'";
		$query = $this->db->query($sql);
		return $query->row();
	}
	function hapus($id){
		$this->db->where('ID', $id);
    $this->db->delete('log_peralatan_medis');
    return true;
	}
}

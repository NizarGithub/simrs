<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data_pegawai_m extends CI_Model { 

	function __construct()
	{
		parent::__construct();
		$this->load->database();  
	}

	function cek_kode_jabatan($kode_jab){
		$sql = " 
		SELECT * FROM kepeg_jabatan WHERE KODE_JABATAN = '$kode_jab'
		";
 
		return $this->db->query($sql)->result();
	}

	function get_departemen(){
		$sql = " 
		SELECT * FROM kepeg_departemen WHERE STS = 0
		ORDER BY ID ASC
		";

		return $this->db->query($sql)->result();
	}

	function get_jabatan(){
		$sql = "
		SELECT * FROM kepeg_kel_jabatan
		ORDER BY ID ASC
		";

		return $this->db->query($sql)->result();
	}

	function get_pangkat(){
		$sql = "
		SELECT * FROM kepeg_pangkat
		ORDER BY ID ASC
		";

		return $this->db->query($sql)->result();
	}

	function get_pendidikan(){
		$sql = "
		SELECT * FROM kepeg_pendidikan
		ORDER BY ID ASC
		";

		return $this->db->query($sql)->result();
	}

	function get_gol_pajak(){
		$sql = "
		SELECT * FROM kepeg_gol_pajak
		ORDER BY ID ASC
		";

		return $this->db->query($sql)->result();
	}

	function get_divisi_by_id_dep($id_dep){
		$sql = "
		SELECT * FROM kepeg_divisi WHERE ID_DEPARTEMEN = $id_dep AND STS = 0
		ORDER BY ID ASC
		";

		return $this->db->query($sql)->result();
	}

	function get_data_jabatan(){
		$sql = "
		SELECT * FROM kepeg_kel_jabatan
		ORDER BY ID ASC
		";

		return $this->db->query($sql)->result();
	}

	function simpan_jabatan($kode_jab, $nama_jab, $uraian){
		$sql = "
			INSERT INTO kepeg_jabatan 
			(KODE_JABATAN, NAMA, URAIAN)
			VALUES 
			('$kode_jab', '$nama_jab', '$uraian')
		";

		$this->db->query($sql);
	}

	function hapus_pegawai($id){
		$sql = "
		DELETE FROM kepeg_pegawai WHERE ID = $id
		";

		$this->db->query($sql);
	}

	function get_data_jab_by_id($id){
		$sql = "
		SELECT * FROM kepeg_jabatan WHERE ID = $id
		";

		return $this->db->query($sql)->row();
	}

	function ubah_jabatan($id_jabatan, $ed_nama_jab, $ed_uraian){
		$sql = "
		UPDATE kepeg_jabatan SET NAMA = '$ed_nama_jab', URAIAN = '$ed_uraian'
		WHERE ID = $id_jabatan
		";

		$this->db->query($sql);
	}

	function simpan_pegawai($id_dep, $id_div, $nip, $nama, $kota_lahir, $tgl_lahir, $alamat, $telpon, $status, $id_jabatan, $id_pangkat){
		$sql = "
		INSERT INTO kepeg_pegawai
		(ID_DEPARTEMEN, ID_DIVISI, NIP, NAMA, KOTA_LAHIR, TGL_LAHIR, ALAMAT, TELPON, STATUS, ID_JABATAN, ID_PANGKAT, FOTO)
		VALUES 
		($id_dep, $id_div, '$nip', '$nama', '$kota_lahir', '$tgl_lahir', '$alamat', '$telpon', '$status', $id_jabatan, $id_pangkat, 'default_pics_of_rs_jt.png')
		";

		$this->db->query($sql);

	}

	function ubah_data_pegawai(
		$id,
		$nip, 
		$id_dep, 
		$id_div,
		$id_jabatan, 
		$id_pangkat,
		$status, 
		$nama, 
		$kota_lahir, 
		$tgl_lahir, 
		$alamat, 
		$telpon, 
		$id_pendidikan, 
		$sk_pangkat, 
		$tgl_sk_pangkat, 
		$tgl_awal_pangkat, 
		$tgl_akhir_pangkat, 
		$sts_jabatan, 
		$sk_jabatan, 
		$tgl_sk_jabatan, 
		$tgl_awal_jabatan, 
		$tgl_akhir_jabatan, 
		$id_gol_pajak
		){

		$sql = "
			UPDATE kepeg_pegawai SET 
				NIP = '$nip',
				ID_DEPARTEMEN = '$id_dep',
				ID_DIVISI = '$id_div',
				ID_JABATAN = '$id_jabatan',
				ID_PANGKAT = '$id_pangkat',
				STATUS = '$status',
				NAMA = '$nama',
				KOTA_LAHIR = '$kota_lahir',
				TGL_LAHIR = '$tgl_lahir',
				ALAMAT = '$alamat',
				TELPON = '$telpon',
				ID_PENDIDIKAN = '$id_pendidikan',
				SK_PANGKAT = '$sk_pangkat',
				TGL_SK_PANGKAT = '$tgl_sk_pangkat',
				TGL_AWAL_PANGKAT = '$tgl_awal_pangkat',
				TGL_AKHIR_PANGKAT = '$tgl_akhir_pangkat',
				STS_JABATAN = '$sts_jabatan',
				SK_JABATAN = '$sk_jabatan',
				TGL_SK_JABATAN = '$tgl_sk_jabatan',
				TGL_AWAL_JABATAN = '$tgl_awal_jabatan',
				TGL_AKHIR_JABATAN = '$tgl_akhir_jabatan',
				ID_GOL_PAJAK = '$id_gol_pajak'
			WHERE ID = '$id'
		";

		$this->db->query($sql);
	}
	
	function simpan_foto_user($nip, $foto){
		$sql = "
		UPDATE kepeg_pegawai SET FOTO = '$foto'
		WHERE NIP = '$nip'
		";

		$this->db->query($sql);
	}

	function cek_nip($id_peg, $nip){
		$sql = "
		SELECT * FROM kepeg_pegawai WHERE NIP = '$nip' AND ID != $id_peg
		";

		return $this->db->query($sql)->result();
	}

	function get_data_pegawai_list(){
		$sql = "
		SELECT a.*, b.NAMA AS JABATAN, c.NAMA_DEP , d.NAMA_DIV, e.GOLONGAN, e.RUANG FROM kepeg_pegawai a 
		LEFT JOIN kepeg_kel_jabatan b ON a.ID_JABATAN = b.ID 
		LEFT JOIN kepeg_departemen c ON a.ID_DEPARTEMEN = c.ID 
		LEFT JOIN kepeg_divisi d ON a.ID_DIVISI = d.ID 
		LEFT JOIN kepeg_pangkat e ON a.ID_PANGKAT = e.ID 
		ORDER BY a.ID ASC
		";

		return $this->db->query($sql)->result();
	}

	function get_data_pegawai(){
		$sql = "
		SELECT a.*, b.NAMA AS JABATAN, c.NAMA_DEP , d.NAMA_DIV FROM kepeg_pegawai a 
		LEFT JOIN kepeg_kel_jabatan b ON a.ID_JABATAN = b.ID 
		LEFT JOIN kepeg_departemen c ON a.ID_DEPARTEMEN = c.ID 
		LEFT JOIN kepeg_divisi d ON a.ID_DIVISI = d.ID 
		ORDER BY a.ID ASC
		";

		return $this->db->query($sql)->result();
	}

	function get_data_pegawai_by_id($id){
		$sql = "
		SELECT * FROM kepeg_pegawai WHERE ID = $id
		";

		return $this->db->query($sql)->row();
	}

	function cari_peg_by_nama($keyword){
		$sql = "
		SELECT a.*, b.NAMA AS JABATAN, c.NAMA_DEP , d.NAMA_DIV FROM kepeg_pegawai a 
		LEFT JOIN kepeg_jabatan b ON a.ID_JABATAN = b.ID 
		LEFT JOIN kepeg_departemen c ON a.ID_DEPARTEMEN = c.ID 
		LEFT JOIN kepeg_divisi d ON a.ID_DIVISI = d.ID 
		WHERE a.NAMA LIKE '%$keyword%'
		ORDER BY a.ID ASC
		";

		return $this->db->query($sql)->result();
	}

	function cari_peg_by_jabatan($id_jabatan){
		$where = "1 = 1";
		if($id_jabatan != ""){
			$where = $where." AND a.ID_JABATAN = $id_jabatan";
		} 
		$sql = "
		SELECT a.*, b.NAMA AS JABATAN, c.NAMA_DEP , d.NAMA_DIV FROM kepeg_pegawai a 
		LEFT JOIN kepeg_jabatan b ON a.ID_JABATAN = b.ID 
		LEFT JOIN kepeg_departemen c ON a.ID_DEPARTEMEN = c.ID 
		LEFT JOIN kepeg_divisi d ON a.ID_DIVISI = d.ID 
		WHERE $where
		ORDER BY a.ID ASC
		";

		return $this->db->query($sql)->result();
	}

}
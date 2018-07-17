<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Add_pegawai_m extends CI_Model {
 
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
		SELECT * FROM kepeg_kel_jabatan WHERE JENIS = 'S'
		ORDER BY ID ASC
		";

		return $this->db->query($sql)->result();
	}

	function get_data_jab_by_jenis($jenis){
		$sql = "
		SELECT * FROM kepeg_kel_jabatan WHERE JENIS = '$jenis'
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

	function get_divisi_by_id_dep($id_dep){
		$sql = "
		SELECT * FROM kepeg_divisi WHERE ID_DEPARTEMEN = $id_dep AND STS = 0
		ORDER BY ID ASC
		";

		return $this->db->query($sql)->result();
	}

	function get_data_jabatan(){
		$sql = "
		SELECT * FROM kepeg_jabatan
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

	function simpan_jabatan($kode_jab, $nama_jab, $uraian){
		$sql = "
			INSERT INTO kepeg_jabatan 
			(KODE_JABATAN, NAMA, URAIAN)
			VALUES 
			('$kode_jab', '$nama_jab', '$uraian')
		";

		$this->db->query($sql);
	}

	function hapus_jabatan($id){
		$sql = "
		DELETE FROM kepeg_jabatan WHERE ID = $id
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

	function simpan_pegawai($id_dep, $id_div, $nip, $nama, $kota_lahir, $tgl_lahir, $alamat, $telpon, $status, $id_jabatan, $id_pangkat,
							$id_pendidikan, $nomor_sk_pangkat, $tgl_sk_pangkat, $tgl_awal_pangkat, $tgl_selesai_pangkat, $sts_jabatan, $nomor_sk_jabatan, $tgl_sk_jabatan,
							$tgl_awal_jabatan, $tgl_selesai_jabatan, $id_gol_pajak)
	{
		$sql = " 
		INSERT INTO kepeg_pegawai
		(
		 ID_DEPARTEMEN, ID_DIVISI, NIP, NAMA, KOTA_LAHIR, TGL_LAHIR, ALAMAT, TELPON, STATUS, ID_JABATAN, ID_PANGKAT,
		 ID_PENDIDIKAN, SK_PANGKAT, TGL_SK_PANGKAT, TGL_AWAL_PANGKAT, TGL_AKHIR_PANGKAT, STS_JABATAN, SK_JABATAN, TGL_SK_JABATAN, TGL_AWAL_JABATAN, TGL_AKHIR_JABATAN, ID_GOL_PAJAK,
		 FOTO
		)
		VALUES 
		(
			$id_dep, $id_div, '$nip', '$nama', '$kota_lahir', '$tgl_lahir', '$alamat', '$telpon', '$status', '$id_jabatan', '$id_pangkat',
			'$id_pendidikan', '$nomor_sk_pangkat', '$tgl_sk_pangkat', '$tgl_awal_pangkat', '$tgl_selesai_pangkat', '$sts_jabatan', '$nomor_sk_jabatan', '$tgl_sk_jabatan',
			'$tgl_awal_jabatan', '$tgl_selesai_jabatan', '$id_gol_pajak',
			'default/default.png'
		)
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

	function cek_nip($nip){
		$sql = "
		SELECT * FROM kepeg_pegawai WHERE NIP = '$nip'
		";

		return $this->db->query($sql)->result();
	}

	function get_pangkat_by_pendidikan($id_pendidikan){
		$sql = "
		SELECT a.*, b.GOLONGAN AS GOL_MIN, b.RUANG AS RUANG_MIN, c.GOLONGAN AS GOL_MAX, c.RUANG AS RUANG_MAX 
		FROM kepeg_pendidikan a
		LEFT JOIN kepeg_pangkat b ON a.MIN_PANGKAT = b.ID
		LEFT JOIN kepeg_pangkat c ON a.MAX_PANGKAT = c.ID
		WHERE a.ID = '$id_pendidikan'
		";

		return $this->db->query($sql)->row();
	}

	function get_gol_pajak_by_id($id_gol){
		$sql = "
		SELECT * FROM kepeg_gol_pajak WHERE ID = '$id_gol'
		";

		return $this->db->query($sql)->row();
	}

}
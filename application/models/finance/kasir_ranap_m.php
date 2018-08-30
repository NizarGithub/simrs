<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kasir_ranap_m extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function get_user_detail($id_user){
		$sql = "
			SELECT
				a.*
			FROM kepeg_pegawai a
			WHERE a.ID = '$id_user'
		";

		return $this->db->query($sql)->row();
	}

	function get_pasien($tanggal,$keyword){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND (a.NAMA_PASIEN LIKE '%$keyword%' OR a.NAMA_ORTU LIKE '%$keyword%')";
		}else{
			$where = $where;
		}

		$sql = "
			SELECT
				a.*,
				(a.TOT_TINDAKAN+a.TOT_RESEP+a.BIAYA_KAMAR+a.BIAYA_VISITE+a.JASA_SARANA+a.TOT_LAB) AS TOTAL
			FROM(
				SELECT
					a.ID,
					a.ID_PASIEN,
					b.KODE_PASIEN,
					b.NAMA AS NAMA_PASIEN,
					b.JENIS_KELAMIN,
					b.NAMA_ORTU,
					a.TANGGAL_MASUK,
					a.WAKTU,
					a.BULAN,
					a.TAHUN,
					a.NAMA_PENANGGUNGJAWAB,
					a.TELEPON,
					a.SISTEM_BAYAR,
					a.ASAL_RUJUKAN,
					a.ID_DOKTER,
					a.KELAS,
					a.ID_KAMAR,
					a.ID_BED,
					a.STATUS_SUDAH,
					a.STATUS_BAYAR,
					IFNULL(c.TOTAL,0) AS TOT_TINDAKAN,
					IFNULL(d.TOTAL,0) AS TOT_RESEP,
					e.BIAYA AS BIAYA_KAMAR,
					e.BIAYA_VISITE,
					e.JASA_SARANA,
					IFNULL(g.TOTAL_TARIF,0) AS TOT_LAB,
					IFNULL(h.JML_KLAIM,0) AS TOT_ASURANSI
				FROM admum_rawat_inap a
				JOIN rk_pasien b ON b.ID = a.ID_PASIEN
				LEFT JOIN rk_ri_tindakan c ON c.ID_PELAYANAN = a.ID
				LEFT JOIN rk_ri_resep d ON d.ID_PELAYANAN = a.ID
				LEFT JOIN admum_kamar_rawat_inap e ON e.ID = a.ID_KAMAR
				LEFT JOIN rk_ri_laborat g ON g.ID_PELAYANAN = a.ID
				LEFT JOIN asr_asuransi h ON h.ID_RAWAT_INAP = a.ID
			) a
			WHERE $where
			AND a.TANGGAL_MASUK = '$tanggal'
			AND a.STATUS_BAYAR = '0'
			ORDER BY a.ID DESC
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function get_pasien_id($id){
		$sql = "
			SELECT
				a.*,
				(a.TOT_TINDAKAN+a.TOT_RESEP+a.BIAYA_KAMAR+a.BIAYA_VISITE+a.JASA_SARANA+a.TOT_LAB) AS TOTAL
			FROM(
				SELECT
					a.ID,
					a.ID_PASIEN,
					b.KODE_PASIEN,
					b.NAMA AS NAMA_PASIEN,
					b.JENIS_KELAMIN,
					b.NAMA_ORTU,
					a.TANGGAL_MASUK,
					a.WAKTU,
					a.BULAN,
					a.TAHUN,
					a.NAMA_PENANGGUNGJAWAB,
					a.TELEPON,
					a.SISTEM_BAYAR,
					a.ASAL_RUJUKAN,
					a.ID_DOKTER,
					a.KELAS,
					a.ID_KAMAR,
					a.ID_BED,
					a.STATUS_SUDAH,
					IFNULL(c.TOTAL,0) AS TOT_TINDAKAN,
					IFNULL(d.TOTAL,0) AS TOT_RESEP,
					e.BIAYA AS BIAYA_KAMAR,
					e.BIAYA_VISITE,
					e.JASA_SARANA,
					IFNULL(g.TOTAL_TARIF,0) AS TOT_LAB,
					IFNULL(h.JML_KLAIM,0) AS TOT_ASURANSI
				FROM admum_rawat_inap a
				JOIN rk_pasien b ON b.ID = a.ID_PASIEN
				LEFT JOIN rk_ri_tindakan c ON c.ID_PELAYANAN = a.ID
				LEFT JOIN rk_ri_resep d ON d.ID_PELAYANAN = a.ID
				LEFT JOIN admum_kamar_rawat_inap e ON e.ID = a.ID_KAMAR
				LEFT JOIN rk_ri_laborat g ON g.ID_PELAYANAN = a.ID
				LEFT JOIN asr_asuransi h ON h.ID_RAWAT_INAP = a.ID
			) a
			WHERE a.ID = '$id'
		";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function get_tindakan($id_ri){
		$sql = "
			SELECT
				DET.*,
				a.ID_PELAYANAN,
				b.NAMA_TINDAKAN,
				b.TARIF
			FROM rk_ri_tindakan_detail DET
			LEFT JOIN rk_ri_tindakan a ON DET.ID_TINDAKAN = a.ID
			LEFT JOIN admum_setup_tindakan b ON b.ID = DET.ID_SETUP_TINDAKAN
			WHERE a.ID_PELAYANAN = '$id_ri'
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function get_resep($id_ri){
		$sql = "
			SELECT
				a.ID,
				a.ID_OBAT,
				b.NAMA_OBAT,
				a.SUBTOTAL
			FROM rk_ri_resep_detail a
			LEFT JOIN (
				SELECT a.ID,a.ID_SETUP_NAMA_OBAT,b.NAMA_OBAT FROM apotek_gudang_obat a
				LEFT JOIN admum_setup_nama_obat b ON b.ID = a.ID_SETUP_NAMA_OBAT
			) b ON b.ID = a.ID_OBAT
			LEFT JOIN rk_ri_resep c ON a.ID_RESEP = c.ID
			WHERE c.ID_PELAYANAN = '$id_ri'
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function get_kamar($id_ri){
		$sql = "
			SELECT
				a.ID,
				a.ID_PASIEN,
				b.KELAS,
				b.BIAYA,
				b.VISITE_DOKTER,
				b.BIAYA_VISITE,
				b.JASA_SARANA,
				c.NOMOR_BED
			FROM admum_rawat_inap a
			LEFT JOIN admum_kamar_rawat_inap b ON b.ID = a.ID_KAMAR
			LEFT JOIN admum_bed_rawat_inap c ON c.ID = a.ID_BED
			WHERE a.ID = '$id_ri'
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function get_lab($id_ri){
		$sql = "
			SELECT
				a.ID,
				a.PEMERIKSAAN,
				b.NAMA_PEMERIKSAAN,
				a.SUBTOTAL
			FROM rk_ri_laborat_detail a
			LEFT JOIN admum_setup_pemeriksaan b ON b.ID = a.PEMERIKSAAN
			LEFT JOIN rk_ri_laborat c ON a.ID_PEMERIKSAAN_RJ = c.ID
			WHERE c.ID_PELAYANAN = '$id_ri'
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function get_asuransi($id_ri){
		$sql = "
			SELECT
				a.*,
				b.NAMA_ASURANSI
			FROM asr_asuransi a
			LEFT JOIN asr_setup_asuransi b ON b.ID = a.ID_ASURANSI
			WHERE a.ID_RAWAT_INAP = '$id_ri'
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function simpan_tunai($id,$invoice,$tanggal,$bulan,$tahun,$waktu,$atas_nama,$total,$bayar,$kembali,$jenis_bayar,$sistem_bayar){
		$sql = "
			INSERT INTO apotek_transaksi(
				ID_RAWAT_INAP,
				INVOICE,
				TANGGAL,
				BULAN,
				TAHUN,
				WAKTU,
				ATAS_NAMA,
				TOTAL,
				BAYAR,
				KEMBALI,
				JENIS_BAYAR,
				SISTEM_BAYAR
			) VALUES (
				'$id',
				'$invoice',
				'$tanggal',
				'$bulan',
				'$tahun',
				'$waktu',
				'$atas_nama',
				'$total',
				'$bayar',
				'$kembali',
				'$jenis_bayar',
				'$sistem_bayar'
			)
		";
		$this->db->query($sql);
	}

	function simpan_non_tunai($id,$invoice,$tanggal,$bulan,$tahun,$waktu,$atas_nama,$total,$bayar,$tambahan,$kembali,$jenis_bayar,$sistem_bayar,$kartu_kredit,$nomor_kartu){
		$sql = "
			INSERT INTO apotek_transaksi(
				ID_RAWAT_INAP,
				INVOICE,
				TANGGAL,
				BULAN,
				TAHUN,
				WAKTU,
				ATAS_NAMA,
				TOTAL,
				BAYAR,
				TAMBAHAN,
				KEMBALI,
				JENIS_BAYAR,
				SISTEM_BAYAR,
				KARTU_KREDIT,
				NOMOR_KARTU
			) VALUES (
				'$id',
				'$invoice',
				'$tanggal',
				'$bulan',
				'$tahun',
				'$waktu',
				'$atas_nama',
				'$total',
				'$bayar',
				'$tambahan',
				'$kembali',
				'$jenis_bayar',
				'$sistem_bayar',
				'$kartu_kredit',
				'$nomor_kartu'
			)
		";
		$this->db->query($sql);
	}

	function simpan_closing($id_rajal, $id_pegawai, $shift, $tanggal, $pukul){
		$data = array(
			'ID_KASIR_RAJAL' => $id_rajal,
			'TANGGAL' => $tanggal,
			'WAKTU' => $pukul,
			'ID_PEGAWAI' => $id_pegawai,
			'SHIFT' => $shift
		);

	  	$this->db->insert('ap_tutup_kasir_rajal', $data);

		$data_update = array(
			'STATUS_CLOSING' => 1
		);

		$this->db->where('ID', $id_rajal);
    	$this->db->update('rk_pembayaran_kasir', $data_update);
	}

	function data_rawat_inap_id($id){
		$sql = "
			SELECT
				RI.ID,
				RI.ID_PASIEN,
				PASIEN.KODE_PASIEN,
				PASIEN.NAMA AS NAMA_PASIEN,
				PASIEN.JENIS_KELAMIN,
				PASIEN.TANGGAL_LAHIR,
				PASIEN.UMUR,
				RI.TANGGAL_MASUK,
				RI.ASAL_RUJUKAN,
				RI.NAMA_PENANGGUNGJAWAB,
				RI.SISTEM_BAYAR,
				RI.KELAS,
				RI.ID_KAMAR,
				RI.ID_DOKTER,
				KRI.KELAS,
				KRI.VISITE_DOKTER,
				PEG.NAMA AS NAMA_DOKTER,
				RI.ID_BED,
				BED.NO,
				BED.NOMOR_BED,
				PASIEN.STATUS,
				PASIEN.ALAMAT,
				PASIEN.PEKERJAAN
			FROM admum_rawat_inap RI
			LEFT JOIN rk_pasien PASIEN ON PASIEN.ID = RI.ID_PASIEN
			LEFT JOIN admum_kamar_rawat_inap KRI ON KRI.ID = RI.ID_KAMAR
			LEFT JOIN admum_bed_rawat_inap BED ON BED.ID = RI.ID_BED
			LEFT JOIN kepeg_pegawai PEG ON PEG.ID = RI.ID_DOKTER
			WHERE RI.ID = '$id'
		";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function data_hasil_pemeriksaan($id_pemeriksaan){
		$sql = "
			SELECT
				DET.ID,
				PRK.KODE,
				PRK.NAMA_PEMERIKSAAN,
				DET.HASIL,
				DET.NILAI_RUJUKAN,
				PRK.TARIF,
				DET.SUBTOTAL,
				DET.TANGGAL,
				DET.BULAN,
				DET.TAHUN
			FROM rk_ri_laborat_detail DET
			LEFT JOIN admum_setup_pemeriksaan PRK ON PRK.ID = DET.PEMERIKSAAN
			WHERE DET.ID_PEMERIKSAAN_RJ = '$id_pemeriksaan'
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_laborat_id($id){
		$sql = "
			SELECT
				LAB.ID,
				LAB.KODE_LAB,
				SET_LAB.JENIS_LABORAT,
				LAB.CITO,
				LAB.TOTAL_TARIF,
				LAB.TANGGAL,
				LAB.BULAN,
				LAB.TAHUN
			FROM rk_ri_laborat LAB
			LEFT JOIN admum_setup_jenis_laborat SET_LAB ON SET_LAB.ID = LAB.JENIS_LABORAT
			WHERE LAB.ID = '$id'
			ORDER BY LAB.ID DESC
		";
		$query = $this->db->query($sql);
		return $query->row();
	}

}

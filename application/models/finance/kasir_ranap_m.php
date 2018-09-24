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
				a.ID,
				a.ID_PASIEN,
				h.KODE_PASIEN,
				h.NAMA AS NAMA_PASIEN,
				h.JENIS_KELAMIN,
				h.NAMA_AYAH,
				h.NAMA_IBU,
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
				a.BIAYA_KAMAR_FIX AS BIAYA_KAMAR,
				a.BIAYA_CHARGE_KAMAR,
				b.JASA_SARANA,
				a.BIAYA_REG,
				b.BIAYA_VISITE,
				c.TOTAL AS TOTAL_TINDAKAN,
				d.JUMLAH_VISITE,
				e.TOTAL_TARIF AS TOTAL_LAB,
				f.TOTAL AS TOTAL_RESEP,
				g.DIRAWAT_SELAMA
			FROM admum_rawat_inap a
			LEFT JOIN admum_kamar_rawat_inap b ON b.ID = a.ID_KAMAR
			JOIN(
				SELECT 
					ID_PELAYANAN,
					SUM(TOTAL) AS TOTAL
				FROM rk_ri_tindakan
				GROUP BY ID_PELAYANAN
			) c ON c.ID_PELAYANAN = a.ID
			JOIN(
				SELECT
					ID_PELAYANAN,
					COUNT(ID) AS JUMLAH_VISITE
				FROM rk_ri_visite
				GROUP BY ID_PELAYANAN
			) d ON d.ID_PELAYANAN = a.ID
			JOIN rk_ri_laborat e ON e.ID_PELAYANAN = a.ID
			JOIN rk_ri_resep f ON f.ID_PELAYANAN = a.ID
			JOIN rk_ri_kondisi_akhir g ON g.ID_PELAYANAN = a.ID
			JOIN rk_pasien h ON h.ID = a.ID_PASIEN
			WHERE $where
			ORDER BY a.ID DESC
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function get_pasien_id($id){
		$sql = "
			SELECT
				a.ID,
				a.ID_PASIEN,
				h.KODE_PASIEN,
				h.NAMA AS NAMA_PASIEN,
				h.JENIS_KELAMIN,
				h.NAMA_AYAH,
				h.NAMA_IBU,
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
				a.BIAYA_KAMAR_FIX AS BIAYA_KAMAR,
				a.BIAYA_CHARGE_KAMAR,
				b.JASA_SARANA,
				b.BIAYA_VISITE,
				c.TOTAL AS TOTAL_TINDAKAN,
				d.JUMLAH_VISITE,
				e.TOTAL_TARIF AS TOTAL_LAB,
				f.TOTAL AS TOTAL_RESEP,
				g.DIRAWAT_SELAMA
			FROM admum_rawat_inap a
			LEFT JOIN admum_kamar_rawat_inap b ON b.ID = a.ID_KAMAR
			JOIN(
				SELECT 
					ID_PELAYANAN,
					SUM(TOTAL) AS TOTAL
				FROM rk_ri_tindakan
				GROUP BY ID_PELAYANAN
			) c ON c.ID_PELAYANAN = a.ID
			JOIN(
				SELECT
					ID_PELAYANAN,
					COUNT(ID) AS JUMLAH_VISITE
				FROM rk_ri_visite
				GROUP BY ID_PELAYANAN
			) d ON d.ID_PELAYANAN = a.ID
			JOIN rk_ri_laborat e ON e.ID_PELAYANAN = a.ID
			JOIN rk_ri_resep f ON f.ID_PELAYANAN = a.ID
			JOIN rk_ri_kondisi_akhir g ON g.ID_PELAYANAN = a.ID
			JOIN rk_pasien h ON h.ID = a.ID_PASIEN
			WHERE a.ID = '$id'
		";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function get_tindakan($id_ri){
		$sql = "
			SELECT
				a.ID,
				a.ID_PELAYANAN,
				a.TANGGAL
			FROM rk_ri_tindakan a
			WHERE a.ID_PELAYANAN = '$id_ri'
			ORDER BY a.ID ASC
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function get_tindakan_det($id_tindakan){
		$sql = "
			SELECT
				a.*,
				b.NAMA_TINDAKAN
			FROM rk_ri_tindakan_detail a
			JOIN admum_setup_tindakan b ON b.ID = a.ID_SETUP_TINDAKAN
			WHERE a.ID_TINDAKAN = '$id_tindakan'
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function get_visite($id_ri){
		$sql = "
			SELECT
				a.*,
				b.NAMA AS NAMA_DOKTER
			FROM rk_ri_visite a
			JOIN kepeg_pegawai b ON b.ID = a.ID_DOKTER
			WHERE a.ID_PELAYANAN = '$id_ri'
			AND a.STATUS_VISITE = '1'
			ORDER BY a.ID ASC
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function get_resep($id_ri){
		$sql = "
			SELECT
				a.ID,
				a.TANGGAL,
				a.KODE_RESEP,
				a.TOTAL
			FROM rk_ri_resep a
			WHERE a.ID_PELAYANAN = '$id_ri'
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function get_biaya_charge_kamar($id_ri){
		$sql = "
			SELECT
				a.ID,
				a.BIAYA_CHARGE_KAMAR,
				a.BIAYA_KAMAR_FIX
			FROM admum_rawat_inap a
			WHERE a.ID = '$id_ri'
		";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function get_biaya_jasa_sarana($id_ri){
		$sql = "
			SELECT
				a.ID,
				a.ID_KAMAR,
				b.JASA_SARANA
			FROM admum_rawat_inap a
			JOIN admum_kamar_rawat_inap b ON b.ID = a.ID_KAMAR
			WHERE a.ID = '$id_ri'
		";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function get_biaya_admin($id_ri){
		$sql = "
			SELECT
				a.ID,
				a.BIAYA_REG
			FROM admum_rawat_inap a
			WHERE a.ID = '$id_ri'
		";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function get_kamar($id_ri){
		$sql = "
			SELECT
				a.*
			FROM(
				SELECT
					a.ID,
					a.ID_PASIEN,
					b.KODE_KAMAR,
					b.KELAS,
					a.BIAYA_KAMAR_FIX,
					b.VISITE_DOKTER,
					c.NO,
					c.NOMOR_BED,
					IFNULL(d.DIRAWAT_SELAMA,0) AS DIRAWAT_SELAMA
				FROM admum_rawat_inap a
				LEFT JOIN admum_kamar_rawat_inap b ON b.ID = a.ID_KAMAR
				LEFT JOIN admum_bed_rawat_inap c ON c.ID = a.ID_BED
				LEFT JOIN rk_ri_kondisi_akhir d ON d.ID_PELAYANAN = a.ID
			) a
			WHERE a.ID = '$id_ri'
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function get_lab($id_ri){
		$sql = "
			SELECT
				a.ID,
				a.KODE_LAB,
				a.TANGGAL,
				a.TOTAL_TARIF
			FROM rk_ri_laborat a
			WHERE a.ID_PELAYANAN = '$id_ri'
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

	function simpan_tunai($id,$id_user,$shift,$invoice,$tanggal,$bulan,$tahun,$waktu,$atas_nama,$total,$bayar,$kembali,$jenis_bayar,$sistem_bayar){
		$sql = "
			INSERT INTO apotek_transaksi(
				ID_RAWAT_INAP,
				ID_USER,
				SHIFT,
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
				'$id_user',
				'$shift',
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

	function simpan_non_tunai($id,$id_user,$shift,$invoice,$tanggal,$bulan,$tahun,$waktu,$atas_nama,$total,$bayar,$tambahan,$kembali,$jenis_bayar,$sistem_bayar,$kartu_kredit,$nomor_kartu){
		$sql = "
			INSERT INTO apotek_transaksi(
				ID_RAWAT_INAP,
				ID_USER,
				SHIFT,
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
				'$id_user',
				'$shift',
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
				PASIEN.PEKERJAAN,
				PASIEN.JENIS_PASIEN,
				RI.BIAYA_REG
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

	function data_cetak_ri($id){
		$sql = "
			SELECT
				RI.ID,
				RI.ID_PASIEN,
				RI.TANGGAL_MASUK,
				RI.ASAL_RUJUKAN,
				RI.NAMA_PENANGGUNGJAWAB,
				RI.SISTEM_BAYAR,
				RI.KELAS,
				RI.ID_KAMAR,
				RI.ID_DOKTER,
				KRI.KELAS,
				KRI.BIAYA,
				KRI.VISITE_DOKTER,
				KRI.BIAYA_VISITE,
				KRI.JASA_SARANA,
				KA.DIRAWAT_SELAMA,
				RI.BIAYA_REG
			FROM admum_rawat_inap RI
			LEFT JOIN admum_kamar_rawat_inap KRI ON KRI.ID = RI.ID_KAMAR
			LEFT JOIN rk_ri_kondisi_akhir KA ON KA.ID_PELAYANAN = RI.ID
			WHERE RI.ID = '$id'
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_cetak_biaya_kamar($id){
		$sql = "
			SELECT
				a.ID,
				a.ID_KAMAR,
				b.KELAS,
				a.BIAYA_KAMAR_FIX,
				c.DIRAWAT_SELAMA
			FROM admum_rawat_inap a
			JOIN admum_kamar_rawat_inap b ON b.ID = a.ID_KAMAR
			JOIN rk_ri_kondisi_akhir c ON c.ID_PELAYANAN = a.ID
			WHERE a.ID = '$id'
		";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function data_cetak_tindakan($id){
		$sql = "
			SELECT
				a.ID,
				b.ID_PELAYANAN,
				a.ID_TINDAKAN,
				a.ID_SETUP_TINDAKAN,
				c.NAMA_TINDAKAN,
				a.SUBTOTAL AS HARGA,
				COUNT(a.ID_SETUP_TINDAKAN) AS JUMLAH,
				SUM(a.SUBTOTAL) AS TOTAL
			FROM rk_ri_tindakan_detail a
			JOIN rk_ri_tindakan b ON a.ID_TINDAKAN = b.ID
			JOIN admum_setup_tindakan c ON c.ID = a.ID_SETUP_TINDAKAN
			WHERE b.ID_PELAYANAN = '$id'
			GROUP BY a.ID_SETUP_TINDAKAN
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_cetak_resep($id){
		$sql = "
			SELECT
				a.ID,
				a.ID_PELAYANAN,
				SUM(a.TOTAL) AS TOTAL
			FROM rk_ri_resep a
			WHERE a.ID_PELAYANAN = '$id'
			GROUP BY a.ID_PELAYANAN
		";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function data_cetak_lab($id){
		$sql = "
			SELECT
				a.ID,
				b.ID_PELAYANAN,
				a.PEMERIKSAAN,
				c.NAMA_PEMERIKSAAN,
				a.SUBTOTAL
			FROM rk_ri_laborat_detail a
			JOIN rk_ri_laborat b ON a.ID_PEMERIKSAAN_RJ = b.ID
			JOIN admum_setup_pemeriksaan c ON a.PEMERIKSAAN = c.ID
			WHERE b.ID_PELAYANAN = '$id'
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_cetak_invoice($id){
		$sql = "
			SELECT
				a.ID,
				a.ID_RAWAT_INAP,
				a.INVOICE
			FROM apotek_transaksi a
			WHERE a.ID_RAWAT_INAP = '$id'
		";
		$query = $this->db->query($sql);
		return $query->row();
	}

}

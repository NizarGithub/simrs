<script type="text/javascript" src="<?php echo base_url(); ?>js-devan/jquery-1.11.1.min.js"></script>

<style type="text/css">
#view_tindakan_tambah, 
#view_tindakan_ubah,
#view_tambah_visite, 
#view_ubah_visite,
#view_tambah_gizi, 
#view_ubah_gizi,
#view_tambah_oksigen, 
#view_ubah_oksigen,
#view_diagnosa_tambah, 
#view_diagnosa_ubah,
#view_resep_tambah, 
#view_resep_ubah,
#view_tambah_infus, 
#view_ubah_infus,
#view_tambah_jasa_perawat, 
#view_ubah_jasa,
#view_icu, 
#view_operasi, 
#view_meninggal,
#form_surat_dokter,
.view_lab,
#view_laborat_tambah{
	display: none;
}

.loading_tabel img{
	margin-top: 75px;
	left: 45%;
	position: absolute;
    z-index: 9999;
    display: none;
}
</style>

<?php
$sess_user = $this->session->userdata('masuk_rs');
$id_user = $sess_user['id'];
?>

<script type="text/javascript">
$(document).ready(function(){
	<?php if($this->session->flashdata('sukses')){?>
		notif_simpan();
	<?php }else if($this->session->flashdata('ubah')){?>
        notif_ubah();
    <?php }else if($this->session->flashdata('hapus')){ ?>
    	notif_hapus();
    <?php } ?>

    toastr.options = {
      "closeButton": false,
      "debug": false,
      "newestOnTop": false,
      "progressBar": true,
      "positionClass": "toast-bottom-right",
      "preventDuplicates": false,
      "onclick": null,
      "showDuration": "300",
      "hideDuration": "1000",
      "timeOut": "5000",
      "extendedTimeOut": "1000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut"
    }

    $(".num_only").keydown(function (e) {
		// Allow: backspace, delete, tab, escape, enter and .
		if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
			 // Allow: Ctrl+A, Command+A
			(e.keyCode == 65 && ( e.ctrlKey === true || e.metaKey === true ) ) || 
			 // Allow: home, end, left, right, down, up
			(e.keyCode >= 35 && e.keyCode <= 40)) {
				 // let it happen, don't do anything
				 return;
		}
		// Ensure that it is a number and stop the keypress
		if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
			e.preventDefault();
		}
	});

    $('#btn_kembali').click(function(){
		window.location = "<?php echo base_url(); ?>poli/rk_pelayanan_ri_c";
	});

    //TINDAKAN

    data_tindakan();

    $('#btn_tambah').click(function(){
		$('#view_tindakan_tambah').show();
		$('#view_tindakan').hide();
		$('#view_tindakan_ubah').hide();
	});

	$('#btn_simpan').click(function(){
		var tr = $('#tabel_tambah_tindakan tbody').find('tr').length;
		if(tr == 0){
			toastr["error"]("Isi tindakan terlebih dahulu!", "Notifikasi");
		}else{
			$.ajax({
				url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/simpan_tindakan',
				data : $('#view_tindakan_tambah').serialize(),
				type : "POST",
				dataType : "json",
				success : function(res){
					notif_simpan();
					data_tindakan();
					$('#tabel_tambah_tindakan tbody tr').remove();
					$('#view_tindakan_tambah').hide();
					$('#view_tindakan').show();
				}
			});
		}
	});

	$('#batal').click(function(){
		$('#view_tindakan_tambah').hide();
		$('#view_tindakan').show();
		$('#view_tindakan_ubah').hide();
		$('#tabel_tambah_tindakan tbody tr').remove();
	});

	$('.btn_tindakan').click(function(){
		$('#popup_tindakan').click();
		load_tindakan();
	});

	$('.btn_pelaksana').click(function(){
		$('#popup_pelaksana').click();
		load_pelaksana();
	});

	// VISITE

	$('#dt_visite').click(function(){
		data_visite();
	});
	
	$('#btn_tambah_vst').click(function(){
		$('#view_visite').hide();
		$('#view_ubah_visite').hide();
		$('#view_tambah_visite').show();
	});

	$('#batalVisite').click(function(){
		$('#view_visite').show();
		$('#view_ubah_visite').hide();
		$('#view_tambah_visite').hide();
	});

	$('.btn_visite').click(function(){
		$('#popup_visite').click();
		load_visite();
	});

	$('.btn_dokter').click(function(){
		$('#popup_dokter').click();
		load_dokter();
	});

	$('#simpanVisite').click(function(){
		var id_visite = $('#id_visite').val();
		var id_dokter = $('#id_dokter').val();

		if(id_visite == ""){
			toastr["error"]("Silahkan isi visite dengan benar!", "Notifikasi");
		}else if(id_dokter == ""){
			toastr["error"]("Cari dokter terlebih dahulu!", "Notifikasi");
		}else{
			$.ajax({
				url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/simpan_visite',
				data : $('#view_tambah_visite').serialize(),
				type : "POST",
				dataType : "json",
				success : function(result){
					notif_simpan();
					data_visite();
					$('#view_visite').show();
					$('#view_tambah_visite').hide();
					$('#view_ubah_visite').hide();
				}
			});
		}
	});

	$('#simpanVisiteUbah').click(function(){
		$.ajax({
			url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/ubah_visite',
			data : $('#view_ubah_visite').serialize(),
			type : "POST",
			dataType : "json",
			success : function(result){
				notif_ubah();
				data_visite();
				$('#view_visite').show();
				$('#view_tambah_visite').hide();
				$('#view_ubah_visite').hide();
			}
		});
	});

	$('#ya_visite').click(function(){
		var id = $('#id_hapus_visite').val();
		var id_pelayanan = "<?php echo $id; ?>";

		$.ajax({
			url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/hapus_visite',
			data : {id:id,id_pelayanan:id_pelayanan},
			type : "POST",
			dataType : "json",
			success : function(result){
				notif_hapus();
				data_visite();
				$('#tidak_visite').click();
			}
		});
	});

	// GIZI

	$('#dt_gizi').click(function(){
		data_gizi();
	});

	$('#btn_tambah_gizi').click(function(){
		$('#view_tambah_gizi').show();
		$('#view_gizi').hide();
		$('#view_ubah_gizi').hide();
	});

	$('#batalGizi').click(function(){
		$('#view_tambah_gizi').hide();
		$('#view_gizi').show();
		$('#view_ubah_gizi').hide();
	});

	$('.btn_gizi').click(function(){
		$('#popup_gizi').click();
		load_gizi();
	});

	$('#simpanGizi').click(function(){
		var id_gizi = $('#id_gizi').val();
		if(id_gizi == ""){
			toastr["error"]("Isi gizi dengan benar!", "Notifikasi");
		}else{
			$.ajax({
				url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/simpan_gizi',
				data : $('#view_tambah_gizi').serialize(),
				type : "POST",
				dataType : "json",
				success : function(result){
					notif_simpan();
					data_gizi();
					$('#view_tambah_gizi').hide();
					$('#view_gizi').show();
					$('#view_ubah_gizi').hide();
				}
			});
		}
	});

	$('#simpanGiziUbah').click(function(){
		$.ajax({
			url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/ubah_gizi',
			data : $('#view_ubah_gizi').serialize(),
			type : "POST",
			dataType : "json",
			success : function(result){
				notif_ubah();
				data_gizi();
				$('#view_tambah_gizi').hide();
				$('#view_gizi').show();
				$('#view_ubah_gizi').hide();
			}
		});
	});

	$('#ya_gizi').click(function(){
		var id = $('#id_hapus_gizi').val();
		$.ajax({
			url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/hapus_gizi',
			data : {id:id},
			type : "POST",
			dataType : "json",
			success : function(result){
				notif_hapus();
				data_gizi();
				$('#tidak_gizi').click();
			}
		});
	});

	// OKSIGEN

	$('#dt_oksigen').click(function(){
		data_oksigen();
	});

	$('#btn_tambah_oksigen').click(function(){
		$('#view_tambah_oksigen').show();
		$('#view_ubah_oksigen').hide();
		$('#view_oksigen').hide();
	});

	$('#batalOksigen').click(function(){
		$('#view_tambah_oksigen').hide();
		$('#view_ubah_oksigen').hide();
		$('#view_oksigen').show();
	});

	$('#simpanOksigen').click(function(){
		var keterangan = $('#keterangan_oksigen').val();
		var jumlah = $('#jumlah_oksigen').val();
		var tarif = $('#tarif_oksigen').val();

		if(keterangan == ""){
			toastr["error"]("Keterangan harus diisi!", "Notifikasi");
		}else if(jumlah == ""){
			toastr["error"]("Isi jumlah oksigen dengan benar!", "Notifikasi");
		}else if(tarif == ""){
			toastr["error"]("Isi tarif oksigen dengan benar!", "Notifikasi");
		}else{
			$.ajax({
				url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/simpan_oksigen',
				data : $('#view_tambah_oksigen').serialize(),
				type : "POST",
				dataType : "json",
				success : function(result){
					notif_simpan();
					data_oksigen();
					$('#view_tambah_oksigen').hide();
					$('#view_ubah_oksigen').hide();
					$('#view_oksigen').show();
				}
			});
		}
	});

	$('#simpanOksigenUbah').click(function(){
		var keterangan = $('#keterangan_oksigen_ubah').val();
		var jumlah = $('#jumlah_oksigen_ubah').val();
		var tarif = $('#tarif_oksigen_ubah').val();

		if(keterangan == ""){
			toastr["error"]("Keterangan harus diisi!", "Notifikasi");
		}else if(jumlah == ""){
			toastr["error"]("Isi jumlah oksigen dengan benar!", "Notifikasi");
		}else if(tarif == ""){
			toastr["error"]("Isi tarif oksigen dengan benar!", "Notifikasi");
		}else{
			$.ajax({
				url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/ubah_oksigen',
				data : $('#view_ubah_oksigen').serialize(),
				type : "POST",
				dataType : "json",
				success : function(result){
					notif_ubah();
					data_oksigen();
					$('#view_tambah_oksigen').hide();
					$('#view_ubah_oksigen').hide();
					$('#view_oksigen').show();
				}
			});
		}
	});

	$('#ya_oksigen').click(function(){
		var id = $('#id_hapus_oksigen').val();
		$.ajax({
			url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/hapus_oksigen',
			data : {id:id},
			type : "POST",
			dataType : "json",
			success : function(result){
				notif_hapus();
				data_oksigen();
				$('#tidak_oksigen').click();
			}
		});
	});

	//INFUS

	$('#dt_infus').click(function(){
		data_infus();
	});

	$('#btn_tambah_if').click(function(){
		$('#view_tambah_infus').show();
		$('#view_infus').hide();

		$.ajax({
			url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/get_kode_infus',
			type : "POST",
			dataType : "json",
			success : function(kode){
				$('#kode_infus').val(kode);
			}
		});
	});

	$('#batalInfus').click(function(){
		$('#view_tambah_infus').hide();
		$('#view_infus').show();
	});

	$('#simpanInfus').click(function(){
		var jumlah = $('#jumlah_infus').val();
		var tarif = $('#tarif_infus').val();
		var pemakaian_selama = $('#pemakaian_selama_infus').val();

		if(jumlah == ""){
			toastr["error"]("Jumlah infus harus diisi!", "Notifikasi");
		}else if(tarif == ""){
			toastr["error"]("Tarif harus diisi!", "Notifikasi");
		}else if(pemakaian_selama == ""){
			toastr["error"]("Pemakaian infus harus diisi!", "Notifikasi");
		}else{
			$.ajax({
				url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/simpan_infus',
				data : $('#view_tambah_infus').serialize(),
				type : "POST",
				dataType : "json",
				success : function(result){
					notif_simpan();
					data_infus();
					$('#view_tambah_infus').hide();
					$('#view_infus').show();
					$('#view_tambah_infus').find("input").val("");
				}
			});
		}
	});

	$('#simpanUbahInfus').click(function(){
		var jumlah = $('#jumlah_infus_ubah').val();
		var tarif = $('#tarif_infus_ubah').val();
		var pemakaian_selama = $('#pemakaian_selama_infus_ubah').val();

		if(jumlah == ""){
			toastr["error"]("Jumlah infus harus diisi!", "Notifikasi");
		}else if(tarif == ""){
			toastr["error"]("Tarif harus diisi!", "Notifikasi");
		}else if(pemakaian_selama == ""){
			toastr["error"]("Pemakaian infus harus diisi!", "Notifikasi");
		}else{
			$.ajax({
				url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/ubah_infus',
				data : $('#view_ubah_infus').serialize(),
				type : "POST",
				dataType : "json",
				success : function(result){
					notif_ubah();
					data_infus();
					$('#view_ubah_infus').hide();
					$('#view_infus').show();
					$('#id_ubah_infus').val("");
				}
			});
		}
	});

	$('#ya_infus').click(function(){
		$.ajax({
			url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/hapus_infus',
			data : $('#form_hapus_infus').serialize(),
			type : "POST",
			dataType : "json",
			success : function(result){
				notif_hapus();
				data_infus();
				$('#tidak_infus').click();
			}
		});
	});

	//JASA PERAWAT

	$('#dt_jasa_prwt').click(function(){
		data_jasa();
	});

	$('#btn_tambah_jp').click(function(){
		$('#view_tambah_jasa_perawat').show();
		$('#view_ubah_jasa_perawat').hide();
		$('#view_jasa_perawat').hide();

		$.ajax({
			url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/get_kode_jasa',
			type : "POST",
			dataType : "json",
			success : function(kode){
				$('#kode_jasa').val(kode);
			}
		});
	});

	$('#batalJP').click(function(){
		$('#view_tambah_jasa_perawat').hide();
		$('#view_ubah_jasa_perawat').hide();
		$('#view_jasa_perawat').show();
		$('#view_tambah_jasa_perawat').find("input[type='text']").val("");
	});

	$('.btn_jasa').click(function(){
		$('#popup_jasa').click();
		load_jasa();
	});

	$('#simpanJP').click(function(){
		var id_jasa = $('#id_jasa').val();
		var jumlah = $('#jumlah_jasa').val();
		var pemakaian = $('#pemakaian_selama_jasa').val();

		if(id_jasa == ""){
			toastr["error"]("Jasa perawat harus diisi!", "Notifikasi");
		}else if(jumlah == ""){
			toastr["error"]("Jumlah harus diisi!", "Notifikasi");
		}else if(pemakaian == ""){
			toastr["error"]("Hari perawatan harus diisi!", "Notifikasi");
		}else{
			$.ajax({
				url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/simpan_jasa',
				data : $('#view_tambah_jasa_perawat').serialize(),
				type : "POST",
				dataType : "json",
				success : function(result){
					data_jasa();
					notif_simpan();
					$('#view_tambah_jasa_perawat').hide();
					$('#view_ubah_jasa_perawat').hide();
					$('#view_jasa_perawat').show();
					$('#view_tambah_jasa_perawat').find("input[type='text']").val("");
					$('#id_jasa').val("");
				}
			});
		}
	});

	$('#ya_jasa').click(function(){
		$.ajax({
			url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/hapus_jasa',
			data : $('#form_hapus_jasa').serialize(),
			type : "POST",
			dataType : "json",
			success : function(result){
				data_jasa();
				notif_hapus();
				$('#tidak_jasa').click();
			}
		});
	});

	// DIAGNOSA

	$('#dt_diagnosa').click(function(){
		data_diagnosa();
	});

	$('#btn_tambah_dg').click(function(){
		$('#view_diagnosa_tambah').show();
		$('#view_diagnosa_ubah').hide();
		$('#view_diagnosa').hide();
	});

	$('#batalDg').click(function(){
		$('#view_diagnosa_tambah').hide();
		$('#view_diagnosa_ubah').hide();
		$('#view_diagnosa').show();
		$('#view_diagnosa_tambah').find('textarea').val("");
		$('#view_diagnosa_tambah').find("input[type='text']").val("");
	});

	$('.btn_kasus_dg').click(function(){
		$('#popup_kasus_dg').click();
		load_kasus();
	});

	$('.btn_spesialistik_dg').click(function(){
		$('#popup_spesialistik_dg').click();
		load_spesialistik();
	});

	$('#simpanDg').click(function(){
		var diagnosa = $('#diagnosa').val();
		var tindakan = $('#tindakan_dg').val();
		var kasus = $('#id_kasus').val();
		var spesialistik = $('#id_spesialistik').val();

		if(diagnosa == ""){
			toastr["error"]("Diagnosa harus diisi dengan benar!", "Notifikasi");
		}else if(tindakan == ""){
			toastr["error"]("Tindakan harus diisi dengan benar!", "Notifikasi");
		}else if(kasus == ""){
			toastr["error"]("Kasus kosong!", "Notifikasi");
		}else if(spesialistik == ""){
			toastr["error"]("Spesialistik kosong!", "Notifikasi");
		}else{
			$.ajax({
				url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/simpan_diagnosa',
				data : $('#view_diagnosa_tambah').serialize(),
				type : "POST",
				dataType : "json",
				success : function(result){
					notif_simpan();
					data_diagnosa();
					$('#view_diagnosa_tambah').hide();
					$('#view_diagnosa_ubah').hide();
					$('#view_diagnosa').show();
					$('#view_diagnosa_tambah').find('textarea').val("");
					$('#view_diagnosa_tambah').find("input[type='hidden']").val("");
					$('#view_diagnosa_tambah').find("input[type='text']").val("");
				}
			});
		}
	});

	$('#simpanDgUbah').click(function(){
		var diagnosa = $('#diagnosa_ubah').val();
		var tindakan = $('#tindakan_dg_ubah').val();
		var kasus = $('#id_kasus_ubah').val();
		var spesialistik = $('#id_spesialistik_ubah').val();

		if(diagnosa == ""){
			toastr["error"]("Silahkan isi diagnosa dengan benar!", "Notifikasi");
			$('#diagnosa').focus();
		}else if(tindakan == ""){
			toastr["error"]("Silahkan isi tindakan dengan benar!", "Notifikasi");
			$('#tindakan_dg').focus();
		}else if(kasus == ""){
			toastr["error"]("Silahkan isi kasus dengan benar!", "Notifikasi");
		}else if(spesialistik == ""){
			toastr["error"]("Silahkan isi spesialistik dengan benar!", "Notifikasi");
		}else{
			$.ajax({
				url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/ubah_diagnosa',
				data : $('#view_diagnosa_ubah').serialize(),
				type : "POST",
				dataType : "json",
				success : function(result){
					notif_ubah();
					data_diagnosa();
					$('#view_diagnosa').show();
					$('#view_diagnosa_ubah').hide();
					$('#view_diagnosa_tambah').hide();
				}
			});
		}
	});

	$('#ya_dg').click(function(){
		var id = $('#id_hapus_dg').val();
		var id_pelayanan = "<?php echo $id; ?>";

		$.ajax({
			url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/hapus_diagnosa',
			data : {id:id,id_pelayanan:id_pelayanan},
			type : "POST",
			dataType : "json",
			success : function(result){
				$('#tidak_dg').click();
				notif_hapus();
				data_diagnosa();
			}
		});
	});

	//LABORAT

	$('#dt_laborat').click(function(){
		data_laborat();
	});

	$('#checkboxLab').click(function(){
		var cek = $('#checkboxLab').is(':checked');
		if(cek == true){
			$('.view_lab').show();
		}else{
			$('.view_lab').hide();
		}
	});

	$('#btn_tambah_lab').click(function(){
		$('#view_laborat_tambah').show();
		$('#view_laborat').hide();
		$('#view_laborat_ubah').hide();

		$.ajax({
			url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/get_kode_lab',
			type : "POST",
			dataType : "json",
			success : function(kode){
				$('#kode_lab').val(kode);
			}
		});
	});

	$('#batalLab').click(function(){
		$('#view_laborat_tambah').hide();
		$('#view_laborat').show();
		$('#view_laborat_ubah').hide();
		$('#tabel_tambah_pemeriksaan tbody tr').remove();
	});

	$('.btn_jenis_laborat').click(function(){
		$('#popup_laborat').click();
		load_laborat();
	});

	$('.btn_pemeriksaan').click(function(){
		$('#popup_pemeriksaan').click();
		load_pemeriksaan();
	});

	$('#simpanLab').click(function(){
		var id_laborat = $('#id_laborat').val();
		var pemeriksaan = $('#tabel_tambah_pemeriksaan tbody tr').length;
		var total_tarif = $('#total_tarif_pemeriksaan').val();
		var cito = $("input[name='cito']").is(":checked");

		if(id_laborat == ""){
		    toastr["error"]("Jenis Laborat harus diisi!", "Notifikasi");
		}else if(pemeriksaan == 0){
			toastr["error"]("Pemeriksaan harus diisi!", "Notifikasi");
		}else if(total_tarif == "" || total_tarif == "0"){
			toastr["error"]("Hitung total tarif dengan benar!", "Notifikasi");
		}else if(cito == false){
			toastr["error"]("Cito belum dipilih!", "Notifikasi");
		}else{
			$.ajax({
				url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/simpan_pemeriksaan',
				data : $('#view_laborat_tambah').serialize(),
				type : "POST",
				dataType : "json",
				success : function(result){
					data_laborat();
					notif_simpan();
					$('#id_laborat').val("");
					$('#jenis_laborat').val("");
					$('#tabel_tambah_pemeriksaan tbody tr').remove();
					$('#total_tarif_pemeriksaan').val("");
					$('#inlineRadio1').removeAttr('checked');
					$('#inlineRadio2').removeAttr('checked');
					$('#view_laborat_tambah').hide();
					$('#view_laborat').show();
				}
			});
		}
	});

	$('#ya_lab').click(function(){
		$.ajax({
			url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/hapus_laborat',
			data : $('#form_hapus_lab').serialize(),
			type : "POST",
			dataType : "json",
			success : function(result){
				$('#tidak_lab').click();
				data_laborat();
				notif_hapus();
			}
		});
	});

	//RESEP

	$('#dt_resep').click(function(){
		data_resep();
	});

	$('#btn_tambah_resep').click(function(){
		$('#view_resep_tambah').show();
		$('#view_resep').hide();
		$('#view_resep_ubah').hide();

		$.ajax({
			url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/get_kode_resep',
			type : "POST",
			dataType : "json",
			success : function(kode){
				$('#kode_resep').val(kode);
			}
		});
	});

	$('#batalResep').click(function(){
		$('#view_resep_tambah').hide();
		$('#view_resep').show();
		$('#view_resep_ubah').hide();
		$('#tabel_tambah_resep tbody tr').remove();
	});

	$('.btn_obat_resep').click(function(){
		$('#popup_resep').click();
		load_obat();
	});

	$('#simpanResep').click(function(){
		var tr = $("#tabel_tambah_resep tbody tr").length;
		if(tr == 0){
			toastr["error"]("Obat kosong!", "Notifikasi");
		}else{
			$.ajax({
				url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/simpan_resep',
				data : $('#view_resep_tambah').serialize(),
				type : "POST",
				dataType : "json",
				success : function(kode){
					$('#view_resep_tambah').hide();
					$('#view_resep').show();
					$('#view_resep_ubah').hide();
					$('#tabel_tambah_resep tbody tr').remove();
					data_resep();
					notif_simpan();
				}
			});
		}
	});

	$('#ya_resep').click(function(){
		var id = $('#id_hapus_resep').val();
		var id_pelayanan = "<?php echo $id; ?>";

		$.ajax({
			url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/hapus_resep',
			data : {id:id,id_pelayanan:id_pelayanan},
			type : "POST",
			dataType : "json",
			success : function(kode){
				$('#tidak_resep').click();
				data_resep();
				notif_hapus();
			}
		});
	});

	// KONDISI Akhir

	$('.btn_ruang_icu').click(function(){
		$('#popup_ruang_icu').click();
		load_ruang_icu();
	});

	$('.btn_ruang_opr').click(function(){
		$('#popup_ruang_operasi').click();
		load_ruang_operasi();
	});

	$('.btn_kamar_jenazah').click(function(){ 
		$('#popup_kamar_jenazah').click();
		load_kamar_jenazah();
	});

	$('.btn_lemari_jenazah').click(function(){
		$('#popup_lemari_jenazah').click();
		load_lemari_jenazah();
	});

	$('#kondisi_akhir').change(function(){
        var kondisi_akhir = $('#kondisi_akhir').val();
        if(kondisi_akhir == 'Operasi'){
        	$('#pindah_rawat_inap').hide();
        	$('#view_operasi').show();
        	$('#view_icu').hide();
        	$('#view_meninggal').hide();
        }else if(kondisi_akhir == 'ICU'){
        	$('#pindah_rawat_inap').hide();
        	$('#view_operasi').hide();
        	$('#view_icu').show();
        	$('#view_meninggal').hide();
        }else if(kondisi_akhir == 'Meninggal'){
        	$('#pindah_rawat_inap').hide();
        	$('#view_operasi').hide();
        	$('#view_icu').hide();
        	$('#view_meninggal').show();
        }else{
        	$('#pindah_rawat_inap').hide();
        	$('#view_operasi').hide();
        	$('#view_icu').hide();
        	$('#view_meninggal').hide();
        }

        if(kondisi_akhir == 'Pulang'){
        	$('#view_dirawat_selama').hide();
        }else{
        	$('#view_dirawat_selama').show();
        }
    });

	$('#simpanKA').click(function(){
		$('#popup_load').show();

		$.ajax({
			url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/simpan_ka',
			data : $('#view_kondisi_akhir').serialize(),
			type : "POST",
			dataType : "json",
			success : function(result){
				$('#popup_load').fadeOut();
				notif_simpan();
			}
		});
	});

	$('#batalKA').click(function(){
		$('#pindah_rawat_inap').hide();
		$('#view_operasi').hide();
		$('#view_icu').hide();
		$('#view_meninggal').hide();

		$('#pindah_rawat_inap').find("input[type='text']").val("");
		$('#view_operasi').find("input[type='text']").val("");
		$('#view_icu').find("input[type='text']").val("");
		$('#view_meninggal').find("input[type='text']").val("");

		$('#pindah_rawat_inap').find("input[type='hidden']").val("");
		$('#view_operasi').find("input[type='hidden']").val("");
		$('#view_icu').find("input[type='hidden']").val("");
		$('#view_meninggal').find("input[type='hidden']").val("");
	});

	//SURAT DOKTER

	$('#dt_surat_dokter').click(function(){
    	data_surat_dokter();
    });

    $('#btn_tambah_sd').click(function(){
    	$('#form_surat_dokter').show();
    	$('#view_surat_dokter').hide();
    });

    $('#batalSD').click(function(){
    	$('#form_surat_dokter').hide();
    	$('#view_surat_dokter').show();
    	$('#waktu_sd').val("");
    	$('#mulai_tgl_sd').val("");
    	$('#sampai_tgl_sd').val("");
    });

    $('#simpanSD').click(function(){
    	var waktu = $('#waktu_sd').val();
    	var tgl1 = $('#mulai_tgl_sd').val();
    	var tgl2 = $('#sampai_tgl_sd').val();

    	if(waktu == ""){
    		toastr["error"]("Mohon waktu istirahat diisi!", "Notifikasi");
    	}else if(tgl1 == ""){
    		toastr["error"]("Mulai tanggal kosong!", "Notifikasi");
    	}else if(tgl2 == ""){
    		toastr["error"]("Sampai tanggal kosong!", "Notifikasi");
    	}else{
    		$('#popup_load').show();

    		$.ajax({
				url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/simpan_surat_dokter',
				data : $('#form_surat_dokter').serialize(),
				type : "POST",
				dataType : "json",
				success : function(result){
					notif_simpan();
					$('#popup_load').fadeOut();
					$('#waktu_sd').val("");
					$('#mulai_tgl_sd').val("");
					$('#sampai_tgl_sd').val("");
					data_surat_dokter();
					$('#form_surat_dokter').hide();
					$('#view_surat_dokter').show();
				}
			});
    	}
    });

    $('#ya_sd').click(function(){
    	$.ajax({
			url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/hapus_surat_dokter',
			data : $('#form_hapus_surat_dokter').serialize(),
			type : "POST",
			dataType : "json",
			success : function(result){
				notif_hapus();
				data_surat_dokter();
				$('#tidak_sd').click();
			}
		});
    });

});

//TINDAKAN

function load_pelaksana(){
	var keyword = $('#cari_pelaksana').val();

	$.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/load_pelaksana',
		data : {keyword:keyword},
		type : "POST",
		dataType : "json",
		success : function(result){
			$tr = "";

			if(result == "" || result == null){
				$tr = "<tr><td colspan='5' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
			}else{
				var no = 0;

				for(var i=0; i<result.length; i++){
					no++;

					result[i].NAMA_POLI = result[i].NAMA_POLI==null?"-":result[i].NAMA_POLI;
					result[i].JABATAN = result[i].JABATAN==null?"-":result[i].JABATAN;

					$tr +=  '<tr style="cursor:pointer;" onclick="klik_pelaksana('+result[i].ID+');">'+
		                    '	<td style="text-align:center;">'+no+'</td>'+
		                    '   <td style="text-align:center;">'+result[i].NIP+'</td>'+
		                    '   <td>'+result[i].NAMA+'</td>'+
		                    '   <td>'+result[i].JABATAN+'</td>'+
		                    '   <td>'+result[i].NAMA_POLI+'</td>'+
		                    '</tr>';
				}
			}

			$('#tb_pelaksana tbody').html($tr);
		}
	});

	$('#cari_pelaksana').off('keyup').keyup(function(){
		load_pelaksana();
	});
}

function klik_pelaksana(id){
	$('#tutup_pelaksana').click();

	$.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/klik_pelaksana',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(row){
			$('#id_pelaksana').val(id);
			$('#pelaksana').val(row['NAMA']);
		}
	});
}

function load_tindakan(){
	$('.loading_tabel').show();
	var keyword = $('#cari_tindakan').val();

	$.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/load_tindakan',
		data : {keyword:keyword},
		type : "POST",
		dataType : "json",
		success : function(result){
			$tr = "";

			if(result == "" || result == null){
				$tr = "<tr><td colspan='4' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
			}else{
				var no = 0;

				for(var i=0; i<result.length; i++){ 
					no++;

					$tr += "<tr style='cursor:pointer;' onclick='klik_tindakan("+result[i].ID+");'>"+
								"<td style='text-align:center;'>"+no+"</td>"+
								"<td>"+result[i].KODE+"</td>"+
								"<td>"+result[i].NAMA_TINDAKAN+"</td>"+
								"<td style='text-align:right;'>"+formatNumber(result[i].TARIF)+"</td>"+
							"</tr>";
				}
			}

			$('#tb_tindakan tbody').html($tr);
			$('.loading_tabel').hide();
		}
	});
}

function klik_tindakan(id){
	$('#tutup_tindakan').click();
	var id_ubah = $('#id_ubah').val();

	if(id_ubah == ""){
		$.ajax({
			url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/klik_tindakan',
			data : {id:id},
			type : "POST",
			dataType : "json",
			success : function(result){
				$tr = "";

				for(var i=0; i<result.length; i++){
					var jumlah_data = $('#tr_'+result[i].ID).length;

					var aksi = "<button type='button' class='btn waves-light btn-danger btn-sm' onclick='deleteRow(this);'><i class='fa fa-times'></i></button>";

					if(jumlah_data > 0){
						var jumlah = $('#jumlah_'+result[i].ID).val();
						$('#jumlah_'+result[i].ID).val(parseInt(jumlah)+1);
					}else{
						$tr = "<tr id='tr_"+result[i].ID+"'>"+
								"<input type='hidden' name='id_setup_tindakan[]' value='"+result[i].ID+"'>"+
								"<input type='hidden' id='tarif_"+result[i].ID+"' value='"+result[i].TARIF+"'>"+
								"<input type='hidden' name='subtotal[]' id='subtotal_"+result[i].ID+"' value=''>"+
								"<td style='vertical-align:middle;'>"+result[i].NAMA_TINDAKAN+"</td>"+
								"<td style='vertical-align:middle; text-align:right;'>"+formatNumber(result[i].TARIF)+"</td>"+
								"<td>"+
									"<div class='col-md-12'>"+
					                    "<input type='text' class='form-control' name='jumlah[]' id='jumlah_"+result[i].ID+"' value='1' onkeyup='FormatCurrency(this); hitung_jumlah("+result[i].ID+");'>"+
				                    "</div>"+
								"</td>"+
								"<td style='vertical-align:middle; text-align:right;'><b id='subtotal_txt_"+result[i].ID+"'></b></td>"+
								"<td align='center'>"+aksi+"</td>"+
							  "</tr>";
					}
				}

				$('#tabel_tambah_tindakan tbody').append($tr);
				hitung_jumlah(id);
				hitung_total_tindakan();
			}
		});
	}else{
		$.ajax({
			url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/klik_tindakan',
			data : {id:id},
			type : "POST",
			dataType : "json",
			success : function(row){
				for(var i=0; i<row.length; i++){
					$('#id_tindakan_ubah').val(row[i].ID);
					$('#tindakan_txt').val(row[i].NAMA_TINDAKAN);
					$('#tarif_txt').val(formatNumber(row[i].TARIF));
					$('#jumlah_ubah').val("");
					$('#subtotal_ubah').val("");
					$('#jumlah_ubah').focus();
				}
			}
		});
	}
}

function deleteRow(btn){
	var row = btn.parentNode.parentNode;
	row.parentNode.removeChild(row);
	hitung_total_tindakan();
}

function hitung_jumlah(id){
	var tarif = $('#tarif_'+id).val();
	var jumlah = $('#jumlah_'+id).val();
	jumlah = jumlah.split(',').join('');

	if(jumlah == ""){
		jumlah = 0;
	}

	var subtotal = parseFloat(tarif) * parseFloat(jumlah);
	$('#subtotal_txt_'+id).html(formatNumber(subtotal));
	$('#subtotal_'+id).val(subtotal);
	hitung_total_tindakan();
}

function hitung_jumlah2(){
	var tarif = $('#tarif_txt').val();
	var jumlah = $('#jumlah_ubah').val();

	tarif = tarif.split(',').join('');
	jumlah = jumlah.split(',').join('');

	if(tarif == ""){
		tarif = 0;
	}

	if(jumlah == ""){
		jumlah = 0;
	}

	var subtotal = parseFloat(tarif) * parseFloat(jumlah);
	$('#subtotal_ubah').val(formatNumber(subtotal));
}

function hitung_total_tindakan(){
	var total = 0;
	$("input[name='subtotal[]']").each(function(idx,elm){
		var tarif = elm.value;
		total += parseFloat(tarif);
	});
	$('#total_tindakan').val(formatNumber(total));
}

function data_tindakan(){
	$('#popup_load').show();
	var id_tindakan = "<?php echo $id; ?>";

	$.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/data_tindakan_ri',
		data : {id_tindakan:id_tindakan},
		type : "POST",
		dataType : "json",
		success : function(result){
			$tr = "";

			if(result == "" || result == null){
				$tr = "<tr><td colspan='7' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
			}else{
				var no = 0;

				for(var i=0; i<result.length; i++){
					no++;

					var aksi =  '<button type="button" class="btn btn-success waves-effect waves-light btn-sm m-b-5" onclick="ubah_tindakan('+result[i].ID+');">'+
									'<i class="fa fa-pencil"></i>'+
								'</button>&nbsp;'+
						   		'<button type="button" class="btn btn-danger waves-effect waves-light btn-sm m-b-5" onclick="hapus_tindakan('+result[i].ID+');">'+
						   			'<i class="fa fa-trash"></i>'+
						   		'</button>';

					$tr += "<tr>"+
								"<td style='text-align:center;'>"+no+"</td>"+
								"<td style='text-align:center;'>"+formatTanggal(result[i].TANGGAL)+"</td>"+
								"<td>"+result[i].NAMA_TINDAKAN+"</td>"+
								"<td style='text-align:right;'>"+formatNumber(result[i].TARIF)+"</td>"+
								"<td style='text-align:center;'>"+result[i].JUMLAH+"</td>"+
								"<td style='text-align:right;'>"+formatNumber(result[i].SUBTOTAL)+"</td>"+
								"<td align='center'>"+aksi+"</td>"+
							"</tr>";
				}
			}

			$('#tabel_tindakan tbody').html($tr);
			$('#popup_load').fadeOut();
		}
	});
}

function ubah_tindakan(id){
	$('#view_tindakan').hide();
	$('#view_tindakan_ubah').show();
	$('#view_tindakan_tambah').hide();

	$.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/data_tindakan_ri_id',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(row){
			$('#id_ubah').val(id);
			$('#tanggal_ubah').val(row['TANGGAL']);
			$('#tindakan_txt').val(row['NAMA_TINDAKAN']);
			$('#tarif_txt').val(formatNumber(row['TARIF']));
			$('#jumlah_ubah').val(row['JUMLAH']);
			$('#subtotal_ubah').val(formatNumber(row['SUBTOTAL']));
		}
	});

	$('#batal_ubah').click(function(){
		$('#view_tindakan').show();
		$('#view_tindakan_ubah').hide();
		$('#view_tindakan_tambah').hide();
	});
}

function hapus_tindakan(id){
	$('#popup_hapus').click();

	$.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/data_tindakan_ri_id',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(row){
			$('#id_hapus').val(id);
			$('#msg').html('Apakah tindakan ini <b>'+row['NAMA_TINDAKAN']+'</b> ingin dihapus?');
		}
	});
}

// VISITE

function data_visite(){
	$('#popup_load').show();
	var id_pelayanan = "<?php echo $id; ?>";

	$.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/data_visite',
		data : {id_pelayanan:id_pelayanan},
		type : "POST",
		dataType : "json",
		success : function(result){
			$tr = "";

			if(result == "" || result == null){
				$tr = "<tr><td colspan='6' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
			}else{
				var no = 0;

				for(var i=0; i<result.length; i++){
					no++;

					var aksi =  '<button type="button" class="btn btn-success waves-effect waves-light btn-sm m-b-5" onclick="ubah_visite('+result[i].ID+');">'+
									'<i class="fa fa-pencil"></i>'+
								'</button>&nbsp;'+
						   		'<button type="button" class="btn btn-danger waves-effect waves-light btn-sm m-b-5" onclick="hapus_visite('+result[i].ID+');">'+
						   			'<i class="fa fa-trash"></i>'+
						   		'</button>';

					$tr += "<tr>"+
								"<td style='text-align:center;'>"+no+"</td>"+
								"<td style='text-align:center;'>"+formatTanggal(result[i].TANGGAL)+"</td>"+
								"<td>"+result[i].NAMA_VISITE+"</td>"+
								"<td style='text-align:right;'>"+formatNumber(result[i].TARIF)+"</td>"+
								"<td>"+result[i].NAMA_DOKTER+"</td>"+
								"<td align='center'>"+aksi+"</td>"+
							"</tr>";
				}
			}

			$('#tabel_visite tbody').html($tr);
			$('#popup_load').fadeOut();
		}
	});
}

function ubah_visite(id){
	$('#view_ubah_visite').show();
	$('#view_tambah_visite').hide();
	$('#view_visite').hide();
	var id_pelayanan = "<?php echo $id; ?>";

	$.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/data_visite_id',
		data : {id:id,id_pelayanan:id_pelayanan},
		type : "POST",
		dataType : "json",
		success : function(row){
			$('#id_ubah_visite').val(id);
			$('#tanggal_visite_ubah').val(row['TANGGAL']);
			$('#id_visite_ubah').val(row['ID_VISITE']);
			$('#visite_txt').val(row['NAMA_VISITE']);
			$('#tarif_visite_ubah').val(formatNumber(row['TARIF']));
			$('#id_dokter_ubah').val(row['ID_DOKTER']);
			$('#dokter_visite_txt').val(row['NAMA_DOKTER']);
		}
	});

	$('#batalVisiteUbah').click(function(){
		$('#view_ubah_visite').hide();
		$('#view_tambah_visite').hide();
		$('#view_visite').show();
		$('#id_ubah_visite').val("");
	});
}

function hapus_visite(id){
	$('#popup_hapus_visite').click();
	var id_pelayanan = "<?php echo $id; ?>";

	$.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/data_visite_id',
		data : {id:id,id_pelayanan:id_pelayanan},
		type : "POST",
		dataType : "json",
		success : function(row){
			$('#id_hapus_visite').val(id);
			$('#msg_visite').html('Apakah data <b>'+row['NAMA_VISITE']+'</b> ingin dihapus?');
		}
	});
}

function load_visite(){
	var keyword = $('#cari_visite').val();

	$.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/load_visite',
		data : {keyword:keyword},
		type : "POST",
		dataType : "json",
		success : function(result){
			$tr = "";

			if(result == "" || result == null){
				$tr = "<tr><td colspan='4' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
			}else{
				var no = 0;

				for(var i=0; i<result.length; i++){
					no++;

					$tr += "<tr style='cursor:pointer;' onclick='klik_visite("+result[i].ID+");'>"+
								"<td style='text-align:center;'>"+no+"</td>"+
								"<td style='text-align:center;'>"+result[i].KODE+"</td>"+
								"<td>"+result[i].NAMA_VISITE+"</td>"+
								"<td style='text-align:right;'>"+formatNumber(result[i].TARIF)+"</td>"+
							"</tr>";
				}
			}

			$('#tb_visite tbody').html($tr);
		}
	});

	$('#cari_visite').off('keyup').keyup(function(){
		load_visite();
	});
}

function klik_visite(id){
	$('#tutup_visite').click();

	$.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/klik_visite',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(row){
			var id_ubah = $('#id_ubah_visite').val();
			if(id_ubah == ""){
				$('#id_visite').val(id);
				$('#visite').val(row['NAMA_VISITE']);
				$('#tarif_visite').val(formatNumber(row['TARIF']));
				$('#id_visite_ubah').val("");
				$('#visite_txt').val("");
				$('#tarif_visite_ubah').val("");
			}else{
				$('#id_visite_ubah').val(id);
				$('#visite_txt').val(row['NAMA_VISITE']);
				$('#tarif_visite_ubah').val(formatNumber(row['TARIF']));
				$('#id_visite').val("");
				$('#visite').val("");
				$('#tarif_visite').val("");
			}

		}
	});
}

function load_dokter(){
	var keyword = $('#cari_dokter').val();

	$.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/load_dokter',
		data : {keyword:keyword},
		type : "POST",
		dataType : "json",
		success : function(result){
			$tr = "";

			if(result == "" || result == null){
				$tr = "<tr><td colspan='4' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
			}else{
				var no = 0;

				for(var i=0; i<result.length; i++){
					no++;

					$tr += "<tr style='cursor:pointer;' onclick='klik_dokter("+result[i].ID+");'>"+
								"<td style='text-align:center;'>"+no+"</td>"+
								"<td style='text-align:center;'>"+result[i].NIP+"</td>"+
								"<td>"+result[i].NAMA+"</td>"+
								"<td>"+result[i].JABATAN+"</td>"+
							"</tr>";
				}
			}

			$('#tb_dokter tbody').html($tr);
		}
	});

	$('#cari_dokter').off('keyup').keyup(function(){
		load_dokter();
	});
}

function klik_dokter(id){
	$('#tutup_dokter').click();

	$.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/klik_dokter',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(row){
			var id_ubah = $('#id_ubah_visite').val();
			if(id_ubah == ""){
				$('#id_dokter').val(id);
				$('#dokter_visite').val(row['NAMA']);
				$('#id_dokter_ubah').val("");
				$('#dokter_visite_txt').val("");
			}else{
				$('#id_dokter_ubah').val(id);
				$('#dokter_visite_txt').val(row['NAMA']);
				$('#id_dokter').val("");
				$('#dokter_visite').val("");
			}
		}
	});
}

// GIZI

function load_gizi(){
	var keyword = $('#cari_gizi').val();

	$.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/load_gizi',
		data : {keyword:keyword},
		type : "POST",
		dataType : "json",
		success : function(result){
			$tr = "";

			if(result == "" || result == null){
				$tr = "<tr><td colspan='4' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
			}else{
				var no = 0;

				for(var i=0; i<result.length; i++){
					no++;

					$tr += "<tr style='cursor:pointer;' onclick='klik_gizi("+result[i].ID+");'>"+
								"<td style='text-align:center;'>"+no+"</td>"+
								"<td>"+result[i].KODE+"</td>"+
								"<td>"+result[i].NAMA_GIZI+"</td>"+
								"<td style='text-align:right;'>"+formatNumber(result[i].TARIF)+"</td>"+
							"</tr>";
				}
			}

			$('#tb_gizi tbody').html($tr);
		}
	});

	$('#cari_gizi').off('keyup').keyup(function(){
		load_gizi();
	});
}

function klik_gizi(id){
	$('#tutup_gizi').click();

	$.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/klik_gizi',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(row){
			var id_ubah = $('#id_ubah_gizi').val();
			var txt = row['KODE']+' : '+row['NAMA_GIZI'];
			if(id_ubah == ""){
				$('#id_gizi').val(id);
				$('#gizi').val(txt);
				$('#tarif_gizi').val(formatNumber(row['TARIF']));

				$('#id_gizi_ubah').val("");
				$('#gizi_txt').val("");
				$('#tarif_gizi_ubah').val("");
			}else{
				$('#id_gizi_ubah').val(id);
				$('#gizi_txt').val(txt);
				$('#tarif_gizi_ubah').val(formatNumber(row['TARIF']));

				$('#id_gizi').val("");
				$('#gizi').val("");
				$('#tarif_gizi').val("");
			}
		}
	});
}

function data_gizi(){
	$('#popup_load').show();
	var id_pelayanan = "<?php echo $id; ?>";
	var id_pasien = "<?php echo $dt->ID_PASIEN; ?>";

	$.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/data_gizi',
		data : {id_pelayanan:id_pelayanan,id_pasien:id_pasien},
		type : "POST",
		dataType : "json",
		success : function(result){
			$tr = "";

			if(result == "" || result == null){
				$tr = "<tr><td colspan='5' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
			}else{
				var no = 0;

				for(var i=0; i<result.length; i++){
					no++;

					var aksi =  '<button type="button" class="btn btn-success waves-effect waves-light btn-sm m-b-5" onclick="ubah_gizi('+result[i].ID+');">'+
									'<i class="fa fa-pencil"></i>'+
								'</button>&nbsp;'+
						   		'<button type="button" class="btn btn-danger waves-effect waves-light btn-sm m-b-5" onclick="hapus_gizi('+result[i].ID+');">'+
						   			'<i class="fa fa-trash"></i>'+
						   		'</button>';

					var txt = result[i].KODE+' : '+result[i].NAMA_GIZI;

					$tr += "<tr>"+
								"<td style='text-align:center;'>"+no+"</td>"+
								"<td style='text-align:center;'>"+formatTanggal(result[i].TANGGAL)+"</td>"+
								"<td>"+txt+"</td>"+
								"<td style='text-align:right;'>"+formatNumber(result[i].TARIF)+"</td>"+
								"<td align='center'>"+aksi+"</td>"+
							"</tr>";
				}
			}

			$('#tabel_gizi tbody').html($tr);
			$('#popup_load').fadeOut();
		}
	});
}

function ubah_gizi(id){
	$('#view_ubah_gizi').show();
	$('#view_gizi').hide();
	$('#view_tambah_gizi').hide();

	var id_pelayanan = "<?php echo $id; ?>";
	var id_pasien = "<?php echo $dt->ID_PASIEN; ?>";

	$.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/data_gizi_id',
		data : {id:id,id_pelayanan:id_pelayanan,id_pasien:id_pasien},
		type : "POST",
		dataType : "json",
		success : function(row){
			$('#id_ubah_gizi').val(id);
			$('#id_gizi_ubah').val(row['ID_GIZI']);
			var txt = row['KODE']+' : '+row['NAMA_GIZI'];
			$('#gizi_txt').val(txt);
			$('#tarif_gizi_ubah').val(formatNumber(row['TARIF']));
		}
	});

	$('#batalGiziUbah').click(function(){
		$('#view_ubah_gizi').hide();
		$('#view_gizi').show();
		$('#view_tambah_gizi').hide();
		$('#id_ubah_gizi').val("");
	});
}

function hapus_gizi(id){
	$('#popup_hapus_gizi').click();
	var id_pelayanan = "<?php echo $id; ?>";
	var id_pasien = "<?php echo $dt->ID_PASIEN; ?>";

	$.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/data_gizi_id',
		data : {id:id,id_pelayanan:id_pelayanan,id_pasien:id_pasien},
		type : "POST",
		dataType : "json",
		success : function(row){
			$('#id_hapus_gizi').val(id);
			var txt = row['KODE']+' : '+row['NAMA_GIZI'];
			$('#msg_gizi').html('Apakah data <b>'+txt+'</b> ingin dihapus?');
		}
	});
}

// Oksigen

function data_oksigen(){
	$('#popup_load').show();
	var id_pelayanan = "<?php echo $id; ?>";
	var id_pasien = "<?php echo $dt->ID_PASIEN; ?>";

	$.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/data_oksigen',
		data : {id_pelayanan:id_pelayanan,id_pasien:id_pasien},
		type : "POST",
		dataType : "json",
		success : function(result){
			$tr = "";

			if(result == "" || result == null){
				$tr = "<tr><td colspan='8' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
			}else{
				var no = 0;

				for(var i=0; i<result.length; i++){
					no++;

					var aksi =  '<button type="button" class="btn btn-success waves-effect waves-light btn-sm m-b-5" onclick="ubah_oksigen('+result[i].ID+');">'+
									'<i class="fa fa-pencil"></i>'+
								'</button>&nbsp;'+
						   		'<button type="button" class="btn btn-danger waves-effect waves-light btn-sm m-b-5" onclick="hapus_oksigen('+result[i].ID+');">'+
						   			'<i class="fa fa-trash"></i>'+
						   		'</button>';
					$tr += "<tr>"+
								"<td style='text-align:center;'>"+no+"</td>"+
								"<td style='text-align:center;'>"+formatTanggal(result[i].TANGGAL)+"</td>"+
								"<td>"+result[i].KETERANGAN+"</td>"+
								"<td style='text-align:center;'>"+result[i].JUMLAH+"</td>"+
								"<td style='text-align:right;'>"+formatNumber(result[i].TARIF)+"</td>"+
								"<td style='text-align:right;'>"+formatNumber(result[i].TOTAL)+"</td>"+
								"<td style='text-align:center;'>"+result[i].PEMAKAIAN_SELAMA+" Hari</td>"+
								"<td align='center'>"+aksi+"</td>"+
							"</tr>";
				}
			}

			$('#tabel_oksigen tbody').html($tr);
			$('#popup_load').fadeOut();
		}
	});
}

function ubah_oksigen(id){
	$('#view_ubah_oksigen').show();
	$('#view_oksigen').hide();
	$('#view_tambah_oksigen').hide();

	var id_pelayanan = "<?php echo $id; ?>";
	var id_pasien = "<?php echo $dt->ID_PASIEN; ?>";

	$.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/data_oksigen_id',
		data : {id:id,id_pelayanan:id_pelayanan,id_pasien:id_pasien},
		type : "POST",
		dataType : "json",
		success : function(row){
			$('#id_ubah_oksigen').val(id);
			$('#keterangan_oksigen_ubah').val(row['KETERANGAN']);
			$('#jumlah_oksigen_ubah').val(formatNumber(row['JUMLAH']));
			$('#tarif_oksigen_ubah').val(formatNumber(row['TARIF']));
		}
	});

	$('#batalOksigenUbah').click(function(){
		$('#view_ubah_oksigen').hide();
		$('#view_oksigen').show();
		$('#view_tambah_oksigen').hide();
		$('#id_ubah_oksigen').val("");
	});
}

function hapus_oksigen(id){
	$('#popup_hapus_oksigen').click();

	var id_pelayanan = "<?php echo $id; ?>";
	var id_pasien = "<?php echo $dt->ID_PASIEN; ?>";

	$.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/data_oksigen_id',
		data : {id:id,id_pelayanan:id_pelayanan,id_pasien:id_pasien},
		type : "POST",
		dataType : "json",
		success : function(row){
			$('#id_hapus_oksigen').val(id);
			$('#msg_oksigen').html('Apakah data ini ingin dihapus?');
		}
	});	
}

function hitung_oksigen(){
	var tarif_oksigen = $('#tarif_oksigen').val();
	var jumlah = $('#jumlah_oksigen').val();

	tarif_oksigen = tarif_oksigen.split(',').join('');
	jumlah = jumlah.split(',').join('');

	if(tarif_oksigen == ""){
		tarif_oksigen = 0;
	}

	if(jumlah == ""){
		jumlah = 0;
	}

	var total = parseFloat(tarif_oksigen) * parseFloat(jumlah);
	$('#total_oksigen').val(formatNumber(total));
}

//INFUS

function data_infus(){
	$('#popup_load').show();
	var id = "<?php echo $id; ?>";

	$.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/data_infus',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(result){
			$tr = "";

			if(result == "" || result == null){
				$tr = "<tr><td colspan='7' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
			}else{
				var no = 0;

				for(var i=0; i<result.length; i++){
					no++;

					var aksi =  '<button type="button" class="btn btn-success waves-effect waves-light btn-sm m-b-5" onclick="ubah_infus('+result[i].ID+');">'+
									'<i class="fa fa-pencil"></i>'+
								'</button>&nbsp;'+
						   		'<button type="button" class="btn btn-danger waves-effect waves-light btn-sm m-b-5" onclick="hapus_infus('+result[i].ID+');">'+
						   			'<i class="fa fa-trash"></i>'+
						   		'</button>';

					$tr += "<tr>"+
								"<td style='text-align:center;'>"+no+"</td>"+
								"<td style='text-align:center;'>"+result[i].KODE+"</td>"+
								"<td style='text-align:center;'>"+result[i].JUMLAH+"</td>"+
								"<td style='text-align:right;'>"+formatNumber(result[i].TARIF)+"</td>"+
								"<td style='text-align:right;'>"+formatNumber(result[i].TOTAL)+"</td>"+
								"<td style='text-align:center;'>"+result[i].PEMAKAIAN_SELAMA+" Hari</td>"+
								"<td align='center'>"+aksi+"</td>"+
							"</tr>";
				}
			}

			$('#tabel_infus tbody').html($tr);
			$('#popup_load').fadeOut();
		}
	});
}

function ubah_infus(id){
	$('#view_ubah_infus').show();
	$('#view_infus').hide();

	var id_pelayanan = "<?php echo $id; ?>";
	var id_pasien = "<?php echo $dt->ID_PASIEN; ?>";

	$.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/data_infus_id',
		data : {id:id,id_pelayanan:id_pelayanan,id_pasien:id_pasien},
		type : "POST",
		dataType : "json",
		success : function(row){
			$('#id_ubah_infus').val(id);
			$('#jumlah_infus_ubah').val(row['JUMLAH']);
			$('#tarif_infus_ubah').val(formatNumber(row['TARIF']));
			$('#total_infus_ubah').val(formatNumber(row['TOTAL']));
			$('#pemakaian_selama_infus_ubah').val(row['PEMAKAIAN_SELAMA']);
		}
	});

	$('#batalUbahInfus').click(function(){
		$('#view_ubah_infus').hide();
		$('#view_infus').show();
		$('#id_ubah_infus').val("");
	});
}

function hapus_infus(id){
	$('#popup_hapus_infus').click();

	var id_pelayanan = "<?php echo $id; ?>";
	var id_pasien = "<?php echo $dt->ID_PASIEN; ?>";

	$.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/data_infus_id',
		data : {id:id,id_pelayanan:id_pelayanan,id_pasien:id_pasien},
		type : "POST",
		dataType : "json",
		success : function(row){
			$('#id_hapus_infus').val(id);
			$('#msg_infus').html('Apakah infus <b>'+row['KODE']+'</b> ini ingin dihapus?');
		}
	});
}

function hitung_infus(){
	var id_ubah = $('#id_ubah_infus').val();
	var jumlah = "";
	var tarif = "";

	if(id_ubah == ""){
		jumlah = $('#jumlah_infus').val();
		tarif = $('#tarif_infus').val();
	}else{
		jumlah = $('#jumlah_infus_ubah').val();
		tarif = $('#tarif_infus_ubah').val();
	}

	jumlah = jumlah.split(',').join('');
	tarif = tarif.split(',').join('');

	if(jumlah == ""){
		jumlah = 0;
	}

	if(tarif == ""){
		tarif = 0;
	}

	var total = parseFloat(jumlah) * parseFloat(tarif);

	if(id_ubah == ""){
		$('#total_infus').val(formatNumber(total));
	}else{
		$('#total_infus_ubah').val(formatNumber(total));
	}
}

//JASA PERAWAT

function data_jasa(){
	$('#popup_load').show();

	var id = "<?php echo $id; ?>";
	var id_pasien = "<?php echo $dt->ID_PASIEN; ?>";

	$.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/data_jasa',
		data : {id:id,id_pasien:id_pasien},
		type : "POST",
		dataType : "json",
		success : function(result){
			$tr = "";

			if(result == "" || result == null){
				$tr = "<tr><td colspan='8' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
			}else{
				var no = 0;

				for(var i=0; i<result.length; i++){
					no++;

					var aksi =  '<button type="button" class="btn btn-success waves-effect waves-light btn-sm m-b-5" onclick="ubah_jasa('+result[i].ID+');">'+
									'<i class="fa fa-pencil"></i>'+
								'</button>&nbsp;'+
						   		'<button type="button" class="btn btn-danger waves-effect waves-light btn-sm m-b-5" onclick="hapus_jasa('+result[i].ID+');">'+
						   			'<i class="fa fa-trash"></i>'+
						   		'</button>';

					$tr += "<tr>"+
								"<td style='text-align:center;'>"+no+"</td>"+
								"<td style='text-align:center;'>"+result[i].KODE+"</td>"+
								"<td>"+result[i].PERAWATAN+"</td>"+
								"<td style='text-align:right;'>"+formatNumber(result[i].TARIF)+"</td>"+
								"<td style='text-align:center;'>"+result[i].JUMLAH+"</td>"+
								"<td style='text-align:center;'>"+result[i].PERAWATAN_SELAMA+" Hari</td>"+
								"<td style='text-align:right;'>"+formatNumber(result[i].TOTAL_SEMUA)+"</td>"+
								"<td align='center'>"+aksi+"</td>"+
							"</tr>";
				}
			}

			$('#tabel_jasa tbody').html($tr);
			$('#popup_load').fadeOut();
		}
	});
}

function load_jasa(){
	var keyword = $('#cari_jasa').val();

	$.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/load_jasa',
		data : {keyword:keyword},
		type : "POST",
		dataType : "json",
		success : function(result){
			$tr = "";

			if(result == "" || result == null){
				$tr = "<tr><td colspan='3' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
			}else{
				var no = 0;

				for(var i=0; i<result.length; i++){
					no++;

					$tr += "<tr style='cursor:pointer;' onclick='klik_jasa("+result[i].ID+");'>"+
								"<td style='text-align:center;'>"+no+"</td>"+
								"<td style='text-align:center;'>"+result[i].KODE+"</td>"+
								"<td>"+result[i].PERAWATAN+"</td>"+
								"<td style='text-align:right;'>"+formatNumber(result[i].TARIF)+"</td>"+
							"</tr>";
				}
			}

			$('#tb_jasa tbody').html($tr);
		}
	});

	$('#cari_jasa').off('keyup').keyup(function(){
		load_jasa();
	});
}

function klik_jasa(id){
	$('#tutup_jasa').click();

	$.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/klik_jasa',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(row){
			var id_ubah = $('#id_ubah_jasa').val();
			if(id_ubah == ""){
				$('#id_jasa').val(id);
				$('#jasa_txt').val(row['PERAWATAN']);
				$('#tarif_jasa').val(formatNumber(row['TARIF']));
				// $('#id_ubah_jasa').val("");
				// $('#kasus_dg_ubah').val("");
			}else{
				$('#id_jasa').val("");
				$('#jasa_txt').val("");
				// $('#id_').val(id);
				// $('#kasus_dg_ubah').val(row['NAMA_KASUS']);
			}
		}
	});
}

function hitung_jasa(){
	var id_ubah = $('#id_ubah_jasa').val();
	var tarif = "";
	var jumlah = "";

	if(id_ubah == ""){
		tarif = $('#tarif_jasa').val();
		jumlah = $('#jumlah_jasa').val();
	}else{

	}

	tarif = tarif.split(',').join('');
	jumlah = jumlah.split(',').join('');

	if(tarif == ""){
		tarif = 0;
	}

	if(jumlah == ""){
		jumlah = 0;
	}

	var total = parseFloat(tarif) * parseFloat(jumlah);

	if(id_ubah == ""){
		$('#total_jasa').val(formatNumber(total));
	}else{

	}
}

function hitung_jasa_hari(){
	var total = $('#total_jasa').val();
	var hari = $('#pemakaian_selama_jasa').val();

	total = total.split(',').join('');
	hari = hari.split(',').join('');

	if(total == ""){
		total = 0;
	}

	if(hari == ""){
		hari = 0;
	}

	var total_semua = parseFloat(total) * parseFloat(hari);
	$('#total_semua_jasa').val(formatNumber(total_semua));
}

function hapus_jasa(id){
	$('#popup_hapus_jasa').click();

	var id_pelayanan = "<?php echo $id; ?>";
	var id_pasien = "<?php echo $dt->ID_PASIEN; ?>";

	$.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/data_jasa_id',
		data : {id:id,id_pelayanan:id_pelayanan,id_pasien:id_pasien},
		type : "POST",
		dataType : "json",
		success : function(row){
			$('#id_hapus_jasa').val(id);
			$('#msg_jasa').html('Apakah jasa perawat <b>'+row['KODE']+'</b> ini ingin dihapus?');	
		}
	});
}

// DIAGNOSA

function load_kasus(){
	var keyword = $('#cari_kasus_dg').val();

	$.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/load_kasus',
		data : {keyword:keyword},
		type : "POST",
		dataType : "json",
		success : function(result){
			$tr = "";

			if(result == "" || result == null){
				$tr = "<tr><td colspan='3' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
			}else{
				var no = 0;

				for(var i=0; i<result.length; i++){
					no++;

					$tr += "<tr style='cursor:pointer;' onclick='klik_kasus("+result[i].ID+");'>"+
								"<td style='text-align:center;'>"+no+"</td>"+
								"<td>"+result[i].KODE+"</td>"+
								"<td>"+result[i].NAMA_KASUS+"</td>"+
							"</tr>";
				}
			}

			$('#tb_kasus_dg tbody').html($tr);
		}
	});

	$('#cari_kasus_dg').off('keyup').keyup(function(){
		load_kasus_diagnosa();
	});
}

function klik_kasus(id){
	$('#tutup_kasus_dg').click();
	
	$.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/klik_kasus',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(row){
			var id_ubah = $('#id_ubah_dg').val();
			if(id_ubah == ""){
				$('#id_kasus').val(id);
				$('#kasus_dg').val(row['NAMA_KASUS']);
				$('#id_kasus_ubah').val("");
				$('#kasus_dg_ubah').val("");
			}else{
				$('#id_kasus').val("");
				$('#kasus_dg').val("");
				$('#id_kasus_ubah').val(id);
				$('#kasus_dg_ubah').val(row['NAMA_KASUS']);
			}
		}
	});
}

function load_spesialistik(){
	var keyword = $('#cari_spesialistik_dg').val();

	$.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/load_spesialistik',
		data : {keyword:keyword},
		type : "POST",
		dataType : "json",
		success : function(result){
			$tr = "";

			if(result == "" || result == null){
				$tr = "<tr><td colspan='3' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
			}else{
				var no = 0;

				for(var i=0; i<result.length; i++){
					no++;

					$tr += "<tr style='cursor:pointer;' onclick='klik_spesialistik("+result[i].ID+");'>"+
								"<td style='text-align:center;'>"+no+"</td>"+
								"<td>"+result[i].KODE+"</td>"+
								"<td>"+result[i].NAMA_SPESIALISTIK+"</td>"+
							"</tr>";
				}
			}

			$('#tb_spesialistik_dg tbody').html($tr);
		}
	});

	$('#cari_spesialistik_dg').off('keyup').keyup(function(){
		load_spesialistik_diagnosa();
	});
}

function klik_spesialistik(id){
	$('#tutup_spesialistik_dg').click();
	
	$.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/klik_spesialistik',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(row){
			var id_ubah = $('#id_ubah_dg').val();
			if(id_ubah == ""){
				$('#id_spesialistik').val(id);
				$('#spesialistik_dg').val(row['NAMA_SPESIALISTIK']);
				$('#id_spesialistik_ubah').val("");
				$('#spesialistik_dg_ubah').val("");
			}else{
				$('#id_spesialistik').val("");
				$('#spesialistik_dg').val("");
				$('#id_spesialistik_ubah').val(id);
				$('#spesialistik_dg_ubah').val(row['NAMA_SPESIALISTIK']);
			}
		}
	});
}

function data_diagnosa(){
	$('#popup_load').show();
	var id = "<?php echo $id; ?>";

	$.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/data_diagnosa',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(result){
			$tr = "";

			if(result == "" || result == null){
				$tr = "<tr><td colspan='5' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
			}else{
				var no = 0;

				for(var i=0; i<result.length; i++){
					no++;

					var aksi =  '<button type="button" class="btn btn-success waves-effect waves-light btn-sm m-b-5" onclick="ubah_diagnosa('+result[i].ID+');">'+
									'<i class="fa fa-pencil"></i>'+
								'</button>&nbsp;'+
						   		'<button type="button" class="btn btn-danger waves-effect waves-light btn-sm m-b-5" onclick="hapus_diagnosa('+result[i].ID+');">'+
						   			'<i class="fa fa-trash"></i>'+
						   		'</button>';

					$tr += "<tr>"+
								"<td style='text-align:center;'>"+no+"</td>"+
								"<td style='text-align:center;'>"+formatTanggal(result[i].TANGGAL)+"</td>"+
								"<td>"+result[i].DIAGNOSA+"</td>"+
								"<td>"+result[i].TINDAKAN+"</td>"+
								"<td align='center'>"+aksi+"</td>"+
							"</tr>";
				}
			}

			$('#tabel_diagnosa tbody').html($tr);
			$('#popup_load').fadeOut();
		}
	});
}

function ubah_diagnosa(id){
	$('#view_diagnosa_ubah').show();
	$('#view_diagnosa').hide();
	$('#view_diagnosa_tambah').hide();
	var id_pelayanan = "<?php echo $id; ?>";

	$.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/data_diagnosa_id',
		data : {id:id,id_pelayanan:id_pelayanan},
		type : "POST",
		dataType : "json",
		success : function(row){
			$('#id_ubah_dg').val(id);
			$('#diagnosa_ubah').val(row['DIAGNOSA']);
			$('#tindakan_dg_ubah').val(row['TINDAKAN']);
		}
	});

	$('#batalDgUbah').click(function(){
		$('#view_diagnosa_ubah').hide();
		$('#view_diagnosa').show();
		$('#view_diagnosa_tambah').hide();
		$('#id_ubah_dg').val("");
	});
}

function hapus_diagnosa(id){
	$('#popup_hapus_dg').click();
	var id_pelayanan = "<?php echo $id; ?>";

	$.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/data_diagnosa_id',
		data : {id:id,id_pelayanan:id_pelayanan},
		type : "POST",
		dataType : "json",
		success : function(row){
			$('#id_hapus_dg').val(id);
			$('#msg_dg').html('Apakah data <b>'+row['DIAGNOSA']+'</b> ingin dihapus?');
		}
	});
}

// LABORAT

function load_laborat(){
	var keyword = $('#cari_laborat').val();

	$.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/load_laborat',
		data : {keyword:keyword},
		type : "POST",
		dataType : "json",
		success : function(result){
			$tr = "";

			if(result == "" || result == null){
				$tr = "<tr><td colspan='2' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
			}else{
				var no = 0;

				for(var i=0; i<result.length; i++){
					no++;

					$tr += "<tr style='cursor:pointer;' onclick='klik_laborat("+result[i].ID+");'>"+
								"<td style='text-align:center;'>"+no+"</td>"+
								"<td>"+result[i].JENIS_LABORAT+"</td>"+
							"</tr>";
				}
			}

			$('#tb_laborat tbody').html($tr);
		}
	});

	$('#cari_laborat').off('keyup').keyup(function(){
		load_laborat();
	});
}

function klik_laborat(id){
	$('#tutup_laborat').click();

	$.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/klik_laborat',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(row){
			$('#id_laborat').val(id);
			$('#jenis_laborat').val(row['JENIS_LABORAT']);
		}
	});
}

function load_pemeriksaan(){
	var keyword = $('#cari_pemeriksaan').val();

	$.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/load_pemeriksaan',
		data : {keyword:keyword},
		type : "POST",
		dataType : "json",
		success : function(result){
			$tr = "";

			if(result == "" || result == null){
				$tr = "<tr><td colspan='4' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
			}else{
				var no = 0;

				for(var i=0; i<result.length; i++){
					no++;

					$tr += "<tr style='cursor:pointer;' onclick='klik_pemeriksaan("+result[i].ID+");'>"+
								"<td style='text-align:center;'>"+no+"</td>"+
								"<td>"+result[i].KODE+"</td>"+
								"<td>"+result[i].NAMA_PEMERIKSAAN+"</td>"+
								"<td style='text-align:right;'>"+formatNumber(result[i].TARIF)+"</td>"+
							"</tr>";
				}
			}

			$('#tb_pemeriksaan tbody').html($tr);
		}
	});
}

function klik_pemeriksaan(id){
	$('#tutup_pemeriksaan').click();

	$.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/klik_pemeriksaan',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(result){
			$tr = "";

			for(var i=0; i<result.length; i++){
				var jumlah_data = $('#tr2_'+result[i].ID).length;

				var aksi = "<button type='button' class='btn waves-light btn-danger btn-sm' onclick='deleteRow2(this);'><i class='fa fa-times'></i></button>";

				if(jumlah_data > 0){
					var jumlah = $('#jumlah_pemeriksaan_'+result[i].ID).val();
					$('#jumlah_pemeriksaan_'+result[i].ID).val(parseInt(jumlah)+1);
				}else{
					$tr = "<tr id='tr2_"+result[i].ID+"'>"+
							"<input type='hidden' name='id_pemeriksaan[]' value='"+result[i].ID+"'>"+
							"<input type='hidden' name='tarif_pemeriksaan[]' value='"+result[i].TARIF+"'>"+
							"<td style='vertical-align:middle;'>"+result[i].NAMA_PEMERIKSAAN+"</td>"+
							// "<td align='center'><input type='text' class='form-control' name='hasil_periksa[]' value='' style='width:200px;'></td>"+
							// "<td align='center'><input type='text' class='form-control' name='nilai_rujukan[]' value='' style='width:200px;'></td>"+
							"<td style='vertical-align:middle; text-align:right;'>"+formatNumber(result[i].TARIF)+"</td>"+
							"<td style='vertical-align:middle; text-align:right;'><b>"+formatNumber(result[i].TARIF)+"</b></td>"+
							"<td align='center'>"+aksi+"</td>"+
						  "</tr>";
				}
			}

			$('#tabel_tambah_pemeriksaan tbody').append($tr);
			hitung_pemeriksaan();
		}
	});
}

function deleteRow2(btn){
	var row = btn.parentNode.parentNode;
	row.parentNode.removeChild(row);
	hitung_pemeriksaan();
}

function hitung_pemeriksaan(){
	var total = 0;
	$("input[name='tarif_pemeriksaan[]']").each(function(idx,elm){
		var tarif = elm.value;
		total += parseFloat(tarif);
	});
	$('#total_tarif_pemeriksaan').val(formatNumber(total));
}

function data_laborat(){
	$('#popup_load').show();
	var id = "<?php echo $id; ?>";

	$.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/data_laborat',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(result){
			var id_pelayanan = "<?php echo $id; ?>";
			$tr = "";
			var total = 0;

			if(result == "" || result == null){
				$tr = "<tr><td colspan='7' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
				$('.view_lab').hide();
				$('#checkboxLab').removeAttr('checked');
			}else{
				$('.view_lab').show();
				$('#checkboxLab').attr('checked','checked');

				var no = 0;

				for(var i=0; i<result.length; i++){
					no++;

					total += parseFloat(result[i].TOTAL_TARIF);

					var cetak = "<a href='<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/cetak_laborat/"+result[i].ID+"/"+id_pelayanan+"' class='btn btn-inverse btn-sm' target='_blank'><i class='fa fa-print'></i></a>";
					var aksi =  '<button type="button" class="btn btn-primary waves-effect waves-light btn-sm m-b-5" onclick="hasil_laborat('+result[i].ID+');">'+
									'<i class="fa fa-tint"></i>'+
								'</button>&nbsp;'+
						   		'<button type="button" class="btn btn-danger waves-effect waves-light btn-sm m-b-5" onclick="hapus_laborat('+result[i].ID+');">'+
						   			'<i class="fa fa-trash"></i>'+
						   		'</button>';

					var cito = "";
					if(result[i].CITO == '1'){
						cito = '<span class="label label-success">Aktif</span>';
					}else{
						cito = '<span class="label label-danger">Tidak Aktif</span>';
					}

					var tanggal = formatTanggal(result[i].TANGGAL)+' - '+result[i].WAKTU;

					$tr += "<tr>"+
								"<td style='vertical-align:middle; text-align:center;'>"+no+"</td>"+
								"<td style='vertical-align:middle;'>"+tanggal+"</td>"+
								"<td style='vertical-align:middle;'>"+result[i].JENIS_LABORAT+"</td>"+
								"<td style='vertical-align:middle; text-align:center;'>"+cito+"</td>"+
								"<td style='vertical-align:middle; text-align:right;'>"+formatNumber(result[i].TOTAL_TARIF)+"</td>"+
								"<td align='center'>"+cetak+"</td>"+
								"<td align='center'>"+aksi+"</td>"+
							"</tr>";
				}
			}

			$('#tabel_laborat tbody').html($tr);
			$('#popup_load').fadeOut();
			$('#grandtotal_laborat').html(formatNumber(total));
		}
	});
}

function hasil_laborat(id){
	$('#popup_hasil_lab').click();

	$.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/data_hasil_pemeriksaan',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(result){
			$tr = "";
			var total = 0;

			if(result == "" || result == null){
				$tr = "<tr><td colspan='4' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
			}else{
				var no = 0;

				for(var i=0; i<result.length; i++){
					no++;
					
					total += parseFloat(result[i].SUBTOTAL);

					$tr += "<tr>"+
								"<td style='vertical-align:middle; text-align:center;'>"+no+"</td>"+
								"<td style='vertical-align:middle;'>"+result[i].NAMA_PEMERIKSAAN+"</td>"+
								"<td style='vertical-align:middle; text-align:right;'>"+formatNumber(result[i].TARIF)+"</td>"+
								"<td style='vertical-align:middle; text-align:right;'>"+formatNumber(result[i].SUBTOTAL)+"</td>"+
							"</tr>";
				}
			}

			$('#tb_hasil_lab tbody').html($tr);
			$('#total_laborat').html(formatNumber(total));
		}
	});
}

function hapus_laborat(id){
	$('#popup_hapus_lab').click();
	
	$.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/data_laborat_id',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(row){
			$('#id_hapus_lab').val(id);
			$('#msg_lab').html('Apakah data <b>'+row['JENIS_LABORAT']+'</b> ingin dihapus?');
		}
	});
}

//RESEP

function load_obat(){
	var keyword = $('#cari_resep').val();

	$.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/load_resep',
		data : {keyword:keyword},
		type : "POST",
		dataType : "json",
		success : function(result){
			$tr = "";

			if(result == "" || result == null){
				$tr = "<tr><td colspan='3' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
			}else{
				var no = 0;

				for(var i=0; i<result.length; i++){
					no++;

					$tr += "<tr style='cursor:pointer;' onclick='klik_obat("+result[i].ID+");'>"+
								"<td style='text-align:center;'>"+no+"</td>"+
								"<td style='text-align:center;'>"+result[i].KODE_OBAT+"</td>"+
								"<td>"+result[i].NAMA_OBAT+"</td>"+
							"</tr>";
				}
			}

			$('#tb_resep tbody').html($tr);
		}
	});
}

function klik_obat(id){
	$('#tutup_resep').click();

	$.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/klik_resep',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(result){
			$tr = "";
			var tot = 0;

			for(var i=0; i<result.length; i++){
				var jumlah_data = $('#tr_resep2_'+result[i].ID).length;
				tot += parseFloat();

				var aksi = "<button type='button' class='btn waves-light btn-danger btn-sm' onclick='deleteRowResep(this);'><i class='fa fa-times'></i></button>";

				if(jumlah_data > 0){
					
				}else{
					$tr = "<tr id='tr_resep2_"+result[i].ID+"'>"+
							"<input type='hidden' name='id_obat_resep[]' value='"+result[i].ID+"'>"+
							"<input type='hidden' name='harga_obat[]' id='harga_obat_"+result[i].ID+"' value='"+result[i].HARGA_JUAL+"'>"+
							"<td>"+result[i].KODE_OBAT+"</td>"+
							"<td>"+result[i].NAMA_OBAT+"</td>"+
							"<td style='text-align:right;'>"+formatNumber(result[i].HARGA_JUAL)+"</td>"+
							"<td align='center'><input type='text' class='form-control' name='jumlah_obat[]' value='' id='jumlah_obat_"+result[i].ID+"' style='width:125px;' onkeyup='FormatCurrency(this); hitung_resep("+result[i].ID+")'></td>"+
							"<td align='center'><input type='text' class='form-control' name='total_obat[]' value='' id='total_obat_"+result[i].ID+"' style='width:125px;' readonly></td>"+
							"<td align='center'><input type='text' class='form-control' name='takaran_resep[]' value='' style='width:125px;'></td>"+
							"<td align='center'><input type='text' class='form-control' name='aturan_minum[]' value='' style='width:125px;'></td>"+
							"<td align='center'>"+aksi+"</td>"+
						  "</tr>";
				}
			}

			$('#tabel_tambah_resep tbody').append($tr);
		}
	});
}

function deleteRowResep(btn){
	var row = btn.parentNode.parentNode;
	row.parentNode.removeChild(row);
}

function data_resep(){
	$('#popup_load').show();
	var id_pelayanan = "<?php echo $id; ?>";

	$.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/data_resep',
		data : {id_pelayanan:id_pelayanan},
		type : "POST",
		dataType : "json",
		success : function(result){
			$tr = "";

			if(result == "" || result == null){
				$tr = "<tr><td colspan='6' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
			}else{
				var no = 0;

				for(var i=0; i<result.length; i++){
					no++;

					var aksi =  '<button type="button" class="btn btn-primary waves-effect waves-light btn-sm m-b-5" onclick="detail_resep('+result[i].ID+');">'+
									'<i class="fa fa-eye"></i>'+
								'</button>&nbsp;'+
						   		'<button type="button" class="btn btn-danger waves-effect waves-light btn-sm m-b-5" onclick="hapus_resep('+result[i].ID+');">'+
						   			'<i class="fa fa-trash"></i>'+
						   		'</button>';

					$tr += "<tr>"+
								"<td style='text-align:center;'>"+no+"</td>"+
								"<td style='text-align:center;'>"+formatTanggal(result[i].TANGGAL)+"</td>"+
								"<td style='text-align:center;'>"+result[i].KODE_RESEP+"</td>"+
								"<td style='text-align:right;'>"+formatNumber(result[i].TOTAL)+"</td>"+
								"<td style='text-align:center;'>"+result[i].DIMINUM_SELAMA+" Hari</td>"+
								"<td align='center'>"+aksi+"</td>"+
							"</tr>";
				}
			}

			$('#tabel_resep tbody').html($tr);
			$('#popup_load').fadeOut();
		}
	});
}

function detail_resep(id){
	$('#popup_det_resep').click();

	$.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/detail_resep',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(result){
			$tr = "";

			if(result == "" || result == null){
				$tr = "<tr><td colspan='5' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
			}else{
				var no = 0;

				for(var i=0; i<result.length; i++){
					no++;

					$tr += "<tr>"+
								"<td style='text-align:center;'>"+no+"</td>"+
								"<td>"+result[i].KODE_OBAT+"</td>"+
								"<td>"+result[i].NAMA_OBAT+"</td>"+
								"<td>"+result[i].TAKARAN+"</td>"+
								"<td>"+result[i].ATURAN_MINUM+"</td>"+
							"</tr>";
				}
			}

			$('#tb_det_resep tbody').html($tr);
		}
	});
}

function hitung_resep(id){
	var harga = $('#harga_obat_'+id).val();
	var jumlah = $('#jumlah_obat_'+id).val();
	harga = harga.split(',').join('');
	jumlah = jumlah.split(',').join('');
	if(jumlah == ""){
		jumlah = '0';
	}
	var total = parseFloat(harga) * parseFloat(jumlah);
	$('#total_obat_'+id).val(formatNumber(total));

	var grandtotal = 0;
	$("input[name='total_obat[]']").each(function(id,elm){
		var t = elm.value;
		t = t.split(',').join('');
		if(t == ""){
			t = '0';
		}
		grandtotal += parseFloat(t);
	});

	$('#grandtotal_resep').html(formatNumber(grandtotal));
	$('#grandtotal_resep_txt').val(grandtotal);
}

function hapus_resep(id){
	$('#popup_hapus_resep').click();

	$.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/data_resep_id',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(row){
			$('#id_hapus_resep').val(id);
			$('#msg_resep').html('Apakah resep <b>'+row['KODE_RESEP']+'</b> ingin dihapus?');
		}
	});
}

function load_ruang_icu(){
	var keyword = $('#cari_ruang_icu').val();

	$.ajax({
        url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/load_ruang_icu',
        data : {keyword:keyword},
        type : "POST",
        dataType : "json",
        success : function(result){
            $tr = "";

            if(result == "" || result == null){
                $tr = "<tr><td colspan='5' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
            }else{
            	var no = 0;

                for(var i=0; i<result.length; i++){
                	no++;

                    $tr += "<tr style='cursor:pointer;' onclick='klik_ruang_icu("+result[i].ID+");'>"+
                                "<td style='text-align:center;'>"+no+"</td>"+
                                "<td style='text-align:center;'>"+result[i].KODE+"</td>"+
                                "<td>"+result[i].NAMA_RUANG+"</td>"+
                                "<td style='text-align:center;'>Level "+angkaRomawi(result[i].LEVEL)+"</td>"+
                                "<td style='text-align:center;'>Tipe "+result[i].TIPE+"</td>"+
                            "</tr>";
                }
            }

            $('#tabel_ruang_icu tbody').html($tr);
        }
    });

    $('#cari_ruang_icu').off('keyup').keyup(function(){
        load_ruang_icu();
    });
}

function klik_ruang_icu(id){
	$('#tutup_ruang_icu').click();

    $.ajax({
        url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/klik_ruang_icu',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(row){
            $('#id_ruang_icu').val(id);
            $('#ruang_icu').val(row['NAMA_RUANG']);
            $('#level_icu').val('Level '+angkaRomawi(row['LEVEL']));
            $('#tipe_icu').val('Tipe '+row['TIPE']);
        }
    });
}

function load_kamar_jenazah(){
	var keyword = $('#cari_kamar_jenazah').val();

	$.ajax({
        url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/load_kamar_jenazah',
        data : {keyword:keyword},
        type : "POST",
        dataType : "json",
        success : function(result){
            $tr = "";

            if(result == "" || result == null){
                $tr = "<tr><td colspan='4' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
            }else{
            	var no = 0;

                for(var i=0; i<result.length; i++){
                	no++;

                    $tr += "<tr style='cursor:pointer;' onclick='klik_kamar_jenazah("+result[i].ID+");'>"+
                                "<td style='text-align:center;'>"+no+"</td>"+
                                "<td style='text-align:center;'>"+result[i].KODE_KAMAR+"</td>"+
                                "<td>"+result[i].NAMA_KAMAR+"</td>"+
                                "<td style='text-align:right;'>"+formatNumber(result[i].BIAYA)+"</td>"+
                            "</tr>";
                }
            }

            $('#tabel_kamar_jenazah tbody').html($tr);
        }
    });

    $('#cari_kamar_jenazah').off('keyup').keyup(function(){
        load_kamar_jenazah();
    });
}

function klik_kamar_jenazah(id){
	$('#tutup_kamar_jenazah').click();

    $.ajax({
        url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/klik_kamar_jenazah',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(row){
            $('#id_kamar_jenazah').val(id);
            $('#kamar_jenazah').val(row['NAMA_KAMAR']);
            $('#tarif_kamar_jenazah').val(formatNumber(row['BIAYA']));
        }
    });
}

function load_lemari_jenazah(){
	var id_kamar = $('#id_kamar_jenazah').val();

	$.ajax({
        url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/load_lemari_jenazah',
        data : {id_kamar:id_kamar},
        type : "POST",
        dataType : "json",
        success : function(result){
            $tr = "";

            if(result == "" || result == null){
                $tr = "<tr><td colspan='4' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
            }else{
            	var no = 0;

                for(var i=0; i<result.length; i++){
                	no++;

                	var stt = "";
                	var disabled = "";
                	if(result[i].STATUS_PAKAI == '0'){
                		stt = '<span class="label label-danger">Kosong</span>';
                		disabled = "style='cursor:pointer;' onclick='klik_lemari_jenazah("+result[i].ID+");'";
                	}else{
                		stt = '<span class="label label-success">Terpakai</span>';
                		disabled = "class='info'";
                	}

                    $tr += "<tr "+disabled+" >"+
                                "<td style='text-align:center;'>"+no+"</td>"+
                                "<td style='text-align:center;'>"+result[i].NOMOR_LEMARI+"</td>"+
                                "<td style='text-align:center;'>"+stt+"</td>"+
                            "</tr>";
                }
            }

            $('#tabel_lemari_jenazah tbody').html($tr);
        }
    });
}

function klik_lemari_jenazah(id){
	$('#tutup_lemari_jenazah').click();

    $.ajax({
        url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/klik_lemari_jenazah',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(row){
            $('#id_lemari_jenazah').val(id);
            $('#lemari_jenazah').val(row['NOMOR_LEMARI']);
        }
    });
}

// SURAT DOKTER

function data_surat_dokter(){
	$('#popup_load').show();
	var id = "<?php echo $id; ?>";

	$.ajax({
        url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/data_surat_dokter',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(result){
            $tr = "";

            if(result == "" || result == null){
                $tr = "<tr><td colspan='6' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
            }else{
            	var no = 0;

                for(var i=0; i<result.length; i++){
                	no++;

                	result[i].JENIS_KELAMIN = result[i].JENIS_KELAMIN=="L"?"Laki - Laki":"Perempuan";

                	var aksi =  '<button type="button" class="btn btn-primary waves-effect waves-light btn-sm m-b-5" onclick="surat_dokter('+result[i].ID+');">'+
									'<i class="fa fa-print"></i>'+
								'</button>&nbsp;'+
						   		'<button type="button" class="btn btn-danger waves-effect waves-light btn-sm m-b-5" onclick="hapus_surat_dokter('+result[i].ID+');">'+
						   			'<i class="fa fa-trash"></i>'+
						   		'</button>';

                    $tr += "<tr>"+
                    			"<td style='text-align:center;'>"+no+"</td>"+
                                "<td style='text-align:center;'>"+formatTanggal(result[i].TANGGAL)+"</td>"+
                                "<td>"+result[i].NAMA+"</td>"+
                                "<td style='text-align:center;'>"+result[i].JENIS_KELAMIN+"</td>"+
                                "<td style='text-align:center;'>"+result[i].UMUR+" Tahun</td>"+
                                "<td align='center'>"+aksi+"</td>"+
                            "</tr>";
                }
            }

            $('#tabel_surat_dokter tbody').html($tr);
            $('#popup_load').fadeOut();
        }
    });
}

function surat_dokter(id){
    window.open('<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/surat_dokter/'+id, '_blank');
    // prt.print();
}

function hapus_surat_dokter(id){
	$('#popup_hapus_sd').click();

	$.ajax({
        url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/data_surat_dokter_id',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(row){
            $('#id_hapus_sd').val(id);
            $('#msg_surat_dokter').html('Apakah surat dokter <b>'+row['NAMA']+'</b> ingin dihapus?');
        }
    });
}
</script>

<div id="popup_load">
    <div class="window_load">
        <img src="<?=base_url()?>picture/progress.gif" height="100" width="125">
    </div>
</div>

<div class="row">
	<div class="col-md-6">
        <div class="card-box">
        	<h4><i class="fa fa-user"></i> Pasien</h4>
        	<hr/>
            <div>
                <div class="text-left">
                    <p class="text-muted font-13">
                    	<strong>NO. RM :</strong> <span class="m-l-15" style="color:#0066b2;"><?php echo $dt->KODE_PASIEN; ?></span>
                    </p>
                    <p class="text-muted font-13">
                    	<strong>NAMA :</strong><span class="m-l-15" style="color:#0066b2;"><?php echo $dt->NAMA_PASIEN; ?></span>
                    </p>
                    <p class="text-muted font-13">
                    	<?php
                    		$jk = "";
                    		if($dt->JENIS_KELAMIN=="L"){$jk="Laki - Laki";}else{$jk="Perempuan";}
                    	?>
                    	<strong>JENIS KELAMIN :</strong> <span class="m-l-15" style="color:#0066b2;"><?php echo $jk; ?></span>
                    </p>
                    <p class="text-muted font-13">
                    	<strong>UMUR :</strong> <span class="m-l-15" style="color:#0066b2;"><?php echo $dt->UMUR; ?> Tahun</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card-box">
        	<h4><i class="fa fa-list"></i> Keterangan</h4>
        	<hr/>
            <div>
                <div class="text-left">
                	<p class="text-muted font-13">
                    	<strong>ASAL RUJUKAN :</strong> <span class="m-l-15" style="color:#0066b2;"><?php echo $dt->ASAL_RUJUKAN; ?></span>
                    </p>
                    <p class="text-muted font-13">
                    	<strong>DOKTER :</strong> <span class="m-l-15" style="color:#0066b2;"><?php echo $dt->NAMA_DOKTER; ?></span>
                    </p>
                    <p class="text-muted font-13">
                    	<strong>KELAS :</strong>
                    	<span class="m-l-15" style="color:#0066b2;"><?php echo $dt->KELAS; ?> - <?php echo $dt->VISITE_DOKTER; ?></span>
                    	&nbsp;&nbsp;
                    	<strong>NO BED :</strong>
                    	<span class="m-l-15" style="color:#0066b2;"><?php echo $dt->NOMOR_BED; ?></span>
                    </p>
                    <p class="text-muted font-13">
                    	<strong>PELAYANAN :</strong> <span class="m-l-15" style="color:#0066b2;"><?php echo $dt->STATUS; ?></span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
	<div class="col-lg-12">
		<div class="card-box">
			<ul class="nav nav-tabs">
                <li role="presentation" class="active">
                    <a href="#tindakan1" role="tab" data-toggle="tab"><i class="fa fa-stethoscope"></i>&nbsp;Tindakan</a>
                </li>
                <!-- <li role="presentation" id="dt_visite">
                    <a href="#visite1" role="tab" data-toggle="tab"><i class="fa fa-user-md"></i>&nbsp;Visite</a>
                </li>
                <li role="presentation" id="dt_gizi">
                    <a href="#gizi1" role="tab" data-toggle="tab"><i class="fa fa-cutlery"></i>&nbsp;Gizi</a>
                </li>
                <li role="presentation" id="dt_oksigen">
                    <a href="#oksigen1" role="tab" data-toggle="tab"><i class="fa fa-battery-full"></i>&nbsp;Oksigen</a>
                </li>
                <li role="presentation" id="dt_infus">
                    <a href="#infus1" role="tab" data-toggle="tab"><i class="fa fa-balance-scale"></i>&nbsp;Infus</a>
                </li>
                <li role="presentation" id="dt_jasa_prwt">
                    <a href="#jasaprwt1" role="tab" data-toggle="tab"><i class="fa fa-hand-paper-o"></i>&nbsp;Jasa Perawat</a>
                </li> -->
                <li role="presentation" id="dt_diagnosa">
                    <a href="#diagnosa1" role="tab" data-toggle="tab"><i class="fa fa-heartbeat"></i>&nbsp;Diagnosa</a>
                </li>
                <li role="presentation" id="dt_laborat">
                    <a href="#laborat1" role="tab" data-toggle="tab"><i class="fa fa-building"></i>&nbsp;Laborat</a>
                </li>
                <li role="presentation" id="dt_resep">
                    <a href="#resep1" role="tab" data-toggle="tab"><i class="fa fa-medkit"></i></i>&nbsp;Resep</a>
                </li>
                <li role="presentation" id="dt_kondisi_akhir">
                    <a href="#kondisi_akhir1" role="tab" data-toggle="tab"><i class="fa fa-check-square-o"></i>&nbsp;Kondisi Akhir</a>
                </li>
                <!--
                <li role="presentation" id="dt_surat_dokter">
                    <a href="#surat_dokter1" role="tab" data-toggle="tab"><i class="fa fa-file-text-o"></i>&nbsp;Surat Dokter</a>
                </li>
                -->
            </ul>
            <div class="tab-content">
            	<div role="tabpanel" class="tab-pane fade in active" id="tindakan1">
                    <form class="form-horizontal" id="view_tindakan">
                    	<div class="form-group">
                    		<div class="col-md-6">
                    			<h4 class="m-t-0"><i class="fa fa-table"></i>&nbsp;<b>Tabel Tindakan</b></h4>
                    		</div>
                    		<div class="col-md-6">
			                    <button class="btn btn-primary m-b-5 pull-right" type="button" id="btn_tambah">
									<i class="fa fa-plus"></i>&nbsp;<b>Tambah Tindakan</b>
								</button>
                    		</div>
                    	</div>
                    	<div class="form-group">
                    		<div class="col-md-12"> 
			                    <div class="table-responsive">
						            <table id="tabel_tindakan" class="table table-bordered">
						                <thead>
						                    <tr class="merah">
						                        <th style="color:#fff; text-align:center;">No</th>
						                        <th style="color:#fff; text-align:center;">Tanggal</th>
						                        <th style="color:#fff; text-align:center;">Tindakan</th>
						                        <th style="color:#fff; text-align:center;">Tarif</th>
						                        <th style="color:#fff; text-align:center;">Jumlah</th>
						                        <th style="color:#fff; text-align:center;">Sub Total</th>
						                        <th style="color:#fff; text-align:center;">Aksi</th>
						                    </tr>
						                </thead>

						                <tbody>
						                    
						                </tbody>
						            </table>
						        </div>
                    		</div>
                    	</div>
                    </form>

					<form class="form-horizontal" id="view_tindakan_tambah" action="" method="post">
						<input type="hidden" name="id_ri" value="<?php echo $id; ?>">
						<input type="hidden" name="id_pasien" value="<?php echo $dt->ID_PASIEN; ?>">
						<input type="hidden" name="id_user" value="<?php echo $id_user; ?>">
						<!-- <div class="form-group">
	                        <label class="col-md-1 control-label">Pelaksana</label>
	                        <div class="col-md-5">
	                            <div class="input-group">
	                            	<input type="hidden" name="id_pelaksana" id="id_pelaksana" value="">
	                                <input type="text" class="form-control" value="" id="pelaksana" readonly="readonly" required="required">
	                                <span class="input-group-btn">
	                                    <button type="button" class="btn btn-primary btn_pelaksana"><i class="fa fa-search"></i></button>
	                                </span>
	                            </div>
	                        </div>
	                    </div> -->
						<div class="form-group">
	                        <label class="col-md-1 control-label">Tindakan</label>
	                        <div class="col-md-5">
	                            <div class="input-group">
	                                <input type="text" class="form-control" value="" readonly="readonly" required="required">
	                                <span class="input-group-btn">
	                                    <button type="button" class="btn btn-inverse btn_tindakan"><i class="fa fa-search"></i></button>
	                                </span>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-md-1 control-label">&nbsp;</label>
	                        <div class="col-md-9">
	                            <div class="table-responsive">
						            <table id="tabel_tambah_tindakan" class="table table-bordered">
						                <thead>
						                    <tr class="kuning_tr">
						                        <th style="color:#fff; text-align:center;">Tindakan</th>
						                        <th style="color:#fff; text-align:center;">Tarif</th>
						                        <th style="color:#fff; text-align:center;">Jumlah</th>
						                        <th style="color:#fff; text-align:center;">Sub Total</th>
						                        <th style="color:#fff; text-align:center;">#</th>
						                    </tr>
						                </thead>

						                <tbody>
						                    
						                </tbody>
						            </table>
						        </div>
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-md-1 control-label">Total</label>
	                        <div class="col-md-5">
	                            <input type="text" class="form-control" name="total_tindakan" id="total_tindakan" value="" readonly="readonly">
	                        </div>
	                    </div>
	                    <hr>
	                    <center>
	                    	<button type="button" class="btn btn-success" id="btn_simpan"><i class="fa fa-save"></i> <b>Simpan</b></button>
	                        <button type="button" class="btn btn-danger" id="batal"><i class="fa fa-times"></i> <b>Batal</b></button>
	                    </center>
					</form>

					<form class="form-horizontal" id="view_tindakan_ubah" action="<?php echo $url_ubah; ?>" method="post">
						<input type="hidden" name="id_ubah" id="id_ubah" value="">
						<input type="hidden" name="id_pelayanan" value="<?php echo $id; ?>">
						<h4><i class="fa fa-pencil"></i> Ubah Tindakan</h4>
						<hr>
						<div class="form-group">
	                        <label class="col-md-1 control-label">Tanggal</label>
	                        <div class="col-md-3">
	                        	<div class="input-group">
	                                <span class="input-group-addon">
	                                    <i class="fa fa-calendar"></i>
	                                </span>
	                                <input type="text" class="form-control" name="tanggal_ubah" id="tanggal_ubah" value="" readonly>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-md-1 control-label">Tindakan</label>
	                        <div class="col-md-5">
	                            <div class="input-group">
	                            	<input type="hidden" name="id_tindakan_ubah" id="id_tindakan_ubah" value="">
	                                <input type="text" class="form-control" id="tindakan_txt" value="" readonly="readonly">
	                                <span class="input-group-btn">
	                                    <button type="button" class="btn btn-inverse btn_tindakan"><i class="fa fa-search"></i></button>
	                                </span>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-md-1 control-label">Tarif</label>
	                        <div class="col-md-5">
	                            <input type="text" class="form-control" id="tarif_txt" value="" readonly>
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-md-1 control-label">Jumlah</label>
	                        <div class="col-md-5">
	                            <input type="text" class="form-control" name="jumlah_ubah" id="jumlah_ubah" value="" onkeyup="FormatCurrency(this); hitung_jumlah2();">
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-md-1 control-label">Sub Total</label>
	                        <div class="col-md-5">
	                            <input type="text" class="form-control" name="subtotal_ubah" id="subtotal_ubah" value="" readonly>
	                        </div>
	                    </div>
	                    <hr>
	                    <center>
	                    	<button type="submit" class="btn btn-success"><i class="fa fa-save"></i> <b>Simpan</b></button>
	                        <button type="button" class="btn btn-danger" id="batal_ubah"><i class="fa fa-times"></i> <b>Batal</b></button>
	                    </center>
					</form>
                </div>

                <div role="tabpanel" class="tab-pane fade" id="visite1">
                	<form class="form-horizontal" id="view_visite">
                    	<div class="form-group">
                    		<div class="col-md-6">
                    			<h4 class="m-t-0"><i class="fa fa-table"></i>&nbsp;<b>Tabel Visite</b></h4>
                    		</div>
                    		<div class="col-md-6">
			                    <button class="btn btn-primary m-b-5 pull-right" type="button" id="btn_tambah_vst">
									<i class="fa fa-plus"></i>&nbsp;<b>Tambah Visite</b>
								</button>
                    		</div>
                    	</div>
                    	<div class="form-group">
                    		<div class="col-md-12"> 
			                    <div class="table-responsive">
						            <table id="tabel_visite" class="table table-bordered">
						                <thead>
						                    <tr class="merah">
						                        <th style="color:#fff; text-align:center;">No</th>
						                        <th style="color:#fff; text-align:center;">Tanggal</th>
						                        <th style="color:#fff; text-align:center;">Visite</th>
						                        <th style="color:#fff; text-align:center;">Tarif</th>
						                        <th style="color:#fff; text-align:center;">Dokter</th>
						                        <th style="color:#fff; text-align:center;">Aksi</th>
						                    </tr>
						                </thead>

						                <tbody>
						                    
						                </tbody>
						            </table>
						        </div>
                    		</div>
                    	</div>
                    </form>

                	<form class="form-horizontal" id="view_tambah_visite">
                		<input type="hidden" name="id_rj" id="id_rj" value="<?php echo $id; ?>">
						<input type="hidden" name="id_pasien" value="<?php echo $dt->ID_PASIEN; ?>">
	                    <div class="form-group">
	                        <label class="col-md-2 control-label">Tanggal</label>
	                        <div class="col-md-4">
	                            <input type="text" class="form-control" name="tanggal_visite" id="tanggal_visite" value="<?php echo date('d-m-Y'); ?>" data-mask="99-99-9999">
	                        	<span class="help-block"><small>(dd-mm-yyyy)</small></span>
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-md-2 control-label">Visite</label>
	                        <div class="col-md-4">
	                            <div class="input-group">
	                                <input type="hidden" name="id_visite" id="id_visite" value="">
	                                <input type="text" class="form-control" id="visite" value="" readonly>
	                                <span class="input-group-btn">
	                                    <button type="button" class="btn btn-danger btn_visite"><i class="fa fa-search"></i></button>
	                                </span>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-md-2 control-label">Tarif</label>
	                        <div class="col-md-4">
	                            <input type="text" class="form-control" name="tarif_visite" id="tarif_visite" value="" readonly>
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-md-2 control-label">Dokter</label>
	                        <div class="col-md-4">
	                            <div class="input-group">
	                                <input type="hidden" name="id_dokter" id="id_dokter" value="">
	                                <input type="text" class="form-control" id="dokter_visite" value="" readonly>
	                                <span class="input-group-btn">
	                                    <button type="button" class="btn btn-primary btn_dokter"><i class="fa fa-search"></i></button>
	                                </span>
	                            </div>
	                        </div>
	                    </div>
	                    <hr>
	                    <center>
	                    	<button type="button" class="btn btn-success" id="simpanVisite"><i class="fa fa-save"></i> <b>Simpan</b></button>
	                        <button type="button" class="btn btn-danger" id="batalVisite"><i class="fa fa-times"></i> <b>Batal</b></button>
	                    </center>
                	</form>

                	<form class="form-horizontal" id="view_ubah_visite">
                		<input type="hidden" name="id_ubah_visite" id="id_ubah_visite" value="">
                		<input type="hidden" name="id_rj" id="id_rj" value="<?php echo $id; ?>">
						<input type="hidden" name="id_pasien" value="<?php echo $dt->ID_PASIEN; ?>">
	                    <div class="form-group">
	                        <label class="col-md-2 control-label">Tanggal</label>
	                        <div class="col-md-4">
	                            <input type="text" class="form-control" name="tanggal_visite_ubah" id="tanggal_visite_ubah" value="" data-mask="99-99-9999">
	                        	<span class="help-block"><small>(dd-mm-yyyy)</small></span>
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-md-2 control-label">Visite</label>
	                        <div class="col-md-4">
	                            <div class="input-group">
	                                <input type="hidden" name="id_visite_ubah" id="id_visite_ubah" value="">
	                                <input type="text" class="form-control" id="visite_txt" value="" readonly>
	                                <span class="input-group-btn">
	                                    <button type="button" class="btn btn-danger btn_visite"><i class="fa fa-search"></i></button>
	                                </span>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-md-2 control-label">Tarif</label>
	                        <div class="col-md-4">
	                            <input type="text" class="form-control" name="tarif_visite_ubah" id="tarif_visite_ubah" value="" readonly>
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-md-2 control-label">Dokter</label>
	                        <div class="col-md-4">
	                            <div class="input-group">
	                                <input type="hidden" name="id_dokter_ubah" id="id_dokter_ubah" value="">
	                                <input type="text" class="form-control" id="dokter_visite_txt" value="" readonly>
	                                <span class="input-group-btn">
	                                    <button type="button" class="btn btn-primary btn_dokter"><i class="fa fa-search"></i></button>
	                                </span>
	                            </div>
	                        </div>
	                    </div>
	                    <hr>
	                    <center>
	                    	<button type="button" class="btn btn-success" id="simpanVisiteUbah"><i class="fa fa-save"></i> <b>Simpan</b></button>
	                        <button type="button" class="btn btn-danger" id="batalVisiteUbah"><i class="fa fa-times"></i> <b>Batal</b></button>
	                    </center>
                	</form>
                </div>

                <div role="tabpanel" class="tab-pane fade" id="gizi1">
                	<form class="form-horizontal" id="view_gizi">
                    	<div class="form-group">
                    		<div class="col-md-6">
                    			<h4 class="m-t-0"><i class="fa fa-table"></i>&nbsp;<b>Tabel Gizi</b></h4>
                    		</div>
                    		<div class="col-md-6">
			                    <button class="btn btn-primary m-b-5 pull-right" type="button" id="btn_tambah_gizi">
									<i class="fa fa-plus"></i>&nbsp;<b>Tambah Gizi</b>
								</button>
                    		</div>
                    	</div>
                    	<div class="form-group">
                    		<div class="col-md-12"> 
			                    <div class="table-responsive">
						            <table id="tabel_gizi" class="table table-bordered">
						                <thead>
						                    <tr class="merah">
						                        <th style="color:#fff; text-align:center;">No</th>
						                        <th style="color:#fff; text-align:center;">Tanggal</th>
						                        <th style="color:#fff; text-align:center;">Gizi</th>
						                        <th style="color:#fff; text-align:center;">Tarif</th>
						                        <th style="color:#fff; text-align:center;">Aksi</th>
						                    </tr>
						                </thead>

						                <tbody>
						                    
						                </tbody>
						            </table>
						        </div>
                    		</div>
                    	</div>
                    </form>

                    <form class="form-horizontal" id="view_tambah_gizi">
                		<input type="hidden" name="id_rj" id="id_rj" value="<?php echo $id; ?>">
						<input type="hidden" name="id_pasien" value="<?php echo $dt->ID_PASIEN; ?>">
	                    <div class="form-group">
	                        <label class="col-md-2 control-label">Gizi</label>
	                        <div class="col-md-4">
	                            <div class="input-group">
	                                <input type="hidden" name="id_gizi" id="id_gizi" value="">
	                                <input type="text" class="form-control" id="gizi" value="" readonly>
	                                <span class="input-group-btn">
	                                    <button type="button" class="btn btn-danger btn_gizi"><i class="fa fa-search"></i></button>
	                                </span>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-md-2 control-label">Tarif</label>
	                        <div class="col-md-4">
	                            <input type="text" class="form-control" name="tarif_gizi" id="tarif_gizi" value="" readonly>
	                        </div>
	                    </div>
	                    <hr>
	                    <center>
	                    	<button type="button" class="btn btn-success" id="simpanGizi"><i class="fa fa-save"></i> <b>Simpan</b></button>
	                        <button type="button" class="btn btn-danger" id="batalGizi"><i class="fa fa-times"></i> <b>Batal</b></button>
	                    </center>
                	</form>

                	<form class="form-horizontal" id="view_ubah_gizi">
                		<input type="hidden" name="id_ubah_gizi" id="id_ubah_gizi" value="">
                		<input type="hidden" name="id_rj" id="id_rj" value="<?php echo $id; ?>">
						<input type="hidden" name="id_pasien" value="<?php echo $dt->ID_PASIEN; ?>">
	                    <div class="form-group">
	                        <label class="col-md-2 control-label">Gizi</label>
	                        <div class="col-md-4">
	                            <div class="input-group">
	                                <input type="hidden" name="id_gizi_ubah" id="id_gizi_ubah" value="">
	                                <input type="text" class="form-control" id="gizi_txt" value="" readonly>
	                                <span class="input-group-btn">
	                                    <button type="button" class="btn btn-danger btn_gizi"><i class="fa fa-search"></i></button>
	                                </span>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-md-2 control-label">Tarif</label>
	                        <div class="col-md-4">
	                            <input type="text" class="form-control" name="tarif_gizi_ubah" id="tarif_gizi_ubah" value="" readonly>
	                        </div>
	                    </div>
	                    <hr>
	                    <center>
	                    	<button type="button" class="btn btn-success" id="simpanGiziUbah"><i class="fa fa-save"></i> <b>Simpan</b></button>
	                        <button type="button" class="btn btn-danger" id="batalGiziUbah"><i class="fa fa-times"></i> <b>Batal</b></button>
	                    </center>
                	</form>
                </div>

                <div role="tabpanel" class="tab-pane fade" id="oksigen1">
                	<form class="form-horizontal" id="view_oksigen">
                    	<div class="form-group">
                    		<div class="col-md-6">
                    			<h4 class="m-t-0"><i class="fa fa-table"></i>&nbsp;<b>Tabel Oksigen</b></h4>
                    		</div>
                    		<div class="col-md-6">
			                    <button class="btn btn-primary m-b-5 pull-right" type="button" id="btn_tambah_oksigen">
									<i class="fa fa-plus"></i>&nbsp;<b>Tambah Oksigen</b>
								</button>
                    		</div>
                    	</div>
                    	<div class="form-group">
                    		<div class="col-md-12"> 
			                    <div class="table-responsive">
						            <table id="tabel_oksigen" class="table table-bordered">
						                <thead>
						                    <tr class="merah">
						                        <th style="color:#fff; text-align:center;">No</th>
						                        <th style="color:#fff; text-align:center;">Tanggal</th>
						                        <th style="color:#fff; text-align:center;">Keterangan</th>
						                        <th style="color:#fff; text-align:center;">Jumlah</th>
						                        <th style="color:#fff; text-align:center;">Tarif</th>
						                        <th style="color:#fff; text-align:center;">Total</th>
						                        <th style="color:#fff; text-align:center;">Pemakaian Selama</th>
						                        <th style="color:#fff; text-align:center;">Aksi</th>
						                    </tr>
						                </thead>

						                <tbody>
						                    
						                </tbody>
						            </table>
						        </div>
                    		</div>
                    	</div>
                    </form>

                    <form class="form-horizontal" id="view_tambah_oksigen">
                		<input type="hidden" name="id_rj" id="id_rj" value="<?php echo $id; ?>">
						<input type="hidden" name="id_pasien" value="<?php echo $dt->ID_PASIEN; ?>">
	                    <div class="form-group">
	                        <label class="col-md-2 control-label">Keterangan</label>
	                        <div class="col-md-8">
	                            <textarea class="form-control" name="keterangan_oksigen" id="keterangan_oksigen" rows="5"></textarea>
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-md-2 control-label">Tarif</label>
	                        <div class="col-md-4">
	                            <input type="text" class="form-control" name="tarif_oksigen" id="tarif_oksigen" value="" onkeyup="FormatCurrency(this); hitung_oksigen();">
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-md-2 control-label">Jumlah</label>
	                        <div class="col-md-4">
	                            <input type="text" class="form-control" name="jumlah_oksigen" id="jumlah_oksigen" value="" onkeyup="FormatCurrency(this); hitung_oksigen();">
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-md-2 control-label">Total</label>
	                        <div class="col-md-4">
	                            <input type="text" class="form-control" name="total_oksigen" id="total_oksigen" value="" readonly>
	                        </div>
	                    </div>
	                    <div class="form-group">
                			<label class="col-sm-2 control-label">Pemakaian Selama</label>
                			<div class="col-sm-4">
                				<div class="input-group">
                                    <input type="text" class="form-control num_only" name="pemakaian_selama" id="pemakaian_selama" value="">
                                    <span class="input-group-addon">Hari</span>
                                </div>
                			</div>
                		</div>
	                    <hr>
	                    <center>
	                    	<button type="button" class="btn btn-success" id="simpanOksigen"><i class="fa fa-save"></i> <b>Simpan</b></button>
	                        <button type="button" class="btn btn-danger" id="batalOksigen"><i class="fa fa-times"></i> <b>Batal</b></button>
	                    </center>
                	</form>

                	<form class="form-horizontal" id="view_ubah_oksigen">
                		<input type="hidden" name="id_ubah_oksigen" id="id_ubah_oksigen" value="">
                		<input type="hidden" name="id_rj" id="id_rj" value="<?php echo $id; ?>">
						<input type="hidden" name="id_pasien" value="<?php echo $dt->ID_PASIEN; ?>">
	                    <div class="form-group">
	                        <label class="col-md-2 control-label">Keterangan</label>
	                        <div class="col-md-8">
	                            <textarea class="form-control" name="keterangan_oksigen_ubah" id="keterangan_oksigen_ubah" rows="5"></textarea>
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-md-2 control-label">Jumlah</label>
	                        <div class="col-md-4">
	                            <input type="text" class="form-control" name="jumlah_oksigen_ubah" id="jumlah_oksigen_ubah" value="" onkeyup="FormatCurrency(this);">
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-md-2 control-label">Tarif</label>
	                        <div class="col-md-4">
	                            <input type="text" class="form-control" name="tarif_oksigen_ubah" id="tarif_oksigen_ubah" value="" onkeyup="FormatCurrency(this);">
	                        </div>
	                    </div>
	                    <hr>
	                    <center>
	                    	<button type="button" class="btn btn-success" id="simpanOksigenUbah"><i class="fa fa-save"></i> <b>Simpan</b></button>
	                        <button type="button" class="btn btn-danger" id="batalOksigenUbah"><i class="fa fa-times"></i> <b>Batal</b></button>
	                    </center>
                	</form>
                </div>

                <div role="tabpanel" class="tab-pane fade" id="infus1">
                	<form class="form-horizontal" id="view_infus">
                    	<div class="form-group">
                    		<div class="col-md-6">
                    			<h4 class="m-t-0"><i class="fa fa-table"></i>&nbsp;<b>Tabel Infus</b></h4>
                    		</div>
                    		<div class="col-md-6">
			                    <button class="btn btn-primary m-b-5 pull-right" type="button" id="btn_tambah_if">
									<i class="fa fa-plus"></i>&nbsp;<b>Tambah Infus</b>
								</button>
                    		</div>
                    	</div>
                    	<div class="form-group">
                    		<div class="col-md-12">
			                    <div class="table-responsive">
						            <table id="tabel_infus" class="table table-bordered">
						                <thead>
						                    <tr class="merah">
						                        <th style="color:#fff; text-align:center;">No</th>
						                        <th style="color:#fff; text-align:center;">Kode Infus</th>
						                        <th style="color:#fff; text-align:center;">Jumlah Infus</th>
						                        <th style="color:#fff; text-align:center;">Tarif</th>
						                        <th style="color:#fff; text-align:center;">Total</th>
						                        <th style="color:#fff; text-align:center;">Dipakai Selama</th>
						                        <th style="color:#fff; text-align:center;">Aksi</th>
						                    </tr>
						                </thead>

						                <tbody>
						                    
						                </tbody>
						            </table>
						        </div>
                    		</div>
                    	</div>
                    </form>

                    <form class="form-horizontal" id="view_tambah_infus">
                		<input type="hidden" name="id_rj" id="id_rj" value="<?php echo $id; ?>">
						<input type="hidden" name="id_pasien" value="<?php echo $dt->ID_PASIEN; ?>">
	                    <div class="form-group">
	                        <label class="col-md-2 control-label">Kode Infus</label>
	                        <div class="col-md-4">
	                            <input type="text" class="form-control" name="kode_infus" id="kode_infus" value="" readonly>
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-md-2 control-label">Jumlah</label>
	                        <div class="col-md-4">
	                            <input type="text" class="form-control" name="jumlah_infus" id="jumlah_infus" value="" onkeyup="FormatCurrency(this); hitung_infus();">
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-md-2 control-label">Tarif</label>
	                        <div class="col-md-4">
	                            <input type="text" class="form-control" name="tarif_infus" id="tarif_infus" value="" onkeyup="FormatCurrency(this); hitung_infus();">
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-md-2 control-label">Total</label>
	                        <div class="col-md-4">
	                            <input type="text" class="form-control" name="total_infus" id="total_infus" value="" readonly>
	                        </div>
	                    </div>
	                    <div class="form-group">
                			<label class="col-sm-2 control-label">Pemakaian Selama</label>
                			<div class="col-sm-4">
                				<div class="input-group">
                                    <input type="text" class="form-control num_only" name="pemakaian_selama_infus" id="pemakaian_selama_infus" value="">
                                    <span class="input-group-addon">Hari</span>
                                </div>
                			</div>
                		</div>
	                    <hr>
	                    <center>
	                    	<button type="button" class="btn btn-success" id="simpanInfus"><i class="fa fa-save"></i> <b>Simpan</b></button>
	                        <button type="button" class="btn btn-danger" id="batalInfus"><i class="fa fa-times"></i> <b>Batal</b></button>
	                    </center>
                	</form>

                	<form class="form-horizontal" id="view_ubah_infus">
                		<input type="hidden" name="id_rj" id="id_rj" value="<?php echo $id; ?>">
						<input type="hidden" name="id_pasien" value="<?php echo $dt->ID_PASIEN; ?>">
						<input type="hidden" name="id_ubah_infus" id="id_ubah_infus" value="">
	                    <div class="form-group">
	                        <label class="col-md-2 control-label">Jumlah</label>
	                        <div class="col-md-4">
	                            <input type="text" class="form-control" name="jumlah_infus_ubah" id="jumlah_infus_ubah" value="" onkeyup="FormatCurrency(this); hitung_infus();">
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-md-2 control-label">Tarif</label>
	                        <div class="col-md-4">
	                            <input type="text" class="form-control" name="tarif_infus_ubah" id="tarif_infus_ubah" value="" onkeyup="FormatCurrency(this); hitung_infus();">
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-md-2 control-label">Total</label>
	                        <div class="col-md-4">
	                            <input type="text" class="form-control" name="total_infus_ubah" id="total_infus_ubah" value="" readonly>
	                        </div>
	                    </div>
	                    <div class="form-group">
                			<label class="col-sm-2 control-label">Pemakaian Selama</label>
                			<div class="col-sm-4">
                				<div class="input-group">
                                    <input type="text" class="form-control num_only" name="pemakaian_selama_infus_ubah" id="pemakaian_selama_infus_ubah" value="">
                                    <span class="input-group-addon">Hari</span>
                                </div>
                			</div>
                		</div>
	                    <hr>
	                    <center>
	                    	<button type="button" class="btn btn-success" id="simpanUbahInfus"><i class="fa fa-save"></i> <b>Simpan</b></button>
	                        <button type="button" class="btn btn-danger" id="batalUbahInfus"><i class="fa fa-times"></i> <b>Batal</b></button>
	                    </center>
                	</form>
                </div>

                <div role="tabpanel" class="tab-pane fade" id="jasaprwt1">
                	<form class="form-horizontal" id="view_jasa_perawat">
                    	<div class="form-group">
                    		<div class="col-md-6">
                    			<h4 class="m-t-0"><i class="fa fa-table"></i>&nbsp;<b>Tabel Jasa Perawat</b></h4>
                    		</div>
                    		<div class="col-md-6">
			                    <button class="btn btn-primary m-b-5 pull-right" type="button" id="btn_tambah_jp">
									<i class="fa fa-plus"></i>&nbsp;<b>Tambah Jasa Perawat</b>
								</button>
                    		</div>
                    	</div>
                    	<div class="form-group">
                    		<div class="col-md-12">
			                    <div class="table-responsive">
						            <table id="tabel_jasa" class="table table-bordered">
						                <thead>
						                    <tr class="merah">
						                        <th style="color:#fff; text-align:center;">No</th>
						                        <th style="color:#fff; text-align:center;">Kode</th>
						                        <th style="color:#fff; text-align:center;">Jasa Perawat</th>
						                        <th style="color:#fff; text-align:center;">Tarif (Rp)</th>
						                        <th style="color:#fff; text-align:center;">Jumlah</th>
						                        <th style="color:#fff; text-align:center;">Perawatan Selama</th>
						                        <th style="color:#fff; text-align:center;">Total Semua (Rp)</th>
						                        <th style="color:#fff; text-align:center;">Aksi</th>
						                    </tr>
						                </thead>

						                <tbody>
						                    
						                </tbody>
						            </table>
						        </div>
                    		</div>
                    	</div>
                    </form>

                    <form class="form-horizontal" id="view_tambah_jasa_perawat">
                		<input type="hidden" name="id_rj" id="id_rj" value="<?php echo $id; ?>">
						<input type="hidden" name="id_pasien" value="<?php echo $dt->ID_PASIEN; ?>">
	                    <div class="form-group">
	                        <label class="col-md-2 control-label">Kode Jasa</label>
	                        <div class="col-md-4">
	                            <input type="text" class="form-control" name="kode_jasa" id="kode_jasa" value="" readonly>
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-md-2 control-label">Jasa Perawatan</label>
	                        <div class="col-md-4">
	                        	<div class="input-group">
	                        		<input type="hidden" name="id_jasa" id="id_jasa" value="">
	                                <input type="text" class="form-control" id="jasa_txt" value="" readonly>
	                                <span class="input-group-btn">
	                                    <button type="button" class="btn btn-primary btn_jasa" style="cursor:cursor;"><i class="fa fa-search"></i></button>
	                                </span>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-md-2 control-label">Tarif</label>
	                        <div class="col-md-4">
	                            <input type="text" class="form-control" name="tarif_jasa" id="tarif_jasa" value="" readonly>
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-md-2 control-label">Jumlah</label>
	                        <div class="col-sm-4">
                				<div class="input-group">
                                    <input type="text" class="form-control num_only" name="jumlah_jasa" id="jumlah_jasa" value="" onkeyup="hitung_jasa(); hitung_jasa_hari();">
                                    <span class="input-group-addon">Kali</span>
                                </div>
                			</div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-md-2 control-label">Total</label>
	                        <div class="col-md-4">
	                            <input type="text" class="form-control" name="total_jasa" id="total_jasa" value="" readonly>
	                        </div>
	                    </div>
	                    <div class="form-group">
                			<label class="col-sm-2 control-label">Perawatan Selama</label>
                			<div class="col-sm-4">
                				<div class="input-group">
                                    <input type="text" class="form-control num_only" name="pemakaian_selama_jasa" id="pemakaian_selama_jasa" value="" onkeyup="hitung_jasa_hari();">
                                    <span class="input-group-addon">Hari</span>
                                </div>
                			</div>
                		</div>
                		<div class="form-group">
	                        <label class="col-md-2 control-label">Total Semua</label>
	                        <div class="col-md-4">
	                            <input type="text" class="form-control" name="total_semua_jasa" id="total_semua_jasa" value="" readonly>
	                        </div>
	                    </div>
	                    <hr>
	                    <center>
	                    	<button type="button" class="btn btn-success" id="simpanJP"><i class="fa fa-save"></i> <b>Simpan</b></button>
	                        <button type="button" class="btn btn-danger" id="batalJP"><i class="fa fa-times"></i> <b>Batal</b></button>
	                    </center>
                	</form>

                	<form class="form-horizontal" id="view_ubah_jasa">
                		<input type="hidden" name="id_rj" id="id_rj" value="<?php echo $id; ?>">
						<input type="hidden" name="id_pasien" value="<?php echo $dt->ID_PASIEN; ?>">
						<input type="hidden" name="id_ubah_jasa" id="id_ubah_jasa" value="">
	                    <div class="form-group">
	                        <label class="col-md-2 control-label">Jumlah</label>
	                        <div class="col-md-4">
	                            <input type="text" class="form-control" name="jumlah_infus_ubah" id="jumlah_infus_ubah" value="" onkeyup="FormatCurrency(this); hitung_infus();">
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-md-2 control-label">Tarif</label>
	                        <div class="col-md-4">
	                            <input type="text" class="form-control" name="tarif_infus_ubah" id="tarif_infus_ubah" value="" onkeyup="FormatCurrency(this); hitung_infus();">
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-md-2 control-label">Total</label>
	                        <div class="col-md-4">
	                            <input type="text" class="form-control" name="total_infus_ubah" id="total_infus_ubah" value="" readonly>
	                        </div>
	                    </div>
	                    <div class="form-group">
                			<label class="col-sm-2 control-label">Pemakaian Selama</label>
                			<div class="col-sm-4">
                				<div class="input-group">
                                    <input type="text" class="form-control num_only" name="pemakaian_selama_infus_ubah" id="pemakaian_selama_infus_ubah" value="">
                                    <span class="input-group-addon">Hari</span>
                                </div>
                			</div>
                		</div>
	                    <hr>
	                    <center>
	                    	<button type="button" class="btn btn-success" id="simpanUbahInfus"><i class="fa fa-save"></i> <b>Simpan</b></button>
	                        <button type="button" class="btn btn-danger" id="batalUbahInfus"><i class="fa fa-times"></i> <b>Batal</b></button>
	                    </center>
                	</form>
                </div>

                <div role="tabpanel" class="tab-pane fade" id="diagnosa1">
                	<form class="form-horizontal" id="view_diagnosa">
                    	<div class="form-group">
                    		<div class="col-md-6">
                    			<h4 class="m-t-0"><i class="fa fa-table"></i>&nbsp;<b>Tabel Diagnosa</b></h4>
                    		</div>
                    		<div class="col-md-6">
			                    <button class="btn btn-primary m-b-5 pull-right" type="button" id="btn_tambah_dg">
									<i class="fa fa-plus"></i>&nbsp;<b>Tambah Diagnosa</b>
								</button>
                    		</div>
                    	</div>
                    	<div class="form-group">
                    		<div class="col-md-12">
			                    <div class="table-responsive">
						            <table id="tabel_diagnosa" class="table table-bordered">
						                <thead>
						                    <tr class="merah">
						                        <th style="color:#fff; text-align:center;">No</th>
						                        <th style="color:#fff; text-align:center;">Tanggal</th>
						                        <th style="color:#fff; text-align:center;">Diagnosa</th>
						                        <th style="color:#fff; text-align:center;">Tindakan</th>
						                        <th style="color:#fff; text-align:center;">Aksi</th>
						                    </tr>
						                </thead>

						                <tbody>
						                    
						                </tbody>
						            </table>
						        </div>
                    		</div>
                    	</div>
                    </form>

                    <form class="form-horizontal" id="view_diagnosa_tambah" action="" method="post">
                    	<input type="hidden" name="id_ri" value="<?php echo $id; ?>">
						<input type="hidden" name="id_pasien" value="<?php echo $dt->ID_PASIEN; ?>">
						<div class="form-group">
							<label class="col-md-2 control-label">Diagnosa</label>
							<div class="col-md-8">
								<textarea class="form-control" rows="5" id="diagnosa" name="diagnosa"></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-2 control-label">Tindakan</label>
							<div class="col-md-8">
								<textarea class="form-control" rows="5" id="tindakan_dg" name="tindakan_dg"></textarea>
							</div>
						</div>
						<!-- <div class="form-group">
	                        <label class="col-md-2 control-label">Kasus</label>
	                        <div class="col-md-5">
	                        	<div class="input-group">
	                        		<input type="hidden" name="id_kasus" id="id_kasus" value="">
	                                <input type="text" class="form-control" id="kasus_dg" value="" readonly>
	                                <span class="input-group-btn">
	                                    <button type="button" class="btn btn-primary btn_kasus_dg" style="cursor:cursor;"><i class="fa fa-search"></i></button>
	                                </span>
	                            </div>
	                        </div>
	                    </div> -->
	                    <!-- <div class="form-group">
	                        <label class="col-md-2 control-label">Spesialistik</label>
	                        <div class="col-md-5">
	                            <div class="input-group">
	                            	<input type="hidden" name="id_spesialistik" id="id_spesialistik" value="">
	                                <input type="text" class="form-control" id="spesialistik_dg" name="spesialistik_dg" value="" readonly="readonly" required="required">
	                                <span class="input-group-btn">
	                                    <button type="button" class="btn btn-inverse btn_spesialistik_dg"><i class="fa fa-search"></i></button>
	                                </span>
	                            </div>
	                        </div>
	                    </div> -->
	                    <hr>
	                    <center>
	                    	<button type="button" class="btn btn-success" id="simpanDg"><i class="fa fa-save"></i> <b>Simpan</b></button>
	                        <button type="button" class="btn btn-danger" id="batalDg"><i class="fa fa-times"></i> <b>Batal</b></button>
	                    </center>
                    </form>

                    <form class="form-horizontal" id="view_diagnosa_ubah" action="" method="post">
                    	<input type="hidden" name="id_ubah_dg" id="id_ubah_dg" value="">
                    	<input type="hidden" name="id_ri" id="id_ri" value="<?php echo $id; ?>">
						<input type="hidden" name="id_pasien" value="<?php echo $dt->ID_PASIEN; ?>">
						<h4><i class="fa fa-plus"></i> Ubah Diagnosa</h4>
						<hr>
						<div class="form-group">
							<label class="col-md-2 control-label">Diagnosa</label>
							<div class="col-md-8">
								<textarea class="form-control" rows="5" id="diagnosa_ubah" name="diagnosa_ubah"></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-2 control-label">Tindakan</label>
							<div class="col-md-8">
								<textarea class="form-control" rows="5" id="tindakan_dg_ubah" name="tindakan_dg_ubah"></textarea>
							</div>
						</div>
	                    <hr>
	                    <center>
	                    	<button type="button" class="btn btn-success" id="simpanDgUbah"><i class="fa fa-save"></i> <b>Simpan</b></button>
	                        <button type="button" class="btn btn-danger" id="batalDgUbah"><i class="fa fa-times"></i> <b>Batal</b></button>
	                    </center>
                    </form>
                </div>

                <div role="tabpanel" class="tab-pane fade" id="laborat1">
                    <form class="form-horizontal" id="view_laborat">
                    	<div class="form-group">
                    		<div class="col-md-6">
                    			<div class="checkbox checkbox-primary">
	                                <input id="checkboxLab" type="checkbox">
	                                <label for="checkboxLab">
	                                    Perlu cek Laborat?
	                                </label>
	                            </div>
                    		</div>
                    	</div>
                    	<hr>
                    	<div class="form-group view_lab">
                    		<div class="col-md-6">
                    			<h4 class="m-t-0"><i class="fa fa-table"></i>&nbsp;<b>Tabel Laborat</b></h4>
                    		</div>
                    		<div class="col-md-6">
			                    <button class="btn btn-primary m-b-5 pull-right" type="button" id="btn_tambah_lab">
									<i class="fa fa-plus"></i>&nbsp;<b>Tambah Laborat</b>
								</button>
                    		</div>
                    	</div>
                    	<div class="form-group view_lab">
                    		<div class="col-md-12">
			                    <div class="table-responsive">
						            <table id="tabel_laborat" class="table table-bordered">
						                <thead>
						                    <tr class="merah">
						                        <th style="color:#fff; text-align:center;">No</th>
						                        <th style="color:#fff; text-align:center;">Tanggal</th>
						                        <th style="color:#fff; text-align:center;">Jenis Laborat</th>
						                        <th style="color:#fff; text-align:center;">Cito</th>
						                        <th style="color:#fff; text-align:center;">Total</th>
						                        <th style="color:#fff; text-align:center;">Cetak</th>
						                        <th style="color:#fff; text-align:center;">Aksi</th>
						                    </tr>
						                </thead>

						                <tbody>
						                    
						                </tbody>
						            </table>
						        </div>
                    		</div>
                    	</div>
                    	<div class="form-group view_lab">
                    		<div class="col-md-8">
                    			&nbsp;
                    		</div>
                    		<div class="col-md-4">
                    			<div class="card-box widget-user" style="background-color:#cee3f8;">
		                            <div>
		                                <img alt="user" class="img-responsive img-circle" src="<?php echo base_url(); ?>picture/Money_44325.png">
		                                <div class="wid-u-info">
		                                    <small class="text-primary"><b>Grand Total</b></small>
		                                    <h4 class="m-t-0 m-b-5 font-600 text-danger" id="grandtotal_laborat">0</h4>
		                                </div>
		                            </div>
		                        </div>
                    		</div>
                    	</div>
                    </form>

                    <form class="form-horizontal" id="view_laborat_tambah" action="" method="post">
                    	<input type="hidden" name="id_ri" id="id_ri" value="<?php echo $id; ?>">
						<input type="hidden" name="id_dokter" value="<?php echo $dt->ID_DOKTER; ?>">
						<input type="hidden" name="id_pasien" value="<?php echo $dt->ID_PASIEN; ?>">
						<h4><i class="fa fa-plus"></i> Tambah Laborat</h4>
						<hr>
						<div class="form-group">
							<label class="col-md-2 control-label">Kode</label>
							<div class="col-md-5">
								<input type="text" class="form-control" name="kode_lab" id="kode_lab" value="" readonly>
							</div>
						</div>
						<div class="form-group">
	                        <label class="col-md-2 control-label">Jenis Laborat</label>
	                        <div class="col-md-5">
	                        	<div class="input-group">
	                        		<input type="hidden" name="id_laborat" id="id_laborat" value="">
	                                <input type="text" class="form-control" id="jenis_laborat" value="" readonly>
	                                <span class="input-group-btn">
	                                    <button type="button" class="btn btn-primary btn_jenis_laborat" style="cursor:cursor;"><i class="fa fa-search"></i></button>
	                                </span>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-md-2 control-label">Pemeriksaan</label>
	                        <div class="col-md-5">
	                            <div class="input-group">
	                                <input type="text" class="form-control" value="" readonly="readonly" required="required">
	                                <span class="input-group-btn">
	                                    <button type="button" class="btn btn-inverse btn_pemeriksaan"><i class="fa fa-search"></i></button>
	                                </span>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-md-2 control-label">&nbsp;</label>
	                        <div class="col-md-8">
	                            <div class="table-responsive">
						            <table id="tabel_tambah_pemeriksaan" class="table table-bordered">
						                <thead>
						                    <tr class="kuning_tr">
						                        <th style="color:#fff; text-align:center;">Pemeriksaan</th>
						                        <!-- <th style="color:#fff; text-align:center;">Hasil</th>
						                        <th style="color:#fff; text-align:center;">Nilai Rujukan</th> -->
						                        <th style="color:#fff; text-align:center;">Tarif</th>
						                        <th style="color:#fff; text-align:center;">Sub Total</th>
						                        <th style="color:#fff; text-align:center;">#</th>
						                    </tr>
						                </thead>

						                <tbody>
						                    
						                </tbody>
						            </table>
						        </div>
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-md-2 control-label">Total Tarif</label>
	                        <div class="col-md-5">
	                            <input type="text" class="form-control" name="total_tarif_pemeriksaan" id="total_tarif_pemeriksaan" value="" readonly="readonly">
	                        </div>
	                    </div>
	                    <div class="form-group">
	                    	<label class="col-md-2 control-label">Cito</label>
	                    	<div class="col-md-5">
	                    		<div class="radio radio-primary radio-inline">
	                                <input type="radio" id="inlineRadio1" value="1" name="cito">
	                                <label for="inlineRadio1"> Aktif </label>
	                            </div>
	                            <div class="radio radio-primary radio-inline">
	                                <input type="radio" id="inlineRadio2" value="0" name="cito">
	                                <label for="inlineRadio2"> Tidak Aktif </label>
	                            </div>
	                    	</div>
	                    </div>
	                    <hr>
	                    <center>
	                    	<button type="button" class="btn btn-success" id="simpanLab"><i class="fa fa-save"></i> <b>Simpan</b></button>
	                        <button type="button" class="btn btn-danger" id="batalLab"><i class="fa fa-times"></i> <b>Batal</b></button>
	                    </center>
                    </form>
                </div>

                <div role="tabpanel" class="tab-pane fade" id="resep1">
                	<form class="form-horizontal" id="view_resep">
                    	<div class="form-group">
                    		<div class="col-md-6">
                    			<h4 class="m-t-0"><i class="fa fa-table"></i>&nbsp;<b>Tabel Resep</b></h4>
                    		</div>
                    		<div class="col-md-6">
			                    <button class="btn btn-primary m-b-5 pull-right" type="button" id="btn_tambah_resep">
									<i class="fa fa-plus"></i>&nbsp;<b>Tambah Resep</b>
								</button>
                    		</div>
                    	</div>
                    	<div class="form-group">
                    		<div class="col-md-12">
			                    <div class="table-responsive">
						            <table id="tabel_resep" class="table table-bordered">
						                <thead>
						                    <tr class="merah">
						                        <th style="color:#fff; text-align:center;">No</th>
						                        <th style="color:#fff; text-align:center;">Tanggal</th>
						                        <th style="color:#fff; text-align:center;">Kode Resep</th>
						                        <th style="color:#fff; text-align:center;">Total</th>
						                        <th style="color:#fff; text-align:center;">Diminum Selama</th>
						                        <th style="color:#fff; text-align:center;">Aksi</th>
						                    </tr>
						                </thead>

						                <tbody>
						                    
						                </tbody>
						            </table>
						        </div>
                    		</div>
                    	</div>
                    </form>

                    <form class="form-horizontal" id="view_resep_tambah" action="" method="post">
                    	<input type="hidden" name="id_rj" id="id_rj" value="<?php echo $id; ?>">
						<input type="hidden" name="id_pasien" value="<?php echo $dt->ID_PASIEN; ?>">
						<input type="hidden" name="grandtotal_resep_txt" id="grandtotal_resep_txt" value="">
						<h4><i class="fa fa-plus"></i> Tambah Resep</h4>
						<hr>
						<div class="form-group">
							<label class="col-md-2 control-label">Kode Resep</label>
							<div class="col-md-5">
								<input type="text" class="form-control" name="kode_resep" id="kode_resep" value="" readonly>
							</div>
						</div>
						<div class="form-group">
	                        <label class="col-md-2 control-label">Obat</label>
	                        <div class="col-md-5">
	                        	<div class="input-group">
	                        		<input type="hidden" name="id_obat" id="id_obat" value="">
	                                <input type="text" class="form-control" id="obat_resep" value="" readonly>
	                                <span class="input-group-btn">
	                                    <button type="button" class="btn btn-primary btn_obat_resep" style="cursor:cursor;"><i class="fa fa-search"></i></button>
	                                </span>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="form-group">
	                    	<label class="col-md-2 control-label">&nbsp;</label>
	                    	<div class="col-md-10">
	                    		<div class="table-responsive">
						            <table id="tabel_tambah_resep" class="table table-bordered">
						                <thead>
						                    <tr class="kuning_tr">
						                        <th style="color:#fff; text-align:center;">Kode Obat</th>
						                        <th style="color:#fff; text-align:center;">Nama Obat</th>
						                        <th style="color:#fff; text-align:center;">Harga</th>
						                        <th style="color:#fff; text-align:center;">Jumlah</th>
						                        <th style="color:#fff; text-align:center;">Total</th>
						                        <th style="color:#fff; text-align:center;">Takaran</th>
						                        <th style="color:#fff; text-align:center;">Aturan Minum</th>
						                        <th style="color:#fff; text-align:center;">#</th>
						                    </tr>
						                </thead>

						                <tbody>
						                    
						                </tbody>
						                <tfoot>
						                	<tr class="active">
						                		<td style="text-align: center; font-weight: bold;" colspan="6">GRANDTOTAL</td>
						                		<td style="text-align: right;" colspan="2"><b id="grandtotal_resep">0</b></td>
						                	</tr>
						                </tfoot>
						            </table>
						        </div>
	                    	</div>
	                    </div>
	                    <div class="form-group">
	                    	<label class="col-md-2 control-label">Diminum Selama</label>
	                    	<div class="col-sm-5">
                				<div class="input-group">
                                    <input type="text" class="form-control num_only" name="diminum_selama" id="diminum_selama" value="">
                                    <span class="input-group-addon">Hari</span>
                                </div>
                			</div>
	                    </div>
	                    <hr>
	                    <center>
	                    	<button type="button" class="btn btn-success" id="simpanResep"><i class="fa fa-save"></i> <b>Simpan</b></button>
	                        <button type="button" class="btn btn-danger" id="batalResep"><i class="fa fa-times"></i> <b>Batal</b></button>
	                    </center>
                    </form>
                </div>

                <div role="tabpanel" class="tab-pane fade" id="kondisi_akhir1">
                	<div class="row">
                		<div class="col-md-12">
                			<div class="alert alert-info">
                                <strong>Pastikan Anda Mengisi Dengan Benar!</strong> 
                                <p>Jika pasien dianggap selesai melakukan pelayanan maka akan hilang dari daftar pelayanan.</p>
                            </div>
                		</div>
                	</div>
                	<hr>
                	<form class="form-horizontal" id="view_kondisi_akhir">
                		<input type="hidden" name="id_rj" id="id_rj" value="<?php echo $id; ?>">
						<input type="hidden" name="id_pasien" value="<?php echo $dt->ID_PASIEN; ?>">
						<input type="hidden" name="asal_rujukan" value="<?php echo $dt->ASAL_RUJUKAN; ?>">
                		<div class="form-group">
                            <label class="col-sm-2 control-label">Status</label>
                            <div class="col-sm-4">
                                <select class="form-control" name="kondisi_akhir" id="kondisi_akhir">
                                    <option value="Dirawat">Dirawat</option>
                                    <option value="Pulang">Pulang</option>
                                    <option value="Dirujuk">Dirujuk</option>
                                    <option value="Operasi">Operasi</option>
                                    <option value="Meninggal">Meninggal</option>
                                </select>
                            </div>
                        </div>
                		<div class="form-group" id="view_dirawat_selama">
                			<label class="col-sm-2 control-label">Dirawat Selama</label>
                			<div class="col-sm-4">
                				<div class="input-group">
                                    <input type="text" class="form-control num_only" name="dirawat_selama" id="dirawat_selama" value="">
                                    <span class="input-group-addon">Hari</span>
                                </div>
                			</div>
                		</div>
                		<div class="form-group">
                			<label class="col-sm-2 control-label">Tanggal Keluar</label>
                			<div class="col-sm-4">
                				<input type="text" class="form-control" name="tanggal_keluar" data-mask="99-99-9999" value="">
                			</div>
                		</div>

                        <!-- <div id="view_icu">
                        	<div class="form-group">
		                        <label class="col-md-2 control-label">Ruang ICU</label>
		                        <div class="col-md-4">
		                            <div class="input-group">
		                                <input type="hidden" name="id_ruang_icu" id="id_ruang_icu" value="">
		                                <input type="text" class="form-control" id="ruang_icu" value="" readonly>
		                                <span class="input-group-btn">
		                                    <button type="button" class="btn btn-danger btn_ruang_icu"><i class="fa fa-search"></i></button>
		                                </span>
		                            </div>
		                        </div>
		                    </div>
		                    <div class="form-group">
		                        <label class="col-md-2 control-label">Level</label>
		                        <div class="col-md-4">
		                            <input type="text" class="form-control" name="level_icu" id="level_icu" value="" readonly>
		                        </div>
		                    </div>
		                    <div class="form-group">
		                        <label class="col-md-2 control-label">Tipe</label>
		                        <div class="col-md-4">
		                            <input type="text" class="form-control" name="tipe_icu" id="tipe_icu" value="" readonly>
		                        </div>
		                    </div>
		                    <div class="form-group">
		                        <label class="col-md-2 control-label">Tarif</label>
		                        <div class="col-md-4">
		                            <input type="text" class="form-control" name="tarif_icu" id="tarif_icu" value="" onkeyup="FormatCurrency(this);">
		                        </div>
		                    </div>
		                    <hr>
                        </div> -->

                        <div id="view_operasi">
                        	<div class="form-group">
		                        <label class="col-md-2 control-label">Ruang Operasi</label>
		                        <div class="col-md-4">
		                            <div class="input-group">
		                                <input type="hidden" name="id_ruang_opr" id="id_ruang_opr" value="">
		                                <input type="text" class="form-control" id="ruang_operasi" value="" readonly>
		                                <span class="input-group-btn">
		                                    <button type="button" class="btn btn-danger btn_ruang_opr"><i class="fa fa-search"></i></button>
		                                </span>
		                            </div>
		                        </div>
		                    </div>
		                    <div class="form-group">
		                        <label class="col-md-2 control-label">Tarif</label>
		                        <div class="col-md-4">
		                            <input type="text" class="form-control" name="tarif_operasi" id="tarif_operasi" value="" onkeyup="FormatCurrency(this);">
		                        </div>
		                    </div>
		                    <hr>
                        </div>

                        <div id="view_meninggal">
                        	<div class="form-group">
		                        <label class="col-md-2 control-label">Kamar Jenazah</label>
		                        <div class="col-md-4">
		                            <div class="input-group">
		                                <input type="hidden" name="id_kamar_jenazah" id="id_kamar_jenazah" value="">
		                                <input type="text" class="form-control" id="kamar_jenazah" value="" readonly>
		                                <span class="input-group-btn">
		                                    <button type="button" class="btn btn-danger btn_kamar_jenazah"><i class="fa fa-search"></i></button>
		                                </span>
		                            </div>
		                        </div>
		                    </div>
		                    <div class="form-group">
		                        <label class="col-md-2 control-label">Tarif</label>
		                        <div class="col-md-4">
		                            <input type="text" class="form-control" name="tarif_kamar_jenazah" id="tarif_kamar_jenazah" value="" readonly>
		                        </div>
		                    </div>
		                    <div class="form-group">
		                        <label class="col-md-2 control-label">Lemari Jenazah</label>
		                        <div class="col-md-4">
		                            <div class="input-group">
		                                <input type="hidden" name="id_lemari_jenazah" id="id_lemari_jenazah" value="">
		                                <input type="text" class="form-control" id="lemari_jenazah" value="" readonly>
		                                <span class="input-group-btn">
		                                    <button type="button" class="btn btn-primary btn_lemari_jenazah"><i class="fa fa-search"></i></button>
		                                </span>
		                            </div>
		                        </div>
		                    </div>
		                    <hr>
                        </div>
                        
                        <center>
	                    	<button type="button" class="btn btn-success" id="simpanKA"><i class="fa fa-save"></i> <b>Simpan</b></button>
	                        <button type="button" class="btn btn-danger" id="batalKA"><i class="fa fa-times"></i> <b>Batal</b></button>
	                    </center>
                    </form>
                </div>

                <div role="tabpanel" class="tab-pane fade" id="surat_dokter1">
                	<form class="form-horizontal" id="view_surat_dokter">
                    	<div class="form-group">
                    		<div class="col-md-6">
                    			<h4 class="m-t-0"><i class="fa fa-table"></i>&nbsp;<b>Tabel Surat Dokter</b></h4>
                    		</div>
                    		<div class="col-md-6">
			                    <button class="btn btn-primary m-b-5 pull-right" type="button" id="btn_tambah_sd">
									<i class="fa fa-plus"></i>&nbsp;<b>Tambah Data</b>
								</button>
                    		</div>
                    	</div>
                    	<div class="form-group">
                    		<div class="col-md-12">
			                    <div class="table-responsive">
						            <table id="tabel_surat_dokter" class="table table-bordered">
						                <thead>
						                    <tr class="merah">
						                        <th style="color:#fff; text-align:center;">No</th>
						                        <th style="color:#fff; text-align:center;">Tanggal</th>
						                        <th style="color:#fff; text-align:center;">Nama Pasien</th>
						                        <th style="color:#fff; text-align:center;">Jenis Kelamin</th>
						                        <th style="color:#fff; text-align:center;">Umur</th>
						                        <th style="color:#fff; text-align:center;">Aksi</th>
						                    </tr>
						                </thead>

						                <tbody>
						                    
						                </tbody>
						            </table>
						        </div>
                    		</div>
                    	</div>
                    </form>

	                <form class="form-horizontal" id="form_surat_dokter">
	                	<input type="hidden" name="id_rj" id="id_rj" value="<?php echo $id; ?>">
						<input type="hidden" name="id_pasien" value="<?php echo $dt->ID_PASIEN; ?>">
						<input type="hidden" name="asal_rujukan" value="<?php echo $dt->ASAL_RUJUKAN; ?>">
	                	<div class="form-group">
	                        <label class="col-md-2 control-label">Nama</label>
	                        <div class="col-md-4">
	                            <input type="text" class="form-control" name="nama_sd" id="nama_sd" value="<?php echo $dt->NAMA_PASIEN; ?>" readonly>
	                        </div>
	                    </div>
	                    <div class="form-group">
                            <label class="col-md-2 control-label">Umur</label>
                            <div class="col-md-4">
	                            <div class="input-group">
	                                <input type="text" class="form-control" name="umur_sd" id="umur_sd" value="<?php echo $dt->UMUR; ?>" readonly>
	                                <span class="input-group-btn">
	                                	<button class="btn btn-primary" type="button" style="cursor:default;">Tahun</button>
	                                </span>
	                            </div>
                            </div>
                        </div>
	                    <div class="form-group">
	                        <label class="col-md-2 control-label">Pekerjaan</label>
	                        <div class="col-md-4">
	                        	<?php
	                        		$dt->PEKERJAAN = $dt->PEKERJAAN==null?"-":$dt->PEKERJAAN;
	                        	?>
	                            <input type="text" class="form-control" name="pekerjaan_sd" id="pekerjaan_sd" value="<?php echo $dt->PEKERJAAN; ?>" readonly>
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-md-2 control-label">Alamat</label>
	                        <div class="col-md-4">
	                            <textarea class="form-control" name="alamat_sd" id="alamat_sd" rows="3" readonly><?php echo $dt->ALAMAT; ?></textarea>
	                        </div>
	                    </div>
	                    <div class="form-group">
                            <label class="col-md-2 control-label">Waktu Istirahat</label>
                            <div class="col-md-4">
	                            <div class="input-group">
	                                <input type="text" class="form-control num_only" name="waktu_sd" id="waktu_sd" value="">
	                                <span class="input-group-btn">
	                                	<button class="btn btn-primary" type="button" style="cursor:default;">Hari</button>
	                                </span>
	                            </div>
                            </div>
                        </div>
                        <div class="form-group">
	                        <label class="col-md-2 control-label">Mulai Tanggal</label>
	                        <div class="col-md-4">
	                            <input type="text" class="form-control" name="mulai_tgl_sd" id="mulai_tgl_sd" value="" data-mask="99-99-9999">
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-md-2 control-label">Sampai Tanggal</label>
	                        <div class="col-md-4">
	                            <input type="text" class="form-control" name="sampai_tgl_sd" id="sampai_tgl_sd" value="" data-mask="99-99-9999">
	                        </div>
	                    </div>
	                    
	                    <hr>

	                    <center>
	                    	<button type="button" class="btn btn-success" id="simpanSD"><i class="fa fa-save"></i> <b>Simpan</b></button>
	                        <button type="button" class="btn btn-danger" id="batalSD"><i class="fa fa-times"></i> <b>Batal</b></button>
	                    </center>
	                </form>
                </div>
            </div>

		    <form class="form-horizontal">
				<div class="form-group">&nbsp;</div>
				<div class="form-group">
					<div class="col-md-4">
						<button class="btn btn-purple btn-block" type="button" id="btn_kembali">
							<i class="fa fa-arrow-circle-left"></i>&nbsp;<b>Kembali</b>
						</button>	
					</div>
				</div>
			</form>
        </div>
    </div>
</div>

<!-- TINDAKAN -->
<button class="btn btn-primary" data-toggle="modal" data-target="#myModal1" id="popup_tindakan" style="display:none;">Standard Modal</button>
<div id="myModal1" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="width:50%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Data Tindakan</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form">
		            <div class="form-group">
		                <div class="col-md-12">
			                <div class="input-group">
			                    <input type="text" class="form-control" id="cari_tindakan" placeholder="Cari..." value="">
			                    <span class="input-group-btn">
			                    	<button type="button" class="btn waves-effect waves-light btn-custom" style="cursor:default;">
			                    		<i class="fa fa-search"></i>
			                    	</button>
			                    </span>
			                </div>
		                </div>
		            </div>
		        </form>
            	<div class="table-responsive">
			        <div class="loading_tabel">
			        	<center>
			        		<img src="<?php echo base_url(); ?>picture/processando.gif" style="width: 75px; height: 75px;">
			        	</center>
			        </div>
            		<div class="scroll-y">
		                <table class="table table-hover table-bordered" id="tb_tindakan">
		                    <thead>
		                        <tr class="hijau_popup">
		                            <th style="text-align:center; color: #fff;" width="50">No</th>
		                            <th style="text-align:center; color: #fff;">Kode</th>
		                            <th style="text-align:center; color: #fff;">Tindakan</th>
		                            <th style="text-align:center; color: #fff;">Tarif</th>
		                        </tr>
		                    </thead>
		                    <tbody>
		                        
		                    </tbody>
		                </table>
            		</div>
            	</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_tindakan">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- // -->

<button class="btn btn-primary" data-toggle="modal" data-target="#myModal2" id="popup_pelaksana" style="display:none;">Standard Modal</button>
<div id="myModal2" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="width:50%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Data Pelaksana</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form">
		            <div class="form-group">
		                <div class="col-md-12">
			                <div class="input-group">
			                    <input type="text" class="form-control" id="cari_pelaksana" placeholder="Cari..." value="">
			                    <span class="input-group-btn">
			                    	<button type="button" class="btn waves-effect waves-light btn-custom" style="cursor:default;">
			                    		<i class="fa fa-search"></i>
			                    	</button>
			                    </span>
			                </div>
		                </div>
		            </div>
		        </form>
            	<div class="table-responsive">
            		<div class="scroll-y">
		                <table class="table table-hover table-bordered" id="tb_pelaksana">
		                    <thead>
		                        <tr class="hijau_popup">
		                            <th style="text-align:center; color: #fff;">No</th>
		                            <th style="text-align:center; color: #fff;">NIP</th>
		                            <th style="text-align:center; color: #fff;">Nama Dokter</th>
		                            <th style="text-align:center; color: #fff;">Jabatan</th>
		                            <th style="text-align:center; color: #fff;">Poli</th>
		                        </tr>
		                    </thead>
		                    <tbody>
		                        
		                    </tbody>
		                </table>
            		</div>
            	</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_pelaksana">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<button id="popup_hapus" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#custom-width-modal" style="display:none;">Custom width Modal</button>
<div id="custom-width-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="width:55%;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="custom-width-modalLabel">Konfirmasi Hapus</h4>
            </div>
            <div class="modal-body">
                <p id="msg"></p>
            </div>
            <div class="modal-footer">
            	<form action="<?php echo $url_hapus; ?>" method="post">
            		<input type="hidden" name="id_hapus" id="id_hapus" value="">
            		<input type="hidden" name="id_pelayanan" value="<?php echo $id; ?>">
            		<input type="hidden" name="ket_hapus" id="ket_hapus" value="">
	                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Tidak</button>
	                <button type="submit" class="btn btn-danger waves-effect waves-light">Ya</button>
            	</form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- VISITE -->
<button class="btn btn-primary" data-toggle="modal" data-target="#myModal3" id="popup_visite" style="display:none;">Standard Modal</button>
<div id="myModal3" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="width:50%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Data Visite</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form">
		            <div class="form-group">
		                <div class="col-md-12">
			                <div class="input-group">
			                    <input type="text" class="form-control" id="cari_visite" placeholder="Cari..." value="">
			                    <span class="input-group-btn">
			                    	<button type="button" class="btn waves-effect waves-light btn-custom" style="cursor:default;">
			                    		<i class="fa fa-search"></i>
			                    	</button>
			                    </span>
			                </div>
		                </div>
		            </div>
		        </form>
            	<div class="table-responsive">
            		<div class="scroll-y">
		                <table class="table table-hover table-bordered" id="tb_visite">
		                    <thead>
		                        <tr class="hijau_popup">
		                            <th style="text-align:center; color: #fff;">No</th>
		                            <th style="text-align:center; color: #fff;">Kode</th>
		                            <th style="text-align:center; color: #fff;">Visite</th>
		                            <th style="text-align:center; color: #fff;">Tarif</th>
		                        </tr>
		                    </thead>
		                    <tbody>
		                        
		                    </tbody>
		                </table>
            		</div>
            	</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_visite">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<button class="btn btn-primary" data-toggle="modal" data-target="#myModal4" id="popup_dokter" style="display:none;">Standard Modal</button>
<div id="myModal4" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="width:50%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Data Dokter</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form">
		            <div class="form-group">
		                <div class="col-md-12">
			                <div class="input-group">
			                    <input type="text" class="form-control" id="cari_dokter" placeholder="Cari..." value="">
			                    <span class="input-group-btn">
			                    	<button type="button" class="btn waves-effect waves-light btn-custom" style="cursor:default;">
			                    		<i class="fa fa-search"></i>
			                    	</button>
			                    </span>
			                </div>
		                </div>
		            </div>
		        </form>
            	<div class="table-responsive">
            		<div class="scroll-y">
		                <table class="table table-hover table-bordered" id="tb_dokter">
		                    <thead>
		                        <tr class="hijau_popup">
		                            <th style="text-align:center; color: #fff;">No</th>
		                            <th style="text-align:center; color: #fff;">Kode</th>
		                            <th style="text-align:center; color: #fff;">Nama Dokter</th>
		                            <th style="text-align:center; color: #fff;">Jabatan</th>
		                        </tr>
		                    </thead>
		                    <tbody>
		                        
		                    </tbody>
		                </table>
            		</div>
            	</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_dokter">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<button id="popup_hapus_visite" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#custom-width-modal2" style="display:none;">Custom width Modal</button>
<div id="custom-width-modal2" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="width:55%;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="custom-width-modalLabel">Konfirmasi Hapus</h4>
            </div>
            <div class="modal-body">
                <p id="msg_visite"></p>
            </div>
            <div class="modal-footer">
            	<form action="" method="post">
            		<input type="hidden" name="id_hapus_visite" id="id_hapus_visite" value="">
	                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal" id="tidak_visite">Tidak</button>
	                <button type="button" class="btn btn-danger waves-effect waves-light" id="ya_visite">Ya</button>
            	</form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- GIZI -->
<button class="btn btn-primary" data-toggle="modal" data-target="#myModal5" id="popup_gizi" style="display:none;">Standard Modal</button>
<div id="myModal5" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="width:50%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Data Visite</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form">
		            <div class="form-group">
		                <div class="col-md-12">
			                <div class="input-group">
			                    <input type="text" class="form-control" id="cari_gizi" placeholder="Cari..." value="">
			                    <span class="input-group-btn">
			                    	<button type="button" class="btn waves-effect waves-light btn-custom" style="cursor:default;">
			                    		<i class="fa fa-search"></i>
			                    	</button>
			                    </span>
			                </div>
		                </div>
		            </div>
		        </form>
            	<div class="table-responsive">
            		<div class="scroll-y">
		                <table class="table table-hover table-bordered" id="tb_gizi">
		                    <thead>
		                        <tr class="hijau_popup">
		                            <th style="text-align:center; color: #fff;">No</th>
		                            <th style="text-align:center; color: #fff;">Kode</th>
		                            <th style="text-align:center; color: #fff;">Gizi</th>
		                            <th style="text-align:center; color: #fff;">Tarif</th>
		                        </tr>
		                    </thead>
		                    <tbody>
		                        
		                    </tbody>
		                </table>
            		</div>
            	</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_gizi">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<button id="popup_hapus_gizi" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#custom-width-modal3" style="display:none;">Custom width Modal</button>
<div id="custom-width-modal3" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="width:55%;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="custom-width-modalLabel">Konfirmasi Hapus</h4>
            </div>
            <div class="modal-body">
                <p id="msg_gizi"></p>
            </div>
            <div class="modal-footer">
            	<form action="" method="post">
            		<input type="hidden" name="id_hapus_gizi" id="id_hapus_gizi" value="">
	                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal" id="tidak_gizi">Tidak</button>
	                <button type="button" class="btn btn-danger waves-effect waves-light" id="ya_gizi">Ya</button>
            	</form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- OKSIGEN -->
<button id="popup_hapus_oksigen" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#custom-width-modal4" style="display:none;">Custom width Modal</button>
<div id="custom-width-modal4" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="width:55%;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="custom-width-modalLabel">Konfirmasi Hapus</h4>
            </div>
            <div class="modal-body">
                <p id="msg_oksigen"></p>
            </div>
            <div class="modal-footer">
            	<form action="" method="post">
            		<input type="hidden" name="id_hapus_oksigen" id="id_hapus_oksigen" value="">
	                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal" id="tidak_oksigen">Tidak</button>
	                <button type="button" class="btn btn-danger waves-effect waves-light" id="ya_oksigen">Ya</button>
            	</form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- INFUS -->
<button id="popup_hapus_infus" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#custom-width-modal5" style="display:none;">Custom width Modal</button>
<div id="custom-width-modal5" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="width:55%;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="custom-width-modalLabel">Konfirmasi Hapus</h4>
            </div>
            <div class="modal-body">
                <p id="msg_infus"></p>
            </div>
            <div class="modal-footer">
            	<form action="" method="post" id="form_hapus_infus">
            		<input type="hidden" name="id_hapus_infus" id="id_hapus_infus" value="">
            		<input type="hidden" name="id_rj" id="id_rj" value="<?php echo $id; ?>">
					<input type="hidden" name="id_pasien" value="<?php echo $dt->ID_PASIEN; ?>">
	                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal" id="tidak_infus">Tidak</button>
	                <button type="button" class="btn btn-danger waves-effect waves-light" id="ya_infus">Ya</button>
            	</form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- JASA PERAWAT -->
<button class="btn btn-primary" data-toggle="modal" data-target="#myModal6" id="popup_jasa" style="display:none;">Standard Modal</button>
<div id="myModal6" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="width:50%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Data Jasa Perawat</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form">
		            <div class="form-group">
		                <div class="col-md-12">
			                <div class="input-group">
			                    <input type="text" class="form-control" id="cari_jasa" placeholder="Cari..." value="">
			                    <span class="input-group-btn">
			                    	<button type="button" class="btn waves-effect waves-light btn-custom" style="cursor:default;">
			                    		<i class="fa fa-search"></i>
			                    	</button>
			                    </span>
			                </div>
		                </div>
		            </div>
		        </form>
            	<div class="table-responsive">
            		<div class="scroll-y">
		                <table class="table table-hover table-bordered" id="tb_jasa">
		                    <thead>
		                        <tr class="hijau_popup">
		                            <th style="text-align:center; color: #fff;">No</th>
		                            <th style="text-align:center; color: #fff;">Kode</th>
		                            <th style="text-align:center; color: #fff;">Jasa Perawatan</th>
		                            <th style="text-align:center; color: #fff;">Tarif</th>
		                        </tr>
		                    </thead>
		                    <tbody>
		                        
		                    </tbody>
		                </table>
            		</div>
            	</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_jasa">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<button id="popup_hapus_jasa" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#custom-width-modal6" style="display:none;">Custom width Modal</button>
<div id="custom-width-modal6" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="width:55%;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="custom-width-modalLabel">Konfirmasi Hapus</h4>
            </div>
            <div class="modal-body">
                <p id="msg_jasa"></p>
            </div>
            <div class="modal-footer">
            	<form action="" method="post" id="form_hapus_jasa">
            		<input type="hidden" name="id_hapus_jasa" id="id_hapus_jasa" value="">
            		<input type="hidden" name="id_rj" id="id_rj" value="<?php echo $id; ?>">
					<input type="hidden" name="id_pasien" value="<?php echo $dt->ID_PASIEN; ?>">
	                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal" id="tidak_jasa">Tidak</button>
	                <button type="button" class="btn btn-danger waves-effect waves-light" id="ya_jasa">Ya</button>
            	</form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- LABORAT -->
<button class="btn btn-primary" data-toggle="modal" data-target="#myModal1_laborat" id="popup_laborat" style="display:none;">Standard Modal</button>
<div id="myModal1_laborat" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Data Jenis Laborat</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form">
		            <div class="form-group">
		                <div class="col-md-12">
			                <div class="input-group">
			                    <input type="text" class="form-control" id="cari_laborat" placeholder="Cari..." value="">
			                    <span class="input-group-btn">
			                    	<button type="button" class="btn waves-effect waves-light btn-custom" style="cursor:default;">
			                    		<i class="fa fa-search"></i>
			                    	</button>
			                    </span>
			                </div>
		                </div>
		            </div>
		        </form>
            	<div class="table-responsive">
            		<div class="scroll-y">
		                <table class="table table-hover table-bordered" id="tb_laborat">
		                    <thead>
		                        <tr class="hijau_popup">
		                            <th style="text-align:center; color: #fff;" width="50">No</th>
		                            <th style="text-align:center; color: #fff;">Jenis Laborat</th>
		                        </tr>
		                    </thead>
		                    <tbody>
		                        
		                    </tbody>
		                </table>
            		</div>
            	</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_laborat">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<button class="btn btn-primary" data-toggle="modal" data-target="#myModal1_pemeriksaan" id="popup_pemeriksaan" style="display:none;">Standard Modal</button>
<div id="myModal1_pemeriksaan" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="width:50%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Data Tindakan</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form">
		            <div class="form-group">
		                <div class="col-md-12">
			                <div class="input-group">
			                    <input type="text" class="form-control" id="cari_pemeriksaan" placeholder="Cari..." value="">
			                    <span class="input-group-btn">
			                    	<button type="button" class="btn waves-effect waves-light btn-custom" style="cursor:default;">
			                    		<i class="fa fa-search"></i>
			                    	</button>
			                    </span>
			                </div>
		                </div>
		            </div>
		        </form>
            	<div class="table-responsive">
            		<div class="scroll-y">
		                <table class="table table-hover table-bordered" id="tb_pemeriksaan">
		                    <thead>
		                        <tr class="hijau_popup">
		                            <th style="text-align:center; color: #fff;" width="50">No</th>
		                            <th style="text-align:center; color: #fff;">Kode</th>
		                            <th style="text-align:center; color: #fff;">Pemeriksaan</th>
		                            <th style="text-align:center; color: #fff;">Tarif</th>
		                        </tr>
		                    </thead>
		                    <tbody>
		                        
		                    </tbody>
		                </table>
            		</div>
            	</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_pemeriksaan">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<button class="btn btn-primary" data-toggle="modal" data-target="#myModal1_hasil_lab" id="popup_hasil_lab" style="display:none;">Standard Modal</button>
<div id="myModal1_hasil_lab" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="width:50%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Hasil Laborat</h4>
            </div>
            <div class="modal-body">
            	<div class="table-responsive">
            		<div class="scroll-y">
		                <table class="table table-bordered" id="tb_hasil_lab">
		                    <thead>
		                        <tr class="hijau_popup">
		                            <th style="text-align:center; color: #fff;" width="50">No</th>
		                            <th style="text-align:center; color: #fff;">Pemeriksaan</th>
		                            <th style="text-align:center; color: #fff;">Tarif</th>
		                            <th style="text-align:center; color: #fff;">Subtotal</th>
		                        </tr>
		                    </thead>
		                    <tbody>
		                        
		                    </tbody>
		                    <tfoot>
		                    	<tr class="active">
		                    		<td colspan="2">&nbsp;</td>
		                    		<td style="text-align:right;"><b>TOTAL :</b></td>
		                    		<td style="text-align:right;"><b id="total_laborat" class="text-danger"></b></td>
		                    	</tr>
		                    </tfoot>
		                </table>
            		</div>
            	</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_hasil_lab">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<button id="popup_hapus_lab" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#custom-width-modal-lab" style="display:none;">Custom width Modal</button>
<div id="custom-width-modal-lab" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="width:55%;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="custom-width-modalLabel">Konfirmasi Hapus</h4>
            </div>
            <div class="modal-body">
                <p id="msg_lab"></p>
            </div>
            <div class="modal-footer">
            	<form action="" method="post" id="form_hapus_lab">
            		<input type="hidden" name="id_hapus_lab" id="id_hapus_lab" value="">
            		<input type="hidden" name="id_pelayanan_lab" value="<?php echo $id; ?>">
            		<input type="hidden" name="ket_hapus_lab" id="ket_hapus_lab" value="">
	                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal" id="tidak_lab">Tidak</button>
	                <button type="button" class="btn btn-danger waves-effect waves-light" id="ya_lab">Ya</button>
            	</form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- RESEP -->
<button id="popup_resep" class="btn btn-primary" data-toggle="modal" data-target="#myModal2_resep" style="display:none;">Standard Modal</button>
<div id="myModal2_resep" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="width:50%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Data Obat</h4>
            </div>
            <div class="modal-body">
            	<form class="form-horizontal" role="form">
		            <div class="form-group">
		                <div class="col-md-12">
			                <div class="input-group">
			                    <input type="text" class="form-control" id="cari_resep" placeholder="Cari..." value="">
			                    <span class="input-group-btn">
			                    	<button type="button" class="btn waves-effect waves-light btn-custom" style="cursor:default;">
			                    		<i class="fa fa-search"></i>
			                    	</button>
			                    </span>
			                </div>
		                </div>
		            </div>
		        </form>
            	<div class="table-responsive">
            		<div class="scroll-y">
		                <table class="table table-bordered table-hover" id="tb_resep">
		                    <thead>
		                        <tr class="hijau_popup">
		                            <th style="text-align:center; color: #fff;" width="50">No</th>
		                            <th style="text-align:center; color: #fff;">Kode</th>
		                            <th style="text-align:center; color: #fff;">Nama Obat</th>
		                        </tr>
		                    </thead>
		                    <tbody>
		                        
		                    </tbody>
		                </table>
            		</div>
            	</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_resep">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<button id="popup_det_resep" class="btn btn-primary" data-toggle="modal" data-target="#myModal3_resep" style="display:none;">Standard Modal</button>
<div id="myModal3_resep" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="width:50%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Detail Resep</h4>
            </div>
            <div class="modal-body">
            	<div class="table-responsive">
            		<div class="scroll-y">
		                <table class="table table-bordered table-hover" id="tb_det_resep">
		                    <thead>
		                        <tr class="hijau_popup">
		                            <th style="text-align:center; color: #fff;" width="50">No</th>
		                            <th style="text-align:center; color: #fff;">Kode Obat</th>
		                            <th style="text-align:center; color: #fff;">Nama Obat</th>
		                            <th style="text-align:center; color: #fff;">Takaran</th>
		                            <th style="text-align:center; color: #fff;">Aturan Minum</th>
		                        </tr>
		                    </thead>
		                    <tbody>
		                        
		                    </tbody>
		                </table>
            		</div>
            	</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_det_resep">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<button id="popup_hapus_resep" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#custom-width-modalResep" style="display:none;">Custom width Modal</button>
<div id="custom-width-modalResep" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="width:55%;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="custom-width-modalLabel">Konfirmasi Hapus</h4>
            </div>
            <div class="modal-body">
                <p id="msg_resep"></p>
            </div>
            <div class="modal-footer">
            	<form action="" method="post" id="form_hapus_resep">
            		<input type="hidden" name="id_hapus_resep" id="id_hapus_resep" value="">
            		<input type="hidden" name="id_pelayanan_resep" value="<?php echo $id; ?>">
	                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal" id="tidak_resep">Tidak</button>
	                <button type="button" class="btn btn-danger waves-effect waves-light" id="ya_resep">Ya</button>
            	</form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- DIAGNOSA -->
<button id="popup_kasus_dg" class="btn btn-primary" data-toggle="modal" data-target="#myModal1_hasil_dg" style="display:none;">Standard Modal</button>
<div id="myModal1_hasil_dg" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="width:50%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Data Kasus</h4>
            </div>
            <div class="modal-body">
            	<form class="form-horizontal" role="form">
		            <div class="form-group">
		                <div class="col-md-12">
			                <div class="input-group">
			                    <input type="text" class="form-control" id="cari_kasus_dg" placeholder="Cari..." value="">
			                    <span class="input-group-btn">
			                    	<button type="button" class="btn waves-effect waves-light btn-custom" style="cursor:default;">
			                    		<i class="fa fa-search"></i>
			                    	</button>
			                    </span>
			                </div>
		                </div>
		            </div>
		        </form>
            	<div class="table-responsive">
            		<div class="scroll-y">
		                <table class="table table-bordered table-hover" id="tb_kasus_dg">
		                    <thead>
		                        <tr class="hijau_popup">
		                            <th style="text-align:center; color: #fff;" width="50">No</th>
		                            <th style="text-align:center; color: #fff;">Kode</th>
		                            <th style="text-align:center; color: #fff;">Nama Kasus</th>
		                        </tr>
		                    </thead>
		                    <tbody>
		                        
		                    </tbody>
		                </table>
            		</div>
            	</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_kasus_dg">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<button id="popup_spesialistik_dg" class="btn btn-primary" data-toggle="modal" data-target="#myModal2_hasil_dg" style="display:none;">Standard Modal</button>
<div id="myModal2_hasil_dg" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="width:50%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Data Spesialistik</h4>
            </div>
            <div class="modal-body">
            	<form class="form-horizontal" role="form">
		            <div class="form-group">
		                <div class="col-md-12">
			                <div class="input-group">
			                    <input type="text" class="form-control" id="cari_spesialistik_dg" placeholder="Cari..." value="">
			                    <span class="input-group-btn">
			                    	<button type="button" class="btn waves-effect waves-light btn-custom" style="cursor:default;">
			                    		<i class="fa fa-search"></i>
			                    	</button>
			                    </span>
			                </div>
		                </div>
		            </div>
		        </form>
            	<div class="table-responsive">
            		<div class="scroll-y">
		                <table class="table table-bordered table-hover" id="tb_spesialistik_dg">
		                    <thead>
		                        <tr class="hijau_popup">
		                            <th style="text-align:center; color: #fff;" width="50">No</th>
		                            <th style="text-align:center; color: #fff;">Kode</th>
		                            <th style="text-align:center; color: #fff;">Spesialistik</th>
		                        </tr>
		                    </thead>
		                    <tbody>
		                        
		                    </tbody>
		                </table>
            		</div>
            	</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_spesialistik_dg">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<button id="popup_hapus_dg" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#custom-width-modalDg" style="display:none;">Custom width Modal</button>
<div id="custom-width-modalDg" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="width:55%;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="custom-width-modalLabel">Konfirmasi Hapus</h4>
            </div>
            <div class="modal-body">
                <p id="msg_dg"></p>
            </div>
            <div class="modal-footer">
            	<form action="" method="post" id="form_hapus_dg">
            		<input type="hidden" name="id_hapus_dg" id="id_hapus_dg" value="">
            		<input type="hidden" name="id_pelayanan_dg" value="<?php echo $id; ?>">
	                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal" id="tidak_dg">Tidak</button>
	                <button type="button" class="btn btn-danger waves-effect waves-light" id="ya_dg">Ya</button>
            	</form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<button class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#myModalICU" id="popup_ruang_icu" style="display:none;">Standard Modal</button>
<div id="myModalICU" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Ruang ICU</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form">
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="input-group">
                                <input type="text" class="form-control" id="cari_ruang_icu" placeholder="Cari..." value="">
                                <span class="input-group-btn">
                                    <button type="button" class="btn waves-effect waves-light btn-warning" style="cursor:default;">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="table-responsive">
                    <div class="scroll-y">
                        <table class="table table-hover table-bordered" id="tabel_ruang_icu">
                            <thead>
                                <tr class="biru_popup">
                                    <th style="text-align:center; color: #fff;" width="50">No</th>
                                    <th style="text-align:center; color: #fff;">Kode Ruang</th>
                                    <th style="text-align:center; color: #fff;">Nama Ruang</th>
                                    <th style="text-align:center; color: #fff;">Level</th>
                                    <th style="text-align:center; color: #fff;">Tipe</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_ruang_icu">Tutup</button>
            </div>
        </div>
    </div>
</div>

<button class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#myModalOpr" id="popup_ruang_operasi" style="display:none;">Standard Modal</button>
<div id="myModalOpr" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Ruang Operasi</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form">
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="input-group">
                                <input type="text" class="form-control" id="cari_ruang_operasi" placeholder="Cari..." value="">
                                <span class="input-group-btn">
                                    <button type="button" class="btn waves-effect waves-light btn-warning" style="cursor:default;">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="table-responsive">
                    <div class="scroll-y">
                        <table class="table table-hover table-bordered" id="tabel_ruang_operasi">
                            <thead>
                                <tr class="biru_popup">
                                    <th style="text-align:center; color: #fff;" width="50">No</th>
                                    <th style="text-align:center; color: #fff;">Kode Ruang</th>
                                    <th style="text-align:center; color: #fff;">Nama Ruang</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_ruang_operasi">Tutup</button>
            </div>
        </div>
    </div>
</div>

<button class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#myModalKJ" id="popup_kamar_jenazah" style="display:none;">Standard Modal</button>
<div id="myModalKJ" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Kamar Jenazah</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form">
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="input-group">
                                <input type="text" class="form-control" id="cari_kamar_jenazah" placeholder="Cari..." value="">
                                <span class="input-group-btn">
                                    <button type="button" class="btn waves-effect waves-light btn-warning" style="cursor:default;">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="table-responsive">
                    <div class="scroll-y">
                        <table class="table table-hover table-bordered" id="tabel_kamar_jenazah">
                            <thead>
                                <tr class="biru_popup">
                                    <th style="text-align:center; color: #fff;" width="50">No</th>
                                    <th style="text-align:center; color: #fff;">Kode Kamar</th>
                                    <th style="text-align:center; color: #fff;">Nama Kamar</th>
                                    <th style="text-align:center; color: #fff;">Tarif</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_kamar_jenazah">Tutup</button>
            </div>
        </div>
    </div>
</div>

<button class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#myModalLJ" id="popup_lemari_jenazah" style="display:none;">Standard Modal</button>
<div id="myModalLJ" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Lemari Jenazah</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form">
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="input-group">
                                <input type="text" class="form-control" id="cari_lemari_jenazah" placeholder="Cari..." value="">
                                <span class="input-group-btn">
                                    <button type="button" class="btn waves-effect waves-light btn-warning" style="cursor:default;">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="table-responsive">
                    <div class="scroll-y">
                        <table class="table table-hover table-bordered" id="tabel_lemari_jenazah">
                            <thead>
                                <tr class="biru_popup">
                                    <th style="text-align:center; color: #fff;" width="50">No</th>
                                    <th style="text-align:center; color: #fff;">Nomor Lemari</th>
                                    <th style="text-align:center; color: #fff;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_lemari_jenazah">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- SURAT DOKTER -->
<button id="popup_hapus_sd" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#custom-width-modalSD" style="display:none;">Custom width Modal</button>
<div id="custom-width-modalSD" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="width:55%;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="custom-width-modalLabel">Konfirmasi Hapus</h4>
            </div>
            <div class="modal-body">
                <p id="msg_surat_dokter"></p>
            </div>
            <div class="modal-footer">
            	<form action="" method="post" id="form_hapus_surat_dokter">
            		<input type="hidden" name="id_hapus_sd" id="id_hapus_sd" value="">
            		<input type="hidden" name="id_pelayanan_sd" value="<?php echo $id; ?>">
	                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal" id="tidak_sd">Tidak</button>
	                <button type="button" class="btn btn-danger waves-effect waves-light" id="ya_sd">Ya</button>
            	</form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
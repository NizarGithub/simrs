<script type="text/javascript" src="<?php echo base_url(); ?>js-devan/jquery-1.11.1.min.js"></script>

<style type="text/css">
#view_tindakan_tambah, #view_tindakan_ubah{
	display: none;
}

#view_laborat_tambah, #view_laborat_ubah{
	display: none;
}

#view_diagnosa_tambah, #view_diagnosa_ubah{
	display: none;
}

#view_resep_tambah, #view_resep_ubah{
	display: none;
}

#pindah_rawat_inap, #view_operasi, #view_icu, #view_meninggal{
	display: none;
}
</style>

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

    $('#btn_kembali').click(function(){
		window.location = "<?php echo base_url(); ?>rekam_medik/rk_pelayanan_igd_c";
	});

	// TINDAKAN

	data_tindakan();

	$('#btn_tambah').click(function(){
		$('#view_tindakan_tambah').show();
		$('#view_tindakan').hide();
		$('#view_tindakan_ubah').hide();
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

	// LABORAT

	$('#dt_laborat').click(function(){
		data_laborat();
	});

	$('#btn_tambah_lab').click(function(){
		$('#view_laborat_tambah').show();
		$('#view_laborat').hide();
		$('#view_laborat_ubah').hide();

		$.ajax({
			url : '<?php echo base_url(); ?>rekam_medik/rk_pelayanan_igd_c/get_kode_lab',
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
		$('#id_laborat').val("");
		$('#jenis_laborat').val("");
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
				url : '<?php echo base_url(); ?>rekam_medik/rk_pelayanan_igd_c/simpan_laborat',
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
			url : '<?php echo base_url(); ?>rekam_medik/rk_pelayanan_igd_c/hapus_laborat',
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

	// DIAGNOSA
	
	$('#dt_diagnosa').click(function(){
		data_diagnosa();
	});

	$('#btn_tambah_dg').click(function(){
		$('#view_diagnosa_tambah').show();
		$('#view_diagnosa').hide();
		$('#view_diagnosa_ubah').hide();
	});

	$('#batalDg').click(function(){
		$('#view_diagnosa_tambah').hide();
		$('#view_diagnosa').show();
		$('#view_diagnosa_ubah').hide();
		$('#id_kasus').val("");
		$('#kasus_dg').val("");
		$('#id_spesialistik').val("");
		$('#spesialistik_dg').val("");
	});

	$('.btn_kasus_dg').click(function(){
		$('#popup_kasus_dg').click();
		load_kasus_diagnosa();
	});

	$('.btn_spesialistik_dg').click(function(){
		$('#popup_spesialistik_dg').click();
		load_spesialistik_diagnosa();
	});

	$('#simpanDg').click(function(){
		var diagnosa = $('#diagnosa').val();
		var tindakan = $('#tindakan_dg').val();
		var kasus = $('#id_kasus').val();
		var spesialistik = $('#id_spesialistik').val();

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
				url : '<?php echo base_url(); ?>rekam_medik/rk_pelayanan_igd_c/simpan_diagnosa',
				data : $('#view_diagnosa_tambah').serialize(),
				type : "POST",
				dataType : "json",
				success : function(result){
					$('#diagnosa').val("");
					$('#tindakan_dg').val("");
					$('#id_kasus').val("");
					$('#id_spesialistik').val("");
					notif_simpan();
					data_diagnosa();
					$('#view_diagnosa').show();
					$('#view_diagnosa_tambah').hide();
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
				url : '<?php echo base_url(); ?>rekam_medik/rk_pelayanan_igd_c/ubah_diagnosa',
				data : $('#view_diagnosa_ubah').serialize(),
				type : "POST",
				dataType : "json",
				success : function(result){
					notif_ubah();
					data_diagnosa();
					$('#view_diagnosa').show();
					$('#view_diagnosa_ubah').hide();
				}
			});
		}
	});

	$('#ya_dg').click(function(){
		var id = $('#id_hapus_dg').val();
		var id_pelayanan = "<?php echo $id; ?>";

		$.ajax({
			url : '<?php echo base_url(); ?>rekam_medik/rk_pelayanan_igd_c/hapus_diagnosa',
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

	//RESEP

	$('#dt_resep').click(function(){
		data_resep();
	});

	$('#btn_tambah_resep').click(function(){
		$('#view_resep_tambah').show();
		$('#view_resep').hide();
		$('#view_resep_ubah').hide();

		$.ajax({
			url : '<?php echo base_url(); ?>rekam_medik/rk_pelayanan_igd_c/get_kode_resep',
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
				url : '<?php echo base_url(); ?>rekam_medik/rk_pelayanan_igd_c/simpan_resep',
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
			url : '<?php echo base_url(); ?>rekam_medik/rk_pelayanan_igd_c/hapus_resep',
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

	// KONDISI AKHIR

	$('#kondisi_akhir').change(function(){
        var kondisi_akhir = $('#kondisi_akhir').val();
        if(kondisi_akhir == 'Rawat Inap'){
        	$('#pindah_rawat_inap').show();
        	$('#view_operasi').hide();
        	$('#view_icu').hide();
        	$('#view_meninggal').hide();
        }else if(kondisi_akhir == 'Operasi'){
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
    });

    $('.btn_ruangan').click(function(){
        $('#popup_ruangan').click();
        load_ruangan();
    });

    $('.btn_bed').click(function(){
        $('#popup_bed').click();
        load_bed();
    });

    $('#simpanKA').click(function(){
		$('#popup_load').show();

		$.ajax({
			url : '<?php echo base_url(); ?>rekam_medik/rk_pelayanan_igd_c/simpan_kondisi',
			data : $('#view_kondisi_akhir').serialize(),
			type : "POST",
			dataType : "json",
			success : function(result){
				notif_simpan();
				$('#popup_load').fadeOut();
				$('#view_kondisi_akhir').find("input[type='text']").val("");
				$('#pindah_rawat_inap').hide();
				$('#view_operasi').hide();
				$('#view_icu').hide();
				$('#view_meninggal').hide();
				$('#lemari_jenazah').html("");
			}
		});
	});

	$('.btn_ruang_opr').click(function(){
		$('#popup_ruang_operasi').click();
		load_ruang_operasi();
	});

	$('.btn_ruang_icu').click(function(){
		$('#popup_ruang_icu').click();
		load_ruang_icu();
	});

	$('.btn_kamar_jenazah').click(function(){ 
		$('#popup_kamar_jenazah').click();
		load_kamar_jenazah();
	});

	$('.btn_lemari_jenazah').click(function(){
		$('#popup_lemari_jenazah').click();
		load_lemari_jenazah();
	});

	$('#batalKA').click(function(){
		$('#pindah_rawat_inap').hide();
		$('#view_operasi').hide();
		$('#pindah_rawat_inap').find("input[type='text']").val("");
		$('#pindah_rawat_inap').find("input[type='hidden']").val("");
		$('#view_operasi').find("input[type='text']").val("");
		$('#view_operasi').find("input[type='hidden']").val("");
	});

});

//TINDAKAN

function load_tindakan(){
	var keyword = $('#cari_tindakan').val();

	$.ajax({
		url : '<?php echo base_url(); ?>rekam_medik/rk_pelayanan_igd_c/load_tindakan',
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
		}
	});
}

function klik_tindakan(id){
	$('#tutup_tindakan').click();
	var id_ubah = $('#id_ubah').val();

	if(id_ubah == ""){
		$.ajax({
			url : '<?php echo base_url(); ?>rekam_medik/rk_pelayanan_igd_c/klik_tindakan',
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
								"<input type='hidden' name='id_tindakan[]' value='"+result[i].ID+"'>"+
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
				hitung_tarif_tindakan();
			}
		});
	}else{
		$.ajax({
			url : '<?php echo base_url(); ?>rekam_medik/rk_pelayanan_rj_c/tindakan_id',
			data : {id:id},
			type : "POST",
			dataType : "json",
			success : function(row){
				$('#id_tindakan_ubah').val(row['ID']);
				$('#tindakan_txt').val(row['NAMA_TINDAKAN']);
				$('#tarif_txt').val(formatNumber(row['TARIF']));
				$('#jumlah_ubah').val("");
				$('#subtotal_ubah').val("");
				$('#jumlah_ubah').focus();
			}
		});
	}
}

function deleteRow(btn){
	var row = btn.parentNode.parentNode;
	row.parentNode.removeChild(row);
	hitung_tarif_tindakan();
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
	hitung_tarif_tindakan();
}

function hitung_tarif_tindakan(){
	var total = 0;
	$("input[name='subtotal[]']").each(function(idx,elm){
		var tarif = elm.value;
		total += parseFloat(tarif);
	});
	$('#tot_tarif_tindakan').val(formatNumber(total));
}

function data_tindakan(){
	$('#popup_load').show();
	var id_pelayanan = "<?php echo $id; ?>";

	$.ajax({
		url : '<?php echo base_url(); ?>rekam_medik/rk_pelayanan_igd_c/data_tindakan',
		data : {id_pelayanan:id_pelayanan},
		type : "POST",
		dataType : "json",
		success : function(result){
			$tr = "";
			var total = 0;

			if(result == "" || result == null){
				$tr = "<tr><td colspan='7' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
			}else{
				var no = 0;

				for(var i=0; i<result.length; i++){
					no++;

					total += parseFloat(result[i].SUBTOTAL);

					var aksi =  '<button type="button" class="btn btn-success waves-effect waves-light btn-sm m-b-5" onclick="ubah_tindakan('+result[i].ID_DET+');">'+
									'<i class="fa fa-pencil"></i>'+
								'</button>&nbsp;'+
						   		'<button type="button" class="btn btn-danger waves-effect waves-light btn-sm m-b-5" onclick="hapus_tindakan('+result[i].ID_DET+');">'+
						   			'<i class="fa fa-trash"></i>'+
						   		'</button>'; 

					var tanggal = formatTanggal(result[i].TANGGAL)+" - "+result[i].WAKTU;

					$tr += "<tr>"+
								"<td style='vertical-align:middle; text-align:center;'>"+no+"</td>"+
								"<td style='vertical-align:middle;'>"+tanggal+"</td>"+
								"<td style='vertical-align:middle;'>"+result[i].NAMA_TINDAKAN+"</td>"+
								"<td style='vertical-align:middle; text-align:right;'>"+formatNumber(result[i].TARIF)+"</td>"+
								"<td style='vertical-align:middle; text-align:center;'>"+formatNumber(result[i].JUMLAH)+"</td>"+
								"<td style='vertical-align:middle; text-align:right;'>"+formatNumber(result[i].SUBTOTAL)+"</td>"+
								"<td align='center'>"+aksi+"</td>"+
							"</tr>";
				}
			}

			$('#tabel_tindakan tbody').html($tr);
			$('#grandtotal_tindakan').html(formatNumber(total));
			$('#popup_load').fadeOut();
		}
	});	
}

function ubah_tindakan(id){
	$('#view_tindakan_ubah').show();
	$('#view_tindakan_tambah').hide();
	$('#view_tindakan').hide();
	var id_pelayanan = "<?php echo $id; ?>";

	$.ajax({
		url : '<?php echo base_url(); ?>rekam_medik/rk_pelayanan_igd_c/data_tindakan_id',
		data : {id:id,id_pelayanan:id_pelayanan},
		type : "POST",
		dataType : "json",
		success : function(row){
			$('#id_ubah').val(id);
			$('#tanggal_ubah').val(formatTanggal(row['TANGGAL']));
			$('#id_tindakan_ubah').val(row['TINDAKAN']);
			$('#tindakan_txt').val(row['NAMA_TINDAKAN']);
			$('#tarif_txt').val(formatNumber(row['TARIF']));
			$('#jumlah_ubah').val(formatNumber(row['JUMLAH']));
			$('#subtotal_ubah').val(formatNumber(row['SUBTOTAL']));
		}
	});

	$('#batal_ubah').click(function(){
		$('#id_ubah').val("");
		$('#view_tindakan_ubah').hide();
		$('#view_tindakan_tambah').hide();
		$('#view_tindakan').show();
	});
}

function hapus_tindakan(id){
	$('#popup_hapus').click();
	var id_pelayanan = "<?php echo $id; ?>";

	$.ajax({
		url : '<?php echo base_url(); ?>rekam_medik/rk_pelayanan_igd_c/data_tindakan_id',
		data : {id:id,id_pelayanan:id_pelayanan},
		type : "POST",
		dataType : "json",
		success : function(row){
			$('#id_hapus').val(id);
			$('#msg').html('Apakah tindakan ini <b>'+row['NAMA_TINDAKAN']+'</b> ingin dihapus?');
		}
	});
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

//LABORAT

function load_laborat(){
	var keyword = $('#cari_laborat').val();

	$.ajax({
		url : '<?php echo base_url(); ?>rekam_medik/rk_pelayanan_igd_c/load_laborat',
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
		url : '<?php echo base_url(); ?>rekam_medik/rk_pelayanan_igd_c/klik_laborat',
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
		url : '<?php echo base_url(); ?>rekam_medik/rk_pelayanan_igd_c/load_pemeriksaan',
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
		url : '<?php echo base_url(); ?>rekam_medik/rk_pelayanan_igd_c/klik_pemeriksaan',
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
							"<td align='center'><input type='text' class='form-control' name='hasil_periksa[]' value='' style='width:200px;'></td>"+
							"<td align='center'><input type='text' class='form-control' name='nilai_rujukan[]' value='' style='width:200px;'></td>"+
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
	var id_pelayanan = "<?php echo $id; ?>";

	$.ajax({
		url : '<?php echo base_url(); ?>rekam_medik/rk_pelayanan_igd_c/data_laborat',
		data : {id_pelayanan:id_pelayanan},
		type : "POST",
		dataType : "json",
		success : function(result){
			var id_pelayanan = "<?php echo $id; ?>";
			$tr = "";
			var total = 0;

			if(result == "" || result == null){
				$tr = "<tr><td colspan='7' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
			}else{
				var no = 0;

				for(var i=0; i<result.length; i++){
					no++;

					total += parseFloat(result[i].TOTAL_TARIF);

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
								"<td align='center'>"+
									"<a href='<?php echo base_url(); ?>rekam_medik/rk_pelayanan_igd_c/cetak_laborat/"+result[i].ID+"/"+id_pelayanan+"' class='btn btn-inverse btn-sm' target='_blank'><i class='fa fa-print'></i></a>"+
								"</td>"+
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
		url : '<?php echo base_url(); ?>rekam_medik/rk_pelayanan_igd_c/data_hasil_pemeriksaan',
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
		url : '<?php echo base_url(); ?>rekam_medik/rk_pelayanan_igd_c/data_laborat_id',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(row){
			$('#id_hapus_lab').val(id);
			$('#msg_lab').html('Apakah data <b>'+row['JENIS_LABORAT']+'</b> ingin dihapus?');
		}
	});
}

//DIAGNOSA

function load_kasus_diagnosa(){
	var keyword = $('#cari_kasus_dg').val();

	$.ajax({
		url : '<?php echo base_url(); ?>rekam_medik/rk_pelayanan_igd_c/data_kasus',
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
		url : '<?php echo base_url(); ?>rekam_medik/rk_pelayanan_igd_c/data_kasus_id',
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

function load_spesialistik_diagnosa(){
	var keyword = $('#cari_spesialistik_dg').val();

	$.ajax({
		url : '<?php echo base_url(); ?>rekam_medik/rk_pelayanan_igd_c/data_spesialistik',
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
		url : '<?php echo base_url(); ?>rekam_medik/rk_pelayanan_igd_c/data_spesialistik_id',
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
		url : '<?php echo base_url(); ?>rekam_medik/rk_pelayanan_igd_c/data_diagnosa',
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

					var aksi =  '<button type="button" class="btn btn-primary waves-effect waves-light btn-sm m-b-5" onclick="ubah_diagnosa('+result[i].ID+');">'+
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
								"<td>"+result[i].NAMA_KASUS+"</td>"+
								"<td>"+result[i].NAMA_SPESIALISTIK+"</td>"+
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
		url : '<?php echo base_url(); ?>rekam_medik/rk_pelayanan_igd_c/data_diagnosa_id',
		data : {id:id,id_pelayanan:id_pelayanan},
		type : "POST",
		dataType : "json",
		success : function(row){
			$('#id_ubah_dg').val(id);
			$('#diagnosa_ubah').val(row['DIAGNOSA']);
			$('#tindakan_dg_ubah').val(row['TINDAKAN']);
			$('#id_kasus_ubah').val(row['ID_KASUS']);
			$('#kasus_dg_ubah').val(row['NAMA_KASUS']);
			$('#id_spesialistik_ubah').val(row['ID_SPESIALISTIK']);
			$('#spesialistik_dg_ubah').val(row['NAMA_SPESIALISTIK']);
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
		url : '<?php echo base_url(); ?>rekam_medik/rk_pelayanan_igd_c/data_diagnosa_id',
		data : {id:id,id_pelayanan:id_pelayanan},
		type : "POST",
		dataType : "json",
		success : function(row){
			$('#id_hapus_dg').val(id);
			$('#msg_dg').html('Apakah data <b>'+row['DIAGNOSA']+'</b> ingin dihapus?');
		}
	});
}

//RESEP

function load_obat(){
	var keyword = $('#cari_resep').val();

	$.ajax({
		url : '<?php echo base_url(); ?>rekam_medik/rk_pelayanan_igd_c/load_obat',
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
		url : '<?php echo base_url(); ?>rekam_medik/rk_pelayanan_igd_c/klik_obat',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(result){
			$tr = "";

			for(var i=0; i<result.length; i++){
				var jumlah_data = $('#tr_resep2_'+result[i].ID).length;

				var aksi = "<button type='button' class='btn waves-light btn-danger btn-sm' onclick='deleteRow(this);'><i class='fa fa-times'></i></button>";

				if(jumlah_data > 0){
					
				}else{
					$tr = "<tr id='tr_resep2_"+result[i].ID+"'>"+
							"<input type='hidden' name='id_obat_resep[]' value='"+result[i].ID+"'>"+
							"<td>"+result[i].KODE_OBAT+"</td>"+
							"<td>"+result[i].NAMA_OBAT+"</td>"+
							"<td align='center'><textarea class='form-control' name='takaran_resep[]' rows='3' style='width:250px;'></textarea></td>"+
							"<td align='center'><textarea class='form-control' name='aturan_minum[]' rows='3' style='width:250px;'></textarea></td>"+
							"<td align='center'>"+aksi+"</td>"+
						  "</tr>";
				}
			}

			$('#tabel_tambah_resep tbody').append($tr);
		}
	});
}

function data_resep(){
	$('#popup_load').show();
	var id_pelayanan = "<?php echo $id; ?>";

	$.ajax({
		url : '<?php echo base_url(); ?>rekam_medik/rk_pelayanan_igd_c/data_resep',
		data : {id_pelayanan:id_pelayanan},
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
		url : '<?php echo base_url(); ?>rekam_medik/rk_pelayanan_igd_c/detail_resep',
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

function hapus_resep(id){
	$('#popup_hapus_resep').click();

	$.ajax({
		url : '<?php echo base_url(); ?>rekam_medik/rk_pelayanan_igd_c/data_resep_id',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(row){
			$('#id_hapus_resep').val(id);
			$('#msg_resep').html('Apakah resep <b>'+row['KODE_RESEP']+'</b> ingin dihapus?')
		}
	});
}

// KONDISI AKHIR

function load_ruangan(){
    var kelas = $('#kelas_kamar').val();
    var keyword = $('#cari_kamar').val();

    $.ajax({
        url : '<?php echo base_url(); ?>rekam_medik/rk_pelayanan_igd_c/load_ruangan',
        data : {kelas:kelas,keyword:keyword},
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

                    $tr += "<tr style='cursor:pointer;' onclick='klik_ruangan("+result[i].ID+");'>"+
                                "<td style='text-align:center;'>"+no+"</td>"+
                                "<td style='text-align:center;'>"+result[i].KODE_KAMAR+"</td>"+
                                "<td>"+result[i].NAMA_KAMAR+"</td>"+
                                "<td style='text-align:center;'>"+result[i].KATEGORI+"</td>"+
                                "<td style='text-align:center;'>"+result[i].KELAS+"</td>"+
                                "<td style='text-align:right;'>"+formatNumber(result[i].BIAYA)+"</td>"+
                            "</tr>";
                }
            }

            $('#tabel_kamar tbody').html($tr);
        }
    });

    $('#cari_kamar').off('keyup').keyup(function(){
        load_ruangan();
    });
}

function klik_ruangan(id){
    $('#tutup_kamar').click();

    $.ajax({
        url : '<?php echo base_url(); ?>rekam_medik/rk_pelayanan_igd_c/klik_ruangan',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(row){
            $('#id_ruangan').val(id);
            var txt = row['KODE_KAMAR']+' - '+row['NAMA_KAMAR'];
            $('#ruang_tujuan').val(txt);
            $('#biaya').val(NumberToMoney(row['BIAYA']));
        }
    });
}

function load_bed(){
    var id_kamar = $('#id_ruangan').val();
    var keyword = $('#cari_bed').val();

    $.ajax({
        url : '<?php echo base_url(); ?>rekam_medik/rk_pelayanan_igd_c/load_bed',
        data : {id_kamar:id_kamar,keyword:keyword},
        type : "POST",
        dataType : "json",
        success : function(result){
            $tr = "";

            if(result == "" || result == null){
                $tr = "<tr><td colspan='3' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
            }else{
                for(var i=0; i<result.length; i++){
                    var stt = "";
                    var warna = "";
                    var disabled = "";
                    var diklik = "";

                    if(result[i].STATUS_PAKAI == 0){
                        stt = '<span class="label label-success">KOSONG</span>';
                        warna = "";
                        disabled = "";
                        diklik = "style='cursor:pointer;' onclick='klik_bed("+result[i].ID+");'";
                    }else{
                        stt = '<span class="label label-danger">TERPAKAI</span>';
                        warna = "terpakai";
                        disabled = "disabled='disabled'";
                        diklik = "";
                    }

                    $tr += "<tr class='"+warna+"' "+disabled+" "+diklik+">"+
                                "<td style='text-align:center;'>"+result[i].NO+"</td>"+
                                "<td>"+result[i].NOMOR_BED+"</td>"+
                                "<td style='text-align:center;'>"+stt+"</td>"+
                            "</tr>";
                }
            }

            $('#tabel_bed tbody').html($tr);
        }
    });

    $('#cari_bed').off('keyup').keyup(function(){
        load_bed();
    });
}

function klik_bed(id){
    $('#tutup_bed').click();

    $.ajax({
        url : '<?php echo base_url(); ?>rekam_medik/rk_pelayanan_igd_c/klik_bed',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(row){
            $('#id_bed').val(id);
            $('#bed').val(row['NOMOR_BED']);
        }
    });
}

function load_ruang_operasi(){
	var keyword = $('#cari_ruang_operasi').val();

	$.ajax({
        url : '<?php echo base_url(); ?>rekam_medik/rk_pelayanan_igd_c/load_ruang_operasi',
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

                    $tr += "<tr style='cursor:pointer;' onclick='klik_ruang_operasi("+result[i].ID+");'>"+
                                "<td style='text-align:center;'>"+no+"</td>"+
                                "<td style='text-align:center;'>"+result[i].KODE+"</td>"+
                                "<td>"+result[i].NAMA_RUANG+"</td>"+
                            "</tr>";
                }
            }

            $('#tabel_ruang_operasi tbody').html($tr);
        }
    });

    $('#cari_ruang_operasi').off('keyup').keyup(function(){
        load_ruang_operasi();
    });
}

function klik_ruang_operasi(id){
	$('#tutup_ruang_operasi').click();

    $.ajax({
        url : '<?php echo base_url(); ?>rekam_medik/rk_pelayanan_igd_c/klik_ruang_operasi',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(row){
            $('#id_ruang_opr').val(id);
            $('#ruang_operasi').val(row['NAMA_RUANG']);
        }
    });
}

function load_ruang_icu(){
	var keyword = $('#cari_ruang_icu').val();

	$.ajax({
        url : '<?php echo base_url(); ?>rekam_medik/rk_pelayanan_igd_c/load_ruang_icu',
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
        url : '<?php echo base_url(); ?>rekam_medik/rk_pelayanan_igd_c/klik_ruang_icu',
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
        url : '<?php echo base_url(); ?>rekam_medik/rk_pelayanan_igd_c/load_kamar_jenazah',
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
        url : '<?php echo base_url(); ?>rekam_medik/rk_pelayanan_igd_c/klik_kamar_jenazah',
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
        url : '<?php echo base_url(); ?>rekam_medik/rk_pelayanan_igd_c/load_lemari_jenazah',
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
        url : '<?php echo base_url(); ?>rekam_medik/rk_pelayanan_igd_c/klik_lemari_jenazah',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(row){
            $('#id_lemari_jenazah').val(id);
            $('#lemari_jenazah').val(row['NOMOR_LEMARI']);
        }
    });
}
</script>

<div id="popup_load">
    <div class="window_load">
        <img src="<?=base_url()?>picture/progress.gif" height="100" width="125">
    </div>
</div>

<div class="col-lg-12">
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
                        	<strong>UMUR :</strong> <span class="m-l-15" style="color:#0066b2;"><?php echo $dt->UMUR; ?> Tahun</span>
                        </p>
                        <p class="text-muted font-13">
                        	<strong>AGAMA :</strong> <span class="m-l-15" style="color:#0066b2;"><?php echo $dt->AGAMA; ?></span>
                        </p>
                    	<p class="text-muted font-13">
                        	<strong>ASAL RUJUKAN :</strong> <span class="m-l-15" style="color:#0066b2;"><?php echo $dt->ASAL_RUJUKAN; ?></span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
	</div>
</div>

<div class="col-lg-12">
	<div class="card-box">
		<div class="row">
			<ul class="nav nav-tabs">
                <li role="presentation" class="active">
                    <a href="#tindakan1" role="tab" data-toggle="tab"><i class="fa fa-stethoscope"></i>&nbsp;Tindakan</a>
                </li>
                <li role="presentation" id="dt_laborat">
                    <a href="#laborat1" role="tab" data-toggle="tab"><i class="fa fa-building"></i>&nbsp;Laboraturium</a>
                </li>
                <li role="presentation" id="dt_diagnosa">
                    <a href="#diagnosa1" role="tab" data-toggle="tab"><i class="fa fa-heartbeat"></i>&nbsp;Diagnosa</a>
                </li>
                <li role="presentation" id="dt_resep">
                    <a href="#resep1" role="tab" data-toggle="tab"><i class="fa fa-medkit"></i>&nbsp;Resep</a>
                </li>
                <li role="presentation" id="dt_kondisi_akhir">
                    <a href="#kondisi_akhir1" role="tab" data-toggle="tab"><i class="fa fa-file-text-o"></i>&nbsp;Kondisi Akhir</a>
                </li>
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
                    	<div class="form-group">
                    		<div class="col-md-8">
                    			&nbsp;
                    		</div>
                    		<div class="col-md-4">
                    			<div class="card-box widget-user" style="background-color:#cee3f8;">
		                            <div>
		                                <img alt="user" class="img-responsive img-circle" src="<?php echo base_url(); ?>picture/rekam_medik/Money_44325.png">
		                                <div class="wid-u-info">
		                                    <small class="text-primary"><b>Grand Total</b></small>
		                                    <h4 class="m-t-0 m-b-5 font-600 text-danger" id="grandtotal_tindakan">0</h4>
		                                </div>
		                            </div>
		                        </div>
                    		</div>
                    	</div>
                    </form>

                    <form class="form-horizontal" id="view_tindakan_tambah" action="<?php echo $url_simpan; ?>" method="post">
						<input type="hidden" name="id_rj" id="id_rj" value="<?php echo $id; ?>">
						<input type="hidden" name="id_pasien" value="<?php echo $dt->ID_PASIEN; ?>">
						<h4><i class="fa fa-plus"></i> Tambah Tindakan</h4>
						<hr>
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
						                        <th style="color:#fff; text-align:center;">Subtotal</th>
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
	                        <label class="col-md-1 control-label">Total Tarif</label>
	                        <div class="col-md-5">
	                        	<input type="text" class="form-control" name="tot_tarif_tindakan" id="tot_tarif_tindakan" value="" readonly>
	                        </div>
	                    </div>
	                    <hr>
	                    <center>
	                    	<button type="submit" class="btn btn-success"><i class="fa fa-save"></i> <b>Simpan</b></button>
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

            	<div role="tabpanel" class="tab-pane fade" id="laborat1">
                	<form class="form-horizontal" id="view_laborat">
                    	<div class="form-group">
                    		<div class="col-md-6">
                    			<h4 class="m-t-0"><i class="fa fa-table"></i>&nbsp;<b>Tabel Laborat</b></h4>
                    		</div>
                    		<div class="col-md-6">
			                    <button class="btn btn-primary m-b-5 pull-right" type="button" id="btn_tambah_lab">
									<i class="fa fa-plus"></i>&nbsp;<b>Tambah Laborat</b>
								</button>
                    		</div>
                    	</div>
                    	<div class="form-group">
                    		<div class="col-md-12">
			                    <div class="table-responsive">
						            <table id="tabel_laborat" class="table table-bordered">
						                <thead>
						                    <tr class="merah">
						                        <th style="color:#fff; text-align:center;">No</th>
						                        <th style="color:#fff; text-align:center;">Tanggal</th>
						                        <th style="color:#fff; text-align:center;">Jenis Laborat</th>
						                        <th style="color:#fff; text-align:center;">Cito</th>
						                        <th style="color:#fff; text-align:center;">Total Tarif</th>
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
                    	<div class="form-group">
                    		<div class="col-md-8">
                    			&nbsp;
                    		</div>
                    		<div class="col-md-4">
                    			<div class="card-box widget-user" style="background-color:#cee3f8;">
		                            <div>
		                                <img alt="user" class="img-responsive img-circle" src="<?php echo base_url(); ?>picture/rekam_medik/Money_44325.png">
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
                    	<input type="hidden" name="id_rj" id="id_rj" value="<?php echo $id; ?>">
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
	                        <div class="col-md-10">
	                            <div class="table-responsive">
						            <table id="tabel_tambah_pemeriksaan" class="table table-bordered">
						                <thead>
						                    <tr class="kuning_tr">
						                        <th style="color:#fff; text-align:center;">Pemeriksaan</th>
						                        <th style="color:#fff; text-align:center;">Hasil</th>
						                        <th style="color:#fff; text-align:center;">Nilai Rujukan</th>
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
						                        <th style="color:#fff; text-align:center;">Kasus</th>
						                        <th style="color:#fff; text-align:center;">Spesialistik</th>
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
                    	<input type="hidden" name="id_rj" id="id_rj" value="<?php echo $id; ?>">
						<input type="hidden" name="id_pasien" value="<?php echo $dt->ID_PASIEN; ?>">
						<h4><i class="fa fa-plus"></i> Tambah Diagnosa</h4>
						<hr>
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
						<div class="form-group">
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
	                    </div>
	                    <div class="form-group">
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
	                    </div>
	                    <hr>
	                    <center>
	                    	<button type="button" class="btn btn-success" id="simpanDg"><i class="fa fa-save"></i> <b>Simpan</b></button>
	                        <button type="button" class="btn btn-danger" id="batalDg"><i class="fa fa-times"></i> <b>Batal</b></button>
	                    </center>
                    </form>

                    <form class="form-horizontal" id="view_diagnosa_ubah" action="" method="post">
                    	<input type="hidden" name="id_ubah_dg" id="id_ubah_dg" value="">
                    	<input type="hidden" name="id_rj" id="id_rj" value="<?php echo $id; ?>">
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
						<div class="form-group">
	                        <label class="col-md-2 control-label">Kasus</label>
	                        <div class="col-md-5">
	                        	<div class="input-group">
	                        		<input type="hidden" name="id_kasus_ubah" id="id_kasus_ubah" value="">
	                                <input type="text" class="form-control" id="kasus_dg_ubah" value="" readonly>
	                                <span class="input-group-btn">
	                                    <button type="button" class="btn btn-primary btn_kasus_dg" style="cursor:cursor;"><i class="fa fa-search"></i></button>
	                                </span>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-md-2 control-label">Spesialistik</label>
	                        <div class="col-md-5">
	                            <div class="input-group">
	                            	<input type="hidden" name="id_spesialistik_ubah" id="id_spesialistik_ubah" value="">
	                                <input type="text" class="form-control" id="spesialistik_dg_ubah" name="spesialistik_dg_ubah" value="" readonly="readonly" required="required">
	                                <span class="input-group-btn">
	                                    <button type="button" class="btn btn-inverse btn_spesialistik_dg"><i class="fa fa-search"></i></button>
	                                </span>
	                            </div>
	                        </div>
	                    </div>
	                    <hr>
	                    <center>
	                    	<button type="button" class="btn btn-success" id="simpanDgUbah"><i class="fa fa-save"></i> <b>Simpan</b></button>
	                        <button type="button" class="btn btn-danger" id="batalDgUbah"><i class="fa fa-times"></i> <b>Batal</b></button>
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
						                        <th style="color:#fff; text-align:center;">Takaran</th>
						                        <th style="color:#fff; text-align:center;">Aturan Minum</th>
						                        <th style="color:#fff; text-align:center;">#</th>
						                    </tr>
						                </thead>

						                <tbody>
						                    
						                </tbody>
						            </table>
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
                	<form class="form-horizontal" id="view_kondisi_akhir">
                		<input type="hidden" name="id_rj" id="id_rj" value="<?php echo $id; ?>">
						<input type="hidden" name="id_pasien" value="<?php echo $dt->ID_PASIEN; ?>">
						<input type="hidden" name="asal_rujukan" value="<?php echo $dt->ASAL_RUJUKAN; ?>">
                		<div class="form-group">
                            <label class="col-sm-2 control-label">Status</label>
                            <div class="col-sm-4">
                                <select class="form-control" name="kondisi_akhir" id="kondisi_akhir">
                                    <option value="Pulang">Pulang</option>
                                    <option value="Atas Permintaan Sendiri">Atas Permintaan Sendiri</option>
                                    <option value="Dirujuk">Dirujuk</option>
                                    <option value="Pindah Poli">Pindah Poli</option>
                                    <option value="Rawat Inap">Rawat Inap</option>
                                    <option value="ICU">ICU</option>
                                    <option value="Operasi">Operasi</option>
                                    <option value="Meninggal">Meninggal</option>
                                </select>
                            </div>
                        </div>

                        <hr>

                        <div id="pindah_rawat_inap">
                        	<h4>Pindah Rawat Inap</h4>
                        	<hr>
                        	<div class="form-group">
		                        <label class="col-md-2 control-label">Kelas</label>
		                        <div class="col-md-4">
		                            <select class="form-control select2" name="kelas_kamar" id="kelas_kamar">
		                                <option value="Semua">Semua</option>
		                                <option value="Kelas 1">Kelas 1</option>
		                                <option value="Kelas 2">Kelas 2</option>
		                                <option value="Kelas 3">Kelas 3</option>
		                                <option value="VIP">VIP</option>
		                                <option value="VVIP">VVIP</option>
		                            </select>
		                        </div>
		                    </div>
		                    <div class="form-group">
		                        <label class="col-md-2 control-label">Ruang Tujuan</label>
		                        <div class="col-md-4">
		                            <div class="input-group">
		                                <input type="hidden" name="id_ruangan" id="id_ruangan" value="">
		                                <input type="text" class="form-control" id="ruang_tujuan" value="" readonly>
		                                <span class="input-group-btn">
		                                    <button type="button" class="btn btn-danger btn_ruangan"><i class="fa fa-search"></i></button>
		                                </span>
		                            </div>
		                        </div>
		                    </div>
		                    <div class="form-group">
		                        <label class="col-md-2 control-label">Biaya Kamar</label>
		                        <div class="col-md-4">
		                            <input type="text" class="form-control" name="biaya" id="biaya" value="" readonly>
		                        </div>
		                    </div>
		                    <div class="form-group">
		                        <label class="col-md-2 control-label">Kamar & Bed</label>
		                        <div class="col-md-4">
		                            <div class="input-group">
		                                <input type="hidden" name="id_bed" id="id_bed" value="">
		                                <input type="text" class="form-control" id="bed" value="" readonly>
		                                <span class="input-group-btn">
		                                    <button type="button" class="btn btn-primary btn_bed"><i class="fa fa-search"></i></button>
		                                </span>
		                            </div>
		                        </div>
		                    </div>
		                    <div class="form-group">
		                        <label class="col-md-2 control-label">Nama P. Jawab</label>
		                        <div class="col-md-4">
		                            <input type="text" class="form-control" name="nama_pjawab" id="nama_pjawab" value="" required="required">
		                        </div>
		                    </div>
		                    <div class="form-group">
		                        <label class="col-md-2 control-label">Telepon</label>
		                        <div class="col-md-4">
		                            <input type="text" class="form-control num_only" name="telepon" id="telepon" value="" maxlength="12" required="required">
		                        </div>
		                    </div>
		                    <div class="form-group">
		                        <label class="col-md-2 control-label">Sistem Bayar</label>
		                        <div class="col-md-4">
		                            <select class="form-control select2" name="sistem_bayar">
		                                <option value="Umum">Umum</option>
		                                <option value="BPJS">BPJS Kesehatan</option>
		                                <option value="PJKA">PJKA</option>
		                                <option value="JAMKESDA">JAMKESDA</option>
		                            </select>
		                        </div>
		                    </div>
		                    <hr>
                        </div>

                        <div id="view_icu">
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
                        </div>

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
                        </div>

                        <hr>

	                    <center>
	                    	<button type="button" class="btn btn-success" id="simpanKA"><i class="fa fa-save"></i> <b>Simpan</b></button>
	                        <button type="button" class="btn btn-danger" id="batalKA"><i class="fa fa-times"></i> <b>Batal</b></button>
	                    </center>
                	</form>
                </div>
            </div>
        </div>
        <div class="row">
			<form class="form-horizontal">
				<div class="form-group">&nbsp;</div>
				<div class="form-group">
					<div class="col-md-4">
						<button class="btn btn-purple btn-block m-b-5" type="button" id="btn_kembali">
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

<!-- LABORAT -->
<button class="btn btn-primary" data-toggle="modal" data-target="#myModal1_laborat" id="popup_laborat" style="display:none;">Standard Modal</button>
<div id="myModal1_laborat" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="width:50%;">
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

<button id="popup_hapus_lab" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#custom-width-modal2" style="display:none;">Custom width Modal</button>
<div id="custom-width-modal2" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
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

<!-- KONDISI AKHIR -->
<button class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#myModal2" id="popup_ruangan" style="display:none;">Standard Modal</button>
<div id="myModal2" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Data Kamar</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form">
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="input-group">
                                <input type="text" class="form-control" id="cari_kamar" placeholder="Cari..." value="">
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
                        <table class="table table-hover table-bordered" id="tabel_kamar">
                            <thead>
                                <tr class="merah_popup">
                                    <th style="text-align:center; color: #fff;" width="50">No</th>
                                    <th style="text-align:center; color: #fff;">Kode Kamar</th>
                                    <th style="text-align:center; color: #fff;">Nama Kamar</th>
                                    <th style="text-align:center; color: #fff;">Kategori</th>
                                    <th style="text-align:center; color: #fff;">Kelas</th>
                                    <th style="text-align:center; color: #fff;">Biaya</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_kamar">Tutup</button>
            </div>
        </div>
    </div>
</div>
<!-- // -->

<button class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#myModal3" id="popup_bed" style="display:none;">Standard Modal</button>
<div id="myModal3" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Data Bed Kamar</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form">
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="input-group">
                                <input type="text" class="form-control" id="cari_bed" placeholder="Cari..." value="">
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
                        <table class="table table-hover table-bordered" id="tabel_bed">
                            <thead>
                                <tr class="biru_popup">
                                    <th style="text-align:center; color: #fff;" width="50">No</th>
                                    <th style="text-align:center; color: #fff;">Kode Bed</th>
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
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_bed">Tutup</button>
            </div>
        </div>
    </div>
</div>

<button class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#myModal4" id="popup_ruang_operasi" style="display:none;">Standard Modal</button>
<div id="myModal4" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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

<button class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#myModal5" id="popup_ruang_icu" style="display:none;">Standard Modal</button>
<div id="myModal5" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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

<button class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#myModal6" id="popup_kamar_jenazah" style="display:none;">Standard Modal</button>
<div id="myModal6" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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

<button class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#myModal7" id="popup_lemari_jenazah" style="display:none;">Standard Modal</button>
<div id="myModal7" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
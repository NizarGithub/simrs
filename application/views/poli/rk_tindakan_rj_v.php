<script type="text/javascript" src="<?php echo base_url(); ?>js-devan/jquery-1.11.1.min.js"></script>

<style type="text/css">
#view_tindakan_tambah, #view_tindakan_ubah{
	display: none;
}

#view_diagnosa_tambah, #view_diagnosa_ubah{
	display: none;
}

#view_resep_tambah, #view_resep_ubah, #view_alergi{
	display: none;
}

#view_laborat_tambah, #view_laborat_ubah, .view_lab, #view_pemeriksaan{
	display: none;
}

#pindah_rawat_inap, #view_icu, #view_operasi, #view_meninggal{
	display: none;
}

#form_surat_dokter{
	display: none;
}
</style>

<script type="text/javascript">
var Base64 = {
    _keyStr: "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",

    encode: function(input) {
        var output = "";
        var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
        var i = 0;

        input = Base64._utf8_encode(input);

        while (i < input.length) {

            chr1 = input.charCodeAt(i++);
            chr2 = input.charCodeAt(i++);
            chr3 = input.charCodeAt(i++);

            enc1 = chr1 >> 2;
            enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
            enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
            enc4 = chr3 & 63;

            if (isNaN(chr2)) {
                enc3 = enc4 = 64;
            } else if (isNaN(chr3)) {
                enc4 = 64;
            }

            output = output + this._keyStr.charAt(enc1) + this._keyStr.charAt(enc2) + this._keyStr.charAt(enc3) + this._keyStr.charAt(enc4);

        }

        return output;
    },


    decode: function(input) {
        var output = "";
        var chr1, chr2, chr3;
        var enc1, enc2, enc3, enc4;
        var i = 0;

        input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");

        while (i < input.length) {

            enc1 = this._keyStr.indexOf(input.charAt(i++));
            enc2 = this._keyStr.indexOf(input.charAt(i++));
            enc3 = this._keyStr.indexOf(input.charAt(i++));
            enc4 = this._keyStr.indexOf(input.charAt(i++));

            chr1 = (enc1 << 2) | (enc2 >> 4);
            chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
            chr3 = ((enc3 & 3) << 6) | enc4;

            output = output + String.fromCharCode(chr1);

            if (enc3 != 64) {
                output = output + String.fromCharCode(chr2);
            }
            if (enc4 != 64) {
                output = output + String.fromCharCode(chr3);
            }

        }

        output = Base64._utf8_decode(output);

        return output;

    },

    _utf8_encode: function(string) {
        string = string.replace(/\r\n/g, "\n");
        var utftext = "";

        for (var n = 0; n < string.length; n++) {

            var c = string.charCodeAt(n);

            if (c < 128) {
                utftext += String.fromCharCode(c);
            }
            else if ((c > 127) && (c < 2048)) {
                utftext += String.fromCharCode((c >> 6) | 192);
                utftext += String.fromCharCode((c & 63) | 128);
            }
            else {
                utftext += String.fromCharCode((c >> 12) | 224);
                utftext += String.fromCharCode(((c >> 6) & 63) | 128);
                utftext += String.fromCharCode((c & 63) | 128);
            }

        }

        return utftext;
    },

    _utf8_decode: function(utftext) {
        var string = "";
        var i = 0;
        var c = c1 = c2 = 0;

        while (i < utftext.length) {

            c = utftext.charCodeAt(i);

            if (c < 128) {
                string += String.fromCharCode(c);
                i++;
            }
            else if ((c > 191) && (c < 224)) {
                c2 = utftext.charCodeAt(i + 1);
                string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
                i += 2;
            }
            else {
                c2 = utftext.charCodeAt(i + 1);
                c3 = utftext.charCodeAt(i + 2);
                string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
                i += 3;
            }

        }

        return string;
    }

}

var ajax = '';
var offset = 5;

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

    //TINDAKAN

    $('#dt_tindakan').click(function(){
		data_tindakan();
	});

    $('#simpanTindakan').click(function(){
    	var tr = $('#tabel_tambah_tindakan tbody tr').length;
		if(tr == 0){
			toastr["error"]("Perhatian! Pilih tindakan dahulu", "Notifikasi");
		}else{
	    	$.ajax({
				url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/simpan_tindakan',
				data : $('#view_tindakan_tambah').serialize(),
				type : "POST",
				dataType : "json",
				success : function(res){
					$('#view_tindakan').show();
					$('#view_tindakan_tambah').hide();
					data_tindakan();
					notif_simpan();
				}
			});
		}
				
    });

	$('#btn_kembali').click(function(){
		window.location = "<?php echo base_url(); ?>poli/poli_home_c";
	});

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
		$('#tot_tarif_tindakan').val("");
	});

	$('.btn_tindakan').click(function(){
		$('#popup_tindakan').click();
		load_tindakan();
	});

	//LABORATURIUM

	$('#dt_laborat').click(function(){
		data_laborat();
	});

	$('#btn_tambah_lab').click(function(){
		$('#view_laborat_tambah').show();
		$('#view_laborat').hide();
		$('#view_laborat_ubah').hide();

		$.ajax({
			url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/get_kode_lab',
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

	$('#btn_tambah_pemeriksaan').click(function(){
		$('#view_pemeriksaan').show();
	});

	$('#btn_tutup_pemeriksaan').click(function(){
		$('#view_pemeriksaan').hide();
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
		}else if(total_tarif == "" || total_tarif == '0'){
			toastr["error"]("Hitung total tarif dengan benar!", "Notifikasi");
		}else if(cito == false){
			toastr["error"]("Cito belum dipilih!", "Notifikasi");
		}else{
			$.ajax({
				url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/simpan_pemeriksaan',
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
			url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/hapus_laborat',
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

	$('#checkboxLab').click(function(){
		var cek = $('#checkboxLab').is(':checked');
		if(cek == true){
			$('.view_lab').show();
		}else{
			$('.view_lab').hide();
		}
	});	

	//DIAGNOSA

	data_diagnosa();

	$('#btn_tambah_dg').click(function(){
		$('#view_diagnosa_tambah').show();
		$('#view_diagnosa').hide();
		$('#view_diagnosa_ubah').hide();
	});

	$('#batalDg').click(function(){
		$('#view_diagnosa_tambah').hide();
		$('#view_diagnosa').show();
		$('#view_diagnosa_ubah').hide();
	});

	$('.btn_penyakit_dg').click(function(){
		$('#popup_penyakit_dg').click();
		load_penyakit_diagnosa();
	});

	$('#cari_penyakit_dg').off('keyup').keyup(function(){
		load_penyakit_diagnosa();
	});

	$('#scroll_data_dg').scroll(function(event){
		$('.loading_tabel_dg').show();
		var keyword = $('#cari_penyakit_dg').val();
		offset += 1;

		if(ajax){
			ajax.abort();
		}

		ajax = $.ajax({
			url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/data_penyakit',
			data : {
				keyword:keyword,
				offset:offset
			},
			type : "GET",
			dataType : "json",
			success : function(result){
				$tr = "";

				if(result == "" || result == null){
					$tr = "<tr><td colspan='3' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
				}else{
					var no = 0;

					for(var i=0; i<result.length; i++){
						no++;

						$tr += "<tr style='cursor:pointer;' onclick='klik_penyakit("+result[i].ID+");'>"+
									"<td style='text-align:center;'>"+no+"</td>"+
									"<td>"+result[i].URAIAN+"</td>"+
									"<td>"+result[i].IN_BAHASA+"</td>"+
								"</tr>";
					}
				}

				$('#tb_penyakit_dg tbody').html($tr);
				$('.loading_tabel_dg').hide();
			}
		});
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
				url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/simpan_diagnosa',
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
				url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/ubah_diagnosa',
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
			url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/hapus_diagnosa',
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

	$("input[name='alergi']").click(function(){
		var alergi = $("input[name='alergi']:checked").val();
		if(alergi == 'Ya'){
			$('#view_alergi').show();
		}else{
			$('#view_alergi').hide();
		}
	});	

	$('#btn_tambah_resep').click(function(){
		$('#view_resep_tambah').show();
		$('#view_resep').hide();
		$('#view_resep_ubah').hide();

		$.ajax({
			url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/get_kode_resep',
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
		$('#id_obat').val("");
		$('#diminum_selama').val("");
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
				url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/simpan_resep',
				data : $('#view_resep_tambah').serialize(),
				type : "POST",
				dataType : "json",
				success : function(kode){
					$('#view_resep_tambah').hide();
					$('#view_resep').show();
					$('#view_resep_ubah').hide();
					$('#tabel_tambah_resep tbody tr').remove();
					$('#id_obat').val("");
					$('#diminum_selama').val("");
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
			url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/hapus_resep',
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

	//KONDISI AKHIR

	$('#dt_kondisi_akhir').click(function(){
		var id_pelayanan = $('#id_rj').val();
		var id_poli = $('#id_poli_ka').val();
		var id_pasien = $('#id_pasien_ka').val();

		$.ajax({
			url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/cek_kondisi_akhir',
			data : {
				id_pelayanan:id_pelayanan,
				id_poli:id_poli,
				id_pasien:id_pasien
			},
			type : "POST",
			dataType : "json",
			success : function(row){
				$('#status_pasien').val(row['KONDISI_AKHIR']);
			}
		});
	});

	$('#kondisi_akhir').change(function(){
        var kondisi_akhir = $('#kondisi_akhir').val();
        if(kondisi_akhir == 'Rawat Inap'){
        	// $('#pindah_rawat_inap').show();
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

	$('#simpanKA').click(function(){
		$('#popup_load').show();

		$.ajax({
			url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/simpan_kondisi',
			data : $('#view_kondisi_akhir').serialize(),
			type : "POST",
			dataType : "json",
			success : function(result){
				notif_simpan();
				$('#dt_kondisi_akhir').click();
				$('#popup_load').fadeOut();
				$('#view_kondisi_akhir').find("input[type='text']").val("");

				$('#pindah_rawat_inap').hide();
				$('#view_operasi').hide();

				$('#pindah_rawat_inap').find("input[type='text']").val("");
				$('#view_operasi').find("input[type='text']").val("");

				$('#pindah_rawat_inap').find("input[type='hidden']").val("");
				$('#view_operasi').find("input[type='hidden']").val("");
			}
		});
	});

	$('#batalKA').click(function(){ 
		$('#pindah_rawat_inap').hide();
		$('#view_icu').hide();
		$('#view_operasi').hide();
		$('#view_meninggal').hide();

		$('#pindah_rawat_inap').find("input[type='text']").val("");
		$('#view_icu').find("input[type='text']").val("");
		$('#view_operasi').find("input[type='text']").val("");
		$('#view_meninggal').find("input[type='text']").val("");

		$('#pindah_rawat_inap').find("input[type='hidden']").val("");
		$('#view_icu').find("input[type='hidden']").val("");
		$('#view_operasi').find("input[type='hidden']").val("");
		$('#view_meninggal').find("input[type='hidden']").val("");
	});

	$('.btn_ruangan').click(function(){
        $('#popup_ruangan').click();
        load_ruangan();
    });

    $('.btn_bed').click(function(){
        $('#popup_bed').click();
        load_bed();
    });

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

    //SURAT DOKTER

    $('#dt_surat_dokter').click(function(){
    	var id = $('#id_rj').val();
    	$.ajax({
    		url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/get_surat_dokter_id',
    		data : {id:id},
    		type : "POST",
    		dataType : "json",
    		success : function(row){
    			if(row['WAKTU_ISTIRAHAT'] == null && row['MULAI_TANGGAL'] == null && row['SAMPAI_TANGGAL'] == null){

    			}else{
	    			$('#waktu_sd').val(row['WAKTU_ISTIRAHAT']);
	    			$('#mulai_tgl_sd').val(row['MULAI_TANGGAL']);
	    			$('#sampai_tgl_sd').val(row['SAMPAI_TANGGAL']);
    			}
    		}
    	});
    });

    $('#dt_surat_pengantar_ri').click(function(){
    	get_kode_pengantar_ri();

    	var id_rj = $('#id_rj_surat_pengantar_ri').val();

    	$.ajax({
    		url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/get_diagnosa_by_idrj',
    		data : {id_rj:id_rj},
    		type : "POST",
    		dataType : "json",
    		success : function(row){
    			$('#diagnosa_dx').val(row['DIAGNOSA']);
    		}
    	});
    });

    $('#simpanSD').click(function(){
    	var id_pasien = $('#id_pasien_sd').val();
    	$.ajax({
    		url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/cek_surat_dokter',
    		data : {id_pasien:id_pasien},
    		type : "POST",
    		dataType : "json",
    		success : function(total){
    			// if(total != 0){
    			// 	notif_surat_dokter();
    			// }else{
    			// }
				$.ajax({
		    		url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/simpan_surat_dokter',
		    		data : $('#view_surat_dokter').serialize(),
		    		type : "POST",
		    		dataType : "json",
		    		success : function(result){
		    			var encodedString = Base64.encode(id_pasien);
		    			window.open('<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/surat_dokter/'+encodedString,'_blank');
						$('#waktu_sd').val("");
				    	$('#mulai_tgl_sd').val("");
				    	$('#sampai_tgl_sd').val("");
		    		}
		    	});
    		}
    	});
    });

    $('#batalSD').click(function(){
    	$('#waktu_sd').val("");
    	$('#mulai_tgl_sd').val("");
    	$('#sampai_tgl_sd').val("");
    });

    $('#simpanPRI').click(function(){
    	var id_rj = $('#id_rj_surat_pengantar_ri').val();
    	var tinggi_badan = $('#tinggi_badan').val();
    	var berat_badan = $('#berat_badan').val();
    	var terapi_dx = $('#terapi_dx').val();

    	if(tinggi_badan == ''){
    		toastr["error"]("Tinggi badan kosong!", "Notifikasi");
			$('#tinggi_badan').focus();
    	}else if(berat_badan == ''){
    		toastr["error"]("Berat badan kosong!", "Notifikasi");
			$('#berat_badan').focus();
    	}else if(terapi_dx == ''){
    		toastr["error"]("Terapi kosong!", "Notifikasi");
			$('#terapi_dx').focus();
    	}else{
    		$.ajax({
    			url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/simpan_surat_pengantar_ri',
	    		data : $('#view_surat_pengantar_ri').serialize(),
	    		type : "POST",
	    		dataType : "json",
	    		success : function(result){
	    			var encodedString = Base64.encode(id_rj);
	    			window.open('<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/cetak_surat_pengantar_ri/'+encodedString,'_blank');
					$('#tinggi_badan').val("");
			    	$('#berat_badan').val("");
			    	$('#diagnosa_dx').val("");
			    	$('#terapi_dx').val("");
			    	get_kode_pengantar_ri();
	    		}
    		});
    	}
    });

    $('#batalPRI').click(function(){
    	$('#tinggi_badan').val("");
    	$('#berat_badan').val("");
    	$('#diagnosa_dx').val("");
    	$('#terapi_dx').val("");
    });

    $('#dt_surat_dokter_ri').click(function(){
    	var id_rj = $('#id_rj_surat_ket_ri').val();

    	$.ajax({
    		url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/get_diagnosa_by_idrj',
    		data : {id_rj:id_rj},
    		type : "POST",
    		dataType : "json",
    		success : function(row){
    			$('#id_penyakit_skri').val(row['ID_PENYAKIT']);
    			$('#diagnosa_sd_ri').val(row['PENYAKIT']);
    		}
    	});
    });

    $('#simpanSDRI').click(function(){
    	var id_rj = $('#id_rj_surat_ket_ri').val();
    	var sampai_tgl = $('#sampai_tgl_sd_ri').val();
    	if(sampai_tgl == ""){
    		toastr["error"]("Sampai tanggal tidak boleh kosong!", "Notifikasi");
    		$('#sampai_tgl_sd_ri').focus();
    	}else{
    		$.ajax({
				url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/simpan_surat_keterangan_ri',
				data : $('#view_surat_keterangan_ri').serialize(),
				type : "POST",
				dataType : "json",
				success : function(result){
					var encodedString = Base64.encode(id_rj);
	    			window.open('<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/cetak_surat_keterangan_ri/'+encodedString,'_blank');
				}
			});
    	}
    });

    $('#simpanSKS').click(function(){
    	var id_rj = $('#id_rj_surat_ket_sehat').val();
    	$.ajax({
			url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/simpan_surat_keterangan_sehat',
			data : $('#view_surat_keterangan_sehat').serialize(),
			type : "POST",
			dataType : "json",
			success : function(result){
				var encodedString = Base64.encode(id_rj);
    			window.open('<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/cetak_surat_keterangan_sehat/'+encodedString,'_blank');
			}
		});
    });

    $('#dt_cetak_darurat').click(function(){
    	get_data_cetak_darurat();
    });

});

//TINDAKAN

function load_tindakan(){
	$('.loading_tabel_tdk').show();
	var keyword = $('#cari_tindakan').val();

	if(ajax){
		ajax.abort();
	}

	ajax = $.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/load_tindakan',
		data : {keyword:keyword},
		type : "GET",
		dataType : "json",
		success : function(result){
			$tr = "";

			if(result == "" || result == null){
				$tr = "<tr><td colspan='3' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
			}else{
				var no = 0;

				for(var i=0; i<result.length; i++){
					no++;

					$tr += "<tr style='cursor:pointer;' onclick='klik_tindakan("+result[i].ID+");'>"+
								"<td style='text-align:center;'>"+no+"</td>"+
								"<td>"+result[i].NAMA_TINDAKAN+"</td>"+
								"<td style='text-align:right;'>"+formatNumber(result[i].TARIF)+"</td>"+
							"</tr>";
				}
			}

			$('#tb_tindakan tbody').html($tr);
			$('.loading_tabel_tdk').hide();
		}
	});

	$('#cari_tindakan').off('keyup').keyup(function(){
		load_tindakan();
	});
}

function klik_tindakan(id){
	$('#tutup_tindakan').click();
	var id_ubah = $('#id_ubah').val();

	if(id_ubah == ""){
		$.ajax({
			url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/klik_tindakan',
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
						$tr = "<tr class='trIkiBosTdk' id='tr_"+result[i].ID+"'>"+
								"<input type='hidden' name='id_tindakan[]' value='"+result[i].ID+"'>"+
								"<input type='hidden' id='tarif_"+result[i].ID+"' value='"+result[i].TARIF+"'>"+
								"<input type='hidden' name='subtotal[]' id='subtotal_"+result[i].ID+"' value=''>"+
								"<td style='vertical-align:middle;'>"+result[i].NAMA_TINDAKAN+"</td>"+
								"<td style='vertical-align:middle; text-align:right;'>"+formatNumber(result[i].TARIF)+"</td>"+
								"<td align='center'>"+
									"<div class='col-md-12'>"+
					                    "<input type='text' class='form-control' name='jumlah[]' id='jumlah_"+result[i].ID+"' value='1' onkeyup='FormatCurrency(this); hitung_jumlah("+result[i].ID+");' style='width: 125px;'>"+
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
			url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/tindakan_id',
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
	var id = "<?php echo $id; ?>";

	$.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/data_tindakan',
		data : {id:id},
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

					var aksi =  '<button type="button" class="btn btn-success waves-effect waves-light btn-sm" onclick="ubah_tindakan('+result[i].ID+');">'+
									'<i class="fa fa-pencil"></i>'+
								'</button>&nbsp;'+
						   		'<button type="button" class="btn btn-danger waves-effect waves-light btn-sm" onclick="hapus_tindakan('+result[i].ID+');">'+
						   			'<i class="fa fa-trash"></i>'+
						   		'</button>'; 

					var tanggal = formatTanggal(result[i].TANGGAL)+" - "+result[i].WAKTU;

					$tr += "<tr>"+
								"<td style='vertical-align:middle; text-align:center;'>"+no+"</td>"+
								"<td style='vertical-align:middle; text-align:center;'>"+tanggal+"</td>"+
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

	$.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/data_tindakan_id',
		data : {id:id},
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

	$.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/data_tindakan_id',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(row){
			$('#id_hapus').val(id);
			$('#msg').html('Apakah tindakan ini <b>'+row['NAMA_TINDAKAN']+'</b> ingin dihapus?');
			$('#ket_hapus').val('Tindakan');
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

//DIAGNOSA

function load_penyakit_diagnosa(){
	$('.loading_tabel_dg').show();
	var keyword = $('#cari_penyakit_dg').val();

	if(ajax){
		ajax.abort();
	}

	ajax = $.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/data_penyakit',
		data : {
			keyword:keyword,
			offset:offset
		},
		type : "GET",
		dataType : "json",
		success : function(result){
			$tr = "";

			if(result == "" || result == null){
				$tr = "<tr><td colspan='3' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
			}else{
				var no = 0;

				for(var i=0; i<result.length; i++){
					no++;

					$tr += "<tr style='cursor:pointer;' onclick='klik_penyakit("+result[i].ID+");'>"+
								"<td style='text-align:center;'>"+no+"</td>"+
								"<td>"+result[i].URAIAN+"</td>"+
								"<td>"+result[i].IN_BAHASA+"</td>"+
							"</tr>";
				}
			}

			$('#tb_penyakit_dg tbody').html($tr);
			$('.loading_tabel_dg').hide();
		}
	});
}

function klik_penyakit(id){
	$('#tutup_penyakit_dg').click();
	
	$.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/data_penyakit_id',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(row){
			var id_ubah = $('#id_ubah_dg').val();
			if(id_ubah == ""){
				$('#id_penyakit').val(id);
				$('#nama_penyakit').val(row['URAIAN']);
				$('#id_penyakit_ubah').val("");
				$('#nama_penyakit_ubah').val("");
			}else{
				$('#id_penyakit').val("");
				$('#nama_penyakit').val("");
				$('#id_penyakit_ubah').val(id);
				$('#nama_penyakit_ubah').val(row['URAIAN']);
			}
		}
	});
}

function data_diagnosa(){
	$('#popup_load').show();
	var id = "<?php echo $id; ?>";

	$.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/data_diagnosa',
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

					var aksi =  '<button type="button" class="btn btn-primary waves-effect waves-light btn-sm" onclick="ubah_diagnosa('+result[i].ID+');">'+
									'<i class="fa fa-pencil"></i>'+
								'</button>&nbsp;'+
						   		'<button type="button" class="btn btn-danger waves-effect waves-light btn-sm" onclick="hapus_diagnosa('+result[i].ID+');">'+
						   			'<i class="fa fa-trash"></i>'+
						   		'</button>';

					$tr += "<tr>"+
								"<td style='vertical-align:middle; text-align:center;'>"+no+"</td>"+
								"<td style='vertical-align:middle; text-align:center;'>"+formatTanggal(result[i].TANGGAL)+"</td>"+
								"<td style='vertical-align:middle;'>"+result[i].DIAGNOSA+"</td>"+
								"<td style='vertical-align:middle;'>"+result[i].URAIAN+"</td>"+
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
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/data_diagnosa_id',
		data : {id:id,id_pelayanan:id_pelayanan},
		type : "POST",
		dataType : "json",
		success : function(row){
			$('#id_ubah_dg').val(id);
			$('#diagnosa_ubah').val(row['DIAGNOSA']);
			$('#id_penyakit_ubah').val(row['ID_PENYAKIT']);
			$('#nama_penyakit_ubah').val(row['URAIAN']);
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
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/data_diagnosa_id',
		data : {id:id,id_pelayanan:id_pelayanan},
		type : "POST",
		dataType : "json",
		success : function(row){
			$('#id_hapus_dg').val(id);
			$('#msg_dg').html('Apakah data <b>'+row['DIAGNOSA']+'</b> ingin dihapus?');
		}
	});
}

//LABORATURIUM

function load_laborat(){
	$('.loading_tabel_lab').show();
	var keyword = $('#cari_laborat').val();

	if(ajax){
		ajax.abort();
	}

	ajax = $.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/load_laborat',
		data : {keyword:keyword},
		type : "GET",
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
			$('.loading_tabel_lab').hide();
		}
	});

	$('#cari_laborat').off('keyup').keyup(function(){
		load_laborat();
	});
}

function klik_laborat(id){
	$('#tutup_laborat').click();

	$.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/klik_laborat',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(row){
			$('#id_laborat').val(id);
			$('#jenis_laborat').val(row['JENIS_LABORAT']);
			klik_pemeriksaan(id);
		}
	});
}

function klik_pemeriksaan(id){
	$('#tutup_pemeriksaan').click();

	$.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/klik_pemeriksaan',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(result){
			$tr = "";

			for(var i=0; i<result.length; i++){
				var jumlah_data = $('#tr2_'+result[i].ID).length;

				var aksi = "<button type='button' class='btn waves-light btn-danger btn-sm' onclick='deleteRow2(this);'><i class='fa fa-times'></i></button>";

				$tr += "<tr id='tr2_"+result[i].ID+"'>"+
							"<input type='hidden' name='id_pemeriksaan[]' value='"+result[i].ID+"'>"+
							"<input type='hidden' name='nilai_normal[]' value='"+result[i].NILAI_NORMAL+"'>"+
							"<input type='hidden' name='tarif_pemeriksaan[]' value='"+result[i].TARIF+"'>"+
							"<td style='vertical-align:middle;'>"+result[i].NAMA_PEMERIKSAAN+"</td>"+
							"<td style='vertical-align:middle;'>"+result[i].NILAI_NORMAL+"</td>"+
							"<td style='vertical-align:middle; text-align:right;'>"+formatNumber(result[i].TARIF)+"</td>"+
							"<td align='center'>"+aksi+"</td>"+
						  "</tr>";
			}

			$('#tabel_tambah_pemeriksaan tbody').html($tr);
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

function load_pemeriksaan(){
	var keyword = $('#cari_pemeriksaan').val();
	var id_jenis_lab = $('#id_laborat').val();

	if(ajax){
		ajax.abort();
	}

	ajax = $.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/load_pemeriksaan',
		data : {
			id_jenis_lab:id_jenis_lab,
			keyword:keyword
		},
		type : "GET",
		dataType : "json",
		success : function(result){
			$tr = "";
			var kosong = "";

			if(result == "" || result == null){
				$tr = "<tr><td colspan='3' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
			}else{
				var no = 0;

				for(var i=0; i<result.length; i++){
					no++;

					$tr += "<tr style='cursor:pointer;' onclick='klik_pemeriksaan_manual("+result[i].ID_NILAI+");'>"+
								"<td style='text-align:center;'>"+no+"</td>"+
								"<td>"+result[i].NAMA_PEMERIKSAAN+"</td>"+
								"<td>"+result[i].NILAI_NORMAL+"</td>"+
								"<td style='text-align:right;'>"+formatNumber(result[i].TARIF)+"</td>"+
							"</tr>";
				}
			}

			$('#tb_pemeriksaan tbody').html($tr);
		}
	});

	$('#cari_pemeriksaan').off('keyup').keyup(function(){
		load_pemeriksaan();
	});
}

function klik_pemeriksaan_manual(id_nilai){
	$('#tutup_pemeriksaan').click();

    $.ajax({
        url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/klik_pemeriksaan_manual',
        data : {id_nilai:id_nilai},
        type : "POST",
        dataType : "json",
        success : function(result){
            $tr = "";

            for(var i=0; i<result.length; i++){
                var jumlah_data = $('#tr2_'+result[i].ID).length;

                var aksi = "<button type='button' class='btn waves-light btn-danger btn-sm' onclick='deleteRow2(this);'><i class='fa fa-times'></i></button>";

                $tr += "<tr id='tr2_"+result[i].ID+"'>"+
                            "<input type='hidden' name='id_pemeriksaan[]' value='"+result[i].ID+"'>"+
                            "<input type='hidden' name='nilai_normal[]' value='"+result[i].NILAI_NORMAL+"'>"+
                            "<input type='hidden' name='tarif_pemeriksaan[]' value='"+result[i].TARIF+"'>"+
                            "<td style='vertical-align:middle;'>"+result[i].NAMA_PEMERIKSAAN+"</td>"+
                            "<td style='vertical-align:middle;'>"+result[i].NILAI_NORMAL+"</td>"+
                            "<td style='vertical-align:middle; text-align:right;'>"+formatNumber(result[i].TARIF)+"</td>"+
                            "<td align='center'>"+aksi+"</td>"+
                          "</tr>";
            }

            $('#tabel_tambah_pemeriksaan tbody').append($tr);
            hitung_pemeriksaan();
            $('#popup_load').hide();
        }
    });
}

function data_laborat(){
	$('#popup_load').show();
	var id = "<?php echo $id; ?>";

	$.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/data_laborat',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(result){
			var id_pelayanan = "<?php echo $id; ?>";
			$tr = "";
			var total = 0;

			if(result == "" || result == null){
				$tr = "<tr><td colspan='7' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
				$('#checkboxLab').removeAttr('checked');
				$('.view_lab').hide();
			}else{
				$('#checkboxLab').attr('checked','checked');
				$('.view_lab').show();

				var no = 0;

				for(var i=0; i<result.length; i++){
					no++;

					total += parseFloat(result[i].TOTAL_TARIF);

					var cetak = "<a href='<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/cetak_laborat/"+result[i].ID+"/"+id_pelayanan+"' class='btn btn-inverse btn-sm' target='_blank'><i class='fa fa-print'></i></a>";
					var aksi =  '<button type="button" class="btn btn-primary waves-effect waves-light btn-sm" onclick="hasil_laborat('+result[i].ID+');">'+
									'<i class="fa fa-tint"></i>'+
								'</button>&nbsp;'+
						   		'<button type="button" class="btn btn-danger waves-effect waves-light btn-sm" onclick="hapus_laborat('+result[i].ID+');">'+
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
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/data_hasil_pemeriksaan',
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
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/data_laborat_id',
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

function custom_pembulatan(nilai, pembulat){
   	var hasil = (Math.ceil(parseInt(nilai))%parseInt(pembulat) == 0) ? Math.ceil(parseInt(nilai)) : Math.round((parseInt(nilai)+parseInt(pembulat)/2)/parseInt(pembulat))*parseInt(pembulat);
   	return hasil;
}

function load_obat(){
	$('.loading_tabel_rsp').show();
	var keyword = $('#cari_resep').val();

	if(ajax){
		ajax.abort();
	}

	ajax = $.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/load_resep',
		data : {keyword:keyword},
		type : "GET",
		dataType : "json",
		success : function(result){
			$tr = "";

			if(result == "" || result == null){
				$tr = "<tr><td colspan='4' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
			}else{
				var no = 0;

				for(var i=0; i<result.length; i++){
					no++;
					var harga = parseFloat(result[i].HARGA_JUAL) + parseFloat(result[i].SERVICE);

					$tr += "<tr style='cursor:pointer;' onclick='klik_obat("+result[i].ID+");'>"+
								"<td style='text-align:center;'>"+no+"</td>"+
								"<td>"+result[i].NAMA_OBAT+"</td>"+
								"<td style='text-align:right;'>"+formatNumber(harga)+"</td>"+
							"</tr>";
				}
			}

			$('#tb_resep tbody').html($tr);
			$('.loading_tabel_rsp').hide();
		}
	});

	$('#cari_resep').off('keyup').keyup(function(){
		load_obat();
	});
}

function deleteRowObat(btn,id){
	var row = btn.parentNode.parentNode;
	row.parentNode.removeChild(row);
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

function klik_obat(id){
	$('#tutup_resep').click();
	var banyak_resep = $('#banyak_resep').val();
	var jumlah_tr = $('#jumlah_tr_rsp').val();

	// if(parseFloat(jumlah_tr) > parseFloat(banyak_resep)){
	// 	toastr["error"]("Jumlah obat sudah maksimal!", "Notifikasi");
	// }else{
	// }

	ini_obatnya(id);
}

function ini_obatnya(id){
	$.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/klik_resep',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(result){
			$tr = "";

			for(var i=0; i<result.length; i++){
				var aksi = "<button type='button' class='btn waves-light btn-danger btn-sm' onclick='deleteRowObat(this,"+result[i].ID+");'><i class='fa fa-times'></i></button>";
				var jumlah_data = $('#tr_resep2_'+result[i].ID).length;

				if(jumlah_data > 0){

				}else{
					var harga = parseFloat(result[i].HARGA_JUAL) + parseFloat(result[i].SERVICE);

					$tr = 	"<tr id='tr_resep2_"+result[i].ID+"'>"+
								"<input type='hidden' name='id_obat_resep[]' value='"+result[i].ID+"'>"+
								"<input type='hidden' name='harga_obat[]' id='harga_obat_"+result[i].ID+"' value='"+harga+"'>"+
								"<input type='hidden' name='service[]' id='service_"+result[i].ID+"' value='"+result[i].SERVICE+"'>"+
								"<td style='vertical-align:middle; text-align:center;'>"+result[i].KODE_OBAT+"</td>"+
								"<td style='vertical-align:middle;'>"+result[i].NAMA_OBAT+"</td>"+
								"<td style='vertical-align:middle; text-align:right;'>"+formatNumber(harga)+"</td>"+
								"<td align='center'><input type='text' class='form-control' name='jumlah_obat[]' value='' id='jumlah_obat_"+result[i].ID+"' style='width:125px;' onkeyup='FormatCurrency(this); hitung_resep("+result[i].ID+")'></td>"+
								"<td align='center'><input type='text' class='form-control' name='total_obat[]' value='' id='total_obat_"+result[i].ID+"' style='width:125px;' readonly></td>"+
								"<td style='vertical-align:middle; text-align:center;'>"+result[i].ID_JENIS_OBAT+"</td>"+
								"<td align='center'><input type='text' class='form-control' name='aturan_minum[]' value='' style='width:125px;'></td>"+
								"<td align='center'>"+
									"<div class='input-group' style='width:125px;'>"+
										"<input type='text' class='form-control' name='diminum_selama[]' value='' onkeyup='FormatCurrency(this);'>"+
										"<span class='input-group-addon'>Hari</span>"+
									"</div>"+
								"</td>"+
								"<td align='center'>"+aksi+"</td>"+
							"</tr>";
				}

				$('#tabel_tambah_resep tbody').append($tr);
			}

			var jumlah_tr = $('#tabel_tambah_resep tbody tr').length;
			$('#jumlah_tr_rsp').val(jumlah_tr);
		}
	});
}

function data_resep(){
	$('#popup_load').show();
	var id_pelayanan = "<?php echo $id; ?>";

	$.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/data_resep',
		data : {id_pelayanan:id_pelayanan},
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

					var aksi =  '<button type="button" class="btn btn-success waves-effect waves-light btn-sm" onclick="detail_resep('+result[i].ID+');">'+
									'<i class="fa fa-eye"></i>'+
								'</button>&nbsp;'+
						   		'<button type="button" class="btn btn-danger waves-effect waves-light btn-sm" onclick="hapus_resep('+result[i].ID+');">'+
						   			'<i class="fa fa-trash"></i>'+
						   		'</button>';

					var alergi = '';
					if(result[i].ALERGI_OBAT == 'Ya'){
						alergi = '<button type="button" class="btn btn-primary waves-effect waves-light btn-sm" onclick="detail_alergi('+result[i].ID+');">'+
									result[i].ALERGI_OBAT+
								 '</button>&nbsp;';
					}else{
						alergi = result[i].ALERGI_OBAT;
					}

					$tr += "<tr>"+
								"<td style='vertical-align:middle; text-align:center;'>"+no+"</td>"+
								"<td style='vertical-align:middle; text-align:center;'>"+formatTanggal(result[i].TANGGAL)+"</td>"+
								"<td style='vertical-align:middle; text-align:center;'>"+result[i].KODE_RESEP+"</td>"+
								"<td style='vertical-align:middle; text-align:center;'>"+result[i].BANYAKNYA_RESEP+" Bungkus</td>"+
								"<td style='vertical-align:middle; text-align:center;'>"+result[i].ITER+" x</td>"+
								"<td style='vertical-align:middle;' align='center'>"+alergi+"</td>"+
								"<td style='vertical-align:middle; text-align:right;'>"+formatNumber(result[i].TOTAL)+"</td>"+
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
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/detail_resep',
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
					var obat = result[i].NAMA_OBAT+' x '+result[i].JUMLAH_BELI;

					$tr += "<tr>"+
								"<td style='text-align:center;'>"+no+"</td>"+
								"<td>"+obat+"</td>"+
								"<td style='text-align:right;'>"+formatNumber(result[i].SUBTOTAL)+"</td>"+
								"<td style='text-align:center;'>"+result[i].GOLONGAN_OBAT+"</td>"+
								"<td style='text-align:center;'>"+result[i].ATURAN_MINUM+"</td>"+
							"</tr>";
				}
			}

			$('#tb_det_resep tbody').html($tr);
		}
	});
}

function detail_alergi(id){
	$('#popup_alergi').click();

	$.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/data_resep_id',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(row){
			$('#msg_alergi').html(row['URAIAN']);
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

	var tot_service = 0;
	$("input[name='service[]']").each(function(id,elm){
		var t = elm.value;
		t = t.split(',').join('');
		if(t == ""){
			t = '0';
		}
		tot_service += parseFloat(t);
	});

	$('#grandtotal_resep').html(formatNumber(grandtotal));
	$('#grandtotal_resep_txt').val(grandtotal);
	$('#total_biaya_service').val(tot_service);
}

function hapus_resep(id){
	$('#popup_hapus_resep').click();

	$.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/data_resep_id',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(row){
			$('#id_hapus_resep').val(id);
			$('#msg_resep').html('Apakah resep <b>'+row['KODE_RESEP']+'</b> ingin dihapus?');
		}
	});
}

// KONDISI AKHIR

function load_ruangan(){
    var kelas = $('#kelas_kamar').val();
    var keyword = $('#cari_kamar').val();

    $.ajax({
        url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/load_ruangan',
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

                    var delapan = new Date('<?php echo date('d/m/Y'); ?> 08:00:00').toLocaleTimeString();
                    var duabelas = new Date('<?php echo date('d/m/Y'); ?> 11:59:00').toLocaleTimeString();
                    var now = new Date().toLocaleTimeString();

                    var cash = 0;
                    var biaya_kamar = result[i].BIAYA;

                    if((parseInt(now) >= parseInt(delapan)) && (parseInt(now) <= parseInt(duabelas))){
                        cash = (15 * parseFloat(biaya_kamar)) / 100;
                    }else{
                        cash = cash;
                    }

                    $tr += "<tr style='cursor:pointer;' onclick='klik_ruangan("+result[i].ID+");'>"+
                                "<td style='text-align:center;'>"+no+"</td>"+
                                "<td style='text-align:center;'>"+result[i].KODE_KAMAR+"</td>"+
                                "<td style='text-align:center;'>"+result[i].KELAS+"</td>"+
                                "<td style='text-align:right;'>"+formatNumber(biaya_kamar)+"</td>"+
                                "<td style='text-align:center;'>"+result[i].VISITE_DOKTER+"</td>"+
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
        url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/klik_ruangan',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(row){
            $('#id_ruangan').val(id);
            var txt = row['KODE_KAMAR']+' - '+row['VISITE_DOKTER'];
            $('#ruang_tujuan').val(txt);
            $('#biaya').val(NumberToMoney(row['BIAYA']));
        }
    });
}

function load_bed(){
    var id_kamar = $('#id_ruangan').val();
    var keyword = $('#cari_bed').val();

    $.ajax({
        url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/load_bed',
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
        url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/klik_bed',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(row){
            $('#id_bed').val(id);
            $('#bed').val(row['NOMOR_BED']);
        }
    });
}

function load_ruang_icu(){
	var keyword = $('#cari_ruang_icu').val();

	$.ajax({
        url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/load_ruang_icu',
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
        url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/klik_ruang_icu',
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

function load_ruang_operasi(){
	var keyword = $('#cari_ruang_operasi').val();

	$.ajax({
        url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/load_ruang_operasi',
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
        url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/klik_ruang_operasi',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(row){
            $('#id_ruang_opr').val(id);
            $('#ruang_operasi').val(row['NAMA_RUANG']);
        }
    });
}

function load_kamar_jenazah(){
	var keyword = $('#cari_kamar_jenazah').val();

	$.ajax({
        url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/load_kamar_jenazah',
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
        url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/klik_kamar_jenazah',
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
        url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/load_lemari_jenazah',
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
        url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/klik_lemari_jenazah',
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

function get_kode_pengantar_ri(){
	$.ajax({
        url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/get_kode_pengantar_ri',
        type : "POST",
        dataType : "json",
        success : function(kode){
            $('#kode_surat_pengantar').val(kode);
        }
    });
}

function hitung_tanggal(){
	var someDate = new Date();
	var numberOfDaysToAdd = $('#waktu_sd').val();
	if(numberOfDaysToAdd != ''){
		someDate.setDate(someDate.getDate() + parseInt(numberOfDaysToAdd)); 
		//Formatting to dd/mm/yyyy :

		var dd = someDate.getDate();
		var mm = someDate.getMonth() + 1;
		var y = someDate.getFullYear();

		if(dd < 10){
			dd = '0'+dd;
		}

		if(mm < 10){
			mm = '0'+mm;
		}

		var someFormattedDate = dd + '-'+ mm + '-'+ y;
		$('#sampai_tgl_sd').val(someFormattedDate);
	}else{
		$('#sampai_tgl_sd').val("");
	}
}

function hitung_tanggal_kurang_dari(){
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

	var tanggal = $('#sampai_tgl_sd_ri').val();
	var d = tanggal.substr(0,2);
	var m = tanggal.substr(3,2);
	var y = tanggal.substr(6);
	var date1 = "<?php echo date('Y-m-d'); ?>";
	var date2 = y+'-'+m+'-'+d;

	// First we split the values to arrays date1[0] is the year, [1] the month and [2] the day
	date1 = date1.split('-');
	date2 = date2.split('-');

	// Now we convert the array to a Date object, which has several helpful methods
	date1 = new Date(date1[0], date1[1], date1[2]);
	date2 = new Date(date2[0], date2[1], date2[2]);

	// We use the getTime() method and get the unixtime (in milliseconds, but we want seconds, therefore we divide it through 1000)
	date1_unixtime = parseInt(date1.getTime() / 1000);
	date2_unixtime = parseInt(date2.getTime() / 1000);

	if(date2_unixtime < date1_unixtime){
		toastr["error"]("Tanggal tidak boleh kurang!", "Notifikasi");
		$('#simpanSDRI').attr('disabled','disabled');
		$('#sampai_tgl_sd_ri').focus();
	}else{
		$('#simpanSDRI').removeAttr('disabled');
	}
}

function get_data_cetak_darurat(){
	$('#popup_load').show();
	var id_pelayanan = "<?php echo $id; ?>";

	$.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/get_data_cetak_darurat',
		data : {id_pelayanan:id_pelayanan},
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

					$tr += "<tr>"+
								"<td style='vertical-align:middle; text-align:center;'>"+no+"</td>"+
								"<td style='vertical-align:middle; text-align:center;'>"+formatTanggal(result[i].TANGGAL)+"</td>"+
								"<td style='vertical-align:middle; text-align:center;'>"+
									"<button type='button' class='btn btn-primary waves-effect waves-light btn-sm' onclick='cetak_surat_keterangan("+result[i].ID_PASIEN+");'>"+
							   			'<i class="fa fa-print"></i>'+
							   		"</button>"+
								"</td>"+
								"<td align='center'>"+
									"<button type='button' class='btn btn-primary waves-effect waves-light btn-sm' onclick='hapus_resep("+result[i].ID+");'>"+
							   			'<i class="fa fa-print"></i>'+
							   		"</button>"+
								"</td>"+
								"<td align='center'>"+
									"<button type='button' class='btn btn-primary waves-effect waves-light btn-sm' onclick='hapus_resep("+result[i].ID+");'>"+
							   			'<i class="fa fa-print"></i>'+
							   		"</button>"+
								"</td>"+
								"<td align='center'>"+
									"<button type='button' class='btn btn-primary waves-effect waves-light btn-sm' onclick='hapus_resep("+result[i].ID+");'>"+
							   			'<i class="fa fa-print"></i>'+
							   		"</button>"+
								"</td>"+
							"</tr>";
				}
			}

			$('#tabel_cetak_darurat tbody').html($tr);
			$('#popup_load').fadeOut();
		}
	});
}

function cetak_surat_keterangan(id_pasien){
	// var encodedString = atob(id_pasien);
	// console.log(id_pasien);
	window.open('<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/surat_dokter_darurat/'+id_pasien,'_blank');
}
</script>

<div id="popup_load">
    <div class="window_load">
        <img src="<?=base_url()?>picture/progress.gif" height="100" width="125">
    </div>
</div>

<div class="row">
	<div class="col-lg-12">
		<div class="row">
			<div class="col-md-6">
	            <div class="card-box">
	            	<h4><i class="fa fa-user"></i> Rekam Medik Pasien</h4>
	            	<hr/>
	            	<table class="table">
	            		<tbody>
	            			<tr>
	            				<td>NO. RM</td>
	            				<td>:</td>
	            				<td><span style="color:#0066b2;"><?php echo $dt->KODE_PASIEN; ?></span></td>
	            				<td>NAMA</td>
	            				<td>:</td>
	            				<td><span style="color:#0066b2;"><?php echo $dt->NAMA_PASIEN; ?></span></td>
	            			</tr>
	            			<tr>
	            				<?php
		                    		$jk = "";
		                    		if($dt->JENIS_KELAMIN=="L"){$jk="Laki - Laki";}else{$jk="Perempuan";}
		                    	?>
	            				<td>JENIS KELAMIN</td>
	            				<td>:</td>
	            				<td><span style="color:#0066b2;"><?php echo $jk; ?></span></td>
	            				<td>UMUR</td>
	            				<td>:</td>
	            				<td><span style="color:#0066b2;"><?php echo $dt->UMUR; ?> Tahun</span></td>
	            			</tr>
	            			<tr>
	            				<td>ALAMAT</td>
	            				<td>:</td>
	            				<td colspan="4">
	            					<span style="color:#0066b2;">
	            						<?php echo $dt->ALAMAT; ?> Kec. <?php echo $dt->KECAMATAN; ?>
	            					</span>
	            				</td>
	            			</tr>
	            			<tr>
	            				<td colspan="2">&nbsp;</td>
	            				<td colspan="4">
	            					<span style="color:#0066b2;">
		            					Kel. <?php echo $dt->KELURAHAN; ?>
		            					Kec. <?php echo $dt->KOTA; ?>
	            					</span>
	            				</td>
	            			</tr>
	            		</tbody>
	            	</table>
	            </div>
	        </div>
	        <div class="col-md-6">
	            <div class="card-box">
	            	<h4><i class="fa fa-user-md"></i> Dokter</h4>
	            	<hr/>
	            	<table class="table">
	            		<tbody>
		            		<tr>
		            			<td>ASAL RUJUKAN</td>
		            			<td>:</td>
		            			<td><span style="color:#0066b2;"><?php echo $dt->ASAL_RUJUKAN; ?></td>
		            		</tr>
		            		<tr>
		            			<td>PELAYANAN</td>
		            			<td>:</td>
		            			<td><span style="color:#0066b2;"><?php echo $dt->STATUS; ?></td>
		            		</tr>
		            		<tr>
		            			<td>POLI</td>
		            			<td>:</td>
		            			<td><span style="color:#0066b2;"><?php echo $dt->NAMA_POLI; ?></td>
		            		</tr>
		            		<tr>
		            			<td>DOKTER</td>
		            			<td>:</td>
		            			<td><span style="color:#0066b2;"><?php echo $dt->NAMA_DOKTER; ?></td>
		            		</tr>
	            		</tbody>
	            	</table>
	            </div>
	        </div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-lg-12">
		<div class="card-box">
			<div class="row">
				<ul class="nav nav-tabs">
					<li role="presentation" id="dt_diagnosa" class="active">
	                    <a href="#diagnosa1" role="tab" data-toggle="tab"><i class="fa fa-heartbeat"></i>&nbsp;Diagnosa</a>
	                </li>
	                <li role="presentation" id="dt_tindakan">
	                    <a href="#tindakan1" role="tab" data-toggle="tab"><i class="fa fa-stethoscope"></i>&nbsp;Tindakan</a>
	                </li>
	                <li role="presentation" id="dt_laborat">
	                    <a href="#laborat1" role="tab" data-toggle="tab"><i class="fa fa-building"></i>&nbsp;Laborat</a>
	                </li>
	                <li role="presentation" id="dt_resep">
	                    <a href="#resep1" role="tab" data-toggle="tab"><i class="fa fa-medkit"></i>&nbsp;Resep</a>
	                </li>
	                <li role="presentation" id="dt_kondisi_akhir">
	                    <a href="#kondisi_akhir1" role="tab" data-toggle="tab"><i class="fa fa-check-square-o"></i>&nbsp;Kondisi Akhir</a>
	                </li>
	                <li class="dropdown" role="presentation">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="javascript:;" aria-expanded="false">
                            Surat Keterangan <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li id="dt_surat_dokter">
                                <a data-toggle="tab" role="tab" href="#surat_dokter1">Surat Keterangan Dokter</a>
                            </li>
                            <li id="dt_surat_pengantar_ri">
                                <a data-toggle="tab" role="tab" href="#data_surat_pengantar_ri1">Surat Pengantar RI</a>
                            </li>
                            <li id="dt_surat_dokter_ri">
                                <a data-toggle="tab" role="tab" href="#data_surat_dokter_ri1">Surat Keterangan RI</a>
                            </li>
                            <!-- <li id="dt_surat_dokter_istirahat">
                                <a data-toggle="tab" role="tab" href="#data_surat_dokter_istirahat1">Surat Keterangan Istirahat</a>
                            </li> -->
                            <li id="dt_surat_dokter_ket_sehat">
                                <a data-toggle="tab" role="tab" href="#data_surat_ket_sehat">Surat Keterangan Sehat</a>
                            </li>
                        </ul>
                    </li>
                    <li role="presentation" id="dt_cetak_darurat">
	                    <a href="#cetak_darurat1" role="tab" data-toggle="tab"><i class="fa fa-file"></i>&nbsp;Cetak Darurat</a>
	                </li>
	            </ul>
	            <div class="tab-content">
	            	<div role="tabpanel" class="tab-pane fade in active" id="diagnosa1">
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
							                        <th style="color:#fff; text-align:center;">Anamnesa</th>
							                        <th style="color:#fff; text-align:center;">Diagnosa</th>
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
							<input type="hidden" name="id_poli" value="<?php echo $dt->ID_POLI; ?>">
							<input type="hidden" name="id_dokter" value="<?php echo $dt->ID_DOKTER; ?>">
							<input type="hidden" name="id_pasien" value="<?php echo $dt->ID; ?>">
							<h4><i class="fa fa-plus"></i> Tambah Diagnosa</h4>
							<hr>
							<div class="form-group">
								<label class="col-md-2 control-label">Anamnesa</label>
								<div class="col-md-8">
									<textarea class="form-control" rows="5" id="diagnosa" name="diagnosa"></textarea>
								</div>
							</div>
							<div class="form-group">
		                        <label class="col-md-2 control-label">Diagnosa</label>
		                        <div class="col-md-5">
		                        	<div class="input-group">
		                        		<input type="hidden" name="id_penyakit" id="id_penyakit" value="">
		                                <input type="text" class="form-control" id="nama_penyakit" value="" readonly>
		                                <span class="input-group-btn">
		                                    <button type="button" class="btn btn-primary btn_penyakit_dg" style="cursor:cursor;"><i class="fa fa-search"></i></button>
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
							<input type="hidden" name="id_poli" value="<?php echo $dt->ID_POLI; ?>">
							<input type="hidden" name="id_dokter" value="<?php echo $dt->ID_DOKTER; ?>">
							<input type="hidden" name="id_pasien" value="<?php echo $dt->ID; ?>">
							<h4><i class="fa fa-plus"></i> Ubah Diagnosa</h4>
							<hr>
							<div class="form-group">
								<label class="col-md-2 control-label">Anamnesa</label>
								<div class="col-md-8">
									<textarea class="form-control" rows="5" id="diagnosa_ubah" name="diagnosa_ubah"></textarea>
								</div>
							</div>
							<div class="form-group">
		                        <label class="col-md-2 control-label">Diagnosa</label>
		                        <div class="col-md-5">
		                        	<div class="input-group">
		                        		<input type="hidden" name="id_penyakit_ubah" id="id_penyakit_ubah" value="">
		                                <input type="text" class="form-control" id="nama_penyakit_ubah" value="" readonly>
		                                <span class="input-group-btn">
		                                    <button type="button" class="btn btn-primary btn_penyakit_dg" style="cursor:cursor;"><i class="fa fa-search"></i></button>
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

	                <div role="tabpanel" class="tab-pane fade" id="tindakan1">
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
							                        <th style="color:#fff; text-align:center;">Tanggal / Waktu</th>
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
			                                <img alt="user" class="img-responsive img-circle" src="<?php echo base_url(); ?>picture/Money_44325.png">
			                                <div class="wid-u-info">
			                                    <small class="text-primary"><b>Grand Total</b></small>
			                                    <h4 class="m-t-0 m-b-5 font-600 text-danger" id="grandtotal_tindakan">0</h4>
			                                </div>
			                            </div>
			                        </div>
	                    		</div>
	                    	</div>
	                    </form>

						<form class="form-horizontal" id="view_tindakan_tambah" action="" method="post">
							<input type="hidden" name="id_rj" id="id_rj" value="<?php echo $id; ?>">
							<input type="hidden" name="id_poli" value="<?php echo $dt->ID_POLI; ?>">
							<input type="hidden" name="id_dokter" value="<?php echo $dt->ID_DOKTER; ?>">
							<input type="hidden" name="id_pasien" value="<?php echo $dt->ID; ?>">
							<h4><i class="fa fa-plus"></i> Tambah Tindakan</h4>
							<hr>
		                    <!--
		                    <div class="form-group">
		                    	<label class="col-md-1 control-label">Perawat</label>
	                    		<?php
			                	// $perawat = $this->model->data_poli_perawat($dt->ID_POLI);
			                	// $no = 0;
			                	// if($perawat == null || $perawat == ""){
			                		
			                	// }else{
				                // 	foreach ($perawat as $value) {
				                // 		$no++;
			                	?>
			                	<div class="col-md-2">
	                    			<div class="inbox-widget nicescroll">
					                 	<a href="javascript:void(0);" style="cursor:default;">
		                                    <div class="inbox-item">
		                                        <div class="inbox-item-img">
		                                        	<img src="<?php //echo base_url(); ?>picture/nurse-icon.png" class="img-circle">
		                                        </div>
		                                        <p class="inbox-item-author"><?php //echo $value->NAMA_PEGAWAI; ?></p>
		                                        <p class="inbox-item-text"><?php //echo $value->NIP; ?></p>
		                                    </div>
		                                </a> 
		                            </div>
	                    		</div>
			                	<?php
			                	// 	}
			                	// }
				                ?>
		                    </div> 
		                    -->
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
		                        <div class="col-md-6">
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
		                    	<button type="button" class="btn btn-success" id="simpanTindakan"><i class="fa fa-save"></i> <b>Simpan</b></button>
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
		                    	<button type="button" class="btn btn-success" id="simpanTindakan_ubah"><i class="fa fa-save"></i> <b>Simpan</b></button>
		                        <button type="button" class="btn btn-danger" id="batal_ubah"><i class="fa fa-times"></i> <b>Batal</b></button>
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
	                    	<input type="hidden" name="id_rj" id="id_rj" value="<?php echo $id; ?>">
							<input type="hidden" name="id_poli" value="<?php echo $dt->ID_POLI; ?>">
							<input type="hidden" name="id_dokter" value="<?php echo $dt->ID_DOKTER; ?>">
							<input type="hidden" name="id_pasien" value="<?php echo $dt->ID; ?>">
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
		                        <div class="col-md-5">
		                        	<button type="button" class="btn btn-success" id="btn_tambah_pemeriksaan"><i class="fa fa-plus"></i> Tambah Pemeriksaan Manual</button>
		                        </div>
		                    </div>
		                    <div class="form-group" id="view_pemeriksaan">
		                        <label class="col-md-2 control-label">Pemeriksaan</label>
		                        <div class="col-md-5">
		                            <div class="input-group">
		                                <input type="text" class="form-control" value="" readonly="readonly" required="required">
		                                <span class="input-group-btn">
		                                    <button type="button" class="btn btn-inverse btn_pemeriksaan"><i class="fa fa-search"></i></button>
		                                </span>
		                            </div>
		                        </div>
		                        <div class="col-md-5">
		                        	<button type="button" class="btn btn-danger" id="btn_tutup_pemeriksaan"><i class="fa fa-times"></i> Tutup</button>
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
							                        <th style="color:#fff; text-align:center;">Nilai Normal</th>
							                        <th style="color:#fff; text-align:center;">Tarif</th>
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
							                        <th style="color:#fff; text-align:center;">Banyak Resep</th>
							                        <th style="color:#fff; text-align:center;">Iter</th>
							                        <th style="color:#fff; text-align:center;">Alergi Obat</th>
							                        <th style="color:#fff; text-align:center;">Total</th>
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
							<input type="hidden" name="id_poli" value="<?php echo $dt->ID_POLI; ?>">
							<input type="hidden" name="id_dokter" value="<?php echo $dt->ID_DOKTER; ?>">
							<input type="hidden" name="id_pasien" value="<?php echo $dt->ID; ?>">
							<input type="hidden" name="grandtotal_resep" id="grandtotal_resep_txt" value="">
							<input type="hidden" id="jumlah_tr_rsp" value="">
							<h4><i class="fa fa-plus"></i> Tambah Resep</h4>
							<hr>
							<div class="form-group">
								<label class="col-md-2 control-label">Kode Resep</label>
								<div class="col-md-5">
									<input type="text" class="form-control" name="kode_resep" id="kode_resep" value="" readonly>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-2 control-label">Alergi / Obat</label>
								<div class="col-md-5">
									<div class="radio radio-inline radio-success">
		                                <input type="radio" id="inlineRadio1" value="Ya" name="alergi">
		                                <label for="inlineRadio1"> Ya </label>
		                            </div>
		                            <div class="radio radio-inline radio-success">
		                                <input type="radio" id="inlineRadio2" value="Tidak" name="alergi">
		                                <label for="inlineRadio2"> Tidak </label>
		                            </div>
								</div>
							</div>
							<div class="form-group" id="view_alergi">
								<label class="col-md-2 control-label">&nbsp;</label>
								<div class="col-md-5">
									<textarea class="form-control" name="alergi_obat" id="alergi_obat" placeholder="Ketikkan disini..."></textarea>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-2 control-label">Banyaknya Resep</label>
								<div class="col-md-5">
									<div class="input-group">
	                                    <input type="text" class="form-control num_only" name="banyak_resep" id="banyak_resep" value="">
	                                    <span class="input-group-addon">Bungkus</span>
	                                </div>
								</div>
							</div>
							<div class="form-group">
		                        <label class="col-md-2 control-label">Obat</label>
		                        <div class="col-md-5">
		                        	<div class="input-group">
		                        		<input type="hidden" name="id_obat" id="id_obat" value="">
		                                <input type="text" class="form-control btn_obat_resep" id="obat_resep" value="" placeholder="klik disini" readonly>
		                                <span class="input-group-btn">
		                                    <button type="button" class="btn btn-primary btn_obat_resep" style="cursor:cursor;"><i class="fa fa-search"></i></button>
		                                </span>
		                            </div>
		                        </div>
		                    </div>
		                    <hr>
		                    <div class="form-group">
		                    	<div class="col-md-12">
		                    		<div class="table-responsive">
							            <table id="tabel_tambah_resep" class="table table-bordered">
							                <thead>
							                    <tr class="kuning_tr">
							                        <th style="color:#fff; text-align:center;">Kode Obat</th>
							                        <th style="color:#fff; text-align:center;">Nama Obat</th>
							                        <th style="color:#fff; text-align:center;">Harga</th>
							                        <th style="color:#fff; text-align:center;">Jumlah</th>
							                        <th style="color:#fff; text-align:center;">Total</th>
							                        <th style="color:#fff; text-align:center;">Jenis Obat</th>
							                        <th style="color:#fff; text-align:center;">Aturan Minum</th>
							                        <th style="color:#fff; text-align:center;">Diminum Selama</th>
							                        <th style="color:#fff; text-align:center;">#</th>
							                    </tr>
							                </thead>
							                <tbody>
							                    
							                </tbody>
							                <tfoot>
							                	<tr class="info">
							                		<td colspan="8" style="text-align: center;"><b>GRANDTOTAL</b></td>
							                		<td>
							                			<b id="grandtotal_resep">0</b>
							                			<input type="hidden" name="total_biaya_service" id="total_biaya_service" value="">
							                		</td>
							                	</tr>
							                </tfoot>
							            </table>
							        </div>
		                    	</div>
		                    </div>
		                    <div class="form-group">
		                    	<label class="col-md-2 control-label">Iter</label>
		                    	<div class="col-sm-5">
	                                <input type="text" class="form-control num_only" name="iter" id="iter" value="">
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
							<input type="hidden" name="id_poli" id="id_poli_ka" value="<?php echo $dt->ID_POLI; ?>">
							<input type="hidden" name="id_dokter" id="id_dokter_ka" value="<?php echo $dt->ID_DOKTER; ?>">
							<input type="hidden" name="id_pasien" id="id_pasien_ka" value="<?php echo $dt->ID; ?>">
							<input type="hidden" name="asal_rujukan" value="Dari Poli">
	                		<div class="form-group">
	                            <label class="col-sm-2 control-label">Status</label>
	                            <div class="col-sm-4">
	                                <select class="form-control" name="kondisi_akhir" id="kondisi_akhir">
	                                    <option value="Pulang">Pulang</option>
	                                    <option value="Rawat Inap">Rawat Inap</option>
	                                    <option value="Dirujuk">Dirujuk</option>
	                                    <option value="ICU">ICU</option>
	                                    <option value="Operasi">Operasi</option>
	                                    <option value="Meninggal">Meninggal</option>
	                                </select>
	                            </div>
	                        </div>
	                        <div class="form-group">
	                        	<label class="col-sm-2 control-label">&nbsp;</label>
	                        	<div class="col-sm-4">
	                        		<input type="text" name="status_pasien" class="form-control" id="status_pasien" value="" readonly>
	                        	</div>
	                        </div>

	                        <hr>

	                        <div id="pindah_rawat_inap">
	                        	<h4>Kamar Rawat Inap</h4>
	                        	<hr>
	                        	<div class="form-group">
			                        <label class="col-md-2 control-label">Kelas</label>
			                        <div class="col-md-4">
			                            <select class="form-control select2" name="kelas_kamar" id="kelas_kamar">
			                                <option value="SVIP">SVIP</option>
					                        <option value="VIP">VIP</option>
					                        <option value="1A">I A</option>
					                        <option value="1B">I B</option>
					                        <option value="2A">II A</option>
					                        <option value="2B">II B</option>
					                        <option value="3">III</option>
					                        <option value="NEO">Ruang Neo</option>
					                        <option value="Ruang Isolasi">Ruang Isolasi</option>
					                        <option value="UGD">UGD</option>
			                            </select>
			                        </div>
			                    </div>
			                    <div class="form-group">
			                        <label class="col-md-2 control-label">Kamar</label>
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
			                        <label class="col-md-2 control-label">No. Bed</label>
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
			                    <!-- <div class="form-group">
			                        <label class="col-md-2 control-label">Sistem Bayar</label>
			                        <div class="col-md-4">
			                            <select class="form-control select2" name="sistem_bayar">
			                                <option value="Umum">Umum</option>
			                                <option value="BPJS">BPJS Kesehatan</option>
			                                <option value="PJKA">PJKA</option>
			                                <option value="JAMKESDA">JAMKESDA</option>
			                            </select>
			                        </div>
			                    </div> -->
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
			                    <hr>
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
	                	<h4 class="header-title m-t-0 m-b-20">Surat Keterangan Dokter</h4>
	                	<hr>
		                <form class="form-horizontal" method="post" id="view_surat_dokter">
		                	<input type="hidden" name="id_rj" id="id_rj" value="<?php echo $id; ?>">
							<input type="hidden" name="id_poli" value="<?php echo $dt->ID_POLI; ?>">
							<input type="hidden" name="id_dokter" value="<?php echo $dt->ID_DOKTER; ?>">
							<input type="hidden" name="id_pasien" id="id_pasien_sd" value="<?php echo $dt->ID; ?>">
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
		                                <input type="text" class="form-control num_only" name="waktu_sd" id="waktu_sd" value="" required="required" onkeyup="hitung_tanggal();">
		                                <span class="input-group-btn">
		                                	<button class="btn btn-primary" type="button" style="cursor:default;">Hari</button>
		                                </span>
		                            </div>
	                            </div>
	                        </div>
	                        <div class="form-group">
		                        <label class="col-md-2 control-label">Mulai Tanggal</label>
		                        <div class="col-md-4">
		                            <input type="text" class="form-control" name="mulai_tgl_sd" id="mulai_tgl_sd" value="<?php echo date('d-m-Y'); ?>" readonly>
		                        </div>
		                    </div>
		                    <div class="form-group">
		                        <label class="col-md-2 control-label">Sampai Tanggal</label>
		                        <div class="col-md-4">
		                            <input type="text" class="form-control" name="sampai_tgl_sd" id="sampai_tgl_sd" value="" readonly>
		                        </div>
		                    </div>
		                    
		                    <hr>

		                    <center>
		                    	<button type="button" class="btn btn-success" id="simpanSD"><i class="fa fa-save"></i> <b>Simpan & Cetak</b></button>
		                        <button type="button" class="btn btn-danger" id="batalSD"><i class="fa fa-times"></i> <b>Batal</b></button>
		                    </center>
		                </form>
	                </div>

	                <div role="tabpanel" class="tab-pane fade" id="data_surat_pengantar_ri1">
	                	<h4 class="header-title m-t-0 m-b-20">Surat Pengantar Rawat Inap</h4>
	                	<hr>
	                	<form class="form-horizontal" method="post" id="view_surat_pengantar_ri">
		                	<input type="hidden" name="id_rj_surat_pengantar_ri" id="id_rj_surat_pengantar_ri" value="<?php echo $id; ?>">
							<input type="hidden" name="id_poli" value="<?php echo $dt->ID_POLI; ?>">
							<input type="hidden" name="id_dokter" value="<?php echo $dt->ID_DOKTER; ?>">
							<input type="hidden" name="id_pasien" id="id_pasien_sd" value="<?php echo $dt->ID; ?>">
							<div class="form-group">
		                        <label class="col-md-2 control-label">Kode Surat</label>
		                        <div class="col-md-4">
		                            <input type="text" class="form-control num_only" name="kode_surat_pengantar" id="kode_surat_pengantar" value="" readonly>
		                        </div>
		                    </div>
		                    <div class="form-group">
		                        <label class="col-md-2 control-label">Tinggi Badan</label>
		                        <div class="col-md-2">
		                        	<div class="input-group">
		                            	<input type="text" class="form-control num_only" name="tinggi_badan" id="tinggi_badan" value="">
		                            	<span class="input-group-btn">
		                                	<button class="btn btn-primary" type="button" style="cursor:default;">cm</button>
		                                </span>
		                        	</div>
		                        </div>
		                    </div>
		                    <div class="form-group">
		                        <label class="col-md-2 control-label">Berat Badan</label>
		                        <div class="col-md-2">
		                        	<div class="input-group">
		                            	<input type="text" class="form-control num_only" name="berat_badan" id="berat_badan" value="">
		                            	<span class="input-group-btn">
		                                	<button class="btn btn-primary" type="button" style="cursor:default;">kg</button>
		                                </span>
		                        	</div>
		                        </div>
		                    </div>
		                    <div class="form-group">
		                        <label class="col-md-2 control-label">Diagnosa (Dx)</label>
		                        <div class="col-md-4">
		                            <textarea class="form-control" name="diagnosa_dx" id="diagnosa_dx" rows="3"></textarea>
		                        </div>
		                    </div>
		                    <div class="form-group">
		                        <label class="col-md-2 control-label">Terapi (Tx)</label>
		                        <div class="col-md-4">
		                            <textarea class="form-control" name="terapi_dx" id="terapi_dx" rows="3"></textarea>
		                        </div>
		                    </div>
		                    
		                    <hr>

		                    <center>
		                    	<button type="button" class="btn btn-success" id="simpanPRI"><i class="fa fa-save"></i> <b>Simpan & Cetak</b></button>
		                        <button type="button" class="btn btn-danger" id="batalPRI"><i class="fa fa-times"></i> <b>Batal</b></button>
		                    </center>
		                </form>
	                </div>

	                <div role="tabpanel" class="tab-pane fade" id="data_surat_dokter_ri1">
	                	<h4 class="header-title m-t-0 m-b-20">Surat Keterangan Rawat Inap</h4>
	                	<hr>
	                	<form class="form-horizontal" method="post" id="view_surat_keterangan_ri">
		                	<input type="hidden" name="id_rj_surat_ket_ri" id="id_rj_surat_ket_ri" value="<?php echo $id; ?>">
							<input type="hidden" name="id_poli" value="<?php echo $dt->ID_POLI; ?>">
							<input type="hidden" name="id_dokter" value="<?php echo $dt->ID_DOKTER; ?>">
							<input type="hidden" name="id_pasien" id="id_pasien_skri" value="<?php echo $dt->ID; ?>">
							<div class="form-group">
		                        <label class="col-md-2 control-label">Mulai Tanggal</label>
		                        <div class="col-md-4">
		                            <input type="text" class="form-control" name="mulai_tgl_sd_ri" id="mulai_tgl_sd_ri" value="<?php echo date('d-m-Y'); ?>" readonly>
		                        </div>
		                    </div>
		                    <div class="form-group">
		                        <label class="col-md-2 control-label">Sampai Tanggal</label>
		                        <div class="col-md-4">
		                            <input type="text" class="form-control" name="sampai_tgl_sd_ri" id="sampai_tgl_sd_ri" value="" onclick="javascript:NewCssCal('sampai_tgl_sd_ri');" onchange="hitung_tanggal_kurang_dari();">
		                        </div>
		                    </div>
		                    <div class="form-group">
		                        <label class="col-md-2 control-label">Diagnosa</label>
		                        <div class="col-md-4">
		                        	<div class="input-group">
		                        		<input type="hidden" name="id_penyakit_skri" id="id_penyakit_skri" value="">
		                                <input type="text" class="form-control" id="diagnosa_sd_ri" value="" readonly>
		                                <span class="input-group-btn">
		                                    <button type="button" class="btn btn-primary" style="cursor:cursor;"><i class="fa fa-search"></i></button>
		                                </span>
		                            </div>
		                        </div>
		                    </div>
		                    <hr>

		                    <center>
		                    	<button type="button" class="btn btn-success" id="simpanSDRI"><i class="fa fa-save"></i> <b>Simpan & Cetak</b></button>
		                        <button type="button" class="btn btn-danger" id="batalSDRI"><i class="fa fa-times"></i> <b>Batal</b></button>
		                    </center>
		                </form>
	                </div>

	                <div role="tabpanel" class="tab-pane fade" id="data_surat_ket_sehat">
	                	<h4 class="header-title m-t-0 m-b-20">Surat Keterangan Sehat</h4>
	                	<hr>
	                	<form class="form-horizontal" method="post" id="view_surat_keterangan_sehat">
		                	<input type="hidden" name="id_rj_surat_ket_sehat" id="id_rj_surat_ket_sehat" value="<?php echo $id; ?>">
							<input type="hidden" name="id_poli" value="<?php echo $dt->ID_POLI; ?>">
							<input type="hidden" name="id_dokter" value="<?php echo $dt->ID_DOKTER; ?>">
							<input type="hidden" name="id_pasien" id="id_pasien_sks" value="<?php echo $dt->ID; ?>">
							<div class="form-group">
								<label class="col-md-2 control-label">Penglihatan :</label>
								<div class="col-md-5">&nbsp;</div>
							</div>
		                    <div class="form-group">
		                        <label class="col-md-2 control-label">&nbsp;</label>
		                        <div class="col-md-4">
		                            <input type="text" class="form-control" name="pakai_kaca_mata" id="pakai_kaca_mata" value="" placeholder="Ketikkan disini...">
		                            <span class="help-block"><small>a. Pakai Kaca Mata</small></span>
		                        </div>
		                    </div>
		                    <div class="form-group">
		                        <label class="col-md-2 control-label">&nbsp;</label>
		                        <div class="col-md-4">
		                            <input type="text" class="form-control" name="tidak_pakai_kaca_mata" id="tidak_pakai_kaca_mata" value="" placeholder="Ketikkan disini...">
		                            <span class="help-block"><small>b. Tidak Pakai Kaca Mata</small></span>
		                        </div>
		                    </div>
		                    <div class="form-group">
		                        <label class="col-md-2 control-label">&nbsp;</label>
		                        <div class="col-md-4">
		                            <input type="text" class="form-control" name="buta_warna" id="buta_warna" value="" placeholder="Ketikkan disini...">
		                            <span class="help-block"><small>c. Buta Warna</small></span>
		                        </div>
		                    </div>
		                    <div class="form-group">
		                        <label class="col-md-2 control-label">Pendengaran</label>
		                        <div class="col-md-4">
		                        	<input type="text" class="form-control" name="pendengaran" id="pendengaran" value="">
		                        </div>
		                    </div>
		                    <div class="form-group">
		                        <label class="col-md-2 control-label">Tinggi Badan</label>
		                        <div class="col-md-2">
		                        	<div class="input-group">
		                            	<input type="text" class="form-control num_only" name="tinggi_badan_sks" id="tinggi_badan_sks" value="">
		                            	<span class="input-group-btn">
		                                	<button class="btn btn-primary" type="button" style="cursor:default;">cm</button>
		                                </span>
		                        	</div>
		                        </div>
		                    </div>
		                    <div class="form-group">
		                        <label class="col-md-2 control-label">Berat Badan</label>
		                        <div class="col-md-2">
		                        	<div class="input-group">
		                            	<input type="text" class="form-control num_only" name="berat_badan_sks" id="berat_badan_sks" value="">
		                            	<span class="input-group-btn">
		                                	<button class="btn btn-primary" type="button" style="cursor:default;">kg</button>
		                                </span>
		                        	</div>
		                        </div>
		                    </div>
		                    <div class="form-group">
		                        <label class="col-md-2 control-label">Tensi</label>
		                        <div class="col-md-4">
		                        	<input type="text" class="form-control" name="tensi" id="tensi" value="">
		                        </div>
		                    </div>
		                    <div class="form-group">
		                        <label class="col-md-2 control-label">Nadi</label>
		                        <div class="col-md-4">
		                        	<input type="text" class="form-control" name="nadi" id="nadi" value="">
		                        </div>
		                    </div>
		                    <div class="form-group">
								<label class="col-md-2 control-label">Dinyatakan bahwa yang diperiksa :</label>
								<div class="col-md-5">&nbsp;</div>
							</div>
							<div class="form-group">
								<label class="col-md-2 control-label">&nbsp;</label>
								<div class="col-md-10">
									<div class="radio radio-inline radio-success">
		                                <input type="radio" id="inlineRadio1" value="A" name="dinyatakan">
		                                <label for="inlineRadio1"> a. Sehat untuk bekerja </label>
		                            </div>
		                            <div class="radio radio-inline radio-success">
		                                <input type="radio" id="inlineRadio2" value="B" name="dinyatakan">
		                                <label for="inlineRadio2"> b. Tidak sehat untuk bekerja </label>
		                            </div>
		                            <div class="radio radio-inline radio-success">
		                                <input type="radio" id="inlineRadio2" value="C" name="dinyatakan">
		                                <label for="inlineRadio2"> c. Memenuhi syarat hanya untuk pekerjaan tertentu </label>
		                            </div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-2 control-label">Untuk Keperluan</label>
								<div class="col-md-8">
									<textarea class="form-control" rows="5" id="untuk_keperluan" name="untuk_keperluan"></textarea>
								</div>
							</div>

		                    <hr>

		                    <center>
		                    	<button type="button" class="btn btn-success" id="simpanSKS"><i class="fa fa-save"></i> <b>Simpan & Cetak</b></button>
		                        <button type="reset" class="btn btn-danger" id="batalSKS"><i class="fa fa-times"></i> <b>Batal</b></button>
		                    </center>
		                </form>
	                </div>

	                <div role="tabpanel" class="tab-pane fade" id="cetak_darurat1">
	                	<form class="form-horizontal">
	                    	<div class="form-group">
	                    		<div class="col-md-6">
	                    			<h4 class="m-t-0"><i class="fa fa-table"></i>&nbsp;<b>Data Surat Keterangan</b></h4>
	                    		</div>
	                    	</div>
	                    	<div class="form-group">
	                    		<div class="col-md-12">
				                    <div class="table-responsive">
							            <table id="tabel_cetak_darurat" class="table table-bordered">
							                <thead>
							                    <tr class="merah">
							                        <th style="color:#fff; text-align:center;">No</th>
							                        <th style="color:#fff; text-align:center;">Tanggal</th>
							                        <th style="color:#fff; text-align:center;">Surat Keterangan Dokter</th>
							                        <th style="color:#fff; text-align:center;">Surat Pengantar RI</th>
							                        <th style="color:#fff; text-align:center;">Surat Keterangan RI</th>
							                        <th style="color:#fff; text-align:center;">Surat Keterangan Sehat</th>
							                    </tr>
							                </thead>
							                <tbody>
							                    
							                </tbody>
							            </table>
							        </div>
	                    		</div>
	                    	</div>
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
</div>

<!-- TINDAKAN -->
<button class="btn btn-primary" data-toggle="modal" data-target="#myModal1" id="popup_tindakan" style="display:none;">Standard Modal</button>
<div id="myModal1" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="width:40%;">
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
		        <div class="loading_tabel_tdk">
		        	<img src="<?php echo base_url(); ?>picture/processando.gif" style="width: 90px; height: 90px;">
		        </div>
            	<div class="table-responsive">
            		<div class="scroll-y">
		                <table class="table table-hover table-bordered" id="tb_tindakan">
		                    <thead>
		                        <tr class="hijau_popup">
		                            <th style="text-align:center; color: #fff;" width="50">No</th>
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
		        <div class="loading_tabel_lab">
		        	<img src="<?php echo base_url(); ?>picture/processando.gif" style="width: 90px; height: 90px;">
		        </div>
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
                <h4 class="modal-title" id="myModalLabel">Data Pemeriksaan</h4>
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
		                            <th style="text-align:center; color: #fff;">Pemeriksaan</th>
		                            <th style="text-align:center; color: #fff;">Nilai Normal</th>
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
    <div class="modal-dialog">
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
<button id="popup_penyakit_dg" class="btn btn-primary" data-toggle="modal" data-target="#myModal2_hasil_dg" style="display:none;">Standard Modal</button>
<div id="myModal2_hasil_dg" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="width:55%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Master Diagnosa</h4>
            </div>
            <div class="modal-body">
            	<form class="form-horizontal" role="form">
		            <div class="form-group">
		                <div class="col-md-12">
			                <div class="input-group">
			                    <input type="text" class="form-control" id="cari_penyakit_dg" placeholder="Cari..." value="">
			                    <span class="input-group-btn">
			                    	<button type="button" class="btn waves-effect waves-light btn-custom" style="cursor:default;">
			                    		<i class="fa fa-search"></i>
			                    	</button>
			                    </span>
			                </div>
		                </div>
		            </div>
		        </form>
		        <div class="loading_tabel_dg">
		        	<img src="<?php echo base_url(); ?>picture/processando.gif" style="width: 90px; height: 90px;">
		        </div>
            	<div class="table-responsive" style="height: 400px;">
                    <div id="scroll_data_dg" style="overflow-y: scroll; overflow-x: hidden; height: 400px;">
		                <table class="table table-bordered table-hover" id="tb_penyakit_dg">
		                    <thead>
		                        <tr class="hijau_popup">
		                            <th style="text-align:center; color: #fff;" width="50">No</th>
		                            <th style="text-align:center; color: #fff;">Uraian</th>
		                            <th style="text-align:center; color: #fff;">In Bahasa</th>
		                        </tr>
		                    </thead>
		                    <tbody>
		                        
		                    </tbody>
		                </table>
            		</div>
            	</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_penyakit_dg">Tutup</button>
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
    <div class="modal-dialog">
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
		        <div class="loading_tabel_rsp">
		        	<img src="<?php echo base_url(); ?>picture/processando.gif" style="width: 90px; height: 90px;">
		        </div>
            	<div class="table-responsive">
            		<div class="scroll-y">
		                <table class="table table-bordered table-hover" id="tb_resep">
		                    <thead>
		                        <tr class="hijau_popup">
		                            <th style="text-align:center; color: #fff;" width="50">No</th>
		                            <th style="text-align:center; color: #fff;">Nama Obat</th>
		                            <th style="text-align:center; color: #fff;">Harga</th>
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
		                            <th style="text-align:center; color: #fff;">Nama Obat</th>
		                            <th style="text-align:center; color: #fff;">Harga</th>
		                            <th style="text-align:center; color: #fff;">Jenis Obat</th>
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

<button id="popup_alergi" class="btn btn-primary" data-toggle="modal" data-target="#myModal4_resep" style="display:none;">Standard Modal</button>
<div id="myModal4_resep" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Alergi Obat Pasien</h4>
            </div>
            <div class="modal-body">
            	<p id="msg_alergi"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<button id="popup_hapus_resep" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#custom-width-modalResep" style="display:none;">Custom width Modal</button>
<div id="custom-width-modalResep" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
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
                                    <th style="text-align:center; color: #fff;">Nomor Kamar</th>
                                    <th style="text-align:center; color: #fff;">Kelas</th>
                                    <th style="text-align:center; color: #fff;">Biaya</th>
                                    <th style="text-align:center; color: #fff;">Visite Dokter Sp.</th>
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
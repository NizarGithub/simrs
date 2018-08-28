<script type="text/javascript" src="<?php echo base_url(); ?>js-devan/jquery-1.11.1.min.js"></script>

<style type="text/css">
#view_tambah, #view_ubah, #tombol_reset, #view_stok, #view_status_obat, #view_gambar_obat{
	display: none;
}
</style>

<script type="text/javascript">
var ajax = "";
$(document).ready(function(){
    <?php if($this->session->flashdata('sukses')){?>
        notif_simpan();
    <?php }else if($this->session->flashdata('ubah')){?>
        notif_ubah();
    <?php }else if($this->session->flashdata('hapus')){ ?>
        notif_hapus();
    <?php } ?>

    data_obat();

    $("input[name='urutkan']").click(function(){
        var urut = $("input[name='urutkan']:checked").val();

        if(urut == 'Stok'){
            $('#view_stok').show();
        }else{
            $('#view_stok').hide();
            data_obat();
        }
    });

    $('#urutkan_stok').change(function(){
        data_obat();
    });

	$('#btn_tambah').click(function(){
		$('#view_tambah').show();
		$('#view_data').hide();
        $('#ket').val('Tambah');
	});

	$('#batal').click(function(){
		window.location = "<?php echo base_url(); ?>apotek/ap_gudang_obat_c";
	});

    $('.btn_nama_obat').click(function(){
        $('#popup_nama_obat').click();
        get_nama_obat();
    });

	$('#jenis_obat').click(function(){
		$('#popup_jenis').click();
		get_jenis_obat();
	});

	$('.btn_jenis').click(function(){
		$('#popup_jenis').click();
		get_jenis_obat();
	});

	// $('#satuan').click(function(){
	// 	$('#popup_satuan').click();
	// 	get_satuan();
	// });
	//
	// $('.btn_satuan').click(function(){
	// 	$('#popup_satuan').click();
	// 	get_satuan();
	// });

		$('#golongan').click(function(){
			$('#popup_golongan').click();
		});

		$('.btn_golongan').click(function(){
			$('#popup_golongan').click();
		});

		$('#kategori').click(function(){
			$('#popup_kategori').click();
		});

		$('.btn_kategori').click(function(){
			$('#popup_kategori').click();
		});

    $('#jumlah_tampil').change(function(){
        data_obat();
    });

    $("#checkbox2").click(function(){
        var cek = $('#checkbox2').is(":checked");
        if(cek == true){
            $('#view_status_obat').show();
        }else{
            $('#view_status_obat').hide();
        }
    });

    $("#checkbox3").click(function(){
        var cek = $('#checkbox3').is(":checked");
        if(cek == true){
            $('#view_gambar_obat').show();
        }else{
            $('#view_gambar_obat').hide();
        }
    });
});

//NAMA OBAT

function get_nama_obat(){
    var keyword = $('#cari_nama_obat').val();

    if(ajax){
        ajax.abort();
    }

    ajax = $.ajax({
        url : '<?php echo base_url(); ?>apotek/ap_gudang_obat_c/data_nama_obat',
        data : {keyword:keyword},
        type : "GET",
        dataType : "json",
        success : function(result){
            $tr = "";

            if(result == "" || result == null){
                $tr = "<tr><td colspan='5' style='text-align:center;'><b>Data tidak ditemukan</b></td></tr>";
            }else{
                var no = 0;
                for(var i=0; i<result.length; i++){
                    no++;

                    $tr += '<tr style="cursor:pointer;" onclick="klik_nama_obat('+result[i].ID+');">'+
                                '<td style="text-align:center;">'+no+'</td>'+
                                '<td>'+result[i].KODE_OBAT+'</td>'+
                                '<td>'+result[i].BARCODE+'</td>'+
                                '<td>'+result[i].NAMA_OBAT+'</td>'+
                                // '<td>'+result[i].MERK+'</td>'+
                            '</tr>';
                }
            }

            $('#tabel_nama_obat tbody').html($tr);
        }
    });

    $('#cari_nama_obat').off('keyup').keyup(function(){
        get_nama_obat();
    });
}

function klik_nama_obat(id){
    $('#tutup_nama_obat').click();

    $.ajax({
        url : '<?php echo base_url(); ?>apotek/ap_gudang_obat_c/klik_nama_obat',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(row){
            var ket = $('#ket').val();

            if(ket == 'Tambah'){
                $('#id_nama_obat').val(id);
                $('#kode_obat').val(row['KODE_OBAT']);
                $('#nama_obat').val(row['NAMA_OBAT']);
                // $('#id_merk').val(row['ID_MERK']);
                // $('#merk').val(row['MERK']);

                if(row['ID_GUDANG'] != null){
                    $('#id_jenis').val(row['ID_JENIS_OBAT']);
                    $('#jenis_obat').val(row['NAMA_JENIS']);
                    // $('#id_satuan').val(row['ID_SATUAN_OBAT']);
                    // $('#satuan').val(row['NAMA_SATUAN']);
                    $('#tanggal_expired').val(row['KADALUARSA']);
                    $('#jumlah').val(NumberToMoney(row['JUMLAH']));
                    $('#isi').val(NumberToMoney(row['ISI']));
                    $('#total').val(NumberToMoney(row['TOTAL']));
                    $('#jumlah_butir').val(NumberToMoney(row['JUMLAH_BUTIR']));
                    $('#harga_beli').val(NumberToMoney(row['HARGA_BELI']));
                    $('#harga_jual').val(NumberToMoney(row['HARGA_JUAL']));
                }else{
                    $('#id_jenis').val("");
                    $('#jenis_obat').val("");
                    // $('#id_satuan').val("");
                    // $('#satuan').val("");
                    $('#tanggal_expired').val("");
                    $('#jumlah').val("");
                    $('#isi').val("");
                    $('#total').val("");
                    $('#jumlah_butir').val("");
                    $('#harga_beli').val("");
                    $('#harga_jual').val("");
                }
            }else{
                $('#id_nama_obat_ubah').val(id);
                $('#kode_obat_ubah').val(row['KODE_OBAT']);
                $('#nama_obat_ubah').val(row['NAMA_OBAT']);
                // $('#id_merk_ubah').val(row['ID_MERK']);
                // $('#merk_ubah').val(row['MERK']);
            }
        }
    });
}
//------------

//JENIS OBAT

function get_jenis_obat(){
	var keyword = $('#cari_jenis').val();

	if(ajax){
		ajax.abort();
	}

	ajax = $.ajax({
        url : '<?php echo base_url(); ?>apotek/ap_gudang_obat_c/data_jenis_obat',
        data : {keyword:keyword},
        type : "GET",
        dataType : "json",
        success : function(result){
            $tr = "";

            if(result == "" || result == null){
            	$tr = "<tr><td colspan='2' style='text-align:center;'><b>Data tidak ditemukan</b></td></tr>";
            }else{
	            var no = 0;
	            for(var i=0; i<result.length; i++){
	            	no++;

	            	$tr += '<tr style="cursor:pointer;" onclick="klik_jenis('+result[i].ID+');">'+
	            				'<td style="text-align:center;">'+no+'</td>'+
	            				'<td>'+result[i].NAMA_JENIS+'</td>'+
	            			'</tr>';
	            }
            }

            $('#tabel_jenis tbody').html($tr);
        }
    });

    $('#cari_jenis').off('keyup').keyup(function(){
    	get_jenis_obat();
    });
}

function klik_jenis(id_jenis){
	$('#tutup_jenis').click();
    $('#cari_jenis').val("");

	$.ajax({
		url : '<?php echo base_url(); ?>apotek/ap_gudang_obat_c/klik_jenis',
		data : {id_jenis:id_jenis},
		type : "POST",
		dataType : "json",
		success : function(row){
            var ket = $('#ket').val();

            if(ket == 'Tambah'){
    			$('#id_jenis').val(id_jenis);
    			$('#jenis_obat').val(row['NAMA_JENIS']);
            }else{
                $('#id_jenis_ubah').val(id_jenis);
                $('#jenis_obat_ubah').val(row['NAMA_JENIS']);
            }
		}
	});
}

function klik_golongan(no_golongan){
	$('#tutup_golongan').click();
	var ket = $('#ket').val();

	var golongan = {
										1 : 'Alkes',
										2 : 'OKT (Obat Keras Tertentu)',
										3 : 'Injeksi',
										4 : 'Supro (Suprositoria)',
										5 : 'Vaksin',
										6 : 'Cream',
										7 : 'Drop',
										8 : 'HV / OTC',
										9 : 'Susu',
									 	10 : 'Sirup',
										11 : 'Tablet',
										12 : 'Generik'
			           }
	 if(ket == 'Tambah'){
		 	$('#id_golongan').val(no_golongan);
 			$('#golongan').val(golongan[no_golongan]);
 	}else{
			$('#id_golongan_ubah').val(no_golongan);
 			$('#golongan_ubah').val(golongan[no_golongan]);
 	}
}

function klik_kategori(no_kategori){
	$('#tutup_kategori').click();
	var ket = $('#ket').val();

	var kategori = {
										1 : 'Obat Bebas',
										2 : 'Obat Bebas Terbatas',
										3 : 'Obat Keras',
										4 : 'Jamu',
										5 : 'Obat Herbal Terstandar',
										6 : 'Fitofarmaka'
			           }
	 if(ket == 'Tambah'){
		 	$('#id_kategori').val(no_kategori);
 			$('#kategori').val(kategori[no_kategori]);
 	}else{
			$('#id_kategori_ubah').val(no_kategori);
 			$('#kategori_ubah').val(kategori[no_kategori]);
 	}
}
//--------------

// //SATUAN OBAT
//
// function get_satuan(){
// 	var keyword = $('#cari_satuan').val();
//
// 	if(ajax){
// 		ajax.abort();
// 	}
//
// 	ajax = $.ajax({
//         url : '<?php //echo base_url(); ?>apotek/ap_gudang_obat_c/data_satuan',
//         data : {keyword:keyword},
//         type : "GET",
//         dataType : "json",
//         success : function(result){
//             $tr = "";
//
//             if(result == "" || result == null){
//             	$tr = "<tr><td colspan='2' style='text-align:center;'><b>Data tidak ditemukan</b></td></tr>";
//             }else{
// 	            var no = 0;
//
// 	            for(var i=0; i<result.length; i++){
// 	            	no++;
//
// 	            	$tr += '<tr style="cursor:pointer;" onclick="klik_satuan('+result[i].ID+');">'+
// 	            				'<td style="text-align:center;">'+no+'</td>'+
// 	            				'<td style="text-align:center;">'+result[i].NAMA_SATUAN+'</td>'+
// 	            			'</tr>';
// 	            }
//             }
//
//             $('#tabel_satuan tbody').html($tr);
//         }
//     });
//
//     $('#cari_satuan').off('keyup').keyup(function(){
//     	get_satuan();
//     });
// }
//
// function klik_satuan(id_satuan){
// 	$('#tutup_satuan').click();
//
// 	$.ajax({
// 		url : '<?php //echo base_url(); ?>apotek/ap_gudang_obat_c/klik_satuan',
// 		data : {id_satuan:id_satuan},
// 		type : "POST",
// 		dataType : "json",
// 		success : function(row){
//             var ket = $('#ket').val();
//
//             if(ket == 'Tambah'){
//                 $('#id_satuan').val(id_satuan);
//                 $('#satuan').val(row['NAMA_SATUAN']);
//                 $('#ket_satuan').html(row['NAMA_SATUAN']);
//             }else{
//                 $('#id_satuan_ubah').val(id_satuan);
//                 $('#satuan_ubah').val(row['NAMA_SATUAN']);
//             }
// 		}
// 	});
// }
// //-------------

function paging($selector){
	var jumlah_tampil = $('#jumlah_tampil').val();

    if(typeof $selector == 'undefined')
    {
        $selector = $("#tabel_obat tbody tr");
    }

    window.tp = new Pagination('#tablePaging', {
        itemsCount:$selector.length,
        pageSize : parseInt(jumlah_tampil),
        onPageSizeChange: function (ps) {
            console.log('changed to ' + ps);
        },
        onPageChange: function (paging) {
            //custom paging logic here
            //console.log(paging);
            var start = paging.pageSize * (paging.currentPage - 1),
                end = start + paging.pageSize,
                $rows = $selector;

            $rows.hide();

            for (var i = start; i < end; i++) {
                $rows.eq(i).show();
            }
        }
    });
}

function data_obat(){
	$('#popup_load').show();

	var keyword = $('#cari_obat').val();
    var urutkan = $("input[name='urutkan']:checked").val();
    var urutkan_stok = $('#urutkan_stok').val();

	if(ajax){
		ajax.abort();
	}

	ajax = $.ajax({
		url : '<?php echo base_url(); ?>apotek/ap_gudang_obat_c/get_data_obat',
        data : {
            keyword:keyword,
            urutkan:urutkan,
            urutkan_stok:urutkan_stok,
        },
        type : "GET",
        dataType : "json",
        success : function(result){
        	$tr = "";

        	if(result == "" || result == null){
        		$tr = "<tr><td colspan='11' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
        	}else{
        		var no = 0;

        		for(var i=0; i<result.length; i++){
        			no++;

                    var aksi =  '<button type="button" class="btn btn-info waves-effect waves-light btn-sm m-b-5" onclick="detail_obat('+result[i].ID+');">'+
                                    '<i class="fa fa-search"></i>'+
                                '</button>&nbsp;'+
																'<button type="button" class="btn btn-success waves-effect waves-light btn-sm m-b-5" onclick="ubah_obat('+result[i].ID+');">'+
                                    '<i class="fa fa-pencil"></i>'+
                                '</button>&nbsp;'+
                                '<button type="button" class="btn btn-danger waves-effect waves-light btn-sm m-b-5" onclick="hapus_obat('+result[i].ID+');">'+
                                    '<i class="fa fa-trash"></i>'+
                                '</button>';

                    var warna = "";

                    if(result[i].AKTIF == 0){
                        warna = "merah_tr";
                    }else{
                        warna = "";
                    }

                    var satuan = "";

                    if(result[i].JUMLAH_BUTIR != 0){
                        satuan = result[i].SATUAN_ISI;
                    }else{
                        satuan = result[i].NAMA_SATUAN;
                    }

        			$tr += "<tr class='"+warna+"'>"+
        						"<td style='text-align:center;'>"+no+"</td>"+
        						"<td>"+
        							"<b>"+result[i].NAMA_OBAT+"</b><br/>"+
                                    "<small>"+result[i].KODE_OBAT+"</small>"+
        						"</td>"+
                                "<td>"+result[i].NAMA_JENIS+"</td>"+
                                "<td style='text-align:right;'>"+NumberToMoney(result[i].HARGA_BELI)+"</td>"+
        						"<td style='text-align:right;'>"+NumberToMoney(result[i].HARGA_JUAL)+"</td>"+
        						"<td>"+NumberToMoney(result[i].TOTAL)+"&nbsp;"+satuan+"</td>"+
        						"<td>"+formatTanggal(result[i].KADALUARSA)+"</td>"+
                                "<td align='center'>"+aksi+"</td>"+
        					"</tr>";
        		}
        	}

        	$('#tabel_obat tbody').html($tr);
        	paging();
        	$('#popup_load').fadeOut();
        }
	});

    $('#tombol_cari').click(function(){
        data_obat();
        $('#tombol_reset').show();
        $('#tombol_cari').hide();
    });

    $('#tombol_reset').click(function(){
        $('#cari_obat').val("");
        data_obat();
        $('#tombol_reset').hide();
        $('#tombol_cari').show();
    });
}

function onEnterText(e){
    if (e.keyCode == 13) {
        data_obat();
        $('#tombol_reset').show();
		$('#tombol_cari').hide();
        return false;
    }
}

function hitung_total(){
    var ket = $('#ket').val();

    if(ket == 'Tambah'){
        var jumlah = $('#jumlah').val();
        var isi = $('#isi').val();

        jumlah = jumlah.split(',').join('');
        isi = isi.split(',').join('');

        if(jumlah == ""){
            jumlah = 0;
        }

        if(isi == ""){
            isi = 0;
        }

        var total = parseFloat(jumlah) * parseFloat(isi);
        $('#total').val(NumberToMoney(total));
    }else{
        var jumlah = $('#jumlah_ubah').val();
        var isi = $('#isi_ubah').val();

        jumlah = jumlah.split(',').join('');
        isi = isi.split(',').join('');

        if(jumlah == ""){
            jumlah = 0;
        }

        if(isi == ""){
            isi = 0;
        }

        var total = parseFloat(jumlah) * parseFloat(isi);
        $('#total_ubah').val(NumberToMoney(total));
    }
}

function convert_kg_to_ml(){
    var ket = $('#ket').val();
    if(ket == 'Tambah'){
        var jumlah = $('#jumlah').val();
        if(jumlah == ""){
            jumlah = 0;
        }else{
            jumlah = jumlah.split(',').join('');
        }
        var ml = parseFloat(jumlah)*1000;
        $('#jumlah_ml').val(NumberToMoney(ml));
    }else{

    }
}

function ubah_obat(id){
    $('#view_ubah').show();
    $('#view_data').hide();
    $('#ket').val('Ubah');

    $.ajax({
        url : '<?php echo base_url(); ?>apotek/ap_gudang_obat_c/data_obat_id',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(row){
            $('#id_ubah').val(id);
            $('#id_nama_obat_ubah').val(row['ID_SETUP_NAMA_OBAT']);
            $('#kode_obat_ubah').val(row['KODE_OBAT']);
            $('#nama_obat_ubah').val(row['NAMA_OBAT']);
            // $('#id_merk_ubah').val(row['ID_MERK']);
            // $('#merk_ubah').val(row['MERK']);
            $('#id_jenis_ubah').val(row['ID_JENIS_OBAT']);
            $('#jenis_obat_ubah').val(row['NAMA_JENIS']);
            // $('#id_satuan_ubah').val(row['ID_SATUAN_OBAT']);
            // $('#satuan_ubah').val(row['NAMA_SATUAN']);
            $('#tanggal_expired_ubah').val(row['KADALUARSA']);
            $('#jumlah_ubah').val(NumberToMoney(row['JUMLAH']));
            $('#isi_ubah').val(NumberToMoney(row['ISI']));
            $('#total_ubah').val(NumberToMoney(row['TOTAL']));
            $('#jumlah_butir_ubah').val(NumberToMoney(row['JUMLAH_BUTIR']));
            $('#harga_beli_ubah').val(NumberToMoney(row['HARGA_BELI']));
            $('#harga_jual_ubah').val(NumberToMoney(row['HARGA_JUAL']));

            var status_obat = "";
            if(row['STATUS_RACIK'] == 0){
                status_obat = "Obat Umum";
            }else{
                status_obat = "Obat Racik";
            }
            $('#status_obat_hidden').val(row['STATUS_RACIK']);
            $('#status_obat_txt').val(status_obat);
						var no_golongan = row['ID_GOLONGAN'];
						var golongan = {
															1 : 'Alkes',
															2 : 'OKT (Obat Keras Tertentu)',
															3 : 'Injeksi',
															4 : 'Supro (Suprositoria)',
															5 : 'Vaksin',
															6 : 'Cream',
															7 : 'Drop',
															8 : 'HV / OTC',
															9 : 'Susu',
														 	10 : 'Sirup',
															11 : 'Tablet',
															12 : 'Generik'
								           }
						$('#id_golongan_ubah').val(no_golongan);
						$('#golongan_ubah').val(golongan[no_golongan]);
						var no_kategori = row['ID_KATEGORI'];
						var kategori = {
															1 : 'Obat Bebas',
															2 : 'Obat Bebas Terbatas',
															3 : 'Obat Keras',
															4 : 'Jamu',
															5 : 'Obat Herbal Terstandar',
															6 : 'Fitofarmaka'
								           }
						$('#id_kategori_ubah').val(no_kategori);
						$('#kategori_ubah').val(kategori[no_kategori]);

            $('#file_hidden').val(row['GAMBAR']);
            var link_gb = "<?php echo base_url(); ?>files/foto_obat/"+row['GAMBAR'];
            $('#gambar_ubah').attr('src',link_gb);
        }
    });

    $('#batal_ubah').click(function(){
        $('#view_ubah').hide();
        $('#view_data').show();
        $('#ket').val("");
    });
}

function hapus_obat(id){
    $('#popup_hps').click();

    $.ajax({
        url : '<?php echo base_url(); ?>apotek/ap_gudang_obat_c/data_obat_id',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(row){
            $('#id_hapus').val(id);
            var txt = row['KODE_OBAT']+' - '+row['NAMA_OBAT'];
            $('#msg').html('Apakah data obat <b>'+txt+'</b> ingin dihapus?');
        }
    });
}

function detail_obat(id){
    $('#popup_detail').click();

    $.ajax({
        url : '<?php echo base_url(); ?>apotek/ap_gudang_obat_c/data_obat_id',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(row){
						var judul_obat_detail = 'Data Obat Detail '+row['NAMA_OBAT'];
						$('#judul_obat_detail').html(judul_obat_detail);
            $('#id_detail').val(id);
            $('#id_nama_obat_detail').val(row['ID_SETUP_NAMA_OBAT']);
            $('#kode_obat_detail').val(row['KODE_OBAT']);
            $('#nama_obat_detail').val(row['NAMA_OBAT']);
            $('#id_jenis_detail').val(row['ID_JENIS_OBAT']);
            $('#jenis_obat_detail').val(row['NAMA_JENIS']);
            $('#tanggal_expired_detail').val(formatTanggal(row['KADALUARSA']));
            $('#jumlah_detail').val(NumberToMoney(row['JUMLAH']));
            $('#isi_detail').val(NumberToMoney(row['ISI']));
            $('#total_detail').val(NumberToMoney(row['TOTAL']));
            $('#jumlah_butir_detail').val(NumberToMoney(row['JUMLAH_BUTIR']));
            $('#harga_beli_detail').val(NumberToMoney(row['HARGA_BELI']));
            $('#harga_jual_detail').val(NumberToMoney(row['HARGA_JUAL']));
            var status_obat = "";
            if(row['STATUS_RACIK'] == 0){
                status_obat = "Obat Umum";
            }else{
                status_obat = "Obat Racik";
            }
            $('#status_obat_hidden_detail').val(row['STATUS_RACIK']);
            $('#status_obat_detail').val(status_obat);
						var no_golongan = row['ID_GOLONGAN'];
						var golongan = {
															1 : 'Alkes',
															2 : 'OKT (Obat Keras Tertentu)',
															3 : 'Injeksi',
															4 : 'Supro (Suprositoria)',
															5 : 'Vaksin',
															6 : 'Cream',
															7 : 'Drop',
															8 : 'HV / OTC',
															9 : 'Susu',
														 	10 : 'Sirup',
															11 : 'Tablet',
															12 : 'Generik'
								           }
						$('#id_golongan_detail').val(no_golongan);
						$('#golongan_detail').val(golongan[no_golongan]);
						var no_kategori = row['ID_KATEGORI'];
						var kategori = {
															1 : 'Obat Bebas',
															2 : 'Obat Bebas Terbatas',
															3 : 'Obat Keras',
															4 : 'Jamu',
															5 : 'Obat Herbal Terstandar',
															6 : 'Fitofarmaka'
								           }
						$('#id_kategori_detail').val(no_kategori);
						$('#kategori_detail').val(kategori[no_kategori]);

						if (row['GAMBAR'] == null || row['GAMBAR'] == '') {
							$('#gambar_detail').hide();
							$('#gambar_tidak_detail').show();
						}else {
							$('#file_hidden').val(row['GAMBAR']);
							$('#gambar_tidak_detail').hide();
							$('#gambar_detail').show();
							var link_gb = "<?php echo base_url(); ?>files/foto_obat/"+row['GAMBAR'];
							$('#gambar_detail').attr('src',link_gb);
						}

						var tanggal_waktu_masuk = row['TANGGAL_MASUK']+' '+row['WAKTU_MASUK'];
						$('#tanggal_waktu_detail').val(tanggal_waktu_masuk);
        }
    });
}
</script>

<div id="popup_load">
    <div class="window_load">
        <img src="<?=base_url()?>picture/progress.gif" height="100" width="125">
    </div>
</div>

<input type="hidden" id="ket" value="">

<div class="col-lg-12">
    <div class="card-box card-tabs" id="view_data">
    	<form class="form-horizontal" role="form" method="post" action="<?php echo $url_cetak; ?>">
            <div class="form-group">
                <div class="col-md-7">
                    <button type="button" class="btn btn-purple waves-effect w-md waves-light" id="btn_tambah">
                        <i class="fa fa-plus"></i> Tambah Obat
                    </button>
                </div>
                <!-- <div class="col-md-5 text-right">
                    <button type="submit" class="btn btn-success waves-effect w-md waves-light" name="cetuk">
                        <i class="fa fa-book"></i> Cetak
                    </button>
                </div> -->
            </div>
            <div class="form-group">
                <label class="col-md-1 control-label" style="text-align:left;">Urutkan</label>
                <div class="col-md-6">
                    <div class="radio radio-purple radio-inline">
                        <input type="radio" name="urutkan" value="Default" id="default" checked="checked">
                        <label for="default"> Default </label>
                    </div>
                    <div class="radio radio-purple radio-inline">
                        <input type="radio" name="urutkan" value="Nama Obat" id="urut_nama_obat">
                        <label for="nama_poli"> Nama Obat </label>
                    </div>
                    <div class="radio radio-purple radio-inline">
                        <input type="radio" name="urutkan" value="Stok" id="urut_stok">
                        <label for="jenis"> Stok </label>
                    </div>
                    <div class="radio radio-purple radio-inline">
                        <input type="radio" name="urutkan" value="Expired" id="urut_expired">
                        <label for="jenis"> Expired </label>
                    </div>
                </div>
                <div class="col-md-4 pull-right">
	                <div class="input-group">
	                    <input type="text" class="form-control" id="cari_obat" placeholder="Cari..." value="" onkeypress="return onEnterText(event);">
	                    <span class="input-group-btn">
	                    	<button type="button" class="btn waves-effect waves-light btn-warning" id="tombol_cari">
	                    		<i class="fa fa-search"></i>
	                    	</button>
	                    	<button type="button" class="btn waves-effect waves-light btn-warning" id="tombol_reset">
	                    		<i class="fa fa-refresh"></i>
	                    	</button>
	                    </span>
	                </div>
                </div>
            </div>
            <div class="form-group" id="view_stok">
                <label class="col-md-1 control-label">&nbsp;</label>
                <div class="col-md-2">
                    <select id="urutkan_stok" class="form-control">
                        <option value="Rendah">Terendah</option>
                        <option value="Tinggi">Tertinggi</option>
                    </select>
                </div>
            </div>
        </form>

    	<div class="table-responsive">
            <table id="tabel_obat" class="table table-bordered">
                <thead>
                    <tr class="biru">
                        <th style="color:#fff; text-align:center;" width="50">No</th>
                        <th style="color:#fff; text-align:center;">Nama Obat</th>
                        <th style="color:#fff; text-align:center;">Jenis Obat</th>
                        <th style="color:#fff; text-align:center;">Harga Beli</th>
                        <th style="color:#fff; text-align:center;">Harga Jual</th>
                        <th style="color:#fff; text-align:center;">Stok</th>
                        <th style="color:#fff; text-align:center;">Tanggal Expired</th>
                        <th style="color:#fff; text-align:center;">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                </tbody>
            </table>
        </div>
        <form class="form-horizontal" role="form">
        	<div class="form-group">
        		<div class="col-md-10">
        			<div id="tablePaging"> </div>
        		</div>
            </div>
            <div class="form-group">
        		<div class="col-md-9">
        			&nbsp;
        		</div>
                <label class="col-md-2 control-label">Jumlah Tampil</label>
                <div class="col-md-1 pull-right">
	                <select class="form-control" id="jumlah_tampil">
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
            </div>
            <!-- <div class="form-group">
                <table>
                    <tr>
                        <td><div class="merah_tr" style="width:15px; height:15px;"></div></td>
                        <td>&nbsp;</td>
                        <td>Keterangan warna tabel</td>
                    </tr>
                </table>
            </div> -->
        </form>
    </div>

    <div class="card-box card-tabs" id="view_tambah">
        <form class="form-horizontal" role="form" action="<?php echo $url_simpan; ?>" method="post" enctype="multipart/form-data">
        	<h4 class="header-title m-t-0 m-b-30">Tambah Obat</h4>
            <hr/>
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="col-md-2 control-label">Kode Obat</label>
                        <div class="col-md-8">
                            <div class="input-group">
                                <input type="hidden" name="id_nama_obat" id="id_nama_obat" value="">
                                <input type="text" class="form-control" id="kode_obat" value="" required="required" readonly>
                                <span class="input-group-btn">
                                    <button class="btn waves-effect waves-light btn-default btn_nama_obat" type="button">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Nama Obat</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="nama_obat" value="" readonly>
                        </div>
                    </div>
                    <!-- <div class="form-group">
                        <label class="col-md-2 control-label">Merk</label>
                        <div class="col-md-8">
                        	<input type="hidden" name="id_merk" id="id_merk" value="">
                            <input type="text" class="form-control" id="merk" value="" readonly>
                        </div>
                    </div> -->
                    <div class="form-group">
                        <label class="col-md-2 control-label">Jenis Obat</label>
                        <div class="col-md-8">
                            <div class="input-group">
                            	<input type="hidden" name="id_jenis" id="id_jenis" value="">
                                <input type="text" class="form-control" id="jenis_obat" value="" required="required" readonly>
                                <span class="input-group-btn">
                                	<button class="btn waves-effect waves-light btn-default btn_jenis" type="button">
                                		<i class="fa fa-search"></i>
                                	</button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="form-group">
                        <label class="col-md-2 control-label">Satuan</label>
                        <div class="col-md-8">
                            <div class="input-group">
                            	<input type="hidden" name="id_satuan" id="id_satuan" value="">
                                <input type="text" class="form-control" id="satuan" value="" required="required" readonly>
                                <span class="input-group-btn">
                                	<button class="btn waves-effect waves-light btn-default btn_satuan" type="button">
                                		<i class="fa fa-search"></i>
                                	</button>
                                </span>
                            </div>
                        </div>
                    </div> -->
                    <div class="form-group">
                        <label class="col-md-2 control-label">Expired</label>
                        <div class="col-md-8">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input type="text" class="form-control datepicker-here" name="tanggal_expired" id="tanggal_expired" value="" required="required" data-language="en" data-date-format="dd-mm-yyyy">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">&nbsp;</label>
                        <div class="col-md-8">
                            <div class="radio radio-purple radio-inline">
                                <input type="radio" name="status_obat" value="1" id="radio_obat1">
                                <label for="radio_obat1"> Obat Racik </label>
                            </div>
                            <div class="radio radio-purple radio-inline">
                                <input type="radio" name="status_obat" value="0" id="radio_obat2">
                                <label for="radio_obat2"> Obat Umum </label>
                            </div>
                        </div>
                    </div>
										<div class="form-group">
                        <label class="col-md-2 control-label">Golongan Obat</label>
                        <div class="col-md-8">
                            <div class="input-group">
                            	<input type="hidden" name="id_golongan" id="id_golongan" value="">
                                <input type="text" class="form-control" id="golongan" value="" required="required" readonly>
                                <span class="input-group-btn">
                                	<button class="btn waves-effect waves-light btn-default btn_golongan" type="button">
                                		<i class="fa fa-search"></i>
                                	</button>
                                </span>
                            </div>
                        </div>
                    </div>
										<div class="form-group">
                        <label class="col-md-2 control-label">Kategori Obat</label>
                        <div class="col-md-8">
                            <div class="input-group">
                            	<input type="hidden" name="id_kategori" id="id_kategori" value="">
                                <input type="text" class="form-control" id="kategori" value="" required="required" readonly>
                                <span class="input-group-btn">
                                	<button class="btn waves-effect waves-light btn-default btn_kategori" type="button">
                                		<i class="fa fa-search"></i>
                                	</button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="col-md-2 control-label">Jumlah</label>
                        <div class="col-md-8">
                                <input type="text" class="form-control" name="jumlah" id="jumlah" value="" required="required" onkeyup="FormatCurrency(this); hitung_total(); convert_kg_to_ml();">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Isi</label>
                        <div class="col-md-8">
                                <input type="text" class="form-control" name="isi" id="isi" value="" required="required" onkeyup="FormatCurrency(this); hitung_total();">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Total</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="total" id="total" value="" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Jumlah Isi</label>
                        <div class="col-md-8">
                                <input type="text" class="form-control" name="jumlah_butir" id="jumlah_butir" value="" required="required" onkeyup="FormatCurrency(this);">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Harga Beli</label>
                        <div class="col-md-8">
                            <div class="input-group">
                                <span class="input-group-addon">Rp</span>
                                <input type="text" class="form-control" name="harga_beli" id="harga_beli" value="" required="required" onkeyup="FormatCurrency(this);">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Harga Jual</label>
                        <div class="col-md-8">
                            <div class="input-group">
                                <span class="input-group-addon">Rp</span>
                                <input type="text" class="form-control" name="harga_jual" id="harga_jual" value="" required="required" onkeyup="FormatCurrency(this);">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Gambar Obat</label>
                        <div class="col-md-8">
                            <input type="file" class="dropify" name="userfile">
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <center>
                <button type="submit" class="btn btn-success waves-effect waves-light m-b-5"> <i class="fa fa-save"></i> <span>Simpan</span> </button>
                <button type="button" class="btn btn-danger waves-effect waves-light m-b-5" id="batal"> <i class="fa fa-times"></i> <span>Batal</span> </button>
            </center>
        </form>
    </div>
</div>

<div class="col-md-12" id="view_ubah">
    <div class="card-box card-tabs">
        <form class="form-horizontal" role="form" action="<?php echo $url_ubah; ?>" method="post" enctype="multipart/form-data">
            <h4 class="header-title m-t-0 m-b-30">Ubah Data Obat</h4>
            <hr/>
            <div class="row">
        		<input type="hidden" name="id_ubah" id="id_ubah" value="">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="col-md-2 control-label">Kode Obat</label>
                        <div class="col-md-8">
                            <div class="input-group">
                                <input type="hidden" name="id_nama_obat_ubah" id="id_nama_obat_ubah" value="">
                                <input type="text" class="form-control" id="kode_obat_ubah" value="" required="required" readonly>
                                <span class="input-group-btn">
                                    <button class="btn waves-effect waves-light btn-default btn_nama_obat" type="button">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Nama Obat</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="nama_obat_ubah" value="" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Jenis Obat</label>
                        <div class="col-md-8">
                            <div class="input-group">
                                <input type="hidden" name="id_jenis_ubah" id="id_jenis_ubah" value="">
                                <input type="text" class="form-control" id="jenis_obat_ubah" value="" required="required" readonly>
                                <span class="input-group-btn">
                                    <button class="btn waves-effect waves-light btn-default btn_jenis" type="button">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Expired</label>
                        <div class="col-md-8">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input type="text" class="form-control datepicker-here" name="tanggal_expired_ubah" id="tanggal_expired_ubah" value="" required="required" data-language="en" data-date-format="dd-mm-yyyy">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Status Obat</label>
                        <div class="col-md-6">
                            <input type="hidden" name="status_obat_hidden" id="status_obat_hidden" value="">
                            <input type="text" class="form-control" id="status_obat_txt" value="" readonly>
                        </div>
                        <div class="col-md-2">
                            <div class="checkbox checkbox-primary">
                                <input type="checkbox" name="checkbox2" id="checkbox2">
                                <label for="checkbox2">
                                    Ubah
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group" id="view_status_obat">
                        <label class="col-md-2 control-label">&nbsp;</label>
                        <div class="col-md-8">
                            <div class="radio radio-purple radio-inline">
                                <input type="radio" name="status_obat_ubah" value="1" id="radio_obat1">
                                <label for="radio_obat1"> Obat Racik </label>
                            </div>
                            <div class="radio radio-purple radio-inline">
                                <input type="radio" name="status_obat_ubah" value="0" id="radio_obat2">
                                <label for="radio_obat2"> Obat Umum </label>
                            </div>
                        </div>
                    </div>
										<div class="form-group">
                        <label class="col-md-2 control-label">Golongan Obat</label>
                        <div class="col-md-8">
                            <div class="input-group">
                            	<input type="hidden" name="id_golongan_ubah" id="id_golongan_ubah" value="">
                                <input type="text" class="form-control" id="golongan_ubah" value="" required="required" readonly>
                                <span class="input-group-btn">
                                	<button class="btn waves-effect waves-light btn-default btn_golongan" type="button">
                                		<i class="fa fa-search"></i>
                                	</button>
                                </span>
                            </div>
                        </div>
                    </div>
										<div class="form-group">
                        <label class="col-md-2 control-label">Kategori Obat</label>
                        <div class="col-md-8">
                            <div class="input-group">
                            	<input type="hidden" name="id_kategori_ubah" id="id_kategori_ubah" value="">
                                <input type="text" class="form-control" id="kategori_ubah" value="" required="required" readonly>
                                <span class="input-group-btn">
                                	<button class="btn waves-effect waves-light btn-default btn_kategori" type="button">
                                		<i class="fa fa-search"></i>
                                	</button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="col-md-2 control-label">Jumlah</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="jumlah_ubah" id="jumlah_ubah" value="" required="required" onkeyup="FormatCurrency(this); hitung_total();">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Isi</label>
                        <div class="col-md-8">
                                <input type="text" class="form-control" name="isi_ubah" id="isi_ubah" value="" required="required" onkeyup="FormatCurrency(this); hitung_total();">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Total</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="total_ubah" id="total_ubah" value="" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Jumlah Isi</label>
                        <div class="col-md-8">
                                <input type="text" class="form-control" name="jumlah_butir_ubah" id="jumlah_butir_ubah" value="" required="required" onkeyup="FormatCurrency(this);">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Harga Beli</label>
                        <div class="col-md-8">
                            <div class="input-group">
                                <span class="input-group-addon">Rp</span>
                                <input type="text" class="form-control" name="harga_beli_ubah" id="harga_beli_ubah" value="" required="required" onkeyup="FormatCurrency(this);">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Harga Jual</label>
                        <div class="col-md-8">
                            <div class="input-group">
                                <span class="input-group-addon">Rp</span>
                                <input type="text" class="form-control" name="harga_jual_ubah" id="harga_jual_ubah" value="" required="required" onkeyup="FormatCurrency(this);">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">View Obat</label>
                        <div class="col-md-6">
                            <input type="hidden" name="file_hidden" id="file_hidden" value="">
                            <img src="" id="gambar_ubah" style="max-height:130px; max-width:250px;">
                        </div>
                        <div class="col-md-2">
                            <div class="checkbox checkbox-primary">
                                <input type="checkbox" name="checkbox3" id="checkbox3">
                                <label for="checkbox3">
                                    Ubah
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group" id="view_gambar_obat">
                        <label class="col-md-2 control-label">Gambar Obat</label>
                        <div class="col-md-8">
                            <input type="file" class="dropify" name="fileuser">
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <center>
                <button type="submit" class="btn btn-success waves-effect waves-light m-b-5"> <i class="fa fa-save"></i> <span>Simpan</span> </button>
                <button type="button" class="btn btn-danger waves-effect waves-light m-b-5" id="batal_ubah"> <i class="fa fa-times"></i> <span>Batal</span> </button>
            </center>
        </form>
    </div>
</div>

<button class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#myModal1" id="popup_nama_obat" style="display:none;">Standard Modal</button>
<div id="myModal1" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Data Nama Obat</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form">
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="input-group">
                                <input type="text" class="form-control" id="cari_nama_obat" placeholder="Cari..." value="">
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
                        <table class="table table-hover" id="tabel_nama_obat">
                            <thead>
                                <tr class="merah_popup">
                                    <th style="text-align:center; color: #fff;" width="50">No</th>
                                    <th style="text-align:center; color: #fff;">Kode Obat</th>
                                    <th style="text-align:center; color: #fff;">Barcode</th>
                                    <th style="text-align:center; color: #fff;">Nama Obat</th>
                                    <!-- <th style="text-align:center; color: #fff;">Merk</th> -->
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_nama_obat">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<button class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#myModal2" id="popup_jenis" style="display:none;">Standard Modal</button>
<div id="myModal2" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Data Jenis Obat</h4>
            </div>
            <div class="modal-body">
            	<form class="form-horizontal" role="form">
		            <div class="form-group">
		                <div class="col-md-12">
			                <div class="input-group">
			                    <input type="text" class="form-control" id="cari_jenis" placeholder="Cari..." value="">
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
		                <table class="table table-hover" id="tabel_jenis">
		                    <thead>
		                        <tr class="merah_popup">
		                            <th style="text-align:center; color: #fff;" width="50">No</th>
		                            <th style="text-align:center; color: #fff;">Jenis Obat</th>
		                        </tr>
		                    </thead>
		                    <tbody>

		                    </tbody>
		                </table>
            		</div>
            	</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_jenis">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<button class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#myModal3" id="popup_satuan" style="display:none;">Standard Modal</button>
<div id="myModal3" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Data Jenis Obat</h4>
            </div>
            <div class="modal-body">
            	<form class="form-horizontal" role="form">
		            <div class="form-group">
		                <div class="col-md-12">
			                <div class="input-group">
			                    <input type="text" class="form-control" id="cari_satuan" placeholder="Cari..." value="">
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
		                <table class="table table-hover" id="tabel_satuan">
		                    <thead>
		                        <tr class="merah_popup">
		                            <th style="text-align:center; color: #fff;" width="50">No</th>
		                            <th style="text-align:center; color: #fff;">Satuan Obat</th>
		                        </tr>
		                    </thead>
		                    <tbody>

		                    </tbody>
		                </table>
            		</div>
            	</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_satuan">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<button id="popup_hps" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#custom-width-modal" style="display:none;">Custom width Modal</button>
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
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Tidak</button>
                    <button type="submit" class="btn btn-danger waves-effect waves-light">Ya</button>
                </form>
            </div>
        </div>
    </div>
</div>

<button class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#myModal4" id="popup_golongan" style="display:none;">Standard Modal</button>
<div id="myModal4" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Data Jenis Golongan Obat</h4>
            </div>
            <div class="modal-body">
            	<div class="table-responsive">
            		<div class="scroll-y">
		                <table class="table table-hover">
		                    <thead>
		                        <tr class="merah_popup">
		                            <th style="text-align:center; color: #fff;" width="50">No</th>
		                            <th style="text-align:center; color: #fff;">Jenis Golongan</th>
		                        </tr>
		                    </thead>
		                    <tbody>
													<?php
													$array = array(
														0 => 'Alkes',
														1 => 'OKT (Obat Keras Tertentu)',
														2 => 'Injeksi',
														3 => 'Supro (Suprositoria)',
														4 => 'Vaksin',
														5 => 'Cream',
														6 => 'Drop',
														7 => 'HV / OTC',
														8 => 'Susu',
														9 => 'Sirup',
														10 => 'Tablet',
														11 => 'Generik',
													);
													$no = 0;

													for ($i=0; $i < count($array); $i++) {
													$no++;
													 ?>
													<tr style="cursor:pointer;" onclick="klik_golongan(<?php echo $no; ?>);">
														<td><?php echo $no; ?></td>
														<td><?php echo $array[$i]; ?></td>
													</tr>
													<?php } ?>
		                    </tbody>
		                </table>
            		</div>
            	</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_golongan">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<button class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#myModal5" id="popup_kategori" style="display:none;">Standard Modal</button>
<div id="myModal5" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Data Jenis Kategori Obat</h4>
            </div>
            <div class="modal-body">
            	<div class="table-responsive">
            		<div class="scroll-y">
		                <table class="table table-hover">
		                    <thead>
		                        <tr class="merah_popup">
		                            <th style="text-align:center; color: #fff;" width="50">No</th>
		                            <th style="text-align:center; color: #fff;">Jenis Golongan</th>
		                        </tr>
		                    </thead>
		                    <tbody>
													<?php
													$array = array(
														0 => 'Obat Bebas',
														1 => 'Obat Bebas Terbatas',
														2 => 'Obat Keras',
														3 => 'Jamu',
														4 => 'Obat Herbal Terstandar',
														5 => 'Fitofarmaka',
													);
													$no = 0;

													for ($i=0; $i < count($array); $i++) {
													$no++;
													 ?>
													<tr style="cursor:pointer;" onclick="klik_kategori(<?php echo $no; ?>);">
														<td><?php echo $no; ?></td>
														<td><?php echo $array[$i]; ?></td>
													</tr>
													<?php } ?>
		                    </tbody>
		                </table>
            		</div>
            	</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_kategori">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<button class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#myModal6" id="popup_detail" style="display:none;">Standard Modal</button>
<div id="myModal6" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <center><h4 class="modal-title" id="judul_obat_detail"></h4>
            </div>
            <div class="modal-body">
								<div class="row">
									<div class="col-md-12">
										<center>
										<img src="" id="gambar_detail" style="height: 200px;">
										<span id="gambar_tidak_detail">Gambar Tidak Ada</span>
									</center>
									</div>
									<div class="col-md-6">
										<div class="input-group m-t-10">
											<span class="input-group-btn">
                      <button type="button" class="btn waves-effect waves-light btn-primary">Nama Obat</button>
                      </span>
                      <input type="text" id="nama_obat_detail" name="example-input2-group2" class="form-control" disabled>
                    </div>
										<div class="input-group m-t-10">
											<span class="input-group-btn">
                      <button type="button" class="btn waves-effect waves-light btn-primary">Jenis Obat</button>
                      </span>
                      <input type="text" id="jenis_obat_detail" name="example-input2-group2" class="form-control" disabled>
                    </div>
										<div class="input-group m-t-10">
											<span class="input-group-btn">
                      <button type="button" class="btn waves-effect waves-light btn-primary">Expired</button>
                      </span>
                      <input type="text" id="tanggal_expired_detail" name="example-input2-group2" class="form-control" disabled>
                    </div>
										<div class="input-group m-t-10">
											<span class="input-group-btn">
                      <button type="button" class="btn waves-effect waves-light btn-primary">Status Obat</button>
                      </span>
                      <input type="text" id="status_obat_detail" name="example-input2-group2" class="form-control" disabled>
                    </div>
										<div class="input-group m-t-10">
											<span class="input-group-btn">
                      <button type="button" class="btn waves-effect waves-light btn-primary">Golongan Obat</button>
                      </span>
                      <input type="text" id="golongan_detail" name="example-input2-group2" class="form-control" disabled>
                    </div>
										<div class="input-group m-t-10">
											<span class="input-group-btn">
                      <button type="button" class="btn waves-effect waves-light btn-primary">Kategori Obat</button>
                      </span>
                      <input type="text" id="kategori_detail" name="example-input2-group2" class="form-control" disabled>
                    </div>
									</div>
									<div class="col-md-6">
										<div class="input-group m-t-10">
											<span class="input-group-btn">
                      <button type="button" class="btn waves-effect waves-light btn-primary">Jumlah</button>
                      </span>
                      <input type="text" id="jumlah_detail" name="example-input2-group2" class="form-control" disabled>
                    </div>
										<div class="input-group m-t-10">
											<span class="input-group-btn">
                      <button type="button" class="btn waves-effect waves-light btn-primary">Isi</button>
                      </span>
                      <input type="text" id="isi_detail" name="example-input2-group2" class="form-control" disabled>
                    </div>
										<div class="input-group m-t-10">
											<span class="input-group-btn">
                      <button type="button" class="btn waves-effect waves-light btn-primary">Jumlah Isi</button>
                      </span>
                      <input type="text" id="jumlah_butir_detail" name="example-input2-group2" class="form-control" disabled>
                    </div>
										<div class="input-group m-t-10">
											<span class="input-group-btn">
                      <button type="button" class="btn waves-effect waves-light btn-primary">Harga Beli</button>
                      </span>
                      <input type="text" id="harga_beli_detail" name="example-input2-group2" class="form-control" disabled>
                    </div>
										<div class="input-group m-t-10">
											<span class="input-group-btn">
                      <button type="button" class="btn waves-effect waves-light btn-primary">Harga Jual</button>
                      </span>
                      <input type="text" id="harga_jual_detail" name="example-input2-group2" class="form-control" disabled>
                    </div>
										<div class="input-group m-t-10">
											<span class="input-group-btn">
                      <button type="button" class="btn waves-effect waves-light btn-primary">Tanggal Dan Waktu Masuk</button>
                      </span>
                      <input type="text" id="tanggal_waktu_detail" name="example-input2-group2" class="form-control" disabled>
                    </div>
									</div>
								</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_kategori">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

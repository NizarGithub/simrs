<script type="text/javascript" src="<?php echo base_url(); ?>js-devan/jquery-1.11.1.min.js"></script>

<style type="text/css">
#view_tambah, #view_ubah, #tombol_reset, #view_stok, #view_status_obat, #view_gambar_obat{
	display: none;
}
.view_tablet_grp{
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

		$('#tanpa_diskon').click(function(){
      	$('#diskon').attr('disabled','disabled');
				$('#diskon').val('');
				var total = $('#total').val();
				$('#grand_total').val(total);
    });

		$('#diskon_persen').click(function(){
				$('#diskon').removeAttr('disabled');
				$('#diskon').val('');
				var total = $('#total').val();
				$('#grand_total').val(total);
		});

		$('#diskon_harga').click(function(){
      	$('#diskon').removeAttr('disabled');
				$('#diskon').val('');
				var total = $('#total').val();
				$('#grand_total').val(total);
    });
});

//NAMA OBAT
function check_tablet(number){
	var ket = $('#ket').val();
	var check_tablet = $('#check_tablet_'+number).is(":checked");
	var check_tablet_ubah = $('#check_tablet_ubah').is(":checked");

	if (ket == 'Tambah') {
		if(check_tablet == true){
				$('.harga_tablet_grp_'+number).show();
				$('#harga_pertablet_'+number).removeAttr('disabled');
		}else{
				$('.harga_tablet_grp_'+number).hide();
				$('#harga_pertablet_'+number).Attr('disabled','disabled');
		}
	}else {
		if(check_tablet_ubah == true){
				$('.view_tablet_grp').show();
				$('#harga_pertablet_ubah').removeAttr('disabled');
		}else{
				$('.view_tablet_grp').hide();
				$('#harga_pertablet_ubah').Attr('disabled','disabled');
		}
	}
}

function get_nama_obat(number){
	$('#popup_nama_obat').click();

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

                    $tr += '<tr style="cursor:pointer;" onclick="klik_nama_obat('+result[i].ID+','+number+');">'+
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

function klik_nama_obat(id, number){
    $('#tutup_nama_obat').click();

    $.ajax({
        url : '<?php echo base_url(); ?>apotek/ap_gudang_obat_c/klik_nama_obat',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(row){
            var ket = $('#ket').val();

            if(ket == 'Tambah'){
                $('#id_nama_obat_'+number).val(id);
                $('#kode_obat_'+number).val(row['KODE_OBAT']);
                $('#nama_obat_'+number).val(row['NAMA_OBAT']);
								$('#jenis_obat_'+number).val(row['NAMA_JENIS']);
								$('#expired_'+number).val(row['EXPIRED']);
								$('#golongan_'+number).val(row['GOLONGAN_OBAT']);
								$('#kategori_'+number).val(row['KATEGORI_OBAT']);
								$('#service_'+number).val(row['SERVICE']);
                // $('#id_merk').val(row['ID_MERK']);
                // $('#merk').val(row['MERK']);

            }else{
                $('#id_nama_obat_ubah').val(id);
                $('#kode_obat_ubah').val(row['KODE_OBAT']);
                $('#nama_obat_ubah').val(row['NAMA_OBAT']);
								$('#jenis_obat_ubah').val(row['NAMA_JENIS']);
								$('#expired_ubah').val(row['EXPIRED']);
								$('#golongan_ubah').val(row['GOLONGAN_OBAT']);
								$('#kategori_ubah').val(row['KATEGORI_OBAT']);
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

        			$tr += "<tr class='"+warna+"'>"+
        						"<td style='text-align:center;'>"+no+"</td>"+
        						"<td>"+
        							"<b>"+result[i].NAMA_OBAT+"</b><br/>"+
                                    "<small>"+result[i].KODE_OBAT+"</small>"+
        						"</td>"+
                                "<td>"+result[i].NAMA_JENIS+"</td>"+
                                "<td style='text-align:right;'>Rp. "+NumberToMoney(result[i].HARGA_BELI)+"</td>"+
        						"<td style='text-align:right;'>Rp. "+NumberToMoney(result[i].HARGA_JUAL)+"</td>"+
        						"<td>"+NumberToMoney(result[i].TOTAL)+"</td>"+
										"<td>"+formatTanggal(result[i].TANGGAL_MASUK)+" : "+result[i].WAKTU_MASUK+"</td>"+
        						"<td>"+formatTanggal(result[i].EXPIRED)+"</td>"+
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

function hitung_total(number){
    var ket = $('#ket').val();

    if(ket == 'Tambah'){
        var jumlah = $('#jumlah_'+number).val();
        var isi = $('#isi_'+number).val();

        jumlah = jumlah.split(',').join('');
        isi = isi.split(',').join('');

        if(jumlah == ""){
            jumlah = 0;
        }

        if(isi == ""){
            isi = 0;
        }

        var total = parseFloat(jumlah) * parseFloat(isi);
        $('#total_'+number).val(NumberToMoney(total));
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
						$('#id_supplier_ubah').val(row['ID_SUPPLIER']);
						$('#nama_supplier_ubah').val(row['NAMA_SUPPLIER']);
            $('#id_nama_obat_ubah').val(row['ID_SETUP_NAMA_OBAT']);
            $('#kode_obat_ubah').val(row['KODE_OBAT']);
            $('#nama_obat_ubah').val(row['NAMA_OBAT']);
            $('#jenis_obat_ubah').val(row['NAMA_JENIS']);
            $('#tanggal_expired_ubah').val(row['EXPIRED']);
            $('#jumlah_ubah').val(NumberToMoney(row['JUMLAH']));
            $('#isi_ubah').val(NumberToMoney(row['ISI']));
            $('#total_ubah').val(NumberToMoney(row['TOTAL']));
            $('#jumlah_butir_ubah').val(NumberToMoney(row['JUMLAH_BUTIR']));
            $('#harga_beli_ubah').val(NumberToMoney(row['HARGA_BELI']));
            $('#harga_jual_ubah').val(NumberToMoney(row['HARGA_JUAL']));
						$('#golongan_ubah').val(row['GOLONGAN_OBAT']);
						$('#kategori_ubah').val(row['KATEGORI_OBAT']);
						$('#service_ubah').val(row['SERVICE']);

						if (row['HARGA_PERTABLET'] == '' || row['HARGA_PERTABLET'] == '0' || row['HARGA_PERTABLET'] == null) {
							$('#check_tablet_ubah').removeAttr('checked');
							$('.view_tablet_grp').hide();
							$('#harga_pertablet_ubah').attr('disabled','disabled');

							harga_pertablet = '';
							if (row['HARGA_PERTABLET'] == '' || row['HARGA_PERTABLET'] == null) {
								harga_pertablet = '0';
							}else {
								harga_pertablet = row['HARGA_PERTABLET'];
							}
							$('#harga_pertablet_ubah').val(NumberToMoney(harga_pertablet));
						}else {
							$('#check_tablet_ubah').attr('checked','checked');
							$('.view_tablet_grp').show();
							$('#harga_pertablet_ubah').removeAttr('disabled');
							$('#harga_pertablet_ubah').val(NumberToMoney(row['HARGA_PERTABLET']));
						}
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
						$('#jenis_obat_detail').val(row['NAMA_JENIS']);
            $('#kode_obat_detail').val(row['KODE_OBAT']);
            $('#nama_obat_detail').val(row['NAMA_OBAT']);
            $('#tanggal_expired_detail').val(formatTanggal(row['EXPIRED']));
            $('#jumlah_detail').val(NumberToMoney(row['JUMLAH']));
            $('#isi_detail').val(NumberToMoney(row['ISI']));
            $('#total_detail').val(NumberToMoney(row['TOTAL']));
            $('#jumlah_butir_detail').val(NumberToMoney(row['JUMLAH_BUTIR']));
						harga_pertablet = '';
						if (row['HARGA_PERTABLET'] == '' || row['HARGA_PERTABLET'] == null) {
							harga_pertablet = '0';
						}else {
							harga_pertablet = row['HARGA_PERTABLET'];
						}
						$('#harga_pertablet_detail').val(NumberToMoney(harga_pertablet));
            $('#harga_beli_detail').val(NumberToMoney(row['HARGA_BELI']));
            $('#harga_jual_detail').val(NumberToMoney(row['HARGA_JUAL']));
						$('#golongan_detail').val(row['GOLONGAN_OBAT']);
						$('#kategori_detail').val(row['KATEGORI_OBAT']);
						$('#service_detail').val(row['SERVICE']);
            var status_obat = "";
            if(row['STATUS_RACIK'] == 0){
                status_obat = "Obat Umum";
            }else{
                status_obat = "Obat Racik";
            }
            $('#status_obat_detail').val(status_obat);
						var tanggal_waktu_masuk = row['TANGGAL_MASUK']+' '+row['WAKTU_MASUK'];
						$('#tanggal_waktu_detail').val(tanggal_waktu_masuk);
        }
    });
}

function hapus_tambah_obat(btn){
	var row = btn.parentNode.parentNode;
  row.parentNode.remove(row);
	var jml_tr = $('#number').val();
	var i = parseFloat(jml_tr)-1;
	$('#number').val(i);
}

function tambah_obat(){
	var jml_tr = $('#number').val();
  var i = parseInt(jml_tr) + 1;
		$menu_1 =
		'<tr class="number_real">'+
			'<td>'+
				'<div class="col-lg-12">'+
								'<div class="portlet">'+
										'<div class="portlet-heading bg-default" style="background-color:#7ac142; color: white;">'+
												'<h3 class="portlet-title">'+
														'<i class="fa fa-medkit"></i> Obat <span id="number_span">'+i+'</span>'+
												'</h3>'+
												'<div class="portlet-widgets" style="color: white;">'+
														'<a data-toggle="collapse" data-parent="#accordion1" class="collapsed" href="#bg-primary'+i+'" style="color: white;"><i class="zmdi zmdi-minus"></i></a>'+
														'<a href="javascript:void(0);" onclick="hapus_tambah_obat(this);"><i class="zmdi zmdi-close" style="color: white;"></i></a>'+
												'</div>'+
												'<div class="clearfix"></div>'+
										'</div>'+
										'<div id="bg-primary'+i+'" class="panel-collapse collapse out">'+
												'<div class="portlet-body">'+
													'<div class="row">'+
															'<div class="col-lg-6">'+
																	'<div class="form-group">'+
																			'<label class="col-md-2 control-label">Kode Obat</label>'+
																			'<div class="col-md-8">'+
																					'<div class="input-group">'+
																							'<input type="hidden" name="id_nama_obat[]" id="id_nama_obat_'+i+'" value="">'+
																							'<input type="text" class="form-control" id="kode_obat_'+i+'" value="" required="required" readonly>'+
																							'<span class="input-group-btn">'+
																									'<button class="btn waves-effect waves-light btn-default" type="button" onclick="get_nama_obat('+i+')">'+
																											'<i class="fa fa-search"></i>'+
																									'</button>'+
																							'</span>'+
																					'</div>'+
																			'</div>'+
																	'</div>'+
																	'<div class="form-group">'+
																			'<label class="col-md-2 control-label">Nama Obat</label>'+
																			'<div class="col-md-8">'+
																					'<input type="text" class="form-control" id="nama_obat_'+i+'" value="" readonly>'+
																			'</div>'+
																	'</div>'+
																	'<div class="form-group">'+
																			'<label class="col-md-2 control-label">Jenis Obat</label>'+
																			'<div class="col-md-8">'+
																					'<input type="text" class="form-control" id="jenis_obat_'+i+'" value="" readonly>'+
																			'</div>'+
																	'</div>'+
																	'<div class="form-group">'+
																			'<label class="col-md-2 control-label">Expired</label>'+
																			'<div class="col-md-8">'+
																					'<input type="text" class="form-control" id="expired_'+i+'" value="" readonly>'+
																			'</div>'+
																	'</div>'+
																	'<div class="form-group">'+
																			'<label class="col-md-2 control-label">Golongan Obat</label>'+
																			'<div class="col-md-8">'+
																					'<input type="text" class="form-control" id="golongan_'+i+'" value="" readonly>'+
																			'</div>'+
																	'</div>'+
																	'<div class="form-group">'+
																			'<label class="col-md-2 control-label">Kategori Obat</label>'+
																			'<div class="col-md-8">'+
																					'<input type="text" class="form-control" id="kategori_'+i+'" value="" readonly>'+
																			'</div>'+
																	'</div>'+
																	'<div class="form-group">'+
																			'<label class="col-md-2 control-label">Service</label>'+
																			'<div class="col-md-8">'+
																					'<input type="text" class="form-control" id="service_'+i+'" value="" readonly>'+
																			'</div>'+
																	'</div>'+
															'</div>'+

															'<div class="col-lg-6">'+
																	'<div class="form-group">'+
																			'<label class="col-md-2 control-label">Jumlah</label>'+
																			'<div class="col-md-8">'+
																							'<input type="text" class="form-control" name="jumlah[]" id="jumlah_'+i+'" value="" required="required" onkeyup="FormatCurrency(this); hitung_total('+i+');">'+
																			'</div>'+
																	'</div>'+
																	'<div class="form-group">'+
																			'<label class="col-md-2 control-label">Isi</label>'+
																			'<div class="col-md-8">'+
																							'<input type="text" class="form-control" name="isi[]" id="isi_'+i+'" value="" required="required" onkeyup="FormatCurrency(this); hitung_total('+i+');">'+
																			'</div>'+
																	'</div>'+
																	'<div class="form-group">'+
																			'<label class="col-md-2 control-label">Total</label>'+
																			'<div class="col-md-8">'+
																					'<input type="text" class="form-control" name="total[]" id="total_'+i+'" value="" readonly>'+
																			'</div>'+
																	'</div>'+
																	'<div class="form-group">'+
																			'<label class="col-md-2 control-label">Jumlah Isi</label>'+
																			'<div class="col-md-8">'+
																							'<input type="text" class="form-control" name="jumlah_butir[]" id="jumlah_butir_'+i+'" value="" required="required" onkeyup="FormatCurrency(this);">'+
																							'<span class="help-block"><small>Blister / Tablet</small></span>'+
																			'</div>'+
																	'</div>'+
																	'<div class="form-group">'+
																			'<label class="col-sm-2 control-label">Per Tablet</label>'+
																			'<div class="col-sm-6">'+
																					'<div class="checkbox checkbox-primary">'+
																							'<input id="check_tablet_'+i+'" type="checkbox" onclick="check_tablet('+i+');">'+
																							'<label for="check_tablet"> Dijual pertablet atau tidak </label>'+
																					'</div>'+
																			'</div>'+
																	'</div>'+
																	'<div class="form-group view_tablet_grp harga_tablet_grp_'+i+'">'+
																			'<label class="col-md-2 control-label">Harga Pertablet</label>'+
																			'<div class="col-md-8">'+
																					'<div class="input-group">'+
																							'<span class="input-group-addon">Rp</span>'+
																							'<input type="text" class="form-control" name="harga_pertablet[]" id="harga_pertablet_'+i+'" value="" required="required" onkeyup="FormatCurrency(this);" disabled="disabled">'+
																					'</div>'+
																			'</div>'+
																	'</div>'+
																	'<div class="form-group">'+
																			'<label class="col-md-2 control-label">Harga Beli</label>'+
																			'<div class="col-md-8">'+
																					'<div class="input-group">'+
																							'<span class="input-group-addon">Rp</span>'+
																							'<input type="text" class="form-control harga_beli" name="harga_beli[]" id="harga_beli_'+i+'" value="" required="required" onkeyup="FormatCurrency(this); hitung_harga_jual('+i+'); hitung_total_harga_beli();">'+
																					'</div>'+
																			'</div>'+
																	'</div>'+
																	'<div class="form-group">'+
																			'<label class="col-md-2 control-label">Harga Jual</label>'+
																			'<div class="col-md-8">'+
																					'<div class="input-group">'+
																							'<span class="input-group-addon">Rp</span>'+
																							'<input type="text" class="form-control" name="harga_jual[]" id="harga_jual_'+i+'" value="" required="required" onkeyup="FormatCurrency(this);" readonly>'+
																					'</div>'+
																			'</div>'+
																	'</div>'+
															'</div>'+
													'</div>'+
												'</div>'+
										'</div>'+
								'</div>'+
						'</div>'+
						'</td>'+
					'</tr>';

				$('#form_tambah_obat').append($menu_1);
				$('#number').val(i);
}

function get_nama_supplier(){
	$('#popup_nama_supplier').click();

    var keyword = $('#cari_nama_supplier').val();

    if(ajax){
        ajax.abort();
    }

    ajax = $.ajax({
        url : '<?php echo base_url(); ?>apotek/ap_gudang_obat_c/data_nama_supplier',
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

                    $tr += '<tr style="cursor:pointer;" onclick="klik_nama_supplier('+result[i].ID+');">'+
                                '<td style="text-align:center;">'+no+'</td>'+
                                '<td>'+result[i].KODE_SUPPLIER+'</td>'+
                                '<td>'+result[i].NAMA_SUPPLIER+'</td>'+
                                // '<td>'+result[i].MERK+'</td>'+
                            '</tr>';
                }
            }

            $('#tabel_nama_supplier tbody').html($tr);
        }
    });

    $('#cari_nama_supplier').off('keyup').keyup(function(){
        get_nama_supplier();;
    });
}

function klik_nama_supplier(id){
	$('#tutup_nama_supplier').click();

	$.ajax({
	url : '<?php echo base_url(); ?>apotek/ap_gudang_obat_c/klik_nama_supplier',
	data : {id:id},
	type : "POST",
	dataType : "json",
	success : function(row){
			var ket = $('#ket').val();

			if(ket == 'Tambah'){
					$('#id_supplier').val(row['ID']);
					$('#nama_supplier').val(row['NAMA_SUPPLIER']);
					// $('#id_merk').val(row['ID_MERK']);
					// $('#merk').val(row['MERK']);

			}else{
					$('#id_supplier_ubah').val(row['ID']);
					$('#nama_supplier_ubah').val(row['NAMA_SUPPLIER']);
					// $().val();
					// $('#id_merk_ubah').val(row['ID_MERK']);
					// $('#merk_ubah').val(row['MERK']);
			}
	}
	});
}

function hitung_harga_jual(number){
	var ket = $('#ket').val();

	if (ket == 'Tambah') {
		var harga_beli = $('#harga_beli_'+number).val();
		harga_beli = harga_beli.split(',').join('');

		if(harga_beli == ""){
				harga_beli = 0;
		}

		var hitung_ppn = parseFloat(harga_beli) * 0.1;
		var hitung_persen = parseFloat(harga_beli) * 0.05;

		var total_harga_jual = parseFloat(hitung_ppn) + parseFloat(hitung_persen) + parseFloat(harga_beli);
		var bulatan = Math.round(total_harga_jual);
		$('#harga_jual_'+number).val(NumberToMoney(bulatan));
	}else {
		var harga_beli = $('#harga_beli_ubah').val();
		harga_beli = harga_beli.split(',').join('');

		if(harga_beli == ""){
				harga_beli = 0;
		}

		var hitung_ppn = parseFloat(harga_beli) * 0.1;
		var hitung_persen = parseFloat(harga_beli) * 0.05;

		var total_harga_jual = parseFloat(hitung_ppn) + parseFloat(hitung_persen) + parseFloat(harga_beli);
		var bulatan = Math.round(total_harga_jual);
		$('#harga_jual_ubah').val(NumberToMoney(bulatan));
	}
}

function hitung_diskon(){
	var cek = $("input[name=status_obat_ubah]:checked").val();
	if (cek == '1') {
		var total = $('#total').val();
		total = total.split(',').join('');
		var diskon = $('#diskon').val();
		var hitung_diskon = diskon / 100;
		var hitung =  total - (total * hitung_diskon);

		// if (total == hitung) {
		// 	hitung = 0;
		// }

		$('#grand_total').val(hitung);
	}else if (cek == '2') {
		var total = $('#total').val();
		total = total.split(',').join('');
		var diskon = $('#diskon').val();
		var hitung = total - diskon;

		$('#grand_total').val(hitung);
	}
}

function hitung_total_harga_beli(){
	var tot = 0;
	$('.harga_beli').each(function(idx,elm){
			var total = elm.value;
			total = total.split(',').join('');
      tot += parseFloat(total);
  });

	$('#total').val(NumberToMoney(tot));
	$('#grand_total').val(NumberToMoney(tot));
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
												<th style="color:#fff; text-align:center;">Tanggal & Waktu Masuk</th>
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
					<input type="hidden" id="number" value="1">
            <hr/>
						<div class="form-group">
								<label class="col-md-1 control-label">Supplier</label>
								<div class="col-md-3">
									<div class="input-group">
											<input type="hidden" name="id_supplier" id="id_supplier" value="">
											<input type="text" class="form-control" id="nama_supplier" value="" required="required" readonly>
											<span class="input-group-btn">
													<button class="btn waves-effect waves-light btn-default" type="button" onclick="get_nama_supplier();">
															<i class="fa fa-search"></i>
													</button>
											</span>
									</div>
								</div>
						</div>
						<div class="form-group">
								<label class="col-md-1 control-label">No Faktur</label>
								<div class="col-md-3">
											<input type="text" name="no_faktur" class="form-control" id="no_faktur" value="" required="required">
								</div>
						</div>
						<table style="width: 100%;" id="form_tambah_obat">
							<tr class="number_real">
								<td>
									<div class="col-lg-12">
						              <div class="portlet">
						                  <div class="portlet-heading bg-default" style="background-color:#7ac142; color: white;">
						                      <h3 class="portlet-title">
						                          <i class="fa fa-medkit"></i> Obat <span id="number_span">1</span>
						                      </h3>
						                      <div class="portlet-widgets" style="color: white;">
						                          <a data-toggle="collapse" data-parent="#accordion1" class="collapsed" href="#bg-primary1" style="color: white;"><i class="zmdi zmdi-minus"></i></a>
						                      </div>
						                      <div class="clearfix"></div>
						                  </div>
						                  <div id="bg-primary1" class="panel-collapse collapse out">
						                      <div class="portlet-body">
																		<div class="row">
																				<div class="col-lg-6">
																						<div class="form-group">
																								<label class="col-md-2 control-label">Kode Obat</label>
																								<div class="col-md-8">
																										<div class="input-group">
																												<input type="hidden" name="id_nama_obat[]" id="id_nama_obat_1" value="">
																												<input type="text" class="form-control" id="kode_obat_1" value="" required="required" readonly>
																												<span class="input-group-btn">
																														<button class="btn waves-effect waves-light btn-default" type="button" onclick="get_nama_obat(1)">
																																<i class="fa fa-search"></i>
																														</button>
																												</span>
																										</div>
																								</div>
																						</div>
																						<div class="form-group">
																								<label class="col-md-2 control-label">Nama Obat</label>
																								<div class="col-md-8">
																										<input type="text" class="form-control" id="nama_obat_1" value="" readonly>
																								</div>
																						</div>
																						<div class="form-group">
																								<label class="col-md-2 control-label">Jenis Obat</label>
																								<div class="col-md-8">
																										<input type="text" class="form-control" id="jenis_obat_1" value="" readonly>
																								</div>
																						</div>
																						<div class="form-group">
																								<label class="col-md-2 control-label">Expired</label>
																								<div class="col-md-8">
																										<input type="text" class="form-control" id="expired_1" value="" readonly>
																								</div>
																						</div>
																						<div class="form-group">
																								<label class="col-md-2 control-label">Golongan Obat</label>
																								<div class="col-md-8">
																										<input type="text" class="form-control" id="golongan_1" value="" readonly>
																								</div>
																						</div>
																						<div class="form-group">
																								<label class="col-md-2 control-label">Kategori Obat</label>
																								<div class="col-md-8">
																										<input type="text" class="form-control" id="kategori_1" value="" readonly>
																								</div>
																						</div>
																						<div class="form-group">
																								<label class="col-md-2 control-label">Service</label>
																								<div class="col-md-8">
																										<input type="text" class="form-control" id="service_1" value="" readonly>
																								</div>
																						</div>
																				</div>

																				<div class="col-lg-6">
																						<div class="form-group">
																								<label class="col-md-2 control-label">Jumlah</label>
																								<div class="col-md-8">
																												<input type="text" class="form-control" name="jumlah[]" id="jumlah_1" value="" required="required" onkeyup="FormatCurrency(this); hitung_total(1);">
																								</div>
																						</div>
																						<div class="form-group">
																								<label class="col-md-2 control-label">Isi</label>
																								<div class="col-md-8">
																												<input type="text" class="form-control" name="isi[]" id="isi_1" value="" required="required" onkeyup="FormatCurrency(this); hitung_total(1);">
																								</div>
																						</div>
																						<div class="form-group">
																								<label class="col-md-2 control-label">Total</label>
																								<div class="col-md-8">
																										<input type="text" class="form-control" name="total[]" id="total_1" value="" readonly>
																								</div>
																						</div>
																						<div class="form-group">
																								<label class="col-md-2 control-label">Jumlah Isi</label>
																								<div class="col-md-8">
																												<input type="text" class="form-control" name="jumlah_butir[]" id="jumlah_butir_1" value="" required="required" onkeyup="FormatCurrency(this);">
																												<span class="help-block"><small>Blister / Tablet</small></span>
																								</div>
																						</div>
																						<div class="form-group">
																								<label class="col-sm-2 control-label">Per Tablet</label>
																								<div class="col-sm-6">
																										<div class="checkbox checkbox-primary">
																												<input id="check_tablet_1" type="checkbox" onclick="check_tablet(1);">
																												<label for="check_tablet"> Dijual pertablet atau tidak </label>
																										</div>
																								</div>
																						</div>
																						<div class="form-group view_tablet_grp harga_tablet_grp_1">
																								<label class="col-md-2 control-label">Harga Pertablet</label>
																								<div class="col-md-8">
																										<div class="input-group">
																												<span class="input-group-addon">Rp</span>
																												<input type="text" class="form-control" name="harga_pertablet[]" id="harga_pertablet_1" value="" required="required" onkeyup="FormatCurrency(this);" disabled="disabled">
																										</div>
																								</div>
																						</div>
																						<div class="form-group">
																								<label class="col-md-2 control-label">Harga Beli</label>
																								<div class="col-md-8">
																										<div class="input-group">
																												<span class="input-group-addon">Rp</span>
																												<input type="text" class="form-control harga_beli" name="harga_beli[]" id="harga_beli_1" value="" required="required" onkeyup="FormatCurrency(this); hitung_harga_jual(1); hitung_total_harga_beli();">
																										</div>
																								</div>
																						</div>
																						<div class="form-group">
																								<label class="col-md-2 control-label">Harga Jual</label>
																								<div class="col-md-8">
																										<div class="input-group">
																												<span class="input-group-addon">Rp</span>
																												<input type="text" class="form-control" name="harga_jual[]" id="harga_jual_1" value="" required="required" onkeyup="FormatCurrency(this);" readonly>
																										</div>
																								</div>
																						</div>
																				</div>
																		</div>
						                      </div>
						                  </div>
						              </div>
						          </div><!-- end col -->
								</td>
							</tr>
						</table>
						<div class="form-group">
								<label class="col-md-1 control-label">&nbsp;</label>
								<div class="col-md-8">
										<div class="radio radio-purple radio-inline">
												<input type="radio" name="status_obat_ubah" value="0" id="tanpa_diskon" checked>
												<label for="tanpa_diskon"> Tanpa Diskon </label>
										</div>
										<div class="radio radio-purple radio-inline">
												<input type="radio" name="status_obat_ubah" value="1" id="diskon_persen">
												<label for="diskon_persen"> Diskon Persen </label>
										</div>
										<div class="radio radio-purple radio-inline">
												<input type="radio" name="status_obat_ubah" value="2" id="diskon_harga">
												<label for="diskon_harga"> Diskon Harga </label>
										</div>
								</div>
						</div>
						<div class="row">
							<div class="col-md-12">
										<label class="col-md-1 control-label">Total</label>
										<div class="col-md-2">
													<input type="text" class="form-control" name="total_semua" id="total" value="" required="required" readonly>
										</div>
										<label class="col-md-1 control-label">Diskon</label>
										<div class="col-md-2">
													<input type="text" class="form-control" name="diskon" id="diskon" value="" onkeyup="hitung_diskon();" disabled="disabled">
										</div>
										<label class="col-md-1 control-label">Grand Total</label>
										<div class="col-md-2">
													<input type="text" class="form-control" name="grand_total" id="grand_total" value="" required="required" readonly>
										</div>
							</div>
						</div>
						<br>
						<hr>
            <center>
								<button type="button" class="btn btn-info waves-effect waves-light m-b-5" id="obat" onclick="tambah_obat();"> <i class="fa fa-plus"></i> <span>Tambah Obat</span> </button>
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
											<label class="col-md-2 control-label">Supplier</label>
											<div class="col-md-8">
													<div class="input-group">
															<input type="hidden" name="id_supplier_ubah" id="id_supplier_ubah" value="">
															<input type="text" class="form-control" id="nama_supplier_ubah" value="" required="required" readonly>
															<span class="input-group-btn">
																	<button class="btn waves-effect waves-light btn-default" type="button" onclick="get_nama_supplier();">
																			<i class="fa fa-search"></i>
																	</button>
															</span>
													</div>
											</div>
									</div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Kode Obat</label>
                        <div class="col-md-8">
                            <div class="input-group">
                                <input type="hidden" name="id_nama_obat_ubah" id="id_nama_obat_ubah" value="">
                                <input type="text" class="form-control" id="kode_obat_ubah" value="" required="required" readonly>
                                <span class="input-group-btn">
                                    <button class="btn waves-effect waves-light btn-default" type="button" onclick="get_nama_obat();">
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
														<input type="text" class="form-control" id="jenis_obat_ubah" value="" readonly>
												</div>
										</div>
										<div class="form-group">
												<label class="col-md-2 control-label">Expired</label>
												<div class="col-md-8">
														<input type="text" class="form-control" id="tanggal_expired_ubah" value="" readonly>
												</div>
										</div>
										<div class="form-group">
												<label class="col-md-2 control-label">Golongan Obat</label>
												<div class="col-md-8">
														<input type="text" class="form-control" id="golongan_ubah" value="" readonly>
												</div>
										</div>
										<div class="form-group">
												<label class="col-md-2 control-label">Kategori Obat</label>
												<div class="col-md-8">
														<input type="text" class="form-control" id="kategori_ubah" value="" readonly>
												</div>
										</div>
										<div class="form-group">
												<label class="col-md-2 control-label">Service</label>
												<div class="col-md-8">
														<input type="text" class="form-control" id="service_ubah" value="" readonly>
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
												<label class="col-sm-2 control-label">Per Tablet</label>
												<div class="col-sm-6">
														<div class="checkbox checkbox-primary">
																<input id="check_tablet_ubah" type="checkbox" onclick="check_tablet();">
																<label for="check_tablet"> Dijual pertablet atau tidak </label>
														</div>
												</div>
										</div>
										<div class="form-group view_tablet_grp">
												<label class="col-md-2 control-label">Harga Pertablet</label>
												<div class="col-md-8">
														<div class="input-group">
																<span class="input-group-addon">Rp</span>
																<input type="text" class="form-control" name="harga_pertablet_ubah" id="harga_pertablet_ubah" value="" required="required" onkeyup="FormatCurrency(this);" disabled="disabled">
														</div>
												</div>
										</div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Harga Beli</label>
                        <div class="col-md-8">
                            <div class="input-group">
                                <span class="input-group-addon">Rp</span>
                                <input type="text" class="form-control" name="harga_beli_ubah" id="harga_beli_ubah" value="" required="required" onkeyup="FormatCurrency(this); hitung_harga_jual();">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Harga Jual</label>
                        <div class="col-md-8">
                            <div class="input-group">
                                <span class="input-group-addon">Rp</span>
                                <input type="text" class="form-control" name="harga_jual_ubah" id="harga_jual_ubah" value="" required="required" onkeyup="FormatCurrency(this);" readonly>
                            </div>
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
										<div class="input-group m-t-10">
											<span class="input-group-btn">
                      <button type="button" class="btn waves-effect waves-light btn-primary">Service</button>
                      </span>
                      <input type="text" id="service_detail" name="example-input2-group2" class="form-control" disabled>
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
                      <button type="button" class="btn waves-effect waves-light btn-primary">Harga Pertablet</button>
                      </span>
                      <input type="text" id="harga_pertablet_detail" name="example-input2-group2" class="form-control" disabled>
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

<button class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#myModal8" id="popup_nama_supplier" style="display:none;">Standard Modal</button>
<div id="myModal8" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Data Nama Supplier</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form">
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="input-group">
                                <input type="text" class="form-control" id="cari_nama_supplier" placeholder="Cari..." value="">
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
                        <table class="table table-hover" id="tabel_nama_supplier">
                            <thead>
                                <tr class="merah_popup">
                                    <th style="text-align:center; color: #fff;" width="50">No</th>
                                    <th style="text-align:center; color: #fff;">Kode Supplier</th>
                                    <th style="text-align:center; color: #fff;">Nama Supplier</th>
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
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_nama_supplier">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type="text/javascript" src="<?php echo base_url(); ?>js-devan/jquery-1.11.1.min.js"></script>

<style type="text/css">
#view_tambah, #view_ubah, #view_merk, #tombol_reset, #msg_barcode, #select_jadi, #select_racik, #select_jadi_ubah, #select_racik_ubah, #view_status_obat{
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

	data_obat();

	$("#jumlah_tampil").change(function(){
    	data_obat();
    });

	$('#btn_tambah').click(function(){
		$('#view_tambah').show();
		$('#view_data').hide();
		$('#ket').val('Tambah');
		get_kode_obat();
	});

	$('#batal').click(function(){
		// window.location = "<?php echo base_url(); ?>apotek/admum_setup_obat_c";
        $('#view_tambah').hide();
        $('#view_data').show();
				$('#ket').val("");
				$('#select_racik').hide();
				$('#select_jadi').hide();
	});

	// $('#merk').click(function(){
	// 	$('#ket').val('Tambah');
	// 	$('#popup_merk').click();
	// 	get_merk();
	// });
	//
	// $('#btn_merk').click(function(){
	// 	$('#ket').val('Tambah');
	// 	$('#popup_merk').click();
	// 	get_merk();
	// });
	//
	// $('#checkbox2').click(function(){
	// 	var cek = $('#checkbox2').is(":checked");
	// 	if(cek == true){
	// 		$('#view_merk').show();
	// 	}else{
	// 		$('#view_merk').hide();
	// 		$('#id_merk_ubah').val("");
	// 		$('#merk_ubah').val("");
	// 	}
	// });
	//
	// $('#merk_ubah').click(function(){
	// 	$('#ket').val('Ubah');
	// 	$('#popup_merk').click();
	// 	get_merk();
	// });
	//
	// $('#btn_merk_ubah').click(function(){
	// 	$('#ket').val('Ubah');
	// 	$('#popup_merk').click();
	// 	get_merk();
	// });
	$('.btn_jenis').click(function(){
		$('#popup_jenis').click();
		get_jenis_obat();
	});

	$('.btn_golongan').click(function(){
		$('#popup_golongan').click();
	});

	$('.btn_kategori').click(function(){
		$('#popup_kategori').click();
	});

	$('#obat_racik').click(function(){
		var robat_racik = $('input[name="status_obat"]:checked').val();
		if (robat_racik == '1') {
			$('#select_racik').show();
			$('#select_jadi').hide();
		}else {
			$('#select_racik').hide();
			$('#select_jadi').hide();
		}
	});

	$('#obat_jadi').click(function(){
		var robat_jadi = $('input[name="status_obat"]:checked').val();
		if (robat_jadi == '0') {
			$('#select_jadi').show();
			$('#select_racik').hide();
		}else {
			$('#select_jadi').hide();
			$('#select_racik').hide();
		}
	});

	$('#obat_racik_ubah').click(function(){
		var robat_racik_ubah = $('input[name="status_obat_ubah"]:checked').val();
		if (robat_racik_ubah == '1') {
			$('#select_racik_ubah').show();
			$('#select_jadi_ubah').hide();
		}else {
			$('#select_racik_ubah').hide();
			$('#select_jadi_ubah').hide();
		}
	});

	$('#obat_jadi_ubah').click(function(){
		var robat_jadi_ubah = $('input[name="status_obat_ubah"]:checked').val();
		if (robat_jadi_ubah == '0') {
			$('#select_jadi_ubah').show();
			$('#select_racik_ubah').hide();
		}else {
			$('#select_jadi_ubah').hide();
			$('#select_racik_ubah').hide();
		}
	});

	$("#checkbox2").click(function(){
			var cek = $('#checkbox2').is(":checked");
			if(cek == true){
					$('#view_status_obat').show();
					$('.clas_service').removeAttr('disabled');
			}else{
					$('#view_status_obat').hide();
					$('.clas_service').attr('disabled','disabled');
			}
	});

});

var ajax = "";

function get_kode_obat(){
	$.ajax({
        url : '<?php echo base_url(); ?>apotek/admum_setup_obat_c/kode_obat',
        type : "POST",
        dataType : "json",
        success : function(kode){
            $('#kode_obat').val(kode);
        }
    });
}

// //MERK OBAT
//
// function get_merk(){
// 	var keyword = $('#cari_merk').val();
//
// 	if(ajax){
// 		ajax.abort();
// 	}
//
// 	ajax = $.ajax({
//         url : '<?php //echo base_url(); ?>apotek/admum_setup_obat_c/data_merk',
//         data : {keyword:keyword},
//         type : "GET",
//         dataType : "json",
//         success : function(result){
//             $tr = "";
//
//             if(result == "" || result == null){
//             	$tr = "<tr><td colspan='2' style='text-align:center;'><b>Merk yang dicari tidak ada</b></td></tr>";
//             }else{
// 	            var no = 0;
// 	            for(var i=0; i<result.length; i++){
// 	            	no++;
//
// 	            	$tr += '<tr style="cursor:pointer;" onclick="klik_merk('+result[i].ID+');">'+
// 	                        '    <td style="text-align:center;">'+no+'</td>'+
// 	                        '    <td>'+result[i].MERK+'</td>'+
// 	                        '</tr>';
// 	            }
//             }
//
//             $('#tabel_merk tbody').html($tr);
//         }
//     });
//
//     $('#cari_merk').off('keyup').keyup(function(){
//     	get_merk();
//     });
// }
//
// function klik_merk(id_merk){
// 	$('#tutup_merk').click();
//
// 	$.ajax({
// 		url : '<?php //echo base_url(); ?>apotek/admum_setup_obat_c/klik_merk',
// 		data : {id_merk:id_merk},
// 		type : "POST",
// 		dataType : "json",
// 		success : function(row){
// 			var ket = $('#ket').val();
//
// 			if(ket == 'Tambah'){
// 				$('#id_merk').val(id_merk);
// 				$('#merk').val(row['MERK']);
// 				$('#id_merk_ubah').val("");
// 				$('#merk_ubah').val("");
// 			}else{
// 				$('#id_merk').val("");
// 				$('#merk').val("");
// 				$('#id_merk_ubah').val(id_merk);
// 				$('#merk_ubah').val(row['MERK']);
// 			}
// 		}
// 	});
// }
//
// //-------------

function get_jenis_obat(){
	var keyword = $('#cari_jenis').val();

	if(ajax){
		ajax.abort();
	}

	ajax = $.ajax({
        url : '<?php echo base_url(); ?>apotek/admum_setup_obat_c/data_jenis_obat',
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
		url : '<?php echo base_url(); ?>apotek/admum_setup_obat_c/klik_jenis',
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
		 	$('#id_golongan').val(golongan[no_golongan]);
 			$('#golongan').val(golongan[no_golongan]);
 	}else{
			$('#id_golongan_ubah').val(golongan[no_golongan]);
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
		 	$('#id_kategori').val(kategori[no_kategori]);
 			$('#kategori').val(kategori[no_kategori]);
 	}else{
			$('#id_kategori_ubah').val(kategori[no_kategori]);
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

	if(ajax){
		ajax.abort();
	}

	ajax = $.ajax({
		url : '<?php echo base_url(); ?>apotek/admum_setup_obat_c/get_data_obat',
        data : {keyword:keyword},
        type : "GET",
        dataType : "json",
        success : function(result){
        	$tr = "";

        	if(result == "" || result == null){
        		$tr = "<tr><td colspan='6' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
        	}else{
        		var no = 0;

        		for(var i=0; i<result.length; i++){
        			no++;

        			var aksi =  '<button type="button" class="btn btn-success waves-effect waves-light btn-sm m-b-5" onclick="ubah_obat('+result[i].ID+');">'+
									'<i class="fa fa-pencil"></i>'+
								'</button>&nbsp;'+
						   		'<button type="button" class="btn btn-danger waves-effect waves-light btn-sm m-b-5" onclick="hapus_obat('+result[i].ID+');">'+
						   			'<i class="fa fa-trash"></i>'+
						   		'</button>';

        			$tr += "<tr>"+
        						"<td style='vertical-align:middle; text-align:center;'>"+no+"</td>"+
        						"<td style='vertical-align:middle; text-align:center;'>"+result[i].KODE_OBAT+"</td>"+
        						"<td style='vertical-align:middle;'>"+result[i].BARCODE+"</td>"+
        						"<td style='vertical-align:middle;'>"+result[i].NAMA_OBAT+"</td>"+
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

function ubah_obat(id){
	$('#view_ubah').show();
	$('#view_data').hide();
	$('#ket').val('Ubah');

	$.ajax({
		url : '<?php echo base_url(); ?>apotek/admum_setup_obat_c/data_obat_id',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(row){
			$('#id_ubah').val(id);
			$('#kode_obat_ubah').val(row['KODE_OBAT']);
			$('#barcode_ubah').val(row['BARCODE']);
			$('#nama_obat_ubah').val(row['NAMA_OBAT']);
			$('#id_jenis_ubah').val(row['ID_JENIS_OBAT']);
			$('#jenis_obat_ubah').val(row['NAMA_JENIS']);
			var status_obat = "";
			if(row['STATUS_OBAT'] == 0){
					status_obat = "Obat Jadi";
			}else{
					status_obat = "Obat Racik";
			}
			$('#status_obat_hidden').val(row['STATUS_OBAT']);
			$('#status_obat_txt').val(status_obat);
			$('#tanggal_expired_ubah').val(row['EXPIRED']);
			$('#id_golongan_ubah').val(row['GOLONGAN_OBAT']);
			$('#golongan_ubah').val(row['GOLONGAN_OBAT']);
			$('#id_kategori_ubah').val(row['KATEGORI_OBAT']);
			$('#kategori_ubah').val(row['KATEGORI_OBAT']);
			$('#service_hidden').val(row['SERVICE']);
			// $('#id_merk_lama').val(row['ID_MERK']);
			// $('#merk_txt').val(row['MERK']);
		}
	});

	$('#batal_ubah').click(function(){
		$('#view_ubah').hide();
		$('#view_data').show();
		$('#ket').val("");
		$('#select_racik_ubah').hide();
		$('#select_jadi_ubah').hide();
	});
}

function hapus_obat(id){
	$('#popup_hps').click();

	$.ajax({
		url : '<?php echo base_url(); ?>apotek/admum_setup_obat_c/data_obat_id',
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

function cek_barcode(){
    var barcode = $('#barcode').val();
    $.ajax({
        url : '<?php echo base_url(); ?>apotek/admum_setup_obat_c/cek_barcode',
        data : {barcode:barcode},
        type : "POST",
        dataType : "json",
        success : function(row){
            if(row['TOTAL'] != 0){
                $('#msg_barcode').show();
                $('#btn_simpan').attr('disabled','disabled');
            }else{
                $('#msg_barcode').hide();
                $('#btn_simpan').removeAttr('disabled');
            }
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
    	<form class="form-horizontal" role="form">
            <div class="form-group">
            	<div class="col-md-7">
        			<button type="button" class="btn btn-purple waves-effect w-md waves-light" id="btn_tambah">
        				<i class="fa fa-plus"></i> Tambah Obat
        			</button>
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
        </form>
    	<div class="table-responsive">
            <table id="tabel_obat" class="table table-striped table-bordered">
                <thead>
                    <tr class="biru">
                        <th style="color:#fff; text-align:center;" width="50">No</th>
                        <th style="color:#fff; text-align:center;">Kode Obat</th>
                        <th style="color:#fff; text-align:center;">Barcode</th>
                        <th style="color:#fff; text-align:center;">Nama Obat</th>
                        <!-- <th style="color:#fff; text-align:center;">Merk</th> -->
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
        </form>
    </div>

    <div class="card-box card-tabs" id="view_tambah">
    	<h4 class="header-title m-t-0 m-b-30">Tambah Obat</h4>
    	<form class="form-horizontal" role="form" action="<?php echo $url_simpan; ?>" method="post">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
                <label class="col-md-2 control-label">Kode Obat</label>
                <div class="col-md-8">
                    <input type="text" class="form-control" name="kode_obat" id="kode_obat" value="" readonly>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Barcode</label>
                <div class="col-md-8">
                    <input type="text" class="form-control" name="barcode" id="barcode" value="" required="required" onchange="cek_barcode();">
                    <span class="help-block" style="color:#ff0000;" id="msg_barcode">
                        <small>Barcode ini sudah ada.</small>
                    </span>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Nama Obat</label>
                <div class="col-md-8">
                    <input type="text" class="form-control" name="nama_obat" value="" required="required">
                </div>
            </div>
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
						<div class="form-group">
								<label class="col-md-2 control-label">Expired</label>
								<div class="col-md-8">
										<div class="input-group">
												<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
												<input type="text" class="form-control datepicker-here" name="tanggal_expired" id="tanggal_expired" value="" required="required" data-language="en" data-date-format="dd-mm-yyyy">
										</div>
								</div>
						</div>
					</div>
					<div class="col-md-6">
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
						<div class="form-group">
								<label class="col-md-2 control-label">&nbsp;</label>
								<div class="col-md-8">
										<div class="radio radio-purple radio-inline">
												<input type="radio" name="status_obat" value="1" id="obat_racik">
												<label for="obat_racik"> Obat Racik </label>
										</div>
										<div class="radio radio-purple radio-inline">
												<input type="radio" name="status_obat" value="0" id="obat_jadi">
												<label for="obat_jadi"> Obat Jadi </label>
										</div>
								</div>
						</div>
						<div class="form-group" id="select_racik">
                <label class="col-md-2 control-label">Service Racik</label>
                <div class="col-md-8">
                    <select class="form-control" name="service">
											<option value="100">100</option>
                    </select>
                </div>
            </div>
						<div class="form-group" id="select_jadi">
								<label class="col-md-2 control-label">Service Jadi</label>
								<div class="col-md-8">
										<select class="form-control" name="service">
											<option value="100">100</option>
											<option value="500">500</option>
										</select>
								</div>
						</div>
					</div>
				</div>

            <!-- <div class="form-group">
                <label class="col-md-2 control-label">Merk</label>
                <div class="col-md-4">
                    <div class="input-group">
                    	<input type="hidden" name="id_merk" id="id_merk" value="">
                        <input type="text" class="form-control" name="merk" id="merk" value="" required="required" readonly>
                        <span class="input-group-btn">
                        	<button class="btn waves-effect waves-light btn-default" type="button" id="btn_merk">
                        		<i class="fa fa-search"></i>
                        	</button>
                        </span>
                    </div>
                </div>
            </div> -->
						<hr>
            <center>
                	<button type="submit" class="btn btn-success waves-effect waves-light m-b-5" id="btn_simpan"> <i class="fa fa-save"></i> <span>Simpan</span> </button>
                	<button type="button" class="btn btn-danger waves-effect waves-light m-b-5" id="batal"> <i class="fa fa-times"></i> <span>Batal</span> </button>
            </center>
        </form>
    </div>

    <div class="card-box card-tabs" id="view_ubah">
    	<form class="form-horizontal" role="form" action="<?php echo $url_ubah; ?>" method="post">
    		<input type="hidden" name="id_ubah" id="id_ubah" value="">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
                <label class="col-md-2 control-label">Kode Obat</label>
                <div class="col-md-8">
                    <input type="text" class="form-control" name="kode_obat_ubah" id="kode_obat_ubah" value="" readonly>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Barcode</label>
                <div class="col-md-8">
                    <input type="text" class="form-control" name="barcode_ubah" id="barcode_ubah" value="">
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Nama Obat</label>
                <div class="col-md-8">
                    <input type="text" class="form-control" name="nama_obat_ubah" id="nama_obat_ubah" value="">
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
					</div>
					<div class="col-md-6">
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
												<input type="radio" name="status_obat_ubah" value="1" id="obat_racik_ubah">
												<label for="obat_racik_ubah"> Obat Racik </label>
										</div>
										<div class="radio radio-purple radio-inline">
												<input type="radio" name="status_obat_ubah" value="0" id="obat_jadi_ubah">
												<label for="obat_jadi_ubah"> Obat Jadi </label>
										</div>
								</div>
						</div>
						<input type="hidden" name="service_hidden" id="service_hidden" value="">
						<div class="form-group" id="select_racik_ubah">
								<label class="col-md-2 control-label">Service Racik</label>
								<div class="col-md-8">
										<select class="form-control" name="service_ubah">
											<option value="100" class="clas_service" disabled="disabled">100</option>
										</select>
								</div>
						</div>
						<div class="form-group" id="select_jadi_ubah">
								<label class="col-md-2 control-label">Service Jadi</label>
								<div class="col-md-8">
										<select class="form-control" name="service_ubah">
											<option value="100" class="clas_service" disabled="disabled">100</option>
											<option value="500" class="clas_service" disabled="disabled">500</option>
										</select>
								</div>
						</div>
					</div>
				</div>
            <!-- <div class="form-group">
                <label class="col-md-2 control-label">Merk</label>
                <div class="col-md-4">
                	<input type="hidden" class="form-control" name="id_merk_lama" id="id_merk_lama" value="" readonly>
                    <input type="text" class="form-control" id="merk_txt" value="" readonly>
                </div>
                <div class="col-md-2">
                	<div class="checkbox checkbox-primary">
                        <input type="checkbox" id="checkbox2">
                        <label for="checkbox2">
                            Ubah
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group" id="view_merk">
                <label class="col-md-2 control-label">&nbsp;</label>
                <div class="col-md-4">
                    <div class="input-group">
                    	<input type="hidden" name="id_merk_ubah" id="id_merk_ubah" value="">
                        <input type="text" class="form-control" name="merk_ubah" id="merk_ubah" value="" required="required" readonly>
                        <span class="input-group-btn">
                        	<button class="btn waves-effect waves-light btn-default" type="button" id="btn_merk_ubah">
                        		<i class="fa fa-search"></i>
                        	</button>
                        </span>
                    </div>
                </div>
            </div> -->
						<hr>
            <center>
                	<button type="submit" class="btn btn-success waves-effect waves-light m-b-5"> <i class="fa fa-save"></i> <span>Simpan</span> </button>
                	<button type="button" class="btn btn-danger waves-effect waves-light m-b-5" id="batal_ubah"> <i class="fa fa-times"></i> <span>Batal</span> </button>
            </center>
        </form>
    </div>
</div>

<!-- <button class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#myModal" id="popup_merk" style="display:none;">Standard Modal</button>
<div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Data Merk Obat</h4>
            </div>
            <div class="modal-body">
            	<form class="form-horizontal" role="form">
		            <div class="form-group">
		                <div class="col-md-12">
			                <div class="input-group">
			                    <input type="text" class="form-control" id="cari_merk" placeholder="Cari merk..." value="">
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
	                <table class="table table-hover" id="tabel_merk">
	                    <thead>
	                        <tr class="merah_popup">
	                            <th style="text-align:center; color: #fff;" width="50">No</th>
	                            <th style="text-align:center; color: #fff;">Merk Obat</th>
	                        </tr>
	                    </thead>
	                    <tbody>

	                    </tbody>
	                </table>
            	</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_merk">Tutup</button>
            </div>
        </div>
    </div>
</div> -->

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
		                            <th style="text-align:center; color: #fff;">Jenis Kategori</th>
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

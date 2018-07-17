<script type="text/javascript" src="<?php echo base_url(); ?>js-devan/jquery-1.11.1.min.js"></script>

<style type="text/css">
#tombol_reset, #view_tambah, #view_detail, #view_ubah{
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
    <?php }else if($this->session->flashdata('tidak_hapus')){ ?>
    	$('#popup_tidak_hapus').click();
    <?php } ?>

    data_kamar();

	$('#btn_tambah').click(function(){
		get_kode_kamar();
		$('#view_tambah').show();
		$('#view_data').hide();
	});

	$('#batal').click(function(){
		window.location = "<?php echo base_url(); ?>admum/admum_setup_kamar_jenazah_c";
	});

	$('#jumlah_tampil').change(function(){
		data_kamar();
	});

	$("input[name='urutkan']").click(function(){
		data_kamar();
	});

	$('#btn_hapus_lemari').click(function(){
		var id_hapus_lemari = $('#id_hapus_lemari').val();
		var id_kamar_jenazah = $('#id_kamar_jenazah').val();
		var id = $('#id_kamar_jenazah').val();

		$.ajax({
			url : '<?php echo base_url(); ?>admum/admum_setup_kamar_jenazah_c/hapus_lemari',
			data : {
				id_hapus_lemari:id_hapus_lemari,
				id_kamar_jenazah:id_kamar_jenazah
			},
			type : "POST",
			dataType : "json",
			success : function(result){
				notif_hapus();
				$('#popup_hapus_lemari').hide();
				data_kamar();

				$.ajax({
					url : '<?php echo base_url(); ?>admum/admum_setup_kamar_jenazah_c/data_lemari',
					data : {id:id},
					type : "POST",
					dataType : "json",
					success : function(result){
						$('#kode_kamar_detail').val(result['detail']['KODE_KAMAR']);
						$('#nama_kamar_detail').val(result['detail']['NAMA_KAMAR']);
						$('#biaya_detail').val(formatNumber(result['detail']['BIAYA']));
						$('#jumlah_bed_detail').val(formatNumber(result['detail']['JUMLAH_LEMARI']));

						$div = "";

						for(var i=0; i<result['lemari'].length; i++){
							var stt_pakai = "";
							var btn_hapus = "";

							if(result['lemari'][i].STATUS_PAKAI == 0){
								stt_pakai = '<small><b>Status</b></small> : <small class="text-success"><b>Kosong</b></small>';
							}else{
								stt_pakai = '<small><b>Status</b></small> : <small class="text-danger"><b>Terpakai</b></small>';
							}

							if(result['lemari'][i].STATUS_HAPUS == 1){
								btn_hapus = '<button type="button" class="btn btn-danger waves-effect" onclick="hapus_lemari('+result['lemari'][i].ID+','+id+');"><i class="fa fa-times"></i> Hapus</button>';
							}else{
								btn_hapus = '';
							}

							$div += '<div class="col-lg-3">'+
								    '    <div class="card-box widget-user">'+
								    '        <div>'+
								    '            <img alt="user" class="img-responsive img-circle" src="<?php echo base_url(); ?>picture/admum/file-cabinet-hi.png">'+
								    '            <div class="wid-u-info">'+
								    '                <h4 class="m-t-0 m-b-5">'+result['lemari'][i].NOMOR_LEMARI+'</h4>'+
								    				 stt_pakai+'<br/>'+
								    				 btn_hapus+
								    '            </div>'+
								    '        </div>'+
								    '    </div>'+
								    '</div>';
						}

						$('#view_detail_bed').html($div);
					}
				});
			}
		});
	});
});

function get_kode_kamar(){
	$.ajax({
		url : '<?php echo base_url(); ?>admum/admum_setup_kamar_jenazah_c/kode_kamar',
		type : "POST",
		dataType : "json",
		success : function(kode){
			$('#kode_kamar').val(kode);
		}
	});
}

function paging($selector){
	var jumlah_tampil = $('#jumlah_tampil').val();

    if(typeof $selector == 'undefined')
    {
        $selector = $("#tabel_jenazah tbody tr");
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

function data_kamar(){
	$('#popup_load').show();
	var keyword = $('#cari_kamar').val();
	var urutkan = $("input[name='urutkan']:checked").val();

	if(ajax){
		ajax.abort();
	}

	ajax = $.ajax({
		url : '<?php echo base_url(); ?>admum/admum_setup_kamar_jenazah_c/data_kamar_jenazah',
		data : {
			keyword:keyword,
			urutkan:urutkan
		},
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

					var aksi = "";

					if(result[i].TOTAL != 0){
						aksi =  '<button type="button" class="btn btn-info waves-effect waves-light btn-sm m-b-5" onclick="detail_lemari('+result[i].ID+');">'+
									'<i class="fa fa-eye"></i>'+
								'</button>&nbsp;'+
								'<button type="button" class="btn btn-success waves-effect waves-light btn-sm m-b-5" onclick="ubah_kamar('+result[i].ID+');">'+
									'<i class="fa fa-pencil"></i>'+
								'</button>&nbsp;'+
						   		'<button type="button" class="btn btn-danger waves-effect waves-light btn-sm m-b-5" onclick="hapus_kamar('+result[i].ID+');">'+
						   			'<i class="fa fa-trash"></i>'+
						   		'</button>';
					}else{
						aksi =  '<button type="button" class="btn btn-pink waves-effect waves-light btn-sm m-b-5" onclick="tambah_lemari('+result[i].ID+');">'+
									'<i class="fa fa-bed"></i>'+
								'</button>&nbsp;'+
								'<button type="button" class="btn btn-success waves-effect waves-light btn-sm m-b-5" onclick="ubah_kamar('+result[i].ID+');">'+
									'<i class="fa fa-pencil"></i>'+
								'</button>&nbsp;'+
						   		'<button type="button" class="btn btn-danger waves-effect waves-light btn-sm m-b-5" onclick="hapus_kamar('+result[i].ID+');">'+
						   			'<i class="fa fa-trash"></i>'+
						   		'</button>';
					}

					$tr += "<tr>"+
								"<td style='vertical-align:middle; text-align:center;'>"+no+"</td>"+
								"<td style='vertical-align:middle; text-align:center;'>"+result[i].KODE_KAMAR+"</td>"+
								"<td style='vertical-align:middle;'>"+result[i].NAMA_KAMAR+"</td>"+
								"<td style='vertical-align:middle; text-align:right;'>"+formatNumber(result[i].BIAYA)+"</td>"+
								"<td style='vertical-align:middle; text-align:center;'>"+formatNumber(result[i].JUMLAH_LEMARI)+"</td>"+
								"<td align='center'>"+aksi+"</td>"+
							"</tr>";
				}
			}

			$('#tabel_jenazah tbody').html($tr);
			paging();
			$('#popup_load').fadeOut();
		}
	});

	$('#tombol_cari').click(function(){
		data_kamar();
		$('#tombol_reset').show();
		$('#tombol_cari').hide();
	});

	$('#tombol_reset').click(function(){
		$('#cari_kamar').val("");
		data_kamar();
		$('#tombol_reset').hide();
		$('#tombol_cari').show();
	});
}

function onEnterText(e){
    if (e.keyCode == 13) {
        data_kamar();
        $('#tombol_reset').show();
		$('#tombol_cari').hide();
        return false;
    }
}

function tambah_lemari(id){
	$('#popup_bed').click();

	$.ajax({
		url : "<?php echo base_url(); ?>admum/admum_setup_kamar_jenazah_c/data_kamar_jenazah_id",
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(row){
			$('#id_kamar').val(id);
			var nama_kamar = row['NAMA_KAMAR'];
			var judul = "Jumlah Bed Kamar "+nama_kamar;
			$('#myModalLabel').html(judul);

			var jumlah_bed = row['JUMLAH_LEMARI'];

			$tr = "";
			var no = 0;

			for(var i=0; i<parseInt(jumlah_bed); i++){
				no++;

				var nomor = nama_kamar+"-"+no;

				$tr += "<tr>"+
							"<input type='hidden' name='no[]' value='"+no+"'>"+
							"<input type='hidden' name='nomor_lemari[]' value='"+nomor+"'>"+
							"<input type='hidden' name='jumlah[]' value='1'>"+
							"<td style='text-align:center;'>"+nomor+"</td>"+
							"<td style='text-align:center;'>1</td>"+
						"</tr>";
			}

			$('#tabel_lemari tbody').html($tr);
		}
	});
}

function detail_lemari(id){
	$('#popup_detail').click();

	$.ajax({
		url : '<?php echo base_url(); ?>admum/admum_setup_kamar_jenazah_c/data_lemari',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(result){
			$('#kode_kamar_detail').val(result['detail']['KODE_KAMAR']);
			$('#nama_kamar_detail').val(result['detail']['NAMA_KAMAR']);
			$('#biaya_detail').val(formatNumber(result['detail']['BIAYA']));
			$('#jumlah_bed_detail').val(formatNumber(result['detail']['JUMLAH_LEMARI']));

			$div = "";

			for(var i=0; i<result['lemari'].length; i++){
				var stt_pakai = "";
				var btn_hapus = "";

				if(result['lemari'][i].STATUS_PAKAI == 0){
					stt_pakai = '<small><b>Status</b></small> : <small class="text-success"><b>Kosong</b></small>';
				}else{
					stt_pakai = '<small><b>Status</b></small> : <small class="text-danger"><b>Terpakai</b></small>';
				}

				if(result['lemari'][i].STATUS_HAPUS == 1){
					btn_hapus = '<button type="button" class="btn btn-danger waves-effect" onclick="hapus_lemari('+result['lemari'][i].ID+','+id+');"><i class="fa fa-times"></i> Hapus</button>';
				}else{
					btn_hapus = '';
				}

				$div += '<div class="col-lg-3">'+
					    '    <div class="card-box widget-user">'+
					    '        <div>'+
					    '            <img alt="user" class="img-responsive img-circle" src="<?php echo base_url(); ?>picture/admum/file-cabinet-hi.png">'+
					    '            <div class="wid-u-info">'+
					    '                <h4 class="m-t-0 m-b-5">'+result['lemari'][i].NOMOR_LEMARI+'</h4>'+
					    				 stt_pakai+'<br/>'+
					    				 btn_hapus+
					    '            </div>'+
					    '        </div>'+
					    '    </div>'+
					    '</div>';
			}

			$('#view_detail_bed').html($div);
		}
	});
}

function ubah_kamar(id){
	$('#view_ubah').show();
	$('#view_data').hide();

	$.ajax({
		url : '<?php echo base_url(); ?>admum/admum_setup_kamar_jenazah_c/data_kamar_jenazah_id',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(row){
			$('#id_ubah').val(id);
			$('#kode_kamar_ubah').val(row['KODE_KAMAR']);
			$('#nama_kamar_ubah').val(row['NAMA_KAMAR']);
			$('#biaya_ubah').val(NumberToMoney(row['BIAYA']));
			$('#jumlah_lemari_ubah').val(NumberToMoney(row['JUMLAH_LEMARI']));

			if(row['TOTAL'] != 0){
				$('#jumlah_lemari_ubah').attr('readonly','readonly');
			}else{
				$('#jumlah_lemari_ubah').removeAttr('readonly');
			}
		}
	});

	$('#batal_ubah').click(function(){
		$('#view_ubah').hide();
		$('#view_data').show();
	});
}

function hapus_kamar(id){
	$('#popup_hps').click();

	$.ajax({
		url : '<?php echo base_url(); ?>admum/admum_setup_kamar_jenazah_c/data_kamar_jenazah_id',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(row){
			$('#id_hapus').val(id);
			var txt = row['KODE_KAMAR']+' - '+row['NAMA_KAMAR'];
			$('#msg').html('Apakah kamar <b>'+txt+'</b> ingin dihapus?');
		}
	});
}

function hapus_lemari(id,id_kamar){
	$('#popup_hapus_lemari').show();

	$.ajax({
		url : '<?php echo base_url(); ?>admum/admum_setup_kamar_jenazah_c/data_lemari_id',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(row){
			$('#id_kamar_jenazah').val(id_kamar);
			$('#id_hapus_lemari').val(id);
			var txt = row['NOMOR_LEMARI'];
			$('#msg_bed').html('Apakah lemari <b>'+txt+'</b> ingin dihapus?');
		}
	});

	$('#tutup').click(function(){
		$('#popup_hapus_lemari').fadeOut();
	});
}
</script>

<div id="popup_load">
    <div class="window_load">
        <img src="<?=base_url()?>picture/progress.gif" height="100" width="125">
    </div>
</div>

<div class="col-lg-12" id="view_data">
    <div class="card-box card-tabs">
    	<div class="row">
            <div class="col-md-12">
            	<form class="form-horizontal" role="form" action="<?php echo $url_cetak; ?>" target="_blank" method="post">
		            <div class="form-group">
            			<div class="col-md-12">
			                <button type="button" class="btn waves-effect waves-light btn-purple" id="btn_tambah">
	                    		<i class="fa fa-plus"></i> Tambah
	                    	</button>
		                </div>
		            </div>
		            <div class="form-group">
		            	<label class="col-md-1 control-label" style="text-align:left;">Urutkan</label>
            			<div class="col-md-6">
                			<div class="radio radio-success radio-inline">
                                <input type="radio" name="urutkan" value="Default" id="default" checked="checked">
                                <label for="default"> Default </label>
                            </div>
                            <div class="radio radio-success radio-inline">
                                <input type="radio" name="urutkan" value="Kode Kamar">
                                <label for="jenis"> Kode Kamar </label>
                            </div>
                			<div class="radio radio-success radio-inline">
                                <input type="radio" name="urutkan" value="Nama Kamar">
                                <label for="nama_poli"> Nama Kamar </label>
                            </div>
            			</div>
		                <div class="col-md-5">
		                	<button style="float: left; margin-right: 10px; margin-top: 2px;" type="submit" class="btn btn-success waves-effect w-md waves-light m-b-5"><i class="fa fa-file-text-o"></i> <b>Cetak Excel</b></button>
			                <div class="input-group">
			                    <input type="text" class="form-control" id="cari_kamar" placeholder="Cari..." value="" onkeypress="return onEnterText(event);">
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
		            <table id="tabel_jenazah" class="table table-striped table-bordered">
		                <thead>
		                    <tr class="biru">
		                        <th style="color:#fff; text-align:center;" width="50">No</th>
		                        <th style="color:#fff; text-align:center;">Kode Kamar</th>
		                        <th style="color:#fff; text-align:center;">Nama Kamar</th>
		                        <th style="color:#fff; text-align:center;">Biaya</th>
		                        <th style="color:#fff; text-align:center;">Jumlah Lemari</th>
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
        </div>
    </div>
</div>

<div class="col-lg-12" id="view_tambah">
    <div class="card-box card-tabs">
    	<h4 class="header-title m-t-0 m-b-30">Tambah Kamar</h4>
		<hr/>
    	<form class="form-horizontal" role="form" action="<?php echo $url_simpan; ?>" method="post">
        	<div class="form-group">
                <label class="col-md-2 control-label">Kode Kamar</label>
                <div class="col-md-6">
                	<div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                    	<input type="text" class="form-control" name="kode_kamar" id="kode_kamar" value="" readonly>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Nama Kamar</label>
                <div class="col-md-6">
                    <input type="text" class="form-control" name="nama_kamar" value="" required="required">
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Biaya</label>
                <div class="col-md-6">
                	<div class="input-group">
                        <span class="input-group-addon">Rp</span>
                    	<input type="text" class="form-control" name="biaya" value="" required="required" onkeyup="FormatCurrency(this);">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Jumlah Lemari</label>
                <div class="col-md-6">
                	<div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-bed"></i></span>
                    	<input type="text" class="form-control" name="jumlah_bed" value="" required="required" onkeyup="FormatCurrency(this);">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">&nbsp;</label>
                <div class="col-md-3">
                	<button type="submit" class="btn btn-success waves-effect waves-light m-b-5"> <i class="fa fa-save"></i> <span>Simpan</span> </button>
                	<button type="button" class="btn btn-danger waves-effect waves-light m-b-5" id="batal"> <i class="fa fa-times"></i> <span>Batal</span> </button>
                </div>
            </div>
    	</form>
    </div>
</div>

<button id="popup_bed" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#myModal2" style="display:none;">Standard Modal</button>
<div id="myModal2" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="<?php echo $url_simpan_lemari; ?>" method="post">
        	<div class="modal-content">
	            <div class="modal-header">
	                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	                <h4 class="modal-title" id="myModalLabel"></h4>
	            </div>
            	<div class="modal-body">
            		<input type="hidden" name="id_kamar" id="id_kamar" value="">
	            	<div class="table-responsive">
	            		<div class="scroll-y">
			                <table class="table table-striped" id="tabel_lemari">
			                    <thead>
			                        <tr class="merah_popup">
			                            <th style="text-align:center; color: #fff;">Kode</th>
			                            <th style="text-align:center; color: #fff;">Jumlah</th>
			                        </tr>
			                    </thead>
			                    <tbody>
			                        
			                    </tbody>
			                </table>
	            		</div>
	            	</div>
            	</div>
	            <div class="modal-footer">
	            	<button type="submit" class="btn btn-primary waves-effect">Simpan</button>
	                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_lemari">Tutup</button>
	            </div>
        	</div><!-- /.modal-content -->
        </form>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<button id="popup_detail" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#full-width-modal" style="display:none;">Full width Modal</button>
<div id="full-width-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="full-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-full">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="full-width-modalLabel">Detail Kamar</h4>
            </div>
            <div class="modal-body">
		    	<form class="form-horizontal" role="form">
			    	<div class="row">
				    	<div class="col-md-6">
			    			<div class="form-group">
				                <label class="col-md-3 control-label">Kode Kamar</label>
				                <div class="col-md-8">
				                	<div class="input-group">
				                        <span class="input-group-addon"><i class="fa fa-lock"></i></span>
				                    	<input type="text" class="form-control" id="kode_kamar_detail" value="" readonly>
				                    </div>
				                </div>
				            </div>
				    	</div>
				    	<div class="col-md-6">
				            <div class="form-group">
				                <label class="col-md-3 control-label">Biaya</label>
				                <div class="col-md-8">
				                	<div class="input-group">
				                        <span class="input-group-addon">Rp</span>
				                    	<input type="text" class="form-control" id="biaya_detail" value="" readonly>
				                    </div>
				                </div>
				            </div>
				    	</div>
			    	</div>
			    	<div class="row">
			    		<div class="col-md-6">
			    			<div class="form-group">
				                <label class="col-md-3 control-label">Nama Kamar</label>
				                <div class="col-md-8">
				                    <input type="text" class="form-control" id="nama_kamar_detail" value="" readonly>
				                </div>
				            </div>
			    		</div>
			    		<div class="col-md-6">
			    			<div class="form-group">
				                <label class="col-md-3 control-label">Jumlah Lemari</label>
				                <div class="col-md-8">
				                	<div class="input-group">
				                        <span class="input-group-addon"><i class="fa fa-bed"></i></span>
				                    	<input type="text" class="form-control" id="jumlah_bed_detail" value="" readonly>
				                    </div>
				                </div>
				            </div>
			    		</div>
			    	</div>
		    	</form>

		    	<hr/>

		    	<form class="form-horizontal" role="form">
			    	<div class="row">
			    		<div class="col-md-12">
			    			<div class="form-group">
							    <div id="view_detail_bed">
							    	
							    </div>
			    			</div>
			    		</div>
			    	</div>
		    	</form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="col-lg-12" id="view_ubah">
    <div class="card-box card-tabs">
    	<h4 class="header-title m-t-0 m-b-30">Ubah Kamar</h4>
		<hr/>
    	<form class="form-horizontal" role="form" action="<?php echo $url_ubah; ?>" method="post">
    		<input type="hidden" name="id_ubah" id="id_ubah" value="">
        	<div class="form-group">
                <label class="col-md-2 control-label">Kode Kamar</label>
                <div class="col-md-6">
                	<div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                    	<input type="text" class="form-control" id="kode_kamar_ubah" value="" readonly>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Nama Kamar</label>
                <div class="col-md-6">
                    <input type="text" class="form-control" name="nama_kamar_ubah" id="nama_kamar_ubah" value="" required="required">
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Biaya</label>
                <div class="col-md-6">
                	<div class="input-group">
                        <span class="input-group-addon">Rp</span>
                    	<input type="text" class="form-control" name="biaya_ubah" id="biaya_ubah" value="" required="required" onkeyup="FormatCurrency(this);">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Jumlah Lemari</label>
                <div class="col-md-6">
                	<div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-bed"></i></span>
                    	<input type="text" class="form-control" name="jumlah_lemari_ubah" id="jumlah_lemari_ubah" value="" required="required" onkeyup="FormatCurrency(this);">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">&nbsp;</label>
                <div class="col-md-3">
                	<button type="submit" class="btn btn-success waves-effect waves-light m-b-5"> <i class="fa fa-save"></i> <span>Simpan</span> </button>
                	<button type="button" class="btn btn-danger waves-effect waves-light m-b-5" id="batal_ubah"> <i class="fa fa-times"></i> <span>Batal</span> </button>
                </div>
            </div>
    	</form>
    </div>
</div>

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

<button id="popup_tidak_hapus" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#myModal3" style="display:none;">Standard Modal</button>
<div id="myModal3" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
    	<div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="custom-width-modalLabel">Peringatan</h4>
            </div>
        	<div class="modal-body">
        		<p>Data tidak bisa terhapus.</p>
        		<p>Hapus dahulu data lemari kamar yang bersangkutan.</p>
        	</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal">Tutup</button>
            </div>
    	</div>
    </div>
</div>

<div id="popup_hapus_lemari">
	<div class="window_hapus_lemari">
		<div class="modal-dialog">
			<div class="modal-header">
                <h4 class="modal-title" id="custom-width-modalLabel">Konfirmasi Hapus</h4>
            </div>
        	<div class="modal-body">
        		<p id="msg_bed"></p>
        	</div>
            <div class="modal-footer">
            	<form action="" method="post">
            		<input type="hidden" name="id_kamar_jenazah" id="id_kamar_jenazah" value="">
            		<input type="hidden" name="id_hapus_lemari" id="id_hapus_lemari" value="">
	                <button type="button" class="btn btn-danger waves-effect" id="btn_hapus_lemari">Hapus</button>
	                <button type="button" class="btn btn-inverse waves-effect" id="tutup">Tutup</button>
            	</form>
            </div>
		</div>
	</div>
</div>
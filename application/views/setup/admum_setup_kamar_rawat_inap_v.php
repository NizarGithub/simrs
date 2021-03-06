<script type="text/javascript" src="<?php echo base_url(); ?>js-devan/jquery-1.11.1.min.js"></script>

<style type="text/css">
#view_tambah, #view_kamar, #tombol_reset, #view_detail, #view_ubah{
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

	toastr.options = {
      "closeButton": false,
      "debug": false,
      "newestOnTop": false,
      "progressBar": false,
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

	$('#btn_tambah').click(function(){
		$('#view_tambah').show();
		$('#view_data').hide();
	});

	$('#batal').click(function(){
		window.location = "<?php echo base_url(); ?>setup/admum_setup_kamar_rawat_inap_c";
	});

	$("input[name='urutkan']").click(function(){
		data_kamar();
	});

	$("input[name='cari_berdasarkan']").click(function(){
		var cari = $("input[name='cari_berdasarkan']:checked").val();
		if(cari == 'Nama Kamar'){
			$('#view_kamar').hide();
			$('#cari_kamar').focus();
		}else{
			$('#view_kamar').show();
		}
	});

	$('#pilih_kelas').change(function(){
		data_kamar();
	});

	$('#jumlah_tampil').change(function(){
		data_kamar();
	});

	$('#btn_hapus_bed').click(function(){
		var id_hapus_bed = $('#id_hapus_bed').val();
		var id_kamar_rawat_inap = $('#id_kamar_rawat_inap').val();
		var id = $('#id_kamar_rawat_inap').val();

		$.ajax({
			url : '<?php echo base_url(); ?>setup/admum_setup_kamar_rawat_inap_c/hapus_bed',
			data : {id_hapus_bed:id_hapus_bed,id_kamar_rawat_inap:id_kamar_rawat_inap},
			type : "POST",
			dataType : "json",
			async : false,
			success : function(result){
				notif_hapus();
				$('#popup_hapus_bed').hide();
				data_kamar();

				$.ajax({
					url : '<?php echo base_url(); ?>setup/admum_setup_kamar_rawat_inap_c/data_bed',
					data : {id:id},
					type : "POST",
					dataType : "json",
					async : false,
					success : function(result){
						$('#kode_kamar_detail').val(result['detail']['KODE_KAMAR']);
						$('#nama_kamar_detail').val(result['detail']['NAMA_KAMAR']);
						$('#kategori_detail').val(result['detail']['KATEGORI']);
						$('#kelas_detail').val(result['detail']['KELAS']);
						$('#biaya_detail').val(formatNumber(result['detail']['BIAYA']));
						$('#jumlah_bed_detail').val(formatNumber(result['detail']['JUMLAH_BED']));
						$('#fasilitas_detail').val(formatNumber(result['detail']['FASILITAS']));

						$div = "";

						for(var i=0; i<result['bed'].length; i++){
							var stt_pakai = "";
							var btn_hapus = "";

							if(result['bed'][i].STATUS_PAKAI == 0){
								stt_pakai = '<small><b>Status</b></small> : <small class="text-success"><b>Kosong</b></small>';
							}else{
								stt_pakai = '<small><b>Status</b></small> : <small class="text-danger"><b>Terpakai</b></small>';
							}

							if(result['bed'][i].STATUS_HAPUS == 1){
								btn_hapus = '<button type="button" class="btn btn-danger waves-effect" onclick="hapus_bed('+result['bed'][i].ID+','+id+');"><i class="fa fa-times"></i> Hapus</button>';
							}else{
								btn_hapus = '';
							}

							$div += '<div class="col-lg-3">'+
								    '    <div class="card-box widget-user">'+
								    '        <div>'+
								    '            <img alt="user" class="img-responsive img-circle" src="<?php echo base_url(); ?>picture/admum/hospital-beds-56364.png">'+
								    '            <div class="wid-u-info">'+
								    '                <h4 class="m-t-0 m-b-5">'+result['bed'][i].NOMOR_BED+'</h4>'+
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

function cek_nomor_kamar(){
	var nomor = $('#kode_kamar').val();

	$.ajax({
		url : '<?php echo base_url(); ?>setup/admum_setup_kamar_rawat_inap_c/cek_nomor_kamar',
		data : {nomor:nomor},
		type : "POST",
		dataType : "json",
		success : function(res){
			if(res.length != 0){
				var ket = "Nomor kamar "+res['KODE_KAMAR']+' ini! Sudah ada.';
				toastr["error"](ket, "Notifikasi");
				$('#btn_simpan').attr('disabled','disabled');
				$('#border_kode_kamar').addClass('has-error');
			}else{
				$('#btn_simpan').removeAttr('disabled');
				$('#border_kode_kamar').removeClass('has-error');
			}
		}
	});
}

function paging($selector){
	var jumlah_tampil = $('#jumlah_tampil').val();

    if(typeof $selector == 'undefined')
    {
        $selector = $("#tabel_rawat_inap tbody tr");
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
	var cari_berdasarkan = $("input[name='cari_berdasarkan']:checked").val();
	var pilih_kelas = $('#pilih_kelas').val();

	if(ajax){
		ajax.abort();
	}

	ajax = $.ajax({
		url : '<?php echo base_url(); ?>setup/admum_setup_kamar_rawat_inap_c/data_kamar',
		data : {
			keyword:keyword,
			urutkan:urutkan,
			cari_berdasarkan:cari_berdasarkan,
			pilih_kelas:pilih_kelas
		},
		type : "GET",
		dataType : "json",
		success : function(result){
			$tr = "";

			if(result == "" || result == null){
				$tr = "<tr><td colspan='10' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
			}else{
				var no = 0;

				for(var i=0; i<result.length; i++){
					no++;

					var aksi = "";

					if(result[i].TOTAL != 0){
						aksi =  '<button type="button" class="btn btn-info waves-effect waves-light btn-sm" onclick="detail_kamar('+result[i].ID+');">'+
									'<i class="fa fa-eye"></i>'+
								'</button>&nbsp;'+
								'<button type="button" class="btn btn-success waves-effect waves-light btn-sm" onclick="ubah_kamar('+result[i].ID+');">'+
									'<i class="fa fa-pencil"></i>'+
								'</button>&nbsp;'+
						   		'<button type="button" class="btn btn-danger waves-effect waves-light btn-sm" onclick="hapus_kamar('+result[i].ID+');">'+
						   			'<i class="fa fa-trash"></i>'+
						   		'</button>';
					}else{
						aksi =  '<button type="button" class="btn btn-pink waves-effect waves-light btn-sm" onclick="tambah_bed('+result[i].ID+');">'+
									'<i class="fa fa-bed"></i>'+
								'</button>&nbsp;'+
								'<button type="button" class="btn btn-success waves-effect waves-light btn-sm" onclick="ubah_kamar('+result[i].ID+');">'+
									'<i class="fa fa-pencil"></i>'+
								'</button>&nbsp;'+
						   		'<button type="button" class="btn btn-danger waves-effect waves-light btn-sm" onclick="hapus_kamar('+result[i].ID+');">'+
						   			'<i class="fa fa-trash"></i>'+
						   		'</button>';
					}

					$tr += "<tr>"+
								"<td style='vertical-align:middle; text-align:center;'>"+no+"</td>"+
								"<td style='vertical-align:middle; text-align:center;'>"+result[i].KODE_KAMAR+"</td>"+
								"<td style='vertical-align:middle; text-align:center;'>"+result[i].KELAS+"</td>"+
								"<td style='vertical-align:middle; text-align:right;'>"+formatNumber(result[i].BIAYA)+"</td>"+
								"<td style='vertical-align:middle; text-align:center;'>"+result[i].VISITE_DOKTER+"</td>"+
								"<td style='vertical-align:middle; text-align:right;'>"+formatNumber(result[i].BIAYA_VISITE)+"</td>"+
								"<td style='vertical-align:middle; text-align:right;'>"+formatNumber(result[i].JASA_SARANA)+"</td>"+
								"<td style='vertical-align:middle; text-align:center;'>"+result[i].PERUNTUKAN_KAMAR+"</td>"+
								"<td style='vertical-align:middle; text-align:center;'>"+formatNumber(result[i].JUMLAH_BED)+"</td>"+
								"<td align='center'>"+aksi+"</td>"+
							"</tr>";
				}
			}

			$('#tabel_rawat_inap tbody').html($tr);
			paging();
			$('#popup_load').fadeOut();
		}
	});

	$('#cari_kamar').off('keyup').keyup(function(){
		data_kamar();
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

function tambah_bed(id){
	$('#popup_bed').click();

	$.ajax({
		url : "<?php echo base_url(); ?>setup/admum_setup_kamar_rawat_inap_c/data_kamar_id",
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(row){
			$('#id_kamar').val(id);
			var nama_kamar = row['KELAS'];
			var judul = "Jumlah Bed Kamar "+nama_kamar;
			$('#myModalLabel').html(judul);

			var jumlah_bed = row['JUMLAH_BED'];

			$tr = "";
			var no = 0;

			for(var i=0; i<parseInt(jumlah_bed); i++){
				no++;

				var nomor = nama_kamar+" - "+no;

				$tr += "<tr>"+
							"<input type='hidden' name='no[]' value='"+no+"'>"+
							"<input type='hidden' name='nomor_bed[]' value='"+nomor+"'>"+
							"<input type='hidden' name='jumlah[]' value='1'>"+
							"<td style='text-align:center;'>"+nomor+"</td>"+
							"<td style='text-align:center;'>1</td>"+
						"</tr>";
			}

			$('#tabel_bed tbody').html($tr);
		}
	});
}

function detail_kamar(id){
	$('#popup_detail').click();

	$.ajax({
		url : '<?php echo base_url(); ?>setup/admum_setup_kamar_rawat_inap_c/data_bed',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(result){
			$('#kode_kamar_detail').val(result['detail']['KODE_KAMAR']);
			$('#kelas_detail').val(result['detail']['KELAS']);
			$('#biaya_detail').val(formatNumber(result['detail']['BIAYA']));
			$('#visite_dokter_detail').val(result['detail']['VISITE_DOKTER']);
			$('#biaya_visite_detail').val(formatNumber(result['detail']['BIAYA_VISITE']));
			$('#jasa_sarana_detail').val(formatNumber(result['detail']['JASA_SARANA']));
			$('#jumlah_bed_detail').val(formatNumber(result['detail']['JUMLAH_BED']));
			$('#fasilitas_detail').val(formatNumber(result['detail']['PERUNTUKAN_KAMAR']));

			$div = "";

			for(var i=0; i<result['bed'].length; i++){
				var stt_pakai = "";
				var btn_hapus = "";

				if(result['bed'][i].STATUS_PAKAI == 0){
					stt_pakai = '<small><b>Status</b></small> : <small class="text-success"><b>Kosong</b></small>';
				}else{
					stt_pakai = '<small><b>Status</b></small> : <small class="text-danger"><b>Terpakai</b></small>';
				}

				if(result['bed'][i].STATUS_HAPUS == 1){
					btn_hapus = '<button type="button" class="btn btn-danger waves-effect" onclick="hapus_bed('+result['bed'][i].ID+','+id+');"><i class="fa fa-times"></i> Hapus</button>';
				}else{
					btn_hapus = '';
				}

				$div += '<div class="col-lg-3">'+
					    '    <div class="card-box widget-user">'+
					    '        <div>'+
					    '            <img alt="user" class="img-responsive img-circle" src="<?php echo base_url(); ?>picture/admum/hospital-beds-56364.png">'+
					    '            <div class="wid-u-info">'+
					    '                <h4 class="m-t-0 m-b-5">'+result['bed'][i].NOMOR_BED+'</h4>'+
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
		url : '<?php echo base_url(); ?>setup/admum_setup_kamar_rawat_inap_c/data_kamar_id',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(row){
			$('#id_ubah').val(id);
			$('#kode_kamar_ubah').val(row['KODE_KAMAR']);

			if(row['KELAS'] == "SVIP"){
				$('#kelas_kamar_ubah option[value="SVIP"]').attr('selected','selected');
			}else if(row['KELAS'] == "VIP"){
				$('#kelas_kamar_ubah option[value="VIP"]').attr('selected','selected');
			}else if(row['KELAS'] == "1A"){
				$('#kelas_kamar_ubah option[value="1A"]').attr('selected','selected');
			}else if(row['KELAS'] == "1B"){
				$('#kelas_kamar_ubah option[value="1B"]').attr('selected','selected');
			}else if(row['KELAS'] == "2A"){
				$('#kelas_kamar_ubah option[value="2A"]').attr('selected','selected');
			}else if(row['KELAS'] == "2B"){
				$('#kelas_kamar_ubah option[value="2B"]').attr('selected','selected');
			}else if(row['KELAS'] == "3"){
				$('#kelas_kamar_ubah option[value="3"]').attr('selected','selected');
			}else if(row['KELAS'] == "Neo"){
				$('#kelas_kamar_ubah option[value="Neo"]').attr('selected','selected');
			}

			$('#biaya_ubah').val(NumberToMoney(row['BIAYA']));

			if(row['VISITE_DOKTER'] == 'Tarif A'){
				$('#inlineRadio_ubah3').attr('checked','checked');
			}else if(row['VISITE_DOKTER'] == 'Tarif B'){
				$('#inlineRadio_ubah4').attr('checked','checked');
			}else if(row['VISITE_DOKTER'] == 'Tarif C'){
				$('#inlineRadio_ubah5').attr('checked','checked');
			}

			$('#tarif_ubah').val(NumberToMoney(row['BIAYA_VISITE']));
			$('#jasa_sarana_ubah').val(NumberToMoney(row['JASA_SARANA']));
			$('#peruntukan_kamar_ubah').val(row['PERUNTUKAN_KAMAR']);
			$('#jumlah_bed_ubah').val(NumberToMoney(row['JUMLAH_BED']));

			if(row['TOTAL'] != 0){
				$('#jumlah_bed_ubah').attr('readonly','readonly');
			}else{
				$('#jumlah_bed_ubah').removeAttr('readonly');
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
		url : '<?php echo base_url(); ?>setup/admum_setup_kamar_rawat_inap_c/data_kamar_id',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(row){
			$('#id_hapus').val(id);
			var txt = row['KODE_KAMAR']+' - '+row['KELAS'];
			$('#msg').html('Apakah kamar <b>'+txt+'</b> ingin dihapus?');
		}
	});
}

function hapus_bed(id,id_kamar){
	$('#popup_hapus_bed').show();

	$.ajax({
		url : '<?php echo base_url(); ?>setup/admum_setup_kamar_rawat_inap_c/data_bed_id',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(row){
			$('#id_hapus_bed').val(id);
			$('#id_kamar_rawat_inap').val(id_kamar);
			var txt = row['NOMOR_BED'];
			$('#msg_bed').html('Apakah bed <b>'+txt+'</b> ingin dihapus?');
		}
	});

	$('#tutup_bed').click(function(){
		$('#popup_hapus_bed').fadeOut();
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
		            	<div class="col-md-7">
			                <button type="button" class="btn waves-effect waves-light btn-purple" id="btn_tambah">
	                    		<i class="fa fa-plus"></i> Tambah
	                    	</button>
		                </div>
		            	<div class="col-md-5">
		                	<input type="submit" name="pdf" class="btn btn-danger pull-right" value="Cetak PDF">
		                	<input type="submit" name="excel" class="btn btn-success m-r-5 pull-right" value="Cetak Excel">
		                </div>
		            </div>
		            <div class="form-group">
		            	<label class="col-md-1 control-label" style="text-align:left; width:8%;">Cari Berdasarkan</label>
		            	<div class="col-md-4">
                			<div class="radio radio-primary radio-inline">
                                <input type="radio" name="cari_berdasarkan" value="Kode Kamar">
                                <label for="nama_poli"> Kode Kamar </label>
                            </div>
                            <div class="radio radio-primary radio-inline">
                                <input type="radio" name="cari_berdasarkan" value="Kelas Kamar">
                                <label for="jenis"> Kelas Kamar </label>
                            </div>
            			</div>
		            </div>
		            <div class="form-group" id="view_kamar">
		            	<label class="col-md-1 control-label" style="width:8%;">&nbsp;</label>
		            	<div class="col-md-3">
		            		<select class="form-control" id="pilih_kelas">
                                <option value="SVIP">SVIP</option>
		                        <option value="VIP">VIP</option>
		                        <option value="1A">I A</option>
		                        <option value="1B">I B</option>
		                        <option value="2A">II A</option>
		                        <option value="2B">II B</option>
		                        <option value="3">III</option>
		                        <option value="Neo">Ruang Neo</option>
                            </select>
		            	</div>
		            </div>
		            <div class="form-group">
		            	<div class="col-md-12">
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
		            <table id="tabel_rawat_inap" class="table table-striped table-bordered">
		                <thead>
		                    <tr class="biru">
		                        <th style="color:#fff; text-align:center;" width="50">No</th>
		                        <th style="color:#fff; text-align:center;">Nomor Kamar</th>
		                        <th style="color:#fff; text-align:center;">Kelas</th>
		                        <th style="color:#fff; text-align:center;">Biaya</th>
		                        <th style="color:#fff; text-align:center;">Visite Dokter Sp.</th>
		                        <th style="color:#fff; text-align:center;">Biaya Visite</th>
		                        <th style="color:#fff; text-align:center;">Jasa Sarana RS</th>
		                        <th style="color:#fff; text-align:center;">Peruntukan Kmr</th>
		                        <th style="color:#fff; text-align:center;">Jumlah Bed</th>
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
                    	<label class="col-md-1 control-label" style="text-align:left; width: 5%;">Urutkan</label>
		        		<div class="col-md-8">
		        			<div class="radio radio-success radio-inline">
                                <input type="radio" name="urutkan" value="Default" id="default" checked="checked">
                                <label for="default"> Default </label>
                            </div>
                			<div class="radio radio-success radio-inline">
                                <input type="radio" name="urutkan" value="Kode Kamar">
                                <label for="nama_poli"> Kode Kamar </label>
                            </div>
                            <div class="radio radio-success radio-inline">
                                <input type="radio" name="urutkan" value="Kelas Kamar">
                                <label for="jenis"> Kelas Kamar </label>
                            </div>
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
        	<div class="form-group" id="border_kode_kamar">
                <label class="col-md-2 control-label">Nomor Kamar</label>
                <div class="col-md-6">
                	<div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                    	<input type="text" class="form-control" name="kode_kamar" id="kode_kamar" value="">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Kelas</label>
                <div class="col-md-3">
                    <select class="form-control select2" name="kelas_kamar">
                        <option value="SVIP">SVIP</option>
                        <option value="VIP">VIP</option>
                        <option value="1A">I A</option>
                        <option value="1B">I B</option>
                        <option value="2A">II A</option>
                        <option value="2B">II B</option>
                        <option value="3">III</option>
                        <option value="Neo">Ruang Neo</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Biaya</label>
                <div class="col-md-3">
                	<div class="input-group">
                        <span class="input-group-addon">Rp</span>
                    	<input type="text" class="form-control" name="biaya" value="" required="required" onkeyup="FormatCurrency(this);">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Visite Dokter Spesialis</label>
                <div class="col-md-6">
                	<div class="radio radio-primary radio-inline">
                        <input type="radio" id="inlineRadio3" value="Tarif A" name="visite">
                        <label for="inlineRadio3"> Tarif A </label>
                    </div>
                    <div class="radio radio-primary radio-inline">
                        <input type="radio" id="inlineRadio4" value="Tarif B" name="visite">
                        <label for="inlineRadio4"> Tarif B </label>
                    </div>
                    <div class="radio radio-primary radio-inline">
                        <input type="radio" id="inlineRadio5" value="Tarif C" name="visite">
                        <label for="inlineRadio5"> Tarif C </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">&nbsp;</label>
                <div class="col-md-3">
                	<div class="input-group">
                        <span class="input-group-addon">Rp</span>
                    	<input type="text" class="form-control" name="tarif" value="" required="required" onkeyup="FormatCurrency(this);">
                    </div>
                </div>
            </div>
            <!-- <div class="form-group">
                <label class="col-md-2 control-label">Nama Kamar</label>
                <div class="col-md-6">
                    <input type="text" class="form-control" name="nama_kamar" value="" required="required">
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Kategori</label>
                <div class="col-md-3">
                    <select class="form-control" name="kategori">
                        <option value="Umum">Umum</option>
                    </select>
                </div>
            </div> -->
            <div class="form-group">
                <label class="col-md-2 control-label">Jasa Sarana RS</label>
                <div class="col-md-3">
                	<div class="input-group">
                        <span class="input-group-addon">Rp</span>
                    	<input type="text" class="form-control" name="jasa_sarana" value="" required="required" onkeyup="FormatCurrency(this);">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Peruntukan Kamar</label>
                <div class="col-md-6">
                    <textarea name="peruntukan_kamar" class="form-control" rows="5"></textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Jumlah Bed</label>
                <div class="col-md-3">
                	<div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-bed"></i></span>
                    	<input type="text" class="form-control" name="jumlah_bed" value="" required="required" onkeyup="FormatCurrency(this);">
                    </div>
                </div>
            </div>
            <hr>
            <div class="form-group">
                <label class="col-md-2 control-label">&nbsp;</label>
                <div class="col-md-3">
                	<button type="submit" class="btn btn-success" id="btn_simpan"> <i class="fa fa-save"></i> <span>Simpan</span> </button>
                	<button type="button" class="btn btn-danger" id="batal"> <i class="fa fa-times"></i> <span>Batal</span> </button>
                </div>
            </div>
    	</form>
    </div>
</div>

<div class="col-lg-12" id="view_ubah">
    <div class="card-box card-tabs">
    	<h4 class="header-title m-t-0 m-b-30">Ubah Kamar</h4>
		<hr/>
    	<form class="form-horizontal" role="form" action="<?php echo $url_ubah; ?>" method="post">
    		<input type="hidden" name="id_ubah" id="id_ubah" value="">
        	<div class="form-group">
                <label class="col-md-2 control-label">Nomor Kamar</label>
                <div class="col-md-6">
                	<div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                    	<input type="text" class="form-control" name="kode_kamar_ubah" id="kode_kamar_ubah" value="">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Kelas</label>
                <div class="col-md-3">
                    <select class="form-control" name="kelas_kamar_ubah" id="kelas_kamar_ubah">
                        <option value="SVIP">SVIP</option>
                        <option value="VIP">VIP</option>
                        <option value="1A">I A</option>
                        <option value="1B">I B</option>
                        <option value="2A">II A</option>
                        <option value="2B">II B</option>
                        <option value="3">III</option>
                        <option value="Neo">Ruang Neo</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Biaya</label>
                <div class="col-md-3">
                	<div class="input-group">
                        <span class="input-group-addon">Rp</span>
                    	<input type="text" class="form-control" name="biaya_ubah" id="biaya_ubah" value="" required="required" onkeyup="FormatCurrency(this);">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Visite Dokter Spesialis</label>
                <div class="col-md-6">
                	<div class="radio radio-primary radio-inline">
                        <input type="radio" id="inlineRadio_ubah3" value="Tarif A" name="visite_ubah">
                        <label for="inlineRadio_ubah3"> Tarif A </label>
                    </div>
                    <div class="radio radio-primary radio-inline">
                        <input type="radio" id="inlineRadio_ubah4" value="Tarif B" name="visite_ubah">
                        <label for="inlineRadio_ubah4"> Tarif B </label>
                    </div>
                    <div class="radio radio-primary radio-inline">
                        <input type="radio" id="inlineRadio_ubah5" value="Tarif C" name="visite_ubah">
                        <label for="inlineRadio_ubah5"> Tarif C </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">&nbsp;</label>
                <div class="col-md-3">
                	<div class="input-group">
                        <span class="input-group-addon">Rp</span>
                    	<input type="text" class="form-control" name="tarif_ubah" id="tarif_ubah" value="" required="required" onkeyup="FormatCurrency(this);">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Jasa Sarana RS</label>
                <div class="col-md-3">
                	<div class="input-group">
                        <span class="input-group-addon">Rp</span>
                    	<input type="text" class="form-control" name="jasa_sarana_ubah" id="jasa_sarana_ubah" value="" required="required" onkeyup="FormatCurrency(this);">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Peruntukan Kamar</label>
                <div class="col-md-6">
                    <textarea name="peruntukan_kamar_ubah" id="peruntukan_kamar_ubah" class="form-control" rows="5"></textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Jumlah Bed</label>
                <div class="col-md-6">
                	<div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-bed"></i></span>
                    	<input type="text" class="form-control" name="jumlah_bed_ubah" id="jumlah_bed_ubah" value="" required="required" onkeyup="FormatCurrency(this);">
                    </div>
                </div>
            </div>
            <hr>
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
				                <label class="col-md-3 control-label">Visite Dokter Sp.</label>
				                <div class="col-md-8">
				                	<input type="text" class="form-control" id="visite_dokter_detail" value="" readonly>
				                </div>
				            </div>
			    		</div>
			    	</div>
			    	<div class="row">
			    		<div class="col-md-6">
			    			<div class="form-group">
				                <label class="col-md-3 control-label">Kelas</label>
				                <div class="col-md-8">
				                    <input type="text" class="form-control" id="kelas_detail" value="" readonly>
				                </div>
				            </div>
			    		</div>
			    		<div class="col-md-6">
				            <div class="form-group">
				                <label class="col-md-3 control-label">Biaya Visite</label>
				                <div class="col-md-8">
				                	<div class="input-group">
				                        <span class="input-group-addon">Rp</span>
				                    	<input type="text" class="form-control" id="biaya_visite_detail" value="" readonly>
				                    </div>
				                </div>
				            </div>
				    	</div>
			    	</div>
			    	<div class="row">
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
			    		<div class="col-md-6">
			    			<div class="form-group">
				                <label class="col-md-3 control-label">Jumlah Bed</label>
				                <div class="col-md-8">
				                	<div class="input-group">
				                        <span class="input-group-addon"><i class="fa fa-bed"></i></span>
				                    	<input type="text" class="form-control" id="jumlah_bed_detail" value="" readonly>
				                    </div>
				                </div>
				            </div>
			    		</div>
			    	</div>
			    	<div class="row">
			    		<div class="col-md-6">
			    			<div class="form-group">
				                <label class="col-md-3 control-label">Jasa Sarana RS</label>
				                <div class="col-md-8">
				                	<div class="input-group">
				                        <span class="input-group-addon">Rp</span>
				                    	<input type="text" class="form-control" id="jasa_sarana_detail" value="" readonly>
				                    </div>
				                </div>
				            </div>
			    		</div>
			    		<div class="col-md-6">
			    			<div class="form-group">
				                <label class="col-md-3 control-label">Peruntukan Kamar</label>
				                <div class="col-md-8">
				                    <textarea id="fasilitas_detail" class="form-control" rows="5" readonly></textarea>
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

<button id="popup_bed" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#myModal2" style="display:none;">Standard Modal</button>
<div id="myModal2" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="<?php echo $url_simpan_bed; ?>" method="post">
        	<div class="modal-content">
	            <div class="modal-header">
	                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	                <h4 class="modal-title" id="myModalLabel"></h4>
	            </div>
            	<div class="modal-body">
            		<input type="hidden" name="id_kamar" id="id_kamar" value="">
	            	<div class="table-responsive">
	            		<div class="scroll-y">
			                <table class="table table-bordered" id="tabel_bed">
			                    <thead>
			                        <tr class="merah_popup">
			                            <th style="text-align:center; color: #fff;">Nomor Bed</th>
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
	                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_jenis">Tutup</button>
	            </div>
        	</div><!-- /.modal-content -->
        </form>
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
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

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
        		<p><b>Hapus dahulu Data Bed kamar yang bersangkutan.</b></p>
        	</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal">Tutup</button>
            </div>
    	</div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id="popup_hapus_bed">
	<div class="window_hapus_bed">
		<div class="modal-dialog">
			<div class="modal-header">
                <h4 class="modal-title" id="custom-width-modalLabel">Konfirmasi Hapus</h4>
            </div>
        	<div class="modal-body">
        		<p id="msg_bed"></p>
        	</div>
            <div class="modal-footer">
            	<form action="<?php echo $url_hapus_bed; ?>" method="post">
            		<input type="hidden" name="id_hapus_bed" id="id_hapus_bed" value="">
            		<input type="hidden" name="id_kamar_rawat_inap" id="id_kamar_rawat_inap" value="">
	                <button type="button" class="btn btn-danger waves-effect" id="btn_hapus_bed">Hapus</button>
	                <button type="button" class="btn btn-inverse waves-effect" id="tutup_bed">Tutup</button>
            	</form>
            </div>
		</div>
	</div>
</div>
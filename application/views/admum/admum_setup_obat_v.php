<script type="text/javascript" src="<?php echo base_url(); ?>js-devan/jquery-1.11.1.min.js"></script>

<style type="text/css">
#view_tambah, #view_ubah, #view_merk, #tombol_reset, #msg_barcode{
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
		get_kode_obat();
	});

	$('#batal').click(function(){
		window.location = "<?php echo base_url(); ?>admum/admum_setup_obat_c";
	});

	$('#merk').click(function(){
		$('#ket').val('Tambah');
		$('#popup_merk').click();
		get_merk();
	});

	$('#btn_merk').click(function(){
		$('#ket').val('Tambah');
		$('#popup_merk').click();
		get_merk();
	});

	$('#checkbox2').click(function(){
		var cek = $('#checkbox2').is(":checked");
		if(cek == true){
			$('#view_merk').show();
		}else{
			$('#view_merk').hide();
			$('#id_merk_ubah').val("");
			$('#merk_ubah').val("");
		}
	});

	$('#merk_ubah').click(function(){
		$('#ket').val('Ubah');
		$('#popup_merk').click();
		get_merk();
	});

	$('#btn_merk_ubah').click(function(){
		$('#ket').val('Ubah');
		$('#popup_merk').click();
		get_merk();
	});
});

var ajax = "";

function get_kode_obat(){
	$.ajax({
        url : '<?php echo base_url(); ?>admum/admum_setup_obat_c/kode_obat',
        type : "POST",
        dataType : "json",
        success : function(kode){
            $('#kode_obat').val(kode);
        }
    });
}

//MERK OBAT

function get_merk(){
	var keyword = $('#cari_merk').val();

	if(ajax){
		ajax.abort();
	}

	ajax = $.ajax({
        url : '<?php echo base_url(); ?>admum/admum_setup_obat_c/data_merk',
        data : {keyword:keyword},
        type : "GET",
        dataType : "json",
        success : function(result){
            $tr = "";

            if(result == "" || result == null){
            	$tr = "<tr><td colspan='2' style='text-align:center;'><b>Merk yang dicari tidak ada</b></td></tr>";
            }else{
	            var no = 0;
	            for(var i=0; i<result.length; i++){
	            	no++;

	            	$tr += '<tr style="cursor:pointer;" onclick="klik_merk('+result[i].ID+');">'+
	                        '    <td style="text-align:center;">'+no+'</td>'+
	                        '    <td>'+result[i].MERK+'</td>'+
	                        '</tr>';
	            }
            }

            $('#tabel_merk tbody').html($tr);
        }
    });

    $('#cari_merk').off('keyup').keyup(function(){
    	get_merk();
    });
}

function klik_merk(id_merk){
	$('#tutup_merk').click();

	$.ajax({
		url : '<?php echo base_url(); ?>admum/admum_setup_obat_c/klik_merk',
		data : {id_merk:id_merk},
		type : "POST",
		dataType : "json",
		success : function(row){
			var ket = $('#ket').val();

			if(ket == 'Tambah'){
				$('#id_merk').val(id_merk);
				$('#merk').val(row['MERK']);
				$('#id_merk_ubah').val("");
				$('#merk_ubah').val("");
			}else{
				$('#id_merk').val("");
				$('#merk').val("");
				$('#id_merk_ubah').val(id_merk);
				$('#merk_ubah').val(row['MERK']);
			}
		}
	});
}

//-------------

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
		url : '<?php echo base_url(); ?>admum/admum_setup_obat_c/get_data_obat',
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
        						"<td style='vertical-align:middle;'>"+result[i].MERK+"</td>"+
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

	$.ajax({
		url : '<?php echo base_url(); ?>admum/admum_setup_obat_c/data_obat_id',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(row){
			$('#id_ubah').val(id);
			$('#kode_obat_ubah').val(row['KODE_OBAT']);
			$('#barcode_ubah').val(row['BARCODE']);
			$('#nama_obat_ubah').val(row['NAMA_OBAT']);
			$('#id_merk_lama').val(row['ID_MERK']);
			$('#merk_txt').val(row['MERK']);
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
		url : '<?php echo base_url(); ?>admum/admum_setup_obat_c/data_obat_id',
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
        url : '<?php echo base_url(); ?>admum/admum_setup_obat_c/cek_barcode',
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
                        <th style="color:#fff; text-align:center;">Merk</th>
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
            <div class="form-group">
                <label class="col-md-2 control-label">Kode Obat</label>
                <div class="col-md-4">
                    <input type="text" class="form-control" name="kode_obat" id="kode_obat" value="" readonly>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Barcode</label>
                <div class="col-md-4">
                    <input type="text" class="form-control" name="barcode" id="barcode" value="" required="required" onchange="cek_barcode();">
                    <span class="help-block" style="color:#ff0000;" id="msg_barcode">
                        <small>Barcode ini sudah ada.</small>
                    </span>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Nama Obat</label>
                <div class="col-md-4">
                    <input type="text" class="form-control" name="nama_obat" value="" required="required">
                </div>
            </div>
            <div class="form-group">
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
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">&nbsp;</label>
                <div class="col-md-3">
                	<button type="submit" class="btn btn-success waves-effect waves-light m-b-5" id="btn_simpan"> <i class="fa fa-save"></i> <span>Simpan</span> </button>
                	<button type="button" class="btn btn-danger waves-effect waves-light m-b-5" id="batal"> <i class="fa fa-times"></i> <span>Batal</span> </button>
                </div>
            </div>
        </form>
    </div>

    <div class="card-box card-tabs" id="view_ubah">
    	<form class="form-horizontal" role="form" action="<?php echo $url_ubah; ?>" method="post">
    		<input type="hidden" name="id_ubah" id="id_ubah" value="">
            <div class="form-group">
                <label class="col-md-2 control-label">Kode Obat</label>
                <div class="col-md-6">
                    <input type="text" class="form-control" name="kode_obat_ubah" id="kode_obat_ubah" value="" readonly>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Barcode</label>
                <div class="col-md-6">
                    <input type="text" class="form-control" name="barcode_ubah" id="barcode_ubah" value="">
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Nama Obat</label>
                <div class="col-md-6">
                    <input type="text" class="form-control" name="nama_obat_ubah" id="nama_obat_ubah" value="">
                </div>
            </div>
            <div class="form-group">
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

<button class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#myModal" id="popup_merk" style="display:none;">Standard Modal</button>
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
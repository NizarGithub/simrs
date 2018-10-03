<script type="text/javascript" src="<?php echo base_url(); ?>js-devan/jquery-1.11.1.min.js"></script>
<style type="text/css">
#tombol_reset,
#view_ubah{
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

	get_data_kategori();

	$("#jumlah_tampil").change(function(){
    	get_data_kategori();
    });
});

function paging($selector){
	var jumlah_tampil = $('#jumlah_tampil').val();

    if(typeof $selector == 'undefined')
    {
        $selector = $("#tabel_data tbody tr");
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

function get_data_kategori(){
	$('#popup_load').show();
	var keyword = $('#cari_kategori').val();

	if(ajax){
		ajax.abort();
	}

	ajax = $.ajax({
        url : '<?php echo base_url(); ?>finance/admum_setup_kategori_c/get_data_kategori',
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

        			var aksi =  '<button type="button" class="btn btn-success waves-effect waves-light btn-sm" onclick="ubah_kategori('+result[i].ID+');">'+
									'<i class="fa fa-pencil"></i>'+
								'</button>&nbsp;'+
						   		'<button type="button" class="btn btn-danger waves-effect waves-light btn-sm" onclick="hapus_kategori('+result[i].ID+');">'+
						   			'<i class="fa fa-trash"></i>'+
						   		'</button>';

        			$tr += "<tr>"+
        						"<td style='vertical-align:middle; text-align:center;'>"+no+"</td>"+
        						"<td style='vertical-align:middle;'>"+result[i].NAMA_KATEGORI+"</td>"+
        						"<td align='center'>"+aksi+"</td>"+
        					"</tr>";
        		}
        	}

        	$('#tabel_data tbody').html($tr);
        	$('#total_data').html(parseInt(result.length));
        	paging();
        	$('#popup_load').fadeOut();
        }
    });

	$('#tombol_cari').click(function(){
		get_data_kategori();
		$('#tombol_reset').show();
		$('#tombol_cari').hide();
	});

	$('#tombol_reset').click(function(){
		$('#cari_kategori').val("");
		get_data_kategori();
		$('#tombol_reset').hide();
		$('#tombol_cari').show();
	});
}

function onEnterText(e){
    if (e.keyCode == 13) {
        get_data_kategori();
        $('#tombol_reset').show();
		$('#tombol_cari').hide();
        return false;
    }
}

function cek_kategori(){
	var nama_kategori = $('#nama_kategori').val();

	$.ajax({
        url : '<?php echo base_url(); ?>finance/admum_setup_kategori_c/cek_kategori',
        data : {nama_kategori:nama_kategori},
        type : "POST",
        dataType : "json",
        success : function(msg){
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

		    if(msg == 'Ada'){
		    	toastr["error"]("Perhatian! Kategori ini sudah ada.", "Notifikasi");
		    	$('#simpan').attr('disabled','disabled');
		    }else{
		    	$('#simpan').removeAttr('disabled');
		    }
        }
    });
}

function ubah_kategori(id){
	$('#view_ubah').show();
	$('#view_data').hide();

	$.ajax({
		url : '<?php echo base_url(); ?>finance/admum_setup_kategori_c/data_kategori_id',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(row){
			$('#id_ubah').val(id);
			$('#nama_kategori_ubah').val(row['NAMA_KATEGORI']);
		}
	});

	$('#batal_ubah').click(function(){
		$('#view_ubah').hide();
		$('#view_data').show();
	});
}

function hapus_kategori(id){
	$('#popup_hps').click();

	$.ajax({
		url : '<?php echo base_url(); ?>finance/admum_setup_kategori_c/data_kategori_id',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(row){
			$('#id_hapus').val(id);
			var txt = row['NAMA_KATEGORI'];
			$('#msg').html('Apakah kategori <b>'+txt+'</b> ingin dihapus?');
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
    <div class="col-lg-12" id="view_data">
    	<div class="card-box">
            <ul class="nav nav-tabs">
                <li class="active" role="presentation">
                    <a data-toggle="tab" role="tab" href="#data_kategori1"><i class="fa fa-table"></i> Data Kategori</a>
                </li>
                <li role="presentation">
                    <a data-toggle="tab" role="tab" href="#tambah_kategori1"><i class="fa fa-plus"></i> Tambah Kategori</a>
                </li>
            </ul>
            <div class="tab-content">
            	<div id="data_kategori1" class="tab-pane fade in active" role="tabpanel">
            		<form class="form-horizontal" role="form" action="" target="_blank" method="post">
            			<div class="form-group">
                            <div class="col-md-12">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="cari_kategori" id="cari_kategori" placeholder="Cari kategori..." value="" onkeypress="return onEnterText(event);">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn waves-effect waves-light btn-default" id="tombol_cari">
                                            <i class="fa fa-search"></i>
                                        </button>
                                        <button type="button" class="btn waves-effect waves-light btn-warning" id="tombol_reset" data-original-title="Reset Pencarian" title="" data-placement="top" data-toggle="tooltip">
                                            <i class="fa fa-refresh"></i>
                                        </button>
                                    </span>
                                </div>
                                <span class="help-block" style="margin-bottom: 0px;">
                                    <small><i>*ketikkan nama kategori untuk pencarian data, lalu tekan Enter</i></small>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="tabel_data" class="table table-hover table-bordered">
                                        <thead>
                                            <tr class="biru">
                                                <th style="color:#fff; text-align:center;">No</th>
                                                <th style="color:#fff; text-align:center;">Kategori</th>
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
                            <div class="col-md-10">
                                <div id="tablePaging"> </div>
                            </div>
                            <div class="col-md-2">
                                <h4 class="header-title">Total Data : <b id="total_data"></b></h4>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-9">
                                
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

            	<div id="tambah_kategori1" class="tab-pane fade in">
	            	<div class="row">
	                    <div class="col-md-12">
	                        <form class="form-horizontal" role="form" action="<?php echo base_url(); ?>finance/admum_setup_kategori_c/simpan" method="post">
	                        	<div class="form-group">
			                        <label class="col-md-2 control-label">Nama Kategori</label>
			                        <div class="col-md-4">
			                            <input type="text" class="form-control" name="nama_kategori" id="nama_kategori" value="" onkeyup="cek_kategori();">
			                        </div>
			                    </div>
			                    <hr>
			                    <div class="form-group">
			                        <label class="col-md-2 control-label">&nbsp;</label>
			                        <div class="col-md-3">
			                        	<button type="submit" class="btn btn-success waves-effect waves-light" id="simpan"> 
			                        		<i class="fa fa-save"></i> <span>Simpan</span> 
			                        	</button>
			                        	<button type="reset" class="btn btn-danger waves-effect waves-light"> 
			                        		<i class="fa fa-times"></i> <span>Batal</span> 
			                        	</button>
			                        </div>
			                    </div>
			                </form>
			            </div>
			        </div>
	            </div>
            </div>
        </div>
    </div>

    <div class="card-box card-tabs" id="view_ubah">
    	<h4 class="header-title m-t-0 m-b-30">Ubah Kategori</h4>
    	<form class="form-horizontal" role="form" action="<?php echo base_url(); ?>finance/admum_setup_kategori_c/ubah" method="post">
    		<input type="hidden" name="id_ubah" id="id_ubah" value="">
        	<div class="form-group">
                <label class="col-md-2 control-label">Nama Kategori</label>
                <div class="col-md-6">
                    <input type="text" class="form-control" name="nama_kategori_ubah" id="nama_kategori_ubah" value="">
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

<button id="popup_hps" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#custom-width-modal" style="display:none;">Custom width Modal</button>
<div id="custom-width-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="custom-width-modalLabel">Konfirmasi Hapus</h4>
            </div>
            <div class="modal-body">
                <p id="msg"></p>
            </div>
            <div class="modal-footer">
            	<form action="<?php echo base_url(); ?>finance/admum_setup_kategori_c/hapus" method="post">
            		<input type="hidden" name="id_hapus" id="id_hapus" value="">
	                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Tidak</button>
	                <button type="submit" class="btn btn-danger waves-effect waves-light">Ya</button>
            	</form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script type="text/javascript" src="<?php echo base_url(); ?>js-devan/jquery-1.11.1.min.js"></script>

<style type="text/css">
#view_ubah, #tombol_reset{
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

    data_jenis_penyakit();

    $("#jumlah_tampil").change(function(){
    	data_jenis_penyakit();
    });

	$('#btn_tambah').click(function(){
		get_kode();
	});
});

var ajax = "";

function get_kode(){
	$.ajax({
        url : '<?php echo base_url(); ?>admum/admum_jenis_penyakit_c/kode',
        type : "POST",
        dataType : "json",
        success : function(kode){
            $('#kode').val(kode);
        }
    });
}

function paging($selector){
	var jumlah_tampil = $('#jumlah_tampil').val();

    if(typeof $selector == 'undefined')
    {
        $selector = $("#tabel_jenis tbody tr");
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

function data_jenis_penyakit(){
	$('#popup_load').show();
	var keyword = $('#cari_jenis').val();

	if(ajax){
		ajax.abort();
	}

	ajax = $.ajax({
		url : '<?php echo base_url(); ?>admum/admum_jenis_penyakit_c/data_jenis_penyakit',
		data : {keyword:keyword},
		type : "GET",
		dataType : "json",
		success : function(result){
			$tr = "";

			if(result == "" || result == null){
				$tr = "<tr><td style='text-align:center;' colspan='5'><b>Data Tidak Ada</b></td></tr>";
			}else{
				var bekam = 0;

				for(var i=0; i<result.length; i++){
					bekam++;

					var aksi =  '<button type="button" class="btn btn-success btn-sm m-b-5" onclick="ubah_jenis('+result[i].ID+');">'+
									'<i class="fa fa-pencil"></i>'+
								'</button>&nbsp;'+
						   		'<button type="button" class="btn btn-danger btn-sm m-b-5" onclick="hapus_jenis('+result[i].ID+');">'+
						   			'<i class="fa fa-trash"></i>'+
						   		'</button>';

					$tr += "<tr>"+
								"<td width='5%' style='text-align:center;'>"+bekam+"</td>"+
								"<td width='30%'>"+result[i].KODE+"</td>"+
								"<td width='50%'>"+result[i].URAIAN+"</td>"+
								"<td width='15%' align='center'>"+aksi+"</td>"+
							"</tr>";
				}
			}

			$('#tabel_jenis tbody').html($tr);
			$('#popup_load').fadeOut();
			paging();
		}
	});

	$('#tombol_cari').click(function(){
		data_jenis_penyakit();
		$('#tombol_reset').show();
		$('#tombol_cari').hide();
	});

	$('#tombol_reset').click(function(){
		$('#cari_satuan').val("");
		data_jenis_penyakit();
		$('#tombol_reset').hide();
		$('#tombol_cari').show();
	});
}

function onEnterText(e){
    if (e.keyCode == 13) {
        data_jenis_penyakit();
        $('#tombol_reset').show();
		$('#tombol_cari').hide();
        return false;
    }
}

function ubah_jenis(id){
	$('#view_ubah').show();
	$('#view_data').hide();

	$.ajax({
		url : '<?php echo base_url(); ?>admum/admum_jenis_penyakit_c/data_jenis_penyakit_id',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(row){
			$('#id_ubah').val(id);
			$('#kode_ubah').val(row['KODE']);
			$('#uraian_ubah').val(row['URAIAN']);
		}
	});

	$('#batal_ubah').click(function(){
		$('#view_ubah').hide();
		$('#view_data').show();
	});
}

function hapus_jenis(id){
	$('#popup_hps').click();

	$.ajax({
		url : '<?php echo base_url(); ?>admum/admum_jenis_penyakit_c/data_jenis_penyakit_id',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(row){
			$('#id_hapus').val(id);
			var txt = row['KODE'];
			$('#msg').html('Apakah jenis penyakit dengan kode <b>'+txt+'</b> ingin dihapus?');
		}
	});
}
</script>

<div id="popup_load">
    <div class="window_load">
        <img src="<?=base_url()?>picture/progress.gif" height="100" width="125">
    </div>
</div>

<div class="col-sm-12">
    <div class="card-box" id="view_data">
        <div class="row">
            <div class="col-lg-12">
                <ul class="nav nav-tabs">
                    <li role="presentation" class="active">
                        <a href="#home1" role="tab" data-toggle="tab"><i class="fa fa-list-alt"></i> Data Jenis Penyakit</a>
                    </li>
                    <li role="presentation">
                        <a href="#profile1" role="tab" data-toggle="tab" id="btn_tambah"><i class="fa fa-plus-square"></i> Tambah Data</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane fade in active" id="home1">
                    	<form class="form-horizontal" role="form" action="<?php echo $url_cetak; ?>" target="_blank" method="post">
		                    <div class="form-group">
		                        <div class="col-md-4 pull-right">
		                        	<button style="float: left; margin-right: 10px; margin-top: 2px;" type="submit" class="btn btn-success waves-effect w-md waves-light m-b-5"><i class="fa fa-file-text-o"></i> <b>Cetak Excel</b></button>
		                            <div class="input-group">
					                    <input type="text" class="form-control" id="cari_jenis" placeholder="Cari..." value="" onkeypress="return onEnterText(event);">
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
	                        <table class="table table-hover table-bordered table-striped" id="tabel_jenis">
	                            <thead>
	                                <tr class="biru">
	                                    <th style="text-align:center; color: #fff;" width="5%">No</th>
	                                    <th style="text-align:center; color: #fff;" width="30%">Kode</th>
	                                    <th style="text-align:center; color: #fff;" width="50%">Uraian</th>
	                                    <th style="text-align:center; color: #fff;" width="15%">Aksi</th>
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

                    <div role="tabpanel" class="tab-pane fade" id="profile1">
                    	<form class="form-horizontal" role="form" action="<?php echo $url_simpan; ?>" method="post">
                    		<div class="row">
			                    <div class="form-group">
			                    	<label class="col-md-2 control-label">Kode</label>
			                        <div class="col-md-4">
			                            <div class="input-group">
			                                <span class="input-group-btn">
			                                    <button type="button" class="btn btn-default" style="cursor:default;">
			                                        <i class="fa fa-lock"></i>
			                                    </button>
			                                </span>
			                                <input type="text" class="form-control" name="kode" id="kode" value="" readonly>
			                            </div>
			                        </div>
			                    </div>
			                    <div class="form-group">
			                    	<label class="col-md-2 control-label">Uraian</label>
			                        <div class="col-md-5">
			                        	<textarea class="form-control" rows="3" name="uraian"></textarea>
			                       </div>
			                    </div>
                    		</div>
                    		<hr>

				            <center>
				                <button type="submit" id="btn_simpan" class="btn btn-success m-b-5"> <i class="fa fa-save"></i> <span>Simpan</span> </button>
				                <button type="button" id="btn_batal" class="btn btn-danger m-b-5"> <i class="fa fa-times"></i> <span>Batal</span> </button>
				            </center>
		                </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card-box card-tabs" id="view_ubah">
    	<form class="form-horizontal" role="form" action="<?php echo $url_ubah; ?>" method="post">
    		<h4 class="m-t-0 m-b-30 page-header header-title"><b>Ubah Data</b></h4>
    		<div class="row">
	    		<input type="hidden" name="id_ubah" id="id_ubah" value="">
	        	<div class="form-group">
	                <label class="col-md-2 control-label">Kode</label>
	                <div class="col-md-4">
	                    <input type="text" class="form-control" name="kode_ubah" id="kode_ubah" value="" readonly>
	                </div>
	            </div>
	            <div class="form-group">
	                <label class="col-md-2 control-label">Uraian</label>
	                <div class="col-md-6">
	                    <textarea class="form-control" name="uraian_ubah" id="uraian_ubah" rows="3"></textarea>
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
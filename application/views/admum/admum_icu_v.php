<script type="text/javascript" src="<?php echo base_url(); ?>js-devan/jquery-1.11.1.min.js"></script>

<style type="text/css">
#tombol_reset, #view_ubah{
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

	get_data();

	$('#tambah_data').click(function(){
		$.ajax({
			url : '<?php echo base_url(); ?>admum/admum_icu_c/get_kode',
			type : "POST",
			dataType : "json",
			success : function(kode){
				$('#kode').val(kode);
			}
		});
	});

	$('#jumlah_tampil').change(function(){
		get_data();
	});

	$('#level').click(function(){
		var level = $('#level').val();
		var tipe = "";

		if(level == '1'){
			tipe = "C dan D";
		}else if(level == '2'){
			tipe = "B";
		}else{
			tipe = "A";
		}

		$('#tipe').val(tipe);
	});

});

function paging($selector){
    var jumlah_tampil = $('#jumlah_tampil').val();

    if(typeof $selector == 'undefined')
    {
        $selector = $("#tabel_ruang tbody tr");
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

function get_data(){
	$('#popup_load').show();
	var keyword = $('#cari').val();

	$.ajax({
		url : '<?php echo base_url(); ?>admum/admum_icu_c/data_ruang',
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

					var aksi =  '<button type="button" class="btn btn-success btn-sm m-b-5" onclick="ubah('+result[i].ID+');">'+
                                    '<i class="fa fa-pencil"></i>'+
                                '</button>&nbsp;'+
                                '<button type="button" class="btn btn-danger btn-sm m-b-5" onclick="hapus('+result[i].ID+');">'+
                                    '<i class="fa fa-trash"></i>'+
                                '</button>';

                    var stt = "";
                    if(result[i].STATUS_PAKAI == '0'){
                    	stt = '<span class="label label-danger">Kosong</span>';
                    }else{
                    	stt = '<span class="label label-success">Terpakai</span>';
                    }

					$tr += "<tr>"+
								"<td style='text-align:center;'>"+no+"</td>"+
								"<td style='text-align:center;'>"+result[i].KODE+"</td>"+
								"<td>"+result[i].NAMA_RUANG+"</td>"+
								"<td>Level "+angkaRomawi(result[i].LEVEL)+"</td>"+
								"<td>Tipe "+result[i].TIPE+"</td>"+
								"<td style='text-align:center;'>"+stt+"</td>"+
								"<td align='center'>"+aksi+"</td>"+
							"</tr>";
				}
			}

			$('#tabel_ruang tbody').html($tr);
			paging();
			$('#popup_load').fadeOut();
		}
	});

	$('#tombol_cari').click(function(){
        get_data();
        $('#tombol_reset').show();
        $('#tombol_cari').hide();
    });

    $('#tombol_reset').click(function(){
        $('#cari').val("");
        get_data();
        $('#tombol_reset').hide();
        $('#tombol_cari').show();
    });
}

function onEnterText(e){
    if (e.keyCode == 13) {
        get_data();
        $('#tombol_reset').show();
        $('#tombol_cari').hide();
        return false;
    }
}

function ubah(id){
	$('#view_ubah').show();
	$('#view_data').hide();

	$.ajax({
		url : '<?php echo base_url(); ?>admum/admum_icu_c/data_ruang_id',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(row){
			$('#id_ubah').val(id);
			$('#kode_ubah').val(row['KODE']);
			$('#nama_ruang_ubah').val(row['NAMA_RUANG']);
			if(row['LEVEL'] == "1"){
                $('#level_ubah option[value="1"]').attr('selected','selected');
            }else if(row['LEVEL'] == '2'){
                $('#level_ubah option[value="2"]').attr('selected','selected');
            }else if(row['LEVEL'] == '3'){
            	$('#level_ubah option[value="3"]').attr('selected','selected');
            }
            $('#tipe_ubah').val(row['TIPE']);
			$('#keterangan_ubah').val(row['KETERANGAN']);
		}
	});

	$('#batal_ubah').click(function(){
		$('#view_ubah').hide();
		$('#view_data').show();
		$('#id_ubah').val("");
	});
}

function hapus(id){
	$('#popup_hapus').click();

	$.ajax({
		url : '<?php echo base_url(); ?>admum/admum_icu_c/data_ruang_id',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(row){
			$('#id_hapus').val(id);
			$('#msg').html('Apakah data <b>'+row['NAMA_RUANG']+'</b> ingin dihapus?');
		}
	});
}
</script>

<div id="popup_load">
    <div class="window_load">
        <img src="<?=base_url()?>picture/progress.gif" height="100" width="125">
    </div>
</div>

<div class="col-lg-12" id="view_data">
	<div class="card-box">
		<div class="row">
			<ul class="nav nav-tabs">
                <li role="presentation" class="active">
                    <a href="#home1" role="tab" data-toggle="tab"><i class="fa fa-table"></i> Data</a>
                </li>
                <li role="presentation" id="tambah_data">
                    <a href="#profile1" role="tab" data-toggle="tab"><i class="fa fa-plus"></i> Tambah Data</a>
                </li>
            </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade in active" id="home1">
                	<form class="form-horizontal" role="form">
	                	<div class="form-group">
			                <div class="col-md-8">
			                    &nbsp;
			                </div>
			                <div class="col-md-4">
				                <div class="input-group">
				                    <input type="text" class="form-control" id="cari" placeholder="Cari..." value="" onkeypress="return onEnterText(event);">
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
			            <table id="tabel_ruang" class="table table-bordered">
			                <thead>
			                    <tr class="biru">
			                        <th style="color:#fff; text-align:center;" width="50">No</th>
			                        <th style="color:#fff; text-align:center;">Kode</th>
			                        <th style="color:#fff; text-align:center;">Nama Ruang</th>
			                        <th style="color:#fff; text-align:center;">Level</th>
			                        <th style="color:#fff; text-align:center;">Tipe</th>
			                        <th style="color:#fff; text-align:center;">Status Pakai</th>
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

                <div role="tabpanel" class="tab-pane fade" id="profile1">
                	<form class="form-horizontal" role="form" action="<?php echo $url_simpan; ?>" method="post">
                		<div class="card-box">
				            <div class="row">
				            	<div class="form-group">
			                        <label class="col-md-2 control-label">Kode</label>
			                        <div class="col-md-5">
			                            <input type="text" class="form-control" name="kode" id="kode" value="" readonly>
			                        </div>
			                    </div>
			                    <div class="form-group">
			                        <label class="col-md-2 control-label">Nama Ruang</label>
			                        <div class="col-md-5">
			                            <input type="text" class="form-control" name="nama_ruang" value="" required="required">
			                        </div>
			                    </div>
			                    <div class="form-group">
			                    	<label class="col-md-2 control-label">Level</label>
			                    	<div class="col-md-5">
			                            <select class="form-control" name="level" id="level">
			                            	<option value="1">I</option>
			                            	<option value="2">II</option>
			                            	<option value="3">III</option>
			                            </select>
			                        </div>
			                    </div>
			                    <div class="form-group">
			                        <label class="col-md-2 control-label">Tipe</label>
			                        <div class="col-md-5">
			                        	<input type="text" class="form-control" name="tipe" id="tipe" value="" readonly>
			                        </div>
			                    </div>
			                    <div class="form-group">
			                        <label class="col-md-2 control-label">Keterangan</label>
			                        <div class="col-md-7">
			                        	<textarea class="form-control" name="keterangan" rows="5"></textarea>
			                        </div>
			                    </div>
			                    <div class="form-group">
			                        <label class="col-md-2 control-label">&nbsp;</label>
			                        <div class="col-md-5">
			                        	<button type="submit" class="btn btn-success"><i class="fa fa-save"></i> <b>Simpan</b></button>
			                        	<button type="button" class="btn btn-danger" id="batal"><i class="fa fa-times"></i> <b>Batal</b></button>
			                        </div>
			                    </div>
				            </div>
				        </div>
                	</form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-lg-12" id="view_ubah">
	<div class="card-box">
		<div class="row">
			<h4><i class="fa fa-pencil"></i> Ubah Data</h4>
			<hr>
			<form class="form-horizontal" role="form" action="<?php echo $url_ubah; ?>" method="post">
				<input type="hidden" name="id_ubah" id="id_ubah" value="">
        		<div class="card-box">
		            <div class="row">
		            	<div class="form-group">
	                        <label class="col-md-2 control-label">Kode</label>
	                        <div class="col-md-5">
	                            <input type="text" class="form-control" name="kode_ubah" id="kode_ubah" value="" readonly>
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-md-2 control-label">Nama Ruang</label>
	                        <div class="col-md-5">
	                            <input type="text" class="form-control" name="nama_ruang_ubah" id="nama_ruang_ubah" value="" required="required">
	                        </div>
	                    </div>
	                    <div class="form-group">
	                    	<label class="col-md-2 control-label">Level</label>
	                    	<div class="col-md-5">
	                            <select class="form-control" name="level_ubah" id="level_ubah">
	                            	<option value="1">I</option>
	                            	<option value="2">II</option>
	                            	<option value="3">III</option>
	                            </select>
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-md-2 control-label">Tipe</label>
	                        <div class="col-md-5">
	                        	<input type="text" class="form-control" name="tipe_ubah" id="tipe_ubah" value="" readonly>
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-md-2 control-label">Keterangan</label>
	                        <div class="col-md-7">
	                        	<textarea class="form-control" name="keterangan_ubah" id="keterangan_ubah" rows="5"></textarea>
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-md-2 control-label">&nbsp;</label>
	                        <div class="col-md-5">
	                        	<button type="submit" class="btn btn-success"><i class="fa fa-save"></i> <b>Simpan</b></button>
	                        	<button type="button" class="btn btn-danger" id="batal_ubah"><i class="fa fa-times"></i> <b>Batal</b></button>
	                        </div>
	                    </div>
		            </div>
		        </div>
        	</form>
		</div>
	</div>
</div>

<button class="btn btn-primary" data-toggle="modal" data-target="#custom-width-modal" id="popup_hapus" style="display:none;">Custom width Modal</button>
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
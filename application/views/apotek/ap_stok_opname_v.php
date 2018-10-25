<script type="text/javascript" src="<?php echo base_url(); ?>js-devan/jquery-1.11.1.min.js"></script>

<style type="text/css">
#view_ubah, #tombol_reset{
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

	data_stok();

	data_preview();

	$("#jumlah_tampil").change(function(){
    	data_preview();
    });

  $('#simpan_stok_opname').click(function(){
      $.ajax({
          url : '<?php echo base_url(); ?>apotek/ap_stok_opname_c/simpan_stok_opname',
          data : $('#form_stok_opname').serialize(),
          type : "POST",
          dataType : "json",
          success : function(res){
              window.location.reload(false);
          }
      });
  });

});


function data_stok(){
	$('#popup_load').show();

	if(ajax){
		ajax.abort();
	}

	ajax = $.ajax({
        url : '<?php echo base_url(); ?>apotek/ap_stok_opname_c/get_data_stok',
        type : "GET",
        dataType : "json",
        success : function(result){
        	$tr = "";

        	if(result == "" || result == null){
        		$tr = "<tr><td colspan='5' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
        	}else{
        		var no = 0;

        		for(var i=0; i<result.length; i++){
        			no++;

        			$tr += "<tr>"+
          						"<td style='vertical-align:middle; text-align:center;'>"+
                      "<input type='hidden' name='id_gudang_obat[]' value='"+result[i].ID+"'>"+
                      "<input type='hidden' name='id_setup_nama_obat[]' value='"+result[i].ID_SETUP_NAMA_OBAT+"'>"+no+"</td>"+
          						"<td style='vertical-align:middle;'>"+result[i].NAMA_OBAT+"</td>"+
                      "<td style='vertical-align:middle; text-align:center;'><input type='hidden' name='stok_sistem[]' value='"+result[i].STOK+"'>"+result[i].STOK+"</td>"+
                      "<td style='vertical-align:middle; text-align:center;'><input type='text' class='form-control' size='1' name='stok_fisik[]' required></td>"+
        					   "</tr>";
        		}
        	}

        	$('#tabel_stok tbody').html($tr);
        	$('#popup_load').fadeOut();
        }
    });
}

function data_preview(){
	$('#popup_load').show();

	var keyword = $('#cari_preview').val();

		$.ajax({
        url : '<?php echo base_url(); ?>apotek/ap_stok_opname_c/get_data_preview',
				data : {keyword:keyword},
        type : "GET",
        dataType : "json",
        success : function(result){
        	$tr = "";

        	if(result == "" || result == null){
        		$tr = "<tr><td colspan='5' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
        	}else{
        		var no = 0;

        		for(var i=0; i<result.length; i++){
        			no++;
        			var aksi = '<button type="button" class="btn btn-primary waves-effect waves-light btn-sm m-b-5" onclick="popup_detail_opname('+result[i].ID+', &quot;'+result[i].TANGGAL+'&quot;);">'+
						   			        '<i class="fa fa-eye"></i>'+
						   		       '</button>';

        			$tr += "<tr>"+
          						"<td style='vertical-align:middle; text-align:center;'>"+no+"</td>"+
          						"<td style='vertical-align:middle;'>"+result[i].TANGGAL+" "+result[i].WAKTU+"</td>"+
											"<td style='vertical-align:middle; text-align:center;'>"+result[i].JUMLAH_OBAT+"</td>"+
          						"<td align='center'>"+aksi+"</td>"+
        					   "</tr>";
        		}
        	}

        	$('#tabel_preview tbody').html($tr);
        	paging();
        	$('#popup_load').fadeOut();
        }
    });

	$('#tombol_cari').click(function(){
		data_preview();
		$('#tombol_reset').show();
		$('#tombol_cari').hide();
	});

	$('#tombol_reset').click(function(){
		$('#cari_preview').val("");
		data_preview();
		$('#tombol_reset').hide();
		$('#tombol_cari').show();
	});
}

function paging($selector){
	var jumlah_tampil = $('#jumlah_tampil').val();

    if(typeof $selector == 'undefined'){
        $selector = $("#tabel_preview tbody tr");
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

function popup_detail_opname(id, tanggal){
		$('#popup_detail_opname').click();
		$('#tanggal_klik').val(tanggal);
		data_detail_opname(id, tanggal);
}

function data_detail_opname(id, tanggal){
	var keyword = $('#search_detail_opname').val();
	var tanggal = $('#tanggal_klik').val();
	$.ajax({
		url : '<?php echo base_url(); ?>apotek/ap_stok_opname_c/data_detail_opname',
		data : {
			keyword:keyword,
			tanggal:tanggal
		},
		type : "GET",
		dataType : "json",
		success : function(result){
			var table = '';
			if(result == null || result == ""){
					table = "<tr><td colspan='6' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
			}else{
					var no = 0;
					for(var i=0; i<result.length; i++){
							no++;
							table += "<tr>"+
													"<td style='text-align:center;'>"+no+"</td>"+
													"<td style='text-align:center;'>"+result[i].NAMA_OBAT+"</td>"+
													"<td style='text-align:center;'>"+result[i].STOK_SISTEM+"</td>"+
													"<td style='text-align:center;'>"+result[i].STOK_FISIK+"</td>"+
													"<td style='text-align:center;'>"+result[i].SELISIH+"</td>"+
													"<td style='text-align:center;'>"+result[i].TANGGAL+" "+result[i].WAKTU+"</td>"+
											"</tr>";
					}
			}
			$('#tabel_detail_stok_opname tbody').html(table);
			// paging_pembayaran();
		}
	});

	$('#search_detail_opname').off('keyup').keyup(function(){
			data_detail_opname();
	});
}

function onEnterText(e){
    if (e.keyCode == 13) {
        data_preview();
        $('#tombol_reset').show();
		$('#tombol_cari').hide();
        return false;
    }
}

function ubah_jenis(id){
	$('#view_ubah').show();
	$('#view_data').hide();

	$.ajax({
		url : '<?php echo base_url(); ?>apotek/ap_stok_opname_c/data_stok_id',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(row){
			$('#id_ubah').val(id);
			$('#nama_jenis_ubah').val(row['NAMA_JENIS']);
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
		url : '<?php echo base_url(); ?>apotek/ap_stok_opname_c/data_stok_id',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(row){
			$('#id_hapus').val(id);
			var txt = row['NAMA_JENIS'];
			$('#msg').html('Apakah jenis obat <b>'+txt+'</b> ingin dihapus?');
		}
	});
}
</script>

<div id="popup_load">
    <div class="window_load">
        <img src="<?=base_url()?>picture/progress.gif" height="100" width="125">
    </div>
</div>

<div class="col-lg-12">
    <div class="card-box card-tabs" id="view_data">
    	<ul class="nav nav-pills">
            <li class="active">
                <a href="#cardpills-1" data-toggle="tab" aria-expanded="true">Stock Opname</a>
            </li>
            <li>
                <a href="#cardpills-2" data-toggle="tab" aria-expanded="false">Preview Stock Opname</a>
            </li>
        </ul>
        <h4 class="header-title">&nbsp;</h4>
    	<div class="tab-content">
            <div id="cardpills-1" class="tab-pane fade in active">
            	<!-- <form class="form-horizontal" role="form">
		            <div class="form-group">
		            	<div class="col-md-7">
                			&nbsp;
            			</div>
		                <div class="col-md-4 pull-right">
			                <div class="input-group m-t-10">
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
		        </form> -->
            <form class="form-horizontal" role="form" id="form_stok_opname"  method="post">
            	<div class="table-responsive">
		            <table id="tabel_stok" class="table table-striped table-bordered">
		                <thead>
		                    <tr class="biru">
		                        <th style="color:#fff; text-align:center;" width="50">No</th>
		                        <th style="color:#fff; text-align:center;">Nama Obat</th>
                            <th style="color:#fff; text-align:center;">Stok Sistem</th>
                            <th style="color:#fff; text-align:center;">Stok Fisik</th>
		                        <!-- <th style="color:#fff; text-align:center;">Aksi</th> -->
		                    </tr>
		                </thead>
		                <tbody>

		                </tbody>
		            </table>
		          </div>
              <div class="form-group">
                  <div class="col-md-12">
                     <button type="submit" class="btn btn-success waves-effect waves-light m-b-5 col-md-12" id="simpan_stok_opname"> <i class="fa fa-save"></i> <span>Simpan</span> </button>
                  </div>
              </div>
		        </form>
          </div>

            <div id="cardpills-2" class="tab-pane fade in">
							<form class="form-horizontal" role="form">
								<div class="form-group">
									<div class="col-md-7">
											&nbsp;
									</div>
										<div class="col-md-4 pull-right">
											<div class="input-group m-t-10">
													<input type="text" class="form-control" id="cari_preview" placeholder="Cari..." value="" onkeypress="return onEnterText(event);">
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
								<table id="tabel_preview" class="table table-striped table-bordered">
										<thead>
												<tr class="biru">
														<th style="color:#fff; text-align:center;" width="50">No</th>
														<th style="color:#fff; text-align:center;">Tanggal</th>
														<th style="color:#fff; text-align:center;">Jumlah Obat</th>
														<th style="color:#fff; text-align:center;">Aksi</th>
														<!-- <th style="color:#fff; text-align:center;">Aksi</th> -->
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


<button class="btn btn-danger" data-toggle="modal" id="popup_detail_opname" style="display:none;" data-target="#modal-large">Show me</button>
<div class="modal fade" id="modal-large" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
						<div class="modal-dialog modal-lg">
								<div class="modal-content">
										<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
												<center><h4 class="modal-title" id="myModalLabel">Data Detail Stok Opname</h4></center>
										</div>
										<div class="modal-body">
											<div class="input-group">
												<input type="text" class="form-control" id="search_detail_opname">
												<span class="input-group-addon bg-blue"><span class="arrow"><i class="fa fa-search"></i></span></span>
											</div>
											<br>
											<input type="hidden" id="tanggal_klik" value="">
											<table id="tabel_detail_stok_opname" class="table table-bordered">
												<thead>
													<tr class="biru">
														<th style="color:#fff; text-align:center;">No</th>
														<th style="color:#fff; text-align:center;">Nama Obat</th>
														<th style="color:#fff; text-align:center;">Stok Sistem</th>
														<th style="color:#fff; text-align:center;">Stok Fisik</th>
														<th style="color:#fff; text-align:center;">Selisih</th>
														<th style="color:#fff; text-align:center;">Tanggal Waktu</th>
													</tr>
												</thead>
												<tbody>
												</tbody>
											</table>
											<center>
												<div id="tablePagingdetail">
												</div>
											</center>
										</div>
										<div class="modal-footer">
												<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
										</div>
								</div>
						</div>
				</div>

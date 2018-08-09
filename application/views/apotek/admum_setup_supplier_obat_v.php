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

	get_kode_supplier();
	data_supplier();

	$('#batal').click(function(){
		window.location = "<?php echo base_url(); ?>apotek/admum_setup_supplier_obat_c";
	});

	$("#jumlah_tampil").change(function(){
    	data_supplier();
    });

    $(".num_only").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl+A, Command+A
            (e.keyCode == 65 && ( e.ctrlKey === true || e.metaKey === true ) ) || 
             // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
});

var ajax = "";

function get_kode_supplier(){
	$.ajax({
        url : '<?php echo base_url(); ?>apotek/admum_setup_supplier_obat_c/kode_supplier_obat',
        type : "POST",
        dataType : "json",
        success : function(kode){
            $('#kode_supplier').val(kode);
        }
    });
}

function paging($selector){
	var jumlah_tampil = $('#jumlah_tampil').val();

    if(typeof $selector == 'undefined')
    {
        $selector = $("#tabel_supplier tbody tr");
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

function data_supplier(){
	$('#popup_load').show();
	var keyword = $('#cari_supplier').val();

	if(ajax){
		ajax.abort();
	}

	ajax = $.ajax({
        url : '<?php echo base_url(); ?>apotek/admum_setup_supplier_obat_c/get_data_supplier',
        data : {keyword:keyword},
        type : "GET",
        dataType : "json",
        success : function(result){
        	$tr = "";

        	if(result == "" || result == null){
        		$tr = "<tr><td colspan='8' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
        	}else{
        		var no = 0;

        		for(var i=0; i<result.length; i++){
        			no++;

        			var aksi =  '<button type="button" class="btn btn-success waves-effect waves-light btn-sm m-b-5" onclick="ubah_supplier('+result[i].ID+');">'+
									'<i class="fa fa-pencil"></i>'+
								'</button>&nbsp;'+
						   		'<button type="button" class="btn btn-danger waves-effect waves-light btn-sm m-b-5" onclick="hapus_supplier('+result[i].ID+');">'+
						   			'<i class="fa fa-trash"></i>'+
						   		'</button>';

        			$tr += "<tr>"+
        						"<td style='vertical-align:middle; text-align:center;'>"+no+"</td>"+
        						"<td style='vertical-align:middle;'>"+result[i].KODE_SUPPLIER+"</td>"+
        						"<td style='vertical-align:middle;'>"+result[i].NAMA_SUPPLIER+"</td>"+
        						"<td style='vertical-align:middle;'>"+result[i].MERK+"</td>"+
        						"<td style='vertical-align:middle;'>"+result[i].ALAMAT+"</td>"+
        						"<td style='vertical-align:middle;'>"+result[i].EMAIL+"</td>"+
        						"<td style='vertical-align:middle; text-align:center;'>"+result[i].TELEPON+"</td>"+
        						"<td align='center'>"+aksi+"</td>"+
        					"</tr>";
        		}
        	}

        	$('#tabel_supplier tbody').html($tr);
        	paging();
        	$('#popup_load').fadeOut();
        }
    });

	$('#tombol_cari').click(function(){
		data_supplier();
		$('#tombol_reset').show();
		$('#tombol_cari').hide();
	});

	$('#tombol_reset').click(function(){
		$('#cari_supplier').val("");
		data_supplier();
		$('#tombol_reset').hide();
		$('#tombol_cari').show();
	});
}

function onEnterText(e){
    if (e.keyCode == 13) {
        data_supplier();
        $('#tombol_reset').show();
		$('#tombol_cari').hide();
        return false;
    }
}

function ubah_supplier(id){
	$('#view_ubah').show();
	$('#view_data').hide();

	$.ajax({
		url : '<?php echo base_url(); ?>apotek/admum_setup_supplier_obat_c/data_supplier_id',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(row){
			$('#id_ubah').val(id);
			$('#kode_supplier_ubah').val(row['KODE_SUPPLIER']);
			$('#nama_supplier_ubah').val(row['NAMA_SUPPLIER']);
			$('#merk_ubah').val(row['MERK']);
			$('#alamat_ubah').val(row['ALAMAT']);
			$('#email_ubah').val(row['EMAIL']);
			$('#telepon_ubah').val(row['TELEPON']);
		}
	});

	$('#batal_ubah').click(function(){
		$('#view_ubah').hide();
		$('#view_data').show();
	});
}

function hapus_supplier(id){
	$('#popup_hps').click();

	$.ajax({
		url : '<?php echo base_url(); ?>apotek/admum_setup_supplier_obat_c/data_supplier_id',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(row){
			$('#id_hapus').val(id);
			var txt = row['KODE_SUPPLIER']+' - '+row['NAMA_SUPPLIER'];
			$('#msg').html('Apakah supplier <b>'+txt+'</b> ingin dihapus?');
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
                <a href="#cardpills-1" data-toggle="tab" aria-expanded="true">Data Supplier Obat</a>
            </li>
            <li>
                <a href="#cardpills-2" data-toggle="tab" aria-expanded="false">Tambah Supplier Obat</a>
            </li>
        </ul>
        <h4 class="header-title">&nbsp;</h4>
    	<div class="tab-content">
            <div id="cardpills-1" class="tab-pane fade in active">
            	<form class="form-horizontal" role="form">
		            <div class="form-group">
		            	<div class="col-md-7">
                			&nbsp;
            			</div>
		                <div class="col-md-4 pull-right">
			                <div class="input-group m-t-10">
			                    <input type="text" class="form-control" id="cari_supplier" placeholder="Cari..." value="" onkeypress="return onEnterText(event);">
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
		            <table id="tabel_supplier" class="table table-striped table-bordered">
		                <thead>
		                    <tr class="biru">
		                        <th style="color:#fff; text-align:center;" width="50">No</th>
		                        <th style="color:#fff; text-align:center;">Kode Supplier</th>
		                        <th style="color:#fff; text-align:center;">Nama Supplier</th>
		                        <th style="color:#fff; text-align:center;">Merk</th>
		                        <th style="color:#fff; text-align:center;">Alamat</th>
		                        <th style="color:#fff; text-align:center;">Email</th>
		                        <th style="color:#fff; text-align:center;">Telepon</th>
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
            <div id="cardpills-2" class="tab-pane fade in">
            	<div class="row">
                    <div class="col-md-12">
                        <form class="form-horizontal" role="form" action="<?php echo $url_simpan; ?>" method="post">
                        	<div class="form-group">
		                        <label class="col-md-2 control-label">Kode Supplier</label>
		                        <div class="col-md-4">
		                            <input type="text" class="form-control" name="kode_supplier" id="kode_supplier" value="" readonly>
		                        </div>
		                    </div>
		                    <div class="form-group">
		                        <label class="col-md-2 control-label">Nama Supplier</label>
		                        <div class="col-md-4">
		                            <input type="text" class="form-control" name="nama_supplier" value="" required="required">
		                        </div>
		                    </div>
		                    <div class="form-group">
		                        <label class="col-md-2 control-label">Merk</label>
		                        <div class="col-md-4">
		                            <input type="text" class="form-control" name="merk" value="" required="required">
		                        </div>
		                    </div>
		                    <div class="form-group">
		                        <label class="col-md-2 control-label">Alamat</label>
		                        <div class="col-md-4">
		                            <textarea name="alamat" class="form-control" rows="5" required="required"></textarea>
		                        </div>
		                    </div>
		                    <div class="form-group">
		                        <label class="col-md-2 control-label">Email</label>
		                        <div class="col-md-4">
		                            <input type="email" class="form-control" name="email" value="">
		                        </div>
		                    </div>
		                    <div class="form-group">
		                        <label class="col-md-2 control-label">Telepon</label>
		                        <div class="col-md-4">
		                            <input type="text" class="form-control num_only" name="telepon" value="" required="required" maxlength="12">
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
            </div>
        </div>
    </div>

    <div class="card-box card-tabs" id="view_ubah">
    	<form class="form-horizontal" role="form" action="<?php echo $url_ubah; ?>" method="post">
    		<input type="hidden" name="id_ubah" id="id_ubah" value="">
        	<div class="form-group">
                <label class="col-md-2 control-label">Kode Supplier</label>
                <div class="col-md-4">
                    <input type="text" class="form-control" id="kode_supplier_ubah" value="" readonly>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Nama Supplier</label>
                <div class="col-md-4">
                    <input type="text" class="form-control" name="nama_supplier_ubah" id="nama_supplier_ubah" value="" required="required">
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Merk</label>
                <div class="col-md-4">
                    <input type="text" class="form-control" name="merk_ubah" id="merk_ubah" value="" required="required">
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Alamat</label>
                <div class="col-md-4">
                    <textarea name="alamat_ubah" id="alamat_ubah" class="form-control" rows="5" required="required"></textarea>
                </div>
            </div>
            <div class="form-group"> 
                <label class="col-md-2 control-label">Email</label>
                <div class="col-md-4">
                    <input type="email" class="form-control" name="email_ubah" id="email_ubah" value="">
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Telepon</label>
                <div class="col-md-4">
                    <input type="text" class="form-control num_only" name="telepon_ubah" id="telepon_ubah" value="" required="required" maxlength="12">
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
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
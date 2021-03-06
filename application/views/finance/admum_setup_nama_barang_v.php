<script type="text/javascript" src="<?php echo base_url(); ?>js-devan/jquery-1.11.1.min.js"></script>

<style type="text/css">
#view_tambah, #view_ubah, #view_kat, #tombol_reset, #msg_barcode{
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

	data_peralatan();

	$("#jumlah_tampil").change(function(){
    	data_peralatan();
    });

	$('#btn_tambah').click(function(){
		$('#view_tambah').show();
		$('#view_data').hide();
		$('#ket').val('Tambah');
		get_kode_peralatan();
	});

	$('#batal').click(function(){
		// window.location = "<?php echo base_url(); ?>finance/admum_setup_nama_barang_c";
        $('#view_tambah').hide();
        $('#view_data').show();
	});

	$('#btn_merk').click(function(){
		$('#ket').val('Tambah');
		$('#popup_merk').click();
		get_merk();
	});

	$('#checkbox2').click(function(){
		var cek = $('#checkbox2').is(":checked");
		if(cek == true){
			$('#view_kat').show();
		}else{
			$('#view_kat').hide();
		}
	});

});

function get_kode_peralatan(){
	$.ajax({
        url : '<?php echo base_url(); ?>finance/admum_setup_nama_barang_c/kode_peralatan',
        type : "POST",
        dataType : "json",
        success : function(kode){
            $('#kode_barang').val(kode);
        }
    });
}

function paging($selector){
	var jumlah_tampil = $('#jumlah_tampil').val();

    if(typeof $selector == 'undefined')
    {
        $selector = $("#tabel_peralatan tbody tr");
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

function data_peralatan(){
	$('#popup_load').show();
	var keyword = $('#cari_alat').val();

	if(ajax){
		ajax.abort();
	}

	ajax = $.ajax({
		url : '<?php echo base_url(); ?>finance/admum_setup_nama_barang_c/get_data_alat',
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

        			var aksi =  '<button type="button" class="btn btn-success waves-effect waves-light btn-sm" onclick="ubah_alat('+result[i].ID+');">'+
									'<i class="fa fa-pencil"></i>'+
								'</button>&nbsp;'+
						   		'<button type="button" class="btn btn-danger waves-effect waves-light btn-sm" onclick="hapus_alat('+result[i].ID+');">'+
						   			'<i class="fa fa-trash"></i>'+
						   		'</button>';

        			$tr += "<tr>"+
        						"<td style='vertical-align:middle; text-align:center;'>"+no+"</td>"+
        						"<td style='vertical-align:middle; text-align:center;'>"+result[i].KODE_ALAT+"</td>"+
        						"<td style='vertical-align:middle;'>"+result[i].NAMA_ALAT+"</td>"+
        						"<td style='vertical-align:middle; text-align:center;'>"+result[i].NAMA_KATEGORI+"</td>"+
        						"<td align='center'>"+aksi+"</td>"+
        					"</tr>";
        		}
        	}

        	$('#tabel_peralatan tbody').html($tr);
        	paging();
        	$('#popup_load').fadeOut();
        }
	});

	$('#tombol_cari').click(function(){
		data_peralatan();
		$('#tombol_reset').show();
		$('#tombol_cari').hide();
	});

	$('#tombol_reset').click(function(){
		$('#cari_alat').val("");
		data_peralatan();
		$('#tombol_reset').hide();
		$('#tombol_cari').show();
	});
}

function onEnterText(e){
    if (e.keyCode == 13) {
        data_peralatan();
        $('#tombol_reset').show();
		$('#tombol_cari').hide();
        return false;
    }
}

function ubah_alat(id){
	$('#view_ubah').show();
	$('#view_data').hide();

	$.ajax({
		url : '<?php echo base_url(); ?>finance/admum_setup_nama_barang_c/data_alat_id',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(row){
			$('#id_ubah').val(id);
			$('#kode_barang_ubah').val(row['KODE_ALAT']);
			$('#nama_barang_ubah').val(row['NAMA_ALAT']);
			$('#id_kat_lama').val(row['ID_KATEGORI']);
            $('#kat_txt').val(row['NAMA_KATEGORI']);
		}
	});

	$('#batal_ubah').click(function(){
		$('#view_ubah').hide();
		$('#view_data').show();
		$('#ket').val("");
	});
}

function hapus_alat(id){
	$('#popup_hps').click();

	$.ajax({
		url : '<?php echo base_url(); ?>finance/admum_setup_nama_barang_c/data_alat_id',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(row){
			$('#id_hapus').val(id);
			var txt = row['KODE_ALAT']+' - '+row['NAMA_ALAT'];
			$('#msg').html('Apakah data alat <b>'+txt+'</b> ingin dihapus?');
		}
	});
}

function cek_barcode(){
    var barcode = $('#barcode').val();
    $.ajax({
        url : '<?php echo base_url(); ?>finance/admum_setup_nama_barang_c/cek_barcode',
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
    	<form class="form-horizontal" role="form" action="<?php echo $url_cetak; ?>" target="_blank" method="post">
            <div class="form-group">
            	<div class="col-md-7">
        			<button type="button" class="btn btn-purple waves-effect w-md waves-light" id="btn_tambah">
        				<i class="fa fa-plus"></i> <b>Tambah Data</b>
        			</button>
                    <button type="submit" class="btn btn-success waves-effect w-md waves-light">
                        <i class="fa fa-file-text-o"></i> <b>Cetak Excel</b>
                    </button>
    			</div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <div class="input-group">
                        <input type="text" class="form-control" id="cari_alat" placeholder="Cari..." value="" onkeypress="return onEnterText(event);">
                        <span class="input-group-btn">
                            <button type="button" class="btn waves-effect waves-light btn-warning" id="tombol_cari">
                                <i class="fa fa-search"></i>
                            </button>
                            <button type="button" class="btn waves-effect waves-light btn-warning" id="tombol_reset">
                                <i class="fa fa-refresh"></i>
                            </button>
                        </span>
                    </div>
                    <span class="help-block" style="margin-bottom: 0px;">
                        <small><i>*pencarian berdasarkan Kode Barang, Nama Barang dan Kategori, lalu tekan Enter</i></small>
                    </span>
                </div>
            </div>
        </form>
    	<div class="table-responsive">
            <table id="tabel_peralatan" class="table table-striped table-bordered">
                <thead>
                    <tr class="biru">
                        <th style="color:#fff; text-align:center;" width="50">No</th>
                        <th style="color:#fff; text-align:center;">Kode Barang</th>
                        <th style="color:#fff; text-align:center;">Nama Barang</th>
                        <th style="color:#fff; text-align:center;">Kategori</th>
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
    	<h4 class="header-title m-t-0 m-b-30">Tambah Peralatan Medis</h4>
    	<form class="form-horizontal" role="form" action="<?php echo $url_simpan; ?>" method="post">
            <div class="form-group">
                <label class="col-md-2 control-label">Kode Barang</label>
                <div class="col-md-4">
                    <input type="text" class="form-control" name="kode_barang" id="kode_barang" value="" readonly>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Nama Barang</label>
                <div class="col-md-4">
                    <input type="text" class="form-control" name="nama_barang" value="" required="required">
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
            <div class="form-group">
                <label class="col-md-2 control-label">Kategori</label>
                <div class="col-md-4">
                    <select name="id_kategori" class="form-control">
                    <?php
                        $kat = $this->model->data_kategori();
                        foreach ($kat as $val) {
                    ?>
                        <option value="<?php echo $val->ID; ?>"><?php echo $val->NAMA_KATEGORI; ?></option>
                    <?php
                        }
                    ?>
                    </select>
                </div>
            </div>
            <hr>
            <div class="form-group">
                <label class="col-md-2 control-label">&nbsp;</label>
                <div class="col-md-3">
                	<button type="submit" class="btn btn-success waves-effect waves-light" id="btn_simpan"> <i class="fa fa-save"></i> <span>Simpan</span> </button>
                	<button type="button" class="btn btn-danger waves-effect waves-light" id="batal"> <i class="fa fa-times"></i> <span>Batal</span> </button>
                </div>
            </div>
        </form>
    </div>

    <div class="card-box card-tabs" id="view_ubah">
    	<form class="form-horizontal" role="form" action="<?php echo $url_ubah; ?>" method="post">
    		<input type="hidden" name="id_ubah" id="id_ubah" value="">
            <div class="form-group">
                <label class="col-md-2 control-label">Kode Barang</label>
                <div class="col-md-6">
                    <input type="text" class="form-control" name="kode_barang_ubah" id="kode_barang_ubah" value="" readonly>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Nama Barang</label>
                <div class="col-md-6">
                    <input type="text" class="form-control" name="nama_barang_ubah" id="nama_barang_ubah" value="">
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Kategori</label>
                <div class="col-md-4">
                	<input type="hidden" class="form-control" name="id_kat_lama" id="id_kat_lama" value="" readonly>
                    <input type="text" class="form-control" id="kat_txt" value="" readonly>
                </div>
                <div class="col-md-2">
                	<div class="checkbox checkbox-primary">
                        <input type="checkbox" id="checkbox2" name="checkbox2">
                        <label for="checkbox2">
                            Ubah
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group" id="view_kat">
                <label class="col-md-2 control-label">&nbsp;</label>
                <div class="col-md-4">
                    <select name="id_kategori_ubah" class="form-control">
                    <?php
                        $kat = $this->model->data_kategori();
                        foreach ($kat as $val) {
                    ?>
                        <option value="<?php echo $val->ID; ?>"><?php echo $val->NAMA_KATEGORI; ?></option>
                    <?php
                        }
                    ?>
                    </select>
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
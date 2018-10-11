<script type="text/javascript" src="<?php echo base_url(); ?>js-devan/jquery-1.11.1.min.js"></script>
<style type="text/css">
#view_ubah,
#tombol_reset{
	display: none;
}
</style>
<script type="text/javascript">
var Base64 = {
    _keyStr: "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",

    encode: function(input) {
        var output = "";
        var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
        var i = 0;

        input = Base64._utf8_encode(input);

        while (i < input.length) {

            chr1 = input.charCodeAt(i++);
            chr2 = input.charCodeAt(i++);
            chr3 = input.charCodeAt(i++);

            enc1 = chr1 >> 2;
            enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
            enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
            enc4 = chr3 & 63;

            if (isNaN(chr2)) {
                enc3 = enc4 = 64;
            } else if (isNaN(chr3)) {
                enc4 = 64;
            }

            output = output + this._keyStr.charAt(enc1) + this._keyStr.charAt(enc2) + this._keyStr.charAt(enc3) + this._keyStr.charAt(enc4);

        }

        return output;
    },


    decode: function(input) {
        var output = "";
        var chr1, chr2, chr3;
        var enc1, enc2, enc3, enc4;
        var i = 0;

        input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");

        while (i < input.length) {

            enc1 = this._keyStr.indexOf(input.charAt(i++));
            enc2 = this._keyStr.indexOf(input.charAt(i++));
            enc3 = this._keyStr.indexOf(input.charAt(i++));
            enc4 = this._keyStr.indexOf(input.charAt(i++));

            chr1 = (enc1 << 2) | (enc2 >> 4);
            chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
            chr3 = ((enc3 & 3) << 6) | enc4;

            output = output + String.fromCharCode(chr1);

            if (enc3 != 64) {
                output = output + String.fromCharCode(chr2);
            }
            if (enc4 != 64) {
                output = output + String.fromCharCode(chr3);
            }

        }

        output = Base64._utf8_decode(output);

        return output;

    },

    _utf8_encode: function(string) {
        string = string.replace(/\r\n/g, "\n");
        var utftext = "";

        for (var n = 0; n < string.length; n++) {

            var c = string.charCodeAt(n);

            if (c < 128) {
                utftext += String.fromCharCode(c);
            }
            else if ((c > 127) && (c < 2048)) {
                utftext += String.fromCharCode((c >> 6) | 192);
                utftext += String.fromCharCode((c & 63) | 128);
            }
            else {
                utftext += String.fromCharCode((c >> 12) | 224);
                utftext += String.fromCharCode(((c >> 6) & 63) | 128);
                utftext += String.fromCharCode((c & 63) | 128);
            }

        }

        return utftext;
    },

    _utf8_decode: function(utftext) {
        var string = "";
        var i = 0;
        var c = c1 = c2 = 0;

        while (i < utftext.length) {

            c = utftext.charCodeAt(i);

            if (c < 128) {
                string += String.fromCharCode(c);
                i++;
            }
            else if ((c > 191) && (c < 224)) {
                c2 = utftext.charCodeAt(i + 1);
                string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
                i += 2;
            }
            else {
                c2 = utftext.charCodeAt(i + 1);
                c3 = utftext.charCodeAt(i + 2);
                string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
                i += 3;
            }

        }

        return string;
    }

}

$(document).ready(function(){
	<?php if($this->session->flashdata('sukses')){?>
        notif_simpan();
    <?php }else if($this->session->flashdata('ubah')){?>
        notif_ubah();
    <?php }else if($this->session->flashdata('hapus')){ ?>
        notif_hapus();
    <?php } ?>

    data_paket();

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

	$('#tombol_cari').click(function(){
		data_paket();
		$('#tombol_reset').show();
		$('#tombol_cari').hide();
	});

	$('#tombol_reset').click(function(){
		$('#cari_nama').val("");
		data_paket();
		$('#tombol_reset').hide();
		$('#tombol_cari').show();
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

function data_paket(){
	$('#popup_load').show();
	var keyword = $('#cari_nama').val();

	$.ajax({
		url : '<?php echo base_url(); ?>finance/setup_nama_paket_c/get_paket',
		data : {keyword:keyword},
		type : "GET",
		dataType : "json",
		success : function(result){
			$tr = "";

			if(result == "" || result == null){
				$tr = "<tr><td colspan='4' style='text-align:center;'><b>Data tidak ditemukan</b></td></tr>";
			}else{
				var no = 0;

				for(var i=0; i<result.length; i++){
					no++;

					var encodedString = Base64.encode(result[i].ID);
                    var aksi =  '<a href="<?php echo base_url(); ?>finance/setup_nama_paket_c/pengaturan_paket/'+encodedString+'" class="btn btn-inverse waves-effect waves-light btn-sm">'+
									'<i class="fa fa-spin fa-cog"></i>'+
								'</a>&nbsp;'+
								'<button type="button" class="btn btn-success waves-effect waves-light btn-sm" onclick="ubah_data('+result[i].ID+');">'+
									'<i class="fa fa-pencil"></i>'+
								'</button>&nbsp;'+
						   		'<button type="button" class="btn btn-danger waves-effect waves-light btn-sm" onclick="hapus_data('+result[i].ID+');">'+
						   			'<i class="fa fa-trash"></i>'+
						   		'</button>';

					$tr += "<tr>"+
								"<td style='vertical-align:middle; text-align:center;'>"+no+"</td>"+
								"<td style='vertical-align:middle;'>"+result[i].NAMA_PAKET+"</td>"+
								"<td style='vertical-align:middle; text-align:center;'>"+result[i].HARI+"</td>"+
								"<td align='center'>"+aksi+"</td>"+
							"</tr>";
				}
			}

			$('#tabel_data tbody').html($tr);
			$('#total_data').html(parseFloat(result.length));
			paging();
			$('#popup_load').fadeOut();
		}
	});
}

function onEnterText(e){
    if (e.keyCode == 13) {
        data_paket();
        $('#tombol_reset').show();
		$('#tombol_cari').hide();
        return false;
    }
}

function ubah_data(id){
	$('#view_ubah').show();
	$('#view_data').hide();

	$.ajax({
		url : '<?php echo base_url(); ?>finance/setup_nama_paket_c/get_paket_id',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(row){
			$('#id_ubah').val(id);
			$('#nama_paket_ubah').val(row['NAMA_PAKET']);
			$('#hari_ubah').val(row['HARI']);
		}
	});

	$('#batal_ubah').click(function(){
		$('#view_ubah').hide();
		$('#view_data').show();
	});
}

function hapus_data(id){
	$('#popup_hps').click();

	$.ajax({
		url : '<?php echo base_url(); ?>finance/setup_nama_paket_c/get_paket_id',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(row){
			$('#id_hapus').val(id);
			var txt = row['NAMA_PAKET'];
			$('#msg').html('Apakah paket <b>'+txt+'</b> ingin dihapus?');
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
                    <a data-toggle="tab" role="tab" href="#daftar1"><i class="fa fa-list"></i> Data Paket</a>
                </li>
                <li role="presentation">
                    <a data-toggle="tab" role="tab" href="#import1"><i class="fa fa-plus"></i> Tambah Paket</a>
                </li>
            </ul>
            <div class="tab-content">
            	<div id="daftar1" class="tab-pane fade in active" role="tabpanel">
			        <form class="form-horizontal" role="form">
			        	<div class="form-group">
			                <div class="col-md-12">
				                <div class="input-group m-t-10">
				                    <input type="text" class="form-control" id="cari_nama" placeholder="Cari..." value="" onkeypress="return onEnterText(event);">
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
			        	<div class="form-group">
			        		<div class="col-md-12">
			                    <div class="table-responsive">
						            <table id="tabel_data" class="table table-bordered">
						                <thead>
						                    <tr class="biru">
						                        <th style="color:#fff; text-align:center;">No</th>
						                        <th style="color:#fff; text-align:center;">Nama Paket</th>
						                        <th style="color:#fff; text-align:center;">Hari</th>
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
			                    <h4 class="header-title pull-right">Total Data : <b id="total_data"></b></h4>
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

            	<div id="import1" class="tab-pane fade in" role="tabpanel">
            		<form class="form-horizontal" role="form" action="<?php echo base_url(); ?>finance/setup_nama_paket_c/simpan" method="post" enctype="multipart/form-data">
			            <div class="form-group">
	                        <label class="col-md-2 control-label">Nama Paket</label>
	                        <div class="col-md-4">
	                            <input type="text" class="form-control" name="nama_paket" value="" required>
	                        </div>
	                    </div>
	                    <div class="form-group">
							<label class="col-md-2 control-label">Jumlah Hari</label>
							<div class="col-md-4">
								<div class="input-group">
                                    <input type="text" class="form-control num_only" name="hari" value="" required>
                                    <span class="input-group-addon">Hari</span>
                                </div>
							</div>
						</div>
	                    <hr>
			            <div class="form-group">
			            	<label class="col-md-2 control-label">&nbsp;</label>
			                <div class="col-md-7">
			                    <button type="submit" class="btn btn-success waves-effect w-md waves-light">Simpan</button>
			                    <button type="reset" class="btn btn-danger waves-effect w-md waves-light">Batal</button>
			                </div>
			            </div>
			        </form>
            	</div>
           	</div>
        </div>
    </div>

    <div class="col-lg-12" id="view_ubah">
    	<div class="card-box">
    		<h4 class="header-title m-t-0 m-b-20">Ubah Nama Paket</h4>
    		<form class="form-horizontal" role="form" action="<?php echo base_url(); ?>finance/setup_nama_paket_c/ubah" method="post" enctype="multipart/form-data">
    			<input type="hidden" name="id_ubah" id="id_ubah" value="">
	            <div class="form-group">
                    <label class="col-md-2 control-label">Nama Paket</label>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="nama_paket_ubah" id="nama_paket_ubah" value="">
                    </div>
                </div>
                <div class="form-group">
					<label class="col-md-2 control-label">Jumlah Hari</label>
					<div class="col-md-4">
						<div class="input-group">
                            <input type="text" class="form-control num_only" name="hari_ubah" id="hari_ubah" value="">
                            <span class="input-group-addon">Hari</span>
                        </div>
					</div>
				</div>
                <hr>
	            <div class="form-group">
	            	<label class="col-md-2 control-label">&nbsp;</label>
	                <div class="col-md-7">
	                    <button type="submit" class="btn btn-success waves-effect w-md waves-light">Simpan</button>
	                    <button type="reset" class="btn btn-danger waves-effect w-md waves-light" id="batal_ubah">Batal</button>
	                </div>
	            </div>
	        </form>
    	</div>
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
            	<form action="<?php echo base_url(); ?>finance/setup_nama_paket_c/hapus" method="post">
            		<input type="hidden" name="id_hapus" id="id_hapus" value="">
	                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Tidak</button>
	                <button type="submit" class="btn btn-danger waves-effect waves-light">Ya</button>
            	</form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
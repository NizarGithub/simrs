<script type="text/javascript" src="<?php echo base_url(); ?>js-devan/jquery-1.11.1.min.js"></script>
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

var ajax = "";
$(document).ready(function(){
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

    <?php if($this->session->flashdata('proses')){?>
        toastr["success"]("Permintaan barang berhasil diproses.", "Notifikasi");
    <?php }else if($this->session->flashdata('batal')){ ?>
    	toastr["success"]("Permintaan barang berhasil dibatalkan.", "Notifikasi");
    <?php } ?>

	get_data_permintaan();

	$('#btn_cari').click(function(){
		get_data_permintaan();
	});

	$('#btn_ya').click(function(){
		var id = $('#id_proses').val();

		$.ajax({
			url : '<?php echo base_url(); ?>finance/permintaan_po_c/diproses',
			data : $('#form_proses').serialize(),
			type : "POST",
			dataType : "json",
			success : function(res){
				var encodedString = Base64.encode(id);
				window.open('<?php echo base_url(); ?>finance/permintaan_po_c/cetak/'+encodedString,'_blank');
				setTimeout(function(){
					window.location = "<?php echo base_url(); ?>finance/permintaan_po_c";
				}, 3000);
			}
		});
	});
});

function get_data_permintaan(){
	$('#popup_load').show();

	var bulan = $('#bulan').val();
	var tahun = $('#tahun').val();

	if(ajax){
		ajax.abort();
	}

	ajax = $.ajax({
		url : '<?php echo base_url(); ?>finance/permintaan_po_c/data_permintaan_barang',
		data : {
			bulan:bulan,
			tahun:tahun
		},
		type : "POST",
		async : false,
		dataType : "json",
		success : function(result){
			$tr = "";

			if(result == "" || result == null){
				$tr = "<tr class='active'><td colspan='8' style='text-align:center;'><b>Data tidak ditemukan</b></td></tr>";
			}else{
				var no = 0;

				for(var i=0; i<result.length; i++){
					no++;

					var stt = '';

					if(result[i].STATUS_BATAL == '1' && result[i].STATUS_PROSES == '0'){
						stt = '<span class="label label-danger">DIBATALKAN</span>';
					}else if(result[i].STATUS_PROSES == '1'){
						stt = '<span class="label label-success">SUDAH DIPROSES</span>';
					}else{
						stt = "<button type='button' class='btn btn-success btn-sm waves-effect w-md waves-light m-r-5' onclick='diproses("+result[i].ID+");'><i class='fa fa-check'></i> Proses</button>"+
							  "<button type='button' class='btn btn-danger btn-sm waves-effect w-md waves-light' onclick='dibatalkan("+result[i].ID+");'><i class='fa fa-times'></i> Batal</button>";
					}

					$tr += "<tr class='info'>"+
								"<td style='vertical-align:middle; text-align:center;'>"+no+"</td>"+
								"<td colspan='6' style='vertical-align:middle;'><b>Kode Permintaan : "+result[i].KODE_PO+"</b></td>"+
								"<td align='center'>"+stt+"</td>"+
							"</tr>";

					var id_permintaan = result[i].ID;

					$.ajax({
						url : '<?php echo base_url(); ?>finance/permintaan_po_c/detail_barang_permintaan',
						data : {id_permintaan:id_permintaan},
						type : "POST",
						dataType : "json",
						async : false,
						success : function(res){
							for(var j=0; j<res.length; j++){

								res[j].NAMA_DIV = res[j].NAMA_DIV==null?"-":res[j].NAMA_DIV;

								$tr += "<tr>"+
											"<td style='vertical-align:middle; text-align:center;'>&nbsp;</td>"+
											"<td style='vertical-align:middle; text-align:center;'>"+res[j].TANGGAL+" - "+res[j].WAKTU+"</td>"+
											"<td style='vertical-align:middle; text-align:center;'>"+res[j].NAMA_DEP+"</td>"+
											"<td style='vertical-align:middle; text-align:center;'>"+res[j].NAMA_DIV+"</td>"+
											"<td style='vertical-align:middle;'>"+res[j].NAMA_ALAT+"</td>"+
											"<td style='vertical-align:middle; text-align:center;'>"+res[j].NAMA_KATEGORI+"</td>"+
											"<td style='vertical-align:middle; text-align:center;'>"+formatNumber(res[j].JUMLAH_PERMINTAAN)+"</td>"+
											"<td style='vertical-align:middle; text-align:center;'>&nbsp;</td>"+
										"</tr>";
							}
						}
					});
				}
			}

			$('#tabel_daftar tbody').html($tr);
			$('#popup_load').hide();
		}
	});
}

function diproses(id){
	$('#popup_proses').click();

	$.ajax({
        url : '<?php echo base_url(); ?>finance/permintaan_po_c/data_barang_diproses',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(res){
        	$tr = "";
        	var no = 0;

            for(var j=0; j<res['res'].length; j++){
            	no++;

				res['res'][j].NAMA_DIV = res['res'][j].NAMA_DIV==null?"-":res['res'][j].NAMA_DIV;

				$tr += "<tr>"+
							"<input type='hidden' name='id_barang_gudang[]' value='"+res['res'][j].ID_BARANG_GUDANG+"'>"+
							"<input type='hidden' name='jumlah_permintaan[]' value='"+res['res'][j].JUMLAH_PERMINTAAN+"'>"+
							"<td style='vertical-align:middle; text-align:center;'>"+no+"</td>"+
							"<td style='vertical-align:middle;'>"+res['res'][j].NAMA_ALAT+"</td>"+
							"<td style='vertical-align:middle; text-align:center;'>"+res['res'][j].NAMA_KATEGORI+"</td>"+
							"<td style='vertical-align:middle; text-align:center;'>"+formatNumber(res['res'][j].JUMLAH_PERMINTAAN)+"</td>"+
						"</tr>";
			}

			$('#tabel_barang tbody').html($tr);

			$('#id_proses').val(id);
            var t = res['row']['KODE_PO'];
            $('#msg_proses').html('<b>Proses kode permintaan <u>'+t+'</u> ini?</b>');
        }
    });
}

function dibatalkan(id){
	$('#popup_batal').click();

	$.ajax({
        url : '<?php echo base_url(); ?>finance/permintaan_po_c/data_permintaan_barang_id',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(row){
            $('#id_batal').val(id);
            var t = row['KODE_PO'];
            $('#msg').html('Apakah kode permintaan <b>'+t+'</b> ingin dibatalkan?');
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
                    <a data-toggle="tab" role="tab" href="#daftar1"><i class="fa fa-list"></i> Daftar Permintaan Barang</a>
                </li>
            </ul>
            <div class="tab-content">
            	<div id="daftar1" class="tab-pane fade in active" role="tabpanel">
            		<form class="form-horizontal">
            			<div class="form-group">
                            <div class="col-md-2">
                                <label for="bulan">Bulan</label>
                                <select class="form-control" name="bulan" id="bulan">
                                <?php
                                    $bulan = array(
                                        0 => "",
                                        1 => "Januari",
                                        2 => "Februari",
                                        3 => "Maret",
                                        4 => "April",
                                        5 => "Mei",
                                        6 => "Juni",
                                        7 => "Juli",
                                        8 => "Agustus",
                                        9 => "September",
                                        10 => "Oktober",
                                        11 => "November",
                                        12 => "Desember"
                                    );
                                    $now = date('n');
                                    $selected = "";

                                    for ($i=0; $i < count($bulan); $i++) { 
                                        if($i == $now){
                                            $selected = "selected='selected'";
                                        }else{
                                            $selected = "";
                                        }
                                ?>
                                    <option <?php echo $selected; ?> value="<?php echo $i; ?>"><?php echo $bulan[$i]; ?></option>
                                <?php
                                    }
                                ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="tahun">Tahun</label>
                                <select class="form-control" name="tahun" id="tahun">
                                <?php
                                    $tahun = date('Y');
                                    $sel = "";
                                    for($i=$tahun-5; $i<$tahun+1; $i++){
                                        if($i == $tahun){
                                            $sel = "selected='selected'";
                                        }else{
                                            $sel = "";
                                        }
                                ?>
                                    <option <?php echo $sel; ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                <?php
                                    }
                                ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="proses">&nbsp;</label><br>
                                <button class="btn btn-warning waves-effect w-md waves-light" id="btn_cari" type="button"><i class="fa fa-search"></i> <b>Cari</b></button>
                            </div>
                        </div>
            			<div class="form-group">
                            <div class="col-md-12">
                                <div class="table-responsive">
						            <table id="tabel_daftar" class="table table-bordered">
						                <thead>
						                    <tr class="biru">
						                        <th style="color:#fff; text-align:center;">No</th>
						                        <th style="color:#fff; text-align:center;">Tanggal / Waktu</th>
						                        <th style="color:#fff; text-align:center;">Departemen</th>
						                        <th style="color:#fff; text-align:center;">Divisi</th>
						                        <th style="color:#fff; text-align:center;">Nama Barang</th>
						                        <th style="color:#fff; text-align:center;">Kategori</th>
						                        <th style="color:#fff; text-align:center;">Jumlah Permintaan</th>
						                        <th style="color:#fff; text-align:center;">Status</th>
						                    </tr>
						                </thead>
						                <tbody>
						                    
						                </tbody>
						            </table>
						        </div>
                            </div>
                        </div>
            		</form>
            	</div>
            </div>
        </div>
    </div>
</div>

<button id="popup_proses" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#custom-width-modal1" style="display:none;">Custom width Modal</button>
<div id="custom-width-modal1" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
        	<form action="" method="post" id="form_proses">
	            <input type="hidden" name="id_proses" id="id_proses" value="">
	            <div class="modal-header">
	                <h4 class="modal-title" id="custom-width-modalLabel">Konfirmasi</h4>
	            </div>
	            <div class="modal-body">
	                <table id="tabel_barang" class="table table-bordered">
		                <thead>
		                    <tr class="merah">
		                        <th style="color:#fff; text-align:center;">No</th>
		                        <th style="color:#fff; text-align:center;">Nama Barang</th>
		                        <th style="color:#fff; text-align:center;">Kategori</th>
		                        <th style="color:#fff; text-align:center;">Jumlah Permintaan</th>
		                    </tr>
		                </thead>
		                <tbody>
		                    
		                </tbody>
		            </table>
		            <div class="alert alert-info alert-dismissable" style="margin-bottom: 0px;">
		            	<center>
		            		<h4 class="m-t-0 header-title"><p id="msg_proses"></p></h4>
		            	</center>
                    </div>
	            </div>
	            <div class="modal-footer">
	            	<center>
		                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="btn_tidak">Tidak</button>
		                <button type="button" class="btn btn-success waves-effect waves-light" id="btn_ya"><i class="fa fa-check"></i> Ya</button>
	            	</center>
	            </div>
        	</form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<button id="popup_batal" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#custom-width-modal" style="display:none;">Custom width Modal</button>
<div id="custom-width-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="custom-width-modalLabel">Konfirmasi Pembatalan</h4>
            </div>
            <div class="modal-body">
                <p id="msg"></p>
            </div>
            <div class="modal-footer">
            	<form action="<?php echo base_url(); ?>finance/permintaan_po_c/dibatalkan" method="post">
            		<input type="hidden" name="id_batal" id="id_batal" value="">
	                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Tidak</button>
	                <button type="submit" class="btn btn-danger waves-effect waves-light">Ya</button>
            	</form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
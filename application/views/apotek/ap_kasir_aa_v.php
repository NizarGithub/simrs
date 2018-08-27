<script type="text/javascript" src="<?php echo base_url(); ?>js-devan/jquery-1.11.1.min.js"></script>

<script type="text/javascript">
var ajax = "";
$(document).ready(function(){
	data_obat();
	get_kode_trx();

	$('#jumlah_tampil').change(function(){
        data_obat();
    });
});

function data_obat(){
	$('#popup_load').show();

	var keyword = $('#cari_obat').val();

	if(ajax){
		ajax.abort();
	}

	ajax = $.ajax({
		url : '<?php echo base_url(); ?>apotek/ap_beli_obat_c/get_data_obat',
        data : {
            keyword:keyword
        },
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

                    var aksi =  '<button type="button" class="btn btn-success waves-light btn-sm" title="Beli" onclick="beli_obat('+result[i].ID+');">'+
                                    '<i class="fa fa-shopping-cart"></i>'+
                                '</button>';

                    var warna = "";

                    if(result[i].AKTIF == 0){
                        warna = "merah_tr";
                    }else{
                        warna = "";
                    }

                    var satuan = "";

                    if(result[i].JUMLAH_BUTIR != 0){
                        satuan = result[i].SATUAN_ISI;
                    }else{
                        satuan = result[i].NAMA_SATUAN;
                    }

        			$tr += "<tr class='"+warna+"'>"+
        						"<td style='text-align:center;'>"+no+"</td>"+
        						"<td>"+
        							"<b>"+result[i].NAMA_OBAT+"</b><br/>"+
                                    "<small>"+result[i].BARCODE+"</small>"+
        						"</td>"+
                                "<td>"+result[i].NAMA_JENIS+"</td>"+
        						"<td style='text-align:right;'>"+NumberToMoney(result[i].HARGA_JUAL)+"</td>"+
        						"<td>"+NumberToMoney(result[i].TOTAL)+"&nbsp;"+satuan+"</td>"+
                                "<td align='center'>"+aksi+"</td>"+
        					"</tr>";
        		}
        	}

        	$('#tabel_obat tbody').html($tr);
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

function get_kode_trx(){
	$.ajax({
		url : '<?php echo base_url(); ?>apotek/ap_beli_obat_c/get_kode',
		type : "POST",
		dataType : "json",
		success : function(kode){
			$('#kode_trx').val(kode);
		}
	});
}

function beli_obat(id){
	$('#popup_load').show();
	$("#tr_0").hide();

	$.ajax({
		url : '<?php echo base_url(); ?>apotek/ap_beli_obat_c/data_obat_id',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(result){
			$tr = "";

			for(var i=0; i<result.length; i++){
				var jumlah_data = $('#tr_'+id).length;
				var aksi = "<button type='button' class='btn waves-light btn-danger' onclick='deleteRow(this); hapus_krj("+id+");'><i class='fa fa-times'></i></button>";
				
				var id_satuan = "";
				var satuan = "";

				if(result[i].JUMLAH_BUTIR != 0){
					id_satuan = 0;
					satuan = result[i].SATUAN_ISI;
				}else{
					id_satuan = result[i].ID_SATUAN;
					satuan = result[i].NAMA_SATUAN;
				}

				if(jumlah_data > 0){
					var jumlah = $('#qty_'+result[i].ID).val();
					$('#qty_'+result[i].ID).val(parseInt(jumlah)+1);
				}else{
					$tr = "<tr id='tr_"+result[i].ID+"'>"+
							"<input type='hidden' id='harga_txt_"+result[i].ID+"' value='"+result[i].HARGA_JUAL+"'>"+
							"<input type='hidden' name='total_txt[]' id='total_txt_"+result[i].ID+"' value=''>"+
							"<td style='vertical-align:middle;'>"+
								result[i].NAMA_OBAT+"<br>"+
								result[i].KODE_OBAT+
							"</td>"+
							"<td style='vertical-align:middle;'>"+result[i].NAMA_JENIS+"</td>"+
							"<td style='vertical-align:middle; text-align:right;'>"+NumberToMoney(result[i].HARGA_JUAL)+"</td>"+
							"<td>"+
								"<div class='col-md-12'>"+
									"<div class='input-group'>"+
									"	 <input type='hidden' name='jumlah_txt[]' id='jumlah_txt_"+result[i].ID+"' value='"+result[i].TOTAL+"'>"+
				                    "    <input type='text' class='form-control' name='qty[]' id='qty_"+result[i].ID+"' value='1' onkeyup='FormatCurrency(this); hitung_jumlah("+result[i].ID+");'>"+
				                    "    <span class='input-group-btn'>"+
				                    "        <button class='btn waves-effect waves-light btn-default' type='button' style='cursor:pointer:default;'>"+
				                    			satuan+
				                    "        </button>"+
				                    "    </span>"+
				                    "</div>"+
			                    "</div>"+
							"</td>"+
							"<td style='vertical-align:middle; text-align:right;'><b id='total_"+result[i].ID+"'></b></td>"+
							"<td align='center'>"+aksi+"</td>"+
						  "</tr>";
				}
			}
			$('#tabel_keranjang tbody').append($tr);
			hitung_jumlah(id);
			$('#popup_load').fadeOut();
		}
	});
}

function deleteRow(btn){
  var row = btn.parentNode.parentNode;
  row.parentNode.removeChild(row);
}

function hitung_jumlah(id){
	var jumlah_txt = $('#jumlah_txt_'+id).val();
	var jumlah = $('#qty_'+id).val();
	var harga = $('#harga_txt_'+id).val();

	if(jumlah == ""){
		jumlah = 0;
	}else{
		jumlah = jumlah.split(',').join('');
	}

	var total = parseFloat(jumlah) * parseFloat(harga);

	if(parseFloat(jumlah) > parseFloat(jumlah_txt)){
		alert('Stok Tidak Mencukupi!');
		$('#simpan_krj').attr('disabled','disabled');
	}else{
		$('#simpan_krj').removeAttr('disabled');
	}

	$('#total_'+id).html(NumberToMoney(total));
	$('#total_txt_'+id).val(total);

	var qty = 0;
	$("input[name='qty[]']").each(function(idz,el){
		var j = el.value;
		if(j == ""){
			j = 0;
		}
		qty += parseFloat(j);
	});
	$('#total_qty').html(NumberToMoney(qty));

	var grandtotal = 0;
	$("input[name='total_txt[]']").each(function(idx,elm){
		var tot = elm.value;
		grandtotal += parseFloat(tot);
	});

	$('#subtotal').html(NumberToMoney(grandtotal));
	$('#grandtotal').html(NumberToMoney(grandtotal));
}

function hapus_krj(id){
	var qty = 0;
	$("input[name='qty[]']").each(function(idz,el){
		var j = el.value;
		qty += parseFloat(j);
	});
	$('#total_qty').html(NumberToMoney(qty));

	var grandtotal = 0;
	$("input[name='total_txt[]']").each(function(idx,elm){
		var tot = elm.value;
		grandtotal += parseFloat(tot);
	});

	$('#subtotal').html(NumberToMoney(grandtotal));
	$('#grandtotal').html(NumberToMoney(grandtotal));
}
</script>

<style type="text/css">
#tombol_reset{
	display: none;
}
</style>

<div id="popup_load">
    <div class="window_load">
        <img src="<?=base_url()?>picture/progress.gif" height="100" width="125">
    </div>
</div>

<?php
function formatTanggal($tanggal){
	$d = substr($tanggal, 0,2);
	$m = substr($tanggal, 3,2);
	$y = substr($tanggal, 6);
	$text = "";

	if($m == '01'){ $text = "Januari";}
	else if($m == '02'){ $text = "Februari";}
	else if($m == '03'){ $text = "Maret";}
	else if($m == '04'){ $text = "April";}
	else if($m == '05'){ $text = "Mei";}
	else if($m == '06'){ $text = "Juni";}
	else if($m == '07'){ $text = "Juli";}
	else if($m == '08'){ $text = "Agustus";}
	else if($m == '09'){ $text = "September";}
	else if($m == '10'){ $text = "Oktober";}
	else if($m == '11'){ $text = "November";}
	else if($m == '12'){ $text = "Desember";}

	return $d." ".$text." ".$y;
}
?>

<div class="col-lg-12">
    <div class="card-box">
    	<form class="form-horizontal" role="form">
            <div class="form-group">
                <div class="col-md-7">&nbsp;</div>
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

    	<div class="table-responsive scroll-y">
            <table id="tabel_obat" class="table table-bordered">
                <thead>
                    <tr class="biru">
                        <th style="color:#fff; text-align:center;" width="50">No</th>
                        <th style="color:#fff; text-align:center;">Nama Obat</th>
                        <th style="color:#fff; text-align:center;">Jenis Obat</th>
                        <th style="color:#fff; text-align:center;">Harga Jual</th>
                        <th style="color:#fff; text-align:center;">Stok</th>
                        <th style="color:#fff; text-align:center;">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    
                </tbody>
            </table>
        </div>
        <form class="form-horizontal" role="form">
        	<div class="form-group">&nbsp;</div>
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

    <div class="card-box">
    	<h4 class="m-t-0 page-header header-title"><b>Keranjang Beli </b></h4>
    	<form class="form-horizontal" role="form">
    		<div class="row">
    			<div class="col-lg-6">
		            <div class="form-group">
		                <label class="col-md-2 control-label">No. Trx</label>
		                <div class="col-md-6">
			                <input type="text" class="form-control" id="kode_trx" name="kode_trx" value="" readonly>
		                </div>
		            </div>
    			</div>
    			<div class="col-lg-6">
		            <div class="form-group">
		                <label class="col-md-2 control-label">Tanggal</label>
		                <div class="col-md-6">
			                <input type="text" class="form-control" id="tanggal" name="tanggal" value="<?php echo formatTanggal(date('d-m-Y'));?>" readonly>
		                </div>
		            </div>
    			</div>
    		</div>

	    	<div class="table-responsive scroll-y">
	            <table id="tabel_keranjang" class="table table-bordered">
	                <thead>
	                    <tr class="pink">
	                        <th style="color:#fff; text-align:center;" width="250">Nama Obat</th>
	                        <th style="color:#fff; text-align:center;">Jenis Obat</th>
	                        <th style="color:#fff; text-align:center;">Harga</th>
	                        <th style="color:#fff; text-align:center;" width="150">Qty</th>
	                        <th style="color:#fff; text-align:center;">Total</th>
	                        <th style="color:#fff; text-align:center;">#</th>
	                    </tr>
	                </thead>
	                <tbody>
	                    <tr id="tr_0">
	                    	<td colspan="7" style="text-align:center;"><b>Belum Ada Transaksi</b></td>
	                    </tr>
	                </tbody>
	            </table>
	        </div>

	        <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-6">
                    <div class="clearfix m-t-40">
                        
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-6 col-md-offset-3">
                    <p class="text-right">Total Qty : <b style="color:#00ab6b;" id="total_qty">0</b></p>
                    <p class="text-right">Subtotal : <b style="color:#c800a1;" id="subtotal">0,00</b></p>
                    <hr>
                    <h3 class="text-right" id="grandtotal">0,00</h3>
                </div>
            </div>

            <hr/>

            <div class="row">
		        <div class="hidden-print">
	                <div class="pull-right">
	                    <button type="button" class="btn btn-primary waves-effect waves-light">Simpan</button>
	                </div>
	            </div>
            </div>
        </form>
    </div>
</div>
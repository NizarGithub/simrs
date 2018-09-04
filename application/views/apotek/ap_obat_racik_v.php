<script type="text/javascript" src="<?php echo base_url(); ?>js-devan/jquery-1.11.1.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
	<?php if($this->session->flashdata('sukses')){?>
        notif_simpan();
    <?php }else if($this->session->flashdata('ubah')){?>
        notif_ubah();
    <?php }else if($this->session->flashdata('hapus')){ ?>
        notif_hapus();
    <?php } ?>

    get_kode();

	$('.btn_apoteker').click(function(){
		$('#popup_apoteker').click();
		data_apoteker();
	});

	$('.btn_bahan').click(function(){
		$('#popup_bahan').click();
		data_bahan_racik();
	});
});

function get_kode(){
	$.ajax({
		url : '<?php echo base_url(); ?>apotek/ap_obat_racik_c/get_kode',
		type : "POST",
		dataType : "json",
		success : function(kode){
			$('#sip').val(kode);
		}
	});
}

function data_apoteker(){
	var keyword = $('#cari_apoteker').val();

	$.ajax({
		url : '<?php echo base_url(); ?>apotek/ap_obat_racik_c/data_apoteker',
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

					$tr += "<tr style='cursor:pointer;' onclick='klik_apoteker("+result[i].ID+");'>"+
								"<td style='text-align:center;'>"+no+"</td>"+
								"<td>"+
									result[i].NAMA_PEGAWAI+"<br>"+
									result[i].NIP+
								"</td>"+
								"<td>"+result[i].NAMA_DEP+"</td>"+
								"<td>"+result[i].NAMA_DIV+"</td>"+
								"<td>"+result[i].JABATAN+"</td>"+
							"</tr>";
				}
			}
			$('#tabel_apoteker tbody').html($tr);
		}
	});

	$('#cari_apoteker').off('keyup').keyup(function(){
		data_apoteker();
	});
}

function klik_apoteker(id){
	$('#tutup_apoteker').click();

	$.ajax({
		url : '<?php echo base_url(); ?>apotek/ap_obat_racik_c/klik_apoteker',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(row){
			$('#id_apoteker').val(id);
			$('#nama_apoteker').val(row['NAMA_PEGAWAI']);
		}
	});
}

function data_bahan_racik(){
	var keyword = $('#cari_bahan').val();

	$.ajax({
		url : '<?php echo base_url(); ?>apotek/ap_obat_racik_c/data_bahan_racik',
		data : {keyword:keyword},
		type : "GET",
		dataType : "json",
		success : function(result){
			$tr = "";

			if(result == "" || result == null){
				$tr = "<tr><td colspan='4' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
			}else{
				var no = 0;

				for(var i=0; i<result.length; i++){
					no++;

					var satuan = "";

					if(result[i].JUMLAH_BUTIR != 0){
						satuan = result[i].SATUAN_ISI;
					}else{
						satuan = result[i].NAMA_SATUAN;
					}

					$tr += "<tr style='cursor:pointer;' onclick='klik_racikan("+result[i].ID+");'>"+
								"<td style='text-align:center;'>"+no+"</td>"+
								"<td>"+result[i].KODE_OBAT+"</td>"+
								"<td>"+
									result[i].NAMA_OBAT+"<br>"+
									"<small>"+result[i].BARCODE+"</small>"+
								"</td>"+
								"<td>"+NumberToMoney(result[i].TOTAL)+"&nbsp;"+satuan+"</td>"+
							"</tr>";
				}
			}
			$('#tabel_bahan tbody').html($tr);
		}
	});

	$('#cari_bahan').off('keyup').keyup(function(){
		data_bahan_racik();
	});
}

function klik_racikan(id){
	$('#tutup_bahan').click();

	$.ajax({
		url : '<?php echo base_url(); ?>apotek/ap_obat_racik_c/klik_racikan',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(result){
			$tr = "";

			for(var i=0; i<result.length; i++){
				var jumlah_data = $('#tr_'+result[i].ID).length;
				var id_satuan = "";
				var satuan = "";

				if(result[i].JUMLAH_BUTIR != 0){
					id_satuan = 0;
					satuan = result[i].SATUAN_ISI;
				}else{
					id_satuan = result[i].ID_SATUAN;
					satuan = result[i].NAMA_SATUAN;
				}

				var aksi = "<button type='button' class='btn waves-light btn-danger' onclick='deleteRow(this);'><i class='fa fa-times'></i></button>";

				if(jumlah_data > 0){
					var jumlah = $('#jumlah_'+result[i].ID).val();
					$('#jumlah_'+result[i].ID).val(parseInt(jumlah)+1);
				}else{
					$tr = "<tr id='tr_"+result[i].ID+"'>"+
							"<input type='hidden' name='id_gudang[]' value='"+result[i].ID+"'>"+
							"<input type='hidden' name='id_nama_obat[]' value='"+result[i].ID_NAMA_OBAT+"'>"+
							"<input type='hidden' name='id_satuan[]' value='"+id_satuan+"'>"+
							"<td style='vertical-align:middle;'>"+result[i].KODE_OBAT+"</td>"+
							"<td style='vertical-align:middle;'>"+result[i].NAMA_OBAT+"</td>"+
							"<td style='vertical-align:middle; text-align:center;'>"+NumberToMoney(result[i].TOTAL)+"&nbsp;"+satuan+"</td>"+
							"<td>"+
								"<div class='col-md-12'>"+
									"<div class='input-group'>"+
									"	 <input type='hidden' name='jumlah_txt[]' id='jumlah_txt_"+result[i].ID+"' value='"+result[i].TOTAL+"'>"+
				                    "    <input type='text' class='form-control' name='jumlah[]' id='jumlah_"+result[i].ID+"' value='1' onkeyup='FormatCurrency(this); hitung_jumlah("+result[i].ID+");'>"+
				                    "    <span class='input-group-btn'>"+
				                    "        <button class='btn waves-effect waves-light btn-default' type='button' style='cursor:pointer:default;'>"+
				                    			satuan+
				                    "        </button>"+
				                    "    </span>"+
				                    "</div>"+
			                    "</div>"+
							"</td>"+
							"<td align='center'>"+aksi+"</td>"+
						  "</tr>";
				}
			}
			$('#tabel_racikan tbody').append($tr);
		}
	});
}

function deleteRow(btn){
  var row = btn.parentNode.parentNode;
  row.parentNode.removeChild(row);
}

function hitung_jumlah(id){
	var jumlah_txt = $('#jumlah_txt_'+id).val();
	var jumlah = $('#jumlah_'+id).val();

	if(jumlah == ""){
		jumlah = 0;
	}else{
		jumlah = jumlah.split(',').join('');
	}

	if(parseFloat(jumlah) > parseFloat(jumlah_txt)){
		alert('Stok Tidak Mencukupi!');
		$('#simpan_bahan').attr('disabled','disabled');
	}else{
		$('#simpan_bahan').removeAttr('disabled');
	}
}
</script>

<div id="popup_load">
    <div class="window_load">
        <img src="<?=base_url()?>picture/progress.gif" height="100" width="125">
    </div>
</div>

<div class="col-lg-12">
	<form action="<?php echo $url_simpan; ?>" method="post" class="form-horizontal" role="form">
    	<div class="card-box card-tabs">
    		<div class="form-group">
                <label class="col-md-2 control-label">Nomor Resep</label>
                <div class="col-md-4">
                    <input type="text" class="form-control" name="sip" id="sip" value="" readonly required="required">
                </div>
            </div>
    		<div class="form-group">
                <label class="col-md-2 control-label">Petugas</label>
                <div class="col-md-4">
                    <div class="input-group">
                        <input type="hidden" name="id_apoteker" id="id_apoteker" value="">
                        <input type="text" class="form-control" id="nama_apoteker" value="" readonly required="required">
                        <span class="input-group-btn">
                            <button class="btn waves-effect waves-light btn-default btn_apoteker" type="button">
                                <i class="fa fa-search"></i>
                            </button>
                        </span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Bahan</label>
                <div class="col-md-4">
                    <div class="input-group">
                        <input type="text" class="form-control" value="" readonly>
                        <span class="input-group-btn">
                            <button class="btn waves-effect waves-light btn-default btn_bahan" type="button">
                                <i class="fa fa-search"></i>
                            </button>
                        </span>
                    </div>
                </div>
            </div>
    	</div>

	    <div class="card-box card-tabs">
	    	<h4 class="header-title"><b>Bahan Racikan</b></h4>
	    	<hr/>
	    	<div class="table-responsive">
	            <table id="tabel_racikan" class="table table-bordered">
	                <thead>
	                    <tr class="kuning">
	                        <th style="color:#fff; text-align:center;">Kode Obat</th>
	                        <th style="color:#fff; text-align:center;">Nama Obat</th>
	                        <th style="color:#fff; text-align:center;">Stok</th>
	                        <th style="color:#fff; text-align:center;" width="200">Jumlah Pakai</th>
	                        <th style="color:#fff; text-align:center;">#</th>
	                    </tr>
	                </thead>

	                <tbody>

	                </tbody>
	            </table>
	        </div>
	        <hr>
	        <center>
	        	<button type="submit" class="btn waves-light btn-primary" id="simpan_bahan">Simpan</button>
	        </center>
	    </div>
	</form>
</div>

<button class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#myModal2" id="popup_apoteker" style="display:none;">Standard Modal</button>
<div id="myModal2" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="width:55%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Data Apoteker</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form">
		            <div class="form-group">
		                <div class="col-md-12">
			                <div class="input-group">
			                    <input type="text" class="form-control" id="cari_apoteker" placeholder="Cari..." value="">
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
            		<div class="scroll-y">
		                <table class="table table-hover table-bordered" id="tabel_apoteker">
		                    <thead>
		                        <tr class="merah_popup">
		                            <th style="text-align:center; color: #fff;" width="50">No</th>
		                            <th style="text-align:center; color: #fff;">Nama Apoteker</th>
		                            <th style="text-align:center; color: #fff;">Departemen</th>
		                            <th style="text-align:center; color: #fff;">Divisi</th>
		                            <th style="text-align:center; color: #fff;">Jabatan</th>
		                        </tr>
		                    </thead>
		                    <tbody>

		                    </tbody>
		                </table>
            		</div>
            	</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_apoteker">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<button class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#myModal1" id="popup_bahan" style="display:none;">Standard Modal</button>
<div id="myModal1" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Data Bahan Racikan</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form">
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="input-group">
                                <input type="text" class="form-control" id="cari_bahan" placeholder="Cari..." value="">
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
                    <div class="scroll-y">
                        <table class="table table-hover" id="tabel_bahan">
                            <thead>
                                <tr class="merah_popup">
                                    <th style="text-align:center; color: #fff;" width="50">No</th>
                                    <th style="text-align:center; color: #fff;">Kode Obat</th>
                                    <th style="text-align:center; color: #fff;">Nama Obat</th>
                                    <th style="text-align:center; color: #fff;">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_bahan">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type="text/javascript" src="<?php echo base_url(); ?>js-devan/jquery-1.11.1.min.js"></script>

<style type="text/css">
#view_tindakan_tambah, #view_tindakan_ubah{
	display: none;
}

#view_diagnosa_tambah, #view_diagnosa_ubah{
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

    $('#btn_kembali').click(function(){
    	window.location = "<?php echo base_url(); ?>rekam_medik/rk_indeks_catatan_medis";
    });

	//TINDAKAN

	data_tindakan();

	$('.btn_tindakan').click(function(){
		$('#popup_tindakan').click();
		load_tindakan();
	});

	//DIAGNOSA

	$('#dt_diagnosa').click(function(){
		data_diagnosa();
	});

	$('#btn_tambah_dg').click(function(){
		$('#view_diagnosa_tambah').show();
		$('#view_diagnosa').hide();
		$('#view_diagnosa_ubah').hide();
	});

	$('#batalDg').click(function(){
		$('#view_diagnosa_tambah').hide();
		$('#view_diagnosa').show();
		$('#view_diagnosa_ubah').hide();
	});

	$('.btn_kasus_dg').click(function(){
		$('#popup_kasus_dg').click();
		load_kasus_diagnosa();
	});

	$('.btn_spesialistik_dg').click(function(){
		$('#popup_spesialistik_dg').click();
		load_spesialistik_diagnosa();
	});

	$('#simpanDgUbah').click(function(){
		var diagnosa = $('#diagnosa_ubah').val();
		var tindakan = $('#tindakan_dg_ubah').val();
		var kasus = $('#id_kasus_ubah').val();
		var spesialistik = $('#id_spesialistik_ubah').val();

		if(diagnosa == ""){
			toastr["error"]("Silahkan isi diagnosa dengan benar!", "Notifikasi");
			$('#diagnosa').focus();
		}else if(tindakan == ""){
			toastr["error"]("Silahkan isi tindakan dengan benar!", "Notifikasi");
			$('#tindakan_dg').focus();
		}else if(kasus == ""){
			toastr["error"]("Silahkan isi kasus dengan benar!", "Notifikasi");
		}else if(spesialistik == ""){
			toastr["error"]("Silahkan isi spesialistik dengan benar!", "Notifikasi");
		}else{
			$.ajax({
				url : '<?php echo base_url(); ?>rekam_medik/rk_indeks_catatan_medis/ubah_diagnosa',
				data : $('#view_diagnosa_ubah').serialize(),
				type : "POST",
				dataType : "json",
				success : function(result){
					notif_ubah();
					data_diagnosa();
					$('#view_diagnosa').show();
					$('#view_diagnosa_ubah').hide();
				}
			});
		}
	});
});

//TINDAKAN

function load_tindakan(){
	var keyword = $('#cari_tindakan').val();

	$.ajax({
		url : '<?php echo base_url(); ?>rekam_medik/rk_indeks_catatan_medis/load_tindakan',
		data : {keyword:keyword},
		type : "POST",
		dataType : "json",
		success : function(result){
			$tr = "";

			if(result == "" || result == null){
				$tr = "<tr><td colspan='4' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
			}else{
				var no = 0;

				for(var i=0; i<result.length; i++){
					no++;

					$tr += "<tr style='cursor:pointer;' onclick='klik_tindakan("+result[i].ID+");'>"+
								"<td style='text-align:center;'>"+no+"</td>"+
								"<td>"+result[i].KODE+"</td>"+
								"<td>"+result[i].NAMA_TINDAKAN+"</td>"+
								"<td style='text-align:right;'>"+formatNumber(result[i].TARIF)+"</td>"+
							"</tr>";
				}
			}

			$('#tb_tindakan tbody').html($tr);
		}
	});
}

function klik_tindakan(id){
	$('#tutup_tindakan').click();
	var id_ubah = $('#id_ubah').val();

	if(id_ubah == ""){
		$.ajax({
			url : '<?php echo base_url(); ?>rekam_medik/rk_indeks_catatan_medis/klik_tindakan',
			data : {id:id},
			type : "POST",
			dataType : "json",
			success : function(result){
				$tr = "";

				for(var i=0; i<result.length; i++){
					var jumlah_data = $('#tr_'+result[i].ID).length;

					var aksi = "<button type='button' class='btn waves-light btn-danger btn-sm' onclick='deleteRow(this);'><i class='fa fa-times'></i></button>";

					if(jumlah_data > 0){
						var jumlah = $('#jumlah_'+result[i].ID).val();
						$('#jumlah_'+result[i].ID).val(parseInt(jumlah)+1);
					}else{
						$tr = "<tr id='tr_"+result[i].ID+"'>"+
								"<input type='hidden' name='id_tindakan[]' value='"+result[i].ID+"'>"+
								"<input type='hidden' id='tarif_"+result[i].ID+"' value='"+result[i].TARIF+"'>"+
								"<input type='hidden' name='subtotal[]' id='subtotal_"+result[i].ID+"' value=''>"+
								"<td style='vertical-align:middle;'>"+result[i].NAMA_TINDAKAN+"</td>"+
								"<td style='vertical-align:middle; text-align:right;'>"+formatNumber(result[i].TARIF)+"</td>"+
								"<td>"+
									"<div class='col-md-12'>"+
					                    "<input type='text' class='form-control' name='jumlah[]' id='jumlah_"+result[i].ID+"' value='1' onkeyup='FormatCurrency(this); hitung_jumlah("+result[i].ID+");'>"+
				                    "</div>"+
								"</td>"+
								"<td style='vertical-align:middle; text-align:right;'><b id='subtotal_txt_"+result[i].ID+"'></b></td>"+
								"<td align='center'>"+aksi+"</td>"+
							  "</tr>";
					}
				}

				$('#tabel_tambah_tindakan tbody').append($tr);
				hitung_jumlah(id);
				hitung_tarif_tindakan();
			}
		});
	}else{
		$.ajax({
			url : '<?php echo base_url(); ?>rekam_medik/rk_indeks_catatan_medis/tindakan_id',
			data : {id:id},
			type : "POST",
			dataType : "json",
			success : function(row){
				$('#id_tindakan_ubah').val(row['ID']);
				$('#tindakan_txt').val(row['NAMA_TINDAKAN']);
				$('#tarif_txt').val(formatNumber(row['TARIF']));
				$('#jumlah_ubah').val("");
				$('#subtotal_ubah').val("");
				$('#jumlah_ubah').focus();
			}
		});
	}
}

function data_tindakan(){
	$('#popup_load').show();
	var id = "<?php echo $id; ?>";

	$.ajax({
		url : '<?php echo base_url(); ?>rekam_medik/rk_indeks_catatan_medis/data_tindakan',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(result){
			$tr = "";
			var total = 0;

			if(result == "" || result == null){
				$tr = "<tr><td colspan='7' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
			}else{
				var no = 0;

				for(var i=0; i<result.length; i++){
					no++;

					total += parseFloat(result[i].SUBTOTAL);

					var aksi =  '<button type="button" class="btn btn-success waves-effect waves-light btn-sm m-b-5" onclick="ubah_tindakan('+result[i].ID+');">'+
									'<i class="fa fa-pencil"></i>'+
								'</button>&nbsp;'; 

					var tanggal = formatTanggal(result[i].TANGGAL)+" - "+result[i].WAKTU;

					$tr += "<tr>"+
								"<td style='vertical-align:middle; text-align:center;'>"+no+"</td>"+
								"<td style='vertical-align:middle;'>"+tanggal+"</td>"+
								"<td style='vertical-align:middle;'>"+result[i].NAMA_TINDAKAN+"</td>"+
								"<td style='vertical-align:middle; text-align:right;'>"+formatNumber(result[i].TARIF)+"</td>"+
								"<td style='vertical-align:middle; text-align:center;'>"+formatNumber(result[i].JUMLAH)+"</td>"+
								"<td style='vertical-align:middle; text-align:right;'>"+formatNumber(result[i].SUBTOTAL)+"</td>"+
								"<td align='center'>"+aksi+"</td>"+
							"</tr>";
				}
			}

			$('#tabel_tindakan tbody').html($tr);
			$('#grandtotal_tindakan').html(formatNumber(total));
			$('#popup_load').fadeOut();
		}
	});
}

function ubah_tindakan(id){
	$('#view_tindakan_ubah').show();
	$('#view_tindakan_tambah').hide();
	$('#view_tindakan').hide();

	$.ajax({
		url : '<?php echo base_url(); ?>rekam_medik/rk_indeks_catatan_medis/data_tindakan_id',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(row){
			$('#id_ubah').val(id);
			$('#tanggal_ubah').val(formatTanggal(row['TANGGAL']));
			$('#id_tindakan_ubah').val(row['TINDAKAN']);
			$('#tindakan_txt').val(row['NAMA_TINDAKAN']);
			$('#tarif_txt').val(formatNumber(row['TARIF']));
			$('#jumlah_ubah').val(formatNumber(row['JUMLAH']));
			$('#subtotal_ubah').val(formatNumber(row['SUBTOTAL']));
		}
	});

	$('#batal_ubah').click(function(){
		$('#id_ubah').val("");
		$('#view_tindakan_ubah').hide();
		$('#view_tindakan_tambah').hide();
		$('#view_tindakan').show();
	});
}

function hitung_jumlah2(){
	var tarif = $('#tarif_txt').val();
	var jumlah = $('#jumlah_ubah').val();

	tarif = tarif.split(',').join('');
	jumlah = jumlah.split(',').join('');

	if(tarif == ""){
		tarif = 0;
	}

	if(jumlah == ""){
		jumlah = 0;
	}

	var subtotal = parseFloat(tarif) * parseFloat(jumlah);
	$('#subtotal_ubah').val(formatNumber(subtotal));
}

//DIAGNOSA

function load_kasus_diagnosa(){
	var keyword = $('#cari_kasus_dg').val();

	$.ajax({
		url : '<?php echo base_url(); ?>rekam_medik/rk_indeks_catatan_medis/data_kasus',
		data : {keyword:keyword},
		type : "POST",
		dataType : "json",
		success : function(result){
			$tr = "";

			if(result == "" || result == null){
				$tr = "<tr><td colspan='3' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
			}else{
				var no = 0;

				for(var i=0; i<result.length; i++){
					no++;

					$tr += "<tr style='cursor:pointer;' onclick='klik_kasus("+result[i].ID+");'>"+
								"<td style='text-align:center;'>"+no+"</td>"+
								"<td>"+result[i].KODE+"</td>"+
								"<td>"+result[i].NAMA_KASUS+"</td>"+
							"</tr>";
				}
			}

			$('#tb_kasus_dg tbody').html($tr);
		}
	});

	$('#cari_kasus_dg').off('keyup').keyup(function(){
		load_kasus_diagnosa();
	});
}

function klik_kasus(id){
	$('#tutup_kasus_dg').click();
	
	$.ajax({
		url : '<?php echo base_url(); ?>rekam_medik/rk_indeks_catatan_medis/data_kasus_id',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(row){
			var id_ubah = $('#id_ubah_dg').val();
			if(id_ubah == ""){
				$('#id_kasus').val(id);
				$('#kasus_dg').val(row['NAMA_KASUS']);
				$('#id_kasus_ubah').val("");
				$('#kasus_dg_ubah').val("");
			}else{
				$('#id_kasus').val("");
				$('#kasus_dg').val("");
				$('#id_kasus_ubah').val(id);
				$('#kasus_dg_ubah').val(row['NAMA_KASUS']);
			}
		}
	});
}

function load_spesialistik_diagnosa(){
	var keyword = $('#cari_spesialistik_dg').val();

	$.ajax({
		url : '<?php echo base_url(); ?>rekam_medik/rk_indeks_catatan_medis/data_spesialistik',
		data : {keyword:keyword},
		type : "POST",
		dataType : "json",
		success : function(result){
			$tr = "";

			if(result == "" || result == null){
				$tr = "<tr><td colspan='3' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
			}else{
				var no = 0;

				for(var i=0; i<result.length; i++){
					no++;

					$tr += "<tr style='cursor:pointer;' onclick='klik_spesialistik("+result[i].ID+");'>"+
								"<td style='text-align:center;'>"+no+"</td>"+
								"<td>"+result[i].KODE+"</td>"+
								"<td>"+result[i].NAMA_SPESIALISTIK+"</td>"+
							"</tr>";
				}
			}

			$('#tb_spesialistik_dg tbody').html($tr);
		}
	});

	$('#cari_spesialistik_dg').off('keyup').keyup(function(){
		load_spesialistik_diagnosa();
	});
}

function klik_spesialistik(id){
	$('#tutup_spesialistik_dg').click();
	
	$.ajax({
		url : '<?php echo base_url(); ?>rekam_medik/rk_indeks_catatan_medis/data_spesialistik_id',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(row){
			var id_ubah = $('#id_ubah_dg').val();
			if(id_ubah == ""){
				$('#id_spesialistik').val(id);
				$('#spesialistik_dg').val(row['NAMA_SPESIALISTIK']);
				$('#id_spesialistik_ubah').val("");
				$('#spesialistik_dg_ubah').val("");
			}else{
				$('#id_spesialistik').val("");
				$('#spesialistik_dg').val("");
				$('#id_spesialistik_ubah').val(id);
				$('#spesialistik_dg_ubah').val(row['NAMA_SPESIALISTIK']);
			}
		}
	});
}

function data_diagnosa(){
	$('#popup_load').show();
	var id = "<?php echo $id; ?>";

	$.ajax({
		url : '<?php echo base_url(); ?>rekam_medik/rk_indeks_catatan_medis/data_diagnosa',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(result){
			$tr = "";

			if(result == "" || result == null){
				$tr = "<tr><td colspan='7' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
			}else{
				var no = 0;

				for(var i=0; i<result.length; i++){
					no++;

					var aksi =  '<button type="button" class="btn btn-primary waves-effect waves-light btn-sm m-b-5" onclick="ubah_diagnosa('+result[i].ID+');">'+
									'<i class="fa fa-pencil"></i>'+
								'</button>&nbsp;';

					$tr += "<tr>"+
								"<td style='text-align:center;'>"+no+"</td>"+
								"<td style='text-align:center;'>"+formatTanggal(result[i].TANGGAL)+"</td>"+
								"<td>"+result[i].DIAGNOSA+"</td>"+
								"<td>"+result[i].TINDAKAN+"</td>"+
								"<td>"+result[i].NAMA_KASUS+"</td>"+
								"<td>"+result[i].NAMA_SPESIALISTIK+"</td>"+
								"<td align='center'>"+aksi+"</td>"+
							"</tr>";
				}
			}

			$('#tabel_diagnosa tbody').html($tr);
			$('#popup_load').fadeOut();
		}
	});
}

function ubah_diagnosa(id){
	$('#view_diagnosa_ubah').show();
	$('#view_diagnosa').hide();
	$('#view_diagnosa_tambah').hide();
	var id_pelayanan = "<?php echo $id; ?>";

	$.ajax({
		url : '<?php echo base_url(); ?>rekam_medik/rk_indeks_catatan_medis/data_diagnosa_id',
		data : {id:id,id_pelayanan:id_pelayanan},
		type : "POST",
		dataType : "json",
		success : function(row){
			$('#id_ubah_dg').val(id);
			$('#diagnosa_ubah').val(row['DIAGNOSA']);
			$('#tindakan_dg_ubah').val(row['TINDAKAN']);
			$('#id_kasus_ubah').val(row['ID_KASUS']);
			$('#kasus_dg_ubah').val(row['NAMA_KASUS']);
			$('#id_spesialistik_ubah').val(row['ID_SPESIALISTIK']);
			$('#spesialistik_dg_ubah').val(row['NAMA_SPESIALISTIK']);
		}
	});

	$('#batalDgUbah').click(function(){
		$('#view_diagnosa_ubah').hide();
		$('#view_diagnosa').show();
		$('#view_diagnosa_tambah').hide();
		$('#id_ubah_dg').val("");
	});
}
</script>

<div id="popup_load">
    <div class="window_load">
        <img src="<?=base_url()?>picture/progress.gif" height="100" width="125">
    </div>
</div>

<div class="col-lg-12">
	<div class="row">
		<div class="col-md-6">
            <div class="card-box">
            	<h4><i class="fa fa-user"></i> Pasien</h4>
            	<hr/>
                <div>
                    <div class="text-left">
                        <p class="text-muted font-13">
                        	<strong>NO. RM :</strong> <span class="m-l-15" style="color:#0066b2;"><?php echo $dt->KODE_PASIEN; ?></span>
                        </p>
                        <p class="text-muted font-13">
                        	<strong>NAMA :</strong><span class="m-l-15" style="color:#0066b2;"><?php echo $dt->NAMA_PASIEN; ?></span>
                        </p>
                        <p class="text-muted font-13">
                        	<?php
	                    		$jk = "";
	                    		if($dt->JENIS_KELAMIN=="L"){$jk="Laki - Laki";}else{$jk="Perempuan";}
	                    	?>
                        	<strong>JENIS KELAMIN :</strong> <span class="m-l-15" style="color:#0066b2;"><?php echo $jk; ?></span>
                        </p>
                        <p class="text-muted font-13">
                        	<strong>UMUR :</strong> <span class="m-l-15" style="color:#0066b2;"><?php echo $dt->UMUR; ?> Tahun</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card-box">
            	<h4><i class="fa fa-user-md"></i> Dokter</h4>
            	<hr/>
                <div>
                    <div class="text-left">
                        <p class="text-muted font-13">
                        	<strong>ASAL RUJUKAN :</strong> <span class="m-l-15" style="color:#0066b2;"><?php echo $dt->ASAL_RUJUKAN; ?></span>
                        </p>
                        <p class="text-muted font-13">
                        	<strong>PELAYANAN :</strong> <span class="m-l-15" style="color:#0066b2;"><?php echo $dt->STATUS; ?></span>
                        </p>
                        <p class="text-muted font-13">
                        	<strong>POLI :</strong><span class="m-l-15" style="color:#0066b2;"><?php echo $dt->NAMA_POLI; ?></span>
                        </p>
                        <p class="text-muted font-13">
                        	<strong>DOKTER :</strong><span class="m-l-15" style="color:#0066b2;"><?php echo $dt->NAMA_DOKTER; ?></span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
	</div>
</div>

<div class="col-lg-12">
	<div class="card-box">
		<div class="row">
			<ul class="nav nav-tabs">
                <li role="presentation" class="active">
                    <a href="#tindakan1" role="tab" data-toggle="tab"><i class="fa fa-stethoscope"></i>&nbsp;Tindakan</a>
                </li>
                <li role="presentation" id="dt_diagnosa">
                    <a href="#diagnosa1" role="tab" data-toggle="tab"><i class="fa fa-heartbeat"></i>&nbsp;Diagnosa</a>
                </li>
            </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade in active" id="tindakan1">
                	<form class="form-horizontal" id="view_tindakan">
                    	<div class="form-group">
                    		<div class="col-md-6">
                    			<h4 class="m-t-0"><i class="fa fa-table"></i>&nbsp;<b>Tabel Tindakan</b></h4>
                    		</div>
                    	</div>
                    	<div class="form-group">
                    		<div class="col-md-12"> 
			                    <div class="table-responsive">
						            <table id="tabel_tindakan" class="table table-bordered">
						                <thead>
						                    <tr class="merah">
						                        <th style="color:#fff; text-align:center;">No</th>
						                        <th style="color:#fff; text-align:center;">Tanggal</th>
						                        <th style="color:#fff; text-align:center;">Tindakan</th>
						                        <th style="color:#fff; text-align:center;">Tarif</th>
						                        <th style="color:#fff; text-align:center;">Jumlah</th>
						                        <th style="color:#fff; text-align:center;">Sub Total</th>
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
                    		<div class="col-md-8">
                    			&nbsp;
                    		</div>
                    		<div class="col-md-4">
                    			<div class="card-box widget-user" style="background-color:#cee3f8;">
		                            <div>
		                                <img alt="user" class="img-responsive img-circle" src="<?php echo base_url(); ?>picture/rekam_medik/Money_44325.png">
		                                <div class="wid-u-info">
		                                    <small class="text-primary"><b>Grand Total</b></small>
		                                    <h4 class="m-t-0 m-b-5 font-600 text-danger" id="grandtotal_tindakan">0</h4>
		                                </div>
		                            </div>
		                        </div>
                    		</div>
                    	</div>
                    </form>

                    <form class="form-horizontal" id="view_tindakan_ubah" action="<?php echo $url_ubah; ?>" method="post">
						<input type="hidden" name="id_ubah" id="id_ubah" value="">
						<input type="hidden" name="id_pelayanan" value="<?php echo $id; ?>">
						<h4><i class="fa fa-pencil"></i> Ubah Tindakan</h4>
						<hr>
						<div class="form-group">
	                        <label class="col-md-1 control-label">Tanggal</label>
	                        <div class="col-md-3">
	                        	<div class="input-group">
	                                <span class="input-group-addon">
	                                    <i class="fa fa-calendar"></i>
	                                </span>
	                                <input type="text" class="form-control" name="tanggal_ubah" id="tanggal_ubah" value="" readonly>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-md-1 control-label">Tindakan</label>
	                        <div class="col-md-5">
	                            <div class="input-group">
	                            	<input type="hidden" name="id_tindakan_ubah" id="id_tindakan_ubah" value="">
	                                <input type="text" class="form-control" id="tindakan_txt" value="" readonly="readonly">
	                                <span class="input-group-btn">
	                                    <button type="button" class="btn btn-inverse btn_tindakan"><i class="fa fa-search"></i></button>
	                                </span>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-md-1 control-label">Tarif</label>
	                        <div class="col-md-5">
	                            <input type="text" class="form-control" id="tarif_txt" value="" readonly>
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-md-1 control-label">Jumlah</label>
	                        <div class="col-md-5">
	                            <input type="text" class="form-control" name="jumlah_ubah" id="jumlah_ubah" value="" onkeyup="FormatCurrency(this); hitung_jumlah2();">
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-md-1 control-label">Sub Total</label>
	                        <div class="col-md-5">
	                            <input type="text" class="form-control" name="subtotal_ubah" id="subtotal_ubah" value="" readonly>
	                        </div>
	                    </div>
	                    <hr>
	                    <center>
	                    	<button type="submit" class="btn btn-success"><i class="fa fa-save"></i> <b>Simpan</b></button>
	                        <button type="button" class="btn btn-danger" id="batal_ubah"><i class="fa fa-times"></i> <b>Batal</b></button>
	                    </center>
					</form>
                </div>

                <div role="tabpanel" class="tab-pane fade" id="diagnosa1">
                	<form class="form-horizontal" id="view_diagnosa">
                    	<div class="form-group">
                    		<div class="col-md-6">
                    			<h4 class="m-t-0"><i class="fa fa-table"></i>&nbsp;<b>Tabel Diagnosa</b></h4>
                    		</div>
                    	</div>
                    	<div class="form-group">
                    		<div class="col-md-12">
			                    <div class="table-responsive">
						            <table id="tabel_diagnosa" class="table table-bordered">
						                <thead>
						                    <tr class="merah">
						                        <th style="color:#fff; text-align:center;">No</th>
						                        <th style="color:#fff; text-align:center;">Tanggal</th>
						                        <th style="color:#fff; text-align:center;">Diagnosa</th>
						                        <th style="color:#fff; text-align:center;">Tindakan</th>
						                        <th style="color:#fff; text-align:center;">Kasus</th>
						                        <th style="color:#fff; text-align:center;">Spesialistik</th>
						                        <th style="color:#fff; text-align:center;">Aksi</th>
						                    </tr>
						                </thead>

						                <tbody>
						                    
						                </tbody>
						            </table>
						        </div>
                    		</div>
                    	</div>
                    </form>

                    <form class="form-horizontal" id="view_diagnosa_ubah" action="" method="post">
                    	<input type="hidden" name="id_ubah_dg" id="id_ubah_dg" value="">
                    	<input type="hidden" name="id_rj" id="id_rj" value="<?php echo $id; ?>">
						<input type="hidden" name="id_poli" value="<?php echo $dt->ID_POLI; ?>">
						<input type="hidden" name="id_dokter" value="<?php echo $dt->ID_DOKTER; ?>">
						<input type="hidden" name="id_pasien" value="<?php echo $dt->ID; ?>">
						<h4><i class="fa fa-plus"></i> Ubah Diagnosa</h4>
						<hr>
						<div class="form-group">
							<label class="col-md-2 control-label">Diagnosa</label>
							<div class="col-md-8">
								<textarea class="form-control" rows="5" id="diagnosa_ubah" name="diagnosa_ubah"></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-2 control-label">Tindakan</label>
							<div class="col-md-8">
								<textarea class="form-control" rows="5" id="tindakan_dg_ubah" name="tindakan_dg_ubah"></textarea>
							</div>
						</div>
						<div class="form-group">
	                        <label class="col-md-2 control-label">Kasus</label>
	                        <div class="col-md-5">
	                        	<div class="input-group">
	                        		<input type="hidden" name="id_kasus_ubah" id="id_kasus_ubah" value="">
	                                <input type="text" class="form-control" id="kasus_dg_ubah" value="" readonly>
	                                <span class="input-group-btn">
	                                    <button type="button" class="btn btn-primary btn_kasus_dg" style="cursor:cursor;"><i class="fa fa-search"></i></button>
	                                </span>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-md-2 control-label">Spesialistik</label>
	                        <div class="col-md-5">
	                            <div class="input-group">
	                            	<input type="hidden" name="id_spesialistik_ubah" id="id_spesialistik_ubah" value="">
	                                <input type="text" class="form-control" id="spesialistik_dg_ubah" name="spesialistik_dg_ubah" value="" readonly="readonly" required="required">
	                                <span class="input-group-btn">
	                                    <button type="button" class="btn btn-inverse btn_spesialistik_dg"><i class="fa fa-search"></i></button>
	                                </span>
	                            </div>
	                        </div>
	                    </div>
	                    <hr>
	                    <center>
	                    	<button type="button" class="btn btn-success" id="simpanDgUbah"><i class="fa fa-save"></i> <b>Simpan</b></button>
	                        <button type="button" class="btn btn-danger" id="batalDgUbah"><i class="fa fa-times"></i> <b>Batal</b></button>
	                    </center>
                    </form>
                </div>
            </div>
        </div>
        <div class="row">
			<form class="form-horizontal">
				<div class="form-group">&nbsp;</div>
				<div class="form-group">
					<div class="col-md-4">
						<button class="btn btn-purple btn-block m-b-5" type="button" id="btn_kembali">
							<i class="fa fa-arrow-circle-left"></i>&nbsp;<b>Kembali</b>
						</button>	
					</div>
				</div>
			</form>
		</div>
    </div>
</div>

<!-- TINDAKAN -->
<button class="btn btn-primary" data-toggle="modal" data-target="#myModal1" id="popup_tindakan" style="display:none;">Standard Modal</button>
<div id="myModal1" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="width:50%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Data Tindakan</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form">
		            <div class="form-group">
		                <div class="col-md-12">
			                <div class="input-group">
			                    <input type="text" class="form-control" id="cari_tindakan" placeholder="Cari..." value="">
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
		                <table class="table table-hover table-bordered" id="tb_tindakan">
		                    <thead>
		                        <tr class="hijau_popup">
		                            <th style="text-align:center; color: #fff;" width="50">No</th>
		                            <th style="text-align:center; color: #fff;">Kode</th>
		                            <th style="text-align:center; color: #fff;">Tindakan</th>
		                            <th style="text-align:center; color: #fff;">Tarif</th>
		                        </tr>
		                    </thead>
		                    <tbody>
		                        
		                    </tbody>
		                </table>
            		</div>
            	</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_tindakan">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- // -->

<!-- DIAGNOSA -->
<button id="popup_kasus_dg" class="btn btn-primary" data-toggle="modal" data-target="#myModal1_hasil_dg" style="display:none;">Standard Modal</button>
<div id="myModal1_hasil_dg" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="width:50%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Data Kasus</h4>
            </div>
            <div class="modal-body">
            	<form class="form-horizontal" role="form">
		            <div class="form-group">
		                <div class="col-md-12">
			                <div class="input-group">
			                    <input type="text" class="form-control" id="cari_kasus_dg" placeholder="Cari..." value="">
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
		                <table class="table table-bordered table-hover" id="tb_kasus_dg">
		                    <thead>
		                        <tr class="hijau_popup">
		                            <th style="text-align:center; color: #fff;" width="50">No</th>
		                            <th style="text-align:center; color: #fff;">Kode</th>
		                            <th style="text-align:center; color: #fff;">Nama Kasus</th>
		                        </tr>
		                    </thead>
		                    <tbody>
		                        
		                    </tbody>
		                </table>
            		</div>
            	</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_kasus_dg">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<button id="popup_spesialistik_dg" class="btn btn-primary" data-toggle="modal" data-target="#myModal2_hasil_dg" style="display:none;">Standard Modal</button>
<div id="myModal2_hasil_dg" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="width:50%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Data Spesialistik</h4>
            </div>
            <div class="modal-body">
            	<form class="form-horizontal" role="form">
		            <div class="form-group">
		                <div class="col-md-12">
			                <div class="input-group">
			                    <input type="text" class="form-control" id="cari_spesialistik_dg" placeholder="Cari..." value="">
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
		                <table class="table table-bordered table-hover" id="tb_spesialistik_dg">
		                    <thead>
		                        <tr class="hijau_popup">
		                            <th style="text-align:center; color: #fff;" width="50">No</th>
		                            <th style="text-align:center; color: #fff;">Kode</th>
		                            <th style="text-align:center; color: #fff;">Spesialistik</th>
		                        </tr>
		                    </thead>
		                    <tbody>
		                        
		                    </tbody>
		                </table>
            		</div>
            	</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_spesialistik_dg">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
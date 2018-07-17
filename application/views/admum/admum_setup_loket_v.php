<script type="text/javascript" src="<?php echo base_url(); ?>js-devan/jquery-1.11.1.min.js"></script>

<style type="text/css">
#view_ubah, #view_sopir, #tombol_reset{
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

    // data_loket();

    $('#jumlah_tampil').change(function(){
        data_loket();
    });

    $('#tambah_data').click(function(){
		get_kode();
	});

	$('.btn_sopir').click(function(){
		$('#popup_pegawai').click();
		data_pegawai();
	});

	$('.btn_perawat').click(function(){
		$('#popup_perawat').click();
		data_perawat();
	});

	$('#batal').click(function(){
		window.location = "<?php echo base_url(); ?>admum/admum_ambulance_c";
	});

	$('#checkbox2').click(function(){
		var cek = $('#checkbox2').is(":checked");
		if(cek == true){
			$('#view_sopir').show();
		}else{
			$('#view_sopir').hide();
		}
	});

	$('#btn_ya_prwt').click(function(){
		var id = $('#id_data_hapus').val();
		var id_perawat_hapus = $('#id_perawat_hapus').val();

		$.ajax({
			url : '<?php echo base_url(); ?>admum/admum_ambulance_c/hapus_perawat',
			data : {id_perawat_hapus:id_perawat_hapus},
			type : "POST",
			dataType : "json",
			success : function(result){
				$('#btn_tidak_prwt').click();
				ubah_ambulance(id);
				notif_hapus();
			}
		});
	});
});

function get_kode(){
	$.ajax({
		url : '<?php echo base_url(); ?>admum/admum_ambulance_c/get_kode',
		type : "POST",
		dataType : "json",
		success : function(kode){
			$('#kode').val(kode);
		}
	});
}

function data_pegawai(){
	var keyword = $('#cari_pegawai').val();

	$.ajax({
		url : '<?php echo base_url(); ?>admum/admum_ambulance_c/data_pegawai',
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

					$tr += "<tr style='cursor:pointer;' onclick='klik_pegawai("+result[i].ID+");'>"+
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
			$('#tabel_pegawai tbody').html($tr);
		}
	});

	$('#cari_pegawai').off('keyup').keyup(function(){
		data_pegawai();
	});
}

function klik_pegawai(id){
	$('#tutup_pegawai').click();

	$.ajax({
		url : '<?php echo base_url(); ?>admum/admum_ambulance_c/klik_pegawai',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(row){
			var id_ubah = $('#id_ubah').val();

			if(id_ubah == ""){
				$('#id_sopir').val(id);
				$('#sopir').val(row['NAMA_PEGAWAI']);
			}else{
				$('#id_sopir_ubah').val(id);
				$('#sopir_ubah').val(row['NAMA_PEGAWAI']);
			}
		}
	});
}

function data_perawat(){
	var keyword = $('#cari_perawat').val();

	$.ajax({
		url : '<?php echo base_url(); ?>admum/admum_ambulance_c/data_pegawai',
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

					$tr += "<tr style='cursor:pointer;' onclick='klik_perawat("+result[i].ID+");'>"+
								"<td style='text-align:center;'>"+no+"</td>"+
								"<td>"+result[i].NIP+"</td>"+
								"<td>"+result[i].NAMA_PEGAWAI+"</td>"+
								"<td>"+result[i].NAMA_DEP+"</td>"+
								"<td>"+result[i].NAMA_DIV+"</td>"+								
							"</tr>";
				}
			}
			$('#tabel_perawat tbody').html($tr);
		}
	});

	$('#cari_perawat').off('keyup').keyup(function(){
		data_perawat();
	});
}

function klik_perawat(id){
	$('#tutup_perawat').click();

	$.ajax({
		url : '<?php echo base_url(); ?>admum/admum_ambulance_c/klik_perawat',
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
					$tr = 
					"<tr id='tr_"+result[i].ID+"'>"+
					   "<input type='hidden' name='id_perawat[]' value='"+result[i].ID+"'>"+
					   "<td style='vertical-align:middle;'>"+result[i].NIP+"</td>"+
					   "<td style='vertical-align:middle;'>"+result[i].NAMA_PEGAWAI+"</td>"+
					   "<td align='center'>"+aksi+"</td>"+
				    "</tr>";
				}
			}

			$('#tb_perawat tbody').append($tr);
		}
	});
}

function deleteRow(btn){
  var row = btn.parentNode.parentNode;
  row.parentNode.removeChild(row);
}

function paging($selector){
    var jumlah_tampil = $('#jumlah_tampil').val();

    if(typeof $selector == 'undefined')
    {
        $selector = $("#tabel_loket tbody tr");
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

function data_loket(){
	$('#popup_load').show();
	var keyword = $('#cari_prwt').val();

	$.ajax({
		url : '<?php echo base_url(); ?>admum/admum_setup_loket_c/data_loket',
		data : {keyword:keyword},
		type : "POST",
		dataType : "json",
		success : function(result){
			$tr = "";

			if(result == "" || result == null){
				$tr = "<tr><td colspan='6' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
			}else{
				var no = 0;
				for(var i=0; i<result.length; i++){
					no++;

					var aksi =  '<button type="button" class="btn btn-success btn-sm m-b-5" onclick="ubah_ambulance('+result[i].ID+');">'+
                                    '<i class="fa fa-pencil"></i>'+
                                '</button>&nbsp;'+
                                '<button type="button" class="btn btn-danger btn-sm m-b-5" onclick="hapus_ambulance('+result[i].ID+');">'+
                                    '<i class="fa fa-trash"></i>'+
                                '</button>';

					$tr += "<tr>"+
								"<td style='text-align:center;'>"+no+"</td>"+
								"<td style='text-align:center;'>"+result[i].KODE+"</td>"+
								"<td style='text-align:center;'>"+result[i].NOMOR_PLAT+"</td>"+
								"<td>"+result[i].NAMA_SOPIR+"</td>"+
								"<td align='center'>"+
									"<button class='btn btn-info btn-sm m-b-5' type='button' onclick='detail_perawat("+result[i].ID+");'><i class='fa fa-users'></i> Perawat</button>"+
								"</td>"+
								"<td align='center'>"+aksi+"</td>"+
							"</tr>";
				}
			}

			$('#tabel_loket tbody').html($tr);
			paging();
			$('#popup_load').fadeOut();
		}
	});

	$('#tombol_cari').click(function(){
        data_ambulance();
        $('#tombol_reset').show();
        $('#tombol_cari').hide();
    });

    $('#tombol_reset').click(function(){
        $('#cari_prwt').val("");
        data_ambulance();
        $('#tombol_reset').hide();
        $('#tombol_cari').show();
    });
}

function onEnterText(e){
    if (e.keyCode == 13) {
        data_ambulance();
        $('#tombol_reset').show();
        $('#tombol_cari').hide();
        return false;
    }
}

function ubah_ambulance(id){
	$('#view_ubah').show();
	$('#view_data').hide();

	$.ajax({
		url : '<?php echo base_url(); ?>admum/admum_ambulance_c/data_detail_perawat',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(result){
			$('#id_ubah').val(id);
			$('#kode_ubah').val(result['ambulance']['KODE']);
			$('#nomor_plat_ubah').val(result['ambulance']['NOMOR_PLAT']);
			$('#id_sopir_txt').val(result['ambulance']['ID_SOPIR']);
			$('#sopir_txt').val(result['ambulance']['NAMA_SOPIR']);

			$tr = "";

			if(result['perawat'] == "" || result['perawat'] == null){
				$('#btn_tambah_perawat').removeAttr('disabled');
			}else{
				$('#btn_tambah_perawat').attr('disabled','disabled');
			}

			for(var i=0; i<result['perawat'].length; i++){
				var aksi =  "<button type='button' class='btn btn-success btn-sm m-b-5' onclick='ubah_perawat("+result['perawat'][i].ID+");'><i class='fa fa-pencil'></i></button>&nbsp;"+
							"<button type='button' class='btn btn-danger btn-sm m-b-5' onclick='hapus_perawat("+result['perawat'][i].ID+","+id+");'><i class='fa fa-times'></i></button>";

				$tr += "<tr>"+
							"<input type='hidden' name='id_ubah_perawat[]' value='"+result['perawat'][i].ID+"'>"+
							"<input type='hidden' name='id_prwt_ubah[]' id='id_perawat_"+result['perawat'][i].ID+"' value='"+result['perawat'][i].ID_PERAWAT+"'>"+
							"<td id='nip_"+result['perawat'][i].ID+"'>"+
								result['perawat'][i].NIP_PERAWAT+
							"</td>"+
							"<td id='nama_perawat_"+result['perawat'][i].ID+"'>"+
								result['perawat'][i].NAMA_PERAWAT+
							"</td>"+
							"<td align='center'>"+aksi+"</td>"+
						"</tr>";
			}

			$('#tb_perawat_ubah tbody').html($tr);
		}
	});

	$('#batal_ubah').click(function(){
		$('#view_data').show();
		$('#view_ubah').hide();
		$('#id_ubah').val("");
	});
}

function hapus_loket(id){
	$('#popup_hapus').click();
	$.ajax({
		url : '<?php echo base_url(); ?>admum/admum_setup_loket_c/data_loket_id',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(row){
			$('#id_hapus').val(id);
			var text = row['NAMA_LOKET'];
            $('#msg').html('Apakah <b>'+text+'</b> ingin dihapus?');
		}
	});
}

function ubah_perawat(id_perawat){
	$('#popup_ubah_perawat').click();
	var keyword = $('#cari_perawat_ubah').val();

	$.ajax({
		url : '<?php echo base_url(); ?>admum/admum_ambulance_c/data_pegawai',
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

					$tr += "<tr style='cursor:pointer;' onclick='klik_perawat_ubah("+result[i].ID+","+id_perawat+");'>"+
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
			$('#tabel_perawat_ubah tbody').html($tr);
		}
	});
}

function klik_perawat_ubah(id,id_perawat){
	$('#tutup_perawat_ubah').click();

	$.ajax({
		url : '<?php echo base_url(); ?>admum/admum_ambulance_c/klik_pegawai',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(row){
			$('#id_perawat_'+id_perawat).val(row['ID']);
			$('#nip_'+id_perawat).html(row['NIP']);
			$('#nama_perawat_'+id_perawat).html(row['NAMA_PEGAWAI']);
		}
	});
}

function hapus_perawat(id_perawat,id){
	$('#popup_hapus_perawat').click();

	$.ajax({
		url : '<?php echo base_url(); ?>admum/admum_ambulance_c/data_perawat_id',
		data : {id_perawat:id_perawat},
		type : "POST",
		dataType : "json",
		success : function(row){
			$('#id_data_hapus').val(id);
			$('#id_perawat_hapus').val(id_perawat);
			var txt = row['NIP_PERAWAT']+' - '+row['NAMA_PERAWAT'];
			$('#msg_prwt').html('Apakah perawat <b>'+txt+'</b> ingin dihapus?');
		}
	});
}

function detail_operator(id, nama_loket){
	$('#popup_detail_operator').click();
	$.ajax({
		url : '<?php echo base_url(); ?>admum/admum_setup_loket_c/data_detail_operator',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(result){
			$('#nama_loket_popup').val(nama_loket);

			$tr = "";

			if(result['operator'] == "" || result['operator'] == null){
				$tr = "<tr><td colspan='5' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
			}else{
				var no = 0;

				for(var i=0; i<result['operator'].length; i++){
					no++;

					$tr += "<tr>"+
								"<td style='text-align:center;'>"+result['operator'][i].NIP+"</td>"+
								"<td>"+result['operator'][i].NAMA_OPERATOR+"</td>"+
								"<td>"+result['operator'][i].NAMA_DEP2+"</td>"+
								"<td>"+result['operator'][i].NAMA_DIV2+"</td>"+
							"</tr>";
				}
			}

			$('#tabel_detail_operator tbody').html($tr);
		}
	});
}

function tambah_perawat(){
	$('#popup_tambah_perawat').click();
	var keyword = $('#cari_tambah_perawat').val();

	$.ajax({
		url : '<?php echo base_url(); ?>admum/admum_ambulance_c/data_pegawai',
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

					$tr += "<tr style='cursor:pointer;' onclick='klik_perawat_tambah("+result[i].ID+");'>"+
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
			$('#tabel_tambah_perawat tbody').html($tr);
		}
	});
}

function klik_perawat_tambah(id){
	$('#tutup_tambah_perawat').click();

	$.ajax({
		url : '<?php echo base_url(); ?>admum/admum_ambulance_c/klik_perawat',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(result){
			$tr = "";

			for(var i=0; i<result.length; i++){
				var jumlah_data = $('#tr_ubah_'+result[i].ID).length;

				var aksi = "<button type='button' class='btn waves-light btn-danger btn-sm' onclick='deleteRow(this);'><i class='fa fa-times'></i></button>";

				if(jumlah_data > 0){
					var jumlah = $('#jumlah_'+result[i].ID).val();
					$('#jumlah_'+result[i].ID).val(parseInt(jumlah)+1);
				}else{
					$tr += "<tr id='tr_ubah_"+result[i].ID+"'>"+
								"<input type='hidden' name='id_ubah_perawat[]' value='"+result[i].ID+"'>"+
								"<input type='hidden' name='id_prwt_ubah[]' id='id_perawat_"+result[i].ID+"' value='"+result[i].ID+"'>"+
								"<td id='nip_"+result[i].ID+"'>"+
									result[i].NIP+
								"</td>"+
								"<td id='nama_perawat_"+result[i].ID+"'>"+
									result[i].NAMA_PEGAWAI+
								"</td>"+
								"<td align='center'>"+aksi+"</td>"+
							"</tr>";
				}
			}

			$('#tb_perawat_ubah tbody').append($tr);
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
                    <a href="#home1" role="tab" data-toggle="tab"><i class="zmdi zmdi-local-library"></i> Data Loket</a>
                </li>
                <li role="presentation" id="tambah_data">
                    <a href="#profile1" role="tab" data-toggle="tab"><i class="fa fa-plus"></i> Tambah Loket</a>
                </li>
            </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade in active" id="home1">
                	<div class="row">
					    <div class="col-sm-12">
					        <div class="card-box table-responsive">
					            <table id="datatable" class="table table-striped table-bordered">
					                <thead> 
					                    <tr class="biru">
										    <th style="color:#fff; text-align:center;">No</th>
										    <th style="color:#fff; text-align:center;">Nama Loket</th>
										    <th style="color:#fff; text-align:center;">Kode Antrian</th>
										    <th style="color:#fff; text-align:center;">Operator</th>
										    <th style="color:#fff; text-align:center;">Akses Menu</th>
										    <th style="color:#fff; text-align:center;">Aksi</th>
										</tr>
					                </thead>
					                <tbody>
					                	<?PHP 
					                		$no = 0;
					                		foreach ($dt as $key => $row) { 
					                			$data_akses = $this->model->getAksesMenu($row->ID);
					                			$no++;
					                	?>
					                	<tr>
					                		<td style="vertical-align:middle;" align="center"> <?=$no;?> </td>
					                		<td style="vertical-align:middle;" align="left"> <?=$row->NAMA_LOKET;?> </td>
					                		<td style="vertical-align:middle;" align="center"> <?=$row->KODE;?> - <?=strtoupper($row->UNTUK);?> </td>
					                		<td style="vertical-align:middle;" align="center"> 
					                			<button class="btn btn-info btn-sm m-b-5" type="button" onclick="detail_operator('<?=$row->ID;?>','<?=$row->NAMA_LOKET;?>');">
					                				<i class="fa fa-users"></i> Operator
					                			</button> 
					                		</td>
					                		<td style="vertical-align:middle;"align="left"> 
					                			<?PHP foreach ($data_akses as $key => $aks) { 
					                				$akses = "";
					                				if($aks->AKSES == "billing"){
					                					$akses = "Billing";
					                				} else if($aks->AKSES == "admum"){
					                					$akses = "Administrasi Umum";
					                				} else if($aks->AKSES == "rekam_medik"){
					                					$akses = "Rekam Medik";
					                				} else if($aks->AKSES == "apotek"){
					                					$akses = "Apotek";
					                				}

					                				echo " - ".$akses."<br>";
					                			} ?>
					                		</td>
					                		<td style="vertical-align:middle;" align="center"> 
					                			<button type="button" class="btn btn-success btn-sm m-b-5" onclick="window.location='<?=base_url();?>admum/admum_setup_loket_c/edit/<?=$row->ID;?>';">
				                                    <i class="fa fa-pencil"></i>
				                                </button>&nbsp;
				                                <button type="button" class="btn btn-danger btn-sm m-b-5" onclick="hapus_loket(<?=$row->ID;?>);">
				                                    <i class="fa fa-trash"></i>
                                				</button>
					                		</td>
					                	</tr>
					                	<?PHP } ?>
					                </tbody>
					            </table>
					        </div>
					    </div>
					</div>

                </div>

                <div role="tabpanel" class="tab-pane fade" id="profile1">
                    <form class="form-horizontal" role="form" action="<?php echo $url_simpan; ?>" method="post">
				        <div class="card-box">
				            <div class="row">

			                    <div class="form-group">
			                        <label class="col-md-2 control-label">Nama Loket</label>
			                        <div class="col-md-5">
			                            <input type="text" class="form-control" name="nama_loket" id="nama_loket" value="" required="required">
			                        </div>
			                    </div>

			                    <div class="form-group">
			                        <label class="col-md-2 control-label"> Kode Antrian </label>
			                        <div class="col-md-5">
			                            <select class="form-control" name="kode_antrian" id="kode_antrian" required="required">
	                                        <option value=""> -- Pilih</option>
	                                        <?PHP 
		                                        foreach ($dt_kode_antrian as $key => $antrian) {
		                                        	echo '<option value="'.$antrian->ID.'"> '.$antrian->KODE.' - '.strtoupper($antrian->UNTUK).'</option>';
		                                        }
	                                        ?>
	                                    </select>
			                        </div>
			                    </div>

			                    <div class="form-group">
			                        <label class="col-md-2 control-label"> Akses Loket </label>
			                        <div class="col-md-10">
			                            <div class="checkbox checkbox-warning">
			                                <input type="checkbox" name="akses[]" id="inlineCheckbox1" value="billing">
			                                <label for="inlineCheckbox1"> Billing </label>
			                            </div>
			                            <div class="checkbox checkbox-primary">
			                                <input type="checkbox" name="akses[]" id="inlineCheckbox2" value="admum">
			                                <label for="inlineCheckbox2"> Administrasi Umum </label>
			                            </div>
			                            <div class="checkbox checkbox-danger">
			                                <input type="checkbox" name="akses[]" id="inlineCheckbox3" value="rekam_medik">
			                                <label for="inlineCheckbox3"> Rekam Medik </label>
			                            </div>
			                            <div class="checkbox checkbox-success">
			                                <input type="checkbox" name="akses[]" id="inlineCheckbox4" value="apotek">
			                                <label for="inlineCheckbox4"> Apotek </label>
			                            </div>
			                        </div>
			                    </div>

			                    <div class="form-group">
			                        <label class="col-md-2 control-label">Operator Loket</label>
			                        <div class="col-md-5">
			                            <div class="input-group">
			                                <button type="button" class="btn btn-warning btn_perawat"><i class="fa fa-search"> <b> Cari Operator</b> </i></button>
			                            </div>
			                        </div>
			                    </div>
			                    <div class="form-group">
			                        <label class="col-md-2 control-label">&nbsp;</label>
			                        <div class="col-md-5">
			                            <div class="table-responsive">
								            <table id="tb_perawat" class="table table-hover table-bordered">
								                <thead>
								                    <tr class="biru">
								                        <th style="color:#fff; text-align:center;">NIP</th>
								                        <th style="color:#fff; text-align:center;">Nama Perawat</th>
								                        <th style="color:#fff; text-align:center;">#</th>
								                    </tr>
								                </thead>

								                <tbody>
								                    
								                </tbody>
								            </table>
								        </div>
			                        </div>
			                    </div>

			                    <hr>

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
        <form class="form-horizontal" role="form" action="<?php echo $url_ubah; ?>" method="post">
        	<input type="hidden" name="id_ubah" id="id_ubah" value="">
            <h4 class="header-title m-t-0 m-b-30">Ubah Data Pasien</h4>
            <hr/>
            <div class="row">
                <div class="form-group">
                    <label class="col-md-2 control-label">Kode</label>
                    <div class="col-md-5">
                        <input type="text" class="form-control" name="kode_ubah" id="kode_ubah" value="" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">Nomor Plat</label>
                    <div class="col-md-5">
                        <input type="text" class="form-control" name="nomor_plat_ubah" id="nomor_plat_ubah" value="">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">Sopir</label>
                    <div class="col-md-4">
                    	<input type="hidden" name="id_sopir_txt" id="id_sopir_txt" value="">
                    	<input type="text" class="form-control" id="sopir_txt" value="" readonly="readonly">
                    </div>
                    <div class="col-md-1">
                        <div class="checkbox checkbox-primary">
                            <input id="checkbox2" type="checkbox" name="cek_sopir" value="1">
                            <label for="checkbox2">
                            	Ubah
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group" id="view_sopir">
                    <label class="col-md-2 control-label">&nbsp;</label>
                    <div class="col-md-5">
                        <div class="input-group">
                        	<input type="hidden" name="id_sopir_ubah" id="id_sopir_ubah" value="">
                            <input type="text" class="form-control" id="sopir_ubah" value="" readonly="readonly">
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-success btn_sopir"><i class="fa fa-search"></i></button>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                	<label class="col-md-2 control-label">Perawat</label>
                	<div class="col-md-6">
                		<button class="btn btn-warning waves-effect w-md waves-light m-b-5" id="btn_tambah_perawat" type="button" onclick="tambah_perawat();">Tambah Perawat</button>
                	</div>
                </div>
                <div class="form-group">
                	<label class="col-md-2 control-label">&nbsp;</label>
                	<div class="col-md-6">
                        <div class="table-responsive">
				            <table id="tb_perawat_ubah" class="table table-hover table-bordered">
				                <thead>
				                    <tr class="biru">
				                        <th style="color:#fff; text-align:center;">NIP</th>
				                        <th style="color:#fff; text-align:center;">Nama Perawat</th>
				                        <th style="color:#fff; text-align:center;">#</th>
				                    </tr>
				                </thead>

				                <tbody>
				                    
				                </tbody>
				            </table>
				        </div>
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
        </form>
    </div>
</div>

<button class="btn btn-primary" data-toggle="modal" data-target="#myModal2" id="popup_pegawai" style="display:none;">Standard Modal</button>
<div id="myModal2" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="width:55%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Data Sopir</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form">
		            <div class="form-group">
		                <div class="col-md-12">
			                <div class="input-group">
			                    <input type="text" class="form-control" id="cari_pegawai" placeholder="Cari..." value="">
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
		                <table class="table table-hover table-bordered" id="tabel_pegawai">
		                    <thead>
		                        <tr class="hijau_popup">
		                            <th style="text-align:center; color: #fff;" width="50">No</th>
		                            <th style="text-align:center; color: #fff;">Nama Sopir</th>
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
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_pegawai">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<button class="btn btn-primary" data-toggle="modal" data-target="#myModal3" id="popup_perawat" style="display:none;">Standard Modal</button>
<div id="myModal3" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="width:55%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Data Perawat</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form">
		            <div class="form-group">
		                <div class="col-md-12">
			                <div class="input-group">
			                    <input type="text" class="form-control" id="cari_perawat" placeholder="Cari..." value="">
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
		                <table class="table table-hover table-bordered" id="tabel_perawat">
		                    <thead>
		                        <tr class="kuning_popup">
		                            <th style="text-align:center; color: #fff;" width="50">No</th>
		                            <th style="text-align:center; color: #fff;">NIP</th>
		                            <th style="text-align:center; color: #fff;">Nama Perawat</th>
		                            <th style="text-align:center; color: #fff;">Departemen</th>
		                            <th style="text-align:center; color: #fff;">Divisi</th>
		                        </tr>
		                    </thead>
		                    <tbody>
		                        
		                    </tbody>
		                </table>
            		</div>
            	</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_perawat">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<button class="btn btn-primary" data-toggle="modal" data-target="#myModal4" id="popup_detail_operator" style="display:none;">Standard Modal</button>
<div id="myModal4" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="width:55%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel"> Operator Loket </h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form">
		            <div class="form-group">
		            	<label class="col-md-2 control-label">Nama Loket</label>
		                <div class="col-md-6">
		                    <input type="text" class="form-control" id="nama_loket_popup" value="" readonly="readonly">
		                </div>
		            </div>
		        </form>
            	<div class="table-responsive">
            		<div class="scroll-y">
		                <table class="table table-hover table-bordered" id="tabel_detail_operator">
		                    <thead>
		                        <tr class="biru_popup">
		                            <th style="text-align:center; color: #fff;" width="50">NIP</th>
		                            <th style="text-align:center; color: #fff;">Nama Opeator</th>
		                            <th style="text-align:center; color: #fff;">Departemen</th>
		                            <th style="text-align:center; color: #fff;">Divisi</th>
		                        </tr>
		                    </thead>
		                    <tbody>
		                        
		                    </tbody>
		                </table>
            		</div>
            	</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_perawat">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

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
                    <input name="hapus" type="submit" class="btn btn-danger" value="Ya" />
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<button class="btn btn-primary" data-toggle="modal" data-target="#myModal5" id="popup_ubah_perawat" style="display:none;">Standard Modal</button>
<div id="myModal5" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="width:55%;">
        <div class="modal-content">
        	<form class="form-horizontal" role="form">
	            <div class="modal-header">
	                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	                <h4 class="modal-title" id="myModalLabel">Data Perawat</h4>
	            </div>
            	<div class="modal-body">
		            <form class="form-horizontal" role="form">
			            <div class="form-group">
			                <div class="col-md-12">
				                <div class="input-group">
				                    <input type="text" class="form-control" id="cari_perawat_ubah" placeholder="Cari..." value="">
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
			                <table class="table table-hover table-bordered" id="tabel_perawat_ubah">
			                    <thead>
			                        <tr class="kuning_popup">
			                            <th style="text-align:center; color: #fff;" width="50">No</th>
			                            <th style="text-align:center; color: #fff;">Nama Perawat</th>
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
	                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_perawat_ubah">Tutup</button>
	            </div>
		    </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<button class="btn btn-primary" data-toggle="modal" data-target="#custom-width-modal2" id="popup_hapus_perawat" style="display:none;">Custom width Modal</button>
<div id="custom-width-modal2" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="width:55%;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="custom-width-modalLabel">Konfirmasi Hapus</h4>
            </div>
            <div class="modal-body">
                <p id="msg_prwt"></p>
            </div>
            <div class="modal-footer">
                <form action="<?php echo $url_hapus; ?>" method="post">
                	<input type="hidden" name="id_data_hapus" id="id_data_hapus" value="">
                    <input type="hidden" name="id_perawat_hapus" id="id_perawat_hapus" value="">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal" id="btn_tidak_prwt">Tidak</button>
                    <button type="button" class="btn btn-danger waves-effect waves-light" id="btn_ya_prwt">Ya</button>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<button class="btn btn-primary" data-toggle="modal" data-target="#myModal6" id="popup_tambah_perawat" style="display:none;">Standard Modal</button>
<div id="myModal6" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="width:55%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Data Perawat</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form">
		            <div class="form-group">
		                <div class="col-md-12">
			                <div class="input-group">
			                    <input type="text" class="form-control" id="cari_tambah_perawat" placeholder="Cari..." value="">
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
		                <table class="table table-hover table-bordered" id="tabel_tambah_perawat">
		                    <thead>
		                        <tr class="kuning_popup">
		                            <th style="text-align:center; color: #fff;" width="50">No</th>
		                            <th style="text-align:center; color: #fff;">Nama Perawat</th>
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
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_tambah_perawat">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
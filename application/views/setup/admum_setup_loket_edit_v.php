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

	$('.btn_perawat').click(function(){
		$('#popup_perawat').click();
		data_perawat();
	});

	$('#batal').click(function(){
		window.location = "<?php echo base_url(); ?>setup/admum_setup_loket_c";
	});
});

function data_perawat(){
	var keyword = $('#cari_perawat').val();

	$.ajax({
		url : '<?php echo base_url(); ?>setup/admum_ambulance_c/data_pegawai',
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

					result[i].NAMA_DIV = result[i].NAMA_DIV==null?"-":result[i].NAMA_DIV;

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
		url : '<?php echo base_url(); ?>setup/admum_ambulance_c/klik_perawat',
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
</script>

<div class="row">
	<div class="col-lg-12">
		<div class="card-box">
			<form class="form-horizontal" role="form" action="<?php echo $url_simpan; ?>" method="post">
		        <div class="card-box">
		            <div class="row">
	                    <div class="form-group">
	                        <label class="col-md-2 control-label">Nama Loket</label>
	                        <div class="col-md-5">
	                            <input type="text" class="form-control" name="nama_loket" id="nama_loket" value="<?=$dt->NAMA_LOKET;?>" required="required">
	                        </div>
	                    </div>

	                    <div class="form-group">
	                        <label class="col-md-2 control-label"> Kode Antrian </label>
	                        <div class="col-md-5">
	                            <select class="form-control" name="kode_antrian" id="kode_antrian" required="required">
                                    <option value=""> -- Pilih</option>
                                    <?PHP 
                                    	$chk = "";
                                        foreach ($dt_kode_antrian as $key => $antrian) {
                                        	if($antrian->ID == $dt->KODE_ANTRIAN){
                                        		$chk = "selected";
                                        	} else {
                                        		$chk = "";
                                        	}
                                        	echo '<option '.$chk.' value="'.$antrian->ID.'"> '.$antrian->KODE.' - '.strtoupper($antrian->UNTUK).'</option>';
                                        }
                                    ?>
                                </select>
	                        </div>
	                    </div>

	                    <?PHP 
	                    $beranda = "";
	                    $admum = "";
	                    $poli = "";
	                    $lab = "";
	                    $apotek = "";
	                    $rekam_medik = "";
	                    $billing = "";
	                    foreach ($dtAkses as $key => $aks) {
	                    	if($aks->AKSES == "pasien"){
	                    		$beranda = "checked";
	                    	} else if($aks->AKSES == "admum"){
	                    		$admum = "checked";
	                    	} else if($aks->AKSES == "poli"){
	                    		$poli = "checked";
	                    	} else if($aks->AKSES == "lab"){
	                    		$lab = "checked";
	                    	}else if($aks->AKSES == "apotek"){
	                    		$apotek = "checked";
	                    	}else if($aks->AKSES == "rekam_medik"){
	                    		$rekam_medik = "checked";
	                    	}else if($aks->AKSES == "billing"){
	                    		$billing = "checked";
	                    	}
	                    }
	                    ?>

	                    <div class="form-group">
	                        <label class="col-md-2 control-label"> Akses Loket </label>
	                        <div class="col-md-10">
	                            <div class="checkbox checkbox-purple">
	                                <input type="checkbox" <?=$beranda;?> name="akses[]" id="inlineCheckbox1" value="pasien">
	                                <label for="inlineCheckbox1"> Beranda Pasien </label>
	                            </div>
	                            <div class="checkbox checkbox-primary">
	                                <input type="checkbox" <?=$admum;?> name="akses[]" id="inlineCheckbox2" value="admum">
	                                <label for="inlineCheckbox2"> Admission </label>
	                            </div>
	                            <div class="checkbox checkbox-success">
	                                <input type="checkbox" <?=$poli;?> name="akses[]" id="inlineCheckbox_poli" value="poli">
	                                <label for="inlineCheckbox_poli"> Poli </label>
	                            </div>
	                            <div class="checkbox checkbox-primary">
	                                <input type="checkbox" <?=$lab;?> name="akses[]" id="inlineCheckbox_lab" value="lab">
	                                <label for="inlineCheckbox_lab"> Laborat </label>
	                            </div>
	                            <div class="checkbox checkbox-success">
	                                <input type="checkbox" <?=$apotek;?> name="akses[]" id="inlineCheckbox4" value="apotek">
	                                <label for="inlineCheckbox4"> Apotek </label>
	                            </div>
	                            <div class="checkbox checkbox-danger">
	                                <input type="checkbox" <?=$rekam_medik;?> name="akses[]" id="inlineCheckbox3" value="rekam_medik">
	                                <label for="inlineCheckbox3"> Rekam Medik </label>
	                            </div>
	                            <div class="checkbox checkbox-warning">
	                                <input type="checkbox" <?=$billing;?> name="akses[]" id="inlineCheckbox1" value="billing">
	                                <label for="inlineCheckbox1"> Billing </label>
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
						                    <?PHP foreach ($dtOperator as $key => $op) { ?>
						                      	<tr>
												   <input type="hidden" name="id_perawat[]" value="<?=$op->ID_PEGAWAI;?>">
												   <td style="vertical-align:middle;"><?=$op->NIP;?></td>
												   <td style="vertical-align:middle;"><?=$op->NAMA_OPERATOR;?></td>
												   <td align="center">
												   		<button type="button" class="btn waves-light btn-danger btn-sm" onclick="deleteRow(this);">
												   			<i class="fa fa-times"></i>
												   		</button>
												   </td>
											    </tr> 
										    <?PHP } ?>
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
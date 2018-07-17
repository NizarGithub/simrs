<div id="popup_load" style="display:none;">
    <div class="window_load">
        <img src="<?=base_url()?>picture/progress.gif" height="100" width="125">
    </div>
</div>

<div class="alert alert-info fade in m-b-0" style="color:#666">
    <h4 style="font-weight: bold;"> Panduan Singkat </h4>
    <p>
    	Menu ini berfungsi untuk menghasilkan data gaji pegawai sesuai dengan bulan dan tahun yang dipilih.
    </p>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="portlet box blue">
			<div class="portlet-title">

				<div class="tools">
					<a href="" class="collapse">
					</a>
					<a href="" class="reload">
					</a>
				</div>
			</div>
 
			<div class="portlet-body form">
				<form role="form" method="POST" action="<?=base_url().$post_url;?>" enctype="multipart/form-data">
					<div class="form-body">
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label class="control-label">Bulan</label>
									<select class="form-control" name="bulan" id="bulan">
										<?PHP $bln_modal = date('m'); ?>
										<option <?PHP if($bln_modal == "01"){ echo "selected"; } ?> value="1">Januari</option>
										<option <?PHP if($bln_modal == "02"){ echo "selected"; } ?> value="2">Februari</option>
										<option <?PHP if($bln_modal == "03"){ echo "selected"; } ?> value="3">Maret</option>
										<option <?PHP if($bln_modal == "04"){ echo "selected"; } ?> value="4">April</option>
										<option <?PHP if($bln_modal == "05"){ echo "selected"; } ?> value="5">Mei</option>
										<option <?PHP if($bln_modal == "06"){ echo "selected"; } ?> value="6">Juni</option>
										<option <?PHP if($bln_modal == "07"){ echo "selected"; } ?> value="7">Juli</option>
										<option <?PHP if($bln_modal == "08"){ echo "selected"; } ?> value="8">Agustus</option>
										<option <?PHP if($bln_modal == "09"){ echo "selected"; } ?> value="9">September</option>
										<option <?PHP if($bln_modal == "10"){ echo "selected"; } ?> value="10">Oktober</option>
										<option <?PHP if($bln_modal == "11"){ echo "selected"; } ?> value="11">November</option>
										<option <?PHP if($bln_modal == "12"){ echo "selected"; } ?> value="12">Desember</option>
									</select>
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label class="control-label">Tahun</label>
									<select class="form-control" id="tahun" name="tahun">
				                        <?php
				                            $thn = date('Y');
				                            for($i=2014; $i<=$thn+1; $i++) {
				                                if ($i==$thn){
				                                    echo"<option selected='selected' value=".$i."> ".$i." </option>";
				                                }else{
				                                    echo"<option value=".$i."> ".$i." </option>";
				                                }
				                            }
				                        ?>
				                    </select>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="form-group">
                                <label class="col-md-3 control-label" style="color: #0099e5;"> Generate data gaji pegawai </label>
                            </div>
						</div>

					</div>

					<div class="modal-footer">
                        <center>
                            <button onclick="generate_gaji();" type="button" class="btn btn-primary waves-effect w-md waves-light m-b-5"> Generate Gaji </button>
                        </center>
                    </div>

				</form>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	function generate_gaji() {
		var bln = $('#bulan').val(); 
		var thn = $('#tahun').val();
		$('#popup_load').show();	

		$.ajax({
	        url : '<?php echo base_url(); ?>kepeg/generate_gaji_c/generate_gaji',
	        data : {
	        	bln:bln,
	        	thn:thn,
	        },
	        type : "POST",
	        dataType : "json",
	        success : function(result){
	        	if(result == "OK"){
	        		$('#popup_load').hide();
	        		notif_generate();
	        	}
	        }
	    });

	}
</script>
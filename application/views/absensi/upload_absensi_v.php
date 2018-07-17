<div class="alert alert-info fade in m-b-0" style="color:#666">
    <h4 style="font-weight: bold;"> Panduan Singkat </h4>
    <p>
    	Unduh data absensi dari program Fingerspot. Buka file excel yang telah diunduh lalu <b> Save As </b> sebagai <b>.xls</b> <br>
		Jika sudah, upload file tersebut di form dibawah ini.
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
							<!--/span-->
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
							<!--/span-->

							

						</div>

						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label class="control-label">File Upload</label> <br>
									<div class="fileinput fileinput-new" data-provides="fileinput">
									    	<input id="file_upload" type="file" class="dropify" name="userfile" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"  />

									    <span class="fileinput-filename"></span>
									    <center>
									    <span class="fileinput-new"> <b style="color:red;"> Hanya xls, xlsx, csv </b> </span>
									    </center>
									</div>
								</div>
							</div>
						</div>

					</div>

					<div class="form-actions">
						<div class="row">
							<div class="col-md-offset-5 col-md-5">
								<input type="submit" name="simpan" class="btn btn-info" value="Simpan" />
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<input type="reset" class="btn btn-inverse" value="Batal" />
							</div>
						</div>
					</div>

				</form>
			</div>
		</div>
	</div>
</div>
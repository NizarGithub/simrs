<div class="row">
    <div class="col-sm-12">
        <div class="card-box"> 
            <div class="row">
                <div class="col-lg-12">
                    <form class="form-horizontal" role="form" method="post" action="<?=base_url().$post_url;?>" target="_blank"> 
						<div class="row">
							<div class="row">
		                        <div class="col-sm-offset-2 col-sm-10">
									<div class="form-group">
										<label class="col-md-2 control-label">Nama Laporan</label>
										<label class="col-md-6 control-label" style="text-align: left; margin-top: -2px;"> 
											<b style="font-size: 17px;"> &nbsp; Laporan Pembayaran Tagihan</b> 
										</label>
									</div> 
								</div>
							</div> 

	                        <div class="col-sm-offset-2 col-sm-10">
								<div class="form-group">
									<label class="col-md-2 control-label">Bulan</label>
									<div class="col-md-6">
										<select class="form-control" name="bulan" id="bulan">
											<?PHP $bln_modal = date('m'); ?>
											<option <?PHP if($bln_modal == "01"){ echo "selected"; } ?> value="01">Januari</option>
											<option <?PHP if($bln_modal == "02"){ echo "selected"; } ?> value="02">Februari</option>
											<option <?PHP if($bln_modal == "03"){ echo "selected"; } ?> value="03">Maret</option>
											<option <?PHP if($bln_modal == "04"){ echo "selected"; } ?> value="04">April</option>
											<option <?PHP if($bln_modal == "05"){ echo "selected"; } ?> value="05">Mei</option>
											<option <?PHP if($bln_modal == "06"){ echo "selected"; } ?> value="06">Juni</option>
											<option <?PHP if($bln_modal == "07"){ echo "selected"; } ?> value="07">Juli</option>
											<option <?PHP if($bln_modal == "08"){ echo "selected"; } ?> value="08">Agustus</option>
											<option <?PHP if($bln_modal == "09"){ echo "selected"; } ?> value="09">September</option>
											<option <?PHP if($bln_modal == "10"){ echo "selected"; } ?> value="10">Oktober</option>
											<option <?PHP if($bln_modal == "11"){ echo "selected"; } ?> value="11">November</option>
											<option <?PHP if($bln_modal == "12"){ echo "selected"; } ?> value="12">Desember</option>
										</select>
									</div>
								</div>
							</div>
						</div>

						<div class="row">
	                        <div class="col-sm-offset-2 col-sm-10">
								<div class="form-group">
									<label class="col-md-2 control-label">Tahun</label>
									<div class="col-md-4">
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
						</div>

						<div class="row">
	                        <div class="col-sm-offset-2 col-sm-10">
								<div class="form-group">
									<label class="col-md-2 control-label">Format Laporan</label>
									<div class="col-md-6">
										<div class="radio radio-success">
		                                    <input type="radio" id="inlineRadio1" value="excel" name="format" checked>
		                                    <label for="inlineRadio1"> Excel (.xls) </label>
		                                </div>
		                                <div class="radio radio-danger">
		                                    <input type="radio" id="inlineRadio2" value="pdf" name="format">
		                                    <label for="inlineRadio2"> PDF (.pdf) </label>
		                                </div>
									</div>
								</div>
							</div>
						</div>

						<hr>

                        <div class="form-group m-b-0">
                            <div class="col-sm-offset-5 col-sm-10">
                              <input type="submit" class="btn btn-lg btn-primary" value="Cetak Laporan" name="cetak"/>
                            </div>
                        </div>

                    </form>
                </div><!-- end col -->

            </div><!-- end row -->
        </div>
    </div><!-- end col -->
</div>

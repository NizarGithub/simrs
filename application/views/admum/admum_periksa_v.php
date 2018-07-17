<div class="col-lg-12">
	<div class="card-box">
		<form class="form-horizontal" role="form">
            <div class="form-group">
            	<label class="col-md-2 control-label" style="width:125px; text-align:left;">Nama Pasien</label>
                <div class="col-md-4">
	            	<select class="form-control select2">
	            		<option>Pilih Pasien</option>
	            	<?php
	            		foreach ($data_pasien as $value) {
	            	?>
                        <option value="<?php echo $value->ID; ?>"><?php echo $value->KODE_PASIEN." - ".$value->NAMA; ?></option>
	            	<?php
	            		}
	            	?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-3 control-label" style="width:125px; text-align:left;">Jenis Kelamin</label>
                <div class="col-md-4">
                    <input type="text" class="form-control" name="jenis_kelamin" id="jenis_kelamin" value="" readonly>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-3 control-label" style="width:125px; text-align:left;">Umur</label>
                <div class="col-md-4">
                    <input type="text" class="form-control" name="umur" id="umur" value="" readonly>
                </div>
            </div>
        </form>
    </div>
</div>
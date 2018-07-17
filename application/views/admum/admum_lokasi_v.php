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
});

function data_provinsi(){
    var id_kota_kab = $('#kota').val();
    $.ajax({
        url : '<?php echo base_url(); ?>admum/admum_lokasi_c/data_provinsi',
        data : {id_kota_kab:id_kota_kab},
        type : "POST",
        dataType : "json",
        success : function(row){
            $('#id_provinsi').val(row['ID_PROV']);
            $('#id_kota').val(row['lokasi_kabupatenkota']);
            $('#provinsi').val(row['PROV']);
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
                    <a href="#home1" role="tab" data-toggle="tab"><i class="fa fa-map"></i> Default Lokasi</a>
                </li>
                <li role="presentation" id="tambah_data">
                    <a href="#profile1" role="tab" data-toggle="tab"><i class="fa fa-cog"></i> Setting Lokasi</a>
                </li>
            </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade in active" id="home1">
                	<form class="form-horizontal" role="form">
                		<div class="card-box">
				            <div class="row">
				            	<div class="form-group">
				            		<div class="col-md-4">
					            		<table class="table table-striped table-responsive">
					            			<tbody>
					            				<tr>
					            					<td colspan="3">Lokasi Sekarang</td>
					            				</tr>
					            				<?php
				            					$dt = $this->model->default_lokasi();
				            					if($dt == null || $dt == ""){
				            					?>
				            					<tr>
					            					<td>Kota / Kabupaten</td>
					            					<td>:</td>
					            					<td>Belum Disetting</td>
					            				</tr>
					            				<tr>
					            					<td>Provinsi</td>
					            					<td>:</td>
					            					<td>Belum Disetting</td>
					            				</tr>
				            					<?php
				            					}else{
				            						foreach ($dt as $value) {
				            							$dt_kota = $this->model->kota_kab_id($value->ID_KOTA_KAB,$value->ID_PROVINSI);
				            					?>
				            					<tr>
					            					<td>Kota / Kabupaten</td>
					            					<td>:</td>
					            					<td><?php echo $dt_kota->KOTA; ?></td>
					            				</tr>
					            				<tr>
					            					<td>Provinsi</td>
					            					<td>:</td>
					            					<td><?php echo $dt_kota->PROV; ?></td>
					            				</tr>
				            					<?php
				            						}
				            					}
					            				?>
					            			</tbody>
					            		</table>
				            		</div>
				            	</div>
				           	</div>
				        </div>
				    </form>
                </div>

                <div role="tabpanel" class="tab-pane fade" id="profile1">
                	<form class="form-horizontal" role="form" action="<?php echo $url_simpan; ?>" method="post">
                		<div class="card-box">
				            <div class="row">
				            	<div class="form-group">
			                        <label class="col-md-3 control-label">Kabupaten / Kota</label>
			                        <div class="col-md-4">
			                            <select class="form-control select2" name="kota" id="kota" onchange="data_provinsi();">
			                            <?php
			                                $data_kota = $this->model->kota_kab();
			                                foreach ($data_kota as $val_kota) {
			                            ?>
			                                <option value="<?php echo $val_kota->KOTA; ?>"><?php echo $val_kota->KOTA; ?></option>
			                            <?php
			                                }
			                            ?>
			                            </select>
			                        </div>
			                    </div>
			                    <div class="form-group">
			                        <label class="col-md-3 control-label">Provinsi</label>
			                        <div class="col-md-4">
			                        	<input type="hidden" name="id_kota" id="id_kota" value="">
			                            <input type="hidden" name="id_provinsi" id="id_provinsi" value="">
			                            <input type="text" class="form-control" name="provinsi" id="provinsi" value="" readonly="readonly">
			                        </div>
			                    </div>
			                    <hr>
			                    <div class="form-group">
			                        <label class="col-md-3 control-label">&nbsp;</label>
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
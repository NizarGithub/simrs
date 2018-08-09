<script type="text/javascript" src="<?php echo base_url(); ?>js-devan/jquery-1.11.1.min.js"></script>

<style type="text/css">
#view_laborat_tambah, #view_laborat_ubah{
      display: none;
}
</style>

<script type="text/javascript">
$(document).ready(function(){
      $('#btn_tambah_lab').click(function(){
            $('#view_laborat_tambah').show();
            $('#view_laborat').hide();
            $('#view_laborat_ubah').hide();

            $.ajax({
                  url : '<?php echo base_url(); ?>lab/lab_home_c/get_kode_lab',
                  type : "POST",
                  dataType : "json",
                  success : function(kode){
                        $('#kode_lab').val(kode);
                  }
            });
      });

      $('#batalLab').click(function(){
            $('#view_laborat_tambah').hide();
            $('#view_laborat').show();
      });

      $('.btn_jenis_laborat').click(function(){
            $('#popup_laborat').click();
            load_laborat();
      });
});

function load_laborat(){
      var keyword = $('#cari_laborat').val();

      $.ajax({
            url : '<?php echo base_url(); ?>lab/lab_home_c/load_laborat',
            data : {keyword:keyword},
            type : "POST",
            dataType : "json",
            success : function(result){
                  $tr = "";

                  if(result == "" || result == null){
                        $tr = "<tr><td colspan='2' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
                  }else{
                        var no = 0;

                        for(var i=0; i<result.length; i++){
                              no++;

                              $tr += "<tr style='cursor:pointer;' onclick='klik_laborat("+result[i].ID+");'>"+
                                                "<td style='text-align:center;'>"+no+"</td>"+
                                                "<td>"+result[i].JENIS_LABORAT+"</td>"+
                                          "</tr>";
                        }
                  }

                  $('#tb_laborat tbody').html($tr);
            }
      });

      $('#cari_laborat').off('keyup').keyup(function(){
            load_laborat();
      });
}

function klik_laborat(id){
      $('#tutup_laborat').click();

      $.ajax({
            url : '<?php echo base_url(); ?>lab/lab_home_c/klik_laborat',
            data : {id:id},
            type : "POST",
            dataType : "json",
            success : function(row){
                  $('#id_laborat').val(id);
                  $('#jenis_laborat').val(row['JENIS_LABORAT']);
            }
      });
}
</script>

<div class="col-lg-12">
	<div class="row">
		<div class="col-md-6">
            <div class="card-box">
            	<h4><i class="fa fa-user"></i> Rekam Medik Pasien</h4>
            	<hr/>
            	<table class="table">
            		<tbody>
            			<tr>
            				<td>NO. RM</td>
            				<td>:</td>
            				<td><span style="color:#0066b2;"><?php echo $dt->KODE_PASIEN; ?></span></td>
            				<td>NAMA</td>
            				<td>:</td>
            				<td><span style="color:#0066b2;"><?php echo $dt->NAMA_PASIEN; ?></span></td>
            			</tr>
            			<tr>
            				<?php
	                    		$jk = "";
	                    		if($dt->JENIS_KELAMIN=="L"){$jk="Laki - Laki";}else{$jk="Perempuan";}
	                    	?>
            				<td>JENIS KELAMIN</td>
            				<td>:</td>
            				<td><span style="color:#0066b2;"><?php echo $jk; ?></span></td>
            				<td>UMUR</td>
            				<td>:</td>
            				<td><span style="color:#0066b2;"><?php echo $dt->UMUR; ?> Tahun</span></td>
            			</tr>
            			<tr>
            				<?php
	                    		$kerja = "";
	                    		if($dt->PEKERJAAN=="" || $dt->PEKERJAAN==null){$kerja="-";}else{$kerja=$dt->PEKERJAAN;}
	                    	?>
            				<td>ALAMAT</td>
            				<td>:</td>
            				<td>
            					<span style="color:#0066b2;">
            						<?php echo $dt->ALAMAT; ?> Kec. <?php echo $dt->KECAMATAN; ?><br>
            						Kel. <?php echo $dt->KELURAHAN; ?> <br>
            						Kec. <?php echo $dt->KOTA; ?>
            					</span>
            				</td>
            				<td>PEKERJAAN</td>
            				<td>:</td>
            				<td><span style="color:#0066b2;"><?php echo $kerja; ?></span></td>
            			</tr>
            		</tbody>
            	</table>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card-box">
            	<h4><i class="fa fa-user-md"></i> Dokter</h4>
            	<hr/>
            	<table class="table">
            		<tbody>
	            		<tr>
	            			<td>ASAL RUJUKAN</td>
	            			<td>:</td>
	            			<td><span style="color:#0066b2;"><?php echo $dt->ASAL_RUJUKAN; ?></td>
	            		</tr>
	            		<tr>
	            			<td>PELAYANAN</td>
	            			<td>:</td>
	            			<td><span style="color:#0066b2;"><?php echo $dt->STATUS; ?></td>
	            		</tr>
	            		<tr>
	            			<td>POLI</td>
	            			<td>:</td>
	            			<td><span style="color:#0066b2;"><?php echo $dt->NAMA_POLI; ?></td>
	            		</tr>
	            		<tr>
	            			<td>DOKTER</td>
	            			<td>:</td>
	            			<td><span style="color:#0066b2;"><?php echo $dt->NAMA_DOKTER; ?></td>
	            		</tr>
            		</tbody>
            	</table>
            </div>
        </div>
	</div>
</div>

<div class="col-lg-12">
      <div class="card-box">
            <div class="row">
                  <ul class="nav nav-tabs">
                      <li role="presentation" id="dt_laborat">
                          <a href="#laborat1" role="tab" data-toggle="tab"><i class="fa fa-building"></i>&nbsp;Laboraturium</a>
                      </li>
                  </ul>
                  <div class="tab-content">
                        <form class="form-horizontal" id="view_laborat">
                              <div class="form-group">
                                    <div class="col-md-6">
                                          <h4 class="m-t-0"><i class="fa fa-table"></i>&nbsp;<b>Tabel Laborat</b></h4>
                                    </div>
                                    <div class="col-md-6">
                                    <button class="btn btn-primary m-b-5 pull-right" type="button" id="btn_tambah_lab">
                                                <i class="fa fa-plus"></i>&nbsp;<b>Tambah Laborat</b>
                                          </button>
                                    </div>
                              </div>
                              <div class="form-group">
                                    <div class="col-md-12">
                                    <div class="table-responsive">
                                          <table id="tabel_laborat" class="table table-bordered">
                                              <thead>
                                                  <tr class="merah">
                                                      <th style="color:#fff; text-align:center;">No</th>
                                                      <th style="color:#fff; text-align:center;">Tanggal</th>
                                                      <th style="color:#fff; text-align:center;">Jenis Laborat</th>
                                                      <th style="color:#fff; text-align:center;">Cito</th>
                                                      <th style="color:#fff; text-align:center;">Total Tarif</th>
                                                      <th style="color:#fff; text-align:center;">Cetak</th>
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
                                            <img alt="user" class="img-responsive img-circle" src="<?php //echo base_url(); ?>picture/poli/Money_44325.png">
                                            <div class="wid-u-info">
                                                <small class="text-primary"><b>Grand Total</b></small>
                                                <h4 class="m-t-0 m-b-5 font-600 text-danger" id="grandtotal_laborat">0</h4>
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                              </div>
                        </form>

                        <form class="form-horizontal" id="view_laborat_tambah" action="" method="post">
                              <input type="hidden" name="id_rj" id="id_rj" value="<?php //echo $id; ?>">
                              <input type="hidden" name="id_poli" value="<?php //echo $dt->ID_POLI; ?>">
                              <input type="hidden" name="id_dokter" value="<?php //echo $dt->ID_DOKTER; ?>">
                              <input type="hidden" name="id_pasien" value="<?php //echo $dt->ID; ?>">
                              <h4><i class="fa fa-plus"></i> Tambah Laborat</h4>
                              <hr>
                              <div class="form-group">
                                    <label class="col-md-2 control-label">Kode</label>
                                    <div class="col-md-5">
                                          <input type="text" class="form-control" name="kode_lab" id="kode_lab" value="" readonly>
                                    </div>
                              </div>
                              <div class="form-group">
                                <label class="col-md-2 control-label">Jenis Laborat</label>
                                <div class="col-md-5">
                                    <div class="input-group">
                                          <input type="hidden" name="id_laborat" id="id_laborat" value="">
                                        <input type="text" class="form-control" id="jenis_laborat" value="" readonly>
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-primary btn_jenis_laborat" style="cursor:cursor;"><i class="fa fa-search"></i></button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Pemeriksaan</label>
                                <div class="col-md-5">
                                    <div class="input-group">
                                        <input type="text" class="form-control" value="" readonly="readonly" required="required">
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-inverse btn_pemeriksaan"><i class="fa fa-search"></i></button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">&nbsp;</label>
                                <div class="col-md-10">
                                    <div class="table-responsive">
                                          <table id="tabel_tambah_pemeriksaan" class="table table-bordered">
                                              <thead>
                                                  <tr class="kuning_tr">
                                                      <th style="color:#fff; text-align:center;">Pemeriksaan</th>
                                                      <th style="color:#fff; text-align:center;">Hasil</th>
                                                      <th style="color:#fff; text-align:center;">Nilai Rujukan</th>
                                                      <th style="color:#fff; text-align:center;">Tarif</th>
                                                      <th style="color:#fff; text-align:center;">Sub Total</th>
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
                                <label class="col-md-2 control-label">Total Tarif</label>
                                <div class="col-md-5">
                                    <input type="text" class="form-control" name="total_tarif_pemeriksaan" id="total_tarif_pemeriksaan" value="" readonly="readonly">
                                </div>
                            </div>
                            <div class="form-group">
                              <label class="col-md-2 control-label">Cito</label>
                              <div class="col-md-5">
                                    <div class="radio radio-primary radio-inline">
                                        <input type="radio" id="inlineRadio1" value="1" name="cito">
                                        <label for="inlineRadio1"> Aktif </label>
                                    </div>
                                    <div class="radio radio-primary radio-inline">
                                        <input type="radio" id="inlineRadio2" value="0" name="cito">
                                        <label for="inlineRadio2"> Tidak Aktif </label>
                                    </div>
                              </div>
                            </div>
                            <hr>
                            <center>
                                <button type="button" class="btn btn-success" id="simpanLab"><i class="fa fa-save"></i> <b>Simpan</b></button>
                                <button type="button" class="btn btn-danger" id="batalLab"><i class="fa fa-times"></i> <b>Batal</b></button>
                            </center>
                        </form>
                  </div>
            </div>
      </div>
</div>

<button class="btn btn-primary" data-toggle="modal" data-target="#myModal1_laborat" id="popup_laborat" style="display:none;">Standard Modal</button>
<div id="myModal1_laborat" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="width:50%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Data Jenis Laborat</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form">
                        <div class="form-group">
                            <div class="col-md-12">
                                  <div class="input-group">
                                      <input type="text" class="form-control" id="cari_laborat" placeholder="Cari..." value="">
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
                            <table class="table table-hover table-bordered" id="tb_laborat">
                                <thead>
                                    <tr class="hijau_popup">
                                        <th style="text-align:center; color: #fff;" width="50">No</th>
                                        <th style="text-align:center; color: #fff;">Jenis Laborat</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                            </table>
                        </div>
                  </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_laborat">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
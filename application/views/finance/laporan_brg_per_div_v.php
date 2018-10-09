<script type="text/javascript" src="<?php echo base_url(); ?>js-devan/jquery-1.11.1.min.js"></script>
<style type="text/css">
#form_tanggal, #form_filter_tanggal, #form_bulan, #form_filter_bulan, #form_divisi, #form_filter_divisi{
	display: none;
}
</style>
<script type="text/javascript">
var ajax = "";
$(document).ready(function(){
	  <?php if($this->session->flashdata('sukses')){?>
        notif_simpan();
    <?php }else if($this->session->flashdata('ubah')){?>
        notif_ubah();
    <?php }else if($this->session->flashdata('hapus')){ ?>
        notif_hapus();
    <?php } ?>

    $('#btn_reset').change(function(){
      window.location = "<?php echo base_url(); ?>finance/laporan_brg_per_div_c";
    });

    $('#jumlah_tampil').change(function(){
    	data_peralatan();
    });

    $('#btn_cari').click(function(){
      data_peralatan();
      $('#btn_print').removeAttr('disabled');
    });

    $('#klik_semua').click(function(){
      $('#form_semua').show();
      $('#form_tanggal').hide();
      $('#form_filter_tanggal').hide();
      $('#form_bulan').hide();
      $('#form_filter_bulan').hide();
  	});

    $('#klik_tanggal').click(function(){
      $('#form_semua').hide();
      $('#form_tanggal').show();
      $('#form_filter_tanggal').show();
      $('#form_bulan').hide();
      $('#form_filter_bulan').hide();
  	});

    $('#klik_bulan').click(function(){
      $('#form_semua').hide();
      $('#form_tanggal').hide();
      $('#form_filter_tanggal').hide();
      $('#form_bulan').show();
      $('#form_filter_bulan').show();
  	});

    $('.btn_departemen').click(function(){
  		$('#popup_departemen').click();
  		get_departemen();
      $('#id_divisi').val("");
      $('#divisi').val("");
  	});

  	$('.btn_divisi').click(function(){
  		$('#popup_divisi').click();
  		get_divisi();
  	});
});

function hitung_tanggal_kurang_dari(){
  toastr.options = {
      "closeButton": false,
      "debug": false,
      "newestOnTop": false,
      "progressBar": true,
      "positionClass": "toast-bottom-right",
      "preventDuplicates": false,
      "onclick": null,
      "showDuration": "300",
      "hideDuration": "1000",
      "timeOut": "5000",
      "extendedTimeOut": "1000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut"
  }

  var tanggal = $('#tanggal_sampai').val();
  var d = tanggal.substr(0,2);
  var m = tanggal.substr(3,2);
  var y = tanggal.substr(6);
  var date1 = "<?php echo date('Y-m-d'); ?>";
  var date2 = y+'-'+m+'-'+d;

  // First we split the values to arrays date1[0] is the year, [1] the month and [2] the day
  date1 = date1.split('-');
  date2 = date2.split('-');

  // Now we convert the array to a Date object, which has several helpful methods
  date1 = new Date(date1[0], date1[1], date1[2]);
  date2 = new Date(date2[0], date2[1], date2[2]);

  // We use the getTime() method and get the unixtime (in milliseconds, but we want seconds, therefore we divide it through 1000)
  date1_unixtime = parseInt(date1.getTime() / 1000);
  date2_unixtime = parseInt(date2.getTime() / 1000);

  if(date2_unixtime < date1_unixtime){
    toastr["error"]("Tanggal tidak boleh kurang!", "Notifikasi");
    $('#btn_cari_tgl').attr('disabled','disabled');
    $('#tanggal_sampai').focus();
  }else{
    $('#btn_cari_tgl').removeAttr('disabled');
  }
}

function data_peralatan(){
	$('#popup_load').show();

  var id_departemen = $('#id_departemen').val();
  var id_divisi = $('#id_divisi').val();
  var by = $("input[name='by']:checked").val();
	var tanggal_sekarang = $('#tanggal_sekarang').val();
  var tanggal_sampai = $('#tanggal_sampai').val();
  var bulan = $('#select_bulan').val();
  var tahun = $('#tahun').val();

	if(ajax){
		ajax.abort();
	}

	ajax = $.ajax({
		url : '<?php echo base_url(); ?>finance/laporan_brg_per_div_c/data_peralatan',
    data : {
      id_departemen:id_departemen,
      id_divisi:id_divisi,
      by:by,
      tanggal_sekarang:tanggal_sekarang,
      tanggal_sampai:tanggal_sampai,
      bulan:bulan,
      tahun:tahun
    },
		type : "POST",
		dataType : "json",
		success : function(result){
			$tr = "";

			if(result == "" || result == null){
				$tr = "<tr><td colspan='7' style='text-align:center;'><b>Data tidak ditemukan</b></td></tr>";
			}else{
				var no = 0;
				for(var i=0; i<result.length; i++){
					no++;
					$tr += "<tr>"+
								"<td style='text-align:center;'>"+no+"</td>"+
                "<td style='text-align:center;'>"+result[i].KODE_ALAT+"</td>"+
                "<td>"+result[i].NAMA_ALAT+"</td>"+
								"<td style='text-align:center;'>"+result[i].NAMA_KATEGORI+"</td>"+
								"<td style='text-align:right;'>"+NumberToMoney(result[i].HARGA_BELI)+"</td>"+
								"<td style='text-align:center;'>"+result[i].TOTAL+"</td>"+
								"<td>"+result[i].KETERANGAN+"</td>"+
							"</tr>";
				}
			}

			$('#tabel_alat tbody').html($tr);
			$('#popup_load').fadeOut();
		}
	});
}

function get_departemen(){
	var keyword = $('#cari_departemen').val();

	if(ajax){
		ajax.abort();
	}

	ajax = $.ajax({
      url : '<?php echo base_url(); ?>finance/laporan_brg_per_div_c/data_departemen',
      data : {keyword:keyword},
      type : "GET",
      dataType : "json",
      success : function(result){
          $tr = "";

          if(result == "" || result == null){
          	$tr = "<tr><td colspan='2' style='text-align:center;'><b>Data tidak ditemukan</b></td></tr>";
          }else{
            var no = 0;
            for(var i=0; i<result.length; i++){
            	no++;
              $tr += '<tr style="cursor:pointer;" onclick="klik_departemen('+result[i].ID+');">'+
            					'<td style="text-align:center;">'+no+'</td>'+
            					'<td>'+result[i].NAMA_DEP+'</td>'+
            				'</tr>';
            }
          }

          $('#tabel_departemen tbody').html($tr);
      }
  });

  $('#cari_departemen').off('keyup').keyup(function(){
  	get_departemen();
  });
}

function klik_departemen(id_departemen){
	$('#tutup_departemen').click();

	$.ajax({
  		url : '<?php echo base_url(); ?>finance/laporan_brg_per_div_c/klik_departemen',
  		data : {id_departemen:id_departemen},
  		type : "POST",
  		dataType : "json",
  		success : function(row){
        $('#id_departemen').val(id_departemen);
        $('#departemen').val(row['NAMA_DEP']);
  		}
	});

}

function get_divisi(){
  	var keyword = $('#cari_divisi').val();
    var id_departemen = $('#id_departemen').val();

  	if(ajax){
  		ajax.abort();
  	}
			
		ajax = $.ajax({
        url : '<?php echo base_url(); ?>finance/laporan_brg_per_div_c/data_divisi',
        data : {
					keyword:keyword,
					id_departemen:id_departemen
				},
        type : "POST",
        dataType : "json",
        success : function(result){
            $tr = "";

            if(result == "" || result == null){
            	$tr = "<tr><td colspan='2' style='text-align:center;'><b>Data tidak ditemukan</b></td></tr>";
            }else{
	            var no = 0;
	            for(var i=0; i<result.length; i++){
	            	no++;
	               $tr += '<tr style="cursor:pointer;" onclick="klik_divisi('+result[i].ID+');">'+
  	            					'<td style="text-align:center;">'+no+'</td>'+
  	            					'<td>'+result[i].NAMA_DIV+'</td>'+
	            				  '</tr>';
	            }
            }

            $('#tabel_divisi tbody').html($tr);
        }
	  });

    $('#cari_divisi').off('keyup').keyup(function(){
    	get_divisi();
    });
}

function klik_divisi(id_divisi){
  	$('#tutup_divisi').click();

  	$.ajax({
  		url : '<?php echo base_url(); ?>finance/laporan_brg_per_div_c/klik_divisi',
  		data : {id_divisi:id_divisi},
  		type : "POST",
  		dataType : "json",
  		success : function(row){
        $('#id_divisi').val(id_divisi);
        $('#divisi').val(row['NAMA_DIV']);
  		}
  	});
}
</script>

<div id="popup_load">
    <div class="window_load">
        <img src="<?=base_url()?>picture/progress.gif" height="100" width="125">
    </div>
</div>

<div class="row">
  <div class="col-lg-12">
      <div class="card-box">
      	<form class="form-horizontal" role="form" method="post" action="<?php echo base_url(); ?>finance/laporan_brg_per_div_c/cetak" target="_blank">
          <div class="form-body">
            <div class="form-group">
                <label class="col-md-1 control-label" style="text-align:left;">Departemen :</label>
                <div class="col-md-3">
                    <div class="input-group">
                        <input type="hidden" id="id_departemen" value="" name="id_departemen">
                        <input type="text" class="form-control" id="departemen" value="" required="required" readonly>
                        <span class="input-group-btn">
                            <button class="btn waves-effect waves-light btn-danger btn_departemen" type="button">
                                <i class="fa fa-search"></i>
                            </button>
                        </span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-1 control-label" style="text-align:left;">Divisi :</label>
                <div class="col-md-3">
                    <div class="input-group">
                        <input type="hidden" id="id_divisi" value="" name="id_divisi">
                        <input type="text" class="form-control" id="divisi" value="" required="required" readonly>
                        <span class="input-group-btn">
                            <button class="btn waves-effect waves-light btn-warning btn_divisi" type="button">
                                <i class="fa fa-search"></i>
                            </button>
                        </span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-1 control-label" style="text-align:left;">Filter :</label>
                <div class="col-md-6">
                    <div class="radio radio-success radio-inline">
                        <input type="radio" name="by" value="Semua" id="klik_semua" checked="checked">
                        <label for="klik_semua"> Semua </label>
                    </div>
                    <div class="radio radio-success radio-inline">
                        <input type="radio" name="by" value="Tanggal" id="klik_tanggal">
                        <label for="klik_tanggal"> Tanggal </label>
                    </div>
                    <div class="radio radio-success radio-inline">
                        <input type="radio" name="by" value="Bulan" id="klik_bulan">
                        <label for="klik_bulan"> Bulan </label>
                    </div>
                </div>
            </div>
            <div id="form_filter_tanggal" class="form-group">
                <label class="col-md-1 control-label" style="text-align:left;">Tanggal :</label>
                <div class="col-sm-4">
                    <div class="input-group">
                        <input type="text" name="tanggal_sekarang" id="tanggal_sekarang" class="form-control" readonly onclick="javascript:NewCssCal('tanggal_sekarang')" placeholder="klik disini">
                        <span class="input-group-addon bg-primary b-0 text-white">s/d</span>
                        <input type="text" name="tanggal_sampai" id="tanggal_sampai" class="form-control" onclick="javascript:NewCssCal('tanggal_sampai')" placeholder="klik disini" readonly onchange="hitung_tanggal_kurang_dari();">
                    </div>
                </div>
            </div>
            <div id="form_filter_bulan" class="form-group">
                <label class="col-md-1 control-label" style="text-align:left;">Bulan :</label>
                <div class="col-sm-2">
                  <select class="form-control" id="select_bulan" name="select_bulan">
                      <option value="1" <?php if(date('n') == '1'){echo "selected='selected'";}else{echo "";}?>>Januari</option>
                      <option value="2" <?php if(date('n') == '2'){echo "selected='selected'";}else{echo "";}?>>Februari</option>
                      <option value="3" <?php if(date('n') == '3'){echo "selected='selected'";}else{echo "";}?>>Maret</option>
                      <option value="4" <?php if(date('n') == '4'){echo "selected='selected'";}else{echo "";}?>>April</option>
                      <option value="5" <?php if(date('n') == '5'){echo "selected='selected'";}else{echo "";}?>>Mei</option>
                      <option value="6" <?php if(date('n') == '6'){echo "selected='selected'";}else{echo "";}?>>Juni</option>
                      <option value="7" <?php if(date('n') == '7'){echo "selected='selected'";}else{echo "";}?>>Juli</option>
                      <option value="8" <?php if(date('n') == '8'){echo "selected='selected'";}else{echo "";}?>>Agustus</option>
                      <option value="9" <?php if(date('n') == '9'){echo "selected='selected'";}else{echo "";}?>>September</option>
                      <option value="10" <?php if(date('n') == '10'){echo "selected='selected'";}else{echo "";}?>>Oktober</option>
                      <option value="11" <?php if(date('n') == '11'){echo "selected='selected'";}else{echo "";}?>>November</option>
                      <option value="12" <?php if(date('n') == '12'){echo "selected='selected'";}else{echo "";}?>>Desember</option>
                  </select>
                </div>
                <label class="col-md-1 control-label">Tahun :</label>
                <div class="col-md-2">
                    <select class="form-control" name="tahun" id="tahun">
                    <?php
                        $tahun = date('Y');
                        $sel = "";
                        for($i=$tahun-5; $i<$tahun+1; $i++){
                            if($i == $tahun){
                                $sel = "selected='selected'";
                            }else{
                                $sel = "";
                            }
                    ?>
                        <option <?php echo $sel; ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
                    <?php
                        }
                    ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-1 control-label">&nbsp;</label>
                <div class="col-md-2">
                    <button type="button" class="btn btn-purple" id="btn_cari"><i class="fa fa-search"></i> Cari</button>
                </div>
            </div>
            <div class="form-group">
              <div class="col-md-12">
                <div class="table-responsive">
                    <table id="tabel_alat" class="table table-bordered">
                        <thead>
                            <tr class="biru">
                              <th style="color:#fff; text-align:center;" width="50">No</th>
                              <th style="color:#fff; text-align:center;">Kode Barang</th>
                              <th style="color:#fff; text-align:center;">Nama Barang</th>
                              <th style="color:#fff; text-align:center;">Kategori</th>
                              <th style="color:#fff; text-align:center;">Harga Beli</th>
                              <th style="color:#fff; text-align:center;">Stok</th>
                              <th style="color:#fff; text-align:center;">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="active"><td colspan='7' style='text-align:center;'><b>Belum Ada Data</b></td></tr>
                        </tbody>
                    </table>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="col-md-6">
                  <div class="radio radio-purple radio-inline">
                      <input type="radio" name="print" id="excel" value="excel">
                      <label for="excel"> Excel </label>
                  </div>
                  <div class="radio radio-purple radio-inline">
                      <input type="radio" name="print" id="pdf" value="pdf">
                      <label for="pdf"> PDF </label>
                  </div>
              </div>
            </div>
            <hr>
            <div class="form-group">
              <div class="col-md-12">
                <button type="submit" class="btn btn-success" id="btn_print" disabled="disabled"><i class="fa fa-print"></i> Print</button>
                <button type="button" class="btn btn-danger" id="btn_reset"><i class="fa fa-refresh"></i> Reset</button>
              </div>
            </div>
          </div>
        </form>
      </div>
  </div>
</div>

<button class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#myModal1" id="popup_departemen" style="display:none;" type="button">Standard Modal</button>
  <div id="myModal1" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                  <h4 class="modal-title" id="myModalLabel">Data Departemen</h4>
              </div>
              <div class="modal-body">
              	<form class="form-horizontal" role="form">
  		            <div class="form-group">
  		                <div class="col-md-12">
  			                <div class="input-group">
  			                    <input type="text" class="form-control" id="cari_departemen" placeholder="Cari..." value="">
  			                    <span class="input-group-btn">
  			                    	<button type="button" class="btn waves-effect waves-light btn-custom" style="cursor:default;">
  			                    		<i class="fa fa-search"></i>
  			                    	</button>
  			                    </span>
  			                </div>
  		                </div>
  		            </div>
  		        </form>
              	<div class="table table-responsive">
              		<div class="scroll-y">
  		                <table class="table table-hover" id="tabel_departemen">
  		                    <thead>
  		                        <tr class="merah_popup">
  		                            <th style="text-align:center; color: #fff;" width="50">No</th>
  		                            <th style="text-align:center; color: #fff;">Departemen</th>
  		                        </tr>
  		                    </thead>
  		                    <tbody>

  		                    </tbody>
  		                </table>
              		</div>
              	</div>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_departemen">Tutup</button>
              </div>
          </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->


  <button class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#myModal2" id="popup_divisi" style="display:none;">Standard Modal</button>
  <div id="myModal2" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                  <h4 class="modal-title" id="myModalLabel">Data Divisi</h4>
              </div>
              <div class="modal-body">
              	<form class="form-horizontal" role="form">
  		            <div class="form-group">
  		                <div class="col-md-12">
  			                <div class="input-group">
  			                    <input type="text" class="form-control" id="cari_divisi" placeholder="Cari..." value="">
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
  		                <table class="table table-hover" id="tabel_divisi">
  		                    <thead>
  		                        <tr class="merah_popup">
  		                            <th style="text-align:center; color: #fff;" width="50">No</th>
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
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_divisi">Tutup</button>
              </div>
          </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->

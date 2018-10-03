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

    data_peralatan();

    $('#jumlah_tampil').change(function(){
    	data_peralatan();
    });

    $('#klik_semua').click(function(){
      $('#form_semua').show();
      $('#form_tanggal').hide();
      $('#form_filter_tanggal').hide();
      $('#form_bulan').hide();
      $('#form_filter_bulan').hide();
      $('#form_divisi').hide();
      $('#form_filter_divisi').hide();
  	});
    $('#klik_tanggal').click(function(){
      $('#form_semua').hide();
      $('#form_tanggal').show();
      $('#form_filter_tanggal').show();
      $('#form_bulan').hide();
      $('#form_filter_bulan').hide();
      $('#form_divisi').hide();
      $('#form_filter_divisi').hide();
  	});
    $('#klik_bulan').click(function(){
      $('#form_semua').hide();
      $('#form_tanggal').hide();
      $('#form_filter_tanggal').hide();
      $('#form_bulan').show();
      $('#form_filter_bulan').show();
      $('#form_divisi').hide();
      $('#form_filter_divisi').hide();
  	});
    $('#klik_divisi').click(function(){
      $('#form_semua').hide();
      $('#form_tanggal').hide();
      $('#form_filter_tanggal').hide();
      $('#form_bulan').hide();
      $('#form_filter_bulan').hide();
      $('#form_divisi').show();
      $('#form_filter_divisi').show();
  	});

    $('.btn_departemen').click(function(){
  		$('#popup_departemen').click();
  		get_departemen();
  	});

  	$('.btn_divisi').click(function(){
  		$('#popup_divisi').click();
  		get_divisi();
  	});
});
function data_peralatan(){
	$('#popup_load').show();
	var id_divisi = $('#id_divisi').val();

	if(ajax){
		ajax.abort();
	}

	ajax = $.ajax({
		url : '<?php echo base_url(); ?>finance/log_laporan_barang_c/data_peralatan',
		type : "POST",
		dataType : "json",
		success : function(result){
			$tr = "";
			if(result == "" || result == null){
				$tr = "<tr><td colspan='9' style='text-align:center;'><b>Data tidak ditemukan</b></td></tr>";
			}else{
				var no = 0;
				for(var i=0; i<result.length; i++){
					no++;
					$tr += "<tr>"+
								"<td style='text-align:center;'>"+no+"</td>"+
								"<td>"+
									"<b>"+result[i].NAMA_ALAT+"</b><br>"+
									"<small>"+result[i].BARCODE+"</small>"+
								"</td>"+
								"<td style='text-align:center;'>"+result[i].URUT_BARANG+"</td>"+
								"<td>"+result[i].JENIS_ALAT+"</td>"+
								"<td style='text-align:right;'>"+NumberToMoney(result[i].HARGA_BELI)+"</td>"+
								"<td style='text-align:right;'>"+result[i].ISI+"</td>"+
								"<td style='text-align:center;'>"+formatTanggal(result[i].TANGGAL_MASUK)+"</td>"+
								"<td style='text-align:center;'>"+result[i].WAKTU_MASUK+"</td>"+
							"</tr>";
				}
			}
			$('#tabel_alat tbody').html($tr);
			paging();
			$('#popup_load').fadeOut();
		}
	});
}
function paging($selector){
	var jumlah_tampil = $('#jumlah_tampil').val();

    if(typeof $selector == 'undefined')
    {
        $selector = $("#tabel_alat tbody tr");
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
function range_tanggal(){
  $('#popup_load').show();
  var tanggal_sekarang = $('#tanggal_sekarang').val();
  var tanggal_sampai = $('#tanggal_sampai').val();
  $.ajax({
     url:'<?php echo base_url(); ?>finance/log_laporan_barang_c/range_tanggal',
     data : {
       tanggal_sekarang:tanggal_sekarang,
       tanggal_sampai:tanggal_sampai
     },
     type : "POST",
 		 dataType : "json",
     success: function(result){
       $tr = "";
 			if(result == "" || result == null){
 				$tr = "<tr><td colspan='9' style='text-align:center;'><b>Data tidak ditemukan</b></td></tr>";
 			}else{
 				var no = 0;
 				for(var i=0; i<result.length; i++){
 					no++;
 					$tr += "<tr>"+
 								"<td style='text-align:center;'>"+no+"</td>"+
 								"<td>"+
 									"<b>"+result[i].NAMA_ALAT+"</b><br>"+
 									"<small>"+result[i].BARCODE+"</small>"+
 								"</td>"+
 								"<td style='text-align:center;'>"+result[i].URUT_BARANG+"</td>"+
 								"<td>"+result[i].JENIS_ALAT+"</td>"+
 								"<td style='text-align:right;'>"+NumberToMoney(result[i].HARGA_BELI)+"</td>"+
 								"<td style='text-align:right;'>"+result[i].ISI+"</td>"+
 								"<td style='text-align:center;'>"+formatTanggal(result[i].TANGGAL_MASUK)+"</td>"+
 								"<td style='text-align:center;'>"+result[i].WAKTU_MASUK+"</td>"+
 							"</tr>";
 				}
 			}
 			$('#tabel_alat tbody').html($tr);
 			paging();
      $('#popup_load').fadeOut();
     }
   });
}
function range_bulan(){
  $('#popup_load').show();
  var select_bulan = $('#select_bulan').val();
  $.ajax({
     url:'<?php echo base_url(); ?>finance/log_laporan_barang_c/range_bulan',
     data : {select_bulan:select_bulan},
     type : "POST",
 		 dataType : "json",
     success: function(result){
       $tr = "";
 			if(result == "" || result == null){
 				$tr = "<tr><td colspan='9' style='text-align:center;'><b>Data tidak ditemukan</b></td></tr>";
 			}else{
 				var no = 0;
 				for(var i=0; i<result.length; i++){
 					no++;
 					$tr += "<tr>"+
 								"<td style='text-align:center;'>"+no+"</td>"+
 								"<td>"+
 									"<b>"+result[i].NAMA_ALAT+"</b><br>"+
 									"<small>"+result[i].BARCODE+"</small>"+
 								"</td>"+
 								"<td style='text-align:center;'>"+result[i].URUT_BARANG+"</td>"+
 								"<td>"+result[i].JENIS_ALAT+"</td>"+
 								"<td style='text-align:right;'>"+NumberToMoney(result[i].HARGA_BELI)+"</td>"+
 								"<td style='text-align:right;'>"+result[i].ISI+"</td>"+
 								"<td style='text-align:center;'>"+formatTanggal(result[i].TANGGAL_MASUK)+"</td>"+
 								"<td style='text-align:center;'>"+result[i].WAKTU_MASUK+"</td>"+
 							"</tr>";
 				}
 			}
 			$('#tabel_alat tbody').html($tr);
 			paging();
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
        url : '<?php echo base_url(); ?>finance/log_laporan_barang_c/data_departemen',
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
	            					'<td style="text-align:center;">'+result[i].NAMA_DEP+'</td>'+
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
		url : '<?php echo base_url(); ?>finance/log_laporan_barang_c/klik_departemen',
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
	if(ajax){
		ajax.abort();
	}
			var id_departemen = $('#id_departemen').val();
			ajax = $.ajax({
		        url : '<?php echo base_url(); ?>finance/log_peralatan_medis_c/data_divisi',
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
			            					'<td style="text-align:center;">'+result[i].NAMA_DIV+'</td>'+
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
		url : '<?php echo base_url(); ?>finance/log_laporan_barang_c/klik_divisi',
		data : {id_divisi:id_divisi},
		type : "POST",
		dataType : "json",
		success : function(row){
                $('#id_divisi').val(id_divisi);
                $('#divisi').val(row['NAMA_DIV']);
		}
	});
}
function search_divisi(){
  $('#popup_load').show();
  var id_divisi = $('#id_divisi').val();
  $.ajax({
     url:'<?php echo base_url(); ?>finance/log_laporan_barang_c/search_divisi',
     data : {id_divisi:id_divisi},
     type : "POST",
 		 dataType : "json",
     success: function(result){
       $tr = "";
 			if(result == "" || result == null){
 				$tr = "<tr><td colspan='9' style='text-align:center;'><b>Data tidak ditemukan</b></td></tr>";
 			}else{
 				var no = 0;
 				for(var i=0; i<result.length; i++){
 					no++;
 					$tr += "<tr>"+
 								"<td style='text-align:center;'>"+no+"</td>"+
 								"<td>"+
 									"<b>"+result[i].NAMA_ALAT+"</b><br>"+
 									"<small>"+result[i].BARCODE+"</small>"+
 								"</td>"+
 								"<td style='text-align:center;'>"+result[i].URUT_BARANG+"</td>"+
 								"<td>"+result[i].JENIS_ALAT+"</td>"+
 								"<td style='text-align:right;'>"+NumberToMoney(result[i].HARGA_BELI)+"</td>"+
 								"<td style='text-align:right;'>"+result[i].ISI+"</td>"+
 								"<td style='text-align:center;'>"+formatTanggal(result[i].TANGGAL_MASUK)+"</td>"+
 								"<td style='text-align:center;'>"+result[i].WAKTU_MASUK+"</td>"+
 							"</tr>";
 				}
 			}
 			$('#tabel_alat tbody').html($tr);
 			paging();
      $('#popup_load').fadeOut();
     }
   });
}
</script>

<div id="popup_load">
    <div class="window_load">
        <img src="<?=base_url()?>picture/progress.gif" height="100" width="125">
    </div>
</div>

<div class="col-lg-12">
    <div class="card-box">
    	<form class="form-horizontal" role="form" method="POST" action="<?php echo base_url(); ?>finance/log_laporan_barang_c/cetak" target="_blank">
        <div class="form-body">
          <div class="form-group">
              <label class="col-md-2 control-label" style="text-align:left; width: 13%;">Cetak Berdasarkan :</label>
              <div class="col-md-6">
                  <div class="radio radio-purple radio-inline">
                      <input type="radio" name="by" value="Semua" id="klik_semua" checked="checked">
                      <label for="klik_semua"> Semua </label>
                  </div>
                  <div class="radio radio-purple radio-inline">
                      <input type="radio" name="by" value="Tanggal" id="klik_tanggal">
                      <label for="klik_tanggal"> Tanggal </label>
                  </div>
                  <div class="radio radio-purple radio-inline">
                      <input type="radio" name="by" value="Bulan" id="klik_bulan">
                      <label for="klik_bulan"> Bulan </label>
                  </div>
                  <div class="radio radio-purple radio-inline">
                      <input type="radio" name="by" value="Divisi" id="klik_divisi">
                      <label for="klik_divisi"> Divisi </label>
                  </div>
              </div>
          </div>
          <div id="form_filter_tanggal" class="form-group">
              <label class="col-md-1 control-label" style="text-align:left; width: 7%;">Tanggal :</label>
              <div class="col-sm-4">
                  <div class="input-group">
                      <input type="text" data-mask="99-99-9999" name="tanggal_sekarang" id="tanggal_sekarang" class="form-control input-sm">
                      <span class="input-group-addon bg-primary b-0 text-white">S/D</span>
                      <input type="text" data-mask="99-99-9999" name="tanggal_sampai" id="tanggal_sampai" class="form-control input-sm">
                  </div>
              </div>
              <button type="button" class="btn btn-info btn-sm" onclick="range_tanggal();" name="button"><i class="fa fa-search"></i> Cari</button>
          </div>
          <div id="form_filter_bulan" class="form-group">
              <label class="col-md-1 control-label" style="text-align:left; width: 6%;">Bulan :</label>
              <div class="col-sm-2">
                <select class="form-control input-sm" id="select_bulan" name="select_bulan">
                    <option value="01">Januari</option>
                    <option value="02">Februari</option>
                    <option value="03">Maret</option>
                    <option value="04">April</option>
                    <option value="05">Mei</option>
                    <option value="06">Juni</option>
                    <option value="07">Juli</option>
                    <option value="08">Agustus</option>
                    <option value="09">September</option>
                    <option value="10">Oktober</option>
                    <option value="11">November</option>
                    <option value="12">Desember</option>
                </select>
              </div>
              <button type="button" class="btn btn-info btn-sm" onclick="range_bulan();" name="button"><i class="fa fa-search"></i> Cari</button>
          </div>
          <div id="form_filter_divisi" class="form-group">
              <label class="col-md-1 control-label" style="text-align:left; width: 10%;">Departemen :</label>
              <div class="col-md-3">
                  <div class="input-group">
                      <input type="hidden" id="id_departemen" value="" name="id_departemen">
                      <input type="text" class="form-control input-sm" id="departemen" value="" required="required" readonly>
                      <span class="input-group-btn">
                          <button class="btn waves-effect waves-light btn-default btn_departemen btn-sm" type="button">
                              <i class="fa fa-search"></i>
                          </button>
                      </span>
                  </div>
              </div>
              <label class="col-md-1 control-label" style="text-align:left; width: 6%;">Divisi :</label>
              <div class="col-md-3">
                  <div class="input-group">
                      <input type="hidden" id="id_divisi" value="" name="id_divisi">
                      <input type="text" class="form-control input-sm" id="divisi" value="" required="required" readonly>
                      <span class="input-group-btn">
                          <button class="btn waves-effect waves-light btn-default btn_divisi btn-sm" type="button">
                              <i class="fa fa-search"></i>
                          </button>
                      </span>
                  </div>
              </div>
              <button type="button" class="btn btn-info btn-sm" onclick="search_divisi();" name="button"><i class="fa fa-search"></i> Cari</button>
          </div>
          <div class="form-group col-md-12">
            <div class="table-responsive">
                  <table id="tabel_alat" class="table table-bordered">
                      <thead>
                          <tr class="biru">
                              <th style="color:#fff; text-align:center;" width="50">No</th>
                              <th style="color:#fff; text-align:center;">Nama Alat</th>
                              <th style="color:#fff; text-align:center;">No. FIFO</th>
                              <th style="color:#fff; text-align:center;">Jenis Alat</th>
                              <th style="color:#fff; text-align:center;">Harga Beli</th>
                              <th style="color:#fff; text-align:center;">Stok</th>
                              <th style="color:#fff; text-align:center;">Tanggal Masuk</th>
                              <th style="color:#fff; text-align:center;">Waktu</th>
                          </tr>
                      </thead>
                      <tbody>
                      </tbody>
                  </table>
              </div>
          </div>
          <div class="form-group">
            <div class="col-md-10">
              <div id="tablePaging"> </div>
            </div>
          </div>
          <div class="form-group">
            <div class="col-md-9">
              &nbsp;
            </div>
            <label class="col-md-2 control-label">Jumlah Tampil</label>
              <div class="col-md-1 pull-right">
                <select class="form-control" id="jumlah_tampil">
                      <option value="10">10</option>
                      <option value="20">20</option>
                      <option value="50">50</option>
                      <option value="100">100</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <div class="col-md-6">
                  <div class="radio radio-purple radio-inline">
                      <input type="radio" name="print" id="excel" value="excel" checked="checked">
                      <label for="excel"> Excel </label>
                  </div>
                  <div class="radio radio-purple radio-inline">
                      <input type="radio" name="print" id="pdf" value="pdf" >
                      <label for="pdf"> PDF </label>
                  </div>
              </div>
            </div>
            <div class="form-group">
              <div class="col-md-2" style="width: 10%;">
                <button type="submit" class="btn btn-info" name="button"><i class="fa fa-print"></i> Print</button>
              </div>
            </div>
        </div>
      </form>
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

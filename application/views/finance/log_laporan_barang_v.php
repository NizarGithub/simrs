<script type="text/javascript" src="<?php echo base_url(); ?>js-devan/jquery-1.11.1.min.js"></script>
<style type="text/css">
#form_tanggal, #form_filter_tanggal, #form_bulan, #form_filter_bulan {
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

    $('#btn_reset').change(function(){
      window.location = "<?php echo base_url(); ?>finance/log_laporan_barang_c";
    });

    $('#jumlah_tampil').change(function(){
    	data_peralatan();
    });

    $('#klik_semua').click(function(){
      $('#form_semua').show();
      $('#form_tanggal').hide();
      $('#form_filter_tanggal').hide();
      $('#form_bulan').hide();
      $('#form_filter_bulan').hide();
      data_peralatan();
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

function data_peralatan(){
	$('#popup_load').show();

  var by = $("input[name='by']:checked").val();
	var tanggal_sekarang = $('#tanggal_sekarang').val();
  var tanggal_sampai = $('#tanggal_sampai').val();
  var bulan = $('#select_bulan').val();
  var tahun = $('#tahun').val();

	if(ajax){
		ajax.abort();
	}

	ajax = $.ajax({
		url : '<?php echo base_url(); ?>finance/log_laporan_barang_c/data_peralatan',
    data : {
      by:by,
      tanggal_sekarang:tanggal_sekarang,
      tanggal_sampai:tanggal_sampai,
      bulan:bulan,
      tahun:tahun
    },
		type : "GET",
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
      $('#total_data').html(parseInt(result.length));
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

<div class="row">
  <div class="col-lg-12">
      <div class="card-box">
      	<form class="form-horizontal" role="form" method="post" action="<?php echo base_url(); ?>finance/log_laporan_barang_c/cetak" target="_blank">
          <div class="form-body">
            <div class="form-group">
                <label class="col-md-2 control-label" style="text-align:left; width: 10%;">Cetak Berdasarkan :</label>
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
                </div>
            </div>
            <div id="form_filter_tanggal" class="form-group">
                <label class="col-md-1 control-label" style="text-align:left; width: 10%;">Tanggal :</label>
                <div class="col-sm-4">
                    <div class="input-group">
                        <input type="text" name="tanggal_sekarang" id="tanggal_sekarang" class="form-control" readonly onclick="javascript:NewCssCal('tanggal_sekarang')" placeholder="klik disini">
                        <span class="input-group-addon bg-primary b-0 text-white">s/d</span>
                        <input type="text" name="tanggal_sampai" id="tanggal_sampai" class="form-control" onclick="javascript:NewCssCal('tanggal_sampai')" placeholder="klik disini" readonly onchange="hitung_tanggal_kurang_dari();">
                    </div>
                </div>
                <button type="button" class="btn btn-info" onclick="data_peralatan();" name="button" id="btn_cari_tgl"><i class="fa fa-search"></i> Cari</button>
            </div>
            <div id="form_filter_bulan" class="form-group">
                <label class="col-md-1 control-label" style="text-align:left; width: 10%;">Bulan :</label>
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
                <button type="button" class="btn btn-info" onclick="data_peralatan();" name="button"><i class="fa fa-search"></i> Cari</button>
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

                        </tbody>
                    </table>
                </div>
              </div>
            </div>
            <div class="form-group">
                <div class="col-md-10">
                    <div id="tablePaging"> </div>
                </div>
                <div class="col-md-2">
                    <h4 class="header-title pull-right">Total Barang : <b id="total_data"></b></h4>
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
                        <input type="radio" name="print" id="excel" value="excel">
                        <label for="excel"> Excel </label>
                    </div>
                    <div class="radio radio-purple radio-inline">
                        <input type="radio" name="print" id="pdf" value="pdf" >
                        <label for="pdf"> PDF </label>
                    </div>
                </div>
              </div>
              <hr>
              <div class="form-group">
                <div class="col-md-12">
                  <button type="submit" class="btn btn-success" name="button"><i class="fa fa-print"></i> Print</button>
                  <button type="button" class="btn btn-danger" id="btn_reset"><i class="fa fa-refresh"></i> Reset</button>
                </div>
              </div>
          </div>
        </form>
      </div>
  </div>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>js-devan/jquery-1.11.1.min.js"></script>
<style type="text/css">
#view_tanggal,
.view_bulan{
	display: none;
}
</style>
<script type="text/javascript">
var ajax = '';
$(document).ready(function(){
	$("input[name='jenis_cetak']").click(function(){
		var ctk = $("input[name='jenis_cetak']:checked").val();
		if(ctk == 'Harian'){
			$('#view_tanggal').show();
			$('.view_bulan').hide();
		}else{
			$('#view_tanggal').hide();
			$('.view_bulan').show();
		}
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

	var tanggal = $('#tanggal_akhir').val();
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
		$('#btn_cari').attr('disabled','disabled');
		$('#tanggal_akhir').focus();
	}else{
		$('#btn_cari').removeAttr('disabled');
	}
}
</script>
<div id="popup_load">
    <div class="window_load">
        <img src="<?=base_url()?>picture/progress.gif" height="100" width="125">
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card-box">
            <ul class="nav nav-tabs">
                <li role="presentation" class="active">
                    <a href="#home1" role="tab" data-toggle="tab">Data Pasien</a>
                </li>
            </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade in active" id="home1">
                    <form class="form-horizontal" role="form" method="post" target="_blank" action="">
                        <div class="form-group">
                            <label class="col-md-1 control-label" style="text-align: left;">Cetak</label>
			                <div class="col-md-6">
			                    <div class="radio radio-purple radio-inline">
			                        <input type="radio" name="jenis_cetak" value="Harian" id="harian">
			                        <label for="harian"> Harian </label>
			                    </div>
			                    <div class="radio radio-purple radio-inline">
			                        <input type="radio" name="jenis_cetak" value="Bulanan" id="bulanan">
			                        <label for="bulanan"> Bulanan </label>
			                    </div>
			                </div>
                        </div>
                        <div class="form-group" id="view_tanggal">
                            <label class="col-md-1 control-label">&nbsp;</label>
                            <div class="col-sm-4">
                                <div id="date-range" class="input-daterange input-group">
                                    <input type="text" name="tanggal_awal" id="tanggal_awal" class="form-control" value="" readonly onclick="javascript:NewCssCal('tanggal_awal');">
                                    <span class="input-group-addon bg-primary b-0 text-white">s/d</span>
                                    <input type="text" name="tanggal_akhir" id="tanggal_akhir" class="form-control" value="" readonly onclick="javascript:NewCssCal('tanggal_akhir');" onchange="hitung_tanggal_kurang_dari();">
                                </div>
                            </div>
                        </div>
                        <div class="form-group view_bulan">
                            <label class="col-md-1 control-label" style="text-align: left;">Bulan</label>
                            <div class="col-sm-2">
                                <select class="form-control" name="bulan" id="bulan">
                                <?php
                                    $bulan = array(
                                        0 => "",
                                        1 => "Januari",
                                        2 => "Februari",
                                        3 => "Maret",
                                        4 => "April",
                                        5 => "Mei",
                                        6 => "Juni",
                                        7 => "Juli",
                                        8 => "Agustus",
                                        9 => "September",
                                        10 => "Oktober",
                                        11 => "November",
                                        12 => "Desember"
                                    );
                                    $now = date('n');
                                    $selected = "";

                                    for ($i=0; $i < count($bulan); $i++) { 
                                        if($i == $now){
                                            $selected = "selected='selected'";
                                        }else{
                                            $selected = "";
                                        }
                                ?>
                                    <option <?php echo $selected; ?> value="<?php echo $i; ?>"><?php echo $bulan[$i]; ?></option>
                                <?php
                                    }
                                ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group view_bulan">
                            <label class="col-md-1 control-label" style="text-align: left;">Tahun</label>
                            <div class="col-sm-2">
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
                                <button class="btn btn-success waves-effect w-md waves-light" type="button" id="btn_cari"><i class="fa fa-search"></i> Cari</button>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group">
                        	<div class="col-md-12">
	                            <div class="table-responsive">
                                    <div class="scroll-y">
    	                                <table class="table table-hover table-bordered" id="tabel_data">
    	                                    <thead>
    	                                        <tr class="merah">
    	                                            <th style="color:#fff; text-align:center; vertical-align: middle;">No</th>
                                                    <th style="color:#fff; text-align:center; vertical-align: middle;">No. RM</th>
                                                    <th style="color:#fff; text-align:center; vertical-align: middle;">Nama Pasien</th>
                                                    <th style="color:#fff; text-align:center; vertical-align: middle;">Tanggal MRS</th>
                                                    <th style="color:#fff; text-align:center; vertical-align: middle;">Nama Poli</th>
    	                                        </tr>
    	                                    </thead>
    	                                    <tbody>
    	                                        <tr class="active">
    	                                        	<td colspan="5" style="text-align: center;">Belum ada data</td>
    	                                        </tr>
    	                                    </tbody>
    	                                </table>
                                    </div>
	                            </div>
                            </div>
                        </div>
                        <div class="form-group">
                        	<div class="col-md-10">
                        		&nbsp;
                        	</div>
                            <div class="col-md-2">
                                <h4 class="header-title">Total Pasien : <b id="total_pasien">-</b></h4>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-1 control-label" style="text-align: left;">Jenis Laporan</label>
			                <div class="col-md-6">
			                    <div class="radio radio-purple radio-inline">
			                        <input type="radio" name="jenis_laporan" value="Pdf" id="pdf">
			                        <label for="pdf"> PDF </label>
			                    </div>
			                    <div class="radio radio-purple radio-inline">
			                        <input type="radio" name="jenis_laporan" value="Excel" id="excel">
			                        <label for="excel"> Excel </label>
			                    </div>
			                </div>
                        </div>
                        <hr>
                        <div class="form-group">
                        	<label class="col-md-1 control-label" style="text-align: left;">&nbsp;</label>
                        	<div class="col-md-10">
                        		<button class="btn btn-primary waves-effect w-md waves-light" type="submit"><i class="fa fa-print"></i> Cetak</button>
                        	</div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
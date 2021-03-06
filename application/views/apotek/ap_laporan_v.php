<script type="text/javascript" src="<?php echo base_url(); ?>js-devan/jquery-1.11.1.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){

	//LAPORAN PENJUALAN

	data_laporan();

	$("input[name='pilihan']").click(function(){
		var rd = $("input[name='pilihan']:checked").val();
		if(rd == 'bulanan'){
			$('#view_bulan').show();
		}else{
			$('#view_bulan').hide();
			data_laporan();
		}
	});

	$('#jumlah_tampil').change(function(){
		data_laporan();
	});

	//STOK OBAT

	$('#dt_stok_obat').click(function(){
		data_obat();
	});

	$('#dt_gudang_obat').click(function(){
		data_gudang_obat();
	});

	$("input[name='urutkan']").click(function(){
        var urut = $("input[name='urutkan']:checked").val();

        if(urut == 'Stok'){
            $('#view_stok').show();
        }else{
            $('#view_stok').hide();
            data_obat();
        }
    });

    $('#urutkan_stok').change(function(){
        data_obat();
    });

		$("input[name='urutkan_gudang']").click(function(){
	        var urut = $("input[name='urutkan_gudang']:checked").val();

	        if(urut == 'Stok'){
	            $('#view_stok_gudang').show();
	        }else{
	            $('#view_stok_gudang').hide();
	            data_gudang_obat();
	        }
	    });

	    $('#urutkan_stok_gudang').change(function(){
	        data_gudang_obat();
	    });

});

//LAPORAN PENJUALAN

function paging($selector){
    var jumlah_tampil = $('#jumlah_tampil').val();

    if(typeof $selector == 'undefined')
    {
        $selector = $("#tabel_laporan tbody tr");
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

function data_laporan(){
	$('#popup_load').show();
	var pilihan = $("input[name='pilihan']:checked").val();
	var bulan = $('#bulan').val();

	$.ajax({
		url : '<?php echo base_url(); ?>apotek/ap_laporan_c/data_laporan',
		data : {pilihan:pilihan,bulan:bulan},
		type : "POST",
		dataType : "json",
		success : function(result){
			$tr = "";

			if(result == "" || result == null){
				$tr = "<tr><td colspan='6' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
			}else{
				var no = 0;
				for(var i=0; i<result.length; i++){
					no++;

					var aksi =  '<a class="btn btn-success btn-sm m-b-5" href="<?php echo base_url(); ?>apotek/ap_laporan_c/cetak/'+result[i].ID+'" target="_blank">'+
                                    '<i class="fa fa-print"></i>'+
                                '</button>&nbsp;';

					$tr += "<tr>"+
								"<td style='text-align:center;'>"+no+"</td>"+
								"<td style='text-align:center;'>"+result[i].STATUS+"</td>"+
								"<td style='text-align:center;'>"+formatTanggal(result[i].TANGGAL)+"</td>"+
								"<td style='text-align:center;'>"+result[i].INVOICE+"</td>"+
								"<td style='text-align:right;'>Rp. "+NumberToMoney(result[i].TOTAL)+"</td>"+
								"<td align='center'>"+aksi+"</td>"+
							"</tr>";
				}
			}

			$('#tabel_laporan tbody').html($tr);
			paging();
			$('#popup_load').fadeOut();
		}
	});
}

//STOK OBAT

function pagingStokObat($selector){
	var jumlah_tampil = $('#jumlah_tampil_so').val();

    if(typeof $selector == 'undefined')
    {
        $selector = $("#tabel_obat tbody tr");
    }

    window.tp = new Pagination('#tablePagingStokObat', {
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

function data_obat(){
	$('#popup_load').show();

	var keyword = $('#cari_obat').val();
  var urutkan = $("input[name='urutkan']:checked").val();
  var urutkan_stok = $('#urutkan_stok').val();

	$.ajax({
		url : '<?php echo base_url(); ?>apotek/ap_laporan_c/get_data_obat',
        data : {
            keyword:keyword,
            urutkan:urutkan,
            urutkan_stok:urutkan_stok,
        },
        type : "POST",
        dataType : "json",
        success : function(result){
        	$tr = "";

        	if(result == "" || result == null){
        		$tr = "<tr><td colspan='11' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
        	}else{
        		var no = 0;

        		for(var i=0; i<result.length; i++){
        			no++;

                    // Here are the two dates to compare
					var date1 = "<?php echo date('Y-m-d'); ?>";
					var date2 = result[i].KADALUARSA_BALIK;

					// First we split the values to arrays date1[0] is the year, [1] the month and [2] the day
					date1 = date1.split('-');
					date2 = date2.split('-');

					// Now we convert the array to a Date object, which has several helpful methods
					date1 = new Date(date1[0], date1[1], date1[2]);
					date2 = new Date(date2[0], date2[1], date2[2]);

					// We use the getTime() method and get the unixtime (in milliseconds, but we want seconds, therefore we divide it through 1000)
					date1_unixtime = parseInt(date1.getTime() / 1000);
					date2_unixtime = parseInt(date2.getTime() / 1000);

					// This is the calculated difference in seconds
					var timeDifference = date2_unixtime - date1_unixtime;

					// in Hours
					var timeDifferenceInHours = timeDifference / 60 / 60;

					// and finaly, in days :)
					var timeDifferenceInDays = timeDifferenceInHours  / 24;

					// console.log(timeDifferenceInDays);

					var warna = "";
					var keterangan = "";
					var aksi = "";

					if(timeDifferenceInDays <= 120 && timeDifferenceInDays > 0){
						warna = "class='warning'";
						keterangan = "Masa kadaluarsa tinggal <b>"+timeDifferenceInDays+" hari</b>";
					}else if(timeDifferenceInDays == 0){
						warna = "class='danger'";
						keterangan = "Masa kadaluarsa telah habis";
					}else if(timeDifferenceInDays < 0){
						warna = "class='danger'";
						keterangan = "Masa kadaluarsa telah habis";
					}else{
						warna = "";
						keterangan = "Masa kadaluarsa tersisa <b>"+timeDifferenceInDays+"</b> hari";
					}

        			$tr += "<tr "+warna+" >"+
        						"<td style='text-align:center;'>"+no+"</td>"+
        						"<td>"+
        							"<b>"+result[i].NAMA_OBAT+"</b><br/>"+
                                    "<small>"+result[i].KODE_OBAT+"</small>"+
        						"</td>"+
                                "<td style='text-align:center;'>"+result[i].ID_JENIS_OBAT+"</td>"+
        						"<td><center>"+NumberToMoney(result[i].TOTAL)+"</center></td>"+
        						"<td style='text-align:center;'>"+formatTanggal(result[i].EXPIRED)+"</td>"+
        						"<td style='text-align:center;'>"+keterangan+"</td>"+
        					"</tr>";
        		}
        	}

        	$('#tabel_obat tbody').html($tr);
        	pagingStokObat();
        	$('#popup_load').fadeOut();
        }
	});

    $('#tombol_cari').click(function(){
        data_obat();
        $('#tombol_reset').show();
        $('#tombol_cari').hide();
    });

    $('#tombol_reset').click(function(){
        $('#cari_obat').val("");
        data_obat();
        $('#tombol_reset').hide();
        $('#tombol_cari').show();
    });
}

function data_gudang_obat(){
	$('#popup_load').show();

	var keyword = $('#cari_obat_gudang').val();
  var urutkan = $("input[name='urutkan_gudang']:checked").val();
  var urutkan_stok = $('#urutkan_stok_gudang').val();

	$.ajax({
		url : '<?php echo base_url(); ?>apotek/ap_laporan_c/get_data_gudang_obat',
        data : {
            keyword:keyword,
            urutkan:urutkan,
            urutkan_stok:urutkan_stok,
        },
        type : "POST",
        dataType : "json",
        success : function(result){
        	$tr = "";
        	if(result == "" || result == null){
        		$tr = "<tr><td colspan='11' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
        	}else{
						var no = 0;

						for(var i=0; i<result.length; i++){
							no++;

        			$tr += "<tr>"+
        							"<td style='text-align:center;'>"+no+"</td>"+
        							"<td>"+
        								"<b>"+result[i].NAMA_OBAT+"</b><br/>"+
                        "<small>"+result[i].KODE_OBAT+"</small>"+
        							"</td>"+
                      "<td style='text-align:center;'>"+result[i].NAMA_JENIS+"</td>"+
											"<td style='text-align:right;'>Rp. "+NumberToMoney(result[i].HARGA_BELI)+"</td>"+
											"<td style='text-align:right;'>Rp. "+NumberToMoney(result[i].TOTAL_HARGA)+"</td>"+
        						  "<td style='text-align:center;'>"+NumberToMoney(result[i].TOTAL)+"</td>"+
        						  "<td style='text-align:center;'>"+formatTanggal(result[i].KADALUARSA)+"</td>"+
        					  "</tr>";
        		}
        	}

        	$('#tabel_gudang tbody').html($tr);
        	pagingGudangObat();
        	$('#popup_load').fadeOut();
        }
	});

    $('#tombol_cari').click(function(){
        data_obat();
        $('#tombol_reset').show();
        $('#tombol_cari').hide();
    });

    $('#tombol_reset').click(function(){
        $('#cari_obat_gudang').val("");
        data_gudang_obat();
        $('#tombol_reset').hide();
        $('#tombol_cari').show();
    });
}

function pagingGudangObat($selector){
	var jumlah_tampil = $('#jumlah_tampil_gb').val();
    if(typeof $selector == 'undefined')
    {
        $selector = $("#tabel_gudang tbody tr");
    }
    window.tp = new Pagination('#tablePagingGudangObat', {
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

function onEnterText(e){
    if (e.keyCode == 13) {
        data_obat();
        $('#tombol_reset').show();
		$('#tombol_cari').hide();
        return false;
    }
}
</script>

<style type="text/css">
#view_ubah{
	display: none;
}

#view_bulan{
	display: none;
}

#view_stok, #view_status_obat{
	display: none;
}

#view_stok_gudang{
	display: none;
}

#tombol_reset{
	display: none;
}
</style>

<div id="popup_load">
    <div class="window_load">
        <img src="<?=base_url()?>picture/progress.gif" height="100" width="125">
    </div>
</div>

<div class="col-lg-12" id="view_data">
	<div class="card-box">
		<div class="row">
			<ul class="nav nav-tabs">
                <li role="presentation" id="dt_penjualan_obat" class="active">
                    <a href="#home1" role="tab" data-toggle="tab"><i class="fa fa-file-text-o"></i> Data Laporan Penjualan</a>
                </li>
                <li role="presentation" id="dt_stok_obat">
                    <a href="#stok_obat1" role="tab" data-toggle="tab"><i class="fa fa-file-text-o"></i> Data Stok Obat</a>
                </li>
								<li role="presentation" id="dt_gudang_obat">
                    <a href="#gudang_obat1" role="tab" data-toggle="tab"><i class="fa fa-file-text-o"></i> Laporan Gudang Obat</a>
                </li>
            </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade in active" id="home1">
                	<form class="form-horizontal" role="form">
	                	<div class="form-group">
	                		<label class="control-label col-md-1" style="text-align:left;">Filter</label>
			                <div class="col-md-8">
			                    <div class="radio radio-danger radio-inline">
	                                <input type="radio" id="inlineRadio1" value="default" name="pilihan" checked>
	                                <label for="inlineRadio1"> Default </label>
	                            </div>
	                            <div class="radio radio-danger radio-inline">
	                                <input type="radio" id="inlineRadio2" value="tanggal" name="pilihan">
	                                <label for="inlineRadio2"> Tanggal </label>
	                            </div>
			                </div>
			            </div>
			            <div class="form-group" id="view_tanggal">
			            	<label class="control-label col-md-1" style="text-align:left;">&nbsp;</label>
			            	<div class="col-md-4">
												<div class="input-daterange input-group">
				            			<input type="text" name="tanggal_dari" value="" class="form-control" data-mask="99-99-9999">
													<span class="input-group-addon bg-primary b-0 text-white">Sampai</span>
													<input type="text" name="tanggal_sampai" value="" class="form-control" data-mask="99-99-9999">
				            		</div>
			            	</div>
										<div class="col-md-3">
												<button type="button" class="btn btn-primary" name="button">Cari</button>\
			            	</div>
			            </div>
                	</form>
                    <div class="table-responsive">
			            <table id="tabel_laporan" class="table table-bordered">
			                <thead>
			                    <tr class="biru">
			                        <th style="color:#fff; text-align:center;">No</th>
			                        <th style="color:#fff; text-align:center;">Status</th>
			                        <th style="color:#fff; text-align:center;">Tgl Transaksi</th>
			                        <th style="color:#fff; text-align:center;">Waktu</th>
			                        <th style="color:#fff; text-align:center;">Total</th>
			                        <th style="color:#fff; text-align:center;">Aksi</th>
			                    </tr>
			                </thead>

			                <tbody>

			                </tbody>
			            </table>
			        </div>
			        <form class="form-horizontal" role="form">
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
			        </form>
                </div>

                <div role="tabpanel" class="tab-pane fade" id="stok_obat1">
                	<form class="form-horizontal" role="form" action="<?php echo $url_cetak_so; ?>" method="post" target="blank">
			            <div class="form-group">
			                <label class="col-md-1 control-label" style="text-align:left;">Urutkan</label>
			                <div class="col-md-6">
			                    <div class="radio radio-purple radio-inline">
			                        <input type="radio" name="urutkan" value="Default" id="default" checked="checked">
			                        <label for="default"> Default </label>
			                    </div>
			                    <div class="radio radio-purple radio-inline">
			                        <input type="radio" name="urutkan" value="Nama Obat" id="urut_nama_obat">
			                        <label for="nama_poli"> Nama Obat </label>
			                    </div>
			                    <div class="radio radio-purple radio-inline">
			                        <input type="radio" name="urutkan" value="Stok" id="urut_stok">
			                        <label for="jenis"> Stok </label>
			                    </div>
			                    <div class="radio radio-purple radio-inline">
			                        <input type="radio" name="urutkan" value="Expired" id="urut_expired">
			                        <label for="jenis"> Expired </label>
			                    </div>
			                </div>
			                <div class="col-md-4 pull-right">
				                <div class="input-group">
				                    <input type="text" class="form-control" name="cari_obat" id="cari_obat" placeholder="Cari..." value="" onkeypress="return onEnterText(event);">
				                    <span class="input-group-btn">
				                    	<button type="button" class="btn waves-effect waves-light btn-warning" id="tombol_cari">
				                    		<i class="fa fa-search"></i>
				                    	</button>
				                    	<button type="button" class="btn waves-effect waves-light btn-warning" id="tombol_reset">
				                    		<i class="fa fa-refresh"></i>
				                    	</button>
				                    </span>
				                </div>
			                </div>
			            </div>
			            <div class="form-group" id="view_stok">
			                <label class="col-md-1 control-label">&nbsp;</label>
			                <div class="col-md-2">
			                    <select name="urutkan_stok" id="urutkan_stok" class="form-control">
			                        <option value="Rendah">Terendah</option>
			                        <option value="Tinggi">Tertinggi</option>
			                    </select>
			                </div>
			            </div>
			            <div class="form-group">
			            	<div class="table-responsive">
					            <table id="tabel_obat" class="table table-bordered">
					                <thead>
					                    <tr class="biru">
					                        <th style="color:#fff; text-align:center;" width="50">No</th>
					                        <th style="color:#fff; text-align:center;">Nama Obat</th>
					                        <th style="color:#fff; text-align:center;">Jenis Obat</th>
					                        <th style="color:#fff; text-align:center;">Sisa Stok</th>
					                        <th style="color:#fff; text-align:center;">Expired</th>
					                        <th style="color:#fff; text-align:center;">Keterangan</th>
					                    </tr>
					                </thead>

					                <tbody>

					                </tbody>
					            </table>
					        </div>
			            </div>
			            <div class="form-group">
			        		<div class="col-md-10">
			        			<div id="tablePagingStokObat"> </div>
			        		</div>
			            </div>
			            <div class="form-group">
			        		<div class="col-md-9">
			        			<button type="submit" class="btn waves-effect waves-light btn-danger"><i class="fa fa-print"></i> Cetak</button>
			        		</div>
			                <label class="col-md-2 control-label">Jumlah Tampil</label>
			                <div class="col-md-1 pull-right">
				                <select class="form-control" id="jumlah_tampil_so">
			                        <option value="10">10</option>
			                        <option value="20">20</option>
			                        <option value="50">50</option>
			                        <option value="100">100</option>
			                    </select>
			                </div>
			            </div>
			        </form>
            </div>

						<div role="tabpanel" class="tab-pane fade" id="gudang_obat1">
							<form class="form-horizontal" role="form" action="<?php echo base_url(); ?>apotek/ap_laporan_c/cetak_gudang_obat" method="post" target="blank">
							<div class="form-group">
									<label class="col-md-1 control-label" style="text-align:left;">Urutkan</label>
									<div class="col-md-6">
											<div class="radio radio-purple radio-inline">
													<input type="radio" name="urutkan_gudang" value="Default" id="default_gudang" checked="checked">
													<label for="default"> Default </label>
											</div>
											<div class="radio radio-purple radio-inline">
													<input type="radio" name="urutkan_gudang" value="Nama Obat" id="urut_nama_obat_gudang">
													<label for="nama_poli"> Nama Obat </label>
											</div>
											<div class="radio radio-purple radio-inline">
													<input type="radio" name="urutkan_gudang" value="Stok" id="urut_stok_gudang">
													<label for="jenis"> Stok </label>
											</div>
											<div class="radio radio-purple radio-inline">
													<input type="radio" name="urutkan_gudang" value="Expired" id="urut_expired_gudang">
													<label for="jenis"> Expired </label>
											</div>
									</div>
									<div class="col-md-4 pull-right">
										<div class="input-group">
												<input type="text" class="form-control" name="cari_obat" id="cari_obat_gudang" placeholder="Cari..." value="" onkeypress="return onEnterText(event);">
												<span class="input-group-btn">
													<button type="button" class="btn waves-effect waves-light btn-warning" id="tombol_cari">
														<i class="fa fa-search"></i>
													</button>
													<button type="button" class="btn waves-effect waves-light btn-warning" id="tombol_reset">
														<i class="fa fa-refresh"></i>
													</button>
												</span>
										</div>
									</div>
							</div>
							<div class="form-group" id="view_stok_gudang">
									<label class="col-md-1 control-label">&nbsp;</label>
									<div class="col-md-2">
											<select name="urutkan_stok_gudang" id="urutkan_stok_gudang" class="form-control">
													<option value="Rendah">Terendah</option>
													<option value="Tinggi">Tertinggi</option>
											</select>
									</div>
							</div>
							<div class="form-group">
								<div class="table-responsive">
									<table id="tabel_gudang" class="table table-bordered">
											<thead>
													<tr class="biru">
															<th style="color:#fff; text-align:center;" width="50">No</th>
															<th style="color:#fff; text-align:center;">Nama Obat</th>
															<th style="color:#fff; text-align:center;">Jenis Obat</th>
															<th style="color:#fff; text-align:center;">Harga Beli</th>
															<th style="color:#fff; text-align:center;">Harga Jual</th>
															<th style="color:#fff; text-align:center;">Stok</th>
															<th style="color:#fff; text-align:center;">Tanggal Expired</th>
													</tr>
											</thead>
											<tbody>
											</tbody>
									</table>
							</div>
							</div>
							<div class="form-group">
								<div class="col-md-10">
									<div id="tablePagingGudangObat"></div>
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
							<div class="col-md-9">
								<button type="submit" class="btn waves-effect waves-light btn-danger"><i class="fa fa-print"></i> Cetak</button>
							</div>
									<label class="col-md-2 control-label">Jumlah Tampil</label>
									<div class="col-md-1 pull-right">
										<select class="form-control" id="jumlah_tampil_gb">
													<option value="10">10</option>
													<option value="20">20</option>
													<option value="50">50</option>
													<option value="100">100</option>
											</select>
									</div>
							</div>
					</form>
					</div>

        </div>
			</div>
		</div>
	</div>

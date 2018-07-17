<script type="text/javascript" src="<?php echo base_url(); ?>js-devan/jquery-1.11.1.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
	//RAWAT JALAN

	data_rawat_jalan();

	$('#jumlah_tampil').change(function(){
		data_rawat_jalan();
	});

	//RAWAT INAP

	$('#dt_ri').click(function(){
		data_rawat_inap();
	});

	$('#jumlah_tampilRI').change(function(){
		data_rawat_inap();
	});

	//IGD

	$('#dt_igd').click(function(){
		data_igd();
	});

	$('#jumlah_tampilIGD').change(function(){
		data_igd();
	});
});

//RAWAT JALAN

function paging($selector){
	var jumlah_tampil = $('#jumlah_tampil').val();

    if(typeof $selector == 'undefined')
    {
        $selector = $("#tabel_rj tbody tr");
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

function data_rawat_jalan(){
	$('#popup_load').show();
	var tanggal = "<?php echo date('Y-m-d'); ?>";

	$.ajax({
		url : '<?php echo base_url(); ?>rekam_medik/rk_indeks_catatan_medis/data_rawat_jalan',
		data : {tanggal:tanggal},
		type : "POST",
		dataType : "json",
		success : function(result){
			$tr = "";

			if(result == "" || result == null){
				$tr = "<tr><td colspan='9' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
			}else{
				var no = 0;

				for(var i=0; i<result.length; i++){
					no++;

					var aksi =  '<a href="<?php echo base_url(); ?>rekam_medik/rk_indeks_catatan_medis/icd_rawat_jalan/'+result[i].ID_RJ+'" class="on-default edit-row"><i class="fa fa-list"></i>&nbsp;ICD</a>';

					var stt = "";
					if(result[i].STATUS_SUDAH == 0){
						stt = '<span class="label label-warning">Belum</span>';
					}else{
						stt = '<span class="label label-success">Sudah</span>';
					}
					
					$tr += "<tr>"+
								"<td style='text-align:center;'>"+no+"</td>"+
								"<td>"+result[i].KODE_PASIEN+"</td>"+
								"<td>"+result[i].NAMA_PASIEN+"</td>"+
								"<td>"+result[i].ASAL_RUJUKAN+"</td>"+
								"<td>"+result[i].NAMA_POLI+"</td>"+
								"<td style='text-align:center;'>"+result[i].SISTEM_BAYAR+"</td>"+
								"<td style='text-align:center;'>"+result[i].TANGGAL+"</td>"+
								"<td style='text-align:center;'>"+stt+"</td>"+
								"<td align='center'>"+aksi+"</td>"+
							"</tr>";
				}
			}

			$('#tabel_rj tbody').html($tr);
			paging();
			$('#popup_load').fadeOut();
		}
	});
}

//RAWAT INAP

function pagingRI($selector){
	var jumlah_tampil = $('#jumlah_tampil').val();

    if(typeof $selector == 'undefined')
    {
        $selector = $("#tabel_ri tbody tr");
    }

    window.tp = new Pagination('#tablePagingRI', {
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

function data_rawat_inap(){
	$('#popup_load').show();
	var tanggal = "<?php echo date('Y-m-d'); ?>";

	$.ajax({
		url : '<?php echo base_url(); ?>rekam_medik/rk_indeks_catatan_medis/data_rawat_inap',
		data : {tanggal:tanggal},
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

					var aksi =  '<a href="<?php echo base_url(); ?>rekam_medik/rk_pelayanan_ri_c/tindakan_ri/'+result[i].ID+'" class="on-default edit-row"><i class="fa fa-user-md"></i>&nbsp;Tindakan</a>';

					$tr += "<tr>"+
								"<td style='text-align:center;'>"+no+"</td>"+
								"<td>"+result[i].KODE_PASIEN+"</td>"+
								"<td>"+result[i].NAMA_PASIEN+"</td>"+
								"<td>"+result[i].ASAL_RUJUKAN+"</td>"+
								"<td style='text-align:center;'>"+result[i].SISTEM_BAYAR+"</td>"+
								"<td style='text-align:center;'>"+result[i].TANGGAL_MASUK+"</td>"+
							"</tr>";
				}
			}

			$('#tabel_ri tbody').html($tr);
			pagingRI();
			$('#popup_load').fadeOut();
		}
	});
}

//IGD

function pagingIGD($selector){
	var jumlah_tampil = $('#jumlah_tampilIGD').val();

    if(typeof $selector == 'undefined')
    {
        $selector = $("#tabel_igd tbody tr");
    }

    window.tp = new Pagination('#tablePagingIGD', {
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

function data_igd(){
	$('#popup_load').show();
	var tanggal = "<?php echo date('Y-m-d'); ?>";

	$.ajax({
		url : '<?php echo base_url(); ?>rekam_medik/rk_indeks_catatan_medis/data_igd',
		data : {tanggal:tanggal},
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

					var aksi =  '<a href="<?php echo base_url(); ?>rekam_medik/rk_pelayanan_igd_c/tindakan_igd/'+result[i].ID+'" class="on-default edit-row"><i class="fa fa-user-md"></i>&nbsp;Tindakan</a>';

					$tr += "<tr>"+
								"<td style='text-align:center;'>"+no+"</td>"+
								"<td>"+result[i].KODE_PASIEN+"</td>"+
								"<td>"+result[i].NAMA_PASIEN+"</td>"+
								"<td>"+result[i].ASAL_RUJUKAN+"</td>"+
								"<td style='text-align:center;'>"+result[i].SISTEM_BAYAR+"</td>"+
								"<td style='text-align:center;'>"+result[i].TANGGAL+"</td>"+
							"</tr>";
				}
			}

			$('#tabel_igd tbody').html($tr);
			pagingIGD();
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
		<div class="row">
			<ul class="nav nav-tabs">
                <li role="presentation" class="active">
                    <a href="#rj1" role="tab" data-toggle="tab"><i class="fa fa-stethoscope"></i> Indeks Rawat Jalan</a>
                </li>
                <li role="presentation" id="dt_ri">
                    <a href="#ri1" role="tab" data-toggle="tab"><i class="fa fa-bed"></i> Indeks Rawat Inap</a>
                </li>
                <li role="presentation" id="dt_igd">
                    <a href="#igd1" role="tab" data-toggle="tab"><i class="fa fa-plus-square"></i> Indeks IGD</a>
                </li>
            </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade in active" id="rj1">
                	<div class="table-responsive">
			            <table id="tabel_rj" class="table table-bordered">
			                <thead>
			                    <tr class="hijau">
			                        <th style="color:#fff; text-align:center;">No</th>
			                        <th style="color:#fff; text-align:center;">No. RM</th>
			                        <th style="color:#fff; text-align:center;">Nama</th>
			                        <th style="color:#fff; text-align:center;">Asal Rujukan</th>
			                        <th style="color:#fff; text-align:center;">Nama Poli</th>
			                        <th style="color:#fff; text-align:center;">Sistem Bayar</th>
			                        <th style="color:#fff; text-align:center;">Tanggal Pelayanan</th>
			                        <th style="color:#fff; text-align:center;">Status ICD</th>
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

                <div role="tabpanel" class="tab-pane fade" id="ri1">
                	<div class="table-responsive">
			            <table id="tabel_ri" class="table table-bordered">
			                <thead>
			                    <tr class="biru">
			                        <th style="color:#fff; text-align:center;">No</th>
			                        <th style="color:#fff; text-align:center;">No. RM</th>
			                        <th style="color:#fff; text-align:center;">Nama</th>
			                        <th style="color:#fff; text-align:center;">Asal Rujukan</th>
			                        <th style="color:#fff; text-align:center;">Sistem Bayar</th>
			                        <th style="color:#fff; text-align:center;">Tanggal Pelayanan</th>
			                    </tr>
			                </thead>

			                <tbody>
			                    
			                </tbody>
			            </table>
			        </div>
			        <form class="form-horizontal" role="form">
			        	<div class="form-group">
			        		<div class="col-md-10">
			        			<div id="tablePagingRI"> </div>
			        		</div>
	                    </div>
	                    <div class="form-group">
			        		<div class="col-md-9">
			        			&nbsp;
			        		</div>
	                        <label class="col-md-2 control-label">Jumlah Tampil</label>
	                        <div class="col-md-1 pull-right">
				                <select class="form-control" id="jumlah_tampilRI">
	                                <option value="10">10</option>
	                                <option value="20">20</option>
	                                <option value="50">50</option>
	                                <option value="100">100</option>
	                            </select>
			                </div>
	                    </div>
			        </form>
                </div>

                <div role="tabpanel" class="tab-pane fade" id="igd1">
                	<div class="table-responsive">
			            <table id="tabel_igd" class="table table-bordered">
			                <thead>
			                    <tr class="merah">
			                        <th style="color:#fff; text-align:center;">No</th>
			                        <th style="color:#fff; text-align:center;">No. RM</th>
			                        <th style="color:#fff; text-align:center;">Nama</th>
			                        <th style="color:#fff; text-align:center;">Asal Rujukan</th>
			                        <th style="color:#fff; text-align:center;">Sistem Bayar</th>
			                        <th style="color:#fff; text-align:center;">Tanggal Pelayanan</th>
			                    </tr>
			                </thead>

			                <tbody>
			                    
			                </tbody>
			            </table>
			        </div>
			        <form class="form-horizontal" role="form">
			        	<div class="form-group">
			        		<div class="col-md-10">
			        			<div id="tablePagingIGD"> </div>
			        		</div>
	                    </div>
	                    <div class="form-group">
			        		<div class="col-md-9">
			        			&nbsp;
			        		</div>
	                        <label class="col-md-2 control-label">Jumlah Tampil</label>
	                        <div class="col-md-1 pull-right">
				                <select class="form-control" id="jumlah_tampilIGD">
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
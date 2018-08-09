<script type="text/javascript" src="<?php echo base_url(); ?>js-devan/jquery-1.11.1.min.js"></script>

<style type="text/css">
#tombol_reset{
	display: none;
}

#tombol_reset2{
	display: none;
}
</style>

<script type="text/javascript">
$(document).ready(function(){
	data_pasien_belum();

	$('#dt_pasien_sudah').click(function(){
		data_pasien_sudah();
	});

	$('#jumlah_tampil').change(function(){
		data_pasien_belum();
	});

	$('#jumlah_tampil2').change(function(){
		data_pasien_sudah();
	});

});

// PASIEN BELUM

function paging($selector){
	var jumlah_tampil = $('#jumlah_tampil').val();

    if(typeof $selector == 'undefined')
    {
        $selector = $("#tabel_pasien_belum tbody tr");
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

function data_pasien_belum(){
	$('#popup_load').show();
	var keyword = $('#cari_pasien_belum').val();

	$.ajax({
		url : '<?php echo base_url(); ?>rekam_medik/rk_pelayanan_ri_c/data_rawat_inap',
		data : {keyword:keyword},
		type : "POST",
		dataType : "json",
		success : function(result){
			$tr = "";

			if(result == "" || result == null){
				$tr = "<tr><td colspan='8' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
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
								"<td>"+result[i].NAMA_KAMAR+"</td>"+
								"<td style='text-align:center;'>"+result[i].SISTEM_BAYAR+"</td>"+
								"<td style='text-align:center;'>"+result[i].TANGGAL_MASUK+"</td>"+
								"<td align='center'>"+aksi+"</td>"+
							"</tr>";
				}
			}

			$('#tabel_pasien_belum tbody').html($tr);
			paging();
			$('#popup_load').fadeOut(); 
		}
	});

	$('#tombol_cari').click(function(){
        data_pasien_belum();
        $('#tombol_reset').show();
        $('#tombol_cari').hide();
    });

    $('#tombol_reset').click(function(){
        $('#cari_pasien_belum').val("");
        data_pasien_belum();
        $('#tombol_reset').hide();
        $('#tombol_cari').show();
    });
}

function onEnterText(e){
    if (e.keyCode == 13) {
        data_pasien_belum();
        $('#tombol_reset').show();
        $('#tombol_cari').hide();
        return false;
    }
}

// PASIEN SUDAH

function paging2($selector){
	var jumlah_tampil = $('#jumlah_tampil2').val();

    if(typeof $selector == 'undefined')
    {
        $selector = $("#tabel_pasien_sudah tbody tr");
    }

    window.tp = new Pagination('#tablePaging2', {
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

function data_pasien_sudah(){
	$('#popup_load').show();
	var keyword = $('#cari_pasien_sudah').val();

	$.ajax({
		url : '<?php echo base_url(); ?>rekam_medik/rk_pelayanan_ri_c/data_pasien_sudah',
		data : {keyword:keyword},
		type : "POST",
		dataType : "json",
		success : function(result){
			$tr = "";

			if(result == "" || result == null){
				$tr = "<tr><td colspan='8' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
			}else{
				var no = 0;

				for(var i=0; i<result.length; i++){
					no++;

					var stt = "";
					var warna = "";

					if(result[i].KONDISI_AKHIR == 'Dirawat'){
						stt = '<span class="label label-primary">'+result[i].KONDISI_AKHIR+'</span>';
						warna = 'class="biru_stt_pindah"';
					}else if(result[i].KONDISI_AKHIR == 'Pindah Poli'){
						stt = '<span class="label label-warning">'+result[i].KONDISI_AKHIR+'</span>';
						warna = 'class="warning"';
					}else if(result[i].KONDISI_AKHIR == 'Operasi'){
						stt = '<span class="label label-danger">'+result[i].KONDISI_AKHIR+'</span>';
						warna = 'class="danger"';
					}else if(result[i].KONDISI_AKHIR == 'Pulang'){
						stt = '<span class="label label-success">'+result[i].KONDISI_AKHIR+'</span>';
						warna = 'class="hijau_stt_pindah"';
					}else if(result[i].KONDISI_AKHIR == null){
						stt = '-';
						warna = '';
					}else{
						stt = result[i].KONDISI_AKHIR;
						warna = "-";
					}

					var date = new Date(result[i].TANGGAL_MASUK_BALIK);
					date.setDate(date.getDate() + parseInt(result[i].DIRAWAT_SELAMA));

					var dateMsg = date.getDate()+'-'+ (date.getMonth()+1) +'-'+date.getFullYear();

					$tr += "<tr "+warna+" >"+
								"<td style='text-align:center;'>"+no+"</td>"+
								"<td>"+result[i].KODE_PASIEN+"</td>"+
								"<td>"+result[i].NAMA_PASIEN+"</td>"+
								"<td>"+result[i].ASAL_RUJUKAN+"</td>"+
								"<td>"+result[i].NAMA_KAMAR+"</td>"+
								"<td style='text-align:center;'>"+result[i].SISTEM_BAYAR+"</td>"+
								"<td style='text-align:center;'>"+result[i].TANGGAL_MASUK+"</td>"+
								"<td style='text-align:center;'>"+result[i].DIRAWAT_SELAMA+" Hari</td>"+
								"<td style='text-align:center;'>"+dateMsg+"</td>"+
								"<td style='text-align:center;'>"+stt+"</td>"+
							"</tr>";
				}
			}

			$('#tabel_pasien_sudah tbody').html($tr);
			paging2();
			$('#popup_load').fadeOut();
		}
	});

	$('#tombol_cari2').click(function(){
        data_pasien_sudah();
        $('#tombol_reset2').show();
        $('#tombol_cari2').hide();
    });

    $('#tombol_reset2').click(function(){
        $('#cari_pasien_sudah').val("");
        data_pasien_sudah();
        $('#tombol_reset2').hide();
        $('#tombol_cari2').show();
    });
}

function onEnterText2(e){
    if (e.keyCode == 13) {
        data_pasien_sudah();
        $('#tombol_reset2').show();
        $('#tombol_cari2').hide();
        return false;
    }
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
                    <a href="#pasien_belum" role="tab" data-toggle="tab"><i class="fa fa-download"></i> Pasien Belum</a>
                </li>
                <li role="presentation" id="dt_pasien_sudah">
                    <a href="#pasien_sudah" role="tab" data-toggle="tab"><i class="fa fa-upload"></i> Pasien Sudah</a>
                </li>
            </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade in active" id="pasien_belum">
                	<form class="form-horizontal" role="form">
                		<div class="form-group">
                			<div class="col-md-5 pull-right">
				                <div class="input-group">
				                    <input type="text" class="form-control" id="cari_pasien_belum" placeholder="Cari..." value="" onkeypress="return onEnterText(event);">
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
                	</form>
                	<div class="table-responsive">
			            <table id="tabel_pasien_belum" class="table table-bordered">
			                <thead>
			                    <tr class="merah">
			                        <th style="color:#fff; text-align:center;">No</th>
			                        <th style="color:#fff; text-align:center;">No. RM</th>
			                        <th style="color:#fff; text-align:center;">Nama</th>
			                        <th style="color:#fff; text-align:center;">Asal Rujukan</th>
			                        <th style="color:#fff; text-align:center;">Nama Ruang</th>
			                        <th style="color:#fff; text-align:center;">Sistem Bayar</th>
			                        <th style="color:#fff; text-align:center;">Tanggal Pelayanan</th>
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

                <div role="tabpanel" class="tab-pane fade" id="pasien_sudah">
                	<form class="form-horizontal" role="form">
                		<div class="form-group">
                			<div class="col-md-5 pull-right">
				                <div class="input-group">
				                    <input type="text" class="form-control" id="cari_pasien_sudah" placeholder="Cari..." value="" onkeypress="return onEnterText2(event);">
				                    <span class="input-group-btn">
				                    	<button type="button" class="btn waves-effect waves-light btn-warning" id="tombol_cari2">
				                    		<i class="fa fa-search"></i>
				                    	</button>
				                    	<button type="button" class="btn waves-effect waves-light btn-warning" id="tombol_reset2">
				                    		<i class="fa fa-refresh"></i>
				                    	</button>
				                    </span>
				                </div>
			                </div>
                		</div>
                	</form>
                	<div class="table-responsive">
			            <table id="tabel_pasien_sudah" class="table table-bordered">
			                <thead>
			                    <tr class="biru">
			                        <th style="color:#fff; text-align:center;">No</th>
			                        <th style="color:#fff; text-align:center;">No. RM</th>
			                        <th style="color:#fff; text-align:center;">Nama</th>
			                        <th style="color:#fff; text-align:center;">Asal Rujukan</th>
			                        <th style="color:#fff; text-align:center;">Nama Ruang</th>
			                        <th style="color:#fff; text-align:center;">Sistem Bayar</th>
			                        <th style="color:#fff; text-align:center;">Tgl Pelayanan</th>
			                        <th style="color:#fff; text-align:center;">Dirawat Selama</th>
			                        <th style="color:#fff; text-align:center;">Tgl Pulang</th>
			                        <th style="color:#fff; text-align:center;">Kondisi Akhir</th>
			                    </tr>
			                </thead>

			                <tbody>
			                    
			                </tbody>
			            </table>
			        </div>
			        <form class="form-horizontal" role="form">
			        	<div class="form-group">
			        		<div class="col-md-10">
			        			<div id="tablePaging2"> </div>
			        		</div>
	                    </div>
	                    <div class="form-group">
			        		<div class="col-md-9">
			        			&nbsp;
			        		</div>
	                        <label class="col-md-2 control-label">Jumlah Tampil</label>
	                        <div class="col-md-1 pull-right">
				                <select class="form-control" id="jumlah_tampil2">
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
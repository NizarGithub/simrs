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
	data_rawat_jalan();

	$('#pasien_sudah').click(function(){
		data_pasien_sudah();
	});
});

//PASIEN BELUM 

function paging($selector){
	var jumlah_tampil = $('#jumlah_tampil').val();

    if(typeof $selector == 'undefined')
    {
        $selector = $("#tabel_pelayanan tbody tr");
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
	var keyword = $('#cari_pelayanan').val();

	$.ajax({
		url : '<?php echo base_url(); ?>rekam_medik/rk_pelayanan_rj_c/data_rawat_jalan',
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

					var aksi =  '<a href="<?php echo base_url(); ?>rekam_medik/rk_pelayanan_rj_c/tindakan_rj/'+result[i].ID_RJ+'" class="on-default edit-row"><i class="fa fa-user-md"></i>&nbsp;Tindakan</a>';

					$tr += "<tr>"+
								"<td style='text-align:center;'>"+no+"</td>"+
								"<td>"+result[i].KODE_PASIEN+"</td>"+
								"<td>"+result[i].NAMA_PASIEN+"</td>"+
								"<td>"+result[i].ASAL_RUJUKAN+"</td>"+
								"<td>"+result[i].NAMA_POLI+"</td>"+
								"<td style='text-align:center;'>"+result[i].SISTEM_BAYAR+"</td>"+
								"<td style='text-align:center;'>"+result[i].TANGGAL+"</td>"+
								"<td align='center'>"+aksi+"</td>"+
							"</tr>";
				}
			}

			$('#tabel_pelayanan tbody').html($tr);
			paging();
			$('#popup_load').fadeOut();
		}
	});

	$('#cari_pelayanan').off('keyup').keyup(function(){
		data_rawat_jalan();
	});
}

//PASIEN SUDAH 

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
		url : '<?php echo base_url(); ?>rekam_medik/rk_pelayanan_rj_c/data_pasien_sudah',
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

					var aksi =  '<a href="<?php echo base_url(); ?>rekam_medik/rk_pelayanan_rj_c/tindakan_rj/'+result[i].ID_RJ+'" class="on-default edit-row"><i class="fa fa-user-md"></i>&nbsp;Ubah Tindakan</a>';

					var stt = "";
					var warna = "";

					if(result[i].KONDISI_AKHIR == 'Rawat Inap'){
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

					$tr += "<tr "+warna+" >"+
								"<td style='text-align:center;'>"+no+"</td>"+
								"<td>"+result[i].KODE_PASIEN+"</td>"+
								"<td>"+result[i].NAMA_PASIEN+"</td>"+
								"<td>"+result[i].ASAL_RUJUKAN+"</td>"+
								"<td>"+result[i].NAMA_POLI+"</td>"+
								"<td style='text-align:center;'>"+result[i].SISTEM_BAYAR+"</td>"+
								"<td style='text-align:center;'>"+result[i].TANGGAL+"</td>"+
								"<td style='text-align:center;'>"+stt+"</td>"+
							"</tr>";
				}
			}

			$('#tabel_pasien_sudah tbody').html($tr);
			paging2();
			$('#popup_load').fadeOut();
		}
	});

	$('#cari_pasien_sudah').off('keyup').keyup(function(){
		data_pasien_sudah();
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
                    <a href="#home1" role="tab" data-toggle="tab"><i class="fa fa-download"></i> Pasien Belum</a>
                </li>
                <li role="presentation" id="pasien_sudah">
                    <a href="#profile1" role="tab" data-toggle="tab"><i class="fa fa-upload"></i> Pasien Sudah</a>
                </li>
            </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade in active" id="home1">
                	<form class="form-horizontal" role="form">
                		<div class="form-group">
                			<div class="col-md-5 pull-right">
				                <div class="input-group">
				                    <input type="text" class="form-control" id="cari_pelayanan" placeholder="Cari..." value="">
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
			            <table id="tabel_pelayanan" class="table table-bordered">
			                <thead>
			                    <tr class="merah">
			                        <th style="color:#fff; text-align:center;">No</th>
			                        <th style="color:#fff; text-align:center;">No. RM</th>
			                        <th style="color:#fff; text-align:center;">Nama</th>
			                        <th style="color:#fff; text-align:center;">Asal Rujukan</th>
			                        <th style="color:#fff; text-align:center;">Nama Poli</th>
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

                <div role="tabpanel" class="tab-pane fade" id="profile1">
                	<form class="form-horizontal" role="form">
                		<div class="form-group">
                			<div class="col-md-5 pull-right">
				                <div class="input-group">
				                    <input type="text" class="form-control" id="cari_pasien_sudah" placeholder="Cari..." value="">
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
			                        <th style="color:#fff; text-align:center;">Nama Poli</th>
			                        <th style="color:#fff; text-align:center;">Sistem Bayar</th>
			                        <th style="color:#fff; text-align:center;">Tanggal Pelayanan</th>
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
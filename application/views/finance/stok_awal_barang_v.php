<script type="text/javascript" src="<?php echo base_url(); ?>js-devan/jquery-1.11.1.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
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

    <?php if($this->session->flashdata('sukses')){?>
        toastr["success"]("Import data berhasil.", "Notifikasi");
    <?php }else if($this->session->flashdata('gagal')){ ?>
    	toastr["error"]("Import data gagal.", "Notifikasi");
    <?php } ?>

	data_barang();

	$('#btn_import').click(function(){
		$('#popup_load').show();
	});
});

function paging($selector){
	var jumlah_tampil = $('#jumlah_tampil').val();

    if(typeof $selector == 'undefined')
    {
        $selector = $("#tabel_barang tbody tr");
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

function data_barang(){
	$('#popup_load').show();

	$.ajax({
		url : '<?php echo base_url(); ?>finance/stok_awal_barang_c/data_barang',
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

                    result[i].KETERANGAN = result[i].KETERANGAN==null?"-":result[i].KETERANGAN;

					$tr += "<tr>"+
								"<td style='vertical-align:middle; text-align:center;'>"+no+"</td>"+
								"<td style='vertical-align:middle;'>"+result[i].NAMA_ALAT+"</td>"+
								"<td style='vertical-align:middle; text-align:center;'>"+result[i].NAMA_KATEGORI+"</td>"+
								"<td style='vertical-align:middle; text-align:center;'>"+NumberToMoney(result[i].TOTAL)+"</td>"+
								"<td style='vertical-align:middle; text-align:right;'>"+NumberToMoney(result[i].HARGA_BELI)+"</td>"+
								"<td style='vertical-align:middle;'>"+result[i].KETERANGAN+"</td>"+
							"</tr>";
				}
			}

			$('#tabel_barang tbody').html($tr);
			$('#total_data').html(parseFloat(result.length));
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
    <div class="col-lg-12" id="view_data">
    	<div class="card-box">
            <ul class="nav nav-tabs">
                <li class="active" role="presentation">
                    <a data-toggle="tab" role="tab" href="#daftar1"><i class="fa fa-list"></i> Data Barang</a>
                </li>
                <li role="presentation">
                    <a data-toggle="tab" role="tab" href="#import1"><i class="fa fa-download"></i> Import Excel</a>
                </li>
            </ul>
            <div class="tab-content">
            	<div id="daftar1" class="tab-pane fade in active" role="tabpanel">
			        <form class="form-horizontal" role="form" action="<?php echo base_url(); ?>finance/stok_awal_barang_c/export_excel" method="post" target="_blank" enctype="multipart/form-data">
			        	<div class="form-group">
			                <div class="col-md-7">
			                    <button type="submit" class="btn btn-success waves-effect w-md waves-light">
			                        <i class="fa fa-upload"></i> Export Excel
			                    </button>
			                </div>
			            </div>
			        	<div class="form-group">
			        		<div class="col-md-12">
			                    <div class="table-responsive">
						            <table id="tabel_barang" class="table table-bordered">
						                <thead>
						                    <tr class="biru">
						                        <th style="color:#fff; text-align:center;">No</th>
						                        <th style="color:#fff; text-align:center;">Nama Barang</th>
						                        <th style="color:#fff; text-align:center;">Kategori</th>
						                        <th style="color:#fff; text-align:center;">Stok</th>
						                        <th style="color:#fff; text-align:center;">Harga Beli</th>
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
			        </form>
            	</div>

            	<div id="import1" class="tab-pane fade in" role="tabpanel">
            		<form class="form-horizontal" role="form" action="<?php echo base_url(); ?>finance/stok_awal_barang_c/import_excel" method="post" enctype="multipart/form-data">
			            <div class="form-group">
			                <div class="col-md-4">
			                    <input type="file" class="dropify" name="fileexcel" accept="application/vnd.ms-excel" data-max-file-size="5M">
			                </div>
			            </div>
			            <div class="form-group">
			                <div class="col-md-7">
			                    <button type="submit" class="btn btn-success waves-effect w-md waves-light" id="btn_import">
			                        <i class="fa fa-download"></i> Import
			                    </button>
			                </div>
			            </div>
			        </form>
            	</div>
           	</div>
        </div>
    </div>
</div>
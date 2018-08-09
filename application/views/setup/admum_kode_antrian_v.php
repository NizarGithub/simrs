<script type="text/javascript" src="<?php echo base_url(); ?>js-devan/jquery-1.11.1.min.js"></script>

<style type="text/css">
#view_ubah, #view_sopir, #tombol_reset{
	display: none;
}
</style>

<script type="text/javascript">
$(document).ready(function(){
	<?php if($msg == 1){?>
        notif_simpan();
    <?php }else if($this->session->flashdata('ubah')){?>
        notif_ubah();
    <?php }else if($msg == 3){ ?>
        notif_hapus();
    <?php } ?>

    data_setup_antrian();

    $('#jumlah_tampil').change(function(){
        data_setup_antrian();
    });



    $("#antrian_max").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl+A, Command+A
            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
             // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
});

function onEnterText(e){
    if (e.keyCode == 13) {
        data_setup_antrian();
        $('#tombol_reset').show();
        $('#tombol_cari').hide();
        return false;
    }
}

function paging($selector){
    var jumlah_tampil = $('#jumlah_tampil').val();

    if(typeof $selector == 'undefined')
    {
        $selector = $("#tabel_antrian tbody tr");
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

function data_setup_antrian(){
	$('#popup_load').show();
	var keyword = $('#cari_antrian').val();

	$.ajax({
		url : '<?php echo base_url(); ?>setup/admum_kode_antrian_c/data_antrian',
		data : {keyword:keyword},
		type : "POST",
		dataType : "json",
		success : function(result){
			$tr = "";

			if(result == "" || result == null){
				$tr = "<tr><td colspan='5' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
			}else{
				var no = 0;
				for(var i=0; i<result.length; i++){
					no++;
					var antrian_max = result[i].ANTRIAN_MAX;
					if(antrian_max == 0){
						antrian_max = "Tidak ada batas maksimal";
					}

					var aksi =  '<button type="button" class="btn btn-danger btn-sm m-b-5" onclick="hapus_antrian('+result[i].ID+');">'+
                                    '<i class="fa fa-trash"></i> Hapus'+
                                '</button>';

					$tr += "<tr>"+
								"<td style='text-align:center; vertical-align:middle;'>"+no+"</td>"+
								"<td style='text-align:center; vertical-align:middle;'>"+result[i].KODE+"</td>"+
								"<td style='text-align:center; vertical-align:middle; text-transform:capitalize;'>"+result[i].UNTUK+"</td>"+
								"<td style='text-align:center; vertical-align:middle;'>"+antrian_max+"</td>"+
								"<td style='vertical-align:middle;' align='center'>"+aksi+"</td>"+
							"</tr>";
				}
			}

			$('#tabel_antrian tbody').html($tr);
			paging();
			$('#popup_load').fadeOut();
		}
	});

	$('#tombol_cari').click(function(){
        data_setup_antrian();
        $('#tombol_reset').show();
        $('#tombol_cari').hide();
    });

    $('#tombol_reset').click(function(){
        $('#cari_antrian').val("");
        data_setup_antrian();
        $('#tombol_reset').hide();
        $('#tombol_cari').show();
    });
}

function cek_kode_antrian(val){
	$.ajax({
		url : '<?php echo base_url(); ?>setup/admum_kode_antrian_c/cek_kode_antrian',
		data : {val:val},
		type : "POST",
		dataType : "json",
		success : function(result){
			if(result > 0){
				$('#kode_warning').show();
				$('#sts_kode').val(1);
			} else {
				$('#kode_warning').hide();
				$('#sts_kode').val(0);
			}
		}
	});
}

function cek_simpan(){
	var a = "";
	var sts_kode = $('#sts_kode').val();

	if(sts_kode > 0){
		alert('Kode antrian ini telah terpakai, silahkan pilih kode lainnya');
		a = false;
	} else if(sts_kode == 0){
		a = true;
	}

	return a;
}

function hapus_antrian(id){
	$('#popup_hapus').click();
	$('#id_hapus').val(id);
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
                    <a href="#home1" role="tab" data-toggle="tab"><i class="fa fa-fax"></i> Kode Antrian </a>
                </li>
                <li role="presentation" id="tambah_data">
                    <a href="#profile1" role="tab" data-toggle="tab"><i class="fa fa-plus"></i> Tambah Kode Antrian</a>
                </li>
            </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade in active" id="home1">
                	<form class="form-horizontal" role="form">
	                	<div class="form-group">
			                <div class="col-md-8">
			                    &nbsp;
			                </div>
			                <div class="col-md-4">
				                <div class="input-group">
				                    <input type="text" class="form-control" id="cari_antrian" placeholder="Cari..." value="" onkeypress="return onEnterText(event);">
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
			            <table id="tabel_antrian" class="table table-bordered">
			                <thead>
			                    <tr class="biru">
			                        <th style="color:#fff; text-align:center;">No</th>
			                        <th style="color:#fff; text-align:center;">Kode Antrian</th>
			                        <th style="color:#fff; text-align:center;"> Antrian Untuk </th>
			                        <th style="color:#fff; text-align:center;"> Antrian Max </th>
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
                    <form class="form-horizontal" role="form" action="<?php echo $post_url; ?>" method="post" onsubmit="return cek_simpan();">
				        <div class="card-box">
				            <div class="row">
				            	<div class="form-group">
			                        <label class="col-md-2 control-label">Kode Antrian</label>
			                        <div class="col-md-5">
			                        	<input type="hidden" id="sts_kode" name="sts_kode" value="0">
			                            <input type="text" autocomplete="off" onkeyup="cek_kode_antrian(this.value);" class="form-control" name="kode_antrian" id="kode_antrian" required="required">
			                        	<span class="help-block" id="kode_warning" style="display:none;">
			                            	<small style="color:red;"> Kode antrian ini telah terpakai, silahkan pilih kode lainnya </small>
			                            </span>
			                        </div>
			                    </div>

			                    <div class="form-group">
			                        <label class="col-md-2 control-label">Antrian Untuk</label>
			                        <div class="col-md-5">
			                            <select class="form-control" name="untuk" id="untuk" required="required">
	                                        <option value=""> -- Pilih</option>
	                                        <option value="kasir"> Kasir / Pembayaran </option>
	                                        <option value="poli"> Poli </option>
	                                        <option value="konsultasi"> Konsultasi </option>
	                                        <option value="jenguk"> Jenguk Pasien </option>
	                                        <option value="apotek"> Aprotek </option>
	                                    </select>
			                        </div>
			                    </div>

			                    <div class="form-group">
			                        <label class="col-md-2 control-label">Antrian Maksimal</label>
			                        <div class="col-md-5">
			                            <input type="text" class="form-control" name="antrian_max" id="antrian_max" required="required">
			                            <span class="help-block">
			                            	<small>Isikan 0 jika tidak ada batas maksimal</small>
			                            </span>
			                        </div>
			                    </div>

			                    <div class="form-group">
			                        <label class="col-md-2 control-label">&nbsp;</label>
			                        <div class="col-md-5">
			                        	<button type="submit" class="btn btn-success"><i class="fa fa-save"></i> <b>Simpan</b></button>
			                        	<button type="button" class="btn btn-danger" id="batal"><i class="fa fa-times"></i> <b>Batal</b></button>
			                        </div>
			                    </div>
				            </div>
				        </div>
				    </form>
                </div>
            </div>
		</div>
	</div>
</div>


<button class="btn btn-primary" data-toggle="modal" data-target="#custom-width-modal" id="popup_hapus" style="display:none;">Custom width Modal</button>
<div id="custom-width-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="width:55%;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="custom-width-modalLabel">Konfirmasi Hapus</h4>
            </div>
            <div class="modal-body">
                <p id="msg"> Apakah data ini ingin dihapus ? </p>
            </div>
            <div class="modal-footer">
                <form action="<?php echo $post_url; ?>" method="post">
                    <input type="hidden" name="id_hapus" id="id_hapus" value="">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Tidak</button>
                    <input type="submit" name="hapus" class="btn btn-danger" value="Ya"/>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script type="text/javascript" src="<?php echo base_url(); ?>js-devan/jquery-1.11.1.min.js"></script>
<style type="text/css">
.loading_tabel_brg{
    z-index: 9999;
    position: absolute;
    left: 45%;
    top: 40%;
    display: none;
}

.loading_tabel_brg_det{
    z-index: 9999;
    position: absolute;
    left: 45%;
    top: 40%;
    display: none;
}
</style>
<script type="text/javascript">
var ajax = "";
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

	<?php if($this->session->flashdata('ubah')){?>
        notif_ubah();
    <?php }else if($this->session->flashdata('batal')){ ?>
    	toastr["success"]("Permintaan barang berhasil dibatalkan.", "Notifikasi");
    <?php }else if($this->session->flashdata('kirim')){ ?>
        notif_kirim();
    <?php }else if($this->session->flashdata('sukses')){ ?>
        notif_simpan();
    <?php } ?>

	get_data_permintaan();

	$('#btn_proses').click(function(){
		get_data_permintaan();
	});

	$('#batal').click(function(){
		window.location = "<?php echo base_url(); ?>rekam_medik/rk_permintaan_po_c";
	});

	$('#li_input').click(function(){
		get_kode_po();
	});

	$('.btn_barang').click(function(){
		$('#popup_barang').click();
		get_data_barang();
	});

	$('#tutup_barang').click(function(){
		$('#cari_barang').val("");
	});	
});

function get_kode_po(){
	$.ajax({
        url : '<?php echo base_url(); ?>rekam_medik/rk_permintaan_po_c/get_kode_po',
        type : "POST",
        dataType : "json",
        success : function(kode){
            $('#kode_po').val(kode); 
        }
    });
}

function get_data_permintaan(){
	$('#popup_load').show();

	var bulan = $('#bulan').val();
	var tahun = $('#tahun').val();

	if(ajax){
		ajax.abort();
	}

	ajax = $.ajax({
		url : '<?php echo base_url(); ?>rekam_medik/rk_permintaan_po_c/data_permintaan_barang',
		data : {
			bulan:bulan,
			tahun:tahun
		},
		type : "POST",
		dataType : "json",
		success : function(result){
			$tr = "";

			if(result == "" || result == null){
				$tr = "<tr><td colspan='6' style='text-align:center;'><b>Data tidak ditemukan</b></td></tr>";
			}else{
				var no = 0;

				for(var i=0; i<result.length; i++){
					no++;

					var stt = '';
					if(result[i].STATUS_BATAL == '1'){
						stt = '<span class="label label-primary">BERHASIL DIBATALKAN</span>';
					}else{
						stt = "<button type='button' class='btn btn-danger btn-sm waves-effect w-md waves-light' onclick='dibatalkan("+result[i].ID+");'><i class='fa fa-times'></i></button>";
					}

					$tr += "<tr>"+
								"<td style='vertical-align:middle; text-align:center;'>"+no+"</td>"+
								"<td style='vertical-align:middle; text-align:center;'>"+result[i].KODE_PO+"</td>"+
								"<td style='vertical-align:middle; text-align:center;'>"+result[i].TANGGAL+"</td>"+
								"<td style='vertical-align:middle; text-align:center;'>"+result[i].WAKTU+"</td>"+
								"<td style='vertical-align:middle; text-align:center;'>"+
									"<button type='button' class='btn btn-primary btn-sm waves-effect w-md waves-light' onclick='get_detail_barang("+result[i].ID+");'>"+formatNumber(result[i].TOTAL_BARANG)+" Item</button>"+
								"</td>"+
								"<td style='vertical-align:middle; text-align:center;'>"+stt+"</td>"+
							"</tr>";
				}
			}

			$('#tabel_daftar tbody').html($tr);
			$('#popup_load').hide();
		}
	});
}

function get_detail_barang(id_permintaan){
	$('#popup_det_barang').click();
	$('.loading_tabel_brg_det').show();

	$.ajax({
		url : '<?php echo base_url(); ?>rekam_medik/rk_permintaan_po_c/detail_barang_permintaan',
		data : {id_permintaan:id_permintaan},
		type : "POST",
		dataType : "json",
		success : function(result){
			$tr = "";

			if(result == "" || result == null){
				$tr = "<tr><td colspan='5' style='text-align:center;'><b>Data tidak ditemukan</b></td></tr>";
			}else{
				var no = 0;

				for(var i=0; i<result.length; i++){
					no++;

					$tr += "<tr>"+
								"<td style='vertical-align:middle; text-align:center;'>"+no+"</td>"+
								"<td style='vertical-align:middle; text-align:center;'>"+result[i].KODE_ALAT+"</td>"+
								"<td style='vertical-align:middle;'>"+result[i].NAMA_ALAT+"</td>"+
								"<td style='vertical-align:middle; text-align:center;'>"+result[i].NAMA_KATEGORI+"</td>"+
								"<td style='vertical-align:middle; text-align:center;'>"+formatNumber(result[i].JUMLAH_PERMINTAAN)+"</td>"+
							"</tr>";
				}
			}

			$('#tabel_barang_det tbody').html($tr);
			$('.loading_tabel_brg_det').hide();
		}
	});
}

function dibatalkan(id){
	$('#popup_batal').click();

	$.ajax({
        url : '<?php echo base_url(); ?>rekam_medik/rk_permintaan_po_c/data_permintaan_barang_id',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(row){
            $('#id_batal').val(id);
            var t = row['KODE_PO'];
            $('#msg').html('Apakah kode permintaan <b>'+t+'</b> ingin dibatalkan?');
        }
    });
}

function get_data_barang(){
	$('.loading_tabel_brg').show();
	var keyword = $('#cari_barang').val();

	if(ajax){
		ajax.abort();
	}

	ajax = $.ajax({
		url : '<?php echo base_url(); ?>rekam_medik/rk_permintaan_po_c/data_peralatan',
		data : {
			keyword:keyword
		},
		type : "GET",
		dataType : "json",
		success : function(result){
			$tr = "";

			if(result == "" || result == null){
				$tr = "<tr><td colspan='4' style='text-align:center;'><b>Data tidak ditemukan</b></td></tr>";
			}else{
				var no = 0;

				for(var i=0; i<result.length; i++){
					no++;

					$tr += "<tr style='cursor:pointer;' onclick='klik_barang("+result[i].ID+");'>"+
								"<td style='vertical-align:middle; text-align:center;'>"+no+"</td>"+
								"<td style='vertical-align:middle; text-align:center;'>"+result[i].KODE_ALAT+"</td>"+
								"<td style='vertical-align:middle;'>"+result[i].NAMA_ALAT+"</td>"+
								"<td style='vertical-align:middle; text-align:center;'>"+result[i].NAMA_KATEGORI+"</td>"+
								"<td style='vertical-align:middle; text-align:center;'>"+formatNumber(result[i].TOTAL)+"</td>"+
							"</tr>";
				}
			}

			$('#tabel_barang tbody').html($tr);
			$('.loading_tabel_brg').fadeOut();
		}
	});

	$('#cari_barang').off('keyup').keyup(function(){
        get_data_barang();
    });
}

function deleteRow(btn,id){
    var row = btn.parentNode.parentNode;
    row.parentNode.removeChild(row);
    hitung_item(id);
}

function klik_barang(id){
	$('#tutup_barang').click();

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

	$.ajax({
		url : '<?php echo base_url(); ?>rekam_medik/rk_permintaan_po_c/data_peralatan_id',
		data : {
			id:id
		},
		type : "POST",
		dataType : "json",
		success : function(result){
			$tr = "";

			for(var i=0; i<result.length; i++){
				var jumlah_tr = $('#jumlah_tr_'+result[i].ID).length;
				
				if(result[i].TOTAL == '0'){
					toastr["error"]("Barang tidak bisa diorder! Stok barang habis.", "Perhatian");
				}else if(parseInt(jumlah_tr) == '0'){
					$tr = "<tr id='jumlah_tr_"+result[i].ID+"'>"+
								"<td style='vertical-align:middle; text-align:center;'>"+result[i].KODE_ALAT+"</td>"+
								"<td style='vertical-align:middle;'>"+result[i].NAMA_ALAT+"</td>"+
								"<td style='vertical-align:middle; text-align:center;'>"+result[i].NAMA_KATEGORI+"</td>"+
								"<td style='vertical-align:middle; text-align:center;'>"+result[i].TOTAL+"</td>"+
								"<td align='center'>"+
									"<input type='hidden' name='id_barang[]' value='"+result[i].ID+"'>"+
									"<input type='hidden' id='stok_"+result[i].ID+"' value='"+result[i].TOTAL+"'>"+
									"<input type='text' name='jumlah[]' class='form-control' id='jumlah_"+result[i].ID+"' value='' onkeyup='FormatCurrency(this); hitung_item("+result[i].ID+");' style='width:125px;'>"+
								"</td>"+
								"<td align='center'>"+
									"<button type='button' class='btn btn-danger' onclick='deleteRow(this,"+result[i].ID+");'><i class='fa fa-trash'></i></button>"+
								"</td>"
							"</tr>";

					$('#tabel_data tbody').append($tr);
					hitung_item();
				}
			}
		}
	});
}

function hitung_item(id){
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

	var stok = $('#stok_'+id).val();
	var jumlah = $('#jumlah_'+id).val();

	jumlah = jumlah.split(',').join('');

	if(parseFloat(jumlah) > parseFloat(stok)){
		toastr["error"]("Perhatian! Jumlah permintaan tidak boleh melebihi stok.", "Notifikasi");
		$('#simpan').attr('disabled','disabled');
	}else{
		$('#simpan').removeAttr('disabled');
	}

	var total = 0;
	$("input[name='jumlah[]']").each(function(idx,elm){
		var tot = elm.value;
		tot = tot.split(',').join('');
		if(tot == ""){
			tot = 0;
		}
		total += parseFloat(tot);
	});

	$('#total_barang').val(formatNumber(total));
}
</script>

<?php
$sess_user = $this->session->userdata('masuk_rs');
$id_user = $sess_user['id'];
$sql = "
	SELECT
		a.ID,
		a.ID_DEPARTEMEN,
		a.ID_DIVISI,
		b.NAMA_DEP,
		c.NAMA_DIV
	FROM kepeg_pegawai a
	LEFT JOIN kepeg_departemen b ON a.ID_DEPARTEMEN = b.ID
	LEFT JOIN kepeg_divisi c ON a.ID_DIVISI = c.ID
	WHERE a.ID = '$id_user'
";
$qry = $this->db->query($sql)->row();
$id_dep = $qry->ID_DEPARTEMEN;
$id_div = $qry->ID_DIVISI;
$nama_dep = $qry->NAMA_DEP;
?>

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
                    <a data-toggle="tab" role="tab" href="#daftar1"><i class="fa fa-list"></i> Daftar Permintaan Barang</a>
                </li>
                <li role="presentation" id="li_input">
                    <a data-toggle="tab" role="tab" href="#poli1"><i class="fa fa-pencil"></i> Permintaan ke Pengadaan Barang</a>
                </li>
            </ul>
            <div class="tab-content">
            	<div id="daftar1" class="tab-pane fade in active" role="tabpanel">
            		<form class="form-horizontal">
            			<div class="form-group">
                            <div class="col-md-2">
                                <label for="bulan">Bulan</label>
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
                            <div class="col-md-2">
                                <label for="tahun">Tahun</label>
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
                            <div class="col-md-2">
                                <label for="proses">&nbsp;</label><br>
                                <button class="btn btn-warning waves-effect w-md waves-light" id="btn_proses" type="button">Proses</button>
                            </div>
                        </div>
            			<div class="form-group">
                            <div class="col-md-12">
                                <div class="table-responsive">
						            <table id="tabel_daftar" class="table table-bordered">
						                <thead>
						                    <tr class="hijau">
						                        <th style="color:#fff; text-align:center;">No</th>
						                        <th style="color:#fff; text-align:center;">Kode Permintaan</th>
						                        <th style="color:#fff; text-align:center;">Tanggal</th>
						                        <th style="color:#fff; text-align:center;">Waktu</th>
						                        <th style="color:#fff; text-align:center;">Total Barang</th>
						                        <th style="color:#fff; text-align:center;">Dibatalkan</th>
						                    </tr>
						                </thead>
						                <tbody>
						                    
						                </tbody>
						            </table>
						        </div>
                            </div>
                        </div>
            		</form>
            	</div>

                <div id="poli1" class="tab-pane fade in" role="tabpanel">
                	<form class="form-horizontal" role="form" action="<?php echo base_url(); ?>rekam_medik/rk_permintaan_po_c/simpan" method="post">
                		<input type="hidden" name="id_departemen" id="id_departemen" value="<?php echo $id_dep; ?>">
                		<input type="hidden" name="id_divisi" id="id_divisi" value="<?php echo $id_div; ?>">
                		<input type="hidden" name="id_pegawai" id="id_pegawai" value="<?php echo $id_user; ?>">
                		<div class="form-group">
                            <label class="col-md-2 control-label">Kode PO</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="kode_po" id="kode_po" value="" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Divisi</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" value="<?php echo $nama_dep; ?>" readonly>
                            </div>
                        </div>
                        <div class="form-group">
	                        <label class="col-md-2 control-label">Cari Barang</label>
	                        <div class="col-md-4">
	                            <div class="input-group">
	                                <input type="text" class="form-control" value="" readonly="readonly" required="required">
	                                <span class="input-group-btn">
	                                    <button type="button" class="btn btn-inverse btn_barang"><i class="fa fa-search"></i></button>
	                                </span>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-md-2 control-label">&nbsp;</label>
	                        <div class="col-md-7">
	                            <div class="table-responsive">
						            <table id="tabel_data" class="table table-bordered">
						                <thead>
						                    <tr class="kuning_tr">
						                        <th style="color:#fff; text-align:center;">Kode Barang</th>
						                        <th style="color:#fff; text-align:center;">Nama Barang</th>
						                        <th style="color:#fff; text-align:center;">Kategori</th>
						                        <th style="color:#fff; text-align:center;">Stok</th>
						                        <th style="color:#fff; text-align:center;">Jumlah Permintaan</th>
						                        <th style="color:#fff; text-align:center;">#</th>
						                    </tr>
						                </thead>
						                <tbody>
						                    
						                </tbody>
						            </table>
						        </div>
	                        </div>
	                    </div>
	                    <div class="form-group">
                            <label class="col-md-2 control-label">Total Barang</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="total_barang" id="total_barang" value="" readonly>
                            </div>
                        </div>
	                    <hr>
	                    <div class="form-group">
	                    	<label class="col-md-2 control-label">&nbsp;</label>
	                    	<div class="col-md-6">
	                    		<button type="submit" class="btn btn-success" id="simpan"><i class="fa fa-save"></i> <b>Simpan</b></button>
		                        <button type="button" class="btn btn-danger" id="batal"><i class="fa fa-times"></i> <b>Batal</b></button>
	                    	</div>
	                    </div>
                	</form>
                </div>
            </div>
        </div>
    </div>
</div>

<button class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#myModal1" id="popup_barang" style="display:none;">Standard Modal</button>
<div id="myModal1" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 50%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Data Barang</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form">
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="input-group">
                                <input type="text" class="form-control" id="cari_barang" placeholder="Cari..." value="">
                                <span class="input-group-btn">
                                    <button type="button" class="btn waves-effect waves-light btn-custom" style="cursor:default;">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="loading_tabel_brg">
		        	<img src="<?php echo base_url(); ?>picture/processando.gif" style="width: 90px; height: 90px;">
		        </div>
                <div class="table-responsive">
                    <div class="scroll-y">
                        <table class="table table-hover" id="tabel_barang">
                            <thead>
                                <tr class="biru_popup">
                                    <th style="text-align:center; color: #fff;">No</th>
                                    <th style="text-align:center; color: #fff; white-space: nowrap;">Kode Barang</th>
                                    <th style="text-align:center; color: #fff;">Nama Barang</th>
                                    <th style="text-align:center; color: #fff;">Kategori</th>
                                    <th style="text-align:center; color: #fff;">Stok</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
                <div id="tablePaging"> </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_barang">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<button class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#myModal2" id="popup_det_barang" style="display:none;">Standard Modal</button>
<div id="myModal2" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 50%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Detail Barang</h4>
            </div>
            <div class="modal-body">
                <div class="loading_tabel_brg_det">
		        	<img src="<?php echo base_url(); ?>picture/processando.gif" style="width: 90px; height: 90px;">
		        </div>
                <div class="table-responsive">
                    <div class="scroll-y">
                        <table class="table table-bordered" id="tabel_barang_det">
                            <thead>
                                <tr class="merah">
                                    <th style="text-align:center; color: #fff;">No</th>
                                    <th style="text-align:center; color: #fff; white-space: nowrap;">Kode Barang</th>
                                    <th style="text-align:center; color: #fff;">Nama Barang</th>
                                    <th style="text-align:center; color: #fff;">Kategori</th>
                                    <th style="text-align:center; color: #fff;">Jumlah Permintaan</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_barang_det">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<button id="popup_batal" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#custom-width-modal" style="display:none;">Custom width Modal</button>
<div id="custom-width-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="custom-width-modalLabel">Konfirmasi Pembatalan</h4>
            </div>
            <div class="modal-body">
                <p id="msg"></p>
            </div>
            <div class="modal-footer">
            	<form action="<?php echo base_url(); ?>rekam_medik/rk_permintaan_po_c/dibatalkan" method="post">
            		<input type="hidden" name="id_batal" id="id_batal" value="">
	                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Tidak</button>
	                <button type="submit" class="btn btn-danger waves-effect waves-light">Ya</button>
            	</form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
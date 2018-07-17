<script type="text/javascript" src="<?php echo base_url(); ?>js-devan/jquery-1.11.1.min.js"></script>

<style type="text/css">
#view_tambah, #view_stok, #tombol_reset, #view_gambar, #view_input_pemakaian{
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

    $("input[name='urutkan']").click(function(){
    	var urutkan = $("input[name='urutkan']:checked").val();
    	if(urutkan == 'Stok'){
    		$('#view_stok').show();
    	}else{
    		$('#view_stok').hide();
    		data_peralatan();
    	}
    });

    $('#urutkan_stok').change(function(){
    	data_peralatan();
    });

    $('#jumlah_tampil').change(function(){
    	data_peralatan();
    });

	$('#btn_tambah').click(function(){
		$('#view_tambah').show();
		$('#view_data').hide();
		$('#ket').val('Tambah');
	});

	$('#batal').click(function(){
		window.location = "<?php echo base_url(); ?>logistik/log_peralatan_medis_c";
	});

	$('#kode_alat').click(function(){
		$('#popup_nama_alat').click();
		load_nama_alat();
	});

	$('.btn_nama_alat').click(function(){
		$('#popup_nama_alat').click();
		load_nama_alat();
	});

	$('#satuan').click(function(){
		$('#popup_satuan').click();
		get_satuan();
	});

	$('.btn_satuan').click(function(){
		$('#popup_satuan').click();
		get_satuan();
	});
});

function load_nama_alat(){
	var keyword = $('#cari_nama_alat').val();

	if(ajax){
		ajax.abort();
	}

	ajax = $.ajax({
		url : '<?php echo base_url()?>logistik/log_peralatan_medis_c/load_nama_alat',
		data : {keyword:keyword},
		type : "GET",
		dataType : "json",
		success : function(result){
			$tr = "";

			if(result == "" || result == null){
				$tr = "<tr><td colspan='5' style='text-align:center;'><b>Data tidak ditemukan</b></td></tr>";
			}else{
				var no = 0;
				for(var i=0; i<result.length; i++){
					no++;

					$tr += "<tr style='cursor:pointer;' onclick='klik_nama_alat("+result[i].ID+");'>"+
								"<td style='text-align:center;'>"+no+"</td>"+
								"<td style='text-align:center;'>"+result[i].KODE_ALAT+"</td>"+
								"<td style='text-align:center;'>"+result[i].BARCODE+"</td>"+
								"<td>"+result[i].NAMA_ALAT+"</td>"+
								"<td>"+result[i].MERK+"</td>"+
							"</tr>";
				}
			}

			$('#tabel_nama_alat tbody').html($tr);
		}
	});

	$('#cari_nama_alat').off('keyup').keyup(function(){
		load_nama_alat();
	});
}

function klik_nama_alat(id){
	$('#tutup_nama_alat').click();

	$.ajax({
		url : '<?php echo base_url(); ?>logistik/log_peralatan_medis_c/klik_nama_alat',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(row){
			$('#id_nama_alat').val(id);

			if(row['ID_ALAT'] == null){
				$('#kode_alat').val(row['KODE_ALAT']);
				$('#nama_alat').val(row['NAMA_ALAT']);
				$('#merk').val(row['MERK']);
				$('#jenis_alat').val(row['JENIS_ALAT']);
				$('#id_satuan').val("");
				$('#satuan').val("");
				$('#view_select_pemakaian').show();
				$('#view_input_pemakaian').hide();
				$('#jumlah').val("");
				$('#isi').val("");
				$('#total').val("");
				$('#harga_beli').val("");
				$('#view_gambar').hide();
			}else{
				$('#kode_alat').val(row['KODE_ALAT']);
				$('#nama_alat').val(row['NAMA_ALAT']);
				$('#merk').val(row['MERK']);
				$('#jenis_alat').val(row['JENIS_ALAT']);
				$('#id_satuan').val(row['ID_SATUAN']);
				$('#satuan').val(row['NAMA_SATUAN']);
				$('#view_select_pemakaian').hide();
				$('#view_input_pemakaian').show();
				$('#pemakaian_txt').val(row['PEMAKAIAN']);
				$('#jumlah').val(NumberToMoney(row['JUMLAH']));
				$('#isi').val(NumberToMoney(row['ISI']));
				$('#total').val(NumberToMoney(row['TOTAL']));
				$('#harga_beli').val(NumberToMoney(row['HARGA_BELI']));

				var link_gambar = "<?php echo base_url(); ?>files/foto_alat/"+row['GAMBAR'];
				$('#view_gambar').show();
				$('#gambar').attr('src',link_gambar);
				$('#file_hidden').val(row['GAMBAR']);
			}
		}
	});
}

function get_satuan(){
	var keyword = $('#cari_satuan').val();

	if(ajax){
		ajax.abort();
	}

	ajax = $.ajax({
        url : '<?php echo base_url(); ?>logistik/log_peralatan_medis_c/data_satuan',
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

	            	$tr += '<tr style="cursor:pointer;" onclick="klik_satuan('+result[i].ID+');">'+
	            				'<td style="text-align:center;">'+no+'</td>'+
	            				'<td style="text-align:center;">'+result[i].NAMA_SATUAN+'</td>'+
	            			'</tr>';
	            }
            }

            $('#tabel_satuan tbody').html($tr);
        }
    });

    $('#cari_satuan').off('keyup').keyup(function(){
    	get_satuan();
    });
}

function klik_satuan(id_satuan){
	$('#tutup_satuan').click();

	$.ajax({
		url : '<?php echo base_url(); ?>logistik/log_peralatan_medis_c/klik_satuan',
		data : {id_satuan:id_satuan},
		type : "POST",
		dataType : "json",
		success : function(row){
            var ket = $('#ket').val();

            if(ket == 'Tambah'){
                $('#id_satuan').val(id_satuan);
                $('#satuan').val(row['NAMA_SATUAN']);
            }else{
                $('#id_satuan_ubah').val(id_satuan);
                $('#satuan_ubah').val(row['NAMA_SATUAN']);
            }
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

function data_peralatan(){
	$('#popup_load').show();
	var keyword = $('#cari_alat').val();
	var urutkan = $("input[name='urutkan']:checked").val();
	var urutkan_stok = $('#urutkan_stok').val();

	if(ajax){
		ajax.abort();
	}

	ajax = $.ajax({
		url : '<?php echo base_url(); ?>logistik/log_peralatan_medis_c/data_peralatan',
		data : {
			keyword:keyword,
			urutkan:urutkan,
			urutkan_stok:urutkan_stok
		},
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

					var aksi =  '<button type="button" class="btn btn-success waves-effect waves-light btn-sm m-b-5" onclick="ubah_alat('+result[i].ID+');">'+
                                    '<i class="fa fa-pencil"></i>'+
                                '</button>&nbsp;'+
                                '<button type="button" class="btn btn-danger waves-effect waves-light btn-sm m-b-5" onclick="hapus_alat('+result[i].ID+');">'+
                                    '<i class="fa fa-trash"></i>'+
                                '</button>';

					$tr += "<tr>"+
								"<td style='text-align:center;'>"+no+"</td>"+
								"<td>"+
									"<b>"+result[i].NAMA_ALAT+"</b><br>"+
									"<small>"+result[i].BARCODE+"</small>"+
								"</td>"+
								"<td style='text-align:center;'>"+result[i].URUT_BARANG+"</td>"+
								"<td>"+result[i].JENIS_ALAT+"</td>"+
								"<td style='text-align:right;'>"+NumberToMoney(result[i].HARGA_BELI)+"</td>"+
								"<td style='text-align:right;'>"+NumberToMoney(result[i].TOTAL)+"</td>"+
								"<td style='text-align:center;'>"+formatTanggal(result[i].TANGGAL_MASUK)+"</td>"+
								"<td style='text-align:center;'>"+result[i].WAKTU_MASUK+"</td>"+
								"<td align='center'>"+aksi+"</td>"+
							"</tr>";
				}
			}
			$('#tabel_alat tbody').html($tr);
			paging();
			$('#popup_load').fadeOut();
		}
	});

	$('#tombol_cari').click(function(){
        data_peralatan();
        $('#tombol_reset').show();
        $('#tombol_cari').hide();
    });

    $('#tombol_reset').click(function(){
        $('#cari_alat').val("");
        data_peralatan();
        $('#tombol_reset').hide();
        $('#tombol_cari').show();
    });
}

function onEnterText(e){
    if (e.keyCode == 13) {
        data_peralatan();
        $('#tombol_reset').show();
		$('#tombol_cari').hide();
        return false;
    }
}

function hitung_total(){
    var ket = $('#ket').val();

    if(ket == 'Tambah'){
        var jumlah = $('#jumlah').val();
        var isi = $('#isi').val();

        jumlah = jumlah.split(',').join('');
        isi = isi.split(',').join('');

        if(jumlah == ""){
            jumlah = 0;
        }

        if(isi == ""){
            isi = 0;
        }

        var total = parseFloat(jumlah) * parseFloat(isi);
        $('#total').val(NumberToMoney(total));
    }else{
        var jumlah = $('#jumlah_ubah').val();
        var isi = $('#isi_ubah').val();

        jumlah = jumlah.split(',').join('');
        isi = isi.split(',').join('');

        if(jumlah == ""){
            jumlah = 0;
        }

        if(isi == ""){
            isi = 0;
        }

        var total = parseFloat(jumlah) * parseFloat(isi);
        $('#total_ubah').val(NumberToMoney(total));
    }
}
</script>

<input type="hidden" id="ket" value="">

<div id="popup_load">
    <div class="window_load">
        <img src="<?=base_url()?>picture/progress.gif" height="100" width="125">
    </div>
</div>

<div class="col-lg-12">
    <div class="card-box" id="view_data">
    	<form class="form-horizontal" role="form">
            <div class="form-group">
                <div class="col-md-7">
                    <button type="button" class="btn btn-purple waves-effect w-md waves-light" id="btn_tambah">
                        <i class="fa fa-plus"></i> Tambah
                    </button>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-1 control-label" style="text-align:left;">Urutkan</label>
                <div class="col-md-6">
                    <div class="radio radio-purple radio-inline">
                        <input type="radio" name="urutkan" value="Default" id="default" checked="checked">
                        <label for="default"> Default </label>
                    </div>
                    <div class="radio radio-purple radio-inline">
                        <input type="radio" name="urutkan" value="Nama Alat" id="urut_nama_alat">
                        <label for="urut_nama_alat"> Nama Alat </label>
                    </div>
                    <div class="radio radio-purple radio-inline">
                        <input type="radio" name="urutkan" value="Stok" id="urut_stok">
                        <label for="urut_stok"> Stok </label>
                    </div>
                </div>
                <div class="col-md-4 pull-right">
	                <div class="input-group">
	                    <input type="text" class="form-control" id="cari_alat" placeholder="Cari..." value="" onkeypress="return onEnterText(event);">
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
                    <select id="urutkan_stok" class="form-control">
                        <option value="Rendah">Terendah</option>
                        <option value="Tinggi">Tertinggi</option>
                    </select>
                </div>
            </div>
        </form>

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
            <div class="form-group">
                <table>
                    <tr>
                        <td><div class="merah_tr" style="width:15px; height:15px;"></div></td>
                        <td>&nbsp;</td>
                        <td>Keterangan warna tabel</td>
                    </tr>
                </table>
            </div>
        </form>
    </div>

    <div class="card-box" id="view_tambah">
    	<form class="form-horizontal" role="form" action="<?php echo $url_simpan; ?>" method="post" enctype="multipart/form-data">
            <h4 class="header-title m-t-0 m-b-30">Tambah Alat</h4>
            <hr/>
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="col-md-2 control-label">Kode Alat</label>
                        <div class="col-md-8">
                            <div class="input-group">
                                <input type="hidden" name="id_nama_alat" id="id_nama_alat" value="">
                                <input type="text" class="form-control" id="kode_alat" value="" required="required" readonly>
                                <span class="input-group-btn">
                                    <button class="btn waves-effect waves-light btn-default btn_nama_alat" type="button">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Nama Alat</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="nama_alat" value="" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Merk</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="merk" value="" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Jenis Obat</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="jenis_alat" value="" required="required" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Satuan</label>
                        <div class="col-md-8">
                            <div class="input-group">
                            	<input type="hidden" name="id_satuan" id="id_satuan" value="">
                                <input type="text" class="form-control" id="satuan" value="" required="required" readonly>
                                <span class="input-group-btn">
                                	<button class="btn waves-effect waves-light btn-default btn_satuan" type="button">
                                		<i class="fa fa-search"></i>
                                	</button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Pemakaian</label>
                        <div class="col-md-6" id="view_select_pemakaian">
		                    <select name="pemakaian" id="pemakaian" class="form-control">
		                    	<option value="Sekali Pakai">Sekali Pakai</option>
		                    	<option value="Dipakai Berulang">Dipakai Berulang</option>
		                    </select>
		                </div>
		                <div class="col-md-8" id="view_input_pemakaian">
                            <input type="text" class="form-control" name="pemakaian_txt" id="pemakaian_txt" value="" readonly>
                        </div>
                    </div>
                    <div class="form-group" id="view_gambar">
                        <label class="col-md-2 control-label">View Gambar</label>
                        <div class="col-md-6">
                        	<input type="hidden" name="file_hidden" id="file_hidden" value="">
		                    <img src="" id="gambar" style="max-width:250px; max-height:125px;">
		                </div>
                    </div>
                </div>

                <div class="col-lg-6">
                	<div class="form-group">
                        <label class="col-md-2 control-label">Jumlah</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="jumlah" id="jumlah" value="" required="required" onkeyup="FormatCurrency(this); hitung_total();">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Isi</label>
                        <div class="col-md-8">
                            <div class="input-group">
                                <input type="text" class="form-control" name="isi" id="isi" value="" required="required" onkeyup="FormatCurrency(this); hitung_total();">
                                <span class="input-group-btn">
                                	<button class="btn waves-effect waves-light btn-pink" type="button" style="cursor:default;">buah</button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Total</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="total" id="total" value="" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Harga Beli</label>
                        <div class="col-md-8">
                            <div class="input-group">
                                <span class="input-group-addon">Rp</span>
                                <input type="text" class="form-control" name="harga_beli" id="harga_beli" value="" required="required" onkeyup="FormatCurrency(this);">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Gambar</label>
                        <div class="col-md-8">
                            <input type="file" class="dropify" name="userfile">
                        </div>
                    </div>
                </div>
            </div>
            <hr/>
            <center>
                <button type="submit" class="btn btn-success waves-effect waves-light m-b-5"> <i class="fa fa-save"></i> <span>Simpan</span> </button>
                <button type="button" class="btn btn-danger waves-effect waves-light m-b-5" id="batal"> <i class="fa fa-times"></i> <span>Batal</span> </button>
            </center>
    	</form>
    </div>
</div>

<button class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#myModal1" id="popup_nama_alat" style="display:none;">Standard Modal</button>
<div id="myModal1" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Data Nama Alat Medis</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form">
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="input-group">
                                <input type="text" class="form-control" id="cari_nama_alat" placeholder="Cari..." value="">
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
                        <table class="table table-hover" id="tabel_nama_alat">
                            <thead>
                                <tr class="merah_popup">
                                    <th style="text-align:center; color: #fff;" width="50">No</th>
                                    <th style="text-align:center; color: #fff;">Kode Alat</th>
                                    <th style="text-align:center; color: #fff;">Barcode</th>
                                    <th style="text-align:center; color: #fff;">Nama Alat</th>
                                    <th style="text-align:center; color: #fff;">Merk</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_nama_alat">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<button class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#myModal3" id="popup_satuan" style="display:none;">Standard Modal</button>
<div id="myModal3" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Data Jenis Obat</h4>
            </div>
            <div class="modal-body">
            	<form class="form-horizontal" role="form">
		            <div class="form-group">
		                <div class="col-md-12">
			                <div class="input-group">
			                    <input type="text" class="form-control" id="cari_satuan" placeholder="Cari..." value="">
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
		                <table class="table table-hover" id="tabel_satuan">
		                    <thead>
		                        <tr class="merah_popup">
		                            <th style="text-align:center; color: #fff;" width="50">No</th>
		                            <th style="text-align:center; color: #fff;">Satuan Obat</th>
		                        </tr>
		                    </thead>
		                    <tbody>
		                        
		                    </tbody>
		                </table>
            		</div>
            	</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_satuan">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
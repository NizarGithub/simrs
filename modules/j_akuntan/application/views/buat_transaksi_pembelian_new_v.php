<?PHP 
$no_transaksi = 10001;
if($no_trx->NEXT != "" || $no_trx->NEXT != null ){
	$no_transaksi = $no_trx->NEXT+1;
}

?>

<style type="text/css">

input[type=checkbox]
{
  /* Double-sized Checkboxes */
  -ms-transform: scale(1.3); /* IE */
  -moz-transform: scale(1.3); /* FF */
  -webkit-transform: scale(1.3); /* Safari and Chrome */
  -o-transform: scale(1.3); /* Opera */
  padding: 10px;
}

</style>


<!-- Modal Add produk -->
<div class="modal fade" id="modal_add_produk" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"> Tambah Produk </h4>
      </div>
      <div class="modal-body">
        

		<div class="row-fluid">
			<div class="span12" style="font-size: 15px;">
				<div class="control-group" style="margin-left: 10px;">
					<label class="control-label"> <b style="font-size: 14px;"> Kode Produk </b> </label>
					<div class="controls">
						<input type="text" class="span12" value="" name="kode_produk_add" id="kode_produk_add">
					</div>
				</div>

				<div class="control-group" style="margin-left: 10px;">
					<label class="control-label"> <b style="font-size: 14px;"> Nama Produk </b> </label>
					<div class="controls">
						<input type="text" class="span12" value="" name="nama_produk_add" id="nama_produk_add">
					</div>
				</div>

				<div class="control-group" style="margin-left: 10px;">
					<label class="control-label"> <b style="font-size: 14px;"> Satuan </b> </label>
					<div class="controls">
						<input type="text" class="span12" value="" name="satuan_add" id="satuan_add">
					</div>
				</div>

				<div class="control-group" style="margin-left: 10px;">
					<label class="control-label"> <b style="font-size: 14px;"> Deskripsi </b> </label>
						<div class="controls">
							<textarea rows="4" id="deskripsi_add" name="deskripsi_add" style="resize:none; height: 87px; width: 400px;"></textarea>
						</div>
				</div> 
			</div>
		</div>

      </div>
      <div class="modal-footer">
        <button id="tutup_add_produk" type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
        <button type="button" class="btn btn-success" onclick="simpan_add_produk();"> Simpan Produk </button>
      </div>
    </div>
  </div>
</div>


<input id="tr_utama_count" value="1" type="hidden"/>
<div class="row-fluid ">
	<div class="span12">
		<div class="primary-head">
			<h3 class="page-header"> <i class="icon-shopping-cart"></i>  Buat Belanja Baru </h3>
		</div>
		<ul class="breadcrumb">
			<li><a href="#" class="icon-home"></a><span class="divider "><i class="icon-angle-right"></i></span></li>
			<li><a href="#">Master Data</a><span class="divider"><i class="icon-angle-right"></i></span></li>
			<li> Belanja Harian <span class="divider"><i class="icon-angle-right"></i></span></li>
			<li class="active"> Buat Belanja Baru </li>
		</ul>
	</div>
</div>

<form action="<?=base_url().$post_url;?>" method="post">

<div class="breadcrumb" style="background:#E0F7FF;">
	<div class="control-group">
		<label class="control-label"> <b style="font-size: 14px;"> Supplier </b> </label>
		<div class="controls">
			<div class="input-append">
				<input type="text" id="pelanggan" name="pelanggan" readonly style="background:#FFF;">
				<input type="hidden" id="pelanggan_sel" name="pelanggan_sel" readonly style="background:#FFF;">
				<button onclick="show_pop_pelanggan();" type="button" class="btn">Cari</button>
			</div>
		</div>
	</div>
</div>

<div class="row-fluid" style="background: #F5EADA; padding-top: 15px; padding-bottom: 15px;">
	<div class="span4">
		<div class="control-group" style="margin-left: 20px;">
			<label class="control-label"> <b style="font-size: 14px;"> Alamat Supplier </b> </label>
				<div class="controls">
					<textarea rows="4" id="alamat_tagih" name="alamat_tagih" style="resize:none; height: 87px; width: 324px;"></textarea>
				</div>
		</div>
	</div>


	<div class="span3">
		<div class="control-group" style="margin-left: 10px;">
			<label class="control-label"> <b style="font-size: 14px;"> Tanggal Transaksi </b> </label>
				<div class="controls">
					<div id="datetimepicker1" class="input-append date ">
						<input value="<?=date('d-m-Y');?>" required name="tgl_trx" onclick="$('#add_on_pick').click();" data-format="dd-MM-yyyy" type="text"><span class="add-on "><i id="add_on_pick" data-time-icon="icon-time" data-date-icon="icon-calendar"></i></span>
					</div>
				</div>
		</div>

		<div class="control-group" style="margin-left: 10px;">
			<label class="control-label"> <b style="font-size: 14px;"> No. Transaksi </b> </label>
			<div class="controls">
				<input readonly type="text" class="span12" value="OUT-<?=$no_transaksi;?>" name="no_trx" id="no_trx">
				<input type="hidden" class="span8" value="<?=$no_transaksi;?>" name="no_trx2" id="no_trx2">
			</div>
		</div>
	</div>


	<div class="span4">
		<div class="control-group">
			<label class="control-label"> <b style="font-size: 14px;"> Uraian </b> </label>
				<div class="controls">
					<textarea rows="4" id="memo_lunas" name="memo_lunas" style="resize:none; height: 87px; width: 324px;"></textarea>
				</div>
		</div> 
	</div>
</div>


<div class="row-fluid" id="view_data">
	<div class="span12">
		<div class="content-widgets light-gray">
			<div class="widget-head orange">
				<h3> </h3>
			</div>
			<div class="widget-container">
				<!-- <button style="margin-bottom: 15px;" data-toggle='modal' data-target='#modal_add_produk' type="button" class="btn btn-danger"><i class="icon-hdd"></i> Tambah Produk </button> -->
				<table class="stat-table table table-hover">
					<thead>
						<tr>
							<th align="center"> Produk / Aset </th>
							<th align="center"> Kuantitas </th>
							<th align="center"> Satuan </th>
							<th align="center"> Harga Satuan (Rp) </th>
							<th align="center"> Jumlah (Rp) </th>
							<th align="center"> Aksi</th>
						</tr>						
					</thead>
					<tbody id="tes">
						<tr id="tr_1" class="tr_utama">
							<td align="center" style="vertical-align:middle;"> 	
								<div class="control-group">
									<div class="controls">
										<div class="input-append">
											<input readonly type="text" id="nama_produk_1" name="nama_produk[]" required style="background:#FFF;">
											<input type="hidden" id="id_produk_1" name="produk[]" readonly style="background:#FFF;" value="0">
											<button onclick="show_pop_produk(1);" type="button" class="btn">Cari</button>
										</div>
									</div>
								</div>
							</td>

							<td align="center" style="vertical-align:middle;"> 
								<div class="controls">
									<input onkeyup="always_one(1);" onchange="always_one(1); hitung_total(1);" id="qty_1" style="font-size: 18px; text-align:center; width: 80px;" type="number"  value="1" name="qty[]">
								</div>
							</td>

							<td align="center" style="vertical-align:middle;"> 
								<div class="controls">
									<input style="font-size: 18px; text-align:left; width: 90px;" type="text"  value="" name="satuan[]" id="satuan_1">
								</div>
							</td>

							<td align="center" style="vertical-align:middle;"> 
								<div class="controls">
									<input required onkeyup="FormatCurrency(this); hitung_total(1);" style="font-size: 18px; text-align:right; width: 175px;" type="text"  value="" name="harga_satuan[]" id="harga_satuan_1">
								</div>
							</td>

							<td align="center" style="vertical-align:middle;"> 
								<div class="controls">
									<input readonly onkeyup="FormatCurrency(this);" style="font-size: 18px; text-align:right; width: 175px; background:#FFF;" type="text"  value="" name="jumlah[]" id="jumlah_1">
								</div>
							</td>

							<td style='background:#FFF; text-align:center; vertical-align: middle;'> 
								<button onclick="hapus_row_pertama();" type="button" class="btn btn-danger"> Hapus </button>
							</td>
						</tr>
					</tbody>
				</table>

				<button style="margin-bottom: 15px;" onclick="tambah_data();" type="button" class="btn btn-info"><i class="icon-plus"></i> Tambah Data </button>

			</div>
		</div>
	</div>
</div>

<div class="row-fluid" id="view_data">
	<div class="span12">
		<div class="content-widgets light-gray">
			<div class="widget-head orange">
				<h3> </h3>
			</div>
			<div class="widget-container">
				<div class="row-fluid">
					<div style="margin-bottom: 15px;" class="span3">
						<h4> Sub Total :</h4> 
					</div>

					<div style="margin-bottom: 15px;" class="span4">
						<h4 id="subtotal_txt"> Rp. 0.00 </h4> 
					</div>

					<div style="margin-bottom: 15px;" class="span4">
						<div class="controls">
							<label class="checkbox" style="font-size: 16px;">
							<input type="checkbox" checked name="is_lunas" id="is_lunas" value="" onclick="cek_islunas();">
							Sudah Membayar</label>
						</div>
					</div>


					
				</div>

				<div class="row-fluid">
					<div class="span3">
						<div class="control-group">
							<label class="control-label"> <b style="font-size: 14px;"> Pajak </b> </label>
								<div class="controls">
									<select required data-placeholder="Pilih Pajak ..." class="chzn-select" tabindex="2" style="width:150px;" name="id_pajak" onchange="hitung_pajak(this.value);">
											<option value="0" style="font-weight:bold;"> Tanpa Pajak </option>
										<?PHP foreach ($get_pajak as $key => $pajak) { ?>
											<option value="<?=$pajak->ID;?>"> <?=$pajak->NAMA_PAJAK;?> </option>
										<?PHP } ?>				
									</select>
									<input name="pajak_prosen" id="pajak_prosen" type="hidden" value="0" />
									<input name="kode_akun_pajak" id="kode_akun_pajak" type="hidden" value="" />
								</div>
						</div>
					</div>

					<div class="span4" style="margin-top: 18px;">
						<input style="font-size: 16px;" type="text" name="pajak_all" id="pajak_all" onkeyup="FormatCurrency(this); hitung_total_semua();"  placeholder="Isikan nilai pajak" /> 
					</div>

					<div class="span4" id="hutang_row" style="display:none;">
						<div class="control-group">
							<label class="control-label"> <b style="font-size: 14px;"> Pilih Kategori Hutang </b> </label>
							<div class="controls">
								<select class="span12" name="akun_hutang">
									<option value="2-2000"> Hutang Usaha </option>
									<option value="2-2030"> Hutang Lainnya </option>
								</select>
							</div>
						</div>
					</div>
				</div>

				<div class="row-fluid" style="margin-top: 10px;">
					<div style="margin-bottom: 15px;" class="span3">
						<h4> Total :</h4> 
					</div>

					<div style="margin-bottom: 15px;" class="span4">
						<h4 id="total_txt" style="color:green;"> Rp. 0.00 </h4> 
					</div>
				</div>

				

				<div class="form-actions">
					<center>
					<input type="hidden" name="sub_total" id="sub_total" value="" />
					
					<input type="hidden" name="total_all" id="total_all" value="" />
					<input type="hidden" name="sts_lunas" id="sts_lunas" value="1" />

					<input type="submit" value="Simpan Pembelian" name="simpan" class="btn btn-success">
					<button class="btn" onclick="window.location='<?=base_url();?>transaksi_pembelian_c' " type="button"> Batal dan Kembali </button>
					</center>
				</div>
			</div>
		</div>
	</div>
</div>

</form>

<!-- COPY ELEMENT -->
<div style="display:none;" id="copy_ag">
	<td align="center" style="vertical-align:middle;"> 
		<div class="control-group" id="copy_ag2">
				<div class="controls">
					<select  data-placeholder="Pilih Produk" class="chzn-select3" tabindex="2" style="width:300px;" name="produk[]" id="copy_select">
						<option value=""></option>
						<?PHP foreach ($get_all_produk as $key => $produk) { ?>
							<option value="<?=$produk->ID;?>"> <?=$produk->NAMA_PRODUK;?> - (<?=$produk->KODE_PRODUK;?>)  </option>
						<?PHP } ?>					
					</select>
				</div>
		</div>
	</td>
	<td align="center" style="vertical-align:middle;"> 
		<div class="controls">
			<textarea rows="2" class="span4" name="deskripsi[]"></textarea>
		</div>
	</td>
	<td align="center" style="vertical-align:middle;"> 
		<div class="controls">
			<input onkeyup="FormatCurrency(this);" style="font-size: 18px;" type="text" class="span3" value="" name="nilai[]">
		</div>
	</td>
</div>
<!-- END COPY ELEMENT -->

<script type="text/javascript">

function hapus_row_pertama(){
	$('#nama_produk_1').val('');
	$('#id_produk_1').val('');
	$('#qty_1').val('');
	$('#satuan_1').val('');
	$('#harga_satuan_1').val('');
	$('#jumlah_1').val('');

	hitung_total_semua();
}

function show_pop_produk(no){
	$('#popup_koang').remove();
	get_popup_produk();
    ajax_produk(no);
}

function get_popup_produk(){
    var base_url = '<?php echo base_url(); ?>';
    var $isi = '<div id="popup_koang">'+
                '<div class="window_koang">'+
                '    <a href="javascript:void(0);"><img src="'+base_url+'ico/cancel.gif" id="pojok_koang"></a>'+
                '    <div class="panel-body">'+
                '    <input style="width: 95%;" type="text" name="search_koang_pro" id="search_koang_pro" class="form-control" value="" placeholder="Cari Produk...">'+
                '    <div class="table-responsive">'+
                '            <table class="table table-hover2" id="tes5">'+
                '                <thead>'+
                '                    <tr>'+
                '                        <th>NO</th>'+
                '                        <th> KODE PRODUK / ASET </th>'+
                '                        <th style="white-space:nowrap;"> NAMA PRODUK / ASET </th>'+
                '                        <th> JENIS </th>'+
                '                    </tr>'+
                '                </thead>'+
                '                <tbody>'+
            
                '                </tbody>'+
                '            </table>'+
                '        </div>'+
                '    </div>'+
                '</div>'+
            '</div>';
    $('body').append($isi);

    $('#pojok_koang').click(function(){
        $('#popup_koang').css('display','none');
        $('#popup_koang').hide();
    });

    $('#popup_koang').css('display','block');
    $('#popup_koang').show();
}

function ajax_produk(id_form){
    var keyword = $('#search_koang_pro').val();
    $.ajax({
        url : '<?php echo base_url(); ?>transaksi_pembelian_c/get_produk_popup',
        type : "POST",
        dataType : "json",
        data : {
            keyword : keyword,
        },
        success : function(result){
            var isine = '';
            var no = 0;
            var tipe_data = "";
            $.each(result,function(i,res){
                no++;
                isine += '<tr onclick="get_produk_detail(\'' +res.ID+ '\',\'' +id_form+ '\');" style="cursor:pointer;">'+
                            '<td align="center">'+no+'</td>'+
                            '<td align="center">'+res.KODE_ALAT+'</td>'+
                            '<td align="left">'+res.NAMA_ALAT+'</td>'+
                            '<td align="center">'+res.JENIS_ALAT+'</td>'+
                        '</tr>';
            });

            if(result.length == 0){
            	isine = "<tr><td colspan='4' style='text-align:center'><b style='font-size: 15px;'> Data tidak tersedia </b></td></tr>";
            }

            $('#tes5 tbody').html(isine); 
            $('#search_koang_pro').off('keyup').keyup(function(){
                ajax_produk();
            });
        }
    });
}

function get_produk_detail(id, no_form){
	var id_produk = id;
    $.ajax({
		url : '<?php echo base_url(); ?>transaksi_pembelian_c/get_produk_detail',
		data : {id_produk:id_produk},
		type : "GET",
		dataType : "json",
		success : function(result){
			$('#qty_'+no_form).val(1);
			$('#id_produk_'+no_form).val(id_produk);
			// $('#satuan_'+no_form).val(result.SATUAN);
			$('#nama_produk_'+no_form).val(result.NAMA_ALAT);
			$('#satuan_'+no_form).focus();

			$('#search_koang_pro').val("");
		    $('#popup_koang').css('display','none');
		    $('#popup_koang').hide()
		}
	});
}


function show_pop_pelanggan(id){
	$('#popup_koang').remove();
    get_popup_pelanggan();
    ajax_pelanggan();
}

function get_popup_pelanggan(){
    var base_url = '<?php echo base_url(); ?>';
    var $isi = '<div id="popup_koang">'+
                '<div class="window_koang">'+
                '    <a href="javascript:void(0);"><img src="'+base_url+'ico/cancel.gif" id="pojok_koang"></a>'+
                '    <div class="panel-body">'+
                '    <input style="width: 95%;" type="text" name="search_koang" id="search_koang" class="form-control" value="" placeholder="Cari Supplier...">'+
                '    <div class="table-responsive">'+
                '            <table class="table table-hover2" id="tes5">'+
                '                <thead>'+
                '                    <tr>'+
                '                        <th>NO</th>'+
                '                        <th style="white-space:nowrap;"> NAMA SUPPLIER / PERUSAHAAN </th>'+
                '                        <th> ALAMAT </th>'+
                '                    </tr>'+
                '                </thead>'+
                '                <tbody>'+
            
                '                </tbody>'+
                '            </table>'+
                '        </div>'+
                '    </div>'+
                '</div>'+
            '</div>';
    $('body').append($isi);

    $('#pojok_koang').click(function(){
        $('#popup_koang').css('display','none');
        $('#popup_koang').hide();
    });

    $('#popup_koang').css('display','block');
    $('#popup_koang').show();
}

function ajax_pelanggan(){
    var keyword = $('#search_koang').val();
    $.ajax({
        url : '<?php echo base_url(); ?>transaksi_pembelian_c/get_pelanggan_popup',
        type : "POST",
        dataType : "json",
        data : {
            keyword : keyword,
        },
        success : function(result){
            var isine = '';
            var no = 0;
            var tipe_data = "";
            $.each(result,function(i,res){
                no++;
                nama_pel = res.NAMA_SUPPLIER;

                isine += '<tr onclick="get_supplier_detail('+res.ID+');" style="cursor:pointer;">'+
                            '<td align="center">'+no+'</td>'+
                            '<td align="center">'+nama_pel+'</td>'+
                            '<td align="center">'+res.ALAMAT+'</td>'+
                        '</tr>';
            });

            if(result.length == 0){
            	isine = "<tr><td colspan='3' style='text-align:center'><b style='font-size: 15px;'> Data tidak tersedia </b></td></tr>";
            }

            $('#tes5 tbody').html(isine); 
            $('#search_koang').off('keyup').keyup(function(){
                ajax_pelanggan();
            });
        }
    });
}

function get_supplier_detail(id_supplier){
	$('#popup_load').show();
	$.ajax({
		url : '<?php echo base_url(); ?>transaksi_pembelian_c/get_supplier_detail',
		data : {id_supplier:id_supplier},
		type : "POST",
		dataType : "json",
		success : function(result){
			$('#popup_load').hide();
			$('#alamat_tagih').val(result.ALAMAT);
			$('#pelanggan').val(result.NAMA_SUPPLIER);
			$('#pelanggan_sel').val(id_supplier);

			$('#search_koang').val("");
		    $('#popup_koang').css('display','none');
		    $('#popup_koang').hide();
		}
	});
}


function cek_islunas(){
	if($("#is_lunas").is(':checked')){
	    $('#hutang_row').hide(); 
	    $('#sts_lunas').val(1); 
	} else {
	    $('#hutang_row').show(); 
	    $('#sts_lunas').val(0); 
	}
}

function hitung_total(id){

	var qty = $('#qty_'+id).val();
	var harga_satuan = $('#harga_satuan_'+id).val();
	harga_satuan = harga_satuan.split(',').join('');

	if(harga_satuan == "" || harga_satuan == null){
		harga_satuan = 0;
	}

	var total = parseFloat(qty) * parseFloat(harga_satuan);
	$('#jumlah_'+id).val(NumberToMoney(total).split('.00').join(''));

	hitung_total_semua();
}


function always_one(id){
	var a = $('#qty_'+id).val();
	if(a <= 0){
		$('#qty_'+id).val(1);
	}
}

function tambah_data() {
	var value =$('#copy_select').html();
	var jml_tr = $('#tr_utama_count').val();
	var i = parseInt(jml_tr) + 1;

	$isi_1 = 
	'<tr id="tr_'+i+'" class="tr_utama">'+
		'<td class="center" style="vertical-align:middle;" id="td_chos_'+i+'">'+
			'<div class="control-group">'+
				'<div class="controls">'+
					'<div class="input-append">'+
						'<input type="text" id="nama_produk_'+i+'" name="nama_produk[]" required style="background:#FFF;">'+
						'<input type="hidden" id="id_produk_'+i+'" name="produk[]" readonly style="background:#FFF;">'+
						'<button onclick="show_pop_produk('+i+');" type="button" class="btn">Cari</button>'+
					'</div>'+
				'</div>'+
			'</div>'+
		'</td>'+

		'<td class="center" align="center" style="vertical-align:middle;"> '+
			'<div class="controls">'+
				'<input onkeyup="always_one('+i+');" onchange="always_one('+i+'); hitung_total('+i+');" style="font-size: 18px; text-align:center; width: 80px;" type="number"  value="0" name="qty[]" id="qty_'+i+'">'+
			'</div>'+
		'</td>'+

		'<td class="center" align="center" style="vertical-align:middle;"> '+
			'<div class="controls">'+
				'<input style="font-size: 18px; text-align:left; width: 90px;" type="text"  value="" name="satuan[]" id="satuan_'+i+'">'+
			'</div>'+
		'</td>'+

		'<td class="center" align="center" style="vertical-align:middle;"> '+
			'<div class="controls">'+
				'<input required onkeyup="FormatCurrency(this); hitung_total('+i+');" style="font-size: 18px; text-align:right; width: 175px;" type="text"  value="" name="harga_satuan[]" id="harga_satuan_'+i+'">'+
			'</div>'+
		'</td>'+

		'<td class="center" align="center" style="vertical-align:middle;"> '+
			'<div class="controls">'+
				'<input readonly onkeyup="FormatCurrency(this);" style="font-size: 18px; text-align:right; width: 175px; background:#FFF;" type="text"  value="" name="jumlah[]" id="jumlah_'+i+'">'+
			'</div>'+
		'</td>'+

		'<td class="center" style="background:#FFF; text-align:center;">'+
			'<button onclick="hapus_row('+i+');" type="button" class="btn btn-danger"> Hapus </button>'+
		'</td>'+
	'</tr>';

	$('#tes').append($isi_1);

	$('#tr_utama_count').val(i);

}




function hitung_total_semua(){
	var sum = 0;
	var pajak_prosen = $('#pajak_prosen').val();
	console.log(pajak_prosen);
	$("input[name='jumlah[]']").each(function(idx, elm) {
		var tot = elm.value.split(',').join('');
		if(tot > 0){
    		sum += parseFloat(tot);
		}
    });

	var pajak = $('#pajak_all').val().split(',').join('');
	if(pajak == ""){
		pajak = 0;
	}

	var total_all = parseFloat(sum) + parseFloat(pajak) ;

    $('#sub_total').val(sum);
    //$('#pajak_all').val(pajak);
    $('#total_all').val(total_all);

    $('#pajak_txt').html('Rp. '+NumberToMoney(pajak));
    $('#subtotal_txt').html('Rp. '+NumberToMoney(sum));
    $('#total_txt').html('Rp. '+NumberToMoney(total_all));
}

function hitung_pajak(id_pajak){
	$('#popup_load').show();
	if(id_pajak > 0){
		$.ajax({
			url : '<?php echo base_url(); ?>transaksi_penjualan_c/get_pajak_prosen',
			data : {id_pajak:id_pajak},
			type : "GET",
			dataType : "json",
			success : function(result){
				$('#pajak_prosen').val(result.PROSEN);
				$('#kode_akun_pajak').val(result.PAJAK_PEMBELIAN);
				hitung_total_semua();
				$('#popup_load').hide();
			}
		});
	} else {
		$('#pajak_prosen').val(0);
		$('#kode_akun_pajak').val('');
		hitung_total_semua();
		$('#popup_load').hide();
	}

	
}

function simpan_add_produk(){
	var kode_produk = $('#kode_produk_add').val();
	var nama_produk = $('#nama_produk_add').val();
	var satuan 		= $('#satuan_add').val();
	var deskripsi   = $('#deskripsi_add').val();

	if(kode_produk == ""){
		alert("Kode Produk Harus di isi.");
	} else if(nama_produk == ""){
		alert("Nama Produk Harus di isi.");
	} else if(satuan == ""){
		alert("Satuan Produk Harus di isi.");
	} else {
		$.ajax({
			url : '<?php echo base_url(); ?>transaksi_pembelian_c/simpan_add_produk',
			data : {
				kode_produk:kode_produk,
				nama_produk:nama_produk,
				satuan:satuan,
				deskripsi:deskripsi,
			},
			type : "POST",
			dataType : "json",
			success : function(result){
				$('#tutup_add_produk').click();
				$.gritter.add({
		            title: 'Notifikasi',
		            position: 'bottom-right',
		            text: 'Data Produk berhasil terimpan.'
		        });
		        return false;
			}
		});
	}

}

function hapus_row (id) {
	$('#tr_'+id).remove();
	hitung_total_semua();
}

</script>
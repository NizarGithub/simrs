<style type="text/css">

input[type=checkbox]
{
  /* Double-sized Checkboxes */
  -ms-transform: scale(1.5); /* IE */
  -moz-transform: scale(1.5); /* FF */
  -webkit-transform: scale(2); /* Safari and Chrome */
  -o-transform: scale(2); /* Opera */
  padding: 10px;
}

</style>

<div class="row-fluid ">
	<div class="span12">
		<div class="primary-head">
			<h3 class="page-header"> <i class="icon-pencil"></i>  Detail Transaksi Penjualan </h3>
			<button style="margin-top: -10px; margin-bottom: 13px;" onclick="window.location='<?=base_url();?>transaksi_penjualan_c'" type="button" class="btn btn-danger">
				<i class="icon-arrow-left"></i> Kembali 
			</button>


		</div>
		<ul class="breadcrumb">
			<li><a href="#" class="icon-home"></a><span class="divider "><i class="icon-angle-right"></i></span></li>
			<li><a href="#">Master Data</a><span class="divider"><i class="icon-angle-right"></i></span></li>
			<li> Transaksi Penjualan <span class="divider"><i class="icon-angle-right"></i></span></li>
			<li class="active"> Detail </li>
		</ul>
	</div>
</div>

<form action="<?=base_url().$post_url;?>" method="post">

<div class="breadcrumb">

	<div class="control-group">
		<label class="control-label">  Pelanggan </label>
		<div class="controls">
			<label class="control-label"> <b style="font-size: 14px;"> <?=$dt->PELANGGAN;?> </b> </label>
		</div>
	</div>
</div>

<div class="row-fluid" style="background: #F5EADA; padding-top: 15px; padding-bottom: 15px;">
	<div class="span4">
		<div class="control-group" style="margin-left: 20px;">
			<label class="control-label">  Alamat Penagihan </label>
				<div class="controls">
					<label class="control-label"> <b style="font-size: 14px;"> <?=$dt->ALAMAT;?> </b> </label>
				</div>
		</div>
	</div>


	<div class="span3">
		<div class="control-group" style="margin-left: 10px;">
			<label class="control-label">  Tanggal Transaksi  </label>
				<div class="controls">
					<label class="control-label"> <b style="font-size: 14px;"> <?=$dt->TGL_TRX;?> </b> </label>
				</div>
		</div>

		<div class="control-group" style="margin-left: 10px;">
			<label class="control-label">  No. Transaksi  </label>
			<div class="controls">
				<label class="control-label"> <b style="font-size: 14px;"> <?=$dt->NO_BUKTI;?> </b> </label>
			</div>
		</div>
	</div>


	<div class="span4">
		<div class="control-group">
			<label class="control-label">  Uraian </label>
				<div class="controls">
					<label class="control-label"> <b style="font-size: 14px;"> <?=$dt->MEMO;?> </b> </label>
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

				<table class="stat-table table table-hover">
					<thead>
						<tr>
							<th align="center"> Produk / Item </th>
							<th align="center"> Kuantitas </th>
							<th align="center"> Satuan </th>
							<th align="center"> Harga Satuan (Rp) </th>
							<th align="center"> Jumlah (Rp) </th>
						</tr>						
					</thead>
					<tbody id="tes">
						<?PHP 
						$no = 0;
						foreach ($dt_detail as $key => $det) { 
							$no++;
						?>
						<tr id="tr_<?=$det->ID;?>" class="tr_utama">
							<td style="vertical-align:middle;"> 

								<div class="control-group">
									<label class="control-label"> <b style="font-size: 14px;"> <?=$det->NAMA_PRODUK;?> </b> </label>
								</div>

							</td>

							<td align="center" style="vertical-align:middle;">
								<div class="control-group">
									<label class="control-label"> <b style="font-size: 14px;"> <?=$det->QTY;?> </b> </label>
								</div>
							</td>

							<td align="center" style="vertical-align:middle;"> 
								<div class="control-group">
									<label class="control-label"> <b style="font-size: 14px;"> <?=$det->SATUAN;?> </b> </label>
								</div>
							</td>

							<td align="center" style="vertical-align:middle;"> 
								<div class="control-group">
									<label class="control-label"> <b style="font-size: 14px;"> <?=number_format($det->HARGA_SATUAN);?> </b> </label>
								</div>
							</td>

							<td align="center" style="vertical-align:middle;"> 
								<div class="control-group">
									<label class="control-label"> <b style="font-size: 14px;"> <?=number_format($det->TOTAL);?> </b> </label>
								</div>
							</td>

						</tr>
						<?PHP } ?>
					</tbody>
				</table>

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
						<h4> Rp. <?=number_format($dt->SUB_TOTAL);?> </h4> 
					</div>

					
				</div>

				<div class="row-fluid">
					<div class="span3">
						<div class="control-group">
							<label class="control-label"> <b style="font-size: 14px;"> Pajak </b> </label>
								<div class="controls">
									<select disabled data-placeholder="Pilih Pajak ..." class="chzn-select" tabindex="2" style="width:150px;" name="id_pajak" id="id_pajak_sel" onchange="hitung_pajak(this.value);">
											<option value="0" style="font-weight:bold;"> Tanpa Pajak </option>
										<?PHP 
											$sel_pajak = "";
											foreach ($get_pajak as $key => $pajak) { 
												if($pajak->ID == $dt->ID_PAJAK){
													$sel_pajak = "selected";
												} else {
													$sel_pajak = "";
												}
											?>
											<option <?=$sel_pajak;?> value="<?=$pajak->ID;?>"> <?=$pajak->NAMA_PAJAK;?> (<?=$pajak->PROSEN;?>%) </option>
										<?PHP } ?>				
									</select>
									<input name="pajak_prosen" id="pajak_prosen" type="hidden" value="0" />
									<input name="kode_akun_pajak" id="kode_akun_pajak" type="hidden" value="<?=$dt->KODE_AKUN_PAJAK;?>" />
								</div>
						</div>
					</div>

					<div class="span4" style="margin-top: 18px;">
						<h4> Rp. <?=number_format($dt->NILAI_PAJAK);?> </h4> 
					</div>

					<div class="span4" id="piutang_row" style="display:none;">
						<div class="control-group">
							<label class="control-label"> <b style="font-size: 14px;"> Pilih Kategori Piutang </b> </label>
							<div class="controls">
								<select class="span12" name="akun_piutang">
									<option <?PHP if($dt->KODE_AKUN_PIUTANG == '1-1200'){ echo "selected"; }?> value="1-1200"> Piutang Usaha </option>
									<option <?PHP if($dt->KODE_AKUN_PIUTANG == '1-1210'){ echo "selected"; }?> value="1-1210"> Piutang Lainnya </option>
								</select>
							</div>
						</div>
					</div>
				</div>

				<div class="row-fluid" style="margin-top: 10px;">
					<div style="margin-bottom: 15px;" class="span3">
						<h3> Total :</h3> 
					</div>

					<div style="margin-bottom: 15px;" class="span4">
						<h3 id="total_txt" style="color:green;"> Rp. <?=number_format($dt->TOTAL);?> </h3> 
					</div>
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


<input type="hidden" id="tmp_row" value="120"/>
<script type="text/javascript">

function show_pop_pelanggan(id){
    get_popup_pelanggan();
    ajax_pelanggan();
}

function get_popup_pelanggan(){
    var base_url = '<?php echo base_url(); ?>';
    var $isi = '<div id="popup_koang">'+
                '<div class="window_koang">'+
                '    <a href="javascript:void(0);"><img src="'+base_url+'ico/cancel.gif" id="pojok_koang"></a>'+
                '    <div class="panel-body">'+
                '    <input style="width: 95%;" type="text" name="search_koang" id="search_koang" class="form-control" value="" placeholder="Cari Pelanggan...">'+
                '    <div class="table-responsive">'+
                '            <table class="table table-hover2" id="tes5">'+
                '                <thead>'+
                '                    <tr>'+
                '                        <th>NO</th>'+
                '                        <th style="white-space:nowrap;"> NAMA PELANGGAN / PERUSAHAAN </th>'+
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
        url : '<?php echo base_url(); ?>transaksi_penjualan_c/get_pelanggan_popup',
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
                nama_pel = res.NAMA_PELANGGAN;
                if(res.TIPE == "Perusahaan"){
                	nama_pel = res.NAMA_PELANGGAN+" <b> ("+res.NAMA_USAHA+")</b>";
                }

                isine += '<tr onclick="get_pelanggan_det('+res.ID+');" style="cursor:pointer;">'+
                            '<td align="center">'+no+'</td>'+
                            '<td align="center">'+nama_pel+'</td>'+
                            '<td align="center">'+res.ALAMAT_TAGIH+'</td>'+
                        '</tr>';
            });

            if(result.length == 0){
            	isine = "<tr><td colspan='5' style='text-align:center'><b style='font-size: 15px;'> Data tidak tersedia </b></td></tr>";
            }

            $('#tes5 tbody').html(isine); 
            $('#search_koang').off('keyup').keyup(function(){
                ajax_no_bukti();
            });
        }
    });
}

function show_pop_produk(no){
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
                '                        <th> KODE PRODUK </th>'+
                '                        <th style="white-space:nowrap;"> NAMA PRODUK </th>'+
                '                        <th> SATUAN </th>'+
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
        url : '<?php echo base_url(); ?>transaksi_penjualan_c/get_produk_popup',
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
                nama_pel = res.NAMA_PELANGGAN;
                if(res.TIPE == "Perusahaan"){
                	nama_pel = res.NAMA_PELANGGAN+" <b> ("+res.NAMA_USAHA+")</b>";
                }

                isine += '<tr onclick="get_produk_detail(\'' +res.ID+ '\',\'' +id_form+ '\');" style="cursor:pointer;">'+
                            '<td align="center">'+no+'</td>'+
                            '<td align="center">'+res.KODE_PRODUK+'</td>'+
                            '<td align="left">'+res.NAMA_PRODUK+'</td>'+
                            '<td align="center">'+res.SATUAN+'</td>'+
                        '</tr>';
            });

            if(result.length == 0){
            	isine = "<tr><td colspan='5' style='text-align:center'><b style='font-size: 15px;'> Data tidak tersedia </b></td></tr>";
            }

            $('#tes5 tbody').html(isine); 
            $('#search_koang').off('keyup').keyup(function(){
                ajax_no_bukti();
            });
        }
    });
}

function get_produk_detail(id, no_form){
    var id_produk = id;
    $.ajax({
		url : '<?php echo base_url(); ?>transaksi_penjualan_c/get_produk_detail',
		data : {id_produk:id_produk},
		type : "GET",
		dataType : "json",
		success : function(result){
			$('#qty_'+no_form).val(1);
			$('#id_produk_'+no_form).val(id_produk);
			$('#satuan_'+no_form).val(result.SATUAN);
			$('#nama_produk_'+no_form).val(result.NAMA_PRODUK);
			$('#harga_satuan_'+no_form).focus();

			$('#search_koang_pro').val("");
		    $('#popup_koang').css('display','none');
		    $('#popup_koang').hide();
		}
	});
}


function cek_islunas(){
	if($("#is_lunas").is(':checked')){
	    $('#piutang_row').hide(); 
	    $('#sts_lunas').val(1); 
	} else {
	    $('#piutang_row').show(); 
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
	var jml_tr = $('#tmp_row').val();
	var i = parseInt(jml_tr) + 1;

	$isi_1 = 
	'<tr id="tr_'+i+'" class="tr_utama">'+
		'<td class="center" style="vertical-align:middle;" id="td_chos_'+i+'">'+
			'<div class="control-group">'+
				'<div class="controls">'+
					'<div class="input-append">'+
						'<input type="text" id="nama_produk_'+i+'" name="nama_produk[]" readonly style="background:#FFF;">'+
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

	$("#produk_"+i).html(value);

	$('#tmp_row').val(i);

	$(".chzn-select_"+i).chosen();



}



function get_pelanggan_det(id_pel){
	$('#popup_load').show();
	$.ajax({
		url : '<?php echo base_url(); ?>transaksi_penjualan_c/get_pelanggan_detail',
		data : {id_pel:id_pel},
		type : "GET",
		dataType : "json",
		success : function(result){
			$('#popup_load').hide();

			$('#search_koang').val("");
		    $('#popup_koang').css('display','none');
		    $('#popup_koang').hide();

			$('#alamat_tagih').val(result.ALAMAT_TAGIH);
			$('#pelanggan').val(result.NAMA_PELANGGAN);
			$('#pelanggan_sel').val(id_pel);
		}
	});
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
				$('#kode_akun_pajak').val(result.PAJAK_PENJUALAN);
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

function hapus_row (id) {
	$('#tr_'+id).remove();
	hitung_total_semua();
}
</script>
<?PHP 
$no_transaksi = 10001;
if($no_trx->NEXT != "" || $no_trx->NEXT != null ){
	$no_transaksi = $no_trx->NEXT+1;
}

?>

<div class="row-fluid ">
	<div class="span12">
		<div class="primary-head">
			<h3 class="page-header"> <i class="icon-money"></i>  Kas & Bank (Transfer Uang Antar Bank) </h3>
			<button style="margin-top: -10px; margin-bottom: 13px;" onclick="window.location='<?=base_url();?>kas_bank_c'" type="button" class="btn btn-danger">
				<i class="icon-arrow-left"></i> Kembali 
			</button>


		</div>
		<ul class="breadcrumb">
			<li><a href="#" class="icon-home"></a><span class="divider "><i class="icon-angle-right"></i></span></li>
			<li><a href="#">Master Data</a><span class="divider"><i class="icon-angle-right"></i></span></li>
			<li> Kas & Bank <span class="divider"><i class="icon-angle-right"></i></span></li>
			<li class="active"> Transfer Uang </li>
		</ul>
	</div>
</div>

<form action="<?=base_url().$post_url;?>" method="post">

<div class="breadcrumb" style="background:#3498DB;">
	<div class="control-group" style="float: left; width: 35%;">
		<label class="control-label"> <b style="font-size: 14px; color:#FFF;"> Transfer Dari </b> </label>
			<div class="controls">
				<select  required  class="chzn-select" tabindex="2" style="width:300px;" name="trf_dari">
					<?PHP foreach ($list_akun as $key => $akun) { ?>
						<option value="<?=$akun->KODE_AKUN;?>"> (<?=$akun->KODE_AKUN;?>) - <?=$akun->NAMA_AKUN;?> (<?=$akun->KATEGORI;?>) </option>
					<?PHP } ?>				
				</select>
			</div>
	</div>

	<div class="control-group" style="float: left; width: 35%;">
		<label class="control-label"> <b style="font-size: 14px; color:#FFF;"> Setor Ke </b> </label>
			<div class="controls">
				<select  required  class="chzn-select" tabindex="2" style="width:300px;" name="setor_ke">
					<?PHP foreach ($list_akun as $key => $akun) { ?>
						<option value="<?=$akun->KODE_AKUN;?>"> (<?=$akun->KODE_AKUN;?>) - <?=$akun->NAMA_AKUN;?> (<?=$akun->KATEGORI;?>) </option>
					<?PHP } ?>				
				</select>
			</div>
	</div>

	<div class="control-group">
		<label class="control-label"> <b style="font-size: 14px; color:#FFF;"> Nilai Transfer </b> </label>
			<div class="controls">
				<input required type="text" style="font-size: 16px;" onkeyup="FormatCurrency(this);" value="" name="nilai_trf" id="nilai_trf" placeholder="0.00">
			</div>
	</div>
</div>

<div class="row-fluid" style="background: #F5EADA; padding-top: 15px; padding-bottom: 15px;">

	<div class="span4">
		<div class="control-group" style="margin-left: 20px;">
			<label class="control-label"> <b style="font-size: 14px;"> Tanggal Transaksi </b> </label>
				<div class="controls">
					<div id="datetimepicker1" class="input-append date ">
						<input value="<?=date('d-m-Y');?>" required name="tgl_trx" onclick="$('#add_on_pick').click();" data-format="dd-MM-yyyy" type="text"><span class="add-on "><i id="add_on_pick" data-time-icon="icon-time" data-date-icon="icon-calendar"></i></span>
					</div>
				</div>
		</div>
	</div>


	<div class="span3">
		<div class="control-group" style="margin-left: 10px;">
			<label class="control-label"> <b style="font-size: 14px;"> No. Transaksi </b> </label>
			<div class="controls">
				<input style="background:#FFF;" readonly type="text" class="span8" value="BANKTRF-<?=$no_transaksi;?>" name="no_trx" id="no_trx">
				<input type="hidden" class="span8" value="<?=$no_transaksi;?>" name="no_trx2" id="no_trx2">
			</div>
		</div>
	</div>

	<div class="span4">
		<div class="control-group" style="margin-left: 10px;">
			<label class="control-label"> <b style="font-size: 14px;"> Memo </b> </label>
				<div class="controls">
					<textarea rows="3" name="memo" style="height: 80px; width: 374px; resize:none;"></textarea>
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
				<div class="form-actions">
					<center>
					<input type="hidden" name="total_all" id="total_all" value="" />
					<input type="submit" value="Simpan" name="simpan" class="btn btn-success">
					<button class="btn" onclick="window.location='<?=base_url();?>kas_bank_c' " type="button"> Batal dan Kembali </button>
					</center>
				</div>
			</div>
		</div>
	</div>
</div>

</form>


<script type="text/javascript">
function tambah_data() {
	var value =$('#copy_select').html();
	var jml_tr = $('.tr_utama').length;
	var i = parseInt(jml_tr) + 1;

	$isi_1 = 
	'<tr id="tr_'+i+'" class="tr_utama">'+
		'<td style="vertical-align:middle;" id="td_chos_'+i+'">'+
			'<select  data-placeholder="Pilih ..." class="chzn-select_'+i+'" tabindex="2" style="width:300px;" name="kode_akun[]" id="sel_chos_'+i+'">'+
			'</select>'+
		'</td>'+
		'<td align="center" style="vertical-align:middle;"> '+
			'<div class="controls">'+
				'<textarea rows="2" style="width:400px;"  name="deskripsi[]"></textarea>'+
			'</div>'+
		'</td>'+
		'<td align="center" style="vertical-align:middle;"> '+
			'<div class="controls">'+
				'<input required onkeyup="FormatCurrency(this); hitung_total();" style="font-size: 18px; text-align:right;" type="text"  value="0" name="nilai[]">'+
			'</div>'+
		'</td>'+
	'</tr>';

	$('#tes').append($isi_1);

	$("#sel_chos_"+i).html(value);

	$(".chzn-select_"+i).chosen();
    $(".chzn-select-deselect").chosen({
        allow_single_deselect: true
    });


}

function hitung_total(){
	var sum = 0;
	$("input[name='nilai[]']").each(function(idx, elm) {
		var tot = elm.value.split(',').join('');
		if(tot > 0){
    		sum += parseFloat(tot);
		}
    });
    $('#total_all').val(sum);
    $('#total_txt').html('Rp. '+NumberToMoney(sum));
}

</script>
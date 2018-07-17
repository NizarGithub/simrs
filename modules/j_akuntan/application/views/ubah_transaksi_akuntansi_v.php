<?PHP 
$no_transaksi = 1;
$no_transaksi2 = 1;
if($get_no_trx_akun->NEXT != "" || $get_no_trx_akun->NEXT != null ){
	$no_transaksi = $get_no_trx_akun->NEXT+1;
	$no_transaksi2 = $get_no_trx_akun->NEXT+1;
}

$no_transaksi = str_pad($no_transaksi, 3, '0', STR_PAD_LEFT);

?>

<style type="text/css">

input[type=checkbox]
{
  /* Double-sized Checkboxes */
  -ms-transform: scale(1); /* IE */
  -moz-transform: scale(1); /* FF */
  -webkit-transform: scale(1.5); /* Safari and Chrome */
  -o-transform: scale(1.5); /* Opera */
  padding: 10px;
}


</style>



<form class="" method="post" action="<?=base_url().$post_url;?>" onsubmit = "return cek_balance();";>

<input type="hidden" id="total_debet_all" name="total_debet_all" value="0">
<input type="hidden" id="total_kredit_all" name="total_kredit_all" value="0">
<input type="hidden" id="no_trx_akun2" name="no_trx_akun2" value="<?=$no_transaksi2;?>">

<div class="row-fluid ">
	<div class="span12">
		<div class="primary-head">
			<h3 class="page-header"> <i class="icon-pencil"></i>  Ubah Transaksi Akuntansi </h3>


		</div>
		<ul class="breadcrumb">
			<li><a href="#" class="icon-home"></a><span class="divider "><i class="icon-angle-right"></i></span></li>
			<li><a href="#">Input Akuntansi</a><span class="divider"><i class="icon-angle-right"></i></span></li>
			<li class="active"> Ubah Transaksi Akuntansi </li>
		</ul>
	</div>
</div>

<div class="breadcrumb">
	<center>
		<button onclick="window.location='<?=base_url();?>input_transaksi_akuntansi_c';" class="btn btn-info" type="button"> <i class="icon-plus-sign"></i> Input Transaksi Akuntansi </button>
	</center>
</div>


<div class="row-fluid form-horizontal" style="background: #F5EADA; padding-top: 15px; padding-bottom: 15px;">
	<div>
		<div class="span6">

			<div class="control-group">
				<label class="control-label"> <b style="font-size: 14px;"> No. Transaksi Akun</b> </label>
				<div class="controls">
					<div class="input-append">
						<input type="text" id="no_trx_akun" name="no_trx_akun" readonly style="background:#FFF;">
						<button onclick="show_pop_no_bukti();" type="button" class="btn">Cari</button>
					</div>
				</div>
			</div>

			<div class="control-group">
				<label class="control-label"> <b style="font-size: 14px;"> No. Bukti</b> </label>
				<div class="controls">
						<input type="text" id="no_bukti" name="no_bukti" value="" readonly style="background:#FFF;">
				</div>
			</div>			

			<div class="control-group">
				<label class="control-label"> <b style="font-size: 14px;"> Tanggal Transaksi </b> </label>
					<div class="controls">
						<div id="datetimepicker1" class="input-append date ">
							<input value="<?=date('d-m-Y');?>" readonly name="tgl_trx" id="tgl_trx" onclick="$('#add_on_pick').click();" data-format="dd-MM-yyyy" type="text"><span class="add-on "><i id="add_on_pick" data-time-icon="icon-time" data-date-icon="icon-calendar"></i></span>
						</div>
					</div>
			</div>

			<div class="control-group">
				<label class="control-label"> <b style="font-size: 14px;"> Tipe </b> </label>
				<div class="controls">
					<div class="input-append">
						<input type="text" id="tipe" name="tipe" value="" readonly>
					</div>
				</div>
			</div>

			<div class="control-group">
				<label class="control-label"> <b style="font-size: 14px;"> Pelanggan / Supplier </b> </label>
				<div class="controls">
					<div class="input-append">
						<input type="text" id="kontak" name="kontak" value="" readonly>
					</div>
				</div>
			</div>

			<div class="control-group">
				<label class="control-label"> <b style="font-size: 14px;"> Alamat </b> </label>
					<div class="controls">
						<textarea readonly rows="4" id="alamat_kontak" name="alamat_kontak" style="resize:none; height: 87px; width: 300px;"></textarea>
					</div>
			</div> 			
		</div>

		<div class="span6">
			<div class="control-group">
				<label class="control-label"> <b style="font-size: 14px;"> Pajak </b> </label>
				<div class="controls">
					<div class="input-append">
						<input type="text" id="pajak" name="pajak" value="" readonly>
					</div>
				</div>
			</div>

			<div class="control-group">
				<label class="control-label"> <b style="font-size: 14px;"> Nilai Pajak </b> </label>
				<div class="controls">
					<div class="input-append">
						<input type="text" id="nilai_pajak" name="nilai_pajak" value="" readonly>
					</div>
				</div>
			</div>

			<div class="control-group">
				<label class="control-label"> <b style="font-size: 14px;"> Nilai Total Transaksi </b> </label>
				<div class="controls">
					<div class="input-append">
						<input type="text" id="nilai_total" name="nilai_total" value="" readonly>
					</div>
				</div>
			</div>

			<div class="control-group">
				<label class="control-label"> <b style="font-size: 14px;"> Uraian </b> </label>
					<div class="controls">
						<textarea rows="4" id="uraian" name="uraian" style="resize:none; height: 87px; width: 300px;"></textarea>
					</div>
			</div>

			<div class="control-group">
				<label class="control-label"> <b style="font-size: 14px;"> Status </b> </label>
				<div class="controls">
					<label class="control-label" style="text-align:left;"> 
						<b id="lunas_sts" style="display:none; font-size: 17px; color:green;"> Lunas </b> 
						<b id="gak_lunas_sts" style="display:none; font-size: 17px; color:red;"> Belum Lunas </b> 
					</label>
				</div>
			</div>

		</div>
	</div>
</div>

<div style="margin-bottom: 15px;" class="span4">
	<div class="controls">
		<label class="checkbox" style="font-size: 16px;">
		<input type="checkbox" checked name="is_showed" id="is_showed" value="" onclick="show_detail();">
		Tampilkan Detail </label>
	</div>
</div>

<div class="row-fluid" style="background: #F5EADA; padding-top: 15px; padding-bottom: 15px;">
	<div class="span12" id="detail_view">
		<div class="widget-head orange">
			<h3> Detail Transaksi </h3>
		</div>
		<table class="stat-table table table-hover">
			<thead>
				<tr>
					<th align="center"> No </th>
					<th align="center"> Produk / Barang</th>
					<th align="center"> Kuantitas </th>
					<th align="center"> Satuan </th>
					<th align="center"> Harga Satuan (Rp) </th>
					<th align="center"> Jumlah (Rp) </th>
				</tr>						
			</thead>
			<tbody id="tes" style="font-size: 13px;">
				<tr> <td colspan="6" align="center"> <b> Silahkan pilih Nomor Bukti terlebih dahulu untuk melihat detail transaksi </b> </td> </tr>
			</tbody>

			<tfoot>
				<tr>
					<td style="background:#F1F1F1;" colspan="5" align="center"> <b style="font-size:14px;"> TOTAL </b> </td>
					<td style="background:#F1F1F1;" align="right" id="sub_total_trx_detail">  </td>
				</tr>
			</tfoot>
		</table>
	</div>
</div>

<div class="row-fluid" style="background: #F5EADA;">
	<div class="span3" style="margin-left: 20px;">
		<div class="control-group">
			    <label class="control-label"> <b style="font-size: 14px;"> Kode Akun </b> </label>
				<div class="controls">
					<select  data-placeholder="Pilih ..." class="chzn-select" tabindex="2" style="width:300px;" id="kode_akun_add" name="kode_akun_add">
						<?PHP foreach ($get_list_akun_all as $key => $akun_all) { ?>
							<option value="<?=$akun_all->KODE_AKUN;?>"> (<?=$akun_all->KODE_AKUN;?>) - <?=$akun_all->NAMA_AKUN;?></option>
						<?PHP } ?>				
					</select>
				</div>
		</div>
	</div>

	<div class="span3" style="margin-left: 80px;">
		<div class="control-group">
			<label class="control-label"> <b style="font-size: 14px;"> Debet </b> </label>
			<div class="controls">
				<div class="input-append">
					<input onkeyup="FormatCurrency(this);" type="text" id="debet_add" name="debet_add" value="">
				</div>
			</div>
		</div>
	</div>

	<div class="span3" style="margin-left: -10px;">
		<div class="control-group">
			<label class="control-label"> <b style="font-size: 14px;"> Kredit </b> </label>
			<div class="controls">
				<div class="input-append">
					<input onkeyup="FormatCurrency(this);" type="text" id="kredit_add" name="kredit_add" value="">
				</div>
			</div>
		</div>
	</div>

	<div class="span2"  style="margin-top: 20px; margin-left: -7px;">
		<button onclick="add_row();" class="btn btn-info" type="button"> <i class="icon-plus"></i> Tambahkan </button>
	</div>
</div>



<div class="row-fluid" style="background: #F5EADA; padding-top: 15px; padding-bottom: 15px;">
	<div class="span12">
		<div class="widget-head blue">
			<h3> Input Transaksi </h3>
		</div>

		<table class="stat-table table table-hover">
			<thead>
				<tr>
					<th align="center"> Kode Akun </th>
					<th align="center"> Nama Akun </th>
					<th align="center"> Debet (Rp) </th>
					<th align="center"> Kredit (Rp) </th>
					<th align="center"> Aksi </th>
				</tr>						
			</thead>
			<tbody id="tes_row" style="font-size: 13px;">
				<tr> <td colspan="6" align="center"> <b> </b> </td> </tr>
			</tbody>

			<tfoot>
				<tr>
					<td style="background:#F1F1F1;" colspan="2" align="center"> <b> TOTAL </b> </td>
					<td style="background:#F1F1F1;" align="right"> <b style="font-size: 13px;" id="tot_debet_txt"> 0 </b> </td>
					<td style="background:#F1F1F1;" align="right"> <b style="font-size: 13px;" id="tot_kredit_txt"> 0 </b> </td>
					<td style="background:#F1F1F1;" align="right">  </td>
				</tr>

				<tr>
					<td style="background:#F1F1F1;" colspan="2" align="center"> <b> SELISIH DEBET & KREDIT </b> </td>
					<td style="background:#F1F1F1;" colspan="2"  align="center"> <b style="color:red; font-size: 13px;" id="tot_debet_sel"> 0 </b> </td>
					<td style="background:#F1F1F1;" align="right">  </td>
				</tr>
			</tfoot>
		</table>
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
					<input type="submit" value="Simpan Transaksi" name="simpan" class="btn btn-success">
					<!-- &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; -->
					<!-- <button class="btn btn-danger" onclick="$('#dialog-btn').click();" type="button"> <i class="icon-remove-sign"></i> Hapus Transaksi Ini </button> -->
					</center>
				</div>
			</div>
		</div>
	</div>
</div>

</form>



<!-- COPY ELEMENT -->
<div style="display:none;">
	<select  data-placeholder="Pilih ..." class="chzn-select3" tabindex="2" style="width:300px;" name="kode_akun[]" id="copy_select_jual">
	<?PHP
		$sel = ""; 
		foreach ($get_list_akun_all as $key => $akun_all) { 
			if($akun_all->KODE_AKUN == "4-4000" ){
				$sel = "selected";
			} else {
				$sel = "";
			}
	?>
		<option <?=$sel;?> value="<?=$akun_all->KODE_AKUN;?>"> (<?=$akun_all->KODE_AKUN;?>) - <?=$akun_all->NAMA_AKUN;?></option>
	<?PHP } ?>					
	</select>

	<select  data-placeholder="Pilih ..." class="chzn-select3" tabindex="2" style="width:300px;" name="kode_akun[]" id="copy_select_beli">
	<?PHP
		$sel = ""; 
		foreach ($get_list_akun_all as $key => $akun_all) { 
			if($akun_all->KODE_AKUN == "5-5900" ){
				$sel = "selected";
			} else {
				$sel = "";
			}
	?>
		<option <?=$sel;?> value="<?=$akun_all->KODE_AKUN;?>"> (<?=$akun_all->KODE_AKUN;?>) - <?=$akun_all->NAMA_AKUN;?></option>
	<?PHP } ?>					
	</select>
</div>

<!-- END COPY ELEMENT -->

<!-- HAPUS MODAL -->
<a id="dialog-btn" href="javascript:;" class="cd-popup-trigger" style="display:none;">View Pop-up</a>
<div class="cd-popup" role="alert">
    <div class="cd-popup-container">

        <form id="delete" method="post" action="<?=base_url().$post_url;?>">
            <input type="hidden" name="id_hapus" id="id_hapus" value="" />
        </form>   
         
        <p>Apakah anda yakin ingin menghapus Transaksi ini?</p>
        <ul class="cd-buttons">            
            <li><a href="javascript:;" onclick="$('#delete').submit();">Ya</a></li>
            <li><a onclick="$('.cd-popup-close').click(); $('#id_hapus').val('');" href="javascript:;">Tidak</a></li>
        </ul>
        <a href="#0" onclick="$('#id_hapus').val('');" class="cd-popup-close img-replace">Close</a>
    </div> <!-- cd-popup-container -->
</div> <!-- cd-popup -->
<!-- END HAPUS MODAL -->

<input id="tr_utama_count" value="10000" type="hidden"/>

<script type="text/javascript">
function show_pop_no_bukti(id){
    get_popup_no_bukti();
    ajax_no_bukti();
}

function get_popup_no_bukti(){
    var base_url = '<?php echo base_url(); ?>';
    var $isi = '<div id="popup_koang">'+
                '<div class="window_koang">'+
                '    <a href="javascript:void(0);"><img src="'+base_url+'ico/cancel.gif" id="pojok_koang"></a>'+
                '    <div class="panel-body">'+
                '    <input style="width: 95%;" type="text" name="search_koang" id="search_koang" class="form-control" value="" placeholder="Cari No. Bukti...">'+
                '    <div class="table-responsive">'+
                '            <table class="table table-hover2" id="tes5">'+
                '                <thead>'+
                '                    <tr>'+
                '                        <th style="white-space:nowrap;">NO TRANSAKSI AKUN</th>'+
                '                        <th>NO BUKTI</th>'+
                '                        <th>TANGGAL</th>'+
                '                        <th>KONTAK</th>'+
                '                        <th>URAIAN</th>'+
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

function ajax_no_bukti(){
    var keyword = $('#search_koang').val();
    $.ajax({
        url : '<?php echo base_url(); ?>input_transaksi_akuntansi_c/get_no_voucher',
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
                tipe_data = "Penjualan";
                if(res.TIPE == "BELI"){
                	tipe_data = "Pembelian / Biaya";
                }

                isine += '<tr onclick="get_transaksi(\'' +res.ID_BUKTI+ '\',\'' +res.TIPE_BUKTI+ '\', \'' +res.TGL+ '\');" style="cursor:pointer;">'+
                            '<td align="center">'+res.NO_VOUCHER+'</td>'+
                            '<td align="center">'+res.NO_BUKTI+'</td>'+
                            '<td align="center">'+res.TGL+'</td>'+
                            '<td align="center">'+res.KONTAK+'</td>'+
                            '<td align="left">'+res.URAIAN+'</td>'+
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

function get_transaksi(id , tipe, tgl_akun){

	$('#tes_row').html('');
	$.ajax({
        url : '<?php echo base_url(); ?>input_transaksi_akuntansi_c/get_transaksi',
        type : "POST",
        dataType : "json",
        data : {
            id : id,
            tipe : tipe,
        },
        success : function(res){

        	var pajak = res.NAMA_PAJAK+" ("+res.PROSEN+"%)";
        	if(res.NAMA_PAJAK == null){
        		pajak = "-";
        	}

        	var no_akun_pajak = "";
        	if(res.TIPE_TRX == "JUAL"){
        		$('#tipe').val('Penjualan');
        		no_akun_pajak = res.PAJAK_PENJUALAN;
        	} else if(res.TIPE_TRX == "BELI"){
        		$('#tipe').val('Pembelian');
        		no_akun_pajak = res.PAJAK_PEMBELIAN;
        	}

        	if(res.LUNAS == 1){
        		$('#lunas_sts').show();
        		$('#gak_lunas_sts').hide();
        	} else {
        		$('#lunas_sts').hide();
        		$('#gak_lunas_sts').show();
        		$('#gak_lunas_sts').html('Belum Lunas ('+res.NAMA_AKUN_HP+') ');
        	}

        	$('#no_bukti').val(res.NO_BUKTI);
        	$('#no_trx_akun').val(res.NO_TRX_AKUN);
        	$('#id_hapus').val(res.NO_TRX_AKUN);
        	$('#tgl_trx').val(tgl_akun);
        	$('#kontak').val(res.PELANGGAN);
        	$('#alamat_kontak').val(res.ALAMAT);
        	$('#uraian').val(res.MEMO);
        	$('#pajak').val(pajak);
        	$('#nilai_pajak').val(NumberToMoney(res.NILAI_PAJAK).split('.00').join(''));
        	$('#nilai_total').val(NumberToMoney(res.TOTAL).split('.00').join(''));
        	$('#sub_total_trx_detail').html("<b style='font-size:15px; color:green;'>"+NumberToMoney(res.SUB_TOTAL).split('.00').join('')+"</b>");

        	$('#search_koang').val("");
		    $('#popup_koang').css('display','none');
		    $('#popup_koang').hide();

		    get_transaksi_detail(id, tipe, res.NO_TRX_AKUN);
		    

		    //get_row_trx(res.TIPE_TRX, res.SUB_TOTAL);

		    // if(res.NAMA_PAJAK != null){
      //   		get_pajak_row(res.TIPE_TRX, no_akun_pajak, res.NILAI_PAJAK);
      //   	}

        	hitung_total_row();
        }
    });


}

function get_transaksi_detail(id, tipe, novoc){
	$.ajax({
        url : '<?php echo base_url(); ?>input_transaksi_akuntansi_c/get_transaksi_detail',
        type : "POST",
        dataType : "json",
        data : {
            id : id,
            tipe : tipe,
        },
        success : function(result){
            var isine = '';
            var no = 0;
            $.each(result,function(i,res){
                no++;
                isine += '<tr>'+
                            '<td style="text-align:center;">'+no+'</td>'+
                            '<td style="text-align:left;">'+res.NAMA_PRODUK+'</td>'+
                            '<td style="text-align:center;">'+res.QTY+'</td>'+
                            '<td style="text-align:center;">'+res.SATUAN+'</td>'+
                            '<td style="text-align:right;">'+NumberToMoney(res.HARGA_SATUAN).split('.00').join('')+'</td>'+
                            '<td style="text-align:right;"><b>'+NumberToMoney(parseFloat(res.HARGA_SATUAN) * parseFloat(res.QTY)).split('.00').join('')+'</b></td>'+
                        '</tr>';
            });
            $('#tes').html(isine); 

            get_voc_detail(novoc);
        }
    });
}

function get_row_trx(tipe, sub_total){
	
	var debet = 0;
	var kredit = 0;
	var value = "";
	var nama_akun = "";

	if(tipe == "JUAL"){
		debet  = 0;
		kredit = sub_total;
		var value = $('#copy_select_jual').html();
		var nama_akun = "Penjualan";
	} else if(tipe == "BELI"){
		debet  = sub_total;
		kredit = 0;
		var value = $('#copy_select_beli').html();
		var nama_akun = "Biaya Produksi";
	}

	var i = $('.tr_row').length + 1;

	$isi = 
	"<tr class='tr_row' id='tr_"+i+"'>"+
	 	"<td style='background:#FFF; text-align:left; width: 255px;' align='center'>"+
	 		"<select onchange='get_kode_akun_rinci(this.value);'  data-placeholder='Pilih ...' class='chzn-select_1' tabindex='2' style='width:250px;' name='kode_akun_row[]' id='sel_chos_1'>"+
			"</select>"+
	 	"</td>"+
	 	"<td style='background:#FFF; text-align:left' id='nama_akun_row' >"+nama_akun+"</td>"+
	 	"<td style='background:#FFF; text-align:right;'> <input type='hidden' class='deb_row' name='debet_row[]' value='"+debet+"'/> "+NumberToMoney(debet).split('.00').join('')+"</td>"+
	 	"<td style='background:#FFF; text-align:right;'> <input type='hidden' class='kre_row' name='kredit_row[]' value='"+kredit+"'/>"+NumberToMoney(kredit).split('.00').join('')+"</td>"+
	 	"<td style='background:#FFF; text-align:center;'> <button onclick='hapus_row("+i+");' type='button' class='btn btn-danger'> Hapus </button> </td>"+
	"</tr>";

	$('#tes_row').html($isi);

	$("#sel_chos_1").html(value);
	$(".chzn-select_1").chosen();

    $(".chzn-select-deselect").chosen({
        allow_single_deselect: true
    });

    hitung_total_row();
}

function get_voc_detail(no_voucher){
	$.ajax({
        url : '<?php echo base_url(); ?>input_transaksi_akuntansi_c/get_voc_detail',
        type : "POST",
        dataType : "json",
        data : {
            no_voucher : no_voucher,
        },
        success : function(res){

		 
		 var isine = '';
         var no = 0;
    	 

	     $.each(res,function(i,result){
	         no++;
	         var nama_akun = result.NAMA_AKUN;
			 var no_akun   = result.KODE_AKUN;

			 isine += 
			 "<tr class='tr_row' id='tr_"+no+"'>"+
			 	"<td style='background:#FFF; text-align:center'><input type='hidden' name='kode_akun_row[]' value='"+no_akun+"'/> "+no_akun+"</td>"+
			 	"<td style='background:#FFF; text-align:left'>"+nama_akun+"</td>"+
			 	"<td style='background:#FFF; text-align:right;'> <input type='hidden' class='deb_row' name='debet_row[]' value='"+result.DEBET+"'/> "+NumberToMoney(result.DEBET).split('.00').join('')+"</td>"+
			 	"<td style='background:#FFF; text-align:right;'> <input type='hidden' class='kre_row' name='kredit_row[]' value='"+result.KREDIT+"'/>"+NumberToMoney(result.KREDIT).split('.00').join('')+"</td>"+
			 	"<td style='background:#FFF; text-align:center;'> <button onclick='hapus_row("+no+");' type='button' class='btn btn-danger'> Hapus </button> </td>"+
			 "</tr>";

		 });
		 $('#tes_row').append(isine);

		 hitung_total_row();

        }
    });
}

function get_kode_akun_rinci(no_akun){
	$.ajax({
        url : '<?php echo base_url(); ?>input_transaksi_akuntansi_c/get_kode_akun_rinci',
        type : "POST",
        dataType : "json",
        data : {
            no_akun : no_akun,
        },
        success : function(result){
        	$('#nama_akun_row').html(result.NAMA_AKUN);
        }
    });
}

function hitung_total_row(){
	var tot_deb = 0;
	$(".deb_row").each(function(idx, elm) {
		var tot = elm.value.split(',').join('');
		if(tot > 0){
    		tot_deb += parseFloat(tot);
		}
    });

    var tot_kre = 0;
	$(".kre_row").each(function(idx, elm) {
		var tot2 = elm.value.split(',').join('');
		if(tot2 > 0){
    		tot_kre += parseFloat(tot2);
		}
    });

    var selisih = 0;
    selisih = parseFloat(tot_deb) - parseFloat(tot_kre);


    if(selisih < 0){
    	$('#tot_debet_sel').html(NumberToMoney(selisih*-1).split('.00').join(''));
    } else if(selisih > 0){
    	$('#tot_debet_sel').html(NumberToMoney(selisih).split('.00').join(''));
    } else {
    	$('#tot_debet_sel').html(0);
    }

    $('#total_debet_all').val(tot_deb);
    $('#total_kredit_all').val(tot_kre);

    $('#tot_debet_txt').html(NumberToMoney(tot_deb).split('.00').join(''));
    $('#tot_kredit_txt').html(NumberToMoney(tot_kre).split('.00').join(''));
}

function add_row(){
	var no_bukti  = $('#no_bukti').val();
	var no_akun  = $('#kode_akun_add').val();
	var debet  = $('#debet_add').val();
	var kredit = $('#kredit_add').val();

	if(no_bukti == ""){
		alert("Silahkan pilih nomor bukti terlebih dahulu");
	} else {

		if(debet == "" && kredit == ""){
			alert('Mohon isikan nilai debet atau kredit');
		} else {

		debet  = debet.split(',').join('');
		kredit = kredit.split(',').join('');

		if(debet == ""){
			debet = 0;
		}

		if(kredit == ""){
			kredit = 0;
		}

		
		var jml_tr = $('#tr_utama_count').val();
	    var i = parseInt(jml_tr) + 1;

		$.ajax({
	        url : '<?php echo base_url(); ?>input_transaksi_akuntansi_c/get_kode_akun_rinci',
	        type : "POST",
	        dataType : "json",
	        data : {
	            no_akun : no_akun,
	        },
	        success : function(result){
	        	$isi = 
				 "<tr class='tr_row' id='tr_"+i+"'>"+
				 	"<td style='background:#FFF; text-align:center'><input type='hidden' name='kode_akun_row[]' value='"+no_akun+"'/> "+no_akun+"</td>"+
				 	"<td style='background:#FFF; text-align:left'>"+result.NAMA_AKUN+"</td>"+
				 	"<td style='background:#FFF; text-align:right;'> <input type='hidden' class='deb_row' name='debet_row[]' value='"+debet+"'/> "+NumberToMoney(debet).split('.00').join('')+"</td>"+
				 	"<td style='background:#FFF; text-align:right;'> <input type='hidden' class='kre_row' name='kredit_row[]' value='"+kredit+"'/>"+NumberToMoney(kredit).split('.00').join('')+"</td>"+
				 	"<td style='background:#FFF; text-align:center;'> <button onclick='hapus_row("+i+");' type='button' class='btn btn-danger'> Hapus </button> </td>"+
				 "</tr>";

			 $('#tes_row').append($isi);
			 hitung_total_row();

			 $('#debet_add').val("");
			 $('#kredit_add').val("");

	        }
	    });

		$('#tr_utama_count').val(i);

	    }
    }
}

function hapus_row (id) {
	$('#tr_'+id).remove();
	hitung_total_row();
}

function show_detail(){
	if($("#is_showed").is(':checked')){
	    $('#detail_view').show(); 
	} else {
	    $('#detail_view').hide(); 
	}
}

function cek_balance(){
	$deb = $('#total_debet_all').val();
	$kre = $('#total_kredit_all').val();
	$nobuk = $('#no_bukti').val();

	$kon = false;
	if($nobuk == "" || $nobuk == null){
		alert('No. Bukti tidak boleh kosong !!');
		$kon = false;
	} else {
		if( $(".deb_row").get(0).value == 0 ){
			    alert('Posisi Debet Harus di Atas !');
				$kon = false;
		} else {
			if(parseFloat($deb) != parseFloat($kre)){
				alert('Debet dan Kredit harus Balance !!');
				$kon = false;
			} else {
				$kon = true;
			}
		}
	}

	return $kon;
}


</script>
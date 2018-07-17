<form id="delete" method="post" action="<?=base_url().$post_url;?>" >

<div class="row-fluid ">
	<div class="span12">
		<div class="primary-head">
			<h3 class="page-header"> <i class="icon-remove-sign"></i> Hapus Transaksi Akuntansi </h3>


		</div>
		<ul class="breadcrumb">
			<li><a href="#" class="icon-home"></a><span class="divider "><i class="icon-angle-right"></i></span></li>
			<li><a href="#">Input Akuntansi</a><span class="divider"><i class="icon-angle-right"></i></span></li>
			<li class="active"> Hapus Transaksi Akuntansi  </li>
		</ul>
	</div>
</div>

<div class="row-fluid form-horizontal" style="background: #F5EADA; padding-top: 15px; padding-bottom: 15px;">
	<div>
		<div class="span12">

			<div class="control-group">
				<label class="control-label"> <b style="font-size: 14px;"> Jenis </b> </label>
				<div class="controls">
					<select class="span4" name="jenis" id="jenis" onchange="set_btn()">
						<option value="ju"> Transaksi Akuntansi / Jurnal Umum </option>
						<option value="jp"> Jurnal Penyesuaian </option>
						<option value="jbk"> Jurnal Bayar Kas Bank </option>
					</select>
				</div>
			</div>

			<div class="control-group">
				<label class="control-label"> <b style="font-size: 14px;"> No. Transaksi Akun </b> </label>
				<div class="controls">
					<div class="input-append">
						<input type="text" id="no_trx_akun" name="no_trx_akun" readonly style="background:#FFF;">
						<button id="ju_btn" onclick="show_pop_no_bukti_ju();" type="button" class="btn">Cari</button>
						<button id="jp_btn" onclick="show_pop_no_bukti_jp();" type="button" class="btn" style="display:none;">Cari</button>
						<button id="jbk_btn" onclick="show_pop_no_bukti_jbk();" type="button" class="btn" style="display:none;">Cari</button>
					</div>
				</div>
			</div>

			<div class="control-group">
				<label class="control-label"> <b style="font-size: 14px;"> Tanggal Transaksi </b> </label>
				<div class="controls">
					<input type="text" id="tgl_trx" name="tgl_trx" readonly style="background:#FFF;">
				</div>
			</div>


			<div class="control-group">
				<label class="control-label"> <b style="font-size: 14px;"> Uraian </b> </label>
					<div class="controls">
						<textarea rows="4" readonly id="uraian" name="uraian" style="resize:none; height: 87px; width: 350px; background:#FFF;"></textarea>
					</div>
			</div>

			<div class="control-group" style="display:none;" id="info_head">
				<label class="control-label"> <b style="font-size: 14px;"> Informasi </b> </label>
				<div class="controls" style="padding-top: 5px;">
					<b id="info_hapus" style="font-size:15px;"> <font style="color:green;"> Aman untuk dihapus </font> </b>
				</div>
			</div> 			
		</div>
	</div>
</div>

<div class="row-fluid" style="background: #F5EADA; padding-top: 15px; padding-bottom: 15px;">
	<div class="span12" id="detail_view">
		<div class="widget-head orange">
			<h3> Detail Transaksi Akuntansi </h3>
		</div>
		<table class="stat-table table table-hover">
			<thead>
				<tr>
					<th align="center"> No </th>
					<th align="center"> Kode Akun </th>
					<th align="center"> Nama Akun </th>
					<th align="center"> Debet (Rp) </th>
					<th align="center"> Kredit (Rp) </th>
				</tr>						
			</thead>
			<tbody id="tes" style="font-size: 13px;">
				<tr> <td colspan="5" align="center"> <b> Silahkan pilih Nomor Transaksi terlebih dahulu untuk melihat detail transaksi </b> </td> </tr>
			</tbody>
			<tfoot>
				<tr>
					<td style="background:#F1F1F1;" colspan="3" align="center"> <b> TOTAL </b> </td>
					<td style="background:#F1F1F1;" align="right"> <b style="font-size: 13px;" id="tot_debet_det"> 0 </b> </td>
					<td style="background:#F1F1F1;" align="right"> <b style="font-size: 13px;" id="tot_kredit_det"> 0 </b> </td>
				</tr>
			</tfoot>
		</table>
	</div>
</div>

<div class="row-fluid" id="view_data" style="display:none;">
	<div class="span12">
		<div class="content-widgets light-gray">
			<div class="widget-head orange">
				<h3> </h3>
			</div>
			<div class="widget-container">
				<div class="form-actions">
					<center>
					<input type="hidden" name="total_all" id="total_all" value="" />
					<button class="btn btn-danger" onclick="$('#dialog-btn').click();" type="button"> Hapus Transaksi </button>
					</center>
				</div>
			</div>
		</div>
	</div>
</div>



<!-- HAPUS MODAL -->
<a id="dialog-btn" href="javascript:;" class="cd-popup-trigger" style="display:none;">View Pop-up</a>
<div class="cd-popup" role="alert">
    <div class="cd-popup-container">         
        <p>Apakah anda yakin ingin menghapus data transaksi ini?</p>
        <ul class="cd-buttons">            
            <li><a href="javascript:;" onclick="$('#delete').submit();">Ya</a></li>
            <li><a onclick="$('.cd-popup-close').click();" href="javascript:;">Tidak</a></li>
        </ul>
        <a href="#0"  class="cd-popup-close img-replace">Close</a>
    </div> <!-- cd-popup-container -->
</div> <!-- cd-popup -->
<!-- END HAPUS MODAL -->

</form>

<script type="text/javascript">
function set_btn() {
	var jenis = $('#jenis').val();
	if(jenis == "ju"){
		$('#ju_btn').show();
		$('#jp_btn').hide();
		$('#jbk_btn').hide();
	} else if(jenis == "jp"){
		$('#ju_btn').hide();
		$('#jp_btn').show();
		$('#jbk_btn').hide();
	} else if(jenis == "jbk"){
		$('#ju_btn').hide();
		$('#jp_btn').hide();
		$('#jbk_btn').show();
	}
}

// POPUP JURNAL UMUM


function show_pop_no_bukti_ju(id){
    get_popup_no_bukti_ju();
    ajax_no_bukti_ju();
}

function get_popup_no_bukti_ju(){
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
                '                        <th>NO</th>'+
                '                        <th style="white-space:nowrap;">NO TRANSAKSI</th>'+
                '                        <th> TANGGAL </th>'+
                '                        <th> URAIAN </th>'+
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

function ajax_no_bukti_ju(){
    var keyword = $('#search_koang').val();
    $.ajax({
        url : '<?php echo base_url(); ?>hapus_transaksi_akun_c/get_no_bukti',
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

                isine += '<tr onclick="get_detail_vocer_ju(\'' +res.NO_VOUCHER+ '\',\'' +res.NO_VOUCHER+ '\');" style="cursor:pointer;">'+
                            '<td align="center">'+no+'</td>'+
                            '<td align="center">'+res.NO_VOUCHER+'</td>'+
                            '<td align="center">'+res.TGL+'</td>'+
                            '<td align="left">'+res.URAIAN+'</td>'+
                        '</tr>';
            });

            if(result.length == 0){
            	isine = "<tr><td colspan='5' style='text-align:center'><b style='font-size: 15px;'> Data tidak tersedia </b></td></tr>";
            }

            $('#tes5 tbody').html(isine); 
            $('#search_koang').off('keyup').keyup(function(){
                ajax_no_bukti_ju();
            });
        }
    });
}

function get_detail_vocer_ju(voc , voc2){

	$('#tes_row').html('');
	$.ajax({
        url : '<?php echo base_url(); ?>hapus_transaksi_akun_c/get_detail_vocer',
        type : "POST",
        dataType : "json",
        data : {
            voc : voc,
            voc2 : voc2,
        },
        success : function(res){

        	$('#no_trx_akun').val(res.NO_VOUCHER);
        	$('#uraian').val(res.URAIAN);
        	$('#tgl_trx').val(res.TGL);


		    $('#popup_koang').remove();
		    $('#info_head').show();
		    cek_no_bukti_ju(voc);
		    get_detail_vocer_akun_ju(voc);

		    $('#view_data').show();

        	//hitung_total_row();
        }
    });
}

function cek_no_bukti_ju(voc){
	$.ajax({
        url : '<?php echo base_url(); ?>hapus_transaksi_akun_c/cek_no_bukti_ju',
        type : "POST",
        dataType : "json",
        data : {
            voc : voc,
        },
        success : function(res){
        	if(res == "aman"){
        		$('#info_hapus').html('<font style="color:green;"> Aman untuk dihapus </font>');
        	} else if(res == "tidak_jp"){
        		$('#info_hapus').html('<font style="color:red;"> Transaksi ini telah terpakai di Jurnal Penyesuaian <br> Menghapus ini juga akan menghapus Jurnal Penyesuaian terkait Transaksi ini. </font>');
        	} else if(res == "tidak_jbk"){
        		$('#info_hapus').html('<font style="color:red;"> Transaksi ini telah terpakai di Jurnal Bayar Kas Bank <br> Menghapus ini juga akan menghapus Jurnal Bayar Kas Bank terkait Transaksi ini. </font>');
        	} else if(res == "tidak_jp_jbk"){
        		$('#info_hapus').html('<font style="color:red;"> Transaksi ini telah terpakai di Jurnal Penyesuaian dan Jurnal Bayar Kas Bank <br> Menghapus ini juga akan menghapus Jurnal Penyesuaian dan Jurnal Bayar Kas Bank terkait Transaksi ini.</font>');
        	}
        }
    });
}

function get_detail_vocer_akun_ju(voc){
	$.ajax({
        url : '<?php echo base_url(); ?>hapus_transaksi_akun_c/get_detail_vocer_akun',
        type : "POST",
        dataType : "json",
        data : {
            voc : voc,
        },
        success : function(result){
            var isine = '';
            var no = 0;
            $deb = 0;
            $kre = 0;
            $.each(result,function(i,res){
                no++;
                $deb += parseFloat(res.DEBET);
                $kre += parseFloat(res.KREDIT);
                isine += '<tr>'+
                            '<td style="text-align:center;">'+no+'</td>'+
                            '<td style="text-align:center;">'+res.KODE_AKUN+'</td>'+
                            '<td style="text-align:left;">'+res.NAMA_AKUN+'</td>'+
                            '<td style="text-align:right;">'+NumberToMoney(res.DEBET).split('.00').join('')+'</td>'+
                            '<td style="text-align:right;">'+NumberToMoney(res.KREDIT).split('.00').join('')+'</td>'+

                        '</tr>';
            });
            $('#tes').html(isine); 
            $('#tot_debet_det').html(NumberToMoney($deb).split('.00').join(''));
            $('#tot_kredit_det').html(NumberToMoney($kre).split('.00').join(''));
        }
    });
}

// POPUP JURNAL PENYESUAIAN

function show_pop_no_bukti_jp(id){
    get_popup_no_bukti_jp();
    ajax_no_bukti_jp();
}

function get_popup_no_bukti_jp(){
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
                '                        <th>NO</th>'+
                '                        <th style="white-space:nowrap;">NO BUKTI</th>'+
                '                        <th>NO TRANSAKSI AKUN</th>'+
                '                        <th> TANGGAL </th>'+
                '                        <th> URAIAN </th>'+
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

function ajax_no_bukti_jp(){
    var keyword = $('#search_koang').val();
    $.ajax({
        url : '<?php echo base_url(); ?>hapus_transaksi_akun_c/get_no_bukti_jp',
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

                isine += '<tr onclick="get_detail_vocer_jp(\'' +res.NO_BUKTI+ '\',\'' +res.NO_BUKTI+ '\');" style="cursor:pointer;">'+
                            '<td align="center">'+no+'</td>'+
                            '<td align="center">'+res.NO_BUKTI+'</td>'+
                            '<td align="center">'+res.NO_VOUCHER+'</td>'+
                            '<td align="center">'+res.TGL+'</td>'+
                            '<td align="left">'+res.URAIAN+'</td>'+
                        '</tr>';
            });

            if(result.length == 0){
            	isine = "<tr><td colspan='5' style='text-align:center'><b style='font-size: 15px;'> Data tidak tersedia </b></td></tr>";
            }

            $('#tes5 tbody').html(isine); 
            $('#search_koang').off('keyup').keyup(function(){
                ajax_no_bukti_jp();
            });
        }
    });
}

function get_detail_vocer_jp(voc , voc2){

	$('#tes_row').html('');
	$.ajax({
        url : '<?php echo base_url(); ?>hapus_transaksi_akun_c/get_detail_vocer_jp',
        type : "POST",
        dataType : "json",
        data : {
            voc : voc,
            voc2 : voc2,
        },
        success : function(res){

        	$('#no_trx_akun').val(res.NO_BUKTI);
        	$('#uraian').val(res.URAIAN);
        	$('#tgl_trx').val(res.TGL);


		    $('#popup_koang').remove();
		    $('#info_head').show();
		    $('#info_hapus').html('<font style="color:green;"> Aman untuk dihapus </font>');
		    get_detail_vocer_akun_jp(voc);
		    $('#view_data').show();

        	//hitung_total_row();
        }
    });
}

function get_detail_vocer_akun_jp(voc){
	$.ajax({
        url : '<?php echo base_url(); ?>hapus_transaksi_akun_c/get_detail_vocer_akun_jp',
        type : "POST",
        dataType : "json",
        data : {
            voc : voc,
        },
        success : function(result){
            var isine = '';
            var no = 0;
            $deb = 0;
            $kre = 0;
            $.each(result,function(i,res){
                no++;
                $deb += parseFloat(res.DEBET);
                $kre += parseFloat(res.KREDIT);
                isine += '<tr>'+
                            '<td style="text-align:center;">'+no+'</td>'+
                            '<td style="text-align:center;">'+res.KODE_AKUN+'</td>'+
                            '<td style="text-align:left;">'+res.NAMA_AKUN+'</td>'+
                            '<td style="text-align:right;">'+NumberToMoney(res.DEBET).split('.00').join('')+'</td>'+
                            '<td style="text-align:right;">'+NumberToMoney(res.KREDIT).split('.00').join('')+'</td>'+

                        '</tr>';
            });
            $('#tes').html(isine); 
            $('#tot_debet_det').html(NumberToMoney($deb).split('.00').join(''));
            $('#tot_kredit_det').html(NumberToMoney($kre).split('.00').join(''));
        }
    });
}

// POPUP JBK

function show_pop_no_bukti_jbk(){
	get_popup_no_bukti_jbk();
    ajax_no_bukti_jbk();
}

function get_popup_no_bukti_jbk(){
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
                '                        <th>NO</th>'+
                '                        <th style="white-space:nowrap;">NO BUKTI</th>'+
                '                        <th>CEK GIRO</th>'+
                '                        <th> TANGGAL </th>'+
                '                        <th> URAIAN </th>'+
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

function ajax_no_bukti_jbk(){
    var keyword = $('#search_koang').val();
    $.ajax({
        url : '<?php echo base_url(); ?>hapus_transaksi_akun_c/get_no_bukti_jbk',
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

                isine += '<tr onclick="get_detail_vocer_jbk(\'' +res.NO_VOUCHER+ '\',\'' +res.CEK_GIRO+ '\');" style="cursor:pointer;">'+
                            '<td align="center">'+no+'</td>'+
                            '<td align="center">'+res.NO_VOUCHER+'</td>'+
                            '<td align="center">'+res.CEK_GIRO+'</td>'+
                            '<td align="center">'+res.TGL_CEK+'</td>'+
                            '<td align="left">'+res.URAIAN+'</td>'+
                        '</tr>';
            });

            if(result.length == 0){
            	isine = "<tr><td colspan='5' style='text-align:center'><b style='font-size: 15px;'> Data tidak tersedia </b></td></tr>";
            }

            $('#tes5 tbody').html(isine); 
            $('#search_koang').off('keyup').keyup(function(){
                ajax_no_bukti_jbk();
            });
        }
    });
}

function get_detail_vocer_jbk(voc , cek_giro){

	$('#tes_row').html('');
	$.ajax({
        url : '<?php echo base_url(); ?>hapus_transaksi_akun_c/get_detail_vocer_jbk',
        type : "POST",
        dataType : "json",
        data : {
            voc : voc,
            cek_giro : cek_giro,
        },
        success : function(res){

        	$('#no_trx_akun').val(res.NO_VOUCHER);
        	$('#uraian').val(res.URAIAN);
        	$('#tgl_trx').val(res.TGL_CEK);


		    $('#popup_koang').remove();
		    $('#info_head').show();
		    $('#info_hapus').html('<font style="color:green;"> Aman untuk dihapus </font>');
		    get_detail_vocer_akun_jbk(voc, cek_giro);
		    $('#view_data').show();
        	//hitung_total_row();
        }
    });
}

function get_detail_vocer_akun_jbk(voc, cek_giro){
	$.ajax({
        url : '<?php echo base_url(); ?>hapus_transaksi_akun_c/get_detail_vocer_akun_jbk',
        type : "POST",
        dataType : "json",
        data : {
            voc : voc,
            cek_giro : cek_giro,
        },
        success : function(result){
            var isine = '';
            var no = 0;
            $deb = 0;
            $kre = 0;
            $.each(result,function(i,res){
                no++;
                $deb += parseFloat(res.DEBET);
                $kre += parseFloat(res.KREDIT);
                isine += '<tr>'+
                            '<td style="text-align:center;">'+no+'</td>'+
                            '<td style="text-align:center;">'+res.KODE_AKUN+'</td>'+
                            '<td style="text-align:left;">'+res.NAMA_AKUN+'</td>'+
                            '<td style="text-align:right;">'+NumberToMoney(res.DEBET).split('.00').join('')+'</td>'+
                            '<td style="text-align:right;">'+NumberToMoney(res.KREDIT).split('.00').join('')+'</td>'+

                        '</tr>';
            });
            $('#tes').html(isine); 
            $('#tot_debet_det').html(NumberToMoney($deb).split('.00').join(''));
            $('#tot_kredit_det').html(NumberToMoney($kre).split('.00').join(''));
        }
    });
}

</script>

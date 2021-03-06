<script type="text/javascript" src="<?php echo base_url(); ?>js-devan/jquery-1.11.1.min.js"></script>

<style type="text/css">
#view_ubah, 
#view_jenis,
#view_jenis_ubah,
#tombol_reset{
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

    data_poli();

    $('#batal').click(function(){
    	window.location = "<?php echo base_url(); ?>setup/admum_poli_c";
    });

    $("input[name='urutkan']").click(function(){
    	data_poli();
    });

    $("input[name='cari_berdasarkan']").click(function(){
    	var cari = $("input[name='cari_berdasarkan']:checked").val();
    	if(cari == 'Jenis'){
    		$('#view_jenis').show();
    	}else{
    		$('#view_jenis').hide();
    		data_poli();
    	}
    });

    $("#jumlah_tampil").change(function(){
    	data_poli();
    });

    $("#pilih_jenis").change(function(){
    	data_poli();
    });

    $('#status').change(function(){
    	var jenis = $('#jenis').val();
    	var status = $('#status').val();

    	if(jenis == 'Poli Umum'){
    		if(status == 'Malam'){
    			$('#view_ket').show();
    			$('#keterangan').val('Di atas jam 21:00 WIB');
    		}else if(status == 'Tarif D'){
    			$('#view_ket').show();
    			$('#keterangan').val('Jasa Dokter Free');
    		}else{
    			$('#view_ket').hide();
    		}
    	}else{
    		if(status == 'Tarif D'){
    			$('#view_ket').show();
    			$('#keterangan').val('Jasa POLI free, hanya Biaya Admin');
    		}else{
    			$('#view_ket').hide();
    		}
    	}
    });

    $('.btn_dokter').click(function(){
		$('#popup_dokter').click();
    	data_dokter();
    });

	$('.btn_dep').click(function(){
		$('#popup_dep').click();
    	get_departemen();
    });

    $('.btn_div').click(function(){
		$('#popup_div').click();
    	get_divisi();
    });    

    $('.btn_cari_perawat').click(function(){
		$('#popup_perawat').click();
    	load_perawat()
    });

    $('#tambah_poli').click(function(){
    	$('#ket').val('Tambah');
    });

	$('#checkbox2_ubah').click(function(){
    	var cek = $('#checkbox2_ubah').is(":checked");
    	if(cek == true){
    		$('#cek_jenis_ubah').val('1');
    		$('#view_jenis_ubah').show();
    	}else{
    		$('#cek_jenis_ubah').val('0');
    		$('#view_jenis_ubah').hide();
    	}
    });    
});

function data_dokter(){
	var keyword = $('#cari_dokter').val();

	if(ajax){
		ajax.abort();
	}

	ajax = $.ajax({
		url : '<?php echo base_url(); ?>setup/admum_poli_c/data_peg_dokter',
		data : {keyword:keyword},
		type : "GET",
		dataType : "json",
		success : function(result){
			$tr = "";

			if(result == "" || result == null){
				$tr = "<tr><td colspan='5' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
			}else{
				var no = 0;

				for(var i=0; i<result.length; i++){
					no++;

					result[i].NAMA_POLI = result[i].NAMA_POLI==null?"-":result[i].NAMA_POLI;
					result[i].JABATAN = result[i].JABATAN==null?"-":result[i].JABATAN;

					$tr +=  '<tr style="cursor:pointer;" onclick="klik_dokter('+result[i].ID+');">'+
		                    '	<td style="text-align:center;">'+no+'</td>'+
		                    '   <td style="text-align:center;">'+result[i].NIP+'</td>'+
		                    '   <td>'+result[i].NAMA+'</td>'+
		                    '   <td>'+result[i].JABATAN+'</td>'+
		                    '   <td>'+result[i].NAMA_POLI+'</td>'+
		                    '</tr>';
				}
			}

			$('#tabel_dokter tbody').html($tr);
		}
	});

	$('#cari_dokter').off('keyup').keyup(function(){
		data_dokter();
	});
}

function klik_dokter(id){
	$('#tutup_dokter').click();

	$.ajax({
		url : '<?php echo base_url(); ?>setup/admum_poli_c/data_peg_dokter_id',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(row){
			var ket = $('#ket').val();
			if(ket == 'Tambah'){
				$('#id_peg_dokter').val(id);
				$('#nama_dokter').val(row['NAMA']);
			}else{
				$('#id_peg_dokter_ubah').val(id);
				$('#nama_dokter_txt').val(row['NAMA']);
			}
		}
	});
}

function paging($selector){
	var jumlah_tampil = $('#jumlah_tampil').val();

    if(typeof $selector == 'undefined')
    {
        $selector = $("#tabel_poli tbody tr");
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

function get_departemen(){
	var keyword = $('#cari_dep').val();

	$.ajax({
		url : '<?php echo base_url(); ?>setup/admum_poli_c/get_departemen',
		data : {keyword:keyword},
		type : "GET",
		dataType : "json",
		success : function(result){
			$tr = "";

			if(result == "" || result == null){
				$tr = "<tr><td colspan='2' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
			}else{
				var no = 0;

				for(var i=0; i<result.length; i++){
					no++;
						   		
					$tr +=  '<tr style="cursor:pointer;" onclick="klik_departemen('+result[i].ID+');">'+
		                    '	<td style="vertical-align:middle; text-align:center;">'+no+'</td>'+
		                    '   <td style="vertical-align:middle;">'+result[i].NAMA_DEP+'</td>'+
		                    '</tr>';
				}
			}

			$('#tabel_dep tbody').html($tr);
		}
	});

	$('#cari_dep').off('keyup').keyup(function(){
		get_departemen();
	});
}

function klik_departemen(id){
	$('#tutup_dep').click();

	$.ajax({
		url : '<?php echo base_url(); ?>setup/admum_poli_c/klik_departemen',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(row){
			var id_ubah = $('#id_ubah').val();
			if(id_ubah != ""){
				$('#id_departemen_ubah').val(id);
				$('#departemen_ubah').val(row['NAMA_DEP']);

				$('#id_departemen').val("");
				$('#departemen').val("");
			}else{
				$('#id_departemen').val(id);
				$('#departemen').val(row['NAMA_DEP']);

				$('#id_departemen_ubah').val("");
				$('#departemen_ubah').val("");
			}
		}
	});
}

function get_divisi(){
	var id_ubah = $('#id_ubah').val();
	var id_dep = '';
	var keyword = $('#cari_div').val();
	if(id_ubah != ""){
		id_dep = $('#id_departemen_ubah').val();
	}else{
		id_dep = $('#id_departemen').val();
	}

	$.ajax({
		url : '<?php echo base_url(); ?>setup/admum_poli_c/get_divisi',
		data : {
			id_dep:id_dep,
			keyword:keyword
		},
		type : "GET",
		dataType : "json",
		success : function(result){
			$tr = "";

			if(result == "" || result == null){
				$tr = "<tr><td colspan='2' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
			}else{
				var no = 0;

				for(var i=0; i<result.length; i++){
					no++;
						   		
					$tr +=  '<tr style="cursor:pointer;" onclick="klik_divisi('+result[i].ID+');">'+
		                    '	<td style="vertical-align:middle; text-align:center;">'+no+'</td>'+
		                    '   <td style="vertical-align:middle;">'+result[i].NAMA_DIV+'</td>'+
		                    '</tr>';
				}
			}

			$('#tabel_div tbody').html($tr);
		}
	});

	$('#cari_div').off('keyup').keyup(function(){
		get_divisi();
	});
}

function klik_divisi(id){
	$('#tutup_div').click();

	$.ajax({
		url : '<?php echo base_url(); ?>setup/admum_poli_c/klik_divisi',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(row){
			var id_ubah = $('#id_ubah').val();
			if(id_ubah != ""){
				$('#id_divisi_ubah').val(id);
				$('#divisi_ubah').val(row['NAMA_DIV']);

				$('#id_divisi').val("");
				$('#divisi').val("");
			}else{
				$('#id_divisi').val(id);
				$('#divisi').val(row['NAMA_DIV']);

				$('#id_divisi_ubah').val("");
				$('#divisi_ubah').val("");
			}
		}
	});
}

function data_poli(){
	$('#popup_load').show();
	var keyword = $('#cari_poli').val();
	var urutkan = $("input[name='urutkan']:checked").val();
	var pilih_jenis = $('#pilih_jenis').val();
	var cari = $("input[name='cari_berdasarkan']:checked").val();

	if(ajax){
		ajax.abort();
	}

	ajax = $.ajax({
		url : '<?php echo base_url(); ?>setup/admum_poli_c/data_poli',
		data : {
			keyword:keyword,
			urutkan:urutkan,
			pilih_jenis:pilih_jenis,
			cari:cari,
		},
		type : "GET",
		dataType : "json",
		success : function(result){
			$tr = "";

			if(result == "" || result == null){
				$tr = "<tr><td colspan='8' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
			}else{
				var no = 0;

				for(var i=0; i<result.length; i++){
					no++;

					result[i].NAMA_DOKTER = result[i].NAMA_DOKTER==null?"-":result[i].NAMA_DOKTER;
					var jenis = result[i].JENIS+' - '+result[i].STATUS;

					var aksi =  '<button type="button" class="btn btn-success waves-effect waves-light btn-sm m-b-5" onclick="ubah_poli('+result[i].ID+');">'+
									'<i class="fa fa-pencil"></i>'+
								'</button>&nbsp;'+
						   		'<button type="button" class="btn btn-danger waves-effect waves-light btn-sm m-b-5" onclick="hapus_poli('+result[i].ID+');">'+
						   			'<i class="fa fa-trash"></i>'+
						   		'</button>';
						   		
					$tr +=  '<tr>'+
		                    '	<td style="vertical-align:middle; text-align:center;">'+no+'</td>'+
		                    '   <td style="vertical-align:middle;">'+result[i].NAMA+'</td>'+
		                    '   <td style="vertical-align:middle;">'+jenis+'</td>'+
		                    '   <td style="vertical-align:middle;">'+result[i].KETERANGAN+'</td>'+
		                    '   <td style="vertical-align:middle;">'+result[i].NAMA_DOKTER+'</td>'+
		                    '   <td style="vertical-align:middle; text-align:center;">'+
			                    	'<a href="javascript:void(0);" class="on-default edit-row" onclick="detail_perawat('+result[i].ID+');">'+
							   			'<i class="fa fa-user"></i>&nbsp;<b>'+result[i].JUMLAH_PERAWAT+'</b> Perawat'+
							   		'</a>'+
		                    '	</td>'+
		                    '   <td style="vertical-align:middle; text-align:right;">'+formatNumber(result[i].BIAYA)+'</td>'+
		                    '   <td style="vertical-align:middle;" align="center">'+aksi+'</td>'+
		                    '</tr>';
				}
			}

			$('#tabel_poli tbody').html($tr);
			paging();
			$('#popup_load').fadeOut();
		}
	});

	$('#tombol_cari').click(function(){
		data_poli();
		$('#tombol_reset').show();
		$('#tombol_cari').hide();
	});

	$('#tombol_reset').click(function(){
		$('#cari_poli').val("");
		data_poli();
		$('#tombol_reset').hide();
		$('#tombol_cari').show();
	});
}

function onEnterText(e){
    if (e.keyCode == 13) {
        data_poli();
        $('#tombol_reset').show();
		$('#tombol_cari').hide();
        return false;
    }
}

function ubah_poli(id){
	$('#ket').val('Ubah');
	$('#view_ubah').show();
	$('#view_data').hide();

	$.ajax({
		url : '<?php echo base_url(); ?>setup/admum_poli_c/data_poli_id',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(row){
			$('#id_ubah').val(id);
			$('#id_departemen_ubah').val(row['poli']['ID_DEPARTEMEN']);
			$('#departemen_ubah').val(row['poli']['NAMA_DEP']);
			$('#id_divisi_ubah').val(row['poli']['ID_DIVISI']);
			$('#divisi_ubah').val(row['poli']['NAMA_DIV']);
			$('#jenis_txt').val(row['poli']['JENIS']);
			$('#nama_poli_ubah').val(row['poli']['NAMA']);

			if(row['poli']['STATUS'] == "Normal"){
				$('#status_ubah option[value="Normal"]').attr('selected','selected');
			}else if(row['poli']['STATUS'] == "Tarif A"){
				$('#status_ubah option[value="Tarif A"]').attr('selected','selected');
			}else if(row['poli']['STATUS'] == "Tarif B"){
				$('#status_ubah option[value="Tarif B"]').attr('selected','selected');
			}else if(row['poli']['STATUS'] == "Tarif C"){
				$('#status_ubah option[value="Tarif C"]').attr('selected','selected');
			}else if(row['poli']['STATUS'] == "Tarif D"){
				$('#status_ubah option[value="Tarif D"]').attr('selected','selected');
			}else if(row['poli']['STATUS'] == "Malam"){
				$('#status_ubah option[value="Malam"]').attr('selected','selected');
			}else if(row['poli']['STATUS'] == "Tarif Emergency"){
				$('#status_ubah option[value="Tarif Emergency"]').attr('selected','selected');
			}else if(row['poli']['STATUS'] == "Cito"){
				$('#status_ubah option[value="Cito"]').attr('selected','selected');
			}else if(row['poli']['STATUS'] == "Konsultasi Melalui Telpon"){
				$('#status_ubah option[value="Konsultasi Melalui Telepon"]').attr('selected','selected');
			}

			$('#keterangan_ubah').val(row['poli']['KETERANGAN']);
			$('#biaya_ubah').val(formatNumber(row['poli']['BIAYA']));
			$('#id_peg_dokter_ubah').val(row['poli']['ID_PEG_DOKTER']);
			$('#nama_dokter_txt').val(row['poli']['NAMA_DOKTER']);

			$tr = "";

			for(var i=0; i<row['prwt'].length; i++){
				var aksi = "<button type='button' class='btn waves-light btn-danger btn-sm' onclick='hapus_perawat(this,"+row['prwt'][i].ID+");'><i class='fa fa-trash'></i></button>";

				$tr += "<tr>"+
							"<td>"+row['prwt'][i].NIP+"</td>"+
							"<td>"+row['prwt'][i].NAMA_PEGAWAI+"</td>"+
							"<td>"+row['prwt'][i].JABATAN+"</td>"+
							"<td align='center'>"+aksi+"</td>"+
						"</tr>";
			}

			$('#tb_perawat_ubah tbody').html($tr);
		}
	});

	$('#batal_ubah').click(function(){
		$('#view_data').show();
		$('#view_ubah').hide();
		$('#ket').val("");
		$('#id_ubah').val("");
	});
}

function hapus_poli(id){
	$('#popup_hps').click();

	$.ajax({
		url : '<?php echo base_url(); ?>setup/admum_poli_c/data_poli_id',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(row){
			$('#id_hapus').val(id);
			var nama_pasien = row['poli']['NAMA'];
			$('#msg').html('Apakah poli <b>'+nama_pasien+'</b> ingin dihapus?');
		}
	});
}

function detail_perawat(id){
	$('#popup_detail_perawat').click();

	$.ajax({
		url : '<?php echo base_url(); ?>setup/admum_poli_c/data_poli_perawat',
		data : {id:id},
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

					$tr += "<tr>"+
								"<td style='text-align:center;'>"+no+"</td>"+
								"<td style='text-align:center;'>"+result[i].NIP+"</td>"+
								"<td>"+result[i].NAMA_PEGAWAI+"</td>"+
								"<td>"+result[i].JABATAN+"</td>"+
								"<td>"+result[i].NAMA_POLI+"</td>"+
							"</tr>";
				}
			}

			$('#tabel_detail_perawat tbody').html($tr);
		}
	});
}

function load_perawat(){
	var keyword = $('#cari_perawat').val();

	if(ajax){
		ajax.abort();
	}

	ajax = $.ajax({
		url : '<?php echo base_url(); ?>setup/admum_poli_c/load_perawat',
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

					result[i].NAMA_POLI = result[i].NAMA_POLI==null?"-":result[i].NAMA_POLI;
					result[i].JABATAN = result[i].JABATAN==null?"-":result[i].JABATAN;

					$tr +=  '<tr style="cursor:pointer;" onclick="klik_perawat('+result[i].ID+');">'+
		                    '	<td style="text-align:center;">'+no+'</td>'+
		                    '   <td style="text-align:center;">'+result[i].NIP+'</td>'+
		                    '   <td>'+result[i].NAMA+'</td>'+
		                    '   <td>'+result[i].JABATAN+'</td>'+
		                    '   <td>'+result[i].NAMA_POLI+'</td>'+
		                    '</tr>';
				}
			}

			$('#tabel_perawat tbody').html($tr);
		}
	});

	$('#cari_perawat').off('keyup').keyup(function(){
		load_perawat();
	});
}

function klik_perawat(id){
	$('#tutup_perawat').click();

	$.ajax({
		url : '<?php echo base_url(); ?>setup/admum_poli_c/klik_perawat',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(result){
			$tr = "";
			var ket = $('#ket').val();

			if(ket == 'Tambah'){
				for(var i=0; i<result.length; i++){
					var jumlah_data = $('#tr_'+result[i].ID).length;

					var aksi = "<button type='button' class='btn waves-light btn-danger btn-sm' onclick='deleteRow(this);'><i class='fa fa-times'></i></button>";

					if(jumlah_data > 0){

					}else{
						$tr = "<tr id='tr_"+result[i].ID+"'>"+
								"<input type='hidden' name='id_perawat[]' value='"+result[i].ID+"'>"+
								"<td style='vertical-align:middle;'>"+result[i].NIP+"</td>"+
								"<td style='vertical-align:middle;'>"+result[i].NAMA+"</td>"+
								"<td style='vertical-align:middle;'>"+result[i].JABATAN+"</td>"+
								"<td align='center'>"+aksi+"</td>"+
							  "</tr>";
					}
				}

				$('#tb_perawat tbody').append($tr);
			}else{
				for(var i=0; i<result.length; i++){
					var jumlah_data = $('#tr2_'+result[i].ID).length;

					var aksi = "<button type='button' class='btn waves-light btn-danger btn-sm' onclick='deleteRow(this);'><i class='fa fa-times'></i></button>";

					if(jumlah_data > 0){

					}else{
						$tr = "<tr id='tr2_"+result[i].ID+"'>"+
								"<input type='hidden' name='id_perawat_ubah[]' value='"+result[i].ID+"'>"+
								"<td style='vertical-align:middle;'>"+result[i].NIP+"</td>"+
								"<td style='vertical-align:middle;'>"+result[i].NAMA+"</td>"+
								"<td style='vertical-align:middle;'>"+result[i].JABATAN+"</td>"+
								"<td align='center'>"+aksi+"</td>"+
							  "</tr>";
					}
				}

				$('#tb_perawat_ubah tbody').append($tr);
			}
		}
	});
}

function deleteRow(btn){
  	var row = btn.parentNode.parentNode;
  	row.parentNode.removeChild(row);
}

function hapus_perawat(btn,id){
	var row = btn.parentNode.parentNode;
  	row.parentNode.removeChild(row);

  	$.ajax({
  		url : '<?php echo base_url(); ?>setup/admum_poli_c/hapus_perawat',
  		data : {id:id},
  		type : "POST",
  		dataType : "json",
  		success : function(result){
  			notif_hapus();
  			data_poli();
  		}
  	});
}
</script>

<div id="popup_load">
    <div class="window_load">
        <img src="<?=base_url()?>picture/progress.gif" height="100" width="125">
    </div>
</div>

<input type="hidden" id="ket" value="">

<div class="col-lg-12" id="view_data">
	<div class="card-box">
		<div class="row">
			<ul class="nav nav-tabs">
                <li role="presentation" class="active">
                    <a href="#home1" role="tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-table"></i> Data Poli</a>
                </li>
                <li role="presentation" id="tambah_poli">
                    <a href="#profile1" role="tab" data-toggle="tab" aria-expanded="false"><i class="fa fa-plus"></i> Tambah Poli</a>
                </li>
            </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade in active" id="home1">
                	<div class="row">
	                    <div class="col-md-12">
	                    	<form class="form-horizontal" role="form" action="<?php echo $url_cetak; ?>" target="_blank" method="post">
	                    		<div class="form-group">
					            	<label class="col-md-1 control-label" style="text-align:left; width: 9%;">Urutkan</label>
	                    			<div class="col-md-3">
		                    			<div class="radio radio-purple radio-inline">
			                                <input type="radio" name="urutkan" value="Default" id="default" checked="checked">
			                                <label for="default"> Default </label>
			                            </div>
		                    			<div class="radio radio-purple radio-inline">
			                                <input type="radio" name="urutkan" value="Nama Poli" id="nama_poli">
			                                <label for="nama_poli"> Nama Poli </label>
			                            </div>
	                    			</div>
					            </div>
					            <div class="form-group">
					            	<label class="col-md-1 control-label" style="text-align:left; width: 9%;">Cari Berdasarkan :</label>
					            	<div class="col-md-3">
		                    			<div class="radio radio-purple radio-inline">
			                                <input type="radio" name="cari_berdasarkan" value="Nama Poli">
			                                <label for="cari_nama_poli"> Nama Poli </label>
			                            </div>
		                                <div class="radio radio-purple radio-inline">
			                                <input type="radio" name="cari_berdasarkan" value="Jenis">
			                                <label for="cari_jenis"> Jenis </label>
			                            </div>
	                    			</div>
					            </div>
					            <div class="form-group">
					            	<div class="col-md-12">
					            		<div class="input-group">
						                    <input type="text" class="form-control" id="cari_poli" placeholder="Cari..." value="" onkeypress="return onEnterText(event);">
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
					            <div class="form-group" id="view_jenis">
					            	<label class="col-md-2 control-label">&nbsp;</label>
					            	<div class="col-md-3">
					            		<select class="form-control" id="pilih_jenis">
					            		<?php
					            			$jenis_poli = $this->model->data_jenis_poli();
					            			foreach ($jenis_poli as $key => $value) {
					            		?>
					            			<option value="<?php echo $value->JENIS; ?>"><?php echo $value->JENIS; ?></option>
					            		<?php
					            			}
					            		?>
			                            </select>
					            	</div>
					            </div>
					            <div class="form-group">
					            	<div class="col-md-12">
					            		<div class="table-responsive">
								            <table id="tabel_poli" class="table table-hover table-bordered">
								                <thead>
								                    <tr class="biru">
								                        <th style="color:#fff; text-align:center;">No</th>
								                        <th style="color:#fff; text-align:center;">Nama Poli</th>
								                        <th style="color:#fff; text-align:center;">Jenis</th>
								                        <th style="color:#fff; text-align:center;">Keterangan</th>
								                        <th style="color:#fff; text-align:center;">Nama Dokter</th>
								                        <th style="color:#fff; text-align:center;">Perawat</th>
								                        <th style="color:#fff; text-align:center;">Biaya</th>
								                        <th style="color:#fff; text-align:center;">Aksi</th>
								                    </tr>
								                </thead>

								                <tbody>
								                    
								                </tbody>
								            </table>
								        </div>
					            	</div>
					            </div>
					        	<div class="form-group">
					        		<div class="col-md-9">
					        			<div id="tablePaging"> </div>
					        		</div>
			                    </div>
			                    <div class="form-group">
					        		<div class="col-md-9">
					        			<div class="radio radio-danger radio-inline">
			                                <input type="radio" name="cetak" value="PDF">
			                                <label for="cari_nama_poli"> PDF </label>
			                            </div>
		                                <div class="radio radio-success radio-inline">
			                                <input type="radio" name="cetak" value="Excel">
			                                <label for="cari_jenis"> Excel </label>
			                            </div>
			                            <button type="submit" class="btn btn-primary waves-effect waves-light m-l-10">
	                    					<i class="fa fa-print"></i>
	                    				</button>
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
	                </div>
                </div>

                <div role="tabpanel" class="tab-pane fade" id="profile1">
                	<div class="row">
	                    <div class="col-md-12">
	                        <form class="form-horizontal" role="form" action="<?php echo $url_simpan; ?>" method="post">
	                        	<div class="form-group">
					                <label class="col-md-2 control-label">Departemen</label>
					                <div class="col-md-4">
					                	<div class="input-group">
					                		<input type="hidden" name="id_departemen" id="id_departemen" value="">
					                        <span class="input-group-addon"><i class="fa fa-bank"></i></span>
					                    	<input type="text" class="form-control" id="departemen" value="" readonly>
					                    	<span class="input-group-addon btn-danger btn_dep" style="cursor:pointer;">
					                    		<i class="fa fa-search"></i>
					                    	</span>
					                    </div>
					                </div>
					            </div>
					            <div class="form-group">
					                <label class="col-md-2 control-label">Divisi</label>
					                <div class="col-md-4">
					                	<div class="input-group">
					                		<input type="hidden" name="id_divisi" id="id_divisi" value="">
					                        <span class="input-group-addon"><i class="fa fa-home"></i></span>
					                    	<input type="text" class="form-control" id="divisi" value="" readonly>
					                    	<span class="input-group-addon btn-primary btn_div" style="cursor:pointer;">
					                    		<i class="fa fa-search"></i>
					                    	</span>
					                    </div>
					                </div>
					            </div>
					            <div class="form-group">
			                        <label class="col-md-2 control-label">Jenis</label>
			                        <div class="col-md-3">
			                            <select class="form-control" name="jenis" id="jenis">
			                            <?php
					            			$jenis_poli = $this->model->data_jenis_poli();
					            			foreach ($jenis_poli as $key => $value) {
					            		?>
					            			<option value="<?php echo $value->JENIS; ?>"><?php echo $value->JENIS; ?></option>
					            		<?php
					            			}
					            		?>
			                            </select>
			                        </div>
			                    </div>
	                        	<div class="form-group">
			                        <label class="col-md-2 control-label">Nama Poli</label>
			                        <div class="col-md-6">
			                            <input type="text" class="form-control" name="nama_poli" value="" required="required">
			                        </div>
			                    </div>
			                    <div class="form-group">
			                        <label class="col-md-2 control-label">Status</label>
			                        <div class="col-md-3">
			                        	<select class="form-control" name="status" id="status">
			                        		<option value="Normal">Normal</option>
			                        		<option value="Tarif A">Tarif A</option>
			                        		<option value="Tarif B">Tarif B</option>
			                        		<option value="Tarif C">Tarif C</option>
			                        		<option value="Tarif D">Tarif D</option>
			                        		<option value="Malam">Malam</option>
			                        		<option value="Tarif Emergency">Tarif Emergency</option>
			                        		<option value="Cito">CITO (Darurat)</option>
			                        		<option value="Konsultasi Melalui Telepon">Konsultasi Melalui Telepon</option>
			                        	</select>
			                        </div>
			                    </div>
			                    <div class="form-group">
			                        <label class="col-md-2 control-label">Keterangan</label>
			                        <div class="col-md-6">
			                            <input type="text" class="form-control" name="keterangan" id="keterangan" value="">
			                        </div>
			                    </div>
			                    <div class="form-group">
			                        <label class="col-md-2 control-label">Biaya</label>
			                        <div class="col-md-6">
			                            <input type="text" class="form-control" name="biaya" value="" onkeyup="FormatCurrency(this);" required>
			                        </div>
			                    </div>
			                    <div class="form-group">
					                <label class="col-md-2 control-label">Dokter P. Jawab</label>
					                <div class="col-md-4">
					                	<div class="input-group">
					                		<input type="hidden" name="id_peg_dokter" id="id_peg_dokter" value="">
					                        <span class="input-group-addon"><i class="fa fa-user-md"></i></span>
					                    	<input type="text" class="form-control" id="nama_dokter" value="" readonly>
					                    	<span class="input-group-addon btn-success btn_dokter" style="cursor:pointer;">
					                    		<i class="fa fa-search"></i>
					                    	</span>
					                    </div>
					                </div>
					            </div>
					            <div class="form-group">
					            	<label class="col-md-2 control-label">Perawat</label>
					            	<div class="col-md-4">
					            		<button type="button" class="btn btn-warning m-b-5 btn_cari_perawat"> <i class="fa fa-search"></i> Cari</button>
					            	</div>
					            </div>
					            <div class="form-group">
					            	<label class="col-md-2 control-label">&nbsp;</label>
					            	<div class="col-md-6">
					            		<div class="table-responsive">
							                <table class="table table-bordered table-hover" id="tb_perawat">
							                    <thead>
							                        <tr class="biru">
							                            <th style="text-align:center; color: #fff;">NIP</th>
							                            <th style="text-align:center; color: #fff;">Nama Perawat</th>
							                            <th style="text-align:center; color: #fff;">Jabatan</th>
							                            <th style="text-align:center; color: #fff;">#</th>
							                        </tr>
							                    </thead>
							                    <tbody>
							                        
							                    </tbody>
							                </table>
						            	</div>
					            	</div>
					            </div>
					            <hr>
			                    <div class="form-group">
			                        <label class="col-md-2 control-label">&nbsp;</label>
			                        <div class="col-md-3">
			                        	<button type="submit" class="btn btn-success waves-effect waves-light m-b-5"> <i class="fa fa-save"></i> <b>Simpan</b></button>
			                        	<button type="button" class="btn btn-danger waves-effect waves-light m-b-5" id="batal"> <i class="fa fa-times"></i> <b>Batal</b></button>
			                        </div>
			                    </div>
				        	</form>
	                    </div>
	                </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-lg-12" id="view_ubah">
    <div class="card-box card-tabs">
    	<div class="row">
			<h4 class="header-title m-t-0 m-b-30">Ubah Data Poli</h4>
			<hr/>
        	<form class="form-horizontal" role="form" action="<?php echo $url_ubah; ?>" method="post">
        		<input type="hidden" name="id_ubah" id="id_ubah" value="">
        		<div class="form-group">
	                <label class="col-md-2 control-label">Departemen</label>
	                <div class="col-md-4">
	                	<div class="input-group">
	                		<input type="hidden" name="id_departemen_ubah" id="id_departemen_ubah" value="">
	                        <span class="input-group-addon"><i class="fa fa-bank"></i></span>
	                    	<input type="text" class="form-control" id="departemen_ubah" value="" readonly>
	                    	<span class="input-group-addon btn-danger btn_dep" style="cursor:pointer;">
	                    		<i class="fa fa-search"></i>
	                    	</span>
	                    </div>
	                </div>
	            </div>
	            <div class="form-group">
	                <label class="col-md-2 control-label">Divisi</label>
	                <div class="col-md-4">
	                	<div class="input-group">
	                		<input type="hidden" name="id_divisi_ubah" id="id_divisi_ubah" value="">
	                        <span class="input-group-addon"><i class="fa fa-home"></i></span>
	                    	<input type="text" class="form-control" id="divisi_ubah" value="" readonly>
	                    	<span class="input-group-addon btn-primary btn_div" style="cursor:pointer;">
	                    		<i class="fa fa-search"></i>
	                    	</span>
	                    </div>
	                </div>
	            </div>
	            <div class="form-group">
                    <label class="col-md-2 control-label">Jenis</label>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="jenis_txt" id="jenis_txt" value="" readonly>
                    </div>
                    <div class="col-md-2">
                    	<div class="checkbox checkbox-primary">
                    		<input type="hidden" name="cek_jenis_ubah" id="cek_jenis_ubah" value="">
                            <input type="checkbox" id="checkbox2_ubah">
                            <label for="checkbox2_ubah">
                                Ubah
                            </label>
                        </div>
                    </div>
                </div>
	            <div class="form-group" id="view_jenis_ubah">
                    <label class="col-md-2 control-label">&nbsp;</label>
                    <div class="col-md-3">
                        <select class="form-control" name="jenis_ubah" id="jenis_ubah">
                        <?php
	            			$jenis_poli = $this->model->data_jenis_poli();
	            			foreach ($jenis_poli as $key => $value) {
	            		?>
	            			<option value="<?php echo $value->JENIS; ?>"><?php echo $value->JENIS; ?></option>
	            		<?php
	            			}
	            		?>
                        </select>
                    </div>
                </div>
        		<div class="form-group">
                    <label class="col-md-2 control-label">Nama Poli</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="nama_poli_ubah" id="nama_poli_ubah" value="">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">Status</label>
                    <div class="col-md-3">
                    	<select class="form-control" name="status_ubah" id="status_ubah">
                    		<option value="Normal">Normal</option>
                    		<option value="Tarif A">Tarif A</option>
                    		<option value="Tarif B">Tarif B</option>
                    		<option value="Tarif C">Tarif C</option>
                    		<option value="Tarif D">Tarif D</option>
                    		<option value="Malam">Malam</option>
                    		<option value="Tarif Emergency">Tarif Emergency</option>
                    		<option value="Cito">CITO (Darurat)</option>
                    		<option value="Konsultasi Melalui Telepon">Konsultasi Melalui Telepon</option>
                    	</select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">Keterangan</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="keterangan_ubah" id="keterangan_ubah" value="">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">Biaya</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="biaya_ubah" id="biaya_ubah" value="" onkeyup="FormatCurrency(this);" required>
                    </div>
                </div>
                <div class="form-group">
	                <label class="col-md-2 control-label">Dokter P. Jawab</label>
	                <div class="col-md-4">
	                	<div class="input-group">
	                		<input type="hidden" name="id_peg_dokter_ubah" id="id_peg_dokter_ubah" value="">
	                        <span class="input-group-addon"><i class="fa fa-user-md"></i></span>
	                    	<input type="text" class="form-control" id="nama_dokter_txt" value="" readonly>
	                    	<span class="input-group-addon btn-success btn_dokter" style="cursor:pointer;">
	                    		<i class="fa fa-search"></i>
	                    	</span>
	                    </div>
	                </div>
	            </div>
	            <div class="form-group">
	                <label class="col-md-2 control-label">Perawat</label>
	                <div class="col-md-4">
	                	<button type="button" class="btn btn-warning waves-effect waves-light m-b-5 btn_cari_perawat"><i class="fa fa-search"></i> Cari</button>
	                </div>
	            </div>
	            <div class="form-group">
	            <label class="col-md-2 control-label">&nbsp;</label>
	                <div class="col-md-6">
	                	<div class="table-responsive">
			                <table class="table table-bordered table-hover" id="tb_perawat_ubah">
			                    <thead>
			                        <tr class="biru">
			                            <th style="text-align:center; color: #fff;">NIP</th>
			                            <th style="text-align:center; color: #fff;">Nama Perawat</th>
			                            <th style="text-align:center; color: #fff;">Jabatan</th>
			                            <th style="text-align:center; color: #fff;">#</th>
			                        </tr>
			                    </thead>
			                    <tbody>
			                        
			                    </tbody>
			                </table>
		            	</div>
	                </div>
	            </div>
	            <hr>
                <div class="form-group">
                    <label class="col-md-2 control-label">&nbsp;</label>
                    <div class="col-md-3">
                    	<button type="submit" class="btn btn-success waves-effect waves-light m-b-5"> <i class="fa fa-save"></i> <b>Simpan</b></button>
                    	<button type="button" class="btn btn-danger waves-effect waves-light m-b-5" id="batal_ubah"> <i class="fa fa-times"></i> <b>Batal</b></button>
                    </div>
                </div>
        	</form>
        </div>
    </div>
</div>

<button id="popup_hps" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#custom-width-modal" style="display:none;">Custom width Modal</button>
<div id="custom-width-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="width:55%;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="custom-width-modalLabel">Konfirmasi Hapus</h4>
            </div>
            <div class="modal-body">
                <p id="msg"></p>
            </div>
            <div class="modal-footer">
            	<form action="<?php echo $url_hapus; ?>" method="post">
            		<input type="hidden" name="id_hapus" id="id_hapus" value="">
	                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Tidak</button>
	                <button type="submit" class="btn btn-danger waves-effect waves-light">Ya</button>
            	</form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<button id="popup_dokter" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#myModal2" style="display:none;">Standard Modal</button>
<div id="myModal2" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width:55%;">
    	<div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Data Dokter</h4>
            </div>
        	<div class="modal-body">
        		<form class="form-horizontal" role="form">
        		<div class="form-group">
		                <div class="col-md-12">
		                	<div class="input-group">
		                    	<input type="text" class="form-control" id="cari_dokter" value="">
		                        <span class="input-group-addon"><i class="fa fa-search"></i></span>
		                    </div>
		                </div>
		            </div>
        		</form>
            	<div class="table-responsive">
            		<div class="scroll-y">
		                <table class="table table-bordered table-hover" id="tabel_dokter">
		                    <thead>
		                        <tr class="merah_popup">
		                            <th style="text-align:center; color: #fff;">No</th>
		                            <th style="text-align:center; color: #fff;">NIP</th>
		                            <th style="text-align:center; color: #fff;">Nama Dokter</th>
		                            <th style="text-align:center; color: #fff;">Jabatan</th>
		                            <th style="text-align:center; color: #fff;">Poli</th>
		                        </tr>
		                    </thead>
		                    <tbody>
		                        
		                    </tbody>
		                </table>
            		</div>
            	</div>
        	</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_dokter">Tutup</button>
            </div>
    	</div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<button id="popup_dep" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#myModal3" style="display:none;">Standard Modal</button>
<div id="myModal3" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
    	<div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Data Departemen</h4>
            </div>
        	<div class="modal-body">
        		<form class="form-horizontal" role="form">
        		<div class="form-group">
		                <div class="col-md-12">
		                	<div class="input-group">
		                    	<input type="text" class="form-control" id="cari_dep" value="">
		                        <span class="input-group-addon"><i class="fa fa-search"></i></span>
		                    </div>
		                </div>
		            </div>
        		</form>
            	<div class="table-responsive">
            		<div class="scroll-y">
		                <table class="table table-bordered table-hover" id="tabel_dep">
		                    <thead>
		                        <tr class="merah_popup">
		                            <th style="text-align:center; color: #fff;">No</th>
		                            <th style="text-align:center; color: #fff;">Departemen</th>
		                        </tr>
		                    </thead>
		                    <tbody>
		                        
		                    </tbody>
		                </table>
            		</div>
            	</div>
        	</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_dep">Tutup</button>
            </div>
    	</div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<button id="popup_div" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#myModal4" style="display:none;">Standard Modal</button>
<div id="myModal4" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
    	<div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Data Divisi</h4>
            </div>
        	<div class="modal-body">
        		<form class="form-horizontal" role="form">
        		<div class="form-group">
		                <div class="col-md-12">
		                	<div class="input-group">
		                    	<input type="text" class="form-control" id="cari_div" value="">
		                        <span class="input-group-addon"><i class="fa fa-search"></i></span>
		                    </div>
		                </div>
		            </div>
        		</form>
            	<div class="table-responsive">
            		<div class="scroll-y">
		                <table class="table table-bordered table-hover" id="tabel_div">
		                    <thead>
		                        <tr class="merah_popup">
		                            <th style="text-align:center; color: #fff;">No</th>
		                            <th style="text-align:center; color: #fff;">Divisi</th>
		                        </tr>
		                    </thead>
		                    <tbody>
		                        
		                    </tbody>
		                </table>
            		</div>
            	</div>
        	</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_div">Tutup</button>
            </div>
    	</div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<button id="popup_perawat" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#myModal5" style="display:none;">Standard Modal</button>
<div id="myModal5" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width:55%;">
    	<div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Data Perawat</h4>
            </div>
        	<div class="modal-body">
        		<form class="form-horizontal" role="form">
        		<div class="form-group">
		                <div class="col-md-12">
		                	<div class="input-group">
		                    	<input type="text" class="form-control" id="cari_perawat" value="">
		                        <span class="input-group-addon"><i class="fa fa-search"></i></span>
		                    </div>
		                </div>
		            </div>
        		</form>
            	<div class="table-responsive">
            		<div class="scroll-y">
		                <table class="table table-bordered table-hover" id="tabel_perawat">
		                    <thead>
		                        <tr class="merah_popup">
		                            <th style="text-align:center; color: #fff;">No</th>
		                            <th style="text-align:center; color: #fff;">NIP</th>
		                            <th style="text-align:center; color: #fff;">Nama Perawat</th>
		                            <th style="text-align:center; color: #fff;">Jabatan</th>
		                            <th style="text-align:center; color: #fff;">Poli</th>
		                        </tr>
		                    </thead>
		                    <tbody>
		                        
		                    </tbody>
		                </table>
            		</div>
            	</div>
        	</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_perawat">Tutup</button>
            </div>
    	</div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<button id="popup_detail_perawat" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#myModal6" style="display:none;">Standard Modal</button>
<div id="myModal6" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width:55%;">
    	<div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Detail Perawat</h4>
            </div>
        	<div class="modal-body">
            	<div class="table-responsive">
	                <table class="table table-bordered" id="tabel_detail_perawat">
	                    <thead>
	                        <tr class="hijau_popup">
	                            <th style="text-align:center; color: #fff;">No</th>
	                            <th style="text-align:center; color: #fff;">NIP</th>
	                            <th style="text-align:center; color: #fff;">Nama Perawat</th>
	                            <th style="text-align:center; color: #fff;">Jabatan</th>
	                            <th style="text-align:center; color: #fff;">Poli</th>
	                        </tr>
	                    </thead>
	                    <tbody>
	                        
	                    </tbody>
	                </table>
            	</div>
        	</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_detail_perawat">Tutup</button>
            </div>
    	</div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
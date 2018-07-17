<style type="text/css">
.coba .active a {
    background: #21AFDA !important;
    color: #fff !important;
}
</style>

<div class="row">
    <div class="col-sm-12">
        <div class="card-box">
            <form class="form-horizontal" role="form" method="post" action="<?=base_url().$post_url;?>" onsubmit="return cek_submit();">
            <div class="row"> 
                <div class="form-group">
                    <label class="col-md-3 control-label">Nama Tim</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="nama_tim" id="nama_tim" value="<?=$dt->NAMA_TIM;?>" required="required">
                        <input type="hidden" name="id_tim" id="id_tim" value="<?=$id_tim;?>">
                    </div>
                </div> 

                <div class="form-group">
                    <label class="col-md-3 control-label"> Ketua Tim </label>
                    <div class="col-md-6">
                        <div class="input-group">
                            <input id="nama_ketua" name="nama_ketua" class="form-control" type="text" readonly value="<?=$dt->KETUA;?>" style="background:#FFF;">
                            <span class="input-group-btn">
                            <button type="button" class="btn waves-effect waves-light btn-primary" onclick="show_pop_ketua();"> <i class="fa fa-search"></i> Pilih Ketua</button>
                            </span>
                        </div>
                        <input name="id_ketua" id="id_ketua" class="form-control" value="<?=$dt->ID_KETUA;?>" type="hidden">
                    </div>
                </div>                          

                <div class="form-group">
                    <label class="col-md-3 control-label">Anggota Tim</label>
                    <div class="col-md-6">
                        <div class="input-group">
                            <button type="button" class="btn btn-warning btn_perawat"><i class="fa fa-search"> <b> Cari Pegawai / Anggota</b> </i></button>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">&nbsp;</label>
                    <div class="col-md-6">
                        <div class="table-responsive">
                            <table id="tb_perawat" class="table table-hover table-bordered">
                                <thead>
                                    <tr class="biru">
                                        <th style="color:#fff; text-align:center;">NIP</th>
                                        <th style="color:#fff; text-align:center;">Nama Perawat</th>
                                        <th style="color:#fff; text-align:center;">#</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?PHP foreach ($dt_anggota as $key => $ag) { ?>
                                    <tr id='tr_<?=$ag->ID;?>'>
                                       <input type='hidden' name='id_anggota[]' value='<?=$ag->ID;?>'>
                                       <td style='vertical-align:middle;'><?=$ag->NIP;?></td>
                                       <td style='vertical-align:middle;'><?=$ag->NAMA;?></td>
                                       <td align='center'><button type='button' class='btn waves-light btn-danger btn-sm' onclick='deleteRow(this);'><i class='fa fa-times'></i></button></td>
                                    </tr>
                                    <?PHP } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <hr style="height: 1px; background:#ccc;">

                <div class="col-sm-12" style="margin-top: 15px;">
                    <div class="card-box table-responsive" style="background:#fff;">
                        <h4 class="header-title m-t-0"> <i class="fa fa-bed"></i> Tanggung Jawab Kamar </h4>
                        <hr>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Kamar</label>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <button type="button" class="btn btn-info btn_kamar"><i class="fa fa-search"> <b> Cari Kamar</b> </i></button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">&nbsp;</label>
                            <div class="col-md-6">
                                <div class="table-responsive">
                                    <table id="tb_kamar" class="table table-hover table-bordered">
                                        <thead>
                                            <tr style="background-color: #0079c1;">
                                                <th style="color:#fff; text-align:center;">Kode</th>
                                                <th style="color:#fff; text-align:center;">Nama Kamar</th>
                                                <th style="color:#fff; text-align:center;">Kelas</th>
                                                <th style="color:#fff; text-align:center;">#</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?PHP foreach ($dt_kamar as $key => $kmr) { ?>
                                            <tr id='tr_kamar_<?=$kmr->ID;?>'>
                                               <input type='hidden' name='id_kamar[]' value='<?=$kmr->ID;?>'>
                                               <td style='vertical-align:middle;'><?=$kmr->KODE_KAMAR;?></td>
                                               <td style='vertical-align:middle;'><?=$kmr->NAMA_KAMAR;?></td>
                                               <td style='vertical-align:middle;'><?=$kmr->KELAS;?></td>
                                               <td align='center'><button type='button' class='btn waves-light btn-danger btn-sm' onclick='deleteRowKamar(this);'><i class='fa fa-times'></i></button></td>
                                            </tr>
                                            <?PHP } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>                                      
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-offset-5 col-sm-10">
                        <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> <b>Simpan</b></button>
                        <button type="button" onclick="window.location='<?=base_url();?>kepeg/setup_tim_perawat_c';" class="btn btn-danger"><i class="fa fa-rotate-left"></i> <b>Kembali</b></button>
                    </div>
                </div>

            </div>
            </form>
        </div>
    </div><!-- end col -->
</div>

<!-- POPUP PERAWAT -->
<button class="btn btn-primary" data-toggle="modal" data-target="#myModal3" id="popup_perawat" style="display:none;">Standard Modal</button>
<div id="myModal3" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
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
			                    <input type="text" class="form-control" id="cari_perawat" placeholder="Cari..." value="">
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
		                <table class="table table-hover table-bordered" id="tabel_perawat">
		                    <thead>
		                        <tr class="kuning_popup">
		                            <th style="text-align:center; color: #fff;" width="50">No</th>
		                            <th style="text-align:center; color: #fff;">NIP</th>
		                            <th style="text-align:center; color: #fff;">Nama Perawat</th>
		                            <th style="text-align:center; color: #fff;">Departemen</th>
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
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_perawat">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- END OF POPUP PERAWAT -->

<!-- POPUP PERAWAT -->
<button class="btn btn-primary" data-toggle="modal" data-target="#myModal4" id="popup_kamar" style="display:none;">Standard Modal</button>
<div id="myModal4" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="width:55%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Data Kamar</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form">
		            <div class="form-group">
		                <div class="col-md-12">
			                <div class="input-group">
			                    <input type="text" class="form-control" id="cari_kamar" placeholder="Cari..." value="">
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
		                <table class="table table-hover table-bordered" id="tabel_kamar">
		                    <thead>
		                        <tr class="kuning_popup">
		                            <th style="text-align:center; color: #fff;" width="50">NO</th>
		                            <th style="text-align:center; color: #fff;">KODE</th>
		                            <th style="text-align:center; color: #fff;">NAMA KAMAR</th>
		                            <th style="text-align:center; color: #fff;">KELAS</th>
		                        </tr>
		                    </thead>
		                    <tbody>
		                        
		                    </tbody>
		                </table>
            		</div>
            	</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_kamar">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- END OF POPUP PERAWAT -->

<!-- HAPUS MODAL -->
<a id="dialog-btn" href="javascript:;" class="cd-popup-trigger" style="display:none;">View Pop-up</a>
<div class="cd-popup" role="alert">
    <div class="cd-popup-container">

        <form id="delete" method="post" action="<?=base_url().$post_url;?>">
            <input type="hidden" name="id_hapus" id="id_hapus" value="" />
        </form>   
         
        <p>Apakah anda yakin ingin menghapus data pegawai ini?</p>
        <ul class="cd-buttons">            
            <li><a href="javascript:;" onclick="$('#delete').submit();">Ya</a></li>
            <li><a onclick="$('.cd-popup-close').click(); $('#id_hapus').val('');" href="javascript:;">Tidak</a></li>
        </ul>
        <a href="#0" onclick="$('#id_hapus').val('');" class="cd-popup-close img-replace">Close</a>
    </div> <!-- cd-popup-container -->
</div> <!-- cd-popup -->
<!-- END HAPUS MODAL -->

<script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
<script type="text/javascript">
jQuery(document).ready(function() {
	$('.btn_perawat').click(function(){
		$('#popup_perawat').click();
		data_perawat();
	});

	$('.btn_kamar').click(function(){
		$('#popup_kamar').click();
		data_kamar();
	});
});

function hapus_tim(id){
    $('#id_hapus').val(id);
    $('#dialog-btn').click(); 
}

function data_perawat(){
	var keyword = $('#cari_perawat').val();

	$.ajax({
		url : '<?php echo base_url(); ?>admum/admum_ambulance_c/data_pegawai',
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

					$tr += "<tr style='cursor:pointer;' onclick='klik_perawat("+result[i].ID+");'>"+
								"<td style='text-align:center;'>"+no+"</td>"+
								"<td>"+result[i].NIP+"</td>"+
								"<td>"+result[i].NAMA_PEGAWAI+"</td>"+
								"<td>"+result[i].NAMA_DEP+"</td>"+
								"<td>"+result[i].NAMA_DIV+"</td>"+								
							"</tr>";
				}
			}
			$('#tabel_perawat tbody').html($tr);
		}
	});

	$('#cari_perawat').off('keyup').keyup(function(){
		data_perawat();
	});
}

function klik_perawat(id){
	$('#tutup_perawat').click();

	$.ajax({
		url : '<?php echo base_url(); ?>admum/admum_ambulance_c/klik_perawat',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(result){
			$tr = "";

			for(var i=0; i<result.length; i++){
				var jumlah_data = $('#tr_'+result[i].ID).length;

				var aksi = "<button type='button' class='btn waves-light btn-danger btn-sm' onclick='deleteRow(this);'><i class='fa fa-times'></i></button>";

				if(jumlah_data > 0){
					var jumlah = $('#jumlah_'+result[i].ID).val();
					$('#jumlah_'+result[i].ID).val(parseInt(jumlah)+1);
				}else{
					$tr = 
					"<tr id='tr_"+result[i].ID+"'>"+
					   "<input type='hidden' name='id_anggota[]' value='"+result[i].ID+"'>"+
					   "<td style='vertical-align:middle;'>"+result[i].NIP+"</td>"+
					   "<td style='vertical-align:middle;'>"+result[i].NAMA_PEGAWAI+"</td>"+
					   "<td align='center'>"+aksi+"</td>"+
				    "</tr>";
				}
			}

			$('#tb_perawat tbody').append($tr);
		}
	});
}

function deleteRow(btn){
  var row = btn.parentNode.parentNode;
  row.parentNode.removeChild(row);
}


function data_kamar(){
	var keyword = $('#cari_kamar').val();
	$.ajax({
		url : '<?php echo base_url(); ?>kepeg/setup_tim_perawat_c/data_kamar',
		data : {keyword:keyword},
		type : "POST",
		dataType : "json",
		success : function(result){
			$tr = "";

			if(result == "" || result == null){
				$tr = "<tr><td colspan='4' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
			}else{
				var no = 0;

				for(var i=0; i<result.length; i++){
					no++;

					$tr += "<tr style='cursor:pointer;' onclick='klik_kamar("+result[i].ID+");'>"+
								"<td style='text-align:center;'>"+no+"</td>"+
								"<td>"+result[i].KODE_KAMAR+"</td>"+
								"<td>"+result[i].NAMA_KAMAR+"</td>"+
								"<td>"+result[i].KELAS+"</td>"+							
							"</tr>";
				}
			}
			$('#tabel_kamar tbody').html($tr);
		}
	});

	$('#cari_kamar').off('keyup').keyup(function(){
		data_kamar();
	});
}

function klik_kamar(id){
	$('#tutup_kamar').click();
	$.ajax({
		url : '<?php echo base_url(); ?>kepeg/setup_tim_perawat_c/klik_kamar',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(result){
			$tr = "";

			for(var i=0; i<result.length; i++){
				var jumlah_data = $('#tr_kamar_'+result[i].ID).length;

				var aksi = "<button type='button' class='btn waves-light btn-danger btn-sm' onclick='deleteRowKamar(this);'><i class='fa fa-times'></i></button>";

				if(jumlah_data == 0){
					$tr = 
					"<tr id='tr_kamar_"+result[i].ID+"'>"+
					   "<input type='hidden' name='id_kamar[]' value='"+result[i].ID+"'>"+
					   "<td style='vertical-align:middle;'>"+result[i].KODE_KAMAR+"</td>"+
					   "<td style='vertical-align:middle;'>"+result[i].NAMA_KAMAR+"</td>"+
					   "<td style='vertical-align:middle;'>"+result[i].KELAS+"</td>"+
					   "<td align='center'>"+aksi+"</td>"+
				    "</tr>";
				}
			}

			$('#tb_kamar tbody').append($tr);
		}
	});
}

function deleteRowKamar(btn){
  var row = btn.parentNode.parentNode;
  row.parentNode.removeChild(row);
}


function show_pop_ketua(){
    get_popup_ketua();
    ajax_ketua();
}

function get_popup_ketua(){
    var base_url = '<?php echo base_url(); ?>';
    var $isi = '<div id="popup_koang">'+
                '<div class="window_koang">'+
                '    <a href="javascript:void(0);"><img src="'+base_url+'assets/custom/ico/cancel.gif" id="pojok_koang"></a>'+
                '    <div class="panel-body">'+
                '    <input style="width: 95%;" type="text" name="search_koang" id="search_koang" class="form-control" value="" placeholder="Cari Pegawai...">'+
                '    <div class="table-responsive">'+
                '		<div class="scroll-y">'+
                '            <table class="table table-hover2" id="tes5">'+
                '                <thead>'+
                '                    <tr>'+
                '                        <th style="text-align:center;">NO</th>'+
                '                        <th style="text-align:center;" style="white-space:nowrap;"> NIP </th>'+
                '                        <th style="text-align:center;"> NAMA </th>'+
                '                        <th style="text-align:center;"> JABATAN </th>'+
                '                    </tr>'+
                '                </thead>'+
                '                <tbody>'+
            
                '                </tbody>'+
                '            </table>'+
                '        </div>'+
                '    </div>'+
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

function ajax_ketua(){
    var keyword = $('#search_koang').val();
    $.ajax({
        url : '<?php echo base_url(); ?>admum/admum_ambulance_c/data_pegawai',
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
                isine += '<tr onclick="get_data_ketua('+res.ID+');" style="cursor:pointer;">'+
                            '<td align="center">'+no+'</td>'+
                            '<td align="center">'+res.NIP+'</td>'+
                            '<td align="left">'+res.NAMA_PEGAWAI+'</td>'+
                            '<td align="center">'+res.JABATAN+'</td>'+
                        '</tr>';
            });

            if(result.length == 0){
                isine = "<tr><td colspan='4' style='text-align:center'><b style='font-size: 15px;'> Data tidak tersedia </b></td></tr>";
            }

            $('#tes5 tbody').html(isine); 
            $('#search_koang').off('keyup').keyup(function(){
                ajax_ketua();
            });
        }
    });
}

function get_data_ketua(id){
    $.ajax({
        url : '<?php echo base_url(); ?>setting/login_pengguna_c/get_data_pegawai',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(res){
            $('#nama_ketua').val(res.NAMA);;
            $('#id_ketua').val(id);
            $('#popup_koang').remove();
        }
    });

}


</script>
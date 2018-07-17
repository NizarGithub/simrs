<style type="text/css">
.coba .active a {
	background: #21AFDA !important;
    color: #fff !important;
}

</style>

<div id="popup_load">
    <div class="window_load">
        <img src="<?=base_url()?>picture/progress.gif" height="100" width="125">
    </div> 
</div> 

<div class="row">
    <div class="col-sm-12">
        <div class="card-box"> 
			<div class="row">
			    <div class="col-lg-12">
			            	<form method="post" class="form-horizontal" role="form" id="form_jab_nilai" action="<?=base_url().$post_url;?>/save_nilai_jab">
				                <div class="row">
				                    <div class="col-lg-12">
				                    	<div class="form-group">
			                                <label class="col-md-3 control-label" style="color: #0099e5;"> Jabatan </label>
			                                <div class="col-md-6">
			                                    <select class="form-control select2" name="id_jabatan" id="id_jabatan" onchange="get_jab_nilai(this.value);">
			                                                <option value="0"> -- Pilih Jabatan </option>
			                                            <?PHP 
			                                                foreach ($get_jabatan as $key => $jab) { 
			                                            ?>
			                                                <option value="<?=$jab->ID;?>"><?=$jab->NAMA;?></option>

			                                            <?PHP } ?>
			                                    </select>
			                                </div>
			                            </div>

			                            <div id="jab_head">

			                            </div>

			                            <div class="modal-footer">
				                            <center>
				                                <button onclick="save_jab_nilai();" type="button" class="btn btn-primary waves-effect w-md waves-light m-b-5"> Simpan </button>
				                            </center>
				                        </div>
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

<script type="text/javascript" src="<?php echo base_url(); ?>js-devan/jquery-1.11.1.min.js"></script>
<script type="text/javascript">
function get_jab_nilai(id_jabatan) { 
	$('#popup_load').show();

	if(id_jabatan == 0){
		$('#jab_head').html('');
		$('#popup_load').hide();
	} else {
	    $.ajax({
	        url : '<?php echo base_url(); ?>kepeg/setup_nilai_gaji_c/get_jab_nilai',
	        data : {id_jabatan:id_jabatan},
	        type : "POST",
	        dataType : "json",
	        success : function(result){
	        	
	        	var isine = "<hr>";
	            $.each(result,function(i,res){

	            	if(res.NAMA_GAJI == 'Denda'){

	            		var cek1 = "";
	            		var cek2 = "";

	            		if(res.NILAI == 1){
	            			cek1 = "";
							cek2 = "checked";
	            		} else {
	            			cek1 = "checked";
							cek2 = "";
	            		}

	            		isine += '<div class="form-group">'+
	                                '<label class="col-md-3 control-label" > Keberlakuan Denda </label>'+
	                                '<div class="col-md-9">'+
	                                	'<input class="form-control" value="'+res.ID+'" type="hidden" name="id_gaji[]">'+
	                                    '<div class="radio radio-info radio-inline">'+
	                                        '<input type="radio" id="inlineRadio1" value="0" name="nilai[]" '+cek1+' >'+
	                                        '<label for="inlineRadio1"> Berlaku </label>'+
	                                    '</div>'+
	                                    '<div class="radio radio-danger radio-inline">'+
	                                        '<input type="radio" id="inlineRadio2" value="1" name="nilai[]" '+cek2+'>'+
	                                        '<label for="inlineRadio2"> Tidak Berlaku </label>'+
	                                    '</div>'+
	                                '</div>'+
	                            '</div>';
	            	} else {
	            		isine += '<div class="form-group">'+
		                            '<label class="col-md-3 control-label"> '+res.NAMA_GAJI+' </label>'+
		                            '<div class="col-md-6">'+
		                                '<input onkeyup="FormatCurrency(this);" class="form-control" value="'+NumberToMoney(res.NILAI).split('.00').join('')+'" type="text" name="nilai[]">'+
		                                '<input class="form-control" value="'+res.ID+'" type="hidden" name="id_gaji[]">'+
		                            '</div>'+
	                        	  '</div>';
	            	}
	            	

	            });

	            $('#jab_head').html(isine);
	            $('#popup_load').hide();
	        }
	    });
	}
}

function show_pop_pegawai(){
    get_popup_pegawai();
    ajax_pegawai();
}

function get_popup_pegawai(){
    var base_url = '<?php echo base_url(); ?>';
    var $isi = '<div id="popup_koang">'+
                '<div class="window_koang">'+
                '    <a href="javascript:void(0);"><img src="'+base_url+'assets/custom/ico/cancel.gif" id="pojok_koang"></a>'+
                '    <div class="panel-body">'+
                '    <input style="width: 95%;" type="text" name="search_koang" id="search_koang" class="form-control" value="" placeholder="Cari Pegawai...">'+
                '    <div class="table-responsive">'+
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

function ajax_pegawai(){
    var keyword = $('#search_koang').val();
    $.ajax({
        url : '<?php echo base_url(); ?>kepeg/setup_nilai_gaji_c/get_pegawai',
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
                var username = res.USERNAME;
                if(username == "" || username == null){
                    username = "(Tanpa username)";
                }

                isine += '<tr onclick="get_data_pegawai('+res.ID+');" style="cursor:pointer;">'+
                            '<td align="center">'+no+'</td>'+
                            '<td align="center">'+res.NIP+'</td>'+
                            '<td align="left">'+res.NAMA+'</td>'+
                            '<td align="center">'+res.JABATAN+'</td>'+
                        '</tr>';
            });

            if(result.length == 0){
                isine = "<tr><td colspan='4' style='text-align:center'><b style='font-size: 15px;'> Data tidak tersedia </b></td></tr>";
            }

            $('#tes5 tbody').html(isine); 
            $('#search_koang').off('keyup').keyup(function(){
                ajax_pegawai();
            });
        }
    });
}

function get_data_pegawai(id){
	$('#popup_load').show();
    $.ajax({
        url : '<?php echo base_url(); ?>kepeg/login_pengguna_c/get_data_pegawai',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(res){            
            $('#nama_pegawai').val(res.NAMA);
            $('#id_pegawai').val(id);
            $('#foto_head').html('<img width="120" src="<?=base_url();?>files/foto_pegawai/'+res.FOTO+'">');
            $('#img_peg').show();
            $('#popup_koang').remove();
            $('#popup_load').hide();
            

            get_peg_nilai(id, res.ID_JABATAN);
        }
    });

}

function get_peg_nilai(id_pegawai, id_jabatan) { 
	$('#popup_load').show();
    $.ajax({
        url : '<?php echo base_url(); ?>kepeg/setup_nilai_gaji_c/get_peg_nilai',
        data : {
        		id_pegawai:id_pegawai,
        		id_jabatan:id_jabatan,
        },
        type : "POST",
        dataType : "json",
        success : function(result){
        	var isine = "<hr>";
            $.each(result,function(i,res){

            	if(res.NAMA_GAJI == 'Denda'){

            		var cek1 = "";
            		var cek2 = "";

            		if(res.NILAI == 1){
            			cek1 = "";
						cek2 = "checked";
            		} else {
            			cek1 = "checked";
						cek2 = "";
            		}

            		isine += '<div class="form-group">'+
                                '<label class="col-md-3 control-label"> Keberlakuan Denda </label>'+
                                '<div class="col-md-9">'+
                                	'<input class="form-control" value="'+res.ID+'" type="hidden" name="id_gaji_peg[]">'+
                                    '<div class="radio radio-info radio-inline">'+
                                        '<input type="radio" id="inlineRadio1" value="0" name="nilai_peg[]" '+cek1+' >'+
                                        '<label for="inlineRadio1"> Berlaku </label>'+
                                    '</div>'+
                                    '<div class="radio radio-danger radio-inline">'+
                                        '<input type="radio" id="inlineRadio2" value="1" name="nilai_peg[]" '+cek2+'>'+
                                        '<label for="inlineRadio2"> Tidak Berlaku </label>'+
                                    '</div>'+
                                '</div>'+
                            '</div>';
            	} else {
            		isine += '<div class="form-group">'+
	                            '<label class="col-md-3 control-label"> '+res.NAMA_GAJI+' </label>'+
	                            '<div class="col-md-6">'+
	                                '<input onkeyup="FormatCurrency(this);" class="form-control" value="'+NumberToMoney(res.NILAI).split('.00').join('')+'" type="text" name="nilai_peg[]">'+
	                                '<input class="form-control" value="'+res.ID+'" type="hidden" name="id_gaji_peg[]">'+
	                            '</div>'+
                        	  '</div>';
            	}
            	

            });

            $('#peg_head').html(isine);
            $('#popup_load').hide();
        }
    });
}


function save_jab_nilai(){

	var id_jabatan = $('#id_jabatan').val();

	if(id_jabatan == 0){
		alert('Silahkan pilih jabatan terlebih dahulu');
	} else {
		$('#popup_load').show();
		var form = $('#form_jab_nilai');

		$.ajax({
		      type: "POST",
		      url: form.attr('action'),
		      data: form.serialize(),
		      success: function(response){

		      			$('#popup_load').hide();
		      			notif_simpan();
		      		
		      }
	    });
	}
}

function save_peg_nilai(){

	var id_pegawai = $('#id_pegawai').val();

	if(id_pegawai == ""){
		alert('Silahkan pilih pegawai terlebih dahulu');
	} else {

		$('#popup_load').show();
		var form = $('#form_peg_nilai');

		$.ajax({
		      type: "POST",
		      url: form.attr('action'),
		      data: form.serialize(),
		      success: function(response){
	  			$('#popup_load').hide();
	  			notif_simpan();	      		
		      }
	    });
	}
}
</script>
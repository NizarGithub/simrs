<script type="text/javascript" src="<?php echo base_url(); ?>js-devan/jquery-1.11.1.min.js"></script>

<style type="text/css">
#view_jenis_lab{
	display: none;
}
</style>

<script type="text/javascript">
$(document).ready(function(){
	$(".num_only").keydown(function (e) {
		// Allow: backspace, delete, tab, escape, enter and .
		if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
			 // Allow: Ctrl+A, Command+A
			(e.keyCode == 65 && ( e.ctrlKey === true || e.metaKey === true ) ) || 
			 // Allow: home, end, left, right, down, up
			(e.keyCode >= 35 && e.keyCode <= 40)) {
				 // let it happen, don't do anything
				 return;
		}
		// Ensure that it is a number and stop the keypress
		if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
			e.preventDefault();
		}
	});

	$("input[name='filter']").click(function(){
		var filter = $("input[name='filter']:checked").val();
		if(filter == 'Semua'){
			$('#view_jenis_lab').hide();
			$('#id_lab').val("");
			$('#jenis_lab').val("");
		}else{
			$('#view_jenis_lab').show();
		}
	});

	$('.btn_jenis_lab').click(function(){
		$('#popup_laborat').click();
		load_laborat();
	});
});

function load_laborat(){
    var keyword = $('#cari_laborat').val();

    $.ajax({
        url : '<?php echo base_url(); ?>lab/lab_laporan_bulanan_c/load_laborat',
        data : {keyword:keyword},
        type : "POST",
        dataType : "json",
        success : function(result){
            $tr = "";

            if(result == "" || result == null){
                $tr = "<tr class='active'><td colspan='2' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
            }else{
                var no = 0;

                for(var i=0; i<result.length; i++){
                    no++;
                    var jenis_lab = '';

                    if(result[i].PER_ITEM == '1'){
                    	jenis_lab = result[i].JENIS_LABORAT+' (Per Item)';
                    }else{
                    	jenis_lab = result[i].JENIS_LABORAT;
                    }

                    $tr += "<tr style='cursor:pointer;' onclick='klik_laborat("+result[i].ID+");'>"+
                                "<td style='text-align:center;'>"+no+"</td>"+
                                "<td>"+jenis_lab+"</td>"+
                            "</tr>";
                }
            }

            $('#tb_laborat tbody').html($tr);
        }
    });

    $('#cari_laborat').off('keyup').keyup(function(){
        load_laborat();
    });
}

function klik_laborat(id){
    $('#tutup_laborat').click();

    $.ajax({
        url : '<?php echo base_url(); ?>lab/lab_laporan_bulanan_c/klik_laborat',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(row){
            $('#id_lab').val(id);
            $('#jenis_lab').val(row['JENIS_LABORAT']);
        }
    });
}
</script>

<div id="popup_load">
    <div class="window_load">
        <img src="<?=base_url()?>picture/progress.gif" height="100" width="125">
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="card-box">
            <h4 class="header-title m-t-0 m-b-30">
            	Laporan Laborat
            </h4>
            <form class="form-horizontal" role="form" action="<?php echo base_url(); ?>lab/lab_laporan_bulanan_c/cetak" method="post" target="blank">
            	<div class="form-group">
                    <label class="col-md-1 control-label" style="width: 5%; text-align: left;">Filter :</label>
                    <div class="col-md-4">
                        <div class="radio radio-info radio-inline">
                            <input type="radio" id="rd_semua" value="Semua" name="filter">
                            <label for="rd_semua"> Semua </label>
                        </div>
                        <div class="radio radio-info radio-inline">
                            <input type="radio" id="rd_jenis_lab" value="Jenis Lab" name="filter">
                            <label for="rd_jenis_lab"> Per Jenis Lab </label>
                        </div>
                    </div>
                </div>
                <div class="form-group" id="view_jenis_lab">
                    <label class="col-md-1 control-label" style="width: 5%; text-align: left;">&nbsp;</label>
                    <div class="col-md-3">
                        <div class="input-group">
                        	<input type="hidden" name="id_lab" id="id_lab" value="">
		                    <input type="text" class="form-control" id="jenis_lab" value="" readonly>
		                    <span class="input-group-btn">
		                        <button type="button" class="btn btn-danger btn_jenis_lab"><i class="fa fa-search"></i></button>
		                    </span>
		                  </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-1" style="text-align: left; width: 5%;">Bulan</label>
                    <div class="col-sm-2">
                        <select class="form-control" name="bulan">
                        <?php
                            $bulan = date('m');
                            $bln_arr = array('Pilih','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
                            for($i=0; $i<13; $i++){
                                $select = "";
                                if($i == $bulan){
                                    $select = "selected='selected'";
                                }else{
                                    $select = $select;
                                }
                        ?>
                            <option value="<?php echo $i; ?>" <?php echo $select; ?>><?php echo $bln_arr[$i]; ?></option>
                        <?php
                            }
                        ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <div class="input-group">
                            <input type="text" class="form-control num_only" name="tahun" value="" maxlength="4" placeholder="Tahun">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-1 control-label" style="width: 5%; text-align: left;">Laporan</label>
                    <div class="col-md-4">
                        <div class="radio radio-info radio-inline">
                            <input type="radio" id="pdf" value="pdf" name="laporan">
                            <label for="pdf"> PDF </label>
                        </div>
                        <div class="radio radio-info radio-inline">
                            <input type="radio" id="excel" value="excel" name="laporan">
                            <label for="excel"> Excel </label>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="form-group">
                	<label class="control-label col-sm-1" style="text-align: left; width: 5%;">&nbsp;</label>
                	<div class="col-md-2">
                		<button type="submit" class="btn btn-primary"><i class="fa fa-print"></i> <b>Cetak</b></button>
                	</div>
                </div>
            </form>
        </div>
    </div>
</div>

<button class="btn btn-primary" data-toggle="modal" data-target="#myModal1_laborat" id="popup_laborat" style="display:none;">Standard Modal</button>
<div id="myModal1_laborat" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="width:50%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Data Jenis Laborat</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form">
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="input-group">
                                <input type="text" class="form-control" id="cari_laborat" placeholder="Cari..." value="">
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
                          <table class="table table-hover table-bordered" id="tb_laborat">
                              <thead>
                                  <tr class="hijau_popup">
                                      <th style="text-align:center; color: #fff;" width="50">No</th>
                                      <th style="text-align:center; color: #fff;">Jenis Laborat</th>
                                  </tr>
                              </thead>
                              <tbody>
                                  
                              </tbody>
                          </table>
                      </div>
                  </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_laborat">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
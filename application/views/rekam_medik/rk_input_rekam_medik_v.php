<script type="text/javascript" src="<?php echo base_url(); ?>js-devan/jquery-1.11.1.min.js"></script>

<style type="text/css">
#view_poli{
    display: none;
}    
</style>

<script type="text/javascript">
var ajax = "";
$(document).ready(function(){
    toastr.options = {
      "closeButton": false,
      "debug": false,
      "newestOnTop": false,
      "progressBar": true,
      "positionClass": "toast-bottom-right", 
      "preventDuplicates": false,
      "onclick": null,
      "showDuration": "300",
      "hideDuration": "1000",
      "timeOut": "5000",
      "extendedTimeOut": "1000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut"
    }

    get_no_rk();

	$('.btn_pasien').click(function(){
		$('#popup_pasien').click();
		load_data_pasien();
	});

    $('.btn_jenis_penyakit').click(function(){
        $('#popup_jenis').click();
        get_jenis_penyakit();
    });

    $('.btn_poli').click(function(){
        $('#popup_poli').click();
        load_data_poli();
    });

    $('#batal').click(function(){
        window.location.reload();
    });

    $('#btn_lanjut').click(function(){
        var id_pasien = $('#id_pasien').val();
        var sakit = $('#sakit').val();
        var id_jenis_penyakit = $('#id_jenis_penyakit').val();
        var wali = $('#nama_wali').val();

        if(id_pasien == ""){
            toastr["error"]("Harap cari pasien terlebih dahulu!","Peringatan");
        }else if(sakit == ""){
            toastr["error"]("Harap isi penyakit pasien!","Peringatan");
        }else if(id_jenis_penyakit == ""){
            toastr["error"]("Harap isi jenis penyakit pasien!","Peringatan");
        }else if(wali == ""){
            toastr["error"]("Harap isi wali pasien!","Peringatan");
        }else{
            var no_rekam_medik = $('#no_rekam_medik').val();
            var id_pasien = $('#id_pasien').val();
            var sakit = $('#sakit').val();
            var id_jenis_penyakit = $('#id_jenis_penyakit').val();
            var tingkatan = $('#tingkatan_penyakit').val();
            var tanggal = $('#tanggal').val();
            var pilihan = $("input[name='pilihan_operasi']:checked").val();
            var nama_wali = $('#nama_wali').val();

            $.ajax({
                url : '<?php echo base_url(); ?>rekam_medik/rk_input_rekam_medik_c/simpan',
                data : {
                    no_rekam_medik:no_rekam_medik,
                    id_pasien:id_pasien,
                    sakit:sakit,
                    id_jenis_penyakit:id_jenis_penyakit,
                    tingkatan:tingkatan,
                    tanggal:tanggal,
                    pilihan:pilihan,
                    nama_wali:nama_wali
                },
                type : "POST",
                dataType : "json",
                success : function(result){
                    $('#view_data').hide();
                    $('#view_poli').show();
                    data_rekam_medik();
                }
            });
        }

    });

    $('#btn_batal2').click(function(){
        $('#view_poli').hide();
        $('#view_data').show();
        $('#id_poli').val("");
        $('#nama_poli').val("");
        $('#nama_dokter').val("");
    });
});

function get_no_rk(){
    $.ajax({
        url : '<?php echo base_url(); ?>rekam_medik/rk_input_rekam_medik_c/kode',
        type : "POST",
        dataType : "json",
        success : function(kode){
            $('#no_rekam_medik').val(kode);
        }
    });
}

function load_data_pasien(){
	var keyword = $('#cari_pasien').val();
	if(ajax){
		ajax.abort();
	}

	ajax = $.ajax({
		url : '<?php echo base_url(); ?>rekam_medik/rk_input_rekam_medik_c/load_data_pasien',
		data : {keyword:keyword},
		type : "POST",
		dataType : "json",
		success : function(result){
			$tr = "";

			if(result == "" || result == null){
				$tr = "<tr><td style='text-align:center;' colspan='5'><b>Data Tidak Ada</b></td></tr>";
			}else{
				var no = 0;

				for(var i=0; i<result.length; i++){
					no++; 

					var jk = "";
					if(result[i].JENIS_KELAMIN == "L"){
						jk = "Laki - Laki";
					}else{
						jk = "Perempuan";
					}

					$tr += "<tr style='cursor:pointer;' onclick='klik_pasien("+result[i].ID+");'>"+
								"<td style='text-align:center;'>"+no+"</td>"+
								"<td>"+result[i].KODE_PASIEN+"</td>"+
								"<td>"+result[i].NAMA+"</td>"+
								"<td style='text-align:center;'>"+jk+"</td>"+
								"<td style='text-align:center;'>"+result[i].UMUR+" Tahun</td>"+
							"</tr>";
				}
			}

			$('#tabel_pasien tbody').html($tr);
		}
	});
}

function klik_pasien(id){
	$('#tutup_pasien').click();

	$.ajax({
		url : '<?php echo base_url(); ?>rekam_medik/rk_input_rekam_medik_c/klik_pasien',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(row){
			$('#id_pasien').val(id);
			$('#nama_pasien').val(row['NAMA']);
			$('#kode_pasien').val(row['KODE_PASIEN']);

			var jk = "";
			if(row['JENIS_KELAMIN'] == "L"){
				jk = "Laki - Laki";
			}else{
				jk = "Perempuan";
			}

			$('#jenis_kelamin').val(jk);
			$('#golongan_darah').val(row['GOLONGAN_DARAH']);
			$('#umur').val(row['UMUR']);
			$('#jenis_pasien').val(row['JENIS_PASIEN']);
            var alamat = row['ALAMAT']+', '+row['KECAMATAN']+', '+row['KOTA'];
            $('#alamat').val(alamat);
		}
	});
}

function load_data_poli(){
    var keyword = $('#cari_poli').val();
    if(ajax){
        ajax.abort();
    }

    ajax = $.ajax({
        url : '<?php echo base_url(); ?>rekam_medik/rk_input_rekam_medik_c/load_data_poli',
        data : {keyword:keyword},
        type : "POST",
        dataType : "json",
        success : function(result){
            $tr = "";

            if(result == "" || result == null){
                $tr = "<tr><td style='text-align:center;' colspan='5'><b>Data Tidak Ada</b></td></tr>";
            }else{
                var no = 0;

                for(var i=0; i<result.length; i++){
                    no++;

                    result[i].NAMA_DOKTER = result[i].NAMA_DOKTER==null?"-":result[i].NAMA_DOKTER;

                    $tr += "<tr style='cursor:pointer;' onclick='klik_poli("+result[i].ID+");'>"+
                                "<td style='text-align:center;'>"+no+"</td>"+
                                "<td>"+result[i].NAMA_POLI+"</td>"+
                                "<td>"+result[i].NAMA_DOKTER+"</td>"+
                            "</tr>";
                }
            }

            $('#tabel_poli tbody').html($tr);
        }
    });
}

function klik_poli(id){
    $('#tutup_poli').click();

    $.ajax({
        url : '<?php echo base_url(); ?>rekam_medik/rk_input_rekam_medik_c/klik_poli',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(row){
            $('#id_poli').val(id);
            $('#nama_poli').val(row['NAMA_POLI']);
            $('#nama_dokter').val(row['NAMA_DOKTER']);
        }
    });
}

function get_jenis_penyakit(){
    var keyword = $('#cari_jenis_penyakit').val();

    if(ajax){
        ajax.abort();
    }

    ajax = $.ajax({
        url : '<?php echo base_url(); ?>rekam_medik/rk_input_rekam_medik_c/data_jenis_penyakit',
        data : {keyword:keyword},
        type : "POST",
        dataType : "json",
        success : function(result){
            $tr = "";
            var ndagel = 0;

            for(var i=0; i<result.length; i++){
                ndagel++;

                $tr += "<tr style='cursor:pointer;' onclick='klik_jenis_penyakit("+result[i].ID+");'>"+
                            "<td style='text-align:center;'>"+ndagel+"</td>"+
                            "<td style='text-align:center;'>"+result[i].KODE+"</td>"+
                            "<td>"+result[i].URAIAN+"</td>"+
                        "</tr>";
            }

            $('#tabel_jenis_penyakit tbody').html($tr);
        }
    });

    $('#cari_jenis_penyakit').off('keyup').keyup(function(){
        get_jenis_penyakit();
    });
}

function klik_jenis_penyakit(id){
    $('#tutup_jenis_penyakit').click();

    $.ajax({
        url : '<?php echo base_url(); ?>rekam_medik/rk_input_rekam_medik_c/data_jenis_penyakit_id',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(row){
            $('#id_jenis_penyakit').val(id);
            $('#jenis_penyakit').val(row['URAIAN']);
        }
    });
}

function data_rekam_medik(){
    $.ajax({
        url : '<?php echo base_url(); ?>rekam_medik/rk_input_rekam_medik_c/get_data_rekam_medik',
        type : "POST",
        dataType : "json",
        success : function(row){
            $('#no_rekam_medik_poli').val(row['NO_REKAM_MEDIK']);
            $('#nama_pasien_poli').val(row['NAMA_PASIEN']);
        }
    });
}
</script>

<div id="popup_load">
    <div class="window_load">
        <img src="<?=base_url()?>picture/progress.gif" height="100" width="125">
    </div>
</div>

<div class="col-lg-12">
	<div class="card-box" id="view_data">
        <form class="form-horizontal" role="form" id="form_pasien">
            <h3 class="m-t-0 m-b-30 page-header header-title text-success"><b>Data Pasien</b></h3>
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="col-md-3 control-label">No. Rekam Medik</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" nama="no_rekam_medik" id="no_rekam_medik" value="" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Cari Pasien</label>
                        <div class="col-md-8">
                            <div class="input-group">
                                <input type="hidden" name="id_pasien" id="id_pasien" value="">
                                <input type="text" class="form-control" id="nama_pasien" value="" required="required" readonly>
                                <span class="input-group-btn">
                                    <button class="btn waves-effect waves-light btn-default btn_pasien" type="button">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Kode Pasien</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="kode_pasien" value="" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Jenis Kelamin</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="jenis_kelamin" value="" readonly>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="col-md-3 control-label">Golongan Darah</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="golongan_darah" value="" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Umur</label>
                        <div class="col-md-8">
                            <div class="input-group">
                                <input type="text" class="form-control" id="umur" value="" readonly>
                                <span class="input-group-btn">
                                    <button class="btn waves-effect waves-light btn-primary" type="button" style="cursor:pointer;">
                                        Tahun
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Jenis Pasien</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="jenis_pasien" value="" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Alamat</label>
                        <div class="col-md-8">
                            <textarea class="form-control" rows="3" id="alamat" readonly="readonly"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <h4 class="m-t-0 m-b-30 page-header header-title text-info"><b>Dokter Diagnosa</b></h4>
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="col-md-3 control-label">Sakit</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" nama="sakit" id="sakit" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Jenis Penyakit</label>
                        <div class="col-md-8">
                            <input type="hidden" name="id_jenis_penyakit" id="id_jenis_penyakit" value="">
                            <button type="button" class="btn btn-warning btn_jenis_penyakit"> 
                                <i class="fa fa-search"></i> <span>Cari</span>
                            </button>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">&nbsp;</label>
                        <div class="col-md-8">
                            <textarea class="form-control" rows="3" name="jenis_penyakit" id="jenis_penyakit" readonly="readonly"></textarea>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="col-md-3 control-label">Tingkatan</label>
                        <div class="col-md-8">
                            <select class="form-control" name="tingkatan_penyakit" id="tingkatan_penyakit">
                                <option value="1">Ringan</option>
                                <option value="2">Sedang</option>
                                <option value="3">Berat</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Tanggal</label>
                        <div class="col-md-8">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input type="text" class="form-control datepicker-here" name="tanggal" id="tanggal" value="<?php echo date('d-m-Y'); ?>" required="required" data-language="en" data-date-format="dd-mm-yyyy" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">&nbsp;</label>
                        <div class="col-md-8">
                            <div class="radio radio-success radio-inline">
                                <input type="radio" id="inlineRadio1" value="0" name="pilihan_operasi" id="rd_tidak_operasi" checked="checked">
                                <label for="inlineRadio1"> Tidak Operasi </label>
                            </div>
                            <div class="radio radio-success radio-inline">
                                <input type="radio" id="inlineRadio2" value="1" name="pilihan_operasi" id="rd_operasi">
                                <label for="inlineRadio2"> Operasi </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="col-md-3 control-label">Nama Wali</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" nama="nama_wali" id="nama_wali" value="">
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            <center>
                <button type="button" id="btn_lanjut" class="btn btn-primary m-b-5"> <i class="fa fa-mail-forward"></i> <span>Lanjut</span> </button>
                <button type="button" id="batal" class="btn btn-danger m-b-5" id="batal"> <i class="fa fa-times"></i> <span>Batal</span> </button>
            </center>
        </form>
    </div>

    <div class="card-box">
        <h4 class="m-t-0 m-b-30 page-header header-title"><b>Data Pasien</b></h4>
        <form class="form-horizontal" role="form">
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="col-md-3 control-label">No. Rekam Medik</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="no_rekam_medik_poli" id="no_rekam_medik_poli" value="" readonly="readonly">
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="col-md-3 control-label">Nama Pasien</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="nama_pasien_poli" id="nama_pasien_poli" value="" readonly="readonly">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="col-md-3 control-label">Cari Poli</label>
                        <div class="col-md-8">
                            <div class="input-group">
                                <input type="hidden" name="id_poli" id="id_poli" value="">
                                <input type="text" class="form-control" id="nama_poli" value="" required="required" readonly>
                                <span class="input-group-btn">
                                    <button class="btn waves-effect waves-light btn-default btn_poli" type="button">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="col-md-3 control-label">Dokter</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="nama_dokter" value="" readonly>
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            <center>
                <button type="button" id="btn_lanjut2" class="btn btn-success m-b-5"> <i class="fa fa-mail-forward"></i> <span>Lanjut</span> </button>
                <button type="button" id="btn_batal2" class="btn btn-danger m-b-5"> <i class="fa fa-times"></i> <span>Batal</span> </button>
            </center>
        </form>
    </div>
</div>

<!-- //LOAD PASIEN -->
<button class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#myModal1" id="popup_pasien" style="display:none;">Standard Modal</button>
<div id="myModal1" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Data Pasien</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form">
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="input-group">
                                <input type="text" class="form-control" id="cari_pasien" placeholder="Cari..." value="">
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
                        <table class="table table-hover table-bordered" id="tabel_pasien">
                            <thead>
                                <tr class="merah_popup">
                                    <th style="text-align:center; color: #fff;" width="50">No</th>
                                    <th style="text-align:center; color: #fff;">Kode Pasien</th>
                                    <th style="text-align:center; color: #fff;">Nama Pasien</th>
                                    <th style="text-align:center; color: #fff;">Jenis Kelamin</th>
                                    <th style="text-align:center; color: #fff;">Umur</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_pasien">Tutup</button>
            </div>
        </div>
    </div>
</div>
<!-- // -->

<!-- LOAD JENIS PENYAKIT -->
<button class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#myModal2" id="popup_jenis" style="display:none;">Standard Modal</button>
<div id="myModal2" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Data Jenis Penyakit</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form">
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="input-group">
                                <input type="text" class="form-control" id="cari_jenis_penyakit" placeholder="Cari..." value="">
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
                        <table class="table table-hover table-bordered" id="tabel_jenis_penyakit">
                            <thead>
                                <tr class="merah_popup">
                                    <th style="text-align:center; color: #fff;" width="50">No</th>
                                    <th style="text-align:center; color: #fff;">Kode</th>
                                    <th style="text-align:center; color: #fff;">Uraian</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_jenis_penyakit">Tutup</button>
            </div>
        </div>
    </div>
</div>
<!-- // -->

<!-- //LOAD DOKTER -->
<button class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#myModal3" id="popup_poli" style="display:none;">Standard Modal</button>
<div id="myModal3" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Data Poli</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form">
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="input-group">
                                <input type="text" class="form-control" id="cari_poli" placeholder="Cari..." value="">
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
                        <table class="table table-hover table-bordered" id="tabel_poli">
                            <thead>
                                <tr class="merah_popup">
                                    <th style="text-align:center; color: #fff;" width="50">No</th>
                                    <th style="text-align:center; color: #fff;">Nama Poli</th>
                                    <th style="text-align:center; color: #fff;">Nama Dokter</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_poli">Tutup</button>
            </div>
        </div>
    </div>
</div>
<!-- // -->
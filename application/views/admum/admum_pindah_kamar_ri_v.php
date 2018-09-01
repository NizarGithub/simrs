<script type="text/javascript" src="<?php echo base_url(); ?>js-devan/jquery-1.11.1.min.js"></script>

<script type="text/javascript">
var ajax = "";

$(document).ready(function(){
	<?php if($this->session->flashdata('sukses')){?>
        notif_simpan();
    <?php } ?>

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

	$("input[name='jenis_kelamin']").click(function(){
		var cek = $("input[name='jenis_kelamin']:checked").val();
		$('#jk_txt').val(cek);
	});

	$('#batal').click(function(){
		window.location = "<?php echo base_url(); ?>admum/admum_pindah_kamar_ri_c";
	});

	$('.btn_pasien').click(function(){
        $('#popup_pasien').click();
        load_data_pasien();
    });

    $('.btn_ruangan').click(function(){
        $('#popup_ruangan').click();
        load_ruangan();
    });

    $('.btn_bed').click(function(){
        $('#popup_bed').click();
        load_bed();
    });

    $('#btn_simpan').click(function(){
    	var nama = $('#nama').val();
    	var jk = $("#jk_txt").val();
    	var umur = $('#umur').val();
    	var alamat = $('#alamat').val();
    	var id_pasien = $('#id_pasien').val();
    	// console.log(jk.length);
    	if(nama == ""){
    		toastr["error"]("Masukkan nama yang bertanda tangan!", "Notifikasi");
    	}else if(jk == ""){
    		toastr["error"]("Pilih jenis kelamin Anda!", "Notifikasi");
    	}else if(umur == ""){
    		toastr["error"]("Masukkan umur Anda!", "Notifikasi");
    	}else if(alamat == ""){
    		toastr["error"]("Masukkan alamat Anda!", "Notifikasi");
    	}else if(id_pasien == ""){
    		toastr["error"]("Masukkan pasien yang akan pindah kamar!", "Notifikasi");
    	}else{
    		$.ajax({
		        url : '<?php echo base_url(); ?>admum/admum_pindah_kamar_ri_c/simpan',
		        data : $('#form_pindah_kamar').serialize(),
		        type : "POST",
		        dataType : "json",
		        success : function(res){
		            window.open('<?php echo base_url(); ?>admum/admum_pindah_kamar_ri_c/cetak_form', '_blank', 'location=yes,height=700,width=800,scrollbars=yes,status=yes');
		            setInterval(function () {
	                    window.location = "<?php echo base_url(); ?>admum/admum_pindah_kamar_ri_c";
	                }, 3000);
		        }
		    });
    	}
    });

});

function load_data_pasien(){
    var keyword = $('#cari_pasien').val();
    
    if(ajax){
        ajax.abort();
    }

    ajax = $.ajax({
        url : '<?php echo base_url(); ?>admum/admum_pindah_kamar_ri_c/load_data_pasien',
        data : {keyword:keyword},
        type : "GET",
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

    $('#cari_pasien').off('keyup').keyup(function(){
        load_data_pasien();
    });
}

function klik_pasien(id){
    $('#tutup_pasien').click();

    $.ajax({
        url : '<?php echo base_url(); ?>admum/admum_pindah_kamar_ri_c/klik_pasien',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(row){
            $('#id_ri').val(id);
            $('#id_pasien').val(row['ID_PASIEN']);
            $('#id_kamar_lama').val(row['ID_KAMAR']);
            $('#id_bed_lama').val(row['ID_BED']);
            $('#nama_pasien').val(row['NAMA']);
            $('#umur_pasien').val(row['UMUR']);
        
            if(row['JENIS_KELAMIN'] == 'L'){
                $('#l_pasien').prop('checked','checked');
                $('#p_pasien').removeAttr('checked');
            }else{
                $('#p_pasien').prop('checked','checked');
                $('#l_pasien').removeAttr('checked');
            }
            
            $('#alamat_pasien').val(row['ALAMAT']);
            $('#dari_kamar').val(row['KELAS']+' - '+row['VISITE_DOKTER']);
            $('#biaya_dari_kamar').val(formatNumber(row['BIAYA']));
        }
    });
}

function load_ruangan(){
    var kelas = $('#kelas_kamar').val();
    var keyword = $('#cari_kamar').val();

    $.ajax({
        url : '<?php echo base_url(); ?>admum/admum_pindah_kamar_ri_c/load_kamar',
        data : {kelas:kelas,keyword:keyword},
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

                    $tr += "<tr style='cursor:pointer;' onclick='klik_ruangan("+result[i].ID+");'>"+
                                "<td style='text-align:center;'>"+no+"</td>"+
                                "<td style='text-align:center;'>"+result[i].KODE_KAMAR+"</td>"+
                                "<td style='text-align:center;'>"+result[i].KELAS+"</td>"+
                                "<td style='text-align:right;'>"+formatNumber(result[i].BIAYA)+"</td>"+
                                "<td style='text-align:center;'>"+result[i].VISITE_DOKTER+"</td>"+
                            "</tr>";
                }
            }

            $('#tabel_kamar tbody').html($tr);
        }
    });

    $('#cari_kamar').off('keyup').keyup(function(){
        load_ruangan();
    });
}

function klik_ruangan(id){
    $('#tutup_kamar').click();

    $.ajax({
        url : '<?php echo base_url(); ?>admum/admum_pindah_kamar_ri_c/klik_kamar',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(row){
            $('#id_ruangan').val(id);
            var txt = row['KODE_KAMAR']+' - '+row['KELAS']+' - '+row['VISITE_DOKTER'];
            $('#ruang_tujuan').val(txt);
            $('#biaya').val(NumberToMoney(row['BIAYA']));
        }
    });
}

function load_bed(){
    var id_kamar = $('#id_ruangan').val();
    var keyword = $('#cari_bed').val();

    $.ajax({
        url : '<?php echo base_url(); ?>admum/admum_pindah_kamar_ri_c/load_bed',
        data : {id_kamar:id_kamar,keyword:keyword},
        type : "POST",
        dataType : "json",
        success : function(result){
            $tr = "";

            if(result == "" || result == null){
                $tr = "<tr><td colspan='3' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
            }else{
                for(var i=0; i<result.length; i++){
                    var stt = "";
                    var warna = "";
                    var disabled = "";
                    var diklik = "";

                    if(result[i].STATUS_PAKAI == 0){
                        stt = '<span class="label label-success">KOSONG</span>';
                        warna = "";
                        disabled = "";
                        diklik = "style='cursor:pointer;' onclick='klik_bed("+result[i].ID+");'";
                    }else{
                        stt = '<span class="label label-danger">TERPAKAI</span>';
                        warna = "terpakai";
                        disabled = "disabled='disabled'";
                        diklik = "";
                    }

                    $tr += "<tr class='"+warna+"' "+disabled+" "+diklik+">"+
                                "<td style='text-align:center;'>"+result[i].NO+"</td>"+
                                "<td>"+result[i].NOMOR_BED+"</td>"+
                                "<td style='text-align:center;'>"+stt+"</td>"+
                            "</tr>";
                }
            }

            $('#tabel_bed tbody').html($tr);
        }
    });

    $('#cari_bed').off('keyup').keyup(function(){
        load_bed();
    });
}

function klik_bed(id){
    $('#tutup_bed').click();

    $.ajax({
        url : '<?php echo base_url(); ?>admum/admum_pindah_kamar_ri_c/klik_bed',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(row){
            $('#id_bed').val(id);
            $('#bed').val(row['NOMOR_BED']);
        }
    });
}
</script>

<div class="row">
	<div class="col-sm-12">
        <form class="form-horizontal" role="form" action="" method="post" id="form_pindah_kamar">
        	<input type="hidden" name="id_ri" id="id_ri" value="">
        	<input type="hidden" name="id_pasien" id="id_pasien" value="">
        	<input type="hidden" id="jk_txt" value="">
        	<input type="hidden" name="id_kamar_lama" id="id_kamar_lama" value="">
        	<input type="hidden" name="id_bed_lama" id="id_bed_lama" value="">
            <div class="card-box">
            	<div class="row">
            		<div class="col-lg-12">
                        <div class="alert alert-info">
                            <i class="fa fa-user"></i>&nbsp;<strong>Kami yang bertanda tangan di bawah ini :</strong>
                        </div>
                    </div>
            		<div class="col-lg-6">
                        <div class="form-group">
                            <label class="col-md-3 control-label">Nama</label>
                            <div class="col-md-7">
                                <input type="text" class="form-control" name="nama" id="nama" value="">
                            </div>
                            <div class="col-md-2">
                            	<div class="radio radio-danger radio-inline">
	                                <input type="radio" name="jenis_kelamin" value="L" id="l">
	                                <label for="l"> L </label>
	                            </div>
                    			<div class="radio radio-danger radio-inline">
	                                <input type="radio" name="jenis_kelamin" value="P" id="p">
	                                <label for="p"> P </label>
	                            </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Umur</label>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" class="form-control num_only" name="umur" id="umur" value="" maxlength="3">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-warning" style="cursor:default;">Tahun</button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Alamat</label>
                            <div class="col-md-9">
                                <textarea rows="5" class="form-control" name="alamat" id="alamat"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">&nbsp;</label>
                            <div class="col-md-9">
                                <h4 class="header-title">Atas nama sendiri / pasien</h4>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Nama</label>
                            <div class="col-md-7">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="nama_pasien" id="nama_pasien" value="" readonly>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-success btn_pasien"><i class="fa fa-search"></i></button>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-2">
                            	<div class="radio radio-purple radio-inline">
	                                <input type="radio" name="jenis_kelamin_pasien" value="L" id="l_pasien">
	                                <label for="l_pasien"> L </label>
	                            </div>
                    			<div class="radio radio-purple radio-inline">
	                                <input type="radio" name="jenis_kelamin_pasien" value="P" id="p_pasien">
	                                <label for="p_pasien"> P </label>
	                            </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Umur</label>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" class="form-control num_only" name="umur_pasien" id="umur_pasien" value="" maxlength="3" readonly>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-warning" style="cursor:default;">Tahun</button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Alamat</label>
                            <div class="col-md-9">
                                <textarea rows="5" class="form-control" name="alamat_pasien" id="alamat_pasien" readonly></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Hubungan dgn pasien :</label>
                            <div class="col-md-4">
                                <select class="form-control select2" name="hubungan_pasien" id="hubungan_pasien">
                                    <option value="Ayah">Ayah</option>
                                    <option value="Ibu">Ibu</option>
                                    <option value="Istri">Istri</option>
                                    <option value="Suami">Suami</option>
                                    <option value="Anak">Anak</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                    	<div class="form-group">
                            <label class="col-md-3 control-label">Dari Kamar</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="dari_kamar" id="dari_kamar" value="" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Biaya</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="biaya_dari_kamar" id="biaya_dari_kamar" value="" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">&nbsp;</label>
                            <div class="col-md-9">
                                <h4 class="header-title">Ke Kamar</h4>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Kelas</label>
                            <div class="col-md-4">
                                <select class="form-control select2" name="kelas_kamar" id="kelas_kamar">
                                    <option value="SVIP">SVIP</option>
                                    <option value="VIP">VIP</option>
                                    <option value="1A">I A</option>
                                    <option value="1B">I B</option>
                                    <option value="2A">II A</option>
                                    <option value="2B">II B</option>
                                    <option value="3">III</option>
                                    <option value="Neo">Ruang Neo</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Kamar</label>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <input type="hidden" name="id_ruangan" id="id_ruangan" value="">
                                    <input type="text" class="form-control" id="ruang_tujuan" value="" required="required" readonly>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-danger btn_ruangan"><i class="fa fa-search"></i></button>
                                    </span>
                                </div>
                            </div>
                            <label class="col-md-1 control-label">No Bed</label>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <input type="hidden" name="id_bed" id="id_bed" value="">
                                    <input type="text" class="form-control" id="bed" value="" required="required" readonly>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-primary btn_bed"><i class="fa fa-search"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Biaya Kamar</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="biaya" id="biaya" value="" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <center>
                    <button type="button" class="btn btn-success m-b-5" id="btn_simpan"><i class="fa fa-refresh"></i> <span><b>Proses</b></span></button>
                    <button type="button" class="btn btn-danger m-b-5" id="batal"><i class="fa fa-times"></i> <span><b>Batal</b></span></button>
                </center>
            </div>
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

<!-- //LOAD RUANGAN -->
<button class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#myModal2" id="popup_ruangan" style="display:none;">Standard Modal</button>
<div id="myModal2" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
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
                                    <button type="button" class="btn waves-effect waves-light btn-warning" style="cursor:default;">
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
                                <tr class="merah_popup">
                                    <th style="text-align:center; color: #fff;" width="50">No</th>
                                    <th style="text-align:center; color: #fff;">Kode Kamar</th>
                                    <th style="text-align:center; color: #fff;">Kelas</th>
                                    <th style="text-align:center; color: #fff;">Biaya</th>
                                    <th style="text-align:center; color: #fff;">Visite Dokter Sp.</th>
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
        </div>
    </div>
</div>
<!-- // -->

<!-- //LOAD BED -->
<button class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#myModal3" id="popup_bed" style="display:none;">Standard Modal</button>
<div id="myModal3" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Data Bed Kamar</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form">
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="input-group">
                                <input type="text" class="form-control" id="cari_bed" placeholder="Cari..." value="">
                                <span class="input-group-btn">
                                    <button type="button" class="btn waves-effect waves-light btn-warning" style="cursor:default;">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="table-responsive">
                    <div class="scroll-y">
                        <table class="table table-hover table-bordered" id="tabel_bed">
                            <thead>
                                <tr class="biru_popup">
                                    <th style="text-align:center; color: #fff;" width="50">No</th>
                                    <th style="text-align:center; color: #fff;">Kode Bed</th>
                                    <th style="text-align:center; color: #fff;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_bed">Tutup</button>
            </div>
        </div>
    </div>
</div>
<!-- // -->
<style type="text/css">
.coba .active a {
    background: #21AFDA !important;
    color: #fff !important;
}
</style>
<div class="row">
    <div class="col-lg-12">
        <div class="card-box card-tabs">
            <ul class="nav nav-tabs coba">
                <li role="presentation" class="active">
                    <a style="background:#f4f8fb;" href="#daftar_jadwal" role="tab" data-toggle="tab" aria-expanded="true"> <i class="fa fa-list"></i> Daftar Jadwal Doktor</a>
                </li> 
                <li role="presentation" class="">
                    <a style="background:#f4f8fb;" href="#add_edit_jadwal" role="tab" data-toggle="tab" aria-expanded="false"><i class="fa fa-pencil"></i> Tambah / Edit Jadwal</a>
                </li>
            </ul>

            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade active in" id="daftar_jadwal">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card-box">
                                <div class="table-responsive">
                                    <table id="datatable" class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th style="text-align:center;">NIP / NAMA</th>
                                                <th style="text-align:center;">SENIN</th>
                                                <th style="text-align:center;">SELASA</th>
                                                <th style="text-align:center;">RABU</th>
                                                <th style="text-align:center;">KAMIS</th>
                                                <th style="text-align:center;">JUMAT</th>
                                                <th style="text-align:center;">SABTU</th>
                                                <th style="text-align:center;">MINGGU</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?PHP 
                                                foreach ($dt as $key => $row) { 
                                                    $dt_senin  = $this->model->getJadwalDokterbyHari($row->ID, 'Senin');
                                                    $dt_selasa = $this->model->getJadwalDokterbyHari($row->ID, 'Selasa');
                                                    $dt_rabu   = $this->model->getJadwalDokterbyHari($row->ID, 'Rabu');
                                                    $dt_kamis  = $this->model->getJadwalDokterbyHari($row->ID, 'Kamis');
                                                    $dt_jumat  = $this->model->getJadwalDokterbyHari($row->ID, 'Jumat');
                                                    $dt_sabtu  = $this->model->getJadwalDokterbyHari($row->ID, 'Sabtu');
                                                    $dt_minggu = $this->model->getJadwalDokterbyHari($row->ID, 'Minggu');
                                            ?>
                                            <tr>
                                                <td style="vertical-align:top;"> <?=$row->NIP;?> <br> <b><?=$row->NAMA;?></b>  </td>
                                                <td>
                                                    <?PHP 
                                                    foreach ($dt_senin as $key => $row) {
                                                        echo "<p style='margin-top: 5px;'>- ".$row->POLI." <br> &nbsp; <b> (".$row->WAKTU_AWAL." - ".$row->WAKTU_AKHIR.") </b></p> ";
                                                    } 
                                                    if(count($dt_senin) == 0){ echo "<center> <p style='margin-top: 5px; color:red;'> Tidak ada jadwal </p> </center>"; }
                                                    ?>
                                                </td>

                                                <td>
                                                    <?PHP 
                                                    foreach ($dt_selasa as $key => $row) {
                                                        echo "<p style='margin-top: 5px;'>- ".$row->POLI." <br> &nbsp; <b> (".$row->WAKTU_AWAL." - ".$row->WAKTU_AKHIR.") </b></p> ";
                                                    } 
                                                    if(count($dt_selasa) == 0){ echo "<center> <p style='margin-top: 5px; color:red;'> Tidak ada jadwal </p> </center>"; }
                                                    ?>
                                                </td>

                                                <td>
                                                    <?PHP 
                                                    foreach ($dt_rabu as $key => $row) {
                                                        echo "<p style='margin-top: 5px;'>- ".$row->POLI." <br> &nbsp; <b> (".$row->WAKTU_AWAL." - ".$row->WAKTU_AKHIR.") </b></p> ";
                                                    } 
                                                    if(count($dt_rabu) == 0){ echo "<center> <p style='margin-top: 5px; color:red;'> Tidak ada jadwal </p> </center>"; }
                                                    ?>
                                                </td>

                                                <td>
                                                    <?PHP 
                                                    foreach ($dt_kamis as $key => $row) {
                                                        echo "<p style='margin-top: 5px;'>- ".$row->POLI." <br> &nbsp; <b> (".$row->WAKTU_AWAL." - ".$row->WAKTU_AKHIR.") </b></p> ";
                                                    } 
                                                    if(count($dt_kamis) == 0){ echo "<center> <p style='margin-top: 5px; color:red;'> Tidak ada jadwal </p> </center>"; }
                                                    ?>
                                                </td>

                                                <td>
                                                    <?PHP 
                                                    foreach ($dt_jumat as $key => $row) {
                                                        echo "<p style='margin-top: 5px;'>- ".$row->POLI." <br> &nbsp; <b> (".$row->WAKTU_AWAL." - ".$row->WAKTU_AKHIR.") </b></p> ";
                                                    } 
                                                    if(count($dt_jumat) == 0){ echo "<center> <p style='margin-top: 5px; color:red;'> Tidak ada jadwal </p> </center>"; }
                                                    ?>
                                                </td>

                                                <td>
                                                    <?PHP 
                                                    foreach ($dt_sabtu as $key => $row) {
                                                        echo "<p style='margin-top: 5px;'>- ".$row->POLI." <br> &nbsp; <b> (".$row->WAKTU_AWAL." - ".$row->WAKTU_AKHIR.") </b></p> ";
                                                    } 
                                                    if(count($dt_sabtu) == 0){ echo "<center> <p style='margin-top: 5px; color:red;'> Tidak ada jadwal </p> </center>"; }
                                                    ?>
                                                </td>

                                                <td>
                                                    <?PHP 
                                                    foreach ($dt_minggu as $key => $row) {
                                                        echo "<p style='margin-top: 5px;'>- ".$row->POLI." <br> &nbsp; <b> (".$row->WAKTU_AWAL." - ".$row->WAKTU_AKHIR.") </b></p> ";
                                                    } 
                                                    if(count($dt_minggu) == 0){ echo "<center> <p style='margin-top: 5px; color:red;'> Tidak ada jadwal </p> </center>"; }
                                                    ?>
                                                </td>
                                            </tr>

                                            <?PHP } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <button type="button" class="btn btn-danger">Cetak PDF</button>
                        </div>
                    </div>
                </div> 

                <div role="tabpanel" class="tab-pane fade" id="add_edit_jadwal">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card-box">
                                <form class="form-horizontal" role="form" method="post" action="<?=base_url().$post_url;?>" onsubmit="return cek_submit();">
                                    <div class="row">
                                        <div class="col-lg-2">
                                            <div class="form-group">
                                                <div class="col-md-6">
                                                    <img src="<?=base_url();?>files/foto_pegawai/default_pics_of_rs_jt.png" id="foto_head" style="width: 200px; height: 200px;">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-8">
                                            <div class="form-group">
                                                <label class="col-md-2 control-label" style="color: #0099e5; margin-top: 10px;"> Nama Doktor </label>
                                                <div class="col-md-6">
                                                    <div class="input-group m-t-10">
                                                        <input id="nama_pegawai" name="nama_pegawai" class="form-control" type="text" readonly value="" style="background:#FFF;">
                                                        <span class="input-group-btn">
                                                        <button type="button" class="btn waves-effect waves-light btn-primary" id="btn_dokter">Cari Doktor</button>
                                                        </span>
                                                    </div>
                                                    <input name="id_pegawai" id="id_pegawai" class="form-control" value="" type="hidden">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label" style="color: #0099e5;"> Alamat </label>
                                                <div class="col-md-6">
                                                    <input name="alamat" id="alamat" readonly style="background:#FFF;" class="form-control" value="" type="text">
                                                </div>
                                            </div>
                                            <div class="form-group"> 
                                                <label class="col-md-2 control-label" style="color: #0099e5;"> Telepon </label>
                                                <div class="col-md-6">
                                                    <input name="telepon" id="telepon" readonly style="background:#FFF;" class="form-control" value="" type="text">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-12" style="margin-top: 25px;">
                                            <div class="card-box table-responsive" style="background:#ffffdf;">
                                                <h4 class="header-title m-t-0 m-b-30"> Jadwal Dokter </h4>

                                                <table id="" class="table table-striped table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th style="text-align:center;">Senin</th>
                                                            <th style="text-align:center;">Selasa</th>
                                                            <th style="text-align:center;">Rabu</th>
                                                            <th style="text-align:center;">Kamis</th>
                                                            <th style="text-align:center;">Jumat</th>
                                                            <th style="text-align:center;">Sabtu</th>
                                                            <th style="text-align:center;">Minggu</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td align="center">
                                                                <!-- <div class="alert alert-info fade in m-b-0" style="margin-top: 10px;">
                                                                    <center>
                                                                        <h4>Big one!</h4>
                                                                        <p>Lorem ipsum dolor sit amet,</p>
                                                                        <p class="m-t-10">
                                                                            <button type="button" class="btn btn-danger waves-effect waves-light"> Hapus </button>
                                                                        </p>
                                                                    </center>
                                                                </div> -->
                                                                <div id="Senin"> </div>
                                                                <button type="button" onclick="$('#sel_hari').val('Senin');" data-toggle="modal" data-target="#add_jadwal" class="btn btn-success waves-effect waves-light m-t-10"> <i class="fa fa-plus"></i> Tambahkan </button>
                                                            </td>
                                                            <td align="center"> <div id="Selasa"> </div> <button onclick="$('#sel_hari').val('Selasa');" data-toggle="modal" data-target="#add_jadwal" type="button" class="btn btn-success waves-effect waves-light m-t-10"> <i class="fa fa-plus"></i> Tambahkan </button> </td>
                                                            <td align="center"> <div id="Rabu">   </div> <button onclick="$('#sel_hari').val('Rabu');" data-toggle="modal" data-target="#add_jadwal" type="button" class="btn btn-success waves-effect waves-light m-t-10"> <i class="fa fa-plus"></i> Tambahkan </button> </td>
                                                            <td align="center"> <div id="Kamis">  </div> <button onclick="$('#sel_hari').val('Kamis');" data-toggle="modal" data-target="#add_jadwal" type="button" class="btn btn-success waves-effect waves-light m-t-10"> <i class="fa fa-plus"></i> Tambahkan </button> </td>
                                                            <td align="center"> <div id="Jumat">  </div> <button onclick="$('#sel_hari').val('Jumat');" data-toggle="modal" data-target="#add_jadwal" type="button" class="btn btn-success waves-effect waves-light m-t-10"> <i class="fa fa-plus"></i> Tambahkan </button> </td>
                                                            <td align="center"> <div id="Sabtu">  </div> <button onclick="$('#sel_hari').val('Sabtu');" data-toggle="modal" data-target="#add_jadwal" type="button" class="btn btn-success waves-effect waves-light m-t-10"> <i class="fa fa-plus"></i> Tambahkan </button> </td>
                                                            <td align="center"> <div id="Minggu"> </div> <button onclick="$('#sel_hari').val('Minggu');" data-toggle="modal" data-target="#add_jadwal" type="button" class="btn btn-success waves-effect waves-light m-t-10"> <i class="fa fa-plus"></i> Tambahkan </button> </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="form-group m-b-0">
                                            <div class="col-sm-offset-5 col-sm-10">
                                              <input type="submit" class="btn btn-lg btn-info" value="Simpan" name="simpan"/>
                                              &nbsp;&nbsp;&nbsp;
                                              <button type="reset" class="btn btn-lg btn-inverse" onclick="window.location='<?=base_url();?>poli/poli_jadwal_doktor_c';">Batal</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div><!-- end col -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="add_jadwal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"> Tambah Jadwal Dokter </h4>
            </div>
            <form method="post" action="<?=base_url().$post_url;?>">
            <div class="modal-body">                
                <div class="row">
                    <div class="col-md-12">
                    	<input type="hidden" name="sel_hari" id="sel_hari" />
                    	<div class="form-group">
                            <label for="id_poli_sel" class="control-label"> Nama Poli </label>
                            <select class="form-control" name="id_poli_sel" id="id_poli_sel">
                               <?PHP foreach ($dt_poli as $key => $poli) { ?>
                                <option value="<?=$poli->ID;?>"> <?=$poli->NAMA;?> </option>
                               <?PHP } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="ed_nama_dep" class="control-label">Waktu Awal</label>
                            <div class="input-group m-b-15">
	                            <div class="bootstrap-timepicker">
	                                <input id="waktu_awal_sel" type="text" class="form-control timepicker2" readonly style="background:#FFF;">
	                            </div>
	                            <span class="input-group-addon bg-primary b-0 text-white"><i class="glyphicon glyphicon-time"></i></span>
                       		</div>
                        </div>
                        <div class="form-group">
                            <label for="ed_nama_dep" class="control-label">Waktu Akhir</label>
                            <div class="input-group m-b-15">
	                            <div class="bootstrap-timepicker">
	                                <input id="waktu_akhir_sel" type="text" class="form-control timepicker2" readonly style="background:#FFF;">
	                            </div>
	                            <span class="input-group-addon bg-primary b-0 text-white"><i class="glyphicon glyphicon-time"></i></span>
                       		</div>
                        </div>                        

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default " data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-info" onclick="add_jadwal_dokter();" data-dismiss="modal"> Simpan Jadwal </button>
            </div>
            </form>
        </div>
    </div>
</div>

<!-- //LOAD DOKTER -->
<button class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#myModal3" id="popup_dokter" style="display:none;">Standard Modal</button>
<div id="myModal3" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
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
                                <input type="text" class="form-control" id="cari_dokter" placeholder="Cari..." value="">
                                <span class="input-group-btn">
                                    <button type="button" class="btn waves-effect waves-light btn-custom" style="cursor:default;">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="load_tabel_dokter">
                    <img src="<?php echo base_url(); ?>picture/processando.gif" style="width: 90px; height: 90px;">
                </div>
                <div class="table-responsive">
                    <div class="scroll-y">
                        <table class="table table-hover table-bordered" id="tabel_dokter">
                            <thead>
                                <tr class="kuning_popup">
                                    <th style="text-align:center; color: #fff;" width="50">No</th>
                                    <th style="text-align:center; color: #fff;">Dokter</th>
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
        </div>
    </div>
</div>
<!-- // -->

<script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
<script type="text/javascript">
var ajax = "";

jQuery(document).ready(function() {
	jQuery('.timepicker2').timepicker({
        showMeridian : false
    });

    $('#btn_dokter').click(function(){
        $('#popup_dokter').click();
        ajax_dokter();
    });
});

function cek_submit(){
	var a = "";
	var nama_pegawai = $('#nama_pegawai').val();

	if(nama_pegawai == ""){
		alert('Mohon pilih doktor terlebih dahulu !');
		a = false;
	} else {
		a = true;
	}

	return a;
}

function add_jadwal_dokter(){
	var sel_hari    = $('#sel_hari').val();
	var nama_poli   = $("#id_poli_sel option:selected").text();
	var id_poli     = $('#id_poli_sel').val();
	var waktu_awal  = $('#waktu_awal_sel').val();
	var waktu_akhir = $('#waktu_akhir_sel').val();

	var i = $('.'+sel_hari).length;
	i = parseInt(i) + 1;

	var isi =  '<div class="alert alert-info fade in m-b-0 '+sel_hari+'" style="margin-top: 10px;" id="head_'+sel_hari+'_'+i+'">'+
					'<center>'+
					    '<h4>'+nama_poli+'</h4>'+
					    '<input type="hidden" name="id_poli[]" value="'+id_poli+'"/>'+
					    '<input type="hidden" name="hari[]" value="'+sel_hari+'"/>'+
					    '<input type="hidden" name="waktu_awal[]" value="'+waktu_awal+'"/>'+
					    '<input type="hidden" name="waktu_akhir[]" value="'+waktu_akhir+'"/>'+
					    '<p> <b> '+waktu_awal+' - '+waktu_akhir+' </b> </p>'+
					    '<p class="m-t-10">'+
					      	'<button onclick="del_sch(\'' +i+ '\',\'' +sel_hari+ '\');" type="button" class="btn btn-danger waves-effect waves-light"> Hapus </button>'+
					    '</p>'+
				    '</center>'+
				'</div>';

	$('#'+sel_hari).append(isi);

}

function del_sch(i, sel_hari){
	$('#head_'+sel_hari+'_'+i).remove();
}

function ajax_dokter(){
    var keyword = $('#cari_dokter').val();

    $.ajax({
        url : '<?php echo base_url(); ?>poli/poli_jadwal_doktor_c/get_doktor',
        type : "GET",
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
                isine += '<tr onclick="get_data_pegawai('+res.ID+');" style="cursor:pointer;">'+
                            '<td align="center">'+no+'</td>'+
                            '<td align="left">'+res.NAMA+'</td>'+
                        '</tr>';
            });

            if(result.length == 0){
                isine = "<tr><td colspan='4' style='text-align:center'><b style='font-size: 15px;'> Data tidak tersedia </b></td></tr>";
            }

            $('#tabel_dokter tbody').html(isine);

            $('#cari_dokter').off('keyup').keyup(function(){
                ajax_dokter();
            });
        }
    });
}

function get_data_pegawai(id){
    $.ajax({
        url : '<?php echo base_url(); ?>poli/poli_jadwal_doktor_c/get_data_pegawai',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(res){
            $('#nama_pegawai').val(res.NAMA);
            $('#alamat').val(res.ALAMAT);
            $('#telepon').val(res.TELPON);
            $('#id_pegawai').val(id);
            $('#foto_head').attr('src','<?=base_url();?>files/foto_pegawai/'+res.FOTO);

            get_jadwal_doktor(id, 'Senin');
            get_jadwal_doktor(id, 'Selasa');
            get_jadwal_doktor(id, 'Rabu');
            get_jadwal_doktor(id, 'Kamis');
            get_jadwal_doktor(id, 'Jumat');
            get_jadwal_doktor(id, 'Sabtu');
            get_jadwal_doktor(id, 'Minggu');

            $('#tutup_dokter').click();
        }
    });

}

function get_jadwal_doktor(id_doktor, hari){
    $.ajax({
        url : '<?php echo base_url(); ?>poli/poli_jadwal_doktor_c/get_jadwal_doktor',
        data : {
            id_doktor:id_doktor,
            hari:hari,
        },
        type : "POST",
        dataType : "json",
        success : function(result){
            var no = 0;
            var isi = "";
            $.each(result,function(i,res){
                no++;
                isi +=  
                '<div class="alert alert-info fade in m-b-0 '+res.HARI+'" style="margin-top: 10px;" id="head_'+res.HARI+'_'+no+'">'+
                    '<center>'+
                        '<h4>'+res.POLI+'</h4>'+
                        '<input type="hidden" name="id_poli[]" value="'+res.ID_POLI+'"/>'+
                        '<input type="hidden" name="hari[]" value="'+res.HARI+'"/>'+
                        '<input type="hidden" name="waktu_awal[]" value="'+res.WAKTU_AWAL+'"/>'+
                        '<input type="hidden" name="waktu_akhir[]" value="'+res.WAKTU_AKHIR+'"/>'+
                        '<p> <b> '+res.WAKTU_AWAL+' - '+res.WAKTU_AKHIR+' </b> </p>'+
                        '<p class="m-t-10">'+
                            '<button onclick="del_sch(\'' +no+ '\',\'' +res.HARI+ '\');" type="button" class="btn btn-danger waves-effect waves-light"> Hapus </button>'+
                        '</p>'+
                    '</center>'+
                '</div>';
            });

            $('#'+hari).html(isi);
        }
    });
}

</script>
<script type="text/javascript" src="<?php echo base_url(); ?>js-devan/jquery-1.11.1.min.js"></script>

<style type="text/css">
#tombol_reset_tdk{
    display: none;
}
</style>

<script type="text/javascript">
var id_pasien = "<?php echo $id_pasien; ?>";
$(document).ready(function(){
    get_tindakan_rj();

    $('#dt_diagnosa').click(function(){
        get_diagnosa_rj();
    });

    $('#dt_laborat').click(function(){
        get_laborat_rj();
    });

    $('#dt_resep').click(function(){
        get_resep_rj();
    });

    $('#btn_kembali').click(function(){
        window.location = "<?php echo base_url(); ?>lab/lab_home_c";
    });
});

function get_tindakan_rj(){
    $('#popup_load').show();
    var tanggal = $('#cari_tdk').val();

    $.ajax({
        url : '<?php echo base_url(); ?>lab/lab_home_c/get_tindakan_rj',
        data : {
            id_pasien:id_pasien,
            tanggal:tanggal
        },
        type : "POST",
        dataType : "json",
        success : function(res){
            $tr = '';

            if(res == null || res == ''){
                $tr = '<tr><td colspan="5" align="center" style="text-align:center;"> <b>Belum ada tindakan</b> </td></tr>';
            }else{
                var no = 0;

                for(var i=0; i<res.length; i++){
                    no++;

                    $tr += '<tr>'+
                                '<td style="text-align:center;">'+no+'</td>'+
                                '<td style="text-align:center;">'+res[i].TANGGAL+'</td>'+
                                '<td>'+res[i].NAMA_TINDAKAN+'</td>'+
                                '<td>'+res[i].NAMA_POLI+'</td>'+
                                '<td>'+res[i].NAMA+'</td>'+
                            '</tr>';
                }
            }

            $('#tabel_tindakan tbody').html($tr);
            $('#popup_load').fadeOut();
        }
    });

    $('#tombol_cari_tdk').click(function(){
        get_tindakan_rj();
        $('#tombol_reset_tdk').show();
        $('#tombol_cari_tdk').hide();
    });

    $('#tombol_reset_tdk').click(function(){
        $('#cari_tdk').val("");
        get_tindakan_rj();
        $('#tombol_reset_tdk').hide();
        $('#tombol_cari_tdk').show();
    });
}

function onEnterTextTdk(e){
    if (e.keyCode == 13) {
        get_tindakan_rj();
        $('#tombol_reset_tdk').show();
        $('#tombol_cari_tdk').hide();
        return false;
    }
}

function get_diagnosa_rj(){
    $('#popup_load').show();
    var tanggal = $('#cari_dg').val();

    $.ajax({
        url : '<?php echo base_url(); ?>lab/lab_home_c/get_diagnosa_rj',
        data : {
            id_pasien:id_pasien,
            tanggal:tanggal
        },
        type : "POST",
        dataType : "json",
        success : function(res){
            $tr = '';

            if(res == null || res == ''){
                $tr = '<tr><td colspan="7" align="center" style="text-align:center;"> <b>Belum ada diagnosa</b> </td></tr>';
            }else{
                var no = 0;

                for(var i=0; i<res.length; i++){
                    no++;

                    $tr += '<tr>'+
                                '<td style="text-align:center;">'+no+'</td>'+
                                '<td style="text-align:center;">'+res[i].TANGGAL+'</td>'+
                                '<td>'+res[i].DIAGNOSA+'</td>'+
                                '<td>'+res[i].TINDAKAN+'</td>'+
                                '<td>'+res[i].URAIAN+'</td>'+
                                '<td>'+res[i].NAMA_POLI+'</td>'+
                                '<td>'+res[i].NAMA+'</td>'+
                            '</tr>';
                }
            }

            $('#tabel_diagnosa tbody').html($tr);
            $('#popup_load').fadeOut();
        }
    });

    $('#tombol_cari_dg').click(function(){
        get_diagnosa_rj();
        $('#tombol_reset_dg').show();
        $('#tombol_cari_dg').hide();
    });

    $('#tombol_reset_dg').click(function(){
        $('#cari_dg').val("");
        get_diagnosa_rj();
        $('#tombol_reset_dg').hide();
        $('#tombol_cari_dg').show();
    });
}

function onEnterTextDg(e){
    if (e.keyCode == 13) {
        get_diagnosa_rj();
        $('#tombol_reset_dg').show();
        $('#tombol_cari_dg').hide();
        return false;
    }
}

function get_laborat_rj(){
    $('#popup_load').show();
    var tanggal = $('#cari_lab').val();

    $.ajax({
        url : '<?php echo base_url(); ?>lab/lab_home_c/get_laborat_rj',
        data : {
            id_pasien:id_pasien,
            tanggal:tanggal
        },
        type : "POST",
        dataType : "json",
        async : false,
        success : function(res){
            $tr = '';

            if(res == null || res == ''){
                $tr = '<tr><td colspan="8" align="center" style="text-align:center;"> <b>Belum ada data laborat</b> </td></tr>';
            }else{
                var no = 0;

                for(var i=0; i<res.length; i++){
                    no++;

                    res[i].CITO = res[i].CITO==0?'Tidak Aktif':'Aktif';

                    $tr += '<tr class="success">'+
                                '<td style="text-align:center;">'+no+'</td>'+
                                '<td colspan="3">'+res[i].JENIS_LABORAT+'</td>'+
                                '<td style="text-align:center;">'+res[i].KODE_LAB+'</td>'+
                                '<td style="text-align:center;">'+res[i].TANGGAL+'</td>'+
                                '<td>'+res[i].CITO+'</td>'+
                            '</tr>';

                    var id_lab = res[i].ID;

                    $.ajax({
                        url : '<?php echo base_url(); ?>lab/lab_home_c/get_pemeriksaan_lab',
                        data : {id_lab:id_lab},
                        type : "POST",
                        dataType : "json",
                        async : false,
                        success : function(result){
                            if(result.length != 0){
                                var no2 = 0;
                                for(var j=0; j<result.length; j++){
                                    no2++;

                                    result[j].HASIL = result[j].HASIL==null?"-":result[j].HASIL;

                                    $tr += '<tr>'+
                                                '<td>&nbsp;</td>'+
                                                '<td>'+result[j].NAMA_PEMERIKSAAN+'</td>'+
                                                '<td>'+result[j].NILAI_NORMAL+'</td>'+
                                                '<td colspan="4">'+result[j].HASIL+'</td>'+
                                             '</tr>';
                                }
                            }
                        }
                    });
                }
            }

            $('#tabel_laborat tbody').html($tr);
            $('#popup_load').fadeOut();
        }
    });

    $('#tombol_cari_lab').click(function(){
        get_laborat_rj();
        $('#tombol_reset_lab').show();
        $('#tombol_cari_lab').hide();
    });

    $('#tombol_reset_lab').click(function(){
        $('#cari_lab').val("");
        get_laborat_rj();
        $('#tombol_reset_lab').hide();
        $('#tombol_cari_lab').show();
    });
}

function onEnterTextLab(e){
    if (e.keyCode == 13) {
        get_laborat_rj();
        $('#tombol_reset_lab').show();
        $('#tombol_cari_lab').hide();
        return false;
    }
}

function get_resep_rj(){
    $('#popup_load').show();
    var tanggal = $('#cari_rsp').val();

    $.ajax({
        url : '<?php echo base_url(); ?>lab/lab_home_c/get_resep_rj',
        data : {
            id_pasien:id_pasien,
            tanggal:tanggal
        },
        type : "POST",
        dataType : "json",
        async : false,
        success : function(res){
            $tr = '';

            if(res == null || res == ''){
                $tr = '<tr><td colspan="7" align="center" style="text-align:center;"> <b>Belum ada resep</b> </td></tr>';
            }else{
                var no = 0;

                for(var i=0; i<res.length; i++){
                    no++;

                    $tr += '<tr class="success">'+
                                '<td style="text-align:center;">'+no+'</td>'+
                                '<td style="text-align:center;">'+res[i].KODE_RESEP+'</td>'+
                                '<td style="text-align:center;">'+res[i].TANGGAL+'</td>'+
                                '<td>'+res[i].DIMINUM_SELAMA+' Hari</td>'+
                                '<td>'+res[i].ALERGI_OBAT+'</td>'+
                                '<td>'+res[i].NAMA_POLI+'</td>'+
                                '<td>'+res[i].NAMA+'</td>'+
                            '</tr>';

                    var id_resep = res[i].ID;

                    $.ajax({
                        url : '<?php echo base_url(); ?>lab/lab_home_c/get_resep_obat_rj',
                        data : {id_resep:id_resep},
                        type : "POST",
                        dataType : "json",
                        async : false,
                        success : function(result){
                            if(result.length != 0){
                                var no2 = 0;
                                for(var j=0; j<result.length; j++){
                                    no2++;

                                    result[j].HASIL = result[j].HASIL==null?"-":result[j].HASIL;

                                    $tr += '<tr>'+
                                                '<td>&nbsp;</td>'+
                                                '<td>'+result[j].NAMA_OBAT+'</td>'+
                                                '<td>'+result[j].TAKARAN+'</td>'+
                                                '<td colspan="4">'+result[j].ATURAN_MINUM+'</td>'+
                                             '</tr>';
                                }
                            }
                        }
                    });
                }
            }

            $('#tabel_resep tbody').html($tr);
            $('#popup_load').fadeOut();
        }
    });

    $('#tombol_cari_rsp').click(function(){
        get_resep_rj();
        $('#tombol_reset_rsp').show();
        $('#tombol_cari_rsp').hide();
    });

    $('#tombol_reset_rsp').click(function(){
        $('#cari_rsp').val("");
        get_resep_rj();
        $('#tombol_reset_rsp').hide();
        $('#tombol_cari_rsp').show();
    });
}

function onEnterTextRsp(e){
    if (e.keyCode == 13) {
        get_resep_rj();
        $('#tombol_reset_rsp').show();
        $('#tombol_cari_rsp').hide();
        return false;
    }
}
</script>

<div id="popup_load">
    <div class="window_load">
        <img src="<?=base_url()?>picture/progress.gif" height="100" width="125">
    </div>
</div>

<div class="row">
  <div class="col-lg-12">
    <div class="card-box">
      <h4><i class="fa fa-user"></i> Detail Pasien</h4><br>
      <div class="row">
        <div class="col-md-3">
        	<table class="table">
            <?php
              $jk = "";
              if($data_pasien->JENIS_KELAMIN=="L"){$jk="Laki - Laki";}else{$jk="Perempuan";}
              $kerja = "";
              if($data_pasien->PEKERJAAN=="" || $data_pasien->PEKERJAAN==null){$kerja="-";}else{$kerja=$data_pasien->PEKERJAAN;}
            ?>
        		<tbody>
        			<tr>
        				<td>NO. RM</td>
        				<td><span style="color:#0066b2;">: <?php echo $data_pasien->KODE_PASIEN; ?></span></td>
                    </tr>
                    <tr>
        				<td>JENIS KELAMIN</td>
        				<td><span style="color:#0066b2;">: <?php echo $jk; ?></span></td>
        			</tr>
        			<tr>
        				<td>NAMA</td>
        				<td><span style="color:#0066b2;">: <?php echo $data_pasien->NAMA; ?></span></td>
                    </tr>
                    <tr>
                        <td>TGL LAHIR</td>
                        <td><span style="color:#0066b2;">: <?php echo $data_pasien->TANGGAL_LAHIR; ?></span></td>
                    </tr>
                    <tr>
        				<td>UMUR</td>
        				<td><span style="color:#0066b2;">: <?php echo $data_pasien->UMUR; ?> Tahun</span></td>
        			</tr>
                    <tr>
                        <td>GOL. DARAH</td>
                        <td><span style="color:#0066b2;">: <?php echo $data_pasien->GOLONGAN_DARAH; ?></span></td>
                    </tr>
        		</tbody>
        	</table>
        </div>
        <div class="col-md-6">
          <table class="table">
            <tbody>
                <tr>
                    <td>PENDIDIKAN</td>
                    <td><span style="color:#0066b2;">: <?php echo $data_pasien->PENDIDIKAN; ?></span></td>
                </tr>
                <tr>
                    <td>AGAMA</td>
                    <td><span style="color:#0066b2;">: <?php echo $data_pasien->AGAMA; ?></span></td>
                </tr>
                <tr>
                    <td>PEKERJAAN</td>
                    <td><span style="color:#0066b2;">: <?php echo $kerja; ?></span></td>
                </tr>
                <tr>
                    <td>TEMPAT LAHIR</td>
                    <td><span style="color:#0066b2;">: <?php echo $data_pasien->TEMPAT_LAHIR; ?></span></td>
                </tr>
                <tr>
                    <td>ALAMAT</td>
                    <td>
                        <span style="color:#0066b2;">
                          : <?php echo $data_pasien->ALAMAT; ?> Kec. <?php echo $data_pasien->KECAMATAN; ?>,
                          Kel. <?php echo $data_pasien->KELURAHAN; ?>, Kec. <?php echo $data_pasien->KOTA; ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>NAMA ORTU</td>
                    <td><span style="color:#0066b2;">: <?php echo $data_pasien->NAMA_AYAH; ?></span></td>
                </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
	</div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card-box">
            <div class="row">
                <ul class="nav nav-tabs">
                    <li role="presentation" class="active">
                        <a href="#tindakan1" role="tab" data-toggle="tab"><i class="fa fa-stethoscope"></i>&nbsp;Tindakan</a>
                    </li>
                    <li role="presentation" id="dt_diagnosa">
                        <a href="#diagnosa1" role="tab" data-toggle="tab"><i class="fa fa-heartbeat"></i>&nbsp;Diagnosa</a>
                    </li>
                    <li role="presentation" id="dt_laborat">
                        <a href="#laborat1" role="tab" data-toggle="tab"><i class="fa fa-building"></i>&nbsp;Laborat</a>
                    </li>
                    <li role="presentation" id="dt_resep">
                        <a href="#resep1" role="tab" data-toggle="tab"><i class="fa fa-medkit"></i>&nbsp;Resep</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane fade in active" id="tindakan1">
                        <form class="form-horizontal" id="view_tindakan">
                            <div class="form-group">
                                <div class="col-md-6">
                                    <h4 class="m-t-0"><i class="fa fa-table"></i>&nbsp;<b>Tabel Tindakan</b></h4>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="cari_tdk" id="cari_tdk" placeholder="ex : <?php echo date('d-m-Y'); ?>" value="" onkeypress="return onEnterTextTdk(event);">
                                        <span class="input-group-btn">
                                            <button type="button" class="btn waves-effect waves-light btn-default" id="tombol_cari_tdk">
                                                <i class="fa fa-search"></i>
                                            </button>
                                            <button type="button" class="btn waves-effect waves-light btn-warning" id="tombol_reset_tdk" data-original-title="Reset Pencarian" title="" data-placement="top" data-toggle="tooltip">
                                                <i class="fa fa-refresh"></i>
                                            </button>
                                        </span>
                                    </div>
                                    <span class="help-block" style="margin-bottom: 0px;">
                                        <small><i>*ketikkan tanggal rekam medik untuk pencarian, lalu tekan Enter</i></small>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12"> 
                                    <div class="table-responsive">
                                        <table id="tabel_tindakan" class="table table-bordered">
                                            <thead>
                                                <tr class="merah">
                                                    <th style="color:#fff; text-align:center;">No</th>
                                                    <th style="color:#fff; text-align:center;">Tanggal</th>
                                                    <th style="color:#fff; text-align:center;">Tindakan</th>
                                                    <th style="color:#fff; text-align:center;">Poli</th>
                                                    <th style="color:#fff; text-align:center;">Dokter</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div role="tabpanel" class="tab-pane fade" id="diagnosa1">
                        <form class="form-horizontal" id="view_diagnosa">
                            <div class="form-group">
                                <div class="col-md-6">
                                    <h4 class="m-t-0"><i class="fa fa-table"></i>&nbsp;<b>Tabel Diagnosa</b></h4>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="cari_dg" id="cari_dg" placeholder="ex : <?php echo date('d-m-Y'); ?>" value="" onkeypress="return onEnterTextDg(event);">
                                        <span class="input-group-btn">
                                            <button type="button" class="btn waves-effect waves-light btn-default" id="tombol_cari_dg">
                                                <i class="fa fa-search"></i>
                                            </button>
                                            <button type="button" class="btn waves-effect waves-light btn-warning" id="tombol_reset_dg" data-original-title="Reset Pencarian" title="" data-placement="top" data-toggle="tooltip">
                                                <i class="fa fa-refresh"></i>
                                            </button>
                                        </span>
                                    </div>
                                    <span class="help-block" style="margin-bottom: 0px;">
                                        <small><i>*ketikkan tanggal rekam medik untuk pencarian, lalu tekan Enter</i></small>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table id="tabel_diagnosa" class="table table-bordered">
                                            <thead>
                                                <tr class="merah">
                                                    <th style="color:#fff; text-align:center;">No</th>
                                                    <th style="color:#fff; text-align:center;">Tanggal</th>
                                                    <th style="color:#fff; text-align:center;">Diagnosa</th>
                                                    <th style="color:#fff; text-align:center;">Tindakan</th>
                                                    <th style="color:#fff; text-align:center;">Jenis Penyakit</th>
                                                    <th style="color:#fff; text-align:center;">Poli</th>
                                                    <th style="color:#fff; text-align:center;">Dokter</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div role="tabpanel" class="tab-pane fade" id="laborat1">
                        <form class="form-horizontal" id="view_laborat">
                            <div class="form-group view_lab">
                                <div class="col-md-6">
                                    <h4 class="m-t-0"><i class="fa fa-table"></i>&nbsp;<b>Tabel Laborat</b></h4>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="cari_lab" id="cari_lab" placeholder="ex : <?php echo date('d-m-Y'); ?>" value="" onkeypress="return onEnterTextLab(event);">
                                        <span class="input-group-btn">
                                            <button type="button" class="btn waves-effect waves-light btn-default" id="tombol_cari_lab">
                                                <i class="fa fa-search"></i>
                                            </button>
                                            <button type="button" class="btn waves-effect waves-light btn-warning" id="tombol_reset_lab" data-original-title="Reset Pencarian" title="" data-placement="top" data-toggle="tooltip">
                                                <i class="fa fa-refresh"></i>
                                            </button>
                                        </span>
                                    </div>
                                    <span class="help-block" style="margin-bottom: 0px;">
                                        <small><i>*ketikkan tanggal rekam medik untuk pencarian, lalu tekan Enter</i></small>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group view_lab">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table id="tabel_laborat" class="table table-bordered">
                                            <thead>
                                                <tr class="merah">
                                                    <th style="color:#fff; text-align:center;">No</th>
                                                    <th style="color:#fff; text-align:center;" colspan="3">Jenis Laborat</th>
                                                    <th style="color:#fff; text-align:center;">Kode Lab</th>
                                                    <th style="color:#fff; text-align:center;">Tanggal</th>
                                                    <th style="color:#fff; text-align:center;">Cito</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div role="tabpanel" class="tab-pane fade" id="resep1">
                        <form class="form-horizontal" id="view_resep">
                            <div class="form-group">
                                <div class="col-md-6">
                                    <h4 class="m-t-0"><i class="fa fa-table"></i>&nbsp;<b>Tabel Resep</b></h4>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="cari_rsp" id="cari_rsp" placeholder="ex : <?php echo date('d-m-Y'); ?>" value="" onkeypress="return onEnterTextRsp(event);">
                                        <span class="input-group-btn">
                                            <button type="button" class="btn waves-effect waves-light btn-default" id="tombol_cari_rsp">
                                                <i class="fa fa-search"></i>
                                            </button>
                                            <button type="button" class="btn waves-effect waves-light btn-warning" id="tombol_reset_rsp" data-original-title="Reset Pencarian" title="" data-placement="top" data-toggle="tooltip">
                                                <i class="fa fa-refresh"></i>
                                            </button>
                                        </span>
                                    </div>
                                    <span class="help-block" style="margin-bottom: 0px;">
                                        <small><i>*ketikkan tanggal rekam medik untuk pencarian, lalu tekan Enter</i></small>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table id="tabel_resep" class="table table-bordered">
                                            <thead>
                                                <tr class="merah">
                                                    <th style="color:#fff; text-align:center;">No</th>
                                                    <th style="color:#fff; text-align:center;">Kode Resep</th>
                                                    <th style="color:#fff; text-align:center;">Tanggal</th>
                                                    <th style="color:#fff; text-align:center;">Diminum Selama</th>
                                                    <th style="color:#fff; text-align:center;">Alergi Obat</th>
                                                    <th style="color:#fff; text-align:center;">Poli</th>
                                                    <th style="color:#fff; text-align:center;">Dokter</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row">
                <form class="form-horizontal">
                    <div class="form-group">&nbsp;</div>
                    <div class="form-group">
                        <div class="col-md-4">
                            <button class="btn btn-purple btn-block m-b-5" type="button" id="btn_kembali">
                                <i class="fa fa-arrow-circle-left"></i>&nbsp;<b>Kembali</b>
                            </button>   
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
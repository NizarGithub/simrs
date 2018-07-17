<?PHP if($warning == 1){ ?>
<div class="alert alert-danger alert-dismissable" style="color: #b96463; font-size: 15px;">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> 
    Maaf, Kode Pendidikan telah terpakai. Silahkan pilih Kode Pendidikan yang berbeda.
</div>
<?PHP } ?>

<div class="row">
    <div class="col-sm-12">
        <div class="card-box">
            <div class="row"> 
                <div class="col-lg-12"> 
                    <form class="form-horizontal" role="form" method="post" action="<?=base_url().$post_url;?>">

                        <div class="form-group">
                            <label class="col-md-2 control-label" style="color: #0099e5;"> Kode Pendidikan </label>
                            <div class="col-md-2">
                                <input name="kode_pendidikan" required class="form-control" value="<?=$kode_golongan;?>" type="text" placeholder="Kode Pendidikan">
                            </div>  
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label" style="color: #0099e5;"> Jenjang </label>
                            <div class="col-md-4">
                                <select class="form-control select2" name="jenjang" id="jenjang" required onchange="get_nama_pendidikan();">
                                           <option value=""> -- Pilih </option>
                                           <?PHP foreach ($dt_jenjang as $key => $jjg) { ?>
                                           <option value="<?=$jjg->ID;?>"> <?=$jjg->NAMA_JENJANG;?> </option>
                                           <?PHP } ?>
                                </select>
                                <span class="help-block">
                                    <small> <a data-toggle="modal" data-target="#kelola_jen" href="#"> Kelola Jenjang </a> </small>
                                </span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label" style="color: #0099e5;"> Bidang </label>
                            <div class="col-md-4">
                                <select class="form-control select2" name="bidang" id="bidang" onchange="get_nama_pendidikan();">
                                       <option value=""> -- Pilih </option>
                                       <?PHP foreach ($dt_bidang as $key => $bid) { ?>
                                       <option value="<?=$bid->ID;?>"> <?=$bid->NAMA_BIDANG;?> </option>
                                       <?PHP } ?>
                                </select>
                                <small> <a data-toggle="modal" data-target="#kelola_bid" href="#"> Kelola Bidang </a> </small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label" style="color: #0099e5;"> Nama Pendidikan </label>
                            <div class="col-md-6">
                                <input name="nama_pendidikan" id="nama_pendidikan" required class="form-control" value="" type="text" placeholder="Nama Pendidikan">
                            </div>
                        </div>

                        <hr>

                        <div class="form-group">
                            <label class="col-md-2 control-label" style="color: #0099e5;"> Pangkat Min. </label>
                            <div class="col-md-4">
                                <select class="form-control select2" name="pangkat_min" id="pangkat_min" onchange="get_pangkat_max(this.value);">
                                       <option value=""> -- Pilih </option>
                                       <?PHP foreach ($dt_pangkat_min as $key => $pkt_min) { ?>
                                       <option value="<?=$pkt_min->ID;?>"> <?=$pkt_min->GOLONGAN;?>/<?=$pkt_min->RUANG;?> - <?=$pkt_min->NAMA;?> </option>
                                       <?PHP } ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label" style="color: #0099e5;"> Pangkat Max. </label>
                            <div class="col-md-4">
                                <select class="form-control select2" name="pangkat_max" id="pangkat_max">
                                    <option value=""> Silahkan Pilih Pangkat Max terlebih dahulu </option>
                                </select>
                            </div>
                        </div>


                        <div class="form-group m-b-0">
                            <div class="col-sm-offset-2 col-sm-10">
                              <input type="submit" class="btn btn-info" value="Simpan" name="simpan"/>
                              &nbsp;&nbsp;&nbsp;
                              <button type="reset" class="btn btn-inverse">Batal</button>
                            </div>
                        </div>

                    </form>
                </div><!-- end col -->

            </div><!-- end row -->
        </div>
    </div><!-- end col -->
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="card-box table-responsive">
            <div class="dropdown pull-right">
                <a href="#" class="dropdown-toggle card-drop" data-toggle="dropdown" aria-expanded="false">
                    <button type="button" class="btn btn-primary waves-effect waves-light w-md m-b-5">Cetak</button>
                </a>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="#">Cetak Excel</a></li>
                    <li><a href="#">Cetak PDF</a></li>
                    <li class="divider"></li>
                    <li><a href="#">Print</a></li>
                </ul>
            </div>

            <h4 class="header-title m-t-0 m-b-30">Data Pendidikan </h4>

            <table id="datatable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th style="text-align:center;"> # </th>
                        <th style="text-align:center;">Kode Pendidikan</th>
                        <th style="text-align:center;">Nama </th>
                        <th style="text-align:center;"> Jenjang </th>
                        <th style="text-align:center;"> Bidang </th>
                        <th style="text-align:center;"> Min. Pangkat </th>
                        <th style="text-align:center;"> Max. Pangkat </th>
                        <th style="text-align:center;">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <?PHP 
                        $no = 0;
                        foreach ($dt as $key => $row) { 
                            $no++;
                    ?>

                    <tr>
                        <td style="vertical-align:middle;"> <?=$no;?> </td>
                        <td style="vertical-align:middle;"> <?=$row->KODE;?> </td>
                        <td style="vertical-align:middle;"> <?=$row->NAMA;?> </td>
                        <td style="vertical-align:middle;"> <?=$row->NAMA_JENJANG;?> </td>
                        <td style="vertical-align:middle;"> <?=$row->NAMA_BIDANG;?> </td>
                        <td style="vertical-align:middle;"> <?=$row->MIN_PKT;?> </td>
                        <td style="vertical-align:middle;"> <?=$row->MAX_PKT;?> </td>
                        <td style="vertical-align:middle;" align="center"> 
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary dropdown-toggle waves-effect" data-toggle="dropdown" aria-expanded="false"> Aksi <span class="caret"></span> </button>
                                <ul class="dropdown-menu">
                                    <li><a onclick="ubah_pendidikan(<?=$row->ID;?>);" data-toggle="modal" data-target="#edit_modal" href="#"> Ubah </a></li>
                                    <li><a onclick="$('#dialog-btn').click(); $('#id_hapus').val('<?=$row->ID;?>');" href="javascript:;"> Hapus </a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>

                    <?PHP } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- KELOLA JENJANG POPUP -->
<!-- Edit Modal -->
<div id="kelola_jen" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"> Kelola Data Jenjang </h4>
            </div>
            <form method="post" action="<?=base_url().$post_url;?>">
            <div class="modal-body">                
                    <div class="row">

                        <div class="col-md-12">
                            <center>
                                <div class="form-group">
                                        <div class="radio radio-info radio-inline">
                                            <input type="radio" id="jen_sts1" value="Tambah" name="status_jen" checked onclick="what_jen();">
                                            <label for="jen_sts1"> Tambah Data </label>
                                        </div>
                                        <div class="radio radio-danger radio-inline">
                                            <input type="radio" id="jen_sts2" value="Hapus" name="status_jen" onclick="what_jen();">
                                            <label for="jen_sts2"> Hapus </label>
                                        </div>
                                </div>
                            </center>
                        </div>

                        <div class="col-md-12 hapus_jen" style="display:none;">
                            <div class="form-group">
                                <label for="jenjang_sel" class="control-label">Nama Jenjang</label>
                                <select class="form-control select2" name="jenjang_sel" id="jenjang_sel">
                                   <?PHP foreach ($dt_jenjang as $key => $jjg) { ?>
                                    <option value="<?=$jjg->ID;?>"> <?=$jjg->NAMA_JENJANG;?> </option>
                                   <?PHP } ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12 add_jen">
                            <div class="form-group">
                                <label for="add_nama_jenjang" class="control-label">Nama Jenjang</label>
                                <input type="text" class="form-control" id="add_nama_jenjang" name="add_nama_jenjang">
                            </div>
                        </div>

                        <div class="col-md-12 add_jen">
                            <div class="form-group">
                                <label class="control-label"> Deskripsi </label>
                                <textarea class="form-control" rows="3" name="add_des_j" id="add_des_j" style="resize:none;"></textarea>
                            </div>
                        </div>
                    </div>

                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default " data-dismiss="modal">Tutup</button>
                <input type="submit" style="display:none;" class="btn btn-danger hapus_jen" name="hapus_jen" value="Hapus"/>
                <input type="submit" class="btn btn-info add_jen" name="add_jen" value="Simpan"/>
            </div>
            </form>
        </div>
    </div>
</div>
<!-- /.modal -->

<!-- END OF KELOLA JENJANG POPUP -->

<!-- KELOLA BIDANG POPUP -->
<!-- Edit Modal -->
<div id="kelola_bid" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"> Kelola Data Bidang </h4>
            </div>
            <form method="post" action="<?=base_url().$post_url;?>">
            <div class="modal-body">                
                    <div class="row">

                        <div class="col-md-12">
                            <center>
                                <div class="form-group">
                                        <div class="radio radio-info radio-inline">
                                            <input type="radio" id="jen_sts1" value="Tambah" name="status_bid" checked onclick="what_bid();">
                                            <label for="jen_sts1"> Tambah Data </label>
                                        </div>
                                        <div class="radio radio-danger radio-inline">
                                            <input type="radio" id="jen_sts2" value="Hapus" name="status_bid" onclick="what_bid();">
                                            <label for="jen_sts2"> Hapus </label>
                                        </div>
                                </div>
                            </center>
                        </div>

                        <div class="col-md-12 hapus_bid" style="display:none;">
                            <div class="form-group">
                                <label for="jenjang_sel" class="control-label">Nama Bidang</label>
                                <select class="form-control select2" name="bidang_sel" id="bidang_sel">
                                   <?PHP foreach ($dt_bidang as $key => $bid) { ?>
                                    <option value="<?=$bid->ID;?>"> <?=$bid->NAMA_BIDANG;?> </option>
                                   <?PHP } ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12 add_bid">
                            <div class="form-group">
                                <label for="add_nama_jenjang" class="control-label">Nama Bidang</label>
                                <input type="text" class="form-control" id="add_nama_bidang" name="add_nama_bidang">
                            </div> 
                        </div>

                        <div class="col-md-12 add_bid">
                            <div class="form-group">
                                <label class="control-label"> Deskripsi </label>
                                <textarea class="form-control" rows="3" name="add_des_b" id="add_des_b" style="resize:none;"></textarea>
                            </div>
                        </div>
                    </div>

                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default " data-dismiss="modal">Tutup</button>
                <input type="submit" style="display:none;" class="btn btn-danger hapus_bid" name="hapus_bid" value="Hapus"/>
                <input type="submit" class="btn btn-info add_bid" name="add_bid" value="Simpan"/>
            </div>
            </form>
        </div>
    </div>
</div>
<!-- /.modal -->

<!-- END OF KELOLA BIDANG POPUP -->

<!-- HAPUS MODAL -->
<a id="dialog-btn" href="javascript:;" class="cd-popup-trigger" style="display:none;">View Pop-up</a>
<div class="cd-popup" role="alert">
    <div class="cd-popup-container">

        <form id="delete" method="post" action="<?=base_url().$post_url;?>">
            <input type="hidden" name="id_hapus" id="id_hapus" value="" />
        </form>   
         
        <p>Apakah anda yakin ingin menghapus data ini?</p>
        <ul class="cd-buttons">            
            <li><a href="javascript:;" onclick="$('#delete').submit();">Ya</a></li>
            <li><a onclick="$('.cd-popup-close').click(); $('#id_hapus').val('');" href="javascript:;">Tidak</a></li>
        </ul>
        <a href="#0" onclick="$('#id_hapus').val('');" class="cd-popup-close img-replace">Close</a>
    </div> <!-- cd-popup-container -->
</div> <!-- cd-popup -->
<!-- END HAPUS MODAL -->

<!-- Edit Modal -->
<div id="edit_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"> Ubah Pendidikan </h4>
            </div>
            <form method="post" action="<?=base_url().$post_url;?>">
            <div class="modal-body">                
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="ed_nama_pangkat" class="control-label"> Kode Pendidikan </label>
                                <input readonly type="text" class="form-control" id="ed_kode_pendidikan" name="ed_kode_pendidikan">
                                <input id="id_pen" name="id_pen" type="hidden" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="ed_jenjang" class="control-label">Nama Jenjang</label>
                                <select class="form-control" name="ed_jenjang" id="ed_jenjang">
                                   <?PHP foreach ($dt_jenjang as $key => $jjg) { ?>
                                    <option value="<?=$jjg->ID;?>"> <?=$jjg->NAMA_JENJANG;?> </option>
                                   <?PHP } ?>
                                </select> 
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="ed_bidang" class="control-label">Nama Bidang</label>
                                <select class="form-control" name="ed_bidang" id="ed_bidang">
                                   <?PHP foreach ($dt_bidang as $key => $bid) { ?>
                                    <option value="<?=$bid->ID;?>"> <?=$bid->NAMA_BIDANG;?> </option>
                                   <?PHP } ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="ed_nama_pangkat" class="control-label">Nama</label>
                                <input required type="text" class="form-control" id="ed_nama_pendidikan" name="ed_nama_pendidikan">
                            </div>
                        </div>

                        <hr>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label"> Pangkat Min. </label>
                                <select class="form-control select2" name="ed_pangkat_min" id="ed_pangkat_min" onchange="get_pangkat_max_ed(this.value);">
                                       <option value=""> -- Pilih </option>
                                       <?PHP foreach ($dt_pangkat_min as $key => $pkt_min) { ?>
                                       <option value="<?=$pkt_min->ID;?>"> <?=$pkt_min->GOLONGAN;?>/<?=$pkt_min->RUANG;?> - <?=$pkt_min->NAMA;?> </option>
                                       <?PHP } ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label"> Pangkat Max. </label>
                                <select class="form-control select2" name="ed_pangkat_max" id="ed_pangkat_max">
                                    <option value=""> Silahkan Pilih Pangkat Max terlebih dahulu </option>
                                </select>
                            </div>
                        </div>
                        
                    </div>

                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default " data-dismiss="modal">Tutup</button>
                <input type="submit" class="btn btn-info" name="ubah" value="Simpan"/>
            </div>
            </form>
        </div>
    </div>
</div>
<!-- /.modal -->

<script type="text/javascript">

function get_nama_pendidikan() {
    var a = $("#jenjang option:selected").text();
    var b = $("#bidang option:selected").text();

    var c = a+" - "+b;
    $('#nama_pendidikan').val(c);
}

function ubah_pendidikan(id) {
    $.ajax({
        url : '<?php echo base_url(); ?>kepeg/setup_pendidikan_c/get_data_pendidikan',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(res){
            $('#id_pen').val(id);
            $('#ed_kode_pendidikan').val(res.KODE);
            $('#ed_jenjang').val(res.JENJANG);
            $('#ed_bidang').val(res.BIDANG);
            $('#ed_nama_pendidikan').val(res.NAMA);
            $('#ed_pangkat_min').val(res.MIN_PANGKAT);
            get_pangkat_max_ed2(res.MIN_PANGKAT, res.MAX_PANGKAT);

            

            $("#ed_jenjang").select2();
            $("#ed_bidang").select2();
            

        }
    });
}

function what_jen(){
    var sts_jen = $("input[name='status_jen']:checked").val();
    if(sts_jen == "Hapus"){
        $('.add_jen').hide();
        $('.hapus_jen').show();
    } else if(sts_jen == "Tambah"){
        $('.hapus_jen').hide();
        $('.add_jen').show();
    }
}

function what_bid(){
    var sts_jen = $("input[name='status_bid']:checked").val();
    if(sts_jen == "Hapus"){
        $('.add_bid').hide();
        $('.hapus_bid').show();
    } else if(sts_jen == "Tambah"){
        $('.hapus_bid').hide();
        $('.add_bid').show();
    }
}

function get_pangkat_max(id_pangkat_min){
    $.ajax({
        url : '<?php echo base_url(); ?>kepeg/setup_pendidikan_c/get_pangkat_max',
        data : {id_pangkat_min:id_pangkat_min},
        type : "POST",
        dataType : "json",
        success : function(result){
            var isine="";
            $.each(result,function(i,res){
                isine += '<option value='+res.ID+'> '+res.GOLONGAN+'/'+res.RUANG+' - '+res.NAMA+'</option>';
            });        

            $('#pangkat_max').html(isine);
            $('#pangkat_max').trigger('change');
        }
    });
}

function get_pangkat_max_ed(id_pangkat_min){
    $.ajax({
        url : '<?php echo base_url(); ?>kepeg/setup_pendidikan_c/get_pangkat_max',
        data : {id_pangkat_min:id_pangkat_min},
        type : "POST",
        dataType : "json",
        success : function(result){
            var isine="";
            $.each(result,function(i,res){
                isine += '<option value='+res.ID+'> '+res.GOLONGAN+'/'+res.RUANG+' - '+res.NAMA+'</option>';
            });        

            $('#ed_pangkat_max').html(isine);
            $('#ed_pangkat_max').trigger('change');
        }
    });
}

function get_pangkat_max_ed2(id_pangkat_min, id_pangkat_max){
    $.ajax({
        url : '<?php echo base_url(); ?>kepeg/setup_pendidikan_c/get_pangkat_max',
        data : {id_pangkat_min:id_pangkat_min},
        type : "POST",
        dataType : "json",
        success : function(result){
            var isine="";
            $.each(result,function(i,res){
                isine += '<option value='+res.ID+'> '+res.GOLONGAN+'/'+res.RUANG+' - '+res.NAMA+'</option>';
            });        

            $('#ed_pangkat_max').html(isine);
            $('#ed_pangkat_max').val(id_pangkat_max);

            $('#ed_pangkat_max').trigger('change');
            $('#ed_pangkat_min').trigger('change');
        }
    });
}


</script>
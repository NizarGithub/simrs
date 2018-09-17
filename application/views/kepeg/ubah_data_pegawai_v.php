<style type="text/css">
.coba .active a {
    background: #21AFDA !important;
    color: #fff !important;
}

</style> 

<div class="row">
    <div class="col-sm-12">
        <div class="card-box" style="padding:0px;"> 
            <form method="post" class="form-horizontal" role="form" enctype="multipart/form-data" action="<?=base_url().$post_url;?>" onsubmit="return cek_nip_submit();">
                <div class="">
                    <ul class="nav nav-tabs coba">                        
                        <li role="presentation" class="active">
                            <a style="" href="#data_peg_tab" role="tab" data-toggle="tab" aria-expanded="true"> <i class="fa fa-user"></i> Data Pegawai</a>
                        </li>
                        <li role="presentation" class="">
                            <a style="" href="#pangkat_tab" role="tab" data-toggle="tab" aria-expanded="false"><i class="fa fa-graduation-cap"></i> Data Pangkat & Pendidikan</a>
                        </li>
                        <li role="presentation" class="">
                            <a style="" href="#jab_tab" role="tab" data-toggle="tab" aria-expanded="false"><i class="fa fa-black-tie"></i> Data Jabatan</a>
                        </li>                        
                        <li role="presentation" class="">
                            <a style="" href="#gol_pajak" role="tab" data-toggle="tab" aria-expanded="false"><i class="fa fa-newspaper-o"></i> Golongan Pajak</a>
                        </li>
                        <li role="presentation" class="">
                            <a style="" href="#foto_tab" role="tab" data-toggle="tab" aria-expanded="false"><i class="fa fa-image"></i> Foto Pegawai</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade active in" id="data_peg_tab">
                            <div class="row">
                            <div class="col-lg-10">  
                                    <div class="form-group">
                                        <label class="col-md-3 control-label" style="color: #0099e5;"> NIP </label>
                                        <div class="col-md-9">
                                            <input class="form-control" value="<?=$dt->NIP;?>" type="text" name="nip" id="nip" onchange="cek_nip();">
                                            <span class="help-block" id="warning_nip" style="display:none;">
                                                <small style="color: red; font-weight: bold; font-size: 13px;"> 
                                                    <i class="fa fa-warning"></i> Perhatian!! NIP tersebut telah terpakai oleh pegawai lain. 
                                                </small>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label" style="color: #0099e5;"> Nama Lengkap </label>
                                        <div class="col-md-9">
                                            <input class="form-control" value="<?=$dt->NAMA;?>" type="text" name="nama">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label" style="color: #0099e5;"> Kota Lahir </label>
                                        <div class="col-md-9">
                                            <input class="form-control" value="<?=$dt->KOTA_LAHIR;?>" type="text" name="kota_lahir">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label" style="color: #0099e5;"> Tgl Lahir </label>
                                        <div class="col-md-9">
                                            <div class="input-group datetimepicker">
                                                <input type="text" required data-mask="99-99-9999" class="form-control" name="tgl_lahir" value="<?=$dt->TGL_LAHIR;?>">
                                                <span class="font-13 text-muted">dd-mm-yyyy</span>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label class="col-md-3 control-label" style="color: #0099e5;"> Alamat </label>
                                        <div class="col-md-9">
                                            <textarea class="form-control" rows="5" name="alamat"><?=$dt->ALAMAT;?></textarea>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label" style="color: #0099e5;"> Telepon </label>
                                        <div class="col-md-9">
                                            <input class="form-control" type="text" name="telpon" value="<?=$dt->TELPON;?>">
                                        </div>
                                    </div>

                                
                            </div>
                        </div>
                        </div>

                        <div role="tabpanel" class="tab-pane fade" id="pangkat_tab"> 
                            <div class="row">
                                <div class="col-lg-10">  
                                    <div class="form-group">
                                        <label class="col-md-3 control-label" style="color: #0099e5;"> Status Pegawai </label>
                                        <div class="col-md-9">
                                            <select class="form-control" name="status" id="status">
                                                <option value=""> -- Pilih </option>  
                                                <option value=""> - </option>
                                                <option value="PNS PUSAT">PNS PUSAT</option>
                                                <option value="CPNS PUSAT">CPNS PUSAT</option>
                                                <option value="PNS DAERAH">PNS DAERAH</option>
                                                <option value="CPNS DAERAH">CPNS DAERAH</option>
                                                <option value="HONORER">HONORER</option>
                                                <option value="OUTSOURCING">OUTSOURCING</option>
                                                <!-- <option value="PENSIUN">PENSIUN</option>
                                                <option value="BERHENTI/PINDAH">BERHENTI/PINDAH</option>
                                                <option value="">MENINGGAL DUNIA</option>
                                                <option value="12">-</option>  -->                                           
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label" style="color: #0099e5;"> Pendidikan </label>
                                        <div class="col-md-6">
                                            <select class="form-control" name="id_pendidikan" id="id_pendidikan" onchange="get_pangkat_min(this.value);">
                                                    <option value=""> -- Pilih </option>                                                       
                                                    <?PHP foreach ($get_pendidikan as $key => $pend) {
                                                      echo "<option value='$pend->ID'>$pend->NAMA </option>";
                                                    }?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label" style="color: #0099e5;"> Min. Pangkat </label>
                                        <div class="col-md-3">
                                            <input class="form-control" readonly value="" type="text" name="min_pangkat" id="min_pangkat">
                                        </div>

                                        <label class="col-md-2 control-label" style="color: #0099e5;"> Max. Pangkat </label>
                                        <div class="col-md-3">
                                            <input class="form-control" readonly value="" type="text" name="max_pangkat" id="max_pangkat">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label" style="color: #0099e5;"> Pangkat </label>
                                        <div class="col-md-9">
                                            <select class="form-control" name="id_pangkat2" id="id_pangkat2" disabled>
                                                       <option value=""> -- Pilih </option>
                                                       <option value="27"> CPNS </option>
                                                    <?PHP 
                                                        foreach ($get_pangkat as $key => $pang) { 
                                                    ?>
                                                        <option value="<?=$pang->ID;?>"><?=$pang->GOLONGAN;?>/<?=$pang->RUANG;?> - <?=$pang->NAMA;?></option>

                                                    <?PHP } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <input type="hidden" name="id_pangkat" id="id_pangkat" value="" />

                                    <div class="form-group">
                                        <label class="col-md-3 control-label" style="color: #0099e5;"> Nomor SK </label>
                                        <div class="col-md-9">
                                            <input class="form-control" value="<?=$dt->SK_PANGKAT;?>" type="text" name="nomor_sk_pangkat">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label" style="color: #0099e5;"> Tanggal SK </label>
                                        <div class="col-md-9">
                                            <input class="form-control" data-mask="99-99-9999" value="<?=$dt->TGL_SK_PANGKAT;?>" type="text" name="tgl_sk_pangkat">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label" style="color: #0099e5;"> Tanggal Awal </label>
                                        <div class="col-md-9">
                                            <input class="form-control" data-mask="99-99-9999" value="<?=$dt->TGL_AWAL_PANGKAT;?>" type="text" name="tgl_awal_pangkat">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label" style="color: #0099e5;"> Tanggal Selesai </label>
                                        <div class="col-md-9">
                                            <input class="form-control" data-mask="99-99-9999" value="<?=$dt->TGL_AKHIR_PANGKAT;?>" type="text" name="tgl_selesai_pangkat">
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div role="tabpanel" class="tab-pane fade" id="jab_tab">
                            <div class="row">
                                <div class="col-lg-10">  
                                    <div class="form-group">
                                        <label class="col-md-3 control-label" style="color: #0099e5;"> Status Jabatan </label>
                                        <div class="col-md-9">
                                            <select class="form-control" name="sts_jabatan" id="sts_jabatan" onchange="get_jabatan(this.value);">
                                                    <option value="S"> STRUKTURAL </option>
                                                    <option value="NON"> NON STRUKTURAL </option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label" style="color: #0099e5;"> Jabatan </label>
                                        <div class="col-md-9">
                                            <select class="form-control select2" name="id_jabatan" id="id_jabatan">
                                                    <?PHP 
                                                        foreach ($get_jabatan as $key => $jab) { 
                                                    ?>
                                                        <option value="<?=$jab->ID;?>"><?=$jab->NAMA;?></option>

                                                    <?PHP } ?>
                                            </select>
                                        </div>
                                    </div>

                                     <div class="form-group">
                                        <label class="col-md-3 control-label" style="color: #0099e5;"> Departemen </label>
                                        <div class="col-md-9">
                                            <select class="form-control select2" name="id_dep" id="id_dep" onchange="get_divisi(this.value);">
                                                        <option value=""> -- Pilih Departemen </option>
                                                    <?PHP 
                                                        foreach ($get_departemen as $key => $dep) { 
                                                    ?>
                                                        <option value="<?=$dep->ID;?>"><?=$dep->NAMA_DEP;?></option>

                                                    <?PHP } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label" style="color: #0099e5;"> Divisi </label>
                                        <div class="col-md-9">
                                            <select class="form-control" name="id_div" id="id_div">

                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label" style="color: #0099e5;"> Nomor SK </label>
                                        <div class="col-md-9">
                                            <input class="form-control" value="<?=$dt->SK_JABATAN;?>" type="text" name="nomor_sk_jabatan">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label" style="color: #0099e5;"> Tanggal SK </label>
                                        <div class="col-md-9">
                                            <input class="form-control" data-mask="99-99-9999" value="<?=$dt->TGL_SK_JABATAN;?>" type="text" name="tgl_sk_jabatan">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label" style="color: #0099e5;"> Tanggal Awal </label>
                                        <div class="col-md-9">
                                            <input class="form-control" data-mask="99-99-9999" value="<?=$dt->TGL_AWAL_JABATAN;?>" type="text" name="tgl_awal_jabatan">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label" style="color: #0099e5;"> Tanggal Selesai </label>
                                        <div class="col-md-9">
                                            <input class="form-control" data-mask="99-99-9999" value="<?=$dt->TGL_AKHIR_JABATAN;?>" type="text" name="tgl_selesai_jabatan">
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div> 

                        <div role="tabpanel" class="tab-pane fade" id="foto_tab">
                            <div class="row">
                                <div class="col-lg-10">  
                                    <div class="form-group">
                                        <label class="col-md-3 control-label" style="color: #0099e5;"> Foto Pegawai </label>
                                        <div class="col-md-9">
                                            <input type="file" class="dropify" name="userfile[]" onchange="$('#temp_image').val(1);" data-default-file="<?=base_url();?>files/foto_pegawai/<?=$dt->FOTO;?>" />
                                            <input type="hidden" class="form-control" value="0" name="temp_image" id="temp_image">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div role="tabpanel" class="tab-pane fade" id="gol_pajak">
                            <div class="row">
                                <div class="col-lg-10">  
                                    <div class="form-group">
                                            <label class="col-md-3 control-label" style="color: #0099e5;"> Golongan Pajak </label>
                                            <div class="col-md-9">
                                                <select class="form-control" name="id_gol_pajak" id="id_gol_pajak" onchange="get_gol_pajak(this.value);">
                                                            <option value=""> Tanpa Golongan Pajak </option>
                                                        <?PHP 
                                                            foreach ($get_gol_pajak as $key => $gol_pajak) { 
                                                        ?>
                                                            <option value="<?=$gol_pajak->ID;?>"><?=$gol_pajak->KODE_GOLONGAN;?></option>

                                                        <?PHP } ?>
                                                </select>
                                            </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label" style="color: #0099e5;"> </label>
                                        <div class="col-md-9">
                                            <input readonly class="form-control" value="" type="text" name="nama_gol_pajak" id="nama_gol_pajak">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label" style="color: #0099e5;"> Nilai PTKP </label>
                                        <div class="col-md-9">
                                            <input readonly class="form-control" value="" type="text" name="ptkp" id="ptkp">
                                        </div>
                                    </div>
                            </div>
                        </div>

                    </div>

                <hr>

                <div class="form-group m-b-0" style="margin-top: 20px;">
                    <div class="col-sm-12">
                        <center>
                          <input type="submit" class="btn btn-info" value="Ubah Data Pegawai" name="simpan"/>
                          &nbsp;&nbsp;&nbsp;
                          <a href="<?=base_url();?>kepeg/data_pegawai_c" class="btn btn-inverse"> Batal </a>
                        </center>
                    </div>
                </div>

                </div><!-- end row -->
            </form>
        </div>
    </div><!-- end col -->
</div>

<input type="hidden" name="sts_nip" id="sts_nip" value=""/>
<script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){

    $('#status').val('<?PHP echo $dt->STATUS;?>');
    $('#id_pendidikan').val(<?PHP echo $dt->ID_PENDIDIKAN;?>);
    get_pangkat_min(<?PHP echo $dt->ID_PENDIDIKAN;?>);
    $('#id_pangkat').val(<?PHP echo $dt->ID_PANGKAT;?>);
    $('#id_pangkat2').val(<?PHP echo $dt->ID_PANGKAT;?>);

    $('#sts_jabatan').val('<?PHP echo $dt->STS_JABATAN;?>');
    $('#id_jabatan').val(<?PHP echo $dt->ID_JABATAN;?>);
    $('#id_dep').val(<?PHP echo $dt->ID_DEPARTEMEN;?>);
    get_divisi2(<?PHP echo $dt->ID_DEPARTEMEN;?>);
    $('#id_gol_pajak').val(<?PHP echo $dt->ID_GOL_PAJAK;?>);
    get_gol_pajak(<?PHP echo $dt->ID_GOL_PAJAK;?>);

});
function get_gol_pajak(id_gol){
    $.ajax({
        url : '<?php echo base_url(); ?>kepeg/add_pegawai_c/get_gol_pajak',
        data : {id_gol:id_gol},
        type : "POST",
        dataType : "json",
        success : function(res){       
            $('#nama_gol_pajak').val(res.NAMA_GOLONGAN);

            if(id_gol == ""){
                $('#ptkp').val(0);
            } else {
                $('#ptkp').val(NumberToMoney(res.PTKP).split('.00').join(''));
            }
            
        }   
    });
}

function get_pangkat_min(id_pendidikan){
     $.ajax({
        url : '<?php echo base_url(); ?>kepeg/add_pegawai_c/get_pangkat_min',
        data : {id_pendidikan:id_pendidikan},
        type : "POST",
        dataType : "json",
        success : function(res){       
            $('#id_pangkat').val(res.MIN_PANGKAT);           

            if(id_pendidikan == ""){
                $('#min_pangkat').val('');
                $('#max_pangkat').val('');
            } else {
                $('#min_pangkat').val(res.GOL_MIN+'/'+res.RUANG_MIN);
                $('#max_pangkat').val(res.GOL_MAX+'/'+res.RUANG_MAX);
            }
        }   
    });
}

function cek_nip(){
    var nip = $('#nip').val();    
    $.ajax({
        url : '<?php echo base_url(); ?>kepeg/add_pegawai_c/cek_nip',
        data : {nip:nip},
        type : "POST",
        dataType : "json",
        success : function(result){  

            $('#sts_nip').val(result);

            if(result > 0){
                $('#warning_nip').show();
            } else {
                $('#warning_nip').hide();
            }        
            
        }
    });
}

function cek_nip_submit(){
   var a = true;   
   var res = $('#sts_nip').val();

    if(res > 0){
        a = false;
        alert("NIP tersebut telah terpakai oleh pegawai lain !");
    } else {
        a = true;
    }  

    return a;

}

function get_divisi (id_dep) {

    $.ajax({
        url : '<?php echo base_url(); ?>kepeg/add_pegawai_c/get_divisi',
        data : {id_dep:id_dep},
        type : "POST",
        dataType : "json",
        success : function(result){
            if(result.length > 0){
                var isine = "<option value='0'> Tanpa Divisi </option>";
                $.each(result,function(i,res){
                    isine += '<option value='+res.ID+'> '+res.NAMA_DIV+' </option>';
                });
            } else {
                var isine = "<option value='0'> Tanpa Divisi </option>";
            }

            $('#id_div').html(isine);            
        }
    });
}

function get_divisi2 (id_dep) {

    $.ajax({
        url : '<?php echo base_url(); ?>kepeg/add_pegawai_c/get_divisi',
        data : {id_dep:id_dep},
        type : "POST",
        dataType : "json",
        success : function(result){
            if(result.length > 0){
                var isine = "<option value='0'> Tanpa Divisi </option>";
                $.each(result,function(i,res){
                    isine += '<option value='+res.ID+'> '+res.NAMA_DIV+' </option>';
                });
            } else {
                var isine = "<option value='0'> Tanpa Divisi </option>";
            }

            $('#id_div').html(isine);            
            $('#id_div').val(<?PHP echo $dt->ID_DIVISI;?>);            
        }
    });
}

function get_jabatan(jenis) {
    $.ajax({
        url : '<?php echo base_url(); ?>kepeg/add_pegawai_c/get_jabatan2',
        data : {jenis:jenis},
        type : "POST",
        dataType : "json",
        success : function(result){
            var isine="";
            $.each(result,function(i,res){
                isine += '<option value='+res.ID+'> '+res.NAMA+' </option>';
            });        

            $('#id_jabatan').html(isine);
            $('#id_jabatan').trigger('change');
        }
    });
}

</script>
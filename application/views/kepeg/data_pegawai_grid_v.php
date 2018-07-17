<div id="popup_load">
    <div class="window_load">
        <img src="<?=base_url()?>picture/progress.gif" height="100" width="125">
    </div>  
</div>
    
<a class="btn btn-default btn-bordred" href="<?=base_url();?>kepeg/data_pegawai_c"> <i class="fa fa-th-list"></i> List </a>
<a class="btn btn-success btn-bordred" href="<?=base_url();?>kepeg/data_pegawai_c/grid"> <i class="fa fa-th"></i> Grid </a>

<div class="card-box card-tabs" style="margin-top: 10px;">
    <div class="row"> 
        <div class="col-md-12"> 
            <form class="form-horizontal" role="form"> 
                <div class="form-group">
                    <label class="col-md-1 control-label" style="text-align:left; width:150px;">Cari Berdasarkan</label>
                    <div class="col-md-3">
                        <div class="radio radio-primary radio-inline">
                            <input id="cari_nama" type="radio" name="cari_berdasarkan" value="Nama" onclick="cek_Cari();">
                            <label for="nama_poli"> Nama Pegawai </label>
                        </div>
                        <div class="radio radio-primary radio-inline">
                            <input id="cari_jab" type="radio" name="cari_berdasarkan" value="Jabatan" onclick="cek_Cari();">
                            <label for="jenis"> Jabatan </label>
                        </div>
                    </div>
                </div>

                <div class="form-group" id="nama_head" style="display:none;">
                    <label class="col-md-1 control-label" style="text-align:left; width:150px;"> &nbsp; </label>
                    <div class="col-md-5">
                        <div class="input-group">
                            <input type="text" class="form-control" id="cari_nama_inp" placeholder="Cari..." value="" onkeyup="cari_peg_by_nama();">
                            <span class="input-group-btn">
                                <button type="button" class="btn waves-effect waves-light btn-warning">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                        </div>
                    </div> 
                </div>

                <div class="form-group" id="jab_head" style="display:none;">
                    <label class="col-md-1 control-label" style="text-align:left; width:150px;"> &nbsp; </label>
                    <div class="col-md-3">
                        <select class="form-control select2" name="cari_jabatan" id="cari_jabatan" onchange="cari_peg_by_jabatan();">
                                    <option value=""> -- Pilih Jabatan </option>
                                <?PHP 
                                    foreach ($get_jabatan as $key => $jab) { 
                                ?>
                                    <option value="<?=$jab->ID;?>"><?=$jab->NAMA;?></option>

                                <?PHP } ?>
                        </select>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>


<div class="row" style="margin-bottom: 20px;">
    <div class="col-md-3">
        <span class="label label-primary" style="font-size: 13px;"> Ditemukan <b id="jml_peg"> <?=count($dt);?> </b> Data Pegawai <font id="jml_peg_opt"> </font> </span>
    </div>
</div>

<div class="row" id="isi_pegawai">
    <?PHP foreach ($dt as $key => $row) { ?> 
    <div class="col-md-3">
        <div class="text-center card-box" style="min-height: 440px; max-height: 440px;">
            <div class="dropdown pull-right">
                <a href="#" class="dropdown-toggle card-drop" data-toggle="dropdown" aria-expanded="false">
                    <i class="zmdi zmdi-more-vert"></i>
                </a>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="<?=base_url();?>kepeg/data_pegawai_c/ubah/<?=$row->ID;?>">Ubah</a></li>
                    <li><a onclick="hapus_peg('<?=$row->ID;?>');" href="javascript:;">Hapus</a></li>
                </ul>
            </div>
            <div>
                <img src="<?=base_url();?>files/foto_pegawai/<?=$row->FOTO;?>" class="img-circle thumb-xl img-thumbnail m-b-10" alt="profile-image">

                <p style="font-weight: bold; font-size: 15px; color:#71B6F9;">
                    <?=$row->NAMA;?>
                </p>

                <p class="m-b-10" style="font-weight: bold; font-size: 15px;">
                    <?=$row->JABATAN;?>
                </p>

                <table>

                    <tr>
                        <td style="vertical-align: top; width: 35%;" align="left"> <b> Departemen </b> </td>
                        <td style="vertical-align: top; width: 5%;" align="left"> : </td>
                        <td style="vertical-align: top;" align="left"> <?=$row->NAMA_DEP==null?"-":$row->NAMA_DEP;?> </td>
                    </tr>

                    <tr>
                        <td style="vertical-align: top; width: 35%;" align="left"> <b> Divisi </b> </td>
                        <td style="vertical-align: top; width: 5%;" align="left"> : </td>
                        <td style="vertical-align: top;" align="left"> <?=$row->NAMA_DIV==null?"-":$row->NAMA_DIV;?> </td>
                    </tr>

                    <tr>
                        <td style="vertical-align: top; width: 35%;" align="left"> <b> Alamat </b> </td>
                        <td style="vertical-align: top; width: 5%;" align="left"> : </td>
                        <td style="vertical-align: top;" align="left"> <?=$row->ALAMAT==null?"-":$row->ALAMAT;?> </td>
                    </tr>

                    <tr>
                        <td style="vertical-align: top; width: 35%;" align="left"> <b> Tgl Lahir </b> </td>
                        <td style="vertical-align: top; width: 5%;" align="left"> : </td>
                        <td style="vertical-align: top;" align="left"> <?=$row->TGL_LAHIR==null?"-":$row->TGL_LAHIR;?> </td>
                    </tr>

                    <tr>
                        <td style="vertical-align: top; width: 35%;" align="left"> <b> Telepon </b> </td>
                        <td style="vertical-align: top; width: 5%;" align="left"> : </td>
                        <td style="vertical-align: top;" align="left"> <?=$row->TELPON==null?"-":$row->TELPON;?> </td>
                    </tr>
                </table>

                <br>
                <button type="button" class="btn btn-custom btn-rounded waves-effect waves-light"> Detail Pegawai </button>
            </div>

        </div>
    </div>
    <?PHP } ?>
</div>

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

<script type="text/javascript" src="<?php echo base_url(); ?>js-devan/jquery-1.11.1.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){

    $("input[name='cari_berdasarkan']").click(function(){
        var cari = $("input[name='cari_berdasarkan']:checked").val();
        if(cari == 'Nama'){
            $('#jab_head').hide();
            $('#nama_head').show();
        }else{
            $('#nama_head').hide();
            $('#jab_head').show();
        }
    });
});

function hapus_peg(id){
    $('#id_hapus').val(id);
    $('#dialog-btn').click(); 
}

function cari_peg_by_nama(){
    var keyword = $('#cari_nama_inp').val();
    $.ajax({
        url : '<?php echo base_url(); ?>kepeg/data_pegawai_c/cari_peg_by_nama',
        data : {keyword:keyword},
        type : "POST",
        dataType : "json",
        success : function(result){
            $('#jml_peg').html(result.length);
            

            if(keyword == ""){
                $('#jml_peg_opt').html('');
            } else {
                $('#jml_peg_opt').html('dengan keyword "'+keyword+'" ');
            }

            if(result.length > 0){
                var isine = "";
                $.each(result,function(i,res){

                    var nama_dep = res.NAMA_DEP;
                    if(nama_dep == null || nama_dep == ""){
                        nama_dep = "-";
                    }

                    var nama_div = res.NAMA_DIV;
                    if(nama_div == null || nama_div == ""){
                        nama_div = "-";
                    }

                    var alamat = res.ALAMAT;
                    if(alamat == null || alamat == ""){
                        alamat = "-";
                    }

                    var tgl_lahir = res.TGL_LAHIR;
                    if(tgl_lahir == null || tgl_lahir == ""){
                        tgl_lahir = "-";
                    }

                    var telpon = res.TELPON;
                    if(telpon == null || telpon == ""){
                        telpon = "-";
                    }

                    isine += '<div class="col-md-3">'+
                                '<div class="text-center card-box">'+
                                    '<div class="dropdown pull-right">'+
                                        '<a href="#" class="dropdown-toggle card-drop" data-toggle="dropdown" aria-expanded="false">'+
                                           '<i class="zmdi zmdi-more-vert"></i>'+
                                        '</a>'+
                                        '<ul class="dropdown-menu" role="menu">'+
                                            '<li><a href="<?=base_url();?>kepeg/data_pegawai_c/ubah/'+res.ID+'">Ubah</a></li>'+
                                            '<li><a onclick="hapus_peg('+res.ID+');" href="javascript:;">Hapus</a></li>'+
                                        '</ul>'+
                                    '</div>'+
                                    '<div>'+
                                        '<img src="<?=base_url();?>files/foto_pegawai/'+res.FOTO+'" class="img-circle thumb-xl img-thumbnail m-b-10" alt="profile-image">'+

                                        '<p style="font-weight: bold; font-size: 15px; color:#71B6F9;">'+res.NAMA+'</p>'+

                                        '<p class="m-b-10" style="font-weight: bold; font-size: 15px;">'+res.JABATAN+'</p>'+

                                        '<table>'+
                                            '<tr>'+
                                                '<td style="vertical-align: top; width: 35%;" align="left"> <b> Departemen </b> </td>'+
                                                '<td style="vertical-align: top; width: 5%;" align="left"> : </td>'+
                                                '<td style="vertical-align: top;" align="left"> '+nama_dep+' </td>'+
                                            '</tr>'+

                                            '<tr>'+
                                                '<td style="vertical-align: top; width: 35%;" align="left"> <b> Divisi </b> </td>'+
                                                '<td style="vertical-align: top; width: 5%;" align="left"> : </td>'+
                                                '<td style="vertical-align: top;" align="left"> '+nama_div+' </td>'+
                                            '</tr>'+

                                            '<tr>'+
                                                '<td style="vertical-align: top; width: 35%;" align="left"> <b> Alamat </b> </td>'+
                                                '<td style="vertical-align: top; width: 5%;" align="left"> : </td>'+
                                                '<td style="vertical-align: top;" align="left"> '+alamat+' </td>'+
                                            '</tr>'+

                                            '<tr>'+
                                                '<td style="vertical-align: top; width: 35%;" align="left"> <b> Tgl Lahir </b> </td>'+
                                                '<td style="vertical-align: top; width: 5%;" align="left"> : </td>'+
                                                '<td style="vertical-align: top;" align="left"> '+tgl_lahir+' </td>'+
                                            '</tr>'+

                                            '<tr>'+
                                                '<td style="vertical-align: top; width: 35%;" align="left"> <b> Telepon </b> </td>'+
                                                '<td style="vertical-align: top; width: 5%;" align="left"> : </td>'+
                                                '<td style="vertical-align: top;" align="left"> '+telpon+' </td>'+
                                            '</tr>'+
                                        '</table>'+

                                        '<br>'+
                                        '<button type="button" class="btn btn-custom btn-rounded waves-effect waves-light"> Detail Pegawai </button>'+
                                    '</div>'+
                                '</div>'+
                            '</div>';
                });
            } else {
                var isine = "Tidak ada data";
            }

            $('#isi_pegawai').html(isine);
        }
    });
}

function cari_peg_by_jabatan(){
    var keyword = $('#cari_jabatan').val();
    var keyword_txt = $("#cari_jabatan option:selected").html();
    $.ajax({
        url : '<?php echo base_url(); ?>kepeg/data_pegawai_c/cari_peg_by_jabatan',
        data : {keyword:keyword},
        type : "POST",
        dataType : "json",
        success : function(result){
            $('#jml_peg').html(result.length);
            

            if(keyword == ""){
                $('#jml_peg_opt').html('');
            } else {
                $('#jml_peg_opt').html('dengan jabatan "'+keyword_txt+'" ');
            }

            if(result.length > 0){
                var isine = "";
                $.each(result,function(i,res){

                    var nama_dep = res.NAMA_DEP;
                    if(nama_dep == null || nama_dep == ""){
                        nama_dep = "-";
                    }

                    var nama_div = res.NAMA_DIV;
                    if(nama_div == null || nama_div == ""){
                        nama_div = "-";
                    }

                    var alamat = res.ALAMAT;
                    if(alamat == null || alamat == ""){
                        alamat = "-";
                    }

                    var tgl_lahir = res.TGL_LAHIR;
                    if(tgl_lahir == null || tgl_lahir == ""){
                        tgl_lahir = "-";
                    }

                    var telpon = res.TELPON;
                    if(telpon == null || telpon == ""){
                        telpon = "-";
                    }

                    isine += '<div class="col-md-3">'+
                                '<div class="text-center card-box">'+
                                    '<div class="dropdown pull-right">'+
                                        '<a href="#" class="dropdown-toggle card-drop" data-toggle="dropdown" aria-expanded="false">'+
                                           '<i class="zmdi zmdi-more-vert"></i>'+
                                        '</a>'+
                                        '<ul class="dropdown-menu" role="menu">'+
                                            '<li><a href="<?=base_url();?>kepeg/data_pegawai_c/ubah/'+res.ID+'">Ubah</a></li>'+
                                            '<li><a onclick="hapus_peg('+res.ID+');" href="javascript:;">Hapus</a></li>'+
                                        '</ul>'+
                                    '</div>'+
                                    '<div>'+
                                        '<img src="<?=base_url();?>files/foto_pegawai/'+res.FOTO+'" class="img-circle thumb-xl img-thumbnail m-b-10" alt="profile-image">'+

                                        '<p style="font-weight: bold; font-size: 15px; color:#71B6F9;">'+res.NAMA+'</p>'+

                                        '<p class="m-b-10" style="font-weight: bold; font-size: 15px;">'+res.JABATAN+'</p>'+

                                        '<table>'+
                                            '<tr>'+
                                                '<td style="vertical-align: top; width: 35%;" align="left"> <b> Departemen </b> </td>'+
                                                '<td style="vertical-align: top; width: 5%;" align="left"> : </td>'+
                                                '<td style="vertical-align: top;" align="left"> '+nama_dep+' </td>'+
                                            '</tr>'+

                                            '<tr>'+
                                                '<td style="vertical-align: top; width: 35%;" align="left"> <b> Divisi </b> </td>'+
                                                '<td style="vertical-align: top; width: 5%;" align="left"> : </td>'+
                                                '<td style="vertical-align: top;" align="left"> '+nama_div+' </td>'+
                                            '</tr>'+

                                            '<tr>'+
                                                '<td style="vertical-align: top; width: 35%;" align="left"> <b> Alamat </b> </td>'+
                                                '<td style="vertical-align: top; width: 5%;" align="left"> : </td>'+
                                                '<td style="vertical-align: top;" align="left"> '+alamat+' </td>'+
                                            '</tr>'+

                                            '<tr>'+
                                                '<td style="vertical-align: top; width: 35%;" align="left"> <b> Tgl Lahir </b> </td>'+
                                                '<td style="vertical-align: top; width: 5%;" align="left"> : </td>'+
                                                '<td style="vertical-align: top;" align="left"> '+tgl_lahir+' </td>'+
                                            '</tr>'+

                                            '<tr>'+
                                                '<td style="vertical-align: top; width: 35%;" align="left"> <b> Telepon </b> </td>'+
                                                '<td style="vertical-align: top; width: 5%;" align="left"> : </td>'+
                                                '<td style="vertical-align: top;" align="left"> '+telpon+' </td>'+
                                            '</tr>'+
                                        '</table>'+

                                        '<br>'+
                                        '<button type="button" class="btn btn-custom btn-rounded waves-effect waves-light"> Detail Pegawai </button>'+
                                    '</div>'+
                                '</div>'+
                            '</div>';
                });
            } else {
                var isine = "Tidak ada data";
            }

            $('#isi_pegawai').html(isine);
        }
    });
}
</script>
<?PHP if($warning == 1){ ?>
<div class="alert alert-danger alert-dismissable" style="color: #b96463; font-size: 15px;">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    Maaf, kode divisi telah terpakai oleh divisi lain. Silahkan pilih kode yang berbeda.
</div>
<?PHP } ?> 

<div class="row">
    <div class="col-sm-12">
        <div class="card-box">
            <div class="row">
                <div class="col-lg-12">
                    <form class="form-horizontal" role="form" method="post" action="<?=base_url().$post_url;?>">
                        <div class="form-group">
                            <label class="col-md-2 control-label" style="color: #0099e5; margin-top: 10px;"> Departemen </label>
                            <div class="col-md-6">
                                <div class="input-group m-t-10">
                                    <input id="dep" name="dep" class="form-control" type="text" readonly value="<?=$nama_dep;?>">
                                    <span class="input-group-btn">
                                    <button type="button" class="btn waves-effect waves-light btn-primary" onclick="show_pop_departemen();">Cari Departemen</button>
                                    </span>
                                </div>
                                <input name="id_dep" id="id_dep" class="form-control" value="<?=$id_dep;?>" type="hidden">
                            </div>
                        </div>

                        <div class="form-group">
                        <label class="col-md-2 control-label" style="color: #0099e5;"> Kode Divisi </label>
                            <div class="col-md-6">
                                <input name="kode_div" required class="form-control" value="<?=$kode_div;?>" type="text" placeholder="Masukkan Kode Divisi">
                            </div>
                        </div>

                        <div class="form-group">
                        <label class="col-md-2 control-label" style="color: #0099e5;"> Nama Divisi </label>
                            <div class="col-md-6">
                                <input name="nama_div" required class="form-control" value="<?=$nama_div;?>" type="text" placeholder="Masukkan Nama Divisi">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label" style="color: #0099e5;"> Uraian </label>
                            <div class="col-md-6">
                                <textarea name="uraian" class="form-control" rows="5"><?=$uraian;?></textarea>
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

            <h4 class="header-title m-t-0 m-b-30">Data Divisi</h4>

            <table id="datatable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th style="text-align:center;">Kode Divisi</th>
                        <th style="text-align:center;">Nama Divisi</th>
                        <th style="text-align:center;">Departemen</th>
                        <th style="text-align:center;">Uraian</th>
                        <th style="text-align:center;">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <?PHP 
                        foreach ($dt as $key => $row) { 
                    ?>

                    <tr>
                        <td style="vertical-align:middle;"> <?=$row->KODE_DIV;?> </td>
                        <td style="vertical-align:middle;"> <?=$row->NAMA_DIV;?> </td>
                        <td style="vertical-align:middle;"> <?=$row->DEPARTEMEN;?> </td>
                        <td style="vertical-align:middle;"> <?=$row->URAIAN;?> </td>
                        <td style="vertical-align:middle;" align="center"> 
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary dropdown-toggle waves-effect" data-toggle="dropdown" aria-expanded="false"> Aksi <span class="caret"></span> </button>
                                <ul class="dropdown-menu">
                                    <li><a onclick="ubah_div(<?=$row->ID;?>);" data-toggle="modal" data-target="#edit_modal" href="#"> Ubah </a></li>
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
                <h4 class="modal-title"> Ubah Divisi </h4>
            </div>
            <form method="post" action="<?=base_url().$post_url;?>">
            <div class="modal-body">   

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group no-margin">
                                <label for="ed_uraian" class="control-label">Departemen</label>
                                <div class="input-group m-t-10">
                                    <input id="ed_dep_txt" name="ed_dep_txt" class="form-control" type="text" readonly >
                                    <input name="ed_id_dep" id="ed_id_dep" class="form-control" value="" type="hidden">
                                    <span class="input-group-btn">
                                    <button type="button" class="btn waves-effect waves-light btn-primary" onclick="show_pop_departemen_pop();">Cari Departemen</button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ed_kode_div" class="control-label">Kode Divisi</label>
                                <input id="ed_kode_div" name="ed_kode_div" type="text" class="form-control" readonly>
                                <input id="id_divisi" name="id_divisi" type="hidden" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ed_nama_div" class="control-label">Nama Divisi</label>
                                <input type="text" class="form-control" id="ed_nama_div" name="ed_nama_div">
                            </div>
                        </div>
                    </div>
                    

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group no-margin">
                                <label for="ed_uraian" class="control-label">Uraian</label>
                                <textarea class="form-control autogrow" id="ed_uraian" name="ed_uraian" style="overflow: hidden; word-wrap: break-word; resize: horizontal; height: 104px;"></textarea>
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

function show_pop_departemen(){
    get_popup_departemen();
    ajax_departemen();
}

function get_popup_departemen(){
    var base_url = '<?php echo base_url(); ?>';
    var $isi = '<div id="popup_koang">'+
                '<div class="window_koang">'+
                '    <a href="javascript:void(0);"><img src="'+base_url+'assets/custom/ico/cancel.gif" id="pojok_koang"></a>'+
                '    <div class="panel-body">'+
                '    <input style="width: 95%;" type="text" name="search_koang" id="search_koang" class="form-control" value="" placeholder="Cari Departemen...">'+
                '    <div class="table-responsive">'+
                '            <table class="table table-hover2" id="tes5">'+
                '                <thead>'+
                '                    <tr>'+
                '                        <th style="text-align:center;">NO</th>'+
                '                        <th style="text-align:center;" style="white-space:nowrap;">KODE</th>'+
                '                        <th style="text-align:center;"> DEPARTEMEN </th>'+
                '                        <th style="text-align:center;"> URAIAN </th>'+
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

function ajax_departemen(){
    var keyword = $('#search_koang').val();
    $.ajax({
        url : '<?php echo base_url(); ?>kepeg/setup_divisi_c/get_departemen',
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

                isine += '<tr onclick="get_data_dep('+res.ID+');" style="cursor:pointer;">'+
                            '<td align="center">'+no+'</td>'+
                            '<td align="center">'+res.KODE+'</td>'+
                            '<td align="left">'+res.NAMA_DEP+'</td>'+
                            '<td align="left">'+res.URAIAN+'</td>'+
                        '</tr>';
            });

            if(result.length == 0){
                isine = "<tr><td colspan='4' style='text-align:center'><b style='font-size: 15px;'> Data tidak tersedia </b></td></tr>";
            }

            $('#tes5 tbody').html(isine); 
            $('#search_koang').off('keyup').keyup(function(){
                ajax_departemen();
            });
        }
    });
}

function get_data_dep(id){
    $.ajax({
        url : '<?php echo base_url(); ?>kepeg/setup_divisi_c/get_data_dep',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(res){
            $('#dep').val(res.NAMA_DEP);
            $('#id_dep').val(id);

            $('#popup_koang').remove();
        }
    });

}

function ubah_div(id) {
    $.ajax({
        url : '<?php echo base_url(); ?>kepeg/setup_divisi_c/get_data_divisi',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(res){
            $('#id_divisi').val(id);
            $('#ed_kode_div').val(res.KODE_DIV);
            $('#ed_nama_div').val(res.NAMA_DIV);
            $('#ed_uraian').val(res.URAIAN);
            $('#ed_dep_txt').val(res.DEPARTEMEN);
            $('#ed_id_dep').val(res.ID_DEPARTEMEN);
        }
    });
}

function show_pop_departemen_pop(){
    get_popup_departemen_pop();
    ajax_departemen_pop();
}

function get_popup_departemen_pop(){
    var base_url = '<?php echo base_url(); ?>';
    var $isi = '<div id="popup_koang">'+
                '<div class="window_koang">'+
                '    <a href="javascript:void(0);"><img src="'+base_url+'assets/custom/ico/cancel.gif" id="pojok_koang"></a>'+
                '    <div class="panel-body">'+
                '    <input style="width: 95%;" type="text" name="search_koang" id="search_koang" class="form-control" value="" placeholder="Cari Departemen...">'+
                '    <div class="table-responsive">'+
                '            <table class="table table-hover2" id="tes5">'+
                '                <thead>'+
                '                    <tr>'+
                '                        <th style="text-align:center;">NO</th>'+
                '                        <th style="text-align:center;" style="white-space:nowrap;">KODE</th>'+
                '                        <th style="text-align:center;"> DEPARTEMEN </th>'+
                '                        <th style="text-align:center;"> URAIAN </th>'+
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

function ajax_departemen_pop(){
    var keyword = $('#search_koang').val();
    $.ajax({
        url : '<?php echo base_url(); ?>kepeg/setup_divisi_c/get_departemen',
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

                isine += '<tr onclick="get_data_dep_pop('+res.ID+');" style="cursor:pointer;">'+
                            '<td align="center">'+no+'</td>'+
                            '<td align="center">'+res.KODE+'</td>'+
                            '<td align="left">'+res.NAMA_DEP+'</td>'+
                            '<td align="left">'+res.URAIAN+'</td>'+
                        '</tr>';
            });

            if(result.length == 0){
                isine = "<tr><td colspan='4' style='text-align:center'><b style='font-size: 15px;'> Data tidak tersedia </b></td></tr>";
            }

            $('#tes5 tbody').html(isine); 
            $('#search_koang').off('keyup').keyup(function(){
                ajax_departemen();
            });
        }
    });
}

function get_data_dep_pop(id){
    $.ajax({
        url : '<?php echo base_url(); ?>kepeg/setup_divisi_c/get_data_dep',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(res){
            $('#ed_dep_txt').val(res.NAMA_DEP);
            $('#ed_id_dep').val(id);

            $('#popup_koang').remove();
        }
    });

}
</script>
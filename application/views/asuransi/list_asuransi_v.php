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
                    <a style="background:#f4f8fb;" href="#all" role="tab" data-toggle="tab" aria-expanded="true"> <i class="fa fa-list"></i> Data Asuransi </a>
                </li> 
                <li role="presentation" class="">
                    <a style="background:#f4f8fb;" href="#add_polis" role="tab" data-toggle="tab" aria-expanded="false"><i class="fa fa-plus"></i> Tambah Polis </a>
                </li>
            </ul> 
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade active in" id="all">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card-box table-responsive">
                                <h4 class="header-title m-t-0 m-b-30">Data Asuransi <?=$title;?></h4>
                                <table id="datatable" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="text-align:center;">No</th>
                                            <th style="text-align:center;">No Polis</th>
                                            <th style="text-align:center;">Nama Pemegang Polis</th>
                                            <th style="text-align:center;">Untuk</th>
                                            <th style="text-align:center;">Jumlah Klaim</th>
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
                                            <td align="center"><?=$no;?></td>
                                            <td align="left"><?=$row->NO_POLIS;?></td>
                                            <td align="left"><?=$row->NAMA_POLIS;?></td>
                                            <td align="left"><?=$row->NAMA;?> <br> <b><?=$row->KODE_PASIEN;?></b></td>
                                            <td align="right"><?=number_format($row->JML_KLAIM);?></td>
                                            <td style="vertical-align:top;" align="center">
                                                <a class="btn btn-danger" onclick="hapus_tim('<?=$row->ID;?>');" href="javascript:;"> <i class="fa fa-trash"></i> </a>
                                            </td>
                                        </tr>    
                                    <?PHP } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div role="tabpanel" class="tab-pane fade" id="add_polis">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card-box">
                                <form class="form-horizontal" role="form" method="post" action="<?=base_url().$post_url;?>" onsubmit="return cek_submit();">
                                <div class="row"> 
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">No. Polis</label>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" name="no_polis" id="no_polis" value="" required="required">
                                        </div>
                                    </div> 

                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Nama Pemegang Polis</label>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" name="nama_pemegang_polis" id="nama_pemegang_polis" value="" required="required">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label"> Untuk </label>
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <input id="nama_pasien" name="nama_pasien" class="form-control" type="text" readonly value="" style="background:#FFF;">
                                                <span class="input-group-btn">
                                                <button type="button" class="btn waves-effect waves-light btn-primary" onclick="show_pop_pasien();"> <i class="fa fa-search"></i> Pilih Pasien</button>
                                                </span>
                                            </div>
                                            <input name="id_pasien" id="id_pasien" class="form-control" value="" type="hidden">
                                        </div>
                                    </div>  

                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Jumlah Klaim</label>
                                        <div class="col-md-6">
                                            <input onkeyup="FormatCurrency(this);"  type="text" class="form-control" name="jml_klaim" id="jml_klaim" value="" required="required">
                                        </div>
                                    </div>                        

                                    <div class="form-group">
                                        <div class="col-sm-offset-5 col-sm-10">
                                            <input  type="submit" name="simpan" class="btn btn-success" value="Simpan"/>
                                            <button type="reset" class="btn btn-danger"><i class="fa fa-times"></i> <b>Batal</b></button>
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

<script type="text/javascript">

function hapus_tim(id){
    $('#id_hapus').val(id);
    $('#dialog-btn').click(); 
}

function show_pop_pasien(){
    get_popup_pasien();
    ajax_pasien();
}

function get_popup_pasien(){
    var base_url = '<?php echo base_url(); ?>';
    var $isi = '<div id="popup_koang">'+
                '<div class="window_koang">'+
                '    <a href="javascript:void(0);"><img src="'+base_url+'assets/custom/ico/cancel.gif" id="pojok_koang"></a>'+
                '    <div class="panel-body">'+
                '    <input style="width: 95%;" type="text" name="search_koang" id="search_koang" class="form-control" value="" placeholder="Cari Pegawai...">'+
                '    <div class="table-responsive">'+
                '       <div class="scroll-y">'+
                '            <table class="table table-hover2" id="tes5">'+
                '                <thead>'+
                '                    <tr>'+
                '                        <th style="text-align:center;">NO</th>'+
                '                        <th style="text-align:center;" style="white-space:nowrap;"> KODE PASIEN </th>'+
                '                        <th style="text-align:center;"> NAMA PASIEN </th>'+
                '                        <th style="text-align:center;"> ALAMAT </th>'+
                '                    </tr>'+
                '                </thead>'+
                '                <tbody>'+
            
                '                </tbody>'+
                '            </table>'+
                '        </div>'+
                '    </div>'+
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

function ajax_pasien(){
    var keyword = $('#search_koang').val();
    $.ajax({
        url : '<?php echo base_url(); ?>asuransi/list_asuransi_c/data_pasien',
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
                isine += '<tr onclick="get_data_pasien('+res.ID+');" style="cursor:pointer;">'+
                            '<td align="center">'+no+'</td>'+
                            '<td align="center">'+res.KODE_PASIEN+'</td>'+
                            '<td align="left">'+res.NAMA+'</td>'+
                            '<td align="center">'+res.ALAMAT+'</td>'+
                        '</tr>';
            });

            if(result.length == 0){
                isine = "<tr><td colspan='4' style='text-align:center'><b style='font-size: 15px;'> Data tidak tersedia </b></td></tr>";
            }

            $('#tes5 tbody').html(isine); 
            $('#search_koang').off('keyup').keyup(function(){
                ajax_pasien();
            });
        }
    });
}

function get_data_pasien(id_pasien){
    $.ajax({
        url : '<?php echo base_url(); ?>asuransi/list_asuransi_c/get_data_pasien_by_id',
        data : {id_pasien:id_pasien},
        type : "POST",
        dataType : "json",
        success : function(res){
            $('#nama_pasien').val(res.NAMA);;
            $('#id_pasien').val(id_pasien);
            $('#popup_koang').remove();
        }
    });
}
</script>
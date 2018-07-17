<style type="text/css">
.tbl_lab thead tr th{
    background: #167ccb none repeat scroll 0 0;
    color: #fff;
    text-align: center;
} 

.tbl_lab tfoot tr td{
    background: #b8cfed none repeat scroll 0 0;
    color: #666;
}
</style>
 
<div class="row">  
    <div class="col-sm-12">
        <div class="card-box">
            <div class="row">
                <div class="col-lg-12">
                    <form class="form-horizontal" role="form" method="post" action="<?=base_url().$post_url;?>">
                        <div class="form-group">
                            <label class="col-md-2 control-label"> Nomor Periksa </label>
                            <div class="col-md-4">
                                <input type="text" name="nomor_periksa" id="nomor_periksa" value="<?=$nomor;?>" class="form-control" readonly style="background:#FFF;" >
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-md-2 control-label"> Kode Pasien </label>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <input id="kode_pasien" name="kode_pasien" class="form-control" type="text" readonly value="" style="background:#FFF;">
                                    <span class="input-group-btn">
                                    <button type="button" class="btn waves-effect waves-light btn-primary" onclick="show_pop_rm();">Cari</button>
                                    </span>
                                </div>
                                <input name="id_pasien" id="id_pasien" class="form-control" value="" type="hidden">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label"> Pasien </label>
                            <div class="col-md-4">
                                <input type="text" name="nama_pasien" id="nama_pasien" class="form-control" readonly style="background:#FFF;" >
                            </div>
                        </div>

                        <div class="form-group" style="margin-top: -10px;">
                            <label class="col-md-2 control-label"> </label>
                            <div class="col-md-6">
                                <input type="text" name="alamat" id="alamat" class="form-control" readonly style="background:#FFF;" >
                            </div>
                        </div>

                        <hr style="border-color: #ccc;">
                        <center>
                            <h4 class="header-title m-t-0 m-b-30"> Jenis Laboratorium </h4>
                        </center>

                        <div class="row">
                            <?PHP foreach ($dt_jenis_lab as $key => $jns) { ?>
                            <div class="col-md-2">
                                <div class="checkbox checkbox-primary">
                                    <input id="jenis_<?=$jns->ID;?>" type="checkbox" value="<?=$jns->ID;?>" onclick="getRow(<?=$jns->ID;?>);">
                                    <label for="jenis_<?=$jns->ID;?>">
                                        <?=$jns->JENIS_LAB;?>
                                    </label>
                                </div>
                            </div>
                            <?PHP } ?>
                        </div>

                        <hr style="border-color: #ccc;">

                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered table-striped tbl_lab" id="tbl_lab">
                                    <thead>
                                        <tr>
                                            <th>KODE</th>
                                            <th>JENIS LAB</th>
                                            <th>CATATAN</th>
                                            <th>BIAYA</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3" align="center"><b>JUMLAH BIAYA</b></td>
                                            <td align="right"><b id="jml_biaya_txt">Rp. 0</b></td>
                                            <input type="hidden" name="jml_biaya" id="jml_biaya" value="0">
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <div class="form-group m-b-0">
                            <div class="col-sm-offset-5 col-sm-10">
                              <input type="submit" class="btn btn-info" value="Simpan" name="simpan"/>
                              &nbsp;
                              <button onclick="window.location='<?=base_url();?>lab/new_request_c';" type="reset" class="btn btn-danger">Batal</button>
                            </div>
                        </div>
                    </form>
                </div><!-- end col -->

            </div><!-- end row -->
        </div>
    </div><!-- end col -->
</div>

<script type="text/javascript">

function getRow(id){
    if($("#jenis_"+id).is(':checked')){
           $.ajax({
            url : '<?php echo base_url(); ?>lab/new_request_c/get_data_lab',
            data : {id:id},
            type : "POST",
            dataType : "json",
            success : function(res){
                var isi = '<tr id="lab_'+id+'">'+
                               '<input type="hidden" class="biaya_lab" name="biaya[]" value="'+res.BIAYA+'"/>'+
                               '<input type="hidden" name="id_lab[]" value="'+res.ID+'"/>'+
                               '<input type="hidden" name="kode_lab[]" value="'+res.KODE_LAB+'"/>'+
                               '<input type="hidden" name="jenis_lab[]" value="'+res.JENIS_LAB+'"/>'+
                               '<td>'+res.KODE_LAB+'</td>'+
                               '<td>'+res.JENIS_LAB+'</td>'+
                               '<td> <input type="text" name="catatan[]" id="catatan[]" class="form-control"/> </td>'+
                               '<td style="text-align:right;">Rp. '+NumberToMoney(res.BIAYA).split('.00').join('')+'</td>'+
                           '</tr>';
                $('#tbl_lab tbody').append(isi);

                var sum = 0;
                $('.biaya_lab').each(function(){
                    sum += parseFloat(this.value);
                });

                $('#jml_biaya').val(sum);
                $('#jml_biaya_txt').html('Rp '+NumberToMoney(sum).split('.00').join(''));
            }
        }); 
    } else {
        $('#lab_'+id).remove();
    }

    
}

function show_pop_rm(){
    get_popup_rm();
    ajax_rm();
}

function get_popup_rm(){
    var base_url = '<?php echo base_url(); ?>';
    var $isi = '<div id="popup_koang">'+
                '<div class="window_koang">'+
                '    <a href="javascript:void(0);"><img src="'+base_url+'assets/custom/ico/cancel.gif" id="pojok_koang"></a>'+
                '    <div class="panel-body">'+
                '    <input style="width: 95%;" type="text" name="search_koang" id="search_koang" class="form-control" value="" placeholder="Cari Pegawai...">'+
                '    <div class="table-responsive">'+
                '            <table class="table table-hover2 table-bordered" id="tes5">'+
                '                <thead>'+
                '                    <tr>'+
                '                        <th style="text-align:center;"> # </th>'+
                '                        <th style="text-align:center;"> No. RM </th>'+
                '                        <th style="text-align:center;"> NAMA </th>'+
                '                        <th style="text-align:center;"> ALAMAT </th>'+
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

function ajax_rm(){
    var keyword = $('#search_koang').val();
    $.ajax({
        url : '<?php echo base_url(); ?>lab/new_request_c/ajax_rm',
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
                isine += '<tr onclick="get_data_rm('+res.ID+');" style="cursor:pointer;">'+
                            '<td align="center">'+no+'</td>'+
                            '<td align="center">'+res.KODE_PASIEN+'</td>'+
                            '<td align="left">'+res.NAMA+'</td>'+
                            '<td align="left">'+res.ALAMAT+'</td>'+
                        '</tr>';
            });

            if(result.length == 0){
                isine = "<tr><td colspan='4' style='text-align:center'><b style='font-size: 15px;'> Data tidak tersedia </b></td></tr>";
            }

            $('#tes5 tbody').html(isine); 
            $('#search_koang').off('keyup').keyup(function(){
                ajax_rm();
            });
        }
    });
}

function get_data_rm(id){
    $.ajax({
        url : '<?php echo base_url(); ?>lab/new_request_c/get_data_rm',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(res){
            $('#id_pasien').val(id);
            $('#kode_pasien').val(res.KODE_PASIEN);
            $('#nama_pasien').val(res.NAMA);
            $('#alamat').val(res.ALAMAT);
            $('#popup_koang').remove();
        }
    });
}
</script>
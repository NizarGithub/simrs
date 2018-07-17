<?PHP 
    if(count($dt) == 0){
?>

<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PORTLET-->
        <div class="portlet box blue-steel">
            <div class="portlet-title">
                <div class="tools">
                    <a class="collapse" href="javascript:;" data-original-title="" title="">
                    </a>
                    <a class="config" data-toggle="modal" href="#portlet-config" data-original-title="" title="">
                    </a>
                </div>
            </div>
            <div class="portlet-body form">
                <form class="form-horizontal form-bordered" action="" method="post" action="<?=base_url().$post_url;?>">
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Jam Masuk</label>
                            <div class="col-md-3">
                                <div class="input-group m-b-15">
                                    <div class="bootstrap-timepicker">
                                        <input required name="jam_masuk" type="text" class="form-control timepicker2" value="08:15">
                                    </div>
                                    <span class="input-group-addon bg-primary b-0 text-white"><i class="glyphicon glyphicon-time"></i></span>
                                </div><!-- input-group -->
                            </div>
                        </div>

                        <div id="head_denda">
                            <div class="form-group denda">
                                <label class="control-label col-md-3">Denda 1</label>
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <input required type="text" name="jam_awal[]" class="form-control timepicker2" data-format="hh:mm" placeholder="Jam Awal">
                                        </div>

                                        <div class="col-md-1" style="margin-top: 8px; margin-left: -10px;">
                                            Sampai
                                        </div>

                                        <div class="col-md-3" style="margin-left: 7px;">
                                            <input required type="text" name="jam_akhir[]" class="form-control timepicker2" data-format="hh:mm" placeholder="Jam Akhir">
                                        </div>
                                    </div>

                                    <div class="row" style="margin-top: 8px;">
                                        <div class="col-md-6">
                                            <input required type="text" name="denda[]" onkeyup="FormatCurrency(this);" class="form-control" placeholder="Denda terlambat">
                                        </div>                                      
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="form-group last">
                            <label class="control-label col-md-3"></label>
                            <div class="col-md-2">
                                <a onclick="add_denda();" href="javascript:;" class="btn blue">
                                <i class="fa fa-plus"></i> Tambah Denda 
                                </a>
                            </div>                            
                        </div>

                        <div class="modal-footer">
                            <center>
                                <input type="submit" class="btn btn-info" name="simpan" value="Simpan"/>
                            </center>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?PHP 
    } else {
?>
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PORTLET-->
        <div class="portlet box blue-steel">
            <div class="portlet-title">
                <div class="tools">
                    <a class="collapse" href="javascript:;" data-original-title="" title="">
                    </a>
                    <a class="config" data-toggle="modal" href="#portlet-config" data-original-title="" title="">
                    </a>
                </div>
            </div>
            <div class="portlet-body form">
                <form class="form-horizontal form-bordered" action="" method="post" action="<?=base_url().$post_url;?>">
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Jam Masuk</label>
                            <div class="col-md-3">
                                <div class="input-group m-b-15">
                                    <div class="bootstrap-timepicker">
                                        <input required name="jam_masuk" type="text" class="form-control timepicker2" value="08:15">
                                    </div>
                                    <span class="input-group-addon bg-primary b-0 text-white"><i class="glyphicon glyphicon-time"></i></span>
                                </div>
                            </div>


                        </div>

                        <div id="head_denda">
                            <?PHP 
                            $no = 0;
                            foreach ($dt as $key => $row) { 
                                if($row->STATUS == 'DENDA'){
                                $no++;
                            ?>

                            <div id="denda_<?=$no;?>" class="form-group denda">
                                <label class="control-label col-md-3">Denda <?=$no;?></label>
                                <div class="col-md-8">
                                    <div class="row" id="row_<?=$no;?>">
                                        <div class="col-md-3">
                                            <input required value="<?=$row->JAM;?>" type="text" name="jam_awal[]" class="form-control timepicker2" data-format="hh:mm" placeholder="Jam Awal">
                                        </div>

                                        <div class="col-md-1" style="margin-top: 8px; margin-left: -10px;">
                                            Sampai
                                        </div>

                                        <div class="col-md-3" style="margin-left: 7px;">
                                            <input required value="<?=$row->JAM2;?>" type="text" name="jam_akhir[]" class="form-control timepicker2" data-format="hh:mm" placeholder="Jam Akhir">
                                        </div>
                                    </div>

                                    <div class="row" style="margin-top: 8px;">
                                        <div class="col-md-6">
                                            <input required value="<?=number_format($row->DENDA);?>" type="text" name="denda[]" onkeyup="FormatCurrency(this);" class="form-control" placeholder="Denda terlambat">
                                        </div>  
                                        <?PHP if($no > 1){ 
                                            $vis = "none";
                                            if($no == count($dt) - 1){
                                                $vis = "block";
                                            }
                                        ?>
                                        <div id="hapus_denda_<?=$no;?>" class="col-md-3" style="display:<?=$vis;?>;">
                                            <a onclick="delete_denda(<?=$no;?>);" href="javascript:;" class="btn red">
                                            <i class="fa fa-remove"></i> Hapus Denda
                                            </a>
                                        </div>      
                                        <?PHP } ?>                          
                                    </div>
                                </div>
                            </div>

                            <?PHP } } ?>
                        </div>


                        <div class="form-group last">
                            <label class="control-label col-md-3"></label>
                            <div class="col-md-2">
                                <a onclick="add_denda();" href="javascript:;" class="btn blue">
                                <i class="fa fa-plus"></i> Tambah Denda 
                                </a>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <center>
                                <input type="submit" class="btn btn-info" name="simpan" value="Simpan"/>
                            </center>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<?PHP } ?>

<div id="copy_denda" style="display:none;">
    <div class="col-md-3">
        <input required type="text" name="jam_awal[]" class="form-control timepicker2" data-format="hh:mm" placeholder="Jam Awal">
    </div>

    <div class="col-md-1" style="margin-top: 8px; margin-left: -10px;">
        Sampai
    </div>

    <div class="col-md-3" style="margin-left: 7px;">
        <input required type="text" name="jam_akhir[]" class="form-control timepicker2" data-format="hh:mm" placeholder="Jam Akhir">
    </div>
</div>

<script type="text/javascript">
function add_denda() {

    var value =$('#copy_denda').html();
    $jml = $('.denda').length;

    $('#hapus_denda_'+$jml).hide();
    var no = $jml + 1;
    $isi = '<div id="denda_'+no+'" class="form-group denda">'+
                '<label class="control-label col-md-3">Denda '+no+'</label>'+
                '<div class="col-md-8">'+
                    '<div class="row" id="row_'+no+'">'+
                        
                    '</div>'+

                    '<div class="row" style="margin-top: 8px;">'+
                        '<div class="col-md-6">'+
                            '<input required type="text" name="denda[]" onkeyup="FormatCurrency(this);" class="form-control" placeholder="Denda terlambat">'+
                        '</div>'+
                        '<div id="hapus_denda_'+no+'" class="col-md-3" style="display:block;">'+
                            '<a onclick="delete_denda('+no+');" href="javascript:;" class="btn red">'+
                            '<i class="fa fa-remove"></i> Hapus Denda '+
                            '</a>'+
                        '</div>'+
                    '</div>'+
                '</div>'+
            '</div>';

    $('#head_denda').append($isi);
    $("#row_"+no).html(value);
    jQuery('.timepicker2').timepicker({
        showMeridian : false
    });
}

function delete_denda(no){

    var nos = no -1;

    $('#denda_'+no).remove();
    $('#hapus_denda_'+nos).show();
}
</script>
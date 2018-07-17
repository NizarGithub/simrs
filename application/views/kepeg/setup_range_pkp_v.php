<?PHP if(count($dt) == 0){ ?>
<div class="row">
    <div class="col-sm-12">
        <div class="card-box">
            <div class="row">
                <div class="col-lg-12">
                    <form class="form-horizontal" role="form" method="post" action="<?=base_url().$post_url;?>">
                        <!-- MULAI RANGE PKP -->
                        <div class="form-group pkp" id="pkp_1">
                            <label class="col-md-3 control-label"> 1) Range PKP </label>
                            <div class="col-md-2">
                                <div class="input-group">
                                    <input  name="prosen_pkp[]" onkeyup="FormatCurrency(this);" class="form-control" type="text">
                                    <span class="input-group-addon">
                                        <b> % </b>
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <input  name="nilai_awal[]" onkeyup="FormatCurrency(this);" class="form-control" type="text">
                            </div>

                            <div class="col-md-1" style="text-align: center; width: 40px;">
                                <label class="control-label"> s/d </label>
                            </div>

                            <div class="col-md-2">
                                <input  name="nilai_akhir[]" onkeyup="FormatCurrency(this);" class="form-control" type="text">
                            </div>
                        </div>

                        <div class="form-group pkp" id="pkp_2">
                            <label class="col-md-3 control-label"> 2) Range PKP </label>
                            <div class="col-md-2">
                                <div class="input-group">
                                    <input  name="prosen_pkp[]" onkeyup="FormatCurrency(this);" class="form-control" type="text">
                                    <span class="input-group-addon">
                                        <b> % </b>
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <input  name="nilai_awal[]" onkeyup="FormatCurrency(this);" class="form-control" type="text">
                            </div>

                            <div class="col-md-1" style="text-align: center; width: 40px;">
                                <label class="control-label"> s/d </label>
                            </div>

                            <div class="col-md-2">
                                <input  name="nilai_akhir[]" onkeyup="FormatCurrency(this);" class="form-control" type="text">
                            </div>
                        </div>

                        <div class="form-group pkp" id="pkp_3">
                            <label class="col-md-3 control-label"> 3) Range PKP </label>
                            <div class="col-md-2">
                                <div class="input-group">
                                    <input  name="prosen_pkp[]" onkeyup="FormatCurrency(this);" class="form-control" type="text">
                                    <span class="input-group-addon">
                                        <b> % </b>
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <input  name="nilai_awal[]" onkeyup="FormatCurrency(this);" class="form-control" type="text">
                            </div>

                            <div class="col-md-1" style="text-align: center; width: 40px;">
                                <label class="control-label"> s/d </label>
                            </div>

                            <div class="col-md-2">
                                <input  name="nilai_akhir[]" onkeyup="FormatCurrency(this);" class="form-control" type="text">
                            </div>
                        </div>

                        <div class="form-group pkp" id="pkp_4">
                            <label class="col-md-3 control-label"> 4) Range PKP </label>
                            <div class="col-md-2">
                                <div class="input-group">
                                    <input  name="prosen_pkp[]" onkeyup="FormatCurrency(this);" class="form-control" type="text">
                                    <span class="input-group-addon">
                                        <b> % </b>
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <input  name="nilai_awal[]" onkeyup="FormatCurrency(this);" class="form-control" type="text">
                            </div>

                            <div class="col-md-1" style="text-align: center; width: 40px;">
                                <label class="control-label"> s/d </label>
                            </div>

                            <div class="col-md-2">
                                <input  name="nilai_akhir[]" onkeyup="FormatCurrency(this);" class="form-control" type="text">
                            </div>
                        </div> 

                        <div id="row_tmp">

                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label"></label>
                            <div class="col-md-4">
                                <button onclick="add_row();" type="button" class="btn btn-inverse"> <i class="fa fa-plus"></i> Tambah </button>
                            </div>
                        </div>

                        <!-- AKHIR RANGE PKP -->
                        <hr>

                        <div class="form-group m-b-0">
                            <div class="col-sm-offset-5 col-sm-10">
                              <input style="width: 20%; margin-left: -30px;" type="submit" class="btn btn-lg btn-info" value="Simpan" name="simpan"/>
                            </div>
                        </div>

                    </form>
                </div><!-- end col -->

            </div><!-- end row -->
        </div>
    </div><!-- end col -->
</div>
<?PHP } else { ?>
<div class="row">
    <div class="col-sm-12">
        <div class="card-box">
            <div class="row">
                <div class="col-lg-12">
                    <form class="form-horizontal" role="form" method="post" action="<?=base_url().$post_url;?>">
                        <!-- MULAI RANGE PKP -->
                        <?PHP 
                        $no = 0;
                        foreach ($dt as $key => $row) {
                           $no++;
                        ?>
                       
                        <div class="form-group pkp" id="pkp_<?=$no;?>">
                            <label class="col-md-3 control-label"> <?=$no;?>) Range PKP </label>
                            <div class="col-md-2">
                                <div class="input-group">
                                    <input  name="prosen_pkp[]" onkeyup="FormatCurrency(this);" class="form-control" type="text" value="<?=number_format($row->PROSEN);?>">
                                    <span class="input-group-addon">
                                        <b> % </b>
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <input  name="nilai_awal[]" onkeyup="FormatCurrency(this);" class="form-control" type="text" value="<?=number_format($row->NILAI_AWAL);?>">
                            </div>

                            <div class="col-md-1" style="text-align: center; width: 40px;">
                                <label class="control-label"> s/d </label>
                            </div>

                            <div class="col-md-2">
                                <input  name="nilai_akhir[]" onkeyup="FormatCurrency(this);" class="form-control" type="text" value="<?=number_format($row->NILAI_AKHIR);?>">
                            </div>
                        </div>

                         <?PHP } ?>

                        <div id="row_tmp">

                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label"></label>
                            <div class="col-md-4">
                                <button onclick="add_row();" type="button" class="btn btn-inverse"> <i class="fa fa-plus"></i> Tambah </button>
                            </div>
                        </div>

                        <!-- AKHIR RANGE PKP -->
                        <hr>

                        <div class="form-group m-b-0">
                            <div class="col-sm-offset-5 col-sm-10">
                              <input style="width: 20%; margin-left: -30px;" type="submit" class="btn btn-lg btn-info" value="Simpan" name="simpan"/>
                            </div>
                        </div>

                    </form>
                </div><!-- end col -->

            </div><!-- end row -->
        </div>
    </div><!-- end col -->
</div>
<?PHP } ?>


<script type="text/javascript">
function add_row() {
    var i = $('.pkp').length;
    var i = parseInt(i) + 1;
    $('.delete').hide();
    var isi =   '<div class="form-group pkp" id="pkp_'+i+'">'+
                    '<label class="col-md-3 control-label"> '+i+') Range PKP </label>'+
                    '<div class="col-md-2">'+
                        '<div class="input-group">'+
                            '<input  name="prosen_pkp[]" onkeyup="FormatCurrency(this);" class="form-control" type="text">'+
                            '<span class="input-group-addon">'+
                                '<b> % </b>'+
                            '</span>'+
                        '</div>'+
                    '</div>'+

                    '<div class="col-md-2">'+
                        '<input  name="nilai_awal[]" onkeyup="FormatCurrency(this);" class="form-control" type="text">'+
                    '</div>'+

                    '<div class="col-md-1" style="text-align: center; width: 40px;">'+
                        '<label class="control-label"> s/d </label>'+
                    '</div>'+

                    '<div class="col-md-2">'+
                        '<input  name="nilai_akhir[]" onkeyup="FormatCurrency(this);" class="form-control" type="text">'+
                    '</div>'+

                    '<div class="col-md-2 delete" id="delete_'+i+'">'+
                        '<button style="margin-top: 4px;" onclick="del_row('+i+');" type="button" class="btn btn-sm btn-danger"> <i class="fa fa-times"></i> Hapus  </button>'+
                    '</div>'+
                '</div>';

        $('#row_tmp').append(isi);
}

function del_row(id){
    $('#pkp_'+id).remove();
    $('.delete').hide();
    var i = $('.pkp').length;
    $('#delete_'+i).show();
}
</script>

<div class="row">
    <div class="col-sm-12">
        <div class="card-box">
            <div class="row">
                <div class="col-lg-12">
                    <form class="form-horizontal" role="form" method="post" action="<?=base_url().$post_url;?>">
                        <div class="form-group">
                        <label class="col-md-2 control-label"> Tahun </label>
                            <div class="col-md-6">
                                <select class="form-control" id="tahun" name="tahun" onchange="get_tgl(this.value);">
                                    <?php
                                        for($i=2014; $i<=$thn+1; $i++) {
                                            if ($i==$thn){
                                                echo"<option selected='selected' value=".$i."> ".$i." </option>";
                                            }else{
                                                echo"<option value=".$i."> ".$i." </option>";
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label"> Tanggal THR </label>
                            <div class="col-md-4">
                                <input class="form-control" data-mask="99-99-9999" value="<?=@$dt->TANGGAL;?>" type="text" name="tgl_thr" id="tgl_thr">
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
            <h4 class="header-title m-t-0 m-b-30"> Jadwal THR </h4>

            <table id="datatable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th style="text-align:center;">NO</th>
                        <th style="text-align:center;">TAHUN</th>
                        <th style="text-align:center;"> BULAN </th>
                        <th style="text-align:center;">TANGGAL</th>
                    </tr>
                </thead>

                <tbody>
                    <?PHP 
                        $no = 0;
                        foreach ($dt2 as $key => $row) { 
                            $no++;
                    ?>

                    <tr>
                        <td align="center" style="vertical-align:middle;"> <?=$no;?> </td>
                        <td align="center" style="vertical-align:middle;"> <?=$row->TAHUN;?> </td>
                        <td align="center" style="vertical-align:middle;"> <?=datetostr($row->BULAN);?> </td>
                        <td align="center" style="vertical-align:middle;"> <?=$row->TANGGAL;?> </td>
                    </tr>

                    <?PHP } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?PHP 
function datetostr($var){

 if($var == "01"){
    $var = "Januari";
 } else if($var == "02"){
    $var = "Februari";
 } else if($var == "03"){
    $var = "Maret";
 } else if($var == "04"){
    $var = "April";
 } else if($var == "05"){
    $var = "Mei";
 } else if($var == "06"){
    $var = "Juni";
 } else if($var == "07"){
    $var = "Juli";
 } else if($var == "08"){
    $var = "Agustus";
 } else if($var == "09"){
    $var = "September";
 } else if($var == "10"){
    $var = "Oktober";
 } else if($var == "11"){
    $var = "November";
 } else if($var == "12"){
    $var = "Desember";
 }

 return $var;

}
?>
<script type="text/javascript">
function get_tgl(tahun){
    $.ajax({
        url : '<?php echo base_url(); ?>kepeg/setup_jadwal_thr_c/get_tgl',
        data : {tahun:tahun},
        type : "POST",
        dataType : "json",
        success : function(res){
            $('#tgl_thr').val(res.TANGGAL);
        }
    });
}
</script>
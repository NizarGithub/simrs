<?PHP if($warning == 1){ ?>
<div class="alert alert-danger alert-dismissable" style="color: #b96463; font-size: 15px;">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    Maaf, kode departemen telah terpakai oleh departemen lain. Silahkan pilih kode yang berbeda.
</div>
<?PHP } ?>

<div class="row">
    <div class="col-sm-12">
        <div class="card-box">
            <div class="row">
                <div class="col-lg-12">
                    <form class="form-horizontal" role="form" method="post" action="<?=base_url().$post_url;?>">
                        <div class="form-group">
                            <label class="col-md-2 control-label" style="color: #0099e5;"> Tanggal Libur </label>
                            <div class="col-lg-6">
                                <input readonly class="form-control input-daterange-datepicker" type="text" name="tgl_libur" value="" placeholder="Masukkan tanggal libur"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label" style="color: #0099e5;"> Keterangan </label>
                            <div class="col-md-6">
                                <textarea name="keterangan" required class="form-control" rows="5"> </textarea>
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

<hr style="background: rgb(102, 102, 102) none repeat scroll 0% 0%; height: 2px;">

<form class="horizontal-form" method="post" action="<?=base_url().$post_url;?>">
    <div class="form-body">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Bulan</label>
                    <select class="form-control" name="bulan" id="bulan">
                        <option <?PHP if($bln == "01"){ echo "selected"; } ?> value="01">Januari</option>
                        <option <?PHP if($bln == "02"){ echo "selected"; } ?> value="02">Februari</option>
                        <option <?PHP if($bln == "03"){ echo "selected"; } ?> value="03">Maret</option>
                        <option <?PHP if($bln == "04"){ echo "selected"; } ?> value="04">April</option>
                        <option <?PHP if($bln == "05"){ echo "selected"; } ?> value="05">Mei</option>
                        <option <?PHP if($bln == "06"){ echo "selected"; } ?> value="06">Juni</option>
                        <option <?PHP if($bln == "07"){ echo "selected"; } ?> value="07">Juli</option>
                        <option <?PHP if($bln == "08"){ echo "selected"; } ?> value="08">Agustus</option>
                        <option <?PHP if($bln == "09"){ echo "selected"; } ?> value="09">September</option>
                        <option <?PHP if($bln == "10"){ echo "selected"; } ?> value="10">Oktober</option>
                        <option <?PHP if($bln == "11"){ echo "selected"; } ?> value="11">November</option>
                        <option <?PHP if($bln == "12"){ echo "selected"; } ?> value="12">Desember</option>
                    </select>
                </div>
            </div>
            <!--/span-->
            <div class="col-md-2">
                <div class="form-group">
                    <label class="control-label">Tahun</label>
                    <select class="form-control" id="tahun" name="tahun">
                        <?php
                            $thn = date('Y');
                            for($i=2014; $i<=$thn+1; $i++) {
                                if ($i==$tahun_aktif){
                                    echo"<option selected='selected' value=".$i."> ".$i." </option>";
                                }else{
                                    echo"<option value=".$i."> ".$i." </option>";
                                }
                            }
                        ?>
                    </select>
                </div>
            </div>
            <!--/span-->

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">&nbsp;</label>
                    <input type="submit" name="cari" class="btn btn-danger form-control" value="Tampilkan" />
                </div>
            </div>

        </div>
</form>


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

            <h4 class="header-title m-t-0 m-b-30">Data Hari Libur</h4>

            <table id="datatable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th style="text-align:center;"> Tanggal </th>
                        <th style="text-align:center;"> Bulan </th>
                        <th style="text-align:center;"> Tahun </th>
                        <th style="text-align:center;"> Keterangan </th>
                        <th style="text-align:center;"> Aksi </th>
                    </tr>
                </thead>

                <tbody>
                    <?PHP 
                        foreach ($dt as $key => $row) { 
                    ?>

                    <tr>
                        <td style="vertical-align:middle;"> <?=$row->TANGGAL;?> </td>
                        <td style="vertical-align:middle;"> <?=datetostr($row->BULAN);?> </td>
                        <td style="vertical-align:middle;"> <?=$row->TAHUN;?> </td>
                        <td style="vertical-align:middle;"> <?=$row->KET;?> </td>
                        <td style="vertical-align:middle;" align="center"> 
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary dropdown-toggle waves-effect" data-toggle="dropdown" aria-expanded="false"> Aksi <span class="caret"></span> </button>
                                <ul class="dropdown-menu">
                                    <li><a onclick="ubah_libur(<?=$row->ID;?>);" data-toggle="modal" data-target="#edit_modal" href="#"> Ubah </a></li>
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
                <h4 class="modal-title"> Ubah Departemen </h4>
            </div>
            <form method="post" action="<?=base_url().$post_url;?>">
            <div class="modal-body">                
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ed_tgl_libur" class="control-label"> Tanggal Libur </label>
                                <input id="ed_tgl_libur" name="ed_tgl_libur" type="text" class="form-control" readonly>
                                <input id="id_libur" name="id_libur" type="hidden" class="form-control">
                                <input id="ed_bln" name="ed_bln" type="hidden" class="form-control" value="<?=$bln;?>">
                                <input id="ed_thn" name="ed_thn" type="hidden" class="form-control" value="<?=$tahun_aktif;?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group no-margin">
                                <label for="ed_ket" class="control-label">Keterangan</label>
                                <textarea class="form-control autogrow" id="ed_ket" name="ed_ket" style="overflow: hidden; word-wrap: break-word; resize: horizontal; height: 104px;"></textarea>
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
function ubah_libur(id) {
    $.ajax({
        url : '<?php echo base_url(); ?>absensi/setup_hari_libur_c/get_data_libur',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(res){
            $('#id_libur').val(id);
            $('#ed_tgl_libur').val(res.TANGGAL);
            $('#ed_ket').val(res.KET);
        }
    });
}
</script>

<?PHP 
function datetostr($var){

 if($var == "1"){
    $var = "Januari";
 } else if($var == "2"){
    $var = "Februari";
 } else if($var == "3"){
    $var = "Maret";
 } else if($var == "4"){
    $var = "April";
 } else if($var == "5"){
    $var = "Mei";
 } else if($var == "6"){
    $var = "Juni";
 } else if($var == "7"){
    $var = "Juli";
 } else if($var == "8"){
    $var = "Agustus";
 } else if($var == "9"){
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
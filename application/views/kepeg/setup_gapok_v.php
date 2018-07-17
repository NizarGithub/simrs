<?PHP if($warning == 1){ ?>
<div class="alert alert-danger alert-dismissable" style="color: #b96463; font-size: 15px;">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    Maaf, kode kelompok jabatan telah terpakai untuk jabatan lain. Silahkan pilih kode yang berbeda.
</div>
<?PHP } ?>

<div class="row">
    <div class="col-sm-12"> 
        <div class="card-box">
            <div class="row">  
                <div class="col-lg-12">
                    <form class="form-horizontal" role="form" method="post" action="<?=base_url().$post_url;?>">

                        <div class="form-group">
                            <label class="col-md-2 control-label" style="color: #0099e5;"> Pangkat </label>
                            <div class="col-md-4">
                                <select class="form-control" name="id_pangkat" id="id_pangkat" onchange="get_gapok(this.value);">
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

                        <div class="form-group">
                            <label class="col-md-2 control-label" style="color: #0099e5;"> Gaji Pokok </label>
                            <div class="col-md-6">
                                <input onkeyup="FormatCurrency(this);" name="gapok" id="gapok" required class="form-control" value="" type="text" placeholder="">
                            </div>
                        </div> 

                        <div class="form-group">
                            <label class="col-md-2 control-label" style="color: #0099e5;"> THR </label>
                            <div class="col-md-6">
                                <input onkeyup="FormatCurrency(this);" name="thr" id="thr" required class="form-control" value="" type="text" placeholder="">
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

            <h4 class="header-title m-t-0 m-b-30">Data Gaji Pokok</h4>

            <table id="datatable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th style="text-align:center;">Golongan / Ruang</th>
                        <th style="text-align:center;">Nama Pangkat</th>
                        <th style="text-align:center;">Gaji Pokok </th>
                        <th style="text-align:center;">THR </th>
                    </tr>
                </thead>

                <tbody>
                    <?PHP 
                        foreach ($dt as $key => $row) { 
                    ?>
                    <?PHP if($row->GOLONGAN == "CPNS"){ ?>
                    <tr>
                        <td style="vertical-align:middle;"> CPNS </td>
                        <td style="vertical-align:middle;"> CPNS </td>
                        <td align="right" style="vertical-align:middle;"> <?=number_format($row->GAPOK);?> </td>
                        <td align="right" style="vertical-align:middle;"> <?=number_format($row->THR);?> </td>
                    </tr>
                    <?PHP } else { ?>
                    <tr>
                        <td style="vertical-align:middle;"> <?=$row->GOLONGAN;?>/<?=$row->RUANG;?> </td>
                        <td style="vertical-align:middle;"> <?=$row->NAMA;?> </td>
                        <td align="right" style="vertical-align:middle;"> <?=number_format($row->GAPOK);?> </td>
                        <td align="right" style="vertical-align:middle;"> <?=number_format($row->THR);?> </td>
                    </tr>

                    <?PHP } ?>

                    <?PHP } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<script type="text/javascript">

function get_gapok(id_pangkat){
    $.ajax({
        url : '<?php echo base_url(); ?>kepeg/setup_gapok_c/get_gapok_by_pangkat',
        data : {id_pangkat:id_pangkat},
        type : "POST",
        dataType : "json",
        success : function(res){
            var gapok = res.GAPOK;
            var thr = res.THR;
            if(gapok == "" || gapok == null){
                gapok = 0;
            }
            $('#gapok').val(NumberToMoney(gapok).split('.00').join(''));
            $('#thr').val(NumberToMoney(thr).split('.00').join(''));
        }
    });
}

function ubah_kel_jab(id) {
   $.ajax({
        url : '<?php echo base_url(); ?>kepeg/setup_kel_jabatan_c/get_data_jabatan',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(res){
            $('#id_kel_jabatan').val(id);
            $('#ed_kode_kel_jab').val(res.KODE_KEL_JAB);
            $('#ed_nama_kel_jab').val(res.NAMA);
            if(res.JENIS == 'S'){
                $('#ed_inlineRadio1').prop('checked', true);
                $('#ed_inlineRadio2').prop('checked', false);
            } else {
                $('#ed_inlineRadio1').prop('checked', false);
                $('#ed_inlineRadio2').prop('checked', true);
            }
        }
    });
}
</script>
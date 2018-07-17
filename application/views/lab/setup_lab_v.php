<?PHP if($warning == 1){ ?>
<div class="alert alert-danger alert-dismissable" style="color: #b96463; font-size: 15px;">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    Maaf, kode laboratorium telah terpakai oleh lab lain. Silahkan pilih kode yang berbeda. 
</div>
<?PHP } ?> 
 
<div class="row"> 
    <div class="col-sm-12"> 
        <div class="card-box">
            <div class="row"> 
                <div class="col-lg-12">
                    <form class="form-horizontal" role="form" method="post" action="<?=base_url().$post_url;?>">
                        <div class="form-group">
                            <label class="col-md-2 control-label" style="color: #0099e5;"> Kode Laboratorium</label>
                            <div class="col-md-3">
                                <input name="kode_lab" class="form-control" value="<?=$kode_lab;?>" type="text" placeholder="Masukkan Kode Laboratorium">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label" style="color: #0099e5;"> Jenis / Tipe Lab </label>
                            <div class="col-md-6">
                                <input name="jenis_lab" required class="form-control" value="<?=$jenis_lab;?>" type="text" placeholder="Masukkan Jenis / Tipe Laboratorium">
                            </div>
                        </div>
 
                        <div class="form-group">
                            <label class="col-md-2 control-label" style="color: #0099e5;"> Biaya </label>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-addon"><b>Rp</b></span>
                                    <input onkeyup="FormatCurrency(this);" name="biaya" class="form-control" required type="text" value="<?=$biaya;?>">
                                </div>
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
                              &nbsp;
                              <button type="reset" class="btn btn-danger">Batal</button>
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
            <h4 class="header-title m-t-0 m-b-30">Data Laboratorium</h4>

            <table id="datatable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th style="text-align:center;">No</th>
                        <th style="text-align:center;">Kode Laboratorium</th>
                        <th style="text-align:center;">Jenis / Tipe Lab</th>
                        <th style="text-align:center;">Biaya (Rp)</th>
                        <th style="text-align:center;">Uraian</th>
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
                        <td style="vertical-align:middle;" align="center"> <?=$no;?> </td>
                        <td style="vertical-align:middle;"> <?=$row->KODE_LAB;?> </td>
                        <td style="vertical-align:middle;"> <?=$row->JENIS_LAB;?> </td>
                        <td style="vertical-align:middle;" align="right"> <?=number_format($row->BIAYA);?> </td>
                        <td style="vertical-align:middle;"> <?=$row->URAIAN;?> </td>
                        <td style="vertical-align:middle;" align="center"> 
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary dropdown-toggle waves-effect" data-toggle="dropdown" aria-expanded="false"> Aksi <span class="caret"></span> </button>
                                <ul class="dropdown-menu">
                                    <li><a onclick="ubah_lab(<?=$row->ID;?>);" data-toggle="modal" data-target="#edit_modal" href="#"> Ubah </a></li>
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
                <h4 class="modal-title"> Ubah Laboratorium </h4>
            </div>
            <form method="post" action="<?=base_url().$post_url;?>">
            <div class="modal-body">                
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ed_kode_lab" class="control-label">Kode Laboratorium</label>
                                <input id="ed_kode_lab" name="ed_kode_lab" type="text" class="form-control" readonly>
                                <input id="id_lab" name="id_lab" type="hidden" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="ed_jenis_lab" class="control-label">Jenis / Tipe Lab</label>
                                <input type="text" class="form-control" id="ed_jenis_lab" name="ed_jenis_lab">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="ed_biaya" class="control-label">Biaya</label>
                                <input onkeyup="FormatCurrency(this);" type="text" class="form-control" id="ed_biaya" name="ed_biaya">
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
                <button type="button" class="btn btn-inverse" data-dismiss="modal">Tutup</button>
                <input type="submit" class="btn btn-success" name="ubah" value="Simpan"/>
            </div>
            </form>
        </div>
    </div>
</div>
<!-- /.modal -->

<script type="text/javascript">
function ubah_lab(id) {
    $.ajax({
        url : '<?php echo base_url(); ?>lab/setup_lab_c/get_data_lab',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(res){
            $('#id_lab').val(id);
            $('#ed_kode_lab').val(res.KODE_LAB);
            $('#ed_jenis_lab').val(res.JENIS_LAB);
            $('#ed_biaya').val(NumberToMoney(res.BIAYA).split('.00').join(''));
            $('#ed_uraian').val(res.URAIAN);
        }
    });
}
</script>
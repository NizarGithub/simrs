<?PHP if($warning == 1){ ?>
<div class="alert alert-danger alert-dismissable" style="color: #b96463; font-size: 15px;">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    Maaf, kode asuransi telah terpakai. Silahkan pilih kode yang berbeda. 
</div>
<?PHP } ?> 
 
<div class="row"> 
    <div class="col-sm-12">
        <div class="card-box"> 
            <div class="row"> 
                <div class="col-lg-12">
                    <form class="form-horizontal" role="form" method="post" action="<?=base_url().$post_url;?>" enctype="multipart/form-data">
                        <div class="form-group">
                            <label class="col-md-2 control-label"> Kode </label>
                            <div class="col-md-6">
                                <input name="kode_asr" class="form-control" value="<?=$kode_asr;?>" type="text" placeholder="Masukkan Kode Asuransi">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label"> Nama Asuransi </label>
                            <div class="col-md-6">
                                <input name="nama_asr" required class="form-control" value="<?=$nama_asr;?>" type="text" placeholder="Masukkan Nama Asuransi">
                            </div>
                        </div>
 
                        <div class="form-group">
                            <label class="col-md-2 control-label"> Uraian </label>
                            <div class="col-md-6">
                                <textarea name="uraian" required class="form-control" rows="3"><?=$uraian;?></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label"> Logo Asuransi </label>
                            <div class="col-md-4">
                                <input type="file" class="dropify" name="userfile[]" onchange="$('#temp_image').val(1);" />
                                <input type="hidden" class="form-control" value="0" name="temp_image" id="temp_image">
                            </div>
                        </div>

                        <div class="form-group m-b-0">
                            <div class="col-sm-offset-2 col-sm-10">
                              <input type="submit" class="btn btn-success" value="Simpan" name="simpan"/>
                              &nbsp;&nbsp;
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

            <h4 class="header-title m-t-0 m-b-30">Data Asuransi</h4>

            <table id="datatable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th style="text-align:center;">No</th>
                        <th style="text-align:center;">Logo</th>
                        <th style="text-align:center;">Kode Asuransi</th>
                        <th style="text-align:center;">Nama Asuransi</th>
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
                        <td style="vertical-align:middle;"> <?=$no;?> </td>
                        <td align="center" style="vertical-align:middle;"> <img width="80" height="80" src="<?=base_url();?>files/asuransi/<?=$row->LOGO;?>"/> </td>
                        <td style="vertical-align:middle;"> <?=$row->KODE;?> </td>
                        <td style="vertical-align:middle;"> <?=$row->NAMA_ASURANSI;?> </td>
                        <td style="vertical-align:middle;"> <?=$row->URAIAN;?> </td>
                        <td style="vertical-align:middle;" align="center"> 
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary dropdown-toggle waves-effect" data-toggle="dropdown" aria-expanded="false"> Aksi <span class="caret"></span> </button>
                                <ul class="dropdown-menu">
                                    <li><a onclick="ubah_asr(<?=$row->ID;?>);" data-toggle="modal" data-target="#edit_modal" href="#"> Ubah </a></li>
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
                <h4 class="modal-title"> Ubah Asuransi </h4>
            </div>
            <form method="post" action="<?=base_url().$post_url;?>" enctype="multipart/form-data">
            <div class="modal-body">                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="ed_kode_asr" class="control-label">Kode Asuransi</label>
                            <input id="ed_kode_asr" name="ed_kode_asr" type="text" class="form-control" readonly>
                            <input id="id_asuransi" name="id_asuransi" type="hidden" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="ed_nama_asr" class="control-label">Nama Asuransi</label>
                            <input type="text" class="form-control" id="ed_nama_asr" name="ed_nama_asr">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group no-margin">
                            <label for="ed_uraian" class="control-label">Uraian</label>
                            <textarea rows="3" class="form-control autogrow" id="ed_uraian" name="ed_uraian" style="overflow: hidden; word-wrap: break-word; resize: horizontal;"></textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label> Ganti Logo Asuransi </label>
                            <input type="file" class="dropify" name="userfile[]" onchange="$('#temp_image_ed').val(1);" />
                            <input type="hidden" class="form-control" value="0" name="temp_image_ed" id="temp_image_ed">
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
                <input type="submit" class="btn btn-success" name="ubah" value="Simpan"/>
            </div>
            </form>
        </div>
    </div>
</div>
<!-- /.modal -->

<script type="text/javascript">
function ubah_asr(id) {
    $.ajax({
        url : '<?php echo base_url(); ?>asuransi/data_asuransi_c/get_data_asuransi',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(res){
            $('#id_asuransi').val(id);
            $('#ed_kode_asr').val(res.KODE);
            $('#ed_nama_asr').val(res.NAMA_ASURANSI);
            $('#ed_uraian').val(res.URAIAN);
        }
    });
}
</script>
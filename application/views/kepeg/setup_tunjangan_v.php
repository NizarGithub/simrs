<?PHP if($warning == 1){ ?>
<div class="alert alert-danger alert-dismissable" style="color: #b96463; font-size: 15px;">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    Maaf, kode tunjangan / gaji telah terpakai. Silahkan pilih kode yang berbeda.
</div>
<?PHP } ?>

<div class="row">
    <div class="col-sm-12">
        <div class="card-box"> 
            <div class="row"> 
                <div class="col-lg-12"> 
                    <form class="form-horizontal" role="form" method="post" action="<?=base_url().$post_url;?>">
                        <div class="form-group">
                            <label class="col-md-2 control-label" style="color: #0099e5;"> Kode Tunjangan </label>
                            <div class="col-md-6">
                                <input name="kode_tunj" class="form-control" value="<?=$kode_tunj;?>" type="text" placeholder="Masukkan Kode Tunjangan / Gaji">
                            </div>
                        </div>

                        <div class="form-group">
                        <label class="col-md-2 control-label" style="color: #0099e5;"> Nama Tunjangan / Gaji </label>
                            <div class="col-md-6">
                                <input name="nama_tunj" required class="form-control" value="<?=$nama_tunj;?>" type="text" placeholder="Masukkan Nama Tunjangan / Gaji">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label" style="color: #0099e5;"> Uraian </label>
                            <div class="col-md-6">
                                <textarea name="uraian" required class="form-control" rows="5"><?=$uraian;?></textarea>
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

            <h4 class="header-title m-t-0 m-b-30">Data Tunjangan / Gaji</h4>

            <table id="datatable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th style="text-align:center;">Kode Tunjangan</th>
                        <th style="text-align:center;">Nama Tunjangan</th>
                        <th style="text-align:center;">Uraian</th>
                        <th style="text-align:center;">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <?PHP 
                        foreach ($dt as $key => $row) { 
                    ?>

                    <tr>
                        <td style="vertical-align:middle;"> <?=$row->KODE_GAJI;?> </td>
                        <td style="vertical-align:middle;"> <?=$row->NAMA_GAJI;?> </td>
                        <td style="vertical-align:middle;"> <?=$row->URAIAN;?> </td>
                        <td style="vertical-align:middle;" align="center"> 
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary dropdown-toggle waves-effect" data-toggle="dropdown" aria-expanded="false"> Aksi <span class="caret"></span> </button>
                                <ul class="dropdown-menu">
                                    <li><a onclick="ubah_dep(<?=$row->ID;?>);" data-toggle="modal" data-target="#edit_modal" href="#"> Ubah </a></li>
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
                <h4 class="modal-title"> Ubah Tunjangan </h4>
            </div>
            <form method="post" action="<?=base_url().$post_url;?>">
            <div class="modal-body">                
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ed_kode_tunj" class="control-label">Kode Tunjangan / Gaji</label>
                                <input id="ed_kode_tunj" name="ed_kode_tunj" type="text" class="form-control" readonly>
                                <input id="id_tunjangan" name="id_tunjangan" type="hidden" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="ed_nama_tunj" class="control-label">Nama Tunjangan / Gaji</label>
                                <input type="text" class="form-control" id="ed_nama_tunj" name="ed_nama_tunj">
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
function ubah_dep(id) { 
    $.ajax({
        url : '<?php echo base_url(); ?>kepeg/setup_tunjangan_c/get_data_tunjangan',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(res){
            $('#id_tunjangan').val(id);
            $('#ed_kode_tunj').val(res.KODE_GAJI);
            $('#ed_nama_tunj').val(res.NAMA_GAJI);
            $('#ed_uraian').val(res.URAIAN);
        }
    });
}
</script>
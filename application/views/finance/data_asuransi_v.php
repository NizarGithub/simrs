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
                    <button type="button" class="btn btn-danger waves-effect waves-light w-md m-b-5">Cetak</button>
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
                        $logo = '';
                        if($row->LOGO == null || $row->LOGO == ""){
                            $logo = base_url().'picture/noimage.png';
                        }else{
                            $logo = base_url()."files/asuransi/".$row->LOGO;
                        }
                ?>
                    <tr>
                        <td style="vertical-align:middle; text-align: center;"> <?=$no;?> </td>
                        <td align="center" style="vertical-align:middle;"> <img width="80" height="80" src="<?=$logo;?>"/> </td>
                        <td style="vertical-align:middle;"> <?=$row->NAMA_ASURANSI;?> </td>
                        <td style="vertical-align:middle;"> <?=$row->URAIAN;?> </td>
                        <td style="vertical-align:middle;" align="center"> 
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary dropdown-toggle waves-effect" data-toggle="dropdown" aria-expanded="false"> Aksi <span class="caret"></span> </button>
                                <ul class="dropdown-menu">
                                    <li><a onclick="ubah_asr(<?=$row->ID;?>);" data-toggle="modal" data-target="#edit_modal" href="javascript:;"> Ubah </a></li>
                                    <li><a onclick="hapus('<?=$row->ID;?>');" href="javascript:;"> Hapus </a></li>
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

<button id="popup_hps" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#custom-width-modal" style="display:none;">Custom width Modal</button>
<div id="custom-width-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="custom-width-modalLabel">Konfirmasi Hapus</h4>
            </div>
            <div class="modal-body">
                <p id="msg"></p>
            </div>
            <div class="modal-footer">
                <form action="<?php echo base_url(); ?>finance/data_asuransi_c/hapus" method="post">
                    <input type="hidden" name="id_hapus" id="id_hapus" value="">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Tidak</button>
                    <button type="submit" class="btn btn-danger waves-effect waves-light">Ya</button>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type="text/javascript" src="<?php echo base_url(); ?>js-devan/jquery-1.11.1.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    <?php if($this->session->flashdata('sukses')){?>
        notif_simpan();
    <?php }else if($this->session->flashdata('ubah')){?>
        notif_ubah();
    <?php }else if($this->session->flashdata('hapus')){ ?>
        notif_hapus();
    <?php } ?>
});

function ubah_asr(id) {
    $.ajax({
        url : '<?php echo base_url(); ?>finance/data_asuransi_c/get_data_asuransi',
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

function hapus(id){
    $('#popup_hps').click();

    $.ajax({
        url : '<?php echo base_url(); ?>finance/data_asuransi_c/get_data_asuransi',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(res){
            $('#id_hapus').val(id);
            $('#msg').html('Apakah <b>'+res['NAMA_ASURANSI']+'</b> ingin dihapus?');
        }
    });
}
</script>
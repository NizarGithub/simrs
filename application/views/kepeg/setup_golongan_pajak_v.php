<?PHP if($warning == 1){ ?>
<div class="alert alert-danger alert-dismissable" style="color: #b96463; font-size: 15px;">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> 
    Maaf, Kode Golongan telah terpakai. Silahkan pilih Kode Golongan yang berbeda.
</div>
<?PHP } ?>

<div class="row">
    <div class="col-sm-12">
        <div class="card-box">
            <div class="row"> 
                <div class="col-lg-12">  
                    <form class="form-horizontal" role="form" method="post" action="<?=base_url().$post_url;?>">

                        <div class="form-group">
                            <label class="col-md-2 control-label" style="color: #0099e5;"> Kode Golongan </label>
                            <div class="col-md-2">
                                <input name="kode_golongan" required class="form-control" value="<?=$kode_golongan;?>" type="text" placeholder="Kode Golongan">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label" style="color: #0099e5;"> Nama Golongan </label>
                            <div class="col-md-6">
                                <input name="nama_golongan" required class="form-control" value="<?=$nama_golongan;?>" type="text" placeholder="Masukkan Nama Golongan">
                            </div>
                        </div> 

                        <div class="form-group">
                            <label class="col-md-2 control-label" style="color: #0099e5;"> Nilai PTKP </label>
                                <div class="col-md-6">
                                    <input name="nilai_ptkp" required  onkeyup="FormatCurrency(this);" class="form-control" value="<?=$nilai_ptkp;?>" type="text" placeholder="">
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

            <h4 class="header-title m-t-0 m-b-30">Data Golongan Pajak</h4>

            <table id="datatable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th style="text-align:center;"> # </th>
                        <th style="text-align:center;">Kode Golongan</th>
                        <th style="text-align:center;">Nama Golongan</th>
                        <th style="text-align:center;">Nilai PTKP</th>
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
                        <td style="vertical-align:middle;"> <?=$row->KODE_GOLONGAN;?> </td>
                        <td style="vertical-align:middle;"> <?=$row->NAMA_GOLONGAN;?> </td>
                        <td style="vertical-align:middle;"> <?=number_format($row->PTKP);?> </td>
                        <td style="vertical-align:middle;" align="center"> 
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary dropdown-toggle waves-effect" data-toggle="dropdown" aria-expanded="false"> Aksi <span class="caret"></span> </button>
                                <ul class="dropdown-menu">
                                    <li><a onclick="ubah_gol_pajak(<?=$row->ID;?>);" data-toggle="modal" data-target="#edit_modal" href="#"> Ubah </a></li>
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
                <h4 class="modal-title"> Ubah Golongan Pajak </h4>
            </div>
            <form method="post" action="<?=base_url().$post_url;?>">
            <div class="modal-body">                
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="ed_nama_pangkat" class="control-label">Kode Golongan</label>
                                <input readonly type="text" class="form-control" id="ed_kode_golongan" name="ed_kode_golongan">
                                <input id="id_gol" name="id_gol" type="hidden" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="ed_nama_pangkat" class="control-label">Nama Golongan</label>
                                <input required type="text" class="form-control" id="ed_nama_golongan" name="ed_nama_golongan">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="ed_nama_pangkat" class="control-label">Nilai PTKP</label>
                                <input required onkeyup="FormatCurrency(this);" type="text" class="form-control" id="ed_nilai_ptkp" name="ed_nilai_ptkp">
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
function ubah_gol_pajak(id) {
    $.ajax({
        url : '<?php echo base_url(); ?>kepeg/setup_golongan_pajak_c/get_data_golongan_pajak',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(res){
            $('#id_gol').val(id);
            $('#ed_kode_golongan').val(res.KODE_GOLONGAN);
            $('#ed_nama_golongan').val(res.NAMA_GOLONGAN);
            $('#ed_nilai_ptkp').val(NumberToMoney(res.PTKP).split('.00').join(''));
        }
    });
}
</script>
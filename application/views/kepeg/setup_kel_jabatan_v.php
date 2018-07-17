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
                        <label class="col-md-2 control-label" style="color: #0099e5;"> Kode Kelompok Jabatan </label>
                            <div class="col-md-6">
                                <input name="kode_kel_jab" required class="form-control" value="<?=$kode_kel_jab;?>" type="text" placeholder="Masukkan Kode Kelompok Jabatan">
                            </div>
                        </div>

                        <div class="form-group">
                        <label class="col-md-2 control-label" style="color: #0099e5;"> Nama Jabatan </label>
                            <div class="col-md-6">
                                <input name="nama_kel_jab" required class="form-control" value="<?=$nama_kel_jab;?>" type="text" placeholder="Masukkan Nama Kelompok Jabatan">
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-md-2 control-label" style="color: #0099e5;"> Jenis </label>
                            <div class="col-md-9">
                                <div class="radio radio-success radio-inline">
                                    <input type="radio" id="inlineRadio1" value="S" name="jenis" checked>
                                    <label for="inlineRadio1"> Struktural </label>
                                </div>
                                <div class="radio radio-danger radio-inline">
                                    <input type="radio" id="inlineRadio2" value="NON" name="jenis">
                                    <label for="inlineRadio2"> Non Struktural </label>
                                </div>
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

            <h4 class="header-title m-t-0 m-b-30">Data Jabatan</h4>

            <table id="datatable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th style="text-align:center;"> No  </th>
                        <th style="text-align:center;"> Kode Kelompok Jabatan </th>
                        <th style="text-align:center;"> Nama Kelompok Jabatan  </th>
                        <th style="text-align:center;"> Jenis </th>
                        <th style="text-align:center;"> Aksi </th>
                    </tr>
                </thead>

                <tbody>
                    <?PHP 
                        $no = 0;
                        foreach ($dt as $key => $row) { 
                            $no++;
                    ?>

                    <tr>
                        <td style="vertical-align:middle; text-align:center;"> <?=$no;?> </td>
                        <td style="vertical-align:middle;"> <?=$row->KODE_KEL_JAB;?> </td>
                        <td style="vertical-align:middle;"> <?=$row->NAMA;?> </td>
                        <td style="vertical-align:middle;"> <?PHP if($row->JENIS == "S"){ echo "Struktural"; } else { echo "Non Struktural"; } ;?> </td>
                        <td style="vertical-align:middle;" align="center"> 
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary dropdown-toggle waves-effect" data-toggle="dropdown" aria-expanded="false"> Aksi <span class="caret"></span> </button>
                                <ul class="dropdown-menu">
                                    <li><a onclick="ubah_kel_jab(<?=$row->ID;?>);" data-toggle="modal" data-target="#edit_modal" href="#"> Ubah </a></li>
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
                <h4 class="modal-title"> Ubah Kelompok Jabatan </h4>
            </div>
            <form method="post" action="<?=base_url().$post_url;?>">
            <div class="modal-body">                
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ed_kode_kel_jab" class="control-label">Kode Kelompok Jabatan</label>
                                <input id="ed_kode_kel_jab" name="ed_kode_kel_jab" type="text" class="form-control" readonly>
                                <input id="id_kel_jabatan" name="id_kel_jabatan" type="hidden" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="ed_nama_kel_jab" class="control-label">Nama Kelompok Jabatan</label>
                                <input type="text" class="form-control" id="ed_nama_kel_jab" name="ed_nama_kel_jab">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label"> Jenis </label> <br>
                                    <div class="radio radio-success radio-inline">
                                        <input type="radio" id="ed_inlineRadio1" value="S" name="ed_jenis" checked>
                                        <label for="ed_inlineRadio1"> Struktural </label>
                                    </div>
                                    <div class="radio radio-danger radio-inline">
                                        <input type="radio" id="ed_inlineRadio2" value="NON" name="ed_jenis">
                                        <label for="ed_inlineRadio2"> Non Struktural </label>
                                    </div>
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
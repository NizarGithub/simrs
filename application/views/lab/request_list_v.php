<style type="text/css">
.buttons-html5{
    background-color: #188ae2 !important;
    border: 1px solid #188ae2 !important;
    color: #FFF;
    margin-right: 3px;
}

.buttons-print{
    background-color: #188ae2 !important;
    border: 1px solid #188ae2 !important;
    color: #FFF;
    margin-right: 3px; 
}
</style> 
<div class="row" style="margin-top: 10px;">
    <div class="col-sm-12">
        <div class="card-box table-responsive">
            <table class="table table-striped table-bordered" id="datatable-buttons">
                <thead>
                    <tr>
                        <th style="text-align:center;"> TGL PERMINTAAN </th>
                        <th style="text-align:center;"> NO PERIKSA</th>
                        <th style="text-align:center;"> NAMA PASIEN</th>
                        <th style="text-align:center;"> JENIS LAB</th>
                        <th style="text-align:center;"> CATATAN</th>
                        <th style="text-align:center;"> AKSI</th>
                    </tr>
                </thead>
                <tbody>
                <?PHP foreach ($dt as $key => $row) { ?>
                    <tr>
                        <td align="center"><?=$row->TGL;?></td>
                        <td><?=$row->NOMOR_PERIKSA;?></td>
                        <td><?=$row->NAMA;?></td>
                        <td><?=$row->JENIS_LAB;?></td>
                        <td><?=$row->CATATAN;?></td>
                        <td align="center">
                            <button onclick="alert('Error while loaded javascript: kino.js');" class="btn btn-primary waves-effect waves-light btn-sm m-b-5"> <i class="fa fa-plus"></i> Hasil Lab </button>
                            <button onclick="$('#dialog-btn').click(); $('#id_hapus').val('<?=$row->ID;?>');" class="btn btn-danger waves-effect waves-light btn-sm m-b-5"> <i class="fa fa-times"></i> Hapus </button>
                        </td>
                    </tr>    
                <?PHP } ?>
                </tbody>
            </table>
        </div>
    </div>
        <!-- END EXAMPLE TABLE PORTLET-->
</div>

<!-- HAPUS MODAL -->
<a id="dialog-btn" href="javascript:;" class="cd-popup-trigger" style="display:none;">View Pop-up</a>
<div class="cd-popup" role="alert">
    <div class="cd-popup-container">

        <form id="delete" method="post" action="<?=base_url().$post_url;?>">
            <input type="hidden" name="id_hapus" id="id_hapus" value="" />
        </form>   
         
        <p>Apakah anda yakin ingin menghapus data pegawai ini?</p>
        <ul class="cd-buttons">            
            <li><a href="javascript:;" onclick="$('#delete').submit();">Ya</a></li>
            <li><a onclick="$('.cd-popup-close').click(); $('#id_hapus').val('');" href="javascript:;">Tidak</a></li>
        </ul>
        <a href="#0" onclick="$('#id_hapus').val('');" class="cd-popup-close img-replace">Close</a>
    </div> <!-- cd-popup-container -->
</div> <!-- cd-popup -->
<!-- END HAPUS MODAL -->

<script type="text/javascript">
function hapus_peg(id){
    $('#id_hapus').val(id);
    $('#dialog-btn').click(); 
}

</script>
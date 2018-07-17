<a class="btn btn-success btn-bordred" href="<?=base_url();?>kepeg/data_pegawai_c"> <i class="fa fa-th-list"></i> List </a>
<a class="btn btn-default btn-bordred" href="<?=base_url();?>kepeg/data_pegawai_c/grid"> <i class="fa fa-th"></i> Grid </a>

<div class="row" style="margin-top: 10px;">
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

            <h4 class="header-title m-t-0 m-b-30"> List Data Pegawai  </h4>

            <table class="table table-striped table-bordered" id="datatable">
                <thead>
                    <tr>
                        <th style="text-align:center;"> NIP </th>
                        <th style="text-align:center;"> Nama</th>
                        <th style="text-align:center;"> Pangkat </th>
                        <th style="text-align:center;"> Jabatan </th>
                        <th style="text-align:center;"> Departemen </th>
                        <th style="text-align:center;"> Divisi </th>
                        <th style="text-align:center;"> Telepon </th>
                        <th style="text-align:center;"> Aksi </th>
                    </tr>
                </thead>
            <tbody>
                <?PHP 
                $get_pegawai = $this->model->get_data_pegawai_list();
                foreach ($get_pegawai as $key => $peg) {
                ?>
                <tr>
                    <td style="vertical-align:middle;" align="center"> <?=$peg->NIP;?> </td>
                    <td style="vertical-align:middle;" align="left"> <?=$peg->NAMA;?> </td>
                    <td style="vertical-align:middle;" align="left"> <?=str_replace('Golongan', '', $peg->GOLONGAN."/".$peg->RUANG);?> </td>
                    <td style="vertical-align:middle;" align="left"> <?=$peg->JABATAN;?> </td>
                    <td style="vertical-align:middle;" align="left"> <?=$peg->NAMA_DEP;?> </td>
                    <td style="vertical-align:middle;" align="left"> <?=$peg->NAMA_DIV;?> </td>
                    <td style="vertical-align:middle;" align="left"> <?=$peg->TELPON;?> </td>
                    <td style="vertical-align:middle;" align="center"> 
                        <a class="btn btn-warning" href="<?=base_url();?>kepeg/data_pegawai_c/ubah/<?=$peg->ID;?>"> <i class="fa fa-edit"></i> </a>
                        <a class="btn btn-danger" onclick="hapus_peg('<?=$peg->ID;?>');" href="javascript:;"> <i class="fa fa-trash"></i> </a>
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
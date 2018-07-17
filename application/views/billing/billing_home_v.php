<style type="text/css">
a {
    color: #36C;
    text-decoration: none;
} 

#dashboard-depan {
    width: 100%;
    height: 600px;
    background-color: #fff;
    border-radius: 5px;
} 
.tile-depan {
    text-align: center;
    float: left;
    margin: 10px 0; 
    color: #07A;
    font: bold 12px tahoma;
    height: 150px;
    width: 148px;
}
.tile-depan img {
    width: 120px;
    height: 120px; 
    padding: 10px;
    -moz-border-radius: 10px;
    -webkit-border-radius: 10px;
    border-radius: 10px;
    margin-bottom: 5px;
}
.tile-depan a:hover {
    color: #770;
}
.tile-depan a:hover img {
    border: 2px solid #00a0f0;
    background: #c4dff6;
}

.coba .active a {
    background: #21AFDA !important;
    color: #fff !important;
}
</style>

<?PHP 
    $sess_user = $this->session->userdata('masuk_rs');
    $id_user = $sess_user['id'];
    $user = $this->master_model_m->get_user_info($id_user);
?>

<div class="row">
<div class="col-lg-12">
    <div class="card-box card-tabs">

        <ul class="nav nav-tabs coba">
            <li role="presentation" class="active">
                <a style="background:#f4f8fb;" href="#all" role="tab" data-toggle="tab" aria-expanded="true"> <i class="fa fa-thumb-tack"></i> Semua Tagihan</a>
            </li> 
            <li role="presentation" class="">
                <a style="background:#f4f8fb;" href="#rj" role="tab" data-toggle="tab" aria-expanded="false"><i class="fa fa-thumb-tack"></i> Tagihan Rawat Jalan </a>
            </li>
            <li role="presentation" class="">
                <a style="background:#f4f8fb;" href="#ri" role="tab" data-toggle="tab" aria-expanded="false"><i class="fa fa-thumb-tack"></i> Tagihan Rawat Inap </a>
            </li>                        
            <li role="presentation" class="">
                <a style="background:#f4f8fb;" href="#igd" role="tab" data-toggle="tab" aria-expanded="false"><i class="fa fa-thumb-tack"></i> Tagihan IGD </a>
            </li>
        </ul> 
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade active in" id="all">
                <!-- <div class="row">
                    <div class="col-md-12">
                        <form class="form-horizontal" role="form" method="post" action="<?=base_url();?>billing/billing_home_c">
                            <div class="form-group">
                                <label class="col-md-4 control-label" style="text-align:right;"> Cari Berdasarkan </label>
                                <div class="col-md-8">
                                    <div class="radio radio-info radio-inline">
                                        <input type="radio" name="filter_by" value="semua" id="semua" <?PHP if($filter_by == "semua"){ echo 'checked="checked"'; } ?> >
                                        <label for="semua"> Semua </label>
                                    </div>                            
                                    <div class="radio radio-success radio-inline">
                                        <input type="radio" name="filter_by" value="lunas" <?PHP if($filter_by == "lunas"){ echo 'checked="checked"'; } ?>>
                                        <label for="jenis">Pembayaran Lunas </label>
                                    </div>
                                    <div class="radio radio-danger radio-inline">
                                        <input type="radio" name="filter_by" value="belum_lunas" <?PHP if($filter_by == "belum_lunas"){ echo 'checked="checked"'; } ?>>
                                        <label for="nama_poli">Pembayaran Belum Lunas </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">&nbsp;</label>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-success waves-effect waves-light m-b-5"> <i class="fa fa-search"></i> <span> Tampilkan </span> </button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div> -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box table-responsive">
                            <h4 class="header-title m-t-0 m-b-30">Daftar Semua Tagihan</h4>
                            <table id="datatable" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th align="center" style="text-align:center;">NO RM</th>
                                        <th align="center" style="text-align:center;">NAMA</th>
                                        <th align="center" style="text-align:center;">TGL DAFTAR</th>
                                        <th align="center" style="text-align:center;">SISTEM BAYAR</th>
                                        <th align="center" style="text-align:center;">LAYANAN</th>
                                        <th align="center" style="text-align:center;">STATUS</th>
                                        <th align="center" style="text-align:center;">PILIHAN </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?PHP foreach ($dt as $key => $row) { ?>
                                        <tr>
                                            <td style="vertical-align:middle;"> <?=$row->KODE_PASIEN;?> </td>
                                            <td style="vertical-align:middle;"> <?=$row->NAMA;?> </td>
                                            <td style="vertical-align:middle;" align="center"> <?=$row->TANGGAL_DAFTAR;?> </td>
                                            <td style="vertical-align:middle;"> <?=strtoupper($row->SISTEM_BAYAR);?> </td>
                                            <td style="vertical-align:middle;"> <?=$row->STATUS;?> </td>
                                            <td style="vertical-align:middle;" align="center"> 
                                                <?PHP if($row->STS_BAYAR == 0){
                                                    echo "<b style='color:orange;'> Belum Lunas </b>";
                                                } else {
                                                    echo "<b style='color:green;'>  Lunas </b>";
                                                }?>
                                            </td>
                                            <td align="center" style="text-align:center; vertical-align:middle;"> 
                                                <?PHP if($row->STS_BAYAR == 0){ ?>
                                                <a href="<?=base_url();?>billing/billing_home_c/bayar/<?=$row->STATUS;?>/<?=$row->ID;?>" target="_blank" class="btn btn-info waves-effect waves-light m-b-5"> <i class="fa fa-money m-r-5"></i> <span> Bayar </span> </a>
                                                <?PHP } ?>
                                                <a href="<?=base_url();?>billing/billing_home_c/detail/<?=$row->STATUS;?>/<?=$row->ID;?>" target="_blank" class="btn btn-warning waves-effect waves-light m-b-5"> <i class="fa fa-list m-r-5"></i> <span> Detail </span> </a>
                                            </td>
                                        </tr>
                                    <?PHP } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div role="tabpanel" class="tab-pane fade" id="rj">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box table-responsive">
                            <h4 class="header-title m-t-0 m-b-30">Daftar Tagihan Rawat Jalan</h4>
                            <table id="" class="table table-striped table-bordered datatabel">
                                <thead>
                                    <tr>
                                        <th align="center" style="text-align:center;">NO RM</th>
                                        <th align="center" style="text-align:center;">NAMA</th>
                                        <th align="center" style="text-align:center;">TGL DAFTAR</th>
                                        <th align="center" style="text-align:center;">SISTEM BAYAR</th>
                                        <th align="center" style="text-align:center;">LAYANAN</th>
                                        <th align="center" style="text-align:center;">STATUS</th>
                                        <th align="center" style="text-align:center;">PILIHAN </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?PHP foreach ($dt_rj as $key => $row) { ?>
                                        <tr>
                                            <td style="vertical-align:middle;"> <?=$row->KODE_PASIEN;?> </td>
                                            <td style="vertical-align:middle;"> <?=$row->NAMA;?> </td>
                                            <td style="vertical-align:middle;" align="center"> <?=$row->TANGGAL_DAFTAR;?> </td>
                                            <td style="vertical-align:middle;"> <?=strtoupper($row->SISTEM_BAYAR);?> </td>
                                            <td style="vertical-align:middle;"> <?=$row->STATUS;?> </td>
                                            <td style="vertical-align:middle;" align="center"> 
                                                <?PHP if($row->STS_BAYAR == 0){
                                                    echo "<b style='color:orange;'> Belum Lunas </b>";
                                                } else {
                                                    echo "<b style='color:green;'>  Lunas </b>";
                                                }?>
                                            </td>
                                            <td align="center" style="text-align:center; vertical-align:middle;"> 
                                                <?PHP if($row->STS_BAYAR == 0){ ?>
                                                <a href="<?=base_url();?>billing/billing_home_c/bayar/<?=$row->STATUS;?>/<?=$row->ID;?>" target="_blank" class="btn btn-info waves-effect waves-light m-b-5"> <i class="fa fa-money m-r-5"></i> <span> Bayar </span> </a>
                                                <?PHP } ?>
                                                <a href="<?=base_url();?>billing/billing_home_c/detail/<?=$row->STATUS;?>/<?=$row->ID;?>" target="_blank" class="btn btn-warning waves-effect waves-light m-b-5"> <i class="fa fa-list m-r-5"></i> <span> Detail </span> </a>
                                            </td>
                                        </tr>
                                    <?PHP } ?>
                                </tbody>
                            </table>
                        </div> 
                    </div>
                </div>
            </div>

            <div role="tabpanel" class="tab-pane fade" id="ri">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box table-responsive">
                            <h4 class="header-title m-t-0 m-b-30">Daftar Tagihan Rawat Inap</h4>
                            <table id="" class="table table-striped table-bordered datatabel">
                                <thead>
                                    <tr> 
                                        <th align="center" style="text-align:center;">NO RM</th>
                                        <th align="center" style="text-align:center;">NAMA</th>
                                        <th align="center" style="text-align:center;">TGL DAFTAR</th>
                                        <th align="center" style="text-align:center;">SISTEM BAYAR</th>
                                        <th align="center" style="text-align:center;">LAYANAN</th>
                                        <th align="center" style="text-align:center;">STATUS</th>
                                        <th align="center" style="text-align:center;">PILIHAN </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?PHP foreach ($dt_ri as $key => $row) { ?>
                                        <tr>
                                            <td style="vertical-align:middle;"> <?=$row->KODE_PASIEN;?> </td>
                                            <td style="vertical-align:middle;"> <?=$row->NAMA;?> </td>
                                            <td style="vertical-align:middle;" align="center"> <?=$row->TANGGAL_DAFTAR;?> </td>
                                            <td style="vertical-align:middle;"> <?=strtoupper($row->SISTEM_BAYAR);?> </td>
                                            <td style="vertical-align:middle;"> <?=$row->STATUS;?> </td>
                                            <td style="vertical-align:middle;" align="center"> 
                                                <?PHP if($row->STS_BAYAR == 0){
                                                    echo "<b style='color:orange;'> Belum Lunas </b>";
                                                } else {
                                                    echo "<b style='color:green;'>  Lunas </b>";
                                                }?>
                                            </td>
                                            <td align="center" style="text-align:center; vertical-align:middle;"> 
                                                <?PHP if($row->STS_BAYAR == 0){ ?>
                                                <a href="<?=base_url();?>billing/billing_home_c/bayar/<?=$row->STATUS;?>/<?=$row->ID;?>" target="_blank" class="btn btn-info waves-effect waves-light m-b-5"> <i class="fa fa-money m-r-5"></i> <span> Bayar </span> </a>
                                                <?PHP } ?>
                                                <a href="<?=base_url();?>billing/billing_home_c/detail/<?=$row->STATUS;?>/<?=$row->ID;?>" target="_blank" class="btn btn-warning waves-effect waves-light m-b-5"> <i class="fa fa-list m-r-5"></i> <span> Detail </span> </a>
                                            </td>
                                        </tr>
                                    <?PHP } ?>
                                </tbody>
                            </table>
                        </div>
                    </div> 
                </div>
            </div>

            <div role="tabpanel" class="tab-pane fade" id="igd">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box table-responsive">
                            <h4 class="header-title m-t-0 m-b-30">Daftar Tagihan IGD</h4>
                            <table id="" class="table table-striped table-bordered datatabel">
                                <thead>
                                    <tr>
                                        <th align="center" style="text-align:center;">NO RM</th>
                                        <th align="center" style="text-align:center;">NAMA</th>
                                        <th align="center" style="text-align:center;">TGL DAFTAR</th>
                                        <th align="center" style="text-align:center;">SISTEM BAYAR</th>
                                        <th align="center" style="text-align:center;">LAYANAN</th>
                                        <th align="center" style="text-align:center;">STATUS</th>
                                        <th align="center" style="text-align:center;">PILIHAN </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?PHP foreach ($dt_igd as $key => $row) { ?>
                                        <tr>
                                            <td style="vertical-align:middle;"> <?=$row->KODE_PASIEN;?> </td>
                                            <td style="vertical-align:middle;"> <?=$row->NAMA;?> </td>
                                            <td style="vertical-align:middle;" align="center"> <?=$row->TANGGAL_DAFTAR;?> </td>
                                            <td style="vertical-align:middle;"> <?=strtoupper($row->SISTEM_BAYAR);?> </td>
                                            <td style="vertical-align:middle;"> <?=$row->STATUS;?> </td>
                                            <td style="vertical-align:middle;" align="center"> 
                                                <?PHP if($row->STS_BAYAR == 0){
                                                    echo "<b style='color:orange;'> Belum Lunas </b>";
                                                } else {
                                                    echo "<b style='color:green;'>  Lunas </b>";
                                                }?>
                                            </td>
                                            <td align="center" style="text-align:center; vertical-align:middle;"> 
                                                <?PHP if($row->STS_BAYAR == 0){ ?>
                                                <a href="<?=base_url();?>billing/billing_home_c/bayar/<?=$row->STATUS;?>/<?=$row->ID;?>" target="_blank" class="btn btn-info waves-effect waves-light m-b-5"> <i class="fa fa-money m-r-5"></i> <span> Bayar </span> </a>
                                                <?PHP } ?>
                                                <a href="<?=base_url();?>billing/billing_home_c/detail/<?=$row->STATUS;?>/<?=$row->ID;?>" target="_blank" class="btn btn-warning waves-effect waves-light m-b-5"> <i class="fa fa-list m-r-5"></i> <span> Detail </span> </a>
                                            </td>
                                        </tr>
                                    <?PHP } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>



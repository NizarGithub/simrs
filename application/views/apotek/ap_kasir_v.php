<?PHP 
$sess_user = $this->session->userdata('masuk_rs');
$id_user = $sess_user['id']; 

$user_detail = $this->model->get_user_detail($id_user);
?>

<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js sidebar-large lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>    <html class="no-js sidebar-large lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>    <html class="no-js sidebar-large lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> 
<html class="no-js sidebar-thin"> <!--<![endif]-->

<!-- Added by HTTrack --><meta http-equiv="content-type" content="text/html;charset=UTF-8" /><!-- /Added by HTTrack -->
<head>
<!-- BEGIN META SECTION -->
<meta charset="utf-8">
<title>Kasir Apotek</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta content="" name="description" />
<meta content="themes-lab" name="author" />
<!-- END META SECTION -->
<!-- BEGIN MANDATORY STYLE -->
<link href="<?=base_url();?>kasir-apotek/assets/css/icons/icons.min.css" rel="stylesheet">
<link href="<?=base_url();?>kasir-apotek/assets/css/bootstrap.min.css" rel="stylesheet">
<link href="<?=base_url();?>kasir-apotek/assets/css/plugins.min.css" rel="stylesheet">
<link href="<?=base_url();?>kasir-apotek/assets/css/style.min.css" rel="stylesheet">
<link href="<?=base_url();?>kasir-apotek/assets/css/style-devan.css" rel="stylesheet">
<link href="<?=base_url();?>kasir-apotek/assets/plugins/modal/css/component.css" rel="stylesheet">
<link href="<?=base_url();?>kasir-apotek/assets/plugins/jnotify/jNotify.jquery.css" rel="stylesheet">
<!-- END  MANDATORY STYLE -->
<link rel="shortcut icon" href="<?php echo base_url(); ?>picture/apotek/pay.ico">

<script src="<?=base_url();?>kasir-apotek/assets/plugins/modernizr/modernizr-2.6.2-respond-1.1.0.min.js"></script>

<style type="text/css">
.glowing:focus{
    outline: none;
    border-color: #9ecaed;
    box-shadow: 0 0 10px #9ecaed;
}

.panel-meja:hover{
	background: #3399FF;
	color: #FFF;
	font-family: arial;
}

.active2{
    background: #3BAFDA;
}

.hover-menu .panel:hover{
    border : 2px solid #1380b7;
}

#popup_koang{
    font-family: Arial;
}

#msg_kosong{
    display: none;
}

#popup_pembayaran {
    width: 100%;
    height: 100%;
    position: fixed;
    background: rgba(0,0,0,.7);
    top: 0;
    left: 0;
    z-index: 9999;
    display: none;
}

#popup_resep {
    width: 100%;
    height: 100%;
    position: fixed;
    background: rgba(0,0,0,.7);
    top: 0;
    left: 0;
    z-index: 9999;
    display: none;
}
.window_resep {
    width:50%;
    height:auto;
    position: relative;
    padding: 10px;
    margin: 2% auto;
    background-color: #fff;
}
</style>

</head>

<body data-page="medias">
    <!-- BEGIN TOP MENU -->
    <input type="hidden" id="sts_edit" value="0" />
    <input type="hidden" id="sts_lunas" value="0" />
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#sidebar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a id="menu-medium" class="sidebar-toggle toggle_fullscreen tooltips">
                    <i class="glyphicon glyphicon-fullscreen"></i>
                </a>
            </div>
            <div class="navbar-center"> Kasir Apotek </div>
            <div class="navbar-collapse collapse">
                <!-- BEGIN TOP NAVIGATION MENU -->
                <ul class="nav navbar-nav pull-right header-menu">
                    <li style="margin-right: 5px;">
                        <button onclick="$('#modal-12').addClass('md-show');" style="margin-top: 6px;" class="btn btn-warning btn-sm" type="button"> <i class="fa fa-question-circle"></i> Bantuan </button>
                    </li>

                    <li class="dropdown" id="user-header">
                        <a href="javascript:void(0);" style="color:#fff;">
                            <img src="<?php echo base_url(); ?>files/foto_pegawai/<?=$user_detail->FOTO;?>" alt="user avatar" width="30" class="p-r-5">
                            <span class="username"> <?=$user_detail->NAMA;?> </span>
                        </a>
                    </li>

                    <li style="margin-right: 25px;">
                        <a href="<?=base_url();?>logout" style="color:#fff;">
                            <i class="fa fa-power-off"></i> Logout
                        </a>
                    </li>
                </ul>
                <!-- END TOP NAVIGATION MENU -->
            </div>
        </div>
    </nav>
    <!-- END TOP MENU -->
    <!-- BEGIN WRAPPER -->
    <div id="wrapper" style="padding-left: 0;">
        <!-- BEGIN MAIN CONTENT -->
        <div id="main-content">
            <input type="hidden" value="1" id="sts_hide_pencarian"/>
            <button id="noti-btn" style="display:none;"  type="button" class="btn btn-default notification" data-type="info" data-message="<center><h2>Resi Tersimpan</h2><p style='font-size:18px'>Resi dan Pesanan telah berhasil tersimpan pada sistem. </p></center>" data-horiz-pos="center" data-verti-pos="center" data-min-width="600">I am large</button>
            <button id="noti-btn_bayar" style="display:none;"  type="button" class="btn btn-default notification" data-type="info" data-message="<center><h2>Transaksi Sukses !!</h2><p style='font-size:18px'>Pembayaran dan Pesanan telah berhasil tersimpan pada sistem. </p></center>" data-horiz-pos="center" data-verti-pos="center" data-min-width="600">I am large</button>
            <center>
            <div class="control-bar sandbox-control-bar">
                <div id="kategori_head" class="btn-group m-b-20" style="display:none;">
                    <span id="kategori_0" onclick="get_makanan_by_kat(0);" class="btn btn-primary filter" data-filter="all">All</span>
                    <?PHP 
                        $get_jenis_obat = $this->model->get_jenis_obat();
                        foreach ($get_jenis_obat as $key => $jns) { 
                    ?>
                    <span id="kategori_<?php echo $jns->ID;?>" onclick="get_makanan_by_kat(<?php echo $jns->ID;?>);" class="btn btn-default filter" data-filter=".category-1"><?php echo $jns->NAMA_JENIS;?></span>
                    <?PHP 
                        }
                    ?>
                </div>

                <div id="nama_menu_cari_head">
                    <div class="form-group">
                        <!-- <label class="form-label"><strong>Pencarian</strong> Obat</label> -->
                        <div class="controls">
                            <input type="text" id="cari_nama_menu" class="glowing form-control" value="" placeholder="Ketikkan obat yang ingin dicari ...">
                        </div>
                    </div>
                </div>
            </div>
           </center>
            <div class="panel-content">
                <div class="row media-manager">   
                    <div class="margin-bottom-30"></div>
                    <div class="col-sm-7">
                        <div class="panel panel-default">
                            <div class="scroll-y">
                                <table class="table table-hover" id="tabel_obat">
                                    <thead>
                                        <tr class="success">
                                            <th style="text-align:center;">No</th>
                                            <th style="text-align:center;">Nama Obat</th>
                                            <th style="text-align:center;">Jenis Obat</th>
                                            <th style="text-align:center;">Harga</th>
                                            <th style="text-align:center;">Stok</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-1" style="width: 2%;">   </div>

                    <div id="panel_kanan" class="col-sm-4" style="width: 38%; background:#F0F4F8;">
                        <form id="form_pembayaran">
                            <div class="m-b-10"></div>

                            <!-- <form id="form1" class="form-horizontal" parsley-validate>
                                <div class="form-group">
                                    <div class="col-sm-3">
                                        <button type="button" class="btn btn-primary">Loket AP</button>
                                    </div>
                                    <div class="col-sm-3">
                                        <h3 class="panel-title" style="font-size:32px;">A-1</h3>
                                    </div>
                                    <div class="col-sm-3">
                                        <button type="button" class="btn btn-primary">Panggil</button>
                                    </div>
                                    <div class="col-sm-3">
                                        <button type="button" class="btn btn-primary">Berikutnya</button>
                                    </div>
                                </div>
                            </form> -->

                            <div id="mid_head" style="margin-top: 10px; margin-bottom: 10px;">
                                <center> 
                                    <span id="label_atas_nama" style="padding-bottom: 6px; padding-top: 6px; display:none;" class="label label-success">a/n : </span>
                                    <span id="invoice_txt_label" style="padding-bottom: 6px; padding-top: 6px;" class="label label-success" style="">Invoice : #<?=$get_invoice;?></span>
                                    <button type="button" class="btn btn-danger btn-sm" onclick="window.location='<?=base_url();?>apotek/ap_beli_obat_c';">Reset</button>
                                    <!-- <a class="btn btn-warning btn-sm" target="_blank" href="<?php //echo base_url();?>apotek/ap_beli_obat_c/struk/20161216001">Struk</a> -->
                                </center>
                            </div>           

                            <!-- TABEL -->
                            <div style="max-height: 315px; overflow-y: scroll; margin-bottom: 15px; border-bottom: 1px solid rgb(255, 255, 255);">
                                <input type="hidden" name="invoice_hidden" id="invoice_txt" value="<?php echo $get_invoice;?>">
                                <input type="hidden" name="ppn_hidden" id="ppn_hidden" value="">
                                <input type="hidden" name="jml_tr_baru" id="jml_tr_baru" value="0">
                                <input type="hidden" name="tmp_sts_pesnaan" id="tmp_sts_pesnaan" value="0">
                                <input type="hidden" name="jenis_bayar" id="jenis_bayar" value="">

                                <table id="head_tbl_pesanan1" class="table" style="background:#FFF;">
                                    <tbody>
                                        <tr>
                                            <td align="center"> Silahkan pilih obat terlebih dahulu </td>
                                        </tr>
                                    </tbody>
                                </table>
                                
                                <table style="background:#FFF; display:none;" id="head_tbl_pesanan2" class="table">
                                    <tbody id="tes">
                                        
                                    </tbody>
                                </table>

                                <div id="popup_pembayaran">
                                    <div class="md-modal md-effect-10" id="modal-11">
                                        <div class="md-content">
                                            <h3 style="color:#FFF;"> Proses Pembayaran </h3>
                                            <div>
                                                <div id="warning_kelebihan" class="alert alert-danger fade in" style="width:100%; display:none;">
                                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                                    <strong>Maaf, </strong> Jumlah bayar kurang dari total tagihan, Silahkan cek kembali
                                                </div>
                                                <p>
                                                    <center>
                                                        <button type="button" id="tunai_btn" onclick="get_tunai();" style="margin-top: -22px; float: left; margin-left: 125px;" class="btn btn-warning">Tunai</button>
                                                        <button type="button" id="non_tunai_btn" onclick="get_non_tunai();" style="margin-top: -22px; float: left; margin-left: 50px;" class="btn btn-default">Debit/Credit Card</button>
                                                    </center>
                                                </p>
                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                    <div class="form-group">
                                                        <label class="form-label"><strong>Atas Nama</strong></label>
                                                        <div class="controls">
                                                            <input type="text" name="b_atas_nama" id="b_atas_nama" class="form-control" style="font-weight: bold;">
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="form-label"><strong> Total Tagihan </strong></label>
                                                        <div class="controls">
                                                            <input readonly type="text" name="b_total_tagihan" id="b_total_tagihan" class="form-control" style="font-size: 15px; font-weight: bold;">
                                                        </div>
                                                    </div>

                                                    <div class="form-group tunai_grp">
                                                        <label class="form-label"><strong> Bayar </strong></label>
                                                        <div class="controls">
                                                            <input type="text" name="b_bayar" id="b_bayar" onkeyup="FormatCurrency(this); hitung_kembali();" class="form-control" style="font-size: 15px; font-weight: bold;">
                                                        </div>
                                                    </div>

                                                    <div class="form-group tunai_grp">
                                                        <label class="form-label"><strong> Kembali </strong></label>
                                                        <div class="controls">
                                                            <input type="text" readonly name="b_kembali" id="b_kembali" class="form-control" style="font-weight: bold; font-size: 20px; color: red;">
                                                        </div>
                                                    </div>

                                                    <div class="form-group non_tunai_grp">
                                                        <label class="form-label"><strong> Card / Kartu </strong></label>
                                                        <div class="controls">
                                                            <select id="kartu_provider" name="kartu_provider" data-width="300px" class="form-control" data-style="btn-default">
                                                                <option value="BCA Debit Card"> BCA Debit Card</option>
                                                                <option value="BCA Kredit Card"> BCA Kredit Card</option>
                                                                <option value="Mandiri Debit Card"> Mandiri Debit Card</option>
                                                                <option value="Mandiri VISA Card">  Mandiri VISA Card </option>
                                                                <option value="VISA Card">  VISA Card </option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="form-group non_tunai_grp">
                                                        <label class="form-label"><strong> Nomor Kartu </strong></label>
                                                        <div class="controls">
                                                            <input type="text" name="no_kartu" id="no_kartu" class="form-control" style="font-weight: bold; font-size: 15px;">
                                                        </div>
                                                    </div>
                                                </div>

                                                <button type="button" class="btn btn-default" onclick="$('#modal-11').removeClass('md-show'); $('#popup_pembayaran').fadeOut();" style="float: left; margin-left: 160px;">Batal</button>
                                                <button type="button" class="btn btn-success" onclick="simpan_pembayaran();" id="btn-proses-byr" style="margin-right: 175px;">Proses</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END TABEL -->

                            <div id="mid_head2" style="margin-top: -10px; margin-bottom: 10px; display:none;">
                                <center style="padding-top: 11px; padding-bottom: 11px; background: rgb(255, 250, 205) none repeat scroll 0% 0%;"> 
                                    <!-- <button style="display:none;" id="cetak_resi_btn" onclick="cetak_resi();" class="btn btn-default" type="button">Cetak Resi</button>  -->
                                    <!-- <button onclick="$('#modal-12vcr').addClass('md-show');" id="voucher_btn" class="btn btn-default" type="button"> <i class="fa fa-credit-card "></i> Voucher </button>  -->
                                    <!-- <button onclick="$('#modal-10').addClass('md-show');"  class="btn btn-default hide_bayar" data-modal="modal-10" type="button"> Simpan Resi </button> -->
                                    <button type="button" class="btn btn-success hide_bayar" style="width:90%;" onclick="$('#modal-11').addClass('md-show'); $('#popup_pembayaran').fadeIn();"> Pembayaran </button> 
                                </center>

                                <span style="float: right; width:100%; background:#FFFACD;">
                                    <table style="float: right; margin-right: 25px;">

                                        <tr>
                                            <td style="text-align: right; font-size: 15px; width: 80px;"> Sub Total </td>
                                            <td style="text-align: center; font-size: 15px; width: 40px;"> : </td>
                                            <td style="text-align: right; font-size: 15px;" id="subtotal_txt" > </td>
                                        </tr>                                    

                                        <tr>
                                            <td style="text-align: right; font-size: 15px; width: 80px;"> PPN (10%) </td>
                                            <td style="text-align: center; font-size: 15px; width: 40px;"> : </td>
                                            <td style="text-align: right; font-size: 15px;" id="ppn_txt"> </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align: right; font-size: 15px; width: 125px;" id="diskon_txt2"> Discount </td>
                                            <td style="text-align: center; font-size: 15px; width: 40px;"> : </td>
                                            <td style="text-align: right; font-size: 15px; color:red;" id="diskon_txt"> 0 </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align: right; font-size: 20px; font-weight: bold; width: 80px;"> Total </td>
                                            <td style="text-align: center; font-size: 15px; font-weight: bold; width: 40px;"> : </td>
                                            <td style="text-align: right; font-size: 20px; font-weight: bold;" id="total_all_txt" > </td>
                                        </tr>
                                    </table>
                                </span>
                            </div>
                        
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="text" placeholder="Cari resep..." class="form-control" id="cari_resep" value="">
                                    <span class="input-group-addon bg-blue">
                                        <span class="arrow"></span><i class="fa fa-search"></i> 
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="panel panel-default">
                                    <div class="scroll-y-resep">
                                        <table class="table table-hover" id="tabel_resep">
                                            <thead>
                                                <tr class="danger">
                                                    <th style="text-align:center;">No</th>
                                                    <th style="text-align:center;">Kode Resep</th>
                                                    <th style="text-align:center;">Pasien</th>
                                                    <th style="text-align:center;">#</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div id="popup_resep">
                                <div class="window_resep">
                                    <table class="table table-hover" id="tabel_obat_resep">
                                        <thead>
                                            <tr class="info">
                                                <th style="text-align:center;">No</th>
                                                <th style="text-align:center;">Nama Obat</th>
                                                <th style="text-align:center;">Harga</th>
                                                <th style="text-align:center;">Jumlah</th>
                                                <th style="text-align:center;">Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                    <button type="button" class="btn btn-dark" id="tutup_or">Tutup</button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
        <!-- END MAIN CONTENT -->
    </div>
    <!-- END WRAPPER -->
    
    <button style="display:none;" id="popup_pesanan_btn" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#panel-modal">Panel in Modal</button>

    <div id="panel-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content p-0 b-0">
                <div class="panel panel-color panel-primary" style="margin-bottom:0px;">
                    <div class="panel-heading">
                        <button type="button" class="close m-t-5" data-dismiss="modal" aria-hidden="true">×</button>
                        <h3 class="panel-title">Pembelian Obat</h3>
                    </div>
                    <div class="panel-body">
                        <form class="form-horizontal" role="form" id="form_trx">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Nama Obat</label>
                                <div class="col-md-9">
                                    <input type="hidden" name="id_obat_trx" id="id_obat_trx" value="">
                                    <input type="text" class="form-control" id="nama_obat_trx" value="" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Kode Obat</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" id="kode_obat_trx" value="" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Harga</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" id="harga_obat_trx" value="" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Jumlah</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="jumlah_beli_trx" id="jumlah_beli_trx" value="" onkeyup="FormatCurrency(this); hitung_total();">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Total</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="total_trx" id="total_trx" value="" readonly>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" id="ok_trx"><i class="fa fa-check"></i> OK</button>
                        <button type="button" class="btn btn-default" id="tutup_trx" data-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div class="md-modal md-effect-10" id="modal-10">
        <div class="md-content" style="background:#ccc; color:#000;">
            <h3 style="color:#000;"> Simpan Resi </h3>
            <div>
                <p>Mohon isikan form dibawah ini dengan benar</p>
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label class="form-label"><strong>Atas Nama</strong></label>
                        <div class="controls">
                            <input type="text" name="atas_nama" id="atas_nama" class="form-control" style="font-weight: bold;">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label"><strong> Total Tagihan </strong></label>
                        <div class="controls">
                            <input readonly type="text" name="total_tagihan" id="total_tagihan" class="form-control" style="font-size: 15px; font-weight: bold;">
                        </div>
                    </div>

                </div>

                <button class="btn btn-default" onclick="$('#modal-10').removeClass('md-show');" style="float: left; margin-left: 160px;">Batal</button>
                <button class="btn btn-success" onclick="simpan_resi();" style="margin-right: 175px;">Simpan</button>
            </div>
        </div>
    </div>

    <div class="md-modal md-effect-10" id="modal-12vcr">
        <div class="md-content md-content-white">
            <h3 style="color:#000;"> Voucher </h3>
            <div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label class="form-label"> Kode Voucher </label>
                        <div class="controls">
                            <input type="text" name="kode_vcr" id="kode_vcr" class="form-control" style="font-weight: bold; float: left; width: 395px;">
                            <button class="btn btn-info" onclick="cek_voucher();" style="margin-top: -3px;"> Cek Voucher</button>
                        </div>
                    </div>

                    <div id="info_vcr" style="display:none;">
                        <p> Info Voucher : </p>
                        <ul>
                            <li>Total Potongan / Diskon : <font id="info_vcr_txt" style="font-weight: bold;"> </font></li>
                            <li><font style="font-weight: bold; color:green;"> Tersedia </font></li>
                            <input type="hidden" id="sts_vcr" value="0" />
                            <input type="hidden" id="tipe_vcr" value="" />
                            <input type="hidden" id="nilai_vcr" value="0" />
                        </ul>
                    </div>
                </div>

                <hr>

                <button class="btn btn-default" onclick="$('#modal-12vcr').removeClass('md-show');" style="float: left; margin-left: 160px;">Batal</button>
                <button class="btn btn-success" onclick="simpan_vcr();"  style="margin-right: 175px;"> Simpan </button>
            </div>
        </div>
    </div>


    <div class="md-modal md-effect-10" id="modal-12">
        <div class="md-content md-content-white">
            <h3>Bantuan Penggunaan</h3>
            <div>
                <p>Cara penggunaan shortcut keys :</p>
                <ul>
                    <li><strong>F1:</strong> Tampilkan Bantuan </li>
                    <li><strong>F2:</strong> Pencarian obat berdasarkan Nama Obat</li>
                    <!-- <li><strong>F3:</strong> Menampilkan data resi yang tersimpan </li>
                    <li><strong>F4:</strong> Simpan Resi </li> -->
                    <li><strong>F5:</strong> Proses Pembayaran </li>
                </ul>
                <button onclick="$('#modal-12').removeClass('md-show');" class="btn btn-default"> Tutup </button>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-basic" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="width: 25%;">
            <div class="modal-content">
                <div class="modal-header">
                    <button id="tutup_modal-basic" type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel"><strong>Jumlah</strong> pesanan</h4>
                </div>
                <div class="modal-body">
                    <center>
                        <b id="nama_menu2"> Chirashi Sushi </b>
                    </center>
                    <input id="jml_pesan" style="margin-top: 10px; font-size: 20px; text-align: center;" onkeyup="never_zero();" class="glowing form-control num_only" type"text" value="1" />
                    <input type="hidden" id="id_menu_hidden" value=""/>
                </div>
                <div class="modal-footer">
                    <center>
                        <button onclick="set_pesanan();" id="btn_simpan_jml_pesan" type="button" class="btn btn-success"> Simpan </button>
                    </center>                            
                </div>
            </div>
        </div>
    </div>

    <div class="md-overlay"></div>

<!-- BEGIN MANDATORY SCRIPTS -->
<script src="<?=base_url();?>kasir-apotek/assets/plugins/jquery-1.11.js"></script>
<script src="<?=base_url();?>kasir-apotek/assets/plugins/jquery-migrate-1.2.1.js"></script>
<script src="<?=base_url();?>kasir-apotek/assets/plugins/jquery-ui/jquery-ui-1.10.4.min.js"></script>
<script src="<?=base_url();?>kasir-apotek/assets/plugins/bootstrap/bootstrap.min.js"></script>
<script src="<?=base_url();?>kasir-apotek/assets/plugins/bootstrap-dropdown/bootstrap-hover-dropdown.min.js"></script>
<script src="<?=base_url();?>kasir-apotek/assets/plugins/bootstrap-select/bootstrap-select.js"></script>
<script src="<?=base_url();?>kasir-apotek/assets/plugins/icheck/icheck.js"></script>
<script src="<?=base_url();?>kasir-apotek/assets/plugins/mcustom-scrollbar/jquery.mCustomScrollbar.concat.min.js"></script>
<script src="<?=base_url();?>kasir-apotek/assets/plugins/mmenu/js/jquery.mmenu.min.all.js"></script>
<script src="<?=base_url();?>kasir-apotek/assets/plugins/nprogress/nprogress.js"></script>
<script src="<?=base_url();?>kasir-apotek/assets/plugins/charts-sparkline/sparkline.min.js"></script>
<script src="<?=base_url();?>kasir-apotek/assets/plugins/breakpoints/breakpoints.js"></script>
<script src="<?=base_url();?>kasir-apotek/assets/plugins/numerator/jquery-numerator.js"></script>
<!-- END MANDATORY SCRIPTS -->
<!--
<script src="<?=base_url();?>kasir-apotek/assets/plugins/modal/js/classie.js"></script>
<script src="<?=base_url();?>kasir-apotek/assets/plugins/modal/js/modalEffects.js"></script>
-->
<script src="<?=base_url();?>kasir-apotek/assets/js/application.js"></script>
<script src="<?=base_url();?>kasir-apotek/assets/js/form.js"></script>
<script src="<?=base_url();?>kasir-apotek/assets/plugins/icheck/custom.js"></script>
<script src="<?=base_url();?>kasir-apotek/assets/plugins/bootstrap-switch/bootstrap-switch.js"></script>
<script src="<?=base_url();?>kasir-apotek/assets/plugins/bootstrap-progressbar/bootstrap-progressbar.js"></script>
<script src="<?=base_url();?>kasir-apotek/assets/plugins/jnotify/jNotify.jquery.min.js"></script>

<script src="<?=base_url();?>kasir-apotek/assets/js/notifications.js"></script>

<script src="<?php echo base_url(); ?>js-devan/js-form.js"></script>

<script type="text/javascript">
$(window).keydown(function(e){
    //console.log(e.keyCode);
    if(e.keyCode == 112){
      //F1 : tampilkan bantuan
      $('#modal-10').removeClass('md-show');
      $('#modal-11').removeClass('md-show');
      $('#modal-12').removeClass('md-show');
      $('#popup_koang').css('display','none');
      $('#popup_koang').hide();

      $('#modal-12').addClass('md-show');
    }
    else if(e.keyCode == 113){
      //F2 : Pencarian by Kategori / Nama
      $('#modal-10').removeClass('md-show');
      $('#modal-11').removeClass('md-show');
      $('#modal-12').removeClass('md-show');
      $('#popup_koang').css('display','none');
      $('#popup_koang').hide();

      var a = $('#sts_hide_pencarian').val();
      if(a == 1){
        $('#kategori_head').hide();
        $('#nama_menu_cari_head').fadeIn('slow');
        $('#cari_nama_menu').val('');
        $('#cari_nama_menu').focus();
        $('#sts_hide_pencarian').val(2);
      } else {
        $('#nama_menu_cari_head').hide();
        $('#kategori_head').fadeIn('slow');
        $('#sts_hide_pencarian').val(1);
      }
    }
    else if(e.keyCode == 114){
      //F3 : Tampil data resi yang tersimpan
      e.preventDefault();
      $('#search_koang').focus();
      $('#modal-10').removeClass('md-show');
      $('#modal-11').removeClass('md-show');
      $('#modal-12').removeClass('md-show');
      
      $('#korang').click();

    }
    else if(e.keyCode == 115){
      //F4 : Simpan Resi
      var a = $('.tr_pesanan').length;
      var b = $('#sts_lunas').val();
      if(a > 0){
        if(b == 0){
            $('#modal-10').addClass('md-show');
        }
      } 

    }
    else if(e.keyCode == 116){
      //F5 : Proses Pembayaran
      e.preventDefault();
      var a = $('.tr_pesanan').length;
      var b = $('#sts_lunas').val();
      if(a > 0){
        if(b == 0){
            $('#modal-11').addClass('md-show');
        }
      } 

    } else if(e.keyCode == 27){
        $('#modal-10').removeClass('md-show');
        $('#modal-11').removeClass('md-show');
        $('#modal-12').removeClass('md-show');
        $('#popup_koang').css('display','none');
        $('#popup_koang').hide();
    }
});

$(document).ready(function(){
    
    data_obat();
    data_resep();

    $('.non_tunai_grp').hide();

    $(".num_only").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl+A
            (e.keyCode == 65 && e.ctrlKey === true) ||
             // Allow: Ctrl+C
            (e.keyCode == 67 && e.ctrlKey === true) ||
             // Allow: Ctrl+X
            (e.keyCode == 88 && e.ctrlKey === true) ||
             // Allow: home, end, left, right
            (e.keyCode >= 35 && e.keyCode <= 39)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });

    $('#korang').click(function(){
        get_popup_barang();
        ajax_barang();
    });

    $('#ok_trx').click(function(){
        var id = $('#id_obat_trx').val();

        $('#head_tbl_pesanan1').hide();
        $('#head_tbl_pesanan2').show();
        $('#mid_head').show();
        $('#mid_head2').show();       

        var qty2  = $('#jumlah_beli_trx').val();

        $.ajax({
            url : '<?=base_url();?>apotek/ap_beli_obat_c/data_obat_id',
            data : {id:id},
            type : "POST",
            dataType : "json",
            success : function(result){    
                $isi = "";
                var warna = "";
                var tot = 0;

                if( $('#sts_edit').val() == 1 ){
                    warna = "style='background:#c4dff6;'";
                }

                for(var i=0; i<result.length; i++){
                    
                    var harga = result[i].HARGA_JUAL;
                    var jumlah_data = $('#tr_pesanan_'+result[i].ID).length;

                    if(jumlah_data > 0){
                        var jumlah = $('#jumlah_obat_hidden_'+result[i].ID).val();
                        $('#jumlah_obat_hidden_'+result[i].ID).val(parseInt(jumlah)+parseInt(qty2));
                        $('#qty_txt_'+result[i].ID).html(parseInt(jumlah)+parseInt(qty2));
                        var tambahjumlah = parseInt(jumlah)+parseInt(qty2);
                        tot = parseFloat(harga) * parseFloat(tambahjumlah);
                        $('#subtotal_hidden_'+id).val(tot);
                    }else{
                        tot = parseFloat(harga) * parseFloat(qty2);

                        $isi = '<tr '+warna+' class="tr_pesanan tr_baru" id="tr_pesanan_'+result[i].ID+'">'+
                                    '<input type="hidden" name="id_obat[]" value="'+result[i].ID+'">'+
                                    '<input type="hidden" name="jumlah_obat[]" id="jumlah_obat_hidden_'+result[i].ID+'" value="'+qty2+'">'+
                                    '<input type="hidden" name="harga_obat[]" id="harga_obat_hidden_'+result[i].ID+'" value="'+result[i].HARGA_JUAL+'">'+
                                    '<input type="hidden" name="subtotal_hidden[]" id="subtotal_hidden_'+result[i].ID+'" value="'+tot+'" class="subtotal">'+
                                    '<td align="left" style="width: 5%;">'+
                                        '<button onclick="del_pesanan(this);" class="btn btn-sm btn-danger hide_bayar" type="button"><i class="fa fa-times"></i> </button>'+
                                    '</td>'+
                                    '<td align="left"> <font id="qty_txt_'+result[i].ID+'"> '+qty2+' </font> X '+result[i].NAMA_OBAT+'</td>'+
                                    '<td align="right" id="harga_txt_'+result[i].ID+'">'+formatNumber(tot)+'</td>'+
                                '</tr>';
                    }

                }

                $('#tes').append($isi);
                get_jumlah_sub(id);
            }
        });

        $('#tutup_trx').click();

        // get_jumlah_sub(id);
        // hitung_diskon();
    });

    $('#tutup_or').click(function(){
        $('#popup_resep').fadeOut();
    });

});

function data_obat(){
    var keyword = $('#cari_nama_menu').val();

    $.ajax({
        url : '<?php echo base_url(); ?>apotek/ap_beli_obat_c/get_data_obat',
        data : {keyword:keyword},
        type : "POST",
        dataType : "json",
        success : function(result){
            $tr = "";

            if(result == null || result == ""){
                $tr = "<tr><td colspan='5' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
            }else{
                var no = 0;
                for(var i=0; i<result.length; i++){
                    no++;

                    $tr += "<tr style='cursor:pointer;' onclick='popup_pesanan("+result[i].ID+");'>"+
                                "<input type='hidden' class='menu_qty' value='0' id='menu_qty_"+result[i].ID+"'>"+
                                "<input type='hidden' value='"+result[i].HARGA_JUAL+"' id='rego_"+result[i].ID+"'>"+
                                "<td style='text-align:center;'>"+no+"</td>"+
                                "<td>"+
                                    result[i].NAMA_OBAT+"<br>"+"<small><b>"+result[i].KODE_OBAT+"</b></small>"+
                                "</td>"+
                                "<td style='text-align:center;'>"+result[i].NAMA_JENIS+"</td>"+
                                "<td style='text-align:right;'>"+formatNumber(result[i].HARGA_JUAL)+"</td>"+
                                "<td style='text-align:center;'>"+result[i].TOTAL+"</td>"+
                            "</tr>";
                }
            }

            $('#tabel_obat tbody').html($tr);
        }
    });

    $('#cari_nama_menu').off('keyup').keyup(function(){
        data_obat();
    });
}

function popup_pesanan(id){
    $('#popup_pesanan_btn').click();

    $.ajax({
        url : '<?php echo base_url(); ?>apotek/ap_beli_obat_c/data_obat_id',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(result){
            for(var i=0; i<result.length; i++){
                $('#id_obat_trx').val(result[i].ID);
                $('#kode_obat_trx').val(result[i].KODE_OBAT);
                $('#nama_obat_trx').val(result[i].NAMA_OBAT);
                $('#harga_obat_trx').val(formatNumber(result[i].HARGA_JUAL));
                $('#jumlah_beli_trx').val("1");
                hitung_total();
            }
        }
    });
}

function hitung_total(){
    var harga = $('#harga_obat_trx').val();
    var jumlah = $('#jumlah_beli_trx').val();
    harga = harga.split(',').join('');
    jumlah = jumlah.split(',').join('');

    if(harga == ""){
        harga = 0;
    }

    if(jumlah == ""){
        jumlah = 0;
    }

    var total = parseFloat(harga) * parseFloat(jumlah);
    $('#total_trx').val(formatNumber(total));
}

function get_jumlah_sub(id){
    var j = $('#jumlah_obat_hidden_'+id).val();
    var h = $('#harga_obat_hidden_'+id).val();
    var t = parseFloat(j) * parseFloat(h);

    var sum = 0;
    $("input[name='subtotal_hidden[]']").each(function(id,elm){
        var n = elm.value;
        sum += parseFloat(n);
    });

    var ppn = (parseFloat(sum) * 10) / 100;
    var total_all = parseFloat(sum) + parseFloat(ppn);

    $('#subtotal_txt').html(formatNumber(sum));
    $('#ppn_txt').html(formatNumber(ppn));
    $('#total_all_txt').html(formatNumber(total_all));

    $('#ppn_hidden').val(ppn);
    $('#b_total_tagihan').val(formatNumber(total_all));
}

function del_pesanan(btn){
    var row = btn.parentNode.parentNode;
    row.parentNode.removeChild(row);

    var id = $('#id_obat_trx').val();
    var jml_tr = $('.tr_pesanan').length;

    if(jml_tr == 0 || jml_tr == null){
        $('#head_tbl_pesanan2').hide();
        $('#head_tbl_pesanan1').show();  
        $('#mid_head').hide();         
        $('#mid_head2').hide(); 
    }

    get_jumlah_sub(id);
}

function hitung_kembali(){
    var byr = $('#b_bayar').val();
    byr = byr.split(',').join('');

    var total = $('#b_total_tagihan').val();
    total = total.split(',').join('');

    var kembali = parseFloat(byr) - parseFloat(total);

    if(byr == "" || byr == null){
        kembali = "";
    }

    if(byr == "") {
        kembali = "";
    } else if(kembali < 0){
        kembali = "";
    }

    $('#b_kembali').val(NumberToMoney(kembali));
}

function never_zero(){
    var nilai = $('.num_only').val();
    if(nilai == "" || nilai == null || nilai == 0){
        $('#btn_simpan_jml_pesan').hide();
    } else {
        $('#btn_simpan_jml_pesan').show(); 
    }
}

function simpan_pembayaran(){
    var b_kembali_sts = $('#b_kembali').val();

    if(b_kembali_sts == "" || b_kembali_sts == null){
        $('#warning_kelebihan').show();
    } else {
        $('#warning_kelebihan').hide();
        
        $.ajax({
            url : '<?=base_url();?>apotek/ap_beli_obat_c/simpan_trx',
            data : $('#form_pembayaran').serialize(),
            type : "POST",
            dataType : "json",
            success : function(result){                 
                $('#modal-11').removeClass('md-show');
                $('#popup_pembayaran').fadeOut();
                cetak_resi();
                window.location = "<?php echo base_url(); ?>apotek/ap_beli_obat_c";
            }
        });
    }
}

function cetak_resi(){
    var invoice = $('#invoice_txt').val();
    var prt = window.open('<?php echo base_url(); ?>apotek/ap_beli_obat_c/struk/'+invoice, '_blank');
    prt.print();
}

function hitung_diskon(){
   var sts_vcr = $('#sts_vcr').val(); 
   if(sts_vcr == 1){
       var tipe  = $('#tipe_vcr').val();
       var nilai = $('#nilai_vcr').val();

       var total = $('#subtotal_txt').html();
       total = total.split(',').join('');

       if(tipe == 'Prosen'){
            var itung = (parseFloat(total) * parseFloat(nilai)) / 100;
            $('#diskon_txt').html(NumberToMoney(itung).split('.00').join(''));

            var tall = $("#total_all_txt").html();
            tall = tall.split(',').join('');
            var grandtotal = parseFloat(tall) - parseFloat(itung);
            $("#total_all_txt").html(NumberToMoney(grandtotal).split('.00').join(''));
            $("#total_tagihan").val(NumberToMoney(grandtotal));
            $("#b_total_tagihan").val(NumberToMoney(grandtotal));
       } else {
            var tall = $("#total_all_txt").html();
            tall = tall.split(',').join('');
            var grandtotal = parseFloat(tall) - parseFloat(nilai);
            $("#total_all_txt").html(NumberToMoney(grandtotal).split('.00').join(''));
            $("#total_tagihan").val(NumberToMoney(grandtotal));
            $("#b_total_tagihan").val(NumberToMoney(grandtotal));
       }
   } 
}

function get_tunai(){
    document.getElementById("tunai_btn").className = "btn btn-warning";
    document.getElementById("non_tunai_btn").className = "btn btn-default";
    $('.tunai_grp').show();
    $('.non_tunai_grp').hide();
    $('#b_kembali').val('');
    $('#b_bayar').val('');
    $('#jenis_bayar').val('Tunai');
} 

function get_non_tunai(){
    var tagihan = $('#b_total_tagihan').val();
    tagihan = tagihan.split(',').join('');

    document.getElementById("non_tunai_btn").className = "btn btn-warning";
    document.getElementById("tunai_btn").className = "btn btn-default";
    $('.tunai_grp').hide();
    $('.non_tunai_grp').show();
    $('#b_kembali').val(0);
    $('#b_bayar').val(tagihan);
    $('#jenis_bayar').val('Kartu Kredit');
}

function data_resep(){
    var keyword = $('#cari_resep').val();

    $.ajax({
        url : '<?=base_url();?>apotek/ap_beli_obat_c/get_resep',
        data : {keyword:keyword},
        type : "POST",
        dataType : "json",
        success : function(result){
            $tr = "";

            if(result == null || result == ""){
                $tr = "<tr><td colspan='4' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
            }else{
                var no = 0;

                for(var i=0; i<result.length; i++){
                    no++;

                    var aksi = "<button type='button' class='btn btn-success btn-sm' onclick='deleteRow(this);'><i class='fa fa-check'></i></button>";

                    $tr += "<tr style='cursor:pointer;' onclick='get_detail_resep("+result[i].ID+","+result[i].DARI+");'>"+
                                "<td style='text-align:center;'>"+no+"</td>"+
                                "<td style='text-align:center;'>"+result[i].KODE_RESEP+"</td>"+
                                "<td>"+result[i].NAMA_PASIEN+"</td>"+
                                "<td align='center'>"+aksi+"</td>"+
                            "</tr>";
                }
            }

            $('#tabel_resep tbody').html($tr);
        }
    });

    $('#cari_resep').off('keyup').keyup(function(){
        data_resep();
    });
}

function deleteRow(btn){
    var row = btn.parentNode.parentNode;
    row.parentNode.removeChild(row);
}

function get_detail_resep(id_resep,dari){ 
    $('#popup_resep').show();

    $.ajax({
        url : '<?=base_url();?>apotek/ap_beli_obat_c/get_resep_id',
        data : {id_resep:id_resep,dari:dari},
        type : "POST",
        dataType : "json",
        success : function(result){                 
            $tr = "";

            var no = 0;

            for(var i=0; i<result.length; i++){
                no++;

                var aksi = "<button type='button' class='btn btn-success btn-sm' onclick='deleteRow(this);'><i class='fa fa-check'></i></button>";

                $tr += "<tr style='cursor:pointer;' onclick='get_detail_resep("+result[i].ID+","+result[i].DARI+");'>"+
                            "<td style='text-align:center;'>"+no+"</td>"+
                            "<td>"+result[i].NAMA_OBAT+"</td>"+
                            "<td style='text-align:right;'>"+formatNumber(result[i].HARGA)+"</td>"+
                            "<td style='text-align:center;'>"+result[i].JUMLAH_BELI+"</td>"+
                            "<td style='text-align:right;'>"+formatNumber(result[i].SUBTOTAL)+"</td>"+
                        "</tr>";
            }

            $('#tabel_obat_resep tbody').html($tr);
        }
    });
}

</script>


</body>

</html>

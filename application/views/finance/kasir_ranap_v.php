<?PHP
$sess_user = $this->session->userdata('masuk_rs');
$id_user = $sess_user['id'];  //ID PEGAWAI

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
<title>Kasir Ranap</title>
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
<link href="<?php echo base_url(); ?>css-devan/style-devan.css" rel="stylesheet" type="text/css" />
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

#msg_kosong,
#view_non_tunai,
#view_notif_bayar,
#notif_sukses,
#view_tambahan{
    display: none;
}
</style>

</head>

<body data-page="medias" onload="startTime();">
    <div id="popup_load">
        <div class="window_load">
            <img src="<?=base_url()?>picture/progress.gif" height="100" width="125">
        </div>
    </div>

    <!-- BEGIN TOP MENU -->
    <input type="hidden" id="sts_edit" value="0" />
    <input type="hidden" id="sts_lunas" value="0" />
    <nav class="navbar navbar-fixed-top" role="navigation" style="background-color: #0863b5;">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#sidebar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a id="menu-medium" class="sidebar-toggle toggle_fullscreen tooltips" style="border-right: 1px solid #d6dde3;">
                    <i class="glyphicon glyphicon-fullscreen"></i>
                </a>
            </div>
            <div class="navbar-center"> <b style="color: #fff;">Kasir Ranap</b> </div>
            <div class="navbar-collapse collapse">
                <!-- BEGIN TOP NAVIGATION MENU -->
                <ul class="nav navbar-nav pull-right header-menu">
                    <li style="margin-right: 5px;">
                        <button onclick="$('#modal-12').addClass('md-show');" style="margin-top: 6px;" class="btn btn-warning btn-sm" type="button"> <i class="fa fa-question-circle"></i> Bantuan </button>
                    </li>

                    <!-- <li class="dropdown" id="user-header">
                        <a href="javascript:void(0);" style="color:#fff;">
                            <img src="<?php //echo base_url(); ?>files/foto_pegawai/<?=$user_detail->FOTO;?>" alt="user avatar" width="30" class="p-r-5">
                            <span class="username"> <?=$user_detail->NAMA;?> </span>
                        </a>
                    </li> -->
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
                <button type="button" id="notif_sukses" class="btn btn-success notification" data-type="success" data-message="<i class='fa fa-check-square-o' style='padding-right:6px'></i> Transaksi sukses dilakukan!" data-horiz-pos="left" data-verti-pos="bottom">Success</button>

                <div id="nama_menu_cari_head">
                    <div class="form-group">
                        <!-- <label class="form-label"><strong>Pencarian</strong> Obat</label> -->
                        <div class="controls">
                            <input type="text" id="cari_nama_menu" class="glowing form-control" value="" placeholder="Ketikkan pasien yang ingin dicari ...">
                        </div>
                    </div>
                </div>
            </div>
            </center>
            <div class="panel-content">
                <div class="row media-manager">
                    <div class="margin-bottom-30"></div>
                    <div class="col-sm-5">
                        <div class="panel panel-default">
                            <div class="panel-heading clearfix pos-rel">
                              <div class="pos-abs top-12 l-15 f-18 c-gray"><i class="fa fa-table"></i></div>
                              <h2 class="panel-title width-100p c-red text-center w-500 f-20 carrois">Data Pasien Rawat Inap</h2>
                            </div>
                            <div class="panel-body messages">
                              <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                  <div class="scroll-y" id="tabel_pasien" style="height: 505px;">
                        
                                  </div>
                                </div>
                              </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-7">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <!-- <h3 class="panel-title">Striped rows <strong>Table</strong></h3> -->
                                <span style="padding-bottom: 6px; padding-top: 6px;" class="label label-success">
                                    Invoice : #<b id="invoice_txt"></b>
                                </span>
                                <button type="button" class="btn btn-danger btn-sm" onclick="window.location='<?=base_url();?>finance/kasir_ranap_c';">Reset</button>
                            </div>
                            <hr>
                            <div class="panel-body messages">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="scroll-y" style="height: 420px;">
                                            <h3 class="panel-title"><strong>Detail</strong> Kamar</h3>
                                            <table class="table table-bordered" id="tabel_kamar_byr">
                                                <thead>
                                                    <tr class="warning">
                                                        <th style="text-align: center;">No</th>
                                                        <th style="text-align: center;">Kelas</th>
                                                        <th style="text-align: center;">No. Bed</th>
                                                        <th style="text-align: center;">Biaya</th>
                                                        <th style="text-align: center;">Hari</th>
                                                        <th style="text-align: center;">Visite Dokter</th>
                                                        <th style="text-align: center;">Jasa Sarana</th>
                                                        <th style="text-align: center;">Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    
                                                </tbody>
                                                <tfoot>
                                                    <tr class="active">
                                                        <td colspan="7" style="text-align: center; font-weight: bold;">Total Biaya</td>
                                                        <td style="text-align: right;"><b id="tot_biaya_kamar">0</b></td>
                                                    </tr>
                                                </tfoot>
                                            </table>

                                            <hr>

                                            <h3 class="panel-title"><strong>Detail</strong> Tindakan</h3>
                                            <table class="table table-bordered" id="tabel_tindakan_byr">
                                                <thead>
                                                    <tr class="info">
                                                        <th style="text-align: center;">No</th>
                                                        <th style="text-align: center;">Tindakan</th>
                                                        <th style="text-align: center;">Biaya</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    
                                                </tbody>
                                                <tfoot>
                                                    <tr class="active">
                                                        <td colspan="2" style="text-align: center; font-weight: bold;">Total Biaya</td>
                                                        <td style="text-align: right;"><b id="tot_biaya_tindakan">0</b></td>
                                                    </tr>
                                                </tfoot>
                                            </table>

                                            <hr>

                                            <h3 class="panel-title"><strong>Detail</strong> Laborat</h3>
                                            <table class="table table-bordered" id="tabel_laborat_byr">
                                                <thead>
                                                    <tr class="danger">
                                                        <th style="text-align: center;">No</th>
                                                        <th style="text-align: center;">Tindakan</th>
                                                        <th style="text-align: center;">Biaya</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    
                                                </tbody>
                                                <tfoot>
                                                    <tr class="active">
                                                        <td colspan="2" style="text-align: center; font-weight: bold;">Total Biaya</td>
                                                        <td style="text-align: right;"><b id="tot_biaya_laborat">0</b></td>
                                                    </tr>
                                                </tfoot>
                                            </table>

                                            <hr>

                                            <h3 class="panel-title"><strong>Detail</strong> Resep</h3>
                                            <table class="table table-bordered" id="tabel_resep_byr">
                                                <thead>
                                                    <tr class="success">
                                                        <th style="text-align: center;">No</th>
                                                        <th style="text-align: center;">Resep</th>
                                                        <th style="text-align: center;">Biaya</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    
                                                </tbody>
                                                <tfoot>
                                                    <tr class="active">
                                                        <td colspan="2" style="text-align: center; font-weight: bold;">Total Biaya</td>
                                                        <td style="text-align: right;"><b id="tot_biaya_resep">0</b></td>
                                                    </tr>
                                                </tfoot>
                                            </table>

                                            <hr>

                                            <h3 class="panel-title"><strong>Detail</strong> Asuransi</h3>
                                            <table class="table table-bordered" id="tabel_asr_byr">
                                                <thead>
                                                    <tr>
                                                        <th style="text-align: center;">No</th>
                                                        <th style="text-align: center;">Nama Asuransi</th>
                                                        <th style="text-align: center;">Asuransi</th>
                                                        <th style="text-align: center;">Biaya</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-md-12 p-t-10">
                                        <button class="btn btn-primary" type="button" id="btn_klik_bayar" style="width: 100%;" disabled="disabled">
                                            Proses Pembayaran <i class="fa fa-arrow-circle-right"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="col-sm-1" style="width: 2%;">   </div> -->

                    <div class="col-sm-12">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-2 col-md-2">
                                        <div class="panel panel-icon no-bd bg-blue hover-effect">
                                            <div class="panel-body bg-blue">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="icon"><i class="fa fa-user"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="panel-footer bg-blue">
                                                <h4><strong>Shift <b class="shift_user">0</b></strong></h4>
                                                <p><?=$user_detail->NAMA;?></p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-2 col-md-2">
                                        <div class="panel panel-icon no-bd bg-red hover-effect">
                                            <div class="panel-body bg-red">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="icon"><i class="fa fa-calendar-o"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="panel-footer bg-red">
                                            <?php
                                                $bulan_arr = array(
                                                    1 =>    "Januari", 2  =>"Februari", 3  =>"Maret", 4 =>"April",
                                                    5 =>    "Mei", 6  =>"Juni", 7  =>"Juli", 8 =>"Agustus",
                                                    9 =>    "September", 10 =>"Oktober", 11 =>"November", 12 =>"Desember"
                                                );
                                                $tgl = date('d');
                                                $bln = $bulan_arr[date('n')];
                                                $thn = date('Y');
                                                $tanggal = $tgl." ".$bln." ".$thn;
                                            ?>
                                                <h4><strong><?php echo $tanggal; ?></strong></h4>
                                                <p>Tanggal</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-2 col-md-2">
                                        <div class="panel panel-icon no-bd hover-effect" style="background-color: #c49f47; color: #fff;">
                                            <div class="panel-body" style="background-color: #c49f47;">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="icon"><i class="fa fa-clock-o"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="panel-footer" style="background-color: #c49f47;">
                                                <h4><strong id="waktu_txt">00:00</strong></h4>
                                                <p>Waktu</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-2 col-md-2">
                                        <div class="panel panel-icon no-bd bg-purple hover-effect">
                                            <div class="panel-body bg-purple">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="icon"><i class="fa fa-files-o"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="panel-footer bg-purple">
                                                <h4><strong>Tutup Kasir</strong></h4>
                                                <p>Input Nota Poli</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-2 col-md-2">
                                        <div class="panel panel-icon no-bd bg-green hover-effect">
                                            <div class="panel-body bg-green">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="icon"><i class="fa fa-file-text"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="panel-footer bg-green">
                                                <h4><strong>Rekap Pendapatan</strong></h4>
                                                <p>Hari, Bulan Dan Tahun</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-2 col-md-2" id="btn_logout" style="cursor: pointer;">
                                        <div class="panel panel-icon no-bd bg-dark hover-effect">
                                            <div class="panel-body bg-dark">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="icon"><i class="fa fa-sign-out"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="panel-footer bg-dark">
                                                <h4><strong>Logout</strong></h4>
                                                <p>Keluar Aplikasi</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END WRAPPER -->

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
                    <li><strong>F2:</strong> Pencarian berdasarkan <b>Nama Pasien</b></li>
                    <!-- <li><strong>F3:</strong> Menampilkan data resi yang tersimpan </li>
                    <li><strong>F4:</strong> Simpan Resi </li>
                    <li><strong>F5:</strong> Proses Pembayaran </li> -->
                </ul>
                <button onclick="$('#modal-12').removeClass('md-show');" class="btn btn-default"> Tutup </button>
            </div>
        </div>
    </div>

    <button class="btn btn-danger" data-toggle="modal" id="popup_closing" style="display:none;" data-target="#modal-basic1">Show me</button>
    <div class="modal fade" id="modal-basic1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #EBEBEB; color: #2B2B2B;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <center><h2 class="modal-title" id="myModalLabel"><strong>Konfirmasi</strong></h2></center>
                </div>
                <div class="modal-body">
                    Apakah anda ingin melakukan penutupan kasir?
                </div>
                <div class="modal-footer">
                    <button type="button" id="btn_ya_closing" class="btn btn-success">Ya</button>
                    <button type="button" class="btn btn-danger" id="btn_tutup_closing" data-dismiss="modal">Tidak</button>
                </div>
            </div>
        </div>
    </div>

    <button class="btn btn-danger" data-toggle="modal" id="popup_logout" style="display:none;" data-target="#modal-basic-out">Show me</button>
    <div class="modal fade" id="modal-basic-out" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #EBEBEB; color: #2B2B2B;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <center><h2 class="modal-title" id="myModalLabel"><strong>Konfirmasi</strong></h2></center>
                </div>
                <div class="modal-body">
                    Apakah anda ingin keluar dari aplikasi?
                </div>
                <div class="modal-footer">
                    <button type="button" id="btn_ya_logout" class="btn btn-success">Ya</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Tidak</button>
                </div>
            </div>
        </div>
    </div>

    <button class="btn btn-danger" data-toggle="modal" id="popup_pembayaran" style="display:none;" data-target="#modal-basic-bayar">Show me</button>
    <div class="modal fade" id="modal-basic-bayar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="form_pembayaran">
                    <input type="hidden" name="id_ri" id="id_ri" value="">
                    <input type="hidden" name="id_user" id="id_user" value="<?php echo $id_user; ?>">
                    <input type="hidden" name="shift" id="shift" value="">
                    <input type="hidden" name="invoice" id="invoice" value="">
                    <input type="hidden" name="sistem_bayar" id="sistem_bayar" value="">
                    <input type="hidden" name="jenis_bayar" id="jenis_bayar" value="Tunai">

                    <div class="modal-header" style="background-color: #EBEBEB; color: #2B2B2B;">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <center><h2 class="modal-title" id="myModalLabel"><strong>Proses Pembayaran</strong></h2></center>
                    </div>
                    <div class="modal-body">
                        <div>
                            <div id="warning_kelebihan" class="alert alert-danger fade in" style="width:100%; display:none;">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                <strong>Maaf, </strong> Jumlah bayar kurang <b id="jumlah_bayar">0</b>
                            </div>
                            <p>
                                <center>
                                    <button type="button" id="tunai_btn" onclick="get_tunai();" class="btn btn-warning">Tunai</button>
                                    <button type="button" id="non_tunai_btn" onclick="get_non_tunai();" class="btn btn-default">Debit/Credit Card</button>
                                </center>
                            </p>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label"><strong>Atas Nama</strong></label>
                                        <div class="controls">
                                            <input type="text" name="b_atas_nama" id="b_atas_nama" class="form-control" style="font-weight: bold;">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label"><strong> Total Biaya </strong></label>
                                        <div class="controls">
                                            <input type="text" name="b_total_tagihan" id="b_total_tagihan" class="form-control" style="font-size: 15px; font-weight: bold;" readonly>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label"><strong> Biaya Asuransi </strong></label>
                                        <div class="controls">
                                            <input type="text" name="b_asuransi" id="b_asuransi" class="form-control" style="font-size: 15px; font-weight: bold;" readonly>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label"><strong> Total </strong></label>
                                        <div class="controls">
                                            <input type="text" name="b_total" id="b_total" class="form-control" style="font-size: 15px; font-weight: bold;" readonly>
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

                                    <div class="form-group">
                                        <label class="form-label"><strong> Bayar </strong></label>
                                        <div class="controls">
                                            <input type="text" name="b_bayar" id="b_bayar" onkeyup="FormatCurrency(this); hitung_kembali();" class="form-control" style="font-size: 15px; font-weight: bold;">
                                        </div>
                                    </div>

                                    <div class="form-group" id="view_tambahan">
                                        <label class="form-label"><strong> Tambahan </strong></label>
                                        <div class="controls">
                                            <input type="hidden" name="asd" id="asd" value="">
                                            <input type="text" name="b_tambahan" id="b_tambahan" onkeyup="FormatCurrency(this); hitung_tambahan();" class="form-control" style="font-size: 15px; font-weight: bold;">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label"><strong> Kembali </strong></label>
                                        <div class="controls">
                                            <input type="text" name="b_kembali" id="b_kembali" class="form-control" style="font-weight: bold; font-size: 20px; color: red;" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" id="btn-proses-byr" disabled="disabled">Proses</button>
                        <button type="button" class="btn btn-default" id="btn-batal-byr" data-dismiss="modal">Batal</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <button type="button" data-type="error" class="btn btn-danger notification" id="notif_closing" data-message="" style="display:none;" data-horiz-pos="left" data-verti-pos="bottom">Error</button>

    <button type="button" onclick="snd.play();" id="btn_suara_closing" style="display:none;" name="button">aaa</button>

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

<!-- <script src="<?=base_url();?>kasir-apotek/assets/plugins/modal/js/classie.js"></script>
<script src="<?=base_url();?>kasir-apotek/assets/plugins/modal/js/modalEffects.js"></script> -->
<script src="<?php echo base_url(); ?>js-devan/pagination.js"></script>


<script src="<?=base_url();?>kasir-apotek/assets/js/application.js"></script>
<script src="<?=base_url();?>kasir-apotek/assets/js/form.js"></script>
<script src="<?=base_url();?>kasir-apotek/assets/plugins/icheck/custom.js"></script>
<script src="<?=base_url();?>kasir-apotek/assets/plugins/bootstrap-switch/bootstrap-switch.js"></script>
<script src="<?=base_url();?>kasir-apotek/assets/plugins/bootstrap-progressbar/bootstrap-progressbar.js"></script>
<script src="<?=base_url();?>kasir-apotek/assets/plugins/jnotify/jNotify.jquery.min.js"></script>

<script src="<?=base_url();?>kasir-apotek/assets/js/notifications.js"></script>

<script src="<?php echo base_url(); ?>js-devan/js-form.js"></script>
<script src="http://code.responsivevoice.org/responsivevoice.js"></script>

<script type="text/javascript">
var snd = new Audio("<?php echo base_url(); ?>sound/Google_Event-1.mp3"); // buffers automatically when created

var Base64 = {
    _keyStr: "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/-",

    encode: function(input) {
        var output = "";
        var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
        var i = 0;

        input = Base64._utf8_encode(input);

        while (i < input.length) {

            chr1 = input.charCodeAt(i++);
            chr2 = input.charCodeAt(i++);
            chr3 = input.charCodeAt(i++);

            enc1 = chr1 >> 2;
            enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
            enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
            enc4 = chr3 & 63;

            if (isNaN(chr2)) {
                enc3 = enc4 = 64;
            } else if (isNaN(chr3)) {
                enc4 = 64;
            }

            output = output + this._keyStr.charAt(enc1) + this._keyStr.charAt(enc2) + this._keyStr.charAt(enc3) + this._keyStr.charAt(enc4);

        }

        return output;
    },


    decode: function(input) {
        var output = "";
        var chr1, chr2, chr3;
        var enc1, enc2, enc3, enc4;
        var i = 0;

        input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");

        while (i < input.length) {

            enc1 = this._keyStr.indexOf(input.charAt(i++));
            enc2 = this._keyStr.indexOf(input.charAt(i++));
            enc3 = this._keyStr.indexOf(input.charAt(i++));
            enc4 = this._keyStr.indexOf(input.charAt(i++));

            chr1 = (enc1 << 2) | (enc2 >> 4);
            chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
            chr3 = ((enc3 & 3) << 6) | enc4;

            output = output + String.fromCharCode(chr1);

            if (enc3 != 64) {
                output = output + String.fromCharCode(chr2);
            }
            if (enc4 != 64) {
                output = output + String.fromCharCode(chr3);
            }

        }

        output = Base64._utf8_decode(output);

        return output;

    },

    _utf8_encode: function(string) {
        string = string.replace(/\r\n/g, "\n");
        var utftext = "";

        for (var n = 0; n < string.length; n++) {

            var c = string.charCodeAt(n);

            if (c < 128) {
                utftext += String.fromCharCode(c);
            }
            else if ((c > 127) && (c < 2048)) {
                utftext += String.fromCharCode((c >> 6) | 192);
                utftext += String.fromCharCode((c & 63) | 128);
            }
            else {
                utftext += String.fromCharCode((c >> 12) | 224);
                utftext += String.fromCharCode(((c >> 6) & 63) | 128);
                utftext += String.fromCharCode((c & 63) | 128);
            }

        }

        return utftext;
    },

    _utf8_decode: function(utftext) {
        var string = "";
        var i = 0;
        var c = c1 = c2 = 0;

        while (i < utftext.length) {

            c = utftext.charCodeAt(i);

            if (c < 128) {
                string += String.fromCharCode(c);
                i++;
            }
            else if ((c > 191) && (c < 224)) {
                c2 = utftext.charCodeAt(i + 1);
                string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
                i += 2;
            }
            else {
                c2 = utftext.charCodeAt(i + 1);
                c3 = utftext.charCodeAt(i + 2);
                string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
                i += 3;
            }

        }

        return string;
    }
}

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
    get_pasien();

    setInterval(function () {
        get_pasien();
    }, 5000);

    get_invoice();

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

    $('#btn_logout').click(function(){
        var suara = new Audio("<?php echo base_url(); ?>sound/alert.mp3"); // buffers automatically when created
        $('#popup_logout').click();
        suara.play();
    });

    $('#btn_ya_logout').click(function(){
        window.location = '<?=base_url();?>logout';
    });

    $('#btn_tunai').click(function(){
        $('#btn_tunai').removeAttr('class');
        $('#btn_tunai').attr('class','btn btn-primary');
        $('#btn_non_tunai').removeAttr('class');
        $('#btn_non_tunai').attr('class','btn btn-primary btn-transparent');
        $('#view_non_tunai').hide();
        $('#bayar2').removeAttr('readonly');
        $('#bayar2').focus();
        $('#jenis_pembayaran').val('Tunai');
    });

    $('#btn_non_tunai').click(function(){
        $('#btn_non_tunai').removeAttr('class');
        $('#btn_non_tunai').attr('class','btn btn-primary');
        $('#btn_tunai').removeAttr('class');
        $('#btn_tunai').attr('class','btn btn-primary btn-transparent');
        $('#view_non_tunai').show();
        $('#bayar2').attr('readonly');
        $('#bayar2').val("");
        $('#kembali2').val("");
        $('#jenis_pembayaran').val('Non Tunai');
    });

    $('#btn_ya_closing').click(function(){
      simpan_closing();
    });

    $('#btn_show_data_pembayaran').click(function(){
        data_pembayaran();
    });

    $('#btn_klik_bayar').click(function(){
        $('#popup_pembayaran').click();
    });

    $('#btn-proses-byr').click(function(){
        $.ajax({
            url : '<?=base_url();?>finance/kasir_ranap_c/simpan_trx',
            data : $('#form_pembayaran').serialize(),
            type : "POST",
            dataType : "json",
            success : function(res){
                var id = $('#id_ri').val();
                window.open('<?php echo base_url(); ?>finance/kasir_ranap_c/cetak/'+id, '_blank', 'location=yes,height=700,width=800,scrollbars=yes,status=yes');
                setInterval(function () {
                    window.location = "<?php echo base_url(); ?>finance/kasir_ranap_c";
                }, 3000);
            }
        });
    });

    $('#btn-batal-byr').click(function(){
        $('#b_bayar').val("");
        $('#b_kembali').val("");
        $('#b_tambahan').val("");
        $('#warning_kelebihan').hide();
        $('#view_tambahan').hide();
    });
});

function simpan_closing(){
  $("input[name='id_rajal_hidden[]']").each(function(idx, elm){
    var id_rajal = elm.value;
    var id_pegawai = $('#id_pegawai').val();
    var shift = $('#shift').val();
    $.ajax({
      url : '<?php echo base_url(); ?>apotek/ap_kasir_rajal_c/simpan_closing',
      data : {
        id_rajal:id_rajal,
        id_pegawai:id_pegawai,
        shift:shift
      },
      type : "POST",
      dataType : "json",
      success : function(){
        $('#btn_tutup_closing').click();
      }
    });
  });
}

function startTime() {
    var today = new Date();
    var h = today.getHours();
    var m = today.getMinutes();
    var s = today.getSeconds();
    m = checkTime(m);
    s = checkTime(s);
    document.getElementById('waktu_txt').innerHTML = h + ":" + m + ":" + s;
    $('#waktu').val(h + ":" + m + ":" + s);
    var t = setTimeout(startTime, 500);
    var jam = h+':'+m;
    // console.log(jam);

    if(h >= 7 && h < 14){
        $('.shift_user').html('1');
        $('#shift').val('1');
    }else if(h >= 14 && h < 23){
        $('.shift_user').html('2');
        $('#shift').val('2');
    }else{
        $('.shift_user').html('3');
        $('#shift').val('3');
    }
}

function checkTime(i) {
    if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
    return i;
}

function get_invoice(){
    $.ajax({
        url : '<?php echo base_url(); ?>finance/kasir_ranap_c/get_invoice',
        type : "POST",
        dataType : "json",
        success : function(res){
            $('#invoice').val(res);
            $('#invoice_txt').html(res);
        }
    });
}

function get_pasien(){
    var keyword = $('#cari_nama_menu').val();

    $.ajax({
        url : '<?php echo base_url(); ?>finance/kasir_ranap_c/get_pasien',
        data : {keyword:keyword},
        type : "GET",
        dataType : "json",
        success : function(result){
            $tr = "";

            if(result == null || result == ""){
                $tr =   '<a href="javascript:;" class="message-item media">'+
                        '  <div class="media">'+
                        '    <img src="<?php echo base_url(); ?>picture/forbidden.png" width="50" class="pull-left">'+
                        '    <div class="media-body">'+
                        '      <h5 class="c-dark"><strong>Perhatian!</strong></h5>'+
                        '      <h4 class="c-dark">Data Tidak Ditemukan</h4>'+
                        '    </div>'+
                        '  </div>'+
                        '</a>';
            }else{
                for(var i=0; i<result.length; i++){
                    var img = '';
                    if(result[i].JENIS_KELAMIN == 'L'){
                        img = '<?php echo base_url(); ?>kasir-apotek/assets/img/avatars/avatar11.png';
                    }else{
                        img = '<?php echo base_url(); ?>kasir-apotek/assets/img/avatars/avatar5.png';
                    }

                    var tanggal = shortMonth(result[i].TANGGAL_MASUK)+' - '+result[i].WAKTU;

                    result[i].SISTEM_BAYAR = result[i].SISTEM_BAYAR=='1'?"Umum":"Asuransi";

                    $tr +=  '<a href="javascript:;" class="message-item media" onclick="klik_pasien('+result[i].ID+');">'+
                            '  <div class="media">'+
                            '    <img src="'+img+'" width="50" class="pull-left">'+
                            '    <div class="media-body">'+
                            '      <div class="col-md-3">'+
                            '           <h5 class="c-dark"><strong>Pasien</strong></h5>'+
                            '           <h4 class="c-dark">'+result[i].NAMA_PASIEN+'</h4>'+
                            '      </div>'+
                            '      <div class="col-md-3">'+
                            '           <h5 class="c-dark"><strong>Total Biaya</strong></h5>'+
                            '           <h4 class="c-dark">'+formatNumber(result[i].TOTAL)+'</h4>'+
                            '      </div>'+
                            '      <div class="col-md-2">'+
                            '           <h5 class="c-dark"><strong>Jenis Bayar</strong></h5>'+
                            '           <h4 class="c-dark">'+result[i].SISTEM_BAYAR+'</h4>'+
                            '      </div>'+
                            '      <div class="col-md-2">'+
                            '           <h5 class="c-dark"><strong>Asuransi</strong></h5>'+
                            '           <h4 class="c-dark">'+formatNumber(result[i].TOT_ASURANSI)+'</h4>'+
                            '      </div>'+
                            '    </div>'+
                            '  </div>'+
                            '  <p class="f-14 c-blue pull-right"><b>'+tanggal+'</b></p>'+
                            '</a>';
                }
            }

            $('#tabel_pasien').html($tr);
        }
    });

    $('#cari_nama_menu').off('keyup').keyup(function(){
        get_pasien();
    });
}

function klik_pasien(id_ri){
    $('#popup_load').show();
    get_kamar(id_ri);
    get_tindakan(id_ri);
    get_lab(id_ri);
    get_resep(id_ri);
    get_asuransi(id_ri);

    $.ajax({
        url : '<?php echo base_url(); ?>finance/kasir_ranap_c/get_pasien_id',
        data : {id:id_ri},
        type : "POST",
        dataType : "json",
        success : function(row){
            $('#id_ri').val(id_ri);
            $('#btn_klik_bayar').removeAttr('disabled');
            $('#b_atas_nama').val(row['NAMA_PASIEN']);
            $('#b_total_tagihan').val(formatNumber(row['TOTAL']));
            $('#b_asuransi').val(formatNumber(row['TOT_ASURANSI']));
            $('#sistem_bayar').val(row['SISTEM_BAYAR']);

            var total = parseFloat(row['TOTAL']) - parseFloat(row['TOT_ASURANSI']);
            $('#b_total').val(formatNumber(total));
            $('#popup_load').hide();
        }
    });
}

function get_kamar(id_ri){
    $.ajax({
        url : '<?php echo base_url(); ?>finance/kasir_ranap_c/get_kamar',
        data : {id_ri:id_ri},
        type : "POST",
        dataType : "json",
        success : function(res){
            $tr = '';
            var tot = 0;

            if(res == null || res == ""){
                $tr = '<tr><td colspan="`8" style="text-align:center;">Data Tidak Ada</td></tr>';
            }else{
                var no = 0;

                for(var i=0; i<res.length; i++){
                    no++;
                    var kelas = res[i].KELAS+' - '+res[i].VISITE_DOKTER;
                    var total = parseFloat(res[i].BIAYA_KAMAR) + parseFloat(res[i].BIAYA_VISITE) + parseFloat(res[i].JASA_SARANA);
                    tot += parseFloat(total);

                    $tr += '<tr>'+
                            '<td style="text-align:center;">'+no+'</td>'+
                            '<td style="text-align:center;">'+kelas+'</td>'+
                            '<td style="text-align:center;">'+res[i].NOMOR_BED+'</td>'+
                            '<td style="text-align:right;">'+formatNumber(res[i].BIAYA)+'</td>'+
                            '<td style="text-align:center;">'+res[i].DIRAWAT_SELAMA+'</td>'+
                            '<td style="text-align:right;">'+formatNumber(res[i].BIAYA_VISITE)+'</td>'+
                            '<td style="text-align:right;">'+formatNumber(res[i].JASA_SARANA)+'</td>'+
                            '<td style="text-align:right;"><b>'+formatNumber(total)+'</b></td>'+
                          '</tr>';
                }
            }

            $('#tot_biaya_kamar').html(formatNumber(tot));
            $('#tabel_kamar_byr tbody').html($tr);
        }
    });
}

function get_tindakan(id_ri){
    $.ajax({
        url : '<?php echo base_url(); ?>finance/kasir_ranap_c/get_tindakan',
        data : {id_ri:id_ri},
        type : "POST",
        dataType : "json",
        success : function(res){
            $tr = '';
            var tot = 0;

            if(res == null || res == ""){
                $tr = '<tr><td colspan="3" style="text-align:center;">Data Tidak Ada</td></tr>';
            }else{
                var no = 0;

                for(var i=0; i<res.length; i++){
                    no++;
                    tot += parseFloat(res[i].TARIF);

                    $tr +=  '<tr>'+
                                '<td style="text-align:center;">'+no+'</td>'+
                                '<td>'+res[i].NAMA_TINDAKAN+'</td>'+
                                '<td style="text-align:right;">'+formatNumber(res[i].TARIF)+'</td>'+
                            '</tr>';
                }
            }

            $('#tot_biaya_tindakan').html(formatNumber(tot));
            $('#tabel_tindakan_byr tbody').html($tr);
        }
    });
}

function get_lab(id_ri){
    $.ajax({
        url : '<?php echo base_url(); ?>finance/kasir_ranap_c/get_lab',
        data : {id_ri:id_ri},
        type : "POST",
        dataType : "json",
        success : function(res){
            $tr = '';
            var tot = 0;

            if(res == null || res == ""){
                $tr = '<tr><td colspan="3" style="text-align:center;">Data Tidak Ada</td></tr>';
            }else{
                var no = 0;

                for(var i=0; i<res.length; i++){
                    no++;
                    tot += parseFloat(res[i].SUBTOTAL);

                    $tr +=  '<tr>'+
                                '<td style="text-align:center;">'+no+'</td>'+
                                '<td>'+res[i].NAMA_PEMERIKSAAN+'</td>'+
                                '<td style="text-align:right;">'+formatNumber(res[i].SUBTOTAL)+'</td>'+
                            '</tr>';
                }
            }

            $('#tot_biaya_laborat').html(formatNumber(tot));
            $('#tabel_laborat_byr tbody').html($tr);
        }
    });
}

function get_resep(id_ri){
    $.ajax({
        url : '<?php echo base_url(); ?>finance/kasir_ranap_c/get_resep',
        data : {id_ri:id_ri},
        type : "POST",
        dataType : "json",
        success : function(res){
            $tr = '';
            var tot = 0;

            if(res == null || res == ""){
                $tr = '<tr><td colspan="4" style="text-align:center;">Data Tidak Ada</td></tr>';
            }else{
                var no = 0;

                for(var i=0; i<res.length; i++){
                    no++;
                    tot += parseFloat(res[i].SUBTOTAL);

                    $tr += '<tr>'+
                            '<td style="text-align:center;">'+no+'</td>'+
                            '<td>'+res[i].NAMA_OBAT+'</td>'+
                            '<td style="text-align:right;"><b>'+formatNumber(res[i].SUBTOTAL)+'</b></td>'+
                          '</tr>';
                }
            }

            $('#tot_biaya_resep').html(formatNumber(tot));
            $('#tabel_resep_byr tbody').html($tr);
        }
    });
}

function get_asuransi(id_ri){
    $.ajax({
        url : '<?php echo base_url(); ?>finance/kasir_ranap_c/get_asuransi',
        data : {id_ri:id_ri},
        type : "POST",
        dataType : "json",
        success : function(res){
            $tr = '';
            var tot = 0;

            if(res == null || res == ""){
                $tr = '<tr><td colspan="4" style="text-align:center;">Tidak Ada Asuransi</td></tr>';
            }else{
                var no = 0;

                for(var i=0; i<res.length; i++){
                    no++;
                    tot += parseFloat(res[i].SUBTOTAL);

                    $tr +=  '<tr>'+
                                '<td style="text-align:center;">'+no+'</td>'+
                                '<td>'+res[i].NAMA_ASURANSI+'</td>'+
                                '<td>'+res[i].ASURANSI+'</td>'+
                                '<td style="text-align:right;">'+formatNumber(res[i].JML_KLAIM)+'</td>'+
                            '</tr>';
                }
            }

            $('#tabel_asr_byr tbody').html($tr);
        }
    });
}

function hitung_kembali(){
    var byr = $('#b_bayar').val();
    byr = byr.split(',').join('');

    var total = $('#b_total').val();
    total = total.split(',').join('');

    var kembali = parseFloat(byr) - parseFloat(total);

    if(byr == "" || byr == null){
        kembali = "";
    }

    if(byr == "") {
        kembali = "";
    }else if(kembali < 0){
        kembali = "";
        $('#warning_kelebihan').show();
        $('#view_tambahan').show();
        var s = parseFloat(total) - parseFloat(byr);
        $('#asd').val(s);
        $('#jumlah_bayar').html(formatNumber(s));
        $('#btn-proses-byr').attr('disabled','disabled');
    }else{
        $('#warning_kelebihan').hide();
        $('#view_tambahan').hide();
        $('#btn-proses-byr').removeAttr('disabled');
    }

    $('#b_kembali').val(NumberToMoney(kembali));
}

function hitung_tambahan(){
    var tambah = $('#b_tambahan').val();
    tambah = tambah.split(',').join('');

    var sisa = $('#asd').val();
    sisa = sisa.split(',').join('');

    // console.log(sisa);

    var kembali = parseFloat(tambah) - parseFloat(sisa);

    if(tambah == ""){
        kembali = "";
        $('#jumlah_bayar').html(formatNumber(sisa));
    }else if(kembali < 0){
        kembali = "";
        $('#warning_kelebihan').show();
        var s = parseFloat(sisa) - parseFloat(tambah);
        // console.log(s);
        $('#jumlah_bayar').html(formatNumber(s));
        $('#btn-proses-byr').attr('disabled','disabled');
    }else{
        $('#warning_kelebihan').hide();
        $('#btn-proses-byr').removeAttr('disabled');
    }

    $('#b_kembali').val(NumberToMoney(kembali));
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
    var tagihan = $('#b_total').val();
    tagihan = tagihan.split(',').join('');

    document.getElementById("non_tunai_btn").className = "btn btn-warning";
    document.getElementById("tunai_btn").className = "btn btn-default";
    $('.tunai_grp').hide();
    $('.non_tunai_grp').show();
    $('#b_kembali').val(0);
    $('#b_bayar').val(formatNumber(tagihan));
    $('#jenis_bayar').val('Non Tunai');
}

function deleteRow(btn){
    var row = btn.parentNode.parentNode;
    row.parentNode.removeChild(row);
}
</script>


</body>

</html>
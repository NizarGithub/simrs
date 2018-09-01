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
<title>Kasir Rajal</title>
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

#msg_kosong,
#view_non_tunai,
#view_notif_bayar,
#notif_sukses{
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
#short_shift{
  display: none;
}
#btn_closing_kasir{
  display: none;
  cursor: pointer;
}

#btn_rekap_pendapatan{
  cursor: pointer;
}
.non_tunai_grp{
  display: none;
}
#view_tambahan{
  display: none;
}
</style>

</head>

<body data-page="medias" onload="startTime(); startNotifClosing();">
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
            <div class="navbar-center"> Kasir Rajal </div>
            <div class="navbar-collapse collapse">
                <!-- BEGIN TOP NAVIGATION MENU -->
                <ul class="nav navbar-nav pull-right header-menu">
                    <li style="margin-right: 5px;">
                        <button id="btn_show_data_pembayaran" style="margin-top: 6px;" class="btn btn-success btn-sm" type="button"> <i class="fa fa-folder-open-o"></i> Data Pembayaran </button>
                      </li>
                    <li style="margin-right: 5px;">
                        <button onclick="$('#modal-12').addClass('md-show');" style="margin-top: 6px;" class="btn btn-warning btn-sm" type="button"> <i class="fa fa-question-circle"></i> Bantuan </button>
                    </li>

                    <!-- <li class="dropdown" id="user-header">
                        <a href="javascript:void(0);" style="color:#fff;">
                            <img src="<?php //echo base_url(); ?>files/foto_pegawai/<?=$user_detail->FOTO;?>" alt="user avatar" width="30" class="p-r-5">
                            <span class="username"> <?=$user_detail->NAMA;?> </span>
                        </a>
                    </li> -->

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
                    <div class="col-sm-8">
                        <div class="panel panel-default">
                            <div class="scroll-y">
                                <table class="table table-hover" id="tabel_pasien">
                                    <thead>
                                        <tr class="success">
                                            <th style="text-align:center;">No</th>
                                            <th style="text-align:center;">TANGGAL</th>
                                            <th style="text-align:center;">Nama</th>
                                            <th style="text-align:center;">Poli</th>
                                            <th style="text-align:center;">Copy Resep</th>
                                            <th style="text-align:center;">Nota Poli</th>
                                            <th style="text-align:center;">Total Biaya</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- <div class="col-sm-1" style="width: 2%;">   </div> -->

                    <div id="panel_kanan" class="col-sm-4" style="width: 32%; background:#F0F4F8;">
                        <form id="form_pembayaran">
                            <div class="m-b-10"></div>

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
                        </form>
                        <hr>
                        <div class="row m-t-20">
                          <div class="col-lg-12 col-md-6" id="long_shift">
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
                          <div class="col-lg-6 col-md-6" id="short_shift">
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

                            <div class="col-lg-6 col-md-6" id="btn_closing_kasir">
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
                                        <p>Tutup (Closing) Kasir</p>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="row m-t-20" id="btn_rekap_pendapatan">
                          <div class="col-lg-6 col-md-6">
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
                            <div class="col-lg-6 col-md-6">
                                <div class="panel panel-icon no-bd bg-dark hover-effect">
                                    <div class="panel-body bg-dark">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="icon"><i class="fa fa-clock-o"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-footer bg-dark">
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
                                        <p id="waktu_txt">00.00</p>
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

    <button id="popup_bayar" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target=".bs-example-modal-lg" style="display: none;">Large modal</button>
    <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form class="form-horizontal" method="post" action="" id="form_pembayaran2">
                    <input type="hidden" name="invoice" id="invoice" value="">
                    <input type="hidden" name="id_rj" id="id_rj" value="">
                    <input type="hidden" name="id_pasien" id="id_pasien" value="">
                    <input type="hidden" name="id_poli" id="id_poli" value="">
                    <input type="hidden" name="id_pegawai" id="id_pegawai" value="<?php echo $id_user; ?>">
                    <input type="hidden" name="shift" id="shift" value="">
                    <input type="hidden" name="tanggal" id="tanggal" value="<?php echo date('d-m-Y'); ?>">
                    <input type="hidden" name="waktu" id="waktu" value="">
                    <input type="hidden" name="jenis_pembayaran" id="jenis_pembayaran" value="">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="myLargeModalLabel">
                            <strong>Form</strong> Pembayaran Pasien : <button type="button" class="btn btn-success" id="nama_pasien_txt"></button>
                        </h4>
                    </div>
                    <div class="modal-body">
                        <div class="tabcordion">
                            <ul id="myTab" class="nav nav-tabs">
                                <li class="active"><a href="#tab1_1" data-toggle="tab">Detail Pembayaran</a></li>
                                <li><a href="#tab1_2" data-toggle="tab">Detail Tindakan</a></li>
                                <li><a href="#tab1_3" data-toggle="tab">Detail Resep</a></li>
                                <li><a href="#tab1_4" data-toggle="tab">Detail Laborat</a></li>
                            </ul>
                            <div id="myTabContent" class="tab-content">
                                <div class="tab-pane fade active in" id="tab1_1">
                                    <div class="row">
                                        <div class="col-md-2" style="margin-right: 10px;">
                                            <div class="form-group">
                                                <label class="form-label"><strong>BIAYA POLI</strong></label>
                                                <div class="controls">
                                                    <input type="text" class="form-control" name="biaya_poli" id="biaya_poli" value="" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3" style="margin-right: 10px;">
                                            <div class="form-group">
                                                <label class="form-label"><strong>BIAYA TINDAKAN</strong></label>
                                                <div class="controls">
                                                    <input type="text" class="form-control" name="biaya_tindakan" id="biaya_tindakan" value="" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3" style="margin-right: 10px;">
                                            <div class="form-group">
                                                <label class="form-label"><strong>BIAYA RESEP</strong></label>
                                                <div class="controls">
                                                    <input type="text" class="form-control" name="biaya_resep" id="biaya_resep" value="" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label"><strong>BIAYA LABORAT</strong></label>
                                                <div class="controls">
                                                    <input type="text" class="form-control" name="biaya_lab" id="biaya_lab" value="0" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-10">
                                            <div class="form-group">
                                                <label class="form-label"><strong>TOTAL</strong></label>
                                                <div class="controls">
                                                    <input type="text" class="form-control" name="grandtotal2" id="grandtotal2" value="" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label class="form-label"><strong>PEMBAYARAN</strong></label>
                                                <div class="controls">
                                                    <button class="btn btn-primary btn-transparent" id="btn_tunai" type="button">Tunai</button>
                                                    <button class="btn btn-primary btn-transparent" id="btn_non_tunai" type="button">Non Tunai</button>
                                                </div>
                                            </div>
                                            <div class="form-group" id="view_non_tunai">
                                                <div class="controls">
                                                    <button class="btn btn-success btn-transparent" id="btn_transfer" type="button">Transfer</button>
                                                    <button class="btn btn-primary btn-transparent" id="btn_wallet" type="button">Wallet</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-10" style="margin-right: 10px;">

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
                                                <label class="form-label"><strong>BAYAR</strong></label>
                                                <div class="controls">
                                                    <input type="text" class="form-control" name="bayar2" id="bayar2" value="" onkeyup="get_bayar(); FormatCurrency(this);" readonly>
                                                </div>
                                            </div>

                                            <div class="form-group" id="view_tambahan">
                                                <label class="form-label"><strong> Tambahan </strong></label>
                                                <div class="controls">
                                                    <input type="text" name="asd" id="asd" value="">
                                                    <input type="text" name="b_tambahan" id="b_tambahan" onkeyup="FormatCurrency(this); hitung_tambahan();" class="form-control" style="font-size: 15px; font-weight: bold;">
                                                </div>
                                            </div>

                                            <div class="form-group" id="view_notif_bayar">
                                                <div class="alert alert-danger" style="width: 100%;">
                                                    <p id="text_notif"></p> <strong id="text_total_notif">0</strong>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label"><strong>KEMBALI</strong></label>
                                                <div class="controls">
                                                    <input type="text" class="form-control" name="kembali2" id="kembali2" value="" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="tab1_2">
                                    <table class="table table-bordered" id="tabel_tindakan">
                                        <thead>
                                            <tr class="danger">
                                                <th style="text-align: center;">NO</th>
                                                <th style="text-align: center;">TINDAKAN</th>
                                                <th style="text-align: center;">BIAYA</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>

                                <div class="tab-pane fade" id="tab1_3">
                                    <table class="table table-bordered" id="tabel_resep2">
                                        <thead>
                                            <tr class="info">
                                                <th></th>
                                                <th style="text-align: center;">KODE RESEP</th>
                                                <th style="text-align: center;">TANGGAL</th>
                                                <th style="text-align: center;">TOTAL</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>

                                <div class="tab-pane fade" id="tab1_4">
                                  <table class="table table-bordered" id="tabel_laborat">
                                      <thead>
                                          <tr class="warning">
                                              <th style="text-align: center;">KODE LAB</th>
                                              <th style="text-align: center;">TANGGAL</th>
                                              <th style="text-align: center;">TOTAL TARIF</th>
                                          </tr>
                                      </thead>
                                      <tbody>
                                      </tbody>
                                  </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" id="btn_tutup" data-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-success" id="btn_bayar" disabled="disabled">Bayar</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

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

    <button class="btn btn-danger" data-toggle="modal" id="popup_data_pembayaran" style="display:none;" data-target="#modal-large">Show me</button>
    <div class="modal fade" id="modal-large" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <center><h3 class="modal-title" id="myModalLabel"><strong>Data Pembayaran</strong></h3></center>
                        </div>
                        <div class="modal-body">
                          <table class="table" id="tabel_pembayaran">
                            <thead>
                              <tr class="info">
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Invoice</th>
                                <th>Total Biaya</th>
                                <th>Pegawai</th>
                                <th>Shift</th>
                                <th>Copy Resep</th>
                                <th>Nota Poli</th>
                              </tr>
                            </thead>
                            <tbody>
                            </tbody>
                          </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

      <button class="btn btn-danger" data-toggle="modal" id="popup_rekap_pendapatan" style="display:none;" data-target="#modal-large2">Show me</button>
      <div class="modal fade" id="modal-large2" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                  <div class="modal-dialog modal-lg">
                      <div class="modal-content">
                          <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                              <center><h3 class="modal-title" id="myModalLabel"><strong>Rekap Pendapatan</strong></h3></center>
                          </div>
                          <div class="modal-body">
                            <div class="row">
                              <form class="" action="<?php echo base_url(); ?>apotek/ap_kasir_rajal_c/print_pdf" target="_blank" method="post">
                              <div class="col-md-12" style="margin-bottom: 1%;">
                                <button type="button" id="btn_filter_semua" onclick="get_filter_semua();" style="float: left;" class="btn btn-success">Semua</button>
                                <button type="button" id="btn_filter_tanggal" onclick="get_filter_tanggal();" style="float: left; margin-left: 1%;" class="btn btn-default">Tanggal</button>
                                <button type="button" id="btn_filter_poli" onclick="get_filter_poli();" style="float: left; margin-left: 1%;" class="btn btn-default">Poli</button>
                              </div>
                              <input type="hidden" name="by" id="hidden_filter_semua" value="semua">
                              <input type="hidden" name="by" id="hidden_filter_tanggal" value="tanggal" disabled>
                              <input type="hidden" name="by" id="hidden_filter_poli" value="poli" disabled>
                              <div id="form_tanggal_filter" style="display: none;">
                                <div class="col-md-4">
                                  <div class="form-group">
                                    <div class="input-group">
                                        <input type="text" name="tanggal_sekarang" id="tanggal_sekarang" class="form-control">
                                        <span class="input-group-addon bg-primary b-0 text-white">S/D</span>
                                        <input type="text" name="tanggal_sampai" id="tanggal_sampai" class="form-control">
                                    </div>
                                  </div>
                                </div>
                                <div class="col-md-1">
                                  <button type="button" class="btn btn-info" onclick="tanggal_filter();" name="button"><i class="fa fa-search"></i> Cari</button>
                                </div>
                              </div>
                              <div id="form_poli_filter" style="display: none;">
                                <div class="col-md-4">
                                  <select class="form-control" name="id_poli" id="result_poli">
                                    <?php
                                    $this->db->select('*');
                                    $this->db->from('admum_poli');
                                    $result_poli = $this->db->get()->result_array();
                                    foreach ($result_poli as $rp) {
                                    ?>
                                    <option value="<?php echo $rp['ID']; ?>"><?php echo $rp['NAMA']; ?></option>
                                    <?php } ?>
                                  </select>
                                </div>
                                <div class="col-md-1">
                                  <button type="button" class="btn btn-info" onclick="poli_filter();" name="button"><i class="fa fa-search"></i> Cari</button>
                                </div>
                              </div>
                              <div class="col-md-12">
                                <table class="table" id="tabel_rekap_pendapatan">
                                  <thead>
                                    <tr class="info">
                                      <th>No</th>
                                      <th>Invoice</th>
                                      <th>Nama Poli</th>
                                      <th>Tanggal</th>
                                      <th>Total Biaya</th>
                                      <th>Pegawai</th>
                                      <th>Shift</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                  </tbody>
                                </table>
                              </div>
                            </div>
                          </div>
                          <div class="modal-footer">
                              <button type="submit" class="btn btn-success">Print</button>
                              <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                          </div>
                          </form>
                      </div>
                  </div>
              </div>

    <button type="button" data-type="error" class="btn btn-danger notification" id="notif_closing" data-message="" style="display:none;" data-horiz-pos="left" data-verti-pos="bottom">Error</button>

    <button type="button" onclick="snd.play();" id="btn_suara_closing" style="display:none;" name="button">aaa</button>

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

    $('#ok_trx').click(function(){
        var id = $('#id_obat_trx').val();

        $('#head_tbl_pesanan1').hide();
        $('#head_tbl_pesanan2').show();
        $('#mid_head').show();
        $('#mid_head2').show();

        var qty2  = $('#jumlah_beli_trx').val();

        $.ajax({
            url : '<?=base_url();?>apotek/ap_kasir_rajal_c/data_obat_id',
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

    $('#btn_tunai').click(function(){
        $('#btn_tunai').removeAttr('class');
        $('#btn_tunai').attr('class','btn btn-primary');
        $('#btn_non_tunai').removeAttr('class');
        $('#btn_non_tunai').attr('class','btn btn-primary btn-transparent');
        $('#view_non_tunai').hide();
        $('#bayar2').removeAttr('readonly');
        $('#bayar2').focus();
        $('#jenis_pembayaran').val('Tunai');
        $('.non_tunai_grp').hide();
        $('#btn_transfer').attr('class','btn btn-success btn-transparent');
        $('#view_tambahan').hide();
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
        $('.non_tunai_grp').hide();
        $('#btn_transfer').attr('class','btn btn-success btn-transparent');
        $('#view_tambahan').hide();
    });

    $('#btn_transfer').click(function(){
      $('#btn_transfer').attr('class','btn btn-success');
      $('#bayar2').removeAttr('readonly');
      $('#bayar2').val("");
      $('#kembali2').val("");
      $('.non_tunai_grp').show();
      $('#view_tambahan').hide();
    });


    $('#btn_bayar').click(function(){
        $.ajax({
            url : '<?php echo base_url(); ?>apotek/ap_kasir_rajal_c/simpan_pembayaran',
            data : $('#form_pembayaran2').serialize(),
            type : "POST",
            dataType : "json",
            success : function(res){
                $('#btn_tutup').click();
                $('#notif_sukses').click();
                $('#id_pasien').val("");
                $('#id_poli').val("");
                $('#jenis_pembayaran').val("");
                var id_rj = $('#id_rj').val();
                var encodedString = Base64.encode(id_rj);
                get_invoice();
                window.open('<?php echo base_url(); ?>apotek/ap_kasir_rajal_c/struk_pembayaran/'+id_rj, '_blank', 'location=yes,height=570,width=520,scrollbars=yes,status=yes');
            }
        });
    });

    $('#btn_closing_kasir').click(function(){
      $('#popup_closing').click();
    });

    $('#btn_rekap_pendapatan').click(function(){
      data_rekap_pendapatan();
    });

    $('#btn_ya_closing').click(function(){
      simpan_closing();
    });

    $('#btn_show_data_pembayaran').click(function(){
        data_pembayaran();
    });

    $('#btn_tutup').click(function(){
      $('#grandtotal2').val("");
      $('#bayar2').val("");
			$('#kembali2').val("");
			$('#b_tambahan').val("");
			$('#warning_kelebihan').hide();
			$('#view_tambahan').hide();
      $('.non_tunai_grp').hide();
      $('#btn_transfer').attr('class','btn btn-success btn-transparent');
      $('#view_notif_bayar').hide();
    });
});

function data_rekap_pendapatan(){
  $('#popup_rekap_pendapatan').click();
  $.ajax({
    url : '<?php echo base_url(); ?>apotek/ap_kasir_rajal_c/data_rekap_pendapatan',
    type : "POST",
    dataType : "json",
    success : function(result){
      var table = '';
      if(result == null || result == ""){
          table = "<tr><td colspan='7' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
      }else{
          var no = 0;
          for(var i=0; i<result.length; i++){
              no++;
              table += "<tr>"+
                          "<td style='text-align:center;'>"+no+"</td>"+
                          "<td>"+result[i].INVOICE+"</td>"+
                          "<td>"+result[i].NAMA_POLI+"</td>"+
                          "<td>"+result[i].TANGGAL+"</td>"+
                          "<td>"+result[i].TOTAL+"</td>"+
                          "<td>"+result[i].NAMA_PEGAWAI+"</td>"+
                          "<td>"+result[i].SHIFT+"</td>"+
                      "</tr>";
          }
      }
      $('#tabel_rekap_pendapatan tbody').html(table);
    }
  });
}

function data_pembayaran(){
  $('#popup_data_pembayaran').click();
  $.ajax({
    url : '<?php echo base_url(); ?>apotek/ap_kasir_rajal_c/data_pembayaran',
    type : "POST",
    dataType : "json",
    success : function(result){
      var table = '';
      if(result == null || result == ""){
          table = "<tr><td colspan='7' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
      }else{
          var no = 0;
          for(var i=0; i<result.length; i++){
              no++;
              table += "<tr>"+
                          "<td style='text-align:center;'>"+no+"</td>"+
                          "<td style='text-align:center;'>"+result[i].TANGGAL_CLOSING+"</td>"+
                          "<td style='text-align:center;'>"+result[i].INVOICE+"</td>"+
                          "<td style='text-align:center;'>"+formatNumber(result[i].TOTAL)+"</td>"+
                          "<td style='text-align:center;'>"+result[i].NAMA_PEGAWAI+"</td>"+
                          "<td style='text-align:center;'>"+result[i].SHIFT+"</td>"+
                          "<td><button class='btn btn-success btn-sm' type='button' onclick='klik_copy_resep("+result[i].ID_RAJAL+");'><i class='fa fa-print'></i> "+result[i].KODE_RESEP+"</button></td>"+
                          "<td><button class='btn btn-info btn-sm' type='button' onclick='klik_print_poli("+result[i].ID_RAJAL+");'><i class='fa fa-print'></i> Print Poli</button></td>"+
                      "</tr>";
          }
      }
      $('#tabel_pembayaran tbody').html(table);
      // paging_pembayaran();
    }
  });
}

function paging_pembayaran($selector){
    if(typeof $selector == 'undefined')
    {
        $selector = $("#tabel_pembayaran tbody tr");
    }
    window.tp = new Pagination('#tablePaging', {
        itemsCount:$selector.length,
        pageSize : '10',
        onPageSizeChange: function (ps) {
            console.log('changed to ' + ps);
        },
        onPageChange: function (paging) {
            //custom paging logic here
            //console.log(paging);
            var start = paging.pageSize * (paging.currentPage - 1),
                end = start + paging.pageSize,
                $rows = $selector;

            $rows.hide();

            for (var i = start; i < end; i++) {
                $rows.eq(i).show();
            }
        }
    });
}

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
        $('#short_shift').hide();
        $('#long_shift').show();
        $('#btn_closing_kasir').hide();
        snd.pause();
        $('#notif_closing').hide();
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
    console.log(jam);

    if(h >= 7 && h < 14){
        $('.shift_user').html('1');
        $('#shift').val('1');
    }else if(h >= 14 && h < 23){
        $('.shift_user').html('2');
        $('#shift').val('2');
    }else{
        $('.shift_user').html('Tutup');
        $('#shift').val('0');
    }
}

function startNotifClosing() {
    var today = new Date();
    var h = today.getHours();
    var m = today.getMinutes();
    var s = today.getSeconds();
    m = checkTime(m);
    s = checkTime(s);
    document.getElementById('waktu_txt').innerHTML = h + ":" + m + ":" + s;
    $('#waktu').val(h + ":" + m + ":" + s);
    var t = setTimeout(startNotifClosing, 6000);
    var jam = h+':'+m;
    console.log(jam);

    if (jam == '15:42') {
      $('#notif_closing').attr("data-message","<i class='fa fa-warning' style='padding-right:6px'></i> Waktu closing shift 1 kurang 15 menit lagi");
      $('#notif_closing').click();
      $('#btn_suara_closing').click();
    }else if (jam == '13:50') {
      $('#notif_closing').attr("data-message","<i class='fa fa-warning' style='padding-right:6px'></i> Waktu closing shift 1 kurang 10 menit lagi");
      $('#notif_closing').click();
      $('#btn_suara_closing').click();
    }else if (jam == '13:55') {
      $('#notif_closing').attr("data-message","<i class='fa fa-warning' style='padding-right:6px'></i> Waktu closing shift 1 kurang 5 menit lagi");
      $('#notif_closing').click();
      $('#btn_suara_closing').click();
    }else if (jam == '20:45') {
      $('#notif_closing').attr("data-message","<i class='fa fa-warning' style='padding-right:6px'></i> Waktu closing shift 2 kurang 15 menit lagi");
      $('#notif_closing').click();
      $('#btn_suara_closing').click();
    }else if (jam == '20:50') {
      $('#notif_closing').attr("data-message","<i class='fa fa-warning' style='padding-right:6px'></i> Waktu closing shift 2 kurang 10 menit lagi");
      $('#notif_closing').click();
      $('#btn_suara_closing').click();
    }else if (jam == '20:55') {
      $('#notif_closing').attr("data-message","<i class='fa fa-warning' style='padding-right:6px'></i> Waktu closing shift 2 kurang 5 menit lagi");
      $('#notif_closing').click();
      $('#btn_suara_closing').click();
    }

    if (((parseInt(h) >= 13 || parseInt(m) >= 45)) && ((parseInt(h) < 14 || parseInt(m) < 00))) {
      $('#short_shift').show();
      $('#long_shift').hide();
      $('#btn_closing_kasir').show();
    }else if (((parseInt(h) >= 20 || parseInt(m) >= 45)) && ((parseInt(h) < 21 || parseInt(m) < 00))) {
      $('#short_shift').show();
      $('#long_shift').hide();
      $('#btn_closing_kasir').show();
    }
}

function checkTime(i) {
    if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
    return i;
}

function cetak_resi(){
    var invoice = $('#invoice').val();
    var prt = window.open('<?php echo base_url(); ?>apotek/ap_kasir_rajal_c/struk/'+invoice, '_blank');
    prt.print();
}

function get_invoice(){
    $.ajax({
        url : '<?php echo base_url(); ?>apotek/ap_kasir_rajal_c/get_invoice',
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
        url : '<?php echo base_url(); ?>apotek/ap_kasir_rajal_c/get_pasien',
        data : {keyword:keyword},
        type : "GET",
        dataType : "json",
        success : function(result){
            $tr = "";

            if(result == null || result == ""){
                $tr = "<tr><td colspan='7' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
            }else{
                var no = 0;
                for(var i=0; i<result.length; i++){
                    no++;
                    var aksi = '';
                    if(result[i].STS_BAYAR == '0'){
                        aksi = "<button class='btn btn-info' type='button' onclick='klik_pasien("+result[i].ID+","+result[i].ID_PASIEN+","+result[i].TOTAL+");'>"+formatNumber(result[i].TOTAL)+"</button>";
                    }else{
                        aksi = '<i class="fa fa-check-square-o"></i> '+formatNumber(result[i].TOTAL);
                    }

                    var not_pol = '';
                    if(result[i].STS_BAYAR == '0'){
                        not_pol = "Nota Poli Kosong";
                    }else{
                        not_pol = "<button class='btn btn-info' type='button' onclick='klik_print_poli("+result[i].ID+");'><i class='fa fa-print'></i> Print Poli</button>";
                    }

                    var status_bayar = '';
                    if(result[i].STS_BAYAR == '0'){
                        status_bayar = "<span class='label label-danger'><b>BL</b></span>";
                    }else{
                        status_bayar = "<span class='label label-success'><b>L</b></span>";
                    }

                    var kode_resep = '';
                    if (result[i].KODE_RESEP == null || result[i].KODE_RESEP == '') {
                      kode_resep = "Kode Resep Kosong"
                    }else {
                      kode_resep = "<button class='btn btn-success' type='button' onclick='klik_copy_resep("+result[i].ID+");'><i class='fa fa-print'></i> "+result[i].KODE_RESEP+"</button>"
                    }

                    $tr += "<tr>"+
                                "<td style='text-align:center;'><input type='hidden' value='"+result[i].ID_KASIR_RAJAL+"' name='id_rajal_hidden[]'>"+no+"</td>"+
                                "<td style='text-align:center;'>"+result[i].TANGGAL+"</td>"+
                                "<td>"+result[i].NAMA+" "+status_bayar+"</td>"+
                                "<td style='text-align:center;'>"+result[i].NAMA_POLI+"</td>"+
                                "<td align='center'>"+kode_resep+"</td>"+
                                "<td align='center'>"+not_pol+"</td>"+
                                "<td style='text-align:right;'>"+aksi+"</td>"+
                            "</tr>";
                }
            }

            $('#tabel_pasien tbody').html($tr);
        }
    });

    $('#cari_nama_menu').off('keyup').keyup(function(){
        get_pasien();
    });
}

function klik_copy_resep(id){
          // var encodedString = Base64.encode(id);
          window.open('<?php echo base_url(); ?>apotek/ap_kasir_rajal_c/struk_resep/'+id, '_blank', 'location=yes,height=570,width=520,scrollbars=yes,status=yes');
}

function klik_print_poli(id){
          // var encodedString = Base64.encode(id);
          window.open('<?php echo base_url(); ?>apotek/ap_kasir_rajal_c/nota_poli/'+id, '_blank', 'location=yes,height=570,width=520,scrollbars=yes,status=yes');
}

function klik_pasien(id,id_pasien,total){
    $('#popup_bayar').click();

    $.ajax({
        url : '<?php echo base_url(); ?>apotek/ap_kasir_rajal_c/get_poli_by_rj',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(row){
            $('#id_rj').val(id);
            $('#id_pasien').val(id_pasien);
            $('#id_poli').val(row['ID_POLI']);
            $('#nama_pasien_txt').html(row['NAMA_PASIEN']);
            $('#biaya_poli').val(formatNumber(row['BIAYA']));
            $('#grandtotal2').val(formatNumber(total));

            get_tindakan(id_pasien);
            get_resep(id_pasien);
            get_laborat(id_pasien);
        }
    });
}

function get_resep(id_pasien){
    $.ajax({
        url : '<?php echo base_url(); ?>apotek/ap_kasir_rajal_c/get_resep2',
        data : {id_pasien:id_pasien},
        type : "POST",
        dataType : "json",
        success : function(res){
            $tr = '';
            $tr2 = '';
            var total_resep = 0;

            if(res['ind'] == null || res['ind'] == ""){
                $tr = '<tr><td colspan="4" style="text-align:center;">Data Tidak Ada</td></tr>';
            }else{
                $tr = '<tr>'+
                        '<td></td>'+
                        '<td>'+res['ind']['KODE_RESEP']+'</td>'+
                        '<td style="text-align:center;">'+res['ind']['TANGGAL']+'</td>'+
                        '<td style="text-align:right;"><b id="total_hitung">'+formatNumber(res['ind']['TOTAL'])+'</b></td>'+
                      '</tr>'+
                      '<tr class="info">'+
                        '<td style="text-align:center;">&nbsp;</td>'+
                        '<td style="text-align:center; font-weight:bold;">NAMA OBAT</td>'+
                        '<td style="text-align:center; font-weight:bold;">JUMLAH</td>'+
                        '<td style="text-align:center;">&nbsp;</td>'+
                      '</tr>';

                for(var i=0; i<res['det'].length; i++){
                    total_resep += parseFloat(res['det'][i].SUBTOTAL);
                    // <input type='text' value='"+result[i].ID_KASIR_RAJAL+"' name='id_rajal_hidden[]'>
                    $tr2 += '<tr>'+
                                '<td>'+
                                '<input type="checkbox" class="form-control" id="cb_resep_'+res['det'][i].ID+'" name="check_total[]" value="'+res['det'][i].SUBTOTAL+'" onclick="hitung_total_resep('+res['det'][i].ID+');" checked>'+
                                '</td>'+
                                '<td>'+res['det'][i].NAMA_OBAT+'</td>'+
                                '<td style="text-align:center;">'+formatNumber(res['det'][i].JUMLAH_BELI)+'</td>'+
                                '<td style="text-align:right;">'+formatNumber(res['det'][i].SUBTOTAL)+'</td>'+
                            '</tr>';
                }

                $('#biaya_resep').val(formatNumber(total_resep));
            }

            $('#tabel_resep2 tbody').html($tr+$tr2);
        }
    });
}

function get_laborat(id_pasien){
    $.ajax({
        url : '<?php echo base_url(); ?>apotek/ap_kasir_rajal_c/get_laborat',
        data : {id_pasien:id_pasien},
        type : "POST",
        dataType : "json",
        success : function(res){
            $tr = '';
            $tr2 = '';
            var total_resep = 0;

            if(res['ind'] == null || res['ind'] == ""){
                $tr = '<tr><td colspan="4" style="text-align:center;">Data Tidak Ada</td></tr>';
            }else{
                $tr = '<tr>'+
                        '<td>'+res['ind']['KODE_LAB']+'</td>'+
                        '<td style="text-align:center;">'+res['ind']['TANGGAL']+'</td>'+
                        '<td style="text-align:right;"><b id="total_hitung">'+formatNumber(res['ind']['TOTAL_TARIF'])+'</b></td>'+
                      '</tr>'+
                      '<tr class="warning">'+
                        '<td colspan="2" style="text-align:center; font-weight:bold;">NAMA PEMERIKSAAN</td>'+
                        '<td style="text-align:center; font-weight:bold;">TARIF</td>'+
                      '</tr>';

                for(var i=0; i<res['det'].length; i++){
                    total_resep += parseFloat(res['det'][i].TARIF);
                    $tr2 += '<tr>'+
                                '<td colspan="2">'+res['det'][i].NAMA_PEMERIKSAAN+'</td>'+
                                '<td style="text-align:right;">'+formatNumber(res['det'][i].TARIF)+'</td>'+
                            '</tr>';
                }

                $('#biaya_lab').val(formatNumber(total_resep));
            }

            $('#tabel_laborat tbody').html($tr+$tr2);
        }
    });
}

function hitung_total_resep(id){
 var id_check = $("input[name='check_total[]']:checked").length;
 var val_check = $('#cb_resep_'+id).val();
 var tot = 0;


  $("input[name='check_total[]']:checked").each(function(idx,elm){
    if (id_check != 0) {
      tot += parseInt(elm.value, 10);
    }
  });
 $('#total_hitung').html(formatNumber(tot));
 $('#biaya_resep').val(formatNumber(tot));

 var biaya_poli = $('#biaya_poli').val();
 biaya_poli = biaya_poli.split(',').join('');
 var biaya_tindakan = $('#biaya_tindakan').val();
 biaya_tindakan = biaya_tindakan.split(',').join('');
 var biaya_resep = $('#biaya_resep').val();
 biaya_resep = biaya_resep.split(',').join('');
 var biaya_lab = $('#biaya_lab').val();
 biaya_lab = biaya_lab.split(',').join('');

 var tambah_setiap_biaya = parseFloat(biaya_poli) + parseFloat(biaya_tindakan) + parseFloat(biaya_resep) + parseFloat(biaya_lab);
 $('#grandtotal2').val(tambah_setiap_biaya);
}

function get_tindakan(id_pasien){
    $.ajax({
        url : '<?php echo base_url(); ?>apotek/ap_kasir_rajal_c/get_tindakan',
        data : {id_pasien:id_pasien},
        type : "POST",
        dataType : "json",
        success : function(res){
            $tr = '';
            $tr2 = '';
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

                $tr2 = '<tr>'+
                        '   <td colspan="2" style="text-align:center;"><b>TOTAL</b></td>'+
                        '   <td style="text-align:right;"><b>'+formatNumber(tot)+'</b></td>'+
                        '</tr>';

                $('#biaya_tindakan').val(formatNumber(tot));
            }

            $('#tabel_tindakan tbody').html($tr+$tr2);
        }
    });
}

function get_bayar(){
    var grandtotal = $('#grandtotal2').val();
    var bayar = $('#bayar2').val();
    grandtotal = grandtotal.split(',').join('');
    bayar = bayar.split(',').join('');

    if(bayar == ""){
        bayar = 0;
    }

    var jenis_bayar = $('#jenis_pembayaran').val();

    if(parseFloat(bayar) < parseFloat(grandtotal)){
        var kembali = parseFloat(bayar) - parseFloat(grandtotal);
        var s = parseFloat(grandtotal) - parseFloat(bayar);
        $('#text_notif').html('Pembayaran kurang ');
        $('#text_total_notif').html(formatNumber(kembali));
        $('#kembali2').val(formatNumber(kembali));
        $('#asd').val(s);
        $('#view_notif_bayar').show();
        $('#btn_bayar').attr('disabled','disabled');

        if (jenis_bayar == 'Non Tunai') {
            $('#view_tambahan').show();
        }else {
            $('#view_tambahan').hide();
        }
    }else{
        var kembali = parseFloat(bayar) - parseFloat(grandtotal);
        $('#kembali2').val(formatNumber(kembali));
        $('#view_notif_bayar').hide();
        $('#btn_bayar').removeAttr('disabled');
        $('#view_tambahan').hide();
    }
}

function hitung_tambahan(){
    var tambah = $('#b_tambahan').val();
    tambah = tambah.split(',').join('');

    var sisa = $('#asd').val();
    sisa = sisa.split(',').join('');

    console.log(sisa);

    var kembali = parseFloat(tambah) - parseFloat(sisa);

    if(tambah == ""){
        kembali = "";
        $('#text_total_notif').html(formatNumber(sisa));
    }else if(kembali < 0){
        kembali = "";
        $('#view_notif_bayar').show();
        var s = parseFloat(sisa) - parseFloat(tambah);
        $('#text_notif').html('Pembayaran kurang ');
        $('#text_total_notif').html(formatNumber(s));
        $('#kembali2').val(formatNumber(s));
        $('#btn_bayar').attr('disabled','disabled');
    }else{
        var kembali_2 = parseFloat(tambah) - parseFloat(sisa);
        $('#kembali2').val(NumberToMoney(kembali_2));
        $('#view_notif_bayar').hide();
        $('#btn_bayar').removeAttr('disabled');
    }
}

function popup_pesanan(id){
    $('#popup_pesanan_btn').click();

    $.ajax({
        url : '<?php echo base_url(); ?>apotek/ap_kasir_rajal_c/data_obat_id',
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

    var jenis_bayar = $('#jenis_pembayaran').val();

    alert(jenis_bayar);

    if(byr == "") {
        kembali = "";
    }else if(kembali < 0){
        kembali = "";
        $('#warning_kelebihan').show();
        var s = parseFloat(total) - parseFloat(byr);
        $('#jumlah_bayar').html(formatNumber(s));
        $('#btn-proses-byr').attr('disabled','disabled');
		if (jenis_bayar == 'Non Tunai') {
				$('#view_tambahan').show();
		}else {
				$('#view_tambahan').hide();
		}
		}else{
        $('#warning_kelebihan').hide();
        $('#btn-proses-byr').removeAttr('disabled');
				$('#view_tambahan').hide();
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
            url : '<?=base_url();?>apotek/ap_kasir_rajal_c/simpan_trx',
            data : $('#form_pembayaran').serialize(),
            type : "POST",
            dataType : "json",
            success : function(result){
                $('#modal-11').removeClass('md-show');
                $('#popup_pembayaran').fadeOut();
                cetak_resi();
                window.location = "<?php echo base_url(); ?>apotek/ap_kasir_rajal_c";
            }
        });
    }
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

function get_filter_semua(){
  document.getElementById("btn_filter_semua").className = "btn btn-success";
  document.getElementById("btn_filter_tanggal").className = "btn btn-default";
  document.getElementById("btn_filter_poli").className = "btn btn-default";
  $('#form_tanggal_filter').hide();
  $('#form_poli_filter').hide();

  $('#hidden_filter_semua').removeAttr('disabled');
  $('#hidden_filter_tanggal').attr('disabled','disabled');
  $('#hidden_filter_poli').attr('disabled','disabled');
  semua_filter();
}

function get_filter_tanggal(){
  document.getElementById("btn_filter_tanggal").className = "btn btn-success";
  document.getElementById("btn_filter_semua").className = "btn btn-default";
  document.getElementById("btn_filter_poli").className = "btn btn-default";
  $('#form_tanggal_filter').show();
  $('#form_poli_filter').hide();

  $('#hidden_filter_tanggal').removeAttr('disabled');
  $('#hidden_filter_semua').attr('disabled','disabled');
  $('#hidden_filter_poli').attr('disabled','disabled');
}

function get_filter_poli(){
  document.getElementById("btn_filter_poli").className = "btn btn-success";
  document.getElementById("btn_filter_semua").className = "btn btn-default";
  document.getElementById("btn_filter_tanggal").className = "btn btn-default";
  $('#form_tanggal_filter').hide();
  $('#form_poli_filter').show();

  $('#hidden_filter_poli').removeAttr('disabled');
  $('#hidden_filter_semua').attr('disabled','disabled');
  $('#hidden_filter_tanggal').attr('disabled','disabled');
}

function tanggal_filter(){
  var tanggal_sekarang = $('#tanggal_sekarang').val();
  var tanggal_sampai = $('#tanggal_sampai').val();
  $.ajax({
    url : '<?php echo base_url(); ?>apotek/ap_kasir_rajal_c/tanggal_filter',
    data : {
      tanggal_sekarang:tanggal_sekarang,
      tanggal_sampai:tanggal_sampai
    },
    type : "POST",
    dataType : "json",
    success : function(result){
      var table = '';
      if(result == null || result == ""){
          table = "<tr><td colspan='7' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
      }else{
          var no = 0;
          for(var i=0; i<result.length; i++){
              no++;
              table += "<tr>"+
                          "<td style='text-align:center;'>"+no+"</td>"+
                          "<td>"+result[i].INVOICE+"</td>"+
                          "<td>"+result[i].NAMA_POLI+"</td>"+
                          "<td>"+result[i].TANGGAL+"</td>"+
                          "<td>"+result[i].TOTAL+"</td>"+
                          "<td>"+result[i].NAMA_PEGAWAI+"</td>"+
                          "<td>"+result[i].SHIFT+"</td>"+
                      "</tr>";
          }
      }
      $('#tabel_rekap_pendapatan tbody').html(table);
    }
  });
}

function poli_filter(){
  var result_poli = $('#result_poli').val();
  $.ajax({
    url : '<?php echo base_url(); ?>apotek/ap_kasir_rajal_c/poli_filter',
    data : {
      result_poli:result_poli,
    },
    type : "POST",
    dataType : "json",
    success : function(result){
      var table = '';
      if(result == null || result == ""){
          table = "<tr><td colspan='7' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
      }else{
          var no = 0;
          for(var i=0; i<result.length; i++){
              no++;
              table += "<tr>"+
                          "<td style='text-align:center;'>"+no+"</td>"+
                          "<td>"+result[i].INVOICE+"</td>"+
                          "<td>"+result[i].NAMA_POLI+"</td>"+
                          "<td>"+result[i].TANGGAL+"</td>"+
                          "<td>"+result[i].TOTAL+"</td>"+
                          "<td>"+result[i].NAMA_PEGAWAI+"</td>"+
                          "<td>"+result[i].SHIFT+"</td>"+
                      "</tr>";
          }
      }
      $('#tabel_rekap_pendapatan tbody').html(table);
    }
  });
}

function semua_filter(){
  $.ajax({
    url : '<?php echo base_url(); ?>apotek/ap_kasir_rajal_c/data_rekap_pendapatan',
    type : "POST",
    dataType : "json",
    success : function(result){
      var table = '';
      if(result == null || result == ""){
          table = "<tr><td colspan='7' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
      }else{
          var no = 0;
          for(var i=0; i<result.length; i++){
              no++;
              table += "<tr>"+
                          "<td style='text-align:center;'>"+no+"</td>"+
                          "<td>"+result[i].INVOICE+"</td>"+
                          "<td>"+result[i].NAMA_POLI+"</td>"+
                          "<td>"+result[i].TANGGAL+"</td>"+
                          "<td>"+result[i].TOTAL+"</td>"+
                          "<td>"+result[i].NAMA_PEGAWAI+"</td>"+
                          "<td>"+result[i].SHIFT+"</td>"+
                      "</tr>";
          }
      }
      $('#tabel_rekap_pendapatan tbody').html(table);
    }
  });
}

function deleteRow(btn){
    var row = btn.parentNode.parentNode;
    row.parentNode.removeChild(row);
}
</script>


</body>

</html>

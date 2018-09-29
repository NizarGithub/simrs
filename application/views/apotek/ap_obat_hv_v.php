<?PHP
$sess_user = $this->session->userdata('masuk_rs');
$id_user = $sess_user['id'];  //ID PEGAWAI
$shift = $sess_user['shift'];

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
<title>Kasir AA</title>
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
<script type="text/javascript" src="<?php echo base_url(); ?>js-devan/jquery-1.11.1.min.js"></script>
<style type="text/css">
#tombol_reset{
	display: none;
}
#popup_load{
	display: none;
}

#msg_kosong,
#view_non_tunai,
#view_notif_bayar,
#notif_sukses,
#view_tambahan{
    display: none;
}

#btn_closing_kasir{
	display: none;
	cursor: pointer;
}
</style>
</head>
<!-- <div id="popup_load">
    <div class="window_load">
        <img src="<?=base_url()?>picture/progress.gif" height="100" width="125">
    </div>
</div> -->
<body data-page="medias" onload="startTime(); startNotifClosing();">
    <!-- BEGIN TOP MENU -->
    <input type="hidden" id="sts_edit" value="0" />
    <input type="hidden" id="sts_lunas" value="0" />
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation" style="background-color: #F9A825; color: white;">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#sidebar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a id="menu-medium" class="sidebar-toggle toggle_fullscreen tooltips" style="color: white; border-right: 1px solid #ffffff;">
                    <i class="glyphicon glyphicon-fullscreen"></i>
                </a>
            </div>
            <div class="navbar-center" style="color: white;"> Entry Penjualan HV / OTC</div>
            <div class="navbar-collapse collapse">
                <!-- BEGIN TOP NAVIGATION MENU -->
                <!-- <ul class="nav navbar-nav pull-right header-menu">
                    <li style="margin-right: 5px;">
                        <button onclick="$('#modal-12').addClass('md-show');" style="margin-top: 6px;" class="btn btn-warning btn-sm" type="button"> <i class="fa fa-question-circle"></i> Bantuan </button>
                    </li>
                </ul> -->
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
                <button type="button" style="display:none;" id="notif_sukses" class="btn btn-success notification" data-type="success" data-message="<i class='fa fa-check-square-o' style='padding-right:6px'></i> Transaksi sukses dilakukan!" data-horiz-pos="left" data-verti-pos="bottom">Success</button>

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

										<div class="col-sm-6">
												<div class="panel panel-default">
														<div class="panel-heading clearfix pos-rel">
															<div class="pos-abs top-12 l-15 f-18 c-gray"><i class="fa fa-table"></i></div>
															<h2 class="panel-title width-100p c-red text-center w-500 f-20 carrois">Data Obat</h2>
														</div>
														<div class="panel-body messages">
															<div class="row">
																<div class="col-md-12 col-sm-12 col-xs-12">
																	<div class="scroll-y" style="height: 477px;" id="tabel_obat">

																	</div>
																</div>
															</div>
														</div>
												</div>
										</div>

									<!-- <form class="" id="form_pembayaran"> -->
										<div class="col-sm-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <!-- <h3 class="panel-title">Striped rows <strong>Table</strong></h3> -->
                                <span style="padding-bottom: 6px; padding-top: 6px;" class="label label-success" style="">
                                    Invoice : #<b id="invoice_txt"></b>
                                </span>
                                <button type="button" class="btn btn-danger btn-sm" onclick="window.location='<?=base_url();?>apotek/ap_obat_hv_c';">Reset</button>
                            </div>
                            <div class="panel-body messages">
															<form id="form_pembayaran">
																<input type="hidden" name="id_pegawai" id="id_pegawai" value="<?php echo $id_user; ?>">
																<input type="hidden" name="shift" id="shift" value="<?php echo $shift; ?>">
																<input type="hidden" name="invoice" id="invoice" value="">
																<input type="hidden" name="total_tagihan" id="total_tagihan">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="withScroll" data-height="432">
                                            <table class="table table-bordered" id="tabel_keranjang">
                                                <thead>
                                                    <tr class="warning">
                                                        <th style="text-align: center;">No</th>
																												<th style="text-align: center;">Barcode</th>
                                                        <th style="text-align: center;">Nama Obat</th>
                                                        <th style="text-align: center;" width="100">Jumlah Beli</th>
                                                        <th style="text-align: center;">Total</th>
																												<th style="text-align: center;">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                                <tfoot>
                                                    <tr class="active">
                                                        <td colspan="4" style="text-align: center; font-weight: bold;">Total Biaya</td>
                                                        <td style="text-align: right;"><b id="tot_biaya_keranjang">0</b></td>
																												<td></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-md-12 p-t-10">
                                        <button class="btn btn-primary" type="button" id="btn_klik_proses" style="width: 100%;" disabled='disabled'>
                                            Proses <i class="fa fa-arrow-circle-right"></i>
                                        </button>
                                    </div>
                                </div>

															</form>
                            </div>
                        </div>
                    </div>
								<!-- </form> -->

                    <!-- <div class="col-sm-1" style="width: 2%;">   </div> -->

                    <div id="panel_kanan" class="col-sm-12">
											<div class="panel panel-default">
												<div class="panel-body">
	                        <div class="row">
														<div class="col-md-12">
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
		                                      <h4><strong>Shift <b class="shift_user"><?php echo $shift; ?></b></strong></h4>
		                                      <p><?=$user_detail->NAMA;?></p>
		                                  </div>
		                              </div>
		                          </div>

															<!-- <div class="col-lg-2 col-md-2" id="btn_rekap_penjualan" style="cursor: pointer;">
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
																					<h4><strong>Rekap Penjualan</strong></h4>
																					<p>Hari, Bulan Dan Tahun</p>
																			</div>
																	</div>
															</div> -->

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
																					<h4><strong>Penjualan</strong></h4>
																					<p>Penjualan HV / OTC</p>
																			</div>
																	</div>
															</div>

															<div class="col-lg-2 col-md-2">
																	<div class="panel panel-icon no-bd hover-effect" style="background-color: #F57F17; color: #fff;">
																			<div class="panel-body" style="background-color: #F9A825;">
																					<div class="row">
																							<div class="col-md-12">
																									<div class="icon"><i class="fa fa-calendar"></i>
																									</div>
																							</div>
																					</div>
																			</div>
																			<div class="panel-footer" style="background-color: #F57F17;">
																				<input type="hidden" id="date_now" value="<?php echo date('d-m-Y'); ?>">
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
	                                <div class="panel panel-icon no-bd bg-red hover-effect">
	                                    <div class="panel-body bg-red">
	                                        <div class="row">
	                                            <div class="col-md-12">
	                                                <div class="icon"><i class="fa fa-clock-o"></i>
	                                                </div>
	                                            </div>
	                                        </div>
	                                    </div>
	                                    <div class="panel-footer bg-red">
	                                        <h4><strong id="waktu_txt">00.00</strong></h4>
	                                        <p>Waktu</p>
	                                    </div>
	                                </div>
	                            	</div>

																<div class="col-lg-2 col-md-2" onclick="$('#modal-12').addClass('md-show');" style="cursor: pointer;">
		                                <div class="panel panel-icon no-bd bg-purple hover-effect">
		                                    <div class="panel-body bg-purple">
		                                        <div class="row">
		                                            <div class="col-md-12">
		                                                <div class="icon"><i class="fa fa-question-circle"></i>
		                                                </div>
		                                            </div>
		                                        </div>
		                                    </div>
		                                    <div class="panel-footer bg-purple">
		                                        <h4><strong>Bantuan</strong></h4>
		                                        <p>Bantuan Pengguna</p>
		                                    </div>
		                                </div>
		                            </div>

															<a href="<?=base_url();?>apotek/ap_portal_kasir_aa_c">
																<div class="col-lg-2 col-md-2" id="btn_logout">
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
																						<h4><strong>Portal</strong></h4>
																						<p>Kembali Ke Portal</p>
																				</div>
																		</div>
																</div>
																</a>

													</div>
												</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END WRAPPER -->

    <div class="md-modal md-effect-10" id="modal-12">
        <div class="md-content md-content-white">
            <h3>Bantuan Penggunaan</h3>
            <div>
                <p>Cara penggunaan shortcut keys :</p>
                <ul>
                    <li><strong>F1:</strong> Tampilkan Bantuan </li>
                    <!-- <li><strong>F2:</strong> Pencarian obat berdasarkan Nama Obat</li> -->
                    <!-- <li><strong>F3:</strong> Menampilkan data resi yang tersimpan </li>
                    <li><strong>F4:</strong> Simpan Resi </li> -->
                    <!-- <li><strong>F5:</strong> Proses Pembayaran </li> -->
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

      <button class="btn btn-danger" data-toggle="modal" id="popup_rekap_penjualan" style="display:none;" data-target="#modal-large2">Show me</button>
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
                                <button type="button" id="btn_filter_bulan" onclick="get_filter_bulan();" style="float: left; margin-left: 1%;" class="btn btn-default">Bulan</button>
                              </div>
                              <input type="hidden" name="by" id="hidden_filter_semua" value="semua">
                              <input type="hidden" name="by" id="hidden_filter_tanggal" value="tanggal" disabled>
                              <input type="hidden" name="by" id="hidden_filter_bulan" value="bulan" disabled>
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
                              <div id="form_bulan_filter" style="display: none;">
                                <div class="col-md-4">
                                  <select class="form-control" name="bulan" id="result_bulan">
																		<option value="01">Januari</option>
							                      <option value="02">Februari</option>
							                      <option value="03">Maret</option>
							                      <option value="04">April</option>
							                      <option value="05">Mei</option>
							                      <option value="06">Juni</option>
							                      <option value="07">Juli</option>
							                      <option value="08">Agustus</option>
							                      <option value="09">September</option>
							                      <option value="10">Oktober</option>
							                      <option value="11">November</option>
							                      <option value="12">Desember</option>
                                  </select>
                                </div>
                                <div class="col-md-1">
                                  <button type="button" class="btn btn-info" onclick="bulan_filter();" name="button"><i class="fa fa-search"></i> Cari</button>
                                </div>
                              </div>
                              <div class="col-md-12">
                                <table class="table" id="tabel_rekap_penjualan">
                                  <thead>
                                    <tr class="info">
                                      <th>No</th>
                                      <th>Invoice</th>
                                      <th>Nama Obat</th>
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

		<button type="button" data-type="success" class="btn btn-success notification" id="notif_berhasil" style="display:none;" data-message="<i class='fa fa-check-square-o' style='padding-right:6px'></i> Data berhasil diproses!" data-horiz-pos="left" data-verti-pos="bottom">Success</button>

    <button type="button" onclick="snd.play();" id="btn_suara_closing" style="display:none;" name="button">aaa</button>

		<button class="btn btn-danger" data-toggle="modal" id="popup_konfirmasi" style="display:none;" data-target="#modal-basic2">Show me</button>
    <div class="modal fade" id="modal-basic2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #EBEBEB; color: #2B2B2B;">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <center><h3 class="modal-title" id="myModalLabel"><strong>Konfirmasi</strong></h3></center>
                        </div>
                        <div class="modal-body">
                            <h4>Apakah anda ingin melakukan proses?</h4>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btn_ya_proses" class="btn btn-success">Ya</button>
                            <button type="button" class="btn btn-danger" id="btn_tidak_proses" data-dismiss="modal">Tidak</button>
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
var ajax = "";
var snd = new Audio("<?php echo base_url(); ?>sound/Google_Event-1.mp3"); // buffers automatically when created
$(document).ready(function(){
	data_obat();

	get_invoice();

	// get_kode_trx();

	data_keranjang();

	// $('#jumlah_tampil').change(function(){
  //       data_obat();
  //   });
	$('#btn_klik_proses').click(function(){
		$('#popup_konfirmasi').click();
	});

	$('#btn_ya_proses').click(function(){
		simpan_proses();
	});

	$('.non_tunai_grp').hide();

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

	$('#btn-batal-byr').click(function(){
			$('#b_bayar').val("");
			$('#b_kembali').val("");
			$('#b_tambahan').val("");
			$('#warning_kelebihan').hide();
			$('#view_tambahan').hide();
	});

	$('#btn-proses-byr').click(function(){
		simpan_pembayaran();
	});

	$('#btn_ya_closing').click(function(){
		simpan_closing();
	});

	$('#btn_closing_kasir').click(function(){
		$('#popup_closing').click();
	});

	$('#btn_rekap_penjualan').click(function(){
		data_rekap_penjualan();
	});
});

function simpan_proses(){
	$.ajax({
		url : '<?php echo base_url(); ?>apotek/ap_obat_hv_c/simpan_proses',
		data : $('#form_pembayaran').serialize(),
		type : "POST",
		dataType : "json",
		success : function(row){
			var id = row.ID;
			// window.open('<?php echo base_url(); ?>apotek/ap_obat_hv_c/cetak/'+id, '_blank', 'location=yes,height=700,width=600,scrollbars=yes,status=yes');
			$('#btn_tidak_proses').click();
			$('#notif_berhasil').click();
			data_keranjang();
			data_obat();
			$('#btn_klik_proses').attr('disabled','disabled');
		}
	});
}

function simpan_closing(){
    $.ajax({
      url : '<?php echo base_url(); ?>apotek/ap_kasir_aa_c/simpan_closing',
      type : "POST",
      dataType : "json",
      success : function(){
        $('#btn_tutup_closing').click();
        $('#btn_closing_kasir').hide();
        $('#notif_closing').hide();
				snd.pause();
				$('#btn_logout').click();
      }
    });
}

function data_obat(){
	var keyword = $('#cari_nama_menu').val();
	var tanggal_sekarang = $('#date_now').val();
	$.ajax({
			url : '<?php echo base_url(); ?>apotek/ap_obat_hv_c/data_obat',
			data : {keyword:keyword},
			type : "GET",
			dataType : "json",
			success : function(result){
					$tr = "";

					if(result == null || result == ""){
							$tr =   '<a href="javascript:;" class="message-item media">'+
												'<div class="media">'+
													'<img src="<?php echo base_url(); ?>picture/forbidden.png" width="50" class="pull-left">'+
													'<div class="media-body">'+
													'<h5 class="c-dark"><strong>Perhatian!</strong></h5>'+
													'<h4 class="c-dark">Data Tidak Ditemukan</h4>'+
													'</div>'+
												'</div>'+
											'</a>';
					}else{
							for(var i=0; i<result.length; i++){
                var status_obat = '';
                if (result[i].STATUS_OBAT == '0') {
                  status_obat = 'Obat Umum';
                }else {
                  status_obat = 'Obat Racik';
                }

								var kadaluarsa = result[i].EXPIRED;
								var balik_hari_kadaluarsa = kadaluarsa.substr(1,1);
								var balik_bulan_kadaluarsa = kadaluarsa.substr(4,1);
								var balik_tahun_kadaluarsa = kadaluarsa.substr(6,4);
								var satuan_tanggal_kadaluarsa = balik_tahun_kadaluarsa+'-'+balik_bulan_kadaluarsa+'-'+balik_hari_kadaluarsa;

								var tanggal = new Date();
								var tahun = tanggal.getFullYear();
								var bulan = tanggal.getMonth() + 1;
								var hari = tanggal.getDate();

								var satuan_tanggal_sekarang = tahun+'-'+bulan+'-'+hari;

								var kadal = '';
								if (new Date(satuan_tanggal_sekarang) >= new Date(satuan_tanggal_kadaluarsa)) {
									kadal = '<small class="pull-right" style="color: red;">Exp : '+result[i].EXPIRED+'</small>';
								}else {
									kadal = '<small class="pull-right" style="color: black;">Exp : '+result[i].EXPIRED+'</small>';
								}

								var stok_habis = '';
								var stok = result[i].TOTAL_STOK_OBAT;
								if (stok < 10 || stok == 10) {
									stok_habis = '<h4 class="c-red">'+result[i].TOTAL_STOK_OBAT+'</h4>';
								}else {
									stok_habis = '<h4 class="c-dark">'+result[i].TOTAL_STOK_OBAT+'</h4>';
								}

									$tr +=  '<a href="javascript:;" class="message-item media" onclick="klik_obat('+result[i].ID+', '+result[i].TOTAL_JUAL+', '+result[i].SERVICE+');">'+
														'<div class="media">'+
															'<img src="<?php echo base_url(); ?>picture/pills.png" width="50" class="pull-left">'+
																'<div class="media-body">'+
                                ''+kadal+''+
																'<div class="col-md-3">'+
																	'<h5 class="c-dark"><strong>'+result[i].BARCODE+'</strong></h5>'+
																	'<h4 class="c-dark">'+result[i].NAMA_OBAT+'</h4>'+
																'</div>'+
                                '<div class="col-md-2">'+
																		'<h5 class="c-dark"><strong>Status</strong></h5>'+
																		'<h4 class="c-dark">'+status_obat+'</h4>'+
																'</div>'+
																'<div class="col-md-2">'+
																		'<h5 class="c-dark"><strong>Harga</strong></h5>'+
																		'<h4 class="c-dark">'+formatNumber(result[i].TOTAL_JUAL)+'</h4>'+
																'</div>'+
																'<div class="col-md-2">'+
																			'<h5 class="c-dark"><strong>Stok</strong></h5>'+
																			''+stok_habis+''+
																'</div>'+
															'</div>'+
														'</div>'+
													'</a>';
							}
					}
					$('#tabel_obat').html($tr);
			}
	});
	$('#cari_nama_menu').off('keyup').keyup(function(){
			data_obat();
	});
}

function klik_obat(id, harga_beli, service){
	$.ajax({
		url : '<?php echo base_url(); ?>apotek/ap_obat_hv_c/simpan_keranjang',
		data : {
						id:id,
						harga_beli:harga_beli,
						service:service
					},
		type : "POST",
		dataType : "json",
		success: function(res){
			data_keranjang();
			$('#btn_klik_proses').removeAttr('disabled');
		}
	});
}

function data_keranjang(){
	$.ajax({
			url : '<?php echo base_url(); ?>apotek/ap_obat_hv_c/data_keranjang',
			type : "POST",
			dataType : "json",
			success : function(res){
					$tr = '';
					var tot = 0;
					if(res == null || res == ""){
							$tr = '<tr><td colspan="6" style="text-align:center;">Data Tidak Ada</td></tr>';
					}else{
							var no = 0;
							for(var i=0; i<res.length; i++){
									no++;
									var total = $('.total_keranjang').val();
									tot += parseFloat(res[i].HARGA_OBAT);

									$tr += '<tr>'+
													'<td style="text-align:center;"><input type="hidden" name="id_gudang_obat[]" value="'+res[i].ID_GUDANG_OBAT+'">'+no+'</td>'+
													'<td style="text-align:center;">'+res[i].BARCODE+'</td>'+
													'<td style="text-align:center;">'+res[i].NAMA_OBAT+'</td>'+
													'<td><input type="text" id="jumlah_beli_'+res[i].ID+'" onkeyup="hitung_jumlah_total('+res[i].ID+'); get_grand_total();" value="1" name="jumlah_beli[]" class="form-control"></td>'+
													'<td style="text-align:right;" id="harga_beli_'+res[i].ID+'">'+
													''+formatNumber(res[i].HARGA_OBAT)+''+
													'</td>'+
													'<td style="display:none;">'+
													'<input type="text" value="'+res[i].HARGA_OBAT+'" id="hidden_harga_beli_'+res[i].ID+'" name="harga_obat[]">'+
													'<input type="text" class="total_keranjang" name="total_keranjang_name[]" id="hidden_total_keranjang_'+res[i].ID+'" value="'+res[i].HARGA_OBAT+'">'+
													'<input type="text" value="'+res[i].SERVICE+'" name="service[]">'+
													'</td>'+
													'<td style="padding-left: 0px; text-align: center;"><button class="btn btn-danger btn-sm" type="button" onclick="hapus_keranjang('+res[i].ID+');"><i class="fa fa-trash-o"></i></button></td>'+
												'</tr>';
							}
					}
					$('#tot_biaya_keranjang').html(formatNumber(tot));
					$('#total_tagihan').val(formatNumber(tot));
					$('#tabel_keranjang tbody').html($tr);
			}
	});
}

function hapus_keranjang(id){
			var jawab = confirm("Yakin untuk menghapus data?");
            if (jawab === true) {
							$.ajax({
								url : '<?php echo base_url(); ?>apotek/ap_obat_hv_c/hapus_keranjang',
								data : {id:id},
								type : "POST",
								dataType : "json",
								success : function(res){
									data_keranjang();
								}
						});
            } else {
              return false;
            }

}

function hitung_jumlah_total(id){
	var jumlah_beli = $('#jumlah_beli_'+id).val();
	if (jumlah_beli == '') {
		jumlah_beli = 0;
	}
	var harga = $('#hidden_harga_beli_'+id).val();
	var jumlah = parseFloat(jumlah_beli) * parseFloat(harga);

	$('#harga_beli_'+id).html(formatNumber(jumlah));
	$('#hidden_total_keranjang_'+id).val(jumlah);
}

function get_grand_total(){
	var total = 0;
	$('.total_keranjang').each(function(idx, elm){
		console.log(elm.value);
		total += parseFloat(elm.value);
	});
	$('#tot_biaya_keranjang').html(formatNumber(total));
	$('#total_tagihan').val(formatNumber(total));
}

function get_invoice(){
    $.ajax({
        url : '<?php echo base_url(); ?>apotek/ap_kasir_aa_c/get_invoice',
        type : "POST",
        dataType : "json",
        success : function(res){
            $('#invoice').val(res);
            $('#invoice_txt').html(res);
        }
    });
}

function onEnterText(e){
    if (e.keyCode == 13) {
        data_obat();
        $('#tombol_reset').show();
		$('#tombol_cari').hide();
        return false;
    }
}

function get_kode_trx(){
	$.ajax({
		url : '<?php echo base_url(); ?>apotek/ap_beli_obat_c/get_kode',
		type : "POST",
		dataType : "json",
		success : function(kode){
			$('#kode_trx').val(kode);
		}
	});
}

function beli_obat(id){
	$('#popup_load').show();
	$("#tr_0").hide();

	$.ajax({
		url : '<?php echo base_url(); ?>apotek/ap_beli_obat_c/data_obat_id',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(result){
			$tr = "";

			for(var i=0; i<result.length; i++){
				var jumlah_data = $('#tr_'+id).length;
				var aksi = "<button type='button' class='btn waves-light btn-danger' onclick='deleteRow(this); hapus_krj("+id+");'><i class='fa fa-times'></i></button>";

				var id_satuan = "";
				var satuan = "";

				if(result[i].JUMLAH_BUTIR != 0){
					id_satuan = 0;
					satuan = result[i].SATUAN_ISI;
				}else{
					id_satuan = result[i].ID_SATUAN;
					satuan = result[i].NAMA_SATUAN;
				}

				if(jumlah_data > 0){
					var jumlah = $('#qty_'+result[i].ID).val();
					$('#qty_'+result[i].ID).val(parseInt(jumlah)+1);
				}else{
					$tr = "<tr id='tr_"+result[i].ID+"'>"+
							"<input type='hidden' id='harga_txt_"+result[i].ID+"' value='"+result[i].HARGA_JUAL+"'>"+
							"<input type='hidden' name='total_txt[]' id='total_txt_"+result[i].ID+"' value=''>"+
							"<td style='vertical-align:middle;'>"+
								result[i].NAMA_OBAT+"<br>"+
								result[i].KODE_OBAT+
							"</td>"+
							"<td style='vertical-align:middle;'>"+result[i].NAMA_JENIS+"</td>"+
							"<td style='vertical-align:middle; text-align:right;'>"+NumberToMoney(result[i].HARGA_JUAL)+"</td>"+
							"<td>"+
								"<div class='col-md-12'>"+
									"<div class='input-group'>"+
									"	 <input type='hidden' name='jumlah_txt[]' id='jumlah_txt_"+result[i].ID+"' value='"+result[i].TOTAL+"'>"+
				                    "    <input type='text' class='form-control' name='qty[]' id='qty_"+result[i].ID+"' value='1' onkeyup='FormatCurrency(this); hitung_jumlah("+result[i].ID+");'>"+
				                    "    <span class='input-group-btn'>"+
				                    "        <button class='btn waves-effect waves-light btn-default' type='button' style='cursor:pointer:default;'>"+
				                    			satuan+
				                    "        </button>"+
				                    "    </span>"+
				                    "</div>"+
			                    "</div>"+
							"</td>"+
							"<td style='vertical-align:middle; text-align:right;'><b id='total_"+result[i].ID+"'></b></td>"+
							"<td align='center'>"+aksi+"</td>"+
						  "</tr>";
				}
			}
			$('#tabel_keranjang tbody').append($tr);
			hitung_jumlah(id);
			$('#popup_load').fadeOut();
		}
	});
}

function deleteRow(btn){
  var row = btn.parentNode.parentNode;
  row.parentNode.removeChild(row);
}

// function hitung_jumlah(id){
// 	var jumlah_txt = $('#jumlah_txt_'+id).val();
// 	var jumlah = $('#qty_'+id).val();
// 	var harga = $('#harga_txt_'+id).val();
//
// 	if(jumlah == ""){
// 		jumlah = 0;
// 	}else{
// 		jumlah = jumlah.split(',').join('');
// 	}
//
// 	var total = parseFloat(jumlah) * parseFloat(harga);
//
// 	if(parseFloat(jumlah) > parseFloat(jumlah_txt)){
// 		alert('Stok Tidak Mencukupi!');
// 		$('#simpan_krj').attr('disabled','disabled');
// 	}else{
// 		$('#simpan_krj').removeAttr('disabled');
// 	}
//
// 	$('#total_'+id).html(NumberToMoney(total));
// 	$('#total_txt_'+id).val(total);
//
// 	var qty = 0;
// 	$("input[name='qty[]']").each(function(idz,el){
// 		var j = el.value;
// 		if(j == ""){
// 			j = 0;
// 		}
// 		qty += parseFloat(j);
// 	});
// 	$('#total_qty').html(NumberToMoney(qty));
//
// 	var grandtotal = 0;
// 	$("input[name='total_txt[]']").each(function(idx,elm){
// 		var tot = elm.value;
// 		grandtotal += parseFloat(tot);
// 	});
//
// 	$('#subtotal').html(NumberToMoney(grandtotal));
// 	$('#grandtotal').html(NumberToMoney(grandtotal));
// }
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

    // if (jam == '13:45') {
    //   $('#notif_closing').attr("data-message","<i class='fa fa-warning' style='padding-right:6px'></i> Waktu closing shift 1 kurang 15 menit lagi");
    //   $('#notif_closing').click();
    //   $('#btn_suara_closing').click();
    // }else if (jam == '13:50') {
    //   $('#notif_closing').attr("data-message","<i class='fa fa-warning' style='padding-right:6px'></i> Waktu closing shift 1 kurang 10 menit lagi");
    //   $('#notif_closing').click();
    //   $('#btn_suara_closing').click();
    // }else if (jam == '13:55') {
    //   $('#notif_closing').attr("data-message","<i class='fa fa-warning' style='padding-right:6px'></i> Waktu closing shift 1 kurang 5 menit lagi");
    //   $('#notif_closing').click();
    //   $('#btn_suara_closing').click();
    // }else if (jam == '20:45') {
    //   $('#notif_closing').attr("data-message","<i class='fa fa-warning' style='padding-right:6px'></i> Waktu closing shift 2 kurang 15 menit lagi");
    //   $('#notif_closing').click();
    //   $('#btn_suara_closing').click();
    // }else if (jam == '20:50') {
    //   $('#notif_closing').attr("data-message","<i class='fa fa-warning' style='padding-right:6px'></i> Waktu closing shift 2 kurang 10 menit lagi");
    //   $('#notif_closing').click();
    //   $('#btn_suara_closing').click();
    // }else if (jam == '20:55') {
    //   $('#notif_closing').attr("data-message","<i class='fa fa-warning' style='padding-right:6px'></i> Waktu closing shift 2 kurang 5 menit lagi");
    //   $('#notif_closing').click();
    //   $('#btn_suara_closing').click();
    // }
		//
		// if (((parseInt(h) >= 13 || parseInt(m) >= 45)) && ((parseInt(h) < 14 || parseInt(m) < 00))) {
		// 	$('#btn_closing_kasir_disabled').hide();
    //   $('#btn_closing_kasir').show();
    // }else if (((parseInt(h) >= 20 || parseInt(m) >= 45)) && ((parseInt(h) < 21 || parseInt(m) < 00))) {
		// 	$('#btn_closing_kasir_disabled').hide();
    //   $('#btn_closing_kasir').show();
    // }
}

function checkTime(i) {
    if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
    return i;
}

function hapus_krj(id){
	var qty = 0;
	$("input[name='qty[]']").each(function(idz,el){
		var j = el.value;
		qty += parseFloat(j);
	});
	$('#total_qty').html(NumberToMoney(qty));

	var grandtotal = 0;
	$("input[name='total_txt[]']").each(function(idx,elm){
		var tot = elm.value;
		grandtotal += parseFloat(tot);
	});

	$('#subtotal').html(NumberToMoney(grandtotal));
	$('#grandtotal').html(NumberToMoney(grandtotal));
}

function hitung_kembali(){
    var byr = $('#b_bayar').val();
    byr = byr.split(',').join('');

    var total = $('#total_tagihan').val();
    total = total.split(',').join('');

    var kembali = parseFloat(byr) - parseFloat(total);

    if(byr == "" || byr == null){
        kembali = "";
    }

		var jenis_bayar = $('#jenis_bayar').val();

    if(byr == "") {
        kembali = "";
    }else if(kembali < 0){
        kembali = "";
        $('#warning_kelebihan').show();
        var s = parseFloat(total) - parseFloat(byr);
        $('#asd').val(s);
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

function hitung_tambahan(){
    var tambah = $('#b_tambahan').val();
    tambah = tambah.split(',').join('');

    var sisa = $('#asd').val();
    sisa = sisa.split(',').join('');

    console.log(sisa);

    var kembali = parseFloat(tambah) - parseFloat(sisa);

    if(tambah == ""){
        kembali = "";
    }else if(kembali < 0){
        kembali = "";
        $('#warning_kelebihan').show();
        var s = parseFloat(sisa) - parseFloat(tambah);
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
    // var tagihan = $('#total_tagihan').val();
    // tagihan = tagihan.split(',').join('');

    document.getElementById("non_tunai_btn").className = "btn btn-warning";
    document.getElementById("tunai_btn").className = "btn btn-default";
    $('.tunai_grp').hide();
    $('.non_tunai_grp').show();
    $('#b_kembali').val('');
    $('#b_bayar').val('');
    $('#jenis_bayar').val('Non Tunai');
}

function get_filter_semua(){
  document.getElementById("btn_filter_semua").className = "btn btn-success";
  document.getElementById("btn_filter_tanggal").className = "btn btn-default";
  document.getElementById("btn_filter_bulan").className = "btn btn-default";
  $('#form_tanggal_filter').hide();
  $('#form_bulan_filter').hide();

  $('#hidden_filter_semua').removeAttr('disabled');
  $('#hidden_filter_tanggal').attr('disabled','disabled');
  $('#hidden_filter_bulan').attr('disabled','disabled');
  semua_filter();
}

function get_filter_tanggal(){
  document.getElementById("btn_filter_tanggal").className = "btn btn-success";
  document.getElementById("btn_filter_semua").className = "btn btn-default";
  document.getElementById("btn_filter_bulan").className = "btn btn-default";
  $('#form_tanggal_filter').show();
  $('#form_bulan_filter').hide();

  $('#hidden_filter_tanggal').removeAttr('disabled');
  $('#hidden_filter_semua').attr('disabled','disabled');
  $('#hidden_filter_bulan').attr('disabled','disabled');
}

function get_filter_bulan(){
  document.getElementById("btn_filter_bulan").className = "btn btn-success";
  document.getElementById("btn_filter_semua").className = "btn btn-default";
  document.getElementById("btn_filter_tanggal").className = "btn btn-default";
  $('#form_tanggal_filter').hide();
  $('#form_bulan_filter').show();

  $('#hidden_filter_bulan').removeAttr('disabled');
  $('#hidden_filter_semua').attr('disabled','disabled');
  $('#hidden_filter_tanggal').attr('disabled','disabled');
}

function data_rekap_penjualan(){
	$('#popup_rekap_penjualan').click();
	$.ajax({
    url : '<?php echo base_url(); ?>apotek/ap_kasir_aa_c/data_rekap_penjualan',
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
                          "<td>"+result[i].NAMA_OBAT+"</td>"+
                          "<td>"+result[i].TANGGAL+"</td>"+
                          "<td>"+result[i].TOTAL+"</td>"+
                          "<td>"+result[i].NAMA_PEGAWAI+"</td>"+
                          "<td>"+result[i].SHIFT+"</td>"+
                      "</tr>";
          }
      }
      $('#tabel_rekap_penjualan tbody').html(table);
    }
  });
}

function tanggal_filter(){
  var tanggal_sekarang = $('#tanggal_sekarang').val();
  var tanggal_sampai = $('#tanggal_sampai').val();
  $.ajax({
    url : '<?php echo base_url(); ?>apotek/ap_kasir_aa_c/tanggal_filter',
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
                          "<td>"+result[i].NAMA_OBAT+"</td>"+
                          "<td>"+result[i].TANGGAL+"</td>"+
                          "<td>"+result[i].TOTAL+"</td>"+
                          "<td>"+result[i].NAMA_PEGAWAI+"</td>"+
                          "<td>"+result[i].SHIFT+"</td>"+
                      "</tr>";
          }
      }
      $('#tabel_rekap_penjualan tbody').html(table);
    }
  });
}

function bulan_filter(){
  var result_bulan = $('#result_bulan').val();
  $.ajax({
    url : '<?php echo base_url(); ?>apotek/ap_kasir_aa_c/bulan_filter',
    data : {
      result_bulan:result_bulan,
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
                          "<td>"+result[i].NAMA_OBAT+"</td>"+
                          "<td>"+result[i].TANGGAL+"</td>"+
                          "<td>"+result[i].TOTAL+"</td>"+
                          "<td>"+result[i].NAMA_PEGAWAI+"</td>"+
                          "<td>"+result[i].SHIFT+"</td>"+
                      "</tr>";
          }
      }
      $('#tabel_rekap_penjualan tbody').html(table);
    }
  });
}

function semua_filter(){
  $.ajax({
    url : '<?php echo base_url(); ?>apotek/ap_kasir_aa_c/data_rekap_pendapatan',
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
                          "<td>"+result[i].NAMA_OBAT+"</td>"+
                          "<td>"+result[i].TANGGAL+"</td>"+
                          "<td>"+result[i].TOTAL+"</td>"+
                          "<td>"+result[i].NAMA_PEGAWAI+"</td>"+
                          "<td>"+result[i].SHIFT+"</td>"+
                      "</tr>";
          }
      }
      $('#tabel_rekap_penjualan tbody').html(table);
    }
  });
}
</script>
</body>
</html>

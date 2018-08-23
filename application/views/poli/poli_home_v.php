<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
        <meta name="author" content="Coderthemes">

        <link rel="shortcut icon" href="<?php echo base_url(); ?>picture/favicon.png">

        <title><?php echo $title; ?></title>

        <!--Morris Chart CSS -->
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/morris/morris.css">

        <!-- Notification css (Toastr) -->
        <link href="<?php echo base_url(); ?>assets/plugins/toastr/toastr.min.css" rel="stylesheet" type="text/css" />

        <!-- Custom box css -->
        <link href="<?php echo base_url(); ?>assets/plugins/custombox/dist/custombox.min.css" rel="stylesheet">

        <!-- DataTables -->
        <link href="<?php echo base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>assets/plugins/datatables/buttons.bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>assets/plugins/datatables/fixedHeader.bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>assets/plugins/datatables/responsive.bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>assets/plugins/datatables/scroller.bootstrap.min.css" rel="stylesheet" type="text/css" />

        <!-- Plugins css-->
        <link href="<?php echo base_url(); ?>assets/plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>assets/plugins/select2/dist/css/select2.css" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url(); ?>assets/plugins/select2/dist/css/select2-bootstrap.css" rel="stylesheet" type="text/css">

        <link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>assets/css/core.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>assets/css/components.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>assets/css/icons.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>assets/css/pages.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>assets/css/menu.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>assets/css/responsive.css" rel="stylesheet" type="text/css" />

        <!--CSS Devan-->
        <link href="<?php echo base_url(); ?>css-devan/warna.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>css-devan/style-devan.css" rel="stylesheet" type="text/css" />

        <link href="<?php echo base_url(); ?>dist/css/datepicker.css" rel="stylesheet" type="text/css">

        <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script> 
        <![endif]-->
        <style type="text/css">
        #ketap_ketip{
            display: none;
        }

        #popup_pasien_baru {
            width: 100%;
            height: 100%;
            position: fixed;
            background: rgba(0,0,0,.7);
            top: 0;
            left: 0;
            z-index: 9999;
            display: none;
        }
        .window_pasien_baru {
            width:50%;
            height:auto;
            position: relative;
            padding: 10px;
            margin: 2% auto;
            background-color: #fff;
        }
        </style>
        <script src="<?php echo base_url(); ?>assets/js/modernizr.min.js"></script>
        <?PHP
            date_default_timezone_set('Asia/Jakarta');
            $sess_user = $this->session->userdata('masuk_rs');
            $id_user = $sess_user['id'];
            $user = $this->master_model_m->get_user_info($id_user);
            $level = $user->LEVEL;
            $id_divisi = $sess_user['id_divisi']; //ID POLI

            $is_operator = $this->master_model_m->is_operator($id_user, 'poli');
            $bulan = array(
                1 =>    "Januari", 2  =>"Februari", 3  =>"Maret", 4 =>"April",
                5 =>    "Mei", 6  =>"Juni", 7  =>"Juli", 8 =>"Agustus",
                9 =>    "September", 10 =>"Oktober", 11 =>"November", 12 =>"Desember"
            );
        ?>
    </head>

    <body onload="startTime();">
        <!-- Navigation Bar-->
        <header id="topnav">
            <div class="topbar-main" style="background-color:#00a4e4; height:60px;">
                <div class="container">

                    <!-- LOGO -->
                    <div class="topbar-left">
                        <a href="<?php echo base_url(); ?>portal" class="logo" style="margin-top:4px;">
                            <img src="<?php echo base_url(); ?>picture/jtech-logo.png" style="max-width:150px; max-height:40px;">
                        </a>
                    </div>
                    <!-- End Logo container-->

                    <!-- LOKET -->
                    <?php 
                    // if(count($is_operator) > 0){ 
                    //     $get_loket = $this->master_model_m->getLoket($id_user, 'poli');
                    //     $get_jml_antrian = $this->master_model_m->getJmlAntrian($get_loket->KODE_ANTRIAN);
                    ?>
                    <!-- <center>
                        <div style="width: 87%; position: absolute; float: left; margin-top: 7px;">
                            <button type="button" class="btn btn-warning waves-effect waves-light w-md m-b-5" style="padding-top: 10px; padding-bottom: 10px;"> 
                                <b id="nama_loket_antrian_txt"> <?=strtoupper($get_loket->NAMA_LOKET);?> </b> 
                            </button>

                            <button type="button" class="btn btn-danger" style="margin-left: 100px; font-size: 32px; margin-top: -8px;"> 
                                <b><?=$get_loket->KODE;?>-<font id="jml_antrian_txt"><?=count($get_jml_antrian) + 0;?></font></b> 

                            </button>

                            <button class="btn btn-purple waves-effect waves-light m-b-5" style="margin-left: 40px;" onclick="panggil_antrian();"> 
                               <i class="fa fa-bullhorn m-r-5"></i>   <span> Panggil </span> 
                            </button>

                            <button class="btn btn-success waves-effect waves-light m-b-5" style="margin-left: 10px;" data-toggle="modal" data-target="#next_antrian"> 
                                <span> Berikutnya </span> <i class="fa fa-chevron-circle-right m-r-5"></i>  
                            </button>
                        </div>
                    </center> -->
                    <?PHP //} ?>
                    <!-- END OF LOKET -->

                    <div class="menu-extras">
                        <ul class="nav navbar-nav navbar-right pull-right" style="background-color:#0a4d8c; height:60px;">
                            <li>
                                <form role="search" class="navbar-left app-search pull-left hidden-xs" style="margin-right:0px; margin-top:0px;">
                                <?php
                                    $nama_div = '';
                                    if($user->NAMA_DIV == null){
                                        $nama_div = $user->JABATAN;
                                    }else{
                                        $nama_div = $user->NAMA_DIV;
                                    }
                                ?>
                                    <h5 style="color:#fff;"><b><?php echo strtoupper($nama_div);?></b></h5>
                                    <h6 style="color:#fff;"><b><?php echo $user->NAMA;?></b></h6>
                                    <!-- <h6 style="color:#fff;"><b><?php echo $user->JABATAN;?></b></h6> -->
                                </form>
                            </li>
                            <li class="dropdown user-box">
                                <a href="" class="dropdown-toggle waves-effect waves-light profile " data-toggle="dropdown" aria-expanded="true">
                                    <img src="<?php echo base_url(); ?>files/foto_pegawai/<?php echo $user->FOTO;?>" alt="user-img" class="img-circle user-img">
                                </a>
                                <ul class="dropdown-menu">
                                    <?php if($level == null){ ?>
                                    <li><a href="<?php echo base_url(); ?>portal"><i class="fa fa-th m-r-5"></i> Portal Depan</a></li>
                                    <?php } ?>
                                    <li><a href="javascript:void(0)"><i class="ti-user m-r-5"></i> Profile</a></li>
                                    <li><a href="javascript:void(0)"><i class="ti-settings m-r-5"></i> Settings</a></li>
                                    <li><a href="javascript:void(0)"><i class="ti-lock m-r-5"></i> Lock screen</a></li>
                                    <li><a href="<?php echo base_url(); ?>logout"><i class="ti-power-off m-r-5"></i> Logout</a></li>
                                </ul>
                            </li>
                        </ul>
                        <ul class="nav navbar-nav navbar-right">
                            <li>
                                <!-- Notification -->
                                <div class="notification-box">
                                    <ul class="list-inline m-b-0">
                                        <li id="li_notif">
                                            <a class="right-bar-toggle" href="javascript:void(0);">
                                                <i class="fa fa-bell-o"></i>
                                            </a>
                                            <span class="badge badge-success" id="tot_pasien">0</span>
                                            <div class="noti-dot">
                                                <span class="dot" id="ketap_ketip"></span>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <!-- End Notification bar -->
                            </li>
                        </ul>
                        <div class="menu-item">
                            <!-- Mobile menu toggle-->
                            <a class="navbar-toggle">
                                <div class="lines">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </div>
                            </a>
                            <!-- End mobile menu toggle-->
                        </div>
                    </div>

                </div>
            </div>

            <div class="navbar-custom">
                <div class="container">
                    <div id="navigation">
                        <!-- Navigation Menu-->
                        <ul class="navigation-menu">
                            <li <?php if($master_menu == 'home'){ echo 'class="active"';}else{echo '';} ?> >
                                <a href="<?php echo base_url(); ?>poli/poli_home_c">
                                    <i class="zmdi zmdi-view-dashboard"></i> <span> Dashboard </span> 
                                </a>
                            </li>
                        </ul>
                        <!-- End navigation menu  --> 
                    </div>
                </div>
            </div>
        </header>
        <!-- End Navigation Bar-->

        <div class="wrapper">
            <div class="container">
                <br>
                <div class="row">
                    <input type="hidden" id="id_antrian_now" value="">
                    <input type="hidden" id="kode_antrian_now" value="">
                    <input type="hidden" id="jml_antrian_now" value="">
                    <input type="hidden" id="id_antrian_now_on" value="">
                    <input type="hidden" id="kode_antrian_now_on" value="">
                    <input type="hidden" id="jml_antrian_now_on" value="">
                    <input type="hidden" id="ke_tindakan" value="">
                    <!-- <div class="col-lg-3 col-md-6">
                        <div class="card-box widget-user">
                            <div>
                                <img alt="user" class="img-responsive img-circle" src="<?php echo base_url(); ?>picture/antri_online.png">
                                <div class="wid-u-info">
                                    <h4 class="m-t-0 m-b-5">Antrian Online</h4>
                                    <button type="button" class="btn btn-info waves-effect waves-light m-b-5"><b id="loket_online"></b></button>
                                    <button type="button" class="btn btn-purple waves-effect waves-light m-b-5" onclick="panggil_antrian('online');"><i class="fa fa-bullhorn m-r-5"></i><b id="nomor_online">-</b></button>
                                    <button type="button" class="btn btn-success waves-effect waves-light m-b-5" onclick="next_antri('online');"><b>Berikutnya</b> <i class="fa fa-arrow-circle-right"></i></button>
                                </div>
                            </div>
                        </div>
                    </div> -->

                    <div class="col-lg-4 col-md-6">
                        <div class="card-box widget-user">
                            <div>
                                <img alt="user" class="img-responsive img-circle" src="<?php echo base_url(); ?>picture/small-queue-management.png">
                                <div class="wid-u-info">
                                    <h4 class="m-t-0 m-b-5"> Nomor Antrian <!-- <b id="loket_offline"></b> --></h4>
                                    <!-- <h2 class="text-success" id="nomor_offline">0</h2> -->
                                    <button type="button" class="btn btn-purple waves-effect waves-light m-t-15" data-original-title="Nomor antrian sekarang" title="" data-placement="top" data-toggle="tooltip">
                                        <b id="nomor_offline"></b>
                                    </button>
                                    <button type="button" class="btn btn-success waves-effect waves-light m-t-15" data-target="#modalClosing" data-toggle="modal" data-original-title="Digunakan untuk menghentikan Nomor Antrian dan me-reset ulang." title="" data-placement="top" data-toggle="tooltip">
                                        <b>Closing Antrian</b> <i class="fa fa-hand-stop-o"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <div class="card-box widget-user">
                            <div>
                                <img alt="user" class="img-responsive img-circle" src="<?php echo base_url(); ?>picture/Clock-Icon-Image.png">
                                <div class="wid-u-info">
                                    <h4 class="m-t-0 m-b-5">Waktu</h4>
                                    <h2 class="text-info" id="waktu_txt">0</h2>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <div class="card-box widget-user">
                            <div>
                                <img alt="user" class="img-responsive img-circle" src="<?php echo base_url(); ?>picture/kisspng-calendar-date-computer-icons-time-calendar-icon-5ac41db68edb81.1459769815228021025852.jpg">
                                <div class="wid-u-info">
                                    <h4 class="m-t-0 m-b-5">Tanggal</h4>
                                    <h2 class="text-danger"><?php echo date('d'); ?> <?php echo $bulan[date('n')]; ?> <?php echo date('Y'); ?></h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php $this->load->view($page); ?>

                <br/>
                <!-- Footer -->
                <footer class="footer text-right">
                    <div class="container">
                        <div class="row">
                            <div class="col-xs-6">
                               © 2016 Aplikasi Rumah Sakit
                            </div>
                            <div class="col-xs-6">
                                <ul class="pull-right list-inline m-b-0">
                                    <?php if($level == null){ ?>
                                    <li>
                                        <a href="<?php echo base_url(); ?>portal" style="color:#887d59;"><i class="fa fa-th"></i> Portal Depan</a>
                                    </li>
                                    <?php } ?>
                                    <li>
                                        <a href="#" style="color:#887d59;"><i class="fa fa-info-circle"></i> Tentang</a>
                                    </li>
                                    <li>
                                        <a href="#" style="color:#887d59;"><i class="fa fa-question-circle"></i> Bantuan</a>
                                    </li>
                                    <li>
                                        <a href="#" style="color:#887d59;"><i class="fa fa-phone-square"></i> Kontak Kami</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </footer>
                <!-- End Footer -->

            </div>
            <!-- end container -->

        </div>

        <!-- Next Antrian Modal -->
        <div id="next_antrian" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content p-0 b-0">
                    <div class="panel panel-color panel-primary">
                        <div class="panel-heading">
                            <button type="button" class="close m-t-5" data-dismiss="modal" aria-hidden="true">×</button>
                            <h3 class="panel-title"> Konfirmasi </h3>
                        </div>
                        <div class="panel-body">
                            <p>Apakah anda ingin melanjutkan ke antrian selanjutnya ?</p>
                        </div>
                        <div class="panel-footer">
                            <center>
                                <button type="button" class="btn btn-inverse " data-dismiss="modal" id="close_next_antrian">Tidak</button>
                                 &nbsp;&nbsp;&nbsp;&nbsp;
                                <button onclick="next_antri();" type="button" class="btn btn-danger"> Ya </button>
                            </center>                        
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Of Next Antrian Modal -->

        <!-- Closing Antrian Modal -->
        <div id="modalClosing" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content p-0 b-0">
                    <div class="panel panel-color panel-primary">
                        <div class="panel-heading">
                            <button type="button" class="close m-t-5" data-dismiss="modal" aria-hidden="true">×</button>
                            <h3 class="panel-title"> Konfirmasi </h3>
                        </div>
                        <div class="panel-body">
                            <p>Apakah anda ingin menghentikan nomor antrian dan mereset ulang?</p>
                        </div>
                        <div class="panel-footer">
                            <center>
                                <button type="button" class="btn btn-inverse " data-dismiss="modal" id="tidak_closing">Tidak</button>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <button type="button" class="btn btn-danger" id="ya_closing"> Ya </button>
                            </center>                        
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Of Closing Antrian Modal -->

        <!-- Modal -->
        <div id="popup_pasien_baru">
            <div class="window_pasien_baru">
                <div class="row" id="view_data">
                    <div class="col-md-12">
                        <div class="card-box">
                            <form class="form-horizontal" role="form">
                                <div class="form-group">
                                    <h4 class="header-title m-t-0">Daftar Pasien Baru</h4>
                                </div>
                                <div class="form-group">
                                    <div class="table-responsive">
                                        <table id="tabel_pasien_home" class="table table-hover table-bordered">
                                            <thead>
                                                <tr class="kuning_popup">
                                                    <th style="text-align:center; vertical-align: middle;">
                                                        <!-- <div class="checkbox checkbox-primary">
                                                            <input id="checkboxAll" type="checkbox" name="centang_semua">
                                                            <label for="checkboxAll">
                                                                &nbsp;
                                                            </label>
                                                        </div> -->
                                                    </th>
                                                    <th style="color:#fff; text-align:center; vertical-align: middle;">No</th>
                                                    <th style="color:#fff; text-align:center; vertical-align: middle;">No. RM</th>
                                                    <th style="color:#fff; text-align:center; vertical-align: middle;">Tgl / Waktu</th>
                                                    <th style="color:#fff; text-align:center; vertical-align: middle;">Nama Pasien</th>
                                                    <th style="color:#fff; text-align:center; vertical-align: middle;">JK</th>
                                                    <th style="color:#fff; text-align:center; vertical-align: middle;">Antrian</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <center>
                                        <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal" id="tutup_pas">Tutup</button>
                                    </center>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- jQuery  -->
        <script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/detect.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/fastclick.js"></script>

        <script src="<?php echo base_url(); ?>assets/js/jquery.slimscroll.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/jquery.blockUI.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/waves.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/wow.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/jquery.nicescroll.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/jquery.scrollTo.min.js"></script>

        <!-- Datatables-->
        <script src="<?php echo base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/plugins/datatables/dataTables.bootstrap.js"></script>
        <script src="<?php echo base_url(); ?>assets/plugins/datatables/dataTables.buttons.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/plugins/datatables/buttons.bootstrap.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/plugins/datatables/jszip.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/plugins/datatables/pdfmake.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/plugins/datatables/vfs_fonts.js"></script>
        <script src="<?php echo base_url(); ?>assets/plugins/datatables/buttons.html5.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/plugins/datatables/buttons.print.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/plugins/datatables/dataTables.fixedHeader.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/plugins/datatables/dataTables.keyTable.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/plugins/datatables/dataTables.responsive.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/plugins/datatables/responsive.bootstrap.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/plugins/datatables/dataTables.scroller.min.js"></script>

        <!-- Datatable init js -->
        <script src="<?php echo base_url(); ?>assets/pages/datatables.init.js"></script>
        
        <!-- Modal-Effect -->
        <script src="<?php echo base_url(); ?>assets/plugins/custombox/dist/custombox.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/plugins/custombox/dist/legacy.min.js"></script>

        <!-- Toastr js -->
        <script src="<?php echo base_url(); ?>assets/plugins/toastr/toastr.min.js"></script>

        <!--JS Devan-->
        <script src="<?php echo base_url(); ?>js-devan/alert.js"></script>
        <script src="<?php echo base_url(); ?>js-devan/js-form.js"></script>
        <script src="<?php echo base_url(); ?>js-devan/pagination.js"></script>
        <script src="<?php echo base_url(); ?>js-devan/md5.js"></script>

        <!-- Plugins Js -->
        <script src="<?php echo base_url(); ?>assets/plugins/bootstrap-inputmask/bootstrap-inputmask.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>assets/plugins/moment/moment.js"></script>
        <script src="<?php echo base_url(); ?>assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
        <script src="<?php echo base_url(); ?>assets/plugins/select2/dist/js/select2.min.js" type="text/javascript"></script>

        <!-- KNOB JS -->
        <!--[if IE]>
        <script type="text/javascript" src="assets/plugins/jquery-knob/excanvas.js"></script>
        <![endif]-->
        <script src="<?php echo base_url(); ?>assets/plugins/jquery-knob/jquery.knob.js"></script>

        <!--Morris Chart
		<script src="<?php //echo base_url(); ?>assets/plugins/morris/morris.min.js"></script>
		<script src="<?php //echo base_url(); ?>assets/plugins/raphael/raphael-min.js"></script>
        -->
        <!-- Dashboard init -->
        <script src="<?php echo base_url(); ?>assets/pages/jquery.dashboard.js"></script>

        <!-- App js -->
        <script src="<?php echo base_url(); ?>assets/js/jquery.core.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/jquery.app.js"></script>

        <script src="<?php echo base_url(); ?>dist/js/datepicker.js"></script>

        <!-- Include English language -->
        <script src="<?php echo base_url(); ?>dist/js/i18n/datepicker.en.js"></script>
        <script src="http://code.responsivevoice.org/responsivevoice.js"></script>

        <script type="text/javascript">
        var snd = new Audio("<?php echo base_url(); ?>sound/nokia_tune_new.mp3"); // buffers automatically when created
        var timer = 0;
        var level = "<?php echo $level; ?>";
        var tdk = $('#ke_tindakan').val();

        jQuery(document).ready(function() {
            $('#datatable').dataTable();
            $('#datatable-keytable').DataTable( { keys: true } );
            $('#datatable-responsive').DataTable();
            $('#datatable-scroller').DataTable( { ajax: "<?php echo base_url(); ?>assets/plugins/datatables/json/scroller-demo.json", deferRender: true, scrollY: 380, scrollCollapse: true, scroller: true } );
            var table = $('#datatable-fixed-header').DataTable( { fixedHeader: true } );
            $(".select2").select2();
            // console.log(level);
            if(level == null || level == ""){
                
            }else{
                if(tdk == ""){
                    get_antrian_offline();
                    // get_antrian_online();
                    get_notif_pasien();
                    timer = setInterval(function () {
                        get_notif_pasien();
                        get_antrian_offline();
                    }, 5000);
                }else{
                    $('#popup_pasien_baru').hide();
                    snd.pause();
                }
            }


            $('#li_notif').click(function(){
                $('#popup_pasien_baru').show();
                data_pasien_baru();
            });

            $('#tutup_pas').click(function(){
                $('#popup_pasien_baru').fadeOut();
                snd.pause();
                clearInterval(timer);
            });

            $('#ya_closing').click(function(){
                $.ajax({
                    url : '<?php echo base_url(); ?>poli/poli_home_c/closing_antrian',
                    type : "POST",
                    dataType : "json",
                    success : function(res){
                        $('#tidak_closing').click();
                        notif_closing_antrian();
                    }
                });
            });
        });

        function startTime() {
            var today = new Date();
            var h = today.getHours();
            var m = today.getMinutes();
            var s = today.getSeconds();
            m = checkTime(m);
            s = checkTime(s);
            document.getElementById('waktu_txt').innerHTML = h + ":" + m + ":" + s;
            var t = setTimeout(startTime, 500);
        }

        function checkTime(i) {
            if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
            return i;
        }

        function get_antrian_offline(){
            var id_user = "<?php echo $id_user; ?>";

            $.ajax({
                url : '<?php echo base_url(); ?>poli/poli_home_c/get_antrian_offline',
                data : {id_user:id_user},
                type : "POST",
                dataType : "json",
                success : function(res){
                    if(res['cek'].length != 0){
                        // $('#loket_offline').html(res['data']['NAMA_LOKET']);
                        var id_kode_antrian = res['data']['KODE_ANTRIAN'];
                        get_nomor_offline(id_kode_antrian);
                        // console.log(id_kode_antrian);
                    }
                }
            });
        }

        function get_nomor_offline(id_kode_antrian){
            var id_user = "<?php echo $id_user; ?>";

            $.ajax({
                url : '<?php echo base_url(); ?>poli/poli_home_c/get_nomor_offline',
                data : {
                    id_kode_antrian:id_kode_antrian,
                    id_user:id_user
                },
                type : "POST",
                dataType : "json",
                success : function(res){
                    $('#id_antrian_now').val(id_kode_antrian);
                    if(res == null || res == ""){
                        $('#kode_antrian_now').val('A');
                        $('#jml_antrian_now').val('1');
                        $('#nomor_offline').html('A-1');
                    }else{
                        $('#kode_antrian_now').val(res['KODE_ANTRIAN']);
                        $('#jml_antrian_now').val(res['NOMOR_ANTRIAN']);
                        $('#nomor_offline').html(res['KODE_ANTRIAN']+'-'+res['NOMOR_ANTRIAN']);
                    }
                }
            });
        }

        function get_antrian_online(){
            var id_user = "<?php echo $id_user; ?>";

            $.ajax({
                url : '<?php echo base_url(); ?>poli/poli_home_c/get_antrian_online',
                data : {id_user:id_user},
                type : "POST",
                dataType : "json",
                success : function(res){
                    $('#loket_online').html(res['data']['NAMA_LOKET']);
                    var id_kode_antrian = res['data']['KODE_ANTRIAN'];
                    get_nomor_online(id_kode_antrian);
                }
            });
        }

        function get_nomor_online(id_kode_antrian){
            var id_user = "<?php echo $id_user; ?>";

            $.ajax({
                url : '<?php echo base_url(); ?>poli/poli_home_c/get_nomor_online',
                data : {
                    id_kode_antrian:id_kode_antrian,
                    id_user:id_user
                },
                type : "POST",
                dataType : "json",
                success : function(res){
                    $('#id_antrian_now_on').val(id_kode_antrian);
                    if(res == null || res == ""){
                        $('#kode_antrian_now_on').val('A');
                        $('#jml_antrian_now_on').val('1');
                        $('#nomor_online').html('A-1');
                    }else{
                        for(var i=0; i<res.length; i++){
                            $('#kode_antrian_now_on').val(res[i].KODE);
                            $('#jml_antrian_now_on').val(res[i].URUT);
                            $('#nomor_online').html(res[i].KODE+'-'+res[i].URUT);
                        }
                    }
                }
            });
        }

        function panggil_antrian(status){
            var kode_antrian = '';
            var jml_antrian  = '';
            var nama_loket_antrian_txt = '';
            if(status == 'offline'){
                kode_antrian = $('#kode_antrian_now').val();
                jml_antrian  = $('#jml_antrian_now').val();
                // nama_loket_antrian_txt = $('#loket_offline').html();
            }else{
                kode_antrian = $('#kode_antrian_now_on').val();
                jml_antrian  = $('#jml_antrian_now_on').val();
                nama_loket_antrian_txt = $('#loket_online').html();
            }

            responsiveVoice.speak(
              "Pengunjung dengan nomor antrian, "+kode_antrian+","+jml_antrian+", silahkan menuju ke "+nama_loket_antrian_txt+". Terima kasih. ",
              "Indonesian Female",
              {
               pitch: 1, 
               rate: 1, 
               volume: 1
              }
             );
        }

        function next_antri(status){
            var id_antrian   = '';
            var kode_antrian = '';
            var jml_antrian  = 0;
            var nama_loket_antrian_txt = '';

            if(status == 'offline'){
                id_antrian   = $('#id_antrian_now').val();
                kode_antrian = $('#kode_antrian_now').val();
                jml_antrian  = $('#jml_antrian_now').val();
                // nama_loket_antrian_txt = $('#loket_offline').html();
            }else{
                id_antrian   = $('#id_antrian_now_on').val();
                kode_antrian = $('#kode_antrian_now_on').val();
                jml_antrian  = $('#jml_antrian_now_on').val();
                nama_loket_antrian_txt = $('#loket_online').html();
            }

            $.ajax({
                url : '<?php echo base_url(); ?>poli/poli_home_c/next_antri',
                data : {
                    id_antrian:id_antrian,
                    kode_antrian:kode_antrian,
                    jml_antrian:jml_antrian,
                    status:status
                },
                type : "POST",
                dataType : "json",
                success : function(row){
                    if(row == 1){
                        var jml_antrian_next = '';
                        if(status == 'offline'){
                            jml_antrian_next = parseInt(jml_antrian) + 1;
                            $('#nomor_offline').html(kode_antrian+'-'+jml_antrian_next);
                            $('#jml_antrian_now').val(jml_antrian_next);
                            get_antrian_offline();
                        }else{
                            jml_antrian_next = parseInt(jml_antrian) + 1;
                            $('#nomor_online').html(kode_antrian+'-'+jml_antrian_next);
                            $('#jml_antrian_now_on').val(jml_antrian_next);
                            get_antrian_online();
                        }

                        // $('#close_next_antrian').click();

                        // responsiveVoice.speak(
                        //   "Pengunjung dengan nomor antrian, "+kode_antrian+","+jml_antrian+", silahkan menuju ke "+nama_loket_antrian_txt+". Terima kasih. ",
                        //   "Indonesian Female",
                        //   {
                        //    pitch: 1, 
                        //    rate: 1, 
                        //    volume: 1
                        //   }
                        // );
                    }
                }
            });
        }

        function get_notif_pasien(){
            var keyword = "";
            var level = "<?php echo $level; ?>";
            var id_divisi = "<?php echo $id_divisi; ?>";

            $.ajax({
                url : '<?php echo base_url(); ?>poli/poli_home_c/notif_pasien_baru',
                data : {
                    keyword:keyword,
                    level:level,
                    id_divisi:id_divisi
                },
                type : "GET",
                dataType : "json",
                success : function(res){
                    if(res.length == 0){
                        $('#ketap_ketip').hide();
                    }else{
                        for(var i=0; i<res.length; i++){
                            if(res[i].STS_TERIMA == '0'){
                                $('#ketap_ketip').show();
                                snd.play();
                                notif_pasien_baru();
                                $('#popup_pasien_baru').show();
                                data_pasien_baru();
                            }else{
                                $('#ketap_ketip').hide();
                            }
                        }
                    }

                    $('#tot_pasien').html(res.length);
                }
            });
        }

        function data_pasien_baru(){
            $.ajax({
                url : '<?php echo base_url(); ?>poli/poli_home_c/notif_pasien_baru',
                type : "POST",
                dataType : "json",
                success : function(result){
                    $tr = "";

                    if(result == "" || result == null){
                        $tr = "<tr><td colspan='7' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
                    }else{
                        var no = 0;

                        for(var i=0; i<result.length; i++){
                            no++;

                            var aksi = '<div class="checkbox checkbox-primary">'+
                                        '    <input id="checkbox'+result[i].ID_RJ+'" type="checkbox" name="centang[]" value="'+result[i].ID_RJ+'" onclick="terima_pasien('+result[i].ID_RJ+');">'+
                                        '    <label for="checkbox'+result[i].ID_RJ+'">&nbsp;</label>'+
                                        '</div>';

                            result[i].WAKTU_DAFTAR = result[i].WAKTU_DAFTAR==null?"00:00":result[i].WAKTU_DAFTAR;
                            var antrian = result[i].KODE_ANTRIAN+' - '+result[i].NOMOR_ANTRIAN;

                            $tr +=  '<tr>'+
                                    '   <td style="vertical-align:middle;" align="center">'+aksi+'</td>'+
                                    '   <td style="cursor:pointer; vertical-align:middle; text-align:center;">'+no+'</td>'+
                                    '   <td style="cursor:pointer; vertical-align:middle; text-align:center;">'+result[i].KODE_PASIEN+'</td>'+
                                    '   <td style="cursor:pointer; vertical-align:middle; text-align:center;">'+result[i].TANGGAL_DAFTAR+' - '+result[i].WAKTU_DAFTAR+'</td>'+
                                    '   <td style="cursor:pointer; vertical-align:middle;">'+result[i].NAMA+'</td>'+
                                    '   <td style="cursor:pointer; vertical-align:middle; text-align:center;">'+result[i].JENIS_KELAMIN+'</td>'+
                                    '   <td style="cursor:pointer; vertical-align:middle; text-align:center;">'+antrian+'</td>'+
                                    '</tr>';
                        }
                    }

                    $('#tabel_pasien_home tbody').html($tr);
                }
            });
        }

        function terima_pasien(id){
            var keyword = "";
            var level = "<?php echo $level; ?>";
            var id_divisi = "<?php echo $id_divisi; ?>";

            $.ajax({
                url : '<?php echo base_url(); ?>poli/poli_home_c/terima_pasien',
                data : {id:id},
                type : "POST",
                dataType : "json",
                success : function(res){
                    snd.pause();
                    $('#popup_pasien_baru').fadeOut();
                    // console.log(res);
                    data_pasien();
                    data_pasien_baru();
                }
            });

            $.ajax({
                url : '<?php echo base_url(); ?>poli/poli_home_c/notif_pasien_baru',
                data : {
                    keyword:keyword,
                    level:level,
                    id_divisi:id_divisi
                },
                type : "GET",
                dataType : "json",
                success : function(res){
                    if(res.length == 0){
                        $('#ketap_ketip').hide();
                    }else{
                        for(var i=0; i<res.length; i++){
                            if(res[i].STS_TERIMA == '0'){
                                $('#ketap_ketip').show();
                                notif_pasien_baru();
                                $('#popup_pasien_baru').show();
                                data_pasien_baru();
                            }else{
                                $('#ketap_ketip').hide();
                            }
                        }
                    }

                    $('#tot_pasien').html(res.length);
                }
            });
        }
        </script>
    </body>
</html>
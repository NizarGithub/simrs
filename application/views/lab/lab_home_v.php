<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
        <meta name="author" content="Coderthemes">

        <link rel="shortcut icon" href="<?php echo base_url(); ?>picture/Indonesian_Red_Cross_Society_logo.png">

        <title><?php echo $title; ?></title>

        <!-- form Uploads -->
        <link href="<?php echo base_url(); ?>assets/plugins/fileuploads/css/dropify.min.css" rel="stylesheet" type="text/css" />
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
        <link rel="stylesheet" href="<?=base_url();?>assets/dialog/css/reset.css"> <!-- CSS reset -->
        <link rel="stylesheet" href="<?=base_url();?>assets/dialog/css/style.css"> <!-- Resource style -->

        <!--CSS Devan-->
        <link href="<?php echo base_url(); ?>css-devan/warna.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>css-devan/style-devan.css" rel="stylesheet" type="text/css" />
        <link href="<?=base_url();?>assets/custom/css/popup.css" rel="stylesheet">
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
        </style>
        <script src="<?php echo base_url(); ?>assets/js/modernizr.min.js"></script>
    </head>

    <?PHP 
        $sess_user = $this->session->userdata('masuk_rs');
        $id_user = $sess_user['id'];
        $user = $this->master_model_m->get_user_info($id_user);
        $level = $user->LEVEL;
        $bulan = array(
            1 =>    "Jan", 2  =>"Feb", 3  =>"Mar", 4 =>"Apr",
            5 =>    "Mei", 6  =>"Jun", 7  =>"Jul", 8 =>"Agt",
            9 =>    "Sep", 10 =>"Okt", 11 =>"Nov", 12 =>"Des"
        );
    ?>

    <body onload="startTime();">
        <!-- Navigation Bar-->
        <header id="topnav">
            <div class="topbar-main" style="background-color:#3be8b0; height:60px;">
                <div class="container">
                    <!-- LOGO -->
                    <?php
                        $sql_logo = "SELECT LOGO FROM admum_setup_logo WHERE POSISI = 'Hor'";
                        $qry_logo = $this->db->query($sql_logo)->row();
                        $logo = '';
                        if($qry_logo->LOGO == null || $qry_logo->LOGO == ""){
                            $logo = base_url().'picture/logo-default.png';
                        }else{
                            $logo = base_url().'picture/logo/'.$qry_logo->LOGO;
                        }
                    ?>
                    <div class="topbar-left">
                        <a href="<?php echo base_url(); ?>portal" class="logo" style="margin-top:4px;">
                            <img src="<?php echo $logo; ?>" style="max-width:150px; max-height:40px;">
                        </a>
                    </div>
                    <!-- End Logo container-->

                    <div class="menu-extras">                        
                        <ul class="nav navbar-nav navbar-right pull-right" style="background-color:#11862f;">
                            <li>
                                <form role="search" class="navbar-left app-search pull-left hidden-xs" style="margin-right:0px;">
                                    <h5 style="color:#fff;"><b><?php echo strtoupper($user->NAMA_DIV);?></b></h5>
                                    <h6 style="color:#fff;"><b><?php echo $user->NAMA;?></b></h6>
                                    <!-- <h6 style="color:#fff;"><b><?php //echo $user->JABATAN;?></b></h6> -->
                                </form>
                            </li>
                            <li class="dropdown user-box">
                                <a href="" class="dropdown-toggle waves-effect waves-light profile " data-toggle="dropdown" aria-expanded="true">
                                    <img src="<?php echo base_url(); ?>files/foto_pegawai/<?=$user->FOTO;?>" alt="user-img" class="img-circle user-img">
                                </a>

                                <ul class="dropdown-menu">
                                    <?php if($level == null){ ?>
                                    <li><a href="<?php echo base_url(); ?>portal"><i class="fa fa-th m-r-5"></i> Portal Depan</a></li>
                                    <?php } ?>
                                    <li><a href="javascript:void(0)"><i class="ti-user m-r-5"></i> Profile</a></li>
                                    <li><a href="javascript:void(0)"><i class="ti-settings m-r-5"></i> Settings</a></li>
                                    <li><a href="javascript:void(0)"><i class="ti-lock m-r-5"></i> Lock screen</a></li>
                                    <li><a href="<?=base_url();?>logout"><i class="ti-power-off m-r-5"></i> Logout</a></li>
                                </ul>
                            </li>
                        </ul>
                        <ul class="nav navbar-nav navbar-right">
                            <li>
                                <!-- Notification -->
                                <div class="notification-box">
                                    <ul class="list-inline m-b-0">
                                        <li>
                                            <a class="right-bar-toggle" href="javascript:void(0);" id="show_notif">
                                                <i class="fa fa-bell-o"></i>
                                            </a>
                                            <span class="badge badge-primary" id="tot_pasien">0</span>
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
                                <a href="<?php echo base_url(); ?>lab/lab_home_c">
                                    <i class="zmdi zmdi-view-dashboard"></i> <span> Dashboard </span> 
                                </a>
                            </li>

                            <?PHP 
                            $get_menu2 = $this->master_model_m->get_menu_2($id_user, 11);
                            foreach ($get_menu2 as $key => $menu2) {
                            ?>

                                <?PHP if($menu2->LINK == null || $menu2->LINK == ""){ ?>
                                    <li <?php if($master_menu == $menu2->MASTER){ echo 'class="has-submenu active"';}else{echo 'class="has-submenu"';} ?> >
                                        <a href="#"><i class="<?=$menu2->ICON;?>"></i><span> <?=$menu2->NAMA;?> </span> </a>
                                        <ul class="submenu">

                                            <?PHP 
                                                $get_menu3 = $this->master_model_m->get_menu_3($id_user, $menu2->ID);
                                                foreach ($get_menu3 as $key => $menu3) {
                                            ?>

                                            <li <?php if($view == $menu3->VIEW){ echo 'class="active"';}else{echo '';} ?> >
                                                <a href="<?php echo base_url().$menu3->LINK; ?>">
                                                    <i class="fa fa-caret-right"></i> &nbsp; <?=$menu3->NAMA;?>
                                                </a>
                                            </li>

                                            <?PHP } ?>

                                        </ul>
                                    </li>
                                <?PHP } else { ?>
                                    <li <?php if($view == $menu2->VIEW){ echo 'class="active"';}else{echo '';} ?> >
                                        <a href="<?php echo base_url().$menu2->LINK;?>">
                                            <i class="<?=$menu2->ICON;?>"></i> <span> <?=$menu2->NAMA;?> </span> 
                                        </a>
                                    </li>
                                <?PHP } ?>

                            <?PHP } ?>
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

                    <?php if($view == 'home'){ ?>
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
                                    <button type="button" class="btn btn-success waves-effect waves-light m-t-15" id="btn_closing" data-original-title="Digunakan untuk menghentikan Nomor Antrian dan me-reset ulang." title="" data-placement="top" data-toggle="tooltip">
                                        <b>Closing Antrian</b> <i class="fa fa-hand-stop-o"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php }else{ ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="card-box widget-user">
                            <div>
                                <img alt="user" class="img-responsive img-circle" src="<?php echo base_url(); ?>picture/Recent-Documents-icon.png">
                                <div class="wid-u-info">
                                    <h4 class="m-t-0 m-b-5">Shift</h4>
                                    <h2 class="text-success" id="shift_txt">0</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
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
                                    <h2 class="text-danger"><?php echo date('d'); ?>-<?php echo $bulan[date('n')]; ?>-<?php echo date('Y'); ?></h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php $this->load->view($page); ?>

                <br>
                <!-- Footer -->
                <footer class="footer text-right" style="position: relative;">
                    <div class="container">
                        <div class="row">
                            <div class="col-xs-6">
                                © 2016 Aplikasi Rumah Sakit
                            </div>
                            <div class="col-xs-6">
                                <ul class="pull-right list-inline m-b-0">
                                    <?php if($level == null){ ?>
                                    <li><a href="<?php echo base_url(); ?>portal"><i class="fa fa-th m-r-5"></i> Portal Depan</a></li>
                                    <?php } ?>
                                    <li>
                                        <a href="#" style="color:#887d59;"><i class="fa fa-info-circle"></i> Tentang</a>
                                    </li>
                                    <li>
                                        <a href="#" style="color:#887d59;"><i class="fa fa-question-circle"></i> Bantuan</a>
                                    </li>
                                    <li>
                                        <a href="#" style="color:#887d59;"><i class="fa fa-phone-square"></i> Kontak Kami</a>
                                    </li
                                </ul>
                            </div>
                        </div>
                    </div>
                </footer>
                <!-- End Footer -->

            </div>
            <!-- end container -->

            <button class="btn btn-primary" data-toggle="modal" data-target="#myModal1_ps_baru" id="popup_pasien_baru" style="display:none;">Standard Modal</button>
            <div id="myModal1_ps_baru" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
                <div class="modal-dialog" style="width:50%;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title" id="myModalLabel">Pasien Baru</h4>
                        </div>
                        <div class="modal-body">
                            <div class="table-responsive">
                                <div class="scroll-y">
                                    <table id="tabel_pasien_home" class="table table-hover table-bordered">
                                        <thead>
                                            <tr class="kuning_popup">
                                                <th style="color:#fff; text-align:center;">No</th>
                                                <th style="color:#fff; text-align:center;">Tgl / Waktu</th>
                                                <th style="color:#fff; text-align:center;">No. RM</th>
                                                <th style="color:#fff; text-align:center;">Nama Pasien</th>
                                                <th style="color:#fff; text-align:center;">Jenis Kelamin</th>
                                                <th style="color:#fff; text-align:center;">Pasien Dari</th>
                                                <th style="color:#fff; text-align:center;">#</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                        </tbody>
                                    </table>  
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_pas">Tutup</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
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

        <!-- Modal-Effect -->
        <script src="<?php echo base_url(); ?>assets/plugins/custombox/dist/custombox.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/plugins/custombox/dist/legacy.min.js"></script>

        <!-- Toastr js -->
        <script src="<?php echo base_url(); ?>assets/plugins/toastr/toastr.min.js"></script>

        <!--JS Devan-->
        <script src="<?php echo base_url(); ?>js-devan/alert.js"></script>
        <script src="<?php echo base_url(); ?>js-devan/js-form.js"></script>
        <script src="<?php echo base_url(); ?>js-devan/pagination.js"></script>

        <!-- Plugins Js -->
        <script src="<?php echo base_url(); ?>assets/plugins/bootstrap-inputmask/bootstrap-inputmask.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>assets/plugins/moment/moment.js"></script>
        <script src="<?php echo base_url(); ?>assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
        <script src="<?php echo base_url(); ?>assets/plugins/select2/dist/js/select2.min.js" type="text/javascript"></script>

        
        <script src="<?php echo base_url(); ?>assets/plugins/jquery-knob/jquery.knob.js"></script>
        <!-- Dashboard init -->
        <script src="<?php echo base_url(); ?>assets/pages/jquery.dashboard.js"></script>

        <!-- file uploads js -->
        <script src="<?=base_url();?>assets/plugins/fileuploads/js/dropify.min.js"></script>

        <!-- App js -->
        <script src="<?php echo base_url(); ?>assets/pages/datatables.init.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/jquery.core.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/jquery.app.js"></script>
        <script src="<?=base_url();?>assets/dialog/js/main.js"></script>
        <script src="<?php echo base_url(); ?>dist/js/datepicker.js"></script>

        <!-- Include English language -->
        <script src="<?php echo base_url(); ?>dist/js/i18n/datepicker.en.js"></script>
        <script type="text/javascript">
        var snd = new Audio("<?php echo base_url(); ?>sound/nokia_tune_new.mp3"); // buffers automatically when created
        var timer = 0;
        var level = "<?php echo $level; ?>";
        var tdk = "<?php echo $view; ?>";

        jQuery(document).ready(function() {
            $('#datatable').dataTable();
            $('#datatable-keytable').DataTable( { keys: true } );
            $('#datatable-responsive').DataTable();
            $('#datatable-scroller').DataTable( { ajax: "<?php echo base_url(); ?>assets/plugins/datatables/json/scroller-demo.json", deferRender: true, scrollY: 380, scrollCollapse: true, scroller: true } );
            var table = $('#datatable-fixed-header').DataTable( { fixedHeader: true } );
            $(".select2").select2();

            toastr.options = {
              "closeButton": false,
              "debug": false,
              "newestOnTop": false,
              "progressBar": true,
              "positionClass": "toast-bottom-left",
              "preventDuplicates": false,
              "showDuration": "300",
              "hideDuration": "1000",
              "timeOut": "5000",
              "extendedTimeOut": "1000",
              "showEasing": "swing",
              "hideEasing": "linear",
              "showMethod": "fadeIn",
              "hideMethod": "fadeOut"
            }

            if(level == null || level == ""){
                
            }else{
                if(tdk == "home"){
                    get_notif_pasien();
                    timer = setInterval(function () {
                        get_notif_pasien();
                    }, 7000);
                }else{
                    snd.pause();
                }
            }

            $('#show_notif').click(function(){
                $('#popup_pasien_baru').click();
                data_pasien_baru();
            });
        });

        TableManageButtons.init();

        $('.dropify').dropify({
            messages: {
                'default': 'Drag and drop a file here or click',
                'replace': 'Drag and drop or click to replace',
                'remove': 'Remove',
                'error': 'Ooops, something wrong appended.'
            },
            error: {
                'fileSize': 'The file size is too big (1M max).'
            }
        });

        function startTime() {
            var today = new Date();
            var h = today.getHours();
            var m = today.getMinutes();
            var s = today.getSeconds();
            m = checkTime(m);
            s = checkTime(s);

            if(h >= 7 && h < 14){
                $('#shift_txt').html('1 (Satu)');
            }else if(h >= 14 && h < 21){
                $('#shift_txt').html('2 (Dua)');
            }else{
                $('#shift_txt').html('3 (Tiga)');
            }

            document.getElementById('waktu_txt').innerHTML = h + ":" + m + ":" + s;
            var t = setTimeout(startTime, 500);
        }

        function checkTime(i) {
            if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
            return i;
        }

        function get_notif_pasien(){
            $.ajax({
                url : '<?php echo base_url(); ?>lab/lab_home_c/notif_pasien_baru',
                type : "GET",
                dataType : "json",
                success : function(res){
                    if(res['TOTAL'] == 0){
                        $('#ketap_ketip').hide();
                        snd.pause();
                    }else{
                        $('#ketap_ketip').show();
                        toastr["success"]("Ada pasien baru!");
                        // snd.play();
                    }

                    $('#tot_pasien').html(res['TOTAL']);
                }
            });
        }

        function data_pasien_baru(){
            $.ajax({
                url : '<?php echo base_url(); ?>lab/lab_home_c/data_pasien_baru',
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
                            
                            var aksi = '<button type="button" class="btn btn-success btn-sm" onclick="terima_pasien('+result[i].ID+');">Proses Tindakan</button>';

                            $tr +=  '<tr>'+
                                    '   <td style="cursor:pointer; vertical-align:middle; text-align:center;">'+no+'</td>'+
                                    '   <td style="cursor:pointer; vertical-align:middle;">'+result[i].TANGGAL+' - '+result[i].WAKTU+'</td>'+
                                    '   <td style="cursor:pointer; vertical-align:middle;">'+result[i].KODE_PASIEN+'</td>'+
                                    '   <td style="cursor:pointer; vertical-align:middle;">'+result[i].NAMA+'</td>'+
                                    '   <td style="cursor:pointer; vertical-align:middle; text-align:center;">'+result[i].JENIS_KELAMIN+'</td>'+
                                    '   <td style="cursor:pointer; vertical-align:middle; text-align:center;">'+result[i].TIPE+'</td>'+
                                    '   <td style="vertical-align:middle;" align="center">'+aksi+'</td>'+
                                    '</tr>';
                        }
                    }

                    $('#tabel_pasien_home tbody').html($tr);
                }
            });
        }

        function tot_pasien_terima(){
            $.ajax({
                url : '<?php echo base_url(); ?>lab/lab_home_c/tot_pasien_terima',
                type : "POST",
                dataType : "json",
                success : function(row){
                    $('#tot_dari_adm').html(row['adm']['TOTAL']);
                    $('#tot_dari_poli').html(row['poli']['TOTAL']);
                }
            });
        }

        function terima_pasien(id){
            $.ajax({
                url : '<?php echo base_url(); ?>lab/lab_home_c/terima_pasien',
                data : {id:id},
                type : "POST",
                dataType : "json",
                success : function(res){
                    $('#tutup_pas').click();
                    data_pasien();
                    data_pasien_baru();
                }
            });
        }
        </script>
    </body>
</html>
<!DOCTYPE html>
<?PHP
$base_url2 =  ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ?  "https" : "http");
$base_url2 .=  "://".$_SERVER['HTTP_HOST'];
$base_url2 .=  str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);
?>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
        <meta name="author" content="Coderthemes">

        <!-- App Favicon -->
        <link rel="shortcut icon" href="<?php echo base_url(); ?>picture/11.ico">

        <!-- App title -->
        <title><?php echo $title; ?></title>

        <!-- App CSS -->
        <link href="<?php echo $base_url2; ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo $base_url2; ?>assets/css/core.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo $base_url2; ?>assets/css/components.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo $base_url2; ?>assets/css/icons.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo $base_url2; ?>assets/css/pages.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo $base_url2; ?>assets/css/menu.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo $base_url2; ?>assets/css/responsive.css" rel="stylesheet" type="text/css" />

        <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->

        <script src="<?php echo $base_url2; ?>assets/js/modernizr.min.js"></script>
    </head>

    <body style="overflow:hidden;">
        <div class="account-pages"></div>
        <div class="clearfix"></div>
        <div class="wrapper-page">
            <div class="text-center">
                
            </div>
        	<div class="card-box">
                <div class="text-center">
                    <img src="<?php echo $base_url2; ?>picture/jtech-logo.png" style="max-height:150px; max-width:290px;">
                </div>
                <div class="panel-body">
                    <form class="form-horizontal m-t-20" action="<?=base_url();?>login_c/login" method="post">
                        <?PHP if($this->session->flashdata('gagal')){ ?>
                        <div class="alert alert-danger alert-dismissable" style="color: #b96463; font-size: 15px;">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            Maaf, Username atau Password salah.
                        </div>
                        <?PHP }else if($this->session->flashdata('sudah_login')){ ?>
                        <div class="alert alert-danger alert-dismissable" style="color: #b96463; font-size: 15px;">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            Maaf, Username ini telah login. Silahkan memakai username sesuai dengan username Anda.
                        </div>
                        <?php } ?>

                        <div class="form-group">
                            <div class="col-xs-12">
                                <input class="form-control" type="text" required="" name="username" placeholder="Username">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-xs-12">
                                <div class="input-group">
                                    <input type="password" class="form-control" name="password" id="password" placeholder="Password">
                                    <span class="input-group-btn">
                                        <button class="btn btn-default" type="button" id="showHide"><i class="fa fa-eye"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-xs-12">
                                <div class="checkbox checkbox-custom">
                                    <input id="checkbox-signup" type="checkbox">
                                    <label for="checkbox-signup">
                                        Remember me
                                    </label>
                                </div>

                            </div>
                        </div>

                        <div class="form-group text-center m-t-30">
                            <div class="col-xs-12">
                                <input type="submit" class="btn btn-custom btn-bordred btn-block " name="login" value="Log In" />
                            </div>
                        </div>
                    </form>

                </div>
            </div>
            <!-- end card-box-->

            <div class="row">
                <div class="col-sm-12 text-center">
                    <p class="text-muted">
                        <a href="javascript:void(0);" class="text-primary m-l-5" style="cursor:default;">
                            <b style="color: #fff;">© 2018 Aplikasi Rumah Sakit</b>
                        </a>
                    </p>
                </div>
            </div>
            
        </div>
        <!-- end wrapper page -->
        

        
    	<script>
            var resizefunc = [];
        </script>

        <!-- jQuery  -->
        <script type="text/javascript" src="<?php echo base_url(); ?>js-devan/jquery-1.11.1.min.js"></script>
        <script src="<?php echo $base_url2; ?>assets/js/jquery.min.js"></script>
        <script src="<?php echo $base_url2; ?>assets/js/bootstrap.min.js"></script>
        <script src="<?php echo $base_url2; ?>assets/js/detect.js"></script>
        <script src="<?php echo $base_url2; ?>assets/js/fastclick.js"></script>
        <script src="<?php echo $base_url2; ?>assets/js/jquery.slimscroll.js"></script>
        <script src="<?php echo $base_url2; ?>assets/js/jquery.blockUI.js"></script>
        <script src="<?php echo $base_url2; ?>assets/js/waves.js"></script>
        <script src="<?php echo $base_url2; ?>assets/js/wow.min.js"></script>
        <script src="<?php echo $base_url2; ?>assets/js/jquery.nicescroll.js"></script>
        <script src="<?php echo $base_url2; ?>assets/js/jquery.scrollTo.min.js"></script>

        <!-- App js -->
        <script src="<?php echo $base_url2; ?>assets/js/jquery.core.js"></script>
        <script src="<?php echo $base_url2; ?>assets/js/jquery.app.js"></script>

        <script type="text/javascript">
        $(document).ready(function() {
          $("#showHide").click(function() {
            if ($("#password").attr("type") == "password") {
              $("#password").attr("type", "text");
              $('#showHide').removeClass('btn-default');
              $('#showHide').addClass('btn-success');
            } else {
              $("#password").attr("type", "password");
              $('#showHide').removeClass('btn-success');
              $('#showHide').addClass('btn-default');
            }
          });
        });
        </script>
	</body>
</html>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
        <meta name="author" content="Coderthemes">

        <!-- App Favicon -->
        <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/images/favicon.ico">

        <!-- App title -->
        <title><?=$title;?></title>

        <!-- App CSS -->
        <link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>assets/css/core.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>assets/css/components.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>assets/css/icons.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>assets/css/pages.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>assets/css/menu.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>assets/css/responsive.css" rel="stylesheet" type="text/css" />

        <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->

        <script src="<?php echo base_url(); ?>assets/js/modernizr.min.js"></script>

    </head>
    <body>
        <div class="account-pages"></div>
        <div class="clearfix"></div>
        <div class="wrapper-page">
            <div class="text-center">
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
                <img src="<?php echo $logo; ?>" style="max-height:150px; max-width:290px;">
            </div>
        	<div class="m-t-40 card-box">
                <div class="text-center">
                    <h4 class="text-uppercase font-bold m-b-0">Halaman Terkunci</h4>
                </div>
                <div class="panel-body">
                    <?PHP if($this->session->flashdata('gagal_buka')){ ?>
                    <div class="alert alert-danger alert-dismissable" style="color: #b96463; font-size: 15px;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        Maaf, Password Anda salah.
                    </div>
                    <?PHP } ?>
                    <form method="post" action="<?php echo base_url(); ?>lockscreen_c/unlock" role="form" class="text-center">
						<div class="user-thumb">
							<img src="<?php echo base_url(); ?>picture/lock.png" class="img-responsive img-circle img-thumbnail" alt="thumbnail">
						</div>
						<div class="form-group">
							<?php
                                $sess_user = $this->session->userdata('masuk_rs');
                                $id_user = $sess_user['id'];
                                $sql = "
                                    SELECT
                                        a.NAMA
                                    FROM kepeg_pegawai a
                                    WHERE a.ID = '$id_user'
                                ";
                                $qry = $this->db->query($sql)->row();
                            ?>
                            <h4 class="m-t-10 header-title"><b><?php echo $qry->NAMA; ?></b></h4>
							<div class="input-group m-t-30">
								<input type="password" class="form-control" name="password" placeholder="Password" required="">
								<span class="input-group-btn">
									<button type="submit" class="btn btn-primary w-sm waves-effect waves-light">
										Buka
									</button>
								</span>
							</div>
						</div>
					</form>
                </div>
            </div>
            <!-- end card-box -->

        </div>
        <!-- end wrapper -->

    	<script>
            var resizefunc = [];
        </script>

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

        <!-- App js -->
        <script src="<?php echo base_url(); ?>assets/js/jquery.core.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/jquery.app.js"></script>

	</body>
</html>
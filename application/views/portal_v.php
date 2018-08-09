<!DOCTYPE html>
<?PHP
$base_url2 =  ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ?  "https" : "http");
$base_url2 .=  "://".$_SERVER['HTTP_HOST'];
$base_url2 .=  str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);
?>
<html class="no-js">
<head>
  <meta charset="utf-8" />
  <!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame -->
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <!-- Mobile viewport optimized: h5bp.com/viewport -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
   

  <title><?php echo $title; ?></title>

  <meta name="robots" content="noindex, nofollow">
  <meta name="description" content="BootMetro : Simple and complete web UI framework to create web apps with Windows 8 Metro user interface." />
  <meta name="keywords" content="bootmetro, modern ui, modern-ui, metro, metroui, metro-ui, metro ui, windows 8, metro style, bootstrap, framework, web ramework, css, html" />
  <meta name="author" content="AozoraLabs by Marcello Palmitessa"/>
   
  <!-- remove or comment this line if you want to use the local fonts -->
  <link href='http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700' rel='stylesheet' type='text/css'>

  <link rel="stylesheet" type="text/css" href="<?=$base_url2;?>assets/portal/css/bootmetro.css">
  <link rel="stylesheet" type="text/css" href="<?=$base_url2;?>assets/portal/css/bootmetro-responsive.css">
  <link rel="stylesheet" type="text/css" href="<?=$base_url2;?>assets/portal/css/bootmetro-icons.css">
  <link rel="stylesheet" type="text/css" href="<?=$base_url2;?>assets/portal/css/bootmetro-ui-light.css">
  <link rel="stylesheet" type="text/css" href="<?=$base_url2;?>assets/portal/css/datepicker.css">

  <!--  these two css are to use only for documentation -->
  <link rel="stylesheet" type="text/css" href="<?=$base_url2;?>assets/portal/css/demo.css">
  <link href="<?=$base_url2;?>assets/portal/css/bootstrap-responsive.css" rel="stylesheet">

  <!-- Le fav and touch icons -->
  <link rel="shortcut icon" href="<?php echo $base_url2; ?>picture/hospital.ico">
  <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?=$base_url2;?>assets/portal/ico/apple-touch-icon-144-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?=$base_url2;?>assets/portal/ico/apple-touch-icon-114-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?=$base_url2;?>assets/portal/ico/apple-touch-icon-72-precomposed.png">
  <link rel="apple-touch-icon-precomposed" href="<?=$base_url2;?>assets/portal/ico/apple-touch-icon-57-precomposed.png">

  <link href="<?php echo $base_url2; ?>css-devan/style-devan.css" rel="stylesheet" type="text/css" />
  
   <!-- All JavaScript at the bottom, except for Modernizr and Respond.
      Modernizr enables HTML5 elements & feature detects; Respond is a polyfill for min/max-width CSS3 Media Queries
      For optimal performance, use a custom Modernizr build: www.modernizr.com/download/ -->
  <script src="<?=$base_url2;?>assets/portal/js/modernizr-2.6.2.min.js"></script>
</head>

<body>
    <?PHP 
        $sess_user = $this->session->userdata('masuk_rs');
        $id_user = $sess_user['id'];
        $user = $this->master_model_m->get_user_info($id_user);
    ?>

   <div id="wrap">
   
      <!-- Header
      ================================================== -->
      <div id="nav-bar" class="">
         <div class="pull-left">
            <div id="header-container">
               <h5>SISTEM INFORMASI RUMAH SAKIT</h5>
               <div class="dropdown">
                  <a class="header-dropdown dropdown-toggle" href="javascript:;" style="color:#FFF;">
                     MY COMPANY
                  </a>
                  <br>
                  <button type="button" onclick="window.location='<?php echo base_url(); ?>logout';" class="btn btn-danger">Logout</button>
            </div>
            </div>
         </div>
         <div class="pull-right" style="margin-right: 40px;">
            <div id="top-info" class="pull-right">
            <a id="settings_old" href="#" class="win-command pull-left">
                <img class="win-commandicon win-commandring" src="<?=$base_url2;?>files/foto_pegawai/<?=$user->FOTO;?>">
            </a>
            <div class="pull-right">
               <h3><?=strtoupper($user->NAMA_DIV);?></h3>
               <h4><?=$user->JABATAN;?></h4>
               <h4><?=$user->NAMA;?></h4>
            </div>
         </div>
      </div>
      </div>
   
      <!--<div id="metro-container" class="-container">-->
         <!--<div class="row">-->
            <!--<div id="hub" class="metro">-->
               <br><br><br>
               <div class="metro panorama" style="margin: 0 15px; width: 90%;">
                  <div class="panorama-sections" style="width: auto !important;">   
                     <div class="panorama-section tile-span-12" style="margin-right: 20px;">
   
                        <h2>Menu aplikasi</h2>
   
                        <?PHP 
                        $no = 0;
                        $warna = "";
                        foreach ($dt_menu as $key => $row) { 
                            $no++;
                            if($no == 7){
                                $no = 1;
                            }

                            if($no == 1){
                                $warna = "blue";
                            } else if($no == 2){
                                $warna = "green";
                            } else if($no == 3){
                                $warna = "purple";
                            } else if($no == 4){
                                $warna = "orange";
                            } else if($no == 5){
                                $warna = "greenDark"; 
                            } else if($no == 6){
                                $warna = "red";
                            } 
                        ?>

                        <a class="tile app bg-color-<?=$warna;?>" href="<?php echo base_url().$row->LINK; ?>" style="background-color:#bbe5fd;">
                          <div class="image-wrapper" style="margin-top: -5px;">
                              <span class="icon <?=$row->ICON;?>"></span>
                          </div>
                          <div class="textover-wrapper transparent" style="padding: 2px 0; width: 100%;">
                             <div class="text2" style="text-align:center; margin-top: 12px;"><b><?=strtoupper($row->NAMA);?></b></div>
                          </div>
                        </a>

                        <?PHP } ?>

                       <a class="tile app bg-color-darken" href="<?php echo base_url(); ?>antrian/antrian_home_c">
                          <div class="image-wrapper" style="margin-top: -5px;">
                              <span class="icon icon-users"></span>
                          </div>
                          <div class="textover-wrapper transparent" style="padding: 2px 0; width: 100%;">
                             <div class="text2" style="text-align:center; margin-top: 12px;"><b>ANTRIAN</b></div>
                          </div>
                        </a>


                     </div>

   
                  </div>
               </div>

            <!--</div>-->
         <!--</div>-->
      <!--</div>-->
   
   </div>
   <div id="charms" class="win-ui-dark slide">
      <div id="theme-charms-section" class="charms-section">
         <div class="charms-header">
            <a href="#" class="close-charms win-backbutton"></a>
            <h2>Settings</h2>
         </div>
   
         <div class="row-fluid">
            <div class="span12">
   
               <form class="">
                  <label for="win-theme-select">Change theme:</label>
                  <select id="win-theme-select" class="">
                     <option value="metro-ui-light">Light</option>
                     <option value="metro-ui-dark">Dark</option>
                  </select>
               </form>
   
            </div>
         </div>
      </div>
   </div>

  <div id="popup_portal">
    <div class="window_portal">
      
    </div>
  </div>

   <!-- Grab Google CDN's jQuery. fall back to local if necessary -->
   <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
   <script>window.jQuery || document.write("<script src='assets/js/jquery-1.8.3.min.js'>\x3C/script>")</script>

   <!--[if IE 7]>
   <script type="text/javascript" src="scripts/bootmetro-icons-ie7.js">
   <![endif]-->

   <script type="text/javascript" src="<?=$base_url2;?>assets/portal/js/bootstrap.min.js"></script>
   <script type="text/javascript" src="<?=$base_url2;?>assets/portal/js/bootmetro-panorama.js"></script>
   <script type="text/javascript" src="<?=$base_url2;?>assets/portal/js/bootmetro-pivot.js"></script>
   <script type="text/javascript" src="<?=$base_url2;?>assets/portal/js/bootmetro-charms.js"></script>
   <script type="text/javascript" src="<?=$base_url2;?>assets/portal/js/bootstrap-datepicker.js"></script>

   <script type="text/javascript" src="<?=$base_url2;?>assets/portal/js/jquery.mousewheel.min.js"></script>
   <script type="text/javascript" src="<?=$base_url2;?>assets/portal/js/jquery.touchSwipe.min.js"></script>

   <script type="text/javascript" src="<?=$base_url2;?>assets/portal/js/holder.js"></script>
   <!--<script type="text/javascript" src="assets/portal/js/perfect-scrollbar.with-mousewheel.min.js"></script>-->
   <script type="text/javascript" src="<?=$base_url2;?>assets/portal/js/demo.js"></script>
</body>
</html>

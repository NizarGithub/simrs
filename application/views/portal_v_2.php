<!DOCTYPE html>
<html> 
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
        <meta name="author" content="Coderthemes">

        <style type="text/css">
        #btn-pojok{
            position: absolute;
            right:    0;
            bottom:   0;
        } 
        </style>
        <link rel="shortcut icon" href="<?php echo base_url(); ?>picture/hospital.ico">

        <!--Portal CSS-->
        <link href="<?php echo base_url(); ?>css-devan/portal.css" type="text/css" rel="stylesheet"/>
        
        <title><?php echo $title; ?></title>
    </head>  

    <body>
        <div id="dashboard-portal">
            <h1><?php echo strtoupper($title); ?></h1>
            <br/>

            <?PHP 
            foreach ($dt_menu as $key => $row) {
            ?>

            <div class="tile-portal">
                <a href="<?php echo base_url().$row->LINK; ?>">
                    <img src="<?php echo base_url().$row->ICON;?>"><br />
                    <?=$row->NAMA;?>
                </a>
            </div>

            <?PHP } ?>

            <div class="tile-portal">
                <a href="<?php echo base_url(); ?>antrian/antrian_home_c"> 
                    <img src="<?php echo base_url() ?>picture/portal/antrian.png"><br />
                    Antrian
                </a>
            </div>
            <div class="tile-portal">
                <a href="<?php echo base_url(); ?>logout">
                    <img src="<?php echo base_url() ?>picture/portal/exit.png"><br />
                    Log Out
                </a>
            </div>
            <div class="clear"></div>
            <div id="inside-footer">
                © 2016 Powered by CV JTECH
            </div>
        </div>
    </body>
</html>
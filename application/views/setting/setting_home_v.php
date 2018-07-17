<style type="text/css">
a {
    color: #36C;
    text-decoration: none;
} 

#dashboard-depan {
    width: 100%; 
    height: 400px;
    background-color: #fff;
    border-radius: 5px;
}
.tile-depan {
    text-align: center;
    float: left;
    margin: 10px 0;
    color: #07A;
    font: bold 12px tahoma;
    height: 150px;
    width: 148px;
}
.tile-depan img {
    width: 120px;
    height: 120px; 
    padding: 10px;
    -moz-border-radius: 10px;
    -webkit-border-radius: 10px;
    border-radius: 10px;
    margin-bottom: 5px;
}
.tile-depan a:hover {
    color: #770;
}
.tile-depan a:hover img {
    border: 2px solid #00a0f0;
    background: #c4dff6;
}
</style>

<?PHP 
    $sess_user = $this->session->userdata('masuk_rs');
    $id_user = $sess_user['id'];
    $user = $this->master_model_m->get_user_info($id_user);
?>

<div class="col-md-12">
    <div id="dashboard-depan"> 
    <?PHP 
        $get_menu2 = $this->master_model_m->get_menu_2($id_user, 12);
        foreach ($get_menu2 as $key => $menu2) {
            $link = base_url().$menu2->LINK;
            $icon = base_url().$menu2->GAMBAR_ICON;
            if($menu2->LINK != null || $menu2->LINK != ""){
    ?>
        <div class="tile-depan">
            <a href="<?php echo $link; ?>">
                <img src="<?php echo $icon; ?>"><br />
                <?php echo $menu2->NAMA; ?>
            </a>
        </div>
    <?php
            }else{
                $get_menu3 = $this->master_model_m->get_menu_3($id_user, $menu2->ID);
                foreach ($get_menu3 as $key => $menu3) {
                    $link3 = base_url().$menu3->LINK;
                    $icon3 = base_url().$menu3->GAMBAR_ICON;
    ?>
                    <div class="tile-depan">
                        <a href="<?php echo $link3; ?>">
                            <img src="<?php echo $icon3; ?>"><br />
                            <?php echo $menu3->NAMA; ?>
                        </a>
                    </div>
    <?php
                }
            }
        }
    ?>
    </div>
</div>


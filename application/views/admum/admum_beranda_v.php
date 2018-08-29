<script type="text/javascript" src="<?php echo base_url(); ?>js-devan/jquery-1.11.1.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){

});

function klik_menu(link){
    window.location = link;
}
</script>

<style type="text/css">
a {
    color: #36C;
    text-decoration: none;
}

#dashboard-depan {
    width: 100%;
    height: 700px;
    /*background-color: #fff;*/
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

<div class="row">
<?PHP 
    $get_menu2 = $this->master_model_m->get_menu_2($id_user, 1);
    foreach ($get_menu2 as $key => $menu2) {
        $link = base_url().$menu2->LINK;
        $icon = base_url().$menu2->GAMBAR_ICON;
        if($menu2->LINK != null || $menu2->LINK != ""){
            $id1 = $menu2->ID;
            $text1 = "";
            if($id1 % 2 == 0){
                $text1 = "text-custom";
            }else{
                $text1 = "text-success";
            }
?>
    <div class="col-lg-3 col-md-6" onclick="klik_menu('<?php echo $link; ?>');" style="cursor: pointer;">
        <div class="card-box widget-user">
            <div>
                <img alt="user" class="img-responsive img-circle" src="<?php echo $icon; ?>">
                <div class="wid-u-info">
                    <h4 class="m-t-0 m-b-5"><?php echo $menu2->NAMA; ?></h4>
                </div>
                <small class="<?php echo $text1; ?>" style="margin-left: 20px;"><b><?php echo str_replace('_', ' ', strtoupper($menu2->VIEW)); ?></b></small>
            </div>
        </div>
    </div>
<?php
        }else{
            $get_menu3 = $this->master_model_m->get_menu_3($id_user, $menu2->ID);
                foreach ($get_menu3 as $key => $menu3) {
                    $link3 = base_url().$menu3->LINK;
                    $icon3 = base_url().$menu3->GAMBAR_ICON;
                    $id = $menu3->ID;
                    $text = "";
                    if($id % 2 == 0){
                        $text = "text-success";
                    }else{
                        $text = "text-custom";
                    }
?>
    <div class="col-lg-3 col-md-6" onclick="klik_menu('<?php echo $link3; ?>');" style="cursor: pointer;">
        <div class="card-box widget-user">
            <div>
                <img alt="user" class="img-responsive img-circle" src="<?php echo $icon3; ?>">
                <div class="wid-u-info">
                    <h4 class="m-t-0 m-b-5"><?php echo $menu3->NAMA; ?></h4>
                    <small class="<?php echo $text; ?>"><b><?php echo str_replace('_', ' ', strtoupper($menu3->VIEW)); ?></b></small>
                </div>
            </div>
        </div>
    </div>
<?php
                }
        }
    }
?>
</div>
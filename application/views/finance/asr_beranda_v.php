<style type="text/css">
a {
    color: #36C;
    text-decoration: none;
} 

#dashboard-depan {
    width: 100%;
    height: 600px;
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

<div class="row">
<?PHP 
$get_data_asuransi = $this->master_model_m->get_data_asuransi();
foreach ($get_data_asuransi as $key => $asr) {
    $label = '';
    if($asr->ID % 2 == 0){
        $label = 'label label-success';
    }else{
        $label = 'label label-purple';
    }
?>
    <div class="col-md-3">
        <div class="text-center card-box">
            <a href="<?php echo base_url();?>asuransi/list_asuransi_c/index/<?=$asr->ID;?>">
                <div>
                    <img alt="profile-image" class="img-circle thumb-xl img-thumbnail m-b-10" src="<?=base_url();?>files/asuransi/<?=$asr->LOGO;?>" style="width: 75px; height: 75px;">
                    <p class="text-muted font-14">
                        <span class="<?php echo $label; ?>"><?=$asr->NAMA_ASURANSI;?></span>
                    </p>
                </div>
            </a>
        </div>
    </div>
<?PHP 
}
?>
</div>
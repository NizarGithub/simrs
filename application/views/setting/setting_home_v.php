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

<div class="row">
    <div class="col-md-3">
        <?PHP 
            $get_menu2 = $this->master_model_m->get_menu_2($id_user, 12);
            foreach ($get_menu2 as $key => $menu2) {
                $link = base_url().$menu2->LINK;
                $icon = base_url().$menu2->GAMBAR_ICON;
                if($menu2->LINK != null || $menu2->LINK != ""){
        ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="card-box widget-user">
                        <a href="<?php echo $link; ?>">
                        <div>
                            <img alt="user" class="img-responsive img-circle" src="<?php echo $icon; ?>">
                            <div class="wid-u-info">
                                <h4 class="m-t-0 m-b-5"><?php echo $menu2->NAMA; ?></h4>
                                <small class="text-warning"><b><?php echo $menu2->NAMA; ?></b></small>
                            </div>
                        </div>
                        </a>
                    </div>
                </div>
            </div>
        <?php
                }else{
                    $get_menu3 = $this->master_model_m->get_menu_3($id_user, $menu2->ID);
                    foreach ($get_menu3 as $key => $menu3) {
                        $link3 = base_url().$menu3->LINK;
                        $icon3 = base_url().$menu3->GAMBAR_ICON;
        ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card-box widget-user">
                                    <a href="<?php echo $link3; ?>">
                                    <div>
                                        <img alt="user" class="img-responsive img-circle" src="<?php echo $icon3; ?>">
                                        <div class="wid-u-info">
                                            <h4 class="m-t-0 m-b-5"><?php echo $menu3->NAMA; ?></h4>
                                            <small class="text-warning"><b><?php echo $menu3->NAMA; ?></b></small>
                                        </div>
                                    </div>
                                    </a>
                                </div>
                            </div>
                        </div>
        <?php
                    }
                }
            }
        ?>    
    </div>
    <div class="col-md-9">
        <div class="card-box widget-user">
            <h4 class="header-title m-t-0 m-b-30">Log Aktivitas User</h4>
            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>User</th>
                        <th>Tanggal</th>
                        <th>Waktu</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $sql = "
                        SELECT 
                            a.*,
                            b.NAMA
                        FROM kepeg_log_aktifitas a
                        LEFT JOIN kepeg_pegawai b ON b.ID = a.ID_PEGAWAI
                        ORDER BY a.ID DESC
                    ";
                    $query = $this->db->query($sql);
                    $no = 0;
                    foreach ($query->result() as $key => $value) {
                        $no++;
                ?>
                    <tr>
                        <td style="text-align: center;"><?php echo $no; ?></td>
                        <td><?php echo $value->NAMA; ?></td>
                        <td><?php echo $value->TANGGAL; ?></td>
                        <td><?php echo $value->WAKTU; ?></td>
                        <td><?php echo $value->KETERANGAN; ?></td>
                    </tr>
                <?php
                    }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
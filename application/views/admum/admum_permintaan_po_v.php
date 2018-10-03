<script type="text/javascript" src="<?php echo base_url(); ?>js-devan/jquery-1.11.1.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){

});
</script>

<?php
$sess_user = $this->session->userdata('masuk_rs');
$id_user = $sess_user['id'];
$sql = "
	SELECT
		a.ID,
		a.ID_DEPARTEMEN,
		a.ID_DIVISI,
		b.NAMA_DEP,
		c.NAMA_DIV
	FROM kepeg_pegawai a
	LEFT JOIN kepeg_departemen b ON a.ID_DEPARTEMEN = b.ID
	LEFT JOIN kepeg_divisi c ON a.ID_DIVISI = c.ID
	WHERE a.ID = '$id_user'
";
$qry = $this->db->query($sql)->row();
$id_dep = $qry->ID_DEPARTEMEN;
$nama_dep = $qry->NAMA_DEP;
?>

<div id="popup_load">
    <div class="window_load">
        <img src="<?=base_url()?>picture/progress.gif" height="100" width="125">
    </div>
</div>

<div class="row">
    <div class="col-lg-12" id="view_data">
    	<div class="card-box">
            <ul class="nav nav-tabs">
                <li class="active" role="presentation">
                    <a data-toggle="tab" role="tab" href="#poli1"><i class="fa fa-home"></i> Permintaan ke Pengadaan Barang</a>
                </li>
            </ul>
            <div class="tab-content">
                <div id="poli1" class="tab-pane fade in active" role="tabpanel">
                	<form class="form-horizontal" role="form" action="" method="post">
                		<div class="form-group">
                            <label class="col-md-2 control-label">Kode PO</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="kode_po" id="kode_po" value="" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Divisi</label>
                            <div class="col-md-6">
                            	<input type="hidden" name="id_departemen" id="id_departemen" value="<?php echo $id_dep; ?>">
                                <input type="text" class="form-control" value="<?php echo $nama_dep; ?>" readonly>
                            </div>
                        </div>

                	</form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<?php if($this->ion_auth->logged_in()) {?>
</div><!--..//page-wrapper end -->
<?php } ?>
</div><!--..//main-wrapper end -->
<div class="sidebar-overlay" data-reff=""></div>

<script src="<?= site_url("assets/jquery-3.2.1.min.js"); ?>"></script>
<script src="<?= site_url("assets/admin/js/popper.min.js"); ?>"></script>
<script src="<?= site_url("assets/bootstrap-4.3.1/js/bootstrap.min.js") ?>"></script>
<script src="<?= site_url("assets/admin/js/select2.min.js"); ?>"></script>
<script src="<?= site_url("assets/admin/js/jquery.slimscroll.js"); ?>"></script>
<?= $before_appjs;?>
<script src="<?= site_url("assets/admin/js/app.js?".time()); ?>"></script>
<script src="<?= site_url("assets/admin/js/functions_doctor.js?".time()); ?>"></script>

<div id="loadingDiv" class="spinner-border text-primary" role="status">
    <span class="sr-only">Loading...</span>
</div>

<?= $before_body;?>

<?php $this->load->view('templates/_parts/admin_master_notifiers_view');?>
</body>
</html>
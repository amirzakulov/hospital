<?php defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('templates/_parts/doctor/doctor_master_header_view');
if($this->ion_auth->in_group(array(4, 7, 9))) {
//if(1 == 1) {
    if($this->uri->segment(2) == "settings")
    {
        $this->load->view('templates/_parts/admin_master_settings_sidebar_view');
    }
    else
    {
        $this->load->view('templates/_parts/doctor/doctor_master_sidebar_view');
    }
?>
<!--    <div class="page-wrapper">-->
    <div class="content p-0">
        <?php echo $view_content; ?>
    </div><!--..//content end -->
<!--    </div><!--..//content end -->
<?php } else { ?>
<!--  Login sahifasi uchun  -->
    <?php echo $view_content; ?>
<?php } ?>
<?php $this->load->view('templates/_parts/doctor/doctor_master_footer_view');?>
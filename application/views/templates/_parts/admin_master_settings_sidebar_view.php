<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="sidebar bg-dark" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div class="sidebar-menu">
            <ul>
                <li>
                    <a href="<?= site_url("admin") ?>"><i class="fa fa-home back-icon"></i> <span>Асосий сахифага қайтиш</span></a>
                </li>
                <li class="menu-title">Созламалар</li>
                <li class="<?= ($this->uri->segment(3) == "company" ? "active":""); ?>">
                    <a href="<?= site_url("admin/settings/company") ?>"><i class="fa fa-building"></i> <span>Ташкилот хақида</span></a>
                </li>
				<li class="<?= ($this->uri->segment(3) == "posprint" ? "active":""); ?>">
					<a href="<?= site_url("admin/settings/posprint") ?>"><i class="fa fa-print"></i> <span>POS Print</span></a>
				</li>
				<li class="<?= ($this->uri->segment(3) == "lab_print" || $this->uri->segment(3) == "uzi_print" ? "active":""); ?>">
					<a href="<?= site_url("admin/settings/lab_print") ?>"><i class="fa fa-print"></i> <span>Printer</span></a>
				</li>
                <li class="d-none">
                    <a href="localization.html"><i class="fa fa-clock-o"></i> <span>Localization</span></a>
                </li>
                <li class="d-none">
                    <a href="theme-settings.html"><i class="fa fa-picture-o"></i> <span>Theme Settings</span></a>
                </li>

                <?php if($this->ion_auth->is_admin()) {?>
                <li class="<?= ($this->uri->segment(3) == "roles_permissions" ? "active":""); ?>">
                    <a href="<?= site_url("admin/settings/roles_permissions") ?>"><i class="fa fa-key"></i> <span>Гурухлар ва уларнинг хуқуқлари</span></a>
                </li>
                <?php } ?>
                <li class="d-none">
                    <a href="email-settings.html"><i class="fa fa-envelope-o"></i> <span>Email Settings</span></a>
                </li>
                <li class="d-none">
                    <a href="invoice-settings.html"><i class="fa fa-pencil-square-o"></i> <span>Invoice Settings</span></a>
                </li>
                <li class="d-none">
                    <a href="salary-settings.html"><i class="fa fa-money"></i> <span>Salary Settings</span></a>
                </li>
                <li class="d-none">
                    <a href="notifications-settings.html"><i class="fa fa-globe"></i> <span>Notifications</span></a>
                </li>
                <li class="<?= ($this->uri->segment(3) == "change_password" ? "active":""); ?>">
                    <a href="<?= site_url("admin/settings/change_password"); ?>"><i class="fa fa-lock"></i> <span>Паролни ўзгартириш</span></a>
                </li>
                <li class="d-none">
                    <a href="leave-type.html"><i class="fa fa-cogs"></i> <span>Leave Type</span></a>
                </li>
            </ul>
        </div>
    </div>
</div>

<div class="page-wrapper">
<?php  $this->load->view('templates/_parts/admin_master_notification_view'); ?>

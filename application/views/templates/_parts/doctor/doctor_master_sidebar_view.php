<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="<?= ($this->uri->segment(3) == "dashboard" ? "active":""); ?>">
                    <a href="<?= site_url("doctor") ?>"><i class="fa fa-dashboard"></i> <span>Асосий сахифа</span></a>
                </li>
                <?php if($this->ion_auth->in_group(4)) { ?>
                <li class="<?= ($this->uri->segment(2) == "patients" ? "active":""); ?>">
                    <a href="<?= site_url("doctor/patients") ?>"><i class="fa fa-users"></i> <span>Беморлар (Кўриг)</span></a>
                </li>
                <li class="d-none <?= ($this->uri->segment(2) == "reports" ? "active":""); ?>">
                    <a href="<?= site_url("doctor/reports") ?>"><i class="fa fa-line-chart"></i> <span>Хисоботлар</span></a>
                </li>
                <?php } ?>
                <?php if($this->ion_auth->in_group(7)) { ?><!-- Laboratory -->
                <li class="<?= (
                		($this->uri->segment(2) == "patients_lab" && $this->uri->segment(3) == "") ||
						($this->uri->segment(2) == "patients_lab" && $this->uri->segment(3) == "patient") ? "active":""); ?>">
                    <a href="<?= site_url("doctor/patients_lab") ?>"><i class="fa fa-user"></i> <span>Беморлар</span></a>
                </li>
                <li class="<?= ($this->uri->segment(2) == "laboratory" ? "active":""); ?>">
                    <a href="<?= site_url("doctor/laboratory") ?>"><i class="fa fa-glass"></i> <span>Лаборатория</span></a>
                </li>
                <li class="<?= ($this->uri->segment(3) == "reports" ? "active":""); ?>">
                    <a href="<?= site_url("doctor/patients_lab/reports") ?>"><i class="fa fa-line-chart"></i> <span>Хисоботлар</span></a>
                </li>
                <?php } ?>
                <?php if($this->ion_auth->in_group(9)) { ?>
				<li class="<?= ($this->uri->segment(2) == "patients_uzi" ? "active":""); ?>">
					<a href="<?= site_url("doctor/patients_uzi") ?>"><i class="fa fa-calculator"></i> <span>Беморлар (УЗИ)</span></a>
				</li>
                <?php } ?>
                <?php if($this->ion_auth->in_group(9)) { ?>
                    <li class="<?= ($this->uri->segment(2) == "templates_uzi" ? "active":""); ?>">
                        <a href="<?= site_url("doctor/templates_uzi") ?>"><i class="fa fa-address-card"></i> <span>Шаблонлар</span></a>
                    </li>
                <?php } ?>

                <li class="<?= ($this->uri->segment(2) == "rooms" ? "active":""); ?>">
                    <a href="<?= site_url("doctor/rooms") ?>"><i class="fa fa-key"></i> <span>Хоналар</span></a>
                </li>
            </ul>
        </div>
    </div>
</div>

<div class="page-wrapper">
<?php  $this->load->view('templates/_parts/admin_master_notification_view'); ?>

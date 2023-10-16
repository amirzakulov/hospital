<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="<?= ($this->uri->segment(2) == "" ? "active":""); ?>">
                    <a href="<?= site_url("admin") ?>"><i class="fa fa-dashboard"></i> <span>Асосий сахифа</span></a>
                </li>
                <?php if($this->ion_auth->is_admin()) {?>
                <li class="submenu">
                    <a href="#"><i class="fa fa-user-md"></i> <span> Шифокорлар </span> <span class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li class="<?= ($this->uri->segment(2) == "doctors" ? "active":""); ?>">
                            <a href="<?= site_url("admin/doctors") ?>"><i class="fa fa-users"></i> <span>Шифокорлар</span></a>
                        </li>
                        <li class="<?= ($this->uri->segment(2) == "doctors_types" ? "active":""); ?>">
                            <a href="<?= site_url("admin/doctors_types") ?>"><i class="fa fa-coffee"></i> <span>Шифокорлар Мутахасислиги</span></a>
                        </li>
                    </ul>
                </li>
                <?php } ?>
                <li class="<?= ($this->uri->segment(2) == "registry" ? "active":""); ?>">
                    <a href="<?= site_url("admin/registry") ?>"><i class="fa fa-list-alt"></i> <span>Қабулхона</span></a>
                </li>
                <li class="<?= ($this->uri->segment(2) == "patients" ? "active":""); ?>">
                    <a href="<?= site_url("admin/patients") ?>"><i class="fa fa-vcard"></i> <span>Беморлар</span></a>
                </li>
                <li class="d-none">
                    <a href="schedule.html"><i class="fa fa-calendar-check-o"></i> <span>Doctor Schedule</span></a>
                </li>
                <?php if($this->ion_auth->is_admin()) {?>
                <li class="<?= $this->uri->segment(2) == "departments" ? "active":""; ?>">
                    <a href="<?= site_url("admin/departments") ?>"><i class="fa fa-hospital-o"></i> <span>Бўлимлар</span></a>
                </li>
                <?php } ?>
                <?php if($this->ion_auth->is_admin() || $this->ion_auth->groups(3)) {?>
                <li class="submenu">
                    <a href="#"><i class="fa fa-key"></i> <span> Хоналар </span> <span class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li class="<?= $this->uri->segment(2) == "rooms" ? "active":""; ?>">
                            <a href="<?= site_url("admin/rooms") ?>"><i class="fa fa-key"></i> <span>Хоналар</span></a>
                        </li>
                        <li class="<?= ($this->uri->segment(2) == "room_types" ? "active":""); ?>">
                            <a href="<?= site_url("admin/room_types") ?>"><i class="fa fa-coffee"></i> <span>Турлари</span></a>
                        </li>
                        <li class="<?= ($this->uri->segment(2) == "room_conditions" ? "active":""); ?>">
                            <a href="<?= site_url("admin/room_conditions") ?>"><i class="fa fa-coffee"></i> <span>Шароитлари</span></a>
                        </li>
                    </ul>
                </li>
                <?php } ?>
                <?php if($this->ion_auth->is_admin()){ ?>
                <li class="<?= ($this->uri->segment(2) == "laboratory" || $this->uri->segment(2) == "lab_categories") ? "active":""; ?>">
                    <a href="<?= site_url("admin/laboratory") ?>"><i class="fa fa-flask"></i> <span>Лаборатория</span></a>
                </li>
                <?php } ?>
                <?php if($this->ion_auth->is_admin()) {?>
                <li class="<?= $this->uri->segment(2) == "uzi" ? "active":""; ?>">
                    <a href="<?= site_url("admin/uzi") ?>"><i class="fa fa-laptop"></i> <span>УЗИ</span></a>
                </li>
                <?php } ?>
                <li class="<?= ($this->uri->segment(2) == "services" ? "active":""); ?>">
                    <a href="<?= site_url("admin/services") ?>"><i class="fa fa-cubes"></i> <span>Қўшимча хизматлар</span></a>
                </li>
				<?php if($this->ion_auth->is_admin()) {?>
					<li class="submenu">
						<a href="#"><i class="fa fa-book"></i> <span> Хисоботлар </span> <span class="menu-arrow"></span></a>
						<ul style="display: none;">
							<li class="<?= ($this->uri->segment(2) == "reports"  && $this->uri->segment(3) == "" ? "active":""); ?>"><a href="<?= site_url("admin/reports"); ?>"> Умумий</a></li>
							<li class="<?= ($this->uri->segment(2) == "reports" && $this->uri->segment(3) == "uzi2" ? "active":""); ?>"><a href="<?= site_url("admin/reports/uzi2"); ?>"> УЗИ</a></li>
							<li class="<?= ($this->uri->segment(2) == "reports"  && $this->uri->segment(3) == "service_modules" ?  "active":""); ?>"><a href="<?= site_url("admin/service_modules"); ?>"> Улушлар</a></li>
							<li class="d-none <?= ($this->uri->segment(3) == "patients" ? "active":""); ?>"><a href="<?= site_url("admin/reports/patients"); ?>"> Беморлар</a></li>
							<li class="d-none <?= ($this->uri->segment(3) == "cash" ? "active":""); ?>"><a href="<?= site_url("admin/reports/cash"); ?>"> Касса</a></li>
							<li class="d-none <?= ($this->uri->segment(3) == "general" ? "active":""); ?>"><a href="<?= site_url("admin/reports/general"); ?>"> Умумий</a></li>
							<li class="d-none <?= ($this->uri->segment(3) == "payments" ? "active":""); ?>"><a href="<?= site_url("admin/reports/payments"); ?>"> Тўловлар</a></li>
						</ul>
					</li>
				<?php } ?>
                <li class="submenu">
                    <a href="#"><i class="fa fa-handshake-o"></i> <span> Хамкорлар </span> <span class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li class="<?= (($this->uri->segment(2) == "partners" && $this->uri->segment(3) != "reports") ? "active":""); ?>">
                            <a href="<?= site_url("admin/partners") ?>"><i class="fa fa-list"></i> <span>Хамкорлар</span></a>
                        </li>
                        <li class="<?= (($this->uri->segment(2) == "partners" && $this->uri->segment(3) == "reports") ? "active":""); ?>">
                            <a href="<?= site_url("admin/partners/reports") ?>"><i class="fa fa-coffee"></i> <span>Ҳисоботлар</span></a>
                        </li>
                    </ul>
                </li>
				<li class="<?= ($this->uri->segment(2) == "expenses_type" ? "active":""); ?>">
					<a href="<?= site_url("admin/expense_type") ?>"><i class="fa fa-calculator"></i> <span>Чиқимлар турлари</span></a>
				</li>
				<li class="<?= ($this->uri->segment(2) == "payment_types" ? "active":""); ?>">
					<a href="<?= site_url("admin/payment_types") ?>"><i class="fa fa-credit-card"></i> <span>Тўлов турлари</span></a>
				</li>
                <?php if($this->ion_auth->is_admin()) {?>
                <li class="submenu d-none1">
                    <a href="#"><i class="fa fa-user"></i> <span> Ходимлар </span> <span class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li><a href="<?= site_url("admin/employees") ?>">Ходимлар</a></li>
<!--                        <li><a href="leaves.html">Татил(Отпуск)</a></li>-->
<!--                        <li><a href="holidays.html">Дам олиш кунлари</a></li>-->
<!--                        <li><a href="attendance.html">Давомат</a></li>-->
                    </ul>
                </li>
                <?php } ?>
                <li class="submenu d-none">
                    <a href="#"><i class="fa fa-money"></i> <span> Accounts </span> <span class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li><a href="invoices.html">Invoices</a></li>
                        <li><a href="payments.html">Payments</a></li>
                        <li><a href="expenses.html">Expenses</a></li>
                        <li><a href="taxes.html">Taxes</a></li>
                        <li><a href="provident-fund.html">Provident Fund</a></li>
                    </ul>
                </li>

                <li class="d-none">
                    <a href="chat.html"><i class="fa fa-comments"></i> <span>Chat</span> <span class="badge badge-pill bg-primary float-right">5</span></a>
                </li>
                <li class="submenu d-none">
                    <a href="#"><i class="fa fa-video-camera camera"></i> <span> Calls</span> <span class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li><a href="voice-call.html">Voice Call</a></li>
                        <li><a href="video-call.html">Video Call</a></li>
                        <li><a href="incoming-call.html">Incoming Call</a></li>
                    </ul>
                </li>
                <li class="submenu d-none">
                    <a href="#"><i class="fa fa-envelope"></i> <span> Email</span> <span class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li><a href="compose.html">Compose Mail</a></li>
                        <li><a href="inbox.html">Inbox</a></li>
                        <li><a href="mail-view.html">Mail View</a></li>
                    </ul>
                </li>
                <li class="submenu d-none">
                    <a href="#"><i class="fa fa-commenting-o"></i> <span> Blog</span> <span class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li><a href="blog.html">Blog</a></li>
                        <li><a href="blog-details.html">Blog View</a></li>
                        <li><a href="add-blog.html">Add Blog</a></li>
                        <li><a href="edit-blog.html">Edit Blog</a></li>
                    </ul>
                </li>
                <li class="d-none">
                    <a href="assets.html"><i class="fa fa-cube"></i> <span>Assets</span></a>
                </li>
                <li class="d-none">
                    <a href="activities.html"><i class="fa fa-bell-o"></i> <span>Activities</span></a>
                </li>
                <li class="submenu d-none">
                    <a href="#"><i class="fa fa-flag-o"></i> <span> Reports </span> <span class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li><a href="expense-reports.html"> Expense Report </a></li>
                        <li><a href="invoice-reports.html"> Invoice Report </a></li>
                    </ul>
                </li>
                <?php if($this->ion_auth->is_admin()) {?>
                <li>
                    <a href="<?= site_url("admin/settings/company") ?>"><i class="fa fa-cog"></i> <span>Созламалар</span></a>
                </li>
                <?php } ?>
                <li class="menu-title d-none">UI Elements</li>
                <li class="submenu d-none">
                    <a href="#"><i class="fa fa-laptop"></i> <span> Components</span> <span class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li><a href="uikit.html">UI Kit</a></li>
                        <li><a href="typography.html">Typography</a></li>
                        <li><a href="tabs.html">Tabs</a></li>
                    </ul>
                </li>
                <li class="submenu d-none">
                    <a href="#"><i class="fa fa-edit"></i> <span> Forms</span> <span class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li><a href="form-basic-inputs.html">Basic Inputs</a></li>
                        <li><a href="form-input-groups.html">Input Groups</a></li>
                        <li><a href="form-horizontal.html">Horizontal Form</a></li>
                        <li><a href="form-vertical.html">Vertical Form</a></li>
                    </ul>
                </li>
                <li class="submenu d-none">
                    <a href="#"><i class="fa fa-table"></i> <span> Tables</span> <span class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li><a href="tables-basic.html">Basic Tables</a></li>
                        <li><a href="tables-datatables.html">Data Table</a></li>
                    </ul>
                </li>
                <li class=" d-none">
                    <a href="calendar.html"><i class="fa fa-calendar"></i> <span>Calendar</span></a>
                </li>
                <li class="menu-title d-none">Extras</li>
                <li class="submenu d-none">
                    <a href="#"><i class="fa fa-columns"></i> <span>Pages</span> <span class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li><a href="login.html"> Login </a></li>
                        <li><a href="register.html"> Register </a></li>
                        <li><a href="forgot-password.html"> Forgot Password </a></li>
                        <li><a href="change-password2.html"> Change Password </a></li>
                        <li><a href="lock-screen.html"> Lock Screen </a></li>
                        <li><a href="profile.html"> Profile </a></li>
                        <li><a href="gallery.html"> Gallery </a></li>
                        <li><a href="error-404.html">404 Error </a></li>
                        <li><a href="error-500.html">500 Error </a></li>
                        <li><a class="active" href="blank-page.html"> Blank Page </a></li>
                    </ul>
                </li>
                <li class="submenu d-none">
                    <a href="javascript:void(0);"><i class="fa fa-share-alt"></i> <span>Multi Level</span> <span class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li class="submenu">
                            <a href="javascript:void(0);"><span>Level 1</span> <span class="menu-arrow"></span></a>
                            <ul style="display: none;">
                                <li><a href="javascript:void(0);"><span>Level 2</span></a></li>
                                <li class="submenu">
                                    <a href="javascript:void(0);"> <span> Level 2</span> <span class="menu-arrow"></span></a>
                                    <ul style="display: none;">
                                        <li><a href="javascript:void(0);">Level 3</a></li>
                                        <li><a href="javascript:void(0);">Level 3</a></li>
                                    </ul>
                                </li>
                                <li><a href="javascript:void(0);"><span>Level 2</span></a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript:void(0);"><span>Level 1</span></a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>

<div class="page-wrapper">
<?php  $this->load->view('templates/_parts/admin_master_notification_view'); ?>

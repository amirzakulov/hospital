<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!DOCTYPE html>
<html lang="en">
<!-- blank-page24:04-->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <link rel="shortcut icon" type="image/x-icon" href="<?= site_url("assets/admin/img/favicon.ico") ?>">
    <title><?= $title; ?></title>
<!--    <link href="--><?//= site_url("assets/fonts/icon.css") ?><!--" rel="stylesheet">-->
    <link href="<?= site_url("assets/bootstrap-4.3.1/css/bootstrap.min.css") ?>" rel="stylesheet">
    <link href="<?= site_url("assets/admin/css/select2.min.css"); ?>" rel="stylesheet">
    <link href="<?= site_url("assets/admin/css/font-awesome.min.css") ?>" rel="stylesheet">
    <?= $before_themeStyle;?>
    <link href="<?= site_url("assets/admin/css/theme-style.css") ?>" rel="stylesheet">
    <link href="<?= site_url("assets/doctor/styles.css?".time()) ?>" rel="stylesheet">

    <!--[if lt IE 9]>
    <script src="<?= site_url('assets/js/html5shiv.min.js'); ?>"></script>
    <script src="<?= site_url('assets/js/respond.min.js'); ?>"></script>
    <![endif]-->
    <?php echo $before_head;?>
</head>

<body class="<?= $this->session->userdata("sidebar_opened") ? "":"mini-sidebar"; ?>">
<div class="main-wrapper account-wrapper">
    <?php if($this->ion_auth->in_group(array(4,7,9))) {?>
        <div class="header">
            <div class="header-left">
                <a href="<?= site_url("doctor"); ?>" class="logo">
                    <img src="<?= site_url("assets/admin/img/logo.png") ?>" width="35" height="35" alt=""> <span><?php echo $this->config->item('cms_title');?></span>
                </a>
            </div>
			<a id="toggle_btn" href="javascript:void(0);" data-url="<?= site_url("admin/ajaxFunctions/sidebar_control") ?>"><i class="fa fa-bars"></i></a>
            <a id="mobile_btn" class="mobile_btn float-left" href="#sidebar"><i class="fa fa-bars"></i></a>
            <ul class="nav user-menu float-right">
                <li class="nav-item dropdown d-none d-sm-block">
                    <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown"><i class="fa fa-bell-o"></i> <span class="badge badge-pill bg-danger float-right">3</span></a>
                    <div class="dropdown-menu notifications">
                        <div class="topnav-dropdown-header">
                            <span>Notifications</span>
                        </div>
                        <div class="drop-scroll">
                            <ul class="notification-list">
                                <li class="notification-message">
                                    <a href="activities.html">
                                        <div class="media">
											<span class="avatar">
												<img alt="John Doe" src="<?= site_url("assets/admin/img/user.jpg") ?>" class="img-fluid rounded-circle">
											</span>
                                            <div class="media-body">
                                                <p class="noti-details"><span class="noti-title">John Doe</span> added new task <span class="noti-title">Patient appointment booking</span></p>
                                                <p class="noti-time"><span class="notification-time">4 mins ago</span></p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li class="notification-message">
                                    <a href="activities.html">
                                        <div class="media">
                                            <span class="avatar">V</span>
                                            <div class="media-body">
                                                <p class="noti-details"><span class="noti-title">Tarah Shropshire</span> changed the task name <span class="noti-title">Appointment booking with payment gateway</span></p>
                                                <p class="noti-time"><span class="notification-time">6 mins ago</span></p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li class="notification-message">
                                    <a href="activities.html">
                                        <div class="media">
                                            <span class="avatar">L</span>
                                            <div class="media-body">
                                                <p class="noti-details"><span class="noti-title">Misty Tison</span> added <span class="noti-title">Domenic Houston</span> and <span class="noti-title">Claire Mapes</span> to project <span class="noti-title">Doctor available module</span></p>
                                                <p class="noti-time"><span class="notification-time">8 mins ago</span></p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li class="notification-message">
                                    <a href="activities.html">
                                        <div class="media">
                                            <span class="avatar">G</span>
                                            <div class="media-body">
                                                <p class="noti-details"><span class="noti-title">Rolland Webber</span> completed task <span class="noti-title">Patient and Doctor video conferencing</span></p>
                                                <p class="noti-time"><span class="notification-time">12 mins ago</span></p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li class="notification-message">
                                    <a href="activities.html">
                                        <div class="media">
                                            <span class="avatar">V</span>
                                            <div class="media-body">
                                                <p class="noti-details"><span class="noti-title">Bernardo Galaviz</span> added new task <span class="noti-title">Private chat module</span></p>
                                                <p class="noti-time"><span class="notification-time">2 days ago</span></p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="topnav-dropdown-footer">
                            <a href="activities.html">View all Notifications</a>
                        </div>
                    </div>
                </li>
                <li class="nav-item dropdown d-none d-sm-block">
                    <a href="javascript:void(0);" id="open_msg_box" class="hasnotifications nav-link"><i class="fa fa-comment-o"></i> <span class="badge badge-pill bg-danger float-right">8</span></a>
                </li>
                <li class="nav-item dropdown has-arrow">
                    <a href="#" class="dropdown-toggle nav-link user-link" data-toggle="dropdown">
                        <span class="user-img"><img class="rounded-circle" src="<?= site_url("assets/admin/img/user.jpg") ?>" width="40" alt="Admin">
							<span class="status online"></span>
                        </span>
                        <?php $user = $this->ion_auth->user()->row(); ?>
                        <span><?= $user->last_name ." ". $user->first_name; ?></span>
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="<?= site_url("doctor/user"); ?>">Менинг профилим</a>
<!--                        <a class="dropdown-item" href="--><?//= site_url("doctor/settings"); ?><!--">Созламалар</a>-->
                        <a class="dropdown-item" href="<?= site_url("doctor/logout") ?>"><?= lang("general_logout") ?></a>
                    </div>
                </li>
            </ul>
            <div class="dropdown mobile-user-menu float-right">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="<?= site_url("doctor/user"); ?>">Менинг профилим</a>
                    <a class="dropdown-item" href="<?= site_url("doctor/logout") ?>"><?= lang("general_logout") ?></a>
                </div>
            </div>
        </div>
    <?php } ?>

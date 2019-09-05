<?php
$CI = &get_instance();
$tpl_user = $CI->Auth->user();
// debug($tpl_user); die();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="shortcut icon" href="<?=TEMPLATE_DIR?>/main_v1/img/favicon.ico" />
	<title><?=$this->config->item('project_name')?> - Админка</title>
	<link rel="stylesheet" href="<?=TEMPLATE_DIR?>/main_v1/vendors/mdi/css/materialdesignicons.min.css">
	<link rel="stylesheet" href="<?=TEMPLATE_DIR?>/main_v1/vendors/font-awesome/css/font-awesome.min.css"/>
	<link rel="stylesheet" href="<?=TEMPLATE_DIR?>/main_v1/vendors/jquery-toast-plugin/jquery.toast.min.css">

	<link rel="stylesheet" href="<?=TEMPLATE_DIR?>/tools/upload/jquery.fileupload.css">
	<link rel="stylesheet" href="<?=TEMPLATE_DIR?>/tools/datetimepicker/jquery.datetimepicker.css">
	<link rel="stylesheet" href="<?=TEMPLATE_DIR?>/tools/owl/assets/owl.carousel.min.css">
	<link rel="stylesheet" href="<?=TEMPLATE_DIR?>/tools/owl/assets/owl.theme.default.min.css">
	<link rel="stylesheet" href="<?=TEMPLATE_DIR?>/tools/select2/select2.min.css">
	<link rel="stylesheet" href="<?=TEMPLATE_DIR?>/tools/ekko-lightbox/ekko-lightbox.css">
	<!-- MAIN CSS -->

	<link rel="stylesheet" href="<?=TEMPLATE_DIR?>/main_v1/css/style.css?v=<?=VERSION?>">
	<link rel="stylesheet" href="<?=TEMPLATE_DIR?>/main_v1/css/custom.css?v=<?=VERSION?>">
</head>

<body>
	<div class="container-scroller">
		<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
			<div class="text-left navbar-brand-wrapper d-flex align-items-center justify-content-between">
				<a class="navbar-brand brand-logo" href="/"><img src="<?=TEMPLATE_DIR?>/main_v1/img/logo_white.png" alt="logo"/></a>
				<a class="navbar-brand brand-logo-mini" href="/"><img src="<?=TEMPLATE_DIR?>/main_v1/img/logo_mini.png" alt="logo"/></a> 
				<button class="navbar-toggler align-self-center" type="button" data-toggle="minimize">
				<span class="mdi mdi-menu"></span>
				</button>
			</div>
			<div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
				<ul class="navbar-nav navbar-nav-right">
					<li class="nav-item dropdown">
						<a class="nav-link count-indicator dropdown-toggle d-flex align-items-center justify-content-center" id="notificationDropdown" href="#" data-toggle="dropdown">
							<i class="mdi mdi-bell-outline mx-0"></i>
							<!-- <p class="notification-ripple notification-ripple-bg">
								<span class="ripple notification-ripple-bg"></span>
								<span class="ripple notification-ripple-bg"></span>
								<span class="ripple notification-ripple-bg"></span>
							</p> -->
						</a>
						<div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
							<p class="mb-0 font-weight-normal float-left dropdown-header">Уведомления</p>
							<!-- <a class="dropdown-item preview-item">
								<div class="preview-thumbnail">
									<div class="preview-icon bg-success">
										<i class="mdi mdi-information mx-0"></i>
									</div>
								</div>
								<div class="preview-item-content">
									<h6 class="preview-subject font-weight-normal">Application Error</h6>
									<p class="font-weight-light small-text mb-0 text-muted">Just now</p>
								</div>
							</a>
							<a class="dropdown-item preview-item">
								<div class="preview-thumbnail">
									<div class="preview-icon bg-warning">
										<i class="mdi mdi-settings mx-0"></i>
									</div>
								</div>
								<div class="preview-item-content">
									<h6 class="preview-subject font-weight-normal">Settings</h6>
									<p class="font-weight-light small-text mb-0 text-muted">Private message</p>
								</div>
							</a>
							<a class="dropdown-item preview-item">
								<div class="preview-thumbnail">
									<div class="preview-icon bg-info">
										<i class="mdi mdi-account-box mx-0"></i>
									</div>
								</div>
								<div class="preview-item-content">
									<h6 class="preview-subject font-weight-normal">New user registration</h6>
									<p class="font-weight-light small-text mb-0 text-muted">2 days ago</p>
								</div>
							</a> -->
						</div>
					</li>
					<li class="nav-item dropdown">
						<a class="nav-link count-indicator d-flex justify-content-center align-items-center nav-message" href="/profile/messages/">
							<i class="mdi mdi-email-outline mx-0"></i>
							<!--<p class="notification-ripple notification-ripple-bg">
								<span class="ripple notification-ripple-bg"></span>
								<span class="ripple notification-ripple-bg"></span>
								<span class="ripple notification-ripple-bg"></span>
							</p>-->
						</a>
					</li>
					<li class="nav-item nav-user-icon">
						<a class="nav-link" href="/admin/users/user/<?=$tpl_user['id']?>/">
							<img src="<?=$tpl_user['img']?>" alt="profile"/>
						</a>
					</li>
					<li class="nav-item nav-settings d-none d-lg-flex dropdown">
						<a class="nav-link" href="#" data-toggle="dropdown" id="appDropdown">
							<i class="mdi mdi-dots-horizontal"></i>
						</a>
						<div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="appDropdown">
							<a class="dropdown-item" href="/profile/"><i class="mdi mdi-settings text-primary"></i>Профиль</a>
							<a class="dropdown-item" href="/auth/logout/"><i class="mdi mdi-logout text-primary"></i>Выход</a>
						</div>
					</li>
				</ul>
				<button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
					<span class="mdi mdi-menu"></span>
				</button>
			</div>
		</nav>
		<div class="container-fluid page-body-wrapper">
			<nav class="sidebar sidebar-offcanvas" id="sidebar">
				<ul class="nav">
					<li class="nav-item nav-profile">
						<div class="nav-link d-flex">
							<div class="profile-image">
								<a href="/admin/users/user/<?=$tpl_user['id']?>/"><img src="<?=$tpl_user['img']?>" alt="image"></a>
							</div>
							<div class="profile-name">
								<p class="name text-capitalize"><?=($tpl_user['full_name'] ?? $tpl_user['email'])?></p>
								<p class="designation text-capitalize"><?=($tpl_user['role_name'] ?? '- - -')?></p>
							</div>
						</div>
					</li>
					<li class="nav-item <?=is_active_menu_item('main')?'active':''?>">
						<a class="nav-link" href="/">
							<i class="mdi mdi-home menu-icon"></i>
							<span class="menu-title">Главная</span>
						</a>
					</li>
					<li class="nav-item <?=is_active_menu_item('news')?'active':''?>">
						<a class="nav-link" href="/admin/news/">
							<i class="mdi mdi-newspaper menu-icon"></i>
							<span class="menu-title">Новости</span>
						</a>
					</li>
					<li class="nav-item <?=is_active_menu_item('faq')?'active':''?>">
						<a class="nav-link" href="/admin/faq/">
							<i class="mdi mdi-comment-question-outline menu-icon"></i>
							<span class="menu-title">FAQ</span>
						</a>
					</li>
					<li class="nav-item <?=is_active_menu_item('courses')?'active':''?>">
						<a class="nav-link" href="/admin/courses/">
							<i class="mdi mdi-view-headline menu-icon"></i>
							<span class="menu-title">Курсы</span>
						</a>
					</li>
					<li class="nav-item <?=is_active_menu_item('streams')?'active':''?>">
						<a class="nav-link" href="/admin/streams/">
							<i class="mdi mdi-message-video menu-icon"></i>
							<span class="menu-title">Онлайн встречи</span>
						</a>
					</li>
					<li class="nav-item <?=is_active_menu_item('users')?'active':''?>">
						<a class="nav-link" href="/admin/users/">
							<i class="mdi mdi-account-circle menu-icon"></i>
							<span class="menu-title">Пользователи</span>
						</a>
					</li>
				</ul>
			</nav>
			<div class="main-panel">
				<div class="content-wrapper">
					<?$this->content()?>
				</div>
			</div>
		</div>
	</div>
	<script src="<?=TEMPLATE_DIR?>/main_v1/js/main.js?v=<?=VERSION?>"></script>
	<script src="<?=TEMPLATE_DIR?>/main_v1/vendors/jquery-toast-plugin/jquery.toast.min.js"></script>

	<script src="<?=TEMPLATE_DIR?>/tools/upload/jquery.ui.widget.js"></script>
	<script src="<?=TEMPLATE_DIR?>/tools/upload/jquery.iframe-transport.js"></script>
	<script src="<?=TEMPLATE_DIR?>/tools/upload/jquery.fileupload.js"></script>	
	<script src="<?=TEMPLATE_DIR?>/tools/datetimepicker/jquery.datetimepicker.full.min.js"></script>	
	<script src="<?=TEMPLATE_DIR?>/tools/owl/owl.carousel.min.js"></script>
	<script src="<?=TEMPLATE_DIR?>/tools/toastr/toastr.min.js"></script>	
	<script src="<?=TEMPLATE_DIR?>/tools/select2/select2.min.js"></script>	
	<script src="<?=TEMPLATE_DIR?>/tools/ekko-lightbox/ekko-lightbox.min.js"></script>	

	<script src="<?=TEMPLATE_DIR?>/main_v1/js/app.js?v=<?=VERSION?>"></script>
	<script src="<?=TEMPLATE_DIR?>/main_v1/js/admin.js?v=<?=VERSION?>"></script>
</body>
</html>
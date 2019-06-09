<?php
$CI = &get_instance();
$tpl_user = $CI->Auth->user();
// debug($tpl_user);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="shortcut icon" href="<?=TEMPLATE_DIR?>/main_v1/img/favicon.ico" />
	<title>Dashboard</title>
	<link rel="stylesheet" href="<?=TEMPLATE_DIR?>/main_v1/vendors/mdi/css/materialdesignicons.min.css">
	<link rel="stylesheet" href="<?=TEMPLATE_DIR?>/main_v1/vendors/jquery-toast-plugin/jquery.toast.min.css">

	<link rel="stylesheet" href="<?=TEMPLATE_DIR?>/main_v1/css/style.css?v=<?=VERSION?>">
	<link rel="stylesheet" href="<?=TEMPLATE_DIR?>/main_v1/css/custom.css?v=<?=VERSION?>">
</head>

<body>
	<div class="container-scroller">
		<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
			<div class="text-left navbar-brand-wrapper d-flex align-items-center justify-content-between">
				<a class="navbar-brand brand-logo" href="/"><img src="<?=TEMPLATE_DIR?>/main_v1/img/logo_white.png" alt="logo"/></a>
				<a class="navbar-brand brand-logo-mini" href="/"><img src="<?=TEMPLATE_DIR?>/main_v1/img/logo_white.png" alt="logo"/></a> 
				<button class="navbar-toggler align-self-center" type="button" data-toggle="minimize">
				<span class="mdi mdi-menu"></span>
				</button>
			</div>
			<div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
				<ul class="navbar-nav navbar-nav-right">
					<li class="nav-item dropdown">
						<a class="nav-link count-indicator dropdown-toggle d-flex align-items-center justify-content-center" id="notificationDropdown" href="#" data-toggle="dropdown">
							<i class="mdi mdi-bell-outline mx-0"></i>
							<p class="notification-ripple notification-ripple-bg">
								<span class="ripple notification-ripple-bg"></span>
								<span class="ripple notification-ripple-bg"></span>
								<span class="ripple notification-ripple-bg"></span>
							</p>
						</a>
						<div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
							<p class="mb-0 font-weight-normal float-left dropdown-header">Notifications</p>
							<a class="dropdown-item preview-item">
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
							</a>
						</div>
					</li>
					<li class="nav-item dropdown">
						<a class="nav-link count-indicator d-flex justify-content-center align-items-center nav-message" href="#">
							<i class="mdi mdi-email-outline mx-0"></i>
							<p class="notification-ripple notification-ripple-bg">
								<span class="ripple notification-ripple-bg"></span>
								<span class="ripple notification-ripple-bg"></span>
								<span class="ripple notification-ripple-bg"></span>
							</p>
						</a>
					</li>
					<li class="nav-item nav-user-icon">
						<a class="nav-link" href="/profile/">
							<img src="<?=$CI->Auth->user()['img']?>" alt="profile"/>
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
			<!-- <div id="right-sidebar" class="settings-panel">
				<ul class="chat-list">
					<li class="list activee">
						<div class="profile"><img src="<?=TEMPLATE_DIR?>/main_v1/images/faces/face1.jpg" alt="image"><span class="online"></span></div>
						<div class="info">
							<p>Thomas Douglas</p>
							<p>Available</p>
						</div>
						<small class="text-muted my-auto">19 min</small>
					</li>
					<li class="list">
						<div class="profile"><img src="<?=TEMPLATE_DIR?>/main_v1/images/faces/face2.jpg" alt="image"><span class="offline"></span></div>
						<div class="info">
							<div class="wrapper d-flex">
								<p>Catherine</p>
							</div>
							<p>Away</p>
						</div>
						<div class="badge badge-success badge-pill my-auto mx-2">4</div>
						<small class="text-muted my-auto">23 min</small>
					</li>
					<li class="list">
						<div class="profile"><img src="<?=TEMPLATE_DIR?>/main_v1/images/faces/face3.jpg" alt="image"><span class="online"></span></div>
						<div class="info">
							<p>Daniel Russell</p>
							<p>Available</p>
						</div>
						<small class="text-muted my-auto">14 min</small>
					</li>
					<li class="list">
						<div class="profile"><img src="<?=TEMPLATE_DIR?>/main_v1/images/faces/face4.jpg" alt="image"><span class="offline"></span></div>
						<div class="info">
							<p>James Richardson</p>
							<p>Away</p>
						</div>
						<small class="text-muted my-auto">2 min</small>
					</li>
					<li class="list">
						<div class="profile"><img src="<?=TEMPLATE_DIR?>/main_v1/images/faces/face5.jpg" alt="image"><span class="online"></span></div>
						<div class="info">
							<p>Madeline Kennedy</p>
							<p>Available</p>
						</div>
						<small class="text-muted my-auto">5 min</small>
					</li>
					<li class="list">
						<div class="profile"><img src="<?=TEMPLATE_DIR?>/main_v1/images/faces/face6.jpg" alt="image"><span class="online"></span></div>
						<div class="info">
							<p>Sarah Graves</p>
							<p>Available</p>
						</div>
						<small class="text-muted my-auto">47 min</small>
					</li>
				</ul>
			</div> -->
			<nav class="sidebar sidebar-offcanvas" id="sidebar">
				<ul class="nav">
					<li class="nav-item nav-profile">
						<div class="nav-link d-flex">
							<div class="profile-image">
								<a href="/profile/"><img src="<?=$CI->Auth->user()['img']?>" alt="image"></a>
							</div>
							<div class="profile-name">
								<p class="name text-capitalize"><?=($tpl_user['full_name'] ?? $tpl_user['email'])?></p>
								<p class="designation text-capitalize"><?=($tpl_user['role_name'] ?? '- - -')?></p>
							</div>
						</div>
					</li>
					<li class="nav-item <?=is_active_menu_item('main')?'active':''?>">
						<a class="nav-link" href="/">
							<i class="mdi mdi-shield-check menu-icon"></i>
							<span class="menu-title">Главная</span>
						</a>
					</li>

					<?if($CI->Auth->isUser()):?>
						<?if($courses = $CI->SubscriptionModel->coursesList(($tpl_user['id'] ?? 0))):?>
							<li class="nav-item <?=is_active_menu_item('courses')?'active':''?>">
								<a class="nav-link" data-toggle="collapse" href="#courses-elements" aria-expanded="false" aria-controls="courses-elements">
									<i class="mdi mdi-view-headline menu-icon"></i>
									<span class="menu-title">Курсы <span class="badge badge-pill badge-info"><?=count($courses)?></span></span>
									<i class="menu-arrow"></i>
								</a>
								<div class="collapse" id="courses-elements">
									<ul class="nav flex-column sub-menu">
										<?foreach($courses as $item):?>
											<li class="nav-item"><a class="nav-link" href="/courses/<?=$item['code']?>/"><?=$item['name']?> (<?=strftime("%B %Y", strtotime($item['ts']))?>)</a></li>
										<?endforeach;?>
									</ul>
								</div>
							</li>
						<?endif;?>
						<li class="nav-item <?=is_active_menu_item('courses', 'enroll')?'active':''?>">
							<a class="nav-link" href="/courses/enroll/">
								<i class="mdi mdi-reply menu-icon"></i>
								<span class="menu-title">Запись на курс</span>
							</a>
						</li>
						<li class="nav-item <?=is_active_menu_item('subscription')?'active':''?>">
							<a class="nav-link" href="/subscription/">
								<i class="mdi mdi-bell menu-icon"></i>
								<span class="menu-title">Подписки</span>
							</a>
						</li>
					<?endif;?>
						
					<?if($CI->Auth->isTeacher()):?>			
						<li class="nav-item <?=is_active_menu_item('groups')?'active':''?>">
							<a class="nav-link" href="/groups/">
								<i class="mdi mdi-account-multiple menu-icon"></i>
								<span class="menu-title">Группы</span>
							</a>
						</li>
						<li class="nav-item <?=is_active_menu_item('teachingstreams')?'active':''?>">
							<a class="nav-link" href="/teachingstreams/">
								<i class="mdi mdi-message-video menu-icon"></i>
								<span class="menu-title">Онлайн встречи</span>
							</a>
						</li>
					<?endif;?>

					<li class="nav-item <?=is_active_menu_item('users')?'active':''?>">
						<a class="nav-link" href="/users/">
							<i class="mdi mdi-account-circle menu-icon"></i>
							<span class="menu-title">Пользователи</span>
						</a>
					</li>
					<li class="nav-item <?=is_active_menu_item('faq')?'active':''?>">
						<a class="nav-link" href="/faq/">
							<i class="mdi mdi-comment-question-outline menu-icon"></i>
							<span class="menu-title">FAQ</span>
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

	<script src="<?=TEMPLATE_DIR?>/main_v1/js/app.js?v=<?=VERSION?>"></script>
</body>
</html>
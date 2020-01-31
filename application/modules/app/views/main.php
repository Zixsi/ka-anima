<?php
$tpl_user = $this->Auth->user();
$this->notifications->load();
$notifications = $this->notifications->list();
$unread_messages = $this->UserMessagesModel->cntUnreadAll(($tpl_user['id'] ?? 0));
$landUrl = $this->config->item('land_url');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="shortcut icon" href="<?=TEMPLATE_DIR?>/main_v1/img/favicon.ico" />
	<title><?=$this->config->item('project_name')?> <?=((isset($pageTitle) && empty($pageTitle) === false)?' - ' . $pageTitle:'')?></title>
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

	<?if($this->config->item('nouse_metriks') !== true):?>
		<?include 'head_metriks.php';?>
	<?endif;?>
</head>

<body>
	<?if($this->config->item('nouse_metriks') !== true):?>
		<?include 'metriks.php';?>
	<?endif;?>

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
							<?if(count($notifications)):?>
								<p class="notification-ripple notification-ripple-bg">
									<span class="ripple notification-ripple-bg"></span>
									<span class="ripple notification-ripple-bg"></span>
									<span class="ripple notification-ripple-bg"></span>
								</p>
							<?endif;?>
						</a>
						<div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
							<?if(count($notifications)):?>
								<!-- <p class="mb-0 font-weight-normal float-left dropdown-header">Уведомления</p> -->
								<?foreach($notifications as $notif):?>
									<a <?=($notif['href'])?'href="'.$notif['href'].'"':''?> class="dropdown-item preview-item">
										<div class="preview-thumbnail">
											<div class="preview-icon bg-<?=$notif['type']?>">
												<i class="<?=$notif['icon']?> mx-0"></i>
											</div>
										</div>
										<div class="preview-item-content">
											<h6 class="preview-subject font-weight-normal"><?=$notif['text']?></h6>
											<!-- <p class="font-weight-light small-text mb-0 text-muted">Just now</p> -->
										</div>
									</a>
								<?endforeach;?>
							<?else:?>
								<p class="mb-0 font-weight-normal text-center dropdown-header">Нет новых уведомлений</p>
							<?endif;?>
						</div>
					</li>
					<li class="nav-item dropdown">
						<a class="nav-link count-indicator d-flex justify-content-center align-items-center nav-message" href="/profile/messages/">
							<i class="mdi mdi-email-outline mx-0"></i>
							<?if($unread_messages > 0):?>
								<p class="notification-ripple notification-ripple-bg">
									<span class="ripple notification-ripple-bg"></span>
									<span class="ripple notification-ripple-bg"></span>
									<span class="ripple notification-ripple-bg"></span>
								</p>
							<?endif;?>
						</a>
					</li>
					<li class="nav-item nav-user-icon">
						<a class="nav-link" href="/profile/">
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
				<button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas" style="color: #ffffff;">
					<span class="mdi mdi-menu"></span>
				</button>
			</div>
		</nav>
		<div class="container-fluid page-body-wrapper">

			<?//$this->load->view('inc_users_online');?>

			<nav class="sidebar sidebar-offcanvas" id="sidebar">
				<ul class="nav">
					<li class="nav-item nav-profile">
						<div class="nav-link d-flex">
							<div class="profile-image">
								<a href="/profile/"><img src="<?=$tpl_user['img']?>" alt="image"></a>
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

					<?if($this->Auth->isUser()):?>
						<li class="nav-item <?=is_active_menu_item('courses')?'active':''?>">
							<a class="nav-link" href="/courses/">
								<i class="mdi mdi-view-headline menu-icon"></i>
								<span class="menu-title">Курсы</span>
							</a>
						</li>
						<li class="nav-item <?=is_active_menu_item('workshop')?'active':''?>">
							<a class="nav-link" href="/workshop/">
								<i class="mdi mdi-brush menu-icon"></i>
								<span class="menu-title">Мастерская</span>
							</a>
						</li>
						<?if($this->Auth->isActive()):?>
							<li class="nav-item <?=(is_active_menu_item('subscription') || is_active_menu_item('groups'))?'active':''?>">
								<a class="nav-link" href="/subscription/">
									<i class="mdi mdi-bell menu-icon"></i>
									<span class="menu-title">Подписки</span>
								</a>
							</li>
							<li class="nav-item <?=is_active_menu_item('transactions')?'active':''?>">
								<a class="nav-link" href="/transactions/">
									<i class="mdi mdi-wallet menu-icon"></i>
									<span class="menu-title">Платежи</span>
								</a>
							</li>
						<?endif;?>
					<?endif;?>
						
					<?if($this->Auth->isTeacher()):?>			
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

					<?if($this->Auth->isActive()):?>
						<li class="nav-item <?=is_active_menu_item('users')?'active':''?>">
							<a class="nav-link" href="/users/">
								<i class="mdi mdi-account-circle menu-icon"></i>
								<span class="menu-title">Пользователи</span>
							</a>
						</li>
					<?endif;?>

					<?if($this->Auth->isUser()):?>
						<li class="nav-item <?=is_active_menu_item('faq')?'active':''?>">
							<a class="nav-link" href="/faq/">
								<i class="mdi mdi-comment-question-outline menu-icon"></i>
								<span class="menu-title">FAQ</span>
							</a>
						</li>
					<?endif;?>
					
					<li class="nav-item <?=is_active_menu_item('supportcontroller')?'active':''?>">
						<a class="nav-link" href="/support/">
							<i class="mdi mdi-human-greeting menu-icon"></i>
							<span class="menu-title">Тех. поддержка</span>
						</a>
					</li>

					<li class="nav-item">
						<a class="nav-link" href="<?=$landUrl?>/#contacts" target="_blank">
							<i class="mdi mdi-email-outline menu-icon"></i>
							<span class="menu-title">Контакты</span>
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
</body>
</html>
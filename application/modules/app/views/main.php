<?$CI = &get_instance();?>
<!doctype html>
<html lang="en">
<head>
	<title>Dashboard</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
	<!-- VENDOR CSS -->
	<link rel="stylesheet" href="<?=TEMPLATE_DIR?>/admin_1/assets/vendor/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?=TEMPLATE_DIR?>/admin_1/assets/vendor/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="<?=TEMPLATE_DIR?>/admin_1/assets/vendor/linearicons/style.css">
	<!-- MAIN CSS -->
	<link rel="stylesheet" href="<?=TEMPLATE_DIR?>/admin_1/assets/css/main.css">
	<link rel="stylesheet" href="<?=TEMPLATE_DIR?>/admin_1/assets/css/app.css">
	<!-- GOOGLE FONTS -->
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700" rel="stylesheet">
	<!-- ICONS -->
	<link rel="apple-touch-icon" sizes="76x76" href="<?=TEMPLATE_DIR?>/admin_1/assets/img/apple-icon.png">
	<link rel="icon" type="image/png" sizes="96x96" href="<?=TEMPLATE_DIR?>/admin_1/assets/img/favicon.png">
</head>

<body>
	<!-- WRAPPER -->
	<div id="wrapper">
		<!-- NAVBAR -->
		<nav class="navbar navbar-default navbar-fixed-top">
			<div class="brand">
				<a href="/"><img src="<?=TEMPLATE_DIR?>/admin_1/assets/img/logo-dark.png" alt="Klorofil Logo" class="img-responsive logo"></a>
			</div>
			<div class="container-fluid">
				<!--
				<div class="navbar-btn">
					<button type="button" class="btn-toggle-fullwidth"><i class="lnr lnr-arrow-left-circle"></i></button>
				</div>-->
				<div class="navbar-btn navbar-left">
					<span class="nav-balance">
						Баланс: <span href="" class="nav-balance-value"><?=number_format($CI->Auth->balance(), 2, '.', ' ')?> $</span>
						<a href="/pay/" class="lnr lnr-plus-circle"></a>
					</span>
				</div>
				<div id="navbar-menu">
					<ul class="nav navbar-nav navbar-right">
						<!--
						<li class="dropdown">
							<a href="#" class="dropdown-toggle icon-menu" data-toggle="dropdown">
								<i class="lnr lnr-alarm"></i>
								<span class="badge bg-danger">5</span>
							</a>
							<ul class="dropdown-menu notifications">
								<li><a href="#" class="notification-item"><span class="dot bg-warning"></span>System space is almost full</a></li>
								<li><a href="#" class="notification-item"><span class="dot bg-danger"></span>You have 9 unfinished tasks</a></li>
								<li><a href="#" class="notification-item"><span class="dot bg-success"></span>Monthly report is available</a></li>
								<li><a href="#" class="notification-item"><span class="dot bg-warning"></span>Weekly meeting in 1 hour</a></li>
								<li><a href="#" class="notification-item"><span class="dot bg-success"></span>Your request has been approved</a></li>
								<li><a href="#" class="more">See all notifications</a></li>
							</ul>
						</li>-->
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown"><img src="<?=TEMPLATE_DIR?>/admin_1/assets/img/user.png" class="img-circle" alt="Avatar"> <span><?=$CI->Auth->user()['email']?></span> <i class="icon-submenu lnr lnr-chevron-down"></i></a>
							<ul class="dropdown-menu">
								<li><a href="#"><i class="lnr lnr-user"></i> <span>Профиль</span></a></li>
								<li><a href="#"><i class="lnr lnr-envelope"></i> <span>Сообщения</span></a></li>
								<li><a href="#"><i class="lnr lnr-cog"></i> <span>Настройки</span></a></li>
								<li><a href="/auth/logout/"><i class="lnr lnr-exit"></i> <span>Выход</span></a></li>
							</ul>
						</li>
					</ul>
				</div>
			</div>
		</nav>
		<!-- END NAVBAR -->
		<!-- LEFT SIDEBAR -->
		<div id="sidebar-nav" class="sidebar">
			<div class="sidebar-scroll">
				<nav>
					<ul class="nav">
						<li><a href="/" class="active"><i class="lnr lnr-home"></i> <span>Главная</span></a></li>
						<li>
							<a href="#subPages1" data-toggle="collapse" class="collapsed"><i class="lnr lnr-graduation-hat"></i> <span>Обучение</span> <i class="icon-submenu lnr lnr-chevron-left"></i></a>
							<div id="subPages1" class="collapse in">
								<ul class="nav">
									<li><a href="/courses/enroll/" class="">Запись на курс</a></li>
									<li><a href="/courses/" class="">Курсы</a></li>
									<li><a href="/subscription/" class="">Подписка</a></li>
								</ul>
							</div>
						</li>
						<?/*if($list_groups = $CI->CoursesGroupsModel->getUserGroups($CI->Auth->userID())):?>
							<li>
								<a href="#subPages2" data-toggle="collapse" class="collapsed"><i class="lnr lnr-users"></i> <span>Группы</span> <i class="icon-submenu lnr lnr-chevron-left"></i></a>
								<div id="subPages2" class="collapse in">
									<ul class="nav">
										<?foreach($list_groups as $val):?>
											<li><a href="/groups/<?=$val['id']?>/" class=""><?=$val['name']?></a></li>
										<?endforeach;?>
									</ul>
								</div>
							</li>
						<?endif;*/?>
						<li>
							<a href="#subPages3" data-toggle="collapse" class="collapsed"><i class="lnr lnr-briefcase"></i> <span>Учительская</span> <i class="icon-submenu lnr lnr-chevron-left"></i></a>
							<div id="subPages3" class="collapse in">
								<ul class="nav">
									<li><a href="/teachingcourses/" class="">Курсы</a></li>
									<li><a href="/teachinggroups/" class="">Группы</a></li>
								</ul>
							</div>
						</li>
					</ul>
				</nav>
			</div>
		</div>
		<!-- END LEFT SIDEBAR -->
		<!-- MAIN -->
		<div class="main">
			<!-- MAIN CONTENT -->
			<div class="main-content">
				<div class="container-fluid">
					<?$this->content()?>
				</div>
			</div>
			<!-- END MAIN CONTENT -->
		</div>
		<!-- END MAIN -->
		<div class="clearfix"></div>
		<footer>
			<div class="container-fluid">
				<p class="copyright">&copy; 2018 Company. All Rights Reserved.</p>
			</div>
		</footer>
	</div>
	<!-- END WRAPPER -->
	<!-- Javascript -->
	<script src="<?=TEMPLATE_DIR?>/admin_1/assets/vendor/jquery/jquery.min.js"></script>
	<script src="<?=TEMPLATE_DIR?>/admin_1/assets/vendor/bootstrap/js/bootstrap.min.js"></script>
	<script src="<?=TEMPLATE_DIR?>/admin_1/assets/vendor/bootstrap/js/holder.min.js"></script>
	<script src="<?=TEMPLATE_DIR?>/admin_1/assets/vendor/jquery-slimscroll/jquery.slimscroll.min.js"></script>
	<script src="<?=TEMPLATE_DIR?>/admin_1/assets/scripts/klorofil-common.js"></script>
	<script src="<?=TEMPLATE_DIR?>/admin_1/assets/scripts/app.js"></script>
</body>
</html>
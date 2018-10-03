<?$CI = &get_instance();
$user_id = $CI->Auth->userID();
?>
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
	<link rel="stylesheet" href="<?=TEMPLATE_DIR?>/admin_1/assets/vendor/toastr/toastr.css">
	<!-- MAIN CSS -->
	<link rel="stylesheet" href="<?=TEMPLATE_DIR?>/admin_1/assets/css/main.css">
	<link rel="stylesheet" href="<?=TEMPLATE_DIR?>/admin_1/assets/css/app.css?<?=VERSION?>">
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
				<a href="/"><img src="<?=TEMPLATE_DIR?>/admin_1/assets/img/logo_mini.png" alt="Klorofil Logo" class="img-responsive logo"></a>
			</div>
			<div class="container-fluid">
				<!--<div class="navbar-btn navbar-left"></div>-->
				<div class="navbar-left">
					<span id="homework-time-left" data-time="<?=date('Ymd', next_monday_ts())?>">
						<div class="float-left icon">
							<span class="lnr lnr-warning"></span>
						</div>
						<div class="float-left">
							<span class="title">До конца сдачи ДЗ осталось</span>
							<span class="value">- - -</span>
						</div>
					</span>
				</div>
				<div id="navbar-menu">
					<ul class="nav navbar-nav navbar-right">
						<li>
							<span id="panel-date-time-msk" style="display: inline-block;">- - -</span>
						</li>
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
						</li>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown"><img src="<?=TEMPLATE_DIR?>/admin_1/assets/img/user.png" class="img-circle" alt="Avatar"> <span><?=$CI->Auth->user()['email']?></span> <i class="icon-submenu lnr lnr-chevron-down"></i></a>
							<ul class="dropdown-menu">
								<li><a href="#"><i class="lnr lnr-user"></i> <span>Профиль</span></a></li>
								<li><a href="#"><i class="lnr lnr-envelope"></i> <span>Сообщения</span></a></li>
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
						<?//if($CI->Auth->checkAccess([['user_menu', 'view']])):?>
							<li>
								<a href="#sub-courses" data-toggle="collapse" class="collapsed"><span>Курсы</span> <i class="icon-submenu lnr lnr-chevron-left"></i></a>
								<div id="sub-courses" class="collapse in">
									<ul class="nav">
										<?if($courses = $CI->SubscriptionModel->coursesList($user_id)):?>
											<?foreach($courses as $item):?>
												<li><a href="/courses/<?=$item['course_group']?>/" class=""><?=$item['name']?></a></li>
											<?endforeach;?>
										<?endif;?>
									</ul>
								</div>
							</li>
							<li><a href="/courses/enroll/" class="">Запись на курс</a></li>
							<li><a href="/subscription/" class="">Подписка</a></li>
						<?//endif;?>

						<?//if($CI->Auth->checkAccess([['teach_menu', 'view']])):?>
							<li>
								<a href="#subPages3" data-toggle="collapse" class="collapsed"><i class="lnr lnr-briefcase"></i> <span>Учительская</span> <i class="icon-submenu lnr lnr-chevron-left"></i></a>
								<div id="subPages3" class="collapse in">
									<ul class="nav">
										<li><a href="/teachingcourses/" class="">Курсы</a></li>
										<li><a href="/teachinggroups/" class="">Группы</a></li>
									</ul>
								</div>
							</li>
						<?//endif;?>

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
	<script src="<?=TEMPLATE_DIR?>/admin_1/assets/vendor/moment/moment.js"></script>
	<script src="<?=TEMPLATE_DIR?>/admin_1/assets/vendor/moment/moment-timezone.min.js"></script>
	<script src="<?=TEMPLATE_DIR?>/admin_1/assets/vendor/moment/countdown.min.js"></script>
	<script src="<?=TEMPLATE_DIR?>/admin_1/assets/vendor/moment/moment-countdown.min.js"></script>
	<script src="<?=TEMPLATE_DIR?>/admin_1/assets/scripts/klorofil-common.js"></script>
	<script src="<?=TEMPLATE_DIR?>/admin_1/assets/scripts/app.js?<?=VERSION?>"></script>
</body>
</html>
<?php
$CI = &get_instance();
$user_id = $CI->Auth->userID();
?>
<!doctype html>
<html lang="en">
<head>
	<title>ADMIN</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
	<!-- VENDOR CSS -->
	<link rel="stylesheet" href="<?=TEMPLATE_DIR?>/admin_1/assets/vendor/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?=TEMPLATE_DIR?>/admin_1/assets/vendor/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="<?=TEMPLATE_DIR?>/admin_1/assets/vendor/linearicons/style.css">
	<link rel="stylesheet" href="<?=TEMPLATE_DIR?>/admin_1/assets/vendor/toastr/toastr.css">
	<link rel="stylesheet" href="<?=TEMPLATE_DIR?>/tools/upload/jquery.fileupload.css">
	<link rel="stylesheet" href="<?=TEMPLATE_DIR?>/tools/datetimepicker/jquery.datetimepicker.css">
	<link rel="stylesheet" href="<?=TEMPLATE_DIR?>/tools/owl/assets/owl.carousel.min.css">
	<link rel="stylesheet" href="<?=TEMPLATE_DIR?>/tools/owl/assets/owl.theme.default.min.css">
	<!-- MAIN CSS -->
	<link rel="stylesheet" href="<?=TEMPLATE_DIR?>/admin_1/assets/css/main.css">
	<link rel="stylesheet" href="<?=TEMPLATE_DIR?>/admin_1/assets/css/app.css?v=<?=VERSION?>">
	<!-- GOOGLE FONTS -->
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700" rel="stylesheet">
	<!-- ICONS -->
	<link rel="apple-touch-icon" sizes="56x64" href="<?=TEMPLATE_DIR?>/admin_1/assets/img/favicon.ico?v=<?=VERSION?>">
	<link rel="icon" type="image/x-icon" sizes="56x64" href="<?=TEMPLATE_DIR?>/admin_1/assets/img/favicon.ico?v=<?=VERSION?>">
</head>

<body>
	<!-- WRAPPER -->
	<div id="wrapper">
		<!-- NAVBAR -->
		<nav class="navbar navbar-default navbar-fixed-top">
			<div class="brand">
				<a href="/"><img src="<?=TEMPLATE_DIR?>/admin_1/assets/img/logo_black.png?v=<?=VERSION?>" alt="Logo" class="img-responsive logo" style="height: 70px;"></a>
			</div>
			<div class="container-fluid">
				<div id="navbar-menu">
					<ul class="nav navbar-nav navbar-right">
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
							<a href="#" class="dropdown-toggle" data-toggle="dropdown"><img src="<?=$CI->Auth->user()['img']?>" class="img-circle" alt="Avatar"> <span><?=$CI->Auth->user()['email']?></span> <i class="icon-submenu lnr lnr-chevron-down"></i></a>
							<ul class="dropdown-menu">
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
						<li><a href="/admin/" <?=is_active_menu_item('main')?'class="active"':''?> ><span>Главная</span></a></li>
						<li><a href="/admin/news/" <?=is_active_menu_item('news')?'class="active"':''?> >Новости</a></li>
						<li><a href="/admin/faq/" <?=is_active_menu_item('faq')?'class="active"':''?> >FAQ</a></li>
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
	<script src="<?=TEMPLATE_DIR?>/tools/upload/jquery.ui.widget.js"></script>
	<script src="<?=TEMPLATE_DIR?>/tools/upload/jquery.iframe-transport.js"></script>
	<script src="<?=TEMPLATE_DIR?>/tools/upload/jquery.fileupload.js"></script>	
	<script src="<?=TEMPLATE_DIR?>/tools/datetimepicker/jquery.datetimepicker.full.min.js"></script>	
	<script src="<?=TEMPLATE_DIR?>/tools/owl/owl.carousel.min.js"></script>	

	<script src="<?=TEMPLATE_DIR?>/admin_1/assets/scripts/admin.js?v=<?=VERSION?>"></script>
</body>
</html>
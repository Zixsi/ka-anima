<?php
$CI = &get_instance();
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
	<!--
	<link rel="stylesheet" href="<?=TEMPLATE_DIR?>/admin_1/assets/vendor/font-awesome/css/font-awesome.min.css">-->
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
	<link rel="stylesheet" href="<?=TEMPLATE_DIR?>/admin_1/assets/vendor/linearicons/style.css">
	<link rel="stylesheet" href="<?=TEMPLATE_DIR?>/admin_1/assets/vendor/toastr/toastr.css">
	<link rel="stylesheet" href="<?=TEMPLATE_DIR?>/tools/upload/jquery.fileupload.css">
	<link rel="stylesheet" href="<?=TEMPLATE_DIR?>/tools/datetimepicker/jquery.datetimepicker.css">
	<link rel="stylesheet" href="<?=TEMPLATE_DIR?>/tools/owl/assets/owl.carousel.min.css">
	<link rel="stylesheet" href="<?=TEMPLATE_DIR?>/tools/owl/assets/owl.theme.default.min.css">
	<link rel="stylesheet" href="<?=TEMPLATE_DIR?>/tools/select2/select2.min.css">
	<!-- MAIN CSS -->
	<link rel="stylesheet" href="<?=TEMPLATE_DIR?>/admin_1/assets/css/main.css">
	<link rel="stylesheet" href="<?=TEMPLATE_DIR?>/admin_1/assets/css/app.css?v=<?=VERSION?>">
	<!-- GOOGLE FONTS -->
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700" rel="stylesheet">
	<!-- ICONS -->
	<link rel="apple-touch-icon" sizes="56x64" href="<?=TEMPLATE_DIR?>/admin_1/assets/img/favicon.ico?v=<?=VERSION?>">
	<link rel="icon" type="image/x-icon" sizes="56x64" href="<?=TEMPLATE_DIR?>/admin_1/assets/img/favicon.ico?v=<?=VERSION?>">

	<script src="<?=TEMPLATE_DIR?>/admin_1/assets/vendor/jquery/jquery.min.js"></script>
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
				<!--<div class="navbar-btn navbar-left"></div>-->
				<div class="navbar-left">
					<?if($CI->Auth->isTeacher()):?>
						<?if($next_stream = $CI->StreamsModel->getNextForAuthor($user_id)):?>
							<span id="homework-time-left" data-time="<?=$next_stream['ts']?>">
								<div class="float-left icon">
									<span class="lnr lnr-warning"></span>
								</div>
								<div class="float-left">
									<span class="title">Ближайшая онлайн встреча</span>
									<span class="value">- - -</span>
								</div>
							</span>
						<?endif;?>
					<?elseif($CI->Auth->isUser()):?>
						<span id="homework-time-left" data-time="<?=date('Y-m-d 00:00:00', next_monday_ts())?>">
							<div class="float-left icon">
								<span class="lnr lnr-warning"></span>
							</div>
							<div class="float-left">
								<span class="title">До конца сдачи ДЗ осталось </span>
								<span class="value">- - -</span>
							</div>
						</span>
					<?endif;?>
				</div>
				<div id="navbar-menu">
					<ul class="nav navbar-nav navbar-right">
						<li>
							<span id="panel-date-time-msk" style="display: inline-block;">- - -</span>
						</li>
						<li>
							<span class="label label-success" style="display: inline-block; font-size: 15px; margin-top: 27px; margin-left: 15px;"><?=($CI->Auth->isTeacher())?'Преподаватель':'Ученик'?></span>
						</li>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown"><img src="<?=$CI->Auth->user()['img']?>" class="img-circle" alt="Avatar"> <span><?=$CI->Auth->user()['email']?></span> <i class="icon-submenu lnr lnr-chevron-down"></i></a>
							<ul class="dropdown-menu">
								<li><a href="/profile/"><i class="lnr lnr-user"></i> <span>Профиль</span></a></li>
								<li><a href="/profile/messages/"><i class="lnr lnr-envelope"></i> <span>Сообщения</span></a></li>
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
						<li><a href="/" <?=is_active_menu_item('main')?'class="active"':''?>  ><span>Главная</span></a></li>
						<?if($CI->Auth->checkAccess([['user_menu', 'view']])):?>
							<?if($courses = $CI->SubscriptionModel->coursesList($user_id)):?>
								<li>
									<a href="#sub-courses" data-toggle="collapse" class="collapsed <?=is_active_menu_item('courses')?'active':''?>"><span>Курсы</span> <i class="icon-submenu lnr lnr-chevron-left"></i></a>
									<div id="sub-courses" class="collapse in">
										<ul class="nav">
											<?foreach($courses as $item):?>
												<li><a href="/courses/<?=$item['code']?>/" class=""><?=$item['name']?> (<?=strftime("%B %Y", strtotime($item['ts']))?>)</a></li>
											<?endforeach;?>
										</ul>
									</div>
								</li>
							<?endif;?>
							<li><a href="/courses/enroll/" <?=is_active_menu_item('courses', 'enroll')?'class="active"':''?> >Запись на курс</a></li>
							<li><a href="/subscription/" <?=is_active_menu_item('subscription')?'class="active"':''?> >Подписка</a></li>
						<?endif;?>
							
						<?if($CI->Auth->checkAccess([['teach_menu', 'view']])):?>
							<li><a href="/groups/" <?=is_active_menu_item('groups')?'class="active"':''?> >Группы</a></li>
							<li><a href="/teachingstreams/" <?=is_active_menu_item('teachingstreams')?'class="active"':''?> >Онлайн встречи</a></li>
						<?endif;?>

						<li><a href="/profile/" <?=is_active_menu_item('profile')?'class="active"':''?> >Профиль</a></li>
						<li><a href="/users/" <?=is_active_menu_item('users')?'class="active"':''?> >Пользователи</a></li>
						<li><a href="/faq/" <?=is_active_menu_item('faq')?'class="active"':''?> >FAQ</a></li>

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
	<script src="<?=TEMPLATE_DIR?>/tools/toastr/toastr.min.js"></script>	
	<script src="<?=TEMPLATE_DIR?>/tools/select2/select2.min.js"></script>	

	<script src="<?=TEMPLATE_DIR?>/admin_1/assets/scripts/app.js?v=<?=VERSION?>"></script>
</body>
</html>
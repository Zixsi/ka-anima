<!doctype html>
<html lang="en" class="fullscreen-bg">
<head>
	<title>Login</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
	<!-- VENDOR CSS -->
	<link rel="stylesheet" href="<?=TEMPLATE_DIR?>/admin_1/assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?=TEMPLATE_DIR?>/admin_1/assets/vendor/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="<?=TEMPLATE_DIR?>/admin_1/assets/vendor/linearicons/style.css">
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
	<div id="wrapper">
		<div class="vertical-align-wrap">
			<div class="vertical-align-middle">
				<?$this->content()?>
			</div>
		</div>
	</div>
</body>
</html>
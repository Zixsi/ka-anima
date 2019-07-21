<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="shortcut icon" href="<?=TEMPLATE_DIR?>/main_v1/img/favicon.ico" />
	<title>Login</title>
	<link rel="stylesheet" href="<?=TEMPLATE_DIR?>/main_v1/vendors/mdi/css/materialdesignicons.min.css">
	<link rel="stylesheet" href="<?=TEMPLATE_DIR?>/main_v1/vendors/jquery-toast-plugin/jquery.toast.min.css">

	<link rel="stylesheet" href="<?=TEMPLATE_DIR?>/main_v1/css/style.css?v=<?=VERSION?>">
	<link rel="stylesheet" href="<?=TEMPLATE_DIR?>/main_v1/css/custom.css?v=<?=VERSION?>">
</head>
<body>
	<div class="page-loader">
		<div class="loader">
			<div class="jumping-dots-loader"><span></span><span></span><span></span></div>
            <div class="loader-message"></div>
       	</div>
	</div>
	<div class="container-scroller">
		<div class="container-fluid page-body-wrapper full-page-wrapper">
			<div class="content-wrapper d-flex align-items-stretch auth auth-img-bg">
				<div class="row flex-grow">
					<div class="col-lg-6 d-flex align-items-center justify-content-center">
						<?$this->content()?>
					</div>
					<div class="col-lg-6 login-half-bg d-flex flex-row">
						<p class="text-white font-weight-medium text-center flex-grow align-self-end"><?=COPYRIGHT?></p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script src="<?=TEMPLATE_DIR?>/main_v1/js/main.js?v=<?=VERSION?>"></script>
	<script src="<?=TEMPLATE_DIR?>/main_v1/vendors/jquery-toast-plugin/jquery.toast.min.js"></script>

	<script src="<?=TEMPLATE_DIR?>/main_v1/js/app.js?v=<?=VERSION?>"></script>
</body>
</html>
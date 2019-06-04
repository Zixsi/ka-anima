<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="shortcut icon" href="<?=TEMPLATE_DIR?>/main_v1/assets/img/favicon.ico" />
	<title>Login</title>
	<link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
	<link rel="stylesheet" href="<?=TEMPLATE_DIR?>/tools/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?=TEMPLATE_DIR?>/tools/toastr/toastr.min.css">
	<link rel="stylesheet" href="<?=TEMPLATE_DIR?>/main_v1/assets/css/app.css?v=<?=VERSION?>">
	<link rel="stylesheet" href="<?=TEMPLATE_DIR?>/main_v1/assets/css/custom.css?v=<?=VERSION?>">
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
						<p class="text-white font-weight-medium text-center flex-grow align-self-end">Copyright &copy; 2018  All rights reserved.</p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script src="<?=TEMPLATE_DIR?>/tools/jquery/jquery.min.js"></script>
	<script src="<?=TEMPLATE_DIR?>/tools/bootstrap/js/bootstrap.min.js"></script>
	<script src="<?=TEMPLATE_DIR?>/tools/toastr/toastr.min.js"></script>
	<script src="<?=TEMPLATE_DIR?>/main_v1/assets/js/app.js?v=<?=VERSION?>"></script>
</body>
</html>
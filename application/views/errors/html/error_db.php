<?if(ENVIRONMENT !== 'production'):?>
	<!DOCTYPE html>
	<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Database Error</title>
		<style type="text/css">

		::selection { background-color: #f07746; color: #fff; }
		::-moz-selection { background-color: #f07746; color: #fff; }

		body {
			background-color: #fff;
			margin: 40px auto;
			max-width: 1024px;
			font: 16px/24px normal "Helvetica Neue", Helvetica, Arial, sans-serif;
			color: #808080;
		}

		a {
			color: #dd4814;
			background-color: transparent;
			font-weight: normal;
			text-decoration: none;
		}

		a:hover {
			color: #97310e;
		}

		h1 {
			color: #fff;
			background-color: #dd4814;
			border-bottom: 1px solid #d0d0d0;
			font-size: 22px;
			font-weight: bold;
			margin: 0 0 14px 0;
			padding: 5px 15px;
			line-height: 40px;
		}

		h2 {
			color:#404040;
			margin:0;
			padding:0 0 10px 0;
		}

		code {
			font-family: Consolas, Monaco, Courier New, Courier, monospace;
			font-size: 13px;
			background-color: #f5f5f5;
			border: 1px solid #e3e3e3;
			border-radius: 4px;
			color: #002166;
			display: block;
			margin: 14px 0 14px 0;
			padding: 12px 10px 12px 10px;
		}

		#container {
			margin: 10px;
			border: 1px solid #d0d0d0;
			box-shadow: 0 0 8px #d0d0d0;
			border-radius: 4px;
		}

		p {
			margin: 0 0 10px;
			padding:0;
		}

		#body {
			margin: 0 15px 0 15px;
			min-height: 96px;
		}
		</style>
	</head>
	<body>
		<div id="container">
			<h1><?php echo $heading; ?></h1>
			<div id="body">
				<?php echo $message; ?>
			</div>
		</div>
	</body>
	</html>

<?else:?>

	<!DOCTYPE html>
	<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="shortcut icon" href="<?=TEMPLATE_DIR?>/main_v1/img/favicon.ico" />
		<title>500 Internal server error</title>
		<link rel="stylesheet" href="<?=TEMPLATE_DIR?>/main_v1/css/style.css?v=<?=VERSION?>">
	</head>
	<body>
		 <div class="container-scroller">
			<div class="container-fluid page-body-wrapper full-page-wrapper">
				<div class="content-wrapper d-flex align-items-center text-center error-page bg-info">
					<div class="row flex-grow">
						<div class="col-lg-7 mx-auto text-white">
							<div class="row align-items-center d-flex flex-row">
								<div class="col-lg-6 text-lg-right pr-lg-4">
									<h1 class="display-1 mb-0 text-white">500</h1>
								</div>
								<div class="col-lg-6 error-page-divider text-lg-left pl-lg-4">
									<h2 class="text-white">SORRY!</h2>
									<h3 class="font-weight-light text-white">Internal server error!</h3>
								</div>
							</div>
							<div class="row mt-5">
								<div class="col-12 text-center mt-xl-2">
									<a class="text-white font-weight-medium" href="/">Back to home</a>
								</div>
							</div>
							<div class="row mt-5">
								<div class="col-12 mt-xl-2">
									<p class="text-white font-weight-medium text-center"><?=COPYRIGHT?></p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
	</html>

<?endif;?>
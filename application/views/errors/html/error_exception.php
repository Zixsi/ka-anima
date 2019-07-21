<?if(ENVIRONMENT !== 'production'):?>
	<div style="border:1px solid #dd4814;padding-left:20px;margin:10px 0;">

		<h4>An uncaught Exception was encountered</h4>

		<p>Type: <?php echo get_class($exception); ?></p>
		<p>Message: <?php echo $message; ?></p>
		<p>Filename: <?php echo $exception->getFile(); ?></p>
		<p>Line Number: <?php echo $exception->getLine(); ?></p>

		<?php if (defined('SHOW_DEBUG_BACKTRACE') && SHOW_DEBUG_BACKTRACE === TRUE): ?>

			<p>Backtrace:</p>
			<?php foreach ($exception->getTrace() as $error): ?>

				<?php if (isset($error['file']) && strpos($error['file'], realpath(BASEPATH)) !== 0): ?>

					<p style="margin-left:10px">
					File: <?php echo $error['file']; ?><br />
					Line: <?php echo $error['line']; ?><br />
					Function: <?php echo $error['function']; ?>
					</p>
				<?php endif ?>

			<?php endforeach ?>

		<?php endif ?>
	</div>
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
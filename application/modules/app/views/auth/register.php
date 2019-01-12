<div class="auth-box">
	<div class="content">
		<div class="header">
			<div class="logo text-center"><img src="<?=TEMPLATE_DIR?>/admin_1/assets/img/logo_black.png?v=<?=VERSION?>" alt="Klorofil Logo"></div>
			<p class="lead">Rigister account</p>
		</div>
		<?=ShowError($error);?>
		<form class="form-auth-small" action="" method="POST">
			<div class="form-group">
				<label for="signip-email" class="control-label sr-only">Email</label>
				<input type="text" class="form-control" id="signip-email" name="email" value="<?=set_value('email', $form['email'], true)?>" placeholder="Email">
			</div>
			<div class="form-group">
				<label for="signip-password" class="control-label sr-only">Password</label>
				<input type="password" class="form-control" id="signip-password" name="password" value="" placeholder="Password">
			</div>
			<div class="form-group">
				<label for="signip-re-password" class="control-label sr-only">Re-Password</label>
				<input type="password" class="form-control" id="signip-re-password" name="repassword" value="" placeholder="Re-Password">
			</div>
			<button type="submit" class="btn btn-primary btn-lg btn-block">SIGN UP</button>
			<div class="bottom">
				<span class="helper-text">Already have account? <a href="/auth/">Sign In</a></span>
			</div>
		</form>
	</div>
	<div class="clearfix"></div>
</div>
<div class="auth-box">
	<div class="content">
		<div class="header">
			<div class="logo text-center"><img src="<?=TEMPLATE_DIR?>/admin_1/assets/img/logo_mini.png" alt="Klorofil Logo"></div>
			<p class="lead">Forgot password</p>
		</div>
		<?=ShowError($error);?>
		<?/*
		<form class="form-auth-small" action="" method="POST">
			<div class="form-group">
				<label for="signin-email" class="control-label sr-only">Email</label>
				<input type="text" class="form-control" id="signin-email" name="email" value="<?=set_value('email', $form['email'], true)?>" placeholder="Email">
			</div>
			<div class="form-group">
				<label for="signin-password" class="control-label sr-only">Password</label>
				<input type="password" class="form-control" id="signin-password" name="password" value="" placeholder="Password">
			</div>
			<div class="form-group clearfix">
				<label class="fancy-checkbox element-left">
					<input name="remember" type="checkbox" value="1" <?=set_checkbox('remember', '1',$form['remember'])?>>
					<span>Remember me</span>
				</label>
			</div>
			<button type="submit" class="btn btn-primary btn-lg btn-block">LOGIN</button>
			<div class="bottom">
				<span class="helper-text"><i class="fa fa-lock"></i> <a href="#">Forgot password?</a></span>
			</div>
		</form>
		*/?>
	</div>
	<div class="clearfix"></div>
</div>
<div class="auth-box">
	<div class="content">
		<div class="header">
			<div class="logo text-center"><img src="<?=TEMPLATE_DIR?>/admin_1/assets/img/logo-dark.png" alt="Klorofil Logo"></div>
			<p class="lead">Login to your account</p>
		</div>
		<form class="form-auth-small" action="">
			<div class="form-group">
				<label for="signin-email" class="control-label sr-only">Email</label>
				<input type="email" class="form-control" id="signin-email" value="" placeholder="Email">
			</div>
			<div class="form-group">
				<label for="signin-password" class="control-label sr-only">Password</label>
				<input type="password" class="form-control" id="signin-password" value="" placeholder="Password">
			</div>
			<div class="form-group clearfix">
				<label class="fancy-checkbox element-left">
					<input type="checkbox">
					<span>Remember me</span>
				</label>
			</div>
			<button type="submit" class="btn btn-primary btn-lg btn-block">LOGIN</button>
			<div class="bottom">
				<span class="helper-text"><i class="fa fa-lock"></i> <a href="#">Forgot password?</a></span>
			</div>
		</form>
	</div>
	<div class="clearfix"></div>
</div>
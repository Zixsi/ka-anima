<div class="auth-form-transparent text-left p-3">
	<div class="brand-logo">
		<a href="/"><img src="<?=TEMPLATE_DIR?>/main_v1/assets/img/logo_black.png" alt="logo"></a>
	</div>
	<h4>Восстановление пароля</h4>
	<form action="" method="POST" class="pt-2" id="auth--forgot-form">
		<div class="form-group">
			<div class="input-group">
				<div class="input-group-prepend bg-transparent">
					<span class="input-group-text bg-transparent border-right-0">
						<i class="mdi mdi-account-outline text-primary"></i>
					</span>
				</div>
				<input type="text" class="form-control form-control-lg border-left-0" name="email" value="" placeholder="E-mail">
			</div>
		</div>
		<div class="my-3">
			<button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">Отправить</button>
		</div>
		<div class="text-center mt-4 font-weight-light">
			<a href="/auth/" class="text-primary">Авторизация</a>
		</div>
	</form>
</div>
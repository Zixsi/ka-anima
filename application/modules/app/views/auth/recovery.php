<div class="auth-form-transparent text-left p-3">
	<div class="brand-logo">
		<a href="/"><img src="<?=TEMPLATE_DIR?>/main_v1/img/logo_black.png" alt="logo"></a>
	</div>
	<h4>Восстановление пароля</h4>
	<form action="" method="POST" class="pt-2" id="auth--recovery-form">
		<input type="hidden" name="code" value="<?=$code?>">
		<div class="form-group">
			<div class="input-group">
				<div class="input-group-prepend bg-transparent">
					<span class="input-group-text bg-transparent border-right-0">
						<i class="mdi mdi-lock-outline text-primary"></i>
					</span>
				</div>
				<input type="password" class="form-control form-control-lg border-left-0" name="password" value="" placeholder="Пароль">
			</div>
		</div>
		<div class="form-group">
			<div class="input-group">
				<div class="input-group-prepend bg-transparent">
					<span class="input-group-text bg-transparent border-right-0">
						<i class="mdi mdi-lock-outline text-primary"></i>
					</span>
				</div>
				<input type="password" class="form-control form-control-lg border-left-0" name="re_password" value="" placeholder="Повторить пароль">
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
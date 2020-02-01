<div class="auth-form-transparent text-left p-3">
	<div class="brand-logo">
		<a href="/"><img src="<?=TEMPLATE_DIR?>/main_v1/img/logo_black.png" alt="logo"></a>
	</div>
	<h4>Авторизация</h4>
	<form action="" method="POST" class="pt-2" id="auth--login-form">
		<input type="hidden" name="remember" value="0">
		<div class="form-group">
			<div class="input-group">
				<div class="input-group-prepend bg-transparent">
					<span class="input-group-text bg-transparent border-right-0">
						<i class="mdi mdi-account-outline text-primary"></i>
					</span>
				</div>
				<input type="text" class="form-control form-control-lg border-left-0" name="email" value="<?=($remembered['email'] ?? '')?>" placeholder="E-mail">
			</div>
		</div>
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
		<div class="my-2 d-flex justify-content-between align-items-center">
			<div class="form-check">
				<label class="form-check-label text-muted">
					<input type="checkbox" class="form-check-input" name="remember" <?=($remembered['remember'] ?? false)?'checked="true"':''?> value="1">
					Запомнить меня
				</label>
			</div>
			<a href="/auth/forgot/" class="auth-link text-black">Забыли пароль?</a>
		</div>
		<div class="my-3">
			<button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">Войти</button>
		</div>
		<div class="text-center">
			<?=$this->ulogin->getCode()?>
		</div>
		<div class="text-center mt-4 font-weight-light">
			У вас нет аккаунта? <a href="/auth/register/" class="text-primary">Создать</a>
		</div>
	</form>
</div>
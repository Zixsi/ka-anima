<div class="auth-form-transparent text-left p-3">
	<div class="brand-logo">
		<a href="/"><img src="<?=TEMPLATE_DIR?>/main_v1/img/logo_black.png" alt="logo"></a>
	</div>
	<h4>Регистрация</h4>
	<form action="" method="POST" class="pt-3" id="auth--register-form">
		<input type="hidden" name="agree" value="0">
		<div class="form-group">
			<div class="input-group">
				<div class="input-group-prepend bg-transparent">
					<span class="input-group-text bg-transparent border-right-0">
						<i class="mdi mdi-account-outline text-primary"></i>
					</span>
				</div>
				<input type="text" class="form-control form-control-lg border-left-0"  name="name" value="" placeholder="Имя">
			</div>
		</div>
		<div class="form-group">
			<div class="input-group">
				<div class="input-group-prepend bg-transparent">
					<span class="input-group-text bg-transparent border-right-0">
						<i class="mdi mdi-account-outline text-primary"></i>
					</span>
				</div>
				<input type="text" class="form-control form-control-lg border-left-0" name="lastname" value="" placeholder="Фамилия">
			</div>
		</div>
		<div class="form-group">
			<div class="input-group">
				<div class="input-group-prepend bg-transparent">
					<span class="input-group-text bg-transparent border-right-0">
						<i class="mdi mdi-email-outline text-primary"></i>
					</span>
				</div>
				<input type="email" class="form-control form-control-lg border-left-0" name="email" value="" placeholder="E-mail">
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
		<div class="mb-4">
			<div class="form-check">
				<label class="form-check-label text-muted">
					<input type="checkbox" class="form-check-input" name="agree" value="1">
					Я согласен со всеми условиями
				</label>
				<label class="form-check-label"><a href="<?=$landUrl?>/terms/" target="_blank">Правила и условия</a> <a href="<?=$landUrl?>/policy/" target="_blank">Политика конфиденциальности</a></label>
			</div>
		</div>
		<div class="mt-3">
			<button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" onclick="ym(55112686, 'reachGoal', 'RegSchool'); return true;">Регистрация</button>
		</div>
		<div class="text-center mt-4 font-weight-light">
			Уже есть аккаунт? <a href="/auth/" class="text-primary">Авторизация</a>
		</div>
	</form>
</div>
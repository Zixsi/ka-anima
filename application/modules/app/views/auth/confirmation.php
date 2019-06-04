<div class="auth-form-transparent text-left p-3">
	<div class="brand-logo">
		<a href="/"><img src="<?=TEMPLATE_DIR?>/main_v1/assets/img/logo_black.png" alt="logo"></a>
	</div>
	<h4>Подтверждение регистрации</h4>
	<?if($success):?>
		<div class="alert alert-fill-success alert-with-icon">
			<i class="fas fa-exclamation-circle"></i>
			<p>Регистрация успешно подстверждена.</p>
			<p>Пожалуйста, авторизуйтесь, перейдя по ссылке.</p>
		</div>
	<?else:?>
		<div class="alert alert-fill-danger alert-with-icon">
			<i class="fas fa-exclamation-circle"></i>
			<p>Неверный код подтверждения.</p>
		</div>
	<?endif;?>
	<div class="text-center mt-4 font-weight-light">
		<a href="/auth/" class="text-primary">Авторизация</a>
	</div>
</div>
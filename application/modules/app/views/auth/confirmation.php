<div class="auth-form-transparent text-left p-3">
	<div class="brand-logo">
		<a href="/"><img src="<?=TEMPLATE_DIR?>/main_v1/img/logo_black.png" alt="logo"></a>
	</div>
	<h4>Подтверждение регистрации</h4>
	<?if($message['text']):?>
		<div class="alert alert-fill-<?=($message['status']?'success':'danger')?>">
			<i class="mdi mdi-alert-circle"></i><?=$message['text']?>
		</div>
	<?endif;?>
	<div class="text-center mt-4 font-weight-light">
		<a href="/auth/" class="text-primary">Авторизация</a>
	</div>
</div>
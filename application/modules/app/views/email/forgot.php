<!DOCTYPE html>
<html>
<head>
	<title>Восстановление пароля</title>
</head>
<body>
	<p>С сайта <a href="<?=($site['url'] ?? '')?>" target="_blank"><?=($site['name'] ?? '')?></a> поступил запрос на изменение доступов к аккаунту.</p>
	<p>Для подтверждения действия перейдите по {unwrap}<a href="<?=($url ?? '')?>" target="_blank">ссылке</a>{/unwrap}.</p>
	<p>С уважением, команда <?=($site['name'] ?? '')?><p>
</body>
</html>
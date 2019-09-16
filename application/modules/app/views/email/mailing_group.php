<!DOCTYPE html>
<html>
<head>
	<title>Начало курса</title>
</head>
<body>
	<p>На сайте <a href="<?=($site['url'] ?? '')?>" target="_blank"><?=($site['name'] ?? '')?></a> <?=($date ?? '')?> начнется курс - 
	 {unwrap}<a href="<?=($site['url'] ?? '')?>groups/<?=($group_code ?? '')?>/" target="_blank"><?=($name ?? '')?></a>{/unwrap}.</p>
	<p>С уважением, команда <?=($site['name'] ?? '')?><p>
</body>
</html>
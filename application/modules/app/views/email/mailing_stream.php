<!DOCTYPE html>
<html>
<head>
	<title>Начало онлайн встречи</title>
</head>
<body>
	<p>На сайте <a href="<?=($site['url'] ?? '')?>" target="_blank"><?=($site['name'] ?? '')?></a> <?=($date ?? '')?> начнется онлайн встреча - 
	 {unwrap}<a href="<?=($site['url'] ?? '')?>groups/<?=($group_code ?? '')?>/stream/<?=($id ?? 0)?>/" target="_blank"><?=($name ?? '')?></a>{/unwrap}.</p>
	<p>С уважением, команда <?=($site['name'] ?? '')?><p>
</body>
</html>
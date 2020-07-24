<!DOCTYPE html>
<html>
    <head>
        <title>Регистрация</title>
    </head>
    <body>
        <p>Ваша регистрация в онлайн-школе <a href="<?= ($site['url'] ?? '') ?>" target="_blank"><?= ($site['name'] ?? '') ?></a> прошла успешно.</p>
        <?php if($originPassword): ?>
            <p>Ваш пароль: <b><?=$originPassword?></b></p>
        <?php endif;?>
        <p>Остался всего один шаг — {unwrap}<a href="<?= ($url ?? '') ?>" target="_blank">подтвердите регистрацию</a>{/unwrap} и Вы у цели!</p>
        <p>С уважением, команда <?= ($site['name'] ?? '') ?><p>
    </body>
</html>
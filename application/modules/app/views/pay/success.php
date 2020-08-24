<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="<?=TEMPLATE_DIR?>/main_v1/img/favicon.ico" />
</head>
<body>
    <?php
    include_once APPPATH . 'modules/app/views/metriks.php';
    ?>
<script type="text/javascript">
    window.onload = function() {
        ym(51851432,'reachGoal','oplataLend', {}, function(){
            window.location.href = '/transactions/';
        });
    }
</script>
</body>
</html>
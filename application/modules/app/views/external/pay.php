<div class="auth-form-transparent text-left p-3">
    <div class="brand-logo">
        <a href="/"><img src="<?= TEMPLATE_DIR ?>/main_v1/img/logo_black.png" alt="logo"></a>
    </div>
    
    <?php if(empty($item)):?>
        <div class="alert alert-danger"><?=$error?></div>
    <?php else:?>
    
        <form action="/external/pay/?code=<?=$item['code']?>&target=<?=$target?>" method="post" class="pt-2" id="external-pay-form">
            <input type="hidden" name="course" value="<?=$item['code']?>">
            <div class="form-group text-center">
                <h3><?=$item['name']?></h3>
            </div>
            
            <?php if($target === 'workshop'):?>
                <div class="form-group text-center" id="price-block">
                    <span class="active"><?=$item['price']?> руб</span>
                </div>
            <?php else:?>
                <?php if((bool) $item['only_standart'] === false):?>
                    <div class="form-group text-center">
                        <label class="mx-2">
                            Стандарт <input type="radio" name="type" value="standart" checked="true" <?=set_radio2('type', 'standart', true)?>>
                        </label>
                        <label class="mx-2">
                            Расширенный <input type="radio" name="type" value="advanced" <?=set_radio2('type', 'advanced')?>>
                        </label>

                        <?php if((float) $item['price']['vip']['full'] > 0.0): ?>
                            <label class="mx-2">
                                Премиум <input type="radio" name="type" value="vip" <?=set_radio2('type', 'vip')?>>
                            </label>
                        <?php endif;?>
                    </div>
                <?php else:?>
                    <input type="radio" name="type" value="standart" checked="true" style="visibility: hidden; opacity: 0; position: absolute; width: 1px; height: 1px;">
                <?php endif;?>
                <div class="form-group text-center">
                    <label class="mx-2">
                        Полная <input type="radio" name="period" value="full" <?=set_radio2('period', 'full', true)?>>
                    </label>
                    <label class="mx-2">
                        Помесячная <input type="radio" name="period" value="month" <?=set_radio2('period', 'month')?>>
                    </label>
                </div> 
                <div class="form-group text-center" id="price-block">
                    <?php foreach ($item['price'] as $key => $row):?>
                        <span id="price-<?=$key?>-full" <?=($key . '-full' === $type . '-' . $period)?'class="active"':''?>><?=$row['full']?> руб</span>
                        <span id="price-<?=$key?>-month" <?=($key . '-month' === $type . '-' . $period)?'class="active"':''?>><?=$row['month']?> руб/мес</span>
                    <?php endforeach;?>
                </div>
            <?php endif;?>

            <?php if($error): ?>
                <div class="alert alert-danger"><?=$error?></div>
            <?php endif;?>

            <?php if($isAuth === false): ?>
                <div class="form-group">
                    <p style="font-size: 14px;">Для продолжения оформления подписки необходимо <a href="/" class="text-primary">авторизоваться</a> или пройти быструю регистрацию</p>
                </div>

                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend bg-transparent">
                            <span class="input-group-text bg-transparent border-right-0">
                                <i class="mdi mdi-account-outline text-primary"></i>
                            </span>
                        </div>
                        <input type="text" class="form-control form-control-lg border-left-0" name="email" value="<?= ($email ?? '') ?>" placeholder="E-mail">
                    </div>
                </div>
            <?php endif; ?>

            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-prepend bg-transparent">
                        <span class="input-group-text bg-transparent border-right-0">
                            <i class="mdi mdi-qrcode text-primary"></i>
                        </span>
                    </div>
                    <input type="text" class="form-control form-control-lg border-left-0" name="promocode" value="<?= ($promocode ?? '') ?>" placeholder="Промокод">
                </div>
            </div>
                
            <div class="my-3">
                <?php if($isAuth): ?>
                    <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium">Оплатить</button>
                <?php else: ?>
                    <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium">Продолжить</button>
                <?php endif; ?>
            </div>

            <?php if($isAuth === false): ?>
                <div class="text-center">
                    <p style="font-size: 14px;">Или авторизуйтесь спомощью одного из сервисов</p>
                    <?=$this->ulogin->getCode() ?>
                </div>
            <?php endif; ?>

        </form>
    <?php endif; ?>
</div>
<h3>Мастерская</h3>
<ul class="nav nav-pills nav-pills-custom">
    <li class="nav-item">
        <a class="nav-link" href="/courses/">Курсы</a>
    </li>
    <li class="nav-item">
        <a class="nav-link active" href="/workshop/">Мастерская</a>
    </li>
    <?php if ($this->Auth->isActive()) :?>
        <li class="nav-item">
            <a class="nav-link" href="/subscription/">
                <span>Подписки</span>
                <?php if ($this->notifications->check('subscription')) :?>
                    <?php $this->notifications->showPoint()?>
                <?php endif;?>
            </a>
        </li>
    <?php endif;?>
</ul>

<?php /*if($types):?>
    <?foreach($types as $typeCode => $typeName):?>
        <span class="badge badge-primary"><?=$typeName?></span>
    <?endforeach;?>
<?endif;*/?>

<div class="row" id="courses--list">
    <?php if ($items) :?>
        <?php //debug($items);?>
        <?php foreach ($items as $val) :?>
            <div class="col-12 col-sm-6 col-lg-4 col-xl-3 item">
                <div class="card">
                    <span class="badge badge-danger badge-type"><?=$types[$val['type']]?></span>
                    <span class="badge badge-danger badge-free"><?=priceFormat($val['price'])?></span>
                    <?php if ($val['type'] === 'webinar') :?>
                        <span class="badge badge-danger badge-date"><?=date(DATE_FORMAT_SHORT.' H:i', strtotime($val['date']))?></span>
                    <?php else :?>
                        <span class="badge badge-danger badge-date"><?=date(DATE_FORMAT_SHORT, strtotime($val['date']))?></span>
                    <?php endif;?>
                    <a href="/workshop/item/<?=$val['code']?>/"><img class="card-img-top" src="/<?=$val['img']?>" alt="<?=htmlspecialchars($val['title'])?>"></a>
                    <div class="card-body">
                        <h4 class="card-title mt-1">
                            <a href="/workshop/item/<?=$val['code']?>/" class="text-primary"><?=htmlspecialchars($val['title'])?></a>
                        </h4>
                        <div class="card-text"><?=strip_tags($val['description'])?></div>
                        <div class="text-center">
                            <a href="/workshop/item/<?=$val['code']?>/" class="btn btn-primary btn-block">Подробнее</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach;?>
    <?php else :?>
        <div class="col-12 pt-4">
            <h4 class="text-center">Список предложений пуст</h4>
        </div>
    <?php endif;?>
</div>
<h3>Подписки</h3>
<ul class="nav nav-pills nav-pills-custom">
    <li class="nav-item">
        <a class="nav-link" href="/courses/">Курсы</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="/workshop/">Мастерская</a>
    </li>
    <li class="nav-item">
        <a class="nav-link active" href="/subscription/">
            <span>Подписки</span>
            <?php if ($this->notifications->check('subscription')) :?>
                <?php $this->notifications->showPoint()?>
            <?php endif;?>
        </a>
    </li>
</ul>

<div class="row" id="courses--list">
    <?php if (count($items)) :?>
        <?php //debug($items);?>
        <?php foreach ($items as $val) :?>
            <div class="col-12 col-sm-6 col-lg-4 col-xl-3 item">
                <div class="card">
                    <span class="badge badge-danger badge-type"><?=$val['objectTypeName']?></span>
                    <?php /*if($val['type'] === 'workshop'):?>
                        <span class="badge badge-danger badge-date"><?=date(DATE_FORMAT_SHORT, $val['tsStart'])?></span>
                    <?endif;*/?>
                    <a href="<?=$val['url']?>"><img class="card-img-top" src="/<?=$val['img']?>" alt="<?=$val['name']?>"></a>
                    <div class="card-body">
                        <h4 class="card-title mt-1">
                            <?php if ($val['isActive']) :?>
                                <a href="<?=$val['url']?>" class="text-primary">
                            <?php else :?>
                                <span class="text-primary">
                            <?php endif;?>
                            
                            <span><?=$val['name']?></span>
                            <?php if ($val['type'] === 'course') :?>
                                &nbsp;<span><?=date(DATE_FORMAT_SHORT, $val['tsStart'])?> - <?=date(DATE_FORMAT_SHORT, $val['tsEnd'])?></span>
                            <?php endif;?>

                            <?php if ($val['isActive']) :?>
                                </a>
                            <?php else :?>
                                </span>
                            <?php endif;?>
                        </h4>
                        <div class="card-text"><?=strip_tags($val['description'])?></div>
                        <div class="text-center">
                            <?php if ($val['isActive']) :?>
                                <a href="<?=$val['url']?>" class="btn btn-primary btn-block">Просмотр</a>
                            <?php else :?>
                                <a href="/pay/?action=renewal&hash=<?=$val['hash']?>" class="btn btn-success btn-block">Продлить</a>
                            <?php endif;?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach;?>
    <?php else :?>
        <div class="col-12">
            <h4 class="text-center">Список подписок пуст</h4>
        </div>
    <?php endif;?>
</div>
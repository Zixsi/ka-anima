<?php
if (!isset($not_viewed)) {
    $not_viewed = $this->ReviewModel->notViewedItems((int) $this->user['id'], (int) $group['id']);
}

$timestamp_start = strtotime($group['ts']);
?>
<h3 class="text-center" style="margin-bottom: 30px;">
    <span><?= $group['name'] ?> [<?= GroupsHelper::getTypeName($group['type']) ?>] </span>
    <?php if($group['ts'] !== 'standart' && $timestamp_start > time()):?>
        <span class="badge badge-danger">Начало <?= date(DATE_FORMAT_SHORT, $timestamp_start) ?></span>
    <?php endif;?>
</h3>
<?php if ((strtotime($subscr['ts_end']) - time()) <= 604800): ?>
<div class="alert alert-info w-100 text-center" style="font-size: 16px;">
    Срок действия вашей подписки заканчивается <?=date('d.m.Y', strtotime($subscr['ts_end']))?>. Продлите чтобы продолжить обучение.
    <a href="/pay/?action=renewal&hash=<?=$subscr['hash']?>" class="btn btn-info btn-sm">Продлить</a> 
</div>
<?php endif;?>
<div class="w-100"></div>
<div class="col-12 mb-2">
    <ul class="nav nav-pills nav-pills-custom">
        <li class="nav-item">
            <a href="/groups/<?= $group['code'] ?>/" class="nav-link <?= ($section == 'index') ? 'active' : '' ?>">Лекции</a>
        </li>
        <li class="nav-item">
            <a href="/groups/<?= $group['code'] ?>/group/" class="nav-link <?= ($section == 'group') ? 'active' : '' ?>">Группа</a>
        </li>
        <?if($subscr['type'] !== 'standart'):?>
        <li class="nav-item">
            <a href="/groups/<?= $group['code'] ?>/review/" class="nav-link <?= ($section == 'review') ? 'active' : '' ?>" style="position: relative;">
                <span>Ревью</span>
                <?if(count($not_viewed)):?>
                <span class="badge bg-danger" style="color: #fff; background-color: #F9354C; position: absolute; top: -8px; right: 5px;">!</span>
                <?endif;?>
            </a>
        </li>
        <li class="nav-item">
            <a href="/groups/<?= $group['code'] ?>/stream/" class="nav-link <?= ($section == 'stream') ? 'active' : '' ?>">Онлайн встречи</a>
        </li>
        <?endif;?>
    </ul>
</div>
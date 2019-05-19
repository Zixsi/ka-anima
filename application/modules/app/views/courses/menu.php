<?php
if(!isset($not_viewed))
{
	// var_dump($this->user_id);
	// var_dump($group['id']); die();

	$not_viewed = $this->ReviewModel->notViewedItems((int) $this->user_id, (int) $group['id']);
}
?>
<div class="col-xs-12 text-center" style="margin-bottom: 30px;">
	<a href="/courses/<?=$group['code']?>/" class="btn btn-<?=($section == 'index')?'primary':'default'?>">Лекции</a>
	<a href="/courses/<?=$group['code']?>/group/" class="btn btn-<?=($section == 'group')?'primary':'default'?>">Группа</a>
	<?if($subscr['type'] !== 'standart'):?>
		<a href="/courses/<?=$group['code']?>/review/" class="btn btn-<?=($section == 'review')?'primary':'default'?>" style="position: relative;">
			<span>Ревью работ</span>
			<?if(count($not_viewed)):?>
				<span class="badge bg-danger" style="color: #fff; background-color: #F9354C; position: absolute; top: -8px; right: 5px;">!</span>
			<?endif;?>
		</a>
		<a href="/courses/<?=$group['code']?>/stream/" class="btn btn-<?=($section == 'stream')?'primary':'default'?>">Онлайн встречи</a>
	<?endif;?>
</div>
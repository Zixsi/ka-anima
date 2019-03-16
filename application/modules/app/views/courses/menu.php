<?php
if(!isset($not_viewed))
{
	$not_viewed = $this->ReviewModel->notViewedItems($this->user_id, $group_id);
}
?>
<div class="col-xs-12 text-center" style="margin-bottom: 30px;">
	<a href="/courses/<?=$group_id?>/" class="btn btn-<?=($section == 'index')?'primary':'default'?>">Лекции</a>
	<a href="/courses/<?=$group_id?>/group/" class="btn btn-<?=($section == 'group')?'primary':'default'?>">Группа</a>
	<?if($group['type'] !== 'standart'):?>
		<a href="/courses/<?=$group_id?>/review/" class="btn btn-<?=($section == 'review')?'primary':'default'?>" style="position: relative;">
			<span>Ревью работ</span>
			<?if(count($not_viewed)):?>
				<span class="badge bg-danger" style="color: #fff; background-color: #F9354C; position: absolute; top: -8px; right: 5px;">!</span>
			<?endif;?>
		</a>
		<a href="/courses/<?=$group_id?>/stream/" class="btn btn-<?=($section == 'stream')?'primary':'default'?>">Онлайн встречи</a>
	<?endif;?>
</div>
<?php
if(!isset($not_viewed))
{
	$not_viewed = $this->ReviewModel->notViewedItems((int) $this->user['id'], (int) $group['id']);
}
?>
<div class="w-100"></div>
<div class="col-6 offset-3 mb-2">
	<ul class="nav nav-pills nav-pills-custom">
		<li class="nav-item">
			<a href="/groups/<?=$group['code']?>/" class="nav-link <?=($section == 'index')?'active':''?>">Лекции</a>
		</li>
		<li class="nav-item">
			<a href="/groups/<?=$group['code']?>/group/" class="nav-link <?=($section == 'group')?'active':''?>">Группа</a>
		</li>
		<?if($subscr['type'] !== 'standart'):?>
			<li class="nav-item">
				<a href="/groups/<?=$group['code']?>/review/" class="nav-link <?=($section == 'review')?'active':''?>" style="position: relative;">
					<span>Лекции</span>
					<?if(count($not_viewed)):?>
						<span class="badge bg-danger" style="color: #fff; background-color: #F9354C; position: absolute; top: -8px; right: 5px;">!</span>
					<?endif;?>
				</a>
			</li>
			<li class="nav-item">
				<a href="/groups/<?=$group['code']?>/stream/" class="nav-link <?=($section == 'stream')?'active':''?>">Онлайн встречи</a>
			</li>
		<?endif;?>
	</ul>
</div>
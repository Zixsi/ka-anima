<h3>Курсы</h3>
<ul class="nav nav-pills nav-pills-custom">
	<li class="nav-item">
		<a class="nav-link" href="/courses/">Предложения</a>
	</li>
	<li class="nav-item">
		<a class="nav-link active" href="/groups/">Подписки</a>
	</li>
</ul>

<div class="row" id="courses--list">
	<?if(!count($items)):?>
		<?//debug($items);?>
		<?foreach($items as $val):?>
			<div class="col-12 col-sm-6 col-lg-4 col-xl-3 item">
				<div class="card">
					<a href="/groups/<?=$val['code']?>/"><img class="card-img-top" src="<?=$val['img']?>" alt="<?=$val['name']?>"></a>
					<div class="card-body">
						<h4 class="card-title mt-1">
							<?if($val['subscr_active']):?>
								<a href="/groups/<?=$val['code']?>/" class="text-primary"><?=$val['name']?> <?=$val['ts_f']?> - <?=$val['ts_end_f']?></a>
							<?else:?>
								<span class="text-primary"><?=$val['name']?> <?=$val['ts_f']?> - <?=$val['ts_end_f']?></span>
							<?endif;?>
						</h4>
						<div class="card-text"><?=strip_tags($val['preview_text'])?></div>
						<div class="text-center">
							<?if($val['subscr_active']):?>
								<a href="/groups/<?=$val['code']?>/" class="btn btn-primary btn-block">Просмотр</a>
							<?else:?>
								<a href="/pay/?action=renewal&hash=<?=$val['hash']?>" class="btn btn-success btn-block">Продлить</a>
							<?endif;?>
						</div>
					</div>
				</div>
			</div>
		<?endforeach;?>
	<?else:?>
		<div class="col-12">
			<h4 class="text-center">Здесь будут подписки на курс</h4>
		</div>
	<?endif;?>
</div>
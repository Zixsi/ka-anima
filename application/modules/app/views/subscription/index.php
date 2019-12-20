<h3>Подписки</h3>
<ul class="nav nav-pills nav-pills-custom">
	<li class="nav-item">
		<a class="nav-link" href="/courses/">Курсы</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" href="/workshop/">Мастерская</a>
	</li>
	<li class="nav-item">
		<a class="nav-link active" href="/subscription/">Подписки</a>
	</li>
</ul>

<div class="row" id="courses--list">
	<?if(count($items)):?>
		<?//debug($items);?>
		<?foreach($items as $val):?>
			<div class="col-12 col-sm-6 col-lg-4 col-xl-3 item">
				<div class="card">
					<span class="badge badge-danger badge-type"><?=$val['objectTypeName']?></span>
					<a href="<?=$val['url']?>"><img class="card-img-top" src="/<?=$val['img']?>" alt="<?=$val['name']?>"></a>
					<div class="card-body">
						<h4 class="card-title mt-1">
							<?if($val['isActive']):?>
								<a href="<?=$val['url']?>" class="text-primary">
							<?else:?>
								<span class="text-primary">
							<?endif;?>
							
							<span><?=$val['name']?></span>
							<?if($val['type'] === 'course'):?>
								&nbsp;<span><?=date(DATE_FORMAT_SHORT, $val['tsStart'])?> - <?=date(DATE_FORMAT_SHORT, $val['tsEnd'])?></span>
							<?endif;?>

							<?if($val['isActive']):?>
								</a>
							<?else:?>
								</span>
							<?endif;?>
						</h4>
						<div class="card-text"><?=strip_tags($val['description'])?></div>
						<div class="text-center">
							<?if($val['isActive']):?>
								<a href="<?=$val['url']?>" class="btn btn-primary btn-block">Просмотр</a>
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
			<h4 class="text-center">Список подписок пуст</h4>
		</div>
	<?endif;?>
</div>
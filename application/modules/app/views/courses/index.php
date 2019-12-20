<h3>Курсы</h3>
<ul class="nav nav-pills nav-pills-custom">
	<li class="nav-item">
		<a class="nav-link active" href="/courses/">Курсы</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" href="/workshop/">Мастерская</a>
	</li>
	<?if($this->Auth->isActive()):?>
		<li class="nav-item">
			<a class="nav-link" href="/subscription/">Подписки</a>
		</li>
	<?endif;?>
</ul>

<div class="row" id="courses--list">
	<?if($items):?>
		<?//debug($items);?>
		<?foreach($items as $val):?>
			<div class="col-12 col-sm-6 col-lg-4 col-xl-3 item">
				<div class="card">
					<span class="badge badge-danger badge-free"><?=($val['min_price'] == 0)?'FREE':'от '.$val['min_price_f'].' '.PRICE_CHAR?></span>
					<a href="/courses/<?=$val['code']?>/"><img class="card-img-top" src="<?=$val['img']?>" alt="<?=$val['name']?>"></a>
					<div class="card-body">
						<h4 class="card-title mt-1">
							<a href="/courses/<?=$val['code']?>/" class="text-primary"><?=$val['name']?></a>
						</h4>
						<div class="card-text"><?=strip_tags($val['preview_text'])?></div>
						<div class="text-center">
							<a href="/courses/<?=$val['code']?>/" class="btn btn-primary btn-block">Подробнее</a>
						</div>
					</div>
				</div>
			</div>
		<?endforeach;?>
	<?else:?>
		<div class="col-12 pt-4">
			<h4 class="text-center">Список предложений пуст</h4>
		</div>
	<?endif;?>
</div>
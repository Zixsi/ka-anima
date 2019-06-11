<h3>Курсы</h3>
<ul class="nav nav-pills nav-pills-custom">
	<li class="nav-item">
		<a class="nav-link active" href="/courses/">Предложения</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" href="#">Подписки</a>
	</li>
</ul>

<div class="row" id="courses--list">
	<?if($items):?>
		<?debug($items);?>
		<?foreach($items as $val):?>
			<div class="col-12 col-sm-6 col-lg-4 col-xl-3 item">
				<div class="card">
					<a href="/courses/<?=$val['code']?>/"><img class="card-img-top" src="<?=$val['img']?>" alt="<?=$val['name']?>"></a>
					<div class="card-body">
						<h4 class="card-title mt-1">
							<a href="/courses/<?=$val['code']?>/" class="text-primary"><?=$val['name']?></a>
						</h4>
						<p class="card-text"><?=$val['description']?></p>
						<div class="text-center">
							<a href="/courses/<?=$val['code']?>/" class="btn btn-primary btn-block">Подробнее</a>
						</div>
					</div>
				</div>
			</div>
		<?endforeach;?>
	<?endif;?>
</div>

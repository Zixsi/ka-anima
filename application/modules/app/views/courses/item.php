<div class="row">
	<div class="col-7">
		<div class="card course-card">
			<div class="card-body">
				<h3 class="card-title"><?=$item['name']?></h3>
				<img src="<?=$item['img_src']?>" alt="<?=$item['name']?>" title="<?=$item['name']?>" class="course-card--img">
				<p><?=$item['description']?></p>
				<h4 class="mt-3 card-title">Лекции курса</h4>

				<?if($lectures):?>
					<?$lectures = array_chunk($lectures, ceil(count($lectures) / 3));?>
					<div class="row">
						<?foreach($lectures as $col):?>
							<div class="col-4">
								<ul>
									<?foreach($col as $val):?>
										<li><?=$val['name']?></li>
									<?endforeach;?>
								</ul>
							</div>
						<?endforeach;?>
					</div>
				<?endif;?>
				<?//debug($offers);?>
				<?//$item['free']?>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
	<div class="col-5">
		<div class="card course-card--teacher mb-4">
			<div class="card-body text-center">
				<div>
					<a href="/profile/<?=$teacher['id']?>/">
						<img src="<?=$teacher['img']?>" class="img-lg rounded-circle mb-2" alt="profile image"/>
					</a>
					<h4><?=$teacher['full_name']?></h4>
					<p class="text-muted mb-0"><?=$teacher['role_name']?></p>
				</div>
				<?if(!empty($teacher['title'])):?>
					<p class="mt-2 card-text"><?=$teacher['title']?></p>
				<?endif;?>
			</div>
		</div>
		<div class="card course-card--groups">
			<div class="card-body pb-3">
				<h3 class="card-title">Группы</h3>
				<?foreach($offers as $key => $val):?>
					<a href="./?date=<?=$val['ts_formated']?>" class="btn-date-change">
						<div class="title"><?=$val['ts_formated']?> - <?=$val['ts_end_formated']?></div>
						<span for="group_<?=$val['code']?>" class="btn btn-<?=($selected_offer_index === $key)?'primary':'secondary'?>">Выбрать</span>
					</a>
				<?endforeach;?>
			</div>
		</div>
	</div>
</div>

<div class="container text-center pricing-table-wrapper pt-5">
	<div class="row pricing-table">
		<div class="col-md-6 col-xl-4 <?=($item['only_standart'])?'offset-md-3 offset-xl-4':''?> grid-margin stretch-card pricing-card">
			<form action="/pay/" method="get" class="card border-primary border pricing-card-body">
				<input type="hidden" name="action" value="new">
				<input type="hidden" name="course" value="<?=$item['code']?>">
				<input type="hidden" name="group" value="<?=($offers[$selected_offer_index]['ts_formated'] ?? '')?>">
				<input type="hidden" name="type" value="standart">
				<div class="text-center pricing-card-head">
					<h3>Стандарт</h3>
					<p>Смогу сам!</p>
					<?if((float) ($item['price']['standart']['full'] ?? 0) > 0 && (float) ($item['price']['standart']['month'] ?? 0) > 0):?>
						<h1 class="font-weight-normal mb-4">
							<span class="price-value price-value--full active"><?=number_format($item['price']['standart']['full'], 2, '.', ' ')?></span>
							<span class="price-value price-value--month"><?=number_format($item['price']['standart']['month'], 2, '.', ' ')?></span>
							&nbsp;<i class="fa fa-rub"></i>
						</h1>
						<div class="row mb-4 period-checker">
							<div class="col-12">
								<div class="form-check">
									<label class="form-check-label">
										<div class="radio-input-wrap">
											<input type="radio" class="form-check-input" name="period" value="full" checked="">
											<i class="input-helper"></i>
										</div>
										<span>Полная</span>
									</label>
								</div>
								<div class="form-check">
									<label class="form-check-label">
										<div class="radio-input-wrap">
											<input type="radio" class="form-check-input" name="period" value="month">
											<i class="input-helper"></i>
										</div>
										<span>Помесячная</span>
									</label>
								</div>
							</div>
						</div>
					<?else:?>
						<h1 class="font-weight-normal mb-4">FREE</h1>
						<div class="row mb-4 period-checker">
							<div class="col-12">
								<div class="form-check">
									<label class="form-check-label">
										<div class="radio-input-wrap">
											<input type="radio" class="form-check-input" name="period" value="full" checked="true">
											<i class="input-helper"></i>
										</div>
										<span>Полная</span>
									</label>
								</div>
								<div class="form-check">
									<label class="form-check-label">
										<div class="radio-input-wrap">
											<input type="radio" class="form-check-input" name="period" value="month" disabled="true">
											<i class="input-helper"></i>
										</div>
										<span>Помесячная</span>
									</label>
								</div>
							</div>
						</div>
					<?endif;?>
				</div>
				<ul class="list-unstyled plan-features">
					<li class="checked"><i class="fa fa-times"></i>Доступ ко всем лекциям курса</li>
					<li><i class="fa fa-times"></i>Проверка домашних работ</li>
					<li><i class="fa fa-times"></i>Закрытый канал в дискорде</li>
					<li><i class="fa fa-times"></i>Груповые онлайн встречи</li>
					<li><i class="fa fa-times"></i>Личные онлайн встречи</li>
					<li><i class="fa fa-times"></i>Сертификат об окончании курса</li>
					<li class="checked"><i class="fa fa-times"></i>Начало в назначенную дату</li>
					<li><i class="fa fa-times"></i>Старт в ближайший понедельник</li>
				</ul>
				<div class="wrapper">
					<?if($selected_offer_index !== null && $this->Auth->isActive()):?>
						<button type="submit" class="btn btn-outline-primary btn-block">Подписаться</button>
					<?else:?>
						<button type="button" class="btn btn-outline-secondary disabled btn-block">Подписаться</button>
					<?endif;?>
				</div>
			</form>
		</div>
		<?if(!$item['only_standart']):?>
			<div class="col-md-6 col-xl-4 grid-margin stretch-card pricing-card">
				<form action="/pay/" method="get" class="card border border-primary pricing-card-body">
					<input type="hidden" name="action" value="new">
					<input type="hidden" name="course" value="<?=$item['code']?>">
					<input type="hidden" name="group" value="<?=($offers[$selected_offer_index]['ts_formated'] ?? '')?>">
					<input type="hidden" name="type" value="advanced">
					<div class="text-center pricing-card-head">
						<h3>Расширенный</h3>
						<p>Хочу в группу!</p>
						<?if((float) ($item['price']['advanced']['full'] ?? 0) > 0 && (float) ($item['price']['advanced']['month'] ?? 0) > 0):?>
							<h1 class="font-weight-normal mb-4">
								<span class="price-value price-value--full active"><?=number_format($item['price']['advanced']['full'], 2, '.', ' ')?></span>
								<span class="price-value price-value--month"><?=number_format($item['price']['advanced']['month'], 2, '.', ' ')?></span>
								&nbsp;<i class="fa fa-rub"></i>
							</h1>
							<div class="row mb-4 period-checker">
								<div class="col-12">
									<div class="form-check">
										<label class="form-check-label">
											<div class="radio-input-wrap">
												<input type="radio" class="form-check-input" name="period" value="full" checked="">
												<i class="input-helper"></i>
											</div>
											<span>Полная</span>
										</label>
									</div>
									<div class="form-check">
										<label class="form-check-label">
											<div class="radio-input-wrap">
												<input type="radio" class="form-check-input" name="period" value="month">
												<i class="input-helper"></i>
											</div>
											<span>Помесячная</span>
										</label>
									</div>
								</div>
							</div>
						<?else:?>
							<h1 class="font-weight-normal mb-4">FREE</h1>
							<div class="row mb-4 period-checker">
								<div class="col-12">
									<div class="form-check">
										<label class="form-check-label">
											<div class="radio-input-wrap">
												<input type="radio" class="form-check-input" name="period" value="full" checked="true">
												<i class="input-helper"></i>
											</div>
											<span>Полная</span>
										</label>
									</div>
									<div class="form-check">
										<label class="form-check-label">
											<div class="radio-input-wrap">
												<input type="radio" class="form-check-input" name="period" value="month" disabled="true">
												<i class="input-helper"></i>
											</div>
											<span>Помесячная</span>
										</label>
									</div>
								</div>
							</div>
						<?endif;?>
					</div>
					<ul class="list-unstyled plan-features">
						<li class="checked"><i class="fa fa-times"></i>Доступ ко всем лекциям курса</li>
						<li class="checked"><i class="fa fa-times"></i>Проверка домашних работ</li>
						<li class="checked"><i class="fa fa-times"></i>Закрытый канал в дискорде</li>
						<li class="checked"><i class="fa fa-times"></i>Груповые онлайн встречи</li>
						<li><i class="fa fa-times"></i>Личные онлайн встречи</li>
						<li class="checked"><i class="fa fa-times"></i>Сертификат об окончании курса</li>
						<li class="checked"><i class="fa fa-times"></i>Начало в назначенную дату</li>
						<li><i class="fa fa-times"></i>Старт в ближайший понедельник</li>
					</ul>
					<div class="wrapper">
						<?if($selected_offer_index !== null  && $this->Auth->isActive()):?>
							<button type="submit" class="btn btn-outline-primary btn-block">Подписаться</button>
						<?else:?>
							<button type="button" class="btn btn-outline-secondary disabled btn-block">Подписаться</button>
						<?endif;?>
					</div>
				</form>
			</div>

			<?if((float) ($item['price']['vip']['full'] ?? 0) > 0 && (float) ($item['price']['vip']['month'] ?? 0) > 0):?>
				<div class="col-md-6 col-xl-4 grid-margin stretch-card pricing-card">
					<form action="/pay/" method="get" class="card border border-primary pricing-card-body">
						<input type="hidden" name="action" value="new">
						<input type="hidden" name="course" value="<?=$item['code']?>">
						<input type="hidden" name="group" value="<?=($vip_offer['ts_formated'] ?? '')?>">
						<input type="hidden" name="type" value="vip">
						<div class="text-center pricing-card-head">
							<div class="badge badge-danger info--date-start"><?=($vip_offer['ts_formated'] ?? '')?></div>
							<h3>VIP</h3>
							<p>Нужен наставник!</p>
							<h1 class="font-weight-normal mb-4">
								<span class="price-value price-value--full active"><?=number_format($item['price']['vip']['full'], 2, '.', ' ')?></span>
								<span class="price-value price-value--month"><?=number_format($item['price']['vip']['month'], 2, '.', ' ')?></span>
								&nbsp;<i class="fa fa-rub"></i>
							</h1>
							<div class="row mb-4 period-checker">
								<div class="col-12">
									<div class="form-check">
										<label class="form-check-label">
											<div class="radio-input-wrap">
												<input type="radio" class="form-check-input" name="period" value="full" checked="">
												<i class="input-helper"></i>
											</div>
											<span>Полная</span>
										</label>
									</div>
									<div class="form-check">
										<label class="form-check-label">
											<div class="radio-input-wrap">
												<input type="radio" class="form-check-input" name="period" value="month">
												<i class="input-helper"></i>
											</div>
											<span>Помесячная</span>
										</label>
									</div>
								</div>
							</div>
						</div>
						<ul class="list-unstyled plan-features">
							<li class="checked"><i class="fa fa-times"></i>Доступ ко всем лекциям курса</li>
							<li class="checked"><i class="fa fa-times"></i>Проверка домашних работ</li>
							<li class="checked"><i class="fa fa-times"></i>Закрытый канал в дискорде</li>
							<li><i class="fa fa-times"></i>Груповые онлайн встречи</li>
							<li class="checked"><i class="fa fa-times"></i>Личные онлайн встречи</li>
							<li class="checked"><i class="fa fa-times"></i>Сертификат об окончании курса</li>
							<li><i class="fa fa-times"></i>Начало в назначенную дату</li>
							<li class="checked"><i class="fa fa-times"></i>Старт в ближайший понедельник</li>
						</ul>
						<div class="wrapper">
							<?if($vip_offer !== null && $this->Auth->isActive()):?>
								<button type="submit" class="btn btn-outline-primary btn-block">Подписаться</button>
							<?else:?>
								<button type="button" class="btn btn-outline-secondary disabled btn-block">Подписаться</button>
							<?endif;?>
						</div>
					</form>
				</div>
			<?endif;?>
		<?endif;?>
	</div>
</div>
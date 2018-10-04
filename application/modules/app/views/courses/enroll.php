<div class="panel" id="enroll-top-panel">
	<div class="panel-body">
		<div class="col-xs-6">
			<h3>Запись на курс</h3>
		</div>	
		<div class="col-xs-6 text-right balance-block-wrap">
			<a href="/subscription/" class="btn btn-md btn-primary">Пополнить баланс</a>
			<span class="balance-block">
				Ваш баланс: <span href="" class="balance-value">$<?=number_format($balance, 2, '.', ' ')?></span>
			</span>
		</div>
	</div>		
</div>

<div class="course-list row">
	<?=ShowError($error);?>
	<?if($items):?>
		<?foreach($items as $item):?>
			<div class="course-list-item col-md-12 col-lg-6">
				<div class="panel">
					<div class="panel-body">
						<div class="custom-tabs-line tabs-line-bottom left-aligned" style="margin-bottom: 25px;">
							<ul class="nav" role="tablist">
								<?$i = 0;?>
								<?foreach($item['groups'] as $group):?>
									<li <?=(($i++) == 0)?'class="active"':''?>><a href="#tab-group-<?=$group['id']?>" role="tab" data-toggle="tab"><?=date('F Y', strtotime($group['ts']))?></a></li>
								<?endforeach;?>
							</ul>
						</div>
						<div class="col-xs-12">
							<div class="row">
								<div class="col-xs-4">
									<span class="thumbnail">
										<img src="/<?=$item['img']?>" alt="">
									</span>
								</div>
								<div class="col-xs-8">
									<div class="item-title"><?=$item['name']?></div>
									<p class="item-text"><?=$item['description']?></p>
								</div>
							</div>
						</div>
						<div class="col-xs-12">
							<div class="text-center">

								<span class="type-title"><?=$course_types[$item['type']]?></span>
								<?if($item['type'] == 0):?>
									<span class="type-info">(без онлайн встреч и без проверки ваших работ)</span>
								<?else:?>
									<span class="type-info">(с онлайн встречами и проверкой ваших работ)</span>
								<?endif;?>
							</div>
							<div class="tab-content">
								<?$i = 0;?>
								<?foreach($item['groups'] as $group):?>
									<div class="tab-pane fade in <?=(($i++) == 0)?'active':''?>" id="tab-group-<?=$group['id']?>">
										<?if($group['subscription']):?>
											<div class="alert alert-info text-center">
												<span>Уже подписаны</span>
											</div>
										<?else:?>
											<form action="" method="post">
												<input type="hidden" name="course" value="<?=$item['id']?>">
												<input type="hidden" name="group" value="<?=$group['id']?>">
												<input type="hidden" name="<?=$csrf['key']?>" value="<?=$csrf['value']?>">
												<div class="row">
													<div class="col-xs-6 text-center">
														<div class="price-info">Цена за первый месяц:</div>
														<label class="fancy-radio">
															<input type="radio" name="price" value="month">
															<span><i></i> <span class="price-value">$ <?=number_format($item['price']['month'], 2, '.', ' ')?></span></span>
														</label>
														
													</div>
													<div class="col-xs-6 text-center">
														<div class="price-info">Цена за весь курс:</div>
														<label class="fancy-radio">
															<input type="radio" name="price" value="full">
															<span><i></i> <span class="price-value">$ <?=number_format($item['price']['full'], 2, '.', ' ')?></span></span>
														</label>
													</div>
												</div>
												<div class="text-center">
													<button type="submit" class="btn btn-md btn-primary">Записаться</button>
												</div>
											</form>
										<?endif;?>
									</div>
								<?endforeach;?>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?endforeach;?>
	<?else:?>
		<div class="alert alert-info alert-dismissible" role="alert">
			<i class="fa fa-info-circle"></i> Нет доступных курсов
		</div>
	<?endif;?>
</div>
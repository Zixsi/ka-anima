<div class="course-list row">
	<?if($items):?>
		<?foreach($items as $item):?>
			<div class="course-list-item col-md-12 col-lg-6">
				<div class="panel">
					<div class="panel-body">
						<div class="col-xs-4">
							<span class="thumbnail">
								<img src="" alt="">
							</span>
						</div>
						<div class="col-xs-8">
							<div class="item-title"><?=$item['name']?></div>
							<p class="item-text"><?=$item['description']?></p>
						</div>
						<form action="" method="post">
							<div class="col-xs-12">
								<div class="text-center">
									<span class="type-title">Обучение с инструктором</span>
									<span class="type-info">(с онлайн встречами и проверкой ваших работ)</span>
								</div>
								<div class="row">
									<div class="col-xs-6 text-center">
										<div class="price-info">Цена за первый месяц:</div>
										<label class="fancy-radio">
											<input type="radio" name="price" value="month">
											<span><i></i> <span class="price-value">$ <?=number_format($item['price_month'], 2, '.', ' ')?></span></span>
										</label>
										
									</div>
									<div class="col-xs-6 text-center">
										<div class="price-info">Цена за весь курс:</div>
										<label class="fancy-radio">
											<input type="radio" name="price" value="full">
											<span><i></i> <span class="price-value">$ <?=number_format($item['price_full'], 2, '.', ' ')?></span></span>
										</label>
									</div>
								</div>
								<div class="text-center">
									<button type="submit" class="btn btn-md btn-primary">Записаться</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		<?endforeach;?>
	<?endif;?>
</div>
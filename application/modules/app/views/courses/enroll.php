<div class="panel" id="enroll-top-panel">
	<div class="panel-body">
		<div class="col-xs-6">
			<h3>Запись на курс</h3>
		</div>	
		<div class="col-xs-6 text-right balance-block-wrap">
			<a href="/subscription/" class="btn btn-md btn-primary">Пополнить баланс</a>
			<span class="balance-block">
				Ваш баланс: <span href="" class="balance-value"><?=number_format($balance, 2, '.', ' ')?>  руб.</span>
			</span>
		</div>
	</div>		
</div>
<?//debug($items);?>
<div class="course-list row">
	<?if($items):?>
		<?foreach($items as $item):?>
			<div class="course-list-item col-md-12 col-lg-6">
				<div class="panel">
					<div class="panel-body">
						<div class="custom-tabs-line tabs-line-bottom left-aligned" style="margin-bottom: 25px;">
							<ul class="nav" role="tablist">
								<?$i = 0;?>
								<?foreach($item['groups'] as $group):?>
									<li <?=(($i++) == 0)?'class="active"':''?>><a href="#tab-group-<?=$group['id']?>" role="tab" data-toggle="tab"><?=strftime("%d %B %Y", strtotime($group['ts']))?></a></li>
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
							<div class="tab-content">
								<?$i = 0;?>
								<?foreach($item['groups'] as $group):?>
									<div class="tab-pane fade in <?=(($i++) == 0)?'active':''?>" id="tab-group-<?=$group['id']?>">
										<?if($item['subscription']):?>
											<div class="alert alert-info text-center">
												<span>Уже подписаны</span>
											</div>
										<?else:?>
											<div class="text-center">
												<button type="button" class="btn btn-md btn-primary btn-subscr-group" data-target="#group<?=$group['id']?>-json-data">Записаться</button>
												<div class="hidden" id="group<?=$group['id']?>-json-data"><?=json_encode(['course' =>  $item['id'], 'group' => $group['id'], 'only_standart' => $item['only_standart'], 'free' => $item['free'], 'price' => $item['price']])?></div>
											</div>
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

<div class="modal" id="modal-enroll" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			</div>
			<div class="modal-body">
				<div class="groups-type-wrap">
					<div class="item item-standart">
						<div class="title">Стандарт</div>
						<div class="price">
							<div class="month">
								<span class="value">0</span>
								<span class="right">
									<span class="dec">00</span>
									<span class="info">руб/<i>мес</i></span>
								</span>
							</div>
							<div class="full">
								<span class="value">0.00</span>
								<span class="info">за весь курс</span>
							</div>
						</div>
						<div class="start">
							<form action="" method="post" class="group-subscr-form" id="item-standart-form">
								<input type="hidden" name="course" value="">
								<input type="hidden" name="group" value="">
								<input type="hidden" name="type" value="standart">
								<div class="row">
									<div class="col-xs-6"><label class="fancy-radio"><input type="radio" name="period" value="month" checked="true"><span><i></i>Месяц</span></label></div>
									<div class="col-xs-6"><label class="fancy-radio"><input type="radio" name="period" value="full"><span><i></i>Весь курс</span></label></div>
								</div>
								<button type="submit" class="btn btn-primary btn-lg">Начать</button>
							</form>
						</div>
						<div class="item-info">
							<ul>
								<li class="checked"><i class="fas fa-times"></i>Доступ к программе с записанными лекциями в онлайн доступе</li>
								<li><i class="fas fa-times"></i>Еженедельный видеоразбор ваших работ</li>
								<li><i class="fas fa-times"></i>Еженедельный онлайн разбор с преподавателем с возможностью задавать вопросы</li>
								<li><i class="fas fa-times"></i>Груповые онлайн встечи с преподавателем с возможностью задавать вопросы</li>
								<li><i class="fas fa-times"></i>Закрытый чат с преподавателем, видео онлайн консультации</li>
								<li><i class="fas fa-times"></i>Сертификат или диплом по окончании курса</li>
								<li class="checked"><i class="fas fa-times"></i>Начало курса в назначеную дату</li>
								<li><i class="fas fa-times"></i>Начало курса в ближайший понедельник</li>
							</ul>
						</div>
					</div>
					<div class="item item-advanced active">
						<div class="popular">Наиболее популярный</div>
						<div class="title">Расширенный</div>
						<div class="price">
							<div class="month">
								<span class="value">0</span>
								<span class="right">
									<span class="dec">00</span>
									<span class="info">руб/<i>мес</i></span>
								</span>
							</div>
							<div class="full">
								<span class="value">0.00</span>
								<span class="info">за весь курс</span>
							</div>
						</div>
						<div class="start">
							<form action="" method="post" class="group-subscr-form">
								<input type="hidden" name="course" value="">
								<input type="hidden" name="group" value="">
								<input type="hidden" name="type" value="advanced">
								<div class="row">
									<div class="col-xs-6"><label class="fancy-radio"><input type="radio" name="period" value="month" checked="true"><span><i></i>Месяц</span></label></div>
									<div class="col-xs-6"><label class="fancy-radio"><input type="radio" name="period" value="full"><span><i></i>Весь курс</span></label></div>
								</div>
								<button type="submit" class="btn btn-danger btn-lg">Начать</button>
							</form>
						</div>
						<div class="item-info">
							<ul>
								<li class="checked"><i class="fas fa-times"></i>Доступ к программе с записанными лекциями в онлайн доступе</li>
								<li class="checked"><i class="fas fa-times"></i>Еженедельный видеоразбор ваших работ</li>
								<li><i class="fas fa-times"></i>Еженедельный онлайн разбор с преподавателем с возможностью задавать вопросы</li>
								<li class="checked"><i class="fas fa-times"></i>Груповые онлайн встечи с преподавателем с возможностью задавать вопросы</li>
								<li><i class="fas fa-times"></i>Закрытый чат с преподавателем, видео онлайн консультации</li>
								<li class="checked"><i class="fas fa-times"></i>Сертификат или диплом по окончании курса</li>
								<li class="checked"><i class="fas fa-times"></i>Начало курса в назначеную дату</li>
								<li><i class="fas fa-times"></i>Начало курса в ближайший понедельник</li>
							</ul>
						</div>
					</div>
					<div class="item item-vip">
						<div class="title">VIP</div>
						<div class="price">
							<div class="month">
								<span class="value">0</span>
								<span class="right">
									<span class="dec">00</span>
									<span class="info">руб/<i>мес</i></span>
								</span>
							</div>
							<div class="full">
								<span class="value">0.00</span>
								<span class="info">за весь курс</span>
							</div>
						</div>
						<div class="start">
							<form action="" method="post" class="group-subscr-form">
								<input type="hidden" name="course" value="">
								<input type="hidden" name="group" value="">
								<input type="hidden" name="type" value="vip">
								<div class="row">
									<div class="col-xs-6"><label class="fancy-radio"><input type="radio" name="period" value="month" checked="true"><span><i></i>Месяц</span></label></div>
									<div class="col-xs-6"><label class="fancy-radio"><input type="radio" name="period" value="full"><span><i></i>Весь курс</span></label></div>
								</div>
								<button type="submit" class="btn btn-primary btn-lg">Начать</button>
							</form>
						</div>
						<div class="item-info">
							<ul>
								<li class="checked"><i class="fas fa-times"></i>Доступ к программе с записанными лекциями в онлайн доступе</li>
								<li class="checked"><i class="fas fa-times"></i>Еженедельный видеоразбор ваших работ</li>
								<li class="checked"><i class="fas fa-times"></i>Еженедельный онлайн разбор с преподавателем с возможностью задавать вопросы</li>
								<li class="checked"><i class="fas fa-times"></i>Груповые онлайн встечи с преподавателем с возможностью задавать вопросы</li>
								<li class="checked"><i class="fas fa-times"></i>Закрытый чат с преподавателем, видео онлайн консультации</li>
								<li class="checked"><i class="fas fa-times"></i>Сертификат или диплом по окончании курса</li>
								<li><i class="fas fa-times"></i>Начало курса в назначеную дату</li>
								<li class="checked"><i class="fas fa-times"></i>Начало курса в ближайший понедельник</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		// $('#modal-enroll').modal('show');
	});
</script>
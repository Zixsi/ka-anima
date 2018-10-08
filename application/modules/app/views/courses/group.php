<div class="row">

	<h3 class="text-center" style="margin-bottom: 30px;"><?=$group['name']?></h3>

	<div class="col-xs-12 text-center" style="margin-bottom: 30px;">
		<a href="/courses/<?=$group_id?>/" class="btn btn-default">Лекции</a>
		<a href="/courses/<?=$group_id?>/group/" class="btn btn-primary">Группа</a>
		<a href="#" class="btn btn-default disabled">Ревью работ</a>
		<a href="#" class="btn btn-default disabled">Онлайн встречи</a>
	</div>

	<div class="col-xs-6">
		<div class="panel panel-headline">
			<div class="panel-body">
				<form action="" method="post" class="form">
					<div class="form-group">
						<label>Разместить сообщение</label>
						<textarea name="text" class="form-control" rows="5" placeholder="Поделитесь вашими мыслями..."></textarea>
					</div>
					<div class="form-group text-right">
						<button type="submit" class="btn btn-primary btn-xxs">Разместить</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="col-xs-6">

		<div class="panel panel-headline">
			<div class="panel-heading">
				<h3 class="panel-title"><?=$group['name']?></h3>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-xs-3">
						<img src="/<?=$group['img_src']?>" class="img-rounded" style="width: 100%; height: auto;">
					</div>
					<div class="col-xs-4">
						<div>
							<a href="javascript:void();">
								<img src="<?=$teacher['img']?>" width="100" height="100" class="img-circle">
							</a>
						</div>
						<p>Инструктор: <?=$teacher['email']?></p>
						<p>E-mail: <?=$teacher['email']?></p>
					</div>
					<div class="col-xs-5 text-right">
						<p>Начало обучения: <?=date('Y-m-d', strtotime($group['ts']))?></p>
						<p>Завершение обучения:  <?=date('Y-m-d', strtotime($group['ts_end']))?></p>
						<p>Всего недель курса: <?=$group['cnt_all']?></p>
						<h3>Текущая неделя: <?=$group['current_week']?></h3>
						<p>Дней до окончания: 0</p>
					</div>
				</div>
			</div>
		</div>

		<div class="panel panel-headline">
			<div class="panel-heading">
				<h3 class="panel-title">Лекции</h3>
			</div>
			<div class="panel-body">
				<?if($lectures):?>
					<ul>
						<?foreach($lectures as $val):?>
							<li>
								<?if($val['active']):?>
									<a href="/courses/<?=$group_id?>/lecture/<?=$val['id']?>"><?=$val['name']?></a>
								<?else:?>
									<span><?=$val['name']?></span>
								<?endif;?>
							</li>
						<?endforeach;?>
					</ul>
				<?endif;?>
			</div>
		</div>

		<div class="panel panel-headline">
			<div class="panel-heading">
				<h3 class="panel-title">Студенты группы</h3>
			</div>
			<div class="panel-body">
				<?if($users):?>
					<?foreach($users as $val):?>
						<a href="javascript:void();">
							<a href="javascript:void();">
								<img src="<?=$val['img']?>" width="50" height="50" class="img-circle">
							</a>
						</a>
					<?endforeach;?>
				<?endif;?>
			</div>
		</div>

	</div>
</div>
<div class="row">

	<h3 class="text-center" style="margin-bottom: 30px;"><?=$group['name']?></h3>
	<?$this->load->view('courses/menu');?>

	<div class="col-xs-6">

		<div id="wall-component">
			<?$this->load->view('tpls/wall');?>
			<div class="panel panel-headline wall-panel">
				<div class="panel-body">
					<form action="" method="post" class="form wall-from" id="wall-main-form">
						<input type="hidden" name="group" value="<?=$group['id']?>" data-const="true">
						<div class="wall-from--input-wrap">
							<textarea name="text" class="wall-from--text-input" placeholder="Напишите сообщение..."></textarea>
							<button type="submit" class="btn wall-from--btn-send">
								<i class="fab fa-telegram-plane"></i>
							</button>
						</div>
					</form>
				</div>
			</div>

			<div class="media-list" id="wall-main-list">
				<?foreach($wall as $val):?>
					<div class="panel panel-headline wall-panel" id="wall-panel-id<?=$val['id']?>">
						<div class="panel-body">
							<div class="media">
								<a class="pull-left" href="/profile/<?=$val['user']?>/" target="_blank">
									<img class="media-object" src="<?=$val['img']?>" alt="<?=$val['full_name']?>">
								</a>
								<div class="media-body">
									<h4 class="media-heading">
										<a href="/profile/<?=$val['user']?>/" target="_blank"><?=$val['full_name']?></a>
										<?if($val['role'] > 0):?>
											<small><span class="label label-success"><?=$val['role_name']?></span></small>
										<?endif;?>
										<br><small><?=$val['ts']?></small>
									</h4>
									<?=htmlspecialchars($val['text'])?>
									<hr>
									<div class="wall-item-tools">
										<button type="button" class="btn btn-comments" data-id="<?=$val['id']?>">
											<i class="far fa-comment-alt"></i> <span class="info-child-cnt"><?=($val['child_cnt'] ?? '')?></span>
										</button>
									</div>
									<div class="wall-wrap-childs">
										<hr>
										<div class="wall-childs" id="wall-childs-id<?=$val['id']?>"></div>
										<form action="" method="post" class="form wall-from wall-child-from">
											<input type="hidden" name="group" value="<?=$group['id']?>" data-const="true">
											<input type="hidden" name="target" value="<?=$val['id']?>" data-const="true">
											<div class="wall-from--input-wrap">
												<textarea name="text" class="wall-from--text-input" placeholder="Напишите сообщение..."></textarea>
												<button type="submit" class="btn wall-from--btn-send">
													<i class="fab fa-telegram-plane"></i>
												</button>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?endforeach;?>
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
						<?if(empty($group['img_src'])):?>
							<span class="img-rounded" style="display: block; width: 100%; height: 100px; background-color: #ccc;"></span>
						<?else:?>
							<img src="/<?=$group['img_src']?>" class="img-rounded" style="width: 100%; height: auto;">
						<?endif;?>
					</div>
					<div class="col-xs-4">
						<div>
							<a href="/profile/<?=$teacher['id']?>/">
								<img src="<?=$teacher['img']?>" width="100" height="100" class="img-circle">
							</a>
						</div>
						<p>Инструктор: <?=$teacher['full_name']?></p>
						<p>E-mail: <?=$teacher['email']?></p>
					</div>
					<div class="col-xs-5 text-right">
						<?if(($start_course = days_to_date($group['ts'])) > 0):?>
							<p>Курс начнется через: <?=$start_course?> дней</p>
						<?endif;?>
						<p>Начало обучения: <?=date('d-m-Y', strtotime($group['ts']))?></p>
						<p>Завершение обучения:  <?=date('d-m-Y', strtotime($group['ts_end']))?></p>
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

		<?if($images):?>
			<div class="panel panel-headline">
				<div class="panel-heading">
					<h3 class="panel-title">Изображения</h3>
				</div>
				<div class="panel-body">
					<div class="row group-image-gallery">
						<?foreach($images as $img):?>
							<div class="col-xs-6 col-md-3">
								<a href="/<?=$img['src']?>" class="thumbnail" target="_blank">
									<img src="/<?=$img['src']?>" alt="" class="img-responsive">
								</a>
							</div>
						<?endforeach;?>
					</div>
				</div>
			</div>
		<?endif;?>

		<div class="panel panel-headline">
			<div class="panel-heading">
				<h3 class="panel-title">Студенты группы</h3>
			</div>
			<div class="panel-body">
				<?if($users):?>
					<?foreach($users as $val):?>
						<a href="/profile/<?=$val['id']?>/">
							<img src="<?=$val['img']?>" width="50" height="50" class="img-circle" title="<?=$val['full_name']?>">
						</a>
					<?endforeach;?>
				<?endif;?>
			</div>
		</div>

	</div>
</div>
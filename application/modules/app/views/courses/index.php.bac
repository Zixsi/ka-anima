<div class="row">
	
	<?if($error):?>
		<div class="col-xs-12"><?=ShowError($error);?></div>
	<?endif;?>

	<?if($courses):?>
		<div class="col-xs-6">
			<div class="panel">
				<div class="panel-body">
					<h4>Курсы</h4>
					<table class="table table-courses">
						<?foreach($courses as $val):?>
							<tr>
								<td class="row-course <?=($group_id == $val['id'])?'active':''?>">
									<a href="/courses/<?=$val['id']?>/">
										<span><?=$val['name']?></span>
										<?if($val['active'] == false):?>
											<span class="label label-danger">Истек</span>
										<?endif;?>
									</a>
								</td>
							</tr>
						<?endforeach;?>
					</table>
				</div>
			</div>
			<div class="panel lectures-block">
				<div class="panel-body">
					<h4>Лекции</h4>
					<table class="table">
						<?if($lectures):?>
							<?foreach($lectures as $val):?>
								<tr>
									<td>
										<span class="lnr lnr-eye"></span>
									</td>
									<td>
										<div class="row">
											<div class="col-xs-8">
												<a href="/courses/<?=$group_id?>/<?=$val['id']?>/"><?=$val['name']?></a>
											</div>
											<div class="col-xs-4 text-right">
												<span class="lnr lnr-sync"></span>
											</div>
										</div>
										<div class="progress progress-xs">
											<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 80%"></div>
										</div>
									</td>
								</tr>
							<?endforeach;?>
						<?endif;?>
					</table>
				</div>
			</div>
		</div>
		<div class="col-xs-6">
			<?if($lecture):?>
				<div class="panel">
					<div class="panel-body">
						<h4>Лекция</h4>
						<div id="lectures-video" style="background-color: #333; height: 360px; width: 100%;">
							<video src="<?=$lecture['video']?>" poster="<?=TEMPLATE_DIR?>/admin_1/assets/img/video-poster.png" width="100%" height="100%" controls="true"></video>
						</div>
					</div>
				</div>
				<div class="panel">
					<div class="panel-body">
						<h4>Задание</h4>
						<div id="lecture-task-text"><?=$lecture['task']?></div>
					</div>
				</div>
				<div class="panel">
					<div class="panel-body">
						<h4 class="panel-title" style="margin-bottom: 25px;">Загрузка заданий</h4>
						<p>Описание к требуемым форматам и размеру загружаемых файлов</p>
						<form action="" method="post" enctype="multipart/form-data">
							<input type="hidden" name="<?=$csrf['key']?>" value="<?=$csrf['value']?>">
							<div class="form-group">
								<input type="file" name="file">
							</div>
							<div class="form-group">
								<textarea class="form-control" name="text" placeholder="Комментарий к файлу"></textarea>
							</div>
							<div class="form-group">
								<button type="submit" class="btn btn-xs btn-primary">Загрузить</button>
							</div>
						</form>
					</div>
				</div>

				<?if($homework):?>
					<div class="panel">
						<div class="panel-body">
							<h4 class="panel-title" style="margin-bottom: 25px;">Мои загруженные задания</h4>
							<table class="table table-bordered">
								<thead>
									<tr>
										<th>Дата</th>
										<th>Название</th>
										<th>Комментарий</th>
									</tr>
								</thead>
								<?foreach($homework as $val):?>
									<tr>
										<td><?=$val['ts']?></td>
										<td><?=$val['name']?></td>
										<td><?=$val['comment']?></td>
									</tr>
								<?endforeach;?>
							</table>
						</div>
					</div>
				<?endif;;?>

			<?else:?>
				<div class="panel">
					<div class="panel-body">
						<h4>Лекция</h4>
						<div id="lectures-video" style="background-color: #333; height: 360px; width: 100%;">
							<video src="" poster="<?=TEMPLATE_DIR?>/admin_1/assets/img/video-poster.png" width="100%" height="100%" controls="true"></video>
						</div>
					</div>
				</div>
			<?endif;?>
		</div>
	<?else:?>
		<div class="col-xs-12 text-center">
			<h4>Еще не подписаны на курсы? Сделайте это прямо сейчас!</h4>
			<a href="/courses/enroll/" class="btn btn-md btn-primary">Записаться</a>
		</div>
	<?endif;?>
</div>
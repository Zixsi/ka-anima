<div class="row">
	<div class="col-xs-12">
		<h3 class="text-center" style="margin-bottom: 30px;"><?=$group['name']?></h3>
	</div>

	<div class="col-xs-12">

		<div class="panel panel-headline panel-color blue">
			<div class="panel-heading">
				<h3 class="panel-title"><?=$item['name']?></h3>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-xs-12">
						<?=ShowFlashMessage();?>
						<form action="" method="post" enctype="multipart/form-data">
							<input type="hidden" name="<?=$csrf['key']?>" value="<?=$csrf['value']?>">
							<div class="form-group">
								<label>Ученик</label>
								<select class="form-control" name="user">
									<option value="0" <?=set_select('user', '0', true)?>>-- none --</option>
									<!--<option value="all" <?=set_select('user', 'all')?>>All users</option>-->
									<?if($homework_users):?>
										<?foreach($homework_users as $val):?>
											<option value="<?=$val['user']?>" <?=set_select('user', $val['user'])?>><?=$val['user_name']?></option>
										<?endforeach;?>
									<?endif;?>
								</select>
							</div>
							<div class="form-group">
								<label>Url</label>
								<input type="text" class="form-control" name="video_url" value="<?=set_value('video_url', '')?>">
							</div>
							<div class="form-group">
								<label>Рекомендации</label>
								<textarea class="form-control" name="text"><?=set_value('text', '')?></textarea>
							</div>
							<div class="form-group row">
								<label class="col-xs-12">Файлы</label>
								<div class="col-xs-3">
									<input type="file" name="files[]">
								</div>
								<div class="col-xs-3">
									<input type="file" name="files[]">
								</div>
								<div class="col-xs-3">
									<input type="file" name="files[]">
								</div>
								<div class="col-xs-3">
									<input type="file" name="files[]">
								</div>
							</div>
							<div class="form-group">
								<button type="submit" class="btn btn-xs btn-primary">Добавить ревью</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>

		<div class="panel panel-headline panel-color blue">
			<div class="panel-heading">
				<h3 class="panel-title">Ревью</h3>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-xs-12">
						<?if($reviews):?>
							<table class="table table-bordered">
								<thead>
									<tr>
										<th>Дата</th>
										<th>Пользователь</th>
										<th>Видео</th>
										<th>Рекомендация</th>
									</tr>
								</thead>
								<?if($reviews):?>
									<?foreach($reviews as $val):?>
										<tr>
											<td><?=$val['ts']?></td>
											<td><?=$val['user_name']?></td>
											<td><?=$val['video_url']?></td>
											<td><?=$val['text']?></td>
										</tr>
									<?endforeach;?>
								<?endif;?>
							</table>
						<?else:?>
							Empty list
						<?endif;?>
					</div>
				</div>
			</div>
		</div>

		<div class="panel panel-headline panel-color blue">
			<div class="panel-heading">
				<h3 class="panel-title">Домашние задания</h3>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-xs-12">
						<table class="table table-bordered">
							<thead>
								<tr>
									<th>Дата</th>
									<th>Пользователь</th>
									<th>Название</th>
									<th>Комментарий</th>
									<th class="text-right">Действие</th>
								</tr>
							</thead>
							<?if($homework):?>
								<?foreach($homework as $val):?>
									<tr>
										<td><?=$val['ts']?></td>
										<td><?=$val['user_name']?></td>
										<td><?=$val['name']?></td>
										<td><?=$val['comment']?></td>
										<td class="text-right">
											<?if($val['type'] == 0):?>
												<a href="/file/download/<?=$val['file']?>" target="_blank" class="btn btn-3xs btn-primary">Скачать</a>
											<?endif;?>
										</td>
									</tr>
								<?endforeach;?>
							<?endif;?>
						</table>
					</div>
				</div>
			</div>
		</div>

	</div>
</div>
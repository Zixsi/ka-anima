<div class="row mb-4">
	<div class="col-6">
		<h3>Редактировать</h3>
	</div>
	<div class="col-6 text-right">
		<a href="../../" class="btn btn-secondary">Назад</a>
	</div>
</div>

<div class="card">
	<div class="card-body">
		<div class="col-xs-12">
			<?=showError($error);?>
			<form action="./" method="POST" enctype="multipart/form-data" id="workshop-form">
				<input type="hidden" name="<?=$csrf['key']?>" value="<?=$csrf['value']?>">
				<?if(empty($item['img']) === false):?>
					<div class="row">
						<div class="col-12">
							<img src="/<?=$item['img']?>" class="thumbnail mb-2" width="300">
						</div>
					</div>
				<?endif;?>
				<div class="row">
					<div class="col-6">
						<div class="form-group">
							<input type="hidden" name="status" value="0">
							<div class="form-check d-inline-block m-0">
								<label class="form-check-label">
									<input type="checkbox" class="form-control" name="status" value="1" <?=set_checkbox('status', 1, ((int) $item['status'] === 1))?>>
									Доступен?
								</label>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-6">
						<div class="form-group">
							<label>Название</label>
							<input type="text" name="title" class="form-control" placeholder="Название" value="<?=set_value('title', $item['title'], true)?>">
						</div>
					</div>
					<div class="col-6">
						<div class="form-group">
							<label>Тип</label>
							<input type="text" class="form-control" disabled="true" readonly="true" value="<?=$item['type']?>">
						</div>
					</div>
				</div>
				<div class="row">
					<?if($item['type'] === 'webinar'):?>
						<div class="col-6">
							<div class="form-group">
								<label>Дата начала <small>(только для вебинара)</small></label>
								<input type="text" name="date" class="form-control datetimepicker2" value="<?=set_value('date', $item['date'], true)?>">
							</div>
						</div>
					<?else: // collection ?>
						<div class="col-6">
							<div class="form-group">
								<label>Стоимость <small>(только для коллекции)</small></label>
								<input type="number" name="price" class="form-control" step="0.01" value="<?=set_value('price', $item['price'], true)?>">
							</div>
						</div>
					<?endif;?>
				</div>
				<div class="row">
					<div class="col-6">
						<div class="form-group">
							<label>Преподаватель</label>
							<select name="teacher" class="form-control">
								<option value="0">Школа CGAim</option>
								<?foreach($teachers as $val):?>
									<option value="<?=$val['id']?>" <?=set_select('teacher', $val['id'], ($item['teacher'] === $val['id']))?> ><?=$val['full_name']?></option>
								<?endforeach;?>
							</select>
						</div>
					</div>
					<div class="col-6">
						<div class="form-group">
							<label>Изображение</label>
							<input type="file" name="img" class="file-upload-default">
							<div class="input-group">
								<input type="text" class="form-control file-upload-info" disabled="" placeholder="Загрузка файла">
								<span class="input-group-append">
									<button class="file-upload-browse btn btn-secondary" type="button">Выбрать</button>
								</span>
							</div>
						</div>
					</div>
					<div class="col-6">
						<div class="form-group">
							<label>Изображение для ленда (1920х600)</label>
							<input type="file" name="img_land_bg" class="file-upload-default">
							<div class="input-group">
								<input type="text" class="form-control file-upload-info" disabled="" placeholder="Загрузка файла">
								<span class="input-group-append">
									<button class="file-upload-browse btn btn-secondary" type="button">Выбрать</button>
								</span>
							</div>
							<?if(empty($item['img_land_bg']) === false):?>
								<div class="row mt-2">
									<div class="col-12">
										<img src="/<?=$item['img_land_bg']?>" class="thumbnail mb-2" width="300">
									</div>
								</div>
							<?endif;?>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-6">
						<div class="form-group">
							<label>Ссылка на трейлер / вебинар</label>
							<input type="text" name="video" class="form-control" value="<?=set_value('video', $item['video'], true)?>">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-6">
						<div class="form-group">
							<label>Описание трейлера / вебинара</label>
							<textarea name="description" class="form-control" id="editor1" placeholder="" rows="8"><?=set_value('description', $item['description'], true)?></textarea>
						</div>
					</div>
					<?if($item['type'] === 'collection'):?>
						<div class="col-6">
							<div class="form-group">
								<label>Описание видео коллекции <small>(только для коллекции)</small></label>
								<textarea name="video_description" class="form-control" id="editor2" placeholder="" rows="8"><?=set_value('video_description', $item['video_description'], true)?></textarea>
							</div>
						</div>
					<?endif;?>
				</div>
				<div class="row">
					<div class="col-6">
						<div class="form-group">
							<label>Meta keywords</label>
							<textarea name="meta_keyword" id="" class="form-control" placeholder="" rows="8"><?=set_value('meta_keyword', $item['meta_keyword'], true)?></textarea>
						</div>
					</div>
					<div class="col-6">
						<div class="form-group">
							<label>Meta description</label>
							<textarea name="meta_description" id="" class="form-control" placeholder="" rows="8"><?=set_value('meta_description', $item['meta_description'], true)?></textarea>
						</div>
					</div>
				</div>
			</form>
			<?if($item['type'] === 'collection'):?>
				<div class="mb-2">
					<div class="pull-right">
						<button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#add-video-workshop">Добавить видео</button>
					</div>
					<h5 class="pt-3 mt-0">Видео</h5>
					<div class="clearfix"></div>
				</div>
				<table class="table mt-4">
					<thead class="thead-light">
						<tr>
							<th>Название</th>
							<th>Ссылка</th>
							<th width="80">Длительность</th>
							<th width="80">Сортировка</th>
							<th width="80" class="text-right">Действие</th>
						</tr>
					</thead>
					<tbody>
						<?if(count($videos)):?>
							<?foreach($videos as $video):?>
								<tr>
									<td><?=$video['title']?></td>
									<td><?=$video['code']?></td>
									<td><?=time2hours($video['duration'])?></td>
									<td><?=$video['sort']?></td>
									<td>
										<div class="btn-group">
											<button type="button" class="btn btn-primary btn-sm" data-id="<?=$video['id']?>" data-toggle="modal" data-target="#edit-video-workshop<?=$video['id']?>"><i class="fa fa-edit"></i></button>
											<button type="button" class="btn btn-danger btn-sm delete-video-workshop" data-id="<?=$video['id']?>"><i class="fa fa-trash"></i></button>
										</div>

										<div class="modal fade edit-video-workshop" id="edit-video-workshop<?=$video['id']?>" tabindex="-1" role="dialog">
											<div class="modal-dialog" role="document">
												<div class="modal-content">
													<div class="modal-header">
														<h5 class="modal-title">Редактировать видео</h5>
														<button type="button" class="close" data-dismiss="modal" aria-label="Close">
															<span aria-hidden="true">&times;</span>
														</button>
													</div>
													<div class="modal-body">
														<form action="" method="post">
															<input type="hidden" name="id" value="<?=$video['id']?>">
															<div class="form-group">
																<label class="form-label">Название</label>
																<input type="text" name="title" value="<?=htmlspecialchars($video['title'])?>" class="form-control">
															</div>
															<div class="form-group">
																<label class="form-label">Ссылка</label>
																<input type="text" name="code" value="<?=htmlspecialchars($video['code'])?>" class="form-control" disabled="true">
															</div>
															<div class="form-group">
																<label class="form-label">Длительность (секунд)</label>
																<input type="number" name="duration" value="<?=((int) $video['duration'])?>" min="0" class="form-control">
															</div>
															<div class="form-group">
																<label class="form-label">Сортировка</label>
																<input type="number" name="sort" value="<?=$video['sort']?>" min="0" max="65535" class="form-control">
															</div>
															<div class="form-group">
																<button type="submit" class="btn btn-primary btn-block">Сохранить</button>
															</div>
														</form>
													</div>
												</div>
											</div>
										</div>
									</td>
								</tr>
							<?endforeach;?>
						<?else:?>
							<tr>
								<td colspan="5" class="text-center">Список видео пуст</td>
							</tr>
						<?endif;?>
					</tbody>
				</table>
			<?endif;?>
			<hr>
			<div class="form-group pt-2">
				<button type="submit" class="btn btn-primary" form="workshop-form">Сохранить</button>
			</div>
		</div>
	</div>
</div>

<?if($item['type'] === 'collection'):?>
	<div class="modal fade" id="add-video-workshop" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Новое видео</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form action="" method="post">
						<input type="hidden" name="source_id" value="<?=$item['id']?>">
						<input type="hidden" name="source_type" value="workshop">
						<div class="form-group">
							<label class="form-label">Название</label>
							<input type="text" name="title" value="" class="form-control">
						</div>
						<div class="form-group">
							<label class="form-label">Ссылка</label>
							<input type="text" name="code" value="" class="form-control">
						</div>
						<div class="form-group">
							<label class="form-label">Длительность (секунд)</label>
							<input type="number" name="duration" value="0" min="0" class="form-control">
						</div>
						<div class="form-group">
							<label class="form-label">Сортировка</label>
							<input type="number" name="sort" value="500" min="0" max="65535" class="form-control">
						</div>
						<div class="form-group">
							<button type="submit" class="btn btn-primary btn-block">Сохранить</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
<?endif;?>
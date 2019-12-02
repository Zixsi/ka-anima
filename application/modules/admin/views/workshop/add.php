<div class="row mb-4">
	<div class="col-6">
		<h3>Новое предложение</h3>
	</div>
	<div class="col-6 text-right">
		<a href="../" class="btn btn-secondary">Назад</a>
	</div>
</div>

<div class="card">
	<div class="card-body">
		<div class="col-xs-12">
			<?=showError($error);?>
			<form action="./" method="POST" enctype="multipart/form-data" id="workshop-form">
				<input type="hidden" name="<?=$csrf['key']?>" value="<?=$csrf['value']?>">
				<div class="form-group">
					<input type="hidden" name="status" value="0">
					<div class="form-check d-inline-block m-0">
						<label class="form-check-label">
							<input type="checkbox" class="form-control" name="status" value="1" <?=set_checkbox('status', 1)?>>
							Доступен?
						</label>
					</div>
				</div>
				<div class="row">
					<div class="col-6">
						<div class="form-group">
							<label>Название</label>
							<input type="text" name="title" class="form-control" placeholder="Название" value="<?=set_value('title', '', true)?>">
						</div>
					</div>
					<div class="col-6">
						<div class="form-group">
							<label>Тип</label>
							<select name="type" class="form-control">
								<option value="collection" <?=set_select('type', 'collection')?>>Коллекция</option>
								<option value="webinar" <?=set_select('type', 'webinar')?>>Вебинар</option>
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-6" data-type="webinar" <?if(empty(set_select('type', 'webinar'))):?>style="display: none;"<?endif;?>>
						<div class="form-group">
							<label>Дата начала</label>
							<input type="text" name="date" class="form-control datetimepicker2" value="<?=set_value('date', date('Y-m-d H:i:00'), true)?>">
						</div>
					</div>
					<div class="col-6">
						<div class="form-group" data-type="collection" <?if(empty(set_select('type', 'collection'))):?>style="display: none;"<?endif;?>>
							<label>Стоимость</label>
							<input type="text" name="price" class="form-control" value="<?=set_value('price', '0', true)?>">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-6">
						<div class="form-group">
							<label>Преподаватель</label>
							<select name="teacher" class="form-control">
								<option value="0">Школа CGAim</option>
								<?foreach($teachers as $val):?>
									<option value="<?=$val['id']?>" <?=set_select('teacher', $val['id'])?> ><?=$val['full_name']?></option>
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
				</div>
				<div class="row">
					<div class="col-6">
						<div class="form-group">
							<label>Ссылка на трейлер / вебинар</label>
							<input type="text" name="video" class="form-control" value="<?=set_value('video', '', true)?>">
						</div>
					</div>
					<div class="col-6">
						<div class="form-group">
							<label>Описание трейлера / вебинара</label>
							<textarea name="description" class="form-control" placeholder="" rows="8"><?=set_value('description', '', true)?></textarea>
						</div>
					</div>
				</div>
				<div class="row" data-type="collection" <?if(empty(set_select('type', 'collection'))):?>style="display: none;"<?endif;?>>
					<div class="col-6">
						<div class="form-group">
							<label>Видео</label>
							<?$videoListIndex = 0?>
							<?if(count($item['video_list'])):?>
								<?foreach($item['video_list'] as $key => $row):?>
									<div class="form-group row">
										<div class="col-6">
											<label class="form-label">Название</label>
											<input type="text" name="video_list[<?=$key?>][name]" class="form-control" value="<?=htmlspecialchars($row['name'] ?? '')?>">
										</div>
										<div class="col-6">
											<label class="form-label">Ссылка</label>
											<input type="text" name="video_list[<?=$key?>][url]" class="form-control" value="<?=htmlspecialchars($row['url'] ?? '')?>">
										</div>
									</div>
									<?$videoListIndex++;?>
								<?endforeach;?>
							<?endif;?>

							<?for($i = 0; $i < 2; $i++):?>
								<div class="form-group row">
									<div class="col-6">
										<label class="form-label">Название</label>
										<input type="text" name="video_list[<?=$videoListIndex?>][name]" class="form-control" value="">
									</div>
									<div class="col-6">
										<label class="form-label">Ссылка</label>
										<input type="text" name="video_list[<?=$videoListIndex?>][url]" class="form-control" value="">
									</div>
								</div>
								<?$videoListIndex++;?>
							<?endfor;?>
						</div>
					</div>
					<div class="col-6">
						<div class="form-group">
							<label>Описание видео</label>
							<textarea name="video_description" class="form-control" placeholder="" rows="8"><?=set_value('video_description', '', true)?></textarea>
						</div>
					</div>
				</div>
				<div class="form-group">
					<button type="submit" class="btn btn-primary">Сохранить</button>
				</div>
			</form>
		</div>
	</div>
</div>
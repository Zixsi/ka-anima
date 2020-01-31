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
					<div class="col-6">
						<div class="form-group">
							<label>Дата начала <small>(только для вебинара)</small></label>
							<input type="text" name="date" class="form-control datetimepicker2" value="<?=set_value('date', date('Y-m-d H:i:00'), true)?>">
						</div>
					</div>
					<div class="col-6">
						<div class="form-group">
							<label>Стоимость <small>(только для коллекции)</small></label>
							<input type="number" name="price" class="form-control" step="0.01" value="<?=set_value('price', '0', true)?>">
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
				</div>
				<div class="row">
					<div class="col-6">
						<div class="form-group">
							<label>Описание трейлера / вебинара</label>
							<textarea name="description" class="form-control" id="editor1" placeholder="" rows="8"><?=set_value('description', '', true)?></textarea>
						</div>
					</div>
					<div class="col-6">
						<div class="form-group">
							<label>Описание видео коллекции <small>(только для коллекции)</small></label>
							<textarea name="video_description" class="form-control" id="editor2" placeholder="" rows="8"><?=set_value('video_description', '', true)?></textarea>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-6">
						<div class="form-group">
							<label>Meta keywords</label>
							<textarea name="meta_keyword" id="" class="form-control" placeholder="" rows="8"><?=set_value('meta_keyword', '', true)?></textarea>
						</div>
					</div>
					<div class="col-6">
						<div class="form-group">
							<label>Meta description</label>
							<textarea name="meta_description" id="" class="form-control" placeholder="" rows="8"><?=set_value('meta_description', '', true)?></textarea>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-12 col-md-6">
						<div class="alert alert-info"><i class="fa fa-info"></i>Добавление видео в коллекцию будет доступно после создания записи</div>
					</div>
				</div>
				<div class="form-group">
					<button type="submit" class="btn btn-primary">Сохранить</button>
				</div>
			</form>
		</div>
	</div>
</div>
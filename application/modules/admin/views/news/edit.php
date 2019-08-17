<div class="row mb-4">
	<div class="col-6">
		<h3>Редактирование</h3>
	</div>
	<div class="col-6 text-right">
		<a href="../" class="btn btn-secondary">Назад</a>
	</div>
</div>

<div class="card">
	<div class="card-body">
		<div class="col-12">
			<?=alert_error($error);?>
			<form action="" method="POST" enctype="multipart/form-data">
				<input type="hidden" name="<?=$csrf['key']?>" value="<?=$csrf['value']?>">
				<div class="row">
					<div class="col-12">
						<input type="hidden" name="img" value="<?=$item['img']?>">
						<?if(!empty($item['img'])):?>
							<img src="<?=$item['img']?>" class="thumbnail mb-2" width="300">
						<?endif;?>
					</div>
					<div class="form-group col-6">
						<label for="ftitle">Заголовок</label>
						<input type="text" name="title" id="ftitle" class="form-control" value="<?=htmlspecialchars(set_value('title', $item['title'], true))?>">
					</div>
					<div class="form-group col-6">
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
				<div class="form-group">
					<label for="">Описание</label>
					<textarea name="description" class="form-control" rows="10"><?=htmlspecialchars(set_value('description', $item['description'], true))?></textarea>
				</div>
				<div class="form-group">
					<label for="">Детальное описание</label>
					<textarea name="text" class="form-control" rows="20"><?=htmlspecialchars(set_value('text', $item['text'], true))?></textarea>
				</div>
				<div class="form-group">
					<button type="submit" class="btn btn-primary">Сохранить</button>
				</div>
			</form>
		</div>
	</div>
</div>
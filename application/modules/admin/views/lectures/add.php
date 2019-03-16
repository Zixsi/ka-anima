<div class="panel">
	<div class="panel-heading">
		<div class="col-xs-6">
			<h3 class="panel-title">Новая лекция</h3>
		</div>
		<div class="col-xs-6 text-right">
			<a href="../" class="btn btn-default btn-xs">Назад</a>
		</div>
	</div>
	<div class="panel-body" style="padding-top: 30px;">
		<div class="col-xs-12">
			<?=ShowError($error);?>
			<form action="./" method="POST" enctype="multipart/form-data">
				<input type="hidden" name="<?=$csrf['key']?>" value="<?=$csrf['value']?>">
				<div class="form-group">
					<label class="fancy-checkbox">
						<input type="checkbox" name="active" id="factive" class="form-control" value="1" <?=set_checkbox('active', 1)?> >
						<span>Доступен?</span>
					</label>
				</div>
				<div class="form-group">
					<label for="ftype">Тип</label>
					<select name="type" id="ftype" class="form-control">
						<?foreach($types as $key => $val):?>
							<option value="<?=$key?>" <?=set_select('type', $key)?> ><?=$val['title']?></option>
						<?endforeach;?>
					</select>
				</div>
				<div class="form-group">
					<label for="fsort">Сортировка</label>
					<input type="text" name="sort" id="fsort" class="form-control" placeholder="Сортировка" value="<?=set_value('sort', 500, true)?>">
				</div>
				<div class="form-group">
					<label for="fname">Название</label>
					<input type="text" name="name" id="fname" class="form-control" placeholder="Название" value="<?=set_value('name', '', true)?>">
				</div>
				<div class="form-group">
					<label for="fdescription">Описание</label>
					<textarea name="description" id="fdescription" class="form-control" placeholder="Описание"><?=set_value('description', '', true)?></textarea>
				</div>
				<div class="form-group">
					<label for="ftask">Задание</label>
					<textarea name="task" id="ftask" class="form-control" placeholder="Задание"><?=set_value('task', '', true)?></textarea>
				</div>
				<div class="form-group">
					<label for="fvideo">Видео</label>
					<input type="text" name="video" id="fvideo" class="form-control" placeholder="Видео" value="<?=set_value('video', '', true)?>">
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
				</div>
				<div class="form-group">
					<button type="submit" class="btn btn-xs btn-primary">Сохранить</button>
				</div>
			</form>
		</div>
	</div>
</div>
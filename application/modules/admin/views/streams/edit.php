<div class="row mb-4">
	<div class="col-6">
		<h3>Редактировать</h3>
	</div>
	<div class="col-6 text-right">
		<a href="../../" class="btn btn-secondary">Назад</a>
	</div>
</div>

<div class="card">
	<div class="card-body" style="padding-top: 30px;">
		<div class="col-12">
			<?=alert_error($error);?>
			<form action="./" method="POST" enctype="multipart/form-data">
				<input type="hidden" name="<?=$csrf['key']?>" value="<?=$csrf['value']?>">
				<input type="hidden" name="group_id" value="<?=$item['group_id']?>">
				<div class="row">
					<div class="col-6">
						<div class="form-group">
							<label for="furl">Url</label>
							<input type="text" name="url" id="furl" class="form-control" placeholder="Url" value="<?=set_value('url', $item['url'], true)?>">
						</div>
						<div class="form-group">
							<label for="fperiod">Название</label>
							<input type="text" name="name" id="fname" class="form-control" placeholder="Название" value="<?=set_value('name', $item['name'], true)?>">
						</div>
						<div class="form-group">
							<label for="datetimepicker">Дата</label>
							<input type="text" name="ts" class="form-control datetimepicker" autocomplete="off" value="<?=set_value('ts', date(DATE_FORMAT_FULL, $item['ts_timestamp']), true)?>">
						</div>
					</div>
					<div class="col-6">
						<div class="form-group">
							<label for="fperiod">Описание</label>
							<textarea name="description" id="fdescription" rows="16" class="form-control" placeholder="Описание"><?=set_value('description', $item['description'], true)?></textarea>
						</div>
					</div>
				</div>
				<div class="form-group">
					<button type="submit" class="btn btn-xs btn-primary">Сохранить</button>
				</div>
			</form>
		</div>
	</div>
</div>
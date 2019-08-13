<div class="row pb-4">
	<div class="col-6">
		<h3>Редактировать</h3>
	</div>
	<div class="col-6 text-right">
		<a href="../../" class="btn btn-secondary">Назад</a>
	</div>
</div>

<div class="card">
	<div class="card-body" style="padding-top: 30px;">
		<div class="col-xs-12">
			<?=ShowError($error);?>
			<form action="./" method="POST" enctype="multipart/form-data">
				<input type="hidden" name="<?=$csrf['key']?>" value="<?=$csrf['value']?>">
				<div class="row">
					<div class="form-group col-6">
						<label for="fgroup">Группа</label>
						<select name="group_id" id="fgroup" class="form-control">
							<option value="0" selected="true">--- none ---</option>
							<?foreach($groups as $val):?>
								<option value="<?=$val['id']?>" <?=set_select('group_id', $val['id'], ($val['id'] == $item['group_id']))?> ><?=$val['name']?> [<?=$val['type']?>] (<?=date(DATE_FORMAT_SHORT, $val['timestamp_start'])?> - <?=date(DATE_FORMAT_SHORT, $val['timestamp_end'])?>)</option>
							<?endforeach;?>
						</select>
					</div>
					<div class="form-group col-6">
						<label for="furl">Url</label>
						<input type="text" name="url" id="furl" class="form-control" placeholder="Url" value="<?=set_value('url', $item['url'], true)?>">
					</div>
				</div>
				<div class="row">
					<div class="form-group col-6">
						<label for="fperiod">Название</label>
						<input type="text" name="name" id="fname" class="form-control" placeholder="Название" value="<?=set_value('name', $item['name'], true)?>">
					</div>
					<div class="form-group col-6">
						<label for="datetimepicker">Дата</label>
						<input type="text" name="ts" id="datetimepicker" class="form-control datetimepicker" autocomplete="off" value="<?=set_value('ts', date(DATE_FORMAT_FULL, strtotime($item['ts'])), true)?>">
					</div>
				</div>
				<div class="form-group">
					<label for="fperiod">Описание</label>
					<textarea name="description" id="fdescription" rows="10" class="form-control" placeholder="Описание"><?=set_value('description', $item['description'], true)?></textarea>
				</div>
				
				<div class="form-group">
					<button type="submit" class="btn btn-primary">Сохранить</button>
				</div>
			</form>
		</div>
	</div>
</div>
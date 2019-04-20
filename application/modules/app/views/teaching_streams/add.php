<div class="panel">
	<div class="panel-heading">
		<div class="col-xs-6">
			<h3 class="panel-title">Новая встреча</h3>
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
					<label for="fgroup">Группа</label>
					<select name="group_id" id="fgroup" class="form-control">
						<option value="0" selected="true">--- none ---</option>
						<?foreach($groups as $val):?>
							<option value="<?=$val['id']?>" <?=set_select('group_id', $val['id'])?> ><?=$val['name']?> (<?=date('d.m.Y', $val['timestamp_start'])?> - <?=date('d.m.Y', $val['timestamp_end'])?>)</option>
						<?endforeach;?>
					</select>
				</div>
				<div class="form-group">
					<label for="furl">Url</label>
					<input type="text" name="url" id="furl" class="form-control" placeholder="Url" value="<?=set_value('url', '', true)?>">
				</div>
				<div class="form-group">
					<label for="fperiod">Название</label>
					<input type="text" name="name" id="fname" class="form-control" placeholder="Название" value="<?=set_value('name', '', true)?>">
				</div>
				<div class="form-group">
					<label for="datetimepicker">Дата</label>
					<input type="text" name="ts" id="datetimepicker" class="form-control" autocomplete="off" value="<?=set_value('ts', '', true)?>">
				</div>
				<div class="form-group">
					<label for="fperiod">Описание</label>
					<textarea name="description" id="fdescription" rows="10" class="form-control" placeholder="Описание"><?=set_value('description', '', true)?></textarea>
				</div>
				
				<div class="form-group">
					<button type="submit" class="btn btn-xs btn-primary">Сохранить</button>
				</div>
			</form>
		</div>
	</div>
</div>
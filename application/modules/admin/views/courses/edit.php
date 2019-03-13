<div class="panel">
	<div class="panel-heading">
		<div class="col-xs-6">
			<h3 class="panel-title">Редактирование</h3>
		</div>
		<div class="col-xs-6 text-right">
			<a href="../../" class="btn btn-default btn-xs">Назад</a>
		</div>
	</div>
	<div class="panel-body" style="padding-top: 30px;">
		<div class="col-xs-12">
			<?=alert_error($error);?>
			<?=show_flash_message();?>
			<form action="./" method="POST" enctype="multipart/form-data">
				<input type="hidden" name="<?=$csrf['key']?>" value="<?=$csrf['value']?>">
				<div class="form-group">
					<label class="fancy-checkbox">
						<input type="hidden" name="active" value="0">
						<input type="checkbox" name="active" id="factive" class="form-control" value="1" <?=set_checkbox('active', 1, ($item['active'] == 1))?> >
						<span>Доступен?</span>
					</label>
				</div>
				<div class="form-group">
					<label for="ftype">Тип</label>
					<select name="type" id="ftype" class="form-control">
						<option selected="true">- - -</option>
						<?foreach($course_types as $key => $val):?>
							<option value="<?=$key?>" <?=set_select('type', $key, ($key == intval($item['type'])))?> ><?=$val?></option>
						<?endforeach;?>
					</select>
				</div>
				<div class="form-group">
					<label for="fteacher">Преподаватель</label>
					<select name="teacher" id="fteacher" class="form-control">
						<option selected="true">- - -</option>
						<?foreach($teachers as $val):?>
							<option value="<?=$val['id']?>" <?=set_select('teacher', $val['id'], ($val['id'] === $item['teacher']))?> ><?=$val['full_name']?></option>
						<?endforeach;?>
					</select>
				</div>
				<div class="form-group">
					<label for="fperiod">Название</label>
					<input type="text" name="name" id="fname" class="form-control" placeholder="Название" value="<?=set_value('name', $item['name'], true)?>">
				</div>
				<div class="form-group">
					<label for="fperiod">Описание</label>
					<textarea name="description" id="fdescription" class="form-control" placeholder="Описание"><?=set_value('description', $item['description'], true)?></textarea>
				</div>

				<div class="form-group">
					<label>Изображение</label>
					<input type="file" name="img" class="form-control">
					<?if($item['img'] > 0):?>
						<img src="/<?=$item['img_src']?>" class="thumbnail" style="margin: 20px 0px; max-width: 300px; height: auto;">
					<?endif;?>
				</div>
				<div class="form-group">
					<div class="row">
						<div class="col-xs-6">
							<label for="fprice_month">Стоимость первого месяца</label>
							<input type="text" name="price_month" id="fprice_month" class="form-control" value="<?=set_value('price_month', $item['price_month'], true)?>">
						</div>
						<div class="col-xs-6">
							<label for="fprice_full">Стоимость полного курса</label>
							<input type="text" name="price_full" id="fprice_full" class="form-control" value="<?=set_value('price_full', $item['price_full'], true)?>">
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
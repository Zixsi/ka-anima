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
					<label class="fancy-checkbox">
						<input type="hidden" name="only_standart" value="0">
						<input type="checkbox" name="only_standart" class="form-control" value="1" <?=set_checkbox('only_standart', 1, ($item['only_standart'] == 1))?> >
						<span>Только стандартный</span>
					</label>
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
					<label for="fcode">Символьный код</label>
					<input type="text" name="code" id="fcode" class="form-control" placeholder="" value="<?=set_value('code', $item['code'], true)?>">
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
					<?foreach($structure['price'] as $key => $val):?>
						<div class="row">
							<h4 class="col-xs-12">Стоимость <?=$key?></h4>
							<div class="col-xs-6">
								<label for="fprice_month">Первый месяц</label>
								<input type="text" name="price[<?=$key?>][month]" class="form-control" value="<?=set_value('price['.$key.'][month]', $item['price'][$key]['month'], true)?>">
							</div>
							<div class="col-xs-6">
								<label for="fprice_full">Полный курс</label>
								<input type="text" name="price[<?=$key?>][full]" class="form-control" value="<?=set_value('price['.$key.'][full]', $item['price'][$key]['full'], true)?>">
							</div>
						</div>
					<?endforeach;?>
				</div>

				<div class="form-group">
					<button type="submit" class="btn btn-xs btn-primary">Сохранить</button>
				</div>
			</form>
		</div>
	</div>
</div>
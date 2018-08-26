<div class="panel">
	<div class="panel-heading">
		<div class="col-xs-6">
			<h3 class="panel-title">Новый курс</h3>
		</div>
		<div class="col-xs-6 text-right">
			<a href="../" class="btn btn-default btn-xs">Назад</a>
		</div>
	</div>
	<div class="panel-body" style="padding-top: 30px;">
		<div class="col-xs-12">
			<?=ShowError($error);?>
			<form action="./" method="POST">
				<input type="hidden" name="<?=$csrf['key']?>" value="<?=$csrf['value']?>">
				<div class="form-group">
					<label class="fancy-checkbox">
						<input type="checkbox" name="active" id="factive" class="form-control" value="1" <?=set_checkbox('active', 1)?> >
						<span>Доступен?</span>
					</label>
				</div>
				<div class="form-group">
					<label for="fperiod">Название</label>
					<input type="text" name="name" id="fname" class="form-control" placeholder="Название" value="<?=set_value('name', '', true)?>">
				</div>
				<div class="form-group">
					<label for="fperiod">Описание</label>
					<textarea name="description" id="fdescription" class="form-control" placeholder="Описание"><?=set_value('description', '', true)?></textarea>
				</div>
				<div class="form-group">
					<div class="row">
						<div class="col-xs-6">
							<label for="fprice_month">Стоимость первого месяца</label>
							<input type="text" name="price_month" id="fprice_month" class="form-control" value="<?=set_value('price_month', 0, true)?>">
						</div>
						<div class="col-xs-6">
							<label for="fprice_full">Стоимость полного курса</label>
							<input type="text" name="price_full" id="fprice_full" class="form-control" value="<?=set_value('price_full', 0, true)?>">
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
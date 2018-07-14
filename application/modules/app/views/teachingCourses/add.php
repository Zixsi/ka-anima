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
				<div class="form-group">
					<label for="fperiod">Название</label>
					<input type="text" name="name" id="fname" class="form-control" placeholder="Название" value="<?=set_value('name', '', true)?>">
				</div>
				<div class="form-group">
					<label for="fperiod">Описание</label>
					<textarea name="description" id="fdescription" class="form-control" placeholder="Описание"><?=set_value('description', '', true)?></textarea>
				</div>
				<div class="form-group">
					<label for="fperiod">Периодичность набора в группу каждые</label>
					<select name="period" id="fperiod" class="form-control">
						<option value="3" <?=set_select('period', 3, true)?> >3 месяца</option>
						<option value="6" <?=set_select('period', 6)?> >6 месяцев</option>
						<option value="9" <?=set_select('period', 9)?> >9 месяцев</option>
						<option value="12"<?=set_select('period', 12)?> >12 месяцев</option>
					</select>
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
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
			<form action="" method="POST">
				<input type="hidden" name="<?=$csrf['key']?>" value="<?=$csrf['value']?>">
				<div class="form-group">
					<label>Название</label>
					<input type="text" name="name" class="form-control" value="<?=set_value('name', $item['name'], true)?>">
				</div>
				<div class="form-group">
					<button type="submit" class="btn btn-primary">Сохранить</button>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="panel">
	<div class="panel-heading">
		<div class="col-xs-6">
			<h3 class="panel-title">Добавить</h3>
		</div>
		<div class="col-xs-6 text-right">
			<a href="../" class="btn btn-default btn-xs">Назад</a>
		</div>
	</div>
	<div class="panel-body" style="padding-top: 30px;">
		<div class="col-xs-12">
			<?=alert_error($error);?>
			<form action="./" method="POST" enctype="multipart/form-data">
				<input type="hidden" name="<?=$csrf['key']?>" value="<?=$csrf['value']?>">
				<div class="form-group">
					<label for="ftitle">Заголовок</label>
					<input type="text" name="title" id="ftitle" class="form-control" value="<?=set_value('title', '', true)?>">
				</div>
				<div class="form-group">
					<label for="ftext">Описание</label>
					<textarea name="text" id="ftext" class="form-control" rows="10"><?=set_value('text', '', true)?></textarea>
				</div>
				<div class="form-group">
					<button type="submit" class="btn btn-xs btn-primary">Сохранить</button>
				</div>
			</form>
		</div>
	</div>
</div>
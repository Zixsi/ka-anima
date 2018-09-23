<h3 style="margin-bottom: 25px;"><?=$group['name']?></h3>

<div class="row">
	<div class="col-xs-6">
		<div class="panel">
			<div class="panel-heading">
				<h4 class="panel-title">Загрузка заданий</h4>
			</div>
			<?=ShowError($error);?>
			<div class="panel-body">
				<p>Описание к требуемым форматам и размеру загружаемых файлов</p>
				<form action="" method="post" enctype="multipart/form-data">
					<input type="hidden" name="<?=$csrf['key']?>" value="<?=$csrf['value']?>">
					<div class="form-group">
						<input type="file" name="file">
					</div>
					<div class="form-group">
						<textarea class="form-control" name="text" placeholder="Комментарий к файлу"></textarea>
					</div>
					<div class="form-group">
						<button type="submit" class="btn btn-xs btn-primary">Загрузить</button>
					</div>
				</form>
			</div>
		</div>
		<div class="panel">
			<div class="panel-heading">
				<h4 class="panel-title">Загруженные задания</h4>
			</div>
			<div class="panel-body">
				<table class="table table-bordered">
					<tr>
						<td>Когда</td>
						<td>Кто</td>
						<td>Файл</td>
						<td>Комментарий</td>
					</tr>
				</table>
			</div>
		</div>
	</div>
	<div class="col-xs-6">
		<div class="panel">
			<div class="panel-heading">
				<h4 class="panel-title">Инфа</h4>
			</div>
			<div class="panel-body">
				<p>Тут блоки с инфой о преподе и прочие блоки</p>
			</div>
		</div>
	</div>
</div>



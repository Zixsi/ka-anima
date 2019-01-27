<div class="panel">
	<div class="panel-heading">
		<div class="col-xs-6">
			<h3 class="panel-title">FAQ</h3>
		</div>
		<div class="col-xs-6 text-right">
			<a href="./add/" class="btn btn-primary btn-xs">Добавить</a>
		</div>
	</div>
	<div class="panel-body" style="padding-top: 30px;">
		<table class="table table-striped">
			<thead>
				<tr>
					<th>Название</th>
					<th class="text-right">Действие</th>
				</tr>
			</thead>
			<tbody>
				<?if(is_array($items)):?>
					<?foreach($items as $item):?>
						<tr>
							<td><?=$item['question']?></td>
							<td class="text-right">
								<a href="./edit/<?=$item['id']?>/" class="btn btn-xxs btn-default lnr lnr-pencil" title="Редактировать"></a>
								<form action="./" method="post" class="delete-item-form" style="display: inline;">
									<input type="hidden" name="id" value="<?=$item['id']?>">
									<input type="hidden" name="type" value="delete">
									<button type="submit" class="btn btn-xxs btn-danger lnr lnr-trash" title="Удалить"></button>
								</form>
							</td>
						</tr>
					<?endforeach;?>
				<?endif;?>
			</tbody>
		</table>
	</div>
</div>
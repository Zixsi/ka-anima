<form action="./" method="post" class="form-inline">
	<input type="hidden" name="<?=$csrf['key']?>" value="<?=$csrf['value']?>">
	<div class="form-group">
		<input type="text" name="amount" class="form-control" value="100">
	</div>
	<div class="form-group">
		<button type="submit" class="btn btn-md btn-primary">Пополнить</button>
	</div>
</form>

<?if($items):?>
	<table class="table table-striped">
		<thead>
			<tr>
				<th width="150">Дата</th>
				<th width="100">Тип</th>
				<th>Описание</th>
				<th class="text-right">Сумма</th>
			</tr>
		</thead>
		<tbody>
		<?foreach($items as $item):?>
			<tr>
				<td><?=$item['ts']?></td>
				<td><span class="label label-<?=($item['type'] == 'IN')?'success':'warning'?>"><?=$item['type']?></span></td>
				<td><?=$item['description']?></td>
				<td class="text-right"><?=number_format($item['amount'], 2, '.', ' ')?> $</td>
			</tr>
		<?endforeach;?>
		</tbody>
	</table>
<?endif;?>
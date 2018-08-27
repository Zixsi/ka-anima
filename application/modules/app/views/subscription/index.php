<table class="table table-bordered">
	<thead>
		<tr>
			<th>Название</th>
			<th>Начинается</th>
			<th>Заканчивается</th>
			<th>Статус</th>
			<th>Осталось оплатить</th>
		</tr>
	</thead>
	<tbody>
		<?foreach($items as $item):?>
			<tr>
				<td><?=$item['description']?></td>
				<td><?=$item['ts_start']?></td>
				<td><?=$item['ts_end']?></td>
				<td>
					<?if(strtotime($item['ts_end']) > time()):?>
						<span class="label label-success">Активный</span>
					<?else:?>
						<span class="label label-danger">Истек</span>
					<?endif;?>	
				</td>
				<td><?=number_format(0, 2, '.', '')?>$</td>
			</tr>
		<?endforeach;?>
	</tbody>
</table>
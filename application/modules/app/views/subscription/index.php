<table class="table table-bordered">
	<thead>
		<tr>
			<th>Название</th>
			<th>Начинается</th>
			<th>Заканчивается</th>
			<th>Статус</th>
			<th>Цена за месяц</th>
			<th>Осталось оплатить</th>
		</tr>
	</thead>
	<tbody>
		<?if($items):?>
			<?foreach($items as $item):?>
				<tr>
					<td><?=$item['description']?></td>
					<td><?=$item['ts_start']?></td>
					<td><?=$item['ts_end']?></td>
					<td>
						<?if($item['active']):?>
							<span class="label label-success">Активный</span>
						<?else:?>
							<span class="label label-danger">Истек</span>
						<?endif;?>	
					</td>
					<td>
						<form action="" method="post">
							<input type="hidden" name="<?=$csrf['key']?>" value="<?=$csrf['value']?>">
							<input type="hidden" name="id" value="<?=$item['id']?>">
							<?if($item['renew'] == 'month'):?>
								$<?=number_format($item['price_month'], 2, '.', '')?> 
								<input type="hidden" name="type" value="month">
								<button type="submit" class="btn btn-3xs btn-success">Продлить</button>
							<?elseif($item['renew'] == 'year'):?>
								<input type="hidden" name="type" value="year">
								Продлить на год ($<?=number_format($item['price_month'], 2, '.', '')?>) 
								<button type="submit" class="btn btn-3xs btn-success">Продлить</button>
							<?endif;?>
						</form>
					</td>
					<td>
						$<?=number_format($item['amount'], 2, '.', '')?>
					</td>
				</tr>
			<?endforeach;?>
		<?endif;?>
	</tbody>
</table>
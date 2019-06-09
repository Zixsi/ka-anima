<div class="panel" id="enroll-top-panel">
	<div class="panel-body">
		<div class="col-xs-6">
			<h3>Подписка</h3>
		</div>	
	</div>		
</div>

<?=alert_error($error);?>

<div class="row">
	<div class="col-xs-12">

		<div class="panel panel-headline">
			<div class="panel-heading">
				<h3 class="panel-title">Ваши подписки на курсы</h3>
			</div>
			<div class="panel-body">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th class="text-center">Название</th>
							<th class="text-center">Начинается</th>
							<th class="text-center">Заканчивается</th>
							<th class="text-center">Статус</th>
							<th class="text-center">Цена за месяц</th>
							<th class="text-center">Осталось оплатить</th>
						</tr>
					</thead>
					<tbody>
						<?if($items):?>
							<?foreach($items as $item):?>
								<tr>
									<td><?=$item['description']?></td>
									<td class="text-center"><?=date('Y-m-d', strtotime($item['ts_start']))?></td>
									<td class="text-center"><?=date('Y-m-d', strtotime($item['ts_end']))?></td>
									<td class="text-center">
										<?if($item['active']):?>
											<span class="label label-success">Активный</span>
										<?else:?>
											<span class="label label-danger">Истек</span>
										<?endif;?>	
									</td>
									<td class="text-center">
										<form action="" method="post">
											<input type="hidden" name="<?=$csrf['key']?>" value="<?=$csrf['value']?>">
											<input type="hidden" name="id" value="<?=$item['id']?>">
											<input type="hidden" name="request_type" value="renew">
											<?if($item['renew'] == 'month'):?>
												<?=number_format($item['data']['price'], 2, '.', '')?>  руб.
												<input type="hidden" name="type" value="month">
												<button type="submit" class="btn btn-3xs btn-success">Продлить</button>
											<?elseif($item['renew'] == 'year'):?>
												<input type="hidden" name="type" value="year">
												<!-- TODO: сумма продления на год -->
												Продлить на год (0 руб.) 
												<button type="submit" class="btn btn-3xs btn-success">Продлить</button>
											<?else:?>
												<span>- - -</span>
											<?endif;?>
										</form>
									</td>
									<td class="text-center">
										<?if($item['amount'] > 0):?>
											<?=number_format($item['amount'], 2, '.', '')?> руб.
										<?else:?>
											<span>- - -</span>
										<?endif;?>
									</td>
								</tr>
							<?endforeach;?>
						<?endif;?>
					</tbody>
				</table>
			</div>
		</div>

	</div>
</div>

<div class="row">
	<div class="col-xs-6">
		<div class="panel panel-headline">
			<div class="panel-heading">
				<h3 class="panel-title">Ваши платежи</h3>
			</div>
			<div class="panel-body">
				<?if($transactions['out']):?>
					<table class="table table-striped">
						<thead>
							<tr>
								<th>Описание</th>
								<th>Сумма</th>
								<th width="180">Дата</th>	
							</tr>
						</thead>
						<tbody>
						<?foreach($transactions['out'] as $item):?>
							<tr>
								<td><?=$item['description']?></td>
								<td><?=number_format($item['amount'], 2, '.', ' ')?>  руб.</td>
								<td><?=$item['ts']?></td>
							</tr>
						<?endforeach;?>
						</tbody>
					</table>
				<?endif;?>
			</div>
		</div>
	</div>
</div>
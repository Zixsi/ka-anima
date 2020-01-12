<h3 class="panel-title mb-4">Ваши платежи</h3>
<?// debug($items);?>
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<?if($items):?>
					<div class="alert alert-fill-warning" role="alert">
						<i class="mdi mdi-alert-circle"></i>
						<span>После успешного подтверждения оплаты курс можно будет найти во вкладке <a href="/subscription/">Подписки</a></span>
					</div>

					<table class="table table-striped">
						<thead>
							<tr>
								<th width="180">Дата</th>
								<th width="180">Статус</th>	
								<th>Описание</th>							
								<th class="text-right" width="180">Сумма</th>
							</tr>
						</thead>
						<tbody>
							<?foreach($items as $item):?>
								<tr class="">
									<td><?=$item['ts_f']?></td>
									<td>
										<?if($item['status'] !== TransactionsModel::STATUS_SUCCESS):?>
											<i class="mdi mdi-alert-circle mx-0 text-<?=$item['status_info']['class']?>" style="font-size: 16px;"></i>
										<?endif;?>
										<span><?=$item['status_info']['name']?></span>
									</td>
									<td><?=$item['description']?></td>
									<td class="text-right"><?=$item['amount_f']?>  <?=PRICE_CHAR?></td>
								</tr>
							<?endforeach;?>
						</tbody>
					</table>
				<?endif;?>
			</div>
		</div>
	</div>
</div>
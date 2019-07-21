<h3 class="panel-title mb-4">Ваши платежи</h3>
<?// debug($items);?>
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<?if($items):?>
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
								<tr>
									<td><?=$item['ts_f']?></td>
									<td><?=$item['status']?></td>
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
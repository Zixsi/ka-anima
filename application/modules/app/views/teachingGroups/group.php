<div class="panel">
	<div class="panel-heading">
		<div class="col-xs-6">
			<h3 class="panel-title">Лекции группы</h3>
		</div>
	</div>
	<div class="panel-body" style="padding-top: 30px;">
		<table class="table table-striped">
			<thead>
				<tr>
					<th width="100">Id</th>
					<th>Название</th>
					<th class="text-right">Дата начала</th>
				</tr>
			</thead>
			<tbody>
				<?if(is_array($items)):?>
					<?foreach($items as $item):?>
						<?if($item['type'] == 1) continue;?>
						<tr>
							<td><?=$item['id']?></td>
							<td>
								<a href="./<?=$item['id']?>/"><?=$item['name']?> (<?=date('F Y', strtotime($item['ts']))?>)</a>
							</td>
							<td class="text-right"><?=date('Y-m-d', strtotime($item['ts']))?></td>
						</tr>
					<?endforeach;?>
				<?endif;?>
			</tbody>
		</table>
	</div>
</div>
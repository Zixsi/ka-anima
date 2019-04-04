<div class="panel">
	<div class="panel-heading">
		<div class="col-xs-6">
			<h3 class="panel-title">Активные группы</h3>
		</div>
	</div>
	<div class="panel-body" style="padding-top: 30px;">
		<table class="table table-striped">
			<thead>
				<tr>
					<th width="100">Id</th>
					<th>Название</th>
					<th class="text-center">Кол-во студентов</th>
					<th class="text-center">Неделя</th>
					<th class="text-right">Начало</th>
					<th class="text-right">Конец</th>
				</tr>
			</thead>
			<tbody>
				<?if(is_array($items)):?>
					<?foreach($items as $item):?>
						<tr>
							<td><?=$item['group_id']?></td>
							<td>
								<a href="./<?=$item['group_id']?>/"><?=$item['name']?> (<?=strftime("%B %Y", strtotime($item['ts']))?>)</a>
							</td>
							<td class="text-center"><?=intval($item['user_cnt'])?></td>
							<td class="text-center"><?=intval($item['weeks_current'])?> из <?=intval($item['weeks_cnt'])?></td>
							<td class="text-right"><?=date('Y-m-d', strtotime($item['ts']))?></td>
							<td class="text-right"><?=date('Y-m-d', strtotime($item['ts_end']))?></td>
						</tr>
					<?endforeach;?>
				<?endif;?>
			</tbody>
		</table>
	</div>
</div>
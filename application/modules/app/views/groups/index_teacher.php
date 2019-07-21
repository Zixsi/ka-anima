<h3 class="panel-title mb-4">Группы</h3>
<?//debug($items);?>
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<?if($items):?>
					<table class="table table-striped">
						<thead>
							<tr>
								<th>Группа</th>
								<th class="text-right" width="100">Участники</th>							
								<th class="text-right" width="100">Тип</th>							
								<th class="text-right" width="100">Статус</th>
							</tr>
						</thead>
						<tbody>
							<?foreach($items as $item):?>
								<tr>
									<td>
										<a href="/groups/<?=$item['code']?>/"><?=$item['name']?> <?=date(DATE_FORMAT_SHORT, $item['timestamp_start'])?> - <?=date(DATE_FORMAT_SHORT, $item['timestamp_end'])?></a>
									</td>
									<td class="text-right"><?=$item['subscr_cnt']?></td>
									<td class="text-right">
										<span class="badge badge-primary d-block"><?=$item['type']?></span>
									</td>
									<td class="text-right">
										<?
										switch($item['status'])
										{
											case -1:
												echo '<span class="badge badge-secondary d-block">Завершен</span>';
											break;
											case 1:
												echo '<span class="badge badge-warning d-block">Скоро</span>';
											break;
											default:
												echo '<span class="badge badge-success d-block">В процессе</span>';
											break;
										}
										?>
									</td>
								</tr>
							<?endforeach;?>
						</tbody>
					</table>
				<?endif;?>
			</div>
		</div>
	</div>
</div>
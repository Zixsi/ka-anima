<h3 class="panel-title mb-4">Группы</h3>
<?//debug($items);?>
<div class="row">
	<div class="col-12">

		<?if($items):?>
			<ul class="nav nav-tabs" role="tablist">
				<?foreach($items as $key => $item):?>
					<li class="nav-item">
						<a class="nav-link <?=($key === 0)?'active':''?>" id="menu-tab-<?=$key?>" data-toggle="tab" href="#tab-content-<?=$key?>" role="tab"><?=$item['text']?></a>
					</li>
				<?endforeach;?>
			</ul>

			<div class="tab-content" style="background-color: #fff;">
				<?foreach($items as $key => $item):?>
					<div class="tab-pane fade <?=($key === 0)?'active show':''?>" id="tab-content-<?=$key?>" role="tabpanel">
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
								<?foreach($item['items'] as $row):?>
									<tr>
										<td>
											<a href="/groups/<?=$row['code']?>/"><?=$row['name']?> <?=date(DATE_FORMAT_SHORT, $row['timestamp_start'])?> - <?=date(DATE_FORMAT_SHORT, $row['timestamp_end'])?></a>
										</td>
										<td class="text-right"><?=$row['subscr_cnt']?></td>
										<td class="text-right">
											<span class="badge badge-primary d-block"><?=$row['type']?></span>
										</td>
										<td class="text-right">
											<span class="badge badge-<?=$item['class']?> d-block"><?=$item['text']?></span>
										</td>
									</tr>
								<?endforeach;?>
							</tbody>
						</table>
					</div>
				<?endforeach;?>
			</div>
		<?else:?>
			<div class="alert alert-info">
				<h5>Список групп пуст. Отображаются только группы с подписанными пользователями.</h5>
			</div>
		<?endif;?> 		
	</div>
</div>
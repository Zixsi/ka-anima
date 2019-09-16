<div class="row mb-4">
	<div class="col-6">
		<h3>Архив</h3>
	</div>
	<div class="col-6 text-right">
		<a href="/admin/courses/" class="btn btn-secondary">Назад</a>
	</div>
</div>

<div class="card">
	<div class="card-body">
		<form action="" method="get" class="row">
			<div class="col-6">
				<div class="input-group mb-4">
					<input type="text" name="search" placeholder="Поиск по коду" value="<?=set_value2('search')?>" class="form-control">
					<div class="input-group-append">
						<button type="submit" class="btn btn-md btn-outline-primary">Поиск</button>
					</div>
				</div>
			</div>
		</form>

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
									<th class="" width="150">Код</th>	
									<th>Группа</th>
															
									<th class="text-right" width="100">Участники</th>							
									<th class="text-right" width="100">Тип</th>							
									<th class="text-right" width="100">Статус</th>
								</tr>
							</thead>
							<tbody>
								<?foreach($item['items'] as $row):?>
									<tr>
										<td class=""><?=$row['code']?></td>
										<td>
											<a href="/admin/groups/<?=$row['code']?>/"><?=$row['name']?> <?=date(DATE_FORMAT_SHORT, $row['timestamp_start'])?> - <?=date(DATE_FORMAT_SHORT, $row['timestamp_end'])?></a>
										</td>
										<td class="text-right"><?=$row['subscription_cnt']?></td>
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
		<?endif;?>
	</div>
</div>
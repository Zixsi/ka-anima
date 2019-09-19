<div class="row">
	<div class="col-6">
		<div class="card mb-4">
			<div class="card-header">
				<h3 class="card-title pt-2">Группа <?=$item['name']?> (<?=$item['code']?>)</h3>
			</div>
			<div class="card-body">
				<button type="button" class="btn btn-primary" id="group--add-user-btn">Добавить ученика</button>
				<table class="table table-bordered mt-4">
					<thead>
						<tr>
							<th>Ученик</th>
							<th class="text-center" width="120">Задания</th>
							<th class="text-center" width="120">Непроверено</th>
						</tr>
					</thead>
					<tbody>
						<?if($users):?>
							<?foreach($users as $val):?>
								<tr <?=($user && $val['id'] == $user['id'])?'class="info"':''?>>
									<td><a href="./?user=<?=$val['id']?>"><?=$val['full_name']?></a></td>
									<td class="text-center"><?=$val['reviews']?> / <?=($item['cnt'] ?? 0)?></td>
									<td class="text-center"><?=($val['homeworks'] - $val['reviews'])?></td>
								</tr>
							<?endforeach;?>
						<?else:?>
							<tr>
								<td colspan="3" class="text-center">Ученики отсутствуют</td>
							</tr>
						<?endif;?>
					</tbody>
				</table>
			</div>
		</div>
		<?if($users):?>
			<div class="card">
				<?if($user):?>
					<div class="card-header">
						<h3 class="card-title pt-2">
							<a href="/admin/users/user/<?=$user['id']?>/" class="text-primary" target="_blank"><?=$user['full_name']?></a>
						</h3>
					</div>
				<?endif;?>
				<div class="card-body">
					<?if($user):?>
						<?if($homeworks):?>
							<div id="teacher--user-homeworks">
								<?//debug($homeworks);?>
								<ul class="nav nav-tabs" role="tablist">
									<?$n = 1;?>
									<?foreach($homeworks as $val):?>
										<li class="nav-item">
											<a class="nav-link <?=($n === 1)?'active':''?>" id="tab-<?=$n?>" data-toggle="tab" href="#tab-content-<?=$n?>" role="tab" aria-controls="tab-content-<?=$n?>" aria-selected="true"><?=$n?></a>
											<span class="badge bg-<?=$val['status']?>"><?=count($val['homeworks'])?></span>
										</li>
										<?$n++;?>
									<?endforeach;?>
								</ul>
								<div class="tab-content">
									<?$n = 1;?>
									<?foreach($homeworks as $val):?>
										<div class="tab-pane fade <?=($n === 1)?'active show':''?>" id="tab-content-<?=$n?>" role="tabpanel">
											<?if(count($val['homeworks'])):?>
												<div class="pb-2">
													<?if(empty($val['review'])):?>
														<?/*
														<button type="button" class="btn btn-primary btn-add-review" data-toggle="modal" data-group="<?=$item['id']?>" data-lecture="<?=$val['id']?>" data-user="<?=$user['id']?>">Добавить ревью</button>
														<hr>*/?>
													<?else:?>
														<div class="row">
															<div class="col-6">
																<a href="<?=$val['review']['video_url']?>" target="_blank" class="btn btn-primary btn-block">Видео</a>
															</div>
															<div class="col-6">
																<a href="<?=$val['review']['file_url']?>" target="_blank" class="btn btn-primary btn-block">Файл</a>
															</div>
														</div>
														<?if(!empty($val['review']['text'])):?>
															<div class="row">
																<div class="col-12 pt-2">
																	<p><?=$val['review']['text']?></p>
																</div>
															</div>
														<?endif;?>
														<div class="row">
															<div class="col-12 text-right">
																<button type="button" class="btn btn-secondary btn-xs bnt-remove-review" data-id="<?=$val['review']['id']?>">Удалить ревью</button>
															</div>
														</div>
														<hr>
													<?endif;?>
												</div>
												<table class="table table-striped table-bordered">
													<thead class="thead-dark">
														<tr>
															<th style="width: 150px;">Дата</th>
															<th>Комментарий</th>
															<th style="width: 90px;">#</th>
														</tr>
													</thead>
													<tbody>
														<?foreach($val['homeworks'] as $hw):?>
															<tr <?=($hw['is_new'])?'class="table-danger"':''?>>
																<td><?=date(DATE_FORMAT_FULL, $hw['ts_timestamp'])?></td>
																<td><?=$hw['comment']?></td>
																<td class="text-right">
																	<a href="./?user=<?=$user['id']?>&lecture=<?=$val['id']?>&action=download&target=homework" class="d-inline-block btn btn-primary btn-xs" target="_blank">Скачать</a>
																</td>
															</tr>
														<?endforeach;?>
													</tbody>
												</table>
											<?else:?>
												<div class="text-center">Нет загруженных файлов</div>
											<?endif;?>
										</div>

										<?$n++;?>
									<?endforeach;?>
								</div>
							</div>
						<?endif;?>
					<?else:?>
						<h3 class="text-center">Выберите ученика</h3>
					<?endif;?>
				</div>
			</div>
		<?endif;?>
	</div>
	<div class="col-6">
		<div class="card mb-4">
			<div class="card-header">
				<h3 class="card-title pt-2">Онлайн встречи</h3>
			</div>
			<div class="card-body">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th width="150">Дата</th>
							<th>Название</th>
							<th class="text-right" width="150">Статус</th>
						</tr>
					</thead>
					<tbody>
						<?if(is_array($streams)):?>
							<?foreach($streams as $val):?>
								<tr>
									<td><?=date(DATE_FORMAT_FULL, strtotime($val['ts']))?></td>
									<td>
										<a href="/admin/streams/item/<?=$val['id']?>/" target="_blank"><?=$val['name']?></a>
									</td>
									<td class="text-right">
										<?if($val['status'] == 0):?>
											<span class="badge badge-success">В процессе</span>
										<?elseif($val['status'] == -1):?>
											<span class="badge badge-danger">Завершено</span>
										<?elseif($val['status'] == 2):?>
											<span class="badge badge-info">Сегодня</span>
										<?else:?>
											<span class="badge badge-warning">Скоро</span>
										<?endif;?>
									</td>
								</tr>
							<?endforeach;?>
						<?else:?>
							<tr>
								<td colspan="3">Список встреч пуст</td>
							</tr>
						<?endif;?>
					</tbody>
				</table>
			</div>
		</div>
		<div class="card mb-4">
			<div class="card-header">
				<h3 class="card-title pt-2">Статистика</h3>
			</div>
			<div class="card-body">
				<canvas id="chart-income"></canvas>
			</div>
		</div>
	</div>
</div>


<div class="modal fade" id="group--add-user-modal" role="dialog" aria-labelledby="" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Добавить ученика</h4>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			</div>
			<div class="modal-body">
				<form action="" method="post">
					<input type="hidden" name="group_id" value="<?=$item['id']?>">
					<input type="hidden" name="type" value="<?=$item['type']?>">
					<div class="form-group users-block">
						<div>
							<select name="user" class="form-controll" id="select2-users" style="width: 100%;"></select>
						</div>
					</div>
					<div class="form-group text-right">
						<button type="submit" class="btn btn-primary">Добавить</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<script src="<?=TEMPLATE_DIR?>/main_v1/vendors/chart.js/Chart.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function(event) {
	drawChart($("#chart-income"), 'Доход', [<?=$stat['labels']?>], [<?=$stat['values']?>]);
});
</script>
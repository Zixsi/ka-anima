<div class="row pb-4">
	<div class="col-6">
		<h3>Онлайн встречи</h3>
	</div>
	<div class="col-6 text-right">
		<a href="./add/" class="btn btn-primary">Создать</a>
	</div>
</div>
<div class="card">
	<div class="card-body" style="padding-top: 30px;">
		<form action="" method="get" class="row">
			<div class="col-3">
				<select name="filter[active]" class="form-control">
					<option value="0" <?=set_select2('filter[active]', 0, true)?>>Все</option>
					<option value="1" <?=set_select2('filter[active]', 1)?>>Активные</option>
					<option value="-1" <?=set_select2('filter[active]', -1)?>>Завершенные</option>
				</select>
			</div>
			<div class="col-4">
				<button class="btn btn-primary">Применить</button>
				<a href="./" class="btn btn-secondary">Сбросить</a>
			</div>
		</form>
		<div class="row">
			<div class="col-12">
				<table class="table table-striped">
					<thead>
						<tr>
							<th width="100">Id</th>
							<th>Курс</th>
							<th>Тема</th>
							<th>Дата</th>
							<th class="text-right">Статус</th>
							<th class="text-right">Действие</th>
						</tr>
					</thead>
					<tbody>
						<?if(is_array($items)):?>
							<?foreach($items as $item):?>
								<tr>
									<td><?=$item['id']?></td>
									<td>
										<?=$item['course_name']?> (<?=date(DATE_FORMAT_SHORT, strtotime($item['course_ts']))?> - <?=date(DATE_FORMAT_SHORT, strtotime($item['course_ts_end']))?>)
									</td>
									<td>
										<a class="text-primary" href="./<?=$item['id']?>/"><?=$item['name']?></a>
									</td>
									<td><?=date(DATE_FORMAT_FULL, strtotime($item['ts']))?></td>
									<td class="text-right">
										<?if($item['status'] == 0):?>
											<span class="badge badge-success">В процессе</span>
										<?elseif($item['status'] == -1):?>
											<span class="badge badge-danger">Завершено</span>
										<?elseif($item['status'] == 2):?>
											<span class="badge badge-info">Сегодня</span>
										<?else:?>
											<span class="badge badge-warning">Скоро</span>
										<?endif;?>
									</td>
									<td class="text-right">
										<a href="./edit/<?=$item['id']?>/" class="d-inline" title="Редактировать">
											<span class="btn btn-xs btn-primary mdi mdi-pencil"></span>
										</a>
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
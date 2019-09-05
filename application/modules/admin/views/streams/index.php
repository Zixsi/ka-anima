<div class="row mb-4">
	<div class="col-6">
		<h3>Онлайн встречи</h3>
	</div>
	<div class="col-6 text-right">
		
	</div>
</div>
<?//debug($items); die();?>
<div class="card">
	<div class="card-body">
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
		<table class="table">
			<thead>
				<tr>
					<th>Курс</th>
					<th>Тема</th>
					<th>Дата</th>
					<th>Статус</th>
					<th>Автор</th>
					<th class="text-right">Действие</th>
				</tr>
			</thead>
			<tbody>
				<?if(is_array($items)):?>
					<?foreach($items as $val):?>
						<tr>
							<td><?=$val['course_name']?></td>
							<td>
								<a href="./item/<?=$val['id']?>/"><?=$val['name']?></a>
							</td>
							<td><?=date(DATE_FORMAT_FULL, $val['ts_timestamp'])?></td>
							<td>
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
							<td>
								<a href="/admin/users/user/<?=$val['author']?>/"><?=$val['author_name']?></a>
							</td>
							<td class="text-right">
								<a href="./item/<?=$val['id']?>/" class="btn btn-xs btn-primary d-inline-block" title="Просмотр">
									<i class="mdi mdi-eye"></i>
								</a>
								<a href="./edit/<?=$val['id']?>/" class="btn btn-xs btn-primary d-inline-block" title="Редактировать">
									<i class="mdi mdi-pencil"></i>
								</a>
							</td>
						</tr>
					<?endforeach;?>
				<?else:?>
					<tr>
						<td colspan="5">Список встреч пуст</td>
					</tr>
				<?endif;?>
			</tbody>
		</table>
	</div>
</div>
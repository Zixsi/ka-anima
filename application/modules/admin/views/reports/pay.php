<div class="row mb-4">
	<div class="col-6">
		<h3>Отчет об оплате</h3>
	</div>
</div>

<div class="card">
	<div class="card-body">
		<form action="" method="get" class="row">
			<div class="form-group col-4 col-md-3">
				<input type="text" name="date" value="<?=$date?>" class="form-control form-control-sm datepiker-db">
			</div>
			<div class="col-4 col-md-3">
				<button type="submit" class="btn btn-outline-primary">Применить</button>
			</div>
		</form>

		<table class="table">
			<thead>
				<tr>
					<th>Дата</th>
					<th>Пользователь</th>
					<th>Описание</th>
					<th class="text-right">Сумма</th>
				</tr>
			</thead>
			<tbody>
				<?if($items):?>
					<?foreach($items as $row):?>
						<tr>
							<td><?=$row['ts']?></td>
							<td>
								<?if(array_key_exists($row['user'], $users)):?>
									<a href="/admin/users/user/<?=$row['user']?>/">
										<span><?=($users[$row['user']]['full_name'] ?? '- - -')?></span>
									</a>
								<?else:?>
									<span>- - -</span>
								<?endif;?>
							</td>
							<td><?=$row['description']?></td>
							<td class="text-right"><?=$row['amount']?></td>
						</tr>
					<?endforeach;?>
				<?endif;?>
			</tbody>
		</table>

	</div>
</div>
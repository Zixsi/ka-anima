<div class="row mb-4">
	<div class="col-6">
		<h3>Мастерская - <?=htmlspecialchars($item['title'])?></h3>
	</div>
	<div class="col-6 text-right">
		<a href="../../" class="btn btn-secondary">Назад</a>
	</div>
</div>

<div class="row">
	<div class="col-12 col-lg-6">
		<div class="card">
			<div class="card-body">
				<h4>Описание</h4>
				<?if(empty($item['img']) === false):?>
					<img src="/<?=$item['img']?>" width="300" class="img-fluid img-thumbnail float-left mr-3 mb-2">
				<?endif;?>
				<p><?=htmlspecialchars($item['description'])?></p>
			</div>
		</div>
	</div>
	<div class="col-12 col-lg-6">
		<div class="card">
			<div class="card-body">
				<h4>Подписки</h4>
				<table class="table table-sm">
					<thead>
						<tr>
							<th>Пользователь</th>
							<th>Дата подписки</th>
							<th class="text-right">Действие</th>
						</tr>
					</thead>
					<tbody>
						<?if($subscriptionUsers):?>
							<?foreach($subscriptionUsers as $user):?>
								<tr>
									<td>
										<a href="/admin/users/user/<?=$user['id']?>/" class="text-primary" target="_blank"><?=$user['full_name']?></a>	
									</td>
									<td><?=$user['ts_start']?></td>
									<td class="text-right">
										<button type="button" class="btn btn-sm btn-danger btn-removeSubscription" data-id="<?=$user['sid']?>"><i class="fa fa-trash"></i></button>
									</td>
								</tr>
							<?endforeach;?>
						<?else:?>
							<tr>
								<td class="text-center" colspan="3">Список пуст</td>
							</tr>
						<?endif;?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

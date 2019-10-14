<div id="support-ticket-list">
	<h3 class="mb-4">Обращения</h3>
	<div class="card">
		<div class="card-body">
			<?if(count($items)):?>
				<table class="table table-striped">
					<thead>
						<tr>
							<th width="160">Дата</th>
							<th width="200">Пользователь</th>
							<th>Текст</th>
							<th>Сообщений</th>
							<th width="100" class="text-right">Статус</th>
						</tr>
					</thead>
					<tbody>
						<?foreach($items as $row):?>
							<tr>
								<td><?=$row['date']?></td>
								<td>
									<a href="/admin/users/user/<?=$row['user']?>/" target="_blank"><?=$row['user_info']['full_name']?></a>
								</td>
								<td>
									<a href="/admin/support/ticket/<?=$row['code']?>/"><?=character_limiter(htmlspecialchars(strip_tags($row['text'])), 200)?></a>
								</td>
								<td class="text-center"><?=($row['message_count'] ?? 0)?></td>
								<td class="text-right">
									<span class="badge badge-<?=$row['status_info']['class']?>"><?=$row['status_info']['text']?></span>
								</td>
							</tr>
						<?endforeach;?>
					</tbody>
				</table>
			<?else:?>
				<h4 class="text-center mb-0">Список обращений пуст</h4>
			<?endif;?>
		</div>
	</div>
</div>
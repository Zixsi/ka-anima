<div id="support-ticket-list">
	<h3 class="mb-4">Обращения</h3>
	<div class="card">
		<div class="card-body">
			<ul class="nav nav-tabs" role="tablist">
				<?foreach($statusList as $key => $row):?>
					<li class="nav-item">
						<a class="nav-link <?=($key === 0)?'active':''?>" id="menu-tab-<?=$row['code']?>" data-toggle="tab" href="#tab-content-<?=$row['code']?>" role="tab"><?=$row['text']?> (<?=count(($items[$row['code']] ?? []))?>)</a>
					</li>
				<?endforeach;?>
			</ul>
			<div class="tab-content" style="background-color: #fff;">
				<?foreach($statusList as $key => $status):?>
					<div class="tab-pane fade <?=($key === 0)?'active show':''?>" id="tab-content-<?=$status['code']?>" role="tabpanel">
						<?if(isset($items[$status['code']]) && count($items[$status['code']])):?>
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
									<?foreach($items[$status['code']] as $row):?>
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
				<?endforeach;?>
			</div>
		</div>
	</div>
</div>
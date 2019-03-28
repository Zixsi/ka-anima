<div class="panel">
	<div class="panel-heading">
		<div class="col-xs-6">
			<h3 class="panel-title">Пользователи</h3>
		</div>
	</div>
	<div class="panel-body" style="padding-top: 30px;">
		<div class="col-xs-12">
			<table class="table">
				<thead>
					<tr>
						<th width="70">#</th>
						<th>Имя</th>
						<th>E-mail</th>
						<th>Группа</th>
						<th>Дата регистрации</th>
						<th width="70" class="text-right">Действия</th>
					</tr>
				</thead>
				<tbody>
					<?if($items):?>
						<?foreach($items as $item):?>
							<tr>
								<td>
									<a href="./user/<?=$item['id']?>/"><img src="<?=$item['img']?>" alt=""></a>
								</td>
								<td>
									<a href="./user/<?=$item['id']?>/"><?=$item['full_name']?></a>
								</td>
								<td>
									<a href="./user/<?=$item['id']?>/"><?=$item['email']?></a>
								</td>
								<td>
									<span class="label label-info"><?=$item['role_name']?></span>
								</td>
								<td><?=$item['ts_created']?></td>
								<td class="text-right">
									<div class="btn-group">
										<button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown">
											<i class="fas fa-ellipsis-v"></i>
										</button>
										<ul class="dropdown-menu" role="menu" style="right: 0; left: auto;">
											<li><a href="./user/<?=$item['id']?>/">Просмотр</a></li>
											<li class="divider"></li>
											<li><a href="javascript:void(0);">Заблокировать</a></li>
											<li><a href="javascript:void(0);">Удалить</a></li>
										</ul>
									</div>
								</td>
							</tr>
						<?endforeach;?>
					<?endif;?>
				</tbody>
			</table>
		</div>
	</div>
</div>
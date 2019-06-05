<h3 style="margin-bottom: 30px;">Пользователи</h3>
<div class="row">
	<div class="col-md-3">
		<div class="panel">
			<div class="panel-heading"></div>
			<div class="panel-body">
				<form action="" method="get" id="filter-user-form">
					<p class="lead">Роли</p>
					<ul class="list-group list-group-filter">
						<li class="list-group-item">
							<input type="radio" name="role" id="from-filter-role1" <?=(!isset($filter['role']) || ($filter['role'] ?? '') === 'all')?'checked':''?> value="all">
							<label for="from-filter-role1">
								<span>Все</span>
								<!--<span class="label label-primary label-pill pull-right"><?=$cnt_roles['all']?></span>-->
							</label>
						</li>
						<li class="list-group-item">
							<input type="radio" name="role" id="from-filter-role2" <?=(($filter['role'] ?? '') === '5')?'checked':''?> value="5">
							<label for="from-filter-role2">
								<span>Администраторы</span>
								<!--<span class="label label-primary label-pill pull-right"><?=$cnt_roles[5]?></span>-->
							</label>
						</li>
						<li class="list-group-item">
							<input type="radio" name="role" id="from-filter-role3" <?=(($filter['role'] ?? '') === '1')?'checked':''?> value="1">
							<label for="from-filter-role3">
								<span>Преподаватели</span>
								<!--<span class="label label-primary label-pill pull-right"><?=$cnt_roles[1]?></span>-->
							</label>
						</li>
						<li class="list-group-item">
							<input type="radio" name="role" id="from-filter-role4" <?=(($filter['role'] ?? '') === '0')?'checked':''?> value="0">
							<label for="from-filter-role4">
								<span>Ученики</span>
								<!--<span class="label label-primary label-pill pull-right"><?=$cnt_roles[0]?></span>-->
							</label>
						</li>
					</ul>
					<p class="lead">Группы</p>
					<ul class="list-group list-group-filter">
						<li class="list-group-item">
							<input type="radio" name="group" id="from-filter-group1" <?=(!isset($filter['group']) || ($filter['group'] ?? '') === 'all')?'checked':''?> checked value="all">
							<label for="from-filter-group1">
								<span>Все</span>
								<!--<span class="label label-primary label-pill pull-right">0</span>-->
							</label>
						</li>
						<li class="list-group-item">
							<input type="radio" name="group" id="from-filter-group2" <?=(($filter['group'] ?? '') === 'active')?'checked':''?> value="active">
							<label for="from-filter-group2">
								<span>Активные</span>
								<!--<span class="label label-primary label-pill pull-right">0</span>-->
							</label>
						</li>
						<li class="list-group-item">
							<input type="radio" name="group" id="from-filter-group3" <?=(($filter['group'] ?? '') === 'blocked')?'checked':''?> value="blocked">
							<label for="from-filter-group3">
								<span>Заблокированные</span>
								<!--<span class="label label-primary label-pill pull-right">0</span>-->
							</label>
						</li>
						<li class="list-group-item">
							<input type="radio" name="group" id="from-filter-group4" <?=(($filter['group'] ?? '') === 'deleted')?'checked':''?> value="deleted">
							<label for="from-filter-group4">
								<span>Удаленные</span>
								<!--<span class="label label-primary label-pill pull-right">0</span>-->
							</label>
						</li>
					</ul>
				</form>
				<button type="button" class="btn btn-md btn-block btn-primary" data-toggle="modal" data-target="#add-user-modal">Добавить пользователя</button>
			</div>
		</div>
	</div>
	<div class="col-md-9">
		<div class="panel">
			<div class="panel-heading"></div>
			<div class="panel-body">
				<div class="col-md-12">
					<table class="table">
						<thead>
							<tr>
								<th width="70">#</th>
								<th width="70"></th>
								<th>Имя</th>
								<th>E-mail</th>
								<th>Роль</th>
								<th>Дата регистрации</th>
								<th width="70" class="text-right">Действия</th>
							</tr>
						</thead>
						<tbody>
							<?if($items):?>
								<?foreach($items as $item):?>
									<tr>
										<td>
											<?if($item['deleted'] == 1):?>
												<i class="fas fa-trash-alt"></i>
											<?endif;?>
											<?if($item['blocked'] == 1):?>
												<i class="fas fa-lock"></i>
											<?endif;?>
										</td>
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
											<span class="label label-primary"><?=$item['role_name']?></span>
										</td>
										<td><?=$item['ts_created']?></td>
										<td class="text-right">
											<div class="btn-group">
												<button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown">
													<i class="fas fa-ellipsis-v"></i>
												</button>
												<ul class="dropdown-menu" role="menu" style="right: 0; left: auto;">
													<li><a href="./user/<?=$item['id']?>/">Просмотр</a></li>
													<?if($item['role'] != '5'):?>
														<li class="divider"></li>
														<?if($item['blocked'] == 0):?>
															<li><a href="javascript:void(0);" class="btn-user-block" data-id="<?=$item['id']?>">Блокировать</a></li>
														<?else:?>
															<li><a href="javascript:void(0);" class="btn-user-unblock" data-id="<?=$item['id']?>">Разблокировать</a></li>
														<?endif;?>
														<?if($item['deleted'] == 0):?>
															<li><a href="javascript:void(0);" class="btn-user-remove" data-id="<?=$item['id']?>">Удалить</a></li>
														<?endif;?>
													<?endif;?>
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
	</div>
</div>

<div class="modal fade" id="add-user-modal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Новая пользователь</h4>
			</div>
			<div class="modal-body">
				<form action="" method="post">
					<div class="form-group">
						<label>Роль</label>
						<select name="role" class="form-control">
							<option value="0" <?=($item['role'] == 0)?'selected="true"':''?>>ученик</option>
							<option value="1" <?=($item['role'] == 1)?'selected="true"':''?>>преподаватель</option>
						</select>
					</div>
					<div class="form-group">
						<label>E-mail</label>
						<input type="text" name="email" class="form-control" value="">
					</div>
					<div class="form-group">
						<label>Имя</label>
						<input type="text" name="name" class="form-control" value="">
					</div>
					<div class="form-group">
						<label>Фамилия</label>
						<input type="text" name="lastname" class="form-control" value="">
					</div>
					<div class="form-group">
						<label>Пароль</label>
						<input type="password" name="password" class="form-control" value="">
					</div>
					<div class="form-group">
						<label>Повторить пароль</label>
						<input type="password" name="re_password" class="form-control" value="">
					</div>
					<div class="form-group text-right">
						<button type="submit" class="btn btn-primary">Сохранить</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="remove-user-modal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			</div>
			<div class="modal-body">
				<form action="" method="post">
					<input type="hidden" name="id" value="0">
					<h4 class="text-center">Вы действительно хотите удалить этого пользователя?</h4>
					<div class="form-group text-center" style="padding-top: 20px;">
						<button type="submit" class="btn btn-danger" style="margin-right: 10px;">Удалить</button>
						<button type="button" class="btn btn-default" style="margin-left: 10px;" data-dismiss="modal">Отмена</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="block-user-modal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			</div>
			<div class="modal-body">
				<form action="" method="post">
					<input type="hidden" name="id" value="0">
					<h4 class="text-center">Вы действительно хотите заблокировать этого пользователя?</h4>
					<div class="form-group text-center" style="padding-top: 20px;">
						<button type="submit" class="btn btn-danger" style="margin-right: 10px;">Подтвердить</button>
						<button type="button" class="btn btn-default" style="margin-left: 10px;" data-dismiss="modal">Отмена</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
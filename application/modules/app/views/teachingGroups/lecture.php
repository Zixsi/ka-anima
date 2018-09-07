<div class="panel">
	<div class="panel-heading">
		<div class="col-xs-6">
			<h3 class="panel-title">Лекция группы</h3>
		</div>
	</div>
	<div class="panel-body" style="padding-top: 30px;">
		<div class="row">
			<div class="col-xs-12">

				<div class="panel">
					<div class="panel-body">
						<h4 class="panel-title" style="margin-bottom: 25px;">Добавить ревью</h4>
						<?=ShowFlashMessage();?>
						<form action="" method="post" enctype="multipart/form-data">
							<input type="hidden" name="<?=$csrf['key']?>" value="<?=$csrf['value']?>">
							<div class="form-group">
								<input type="text" class="form-control" name="url" placeholder="Url">
							</div>
							<div class="form-group">
								<button type="submit" class="btn btn-xs btn-primary">Добавить</button>
							</div>
						</form>
					</div>
				</div>

				<div class="panel">
					<div class="panel-body">
						<h4 class="panel-title" style="margin-bottom: 25px;">Домашние задания</h4>
						<table class="table table-bordered">
							<thead>
								<tr>
									<th>Дата</th>
									<th>Пользователь</th>
									<th>Название</th>
									<th>Комментарий</th>
									<th class="text-right">Действие</th>
								</tr>
							</thead>
							<?if($homework):?>
								<?foreach($homework as $val):?>
									<tr>
										<td><?=$val['ts']?></td>
										<td><?=$val['user_name']?></td>
										<td><?=$val['name']?></td>
										<td><?=$val['comment']?></td>
										<td class="text-right">
											<?if($val['type'] == 0):?>
												<a href="/file/download/<?=$val['file']?>" target="_blank" class="btn btn-3xs btn-primary">Скачать</a>
											<?endif;?>
										</td>
									</tr>
								<?endforeach;?>
							<?endif;?>
						</table>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>
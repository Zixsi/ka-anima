<div class="panel">
	<div class="panel-heading">
		<div class="col-xs-6">
			<h3 class="panel-title">Онлайн встречи</h3>
		</div>
		<div class="col-xs-6 text-right">
			<a href="./add/" class="btn btn-md btn-primary">Создать</a>
		</div>
	</div>
	<div class="panel-body" style="padding-top: 30px;">
		<table class="table table-striped">
			<thead>
				<tr>
					<th width="100">Id</th>
					<th>Курс</th>
					<th>Тема</th>
					<th>Дата</th>
					<th class="text-right">Статус</th>
				</tr>
			</thead>
			<tbody>
				<?if(is_array($items)):?>
					<?foreach($items as $item):?>
						<tr>
							<td><?=$item['id']?></td>
							<td>
								<?=$item['course_name']?> (<?=strftime("%B %Y", strtotime($item['course_ts']))?>)
							</td>
							<td>
								<a href="./<?=$item['group_id']?>/"><?=$item['name']?></a>
							</td>
							<td><?=$item['ts']?></td>
							<td class="text-right">
								<?if($item['status'] == 0):?>
									<span class="label label-success">В процессе</span>
								<?elseif($item['status'] == -1):?>
									<span class="label label-danger">Завершено</span>
								<?else:?>
									<span class="label label-warning">Скоро</span>
								<?endif;?>
							</td>
						</tr>
					<?endforeach;?>
				<?endif;?>
			</tbody>
		</table>
	</div>
</div>
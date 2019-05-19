<div class="panel">
	<div class="panel-heading">
		<h3 class="panel-title">Онлайн встречи</h3>
	</div>
	<div class="panel-body">
		<table class="table">
			<thead>
				<tr>
					<th>Курс</th>
					<th>Тема</th>
					<th>Дата</th>
					<th>Статус</th>
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
							<td><?=date('d.m.Y H:i:00', strtotime($val['ts']))?></td>
							<td>
								<?if($val['status'] == 0):?>
									<span class="label label-success">В процессе</span>
								<?elseif($val['status'] == -1):?>
									<span class="label label-danger">Завершено</span>
								<?elseif($val['status'] == 2):?>
									<span class="label label-info">Сегодня</span>
								<?else:?>
									<span class="label label-warning">Скоро</span>
								<?endif;?>
							</td>
							<td class="text-right">
								<a href="./edit/<?=$val['id']?>/" class="btn btn-xxs btn-default lnr lnr-pencil" title="Редактировать"></a>	
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
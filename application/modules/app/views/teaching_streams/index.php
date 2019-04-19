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
		
		<form action="" method="get" class="row">
			<div class="col-xs-3">
				<select name="filter[active]" class="form-control">
					<option value="0" <?=set_select2('filter[active]', 0, true)?>>Все</option>
					<option value="1" <?=set_select2('filter[active]', 1)?>>Активные</option>
					<option value="-1" <?=set_select2('filter[active]', -1)?>>Завершенные</option>
				</select>
			</div>
			<div class="col-xs-4">
				<button class="btn btn-primary btn-md">Применить</button>
				<a href="./" class="btn btn-default btn-md">Сбросить</a>
			</div>
		</from>

		<table class="table table-striped">
			<thead>
				<tr>
					<th width="100">Id</th>
					<th>Курс</th>
					<th>Тема</th>
					<th>Дата</th>
					<th class="text-right">Статус</th>
					<th class="text-right">Действие</th>
				</tr>
			</thead>
			<tbody>
				<?if(is_array($items)):?>
					<?foreach($items as $item):?>
						<tr>
							<td><?=$item['id']?></td>
							<td>
								<?=$item['course_name']?> (<?=date('d.m.Y', strtotime($item['course_ts']))?> - <?=date('d.m.Y', strtotime($item['course_ts_end']))?>)
							</td>
							<td>
								<a href="./<?=$item['id']?>/"><?=$item['name']?></a>
							</td>
							<td><?=date('d-m-Y H:i:s', strtotime($item['ts']))?></td>
							<td class="text-right">
								<?if($item['status'] == 0):?>
									<span class="label label-success">В процессе</span>
								<?elseif($item['status'] == -1):?>
									<span class="label label-danger">Завершено</span>
								<?elseif($item['status'] == 2):?>
									<span class="label label-info">Сегодня</span>
								<?else:?>
									<span class="label label-warning">Скоро</span>
								<?endif;?>
							</td>
							<td class="text-right">
								<?//if(in_array($item['status'], [1, 2])):?>
									<a href="./edit/<?=$item['id']?>/" class="btn btn-xxs btn-default lnr lnr-pencil" title="Редактировать"></a>
								<?//endif;?>
							</td>
						</tr>
					<?endforeach;?>
				<?endif;?>
			</tbody>
		</table>
	</div>
</div>
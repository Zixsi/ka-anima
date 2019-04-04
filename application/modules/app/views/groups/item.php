<div class="row">
	<div class="col-xs-6">
		<div class="panel">
			<div class="panel-heading">
				<h3 class="panel-title">Группа <?=$item['name']?> (<?=$item['code']?>)</h3>
			</div>
			<div class="panel-body">
				<table class="table">
					<thead>
						<tr>
							<th>Ученик</th>
							<th class="text-center">Выполеннные задания</th>
							<th class="text-center">Непроверенные работы</th>
						</tr>
					</thead>
					<tbody>
						<?foreach($users as $val):?>
							<tr>
								<td><?=$val['full_name']?></td>
								<td class="text-center">0 / 12</td>
								<td class="text-center">5</td>
							</tr>
						<?endforeach;?>
					</tbody>
				</table>
			</div>
		</div>

		<div class="panel">
			<div class="panel-heading">
				<h3 class="panel-title">Иванов Иван</h3>
			</div>
			<div class="panel-body">
				<div id="group-user-lectures">
					<div class="card">
						<div class="head">
							<div class="title">1 урок</div>
						</div>
						<div class="body">
							<span class="fa fa-times btn-remove"></span>
							<div class="title">Отчет загружен</div>
							<p>Ученику будет доступен отчет в личном кабинете</p>
						</div>
					</div>
					<div class="card">
						<div class="head">
							<div class="title">2 урок</div>
						</div>
						<div class="body">
							<span class="fa fa-times btn-remove"></span>
							<div class="title">Отчет загружен</div>
							<p>Ученику будет доступен отчет в личном кабинете</p>
						</div>
					</div>
					<div class="card">
						<div class="head">
							<div class="title">3 урок</div>
						</div>
						<div class="body">
							<div class="title">Ученик выполнил задание</div>
							<div class="actions">
								<button type="button" class="btn btn-primary btn-block">Скачать ДЗ</button>
								<button type="button" class="btn btn-primary btn-block">Добавить отчет</button>
							</div>
						</div>
					</div>
						<div class="card">
						<div class="head">
							<div class="title">4 урок</div>
						</div>
						<div class="body">
							<div class="title">Ученик выполнил задание</div>
							<div class="actions">
								<button type="button" class="btn btn-primary btn-block">Скачать ДЗ</button>
								<button type="button" class="btn btn-primary btn-block">Добавить отчет</button>
							</div>
						</div>
					</div>
					<div class="card">
						<div class="head">
							<div class="title">5 урок</div>
						</div>
						<div class="body">
							<div class="title empty">ДЗ не загружено</div>
						</div>
					</div>
					<div class="card">
						<div class="head">
							<div class="title">6 урок</div>
						</div>
						<div class="body">
							<div class="title empty">ДЗ не загружено</div>
						</div>
					</div>
					<div class="card">
						<div class="head">
							<div class="title">7 урок</div>
						</div>
						<div class="body">
							<div class="title empty">ДЗ не загружено</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-xs-6">
		<div class="panel">
			<div class="panel-heading">
				<h3 class="panel-title">Стена</h3>
			</div>
			<div class="panel-body">
				
			</div>
		</div>
	</div>
</div>
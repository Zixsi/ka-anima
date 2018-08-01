<div class="row">
	<div class="col-xs-6">
		<div class="panel">
			<div class="panel-body">
				<h4>Курсы</h4>
				<table class="table table-courses">
					<?$i = 0;?>
					<?foreach($courses as $course):?>
						<tr>
							<td class="row-course <?=(($i++) == 0)?'active':''?>" data-id="<?=$course['id']?>"><?=$course['name']?> (<?=$course['ts_f']?>)</td>
						</tr>
					<?endforeach;?>
				</table>
			</div>
		</div>
		<div class="panel">
			<div class="panel-body">
				<h4>Лекции</h4>
				<table class="table lectures-block">
					<?foreach($course_lectures as $lecture):?>
						<tr>
							<td>
								<span class="lnr lnr-eye"></span>
							</td>
							<td>
								<div class="row">
									<div class="col-xs-8">
										<?=$lecture['name']?>
									</div>
									<div class="col-xs-4 text-right">
										<span class="lnr lnr-sync"></span>
									</div>
								</div>
								<div class="progress progress-xs">
									<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 80%"></div>
								</div>
							</td>
						</tr>
					<?endforeach;?>
				</table>
			</div>
		</div>
	</div>
	<div class="col-xs-6">
		<div class="panel">
			<div class="panel-body">
				<h4>Лекция</h4>
				<div id="lectures-video" style="background-color: #333; height: 360px; width: 100%;">

				</div>
			</div>
		</div>
		<div class="panel">
			<div class="panel-body">
				<h4>Задание</h4>
				<p>Текст задания</p>
			</div>
		</div>
	</div>
</div>
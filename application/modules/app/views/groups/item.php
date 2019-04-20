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
							<tr <?=($user && $val['id'] == $user['id'])?'class="info"':''?>>
								<td><a href="./?user=<?=$val['id']?>"><?=$val['full_name']?></a></td>
								<td class="text-center"><?=$val['reviews']?> / <?=$item['cnt']?></td>
								<td class="text-center"><?=($val['homeworks'] - $val['reviews'])?></td>
							</tr>
						<?endforeach;?>
					</tbody>
				</table>
			</div>
		</div>
		<div class="panel">
			<?if($user):?>
				<div class="panel-heading">
					<h3 class="panel-title"><?=$user['full_name']?></h3>
				</div>
				<div class="panel-body">
					<div id="group-user-lectures">				
						<?if($homeworks):?>
							<?$i = 1;?>
							<?foreach($homeworks as $val):?>
								<div class="card">
									<div class="head">
										<div class="title"><?=$i?> урок</div>
									</div>
									<div class="body">
										<?if($val['homework'] == false):?>
											<div class="title empty">ДЗ не загружено</div>
										<?elseif($val['review'] == false):?>
											<div class="title">Ученик выполнил задание</div>
											<div class="actions">
												<a href="./?user=<?=$user['id']?>&lecture=<?=$val['lecture_id']?>&action=download&target=homework" class="btn btn-primary btn-block" target="_blank">Скачать ДЗ</a>
												<button type="button" class="btn btn-primary btn-block btn-add-review" data-toggle="modal" data-group="<?=$item['id']?>" data-lecture="<?=$val['lecture_id']?>" data-user="<?=$user['id']?>">Добавить отчет</button>
											</div>
										<?else:?>
											<span class="fas fa-times bnt-remove-review" data-id="<?=$val['review']?>"></span>
											<div class="title">Отчет загружен</div>
											<p>Ученику будет доступен отчет в личном кабинете</p>
										<?endif;?>
									</div>
								</div>
								<?$i++;?>
							<?endforeach;?>
						<?endif;?>
					</div>
				</div>
			<?else:?>
				<h3 class="text-center">Выберите ученика</h3>
			<?endif;?>
		</div>
	</div>
	<div class="col-xs-6">
		<?=$this->wall->show($item['id'])?>
	</div>
</div>

<div class="modal fade" id="add-review-modal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Ревью</h4>
			</div>
			<div class="modal-body">
				<form action="" method="post">
					<input type="hidden" name="group" value="0">
					<input type="hidden" name="user" value="0">
					<input type="hidden" name="lecture" value="0">
					<div class="form-group">
						<label>Ссылка на видео</label>
						<input type="text" name="video_url" value="" class="form-control">
					</div>
					<div class="form-group">
						<label>Ссылка на файл</label>
						<input type="text" name="file_url" value="" class="form-control">
					</div>
					<div class="form-group text-right">
						<button type="submit" class="btn btn-primary">Сохранить</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
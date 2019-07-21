<div class="row">
	<div class="col-6">
		<div class="card mb-4">
			<div class="card-body">
				<h3 class="card-title">Группа <?=$item['name']?> (<?=$item['code']?>)</h3>
				<table class="table">
					<thead>
						<tr>
							<th>Ученик</th>
							<th class="text-center">Выполеннные задания</th>
							<th class="text-center">Непроверенные работы</th>
						</tr>
					</thead>
					<tbody>
						<?if($users):?>
							<?foreach($users as $val):?>
								<tr <?=($user && $val['id'] == $user['id'])?'class="info"':''?>>
									<td><a href="./?user=<?=$val['id']?>"><?=$val['full_name']?></a></td>
									<td class="text-center"><?=$val['reviews']?> / <?=$item['cnt']?></td>
									<td class="text-center"><?=($val['homeworks'] - $val['reviews'])?></td>
								</tr>
							<?endforeach;?>
						<?endif;?>
					</tbody>
				</table>
			</div>
		</div>
		<div class="card">
			<div class="card-body">
				<?if($user):?>
					<h3 class="card-title"><?=$user['full_name']?></h3>
					<div id="group-user-lectures">				
						<?if($homeworks):?>
							<?$i = 1;?>
							<?foreach($homeworks as $val):?>
								<div class="card">
									<div class="card-header">
										<div class="card-title"><?=$i?> урок</div>
									</div>
									<div class="card-body">
										<?if($val['homework'] == false):?>
											<div class="title empty">ДЗ не загружено</div>
										<?elseif($val['review'] == false):?>
											<div class="title">Ученик выполнил задание</div>
											<div class="actions">
												<a href="./?user=<?=$user['id']?>&lecture=<?=$val['lecture_id']?>&action=download&target=homework" class="btn btn-primary btn-block" target="_blank">Скачать ДЗ</a>
												<button type="button" class="btn btn-primary btn-block btn-add-review" data-toggle="modal" data-group="<?=$item['id']?>" data-lecture="<?=$val['lecture_id']?>" data-user="<?=$user['id']?>">Добавить отчет</button>
											</div>
										<?else:?>
											<span class="fa fa-times bnt-remove-review" data-id="<?=$val['review']?>"></span>
											<div class="title">Отчет загружен</div>
											<p>Ученику будет доступен отчет в личном кабинете</p>
										<?endif;?>
									</div>
								</div>
								<?$i++;?>
							<?endforeach;?>
						<?endif;?>
					</div>
				<?else:?>
					<h3 class="text-center">Выберите ученика</h3>
				<?endif;?>
			</div>
		</div>
	</div>
	<div class="col-6">
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
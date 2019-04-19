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
		<div id="wall-component">
			<?$this->load->view('tpls/wall');?>
			<div class="panel panel-headline wall-panel">
				<div class="panel-body">
					<form action="" method="post" class="form wall-from" id="wall-main-form">
						<input type="hidden" name="group" value="<?=$item['id']?>" data-const="true">
						<div class="wall-from--input-wrap">
							<textarea name="text" class="wall-from--text-input" placeholder="Напишите сообщение..."></textarea>
							<button type="submit" class="btn wall-from--btn-send">
								<i class="fab fa-telegram-plane"></i>
							</button>
						</div>
					</form>
				</div>
			</div>

			<div class="media-list" id="wall-main-list">
				<?foreach($wall as $val):?>
					<div class="panel panel-headline wall-panel" id="wall-panel-id<?=$val['id']?>">
						<div class="panel-body">
							<div class="media">
								<a class="pull-left" href="/profile/<?=$val['user']?>/" target="_blank">
									<img class="media-object" src="<?=$val['img']?>" alt="<?=$val['full_name']?>">
								</a>
								<div class="media-body">
									<h4 class="media-heading">
										<a href="/profile/<?=$val['user']?>/" target="_blank"><?=$val['full_name']?></a>
										<?if($val['role'] > 0):?>
											<small><span class="label label-success"><?=$val['role_name']?></span></small>
										<?endif;?>
										<br><small><?=$val['ts']?></small>
									</h4>
									<?=htmlspecialchars($val['text'])?>
									<hr>
									<div class="wall-item-tools">
										<button type="button" class="btn btn-comments" data-id="<?=$val['id']?>">
											<i class="far fa-comment-alt"></i> <span class="info-child-cnt"><?=($val['child_cnt'] ?? '')?></span>
										</button>
									</div>
									<div class="wall-wrap-childs">
										<hr>
										<div class="wall-childs" id="wall-childs-id<?=$val['id']?>"></div>
										<form action="" method="post" class="form wall-from wall-child-from">
											<input type="hidden" name="group" value="<?=$item['id']?>" data-const="true">
											<input type="hidden" name="target" value="<?=$val['id']?>" data-const="true">
											<div class="wall-from--input-wrap">
												<textarea name="text" class="wall-from--text-input" placeholder="Напишите сообщение..."></textarea>
												<button type="submit" class="btn wall-from--btn-send">
													<i class="fab fa-telegram-plane"></i>
												</button>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?endforeach;?>
			</div>
		</div>
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
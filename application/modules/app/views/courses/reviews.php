<div class="row">

	<h3 class="text-center" style="margin-bottom: 30px;"><?=$group['name']?></h3>

	<div class="col-xs-12 text-center" style="margin-bottom: 30px;">
		<a href="/courses/<?=$group_id?>/" class="btn btn-default">Лекции</a>
		<a href="/courses/<?=$group_id?>/group/" class="btn btn-default">Группа</a>
		<a href="/courses/<?=$group_id?>/review/" class="btn btn-primary">Ревью работ</a>
		<a href="/courses/<?=$group_id?>/stream/" class="btn btn-default">Онлайн встречи</a>
	</div>

	<div class="col-xs-12">
		<div class="panel panel-headline">
			<div class="panel-body">
				<div class="row">
					<div class="col-xs-5">
						<?if($review_item):?>
							<div class="video-wrap">
								<iframe src="/video/review/<?=$review_item['id']?>" width="100%" height="100%" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
							</div>
							<div class="video-info">
								<table class="table table-no-bordered">
									<tr>
										<td><b>Лекция:</b></td>
										<td><?=$review_item['lecture_name']?></td>
									</tr>

									<?if(intval($review_item['user']) !== 0):?>
										<tr>
											<td><b>Пользователь:</b></td>
											<td>
												<span><?=$review_item['user_name']?></span>
											</td>
										</tr>
									<?endif;?>

									<?if(!empty($review_item['text'])):?>
										<tr>
											<td><b>Рекомендации:</b></td>
											<td><?=$review_item['text']?></td>
										</tr>
									<?endif;?>
									<?if(!empty($review_item['files'])):?>
										<tr>
											<td><b>Файлы:</b></td>
											<td>- - -</td>
										</tr>
									<?endif;?>
								</table>
							</div>
						<?else:?>
							<div class="video-wrap" style="background-color: #ccc;">
								<div style="color: #fff; text-align: center; text-transform: uppercase; font-size: 40px; padding-top: 24%;">Тут могла быть ваша реклама</div>
							</div>
						<?endif;?>
					</div>
					<div class="col-xs-7">
						<form action="" method="get" class="row">
							<div class="form-group col-xs-4">
								<select class="form-control" name="filter[lecture]">
									<option value="0" <?=set_select2('filter[lecture]', '0', true)?>>-- All lectures --</option>
									<?if($lectures):?>
										<?foreach($lectures as $val):?>
											<option value="<?=$val['id']?>" <?=set_select2('filter[lecture]', $val['id'])?>><?=$val['name']?></option>
										<?endforeach;?>
									<?endif;?>
								</select>
							</div>
							<div class="form-group col-xs-4"">
								<select class="form-control" name="filter[user]">
									<option value="0" <?=set_select2('filter[user]', '0', true)?>>-- All users --</option>
									<?foreach($users as $val):?>
										<option value="<?=$val['id']?>" <?=set_select2('filter[user]', $val['id'])?>><?=$val['user_name']?></option>
									<?endforeach;?>
								</select>
							</div>
							<div class="form-group col-xs-4"">
								<button type="submit" class="btn btn-md btn-primary">Фильтровать</button>
							</div>
						</form>
						<div class="row">
							<?if($items):?>
								<?foreach($items as $item):?>
									<div class="col-xs-3">
										<div class="thumbnail">
											<a href="/courses/1/review/<?=$item['id']?>/?<?=$filter_url?>"><img src="<?=$item['src']?>" width="100%"></a>
											<div class="caption">
												<h3>
													<a href="/courses/1/review/<?=$item['id']?>/?<?=$filter_url?>">Название видео</a>
												</h3>
											</div>
										</div>
									</div>
								<?endforeach;?>
							<?else:?>
								Empty list
							<?endif;?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
</div>
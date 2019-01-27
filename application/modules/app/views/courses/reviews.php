<div class="row">

	<h3 class="text-center" style="margin-bottom: 30px;"><?=$group['name']?></h3>
	<?$this->load->view('courses/menu');?>

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
										<td><b>Дата:</b></td>
										<td><?=date('d-m-Y', strtotime($review_item['ts']))?></td>
									</tr>
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
											<td>
												<?foreach($review_item['files'] as $val):?>
													<p><a href="/file/download/<?=$val['id']?>" target="_blank"><?=$val['name']?></a></p>
												<?endforeach;?>
											</td>
										</tr>
									<?endif;?>
								</table>
							</div>
						<?else:?>
							<div class="text-center" style="background-color: #f6f6f6;">
								<img src="<?=TEMPLATE_DIR?>/admin_1/assets/img/unicorn.jpg" width="60%" height="auto">
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
										<option value="<?=$val['id']?>" <?=set_select2('filter[user]', $val['id'])?>><?=$val['full_name']?></option>
									<?endforeach;?>
								</select>
							</div>
							<div class="form-group col-xs-4"">
								<button type="submit" class="btn btn-md btn-primary">Фильтровать</button>
							</div>
						</form>
						<div class="row group-reviews-list">
							<?if($items):?>
								<?foreach($items as $item):?>
									<div class="col-xs-3">
										<div class="thumbnail">
											<?if(is_array($not_viewed) && in_array($item['id'], $not_viewed)):?>
												<span class="badge bg-danger" style="position: absolute; top: -8px; right: 5px;">!</span>
											<?endif;?>
											<a href="/courses/1/review/<?=$item['id']?>/?<?=$filter_url?>"><img src="<?=$item['src']?>" width="100%"></a>
											<div class="caption">
												<span><?=date('d-m-Y', strtotime($item['ts']))?></span><br>
												<h4>
													<a href="/courses/1/review/<?=$item['id']?>/?<?=$filter_url?>">
														<?=($item['user'])?$item['user_name']:'Общий обзор'?> - <?=$item['name']?>
													</a>
												</h4>
											</div>
										</div>
									</div>
								<?endforeach;?>
							<?else:?>
								<span>Empty list</span>
							<?endif;?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
</div>
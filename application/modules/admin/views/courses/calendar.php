<div class="roadmap-component">
	<div class="roadmap-container">
		<div class="roadmap-header">
			<div class="roadmap-roulete">
				<?if($roadmap['head']):?>
					<?$i = 0;?>
					<?foreach($roadmap['head'] as $k_year => $v_year):?>
						<?foreach($v_year as $v_month):?>
							<div class="r-month">
								<div class="r-month-title"><?=$v_month?></div>
								<span class="r-week <?=(($i === 0)?'first':'')?>"></span>
								<span class="r-week"></span>
								<span class="r-week"></span>
								<span class="r-week last"></span>
							</div>
							<?$i++;?>
						<?endforeach;?>
					<?endforeach;?>
				<?endif;?>
			</div>
		</div>
		<div class="roadmap-body">
			<div class="current-week-mark" style="left: <?=$roadmap['week']['left']?>px"></div>
			<?if($items):?>
				<?$i = 0;?>
				<?foreach($items as $item):?>
					<div class="roadmap-item item-color<?=(++$i)?>">
						<div class="item-head colored">
							<span class="title"><?=$item['name']?></span>
							<span class="btn-menu btn-collapse" data-toggle="collapse" data-target="#items-row-wrap<?=$item['id']?>"><i class="fa fa-angle-up"></i></span>
							<div class="btn-group">
								<span class="btn-menu" data-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></span>
								<ul class="dropdown-menu" role="menu">
									<li><a href="./edit/<?=$item['id']?>/">Редактировать</a></li>
									<li><a href="./<?=$item['id']?>/lectures/">Лекции</a></li>
									<li><a href="javascript: void(0);" data-value="<?=$item['id']?>" class="btn-add-group">Добавить группу</a></li>
								</ul>
							</div>
						</div>
						<div class="items-row-wrap collapse in" id="items-row-wrap<?=$item['id']?>">
							<?if(!empty($roadmap['body'][$item['id']])):?>
								<?foreach($roadmap['body'][$item['id']] as $val):?>
									<div class="item-row">
										<?foreach($val as $v):?>
											<span class="roadmap-mark colored"  style="left: <?=$v['mark']['left']?>px; width: <?=$v['mark']['width']?>px;">
												<span class="icon colored"><i class="<?=$v['style']?>"></i></span>
												<span><?=$v['title']?></span>
												<div class="info">
													<ul>
														<li>Тип: <?=$v['type']?></li>
														<li>Дата: <?=$v['title']?></li>
														<li>Подписано: <?=$v['subscription_cnt']?></li>
														<li>Дней до начала: <?=$v['days']?></li>
													</ul>
												</div>
												<div class="btn-group">
													<span class="btn-menu" data-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></span>
													<ul class="dropdown-menu" role="menu">
														<li><a href="javascript: void(0);">Информация</a></li>
														<?if($v['subscription_cnt'] === 0):?>
															<li><a href="javascript: void(0);" class="btn-remove-group" data-id="<?=$v['id']?>">Удалить</a></li>
														<?endif;?>
													</ul>
												</div>
											</span>
										<?endforeach;?>
									</div>
								<?endforeach;?>
							<?else:?>
								<div class="item-row"></div>
							<?endif;?>
						</div>
						<div class="item-row row-collapse"></div>
					</div>
				<?endforeach;?>
			<?endif;?>
			<div class="roadmap-item item-color-default">
				<div class="item-head colored">
					<span><i class="fa fa-plus"></i></span>
					<a href="./add/"></a>
				</div>
				<div class="item-row"></div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="add-group-modal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Новая группа</h4>
			</div>
			<div class="modal-body">
				<form action="" method="post">
					<input type="hidden" name="course" value="0">
					<div class="form-group">
						<label>Дата начала</label>
						<input type="text" name="date" value="" class="form-control datepiker" id="datetimepickerr">
					</div>
					<div class="form-group">
						<label>Тип</label>
						<select name="type" class="form-control">
							<?foreach(GroupsModel::TYPE as $key => $val):?>
								<option value="<?=$key?>"><?=$val['title']?></option>
							<?endforeach;?>
						</select>
					</div>
					<div class="form-group text-right">
						<button type="submit" class="btn btn-primary">Сохранить</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="remove-group-modal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			</div>
			<div class="modal-body">
				<form action="" method="post">
					<input type="hidden" name="id" value="0">
					<h4 class="text-center">Вы действительно хотите выполнить это действие?</h4>
					<div class="form-group text-center" style="padding-top: 20px;">
						<button type="submit" class="btn btn-danger" style="margin-right: 10px;">Удалить</button>
						<button type="button" class="btn btn-default" style="margin-left: 10px;" data-dismiss="modal">Отмена</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
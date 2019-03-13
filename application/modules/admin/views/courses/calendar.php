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
			<?if($items):?>
				<?$i = 0;?>
				<?foreach($items as $item):?>
					<div class="roadmap-item item-color<?=(++$i)?>">
						<div class="item-head colored">
							<span><?=$item['name']?></span>
							<div class="tools">
								<div class="tools-wrap">
									<a href="./edit/<?=$item['id']?>/"><i class="fa fa-pencil"></i></a>
									<a href="./<?=$item['id']?>/lectures/"><i class="fa fa-list"></i></a>
									<a href="javascript:void(0);" data-value="<?=$item['id']?>" class="btn-add-group"><i class="fa fa-plus"></i></a>
								</div>
							</div>
						</div>
						<?if(!empty($roadmap['body'][$item['id']])):?>
							<?foreach($roadmap['body'][$item['id']] as $val):?>
								<div class="item-row">
									<?foreach($val as $v):?>
										<span class="roadmap-mark colored" style="left: <?=$v['mark']['left']?>px; width: <?=$v['mark']['width']?>px;"><?=$v['title']?></span>
									<?endforeach;?>
								</div>
							<?endforeach;?>
						<?else:?>
							<div class="item-row"></div>
						<?endif;?>
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
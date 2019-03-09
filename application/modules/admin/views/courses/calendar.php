<div class="roadmap-component">
	<div class="roadmap-container">
		<div class="roadmap-header">
			<div class="roadmap-roulete">
				<?if($roadmap_months):?>
					<?$i = 0;?>
					<?foreach($roadmap_months as $k_year => $v_year):?>
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
									<a href="javascript:void(0);" data-toggle="modal" data-target="#course-group-add"><i class="fa fa-plus"></i></a>
								</div>
							</div>
						</div>
						<!--<div class="item-row"></div>-->
					
						<div class="item-row">
							<span class="roadmap-mark colored" style="left: 50px;">15.12 - 14.03</span>
							<span class="roadmap-mark colored" style="left: 700px;">01.07 - 01.10</span>
						</div>
						<div class="item-row">
							<span class="roadmap-mark colored" style="left: 300px;">01.03 - 01.06</span>
							<span class="roadmap-mark colored" style="left: 900px;">01.09 - 31.11</span>
						</div>
					</div>
				<?endforeach;?>
			<?endif;?>
		</div>
	</div>
</div>

<div class="modal fade" id="course-group-add" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Название модали</h4>
			</div>
			<div class="modal-body">
				...
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary">Сохранить</button>
			</div>
		</div>
	</div>
</div>
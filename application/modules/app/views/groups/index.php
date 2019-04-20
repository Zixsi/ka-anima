<div class="row">
	<div class="col-xs-12">
		<div class="panel">
			<div class="panel-heading">
				<h3 class="panel-title">Группы</h3>
			</div>
			<div class="panel-body">
				<?if($items):?>
					<ul>
						<?foreach($items as $val):?>
							<li>
								<a href="/groups/<?=$val['code']?>/"><?=$val['name']?> <?=date('d.m.Y', $val['timestamp_start'])?> - <?=date('d.m.Y', $val['timestamp_end'])?></a> (<b><?=$val['subscr_cnt']?></b> подписано)
							</li>
						<?endforeach;?>
					</ul>
				<?endif;?>
			</div>
		</div>
	</div>
	<div class="col-xs-6">
		
	</div>
</div>
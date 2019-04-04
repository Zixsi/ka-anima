<?if($news):?>
	<?foreach($news as $val):?>
		<div class="panel">
			<div class="panel-heading clearfix">
				<h3 class="panel-title pull-left"><b><?=$val['title']?></b></h3>
				<div class="pull-right"><?=date('d.m.Y', strtotime($val['ts']))?></div>
			</div>
			<div class="panel-body">
				<p><?=$val['text']?></p>
			</div>
		</div>
	<?endforeach;?>
<?endif;?>
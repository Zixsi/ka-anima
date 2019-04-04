<?if($items):?>
	<?foreach($items as $val):?>
		<div class="panel">
			<div class="panel-heading">
				<h3 class="panel-title"><b><?=$val['question']?></b></h3>
			</div>
			<div class="panel-body">
				<p><?=$val['answer']?></p>
			</div>
		</div>
	<?endforeach;?>
<?endif;?>
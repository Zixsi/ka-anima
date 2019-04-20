<div class="row">

	<h3 class="text-center" style="margin-bottom: 30px;"><?=$group['name']?></h3>

	<div class="col-xs-12">
		<div class="panel panel-headline panel-color blue">
			<div class="panel-heading">
				<h3 class="panel-title">Лекции группы</h3>
			</div>
			<div class="panel-body">
				<table class="table table-striped">
					<thead>
						<tr>
							<th>Название</th>
							<th class="text-right">Дата начала</th>
						</tr>
					</thead>
					<tbody>
						<?if(is_array($items)):?>
							<?foreach($items as $item):?>
								<tr>
									<td>
										<?if($item['active']):?>
											<a href="./lecture/<?=$item['id']?>/"><?=$item['name']?></a>
										<?else:?>
											<span><?=$item['name']?></span>
										<?endif;?>
									</td>
									<td class="text-right">
										<?=($item['active'])?date('Y-m-d', strtotime($item['ts'])):'- - -'?>		
									</td>
								</tr>
							<?endforeach;?>
						<?endif;?>
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<div class="col-xs-6">
		<div class="panel panel-headline panel-color blue">
			<div class="panel-heading">
				<h3 class="panel-title">Стена</h3>
			</div>
			<div class="panel-body">
				<form action="" method="post" class="form">
					<div class="form-group">
						<label>Разместить сообщение</label>
						<textarea name="text" class="form-control" rows="5" placeholder="Поделитесь вашими мыслями..."></textarea>
					</div>
					<div class="form-group text-right">
						<button type="submit" class="btn btn-primary btn-xxs">Разместить</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="col-xs-6">
		<div class="panel panel-headline panel-color blue">
			<div class="panel-heading">
				<h3 class="panel-title">Студенты группы</h3>
			</div>
			<div class="panel-body">
				<?if($users):?>
					<?foreach($users as $val):?>
						<a href="/profile/<?=$val['id']?>/">
							<img src="<?=$val['img']?>" width="50" height="50" class="img-circle" title="<?=$val['full_name']?>">
						</a>
					<?endforeach;?>
				<?endif;?>
			</div>
		</div>

		<div class="panel panel-headline panel-color blue">
			<div class="panel-heading">
				<h3 class="panel-title">Загруженные задания</h3>
			</div>
			<div class="panel-body">
				<h4>НО ЗАЧЕМ ОНИ ТУТ ЕСЛИ ОНИ ЕСТЬ В ЛЕКЦИИ?</h4>
			</div>
		</div>

	</div>
	
</div>
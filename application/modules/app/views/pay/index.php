<?//debug($item); die();?>

<?alert_error($error);?>

<?if(($item ?? null)):?>
	<div class="card px-2">
		<div class="card-body">
			<div class="row">
				<div class="col-12">
					<div class="d-flex justify-content-between">
						<div class="col-lg-3 pl-0">
							<p class="mb-0 mt-5">Дата : <?=date(DATE_FORMAT_FULL);?></p>
						</div>
					</div>
					<div class="mt-5 d-flex justify-content-center w-100">
						<div class="table-responsive w-100">
							<table class="table">
								<thead>
									<tr class="bg-dark text-white">
											<th>#</th>
											<th>Описание</th>
											<th class="text-right">Кол-во</th>
											<th class="text-right">Сумма</th>
										</tr>
								</thead>
								<tbody>
									<?$i = 1;?>
									<?foreach($item['list'] as $val):?>
										<tr class="text-right">
											<td class="text-left"><?=$i?></td>
											<td class="text-left"><?=$val['description']?></td>
											<td>1</td>
											<td><?=number_format($val['price'], 2, '.', '')?>&nbsp;<?=PRICE_CHAR?></td>
										</tr>
										<?$i++;?>
									<?endforeach;?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			
			<div class="row pt-4">
				<div class="col-12 col-md-6 col-lg-4">
					<form action="" method="get">
						<div class="input-group">
							<?=makeDefaultFormFields([], ['promocode'])?>
							<input type="text" name="promocode" placeholder="Промокод" value="<?=set_value('promocode', 'zzz')?>" class="form-control">
							<div class="input-group-append">
								<button type="submit" class="btn btn-success float-right">Применить</button>
							</div>
						</div>
					</form>
				</div>
				<div class="col-12 col-md-6 col-lg-8">
					<h4 class="text-right mb-5">Всего : <?=number_format($item['price'], 2, '.', '')?>&nbsp;<?=PRICE_CHAR?></h4>
				</div>
			</div>
			<hr>
			<div class="row">
				<div class="col-12">
					<form action="" method="post">
						<input type="hidden" name="<?=$csrf['key']?>" value="<?=$csrf['value']?>">
						<button type="submit" class="btn btn-success float-right mt-4"><i class="mdi mdi-telegram mr-1"></i>Оплата</button> 
					</form>
				</div>
			</div>
		</div>
	</div>
<?endif;?>

<script src="<?=TEMPLATE_DIR?>/main_v1/vendors/sweetalert/sweetalert.min.js"></script>
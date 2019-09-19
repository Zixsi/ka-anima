<div class="row pb-4">
	<div class="col-6">
		<div class="row">
			<div class="col-6">
				<div class="card">
					<div class="card-body">
						<h4 class="card-title">Общая сумма доходов, <?=PRICE_CHAR?></h4>
						<h3><?=number_format($stat['transactions']['info']['total_amount'], 2, '.', ' ')?></h3>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-6">
		<div class="row">
			<div class="col-4">
				<div class="card">
					<div class="card-body">
						<h4 class="card-title">Всего пользователей</h4>
						<h3><?=number_format($stat['users']['info']['total'], 0, '', ' ')?></h3>
					</div>
				</div>
			</div>
			<div class="col-4">
				<div class="card">
					<div class="card-body">
						<h4 class="card-title">Активировано</h4>
						<h3><?=number_format($stat['users']['info']['active'], 0, '', ' ')?></h3>
					</div>
				</div>
			</div>
			<div class="col-4">
				<div class="card">
					<div class="card-body">
						<h4 class="card-title">Заблокировано</h4>
						<h3><?=number_format($stat['users']['info']['blocked'], 0, '', ' ')?></h3>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row pb-2">
	<div class="col-2">
		<form action="" method="get" id="form-period">
			<select name="period" class="form-control">
				<option value="month" <?=set_select2('period', 'month')?>>Месяц</option>
				<option value="year" <?=set_select2('period', 'year')?>>Год</option>
			</select>
		</form>
	</div>
</div>
<div class="row pb-4">
	<div class="col-6">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title">Доходы, <?=PRICE_CHAR?></h4>
				<canvas id="transactions-chart-income"></canvas>
			</div>
		</div>
	</div>
	<div class="col-6">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title">Регистрации</h4>
				<canvas id="users-chart-registration"></canvas>
			</div>
		</div>
	</div>
</div>

<div class="row pb-4">
	<div class="col-6">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title">Общие доходы, по курсам</h4>
				<canvas id="courses-chart-income"></canvas>
			</div>
		</div>
	</div>
	<div class="col-6">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title">Доходы, по курсам</h4>
				<canvas id="courses-months-chart-income"></canvas>
			</div>
		</div>
	</div>
</div>

 <script src="<?=TEMPLATE_DIR?>/main_v1/vendors/chart.js/Chart.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function(event) {
	
	var formPeriod = $('#form-period');
	formPeriod.find('select').on('change', function(){
		formPeriod.submit();
	});

	drawChart($("#transactions-chart-income"), 'Оплата', [<?=$stat['transactions']['chart']['labels']?>], [<?=$stat['transactions']['chart']['values']?>]);
	drawChart($("#users-chart-registration"), 'Регистрации', [<?=$stat['users']['chart']['labels']?>], [<?=$stat['users']['chart']['values']?>]);
	drawChart($("#courses-chart-income"), 'Доход', [<?=$stat['courses']['summary']['chart']['labels']?>], [<?=$stat['courses']['summary']['chart']['values']?>], 'bar');
	drawChart($("#courses-months-chart-income"), 'Доход', [<?=$stat['courses']['months']['chart']['labels']?>], <?=json_encode($stat['courses']['months']['chart']['data'])?>, 'line', true );
});
</script>
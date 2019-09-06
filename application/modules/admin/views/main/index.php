<div class="row">
	<div class="col-6">
		<div class="card-body">
			<h4 class="card-title">Доходы, <?=PRICE_CHAR?></h4>
			<canvas id="chart-income"></canvas>
		</div>
	</div>
	<div class="col-6">
		
	</div>
</div>

 <script src="<?=TEMPLATE_DIR?>/main_v1/vendors/chart.js/Chart.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function(event) {
	
	if($("#chart-income").length)
	{
		var areaChartIncomeData, areaChartIncomeOptions, areaChartIncomeCanvas, areaChartIncome;

		areaChartIncomeData = {
			labels: [<?=$stat_chart['labels']?>],
			datasets: [{
				label: 'Оплата',
				data: [<?=$stat_chart['values']?>],
				backgroundColor: [
					'rgba(255, 99, 132, 0.2)',
					'rgba(54, 162, 235, 0.2)',
					'rgba(255, 206, 86, 0.2)',
					'rgba(75, 192, 192, 0.2)',
					'rgba(153, 102, 255, 0.2)',
					'rgba(255, 159, 64, 0.2)'
				],
				borderColor: [
					'rgba(255,99,132,1)',
					'rgba(54, 162, 235, 1)',
					'rgba(255, 206, 86, 1)',
					'rgba(75, 192, 192, 1)',
					'rgba(153, 102, 255, 1)',
					'rgba(255, 159, 64, 1)'
				],
				borderWidth: 1,
				fill: true, // 3: no fill
			}]
		};

		areaChartIncomeOptions = {
			plugins: {
				filler: {
					propagate: true
				}
			}
		};
		
		areaChartIncomeCanvas = $("#chart-income").get(0).getContext("2d");
		areaChartIncome = new Chart(areaChartIncomeCanvas, {
			type: 'line',
			data: areaChartIncomeData,
			options: areaChartIncomeOptions
		});
	}
});
</script>
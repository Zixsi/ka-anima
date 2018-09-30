$(document).ready(function(){

	var date_time_panel = $('#panel-date-time-msk');

	date_time_panel.text(moscow_date_time());
	setInterval(function () {
		date_time_panel.text(moscow_date_time());
	}, 1000);
	

});

function moscow_date_time()
{
	return moment().utc().add(3, 'h').locale('ru').format('HH:mm:ss, dd MMM DD YYYY');
}
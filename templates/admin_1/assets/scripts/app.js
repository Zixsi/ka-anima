$(document).ready(function(){

	var date_time_panel = $('#panel-date-time-msk');

	date_time_panel.text(moscow_date_time());
	setInterval(function () {
		date_time_panel.text(moscow_date_time());
	}, 1000);
	
	update_time_to_homework();
});

function moscow_date_time()
{
	return moment().utc().add(3, 'h').locale('ru').format('HH:mm:ss, dd MMM DD YYYY');
}

function update_time_to_homework()
{
	if($('#homework-time-left').length < 1)
	{
		return;
	}

	var container = $('#homework-time-left');
	var value = container.find('.value');

	setInterval(function(){
		var time = moment(container.data('time'), "YYYYMMDD").countdown().toString();
		value.text(time);
	}, 1000);
}
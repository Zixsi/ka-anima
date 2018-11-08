$(document).ready(function(){

	var date_time_panel = $('#panel-date-time-msk');

	date_time_panel.text(moscow_date_time());
	setInterval(function () {
		date_time_panel.text(moscow_date_time());
	}, 1000);
	
	update_time_to_homework();
	upload_files();
	datetimepiker();
});

function moscow_date_time()
{
	//return moment().utc().add(3, 'h').locale('ru').format('HH:mm:ss, dd MMM DD YYYY');
	return moment().utc().add(3, 'h').locale('ru').format('HH:mm:ss, dd DD MMM YYYY');
}

function update_time_to_homework()
{
	if($('#homework-time-left').length < 1)
	{
		return;
	}

	var container = $('#homework-time-left');
	var value = container.find('.value');

	var timer_interval = setInterval(function(){
		var time = moment(container.data('time'), "YYYY-MM-DD HH:mm:ss").countdown();
		value.text(time.toString());
		if(time.days == 0 && time.hours == 0 && time.minutes == 0 && time.seconds == 0)
		{
			clearInterval(timer_interval);
			value.text('Началась');
		}
	}, 1000);
}

function upload_files()
{
	/*$('#fileupload').fileupload({
		url: '/ajax/',
		dataType: 'json',
		done: function (e, data) {
			console.log('###');
			console.log(data.result);
			console.log('###');
			$.each(data.result.files, function (index, file) {
				$('<p/>').text(file.name).appendTo('#files');
			});
		},
		progressall: function (e, data) {
			var progress = parseInt(data.loaded / data.total * 100, 10);
			$('#progress .progress-bar').css(
				'width',
				progress + '%'
			);
		}
	}).prop('disabled', !$.support.fileInput)
		.parent().addClass($.support.fileInput ? undefined : 'disabled');*/
}

/*$.datetimepicker.setDateFormatter({
    parseDate: function (date, format) {
        var d = moment(date, format);
        return d.isValid() ? d.toDate() : false;
    },
    formatDate: function (date, format) {
        return moment(date).format(format);
    },
});*/

function datetimepiker()
{
	$('#datetimepicker').datetimepicker({
		format: 'Y-m-d H:i:00',
		formatDate: 'Y-m-d H:i:00',
		lang:'ru'
	});
}
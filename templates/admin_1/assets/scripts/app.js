$(document).ready(function(){

	var date_time_panel = $('#panel-date-time-msk');

	date_time_panel.text(moscow_date_time());
	setInterval(function () {
		date_time_panel.text(moscow_date_time());
	}, 1000);
	
	update_time_to_homework();
	datetimepiker();
	datepiker();
	lectures_corusel();
	
	item_del_files();
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

/*function upload_files()
{
	$('#fileupload').fileupload({
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
		.parent().addClass($.support.fileInput ? undefined : 'disabled');
}*/

function item_del_files()
{
	$('.btn-remove-file').on('click', function(){
		var id = $(this).data('id');
		$(this).parent().parent().append('<input type="hidden" name="del_files" value="' + id + '">').end().remove();
	});
}

function datetimepiker()
{
	if($('#datetimepicker').length < 1)
	{
		return;
	}

	$.datetimepicker.setLocale('ru');
	$('#datetimepicker').datetimepicker({
		format: 'd-m-Y H:i:00',
		formatDate: 'd-m-Y H:i:00',
		lang:'ru'
	});
}

function datepiker()
{
	if($('.datepiker').length < 1)
	{
		return;
	}

	$.datetimepicker.setLocale('ru');
	$('.datepiker').datetimepicker({
		format: 'd-m-Y',
		formatDate: 'd-m-Y',
		timepicker: false
	});
}

function lectures_corusel()
{
	if($('.week-panel.owl-carousel').length < 1)
	{
		return;
	}

	var i = $('.week-panel.owl-carousel').find('.week-item.current').data('index') * 1;
	//var center_var = ($('.week-panel.owl-carousel').find('.week-item').length >= 4)?false:true;
	var center_var = false;

	$(".owl-carousel").owlCarousel({
		items: 4,
		margin: 0,
		center: center_var,
		mouseDrag: false,
		touchDrag: false,
		pullDrag: false,
		stagePadding: 0,
		startPosition: i,
		nav: true,
		slideBy: 4
	});
}
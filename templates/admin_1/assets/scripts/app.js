var GLOBAL_TIME_DELAY = 300;
var AJAX_API_URL = '/ajax/';

$(document).ready(function(){

	$.fn.serializeControls = function() {
		var data = {};

		function buildInputObject(arr, val) {
			if (arr.length < 1)
				return val;  
			var objkey = arr[0];
			if (objkey.slice(-1) == "]") {
				objkey = objkey.slice(0,-1);
			}
			var result = {};
			if (arr.length == 1){
				result[objkey] = val;
			} else {
				arr.shift();
				var nestedVal = buildInputObject(arr,val);
				result[objkey] = nestedVal;
			}
			return result;
		}

		$.each(this.serializeArray(), function() {
			var val = this.value;
			var c = this.name.split("[");
			var a = buildInputObject(c, val);
			$.extend(true, data, a);
		});
		
		return data;
	}

	toastr.options = {
		"closeButton": true,
		"debug": false,
		"newestOnTop": false,
		"progressBar": true,
		"positionClass": "toast-top-right",
		"preventDuplicates": false,
		"onclick": null,
		"showDuration": "0",
		"hideDuration": "1000",
		"timeOut": "5000",
		"extendedTimeOut": "1000",
		"showEasing": "swing",
		"hideEasing": "linear",
		"showMethod": "fadeIn",
		"hideMethod": "fadeOut"
	};

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

	appListener();

	wallComponent();
});

function toastrMsg(type, text)
{
	toastr[type](text, '');
}

function showLoader(t)
{
	$(t).addClass('ld-loading');
}

function hideLoader(t)
{
	$(t).removeClass('ld-loading');
}

function showPageLoader()
{
	$('.page-loader').addClass('ld-loading');
}

function hidePageLoader()
{
	$('.page-loader').removeClass('ld-loading');
}

function disableInput(t)
{
	$(t).find('input, select, button').attr("disabled", true);
}

function enableInput(t)
{
	$(t).find('input, select, button').attr("disabled", false);
}

function clearInput(t)
{
	t.find('input, textarea, select').each(function(){
		if($(this).data('const') !== undefined && $(this).data('const') !== null)
		{
			// empty
		}
		else
		{
			if($(this).is('input[type="radio"]') || $(this).is('input[type="checkbox"]'))
			{
				$(this).prop('checked', false);
			}
			else if($(this).is('select'))
			{
				$(this).val($(this).find('option:first').val());
				if($(this).hasClass('selectpicker'))
				{
					$(this).selectpicker("refresh");
				}
			}
			else
			{
				var val = '';
				if($(this).data('default') !== undefined && $(this).data('default') !== null)
				{
					val = $(this).data('default');
				}

				$(this).val(val);
			}
		}
	});
}

function setFormInput(t, data)
{
	t.find('input, textarea, select').each(function(){
		var key = $(this).attr('name');
		if(key !== undefined && key !== null)
		{
			if(key.indexOf('[]') > 0)
			{
				key = key.replace(/\[\]/g, '');
			}
			
			var val = data[key];

			if(val !== undefined && val !== null)
			{
				if($(this).is('input[type="radio"]') || $(this).is('input[type="checkbox"]'))
				{
					if(Array.isArray(val))
					{
						if(val.indexOf($(this).val()) >= 0)
						{
							$(this).prop('checked', true);
						}
					}
					else if($(this).val() === val)
					{
						$(this).prop('checked', true);
					}
				}
				else if($(this).is('select'))
				{
					$(this).val(val);
					if($(this).hasClass('selectpicker'))
					{
						$(this).selectpicker("refresh");
					}
				}
				else
				{
					$(this).val(val);
				}
			}
		}
	});
}

// Запрос к API
function ajaxApiQuery(method, params, callback)
{
	showPageLoader();
	var query = {'jsonrpc': '2.0', 'method': method, 'params': params, 'id': 1};
	setTimeout(function(){
		$.ajax({
			url: AJAX_API_URL,
			type: 'POST',
			dataType: 'json',
			data: JSON.stringify(query),
			contentType: 'application/json; charset=utf-8',
			success: function(res){
				if(res.result !== undefined && res.result !== null)
				{
					if(callback && typeof(callback) === "function")
					{
						callback(res.result);
					}
				}
				else if(res.error)
				{
					if(res.error.code === -32000)
					{
						setTimeout(function(){
							window.location.href = "/";
						}, 3000);
					}
					
					toastrMsg('error', res.error.message);
				}

				hidePageLoader();
			},
			error: function(e, code){
				hidePageLoader();
				toastrMsg('error', 'AJAX ERROR');
				console.log(e.responseText);
			}
		});
	}, GLOBAL_TIME_DELAY);
}

function appListener()
{
	var modal_group_subscr = $('#modal-enroll');
	var g_item_standart = modal_group_subscr.find('.item.item-standart');
	var g_item_advanced = modal_group_subscr.find('.item.item-advanced');
	var g_item_vip = modal_group_subscr.find('.item.item-vip');

	var add_review_modal = $('#add-review-modal');
	var add_review_modal_form = add_review_modal.find('form');
	
	$('.btn-subscr-group').on('click', function(){
		var target = $(this).data('target');
		var data = JSON.parse($(target).text());
		console.log(data);

		modal_group_subscr.find('.item').removeClass('disabled').find('.group-subscr-form .row').show();
		if(data.only_standart === '1')
		{
			modal_group_subscr.find('.item').addClass('disabled');;
			g_item_standart.removeClass('disabled');
			if(data.free)
			{
				modal_group_subscr.find('.item .group-subscr-form .row').hide();
			}
		}

		modal_group_subscr.find('input[name="course"]').val(data.course).end().find('input[name="group"]').val(data.group);

		g_item_standart.find('.month .value').text(number_format(data.price.standart.month, 0, '', ' ')).end()
		.find('.full .value').text(number_format(data.price.standart.full, 2, '.', ' '));

		g_item_advanced.find('.month .value').text(number_format(data.price.advanced.month, 0, '', ' ')).end()
		.find('.full .value').text(number_format(data.price.advanced.full, 2, '.', ' '));

		g_item_vip.find('.month .value').text(number_format(data.price.vip.month, 0, '', ' ')).end()
		.find('.full .value').text(number_format(data.price.vip.full, 2, '.', ' '));

		modal_group_subscr.modal('show');
	});

	$('body').on('submit', 'form.group-subscr-form', function(){
		var params = $(this).serializeControls();
		ajaxApiQuery('subscr.group', params, function(res){
			toastrMsg('success', res);
			modal_group_subscr.modal('hide');
			//window.location.reload(true);
		});

		return false;
	});

	// добавление ревью
	$('.btn-add-review').on('click', function(){
		var params = {
			group: $(this).data('group'),
			lecture: $(this).data('lecture'),
			user: $(this).data('user')
		};
		setFormInput(add_review_modal_form, params);
		add_review_modal.modal('show');
	});

	$('.bnt-remove-review').on('click', function(){
		ajaxApiQuery('review.delete', $(this).data('id'), function(res){
			toastrMsg('success', res);
			add_review_modal.modal('hide');
			window.location.reload(true);
		});

		return false;
	});

	add_review_modal_form.on('submit', function(){
		var params = $(this).serializeControls();
		ajaxApiQuery('review.add', params, function(res){
			toastrMsg('success', res);
			add_review_modal.modal('hide');
			window.location.reload(true);
		});

		return false;
	});
}

function wallComponent()
{
	var wall_input = $('#wall-component .wall-from--text-input');
	var wall_main_form = $('#wall-main-form');
	var wall_child_from = $('#wall-component .wall-child-from');
	var tpl_wall_child = $('#wall-child-tpl').html();
	var tpl_wall_item_role = $('#wall-item-role-tpl').html();

	wall_input.on('input change focus', function(e){
		$(this).height(20);
		$(this).height(this.scrollHeight);
	});

	$('#wall-component .wall-item-tools').on('click', '.btn-comments', function(){
		var is_loaded_child = $(this).hasClass('loaded');
		var parent_id = $(this).data('id');
		$(this).parent().parent().find('.wall-wrap-childs').fadeToggle(0).find('.wall-from--text-input').focus();
		// получить список дочерних комментариев
		if(!is_loaded_child)
		{
			$(this).addClass('loaded');
			$('#wall-component').trigger('wall:update-childs', {id: parent_id});
		}
	});

	$('#wall-component').on('wall:update-childs', function(e, params){
		var childs_container = $('#wall-childs-id' + params.id);
		if(childs_container.length > 0)
		{
			ajaxApiQuery('wall.message.child', {id: params.id}, function(res){
				wallFillChilds(childs_container, res);
			});
		}
	});
	
	// обработка главной формы
	wall_main_form.on('submit', function(){
		var form = $(this);
		var params = $(this).serializeControls();
		ajaxApiQuery('wall.message', params, function(res){
			clearInput(form);
			window.location.reload(true);
		});
		return false;
	});

	wall_child_from.on('submit', function(){
		var form = $(this);
		var params = $(this).serializeControls();
		ajaxApiQuery('wall.message', params, function(res){
			clearInput(form);
			$('#wall-component').trigger('wall:update-childs', {id: params.target});
		});
		return false;
	});

	function wallFillChilds(container, data)
	{
		container.empty();
		var cnt = 0;
		data.forEach(function(e, i){
			cnt++;
			html = tpl_wall_child;
			html = html.replace(/{USER_ID}/g, e.user);
			html = html.replace(/{USER_NAME}/g, e.full_name);
			html = html.replace(/{USER_IMG}/g, e.img);
			html = html.replace(/{DATE}/g, e.ts);
			html = html.replace(/{TEXT}/g, e.text);

			if((e.role * 1) > 0)
			{
				html_role = tpl_wall_item_role;
				html_role = html_role.replace(/{VALUE}/g, e.role_name);
				html = html.replace(/{ROLE}/g, html_role);
			}
			else
			{
				html = html.replace(/{ROLE}/g, '');
			}

			container.append(html);
		});

		container.closest('.wall-panel').find('.wall-item-tools .info-child-cnt').text(cnt);
	}
}

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

function number_format(number, decimals = 0, dec_point = '.', thousands_sep = ',')
{
	let sign = number < 0 ? '-' : '';
	let s_number = Math.abs(parseInt(number = (+number || 0).toFixed(decimals))) + "";
	let len = s_number.length;
	let tchunk = len > 3 ? len % 3 : 0;
	let ch_first = (tchunk ? s_number.substr(0, tchunk) + thousands_sep : '');
	let ch_rest = s_number.substr(tchunk).replace(/(\d\d\d)(?=\d)/g, '$1' + thousands_sep);
	let ch_last = decimals ?
	dec_point + (Math.abs(number) - s_number).toFixed(decimals).slice(2):'';

	return sign + ch_first + ch_rest + ch_last;
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
		lang:'ru',
		dayOfWeekStart: 1
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
		lang:'ru',
		timepicker: false,
		dayOfWeekStart: 1
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
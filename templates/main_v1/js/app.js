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

	$(".form-check label,.form-radio label").append('<i class="input-helper"></i>');

	authListener();
});

function toastrMsg(type, text)
{
	if(text.indexOf('<br>'))
	{
		text = text.split('<br>');
		if(text.length == 1)
			text = text[0];
		else
			text = text.slice(0, (text.length - 1));
	}

	var options = {
		heading: '',
		text: text,
		showHideTransition: 'slide',
		icon: 'error',
		position: 'top-right'
	};

	switch(type)
	{
		case 'success':
			options.heading = 'Успешно';
			options.icon = 'success';
			options.loaderBg = '#46c35f';
		break;
		case 'error':
			options.heading = 'Ошибка';
			options.icon = 'error';
			options.loaderBg = '#f96868';
		break;
		case 'warning':
			options.heading = 'Передупреждение';
			options.icon = 'warning';
			options.loaderBg = '#f2a654';
		break;
		case 'info':
			options.heading = 'Информация';
			options.icon = 'info';
			options.loaderBg = '#57c7d4';
		break;
	}

	$.toast(options);
}

function showPageLoader()
{
	$('.page-loader').addClass('ld-loading');
}

function hidePageLoader()
{
	$('.page-loader').removeClass('ld-loading');
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

// ajax запрос
function ajaxQuery(method, params, callback)
{
	showPageLoader();
	var query = {'method': method, 'params': params};
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
						callback(res.result);
				}
				else if(res.error)
				{
					if(res.error.code === 'NOT_AUTHORIZED')
					{
						setTimeout(function(){
							window.location.href = '/';
						}, 2000);
					}
					
					toastrMsg('error', res.error.message);
				}

				hidePageLoader();
			},
			error: function(e, code){
				hidePageLoader();
				toastrMsg('error', 'AJAX ERROR');
				// console.log(e.responseText);
			}
		});
	}, GLOBAL_TIME_DELAY);
}

//====================================================================//

function authListener()
{
	$('#auth--login-form').on('submit', function(){
		ajaxQuery('auth.login', $(this).serializeControls(), function(res){
			window.location.href = '/';
		});
		return false;
	});

	$('#auth--register-form').on('submit', function(){
		ajaxQuery('auth.register', $(this).serializeControls(), function(res){
			window.location.href = '/';
		});
		return false;
	});

	$('#auth--forgot-form').on('submit', function(){
		var params = $(this).serializeControls();
		ajaxQuery('auth.forgot', params.email, function(res){
			toastrMsg('success', 'Письмо отправлено. Проверьте пожалуйста почту');
		});
		return false;
	});

	$('#auth--recovery-form').on('submit', function(){
		ajaxQuery('auth.recovery', $(this).serializeControls(), function(res){
			toastrMsg('success', 'Операция выполнена успешно');
			setTimeout(function(){
				window.location.href = '/';
			}, 3000);
		});
		return false;
	});
}
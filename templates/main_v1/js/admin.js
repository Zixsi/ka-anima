$(document).ready(function(){
	$('.delete-item-form').on('submit', function(){
            if(confirm("Вы действительно хотите УДАЛИТЬ этот элемент?") === false) {
                return false;
            }
	});
        
        $('.delete-item-btn').on('click', function(){
            if(confirm("Вы действительно хотите УДАЛИТЬ этот элемент?") === false) {
                return false;
            }
	});

	AJAX_API_URL = '/admin/ajax/';

	usersListener();
	courseListener();
	groupListener();
	workshopListener();
	subscriptionListener();
	textEditor();
});

function textEditor()
{
	if($('#fdescription').length > 0)
	{
		var editorFdescriptionConf = ['title','bold','italic','underline','fontScale','ol','ul'];
		var editorFdescription = new Simditor({
			id: 'fdescription',
			textarea: $('#fdescription'),
			toolbar: editorFdescriptionConf,
			pasteImage: false,
		});
	}

	if($('#ftask').length > 0)
	{
		var editorFdescriptionConf = ['title','bold','italic','underline','fontScale','ol','ul'];
		var editorFdescription = new Simditor({
			id: 'ftask',
			textarea: $('#ftask'),
			toolbar: editorFdescriptionConf,
			pasteImage: false,
		});
	}

	if($('#editor1').length > 0)
	{
		var editorFdescriptionConf = ['title','bold','italic','underline','fontScale','ol','ul'];
		var editorFdescription = new Simditor({
			id: 'editor1',
			textarea: $('#editor1'),
			toolbar: editorFdescriptionConf,
			pasteImage: false,
		});
	}

	if($('#editor2').length > 0)
	{
		var editorFdescriptionConf = ['title','bold','italic','underline','fontScale','ol','ul'];
		var editorFdescription = new Simditor({
			id: 'editor2',
			textarea: $('#editor2'),
			toolbar: editorFdescriptionConf,
			pasteImage: false,
		});
	}
}

function courseListener()
{
	// name="course"
	var roadmap_component = $('.roadmap-component');
	var add_group_modal = $('#add-group-modal');
	var add_group_modal_form = add_group_modal.find('form');
	var remove_group_modal = $('#remove-group-modal');
	var remove_group_modal_form = remove_group_modal.find('form');
	var admin_lectures_table = $('#admin-lectures-table');
	var admin_remove_lecture_modal = $('#admin-remove-lecture-modal');
	var admin_remove_lecture_form = admin_remove_lecture_modal.find('form');
	
	$('.roadmap-component').on('click', '.btn-add-group', function(){
		add_group_modal_form.find('input[name="course"]').val($(this).data('value'));
		add_group_modal.modal('show');
	});

	add_group_modal_form.on('submit', function(){
		var params = $(this).serializeControls();
		ajaxQuery('group.create', params, function(res){
			//toastrMsg('success', res);
			window.location.reload(true);
		});

		return false;
	});

	add_group_modal_form.on('change', 'select[name="type"]', function(){
		if($(this).val() == 'private')
			add_group_modal_form.find('.users-block').removeClass('hidden');
		else
			add_group_modal_form.find('.users-block').addClass('hidden');
	});

	$('.roadmap-component').on('click', '.btn-remove-group', function(){
		remove_group_modal.find('input[name="id"]').val($(this).data('id'));
		remove_group_modal.modal('show');
	});

	remove_group_modal_form.on('submit', function(){
		var params = $(this).serializeControls();
		ajaxQuery('group.remove', params, function(res){
			//toastrMsg('success', res);
			window.location.reload(true);
		});

		return false;
	});

	$('#select2-users').select2({
		//placeholder: 'Select an item',
		ajax: {
			url: '/admin/users/listForSelect/',
			dataType: 'json',
			delay: 250,
			processResults: function(data) {
				return {
					results: data
				};
			},
			cache: true
		}
	});

	//===== лекции =====//

	admin_lectures_table.on('click', '.btn-item-remove', function(){
		var id = $(this).data('id');
		admin_remove_lecture_form.find('input[name="id"]').val(id);
		admin_remove_lecture_modal.modal('show');
		return false;
	});

	admin_remove_lecture_form.on('submit', function(){
		var params = $(this).serializeControls();
		ajaxQuery('lecture.remove', params.id, function(res){
			toastrMsg('success', res);
			admin_remove_lecture_modal.modal('hide');
			setTimeout(function(){
				window.location.reload(true);
			}, 1000);
		});
		return false;
	});

}

function usersListener()
{
	var add_item_modal = $('#add-user-modal');
	var add_item_modal_form = add_item_modal.find('form');
	var remove_item_modal = $('#remove-user-modal');
	var remove_item_modal_form = remove_item_modal.find('form');
	var block_item_modal = $('#block-user-modal');
	var block_item_modal_form = block_item_modal.find('form');
	var filter_form = $('#filter-user-form');
	var edit_form = $('#form-user-params');
	
	// создание пользователя
	add_item_modal.on('show.bs.modal', function(){
		clearInput(add_item_modal_form);
	});

	add_item_modal_form.on('submit', function(){
		var params = $(this).serializeControls();
		ajaxQuery('user.add', params, function(res){
			add_item_modal.modal('hide');
			clearInput(add_item_modal_form);
			toastrMsg('success', res);

			setTimeout(function(){
				window.location.reload(true);
			}, 1000);
		});

		return false;
	});

	$('.btn-send-email-confirm').on('click', function(){

		ajaxQuery('user.sendConfirmEmail', $(this).data('id'), function(res){
			toastrMsg('success', res);
		}, true);

		return false;
	});

	// блокировка пользователя
	$('.btn-user-block').on('click', function(){
		var id = $(this).data('id');
		block_item_modal_form.find('input[name="id"]').val(id);
		block_item_modal.modal('show');
		return false;
	});

	block_item_modal_form.on('submit', function(){
		var params = $(this).serializeControls();
		ajaxQuery('user.block', params.id, function(res){
			toastrMsg('success', res);
			block_item_modal.modal('hide');
			setTimeout(function(){
				window.location.reload(true);
			}, 1000);
		});

		return false;
	});

	// разблокировка пользователя
	$('.btn-user-unblock').on('click', function(){
		var id = $(this).data('id');
		ajaxQuery('user.unblock', id, function(res){
			toastrMsg('success', res);
			setTimeout(function(){
				window.location.reload(true);
			}, 1000);
		});

		return false;
	});

	// удаление пользователя
	$('.btn-user-remove').on('click', function(){
		var id = $(this).data('id');
		remove_item_modal_form.find('input[name="id"]').val(id);
		remove_item_modal.modal('show');
		return false;
	});

	remove_item_modal_form.on('submit', function(){
		var params = $(this).serializeControls();
		ajaxQuery('user.remove', params.id, function(res){
			toastrMsg('success', res);
			remove_item_modal.modal('hide');
			setTimeout(function(){
				window.location.reload(true);
			}, 1000);
		});

		return false;
	});

	filter_form.on('change', 'input[type="radio"]', function(){
		filter_form.submit();
	});

	edit_form.on('submit', function(){
		var params = $(this).serializeControls();
		var files = formFiles($(this));
		
		ajaxFilesQuery('user.edit', params, files, function(res){
			toastrMsg('success', res);
			setTimeout(function(){
				window.location.reload(true);
			}, 1000);
		});

		return false;
	});

}


function groupListener()
{
	var group__add_user_modal = $('#group--add-user-modal');
	var group__add_user_modal_form = group__add_user_modal.find('form');

	$('#group--add-user-btn').on('click', function(){
		group__add_user_modal.modal('show');
	});

	group__add_user_modal_form.on('submit', function(){
		var params = $(this).serializeControls();
		ajaxQuery('group.user.add', params, function(res){
			toastrMsg('success', res);
			setTimeout(function(){
				window.location.reload(true);
			}, 1000);
		});

		return false;
	});

	$('#group-set-teacher').on('submit', function(){
		if(confirm("Вы уверены что хотите сменить преподавателя?"))
		{
			var params = $(this).serializeControls();
			ajaxQuery('setGroupTeacher', params, function(res){
				toastrMsg('success', res);
				setTimeout(function(){
					window.location.reload(true);
				}, 1000);
			});
		}

		return false;
	});

	$('.group-remove-user').on('submit', function(){
		if(confirm("Вы уверены что хотите удалить ученика?"))
		{
			var params = $(this).serializeControls();
			ajaxQuery('removeGroupUser', params, function(res){
				toastrMsg('success', res);
				setTimeout(function(){
					window.location.reload(true);
				}, 1000);
			});
		}

		return false;
	});
        
        let modalGroupUserMove = $('#group--user-move');
        let modalGroupUserMoveForm = modalGroupUserMove.find('form');
        
        $('.group--user-move-btn').on('click', function() {
            modalGroupUserMoveForm.find('input[name="user"]').val($(this).data('id'));
            modalGroupUserMove.modal('show');
        });
        
        modalGroupUserMoveForm.on('submit', function(){
            var params = $(this).serializeControls();
            
            ajaxQuery('group.user.move', params, function(res){
                toastrMsg('success', res);
                modalGroupUserMoveForm.find('input[name="user"]').val(0);
                setTimeout(function(){
                    window.location.reload(true);
                }, 1000);
            });

            return false;
	});

}

function workshopListener()
{
	var addVideoModal = $('#add-video-workshop');
	var addVideoModalForm = addVideoModal.find('form');
	var editVideoModal = $('.edit-video-workshop');
	var editVideoModalForm = editVideoModal.find('form');

	addVideoModalForm.on('submit', function(){
		var params = $(this).serializeControls();
		ajaxQuery('addVideoWorkshop', params, function(res){
			toastrMsg('success', res);
			setTimeout(function(){
				window.location.reload(true);
			}, 1000);
		});

		return false;
	});

	editVideoModalForm.on('submit', function(){
		var params = $(this).serializeControls();
		ajaxQuery('updateVideoWorkshop', params, function(res){
			toastrMsg('success', res);
			setTimeout(function(){
				window.location.reload(true);
			}, 1000);
		});

		return false;
	});	

	$('.delete-video-workshop').on('click', function(){
		if(confirm('Вы действительно хотите удалить это видео?'))
		{
			var params = {'id': $(this).data('id')};
			ajaxQuery('deleteVideoWorkshop', params, function(res){
				toastrMsg('success', res);
				setTimeout(function(){
					window.location.reload(true);
				}, 1000);
			});
		}

		return false;
	});

	$('.add-user-workshop').on('click', function(){
		var id = $(this).data('id')
		var target = $(this).data('target')

		$(target).find('input[name="id"]').val(id).end().modal('show');
	});

	$('#add-user-workshop-modal form').on('submit', function(){
		var params = $(this).serializeControls();
		ajaxQuery('addUserWorkshop', params, function(res){
			toastrMsg('success', res);
			setTimeout(function(){
				window.location.reload(true);
			}, 1000);
		});

		return false;
	});

	$('#promocode-form select[name="target_type"]').on('change', function(){
		let val = $(this).val();
		let select_target = $('#promocode-form select[name="target_id"]');
		select_target.find('optgroup').hide().end().val(0);
		if(val === '' || val === null || val === undefined) {
			// empty
		} else {
			select_target.find('optgroup[data-type="' + val + '"]').show();
		}
	});
}

function subscriptionListener()
{
	$('.btn-removeSubscription').on('click', function(){
		var id = $(this).data('id');
		if(confirm('Вы действительно хотите отменить подписку?'))
		{
			ajaxQuery('removeSubscription', {id: id}, function(res){
				toastrMsg('success', res);
				setTimeout(function(){
					window.location.reload(true);
				}, 1000);
			});
		}

		return false;
	});
}
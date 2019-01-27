$(document).ready(function(){

	$('.delete-item-form').on('submit', function(){
		if(confirm("Вы действительно хотите УДАЛИТЬ этот элемент?") === false)
			return false;
	});
	
});
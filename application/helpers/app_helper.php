<?
function ShowError($text)
{
	if(empty($text) == FALSE)
	{
		echo '<div class="alert alert-danger">'.$text.'</div>';
	}
}	
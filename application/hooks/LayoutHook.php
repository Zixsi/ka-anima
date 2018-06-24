<?
class LayoutHook
{
	public function Default()
	{
		$CI =& get_instance();
		$CI->load->layout = $CI->config->item('default_layout');
	}
}
<?
class SystemHook
{
	public $CI;

	public function __construct()
	{
		$this->CI = &get_instance();
	}

	public function Profiler()
	{
		$this->CI->output->enable_profiler(false);
	}

	public function InitOptions()
	{
		$this->CI->form_validation->set_error_delimiters('<div>', '</div>');
	}

	public function CheckAuth()
	{
		$c = $this->CI->router->fetch_class();		
		$a = $this->CI->router->fetch_method();
		$check = $this->CI->Auth->Check();

		if($check == false && $c != 'login')
		{
			redirect('?c=login');
		}
		elseif($check == true && $c == 'login' && $a != 'logout')
		{
			redirect('?c=main');
		}
	}
}
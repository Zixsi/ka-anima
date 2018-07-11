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

		if($check == false && $c != 'auth')
		{
			redirect('/auth/');
		}
		elseif($check == true && $c == 'auth' && $a != 'logout')
		{
			redirect('/');
		}
	}
}
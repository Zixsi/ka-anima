<?
class SystemHook
{
	public $CI;

	public function __construct()
	{
		$this->CI = &get_instance();
	}

	public function profiler()
	{
		$this->CI->output->enable_profiler(false);
	}

	public function initOptions()
	{
		date_default_timezone_set('Europe/Moscow');
		setlocale(LC_ALL, 'ru_RU.UTF-8');
		$this->CI->form_validation->set_error_delimiters('<div>', '</div>');
	}

	public function checkAuth()
	{
		if(is_cli() == true)
		{
			return;
		}

		$c = $this->CI->router->fetch_class();		
		$a = $this->CI->router->fetch_method();
		$d = $this->CI->uri->segment(1);
		$check = $this->CI->Auth->check();
		$user = $this->CI->Auth->user();

		//var_dump($d); die();

		if($d == 'admin')
		{
			if($check == false && $c != 'auth')
			{
				redirect('/admin/auth/');
			}
			elseif($check == true && $c == 'auth' && $a != 'logout')
			{
				redirect('/admin/');
			}
			elseif($check == true && $user['role'] != 5)
			{
				redirect('/');
			}
		}
		else
		{
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
}
<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Charts extends Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->helper('url');
		$this->load->library('tank_auth');
	}

	function index()
	{
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login', 'refresh');
		} else {
			$data['user_id']	= $this->tank_auth->get_user_id();
			$data['username']	= $this->tank_auth->get_username();
			$this->load->view('user_charts', $data);
		}
	}
}

/* End of file charts.php */
/* Location: ./application/controllers/charts.php */

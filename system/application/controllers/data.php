<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Data extends Controller
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
		}
	}
}

/* End of file data.php */
/* Location: ./application/controllers/data.php */

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
			return;
		} else {
			$data['user_id'] = $this->tank_auth->get_user_id();
			$data['username'] = $this->tank_auth->get_username();

			$this->db->select("readers.name, readers.id FROM readers ".
					"WHERE readers.user_id=".$this->tank_auth->get_user_id());
			$val = $this->db->get();
			$data['devices']= array();
			
			foreach($val->result() as $row) {
				if(count($data['devices'])== 0)
				{
					$data['reader_id'] = $row->id;
				}
				$data['devices'][] = array('name' =>$row->name, 'id' => (int) $row->id);
			}
			
			$this->template->load('header','user_charts_head',$data);
			$this->template->load('sidebar','user_charts_side',$data);
			$this->template->load('content','user_charts',$data);
		}
			$this->template->render();
	}
}

/* End of file charts.php */
/* Location: ./application/controllers/charts.php */

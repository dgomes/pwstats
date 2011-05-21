<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Devices extends CI_Controller
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

			$this->db->select("readers.name, readers.id, readers.poll_interval, readers.url FROM readers ".
					"WHERE readers.user_id=".$this->tank_auth->get_user_id());
			$val = $this->db->get();
			$data['devices']= array();

			foreach($val->result() as $row) {
				if(count($data['devices'])== 0)
				{
					$data['reader_id'] = $row->id;
				}
				$data['devices'][] = array('name' =>$row->name, 'id' => (int) $row->id, 'url' => $row->url, 'poll_interval' => $row->poll_interval);
			}

			$this->template->load('content','devices',$data);
		}
			$this->template->render();
	}
	function info($id) {
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login', 'refresh');
			return;
		} else {
			$this->db->select("readers.*, models.name AS model_name FROM readers,models ".
					"WHERE readers.user_id=".$this->tank_auth->get_user_id()." AND readers.id=".$id." AND readers.models_id = models.id");
			$val = $this->db->get();
			$data = (array) end($val->result());

			$this->db->select("unit_timestamp AS last_update FROM `entries` WHERE readers_id=1 ORDER BY unit_timestamp DESC LIMIT 1");
			$val = $this->db->get();

			$data = array_merge($data, (array) end($val->result()));

			$this->template->load('content','device_info',$data);

		}
			$this->template->render();
	}
}

/* End of file charts.php */
/* Location: ./application/controllers/charts.php */

<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cron extends Controller {

       function Cron()
       {
	       parent::Controller();
	       $this->load->helper('url');
       }
       function index()
       {
	       $this->db->select('id, models_id');
	       $readers = $this->db->get('readers');
	       foreach($readers->result() as $row) {
			   	$this->db->select('plugin');
			   	$model = $this->db->get_where('models',array('id' => $row->models_id))->result();

				$this->load->library("reader_plugins/".$model[0]->plugin);

				if(method_exists($model[0]->plugin,"read"))
				{
						$ret = call_user_func(array($model[0]->plugin,"read"),$model[0]->id);
				}else
				{
						echo "Could not load plugin: ".$model[0]->plugin."<br />";
				}

			}
       }
}
?>

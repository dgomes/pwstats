<?php
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
		       fopen(site_url("plugins/".$model[0]->plugin."/read/".$row->id),'r');
	       }
       }
}
?>

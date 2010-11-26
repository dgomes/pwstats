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

			   if(file_exists("system/application/controllers/plugins/"+$model[0]->plugin+".php"))
			   {
				   include_once("system/applications/controllers/plugins"+$model[0]->plugin+".php");
				   if(function_exists($model[0]->plugin+".read"))
				   {
						$ret = call_user_func($model[0]->plugin+".read",$model[0]->id);
				   }else
				   {
						echo "Could not load plugin<br />";
				   }
			   }else{
					echo "Could not find plugin: ".$model[0]->plugin."<br />";
			   }
			}
       }
}
?>

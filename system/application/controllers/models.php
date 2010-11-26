<?php
class Models extends Controller {

       function Models()
       {
            parent::Controller();
            $this->load->library('tank_auth');
	    if ($this->tank_auth->is_logged_in())
            	$this->load->scaffolding('models');
       }
}
?>

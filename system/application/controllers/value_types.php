<?php
class Value_types extends Controller {

       function Value_types()
       {
            parent::Controller();

            $this->load->library('tank_auth');
	    if ($this->tank_auth->is_logged_in())
            	$this->load->scaffolding('value_types');
       }
}
?>

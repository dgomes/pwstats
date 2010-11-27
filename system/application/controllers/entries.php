<?php
class Entries extends Controller {

       function Entries()
       {
            parent::Controller();
            $this->load->library('tank_auth');
	    if ($this->tank_auth->is_logged_in())
		    $this->load->scaffolding('entries');
	    else
		    redirect('/auth/login', 'refresh');
       }
}
?>

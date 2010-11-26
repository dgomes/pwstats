<?php
class Readers extends Controller {

       function Readers()
       {
            parent::Controller();
            $this->load->library('tank_auth');
	    if ($this->tank_auth->is_logged_in())
		    $this->load->scaffolding('readers');
	    else
		    redirect('/auth/login', 'refresh');
       }
}
?>

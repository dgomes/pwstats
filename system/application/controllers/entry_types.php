<?php
class Entries_types extends Controller {

       function Entries_types()
       {
            parent::Controller();

            $this->load->library('tank_auth');
	    if ($this->tank_auth->is_logged_in())
		    $this->load->scaffolding('entrie_types');
	    else
		    redirect('/auth/login', 'refresh');
       }
}
?>

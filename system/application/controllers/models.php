<?php
class Models extends CI_Controller {

       function Models()
       {
            parent::Controller();
            $this->load->library('tank_auth');
	    if ($this->tank_auth->is_logged_in())
		    $this->load->scaffolding('models');
	    else
		    redirect('/auth/login', 'refresh');
       }
}
?>

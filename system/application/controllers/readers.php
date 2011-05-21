<?php
class Readers extends CI_Controller {

       function __construct()
       {
            parent::__construct();
            $this->load->library('tank_auth');
	    if ($this->tank_auth->is_logged_in())
		    $this->load->scaffolding('readers');
	    else
		    redirect('/auth/login', 'refresh');
       }
}
?>

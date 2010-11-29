<?php
class Imeterbox extends Controller {
	$plugin = null;

	function Imeterbox()
	{
		parent::Controller();

		$this->load->library("plugins/imeterbox","RP_plugin");
		$plugin = $this->RP_plugin;
	}

	function index() {
		if($plugin != null)
		{
			$plugin->index();
		}
	}

	function read($readers_id) {
		if($plugin == null)
			return null;

		return $this->$plugin->read($this->$db,$readers_id);
	}
}
?>

<?php
class Series extends Controller {

       function Series()
       {
	       parent::Controller();
	       $this->load->helper('url');
       }
       function lastday($reader_id)
       {
		$this->db->select("entry, UNIX_TIMESTAMP(unit_timestamp) AS ts FROM entries WHERE entries_types_id = 2", FALSE);
	       $val = $this->db->get(); 
		foreach($val->result() as $row) {
			echo json_encode($row).",";
	       }
       }
}
?>

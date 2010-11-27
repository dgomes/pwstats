<?php
class Series extends Controller {

       function Series()
       {
	       parent::Controller();
	       $this->load->helper('url');
       }
       function lastday($reader_id)
       {
	       //$this->db->select("entry AS power, UNIX_TIMESTAMP(unit_timestamp) AS ts FROM entries,entries_types WHERE entries_types.name='Power' AND entries.entries_types_id = entries_types.id", FALSE);
	       $this->db->select("entry AS power, UNIX_TIMESTAMP(unit_timestamp) AS ts FROM entries,entries_types WHERE AND entries.reader_id = $reader_id", FALSE);
	       $val = $this->db->get(); 
		foreach($val->result() as $row) {
			echo json_encode($row).",";
	       }
       }
}
?>

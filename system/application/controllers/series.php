<?php
class Series extends Controller {

       function Series()
       {
	       parent::Controller();
	       $this->load->helper('url');
       }
       function lastday($reader_id)
       {
	       $this->db->select("entries.entry AS power, UNIX_TIMESTAMP( entries.unit_timestamp ) AS ts FROM entries, entries_types WHERE entries.readers_id = $reader_id AND  entries_types.name='Power' AND entries.entries_types_id = entries_types.id", FALSE);
		   $val = $this->db->get();
		   $values= null;
		   foreach($val->result() as $row) {
			   $values.= array( 'x' => $row['ts'], 'y'=> $row['power']);
		   }
		  	echo  "[ 'name': 'Power', 'values': ".json_encode($values)."]";
       }
}
?>

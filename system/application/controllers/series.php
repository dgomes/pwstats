<?php
class Series extends Controller {

		function Series()
		{
			parent::Controller();
			$this->load->helper('url');
		}

		function widget($key, $reader_id, $age= 86400)
		{


		}

		function reader($reader_id, $age = 86400 )
		{
			 if (!$this->tank_auth->is_logged_in())
			 {
				 return;
			 }

			$reader_id = (int) $reader_id;
			$age = (int) $age;
			$date = new DateTime();
			$start = $date->getTimestamp() - $age;
			if($age == 0)
				$date->setTimestamp(0);
			else
				$date->setTimestamp($start);
			$this->db->select("entries.entry AS power,".
				"UNIX_TIMESTAMP( entries.unit_timestamp ) AS ts FROM entries, entries_types ".
				"WHERE entries.readers_id = $reader_id AND  entries_types.name='Power' ".
				"AND entries.entries_types_id = entries_types.id ".
				"AND UNIX_TIMESTAMP(entries.site_timestamp) > ".$date->getTimestamp(), FALSE);
			$val = $this->db->get();
			$data = array();
			foreach($val->result() as $row) {
				$data[] = array( (int) $row->ts, (int) $row->power);
			}
			echo  "{ 'name': 'Power', 'values': ".json_encode($data)."}";
       	}
	}
?>

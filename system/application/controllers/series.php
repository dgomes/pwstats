<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Series extends CI_Controller {

		function __construct()
		{
			parent::__construct();
			$this->load->helper('url');

		}

		function widget($key, $reader_id, $age= 86400)
		{


		}

		function reader($reader_id, $min = 86400, $max = 86400, $detail = 0 )
		{
			 if (!$this->tank_auth->is_logged_in())
			 {
				 return;
			 }
			
			
			
			$user = $this->tank_auth->get_user_id();
			$reader_id = (int) $reader_id;
			
			$start = new DateTime("@".round($min/1000,0));
			$end = new DateTime("@".round($max/1000,0));
			$this->db->start_cache();


			$this->db->select("entries.entry AS power, ".
				"UNIX_TIMESTAMP( entries.unit_timestamp) AS ts FROM entries ".
				"INNER JOIN entries_types ON entries_types.id = entries.entries_types_id ".
				"INNER JOIN readers ON readers.user_id = '".$user."' ".
				"WHERE entries.readers_id = $reader_id  ".
				"AND entries.readers_id = readers.id ".
				"AND entries_types.name = 'Power' ".
				"AND UNIX_TIMESTAMP(entries.site_timestamp) >= ".$start->getTimestamp()." ".
				"AND UNIX_TIMESTAMP(entries.site_timestamp) <= ".$end->getTimestamp(), FALSE);
			$val = $this->db->get();
			$data = array();
			$sum = 0;
			$last = 0;
			$count = 0;
			$interval = (($max - $min) / 200 ) / 1000;
			$first = -1;
			foreach($val->result() as $row) {
				if($first == -1)
				{
					$first = $row->ts;
				}
				if($detail == 1)
				{
					$data[] = array( (int) $row->ts-$first, (int) $row->power);	
				}else
				{
					$sum = $sum + $row->power;
					$count++;

					if($last + $interval <= $row->ts)
					{
									$data[] = array( (int) $row->ts-$first, (int)round($sum / $count,0) );
									$count = 0;
									$sum = 0;
									$last = $row->ts;
					}	
				}
				
					
				
			}
			$this->db->stop_cache();
			echo  "{ 'name': 'Power', 'first':".$first.",'values': ".json_encode($data)."}";
       	}
	}
?>

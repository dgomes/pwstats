<?php
class Imeterbox extends Controller {

	function Imeterbox()
	{
		parent::Controller();

	}

	function index() {
		echo "ISA iMeterBor plugin";
	}

	function read($readers_id) {
		$reader = $this->db->get_where('readers', array('id' => $readers_id))->result();
		$data = $this->_parse_imeter($reader[0]->url);
		print_r($data);	
		$query = $this->db->get('value_types');
		$type = array();
		foreach ($query->result() as $row)
			$type[$row->name] = $row->id;

		foreach($data['measure'] as $val_type => $val) 
			if(array_key_exists($val_type,$type)) {
				$this->db->set('readers_id', (int) $readers_id);
				$this->db->set('value', $val);
				$this->db->set('value_types_id', $type[$val_type]);
				$this->db->set('unit_timestamp', $data['timestamp']);
				$this->db->insert('values');
			}
	}

	function _parse_imeter($uri) {
		$dom = new DOMDocument;
		$dom->loadHTMLFile($uri);
		$s = simplexml_import_dom($dom);

		$measure = $s->body->div->div[2]->table[1]->tbody->tr[2]->td[2];
		$measure = explode("\n",$measure);
		array_walk($measure, function(&$s, &$key) {
			$s = trim($s);
			$t = explode(" ", $s);
			$s = $t[0];
		});
		$measure['Energy'] = $measure[1];
		$measure['Power'] = $measure[2];
		$measure['Current'] = $measure[3];
		$measure['Voltage'] = $measure[4];

		$time = $s->body->div->div[2]->table[1]->tbody->tr[3]->td[1];
		$timestamp = str_replace(":","*",$time); //in accordance with http://dev.mysql.com/doc/refman/5.0/en/datetime.html formats '98/12/31 11*30*45'

		return array('measure' => $measure, 'timestamp' => $timestamp); 
	}

}
?>
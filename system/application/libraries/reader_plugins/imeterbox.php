<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Imeterbox {

	function index() {
		echo "ISA iMeterBor plugin";
	}

	function read($readers_id) {
		$reader = $db->get_where('readers', array('id' => $readers_id))->result();
		$data = $this->_parse_imeter($reader[0]->url);
		$query = $db->get('entries_types');
		$type = array();
		foreach ($query->result() as $row)
			$type[$row->name] = $row->id;

		foreach($data['measure'] as $val_type => $val)
			if(array_key_exists($val_type,$type)) {
				$db->set('readers_id', (int) $readers_id);
				$db->set('entry', $val);
				$db->set('entries_types_id', $type[$val_type]);
				$db->set('unit_timestamp', $data['timestamp']);
				$db->insert('entries');
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

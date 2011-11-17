<?php
class CurrentCostENVI extends Controller {

    function CurrentCostENVI()
    {
        parent::Controller();
        $this->load->helper('url');
    }

    function insert($reader_id, $key, $timestamp, $type, $measure)
    {
        $query = $this->db->query("SELECT r.id FROM readers r " .
                                  "INNER JOIN users u ON u.id = r.user_id " .
                                  "WHERE r.id = ? AND r.key = ?", array($reader_id, $key));
        $n = -1;
        if ($query->num_rows() > 0)
        {
            $queryt = $this->db->get('entries_types');
            $types = array();
            foreach ($queryt->result() as $row)
                $types[$row->name] = $row->id;

            if (array_key_exists($type, $types))
            {
                $this->db->set('readers_id', (int) $reader_id);
                $this->db->set('entry', (int) $measure);
                $this->db->set('entries_types_id', $types[$type]);
                $this->db->set('unit_timestamp', date('Y-m-d H:i:s', $timestamp));
                $this->db->insert('entries');

                $n = $this->db->affected_rows();
            }
        }
        echo $n;
    }
}
?>

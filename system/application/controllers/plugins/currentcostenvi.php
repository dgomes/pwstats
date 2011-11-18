<?php
class CurrentCostENVI extends CI_Controller {

    function __construct()
    {
        parent::__construct();

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
                $query = $this->db->query("INSERT INTO entries (readers_id, entry, entries_types_id, unit_timestamp) " .
                                            "VALUES (?, ?, ?, FROM_UNIXTIME(?))", array($reader_id, $measure, $types[$type], $timestamp));

                $n = $this->db->affected_rows();
            }
        }
        echo $n;
    }
}
?>

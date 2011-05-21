<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cron extends CI_Controller {

                private $plugins = null;

                function Cron()
                {
                        parent::Controller();
                        $this->load->helper('url');
                }

                function index()
                {
                        $this->db->select('id, models_id');
                        $readers = $this->db->get('readers');

                        foreach($readers->result() as $row) {
                                $this->db->select('plugin');
                                $model = $this->db->get_where('models',array('id' => $row->models_id))->result();
                                $rp_name = "RP_".$model[0]->plugin;

                                if(!isset($plugins[$rp_name]))
                                {
                                        $this->load->library("reader_plugins/".$model[0]->plugin, NULL,$rp_name);
                                        $plugins[$rp_name]=$this->{$rp_name};
                                }

                                $plugins[$rp_name]->read($this->db,$row->id);

                        }
       }
}

?>

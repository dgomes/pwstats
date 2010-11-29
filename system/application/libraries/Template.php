<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Template {
		var $template_data = array();
		
		function set($name, $value)
		{
			$this->template_data[$name] = $value;
		}
	
		function load($name, $view = '' , $view_data = array())
		{               
			$this->CI =& get_instance();
			$this->set($name, $this->CI->load->view($view, $view_data, TRUE));			
		}
			
		function render(){
			$this->CI =& get_instance();
			return $this->CI->load->view('template', $this->template_data);
		}
}

/* End of file Template.php */
/* Location: ./system/application/libraries/Template.php */

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Layout {

	protected $CI;

    protected $template = 'layout';

	function __construct($type = '')
    {
        $this->CI =& get_instance();

        
    }

    public function view($file_name, $data, $flag = FALSE)
    {
    

        $this->CI->data['content'] = $this->CI->load->view($file_name, $this->CI->data, TRUE);

        if( $flag )
            return $this->CI->load->view($this->template, $this->CI->data, $flag);
        else
            $this->CI->load->view($this->template, $this->CI->data);         

    }
    
    
}

/* End of file Layout.php */
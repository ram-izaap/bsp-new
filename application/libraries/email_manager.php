<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Email_manager
{
	private $_CI;
	private $_cc = array();
	private $_bcc = array();

	public function __construct($options = array())
	{
		$this->_CI = & get_instance();
		$this->_CI->error_message = '';

		foreach ($options as $key => $value) 
		{
			$key = "_{$key}";
			if (isset($this->$key))
				$this->$key = $value;	
		}
		
	}
	
	public function initialize($params = array())
	{
		if(!count($params))
			return FALSE;
	
		foreach ($params as $key => $val)
		{
			$key = "_{$key}";
			if (isset($this->$key))
				$this->$key = $val;
		}
	
	}
	
	public function send_email($to, $toname, $from, $from_name, $subject, $message, $cc = array(),$attachments = array())
	{
		$this->_CI->config->load('email_config');
	
		$this->_CI->load->library('email', $this->_CI->config->item('email'));

		$this->_CI->email->clear(TRUE);
		
		$this->_CI->email->set_newline("\r\n");
	
		$this->_CI->email->from($from,$from_name);
		$this->_CI->email->to($to);
		$this->_CI->email->cc( array_merge($cc, $this->_cc) );
		$this->_CI->email->bcc($this->_bcc);

		$this->_CI->email->subject($subject);
		$this->_CI->email->message($message);
		foreach ($attachments as $file)
			$this->_CI->email->attach($file);
		
		if ( ! $this->_CI->email->Send())
			return FALSE;
		
		return TRUE;
	}
	
	function send_order_confirmation($order_id = '',$data)
	{
		//send email to user.
		
		$message = "Hi ".$data['name']."<br/><br/>";

		$message .= "Your order has been craeted sucessfully!<br/>";

		$message .= "click <a href='".base_url('order/confirmation/'.$order_id)."'>here</a> to view your order details<br/><br/>";

		$message .= "Regards<br/>Claras Online";
		
		$to_emails = $data['buyer_email'].",saravanan@izaaptech.in";
	
		if(!$this->send_email($to_emails, $data['name'], 'saravanan@izaaptech.in', 'Claras Online', "Claras Online - Order#{$order_id}", $message))
		{
			$this->_CI->error_message = "Email sending is failed.";
			return FALSE;
		}
			
		return TRUE;
	}
	

}
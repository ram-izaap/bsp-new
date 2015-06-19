<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

	public $data = array();
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');

	}
	public function index()
	{
		$this->form_validation->set_rules($this->get_rules());

		$this->data['message']='';

		if($this->form_validation->run())
        {
        	$form = $this->input->post();

        	$user = $this->user_model->get_where(array('username'=>$form['username'],'password'=>md5($form['password'])))->row_array();
        
        	if(count($user) > 1){

        		$this->session->set_userdata('userdata',$user);
        		redirect('inventory_upload');
        	}
        	else
        	{
        		$this->data['message'] = "Invalid user or password!";
        	}
        }

        $this->layout->view('login', $this->data);
	}

	function logout(){

		$this->session->unset_userdata('userdata');
		
		redirect('login');
	}

	function get_rules()
	{
    $rules = array(
                array('field' => 'username', 'label' => 'UserName', 'rules' => 'trim|required'),
                array('field' => 'password', 'label' => 'Password', 'rules' => 'trim|required'),
               
            );

    return $rules;
	}	

	
}

/* End of file order.php */
/* Location: ./application/controllers/order.php */
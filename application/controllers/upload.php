<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Upload extends CI_Controller {

	public $data = array();
	protected $required_fields = array('style','description','category','color','size','price','minimum','clearance','essential');

	public function __construct()
	{
		parent::__construct();
		$this->load->model('inventory_model');

	}
	public function index()
	{
		$userdata = $this->session->userdata('userdata');
  		if(!is_array($userdata) || !isset($userdata['username']))
  			redirect('login');
  		
		$errors = array();
		$success_uploads = 0;
		$upload_error = '';


		$config['upload_path'] = './uploads/';
		$config['allowed_types'] = 'csv';
		$config['max_size']	= '2000000000';

		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload('csv-file'))
		{
			$upload_error = $this->upload->display_errors();
		}
		else
		{

			$result = $this->inventory_model->get_options( );
			$options = array();
			foreach ($result as $row) 
			{
				$tmp = strtolower($row['value']);
				$options[$row['attribute_id']][$tmp] = $row['id']; 
			}

			$result = $this->inventory_model->get_categories( );
			$cats_id = array();
			$cats_name = array();
			foreach ($result as $row) 
			{
				$tmp = strtolower($row['name']);
				$cats_name[$row['id']] = $row['name']; 
				$cats_id[$tmp] = $row['id'];
			}

			$upload_data = $this->upload->data();
			//print_r($this->upload->data());die;
			//$tmpName = $_FILES['csv-file']['tmp_name'];
			$tmpName = $config['upload_path'].$upload_data['file_name']; 
			//die($tmpName);
			if(($handle = fopen($tmpName, 'r')) !== FALSE) 
	        	$row=1;
		        while(($data = fgetcsv($handle, 1000, ',')) !== FALSE) 
            {
	            // necessary if a large csv file
	        	//echo "<pre>";
		        {

			        if($row==1)
			        {

		               $field_errors='';

		               foreach($data as $key => $col){

		               		if(!in_array(strtolower($col),$this->required_fields))
		               			$field_errors .= "The ".$col." field is missing<br/>";
		               }

		               if(!empty($field_errors)){
		               	   break;
		               }
		               
		           	}
		           	else
		           	{

			           	$data = array_combine($this->required_fields, array_values($data));
			            
			            //echo '<pre>';print_r($data);die;
			            $colors = explode(';', $data['color']);
			            $sizes 	= explode(';', $data['size']);

			            $category_name = strtolower(trim($data['category']));

		            	if( array_key_exists($category_name, $cats_id) === FALSE )
		            	{
		            		$errors[] = "<b>{$data['style']}:</b> The Category \"$category_name\" is missing in Category list.";
		            		continue;
		            	}

		            	$cat_id = $cats_id[$category_name];


		            	$p_insert = array();
	            		$p_insert['type'] = 'configurable';
		            	$p_insert['parent_id'] = 0;
		            	$p_insert['combination'] = "";
		            	$p_insert['sku'] = $data['style'];
		            	$p_insert['category_id'] = $cat_id;
		            	$p_insert['description'] = $data['description'];
		            	$p_insert['min_qty'] = $data['minimum'];
		            	$p_insert['price'] = (float)$data['price'];
		            	$p_insert['qty'] = 1000;
		            	$p_insert['clearance'] = $data['clearance'];
		            	$p_insert['essential'] = $data['essential'];

		            	//echo '<pre>';print_r($p_insert);die;
		            	//get parent product if exists
		            	$pp = $this->inventory_model->get_where( array('sku' => $data['style']) )->row_array();
		            	$parent_id = 0;
		            	if( !count($pp) )
		            	{
			            	$parent_id = $this->inventory_model->insert( $p_insert );
		            	}
		            	else
		            	{
		            		$parent_id = $pp['id'];
		            		$this->inventory_model->update( array('id' => $parent_id), $p_insert );
		            	}

		            	$child_ids = array();
			            foreach ($colors as $color) 
			            {
			            	$color = strtolower(trim($color));
			            	if( array_key_exists($color, $options[1]) === FALSE )
			            	{
			            		$errors[] = "<b>{$data['style']}:</b> The Color \"$color\" is missing in attribute list.";
			            		continue;
			            	}

			            	

			            	foreach ($sizes as $size) 
				            {
				            	$size = strtolower(trim($size));
				            	
				            	if( array_key_exists($size, $options[2]) === FALSE )
				            	{
				            		$errors[] = "<b>{$data['style']}:</b> The Size \"$size\" is missing in attribute list.";
				            		continue;
				            	}

				            	$color_id = $options[1][$color];
				            	$size_id = $options[2][$size];						            	

				            	$insert  = array();
				            	$insert['type'] = 'simple';
				            	$insert['parent_id'] = $parent_id;
				            	$insert['combination'] = "$color_id,$size_id";
				            	$insert['sku'] = "{$data['style']}-$color-$size";
				            	$insert['category_id'] = $cat_id;
				            	$insert['description'] = $data['description'];
				            	$insert['min_qty'] = $data['minimum'];
				            	$insert['price'] = (float)$data['price'];
				            	$insert['qty'] = 1000;
				            	$insert['clearance'] = $data['clearance'];
				            	$insert['essential'] = $data['essential'];

				            	$variant = $this->inventory_model->get_where( array('sku' => $insert['sku']) )->row_array();
				            	if( count($variant) )
				            	{
				            		$pid = $variant['id'];
				            		$this->inventory_model->update( array('id' => $pid), $insert );
				            	}
				            	else
				            	{
				            		$pid = $this->inventory_model->insert( $insert );
				            	}
				            	

				            	$child_ids[] = $pid;

				            	$success_uploads++;
				            }
			            }


			            //update missing child's qty as 0 ( oos )
			            $this->inventory_model->disable_products( $parent_id, $child_ids );
		        	}

		            $row++;
	            
	            }

	            //echo '<pre>';
			    //print_r($errors);die;

	            
	        }

	        fclose($handle);
		}

		$this->data['upload_error'] = $upload_error;
		$this->data['errors'] = $errors;
		$this->data['success_uploads'] = $success_uploads;
		$this->layout->view('upload', $this->data);
	}

	
}

/* End of file order.php */
/* Location: ./application/controllers/order.php */
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inventory extends CI_Controller {

	public $data = array();

	public function __construct()
	{
		parent::__construct();
		$this->load->model('inventory_model');

	}
	public function index()
	{
		

		$order_links_id = '';
		$products_valid = '';

		$this->form_validation->set_rules($this->get_rules());

		if($this->form_validation->run())
        {
            $form = $this->input->post();

            if(isset($_POST['categories']) || isset($_POST['products']) || isset($_POST['essential']) || isset($_POST['clearance']))
			{
		    	$validatedData = $form;

		    	if(isset($_POST['categories']))
		    	{
		    		$validatedData['categories'] = $_POST['categories'];
		    	}
		    	else
		    	{
		    		$validatedData['categories'] = array();
		    	}

		    	if(isset($_POST['products']))
		    	{
		    		$validatedData['products'] = $_POST['products'];	
		    	}
		    	else
		    	{
		    		$validatedData['products'] = array();	
		    	}

		    	if(isset($_POST['essential']))
		    		$validatedData['essential'] = $_POST['essential'];

		    	if(isset($_POST['clearance']))
		    		$validatedData['clearance'] = $_POST['clearance'];				

		    	//echo '<pre>';print_r($validatedData);die;

		    	$order_links_id = $this->inventory_model->insert( array('order_data' => json_encode($validatedData)), 'order_links' );
		    }
		    else
		    {
		    	$products_valid = "Please Select atleast one product or category";
		    }
        }

		$result = $this->inventory_model->get_options();
		$options = array();
		foreach ($result as $row) 
		{
			$tmp = strtolower($row['value']);
			$options[$row['attribute_id']][$tmp] = $row['id']; 
		}

		$colors = array_values($options[1]);

		$cat_products=array();
		$categories = array();
		$prods = $this->inventory_model->get_products_by_category();
		//echo '<pre>';print_r($prods);die;
		$pc = array();
		foreach ($prods as $row) 
		{
			if( !$row['parent_id'] )
				continue;

			$combinations = explode(',', $row['combination']);

			if( isset($pc[$row['parent_id']]) && in_array($combinations[0], $pc[$row['parent_id']]))
				continue;

			$pc[$row['parent_id']][] = $combinations[0];


			$tmp = explode('-', $row['sku']);
			array_pop( $tmp ); 
			
			$row['sku'] = implode('-', $tmp);

			$cat_products[$row['category_id']][]=$row;
			$categories[$row['category_id']] = $row['category_name'];
		}

		

		$this->data['products'] = $cat_products;
		$this->data['categories'] = $categories;
		$this->data['product_validate'] = $products_valid;
		$this->data['order_links_id'] = $order_links_id;

		//echo '<pre>';print_r($options);
		$this->layout->view('index', $this->data);
	}


	function order( $id = 0 )
	{
		if( !$id )
			redirect();

		$orderlinks_data = $this->inventory_model->get_where(array('id' => $id), '*', 'order_links')->row_array();
		$orderlinks_data = json_decode($orderlinks_data['order_data'], TRUE);

		//get options aaray
		$result = $this->inventory_model->get_options( );
		$options = array();
		foreach ($result as $row) 
		{
			$options[$row['attribute_id']][$row['id']] = $row['value']; 
		}

		$category = is_array($orderlinks_data['categories'])?$orderlinks_data['categories']:array();
		$products = is_array($orderlinks_data['products'])?$orderlinks_data['products']:array();
		$all_clearance = isset($orderlinks_data['clearance'])?$orderlinks_data['clearance']:'';
		$all_essential = isset($orderlinks_data['essential'])?$orderlinks_data['essential']:'';

		$temp = array();
		foreach ($products as $cat_id => $pids) 
		{
			if( in_array($cat_id, $category) === FALSE)
			{
				$temp = array_merge($temp, $pids);
			}
		}
		$products = $temp;

		$result = $this->inventory_model->get_orderd_products( $products, $category, $all_clearance, $all_essential );

		$pdetails 		= array();
		$parent_details = array();
		$close_outs 	= array();
		$prices 		=  array();

		foreach ($result as $l=>$row) 
		{
			
			$parent_id = $row['parent_id'];
			
			if( !$parent_id )
			{
				if( $row['clearance'] == 'Y' )
				{
					$close_outs[$row['id']] = $row;
				}
				else
				{
					$parent_details[$row['id']] = $row;
				}
				
				continue;
			}

			if( !$row['qty'] )
				continue;

			$combinations = explode(',', $row['combination']);
			
			$color_id 	= current($combinations);
			$size_id 	= next($combinations);
			
			$row['size_id'] = $size_id;

			$pdetails[$parent_id][$color_id][$size_id] = $row;

			$prices[$row['id']] = $row['price'];
			
		}

		if($this->input->is_ajax())
		{
			$this->form_validation->set_rules($this->get_rules());

			$output = array('status' => 'success');
			
			if( $this->form_validation->run() )
			{
				$output['status'] = 'success';

				$data = $this->input->post();
				$ordered_products = $data['product'];
				unset($data['product']);

				//update order links data
				$data['categories'] = $orderlinks_data['categories'];
				$data['products'] 	= $orderlinks_data['products'];
				$data['clearance'] 	= $all_clearance;
				$data['essential'] 	= $all_essential;
				$this->inventory_model->update( array('id' => $id), array('order_data' => json_encode($data)), 'order_links' );

				$ordered_product_prices = array();
				$temp = array();
				foreach ($ordered_products as $parent_id => $childs)
				{	
					$ec = 0;
					foreach ($childs as $child_id => $qty) 
					{
						if((int)$qty == 0)
							$ec++;

						$ordered_product_prices[$child_id] = $prices[$child_id];
					}

					if($ec == count($childs))
						unset($ordered_products[$parent_id]);
				}

				$cart_data = array();
				$cart_data['products'] = $ordered_products;
				$cart_data['prices'] = $ordered_product_prices;
									
				$order_id = $this->inventory_model->insert( array('order_link_id' => $id ,'cart_data' => json_encode($cart_data)), 'order' );
				
				$output['order_id'] = $order_id;

				//$this->load->library('email_manager');

				//$this->email_manager->send_order_confirmation($order_id,$orderlinks_data);

				$this->send_mail($order_id,$orderlinks_data);

			}
			else
			{
				$fields = array('name', 'ship_to', 'bill_to', 'notes', 'start_date', 'end_date', 'executive', 'buyer_email');

				$m = [];
				foreach ($fields as $field) 
				{
					if( form_error($field) )
						$m[$field] = array('error' => form_error($field, '<span class="error">', '</span>'));
				}
				
				$output['status'] = 'error';
				$output['errors'] = $m;
			}

			echo json_encode($output);die;
		}

		$this->data['id'] = $id;
		$this->data['order_data'] = $orderlinks_data;
		$this->data['parent_details'] = $parent_details;
		$this->data['pdetails'] = $pdetails;
		$this->data['close_outs'] = $close_outs;
		$this->data['options'] = $options;

		$this->layout->view('order', $this->data);
	}

	function confirmation( $order_id = 0, $download = false )
	{
		if( !$download && !$order_id )
			redirect();

		if( $download && !$order_id )
			return '';

		$order_details = $this->inventory_model->get_where( array('id' => $order_id), '*', 'order' )->row_array();

		$cart_data = json_decode($order_details['cart_data'], TRUE);
		//echo '<pre>';print_r($cart_data);
		$order_link_id = $order_details['order_link_id'];

		//get orderlinks data
		$orderlinks_data = $this->inventory_model->get_where( array('id' => $order_link_id), '*', 'order_links' )->row_array();
		$orderlinks_data = json_decode($orderlinks_data['order_data'], TRUE);


		//get options aaray
		$result = $this->inventory_model->get_options( );
		$options = array();
		foreach ($result as $row) 
		{
			$options[$row['attribute_id']][$row['id']] = $row['value']; 
		}


		$category = is_array($orderlinks_data['categories'])?$orderlinks_data['categories']:array();
		$products = is_array($orderlinks_data['products'])?$orderlinks_data['products']:array();
		$all_clearance = isset($orderlinks_data['clearance'])?$orderlinks_data['clearance']:'';
		$all_essential = isset($orderlinks_data['essential'])?$orderlinks_data['essential']:'';

		$temp = array();
		foreach ($products as $cat_id => $pids) 
		{
			if( in_array($cat_id, $category) === FALSE)
			{
				$temp = array_merge($temp, $pids);
			}
		}
		$products = $temp;

		$result = $this->inventory_model->get_orderd_products( $products, $category, $all_clearance, $all_essential);

		$pdetails 		= array();
		$parent_details = array();
		
		$ordered_parent_products = array_keys( $cart_data['products'] );
		
		$order_total = 0;
		foreach ($result as $row) 
		{
			
			
			$parent_id = $row['parent_id'];
			
			if( !$parent_id )
			{
				if( in_array($row['id'], $ordered_parent_products) )
				{
					$row['order_qty'] = array_sum( $cart_data['products'][$row['id']] );
					$row['sub_total'] = $row['order_qty']*$row['price'];
					$order_total += $row['sub_total'];
					$parent_details[$row['id']] = $row;
				}
				
				continue;
			}


			$combinations = explode(',', $row['combination']);
			
			$color_id 	= current($combinations);
			$size_id 	= next($combinations);
			
			$row['size_id'] = $size_id;

			$pdetails[$parent_id][$color_id][$size_id] = $row;
			
		}


		
        $this->data['order_id'] = $order_id;
        $this->data['order_data'] = (object)$orderlinks_data;
        $this->data['parent_details'] = $parent_details;
        $this->data['pdetails'] = $pdetails;
        $this->data['options'] = $options;
        $this->data['cart_data'] = $cart_data;
        $this->data['order_total'] = $order_total;

        if(!$download)
        {
        	$this->layout->view('confirmation', $this->data);
        }
        else
        {
        	return $this->load->view('pdf', $this->data, TRUE);
        }
        

	}

	function get_rules()
    {
        $rules = array(
                    array('field' => 'name', 'label' => 'Company Name', 'rules' => 'trim|required'),
                    array('field' => 'ship_to', 'label' => 'Ship To', 'rules' => 'trim|required'),
                    array('field' => 'bill_to', 'label' => 'Bill To', 'rules' => 'trim|required'),
                    array('field' => 'notes', 'label' => 'Notes', 'rules' => 'trim|required'),
                    array('field' => 'start_date', 'label' => 'Start Date', 'rules' => 'trim|required'),
                    array('field' => 'end_date', 'label' => 'Completion Date', 'rules' => 'trim|required'),
                    array('field' => 'po', 'label' => 'PO', 'rules' => 'trim'),
                    array('field' => 'terms', 'label' => 'Terms', 'rules' => 'trim'),
                    array('field' => 'buyer_email', 'label' => 'Buyer Email', 'rules' => 'trim|required'),
                    array('field' => 'executive', 'label' => 'Executive', 'rules' => 'trim|required')
                );

        return $rules;
    }

    public function send_mail($order_id = '',$data = array())
    {
    	
    	//send email to user.
    	$stylesheet = file_get_contents(base_url('public/css/style.css'));        
		$content = $this->confirmation($order_id, TRUE);

		$this->load->library('pdf');
        $pdf = $this->pdf->load();
        $pdf_path = './uploads/'.'order-'.$order_id.'-'.time().'.pdf';
        
        $pdf->WriteHTML($stylesheet,1); 
        $pdf->WriteHTML($content); // write the HTML into the PDF
        $pdf->Output($pdf_path, 'F'); // save to file


        $this->load->library('email');

        $config['mailtype'] = 'html';
		$config['charset'] = 'iso-8859-1';
		$config['wordwrap'] = TRUE;

		$this->email->initialize($config);

        $subject = "Clara Sunwoo - Order#{$order_id}";

        $message = "Hi ".$data['name']."<br/><br/>";
		$message .= "Your order has been created sucessfully!<br/>";
		$message .= "click <a href='".base_url('confirmation/'.$order_id)."'>here</a> to view your order details<br/><br/>";
		$message .= "Regards<br/>Clara Sunwoo";

		$this->email->from('test@clarasonline.com', 'Test');
		$this->email->to($data['buyer_email'].',test@clarasonline.com'); 
		$this->email->reply_to('test@clarasonline.com', 'Test');
		//$this->email->cc('another@another-example.com'); 
		//$this->email->bcc('them@their-example.com'); 

		$this->email->subject($subject);
		$this->email->message($message);	
		$this->email->attach($pdf_path);

		$this->email->send();



		
    }

   
}

/* End of file order.php */
/* Location: ./application/controllers/order.php */
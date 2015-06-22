<?php
safe_include("models/app_model.php");
class Inventory_model extends App_model {
    
    
    function __construct()
    {
        parent::__construct();
        $this->_table = 'product';
    }

    public function get_options()
    {
    	$this->db->select('attribute_options.*, attribute.name as name');
        $this->db->from('attribute_options');
        $this->db->join('attribute', 'attribute_options.attribute_id = attribute.id');
        $result = $this->db->get();
        return $result->result_array();    	
    }

    function get_products_by_category()
    {
    	
    	$this->db->select('product.*, category.name as category_name');
        $this->db->from('product');
        $this->db->join('category', "product.category_id = category.id ");
        $result = $this->db->get();
        return $result->result_array(); 
    }

    function get_categories()
    {
    	
    	$this->db->select('*');
        $this->db->from('category');
        $result = $this->db->get();
        return $result->result_array(); 
    }

    function disable_products( $parent_id = 0, $ids = array() )
    {
    	if( !count($ids) || !$parent_id )
          return false;

        $ids = implode(',', $ids);
        $sql = "UPDATE  product set qty='0' where parent_id='$parent_id' and id NOT IN ($ids)";
        return $this->db->query($sql);
    }

    function get_product( $where = array() )
    {
    	$this->db->select('*');
        $this->db->from('product');
        $this->db->where($where);
        $this->db->get();
        echo $this->db->last_query();die;
    }

    function get_orderd_products( $pids = array(), $cat_ids = array(), $all_clearance = '', $all_essential ='' )
    {
    	if( !count($pids) && !count($cat_ids) && $all_clearance == '' && $all_essential =='' )
          return array();
        
        $app_str = "";

        if(count($cat_ids))
        {
          $cat_ids  = implode(',', $cat_ids);
          $app_str .= "category_id IN ($cat_ids) ";
        }

        if(count($pids))
        {
          $pids     = implode(',', $pids);
          $app_str .= $app_str != ''? " OR ":'';
          $app_str .= " id IN ($pids) ";
        }       
        

        //if( $all_clearance != '' )
        //{
            $app_str .= $app_str != ''? " OR ":'';
        	$app_str .= "  clearance='Y' ";
        //}

        if( $all_essential !='' )
        {
            $app_str .= $app_str != ''? " OR ":'';
        	$app_str .= "  essential='Y' ";
        }
        
        if($app_str != '' )
            $app_str = ' ('.$app_str.')';
        else
            $app_str ="1=1";



        $sql = "SELECT p.*,t.sku as parent_sku FROM (
                                SELECT parent_id,sku from product 
                                  WHERE $app_str 
                                    AND parent_id > 0 group by parent_id 
                              ) t 
                          JOIN product p ON(p.parent_id=t.parent_id OR p.id=t.parent_id) 
                          ORDER BY p.id DESC";

        return $this->db->query($sql)->result_array();
    }
    
}
?>
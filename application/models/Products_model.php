<?php class Products_model extends CI_Model{
    protected $table_name;
    protected $primary_key;
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'products';
        $this->primary_key= 'product_id';
    }

    function get($filter = array(),$search = "",$offcet="",$limit="",$select=""){
        $return_extra_fields = "";
        if(!empty($filter))
        {   
            if(isset($filter["in"])){
                $where_id = $filter["in"];
                unset($filter["in"]);
                $this->db->where_in(key($where_id),$where_id[key($where_id)]);
            }
           
            if(isset($filter["cart_user_id"])){
                $user_id = $filter["cart_user_id"];
                unset($filter["cart_user_id"]);
               
                $this->db->join("(Select sum(qty) as qty, product_id from cart where user_id = ".$user_id." group by product_id ) as cart_qty","cart_qty.product_id = products.product_id","left");
                $return_extra_fields .= ",ifnull(cart_qty.qty,0) as cart_qty";
            }
            
            if(isset($filter["or"])){
                $where_id = $filter["or"];
                unset($filter["or"]);
                $this->db->or_where($where_id);
            }
                  
            if(isset($filter["user_id"])){
                $this->db->join("order_items","order_items.product_id = products.product_id");
                $this->db->join("orders","order_items.order_id = orders.order_id");
                $this->db->where("orders.user_id",$filter["user_id"]);
                
                unset($filter["user_id"]);
            }
                
            $this->db->where($filter);
        }
          
        if($search != ""){
            $this->db->or_like(array($this->table_name.'.product_name_en'=>$search,
                                    $this->table_name.'.product_name_ar'=>$search,
                                    'categories.cat_name_en'=>$search,
                                    'categories.cat_name_ar'=>$search));
        }
        if($select == ""){
            $select = "{$this->table_name}.*";
        }
        $this->db->distinct();
		$this->db->select("$select,product_discounts.discount,product_discounts.discount_type,product_discounts.product_discount_id,categories.cat_name_en,categories.cat_name_ar {$return_extra_fields}");
        $this->db->where($this->table_name.".draft","0");
        $this->db->join("categories","categories.category_id = products.category_id");        
        //,ifnull(productoptions.options,0) as options $this->db->join("(Select count(product_option_id) as options, product_id from product_options group by product_id ) as productoptions","productoptions.product_id = products.product_id","left");
        $this->db->join("(select product_discounts.* from product_discounts where product_discounts.start_date <= '".date(MYSQL_DATE_FORMATE)."' and product_discounts.end_date >= '".date(MYSQL_DATE_FORMATE)."' and product_discounts.status = 1 and product_discounts.draft = 0 group by product_discounts.product_id ) as product_discounts","product_discounts.product_id = ".$this->table_name.".product_id","left");
        
      	$this->db->order_by($this->table_name.".".$this->primary_key." desc");
        if($offcet !="" && $limit != ""){
            $this->db->limit($limit,$offcet);
        }
        $q = $this->db->get($this->table_name);
        
        return $q->result();

    }
    
    function get_productoptions_by_id($id){
        
        $this->db->join("product_options","product_groups.product_group_id = product_maps.group_id");
        $this->db->where("product_maps.product_map_id",$id);
        $q = $this->db->get("product_maps");
        return $q->row();
    }
  
    function get_by_id($id,$filter=array()){
        $this->db->where($this->table_name.".draft","0");
        $this->db->join("product_discounts","product_discounts.product_id = ".$this->table_name.".product_id and product_discounts.start_date <= '".date(MYSQL_DATE_FORMATE)."' and product_discounts.end_date >= '".date(MYSQL_DATE_FORMATE)."' and product_discounts.status = 1 and product_discounts.draft = 0","left");
        if($id!="")
            $this->db->where($this->table_name.".".$this->primary_key,$id);
        
            $return_extra_fields = "";
            if(isset($filter["cart_user_id"])){
                $user_id = $filter["cart_user_id"];
                unset($filter["cart_user_id"]);
               
                $this->db->join("(Select sum(qty) as qty, product_id from cart where user_id = ".$user_id." group by product_id ) as cart_qty","cart_qty.product_id = products.product_id","left");
                $return_extra_fields .= ",ifnull(cart_qty.qty,0) as cart_qty";
            }
            
        $this->db->select("{$this->table_name}.*,product_discounts.discount,product_discounts.discount_type,product_discounts.product_discount_id,categories.cat_name_en,categories.cat_name_ar $return_extra_fields");
        $this->db->join("categories","categories.category_id = products.category_id");        
        
        $q = $this->db->get($this->table_name);
        return $q->row();
    }
  
  function get_home_products($userid){
        $return_extra_fields = "";
        if($userid!=0)
        {   
            $this->db->join("(Select sum(qty) as qty, product_id from cart where user_id = ".$userid." group by product_id ) as cart_qty","cart_qty.product_id = products.product_id","left");
            $return_extra_fields .= ",ifnull(cart_qty.qty,0) as cart_qty";
            
             $this->db->join("order_items","order_items.product_id = products.product_id","left");
             $this->db->join("orders","order_items.order_id = orders.order_id");
             $this->db->where("orders.user_id",$userid);             
        }
        
        $this->db->distinct();
		$this->db->select("{$this->table_name}.*,product_discounts.discount,product_discounts.discount_type,product_discounts.product_discount_id,categories.cat_name_en,categories.cat_name_ar {$return_extra_fields}");
        $this->db->join("categories","categories.category_id = products.category_id");        
        //,ifnull(productoptions.options,0) as options $this->db->join("(Select count(product_option_id) as options, product_id from product_options group by product_id ) as productoptions","productoptions.product_id = products.product_id","left");
        $this->db->join("(select product_discounts.* from product_discounts where product_discounts.start_date <= '".date(MYSQL_DATE_FORMATE)."' and product_discounts.end_date >= '".date(MYSQL_DATE_FORMATE)."' and product_discounts.status = 1 and product_discounts.draft = 0 group by product_discounts.product_id ) as product_discounts","product_discounts.product_id = ".$this->table_name.".product_id","left");
        
        $this->db->where($this->table_name.".draft","0");
       
      	$this->db->order_by($this->table_name.".".$this->primary_key." desc");
        
        $this->db->limit(20,0);
        
        $q = $this->db->get($this->table_name);
        
        return $q->result();

    }
}
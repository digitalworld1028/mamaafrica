<?php class Orders_model extends CI_Model{
    protected $table_name;
    protected $primary_key;
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'orders';
        $this->primary_key= 'order_id';
    }
	
    function get($filter = array(),$search = "",$offcet="",$limit=""){
        if(!empty($filter))
        {
            if(isset($filter["in"])){
                $where_id = $filter["in"];
                unset($filter["in"]);
                $this->db->where_in(key($where_id),$where_id[key($where_id)]);
            }
            $this->db->where($filter);
        }
        if($search != ""){
            $this->db->or_like(array($this->table_name.'.order_no'=>$search));
        }
		$this->db->select($this->table_name.".*,oi.total_qty,users.user_firstname,users.user_lastname,users.user_phone,users.user_email,branches.branch_name_en,branches.branch_name_ar,branches.opening_time,branches.closing_time,
        user_address.address_line1,user_address.address_line2,user_address.city,user_address.latitude, user_address.longitude, delivery_boy.boy_name,delivery_boy.boy_phone,delivery_boy.boy_photo");
        $this->db->join("users","users.user_id = ".$this->table_name.".user_id");
        $this->db->join("user_address","orders.user_address_id = user_address.user_address_id","left");       
        $this->db->join("branches","branches.branch_id = orders.branch_id","left");  
        $this->db->join("delivery_boy","delivery_boy.delivery_boy_id  = orders.delivery_boy_id ","left");
        $this->db->join("(select sum(order_qty) as total_qty,order_id from order_items group by order_id ) as oi","oi.order_id = orders.order_id","left");
        $this->db->where($this->table_name.".draft","0");
		$this->db->order_by($this->table_name.".".$this->primary_key." desc");
        if($offcet !="" && $limit != ""){
            $this->db->limit($limit,$offcet);
        }
        $q = $this->db->get($this->table_name);
        return $q->result();

    }

    function get_by_id($id,$payment_ref=""){
        $this->db->select($this->table_name.".*,users.user_firstname,users.user_lastname,users.user_phone,users.user_email,branches.branch_name_en,branches.branch_name_ar,branches.opening_time,branches.closing_time,
        user_address.address_line1,user_address.address_line2,user_address.city,user_address.latitude, user_address.longitude, delivery_boy.boy_name,delivery_boy.boy_phone,delivery_boy.boy_photo
        ");
        if($payment_ref != ""){
            $this->db->where($this->table_name.".payment_ref",$payment_ref);
        }else{
            $this->db->where($this->table_name.".".$this->primary_key,$id);
        }
        $this->db->join("delivery_boy","delivery_boy.delivery_boy_id  = orders.delivery_boy_id ","left");
        
        $this->db->join("users","users.user_id = ".$this->table_name.".user_id");
        $this->db->join("user_address","orders.user_address_id = user_address.user_address_id","left");       
        $this->db->join("branches","branches.branch_id = orders.branch_id","left");  
        $q = $this->db->get($this->table_name);
        return $q->row();
    }
    function get_order_items($order_id){
        $this->db->select("order_items.*,products.product_image ,products.product_name_en,products.product_name_ar,products.calories,products.is_veg");
        $this->db->join("products","products.product_id = order_items.product_id");
        $this->db->where("order_id",$order_id);
        $q = $this->db->get("order_items");
        $result = $q->result();
        foreach($result as $res){
            $res->product_options = $this->get_order_option_items($res->order_item_id);
        }
        return $result;
    }
    
    function get_order_option_items($order_item_id){
        $this->db->select("order_item_options.*,product_options.option_name_en,product_options.option_name_ar");      
        $this->db->join("product_options","product_options.product_option_id = order_item_options.product_option_id");
        
        $this->db->where("order_item_id",$order_item_id);
        $q = $this->db->get("order_item_options");
        return $q->result();
    }
    
    function order_counts_with_status(){
        $this->db->select("count(order_id) as order_total,status, sum(net_amount) as total_net_amount");
        $this->db->where("draft",0);
        $this->db->group_by("status");
        $q = $this->db->get("orders");
        return $q->result();
    }
    function get_status($order_id){
        $this->db->where(array("order_id"=>$order_id));
        $q = $this->db->get("order_status");
        return $q->result();
    }
}

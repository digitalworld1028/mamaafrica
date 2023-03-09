<?php class Productoptions_model extends CI_Model{
    protected $table_name;
    protected $primary_key;
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'product_options';
        $this->primary_key= 'product_option_id';
    }
	
    function get($filter = array(),$search = "",$offcet="",$limit=""){
        $return_extra_fields = "";
        if(!empty($filter))
        {
            if(isset($filter["cart_user_id"])){
                $user_id = $filter["cart_user_id"];
                unset($filter["cart_user_id"]);
               
                $this->db->join("(Select sum(qty) as qty, product_option_id from cart_option where user_id = ".$user_id." group by product_option_id ) as cart_qty","cart_qty.product_option_id = product_options.product_option_id","left");
                $return_extra_fields .= ",ifnull(cart_qty.qty,0) as cart_qty";
            }

            $this->db->where($filter);
        }
        if($search != ""){
            $this->db->or_like(array($this->table_name.'.option_name_en'=>$search,
                                    $this->table_name.'.option_name_ar'=>$search));
        }
		$this->db->select("{$this->table_name}.* $return_extra_fields");      
        $this->db->where($this->table_name.".draft","0");
		$this->db->order_by($this->table_name.".".$this->primary_key." desc");
        if($offcet !="" && $limit != ""){
            $this->db->limit($limit,$offcet);
        }
        $q = $this->db->get($this->table_name);
        return $q->result();

    }

    function get_by_id($id){
        $this->db->where($this->table_name.".".$this->primary_key,$id);
        $q = $this->db->get($this->table_name);
        return $q->row();
    }
}

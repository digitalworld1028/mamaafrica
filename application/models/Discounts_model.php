<?php class Discounts_model extends CI_Model{
    protected $table_name;
    protected $primary_key;
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'product_discounts';
        $this->primary_key= 'product_discount_id';
    }

    function get($filter = array(),$search = "",$offcet="",$limit=""){
        if(!empty($filter))
        {
            $this->db->where($filter);
        }
        if($search != ""){
            $this->db->or_like(array('products.product_name_en'=>$search,
                                    'products.product_name_ar'=>$search));
        }
		$this->db->select("{$this->table_name}.*,products.product_name_ar,products.product_name_en");
        $this->db->join("products","products.product_id = product_discounts.product_id and products.draft = 0");
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
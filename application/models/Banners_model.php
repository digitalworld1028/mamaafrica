<?php class Banners_model extends CI_Model{
    protected $table_name;
    protected $primary_key;
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'banners';
        $this->primary_key= 'banner_id';
    }

    function get($filter = array(),$search = "",$offcet="",$limit=""){
       
        if($search != ""){
            $this->db->or_like(array($this->table_name.'.banner_title_en'=>$search,
                                    $this->table_name.'.banner_title_ar'=>$search));
        }
		$this->db->select("{$this->table_name}.*,products.product_name_en,products.product_name_ar");
        $this->db->where($this->table_name.".draft","0");
        $this->db->join("products","products.product_id = banners.product_id","left");
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

<?php class Contactus_model extends CI_Model{
    protected $table_name;
    protected $primary_key;
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'contact_request';
        $this->primary_key= 'contact_id';
    }

    function get($filter = array(),$search = "",$offcet="",$limit=""){
       
        if($search != ""){
            $this->db->or_like(array($this->table_name.'.fullname'=>$search,
                                    $this->table_name.'.phone'=>$search));
        }
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

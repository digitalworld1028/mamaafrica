<?php class Branches_model extends CI_Model{
    protected $table_name;
    protected $primary_key;
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'branches';
        $this->primary_key= 'branch_id';
    }
	 
    function get($filter = array(),$search = "",$offcet="",$limit=""){
        if(!empty($filter))
        {
            $this->db->where($filter);
        }
        if($search != ""){
            $this->db->or_like(array($this->table_name.'.branch_name_en'=>$search,
                                    $this->table_name.'.branch_name_ar'=>$search));
        }
		$this->db->select("{$this->table_name}.*");
        $this->db->where($this->table_name.".draft","0");		
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

<?php class User_model extends CI_Model{
    protected $table_name;
    protected $primary_key;
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'users';
        $this->primary_key= 'user_id';
    }
	
    function get($filter = array(),$search = "",$offcet="",$limit=""){
        if(!empty($filter))
        {   
            if(isset($filter["in"])){
                $where_id = $filter["in"];
                unset($filter["in"]);
                $val = $where_id[key($where_id)];
                if(!is_array($where_id[key($where_id)])){
                    $val = explode(",",$val);
                }
                $this->db->where_in(key($where_id),$val);
            }
            $this->db->where($filter);
        }
        if($search != ""){
            $this->db->or_like(array($this->table_name.'.user_firstname'=>$search,
                                    $this->table_name.'.user_last'=>$search,
                                    $this->table_name.'.user_email'=>$search,
                                    $this->table_name.'.user_phone'=>$search,
                                    $this->table_name.'.user_company_name'=>$search,
                                    $this->table_name.'.user_company_id'=>$search));
        }
		$this->db->select("{$this->table_name}.*");
        $this->db->where($this->table_name.".draft","0");
        $this->db->order_by($this->table_name.".".$this->primary_key." desc");
        if($offcet !="" && $limit != ""){
            $this->db->limit($limit,$offcet);
        }
        $q = $this->db->get($this->table_name);
        return $q->result();

    }
    function check_match_password($old_pass,$userid)
	{	
		$q=$this->db->get_where('users',array('user_id'=>$userid,'user_password'=>$old_pass));
		if($q->num_rows()>0)
		{
			return 1;
		}
		else
		{
			return 0;
		}			
	}
    
    function get_by_id($id){
        $this->db->where($this->primary_key,$id);
        $q = $this->db->get($this->table_name);
        return $q->row();
    }
    
    function get_address($user_id){
        $this->db->where("user_address.user_id",$user_id);
        $this->db->where("user_address.draft","0");
        $q = $this->db->get("user_address");
        return $q->result();
    }
    
    function get_settings($user_id){
        $this->db->where("user_settings.user_id",$user_id);
        $q = $this->db->get("user_settings");
        return $q->row();
    }
    
}
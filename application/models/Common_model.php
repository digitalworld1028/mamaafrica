<?php
class Common_model extends CI_Model{
    function data_insert($table,$insert_array,$insert_by=false,$allow_log=true){
        if($insert_by){
           
            $user_id = _get_current_user_id($this);
            if(!empty($user_id)){
                $insert_array["created_by"] = $user_id;
            }
            
            $insert_array["created_at"] = date("Y-m-d H:s:i");
        }
        $this->db->insert($table,$insert_array);
        $id = $this->db->insert_id();
        if($allow_log)
            $this->logs_model->set_log($table,"add","",$insert_array,array("key"=>$id));
        return $id;
    }
    function data_update($table,$set_array,$condition,$update_by=false,$allow_log=true){
        if($update_by){
            
            $user_id = _get_current_user_id($this);
            if(!empty($user_id)){
                $set_array["modified_by"] = $user_id;
            }
            
            $set_array["modified_at"] = date("Y-m-d H:s:i");
        }
        $this->db->update($table,$set_array,$condition);
        $return = $this->db->affected_rows();

        if ($allow_log) {
            if (!isset($set_array["draft"])) {
                $this->logs_model->set_log($table, "update", "", $set_array, $condition);
            } else {
                $this->logs_model->set_log($table, "delete", "", $set_array, $condition);
            }
        }
        return $return;
    }
    function data_remove($table,$condition,$hard=true,$update_by=false,$allow_log=true){
        $filter = "";
        
        if(!empty($condition)){
            foreach($condition as $key=>$con){
                $filter .= " and ".$key." = '".$con."' ";
            }
        $sql = "Select * from ".$table." where 1 $filter";
        $q = $this->db->query($sql);
        $row = $q->row(); 
        
        if($row){
            if($hard){
                $this->db->delete($table,$condition);
                if ($allow_log) {
                    $this->logs_model->set_log($table, "delete", "", array(), $condition);
                }
            }else{
                
                $this->data_update($table,array("draft"=>"1"),$condition,$update_by,$allow_log);
                
            }
            
        }
        
        }
        
    }
    
}
?>
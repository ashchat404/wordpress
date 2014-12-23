<?php

/**
 * Description of Meta Value
 *
 * @author Grzegorz Winiarski
 * @package WPJB.Model
 */

class Wpjb_Model_Meta extends Daq_Db_OrmAbstract
{
    protected $_owner = null;
    
    protected $_name = "wpjb_meta";

    protected $_value = array();

    protected function _init()
    {
        $this->_reference["value"] = array(
            "localId" => "id",
            "foreign" => "Wpjb_Model_MetaValue",
            "foreignId" => "meta_id",
            "type" => "ONE_TO_ONE"
        );
    }
    
    public function __toString() 
    {
        $return = array();
        foreach($this->_value as $v) {
            /* @var $v Wpjb_Model_MetaValue */
            $return[] = $v->value;
        }
        
        return join(", ", $return);
    }
    
    public function setOwnerId($id)
    {
        $this->_owner = $id;
    }
    
    public function hasOwnerId()
    {
        if($this->_owner) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Returns list of meta values
     *
     * @return Wpjb_Model_MetaValue[]
     */
    public function getValues($scalar = false)
    {
        if(!$scalar) {
            return $this->_value;
        }
        
        $r = array();
        foreach($this->_value as $v) {
            $r[] = $v->value;
        }
        
        if(count($r) == 1) {
            return $r[0];
        } else {
            return $r;
        }
    }
    
    public function addValue(Wpjb_Model_MetaValue $mv) 
    {
        $this->_value[] = $mv;
    }
    
    public function getFirst()
    {
        if(isset($this->_value[0])) {
            return $this->_value[0];
        } else {
            $meta = new Wpjb_Model_MetaValue;
            $meta->meta_id = $this->getId();
            $meta->object_id = $this->_owner;
            $this->_value[0] = $meta;
            return $this->_value[0];
        }
    }
    
    public function free()
    {
        unset($this->_loaded);
    }
    
    public function value()
    {
        return $this->getFirst()->value;
    }
    
    public function values()
    {
        $arr = array();
        foreach($this->_value as $v) {
            $arr[] = $v->value;
        }
        
        return $arr;
    }
    
    public function getConfig()
    {
        return unserialize($this->meta_value);
    }
    
    public function conf($key, $default = null)
    {
        $c = $this->getConfig();
        if(isset($c[$key])) {
            return $c[$key];
        } else {
            return $default;
        }
    }
    
    public static function import($item)
    {
        $query = new Daq_Db_Query;
        $query->from("Wpjb_Model_Meta t");
        $query->where("name = ?", (string)$item->name);
        $query->where("meta_object = ?", (string)$item->meta_object);
        $query->limit(1);
        
        $result = $query->execute();
        
        if(!empty($result)) {
            return "exists";
        }
        
        $param = array(
            "name" => (string)$item->name,
            "order" => 100,
            "is_builtin" => 0,
            "is_trashed" => 0,
        );
        foreach($item->params->param as $p) {
            $param[(string)$p["name"]] = (string)$p;
        }
        
        $option = get_option("wpjb_form_".(string)$item->meta_object);

        if(!empty($option)) {
            
            if(!isset($option["group"][$param["group"]])) {
                list($group) = array_keys($option["group"]);
                $param["group"] = $group;
            }
            
            $option["field"][$param["name"]] = $param;
            update_option("wpjb_form_".(string)$item->meta_object, $option);
        }
        
        $model = new Wpjb_Model_Meta;
        $model->name = (string)$item->name;
        $model->meta_object = (string)$item->meta_object;
        $model->meta_type = 3;
        $model->meta_value = serialize($param);
        $model->save();
        
        return "inserted";
    }
    

}

?>
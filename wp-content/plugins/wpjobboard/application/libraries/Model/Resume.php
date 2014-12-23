<?php
/**
 * Description of Employer
 *
 * @author greg
 * @package
 */

class Wpjb_Model_Resume extends Daq_Db_OrmAbstract
{
    const ACCOUNT_ACTIVE = 1;
    const ACCOUNT_INACTIVE = 0;

    const RESUME_PENDING  = 1;
    const RESUME_DECLINED = 2;
    const RESUME_APPROVED = 3;
    
    const DELETE_PARTIAL = 1;
    const DELETE_FULL = 2;

    protected $_name = "wpjb_resume";
    
    protected $_details = null;
    
    protected $_metaTable = "Wpjb_Model_Meta";
    
    protected $_metaName = "resume";
    
    protected $_tagTable = array("scheme"=>"Wpjb_Model_Tag", "values"=>"Wpjb_Model_Tagged");
    
    protected $_tagName = "resume";
    
    protected $_user = null;
    
    protected static $_current = null;

    protected function _init()
    {
        $this->_reference["user"] = array(
            "localId" => "user_id",
            "foreign" => "Wpjb_Model_User",
            "foreignId" => "ID",
            "type" => "ONE_TO_ONE"
        );
        $this->_reference["search"] = array(
            "localId" => "id",
            "foreign" => "Wpjb_Model_ResumeSearch",
            "foreignId" => "resume_id",
            "type" => "ONE_TO_ONE"
        );
        $this->_reference["meta"] = array(
            "localId" => "id",
            "foreign" => "Wpjb_Model_MetaValue",
            "foreignId" => "object_id",
            "type" => "ONE_TO_ONCE"
        );
        $this->_reference["tagged"] = array(
            "localId" => "id",
            "foreign" => "Wpjb_Model_Tagged",
            "foreignId" => "object_id",
            "type" => "ONE_TO_ONE"
        );
    }

    public function hasActiveProfile()
    {

        if(!$this->is_active) {
            return false;
        }

        if(!$this->is_approved) {
            return false;
        }

        return true;
    }

    /**
     * Returns currently loggedin user employer object
     *
     * @return Wpjb_Model_Resume
     */
    public static function current()
    {
        if(self::$_current instanceof self) {
            return self::$_current;
        }

        $current_user = wp_get_current_user();

        if($current_user->ID < 1) {
            return null;
        }

        $query = new Daq_Db_Query();
        $object = $query->select()->from(__CLASS__." t")
            ->where("user_id = ?", $current_user->ID)
            ->limit(1)
            ->execute();

        if(isset($object[0])) {
            self::$_current = $object[0];
            return self::$_current;
        }

        return null;
    }

    public function save()
    {
        $id = parent::save();
        
        $this->meta(true);
        $this->geolocate(true);
        
        Wpjb_Model_ResumeSearch::createFrom($this);
        
        return $id;
    }
    
    public function delete($delete = self::DELETE_FULL)
    {
        $user = new WP_User($this->user_id);
        if($user->exists() && $delete == self::DELETE_FULL) {
            require_once(ABSPATH . 'wp-admin/includes/user.php');
            wp_delete_user($this->user_id);
        }
        
        $query = Daq_Db_Query::create();
        $query->from("Wpjb_Model_ResumeSearch t");
        $query->where("resume_id = ?", $this->id);
        foreach($query->execute() as $row) {
            $row->delete();
        }
        
        $query = Daq_Db_Query::create();
        $query->from("Wpjb_Model_ResumeDetail t");
        $query->where("resume_id = ?", $this->id);
        foreach($query->execute() as $row) {
            $row->delete();
        }
        
        $dir = wpjb_upload_dir("resume", "", $this->id, "basedir");
        if(is_dir($dir)) {
            wpjb_recursive_delete($dir);
        }
        
        parent::delete();
    }

    
    public function allToArray()
    {
        $arr = parent::toArray();
        
        $field = (array)$this->getNonEmptyFields();
        $txtar = (array)$this->getNonEmptyTextareas();
        
        foreach($field as $f) {
            $arr["field_".$f->field_id] = $f->value;
        }
        
        foreach($txtar as $f) {
            $arr["field_".$f->field_id] = $f->value;
        }
        
        return $arr;
    }

    /**
     * Renders and returns HTML plain version of resume
     *
     * @return string
     */
    public function renderHTML()
    {
        $instance = Wpjb_Project::getInstance();
        $resume = $this;
        $name = $resume->firstname." ".$resume->lastname;
        $view = new Daq_View($instance->env("template_base")."resumes");
        $view->set("resume", $resume);
        $view->set("can_browse", true);
        $instance->placeHolder = $view;
        ob_start();
        $view->render("resume-min.php");
        $rendered = ob_get_clean();

        return $rendered;
    }
    
    public function getDetails()
    {
        if($this->_details !== null) {
            return $this->_details;
        }
        
        $select = new Daq_Db_Query();
        $select->select();
        $select->from("Wpjb_Model_ResumeDetail t");
        $select->where("resume_id = ?", $this->id);
        $select->order("t.started_at DESC");
        $result = $select->execute();
        
        return $result;
    }
    
    public function getExperience()
    {
        $exp = array();

        foreach($this->getDetails(true) as $detail) {

            if($detail->type == Wpjb_Model_ResumeDetail::EXPERIENCE) {
                $exp[] = $detail;
            }
        }
        
        return $exp;
    }
    
    public function getEducation()
    {
        $edu = array();
        foreach($this->getDetails(true) as $detail) {
            if($detail->type == Wpjb_Model_ResumeDetail::EDUCATION) {
                $edu[] = $detail;
            }
        }
        
        return $edu;
    }
    
    public function getAvatarUrl($resize = null)
    {
        global $wp_version;
        
        $upload = wpjb_upload_dir("resume", "image", $this->id);
        $file = wpjb_glob($upload["basedir"]."/[!_]*");
        
        if(!isset($file[0])) {
            return null;
        }
        
        $filename = basename($file[0]);
        $altfile = "__".$resize."_".basename($file[0]);
        
        if($resize && version_compare($wp_version, "3.5.0")>=0) {
                
            if(!is_file($upload["basedir"]."/".$altfile)) {
                list($max_w, $max_h) = explode("x", $resize);
                $editor = wp_get_image_editor($upload["basedir"]."/".$filename);

                if(!is_wp_error($editor)) {
                    $editor->resize($max_w, $max_h, false);
                    $editor->set_quality(100);
                    $result = $editor->save($upload["basedir"]."/".$altfile);

                    rename($result["path"], $upload["basedir"]."/".$altfile);

                    $filename = $altfile;
                } // endif is_wp_error
            } else {
                $filename = $altfile;
            }
        } 
        
        return $upload["baseurl"]."/".$filename;
    }
    
    public function location()
    {
        $country = Wpjb_List_Country::getByCode($this->candidate_country);
        $country = trim($country['name']);
        
        $addr = array(
            $this->candidate_location,
            $this->candidate_zip_code,
            $this->candidate_state,
            $country
        );
        
        $addr = apply_filters("wpjb_geolocate", $addr, $this);
        
        return join(", ", $addr);
    }
    
    public function locationToString()
    {
        $arr = array();
        $country = Wpjb_List_Country::getByCode($this->candidate_country);
        $country = trim($country['name']);

        if(strlen(trim($this->candidate_location))>0) {
            $arr[] = $this->candidate_location;
        }

        if($this->candidate_country == 840 && strlen(trim($this->candidate_state))>0) {
            $arr[] = $this->candidate_state;
        } else if(strlen($country)>0) {
            $arr[] = $country;
        }

        return apply_filters("wpjb_location_display", implode(", ", $arr), $this);
    }
    
    public static function import($item) 
    {
        global $wpdb;
	
        $user = get_user_by("login", (string)$item->user_login);
        if($user === false) {
            $l1 = strlen((string)$item->user_login);
            $l2 = strlen((string)$item->user_password);
            $l3 = strlen((string)$item->user_email);
            
            if($l1 && $l2 && $l3) {
                $user_id = wp_create_user(
                    (string)$item->user_login, 
                    (string)$item->user_password, 
                    (string)$item->user_email
                );
				
                if($user_id instanceof WP_Error) {
                    return "failed";
                }
				
                $wpdb->update($wpdb->users, array("user_pass"=>(string)$item->user_password), array("ID"=>$user_id));
                
            } else {
                return "failed";
            }
        } else {
            $user_id = $user->ID;
        }
		
        $fullname = trim((string)$item->first_name." ".(string)$item->last_name);
        
        $default = new stdClass();
        $default->user_id = $user_id;
        $default->candidate_slug = Wpjb_Utility_Slug::generate(Wpjb_Utility_Slug::MODEL_RESUME, $fullname);
        $default->phone = "";
        $default->headline = "";
        $default->description = "";
        $default->created_at = date("Y-m-d H:i:s");
        $default->modified_at = date("Y-m-d H:i:s");
        $default->candidate_country = "";
        $default->candidate_state = "";
        $default->candidate_zip_code = "";
        $default->candidate_location = "";
        $default->is_public = 1;
        $default->is_active = 1;

        
        if(isset($item->id)) {
            $id = (int)$item->id;
        } else {
            $id = null;
        }
        
        $object = new self($id);
        
        if($object->exists()) {
            foreach($object->getFieldNames() as $key) {
                if(!isset($item->$key)) {
                    $item->$key = $job->$key;
                }
            }
        } else {
            foreach($default as $key => $value) {
                if(!isset($item->$key)) {
                    $item->$key = $value;
                }
            }
        }
        
        $object->user_id = (string)$item->user_id;
        $object->phone = (string)$item->phone;
        $object->headline = (string)$item->headline;
        $object->description = (string)$item->description;
        $object->created_at = (string)$item->created_at;
        $object->modified_at = (string)$item->modified_at;
        $object->candidate_country = (string)$item->candidate_country;
        $object->candidate_state = (string)$item->candidate_state;
        $object->candidate_zip_code = (string)$item->candidate_zip_code;
        $object->candidate_location = (string)$item->candidate_location;
        $object->is_public = (int)$item->is_public;
        $object->is_active = (int)$item->is_active;
	$object->candidate_slug = (string)$item->candidate_slug;
        $object->save();

        wp_update_user(array(
            "ID" => $user_id,
            "user_email" => (string)$item->user_email,
            "user_url" => (string)$item->user_url,
            "first_name" => (string)$item->first_name,
            "last_name" => (string)$item->last_name,
        ));
        
        Wpjb_Model_ResumeSearch::createFrom($object);

        if(isset($item->metas->meta)) {
            foreach($item->metas->meta as $meta) {
                $name = (string)$meta->name;
                $value = (string)$meta->value;
                $varr = array();

                if($meta->values) {
                    foreach($meta->values->value as $v) {
                        $varr[] = (string)$v;
                    }
                } else {
                    $varr[] = (string)$meta->value;
                }
                
                $vlist = $object->meta->$name->getValues();
                $c = count($varr);
                
                for($i=0; $i<$c; $i++) {
                    if(isset($vlist[$i])) {
                        $vlist[$i]->value = $varr[$i];
                        $vlist[$i]->save();
                    } else {
                        $mv = new Wpjb_Model_MetaValue;
                        $mv->meta_id = $object->meta->$name->getId();
                        $mv->object_id = $object->id;
                        $mv->value = $varr[$i];
                        $mv->save();
                    }
                }
                

            }
        }
        
        foreach($item->details->detail as $d) {
            if((int)$d->id > 0) {
                $detail = new Wpjb_Model_ResumeDetail((int)$d->id);
            } else {
                $detail = new Wpjb_Model_ResumeDetail;
            }
            
            if((string)$d->type == "experience") {
                $detail->type = 1;
            } else {
                $detail->type = 2;
            }
            
            $detail->resume_id = $object->id;
            $detail->started_at = (string)$d->started_at;
            $detail->completed_at = (string)$d->completed_at;
            $detail->is_current = (int)$d->is_current;
            $detail->grantor = (string)$d->grantor;
            $detail->detail_title = (string)$d->detail_title;
            $detail->detail_description = (string)$d->detail_description;
            $detail->save();
        }
        
        if(isset($item->tags->tag)) {
            foreach($item->tags->tag as $tag) {

                if($tag->id) {
                    $tid = (int)$tag->id;
                } else {
                    $tid = self::_resolve($tag);
                }

                $tagged = new Wpjb_Model_Tagged;
                $tagged->tag_id = $tid;
                $tagged->object = "resume";
                $tagged->object_id = $object->id;
                $tagged->save();
            }  
        }
        
        if(isset($item->files->file)) {
            foreach($item->files->file as $file) {
                list($path, $filename) = explode("/", (string)$file->path);
                $upload = wpjb_upload_dir("resume", $path, $object->id, "basedir");
                wp_mkdir_p($upload);
                file_put_contents($upload."/".$filename, base64_decode((string)$file->content));
            }
        }
        
        if($id) {
            return "updated";
        } else {
            return "inserted";
        }
    }
    
    protected static function _resolve($tag) 
    {
        $query = new Daq_Db_Query();
        $query->select();
        $query->from("Wpjb_Model_Tag t");
        $query->where("type = ?", $tag->type);
        $query->where("slug = ?", $tag->slug);
        $query->limit(1);
        
        $result = $query->execute();
        
        if(empty($result)) {
            $t = new Wpjb_Model_Tag;
            $t->type = $tag->type;
            $t->slug = $tag->slug;
            $t->title = $tag->title;
            $t->save();
        } else {
            $t = $result[0];
        }
        
        return $t->id;
    }
    
    /**
     * Returns geolocation parameters for the job
     * 
     * @return stdClass 
     */
    public function getGeo()
    {
        $this->geolocate();
        
        $obj = new stdClass;
        $obj->geo_status = $this->meta->geo_status->value();
        $obj->geo_latitude = $this->meta->geo_latitude->value();
        $obj->geo_longitude = $this->meta->geo_longitude->value();
        
        $obj->status = $this->meta->geo_status->value();
        $obj->lnglat = $obj->geo_latitude.",".$obj->geo_longitude;
        
        return $obj;
    }
    
    public function geolocate($force = false) 
    {
        $arr = array(
            Wpjb_Service_GoogleMaps::GEO_MISSING,
            Wpjb_Service_GoogleMaps::GEO_FOUND,
        );
        
        if(in_array($this->meta->geo_status->value(), $arr) && !$force) {
            return;
        }
        
        $geo = Wpjb_Service_GoogleMaps::locate($this->location());
        
        $meta = $this->meta->geo_status->getFirst();
        $meta->value = $geo->geo_status;
        $meta->save();
        
        $meta = $this->meta->geo_latitude->getFirst();
        $meta->value = $geo->geo_latitude;
        $meta->save();
        
        $meta = $this->meta->geo_longitude->getFirst();
        $meta->value = $geo->geo_longitude;
        $meta->save();
        
    }
    
    public function paymentAccepted(Wpjb_Model_Payment $payment)
    {
        $hash = md5("{$payment->id}|{$payment->object_id}|{$payment->object_type}|{$payment->paid_at}");
        
        $message = Wpjb_Utility_Message::load("notify_employer_resume_paid");
        $message->assign("resume", $this);
        $message->assign("resume_unique_url", wpjr_link_to("resume", $this, array("hash"=>$hash)));
        $message->setTo($payment->email);
        $message->send();
        
    }
    
    public function url()
    {
        return wpjr_link_to("resume", $this);
    }
    
    public function __get($key) 
    {
        if($key == "file") {
            $dir = wpjb_upload_dir("resume", "", $this->id, "basedir");
            $files = new stdClass();
            
            foreach(wpjb_glob($dir."/*") as $path) {
                $basename = basename($path);
                $objectname = str_replace("-", "_", $basename);
                $files->$objectname = array();
                foreach(wpjb_glob($dir."/".$basename."/[!_]*") as $file) {
                    $obj = new stdClass();
                    $obj->basename = basename($file);
                    $obj->path = $file;
                    $obj->url = wpjb_upload_dir("resume", $basename, $this->id, "baseurl")."/".$obj->basename;
                    $obj->size = filesize($obj->path);
                    $files->{$objectname}[] = $obj;
                }
            }
            return $files;
        } elseif($key == "user") {
            
            if(!$this->_user) {
                $this->_user = new WP_User($this->user_id);
            }
            
            return $this->_user;
            
        } else {
            return parent::__get($key);
        }
    }
    
    public function toArray()
    {
        $arr = parent::toArray();
        
        $arr["tag"] = array();
        
        foreach($this->tag() as $key => $tag) {
            $arr["tag"][$key] = array();
            foreach($tag as $t) {
                $arr["tag"][$key][] = $t->toArray();
            }
        }
        
        $arr["url"] = wpjr_link_to("resume", $this);
        $arr["admin_url"] = wpjb_admin_url("resumes", "edit", $this->id);
        
        $arr["education"] = array();
        $arr["experience"] = array();
        
        foreach($this->getDetails(true) as $detail) {

            if($detail->type == Wpjb_Model_ResumeDetail::EXPERIENCE) {
                $d = "experience";
            } else {
                $d = "education";
            }
            
            if(!isset($arr[$d])) {
                $arr[$d] = array();
            }
            
            $arr[$d][] = $detail->toArray();
        }
        
        return $arr;
        
    }
    
    
}

?>
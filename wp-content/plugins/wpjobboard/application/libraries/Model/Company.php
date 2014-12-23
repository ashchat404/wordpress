<?php
/**
 * Description of Employer
 *
 * @author greg
 * @package 
 */

class Wpjb_Model_Company extends Daq_Db_OrmAbstract
{
    
    const ACCESS_UNSET = 0;
    const ACCESS_PENDING = -1;
    const ACCESS_DECLINED = -2;
    const ACCESS_GRANTED = 1;
    
    const ACCOUNT_FULL_ACCESS = 4;
    const ACCOUNT_DECLINED = 3;
    const ACCOUNT_REQUEST = 2;
    const ACCOUNT_ACTIVE = 1;
    const ACCOUNT_INACTIVE = 0;
    
    const DELETE_PARTIAL = 1;
    const DELETE_FULL = 2;

    protected $_name = "wpjb_company";
    
    protected $_metaTable = "Wpjb_Model_Meta";
    
    protected $_metaName = "company";
    
    protected static $_current = null;

    protected function _init()
    {
        $this->_reference["user"] = array(
            "localId" => "user_id",
            "foreign" => "Wpjb_Model_User",
            "foreignId" => "ID",
            "type" => "ONE_TO_ONE"
        );
        $this->_reference["usermeta"] = array(
            "localId" => "user_id",
            "foreign" => "Wpjb_Model_UserMeta",
            "foreignId" => "user_id",
            "type" => "ONE_TO_ONE"
        );
    }

    public function hasActiveProfile()
    {
        if($this->jobs_posted == 0) {
            return false;
        }

        if(!$this->is_active) {
            return false;
        }

        if(!$this->is_public) {
            return false;
        }

        return true;
    }

    /**
     * Returns currently loggedin user employer object
     *
     * @return Wpjb_Model_Company
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

        if($object[0]) {
            self::$_current = $object[0];
            return self::$_current;
        }

        return null;
    }

    public function getLogoUrl()
    {
        $upload = wpjb_upload_dir("company", "company-logo", $this->id);
        $file = wpjb_glob($upload["basedir"]."/*");
        
        if(isset($file[0])) {
            return $upload["baseurl"]."/".basename($file[0]);
        } else {
            return null;
        }
    }

    public function delete($delete = self::DELETE_FULL)
    {
        $user = new WP_User($this->user_id);
        if($user->exists() && $delete == self::DELETE_FULL) {
            require_once(ABSPATH . 'wp-admin/includes/user.php');
            wp_delete_user($this->user_id);
        }
        
        wpjb_bubble_delete(wpjb_upload_dir("company", "", $this->id, "basedir"));
        
        if(!is_null(self::$_current) && self::$_current->id==$this->id) {
            self::$_current = null;
        }
        
        parent::delete();
    }

    public function addAccess($days)
    {
        $activeUntil = $this->access_until;
        $activeUntil = strtotime($activeUntil);

        if($activeUntil<time()) {
            $activeUntil = time();
        }

        $extend = $days*3600*24;

        $this->access_until = date("Y-m-d H:i:s", $activeUntil+$extend);
    }

    public function isEmployer()
    {
        if($this->user_id < 1) {
            return false;
        }
        return current_user_can("manage_jobs");
    }

    public function isActive()
    {
        $isActive = $this->is_active;

        if($isActive == self::ACCOUNT_ACTIVE) {
            return true;
        }

        if($isActive == self::ACCOUNT_FULL_ACCESS) {
            return true;
        }

        return false;
    }
    
    public function isVisible()
    {
        if(!$this->is_public) {
            return false;
        }

        if(!$this->is_active) {
            return false;
        }

        if(!$this->jobs_posted) {
            return false;
        }


        return true;
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
    
    public function location()
    {
        $country = Wpjb_List_Country::getByCode($this->company_country);
        $country = trim($country['name']);
        
        $addr = array(
            $this->company_city,
            $this->company_zip_code,
            $this->company_state,
            $country
        );
        
        $addr = apply_filters("wpjb_geolocate", $addr, $this);
        
        return join(", ", $addr);
    }
    
    public function locationToString()
    {
        $arr = array();
        $country = Wpjb_List_Country::getByCode($this->company_country);
        $country = trim($country['name']);

        if(strlen(trim($this->company_location))>0) {
            $arr[] = $this->company_location;
        }

        if($this->company_country == 840 && strlen(trim($this->company_state))>0) {
            $arr[] = $this->company_state;
        } else if(strlen($country)>0) {
            $arr[] = $country;
        }

        return apply_filters("wpjb_location_display", implode(", ", $arr), $this);
    }
    
    public function save()
    {
        $id = parent::save();
                
        $this->meta(true);
        $this->geolocate(true);
        
        return $id;
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
        
        $default = new stdClass();
        $default->user_id = $user_id;
        $default->company_name = "";
        $default->company_website = "";
        $default->company_info = "";
        $default->company_country = "";
        $default->company_state = "";
        $default->company_zip_code = "";
        $default->company_location = "";
        $default->jobs_posted = 0;
        $default->is_public = 1;
        $default->is_active = 1;
        $default->is_verified = 0;
        
        if(isset($item->id)) {
            $id = (int)$item->id;
        } else {
            $id = null;
        }
        
        $object = new self($id);
        
        if($object->exists()) {
            foreach($object->getFieldNames() as $key) {
                if(!isset($item->$key)) {
                    $item->$key = $default->$key;
                }
            }
        } else {
            foreach($default as $key => $value) {
                if(!isset($item->$key)) {
                    $item->$key = $value;
                }
            }
        }
        
        $object->user_id = (int)$item->user_id;
        $object->company_name = (string)$item->company_name;
        $object->company_website = (string)$item->company_website;
        $object->company_info = (string)$item->company_info;
        $object->company_country = (string)$item->company_country;
        $object->company_state = (string)$item->company_state;
        $object->company_zip_code = (string)$item->company_zip_code;
        $object->company_location = (string)$item->company_location;
        $object->jobs_posted = (int)$item->jobs_posted;
        $object->is_public = (int)$item->is_public;
        $object->is_active = (string)$item->is_active;
        $object->is_verified = (string)$item->is_verified;
        $object->save();
        
        wp_update_user(array(
            "ID" => $user_id,
            "user_email" => (string)$item->user_email,
        ));
		
        $caps = get_user_meta($user_id, 'wp_capabilities', true);
        $roles = array_keys((array)$caps);

        if($roles[0] == "subscriber") {
            wp_update_user(array(
                "ID" => $user_id,
                "role" => "employer"
            ));
        }

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
        
        if(isset($item->files->file)) {
            foreach($item->files->file as $file) {
                list($path, $filename) = explode("/", (string)$file->path);
                $upload = wpjb_upload_dir("company", $path, $object->id, "basedir");
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
    
    public function membership()
    {
        $query = new Daq_Db_Query();
        $query->from("Wpjb_Model_Membership t");
        $query->where("user_id = ?", $this->user_id);
        $query->where("started_at <= ?", date("Y-m-d"));
        $query->where("expires_at >= ?", date("Y-m-d"));
        
        return $query->execute();
    }
    
    /**
     * 
     * @deprecated since 4.0.1
     * @return type
     */
    public function getUsers()
    {
        return $this->user;
    }
    
    public static function search($params = array())
    {
        $query = null;
        $location = null;

        /**
         * @var $count int
         * items per page or maximum number of elements to return
         */
        $count = 25;
        $date_from = null;
        $date_to = null;
        
        /**
         * @var $sort_order mixed
         * string or array, specify sort column and order (either DESC or ASC),
         * you can add more then one sort order. 
         */
        $sort_order = "t1.id DESC";
        
        /**
         * @var $count_only boolean
         * Count jobs only
         */
        $count_only = false;
        
        /**
         * Return only list of job ids instead of objects
         * @var $ids_only boolean
         */
        $ids_only = false;
        
        /**
         * @var $filter string
         * narrow jobs to certain type:
         * - all: all resumes
         * - active: only active resumes
         * - inactive: inactive resumes
         */
        $filter = "active";
        
        extract($params);
        
        $select = new Daq_Db_Query();
        $select->select();
        $select->from("Wpjb_Model_Company t1");
        $select->join("t1.user t2");
        
        switch($filter) {
            case "active": $select->where("t1.is_active=1 AND t1.is_public=1 AND t1.jobs_posted>0"); break;
            case "pending":$select->where("t1.is_active=?", 2); break;
        }

        if($query) {
            $select->where("(company_name LIKE ? OR company_info LIKE ?)", "%$query%");
        }
        
        if($location) {
            $select->where("(company_state LIKE ? OR company_zip_code LIKE ? OR company_location LIKE ?)", "%$location%");
        }
        
        if($login) {
            $select->where("t2.user_login LIKE ?", "%$login%");
        }
        
        if($email) {
            $select->where("t2.user_email LIKE ?", "%$email%");
        }
        
        if($sort_order) {
            $select->order($sort_order);
        }
        
        $itemsFound = $select->select("COUNT(*) AS cnt")->fetchColumn();

        $select->select("*");
        
        if($page && $count) {
            $select->limitPage($page, $count);
        }
        
        if($count_only) {
            return $itemsFound;    
        }
        
        if($ids_only) {
            $select->select("t1.id");
            $list = $select->getDb()->get_col($select->toString());
        } else {   
            $list = $select->execute();
        }
        
        $response = new stdClass;
        $response->company = $list;
        $response->page = (int)$page;
        $response->perPage = (int)$count;
        $response->count = count($list);
        $response->total = (int)$itemsFound;
        $response->pages = ceil($response->total/$response->perPage);
        
        return $response;
    }
}

?>
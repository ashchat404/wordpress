<?php
/**
 * Description of Application
 *
 * @author greg
 * @package
 */

class Wpjb_Model_Application extends Daq_Db_OrmAbstract
{
    const STATUS_REJECTED = 0;
    const STATUS_NEW = 1;
    const STATUS_ACCEPTED =2;
    const STATUS_READ = 3;
    
    protected $_name = "wpjb_application";

    protected $_metaTable = "Wpjb_Model_Meta";
    
    protected $_metaName = "apply";
    
    protected $_fields = array();

    protected $_textareas = array();

    protected function _init()
    {
        $this->_reference["job"] = array(
            "localId" => "job_id",
            "foreign" => "Wpjb_Model_Job",
            "foreignId" => "id",
            "type" => "ONE_TO_ONE"
        );
    }

    public function addFile($file)
    {
        $path = Wpjb_List_Path::getPath("apply_file");
        $path.= "/".$this->id."/";

        if(!is_dir($path)) {
            mkdir($path);
        }
        
        copy($file, $path.basename($file));
    }

    public function getFiles()
    {
        $upload = wpjb_upload_dir("application", "*", $this->id);
        $baseurl = $upload["baseurl"];
        $upload = $upload["basedir"]."/*";
        $files = wpjb_glob($upload);     

        $fArr = array();
        foreach($files as $file) {
            $dir = basename(dirname($file));
            $f = new stdClass;
            $f->basename = basename($file);
            $f->url = str_replace("*", $dir, $baseurl)."/".$f->basename;
            $f->size = filesize($file);
            $f->ext = pathinfo($file, PATHINFO_EXTENSION);
            $f->dir = $file;
            $fArr[] = $f;
        }

        return $fArr;
    }
    
    public function delete()
    {
        $dir = wpjb_upload_dir("application", "", $this->id, "basedir");
        if(is_dir($dir)) {
            wpjb_recursive_delete($dir);
        }
        
        $job = new Wpjb_Model_Job($this->job_id);
        $job->applications--;
        $job->save();

        parent::delete();
    }
    
    public function save()
    {
        if($this->exists()) {
            $isNew = false;
            $old = new self($this->id);
            $oldId = $old->job_id;
            
            $skip = array(Wpjb_Model_Application::STATUS_NEW, Wpjb_Model_Application::STATUS_READ);

            if(!in_array($this->status, $skip) && $old->status != $this->status) {
                $mail = Wpjb_Utility_Message::load("notify_applicant_status_change");
                $mail->assign("application", $this);
                $mail->assign("job", $this->getJob(true));
                $mail->assign("status", wpjb_application_status($this->status));
                $mail->setTo($this->email);
                $mail->send();
            }
            
        } else {
            $isNew = true;
        }
        

        
        $id = parent::save();
        
        if($isNew) {
            $job = new Wpjb_Model_Job($this->job_id);
            $job->applications++;
            $job->save();
        } elseif($oldId != $this->job_id) {
            $job = new Wpjb_Model_Job($this->job_id);
            $job->applications++;
            $job->save();
            
            $job = new Wpjb_Model_Job($oldId);
            $job->applications--;
            $job->save();
        }
        
        if($isNew) {
            do_action("wpjb_application_published", $this);
        }
        
        return $id;
    }
    
    public static function import($item) 
    {
        $job = new Wpjb_Model_Job((int)$item->job_id);
        if(!$job->exists()) {
            return "failed";
        }
        $user = new Wpjb_Model_User((int)$item->user_id);
        if((int)$item->user_id > 0 && $user->getId() < 1) {
            return "failed";
        }
        
        $default = new stdClass();
        $default->job_id = null;
        $default->user_id = null;
        $default->applied_at = date("Y-m-d");
        $default->applicant_name = "";
        $default->message = "";
        $default->email = "";
        $default->status = self::STATUS_NEW;
        
        if(isset($item->id)) {
            $id = (int)$item->id;
        } else {
            $id = null;
        }
        
        $object = new self($id);
        
        if($object->id) {
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
        
        $object->job_id = (int)$item->job_id;
        $object->user_id = (int)$item->user_id;
        $object->applied_at = (string)$item->applied_at;
        $object->applicant_name = (string)$item->applicant_name;
        $object->message = (string)$item->message;
        $object->email = (string)$item->email;
        $object->status = (int)$item->status;
        $object->save();

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
                $upload = wpjb_upload_dir("application", $path, $object->id, "basedir");
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
    
    public static function search($params = array())
    {
        $query = null;
        $job = null;

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
        $sort_order = "t1.applied_at DESC, t1.id DESC";
        
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
        $select->from("Wpjb_Model_Application t1");
        
        switch($filter) {
            case "rejected":$select->where("status = ?", 0); break;
            case "new":     $select->where("status IN(1,3)"); break;
            case "accepted":$select->where("status = ?", 2); break;
        }
        
        if($job) {
            $select->where("job_id = ?", $job);
        }

        if($query) {
            $select->where("(applicant_name LIKE ? OR email LIKE ?)", "%$query%");
        }
        
        if($date_from) {
            $select->where("applied_at >= ?", $date_from);
        }

        if($date_to) {
            $select->where("applied_at <= ?", $date_to);
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
        $response->application = $list;
        $response->page = (int)$page;
        $response->perPage = (int)$count;
        $response->count = count($list);
        $response->total = (int)$itemsFound;
        $response->pages = ceil($response->total/$response->perPage);
        
        return $response;
    }
    
    public function getResume()
    {
        if(!$this->user_id) {
            return null;
        }
        
        $query = new Daq_Db_Query;
        $query->from("Wpjb_Model_Resume t");
        $query->where("user_id = ?", $this->user_id);
        $query->limit(1);
        
        $result = $query->execute();
        
        if(isset($result[0])) {
            return $result[0];
        } else {
            return null;
        }
    }
    
}

?>
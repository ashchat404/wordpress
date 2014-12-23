<?php
/**
 * Description of Main
 *
 * @author greg
 * @package 
 */

class Wpjb_Module_Ajax_Main
{
    public function slugifyAction()
    {
        $list = array("job" => 1, "type" => 1, "category" => 1);

        $id = Daq_Request::getInstance()->post("id");
        $title = Daq_Request::getInstance()->post("title");
        $model = Daq_Request::getInstance()->post("object");

        if(!isset($list[$model])) {
            die;
        }

        die(Wpjb_Utility_Slug::generate($model, $title, $id));
    }
    
    public function hideAction()
    {
        $config = Wpjb_Project::getInstance();
        $config->setConfigParam("activation_message_hide", 1);
        $config->saveConfig();
        
        exit(1);
    }

    public function cleanupAction()
    {

    }
}

?>
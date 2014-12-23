<?php
/**
 * Description of Frontend
 *
 * @author greg
 * @package 
 */

class Wpjb_Controller_Frontend extends Daq_Controller_Abstract
{
    private $_object = null;

    public function __construct()
    {
        wp_enqueue_script("jquery");
        add_action('wp_head', array($this, "_injectMedia"));
        add_filter("wp_title", array($this, "_injectTitle"));
        parent::__construct();
    }

    public function _injectMedia()
    {   
        $theme = wp_get_theme();
        if($theme->get_template() == "twentytwelve") {
            echo '<style type="text/css">';
            echo '.wpjb-form select { padding: 0.428571rem }'.PHP_EOL;
            echo '#wpjb-main img { border-radius: 0px; box-shadow: 0 0px 0px rgba(0, 0, 0, 0) }'.PHP_EOL;
            echo 'table.wpjb-table { font-size: 13px }'.PHP_EOL;
            echo '</style>';
        }
    }

    public function _injectTitle()
    {
        if(strlen(Wpjb_Project::getInstance()->title)>0) {
            return esc_html(Wpjb_Project::getInstance()->title)." \r\n";
        }
    }

    public function setCanonicalUrl($url)
    {
        Wpjb_Project::getInstance()->setEnv("canonical", $url);
    }

    public function setObject(Daq_Db_OrmAbstract $object)
    {
        $this->_object = $object;
    }

    /**
     * Returns object resolved during request dispatch
     *
     * @return Daq_Db_OrmAbstract
     * @throws Exception If trying to get object before it was set
     */
    public function getObject()
    {
        if(!$this->_object instanceof Daq_Db_OrmAbstract) {
            throw new Exception("Object is not instanceof Daq_Db_OrmAbstract");
        }
        return $this->_object;
    }

    /**
     *
     * @param <type> $module
     * @return Daq_Router
     */
    protected function _getRouter($module = "frontend")
    {
        return Wpjb_Project::getInstance()->getApplication($module)->getRouter();
    }

    /**
     * Returns Current Request Object
     * 
     * @return Daq_Request
     */
    public function getRequest()
    {
        return $this->_request;
    }

    public function isMember()
    {
        $info = wp_get_current_user();
        $isAdmin = true;
        if($info->ID > 0) {
            return true;
        } else {
            return false;
        }
    }

    protected function _isUserAdmin()
    {
        $info = wp_get_current_user();
        $isAdmin = true;
        if(!isset($info->wp_capabilities) || !$info->wp_capabilities['administrator']) {
            $isAdmin = false;
        }
        return $isAdmin;
    }
    
    public function setTitle($text, $param = array())
    {
        $k = array();
        $v = array();
        foreach($param as $key => $value) {
            $k[] = "{".$key."}";
            $v[] = esc_html($value);
        }

        $title = apply_filters("wpjb_set_title", rtrim(str_replace($k, $v, $text))." ");
        
        Wpjb_Project::getInstance()->title = $title;
    }
    
    public function redirect($url) 
    {
        do_action("wpjb_redirect", $url, $this);
        
        $this->view->_flash->save();
        wp_redirect($url);
        exit;
    }
    
    public function redirectIf($condition, $url) 
    {
        if($condition) {
            $this->redirect($url);
        }
    }
    
    
    
}

?>
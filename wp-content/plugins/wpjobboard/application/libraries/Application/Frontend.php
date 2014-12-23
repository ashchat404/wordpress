<?php
/**
 * Description of Frontend
 *
 * @author greg
 * @package 
 */

class Wpjb_Application_Frontend extends Daq_Application_FrontAbstract
{
    public function getProject() 
    {
        return Wpjb_Project::getInstance();
    }
    
    public function dispatch($path)
    {
        $route = $this->getRouter()->match(rtrim($path, "/")."/");
        $route = apply_filters("wpjb_dispatched", $route);
        
        $result = $this->_dispatch($route);
        $result = apply_filters("wpjb_select_template", $result);

        if($result === null) {
            $result = $this->_route['action'];
        }
        
        if($result === false) {
            wpjb_flash();
        } elseif(stripos($result, ".php") && is_file($result)) {
            do_action("wpjb_front_pre_render", $this, $result);
            $this->controller->view->render($result, true);
        } else {
            do_action("wpjb_front_pre_render", $this, $result);
            $this->controller->view->render($result.".php");
        }

        $this->_dispatched = true;
    }

}

?>
<?php
/**
 * Description of JobBoardMenu
 *
 * @author greg
 * @package
 */

class Wpjb_Widget_Alerts extends Daq_Widget_Abstract
{
    public function __construct() 
    {
        $this->_context = Wpjb_Project::getInstance();
        $this->_viewAdmin = "alerts.php";
        $this->_viewFront = "alerts.php";
        
        parent::__construct(
            "wpjb-widget-alerts", 
            __("Job Alerts", "wpjobboard"),
            array("description"=>__("Allows to create new job alert", "wpjobboard"))
        );
    }
    
    public function update($new_instance, $old_instance) 
    {
	$instance = $old_instance;
	$instance['title'] = htmlspecialchars($new_instance['title']);
	$instance['hide'] = (int)($new_instance['hide']);
        $instance['smart'] = (int)($new_instance['smart']);
        return $instance;
    }

    public function _filter()
    {
        $smart = false;
        if(isset($this->view->param->smart) && $this->view->param->smart) {
            foreach(array("index", "category", "type", "search") as $action) {
                if(wpjb_is_routed_to("index.$action")) {
                    $smart = true;
                    break;
                }
            }
        }
        
        $this->view->is_smart = $smart;
    }

}

?>
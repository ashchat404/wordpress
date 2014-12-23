<?php
/**
 * Description of Recent Jobs
 *
 * @author greg
 * @package
 */

class Wpjb_Widget_RecentJobs extends Daq_Widget_Abstract
{
    public function __construct() 
    {
        $this->_context = Wpjb_Project::getInstance();
        $this->_viewAdmin = "recent-jobs.php";
        $this->_viewFront = "recent-jobs.php";
        
        parent::__construct(
            "wpjb-recent-jobs", 
            __("Recent Jobs", "wpjobboard"),
            array("description"=>__("Displays list of recently posted jobs", "wpjobboard"))
        );
    }
    
    public function update($new_instance, $old_instance) 
    {
	$instance = $old_instance;
	$instance['title'] = htmlspecialchars($new_instance['title']);
	$instance['count'] = (int)($new_instance['count']);
	$instance['hide'] = (int)($new_instance['hide']);
        return $instance;
    }

    public function _filter()
    {
        $this->view->jobList = wpjb_find_jobs(array(
            "filter" => "active",
            "sort_order" => "t1.job_created_at DESC, t1.id DESC",
            "page" => 1,
            "count" => $this->_get("count", 5)
        ))->job;
    }

}

?>
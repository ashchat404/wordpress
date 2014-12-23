<?php
/**
 * Description of Frontend
 *
 * @author greg
 * @package
 */

class Wpjb_Form_Admin_Config_Indeed extends Daq_Form_Abstract
{
    public $name = null;

    public function init()
    {
        $this->name = __("Indeed API", "wpjobboard");
        $instance = Wpjb_Project::getInstance();

        $e = $this->create("indeed_publisher");
        $e->setValue($instance->getConfig("indeed_publisher"));
        $e->setLabel(__("Indeed Publisher API Key", "wpjobboard"));
        $e->setHint(__("Claim your key at https://ads.indeed.com/jobroll/, It's required to use Indeed Import", "wpjobboard"));
        $this->addElement($e);

        apply_filters("wpja_form_init_config_indeed", $this);

    }
}

?>
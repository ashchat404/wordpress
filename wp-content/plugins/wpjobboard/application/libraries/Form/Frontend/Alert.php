<?php
/**
 * Description of Apply
 *
 * @author greg
 * @package
 */

class Wpjb_Form_Frontend_Alert extends Daq_Form_Abstract
{
    public function init()
    {
        $e = $this->create("keyword", "text");
        $e->setRequired(true);
        $this->addElement($e, "alert");

        $e = $this->create("email", "text");
        $e->setRequired(true);
        $e->addValidator(new Daq_Validate_Email());
        $this->addElement($e, "apply");

        apply_filters("wpjb_form_init_alert", $this);
    }

}

?>
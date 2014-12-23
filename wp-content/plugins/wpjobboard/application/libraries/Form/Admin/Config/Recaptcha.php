<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Recaptcha
 *
 * @author Grzegorz
 */
class Wpjb_Form_Admin_Config_Recaptcha extends Daq_Form_Abstract
{
    public $name = null;

    public function init()
    {
        $this->name = __("reCAPTCHA", "wpjobboard");
        
        $instance = Wpjb_Project::getInstance();
        
        $e = $this->create("front_recaptcha_public");
        $e->setValue($instance->getConfig("front_recaptcha_public"));
        $e->setLabel(__("reCAPTCHA Public Key", "wpjobboard"));
        $this->addElement($e);
        
        $e = $this->create("front_recaptcha_private");
        $e->setValue($instance->getConfig("front_recaptcha_private"));
        $e->setLabel(__("reCAPTCHA Private Key", "wpjobboard"));
        $this->addElement($e);
        
        $e = $this->create("front_recaptcha_enabled", "checkbox");
        $e->setValue($instance->getConfig("front_recaptcha_enabled"));
        $e->setLabel(__("Enable reCAPTCHA for", "wpjobboard"));
        $e->addOption("wpjb_form_init_job", "wpjb_form_init_job", __("Add Job Form", "wpjobboard"));
        $e->addOption("wpjb_form_init_apply", "wpjb_form_init_apply", __("Application Form", "wpjobboard"));
        $e->addOption("wpjb_form_init_company", "wpjb_form_init_company", __("Employer Registration Form", "wpjobboard"));
        $e->addOption("wpjb_form_init_login", "wpjb_form_init_login", __("Employer Login Form", "wpjobboard"));
        $e->addOption("wpjr_form_init_register", "wpjr_form_init_register", __("Candidate Registration Form", "wpjobboard"));
        $e->addOption("wpjr_form_init_login", "wpjr_form_init_login", __("Candidate Login Form", "wpjobboard"));
        $this->addElement($e);

    }
}

?>
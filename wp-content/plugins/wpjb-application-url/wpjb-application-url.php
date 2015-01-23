<?php
/**
Plugin Name: [WPJB] Application URL
Version: 1.0
Author: Greg Winiarski
Description: Allows to set application URL on per job basis.
*/

// Plugin activation
register_activation_hook( __FILE__, 'wpjb_aurl_register');

function wpjb_aurl_register() {
    // registers new custom field in wp_wpjb_meta table
    wpjb_meta_register("job", "application_url");
}

// Plugin deactivation
register_deactivation_hook( __FILE__, 'wpjb_aurl_unregister');

function wpjb_aurl_unregister() {
    // unregisters new custom field in wp_wpjb_meta table
    wpjb_meta_unregister("job", "application_url");
}

// Adding custom fields to the form
add_filter("wpjb_form_init_job", "wpjb_aurl_init_job");
add_filter("wpja_form_init_job", "wpjb_aurl_init_job");

function wpjb_aurl_init_job($form) {
    
    // adding new fields to the form
    $e = $form->create("application_url", "text");
    $e->setBuiltin(false);
    $e->setLabel("Application URL");
    $e->addFilter(new Daq_Filter_WP_Url);
    $e->addValidator(new Daq_Validate_Url);
    $e->setValue($form->getObject()->meta->application_url->value());
    $form->addElement($e, "company");
        
    return $form;
}


add_action("wpjb_front_pre_render", "wpjb_aurl_pre_render", 10, 2);

function wpjb_aurl_pre_render($app, $file) {

    if(!wpjb_is_routed_to("index.single")) {
        return;
    }
    
    $job = $app->controller->view->job;
    
    if(!$job->meta->application_url->value()) {
        return;
    }
    
    $app->controller->view->application_url = $job->meta->application_url->value();
    
}

?>
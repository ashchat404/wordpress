<p>
    <label for="<?php echo $widget->get_field_id("title") ?>">
    <?php _e("Title", "wpjobboard") ?>
    <?php Daq_Helper_Html::build("input", array(
        "id" => $widget->get_field_id("title"),
        "name" => $widget->get_field_name("title"),
        "value" => $instance["title"],
        "type" => "text",
        "class"=> "widefat",
        "maxlength" => 100
    )); 
    ?>
   </label>
</p>

<p>
    <label for="<?php echo $widget->get_field_id("list") ?>">
    <?php _e("List jobs by") ?>
    <?php
        $e = new Daq_Form_Element_Select($widget->get_field_name("list"));
        $e->addClass("widefat");
        $e->addOption(1, 1, "Country");
        $e->addOption(2, 2, "State");
        $e->addOption(3, 3, "City");
        $e->addOption(4, 4, "City, State");
        $e->addOption(5, 5, "Country, City");
        $e->setValue($instance["list"]);
        
        echo $e->render();
    ?>
    </label>
</p>

<p>
   <label for="<?php echo $widget->get_field_id("count") ?>">
   <?php _e("Show jobs count", "wpjobboard") ?>
   <?php Daq_Helper_Html::build("input", array(
       "id" => $widget->get_field_id("count"),
       "name" => $widget->get_field_name("count"),
       "checked" => (int)$instance["count"],
       "value" => 1,
       "type" => "checkbox",
       "class"=> ""
   )); 
   ?>
   </label>
</p>

<p>
   <label for="<?php echo $widget->get_field_id("hide") ?>">
   <?php _e("Show on job board only", "wpjobboard") ?>
   <?php Daq_Helper_Html::build("input", array(
       "id" => $widget->get_field_id("hide"),
       "name" => $widget->get_field_name("hide"),
       "checked" => (int)$instance["hide"],
       "value" => 1,
       "type" => "checkbox",
       "class"=> ""
   )); 
   ?>
   </label>
</p>

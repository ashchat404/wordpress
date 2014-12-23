<?php

function daq_form_layout(Daq_Form_Abstract $form, $options = array()) {
    
    extract($options);
    if(isset($exclude_fields)) {
        $exclude_fields = explode(",", $exclude_fields);
        $exclude_fields = array_map("trim", $exclude_fields);
    } else {
        $exclude_fields = array();
    }
    
    if(isset($exclude_groups)) {
        $exclude_groups = explode(",", $exclude_groups);
        $exclude_groups = array_map("trim", $exclude_groups);
    } else {
        $exclude_groups = array();
    }
?>

<?php echo $form->renderHidden() ?>
<?php foreach($form->getReordered() as $group): ?>

<?php if(count($group->getReordered()) < 1) continue; ?>
<?php if(in_array($group->getName(), $exclude_groups)) continue; ?>
<?php if(!$group->hasVisibleElements($exclude_fields)) continue; ?>

<div class="postbox wpjb-namediv">
    <h3><?php esc_html_e($group->title) ?></h3>
    <div class="inside">
        <table class="form-table">
            <tbody>
            <?php foreach($group->getReordered() as $field): ?>
            <?php /* @var $field Daq_Form_Element */ ?>
            <?php if(in_array($field->getName(), $exclude_fields)) continue; ?>
                <tr valign="top" class="<?php if($field->hasErrors()): ?>error<?php endif; ?>">
                <?php if($field->getName()=="job_title" || $field->getType()=="textarea"): ?>
                <td class="wpjb-td-first" colspan="2">
                    <label for="<?php esc_attr_e($field->getName()) ?>">
                        <?php esc_html_e($field->getLabel()) ?>
                        <?php if($field->isRequired()): ?>
                        <span class="wpjb-red">*</span>
                        <?php endif; ?>
                    </label>
                <?php else: ?>
                <td class="wpjb-td-first" valign="top">
                    <label for="<?php esc_attr_e($field->getName()) ?>">
                        <?php esc_html_e($field->getLabel()) ?>
                        <?php if($field->isRequired()): ?>
                        <span class="wpjb-red">*</span>
                        <?php endif; ?>
                    </label>
                </td>
                <td>
                <?php endif; ?>
                    <?php if(!$field->hasRenderer()): ?>
                    <?php echo $field; ?>
                    <?php else: ?>
                    <?php echo call_user_func($field->getRenderer(), $field, $form); ?>
                    <?php endif; ?>

                    <?php if($field->getHint()): ?>
                    <br/><span class="description"><?php echo $field->getHint() ?></span>
                    <?php endif ?>
                    
                    <?php if($field->hasErrors()): ?>
                    <ul class="updated wpjb-error-list">
                        <li><strong><?php _e("Following errors occured", "wpjobboard") ?></strong></li>
                        <?php foreach($field->getErrors() as $error): ?>
                        <li><?php esc_html_e($error) ?></li>
                        <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
            
            
            </tbody>
        </table>
        <br />
    </div>
</div>
<?php endforeach; ?>


<?php
    
}

function daq_form_layout_config(Daq_Form_Abstract $form, $options = array()) {
    
    extract($options);
    if(isset($exclude_fields)) {
        $exclude_fields = explode(",", $exclude_fields);
        $exclude_fields = array_map("trim", $exclude_fields);
    } else {
        $exclude_fields = array();
    }
    if(isset($exclude_groups)) {
        $exclude_groups = explode(",", $exclude_groups);
        $exclude_groups = array_map("trim", $exclude_groups);
    } else {
        $exclude_groups = array();
    }
    
    $a = array(Daq_Form_Element::TYPE_CHECKBOX, Daq_Form_Element::TYPE_RADIO);
    ob_start();
?>
<table class="form-table" style="max-width:900px">
<tbody>
<?php echo $form->renderHidden() ?>
<?php foreach($form->getReordered() as $group): ?>
<?php if(in_array($group->getName(), $exclude_groups)) continue; ?>
<?php if($group->title): ?>    
        
<tr valign="top">
    <th colspan="2" style="padding-bottom:0px">
        <h3 style="border-bottom:1px solid #dfdfdf; line-height:1.4em; font-size:15px"><?php echo $group->title ?></h3>
    </th>
</tr>

<?php endif; ?>
<?php foreach($group->getReordered() as $field): ?>
<?php if($field->getType() == Daq_Form_Element::TYPE_TEXT) $field->addClass("regular-text") ?>
    
<tr valign="top" class="<?php if($field->hasErrors()): ?>error<?php endif ?>">
    <th scope="row">
        <label <?php if(!in_array($field->getType(), $a)): ?>for="<?php esc_html_e($field->getName()) ?>"<?php endif ?>>
            <?php echo $field->getLabel() ?>
            <?php if($field->isRequired()): ?><span class="wpjb-red">&nbsp;*</span><?php endif; ?>
        </label>
    </th>
    <td>
        <?php if(!$field->hasRenderer()): ?>
        <?php echo $field; ?>
        <?php else: ?>
        <?php echo call_user_func($field->getRenderer(), $field, $form); ?>
        <?php endif; ?>
        
        <?php if($field->getHint()): ?>
        <br/><span class="description"><?php echo $field->getHint() ?></span>
        <?php endif ?>
        
        <?php if($field->hasErrors()): ?>
        <ul class="updated wpjb-error-list">
            <li><strong><?php _e("Following errors occured:", "wpjobboard") ?></strong></li>
            <?php foreach($field->getErrors() as $err): ?>
            <li><?php esc_html_e($err) ?></li>
            <?php endforeach; ?>
        </ul>
        <?php endif; ?>
    </td>
</tr>

<?php endforeach; ?>
<?php endforeach; ?>
</tbody>
</table>
<?php

    return ob_get_clean();

}

function wpjb_form_resume_preview(Daq_Form_Abstract $form, $options = array()) {
    
    extract($options);
    if(isset($exclude_fields)) {
        $exclude_fields = explode(",", $exclude_fields);
        $exclude_fields = array_map("trim", $exclude_fields);
    } else {
        $exclude_fields = array();
    }
    
    if(isset($exclude_groups)) {
        $exclude_groups = explode(",", $exclude_groups);
        $exclude_groups = array_map("trim", $exclude_groups);
    } else {
        $exclude_groups = array();
    }
?>

<?php echo $form->renderHidden() ?>
<?php foreach($form->getReordered() as $group): ?>
<?php if($group->isTrashed()) continue ?>
<?php if(in_array($group->getName(), $exclude_groups)) continue; ?>

<div class="stuffbox wpjb-namediv">
    <h3>
        <?php esc_html_e($group->title) ?> 
        <?php if(!in_array($group->getName(), array("experience", "education"))): ?>
        <a class="" href="<?php echo wpjb_admin_url("resumes", "edit", $form->getId(), array("part"=>$group->getName())) ?>" style="font-weight:strong">Edit</a>
        <?php endif; ?>
    </h3>
    <?php if($group->getName() == "experience"): ?>
    <?php foreach($form->getObject()->getExperience() as $detail): ?>
    <div class="inside wpjb-resume-detail">
        
        <div class="actions">
            <a href="<?php echo wpjb_admin_url("resumes", "editdetail", $detail->id) ?>" class="button"><?php _e("Edit", "wpjobboard") ?></a>
            <a href="<?php echo wpjb_admin_url("resumes", "deletedetail", $detail->id) ?>" class="button"><?php _e("Delete", "wpjobboard") ?></a>
        </div>
        <div>
            <b><?php esc_html_e($detail->detail_title) ?></b>

            <div class="date-range">
            <?php $glue = "" ?>
            <?php if($detail->started_at != "0000-00-00"): ?>
            <?php esc_html_e(date_i18n("M Y", strtotime($detail->started_at))) ?>
            <?php $glue = "-"; ?>
            <?php endif; ?>
            
            <?php if($detail->is_current): ?>
            <?php echo $glue." "; esc_html_e("Current", "wpjobboard") ?>
            <?php elseif($detail->completed_at != "0000-00-00"): ?>
            <?php echo $glue." "; esc_html_e(date_i18n("M Y", strtotime($detail->completed_at))) ?>
            <?php endif; ?>
            </div>
            
            <?php if($detail->grantor): ?>
            <br/><em><?php esc_html_e($detail->grantor) ?></em>
            <?php endif; ?>
        </div>
        
        <?php if($detail->detail_description): ?>
        <?php echo $detail->detail_description ?>
        <?php endif; ?>
    </div>
    <?php endforeach; ?>
    <div class="inside">
        <a href="<?php echo wpjb_admin_url("resumes", "adddetail", null, array("detail"=>$group->getName(), "resume_id"=>$form->getId())) ?>" class="button"><?php _e("Add Experience ...", "wpjobboard") ?></a>
    </div>
    <?php elseif($group->getName() == "education"): ?>
    <?php foreach($form->getObject()->getEducation() as $detail): ?>
    <div class="inside wpjb-resume-detail">
        
        <div class="actions">
            <a href="<?php echo wpjb_admin_url("resumes", "editdetail", $detail->id) ?>" class="button"><?php _e("Edit", "wpjobboard") ?></a>
            <a href="<?php echo wpjb_admin_url("resumes", "deletedetail", $detail->id) ?>" class="button"><?php _e("Delete", "wpjobboard") ?></a>
        </div>
        <div>
            <b><?php esc_html_e($detail->detail_title) ?></b>

            <div class="date-range">
            <?php $glue = "" ?>
            <?php if($detail->started_at != "0000-00-00"): ?>
            <?php esc_html_e(date_i18n("M Y", strtotime($detail->started_at))) ?>
            <?php $glue = "-"; ?>
            <?php endif; ?>
            
            <?php if($detail->is_current): ?>
            <?php echo $glue." "; esc_html_e("Current", "wpjobboard") ?>
            <?php elseif($detail->completed_at != "0000-00-00"): ?>
            <?php echo $glue." "; esc_html_e(date_i18n("M Y", strtotime($detail->completed_at))) ?>
            <?php endif; ?>
            </div>
            
            <?php if($detail->grantor): ?>
            <br/><em><?php esc_html_e($detail->grantor) ?></em>
            <?php endif; ?>
        </div>
        
        <?php if($detail->detail_description): ?>
        <?php echo $detail->detail_description ?>
        <?php endif; ?>
    </div>
    <?php endforeach; ?>
    <div class="inside">
        <a href="<?php echo wpjb_admin_url("resumes", "adddetail", null, array("detail"=>$group->getName(), "resume_id"=>$form->getId())) ?>" class="button"><?php _e("Add Education ...", "wpjobboard") ?></a>
    </div>
    <?php else: ?>
    <div class="inside">
        
        <table class="form-table">
            <tbody>
            <?php foreach($group->getReordered() as $field): ?>
            <?php /* @var $field Daq_Form_Element */ ?>
            <?php if(in_array($field->getName(), $exclude_fields)) continue; ?>
                <tr valign="top" class="">
 
                <td class="wpjb-td-first">
                    <label for="<?php esc_attr_e($field->getName()) ?>">
                        <?php esc_html_e($field->getLabel()) ?>
                    </label>
                </td>
                <td>
                    <?php if($field instanceof Daq_Form_Element_Multi): ?>
                    <?php esc_html_e($field->getValueText()) ?>
                    <?php elseif($field instanceof Daq_Form_Element_Textarea): ?>
                    <?php echo ($field->getValue() ? wpjb_rich_text($field->getValue(), "html") : "-") ?>
                    <?php elseif($field instanceof Daq_Form_Element_File): ?>
                    
                    <?php if(isset($form->getObject()->file->{$field->getName()})) foreach($form->getObject()->file->{$field->getName()} as $file): ?>
                        <a href="<?php esc_attr_e($file->url) ?>" rel="nofollow"><?php esc_html_e($file->basename) ?></a>
                        <?php echo wpjb_format_bytes($file->size) ?><br/>
                    <?php endforeach; ?>

                    <?php else: ?>
                    <?php esc_html_e($field->getValue() ? $field->getValue() : "-") ?>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
            
            
            </tbody>
        </table>
        <br />
    </div>
    <?php endif; ?>
</div>
<?php endforeach; ?>


<?php
    
}

function wpjb_title_slug($e, $form) {
    
    echo $e->render();
    
    $model = new Wpjb_Model_Job();
    $model->job_slug = '<span title="Temporary permalink. Click to edit this part." id="editable-post-name"></span>';
    
    ?>

    <div class="inside"  style="padding-left:0px;">
        <div id="edit-slug-box" style="visibility:hidden">
            <strong><?php _e("Permalink") ?>:</strong>
            <span id="sample-permalink"><?php echo wpjb_link_to("job", $model) ?></span>
            &lrm;
            <span id="edit-slug-buttons" class="wpjb-slug-buttons">
                <a class="edit-slug button hide-if-no-js" href="#post_name"><?php _e("Edit") ?></a>
                <?php if($form->getId()): ?>
                <a class="view-slug button hide-if-no-js" href="<?php echo wpjb_link_to("job", $form->getObject()) ?>"><?php _e("View") ?></a>
                <?php endif; ?>
                <a class="save button" href="#"><?php _e("OK") ?></a> 
                <a href="#" class="cancel"><?php _e("Cancel") ?></a>
            </span>
        </div>
    </div>

    <?php
    
}

function wpjb_activation_message() {

    $amh = wpjb_conf("activation_message_hide", 0);

    $c1 = (bool)strlen(wpjb_conf("license_key"));
    $c2 = false;
    foreach((array)wp_get_sidebars_widgets() as $name => $sidebar) {
        if(in_array($name, array("wp_inactive_widgets"))) {
            continue;
        }
        if(!is_array($sidebar)) {
            continue;
        }
        
        foreach($sidebar as $widget) {
            if(stripos($widget, "wpjb-job-board-menu-") === 0) {
                $c2 = true;
                break;
            }
            if(stripos($widget, "wpjb-resumes-menu-") === 0) {
                $c2 = true;
                break;
            }
        }
    }
    
    if($c1 && $c2) {
        $config = Wpjb_Project::getInstance();
        $config->setConfigParam("activation_message_hide", 1);
        $config->saveConfig();
        
        return;
    }
    
    $url_config = esc_attr(wpjb_admin_url("config", "edit", null, array("form"=>"license")));
    $url_widgets = esc_attr(admin_url("widgets.php"));

?>
<style type="text/css">
.wpjb-post-activation {
    border: 1px solid #464646;
    background: url("../wp-content/plugins/wpjobboard/public/images/admin-icons/job_board_color.png") no-repeat 10px 20px;
    background-color: whitesmoke;
    margin: 15px 5px 15px 5px;
    padding: 5px 5px 5px 50px;
}
.wpjb-post-activation .del {
    text-decoration: line-through;
}
</style>
<script type="text/javascript">
jQuery(function($) {
    $(".wpjb-post-activation-hide").click(function() {
        jQuery.ajax({
            type: "POST",
            url: ajaxurl,
            dataType: "json",
            data: {
                action: "wpjb_main_hide"
            },
            success: function() {
            }
        });
        
        $(".wpjb-post-activation").fadeOut();
    });
});
</script>
<div class="wpjb-post-activation">
    <p><?php _e("Thank you for using <strong>WPJobBoard</strong>! There are few things you can do to get started:", "wpjobboard") ?></p>
    <ol>
        <li class="<?php if($c1) echo "del" ?>"><?php printf(__('<a href="%s">Enter your license code</a> in order to enable automatic updates.', "wpjobboard"), $url_config) ?></li>
        <li class="<?php if($c2) echo "del" ?>"><?php printf(__('Add "Job Board Menu" and/or "Resumes Menu" <a href="%s">widgets to the sidebar</a> (they have all the job board navigation links).', "wpjobboard"), $url_widgets) ?></li>
    </ol>
    <p><a class="button wpjb-post-activation-hide"><?php _e("Do not show this again", "wpjobboard") ?></a>
</div>

<?php

}

function wpjb_admin_pricing_render($e) {
    
    $value = $e->getValue();
    $options = $e->getOptions();
    
    if(empty($options)) {
        $pfor = str_replace("items_", "", $e->getName());
        if($pfor == Wpjb_Model_Pricing::PRICE_SINGLE_JOB) {
            $listing = "single-job";
        } else {
            $listing = "single-resume";
        }
        
        Daq_Helper_Html::build("a", array(
            "class" => "button",
            "href" => wpjb_admin_url("pricing", "add", null, array("listing"=>$listing))
        ), sprintf(__("Add New '%s' Option", "wpjobboard"), $e->getLabel()));
        return;
    }
    
    foreach($e->getOptions() as $option) {
        
        $pricing = new Wpjb_Model_Pricing($option["key"]);
        $param = array("ID: ".$pricing->id);
        
        if($pricing->meta->is_featured->value()) {
            $param[] = __("Featured", "wpjobboard");
        }
        if($pricing->meta->visible->value()) {
            $param[] = sprintf(__("Days Visible: %d", "wpjobboard"), $pricing->meta->visible->value());
        }
        
        $opt = array("status"=>"disabled", "usage"=>"");
        if(isset($value[$pricing->id])) {
            $opt = $value[$pricing->id];
        }
        
        ?>
        <select name="<?php esc_attr_e($e->getName()) ?>[<?php echo $pricing->id ?>][status]" class="wpjb-membership-usage">
            <option value="disabled" <?php selected($opt["status"], "disabled") ?>><?php _e("Not Included", "wpjobboard") ?></option>
            <option value="limited" <?php selected($opt["status"], "limited") ?>><?php _e("Limited", "wpjobboard") ?></option>
            <option value="unlimited" <?php selected($opt["status"], "unlimited") ?>><?php _e("Unlimited", "wpjobboard") ?></option>
        </select>
        <input type="text" name="<?php esc_attr_e($e->getName()) ?>[<?php echo $pricing->id ?>][usage]" size="4" value="<?php esc_attr_e($opt["usage"]) ?>" />
        <label>
            <strong><?php _e($pricing->title) ?></strong>
            <?php if(!empty($param)): ?>(<?php echo join(", ", $param) ?>)<?php endif; ?>
        </label>
        
        <br/>
        <?php
    }
}

function wpjb_admin_membership_render($e, $form) {
    
    $value = $e->getValue();
    $mlist = $form->getMembership();
    
    foreach($e->getOptions() as $option) {
        
        $pricing = new Wpjb_Model_Pricing($option["key"]);
        $param = array("ID: ".$pricing->id);
        $mdata = null;
        
        if(isset($mlist[$pricing->price_for][$pricing->id])) {
            $mdata = $mlist[$pricing->price_for][$pricing->id];
        }
        
        if($pricing->meta->is_featured->value()) {
            $param[] = __("Featured", "wpjobboard");
        }
        if($pricing->meta->visible->value()) {
            $param[] = sprintf(__("Days Visible: %d", "wpjobboard"), $pricing->meta->visible->value());
        }
        
        $opt = array("status"=>"disabled", "usage"=>"", "used"=>"");
        if(isset($value[$pricing->id])) {
            $opt = $value[$pricing->id];
        }

        ?>
        <select name="<?php esc_attr_e($e->getName()) ?>[<?php echo $pricing->id ?>][status]" class="wpjb-membership-usage" style="width:auto">
            <option value="disabled" <?php selected($opt["status"], "disabled") ?>><?php _e("Not Included", "wpjobboard") ?></option>
            <option value="limited" <?php selected($opt["status"], "limited") ?>><?php _e("Limited", "wpjobboard") ?></option>
            <option value="unlimited" <?php selected($opt["status"], "unlimited") ?>><?php _e("Unlimited", "wpjobboard") ?></option>
        </select>
        <span>
            <input type="text" name="<?php esc_attr_e($e->getName()) ?>[<?php echo $pricing->id ?>][used]" size="4" value="<?php esc_attr_e($opt["used"]) ?>" /> /
            <input type="text" name="<?php esc_attr_e($e->getName()) ?>[<?php echo $pricing->id ?>][usage]" size="4" value="<?php esc_attr_e($opt["usage"]) ?>" />
        </span>
        <label>
            <strong><?php _e($pricing->title) ?></strong>
            <?php if(!empty($param)): ?>(<?php echo join(", ", $param) ?>)<?php endif; ?>
        </label>
        
        <br/>
        <?php
    }
}

function wpjb_admin_variable_renderer($field) {
    
    $job = new Wpjb_Model_Job();
    $job = $job->toArray();
    unset($job["read"]);
    unset($job["cache"]);
    $job["tag"] = array("category", "type");
    
    $vars = array();
    $vars[] = array(
        "var" => "job",
        "title" => __("Job Variable", "wpjobboard"),
        "item" => $job
    );
    
    $objects = (array)$field->getValue();
    $customs = null;
    
    ?>
    
    <?php foreach($objects as $o): ?>
        <input type="hidden" name="<?php esc_attr_e($field->getName()) ?>" value="<?php esc_attr_e($o) ?>" />
    <?php endforeach; ?>
        
    <div id="wpjb-mail-var-wrap">

        <?php foreach($vars as $var): ?>
        <?php if(!in_array($var["var"], $objects)) continue; ?>
        <div class="widget wpjb-mail-var-widget">	
             <div class="widget-top">
                <div class="widget-title-action">
                    <a href="#available-widgets" class="widget-action hide-if-no-js"></a>
                </div>
                <div class="widget-title"><h4><?php esc_html_e($var["title"]) ?></h4></div>
            </div>

            <div class="widget-inside">
                <div class="widget-content" style="">
                    <h3><?php _e("Variables", "wpjobboard") ?></h3>
                    <?php foreach($var["item"] as $k => $v): ?>
                    <?php if(is_array($v)) continue; ?>
                    <p>
                        <attr title="<?php echo ucfirst(str_replace("_", " ", $k)) ?>"><img class="wpjb-mail-var-helper" src="<?php esc_attr_e(plugins_url()."/wpjobboard/public/images/question-white.png") ?>" alt="" /></attr>
                        <span class="wpjb-bulb wpjb-mail-var" title="<?php #_e("Click to insert into template", "wpjobboard") ?>">{$<?php echo $var["var"].".".$k ?>}</span>
                    </p>
                    <?php endforeach; ?>

                    <?php if(!empty($var["item"]["meta"])): ?>
                    <h3><?php _e("Custom Fields", "wpjobboard") ?></h3>
                    <?php foreach($var["item"]["meta"] as $k => $v): ?>
                    <?php foreach(array("name", "title", "value", "values") as $m): ?>
                    <p>
                        <attr title="<?php echo ucfirst(str_replace("_", " ", $k." - ".$m)) ?>"><img class="wpjb-mail-var-helper" src="<?php esc_attr_e(plugins_url()."/wpjobboard/public/images/question-white.png") ?>" alt="" /></attr>
                        <span class="wpjb-bulb wpjb-mail-var" title="<?php #_e("Click to insert into template", "wpjobboard") ?>">{$<?php echo $var["var"].".meta.".$k.".".$m ?>}</span>
                    </p>
                    <?php endforeach; ?>
                    <hr style="width:95%" />
                    <?php endforeach; ?>
                    <?php endif; ?>


                    <?php if(isset($var["item"]["tag"])): ?>
                    <h3><?php _e("Tags (categories and job types)", "wpjobboard") ?></h3>
                    <?php foreach($var["item"]["tag"] as $k): ?>
                    <?php foreach(array("id", "type", "slug", "title") as $t): ?>
                    <p>
                        <attr title="<?php echo ucfirst(str_replace("_", " ", $k)) ?>"><img class="wpjb-mail-var-helper" src="<?php esc_attr_e(plugins_url()."/wpjobboard/public/images/question-white.png") ?>" alt="" /></attr>
                        <span class="wpjb-bulb wpjb-mail-var" title="<?php #_e("Click to insert into template", "wpjobboard") ?>">{$<?php echo $var["var"].".tag.".$k.".0.".$t ?>}</span>
                    </p>
                    <?php endforeach; ?>
                    <hr style="width:95%" />
                    <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>

        <?php if($customs): ?>
        <div class="widget wpjb-mail-var-widget">	
             <div class="widget-top">
                <div class="widget-title-action">
                    <a href="#available-widgets" class="widget-action hide-if-no-js"></a>
                </div>
                <div class="widget-title"><h4><?php _e("Custom Variables", "wpjobboard") ?></h4></div>
            </div>

            <div class="widget-inside">
                <div class="widget-content">
                    <h3><?php _e("Variables", "wpjobboard") ?></h3>
                    <?php foreach($customs as $k => $v): ?>
                    <p>
                        <attr title="<?php echo $v ?>"><img class="wpjb-mail-var-helper" src="<?php esc_attr_e(plugins_url()."/wpjobboard/public/images/question-white.png") ?>" alt="" /></attr>
                        <span class="wpjb-bulb wpjb-mail-var" title="<?php #_e("Click to insert into template", "wpjobboard") ?>">{$<?php echo $k ?>}</span>
                    </p>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
        
    <script type="text/javascript">
    jQuery(".widget-top").click(function() {
        jQuery(this).closest("div.widget").find(".widget-inside").toggle();
        return false;
    });
    </script>
    <?php
}

function wpjb_admin_url($page, $action = null, $id = null, $param = array()) {
    
    $arr = array(
        "page" => "wpjb-".trim($page, "/"),
        "action" => $action,
        "id" => $id
    );

    foreach($param as $k=>$v) {
        if(strlen($v)>0) {
            $arr[$k] = $v; 
        }
    }
    
    $query = http_build_query($arr);
    
    return admin_url("admin.php?$query");
}

function wpjb_form_val($form, $element, $default = null) {
    if(!$form->hasElement($element)) {
        return null;
    }
    
    $v = $form->getElement($element)->getValue();
    
    if(!$v && $default) {
        return $default;
    } else {
        return $v;
    }
}

function wpjb_form_val_e($form, $element, $default = null) {
    echo wpjb_form_val($form, $element, $default);
}

function wpjb_column_sort($sorted, $order) {
    if($sorted) {
        echo " sorted ";
    } else {
        echo " desc ";
        return;
    }

    if($order == "desc") {
        echo " desc ";
    } else {
        echo " asc ";
    }
}

function wpjb_column_order($sorted, $order) {
    if(!$sorted) {
        return "asc";
    }

    if($order == "asc") {
        return "desc";
    } else {
        return "asc";
    }

}

?>

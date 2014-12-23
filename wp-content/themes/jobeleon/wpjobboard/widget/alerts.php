<?php echo $theme->before_widget ?>
<?php if ($title) echo $theme->before_title . $title . $theme->after_title ?>

<?php
$keyword_label = __("Keyword", "jobeleon");
$email_label = __("E-Mail", "jobeleon");
?>

<?php if($is_smart): ?>

<div class="wpjb-widget-smart-alert">
    <div><p><?php _e("Like this job search results?", "jobeleon") ?></p></div>
    <a href="#" class="wpjb-subscribe wpjb-button" style="width:100%;display:inline-block;box-sizing:border-box;"><?php _e("Subscribe Now ...", "jobeleon") ?></a>
</div>

<?php else: ?>

<form action="<?php esc_attr_e(wpjb_link_to("alert_confirm")) ?>" method="post">
    <input type="hidden" name="add_alert" value="1" />
    <ul id="wpjb_widget_alerts" class="wpjb_widget">
            <li>
                <label class="wpjb-label"><?php echo $keyword_label ?>:</label>
                <input type="text" name="keyword" value="" placeholder="<?php echo $keyword_label ?>"  />
            </li>
            <li>
                <label class="wpjb-label"><?php echo $email_label ?>:</label>
                <input type="text" name="email" value="" placeholder="<?php echo $email_label; ?>" />
            </li>
            <li>
                <input type="submit" value="<?php _e("Add Alert", "jobeleon") ?>" />
            </li>
    </ul>
</form>

<?php endif; ?>
<?php echo $theme->after_widget ?>

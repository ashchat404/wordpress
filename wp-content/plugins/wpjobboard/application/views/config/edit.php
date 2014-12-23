<div class="wrap">
 
<?php $this->_include("header.php"); ?>
    
<h2>
    <?php esc_html_e($form->name) ?>
    <a class="add-new-h2" href="<?php echo wpjb_admin_url("config"); ?>"><?php _e("Go back &raquo;", "wpjobboard") ?></a> 
</h2>

<?php $this->_include("flash.php"); ?>

<?php if($show_form): ?>
<form action="<?php esc_attr_e($submit_action) ?>" method="post" class="wpjb-form">
    <table class="form-table">
        <tbody>
            <?php echo daq_form_layout_config($form) ?>
        </tbody>
    </table>

    <p class="submit">
    <input type="submit" value="<?php esc_attr_e($submit_title) ?>" class="button-primary" name="Submit"/>
    <?php do_action("wpjb_config_edit_buttons") ?>
    
    <?php if($section == "twitter"): ?>
    <input type="submit" value="<?php _e("Save and send test tweet", "wpjobboard") ?>" class="" name="saventest"/>
    <?php endif; ?>
    </p>

</form>
<?php endif; ?>


    
<?php $this->_include("footer.php"); ?>

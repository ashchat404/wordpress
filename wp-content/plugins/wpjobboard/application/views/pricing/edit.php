<div class="wrap">
    
<?php $this->_include("header.php") ?>
    <h2>
        <?php esc_html_e($title) ?>
        <a class="add-new-h2" href="<?php echo wpjb_admin_url("pricing", "list", null, array("listing"=>$listing)) ?>"><?php _e("Go back &raquo;", "wpjobboard") ?></a> 
    </h2>

<?php $this->_include("flash.php"); ?>

<form action="" method="post" class="wpjb-form">
    <table class="form-table">
        <tbody>
        <?php echo daq_form_layout_config($form) ?>
        </tbody>
    </table>

    <p class="submit">
    <input type="submit" value="<?php _e("Save Changes", "wpjobboard") ?>" class="button-primary" name="Submit"/>
    </p>

</form>

<?php $this->_include("footer.php"); ?>
    
<?php if($listing == "employer-membership"): ?>
<script type="text/javascript">
jQuery(function($) {
    $(".wpjb-membership-usage").change(function() {
        var val = $(this).val();
        if(val != "limited") {
            $(this).next().attr("readonly", "readonly").val("");
        } else {
            $(this).next().attr("readonly", null);
        }
    });
    
    $(".wpjb-membership-usage").change();
});
</script>
<?php endif; ?>

</div>
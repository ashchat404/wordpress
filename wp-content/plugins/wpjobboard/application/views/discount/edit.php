<div class="wrap">
<?php $this->_include("header.php"); ?>
    <h2>
    <?php if($form->getObject()->getId()>0): ?>
    <?php  _e("Edit Promotion", "wpjobboard") ?>
    <?php else: ?>
    <?php  _e("Add Promotion", "wpjobboard"); ?>
    <?php endif; ?>
        
    <a class="add-new-h2" href="<?php echo wpjb_admin_url("discount") ?>"><?php _e("Go back &raquo;", "wpjobboard") ?></a>  
        
    </h2>

<?php $this->_include("flash.php"); ?>

<form action="" method="post">
    <table class="form-table">
        <tbody>
        <?php echo daq_form_layout_config($form) ?>
        </tbody>
    </table>

    <p class="submit">
    <input type="submit" value="<?php _e("Save Changes", "wpjobboard") ?>" class="button-primary" name="Submit"/>
    </p>

</form>
    
<script type="text/javascript">
jQuery(function($) {
    
    $("#expires_at").DatePicker({
        format:wpjb_admin_lang.date_format,
        date: $("#expires_at").val(),
        current: $("#expires_at").val(),
        starts: 1,
        position: 'r',
        onChange: function(formated, dates){
            $("#expires_at").DatePickerHide();
            $("#expires_at").attr("value", formated);
        }
    });
});
</script>

<?php $this->_include("footer.php"); ?>

</div>
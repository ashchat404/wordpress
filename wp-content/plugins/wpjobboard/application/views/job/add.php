<div class="wrap">
<?php $this->_include("header.php"); ?>
    
<h2><?php _e("Add New Job", "wpjobboard"); ?>  <a class="add-new-h2" href="<?php esc_html_e(wpjb_admin_url("job", "add")) ?>"><?php _e("Add New", "wpjobboard") ?></a> </h2>

<?php $this->_include("flash.php"); ?>

<script type="text/javascript">
    <?php $value = $form->getValues(); ?>
    Wpjb.JobState = "<?php echo($value['job_country'] == 840 ? '' : $form->getObject()->job_state); ?>";
    Wpjb.Id = <?php echo $form->getObject()->getId() ?>;
</script>

<form action="" method="post" enctype="multipart/form-data" class="wpjb-form">

<div class="wpjb-sticky-anchor"></div>
<div class="metabox-holder has-right-sidebar" id="poststuff" >
<div class="inner-sidebar wpjb-sticky" id="side-info-column">
<div class="meta-box-sortables ui-sortable" id="side-sortables"><div class="postbox " id="submitdiv">
<div class="handlediv"><br></div><h3 class="hndle"><span><?php _e("Listing", "wpjobboard") ?></span></h3>
<div class="inside">
<div id="submitpost" class="submitbox">

<div id="minor-publishing">

<div id="misc-publishing-actions">
<div class="misc-pub-section">
    <span id="timestamp"><?php _e("Post as", "wpjobboard") ?> <b class="company-edit-label">&nbsp;</b></span>
    <a class="employer-edit hide-if-no-js" href="#"><?php _e("Edit") ?></a>
    <div class="employer hide-if-js">
        <?php echo $form->getElement("employer_id")->render(); ?>
        <a href="#" id="employer-cancel"><?php _e("Cancel", "wpjobboard") ?></a>
    </div>
</div>
    
<div class="misc-pub-section">
    <span><?php _e("Listing", "wpjobboard") ?> <b class="listing-type"><?php _e("Custom", "wpjobboard") ?></b></span>
    <a id="listing-type-link" class="edit-timestamp hide-if-no-js" href="#"><?php _e("Edit") ?></a>
    <div class="listing-type-change">
        <?php echo $form->getElement("listing_type")->render() ?>
        <a href="#" id="listing-type-cancel"><?php _e("Cancel", "wpjobboard") ?></a>
    </div>

</div>
<div class="misc-pub-section curtime ">
    <span id="created_at"><?php _e("Publish") ?> <b class="job_created_at">&nbsp;</b></span>
    <a id="job_created_at_link" class="edit-timestamp hide-if-no-js" href="#"><?php _e("Edit") ?></a>
    <input type="text" id="job_created_at" value="<?php esc_attr_e(wpjb_date($form->value("job_created_at"))) ?>" name="job_created_at" style="visibility:hidden;padding:0;width:1px" size="1" />
</div>
<div class="misc-pub-section curtime ">
    <span id="expires_at"><?php _e("Expires", "wpjobboard") ?> <b class="job_expires_date">&nbsp;</b></span>
    <a id="job_expires_at_link" class="edit-timestamp hide-if-no-js" href="#"><?php _e("Edit") ?></a>
    <a id="job_expires_never" class="edit-timestamp hide-if-no-js" href="#"><?php _e("Never Expires", "wpjobboard") ?></a>
    <input type="text" id="job_expires_at" value="<?php esc_attr_e(wpjb_date($form->value("job_expires_at"))) ?>" name="job_expires_at" style="visibility:hidden;padding:0;width:1px" size="1" />
</div>
    
    
<div class="misc-pub-section misc-pub-section-last ">
    <?php echo $form->getElement("is_active")->render(); ?><br/>
    <?php echo $form->getElement("is_featured")->render(); ?><br/>
    <?php echo $form->getElement("is_filled")->render(); ?><br/>
</div>

</div>
</div>

<div id="major-publishing-actions">
<div id="publishing-action">
    <img alt="" id="ajax-loading" class="ajax-loading" src="<?php esc_attr_e(admin_url("/images/wpspin_light.gif")) ?>" style="display:none">
    <input type="submit" accesskey="p" tabindex="5" value="<?php _e("Publish") ?>" class="button-primary" id="publish" name="publish"></div>
    <div class="clear"></div>
</div>
</div>

</div>
</div>
    


</div>
 
<?php if($form->hasElement("payment_method")): ?>
<div class="postbox " id="submitdiv">
    <div class="handlediv"><br /></div>
    <h3 class="hndle"><span><?php _e("Payment", "wpjobboard") ?></span></h3>
    
    <div class="inside">
        <div id="submitpost" class="submitbox">

        <div class="misc-pub-section wpjb-payment-method">
            <?php _e("Payment method", "wpjobboard") ?> <b class="payment_method">&nbsp;</b>
            <a id="payment_method_link" class="edit-timestamp hide-if-no-js" href="#"><?php _e("Edit") ?></a>
            <div class="payment-method">
                <?php echo $form->getElement("payment_method")->render(); ?>
                <a href="#" id="payment-method-cancel"><?php _e("Cancel", "wpjobboard") ?></a>
            </div>
        </div>

        <div class="misc-pub-section misc-pub-section-last wpjb-payment-details">
            <?php _e("To pay", "wpjobboard") ?> 
            <?php echo $form->getElement("payment_sum")->render() ?>
            <?php echo $form->getElement("payment_currency")->render() ?>
            
            
        </div>

        </div>

    </div>
</div>  
<?php endif; ?>
    
    
    
</div>

    <div id="post-body">
        <div id="post-body-content">

        <?php daq_form_layout($form, array("exclude_fields"=>"payment_method", "exclude_groups"=>"_internal")) ?>

        </div>
        <p class="submit">
            <input type="submit" value="<?php _e("Publish Job", "wpjobboard") ?>" class="button-primary" name="Submit"/>
        </p>
    </div>

    <br class="clear">
</div>

</form>


<script type="text/javascript">
var Today = '<?php echo wpjb_date(date("Y-m-d")) ?>';
var Pricing = <?php echo json_encode($pricing) ?>;
</script>

<?php $this->_include("footer.php"); ?>
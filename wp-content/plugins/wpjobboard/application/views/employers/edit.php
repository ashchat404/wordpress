<div class="wrap">
<?php $this->_include("header.php"); ?>
    

<h2>
<?php if($form->getId()>0): ?>
    <?php _e("Edit Employer", "wpjobboard"); ?> (ID: <?php echo $form->getId() ?>)
<?php else: ?>
    <?php _e("Add New Employer", "wpjobboard"); ?>
<?php endif; ?>
    <a class="add-new-h2" href="<?php echo wpjb_admin_url("employers", "add") ?>"><?php _e("Add New", "wpjobboard") ?></a> 
</h2>
    
<?php $this->_include("flash.php"); ?>

<form action="" method="post" class="wpjb-form" enctype="multipart/form-data">

<div class="metabox-holder has-right-sidebar" id="poststuff" >
    
<div class="inner-sidebar wpjb-sticky" id="side-info-column" style="">
<div class="meta-box-sortables ui-sortable" id="side-sortables"><div class="postbox " id="submitdiv">
<div class="handlediv"><br></div><h3 class="hndle"><span><?php _e("Employer Account", "wpjobboard") ?></span></h3>
<div class="inside">
<div id="submitpost" class="submitbox">

<div id="minor-publishing">
<?php if($form->getId()>0): ?>
<div class="misc-pub-section wpjb-mini-profile">
    <div class="wpjb-avatar">
    <?php echo get_avatar($form->getObject()->user_id) ?>
    </div>
    <strong><?php esc_html_e($user->display_name) ?></strong><br/>
    <p><?php _e("Login", "wpjobboard") ?>: <b><?php esc_html_e($user->user_login) ?></b></p>
    <p><?php _e("ID", "wpjobboard") ?>: <b><?php echo $user->ID ?></b></p>

        
    <br class="clear" />
        
    <p><a href="<?php esc_attr_e(admin_url("user-edit.php?user_id={$user->ID}")) ?>" class="button"><?php _e("view linked user account", "wpjobboard") ?></a></p>
    <p><a href="<?php esc_attr_e(wpjb_admin_url("job", "index", null, array("employer"=>$form->getId()))) ?>" class="button"><?php printf(__("view employer jobs (%d)", "wpjobboard"), $form->getObject()->jobs_posted) ?></a></p>
    <p><a href="<?php esc_attr_e(wpjb_admin_url("memberships", "index", null, array("user_id"=>$user->ID))) ?>" class="button"><?php printf(__("view memberships (%d)", "wpjobboard"), Wpjb_Model_Membership::search(array("count_only"=>1, "user_id"=>$user->ID))) ?></a></p>
    <p><a href="<?php esc_attr_e(wpjb_link_to("company", $form->getObject())) ?>" class="button"><?php _e("view profile") ?></a></p>

</div>
<?php endif; ?>
    
<div class="misc-pub-section wpjb-inline-section">
    <span id="timestamp"><?php _e("Employer Status", "wpjobboard") ?>: <b class="wpjb-inline-label">&nbsp;</b></span>
    <a class="wpjb-inline-edit hide-if-no-js" href="#"><?php _e("Edit") ?></a>
    <div class="wpjb-inline-field wpjb-inline-select hide-if-js">
        <?php echo $form->getElement("is_verified")->render(); ?>
        <a href="#" class="wpjb-inline-cancel"><?php _e("Cancel", "wpjobboard") ?></a>
    </div>
</div>
<div class="misc-pub-section misc-pub-section-last ">
    <?php echo $form->getElement("is_active")->render(); ?><br/>
</div>

</div>


<div id="major-publishing-actions">   
    <?php if($form->getId()): ?>
    <div id="delete-action">
        <a href="<?php esc_attr_e(wpjb_admin_url("employers", "remove")."&".http_build_query(array("users"=>array($form->getId())))) ?>" class="submitdelete deletion wpjb-delete-item-confirm"><?php _e("Delete", "wpjobboard") ?></a>
    </div>
    <div id="publishing-action">
        <input type="submit" accesskey="p" tabindex="5" value="<?php _e("Update", "wpjobboard") ?>" class="button-primary" id="publish" name="publish">
    </div>
    <?php else: ?>
    <div id="publishing-action">
        <input type="submit" accesskey="p" tabindex="5" value="<?php _e("Add Employer", "wpjobboard") ?>" class="button-primary" id="publish" name="publish">
    </div>
    <?php endif; ?>
    <div class="clear"></div>
</div>
</div>

</div>
</div>
    </div>


    
    
    
</div> 


    
    <div id="post-body">
        <div id="post-body-content">
        <?php echo daq_form_layout($form) ?>

        <p class="submit">
            <input type="submit" value="<?php _e("Save company profile", "wpjobboard") ?>" class="button-primary" name="Submit"/>
        </p>
        </div>
    </div>
    
</div>
</form>


<?php $this->_include("footer.php"); ?>

</div>
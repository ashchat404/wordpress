<div class="wrap">
<?php $this->_include("header.php"); ?>
<h2>
    <?php _e("Edit Candidate", "wpjobboard"); ?>

    <?php if(!empty($part)): ?>
    &raquo; <a href="<?php esc_attr_e(wpjb_admin_url("resumes", "edit", $resume->id)) ?>"><?php echo ($user->first_name || $user->last_name) ? esc_html(trim($user->first_name." ".$user->last_name)) : esc_html("ID: ".$resume->ID) ?></a>
    <?php endif; ?>
</h2>

<?php $this->_include("flash.php"); ?>


<form action="" method="post" enctype="multipart/form-data" class="wpjb-form">
<?php if(empty($part)): ?>
<div class="metabox-holder has-right-sidebar" id="poststuff" >
<div class="inner-sidebar wpjb-sticky" id="side-info-column" style="padding-top:10px">
<div class="meta-box-sortables ui-sortable" id="side-sortables"><div class="postbox " id="submitdiv">
<div class="handlediv"><br></div><h3 class="hndle"><span><?php _e("Candidate", "wpjobboard") ?></span></h3>
<div class="inside">
<div id="submitpost" class="submitbox">

<div id="minor-publishing">

<div class="misc-pub-section wpjb-mini-profile">
    <div class="wpjb-avatar">
    <?php echo get_avatar($resume->user_id) ?>
    </div>
    <strong><?php esc_html_e($user->display_name) ?></strong><br/>
    <p><?php _e("Login", "wpjobboard") ?>: <b><?php esc_html_e($user->user_login) ?></b></p>
    <p><?php _e("ID", "wpjobboard") ?>: <b><?php echo $user->ID ?></b></p>

        
    <br class="clear" />
        
    <p><a href="<?php esc_attr_e(admin_url("user-edit.php?user_id={$user->ID}")) ?>" class="button"><?php _e("view linked user account", "wpjobboard") ?></a></p>
    <p><a href="<?php esc_attr_e(wpjr_link_to("resume", $resume)) ?>" class="button"><?php _e("view resume") ?></a></p>
</div>
  
<div class="misc-pub-section curtime">
    <span><?php _e("Created", "wpjobboard") ?> <b class="resume_created_date"><?php echo wpjb_date($form->getElement("created_at")->getValue()) ?></b></span>
    <a id="resume_created_at_link" class="edit-timestamp hide-if-no-js" href="#"><?php _e("Edit") ?></a>
    <input type="text" id="resume_created_at" value="<?php echo wpjb_date($form->getElement("created_at")->getValue()) ?>" name="created_at" style="visibility:hidden;padding:0;width:1px" size="1" />
</div>
<div class="misc-pub-section curtime wpjb-inline-section">
    <span><?php _e("Modified", "wpjobboard") ?> <b class="resume_modified_date"><?php echo wpjb_date($form->getElement("modified_at")->getValue()) ?></b></span>
    <a id="resume_modified_at_link" class="edit-timestamp hide-if-no-js" href="#"><?php _e("Edit") ?></a>
    <input type="text" id="resume_modified_at" value="<?php echo wpjb_date($form->getElement("modified_at")->getValue()) ?>" name="modified_at" style="visibility:hidden;padding:0;width:1px" size="1" />
</div>
    
<div class="misc-pub-section misc-pub-section-last ">
    <input type="hidden" name="part" value="_internal" />
    <?php echo $form->getElement("is_active")->render(); ?><br/>
</div>

</div>


<div id="major-publishing-actions">    
    <div id="delete-action">
        <a href="<?php esc_attr_e(wpjb_admin_url("resumes", "remove")."&".http_build_query(array("users"=>array($form->getId())))) ?>" class="submitdelete deletion wpjb-delete-item-confirm"><?php _e("Delete", "wpjobboard") ?></a>
    </div>
    <div id="publishing-action">
        <input type="submit" accesskey="p" tabindex="5" value="<?php _e("Update", "wpjobboard") ?>" class="button-primary" id="publish" name="publish">
    </div>
    <div class="clear"></div>
</div>
</div>

</div>
</div>
    </div>


    
    
    
</div> 
    
    <?php else: ?>
    <div>
        <?php endif; ?>
    
    <div class="metabox-holder has-right-sidebar" id="poststuff" >
        <div id="post-body">
            <div id="post-body-content">

            <?php if(!empty($part)): ?>
            <?php daq_form_layout($form) ?>
            <?php else: ?>
            <?php wpjb_form_resume_preview($form) ?>
            <?php endif; ?>

            </div>
        </div>

        <br class="clear" />
        
        <?php if(!empty($part)): ?>
        <p class="submit">
            <input type="submit" value="<?php _e("Update", "wpjobboard") ?>" class="button-primary" name="Save"/>
            <input type="submit" value="<?php _e("Update and Go back", "wpjobboard") ?>" class="button" name="SaveClose"/>
        </p>
        <?php endif; ?>
        
    </div>

</div>

<?php $this->_include("footer.php"); ?>
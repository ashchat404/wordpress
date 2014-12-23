<div class="wrap">
<?php $this->_include("header.php"); ?>
    
<h2>
    <?php _e("Employers", "wpjobboard"); ?>
    <a class="add-new-h2" href="<?php echo wpjb_admin_url("employers", "add") ?>"><?php _e("Add New", "wpjobboard") ?></a> 
</h2>

<?php $this->_include("flash.php"); ?>


<form method="post" action="<?php esc_attr_e(wpjb_admin_url("employers", "redirect", null, array("noheader"=>true))) ?>" id="posts-filter">

<?php if(isset($query) && $query): ?>
<div class="updated fade below-h2" style="background-color: rgb(255, 251, 204);">
    <p>
        <?php printf(__("Your employers list is filtered using following prarmeters <strong>%s</strong>.", "wpjobboard"), $rquery) ?>&nbsp;
        <?php _e("Click here to", "wpjobboard") ?>&nbsp;<a href="<?php esc_attr_e(wpjb_admin_url("employers", "index")); ?>"><?php _e("browse all employers", "wpjobboard") ?></a>.
    </p>
</div>
<?php endif; ?>    
    
<p class="search-box">
    <label for="post-search-input" class="hidden">&nbsp;</label>
    <input type="text" value="<?php esc_html_e($query) ?>" name="query" id="post-search-input" class="search-input"/>
    <input type="submit" class="button" value="<?php _e("Search Employers", "wpjobboard") ?>" />
</p>
    
<div class="tablenav">

<div class="alignleft actions">
    <select id="wpjb-action1" name="action">
        <option selected="selected" value=""><?php _e("Bulk Actions", "wpjobboard") ?></option>
        <option value="activate"><?php _e("Activate", "wpjobboard") ?></option>
        <option value="deactivate"><?php _e("Deactivate", "wpjobboard") ?></option>
        <option value="delete"><?php _e("Delete", "wpjobboard") ?></option>
        <?php if(Wpjb_Project::getInstance()->conf("cv_access")==2): ?>
        <option value="approve"><?php _e("Approve", "wpjobboard") ?></option>
        <option value="decline"><?php _e("Decline", "wpjobboard") ?></option>
        <?php endif; ?>
    </select>

    <input type="submit" class="button-secondary action" id="wpjb-doaction1" value="<?php _e("Apply", "wpjobboard") ?>" />

</div>
    
</div>

<div class="clear"/>&nbsp;</div>

<table cellspacing="0" class="widefat post fixed">
    <?php foreach(array("thead", "tfoot") as $tx): ?>
    <<?php echo $tx; ?>>
        <tr>
            <th style="" class="manage-column column-cb check-column" scope="col"><input type="checkbox"/></th>
            <th style="" class="column-comments" scope="col"><?php _e("Id", "wpjobboard") ?></th>
            <th style="" class="" scope="col"><?php _e("Company Name", "wpjobboard") ?></th>
            <th style="" class="" scope="col"><?php _e("Company Location", "wpjobboard") ?></th>
            <th style="" class="" scope="col"><?php _e("Representative", "wpjobboard") ?></th>
            <th style="" class="fixed column-icon" scope="col"><?php _e("Jobs Posted", "wpjobboard") ?></th>
            <th style="" class="fixed column-icon" scope="col"><?php _e("Status", "wpjobboard") ?></th>
        </tr>
    </<?php echo $tx; ?>>
    <?php endforeach; ?>

    <tbody>
        <?php foreach($data as $i => $item): ?>
        <?php $user = $item->getUser(); ?>
        <tr valign="top" class="<?php if($i%2==0): ?>alternate <?php endif; ?> <?php if($item->is_verified == Wpjb_Model_Company::ACCESS_PENDING): ?>wpjb-unread<?php endif; ?> author-self status-publish iedit">
            <th class="check-column" scope="row">
                <input type="checkbox" value="<?php echo $item->getId() ?>" name="item[]"/>
            </th>
            <td class=""><?php echo $item->getId() ?></td>
            <td class="post-title column-title">
                <strong><a title='<?php _e("Edit", "wpjobboard") ?>  "(<?php echo esc_html($item->company_name) ?>)"' href="<?php echo wpjb_admin_url("employers", "edit", $item->getId()); ?>" class="row-title"><?php echo (strlen($item->company_name)<1) ? '<i>'.__("not set", "wpjobboard").'</i>' : esc_html($item->company_name) ?></a></strong>
                <div class="row-actions">
                    <span class="edit"><a title="<?php _e("Edit", "wpjobboard") ?>" href="<?php echo wpjb_admin_url("employers", "edit", $item->getId()); ?>"><?php _e("Edit", "wpjobboard") ?></a> | </span>
                    <span class="view"><a rel="permalink" title="<?php _e("View Profile", "wpjobboard") ?>" href="<?php echo wpjb_link_to("company", $item); ?>"><?php _e("View Profile", "wpjobboard") ?></a> | </span>
                    <span class="view"><a rel="permalink" title="<?php _e("View Jobs", "wpjobboard") ?>" href="<?php echo wpjb_admin_url("job", "index", null, array("employer"=>$item->getId())); ?>"><?php _e("View Jobs", "wpjobboard") ?></a> | </span>
                    <span class="view"><a rel="permalink" class="wpjb-delete wpjb-no-confirm" title="<?php _e("Delete", "wpjobboard") ?>" href="<?php echo wpjb_admin_url("employers", "remove")."&".http_build_query(array("users"=>array($item->id))); ?>"><?php _e("Delete", "wpjobboard") ?></a></span>
                </div>
            </td>
            <td class="author column-author"><?php echo (strlen($item->company_location)<1) ? "-" : esc_html($item->company_location) ?></td>
            <td class="author column-author"><strong><a href="user-edit.php?user_id=<?php echo $user->ID ?>"><?php echo esc_html($user->display_name." (ID: ".$user->ID.")") ?></a></strong></td>
            <td class="categories column-categories"><?php echo $item->jobs_posted ?></td>
            <td class="date column-date">
                <?php if($item->is_active): ?><span class="wpjb-bulb wpjb-bulb-active"><?php _e("Active", "wpjobboard") ?></span><?php else: ?><span class="wpjb-bulb wpjb-bulb-inactive"><?php _e("Inactive", "wpjobboard") ?></span><?php endif; ?>
                <?php if($opt[$item->is_verified]): ?>
                <?php $bulb = array(Wpjb_Model_Company::ACCESS_DECLINED=>"wpjb-bulb-expired", Wpjb_Model_Company::ACCESS_PENDING=>"wpjb-bulb-pending", Wpjb_Model_Company::ACCESS_GRANTED=>"wpjb-bulb-active") ?>
                <span class="wpjb-bulb <?php echo $bulb[$item->is_verified] ?>"><?php esc_html_e($opt[$item->is_verified]); ?></span>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<div class="tablenav">
    <div class="tablenav-pages">
        <?php
            echo paginate_links( array(
                'base' => wpjb_admin_url("employers", "index", null, $param)."%_%",
                'format' => '&p=%#%',
                'prev_text' => __('&laquo;'),
                'next_text' => __('&raquo;'),
                'total' => $total,
                'current' => $current
            ));
        ?>
    </div>


    <div class="alignleft actions">
        <select id="wpjb-action2" name="action2">
            <option selected="selected" value=""><?php _e("Bulk Actions", "wpjobboard") ?></option>
            <option value="activate"><?php _e("Activate", "wpjobboard") ?></option>
            <option value="deactivate"><?php _e("Deactivate", "wpjobboard") ?></option>
            <option value="delete"><?php _e("Delete", "wpjobboard") ?></option>
            <?php if(wpjb_conf("cv_access")==4): ?>
            <option value="approve"><?php _e("Approve", "wpjobboard") ?></option>
            <option value="decline"><?php _e("Decline", "wpjobboard") ?></option>
            <?php endif; ?>
        </select>
        <input type="submit" class="button-secondary action" id="wpjb-doaction2" value="<?php _e("Apply", "wpjobboard") ?>" />

        <br class="clear"/>
    </div>

    <br class="clear"/>
</div>


</form>


</div>

<?php $this->_include("footer.php"); ?>
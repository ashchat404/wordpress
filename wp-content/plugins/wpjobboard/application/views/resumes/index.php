<div class="wrap">
<?php $this->_include("header.php"); ?>
    
<h2>
    <?php _e("Candidates", "wpjobboard"); ?>
    <a class="add-new-h2" href="<?php echo wpjb_admin_url("resumes", "add") ?>"><?php _e("Add New", "wpjobboard") ?></a> 
</h2>

<?php $this->_include("flash.php"); ?>

<form method="post" action="" id="wpjb-delete-form">
    <input type="hidden" name="delete" value="1" />
    <input type="hidden" name="id" value="" id="wpjb-delete-form-id" />
</form>

<?php if(isset($query) && $query): ?>
<div class="updated fade below-h2" style="background-color: rgb(255, 251, 204);">
    <p>
        <?php printf(__("Your candidates list is filtered using following prarmeters <strong>%s</strong>.", "wpjobboard"), $rquery) ?>&nbsp;
        <?php _e("Click here to", "wpjobboard") ?>&nbsp;<a href="<?php esc_attr_e(wpjb_admin_url("resumes", "index")); ?>"><?php _e("browse all candidates", "wpjobboard") ?></a>.
    </p>
</div>
<?php endif; ?>
    
<ul class="subsubsub">
    <li><a <?php if($filter == "all"): ?>class="current"<?php endif; ?> href="<?php esc_attr_e(wpjb_admin_url("resumes", "index", null, array_merge($param, array("filter"=>"all")))) ?>"><?php _e("All", "wpjobboard") ?></a><span class="count">(<?php echo (int)$stat->total ?>)</span> | </li>
    <li><a <?php if($filter == "active"): ?>class="current"<?php endif; ?>href="<?php esc_attr_e(wpjb_admin_url("resumes", "index", null, array_merge($param, array("filter"=>"active")))) ?>"><?php _e("Active", "wpjobboard") ?></a><span class="count">(<?php echo (int)$stat->active ?>)</span> | </li>
    <li><a <?php if($filter == "inactive"): ?>class="current"<?php endif; ?>href="<?php esc_attr_e(wpjb_admin_url("resumes", "index", null, array_merge($param, array("filter"=>"inactive")))) ?>"><?php _e("Inactive", "wpjobboard") ?></a><span class="count">(<?php echo (int)$stat->inactive ?>)</span> </li>
</ul>

<form method="post" action="<?php esc_attr_e(wpjb_admin_url("resumes", "redirect", null, array("noheader"=>true))) ?>" id="posts-filter">
    
<p class="search-box">
    <label for="post-search-input" class="hidden"><?php _e("Search Jobs", "wpjobboard") ?>:</label>
    <input type="text" value="<?php esc_html_e($query) ?>" name="query" id="post-search-input" class="search-input"/>
    <input type="submit" class="button" value="<?php _e("Search Candidates", "wpjobboard") ?>" />
</p>
    
<input type="hidden" name="action" id="wpjb-action-holder" value="-1" />

<div class="tablenav">

<div class="alignleft actions">
    <select id="wpjb-action1">
        <option selected="selected" value="-1"><?php _e("Bulk Actions", "wpjobboard") ?></option>
        <option value="activate"><?php _e("Activate", "wpjobboard") ?></option>
        <option value="deactivate"><?php _e("Deactivate", "wpjobboard") ?></option>
        <option value="delete"><?php _e("Delete", "wpjobboard") ?></option>
    </select>

    <input type="submit" class="button-secondary action" id="wpjb-doaction1" value="<?php _e("Apply", "wpjobboard") ?>"/>

</div>

</div>
    
<div class="clear"/>&nbsp;</div>

<table cellspacing="0" class="widefat post fixed">
    <?php foreach(array("thead", "tfoot") as $tx): ?>
    <<?php echo $tx; ?>>
        <tr>
            <th style="" class="manage-column column-cb check-column" scope="col"><input type="checkbox"/></th>
            <th style="" class="" scope="col"><?php _e("Name", "wpjobboard") ?></th>
            <th style="" class="" scope="col"><?php _e("Headline", "wpjobboard") ?></th>
            <th style="" class="" scope="col"><?php _e("E-mail", "wpjobboard") ?></th>
            <th style="" class="" scope="col"><?php _e("Phone", "wpjobboard") ?></th>
            <th style="" class="" scope="col"><?php _e("Updated (By Owner)", "wpjobboard") ?></th>
            <th style="" class="" scope="col"><?php _e("Status", "wpjobboard") ?></th>
        </tr>
    </<?php echo $tx; ?>>
    <?php endforeach; ?>

    <tbody>
        <?php foreach($data as $i => $item): ?>
        <?php $user = new WP_User($item->user_id); ?>
	<tr valign="top" class="<?php if($i%2==0): ?>alternate <?php endif; ?>  author-self status-publish iedit">
            <th class="check-column" scope="row">
                <input type="checkbox" value="<?php echo $item->getId() ?>" name="item[]"/>
            </th>
            <td class="post-title column-title">
                <strong><a title='<?php _e("Edit", "wpjobboard") ?>' href="<?php echo wpjb_admin_url("resumes", "edit", $item->getId()); ?>" class="row-title"><?php echo ($user->first_name || $user->last_name) ? esc_html(trim($user->first_name." ".$user->last_name)) : esc_html("ID: ".$item->getId()) ?></a></strong>
                <div class="row-actions">
                    <span class="edit"><a title="<?php _e("Edit", "wpjobboard") ?>" href="<?php echo wpjb_admin_url("resumes", "edit", $item->getId()); ?>"><?php _e("Edit", "wpjobboard") ?></a> | </span>
                    <span class="view"><a rel="permalink" title="<?php _e("View", "wpjobboard") ?>" href="<?php echo wpjr_link_to("resume", $item) ?>"><?php _e("View", "wpjobboard") ?></a> | </span>
                    <span><a href="<?php echo wpjb_admin_url("resumes", "remove")."&".http_build_query(array("users"=>array($item->id))) ?>" class="wpjb-delete wpjb-no-confirm"><?php _e("Delete", "wpjobboard") ?></a> </span>
                </div>
            </td>
            <td class="author column-author"><?php echo esc_html($item->headline) ?></td>
            <td class="categories column-categories"><?php echo $item->getUser(true)->user_email ?></td>
            <td class="tags column-tags"><?php echo $item->phone ?></td>
            <td class="date column-date">
                <?php echo wpjb_date($item->modified_at) ?><br/>
                <small>
                    <?php if($item->modified_at == date("Y-m-d")): ?>
                    <?php _e("Today", "wpjobboard"); ?>
                    <?php else: ?>
                    <?php esc_html_e(daq_time_ago_in_words(strtotime($item->modified_at))." ".__("ago", "wpjobboard")) ?>
                    <?php endif; ?>
                </small>
            </td>
            <td class="date column-date">
                <?php if($item->is_active): ?>
                <span class="wpjb-bulb wpjb-bulb-active"><?php _e("Active", "wpjobboard") ?></span>
                <?php else: ?>
                <span class="wpjb-bulb wpjb-bulb-inactive"><?php _e("Disabled", "wpjobboard") ?></span>
                <?php endif; ?>
                
                <?php if($item->is_public): ?>
                <span class="wpjb-bulb wpjb-bulb-active"><?php _e("Public", "wpjobboard") ?></span>
                <?php else: ?>
                <span class="wpjb-bulb wpjb-bulb-inactive"><?php _e("Private", "wpjobboard") ?></span>
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
            'base' => wpjb_admin_url("resumes", "index", null, $param)."%_%",
            'format' => '&p=%#%',
            'prev_text' => __('&laquo;'),
            'next_text' => __('&raquo;'),
            'total' => $total,
            'current' => $current
        ));
        ?>
    </div>


    <div class="alignleft actions">
        <select id="wpjb-action2">
            <option selected="selected" value="-1"><?php _e("Bulk Actions", "wpjobboard") ?></option>
            <option value="activate"><?php _e("Activate", "wpjobboard") ?></option>
            <option value="deactivate"><?php _e("Deactivate", "wpjobboard") ?></option>
            <option value="delete"><?php _e("Delete", "wpjobboard") ?></option>
        </select>
        <input type="submit" class="button-secondary action" id="wpjb-doaction2" value="<?php _e("Apply", "wpjobboard") ?>"/>

        <br class="clear"/>
    </div>

    <br class="clear"/>
</div>


</form>


<?php $this->_include("footer.php"); ?>
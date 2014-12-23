<div class="wrap">
    
<?php $this->_include("header.php") ?>
<h2><?php _e("Applications", "wpjobboard") ?> <a class="add-new-h2" href="<?php esc_html_e(wpjb_admin_url("application", "add")) ?>"><?php _e("Add New", "wpjobboard") ?></a> </h2>
<?php $this->_include("flash.php"); ?>

<script type="text/javascript">
    Wpjb.DeleteType = "application";
</script>

<form method="post" action="<?php esc_attr_e(wpjb_admin_url("application", "redirect", null, array("noheader"=>1))) ?>" id="posts-filter">
    
<?php if($param["job"]>0): ?>
<input type="hidden" name="job" value="<?php esc_attr_e($param["job"]) ?>" />
<?php endif ?>

<?php if(isset($query) && $query): ?>
<div class="updated fade below-h2" style="background-color: rgb(255, 251, 204);">
    <p>
        <?php printf(__("Your applications list is filtered using following prarmeters <strong>%s</strong>.", "wpjobboard"), $rquery) ?>&nbsp;
        <?php _e("Click here to", "wpjobboard") ?>&nbsp;<a href="<?php esc_attr_e(wpjb_admin_url("application", "index")); ?>"><?php _e("browse all applications", "wpjobboard") ?></a>.
    </p>
</div>
<?php endif; ?>

<?php if(isset($jobObject) && $jobObject): ?>
<div class="updated fade below-h2" style="background-color: rgb(255, 251, 204);">
    <p>
        <?php _e("You are browsing applications for job", "wpjobboard") ?>&nbsp;
        <strong><?php esc_html_e($jobObject->job_title) ?> (ID: <?php echo esc_html($jobObject->getId()) ?>)</strong>.
        <?php _e("Click here to", "wpjobboard") ?>&nbsp;<a href="<?php esc_attr_e(wpjb_admin_url("application")); ?>"><?php _e("browse all applications", "wpjobboard") ?></a>.</p>
</div>
<?php endif; ?>

<ul class="subsubsub">
    <li><a <?php if($filter == "all"): ?>class="current"<?php endif; ?> href="<?php esc_attr_e(wpjb_admin_url("application", "index", null, array_merge($param, array("filter"=>"all")))) ?>"><?php _e("All", "wpjobboard") ?></a><span class="count">(<?php echo (int)$stat->all ?>)</span> | </li>
    <li><a <?php if($filter == "new"): ?>class="current"<?php endif; ?> href="<?php esc_attr_e(wpjb_admin_url("application", "index", null, array_merge($param, array("filter"=>"new")))) ?>"><?php _e("New", "wpjobboard") ?></a><span class="count">(<?php echo (int)$stat->new ?>)</span> | </li>
    <li><a <?php if($filter == "accepted"): ?>class="current"<?php endif; ?> href="<?php esc_attr_e(wpjb_admin_url("application", "index", null, array_merge($param, array("filter"=>"accepted")))) ?>"><?php _e("Accepted", "wpjobboard") ?></a><span class="count">(<?php echo (int)$stat->accepted ?>)</span> | </li>
    <li><a <?php if($filter == "rejected"): ?>class="current"<?php endif; ?> href="<?php esc_attr_e(wpjb_admin_url("application", "index", null, array_merge($param, array("filter"=>"rejected")))) ?>"><?php _e("Rejected", "wpjobboard") ?></a><span class="count">(<?php echo (int)$stat->rejected ?>)</span></li>

</ul>

<p class="search-box">
    <label for="post-search-input" class="hidden">&nbsp;</label>
    <input type="text" value="<?php esc_html_e($query) ?>" name="query" id="post-search-input" class="search-input"/>
    <input type="submit" class="button" value="<?php _e("Search Applications", "wpjobboard") ?>" />
</p>
    
<div class="tablenav">

    <div class="alignleft actions">
        <select name="action" id="wpjb-action1">
            <option selected="selected" value=""><?php _e("Bulk Actions") ?></option>
            <option value="delete"><?php _e("Delete", "wpjobboard") ?></option>
            <option value="accept"><?php _e("Accept", "wpjobboard") ?></option>
            <option value="reject"><?php _e("Reject", "wpjobboard") ?></option>
            <option value="read"><?php _e("Mark as read", "wpjobboard") ?></option>
        </select>
    
        <input type="submit" class="button-secondary action" id="wpjb-doaction1" value="Apply"/>
    </div>
    
    <div class="alignleft actions">
        <select name="posted">
            <option value=""><?php _e("View all dates", "wpjobboard") ?></option>
            <?php foreach($months as $k => $v): ?>
            <option value="<?php esc_attr_e($k) ?>" <?php if($posted==$k): ?>selected="selected"<?php endif; ?>><?php esc_html_e($v) ?></option>
            <?php endforeach; ?>
        </select>

        <input type="submit" class="button-secondary" value="<?php _e("Filter", "wpjobboard") ?>" id="post-query-submit"/>
    </div>
    
</div>

<div class="clear"/>&nbsp;</div>

<table cellspacing="0" class="widefat post fixed">
    <?php foreach(array("thead", "tfoot") as $tx): ?>
    <<?php echo $tx; ?>>
        <tr>
            <th style="" class="manage-column column-cb check-column" scope="col"><input type="checkbox"/></th>
            <th style="width:250px" class="" scope="col"><?php _e("Applicant Name", "wpjobboard") ?></th>
            <th style="" class="" scope="col"><?php _e("Applicant Email", "wpjobboard") ?></th>
            <th style="" class="" scope="col"><?php _e("Job", "wpjobboard") ?></th>
            <th style="" class="" scope="col"><?php _e("Files", "wpjobboard") ?></th>
            <th style="" class="" scope="col"><?php _e("Posted", "wpjobboard") ?></th>
            <th style="" class="" scope="col"><?php _e("Status", "wpjobboard") ?></th>
        </tr>
    </<?php echo $tx; ?>>
    <?php endforeach; ?>

    <tbody>
        <?php foreach($data as $i => $item): ?>
	<tr valign="top" class="<?php if($i%2==0): ?>alternate <?php endif; ?>  author-self status-publish iedit <?php if($item->status == 1): ?>wpjb-unread<?php endif; ?>">
            <th class="check-column" scope="row">
                <input type="checkbox" value="<?php esc_attr_e($item->getId()) ?>" name="item[]"/>
            </th>
            <td class="post-title column-title">
                <strong><a title='<?php _e("Edit", "wpjobboard") ?>  "(<?php esc_attr_e($item->applicant_name) ?>)"' href="<?php esc_attr_e(wpjb_admin_url("application", "edit", $item->getId())); ?>" class="row-title"><?php esc_html_e($item->applicant_name) ?></a></strong>
                <div class="row-actions">
                    <span class="edit"><a title="<?php _e("Edit", "wpjobboard") ?>" href="<?php esc_attr_e(wpjb_admin_url("application", "edit", $item->getId())); ?>"><?php _e("Edit", "wpjobboard") ?></a> | </span>
                    <span class=""><a href="<?php esc_attr_e(wpjb_admin_url("application", "delete", $item->getId(), array("noheader"=>1))) ?>" title="<?php _e("Delete", "wpjobboard") ?>" class="wpjb-delete"><?php _e("Delete", "wpjobboard") ?></a> </span>
                </div>
            </td>

            <td class="date column-date">
                <a href="mailto:<?php esc_attr_e($item->email) ?>"><?php esc_attr_e($item->email) ?></a>
            </td>
            <td class="date column-date">
                <?php if($item->getJob(true)->id): ?>
                <a href="<?php esc_attr_e(wpjb_admin_url("job", "edit", $item->getJob()->getId())) ?>"><?php esc_attr_e($item->getJob()->job_title) ?> (ID: <?php esc_html_e($item->getJob()->getId()) ?>)</a>
                <?php else: ?>
                —
                <?php endif; ?>
            </td>
            <td class="date column-date">
                <?php esc_html_e(count($item->getFiles())) ?>
            </td>
            <td class="date column-date">
                <?php esc_html_e(wpjb_date($item->applied_at)) ?><br/>
                <?php echo daq_time_ago_in_words(strtotime($item->applied_at))." ".__("ago.") ?>
            </td>
            <td>
                <?php echo (wpjb_application_status($item->status, true)) ?>
            </td>

        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<div class="tablenav">
    <div class="tablenav-pages">
        <?php
        echo paginate_links( array(
            'base' => wpjb_admin_url("application", "index", null, $param)."%_%",
            'format' => '&p=%#%',
            'prev_text' => __('&laquo;'),
            'next_text' => __('&raquo;'),
            'total' => $total,
            'current' => $current
        ));
        ?>
        
        
    </div>


    <div class="alignleft actions">
        <select name="action2" id="wpjb-action2">
            <option selected="selected" value=""><?php _e("Bulk Actions", "wpjobboard") ?></option>
            <option value="delete"><?php _e("Delete", "wpjobboard") ?></option>
            <option value="accept"><?php _e("Accept", "wpjobboard") ?></option>
            <option value="reject"><?php _e("Reject", "wpjobboard") ?></option>
            <option value="read"><?php _e("Mark as read", "wpjobboard") ?></option>
        </select>
        
        <input type="submit" class="button-secondary action" id="wpjb-doaction2" value="<?php _e("Apply", "wpjobboard") ?>" />
        

    </div>
    
    <div class="alignleft actions">
        
        <?php $p2 = $param; $p2["noheader"] = "1"; ?>
        <a href="<?php esc_attr_e(wpjb_admin_url("application", "export", null, $p2))  ?>" title="<?php _e("Export to CSV", "wpjobboard") ?>"><img src="<?php esc_attr_e(plugins_url()."/wpjobboard/application/public/csv.png") ?>" style="margin-top:5px" alt="<?php _e("Export to CSV", "wpjobboard") ?>" /></a>
        
    </div>

    <br class="clear"/>
</div>


</form>


<?php $this->_include("footer.php"); ?>

</div>
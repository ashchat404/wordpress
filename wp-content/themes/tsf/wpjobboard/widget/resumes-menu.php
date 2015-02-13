<?php
/**
 * Resumes menu
 * 
 * Resumes menu widget template file
 * 
 * 
 * @author Greg Winiarski
 * @package Templates
 * @subpackage Widget
 * 
 */
?>
<?php if ($is_employee && $is_loggedin): ?>
    <?php echo $theme->before_widget ?>
    <?php if ($title) echo $theme->before_title . $title . $theme->after_title ?>

    <ul id="wpjb_widget_resumesmenu" class="wpjb_widget">
        <li>
            <a href="<?php echo wpjb_url() ?>">
                <?php _e("View Jobs", "jobeleon") ?>
            </a>
        </li>

        <li>
            <a href="<?php echo wpjb_link_to("advsearch") ?>">
                <?php _e("Advanced Search", "jobeleon") ?>
            </a>
        </li>
        
        <li>
            <a href="<?php echo wpjr_link_to("myresume") ?>">
                <?php _e("My profile", "jobeleon") ?>
            </a>
        </li>
        <li>
            <a href="<?php echo wpjr_link_to("myapplications") ?>">
                <?php _e("My Applications", "jobeleon") ?>
            </a>
        </li>
        <?php if(!wpjb_conf("front_hide_bookmarks")): ?>
        <li>
            <a href="<?php echo wpjr_link_to("mybookmarks") ?>">
                <?php _e("Saved Jobs", "jobeleon") ?>
            </a>
        </li>
        <?php endif; ?>
        <li>
            <a href="<?php echo wpjr_link_to("logout") ?>">
                <?php _e("Logout", "jobeleon") ?>
            </a>
        </li>

    </ul>

    <?php echo $theme->after_widget ?>
<?php endif; ?>

<?php if (!$is_employee && !$is_loggedin): ?>
<?php echo $theme->before_widget ?>
    <?php if ($title) echo $theme->before_title . $title . $theme->after_title ?>

    <ul id="wpjb_widget_resumesmenu" class="wpjb_widget">
        <li>
            <a href="<?php echo wpjb_url() ?>">
                <?php _e("View Jobs", "jobeleon") ?>
            </a>
        </li>

        <li>
            <a href="<?php echo wpjb_link_to("advsearch") ?>">
                <?php _e("Advanced Search", "jobeleon") ?>
            </a>
        </li>

        <li>
            <a href="<?php echo wpjr_link_to("login") ?>">
                <?php _e("Candidate Login", "jobeleon") ?>
            </a>
        </li>

        <li>
            <a href="<?php echo wpjr_link_to("register") ?>">
                <?php _e("Candidate Registration", "jobeleon") ?>
            </a>
        </li>
    </ul>

    <?php echo $theme->after_widget ?>
<?php endif; ?>
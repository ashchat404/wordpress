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

<?php echo $theme->before_widget ?>
<?php if ($title) echo $theme->before_title . $title . $theme->after_title ?>

<ul id="wpjb_widget_resumesmenu" class="wpjb_widget">
    <li>
        <a href="<?php echo wpjr_url() ?>">
            <?php _e("Browse Resumes", "jobeleon") ?>
        </a>
    </li>
    <li>
        <a href="<?php echo wpjr_link_to("advsearch") ?>">
            <?php _e("Search Resumes", "jobeleon") ?>
        </a>
    </li>
    <?php if ($is_employee && $is_loggedin): ?>
        <li>
            <a href="<?php echo wpjr_link_to("myresume") ?>">
                <?php _e("My Resume", "jobeleon") ?>
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
                <?php _e("My Bookmarks", "jobeleon") ?>
            </a>
        </li>
        <?php endif; ?>
        <li>
            <a href="<?php echo wpjr_link_to("logout") ?>">
                <?php _e("Logout", "jobeleon") ?>
            </a>
        </li>
    <?php elseif (get_option('users_can_register')): ?>
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
    <?php endif; ?>
</ul>

<?php echo $theme->after_widget ?>
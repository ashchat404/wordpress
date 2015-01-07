<?php
/**
 * Employer menu
 * 
 * Employer menu widget template file
 * 
 * 
 * @author Greg Winiarski
 * @package Templates
 * @subpackage Widget
 * 
 */
?>

<?php if ($is_employer && $is_loggedin): ?>
    <?php echo $theme->before_widget ?>
    <?php if ($title) echo $theme->before_title . $title . $theme->after_title ?>

        <ul id="wpjb_widget_jobboardmenu" class="wpjb_widget">
            <?php if ($can_post): ?>
                <li>
                    <a href="<?php echo wpjb_link_to("step_add") ?>">
                        <?php _e("Post a Job", "jobeleon") ?>
                    </a>
                </li>
            <?php endif; ?>
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
                <a href="<?php echo wpjb_link_to("employer_panel") ?>">
                    <?php _e("Company Jobs", "jobeleon") ?>
                </a>
            </li>
            <li>
                <a href="<?php echo wpjb_link_to("employer_edit") ?>">
                    <?php _e("Company Profile", "jobeleon") ?>
                </a>
            </li>
            <li>
                <a href="<?php echo wpjb_link_to("membership") ?>">
                    <?php _e("Membership", "jobeleon") ?>
                </a>
            </li>
            <li>
                <a href="<?php echo wpjb_link_to("employer_logout") ?>">
                    <?php _e("Logout", "jobeleon") ?>
                </a>
            </li>


        </ul>
    <?php echo $theme->after_widget ?>

<?php endif; ?>

<?php if (!$is_employer && !$is_loggedin): ?>
    <?php echo $theme->before_widget ?>
    <?php if ($title) echo $theme->before_title . $title . $theme->after_title ?>

        <ul id="wpjb_widget_jobboardmenu" class="wpjb_widget">
            <?php if ($can_post): ?>
                <li>
                    <a href="<?php echo wpjb_link_to("step_add") ?>">
                        <?php _e("Post a Job", "jobeleon") ?>
                    </a>
                </li>
            <?php endif; ?>
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
                <a href="<?php echo wpjb_link_to("employer_login") ?>">
                    <?php _e("Employer Login", "jobeleon") ?>
                </a>
            </li>
            <li>
                <a href="<?php echo wpjb_link_to("employer_new") ?>">
                    <?php _e("Employer Registration", "jobeleon") ?>
                </a>
            </li>

        </ul>
    <?php echo $theme->after_widget ?>

<?php endif; ?>
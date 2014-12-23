<?php
/**
 * Company job applications
 * 
 * Template displays job applications
 * 
 * 
 * @author Greg Winiarski
 * @package Templates
 * @subpackage JobBoard
 * 
 */
/* @var $applicantList array List of applications to display */
/* @var $job string Wpjb_Model_Job */
?>

<div class="where-am-i">
    <h2><?php _e('Job applications', 'jobeleon'); ?></h2>
</div><!-- .where-am-i -->

<div id="wpjb-main" class="wpjb-page-job-applications">

    <?php wpjb_flash(); ?>

    <div class="wpjb-menu-bar">
        <a href="<?php echo wpjb_link_to("employer_panel") ?>"><?php _e("Company jobs", "jobeleon") ?></a>
        &raquo; <?php esc_html_e($job->job_title) ?>
    </div>

    <table class="wpjb-table">
        <thead>
            <tr>
                <th><?php _e("Applicant name", "jobeleon") ?></th>
                <th><?php _e("E-mail", "jobeleon") ?></th>
                <th><?php _e("Freshness", "jobeleon") ?></th>
                <th><?php _e("Status", "jobeleon") ?></th>
                <th class="wpjb-last">&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($applicantList)) : foreach ($applicantList as $application): ?>
                    <tr class="">
                        <td>
                            <a href="<?php echo wpjb_link_to("job_application", $application) ?>">
                                <?php if ($application->applicant_name): ?>
                                    <?php esc_html_e($application->applicant_name) ?>
                                <?php else: ?>
                                    <?php
                                    _e("ID");
                                    echo ": ";
                                    echo $application->id;
                                    ?>
                                <?php endif; ?>
                            </a><br />
                        </td>
                        <td>
                            <a class="wpjb-mail" href="mailto:<?php esc_attr_e($application->email) ?>"><?php esc_html_e($application->email) ?></a>
                        </td>
                        <td>
                            <?php echo wpjb_time_ago($application->applied_at) ?>
                        </td>
                        <td>
                            <?php echo wpjb_application_status($application->status) ?>
                        </td>
                        <td class="wpjb-last">
                            <div class="company-panel-dropdown">
                                <img id="wpjb-dropdown-<?php echo $application->id ?>-img" src="<?php echo wpjb_img("cog.png") ?>" alt="" />
                                <ul id="wpjb-dropdown-<?php echo $application->id ?>" class="wpjb-dropdown">
                                    <?php foreach (wpjb_application_status() as $k => $v): ?>
                                        <li><a href="<?php echo wpjb_link_to("job_application_status", $application, array("st" => (int) $k)) ?>"><?php esc_html_e($v) ?></a></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <?php
                endforeach;
            else :
                ?>
                <tr>
                    <td colspan="3" align="center">
                        <?php _e("No applicants found.", "jobeleon"); ?>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>


</div>

<script type="text/javascript">
    jQuery(function(){    
        jQuery(".company-panel-dropdown").wpjb_menu({
            position: "right"
        });
    });
</script>

<?php
/**
 * Job list item
 * 
 * This template is responsible for displaying job list item on job list page
 * (template index.php) it is alos used in live search
 * 
 * @author Greg Winiarski
 * @package Templates
 * @subpackage JobBoard
 */
/* @var $job Wpjb_Model_Job */
$suffix = 'green'; // color scheme
$color_scheme = get_theme_mod('wpjobboard_theme_color_scheme');
$suffix = !empty($color_scheme) ? $color_scheme : $suffix;

$random_logo_colors = array('#dbc0e0', '#d7d7d7', '#cde0c0');
?>

<tr class="<?php wpjb_job_features($job); ?>">
    <td class="wpjb-column-logo">
        <?php if ($job->getLogoUrl()): ?>
            <a href="<?php echo wpjb_link_to("job", $job) ?>">
                <img src="<?php echo $job->getLogoUrl("64x64") ?>" id="wpjb-logo" alt="" />
            </a>
        <?php else : ?>
            <a href="<?php echo wpjb_link_to("job", $job) ?>">
                <div class="wpjb-logo-placeholder" style="background-color: <?php echo $random_logo_colors[array_rand($random_logo_colors)]; ?>"></div>
            </a>
        <?php endif; ?>
    </td>
    <td class="wpjb-column-title">
        <a href="<?php echo wpjb_link_to("job", $job) ?>"><?php esc_html_e($job->job_title) ?></a>
        <small class="wpjb-sub"><?php esc_html_e($job->company_name) ?></small>
        <?php if ($job->meta->salary->value()):?>
            <span>£<?php esc_html_e($job->meta->salary->value()) ?></span>
        <?php endif; ?>
    </td>
    <td class="wpjb-column-description">
        <p><?php esc_html_e(substr(strip_tags($job->job_description), 0, 100)) ?><a href="<?php echo wpjb_link_to("job", $job) ?>"> ...More</a></p>
    </td>
    <td class="wpjb-column-location wpjb-last">
        <?php if (wpjb_conf("show_maps") && $job->getGeo()->status == 2): ?>

            <a href="#" class="wpjb-tooltip">
                <img src="<?php echo get_template_directory_uri() . '/wpjobboard/images/location-' . $suffix . '.png' ?>" alt="" class="wpjb-inline-img" />
                <span><img src="http://maps.googleapis.com/maps/api/staticmap?center=<?php echo $job->getGeo()->lnglat ?>&zoom=13&size=500x200&sensor=false" width="500" height="200" /></span>
            </a>
        <?php endif; ?>
        <?php esc_html_e($job->locationToString()) ?>
        <small class="wpjb-sub">
            <?php if(isset($job->getTag()->type[0])): ?>
            <?php esc_html_e($job->getTag()->type[0]->title) ?>
            <?php else: ?>
            —
            <?php endif; ?>
        </small>
    </td>
</tr>

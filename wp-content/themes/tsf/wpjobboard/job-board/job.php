<?php
/**
 * Job details
 * 
 * This template is responsible for displaying job details on job details page
 * (template single.php) and job preview page (template preview.php)
 * 
 * @author Greg Winiarski
 * @package Templates
 * @subpackage JobBoard
 */
/* @var $job Wpjb_Model_Job */
/* @var $company Wpjb_Model_Employer */
$suffix = 'green'; // color scheme
$color_scheme = get_theme_mod('wpjobboard_theme_color_scheme');
$suffix = !empty($color_scheme) ? $color_scheme : $suffix;
?>

<?php $job = wpjb_view("job") ?>

<div itemscope itemtype="http://schema.org/JobPosting">
    <meta itemprop="title" content="<?php esc_attr_e($job->job_title) ?>" />
    <meta itemprop="datePosted" content="<?php esc_attr_e($job->job_created_at) ?>" />

    <header class="entry-header large-12 columns" id="job-info">
                <div class="large-2 medium-2 columns">
                    <?php if ($job->getLogoUrl()): ?>
                        <div class="company_logo">
                            <div class="mid">
                                <img src="<?php echo $job->getLogoUrl() ?>" id="wpjb-logo" alt="" />
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="extra <?php if ($application_url): ?>large-10 medium-8 <?php else: ?>large-8 medium-6<?php endif; ?> columns">
                    <div class="extra">
                        <h1 class="entry-title"><?php esc_html_e(Wpjb_Project::getInstance()->title) ?> <br><br>Salary: <?php esc_html_e($job->meta->salary->value()) ?></h1>
                    </div>
                </div>
                <?php if ($application_url): ?>
                <?php else: ?>
                    <div class="extra large-2 medium-4 columns">
                        <div class="extra">
                            <a id="apply_scroll" class="wpjb-button wpjb-form-toggle wpjb-form-job-apply btn" href="#wpjb-scroll" rel="nofollow"  data-wpjb-form="wpjb-form-job-apply"><?php _e("Apply", "jobeleon") ?> </a>
                        </div>
                    </div>
                <?php endif; ?>
    </header>

    <table class="wpjb-info large-12 columns">
        <tbody>
            <?php if ($job->locationToString()): ?>
                <tr>
                    <td>
                        <span itemprop="jobLocation" itemscope itemtype="http://schema.org/Place">
                            <span itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
                                <meta itemprop="addressLocality" content="<?php esc_attr_e($job->job_city) ?>" /> 
                                <meta itemprop="addressRegion" content="<?php esc_attr_e($job->job_state) ?>" />
                                <meta itemprop="addressCountry" content="<?php $country = Wpjb_List_Country::getByCode($job->job_country); esc_attr_e($country["iso2"]) ?>" />
                                <meta itemprop="postalCode" content="<?php esc_attr_e($job->job_zip_code) ?>" />
       
                                <a href="#" class="wpjb-tooltip wpjb-expand-map">
                                    <img src="<?php echo get_template_directory_uri() . '/wpjobboard/images/location-' . $suffix . '.png' ?>" alt="" class="wpjb-inline-img" />
                                </a>
                        
                                <?php if(wpjb_conf("show_maps") && $job->getGeo()->status==2): ?>
                                <a href="<?php esc_attr_e(wpjb_google_map_url($job)) ?>" class="wpjb-expand-map"><?php esc_html_e($job->locationToString()) ?></a>
                                <?php else: ?>
                                <?php esc_html_e($job->locationToString()) ?>
                                <?php endif; ?>
                        
                            </span>
                        </span>
                    </td>
                </tr>
                <?php if (wpjb_conf("show_maps") && $job->getGeo()->status == 2): ?>
                    <tr style="display: table-row;" class="wpjb-expanded-map-row">
                        <td colspan="2" class="wpjb-expanded-map">
                            <iframe frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src=""></iframe>
                        </td>
                    </tr>
                <?php endif; ?>
            <?php endif; ?>
            <?php if ($job->company_name): ?>
                <tr>
                    <td>
                        <span itemprop="hiringOrganization" itemscope itemtype="http://schema.org/Organization">
                            <span itemprop="name">
                                <?php wpjb_job_company($job) ?>
                            </span>
                        </span>
                        <?php wpjb_job_company_profile($job->getCompany(true)) ?>
                    </td>
                </tr>
            <?php endif; ?>
            <?php if (!empty($job->getTag()->category)): ?>
                <tr>
                    <td>
                        <?php foreach ($job->getTag()->category as $category): ?>
                        <a href="<?php esc_attr_e(wpjb_link_to("category", $category)) ?>">
                            <span itemprop="occupationalCategory">Industry - <?php esc_html_e($category->title) ?></span>
                        </a>
                        <br/>
                        <?php endforeach; ?>
                    </td>
                </tr>
            <?php endif ?>
            <?php if (!empty($job->getTag()->type)): ?>
                <tr>
                    <td class="type">
                        <?php foreach ($job->getTag()->type as $type): ?>
                        <a href="<?php esc_attr_e(wpjb_link_to("type", $type)) ?>">
                            <span itemprop="employmentType"><?php esc_html_e($type->title) ?></span>
                        </a>
                        <br/>
                        <?php endforeach; ?>
                    </td>
                </tr>
            <?php endif; ?>
            <?php do_action("wpjb_template_job_meta_text", $job) ?>
        </tbody>
    </table>
    <div class="row">
        <h3 style="padding-left:25px"><?php _e("More Details", "jobeleon") ?></h3>
        <?php foreach($job->getMeta(array("visibility"=>0, "meta_type"=>3, "empty"=>false, "field_type_exclude"=>"ui-input-textarea")) as $k => $value): ?>
        
            <div class="large-12 columns">
                <?php if ($value->conf("type") == "ui-input-file"): ?>
                    <?php foreach ($job->file->{$value->name} as $file): ?>
                        <div class="large-4 medium-4 columns">
                            <?php esc_html_e($value->conf("title")); ?><span> - </span>
                        </div>
                        <div class="large-8 medium-8 columns">
                            <a href="<?php esc_attr_e($file->url) ?>" rel="nofollow"><?php esc_html_e($file->basename) ?></a>
                        </div>
                        
                        <?php echo wpjb_format_bytes($file->size) ?><br/>
                    <?php endforeach ?>
                <?php else: ?>
                        <div class="large-2 medium-3 small-3 columns">
                            <?php esc_html_e($value->conf("title")); ?><span> - </span>
                        </div>
                        <div class="large-10 medium-9 small-9 columns">
                            <?php esc_html_e(join(", ", (array) $value->values())) ?>
                        </div>

                        
                <?php endif; ?>
            </div>
        
        <?php endforeach; ?>
    </div>
    <div class="wpjb-job-content large-12 columns">

        <h3><?php _e("Description", "jobeleon") ?></h3>
        <div itemprop="description" class="wpjb-job-text">

            <?php wpjb_rich_text($job->job_description, $job->meta->job_description_format->value()) ?>

        </div>

        <?php foreach($job->getMeta(array("visibility"=>0, "meta_type"=>3, "empty"=>false, "field_type"=>"ui-input-textarea")) as $k => $value): ?>

            <h3><?php esc_html_e($value->conf("title")); ?></h3>
            <div class="wpjb-job-text">
                <?php wpjb_rich_text($value->value(), $value->conf("textarea_wysiwyg") ? "html" : "text") ?>
            </div>

        <?php endforeach; ?>

        <?php do_action("wpjb_template_job_meta_richtext", $job) ?>
    </div>

</div>
<script type="text/javascript">
$( window ).load(function() {

});
</script>
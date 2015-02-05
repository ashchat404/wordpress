<?php
/**
 * Company profile page
 * 
 * This template displays company profile page
 * 
 * 
 * @author Greg Winiarski
 * @package Templates
 * @subpackage JobBoard
 */
/* @var $jobList array List of active company job openings */
/* @var $company Wpjb_Model_Company Company information */

$suffix = 'green'; // color scheme
$color_scheme = get_theme_mod('wpjobboard_theme_color_scheme');
$suffix = !empty($color_scheme) ? $color_scheme : $suffix;
?>

<div id="wpjb-main" class="wpjb-page-company" >

    <?php wpjb_flash() ?>
    <?php if($company->isVisible() || (Wpjb_Model_Company::current() && Wpjb_Model_Company::current()->id == $company->id)): ?>
    <header class="entry-header large-12 columns" id="job-info">
        <div class="large-2 medium-4 columns">
            <?php if ($company->getLogoUrl()): ?>
                <div class="profile_logo"><img src="<?php echo $company->getLogoUrl() ?>" id="wpjb-logo" alt="" /></div>
                <div class="company-social">
                    <?php if ($company->meta->facebook_link): ?>
                        <a target="_blank" href="<?php esc_html_e($company->meta->facebook_link->value()) ?>"><i class="fi-social-facebook"></i></a>
                    <?php endif; ?>
                    <?php if ($company->meta->twitter_username): ?>
                        <a target="_blank" href="http://twitter.com/<?php esc_html_e($company->meta->twitter_username->value()) ?>"><i class="fi-social-twitter"></i></a>
                    <?php endif; ?>
                    <?php if ($company->meta->linkedin_link): ?>
                        <a target="_blank" href="<?php esc_html_e($company->meta->linkedin_link->value()) ?>"><i class="fi-social-linkedin"></i></a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="large-10 medium-8 columns">
            <div class="extra comp">
                <div class="large-9 columns">
                    <h1 class="entry-title"><?php esc_html_e(Wpjb_Project::getInstance()->title) ?> - </h1>
                    <p class="entry-exerpt">"<?php esc_html_e($company->meta->company_exerpt->value()) ?>"</p>

                </div>
                <div class="large-3 columns">
                    <div class="comp_fl">
                        <a class="btn fol" href="#"> Follow</a>
                        <form action="http://localhost:8888/wordpress/jobs-3/alert-confirmation" method="post">
                            <input type="hidden" name="add_alert" value="1">
                            <ul id="wpjb_widget_alerts" class="wpjb_widget">
                                    <li>
                                        <input type="text" name="keyword" value="<?php esc_html_e(Wpjb_Project::getInstance()->title) ?>" placeholder="Keyword">
                                    </li>
                                    <li>
                                        <input type="text" name="email" value="" placeholder="E-Mail">
                                    </li>
                                    <li>
                                        <input type="submit" value="Keep me posted">
                                    </li>
                            </ul>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
    </header>

        <table class="wpjb-info large-12 columns">
            <tbody>
                <?php if ($company->locationToString()): ?>
                    <tr>
                        <td>
                            <a href="#" class="wpjb-tooltip wpjb-expand-map">
                                <img src="<?php echo get_template_directory_uri() . '/wpjobboard/images/location-' . $suffix . '.png' ?>" alt="" class="wpjb-inline-img" />
                            </a>
                    
                            <?php if(wpjb_conf("show_maps") && $company->getGeo()->status==2): ?>
                            <a href="<?php esc_attr_e(wpjb_google_map_url($company)) ?>" class="wpjb-expand-map"><?php esc_html_e($company->locationToString()) ?></a>
                            <?php else: ?>
                            <?php esc_html_e($company->locationToString()) ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                    
                    <?php if (wpjb_conf("show_maps") && $company->getGeo()->status == 2): ?>
                        <tr style="display: table-row;" class="wpjb-expanded-map-row">
                            <td colspan="2" class="wpjb-expanded-map">
                                <iframe frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src=""></iframe>
                            </td>
                        </tr>
                    <?php endif; ?>

                    <tr>
                        <td>
                                <?php $jobCount = wpjb_find_jobs($param) ?>
                                <?php if ($jobCount->total > 0): foreach ($jobCount->job as $job): ?>
                                    <?php $tot=$jobCount->total?>
                                        <?php
                                    endforeach;
                                    echo $tot." Opening (s)";
                                else :
                                    ?>
                                    <li>
                                        <?php _e("No jobs.", "jobeleon"); ?>
                                    </li>
                                <?php endif; ?>
                        </td>
                    </tr>
                <?php endif; ?>

                <?php if ($company->company_website): ?>
                    <tr>
                        <td><a target="_blank" href="<?php esc_attr_e($company->company_website) ?>" class="wpjb-company-link"><?php esc_html_e($company->company_website) ?></a></td>
                    </tr>
                <?php endif; ?>
                <?php if ($company->meta->company_industry): ?>
                    <tr>
                        <td>Industry - <?php esc_html_e($company->meta->company_industry->value()) ?></td>
                    </tr>
                <?php endif; ?>

                <?php do_action("wpjb_template_company_meta_text", $company) ?>
            </tbody>
        </table>

        <div class="wpjb-job-content large-12 columns">
            <h3 class="text-center">Why work for us?</h3>

            <div class="wpjb-job-text">

                <?php wpjb_rich_text($company->company_info, $company->meta->company_info_format->value()) ?>

            </div>

            <?php foreach($company->getMeta(array("visibility"=>0, "meta_type"=>3, "empty"=>false, "field_type"=>"ui-input-textarea")) as $k => $value): ?>

                <div class="wpjb-job-text">
                    <?php wpjb_rich_text($value->value()) ?>
                </div>

            <?php endforeach; ?>

            <?php do_action("wpjb_template_job_meta_richtext", $company) ?>

        </div>
        <div class="clear"></div>

        <div id="company-openings" class="wpjb-company-openings large-12 columns">
            <h3 class="text-center"><?php _e("Jobs available at ", "jobeleon") ?><?php esc_html_e($company->company_name) ?></h3>
            <ul class="wpjb-company-list">
                <?php $jobList = wpjb_find_jobs($param) ?>
                <?php if ($jobList->total > 0): foreach ($jobList->job as $job): ?>
                        <?php /* @var $job Wpjb_Model_Job */ ?>
                        <li class="<?php wpjb_job_features($job); ?> large-4 columns text-center">
                            <a href="<?php echo wpjb_link_to("job", $job); ?>"><?php esc_html_e($job->job_title) ?></a>
                        </li>
                        <?php
                    endforeach;
                else :
                    ?>
                    <li>
                        <?php _e("Currently this employer doesn't have any openings.", "jobeleon"); ?>
                    </li>
                <?php endif; ?>
            </ul>
        </div>

    <?php endif; ?>

</div>
<script type="text/javascript">
    $(".fol").click(function(event){
        $(this).hide();
        event.preventDefault();
        $(".extra form").fadeIn();
    });
</script>

<?php
/**
 * Featured Jobs
 * 
 * Featured jobs widget template file
 * 
 * 
 * @author Greg Winiarski
 * @package Templates
 * @subpackage Widget
 * 
 */
/* @var $jobList array List of Wpjb_Model_Job objects */
?>

<?php echo $theme->before_widget ?>
<?php if ($title) echo $theme->before_title . $title . $theme->after_title ?>
<div id="jobs" class="row">
    <?php if (!empty($jobList)): foreach ($jobList as $job): ?>
    <div>
        <li class="large-12 columns">
            <a class="row block" href="<?php echo wpjb_link_to("job", $job) ?>">
                <?php if ($job->getLogoUrl()): ?>
                    <div class="featured_logo">
                        <div class="chld">
                            <img src="<?php echo $job->getLogoUrl() ?>" alt="" />
                        </div>

                    </div>
                <?php endif; ?>
                <div class="row featured_content">
                    <h3 class="job-title">
                        <?php esc_html_e($job->job_title) ?>
                        <p class="job-company"> - <?php esc_html_e($job->company_name) ?></p>
                    </h3>
                    <p><?php esc_html_e(substr(strip_tags($job->job_description), 0, 100)) ?><i> ...More</i></p>
                </div>

                <div class="featured_extra">
                    <div class="large-12 columns location">
                        <p class=""><i class="fi-marker"></i><?php esc_html_e($job->locationToString()) ?></p>
                    </div>
                    <div class="large-12 columns salary">
                        <p><?php esc_html_e($job->meta->salary->value()) ?></p>
                    </div>
                </div>
            </a>
        </li> 
    </div>
    <?php endforeach; else: ?>
        <li><?php _e("No featured jobs found.", "jobeleon") ?></li> 
    <?php endif; ?>
</div>

<script type="text/javascript">
$(document).ready(function(){
    $('#jobs').slick({
        dots: true,
        centerMode: false,
        centerPadding: '60px',
        slidesToShow: 3,
      responsive: [
        {
          breakpoint: 768,
          settings: {
            arrows: false,
            centerMode: true,
            centerPadding: '40px',
            slidesToShow: 2
          }
        },
        {
          breakpoint: 480,
          settings: {
            arrows: false,
            centerMode: true,
            centerPadding: '40px',
            slidesToShow: 1
          }
        }
      ]
    });
});
</script>

<?php echo $theme->after_widget ?>
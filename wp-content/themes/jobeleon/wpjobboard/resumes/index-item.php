<tr class="wpjr-resume-item">
    <td>
        <div class="wpjr-resume-item-content">
            <div class="wpjb-resume-photo-wrap">
                <?php if ($resume->getAvatarUrl()): ?>
                    <a href="<?php echo wpjr_link_to("resume", $resume) ?>"><img src="<?php esc_attr_e($resume->getAvatarUrl("50x50")) ?>" alt="" class="wpjb-resume-photo" /></a>
                <?php else: ?>
                    <a href="<?php echo wpjr_link_to("resume", $resume) ?>"><img src="<?php echo get_template_directory_uri() . '/wpjobboard/images/candidate-dark-avatar.png'; ?>" alt="" /></a>
                <?php endif; ?>

            </div>
            <div class="wpjr-resume-item-title">
                <a href="<?php echo wpjr_link_to("resume", $resume) ?>"><?php esc_html_e($resume->getSearch(true)->fullname) ?></a>
                <span class="wpjr-resume-item-headline"><?php esc_html_e($resume->headline) ?></span>
            </div><!-- .wpjr-resume-item-title -->
            <div class="wpjr-resume-item-date">
                <img src="<?php echo get_template_directory_uri() . '/wpjobboard/images/calendar-grey.png'; ?>" alt="" class="wpjb-inline-img" />
                <?php echo wpjb_date_display("M, d", $resume->modified_at, true); ?>
            </div>
        </div><!-- .wpjr-resume-item-content -->
        <div class="wpjr-resume-item-meta">
            <div class="wpjr-resume-item-location">
                <?php if (wpjb_conf("show_maps") && $resume->getGeo()->status == 2): ?>
                    <a href="#" class="wpjb-tooltip">
                        <img src="<?php echo get_template_directory_uri() . '/wpjobboard/images/location-grey.png'; ?>" alt="" class="wpjb-inline-img" />
                        <span><img src="http://maps.googleapis.com/maps/api/staticmap?center=<?php echo $resume->getGeo()->lnglat ?>&zoom=13&size=500x200&sensor=false" width="500" height="200" /></span>
                    </a>
                <?php endif; ?>
                <?php esc_html_e($resume->locationToString()) ?>
            </div><!-- .wpjr-resume-item-location -->
            <div class="wpjr-resume-item-category">
                <?php if (isset($resume->tag->category[0])) : ?>
                    <img src="<?php echo get_template_directory_uri() . '/wpjobboard/images/category-grey.png'; ?>" alt="" class="wpjb-inline-img" />
                    <span><?php echo $resume->tag->category[0]->title; ?></span>
                <?php else: ?>
                &#8212;
                <?php endif; ?>
            </div><!-- .wpjr-resume-item-category -->
        </div><!-- .wpjr-resume-item-meta -->
    </td><!-- .wpjr-resume-item -->
</tr>

<?php // echo '<pre>'; print_r( $resume->meta ); echo '</pre>'; ?>

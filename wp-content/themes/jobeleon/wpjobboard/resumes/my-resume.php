<?php /* @var $resume Wpjb_Model_Resume */ ?>

<div class="where-am-i">
    <h2><?php _e('My Resume', 'jobeleon'); ?></h2> 
</div><!-- .where-am-i -->

<div id="wpjb-main" class="wpjr-page-my-resume">

    <?php wpjb_flash() ?>

    <div class="wpjb-menu-bar">
        <a href="<?php echo wpjr_link_to("resume", $resume) ?>" class="btn"><?php _e("View Resume", "wpjobboard"); ?></a> 
        <a href="<?php echo wpjr_link_to("myresume_password") ?>" class="btn"><?php _e("Change Password", "wpjobboard") ?></a> 
        <a href="<?php echo wpjr_link_to("myresume_delete") ?>" class="btn"><?php _e("Delete Account", "wpjobboard") ?></a> 
    </div>
    
    <form action="" method="post" id="wpjb-resume" class="wpjb-form" enctype="multipart/form-data">
        <fieldset>
            <legend><?php _e("Resume Information", "jobeleon") ?></legend>
            <?php if(wpjb_conf("cv_approval") == 1): ?>
            <div>
                <label class="wpjb-label"><?php _e("Resume Status", "jobeleon") ?>:</label>
                <span><?php echo wpjb_resume_status($resume) ?></span>
            </div>
            <?php endif; ?>
            <div>
                <label class="wpjb-label"><?php _e("Last Updated", "jobeleon") ?></label>
                <?php if ($resume->modified_at == "0000-00-00 00:00:00"): ?>
                    <span><?php _e("Never", "jobeleon") ?></span>
                <?php else: ?>
                    <span><?php echo wpjb_date_display(get_option("date_format"), $resume->modified_at) ?></span>
                <?php endif; ?>
            </div>
        </fieldset>
        <?php foreach ($form->getReordered() as $group): ?>
        <?php if($group->isTrashed()) continue ?>

            <?php if ($group->getName() == "experience"): ?>
                <fieldset class="wpjb-resume-detail wpjb-fieldset-<?php echo $group->getName() ?>">
                    <legend>
                        <?php esc_html_e($group->title) ?>
                        &nbsp;
                        <a href="<?php esc_attr_e(wpjr_link_to("myresume_detail_add", null, array("detail" => $group->getName()))) ?>">(<?php _e("Add Experience", "jobeleon") ?>)</a>
                    </legend>

                    <?php foreach ($form->getObject()->getExperience() as $detail): ?>

                        <div class="wpjb-experience">
                            <b>
                                <?php esc_html_e($detail->detail_title) ?>
                                <a href="<?php esc_attr_e(wpjr_link_to("myresume_detail_edit", $detail)) ?>" class="button"><?php _e("Edit", "jobeleon") ?></a>
                                <a href="<?php esc_attr_e(wpjr_link_to("myresume_detail_delete", $detail)) ?>" class="button"><?php _e("Delete", "jobeleon") ?></a>
                            </b>

                            <div class="date-range">
                            <?php $glue = "" ?>
                            <?php if($detail->started_at != "0000-00-00"): ?>
                            <?php esc_html_e(wpjb_date_display("M Y", $detail->started_at)) ?>
                            <?php $glue = "-"; ?>
                            <?php endif; ?>

                            <?php if($detail->is_current): ?>
                            <?php echo $glue." "; esc_html_e("Current", "jobeleon") ?>
                            <?php elseif($detail->completed_at != "0000-00-00"): ?>
                            <?php echo $glue." "; esc_html_e(wpjb_date_display("M Y", $detail->completed_at)) ?>
                            <?php endif; ?>
                            </div>

                            <?php if ($detail->grantor): ?>
                                <br/><em><?php esc_html_e($detail->grantor) ?></em>
                            <?php endif; ?>

                            <?php if ($detail->detail_description): ?>
                                <br/><div class="wpjb-detail-description"><?php echo nl2br(esc_html($detail->detail_description)) ?></div>
                            <?php endif; ?>
                        </div>

                    <?php endforeach; ?>

                    <?php if (count($form->getObject()->getExperience()) == 0): ?>
                        <div class="wpjb-experience"><?php _e("No experience added yet.", "jobeleon") ?></div>
                    <?php endif; ?>


                </fieldset>

            <?php elseif ($group->getName() == "education"): ?>

                <fieldset class="wpjb-resume-detail wpjb-fieldset-<?php echo $group->getName() ?>">
                    <legend>
                        <?php esc_html_e($group->title) ?>
                        &nbsp;
                        <a href="<?php esc_attr_e(wpjr_link_to("myresume_detail_add", null, array("detail" => $group->getName()))) ?>">(<?php _e("Add Education", "jobeleon") ?>)</a>
                    </legend>

                    <?php foreach ($form->getObject()->getEducation() as $detail): ?>

                        <div class="wpjb-education">
                            <b>
                                <?php esc_html_e($detail->detail_title) ?>

                                <a href="<?php esc_attr_e(wpjr_link_to("myresume_detail_edit", $detail)) ?>" class="button"><?php _e("Edit", "jobeleon") ?></a>
                                <a href="<?php esc_attr_e(wpjr_link_to("myresume_detail_delete", $detail)) ?>" class="button"><?php _e("Delete", "jobeleon") ?></a>
                            </b>

                            <div class="date-range">
                            <?php $glue = "" ?>
                            <?php if($detail->started_at != "0000-00-00"): ?>
                            <?php esc_html_e(wpjb_date_display("M Y", $detail->started_at)) ?>
                            <?php $glue = "-"; ?>
                            <?php endif; ?>

                            <?php if($detail->is_current): ?>
                            <?php echo $glue." "; esc_html_e("Current", "jobeleon") ?>
                            <?php elseif($detail->completed_at != "0000-00-00"): ?>
                            <?php echo $glue." "; esc_html_e(wpjb_date_display("M Y", $detail->completed_at)) ?>
                            <?php endif; ?>
                            </div>

                            <?php if ($detail->grantor): ?>
                                <br/><em><?php esc_html_e($detail->grantor) ?></em>
                            <?php endif; ?>
                            <?php if ($detail->detail_description): ?>
                                <br/><div class="wpjb-detail-description"><?php echo nl2br(esc_html($detail->detail_description)) ?></div>
                            <?php endif; ?>
                        </div>



                    <?php endforeach; ?>

                    <?php if (count($form->getObject()->getEducation()) == 0): ?>
                        <div class="wpjb-education"><?php _e("No education added yet.", "jobeleon") ?></div>
                    <?php endif; ?>
                </fieldset>

            <?php else: ?>

                <fieldset class="wpjb-fieldset-<?php esc_attr_e($group->getName()) ?>">
                    <legend>
                        <?php esc_html_e($group->title) ?>
                        &nbsp;
                        <a href="<?php esc_attr_e(wpjr_link_to("myresume_edit", null, array("group" => $group->getName()))) ?>">(<?php _e("Edit", "jobeleon") ?>)</a>
                    </legend>
                    <?php foreach ($group->getReordered() as $name => $field): ?>
                        <?php /* @var $field Daq_Form_Element */ ?>
                        <div class="<?php wpjb_form_input_features($field) ?>">

                            <label class="wpjb-label">
                                <?php esc_html_e($field->getLabel()) ?>
                            </label>

                            <div class="wpjb-field">
                                <?php if ($field instanceof Daq_Form_Element_Multi): ?>
                                    <?php esc_html_e($field->getValueText()) ?>
                                <?php elseif ($field instanceof Daq_Form_Element_Textarea): ?>
                                    <?php echo ($field->getValue() ? wpjb_rich_text($field->getValue(), "html") : "-") ?>
                                <?php elseif ($field instanceof Daq_Form_Element_File): ?>
                                    <?php $fname = $field->getName(); ?>
                                    <?php if(isset($resume->file->$fname)): ?>
                                    <?php foreach($resume->file->$fname as $file): ?>
                                    <a href="<?php esc_attr_e($file->url) ?>"><?php esc_html_e($file->basename) ?></a>
                                    <?php echo wpjb_format_bytes($file->size) ?><br/>
                                    <?php endforeach ?>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <?php esc_html_e($field->getValue() ? $field->getValue() : "-") ?>
                                <?php endif; ?>
                            </div>

                        </div>
                    <?php endforeach; ?>

                <?php endif; ?>
            </fieldset>
        <?php endforeach; ?>


    </form>



</div>

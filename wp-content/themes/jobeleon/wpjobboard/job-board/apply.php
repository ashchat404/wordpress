<?php
/**
 * Apply for a job form
 * 
 * Displays form that allows to apply for a selected job
 * 
 * @author Greg Winiarski
 * @package Templates
 * @subpackage JobBoard
 */
/* @var $members_only bool True if only registered members can apply for jobs */
/* @var $application_sent bool True if form was just submitted */
/* @var $job Wpjb_Model_Job */
/* @var $form Wpjb_Form_Apply */
?>
<div class="where-am-i">
    <h2><?php _e('Apply', 'jobeleon'); ?></h2>
</div><!-- .where-am-i -->


<div id="wpjb-main" class="wpjb-page-apply">

    <?php wpjb_flash(); ?>

    <?php if (isset($members_only) && $members_only): ?>
        <a href="<?php echo wpjb_link_to("job", $job) ?>"><?php _e("Go back to job details.", "jobeleon") ?></a>
    <?php else: ?>

        <?php if (!isset($application_sent)): ?>
            <form id="wpjb-apply-form" action="" method="post" enctype="multipart/form-data" class="wpjb-form">
                <?php echo $form->renderHidden() ?>
                <?php foreach ($form->getReordered() as $group): ?>
                    <?php /* @var $group stdClass */ ?> 
                    <fieldset class="wpjb-fieldset-<?php esc_attr_e($group->getName()) ?>">
                        <legend class="wpjb-empty"><?php esc_html_e($group->title) ?></legend>
                        <?php foreach ($group->getReordered() as $name => $field): ?>
                            <?php /* @var $field Daq_Form_Element */ ?>
                            <div class="<?php wpjb_form_input_features($field) ?>">

                                <label class="wpjb-label">
                                    <?php esc_html_e($field->getLabel()) ?>
                                    <?php if ($field->isRequired()): ?><span class="wpjb-required">*</span><?php endif; ?>
                                </label>

                                <div class="wpjb-field">
                                    <?php wpjb_form_render_input($form, $field) ?>
                                    <?php wpjb_form_input_hint($field) ?>
                                    <?php wpjb_form_input_errors($field) ?>
                                </div>

                            </div>
                        <?php endforeach; ?>
                    </fieldset>
                <?php endforeach; ?>
                <fieldset>
                    <legend class="wpjb-empty"></legend>
                    <input type="submit" class="wpjb-submit" id="wpjb_submit" value="<?php _e("Send Application", "jobeleon") ?>" />
                    <?php _e("or", "jobeleon"); ?>
                    <a href="<?php echo wpjb_link_to("job", $job) ?>"><?php _e("cancel and go back", "jobeleon") ?></a>
                </fieldset>
            </form>
        <?php else: ?>
            <a class="wpjb-button wpjb-cancel" href="<?php echo wpjb_link_to("job", $job) ?>"><?php _e("&larr; Go back to job details.", "jobeleon") ?></a>
        <?php endif; ?>

    <?php endif; ?>
</div>

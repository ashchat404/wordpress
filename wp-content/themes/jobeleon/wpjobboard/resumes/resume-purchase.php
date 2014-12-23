<?php
/**
 * Save job
 * 
 * Template displayed when job is being saved
 * 
 * 
 * @author Greg Winiarski
 * @package Templates
 * @subpackage JobBoard
 * 
 */
/* @var $job Wpjb_Model_Job */
/* @var $action string */
/* @var $online boolean True if job was automatically published */
/* @var $payment string Payment form */
/* @var $can_post boolean User has job posting priviledges */
?>

<div class="where-am-i">
    <h2><?php _e('Purchase resume', 'jobeleon'); ?></h2>
</div><!-- .where-am-i -->

<div id="wpjb-main" class="wpjb-page-save">

    <?php wpjb_flash(); ?>

    <table class="wpjb-info" id="wpjb-payment">
        <tbody>
            <tr>
                <td><b><?php _e("Amount", "jobeleon") ?>:</b></td>
                <td><?php esc_html_e(wpjb_price($payment->payment_sum, $payment->payment_currency)) ?></td>
            </tr>
            <tr>
                <td><b><?php _e("Discount", "jobeleon") ?>:</b></td>
                <td><?php esc_html_e(wpjb_price($payment->payment_discount, $payment->payment_currency)) ?></td>
            </tr>
            <tr>
                <td><b><?php _e("To Pay", "jobeleon") ?>:</b></td>
                <td><?php esc_html_e(wpjb_price($payment->getTotal(), $payment->payment_currency)) ?></td>
            </tr>
            <tr class="wpjb-no-border">
                <td colspan="2"><?php echo $button->render() ?></td>
            </tr>
        </tbody>
    </table>



</div>


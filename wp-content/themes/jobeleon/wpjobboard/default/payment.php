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

 /* @var $payment Object Payment form */
 /* @var $payment_form String */

?>

<div class="where-am-i">
    <h2><?php _e('Payment', 'jobeleon'); ?></h2>
</div><!-- .where-am-i -->

<div id="wpjb-main" class="wpjb-page-default-payment">

    <header class="entry-header">
        <h1 class="entry-title"><?php esc_html_e(Wpjb_Project::getInstance()->title) ?></h1>
    </header>
    
    <?php wpjb_flash(); ?>

    <p class="wpjb-complete">

        <?php _e("Please use form below to make payment. Thank you!", "wpjobboard") ?>

        <table class="wpjb-info" id="wpjb-payment">
            <tbody>
                <tr>
                    <td><b><?php _e("Listing Cost", "wpjobboard") ?>:</b></td>
                    <td><?php esc_html_e(wpjb_price($payment->payment_sum+$payment->payment_discount, $payment->payment_currency)) ?></td>
                </tr>
                <tr>
                    <td><b><?php _e("Discount", "wpjobboard") ?>:</b></td>
                    <td><?php esc_html_e(wpjb_price($payment->payment_discount, $payment->payment_currency)) ?></td>
                </tr>
                <tr>
                    <td><b><?php _e("To Pay", "wpjobboard") ?>:</b></td>
                    <td><?php esc_html_e(wpjb_price($payment->getTotal(), $payment->payment_currency)) ?></td>
                </tr>
                <tr class="wpjb-no-border">
                    <td colspan="2"><?php echo $payment_form ?></td>
                </tr>
            </tbody>
        </table>
    
    </p>

</div>


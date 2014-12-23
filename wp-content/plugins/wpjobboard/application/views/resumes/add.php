<div class="wrap">
<?php $this->_include("header.php"); ?>
<h2><?php _e("Add New Candidate", "wpjobboard"); ?> <a class="add-new-h2" href="<?php echo wpjb_admin_url("resumes", "add") ?>"><?php _e("Add New", "wpjobboard") ?></a> </h2>

<?php $this->_include("flash.php"); ?>


<form action="" method="post" id="wpjb-resume" class="wpjb-form" enctype="multipart/form-data">
    <table class="form-table">
        <tbody>

        <?php echo daq_form_layout_config($form) ?>

        <tr valign="top">
            <td class="wpjb-form-spacer" colspan="2"><h3><?php echo esc_html($formPart["title"]) ?></h3></td>
        </tr>
        
        </tbody>
    </table>

    <p class="submit">
    <input type="submit" value="<?php _e("Create Candidate", "wpjobboard") ?>" class="button-primary" name="Submit"/>
    </p>

</form>


<?php $this->_include("footer.php"); ?>
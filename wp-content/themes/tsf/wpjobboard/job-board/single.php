<?php
/**
 * Job details container
 * 
 * Inside this template job details page is generated (using function 
 * wpjb_job_template)
 * 
 * @author Greg Winiarski
 * @package Templates
 * @subpackage JobBoard
 * 
 * @var $application_url string
 * @var $job Wpjb_Model_Job
 * @var $related array List of related jobs
 * @var $show_related boolean
 */
?>

<div id="wpjb-main" class="wpjb-job wpjb-page-single">
    <?php wpjb_flash() ?>
    
    <?php $this->render("job.php") ?>

    <?php if (!wpjb_conf("front_hide_apply_link")): ?>

        <?php if ($members_only): ?>
            <div class="wpjb-job-apply">
                <div class="wpjb-flash-error">
                    <span><?php esc_html_e($form_error) ?></span>
                </div>

                <div>
                    <a class="wpjb-button" href="<?php esc_attr_e(wpjr_link_to("login")) ?>"><?php _e("Login", "jobeleon") ?></a>
                    <a class="wpjb-button" href="<?php esc_attr_e(wpjr_link_to("register")) ?>"><?php _e("Register", "jobeleon") ?></a>
                </div>
            </div>
        <?php elseif($can_apply): ?>
            <div class="wpjb-job-apply scroll_target large-12 columns" id="wpjb-scroll">
                <div>
                    <?php if ($application_url): ?>
                        <a class="wpjb-button btn" href="<?php esc_attr_e($application_url) ?>"><?php _e("Apply", "jobeleon") ?></a>
                    <?php else: ?>
                        <a class="wpjb-button wpjb-form-toggle wpjb-form-job-apply btn" href="<?php esc_attr_e(wpjb_link_to("job", $job, array("form"=>"apply"))) ?>#wpjb-scroll" rel="nofollow"  data-wpjb-form="wpjb-form-job-apply"><?php _e("Apply Online", "jobeleon") ?>  <span class="wpjb-slide-icon wpjb-none">&nbsp;</span></a>
                    <?php endif; ?>
                        
                    <?php do_action("wpjb_tpl_single_actions", $job) ?>
                </div>
                
                

                <div id="wpjb-form-job-apply" class="large-12 columns wpjb-form-slider <?php if(!$show->apply): ?>wpjb-none<?php endif; ?>">

                    <?php if (isset($form_error)): ?>
                        <div class="wpjb-flash-error" style="margin:5px">
                            <span><?php esc_html_e($form_error) ?></span>
                        </div>
                    <?php endif; ?>

                    <form id="wpjb-apply-form" action="<?php esc_attr_e(wpjb_link_to("job", $job, array("form"=>"apply"))) ?>#wpjb-scroll" method="post" enctype="multipart/form-data" class="wpjb-form wpjb-form-nolines large-6 medium-6 columns">
                        <?php echo $form->renderHidden() ?>
                        <?php foreach ($form->getReordered() as $group): ?>
                            <?php /* @var $group stdClass */ ?> 
                            <fieldset class="wpjb-fieldset-<?php esc_attr_e($group->getName()) ?>">

                                <?php if ($group->title): ?>
                                    <?php // <legend><?php esc_html_e($group->title) </legend>  ?>
                                <?php endif; ?>

                                <?php foreach ($group->getReordered() as $name => $field): ?>
                                    <?php /* @var $field Daq_Form_Element */ ?>
                                    <div class="<?php wpjb_form_input_features($field) ?>">

                                        <label class="wpjb-label">
                                            <?php esc_html_e($field->getLabel()) ?><?php if ($field->isRequired()):?><span class="wpjb-required">*</span><?php endif; ?>
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
<!--                         <fieldset>
                            <legend class="wpjb-empty"></legend>
                            <input type="submit" class="wpjb-submit" id="wpjb_submit" value="<?php _e("Send Application", "jobeleon") ?>" />
                        </fieldset> -->
                    </form>

                    <?php if(!$is_loggedin): ?>

                    <form id="wjp_register" action="" class="wpjb-form large-6 medium-6 columns" method="post">
                        <fieldset class="wpjb-fieldset-x">
                            <div class="wpjb-element-input-text wpjb-element-name-firstname">
                                <label class="wpjb-label">First name <span class=
                                "wpjb-required">*</span></label>

                                <div class="wpjb-field">
                                    <input id="firstname" name="firstname" type="text">
                                </div>
                            </div>

                            <div class="wpjb-element-input-text wpjb-element-name-lastname">
                                <label class="wpjb-label">Last name <span class=
                                "wpjb-required">*</span></label>

                                <div class="wpjb-field">
                                    <input id="lastname" name="lastname" type="text">
                                </div>
                            </div>

                            <div class="wpjb-element-input-text wpjb-element-name-user_login">
                                <label class="wpjb-label">Username <span class=
                                "wpjb-required">*</span></label>

                                <div class="wpjb-field">
                                    <input id="user_login" name="user_login" type="text">
                                </div>
                            </div>

                            <div class=
                            "wpjb-element-input-password wpjb-element-name-user_password">
                                <label class="wpjb-label">Password <span class=
                                "wpjb-required">*</span></label>

                                <div class="wpjb-field">
                                    <input id="user_password" name="user_password" type=
                                    "password">
                                </div>
                            </div>

                            <div class=
                            "wpjb-element-input-password wpjb-element-name-user_password2">
                                <label class="wpjb-label">Password (repeat)<span class=
                                "wpjb-required">*</span></label>

                                <div class="wpjb-field">
                                    <input id="user_password2" name="user_password2" type=
                                    "password">
                                </div>
                            </div>

                            <div class="wpjb-element-input-text wpjb-element-name-user_email">
                                <label class="wpjb-label">E-mail <span class=
                                "wpjb-required">*</span></label>

                                <div class="wpjb-field">
                                    <input id="user_email" name="user_email" type="text">
                                </div>
                            </div>

<!--                             <div>
                                <input id="wpjb_submit" name="wpjb_login" type="submit" value=
                                "Register account">
                            </div> -->
                        </fieldset>
                    </form>

                    <div class="error">
                        <p></p>
                    </div>
                    <div class="success">
                        <p></p>
                    </div>

                    <input type="button" value="Register and apply" class="one-submit" onclick="sub()" />
                <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
    
    <?php $relatedJobs = wpjb_find_jobs($related) ?>
    <?php if ($show_related && $relatedJobs->total > 0): ?>
        <div class="large-12 columns wpjb-job-content wpjb-related-jobs">
            <h3><?php _e("Related Jobs", "jobeleon") ?></h3>
            <ul>
                <?php foreach ($relatedJobs->job as $relatedJob): ?>
                    <?php /* @var $relatedJob Wpjb_Model_Job */ ?>
                    <li class="<?php wpjb_job_features($relatedJob); ?>">

                        <?php if ($relatedJob->isNew()): ?><span class="btn wpjb-new-related wpjb-new-btn"><?php _e("New", "jobeleon") ?></span><?php endif; ?>
                        <a href="<?php echo wpjb_link_to("job", $relatedJob); ?>"><?php esc_html_e($relatedJob->job_title) ?></a>
                        <span class="wpjb-related-posted"><?php wpjb_time_ago($relatedJob->job_created_at, __("posted {time_ago} ago.", "jobeleon")) ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    
</div>
<script type="text/javascript">
$("form.wpjb-form").get(1).setAttribute('action','http://testing.thesalesfloor.co.uk/new/wordpress/resumes/register/');
$('#email').change(function() {
    $('#user_email').val($(this).val());
});
function validateEmail(sEmail) {
    var filter = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
    if (filter.test(sEmail)) {
        return true;
    }
    else {
        return false;
    }
}

function sub(){
var proceed = true;

var ap_name = $("#wpjb-apply-form input#applicant_name").val();
var ap_email = $("#wpjb-apply-form input#email").val();

var name = $("#wjp_register input#firstname").val();
var lastname = $("#wjp_register input#lastname").val();
var uname = $("#wjp_register input#user_login").val();
var pass = $("#wjp_register input#user_password").val();
var pass2 = $("#wjp_register input#user_password2").val();
var email = $("#wjp_register input#user_email").val();

if(name == '' || lastname == '' || uname == '' || pass == '' || pass2 == '' || email == '' || ap_name == '' || ap_email == ''){
    $(".error p").html("Please fill all the fields which are marked with *");
    proceed = false;
    return false;
}

if(validateEmail(ap_email)){

}else{
    $(".error p").html("Your email is invalid");
    proceed = false;
    return false;
}

if(validateEmail(email)){
    proceed = true;
}else{
    $(".error p").html("Your email is invalid");
    proceed = false;
    return false;
}

if(pass != pass2){
    $(".error p").html("Passwords do not match");
    proceed = false;
    return false;
}

    $("#wpjb-apply-form").submit(function (event){

        if(proceed == true){
            var postData = $(this).serializeArray();
            var formURL = $(this).attr("action");
            $.ajax(
            {
                url : formURL,
                type: "POST",
                data : postData,
                success:function(data, textStatus, jqXHR) 
                {
                    $(".error").hide();
                    $(".success p").html("Application sent and registered successfully, click <a href='http://testing.thesalesfloor.co.uk/new/wordpress/resumes/my-resume/'><b>here</b></a> you view your account");
                },
                error: function(jqXHR, textStatus, errorThrown) 
                {
                    $(".error p").html("Something went wrong"); 
                }
            });
            event.preventDefault();
            $(this).unbind(event);            
        }

    });
    $("#wpjb-apply-form").submit();

    $("#wjp_register").submit(function (event){
                        
        if(proceed == true){
            var postData1 = $(this).serializeArray();
            var formURL1 = $(this).attr("action");
            $.ajax(
            {
                url : formURL1,
                type: "POST",
                data : postData1,
                success:function(data, textStatus, jqXHR) 
                {
                    $(".error").hide();
                    $(".success p").html("Application sent and registered successfully, click <a href='http://testing.thesalesfloor.co.uk/new/wordpress/resumes/my-resume/'><b>here</b></a> you view your account");
                },
                error: function(jqXHR, textStatus, errorThrown) 
                {
                    $(".error p").html("Something went wrong"); 
                }
            });
            event.preventDefault();
            $(this).unbind(event);
        }else{

        }

    });   
    $("#wjp_register").submit();

}
</script>

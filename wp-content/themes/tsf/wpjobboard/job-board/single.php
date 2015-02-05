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
<script type="text/javascript" src="https://apis.google.com/js/api.js"></script>
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
                        <a class="wpjb-button wpjb-form-toggle wpjb-form-job-apply btn" href="<?php esc_attr_e(wpjb_link_to("job", $job, array("form"=>"apply"))) ?>#wpjb-scroll" rel="nofollow"  data-wpjb-form="wpjb-form-job-apply"><?php _e("Application Link", "jobeleon") ?>  <span class="wpjb-slide-icon wpjb-none">&nbsp;</span></a>
                    <?php else: ?>
                        <a class="wpjb-button wpjb-form-toggle wpjb-form-job-apply btn" href="<?php esc_attr_e(wpjb_link_to("job", $job, array("form"=>"apply"))) ?>#wpjb-scroll" rel="nofollow"  data-wpjb-form="wpjb-form-job-apply"><?php _e("Apply Online", "jobeleon") ?>  <span class="wpjb-slide-icon wpjb-none">&nbsp;</span></a>
                    <?php endif; ?>
                        
                    <?php do_action("wpjb_tpl_single_actions", $job) ?>
                </div>
                
                

                <div id="wpjb-form-job-apply" class="large-12 columns wpjb-form-slider <?php if(!is_user_logged_in()):?> wpjb-form-slider-width<?php else:?><?php endif;?> <?php if(!$show->apply): ?>wpjb-none<?php endif; ?>">

                    <?php if (isset($form_error)): ?>
                        <div class="wpjb-flash-error" style="margin:5px">
                            <span><?php esc_html_e($form_error) ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if ($application_url): ?>
                        <?php echo do_shortcode( '[contact-form-7 id="63" title="Contact form 1"]' ); ?>
                    <?php else:?>
                        <?php if(!is_user_logged_in()):?>
                            <form id="wpjb-apply-form" action="<?php esc_attr_e(wpjb_link_to("job", $job, array("form"=>"apply"))) ?>#wpjb-scroll" method="post" enctype="multipart/form-data" class="wpjb-form wpjb-form-nolines <?php if(!is_user_logged_in()):?><?php else:?><?php endif;?>">
                                <?php echo $form->renderHidden() ?>
                                <?php foreach ($form->getReordered() as $group): ?>
                                    <?php /* @var $group stdClass */ ?> 
                                        
                                        <?php if ($group->title): ?>
                                            <?php // <legend><?php esc_html_e($group->title) </legend>  ?>
                                        <?php endif; ?>

                                        <div class="large-6 columns">
                                            <?php foreach ($group->getReordered() as $name => $field): ?>
                                                <?php if($field->getlabel() != "Message" && $field->getlabel() != "Attachments"): ?>
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
                                            <?php endif;?>
                                            <?php endforeach; ?>
                                        </div>

                                        <div class="large-6 columns">
                                            <?php foreach ($group->getReordered() as $name => $field): ?>
                                                <?php if($field->getlabel() == "Message" || $field->getlabel() == "Attachments"): ?>
                                                    <div class="<?php wpjb_form_input_features($field) ?>">
                                                        <div class="wpjb-field">
                                                            <?php wpjb_form_render_input($form, $field) ?>
                                                            <?php wpjb_form_input_hint($field) ?>
                                                            <?php wpjb_form_input_errors($field) ?>
                                                        </div>
                                                    </div>
                                            <?php endif;?>
                                            <?php endforeach; ?>
                                            <div class="large-6 medium-6 small-6 columns dp_gd">
                                                    <div id="dp">
                                                        <a id="dp_choose" href="#"><img src="<?php bloginfo('template_url'); ?>/wpjobboard/images/dp.png"></a>
                                                        <span class="dp_msg"></span>
                                                    </div>
                                                    <div id="gd">
                                                        <a id="gd_choose" href="#" ><img src="<?php bloginfo('template_url'); ?>/wpjobboard/images/gd.png"></a>
                                                        <span class="gd_msg"></span>
                                                    </div>
                                            </div>
                                        </div>
                                <?php endforeach; ?>
                            </form>

                        

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
                                        <label class="wpjb-label">Password (Repeat)<span class=
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
                                </fieldset>
                            </form>
                            <div class="clear"></div>
                            <div class="msgs">
                                <div class="error text-center">
                                    <p class="reg"></p><p class="app"></p><p class="err"></p>
                                </div>
                                <div class="success text-center">
                                    <p class="reg"></p><p class="app"></p>
                                </div>
                            </div>

                            <input type="button" value="Apply and Save Details" class="one-submit" onclick="validation()" />
                        <?php else: ?>
                            <form id="wpjb-apply-form" action="<?php esc_attr_e(wpjb_link_to("job", $job, array("form"=>"apply"))) ?>#wpjb-scroll" method="post" enctype="multipart/form-data" class="wpjb-form wpjb-form-nolines">
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
                                            <div class="large-6 columns dp_gd">
                                                    <div id="dp">
                                                        <a id="dp_choose" href="#"><img src="<?php bloginfo('template_url'); ?>/wpjobboard/images/dp.png"></a>
                                                        <span class="dp_msg"></span>
                                                    </div>
                                                    <div id="gd">
                                                        <a id="gd_choose" href="#" ><img src="<?php bloginfo('template_url'); ?>/wpjobboard/images/gd.png"></a>
                                                        <span class="gd_msg"></span>
                                                    </div>
                                            </div>
                                    </fieldset>
                                <?php endforeach; ?>
                                <fieldset>
                                    <legend class="wpjb-empty"></legend>
                                    <input type="submit" class="wpjb-submit" id="wpjb_submit" value="<?php _e("Send Application", "jobeleon") ?>" />
                                </fieldset>
                            </form>
                        <?php endif;?>
                    <?php endif;?>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
<div class="cd-tabs">
    <nav>
        <ul class="cd-tabs-navigation">
            <li><a data-content="related_jobs" class="selected" href="#0"><h3><?php _e("Related Jobs", "jobeleon") ?></h3></a></li>
            <li><a data-content="related_location" href="#0"><h3><?php _e("Jobs by location", "jobeleon") ?></h3></a></li>
            <li><a data-content="related_industry" href="#0"><h3><?php _e("Jobs by Industry", "jobeleon") ?></h3></a></li>
        </ul>
    </nav>

    <ul class="cd-tabs-content">
        <li id="related_jobs" data-content="related_jobs" class="selected">
        </li>

        <li id="related_location" data-content="related_location">
        </li>

        <li id="related_industry" data-content="related_industry">
        </li>

    </ul> <!-- cd-tabs-content -->
</div> <!-- cd-tabs -->
    
</div>
<script type="text/javascript" src="https://www.dropbox.com/static/api/2/dropins.js" id="dropboxjs" data-app-key="5d6y33s4m5iznz8"></script>
<script type="text/javascript">
    $("#related_jobs").load("http://testing.thesalesfloor.co.uk/new/wordpress/jobs/find/?query=<?php $l = str_replace(' ','+',$job->job_title);  echo $l; ?>=&category= #wpjb-job-list",function(){
        if($("#related_jobs .wpjb-table tr").attr("id") === "job-<?php esc_html_e($job->id); ?>"){
            $("#job-<?php esc_html_e($job->id);?>").remove();
        }
        if($("#related_jobs #wpjb-job-list tbody tr").length === 0){
            $("#related_jobs #wpjb-job-list tbody").append("<tr></tr>");
            $("#related_jobs #wpjb-job-list tbody tr").text("No job found");
        }
    });
    $("#related_location").load("http://testing.thesalesfloor.co.uk/new/wordpress/jobs/find/?query=<?php $p = str_replace(' ','+',$job->locationToString());echo $p; ?>=&category= #wpjb-job-list",function(){
        if($("#related_location .wpjb-table tr").attr("id") === "job-<?php esc_html_e($job->id); ?>"){
            $("#job-<?php esc_html_e($job->id);?>").remove();
        }
        if($("#related_location #wpjb-job-list tbody tr").length === 0){
            $("#related_location #wpjb-job-list tbody").append("<tr></tr>");
            $("#related_location #wpjb-job-list tbody tr").text("No job found");
        }

    });
    $("#related_industry").load("http://testing.thesalesfloor.co.uk/new/wordpress/jobs/find/?query=&category=<?php foreach ($job->getTag()->category as $category){esc_html_e($category->id);} ?> #wpjb-job-list",function(){
        if($("#related_industry .wpjb-table tr").attr("id") === "job-<?php esc_html_e($job->id); ?>"){
            $("#job-<?php esc_html_e($job->id);?>").remove();
        }
        if($("#related_industry #wpjb-job-list tbody tr").length === 0){
            $("#related_industry #wpjb-job-list tbody").append("<tr></tr>");
            $("#related_industry #wpjb-job-list tbody tr").text("No job found");
        }
    });


jQuery(document).ready(function($){
    var tabItems = $('.cd-tabs-navigation a'),
        tabContentWrapper = $('.cd-tabs-content');

    tabItems.on('click', function(event){
        event.preventDefault();
        var selectedItem = $(this);
        if( !selectedItem.hasClass('selected') ) {
            var selectedTab = selectedItem.data('content'),
                selectedContent = tabContentWrapper.find('li[data-content="'+selectedTab+'"]'),
                slectedContentHeight = selectedContent.innerHeight();
            
            tabItems.removeClass('selected');
            selectedItem.addClass('selected');
            selectedContent.addClass('selected').siblings('li').removeClass('selected');
            //animate tabContentWrapper height when content changes 
            tabContentWrapper.animate({
                'height': slectedContentHeight
            }, 200);
        }
    });

    //hide the .cd-tabs::after element when tabbed navigation has scrolled to the end (mobile version)
    checkScrolling($('.cd-tabs nav'));
    $(window).on('resize', function(){
        checkScrolling($('.cd-tabs nav'));
        tabContentWrapper.css('height', 'auto');
    });
    $('.cd-tabs nav').on('scroll', function(){ 
        checkScrolling($(this));
    });

    function checkScrolling(tabs){
        var totalTabWidth = parseInt(tabs.children('.cd-tabs-navigation').width()),
            tabsViewport = parseInt(tabs.width());
        if( tabs.scrollLeft() >= totalTabWidth - tabsViewport) {
            tabs.parent('.cd-tabs').addClass('is-ended');
        } else {
            tabs.parent('.cd-tabs').removeClass('is-ended');
        }
    }
});

    var ap_link = "<?php esc_attr_e($application_url) ?>";
    console.log(ap_link);
    $(document).ready( function() {
        var proceed;
        options = {
            success: function(files) {
                $("#dropbox_link").val(files[0].link);
                $(".dp_msg").html("✔");
            },
            cancel: function() {
            },
            linkType: "preview", // or "direct"
            multiselect: false, // or true
            extensions: ['.pdf', '.doc', '.docx'],
        };
        $("#dp_choose").click(function(event){
            event.preventDefault();
            Dropbox.choose(options);
        })
        $(".wpcf7-form input[type=url]").val($(location).attr('href'));
        if($("form.wpjb-form").length){
            $("form.wpjb-form").get(1).setAttribute('action','http://testing.thesalesfloor.co.uk/new/wordpress/resumes/register/');            
        }
        $('#email').change(function() {
            $('#user_email').val($(this).val());
        });
        $('#applicant_name').change(function() {
            $('#firstname').val($(this).val());
        });
        $('#last_name').change(function() {
            $('#lastname').val($(this).val());
        });
    });
    function onApiLoad(){
        gapi.load('auth',{'callback':onAuthApiLoad}); 
        gapi.load('picker'); 
    }
    $("#gd_choose").click(function(event){
        event.preventDefault();
        onApiLoad();
    });
    function onAuthApiLoad(){
        window.gapi.auth.authorize({
            'client_id':'139188146942-8fb0f8h6rdgkq2b3foiornkmjmmbdknj.apps.googleusercontent.com',
            'scope':['https://www.googleapis.com/auth/drive']
        },handleAuthResult);
    } 
    var oauthToken;
    function handleAuthResult(authResult){
        if(authResult && !authResult.error){
            oauthToken = authResult.access_token;
            createPicker();
        }else{
        }
    }
    function createPicker(){    
        var picker = new google.picker.PickerBuilder()
            .addView(new google.picker.DocsView())                
            .setOAuthToken(oauthToken)
            .setDeveloperKey('AIzaSyBt8bjYSFra-dwqNSVj-EQmLb2HyuwMcVU')
            .setCallback(pickerCallback)
            .build();
        picker.setVisible(true);
    }
    function pickerCallback(data) {
        var url = 'nothing';
        if (data[google.picker.Response.ACTION] == google.picker.Action.PICKED) {
          var doc = data[google.picker.Response.DOCUMENTS][0];
          url = doc[google.picker.Document.URL];
          $('#googledrive_link').val(url);
          $(".gd_msg").html("✔");
        }
        
      }
    function validateEmail(sEmail) {
        var filter = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
        if (filter.test(sEmail)) {
            return true;
        }
        else {
            return false;
        }
    }
    function validation(){
        proceed = true;
        var ap_name = $("#wpjb-apply-form input#applicant_name").val();
        var ap_email = $("#wpjb-apply-form input#email").val();
        var name = $("#wjp_register input#firstname").val();
        var lastname = $("#wjp_register input#lastname").val();
        var uname = $("#wjp_register input#user_login").val();
        var pass = $("#wjp_register input#user_password").val();
        var pass2 = $("#wjp_register input#user_password2").val();
        var email = $("#wjp_register input#user_email").val();
        if(name == '' || lastname == '' || uname == '' || pass == '' || pass2 == '' || email == '' || ap_name == '' || ap_email == ''){
            $(".error p.err").html("Please fill all the fields which are marked with *");
            proceed = false;
            return false;
        }
        else if(!validateEmail(email)){
            $(".error p.err").html("Your email is invalid");
            proceed = false;
            return false;    
        }
        else if(!validateEmail(ap_email)){
            $(".error p.err").html("Your email is invalid");
            proceed = false;
            return false;
        }
        else if(pass != pass2){
            $(".error p.err").html("Passwords do not match");
            proceed = false;
            return false;
        }
        else{
            var data = 'email-address='+email+'&usname='+uname;
            $(".success p.app").show();
            $(".success p.app").html("<img src='<?php bloginfo('template_url'); ?>/wpjobboard/images/spinner-2x.gif'>")
            $.ajax({
                    type:"post",
                    url:"<?php bloginfo('template_url'); ?>/wpjobboard/job-board/check.php",
                    data:data,
                    success:function(result){
                        if(result=="emailTakenunTaken"){
                            $(".success p.app").hide();
                            $(".error p.err").html("Email used for registration and username are already taken, please try again or click <a href='http://localhost:8888/wordpress/wp-login.php?action=lostpassword'>Forgot Password</a>");
                            proceed = false;
                            return false;
                            
                        }
                        else if(result=="emailAvailableunTaken"){
                            $(".success p.app").hide();
                            $(".error p.err").html("Username already taken");
                            proceed = false;
                            return false;
                            
                        }
                        else if(result=="emailTakenunAvailable"){
                            $(".success p.app").hide();
                            $(".error p.err").html("Email used for registration is already taken, please use a different email or click <a href='http://localhost:8888/wordpress/wp-login.php?action=lostpassword'>Forgot Password</a>");
                            proceed = false;
                            return false;
                            
                        }else{
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
                                            $(".success p.app").show();
                                            $(".success p.app").html("Application sent successfully, view your email for confirmation");
                                        },
                                        error: function(jqXHR, textStatus, errorThrown) 
                                        {
                                            $(".error p.app").html("Something went wrong"); 
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
                                            $(".success p.reg").html("Registered successfully, click <a href='http://testing.thesalesfloor.co.uk/new/wordpress/resumes/my-resume/'><b>here</b></a> you view your account");
                                        },
                                        error: function(jqXHR, textStatus, errorThrown) 
                                        {
                                            $(".error p.red").html("Something went wrong"); 
                                        }
                                    });
                                    event.preventDefault();
                                    $(this).unbind(event);
                                }else{
                                }
                            });   
                            $("#wjp_register").submit();
                        }
                    }
             });
        }
    }
</script>

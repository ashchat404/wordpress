<?php
require_once ('../../../../../wp-load.php');
$mm = $_POST['email-address'];
$nn = $_POST['usname'];

if ( isset( $_POST['email-address'] ) && ! empty( $_POST['email-address'] ) && isset( $_POST['usname'] ) &&  ! empty( $_POST['usname'] )) {
	
  //sanitize the data
  $email_addr = trim( strip_tags( stripslashes( $_POST['email-address'] ) ) );
  $uname = trim( strip_tags( stripslashes( $_POST['usname'] ) ) );

	if( false == get_user_by( 'email', $email_addr ) ) {
		echo "emailAvailable";
	} else {
		echo "emailTaken";
	}

	if(!username_exists( $uname )){
		echo "unAvailable";
	}else{
		echo "unTaken";
	}

}
?>
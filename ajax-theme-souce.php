1. enqueue the script
2. localize - we can hardcode a value on our .js file. But if we want to get dynamic value from PHP, localize the value in PHP and send to .js file.
			You can  get the value dynamic value in .js file now.
3.  JS file- serialize and get form field values and send to the PHP file
4.  PHP file- do the funcionality and send the response back to JS file	
5.  JS file- Print the response	


<?php

/*     functions.php     */

function test_ajax_load_scripts() {

	wp_enqueue_script( "ajax-test", get_stylesheet_directory_uri() . '/js/la-ajax-script.js', array( 'jquery' ) );

	wp_localize_script( 'ajax-test', 'the_ajax_script', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );	
}
add_action('wp_print_scripts', 'test_ajax_load_scripts');

function pet_on_hold_form_ajax_process_request(){
	$fname = $_POST['formdata'][0]['value'];
	$lname = $_POST['formdata'][1]['value'];
	$email = $_POST['formdata'][2]['value'];
	$address = $_POST['formdata'][3]['value'];
	$city = $_POST['formdata'][4]['value'];
	$state = $_POST['formdata'][5]['value'];
	$zip = $_POST['formdata'][6]['value'];
	$message = $_POST['formdata'][7]['value'];
	$place_on_hold = $_POST['formdata'][8]['value'];
	$total = $_POST['formdata'][9]['value'];
	$card_no = $_POST['formdata'][10]['value'];
	$exp_month = $_POST['formdata'][11]['value'];
	$exp_year = $_POST['formdata'][12]['value'];
	$security_code = $_POST['formdata'][13]['value'];
	$card_holder_name = $_POST['formdata'][14]['value'];
	
	$from = get_field('from', 'option');
	$to = get_field('to', 'option');
	$subject = get_field('subject_3', 'option');	
	$msg = "<table><tr><td>Name</td><td>".$fname." ".$lname."</td></tr><tr><td>Email</td><td>".$email."</td></tr><tr><td>Address</td><td>".$address."</td></tr><tr><td>City</td><td>".$city."</td></tr><tr><td>State</td><td>".$state."</td></tr><tr><td>Zip</td><td>".$zip."</td></tr><tr><td>Message</td><td>".$message."</td></tr><tr><td>Place On-Hold</td><td>".$place_on_hold."</td></tr><tr><td>Total</td><td>".$total."</td></tr><tr><td>Card No</td><td>".$card_no."</td></tr><tr><td>Expiration Month</td><td>".$exp_month."</td></tr><tr><td>Expiration Year</td><td>".$exp_year."</td></tr><tr><td>Security Code</td><td>".$security_code."</td></tr><tr><td>Cardholder Name</td><td>".$card_holder_name."</td></tr></table>";
	
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	$headers .= 'From: <'.$from.'>' . "\r\n";
	
	if(mail($to,$subject,$msg,$headers)){
		$response = '{"status":"0", "message":"Thank you for your message. It has been sent."}';
	}else{
		$response = '{"status":"1", "message":"There was an error trying to send your message. Please try again later."}';
	}
	echo $response;
	die();
}

add_action('wp_ajax_pet_on_hold_form', 'pet_on_hold_form_ajax_process_request');
add_action('wp_ajax_nopriv_pet_on_hold_form', 'pet_on_hold_form_ajax_process_request');

?>

------------------------------------------------------------------

js/la-ajax-script.js

<script>
jQuery(document).ready( function($) {
	jQuery("#productsubmit").click( function() {
		
		var emailRegex =  /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
		
		var formEmail = $("#email").val();

		if(jQuery("#email").val() == ""){
			jQuery("#email").focus();
			jQuery("#email_error").show();
			jQuery("#email_error").html('Please Enter Email');
			return false;
		}else if(!emailRegex.test(formEmail)){
			jQuery("#email").focus;
			jQuery("#email_error").show();
			jQuery("#email_error").html('Please Enter Valid Email');
			return false;
		}else{
			jQuery("#email_error").html('');
		}
		
		if($('input[name=optradio]:checked').length<=0)
		{
			jQuery("#radio_error").html('OK to text you is required');
			return false;
		}else{
			jQuery("#radio_error").html('');
		}
		
		var input_data = jQuery("#productForm").serializeArray();
		var formDataJ = JSON.stringify(input_data);
		var formData = JSON.parse(formDataJ);
		
		jQuery.ajax({
			type : 'POST',
			datatype: 'json',
			url  : the_ajax_script.ajaxurl,
			data : {action : 'my_pdf_form', formdata: formData},
			success: function(response){var json_resp = jQuery.parseJSON(response); 
			if(json_resp.status == 0){
				jQuery("#form-success").html(json_resp.message);
			}else if(json_resp.status == 1){
				jQuery("#form-error").html(json_resp.message);
			}else{}
			}
			});
		return false;

	});
	});
	
</script>	
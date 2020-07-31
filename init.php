<?php
/*
Plugin Name: Ajax test Plugin
*/

add_action('admin_menu','testPlugin');
function testPlugin() {
	
	add_menu_page('Test',
	'Test', 
	'manage_options',
	'test_plugin',
	'test_plugin' 
	);

}

function test_plugin(){?>
<p class="ajax-link" >Test</p>
<?php 
 }
 
 function test_ajax_load_scripts() {
	// load our jquery file that sends the $.post request
	wp_enqueue_script( "ajax-test", plugin_dir_url( __FILE__ ) . '/ajax-test.js', array( 'jquery' ) );
 
	// make the ajaxurl var available to the above script
	wp_localize_script( 'ajax-test', 'the_ajax_script', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );	
}
add_action('wp_print_scripts', 'test_ajax_load_scripts');

function text_ajax_process_request() {
	// first check if data is being sent and that it is the data we want
  	if ( isset( $_POST["post_var"] ) ) {
		// now set our response var equal to that of the POST var (this will need to be sanitized based on what you're doing with with it)
		$response = $_POST["post_var"];
		// send the response back to the front end
		echo $response;
		die();
	}
}
add_action('wp_ajax_test_response', 'text_ajax_process_request');
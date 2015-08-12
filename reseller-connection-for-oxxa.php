<?php defined('ABSPATH') or die("No script kiddies please!");
/*
Plugin Name: Reseller Connection for OXXA.com
Plugin URI: http://nielshoogenhout.be
Description: Reseller Connection for OXXA.com let's your users manage their domainnames with their Wordpress website profile and login.
Author: Niels Hoogenhout
Version: 0.0.1
Author URI: http://nielshoogenhout.be
*/

   include(plugin_dir_path( __FILE__ )."rcfo-functions.php");
   include(plugin_dir_path( __FILE__ )."rcfo-pagelist.php");
   include(plugin_dir_path( __FILE__ )."rcfo-check-widget.php");

function rcfo_nh_version(){
	$plugin_data = get_plugin_data( __FILE__ );
	if(get_option('rcfo_nh_version') == ""){
		#wp_mail("info@nielshoogenhout.be", "Install ORC V".$plugin_data['Version']." ".get_bloginfo('name'), get_bloginfo('name').": ".get_bloginfo('wpurl'));
		update_option('rcfo_nh_version',$plugin_data['Version']);
	}elseif(get_option('rcfo_nh_version') != $plugin_data['Version']){
		#wp_mail("info@nielshoogenhout.be", "Upgrade ORC V".$plugin_data['Version']." ".get_bloginfo('name'), get_bloginfo('name').": ".get_bloginfo('wpurl'));
		update_option('rcfo_nh_version',$plugin_data['Version']);	}
}

function rcfo_nh_scripts() {

	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui-core');
	wp_enqueue_script('jquery-ui-dialog');
	wp_enqueue_style( 'wp-jquery-ui-dialog' );

}

function load_plugin_textdomain_rcfo_nh() {
  load_plugin_textdomain( 'rcfo-nh', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}

function menu_rcfo_nh() {
	add_options_page( 'OXXA Reseller Connection', 'OXXA Admin Settings', 'manage_options', 'rcfo-nh', 'plugin_options_rcfo_nh' );
}

function plugin_options_rcfo_nh() {
	if ( current_user_can( 'manage_options' ) ) {
	?>

	<script src="http://crypto-js.googlecode.com/svn/tags/3.0.2/build/rollups/md5.js"></script>
	<script type="text/javascript">
	jQuery(document).ready(function($) {
	    $('a[rcfo-settings-page^=#rcfo-option]').click(function() { event.preventDefault();
	  	var div = $(this).attr('rcfo-settings-page');
 		$(".wp-rcfo-set").hide(); 
       		$(div).show();
		localStorage.setItem("rcfo-Hash", div);
	    });
		if(localStorage.getItem("rcfo-Hash") === null) { var hash = "rcfo-option-1"; } else { var hash = localStorage.getItem("rcfo-Hash"); }
		$(hash).show();
	    $('#rcfo_nh_price_type').on('change', function() {
    		  var countryVal = $("select option:selected").val();
    		$("#type-box-2, #type-box-3").hide();
    		if(countryVal == 2) {$("#type-box-2").show();} else if(countryVal == 3) {$("#type-box-3").show();};
	    });
   		if (location.hash) {
 		 setTimeout(function() {
		    window.scrollTo(0, 0);
		  }, 1);
		}
	    $('#rcfo_nh_key_enter').on('input',function(e){
		var passhash = CryptoJS.MD5($(this).val());
      	     $('#rcfo_nh_key').val(passhash);
   	    });
	});
	</script>

<div style="position: fixed; right; top: 60px; right: 30px;">
	<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
        <input type="hidden" name="cmd" value="_s-xclick">
        <input type="hidden" name="hosted_button_id" value="ENVJCYHTHYDVJ">
        <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
        <img alt="" border="0" src="https://www.paypalobjects.com/nl_NL/i/scr/pixel.gif" width="1" height="1">
        </form>
</div>

    <form method="post" action="options.php"> 
        <?php @settings_fields('options_group_rcfo_nh'); ?>
        <?php @do_settings_fields('options_group_rcfo_nh'); ?>
	<?php echo '<div class="wrap"><h2>'. __('OXXA Reseller Settings', 'rcfo-nh').'</h2>'; ?>

<a rcfo-settings-page="#rcfo-option-1" class="button button-primary"><?php _e('Oxxa Connection', 'rcfo-nh'); ?></a> <a rcfo-settings-page="#rcfo-option-2" class="button button-primary"><?php _e('Price calculations', 'darc-nh'); ?></a> <a rcfo-settings-page="#rcfo-option-3" class="button button-primary"><?php _e('Shortcode examples', 'rcfo-nh'); ?></a> <a href="https://wordpress.org/support/plugin/" target="_blank" class="button button-primary"><?php _e('Support', 'rcfo-nh'); ?></a> <a href="http://nielshoogenhout.eu/support-contact/?utm_source=WP-Plugin&utm_medium=referral&utm_campaign=orc" target="_blank" class="button button-primary"><?php _e('Contact', 'rcfo-nh'); ?></a>

<div id="rcfo-option-1" class="wp-rcfo-set" style="display: none;">
	<h4><?php _e('Setup the connection to Oxxa.com', 'rcfo-nh'); ?></h4>
        <table class="form-table">  
            <tr valign="top">
                <th scope="row"><label for="rcfo_nh_account"><?php _e('Reseller username', 'rcfo-nh'); ?></label></th>
                <td><input type="text" name="rcfo_nh_account" id="rcfo_nh_account" value="<?php echo get_option('rcfo_nh_account'); ?>" />
		</td><td><span class="description"><?php _e('Reseller OXXA account', 'rcfo-nh'); ?></span></td>
            </tr>
	<?php if(get_option('rcfo_nh_key') == ""){ ?>
            <tr valign="top">
                <th scope="row"><label for="rcfo_nh_key_enter"><?php _e('Reseller password', 'rcfo-nh'); ?></label></th>
                <td><input type="password" name="rcfo_nh_key_enter" id="rcfo_nh_key_enter" />
		</td><td><span class="description"><?php _e('Your reseller password (Please post your <a href="http://www.md5.cz" target=\"_blank\">hashed password</a> for security reasons!', 'rcfo-nh'); ?></span></td>
            </tr>
	    <tr valign="top" style="display:none;">
                <th scope="row"><label for="rcfo_nh_key"><?php _e('Encoded password', 'rcfo-nh'); ?></label></th>
                <td><input type="text" name="rcfo_nh_key" id="rcfo_nh_key" value="<?php echo get_option('rcfo_nh_key'); ?>" />
		</td><td><span class="description"><?php _e('Your reseller password (You password will is saved hashed for security reasons!', 'rcfo-nh'); ?></span></td>
            </tr>
	<?php }else{ ?>
            <tr valign="top">
                <th scope="row"><label for="rcfo_nh_key"><?php _e('Reseller password', 'rcfo-nh'); ?></label></th>
                <td><input type="hidden" name="rcfo_nh_key" id="rcfo_nh_key" value="<?php echo get_option('rcfo_nh_key'); ?>" CHECKED visible=HIDDEN /><input type="checkbox" name="rcfo_nh_key" id="rcfo_nh_key" value="" /> <?php _e('delete password', 'rcfo-nh'); ?></td>
		<td><span class="description"><?php _e('Check this field to delete your password form the database.', 'rcfo-nh'); ?></span></td>
            </tr>
	<?php } ?>
        </table>

        <?php @submit_button(); ?>

</div><div id="rcfo-option-2" class="wp-rcfo-set" style="display: none;">

	<h4><?php _e('Profit margin for domain names:', 'rcfo-nh'); ?></h4>
        <table class="form-table">  
            <tr valign="top">
                <th scope="row"><label for="rcfo_nh_price_type"><?php _e('Type', 'rcfo-nh'); ?></label></th>
                <td><select name="rcfo_nh_price_type" id="rcfo_nh_price_type">
			<option value="1" <?php selected(get_option( 'rcfo_nh_price_type'),'1'); ?> >Purchase price</option>
			<option value="2" <?php selected(get_option( 'rcfo_nh_price_type'),'2'); ?> >Percentage</option> 
			<option value="3" <?php selected(get_option( 'rcfo_nh_price_type'),'3'); ?> >Fixed amount</option> 
		</select></td><td><span class="description"> <?php _e('Choose the margin type you want to set.', 'rcfo-nh'); ?></span>
		</td>
            </tr>
	    <tr id="type-box-2" style="<? if(get_option('rcfo_nh_price_type') != "2"){ ?> display: none; <?php } ?>">
                <th scope="row"><label for="rcfo_nh_price_percentage"><?php _e('Percentage', 'rcfo-nh'); ?></label></th>
                <td><input type="number" step="any" min="0" size="4" name="rcfo_nh_price_percentage" id="rcfo_nh_price_percentage" value="<?php echo get_option('rcfo_nh_price_percentage'); ?>" /> %
		</td><td><span class="description"><?php _e('Enter the percentage you want as a margin above the purchase price. <i>Example: 7,5</i>', 'rcfo-nh'); ?></span></td>
            </tr>
	    <tr id="type-box-3" style="<? if(get_option('rcfo_nh_price_type') != "3"){ ?> display: none; <?php } ?>">
                <th scope="row"><label for="rcfo_nh_price_fixed"><?php _e('Amount', 'rcfo-nh'); ?></label></th>
                <td>&#8364;<input type="number" step="any" min="0" size="4" name="rcfo_nh_price_fixed" id="rcfo_nh_price_fixed" value="<?php echo get_option('rcfo_nh_price_fixed'); ?>" />
		</td><td><span class="description"><?php _e('Enter the amount you want as a margin above the purchase price. <i>Example: 2,5</i>', 'rcfo-nh'); ?></span></td>
            </tr>
	</table>
	<h4><?php _e('Tax calculation:', 'rcfo-nh'); ?></h4>
        <table class="form-table">  
	    <tr>
                <th scope="row"><label for="rcfo_nh_price_tax"><?php _e('Tax:', 'rcfo-nh'); ?></label></th>
                <td><input type="number" step="any" min="0" size="4" name="rcfo_nh_price_tax" id="rcfo_nh_price_tax" value="<?php echo get_option('rcfo_nh_price_tax','0'); ?>" /> %
		</td><td><span class="description"><?php _e('Enter the legal tax rate of your country or put at 0 for excluding tax.', 'rcfo-nh'); ?></span></td>
            </tr>
	    <tr>
                <th scope="row"><label for="rcfo_nh_price_calc_tax"><?php _e('Calculate tax?', 'rcfo-nh'); ?></label></th>
		<td><input type="checkbox" name="rcfo_nh_price_calc_tax" id="rcfo_nh_price_calc_tax" value="0" <?php checked( '0', get_option( 'rcfo_nh_price_calc_tax' ) ); ?> ></td> 
		</td><td><span class="description"> <?php _e('Do you want to calculate tax rate into the shown prices?', 'rcfo-nh'); ?></span></td>
            </tr>
	</table>

        <?php @submit_button(); ?>

</div>
    </form>

  <div id="rcfo-option-3" class="wp-rcfo-set" style="display: none;">
 	<?php echo '<h3>'. __('Domain list for the user:', 'rcfo-nh').'</h3>'; ?>
 	<?php echo '<input type="text" size="25" value="[rcfo-nh-page-list]" onclick="this.select()">'.__('No settings needed', 'rcfo-nh'); ?>

</div>
<?php
	echo '</div>';
	}
}

function register_setting_rcfo_nh() {
	register_setting('options_group_rcfo_nh', 'rcfo_nh_account'); 
	register_setting('options_group_rcfo_nh', 'rcfo_nh_key');
	register_setting('options_group_rcfo_nh', 'rcfo_nh_price_calc_tax');
	register_setting('options_group_rcfo_nh', 'rcfo_nh_price_tax');
	register_setting('options_group_rcfo_nh', 'rcfo_nh_price_type');
	register_setting('options_group_rcfo_nh', 'rcfo_nh_price_percentage');
	register_setting('options_group_rcfo_nh', 'rcfo_nh_price_fixed');
} 

function user_profile_field_rcfo_nh() {
    if ( current_user_can( 'manage_options' ) ){
		global $profileuser; ?>
 <h3>OXXA Connection Plugin</h3>
 
 <table class="form-table">
 <tr>
 <th><label for="rcfo_nh_oxxaid"><?php _e('OXXA.com username', 'rcfo-nh'); ?></label></th>
 <td>
 <input type="text" id="rcfo_nh_oxxaid" name="rcfo_nh_oxxaid" size="20" value="<?php echo get_the_author_meta( 'rcfo_nh_oxxaid', $profileuser->ID ); ?>">
 <span class="description"><?php _e('Fill in the username of the clients oxxa.com account.', 'rcfo-nh'); ?></span>
 </td>
 </tr>
 </table>
<?php 
    }
}

function save_user_profile_field_rcfo_nh($user_id){
    if ( current_user_can( 'manage_options' ) ){
 	update_user_meta( $user_id, 'rcfo_nh_oxxaid', $_POST['rcfo_nh_oxxaid'] );
    }
}


function register_shortcodes_rcfo_nh(){
   add_shortcode('rcfo-nh-page-list', 'rcfo_nh_page_list');
   add_shortcode('rcfo-nh-domain-check', 'rcfo_nh_domain_check');
}

add_action( 'admin_init', 'register_setting_rcfo_nh' );
add_action( 'admin_menu', 'menu_rcfo_nh' );
add_action( 'admin_menu', 'rcfo_nh_version' );

add_action( 'show_user_profile', 'user_profile_field_rcfo_nh' );
add_action( 'edit_user_profile', 'user_profile_field_rcfo_nh' );
add_action( 'personal_options_update', 'save_user_profile_field_rcfo_nh' );
add_action( 'edit_user_profile_update', 'save_user_profile_field_rcfo_nh' );

add_action('plugins_loaded', 'rcfo_nh_init_sidebar_widget');
add_action('plugins_loaded', 'load_plugin_textdomain_rcfo_nh');

add_action( 'wp_enqueue_scripts', 'rcfo_nh_scripts' );

add_action( 'init', 'register_shortcodes_rcfo_nh');

?>
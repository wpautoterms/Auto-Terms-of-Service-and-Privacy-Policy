<?php
/*
Plugin Name: Auto Terms of Service and Privacy Policy
Plugin URI: http://www.wpautoterms.com/
Description: Puts your own information into a version of Automattic's <a href="http://en.wordpress.com/tos/">Terms of Service</a> and <a href="http://automattic.com/privacy/">Privacy Policy</a>, both available under the <a href="http://creativecommons.org/licenses/by-sa/3.0/">Creative Commons Sharealike</a> license, that have been modified to exclude specifics to Automattic (like mentions of "JetPack", "WordPress.com", and "VIP") and have more generic language that can apply to most any site or service provider, including single sites, subscription sites, blog networks, and others. <strong>Edit plugin's settings, then use one or more of the 3 available shortcodes: [my_terms_of_service_and_privacy_policy], [my_terms_of_service], and/or [my_privacy_policy]</strong>
Text Domain: auto-terms-of-service-and-privacy-policy
Version: 1.8.1
Author: TourKick (Clifford P)
Author URI: http://www.wpautoterms.com/
License: GPL version 3 or any later version
*/
/*
WARNING: I'm human. I'm not perfect. Neither are you. Neither is this...
But hopefully it's more good than bad.
*/
/*
DISCLAIMER: I am not an attorney. I am not liable for any content, code, or other errors or omissions or inaccuracies. This plugin provides no warranties or guarantees. Do not rely on me to update the plugin ever. USE AT YOUR OWN RISK. I am not liable if Automattic removes their permission to use and adapt their work or if this plugin blows you or your website up or does anything negative. Finally, it needs to be said: READ YOUR OWN TERMS OF SERVICE AND PRIVACY POLICY BEFORE PUBLISHING. If you do not like Automattic's version, simply replacing their info with yours, maybe this plugin is not for you.
-Clifford Paulick and/or TourKick LLC
*/


defined( 'ABSPATH' ) or die(); //do not allow plugin file to be called directly (security protection)


// plugin folder path
if(!defined('TCPP_PLUGIN_DIR')) {
	define('TCPP_PLUGIN_DIR', plugin_dir_path( __FILE__ ));
}
// plugin folder url
if(!defined('TCPP_PLUGIN_URL')) {
	define('TCPP_PLUGIN_URL', plugin_dir_url( __FILE__ ));
}


// START Freemius
// Create a helper function for easy SDK access.
function tk_auto_terms_freemius() {
    global $tk_auto_terms_freemius;

    if ( ! isset( $tk_auto_terms_freemius ) ) {
        // Include Freemius SDK.
        require_once dirname(__FILE__) . '/freemius/start.php';

        $tk_auto_terms_freemius = fs_dynamic_init( array(
            'id'                => '78',
            'slug'              => 'auto-terms-of-service-and-privacy-policy',
            'type'              => 'plugin',
            'public_key'        => 'pk_d9ea0af03f0660769f7de48a9f664',
            'is_premium'        => false,
            'has_addons'        => false,
            'has_paid_plans'    => false,
            'menu'              => array(
                'slug'       => 'auto-terms-of-service-and-privacy-policy/auto-terms-of-service-privacy-policy.php',
                'parent'     => array(
                    'slug' => 'options-general.php',
                ),
            ),
        ) );
    }

    return $tk_auto_terms_freemius;
}

// Init Freemius.
tk_auto_terms_freemius();


function tk_auto_terms_freemius_agreement_text() {
	return sprintf( __( 'By using this plugin, you agree to %s and %s Terms.', 'auto-terms-of-service-and-privacy-policy' ),
		'<a target="_blank" href="http://tourkick.com/terms/?utm_source=terms_agreement_text&utm_medium=free-plugin&utm_term=Auto%20Terms%20plugin&utm_campaign=WP%20Auto%20Terms">TourKick\'s</a>',
		'<a target="_blank" href="https://freemius.com/terms/">Freemius\'</a>'
	);
}


// Freemius: customize the new user message
/* Default text:
Please help us improve Auto Terms of Service and Privacy Policy! If you opt-in, some data about your usage of Auto Terms of Service and Privacy Policy will be sent to freemius.com. If you skip this, that's okay! Auto Terms of Service and Privacy Policy will still work just fine.
*/
function tk_auto_terms_freemius_custom_connect_message(
	$message,
	$user_first_name,
	$plugin_title,
	$user_login,
	$site_link,
	$freemius_link
) {
	$tk_custom_message = sprintf(
			__fs( 'hey-x' ) . '<br><br>' . __( 'The <strong>%2$s</strong> plugin is ready to go! Want to help make %2$s more awesome? Securely share some data to get the best experience and stay informed.', 'auto-terms-of-service-and-privacy-policy' ),
			$user_first_name,
			$plugin_title,
			'<strong>' . $user_login . '</strong>',
			$site_link,
			$freemius_link
	);
	
	$tk_custom_message .= '<br><small>' . tk_auto_terms_freemius_agreement_text() . '</small>';
	
	return $tk_custom_message;
}
tk_auto_terms_freemius()->add_filter( 'connect_message', 'tk_auto_terms_freemius_custom_connect_message', 10, 6 );


// END Freemius


// Add settings link on plugin page from http://bavotasan.com/2009/a-settings-link-for-your-wordpress-plugins/
function atospp_plugin_settings_link($links) {
  $settings_link = '<a href="options-general.php?page=auto-terms-of-service-and-privacy-policy/auto-terms-of-service-privacy-policy.php">Settings</a>';
  array_unshift($links, $settings_link);
  return $links;
}
$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'atospp_plugin_settings_link' );



/* --------------------- START OPTIONS PAGE ------------------ */
class ATOSPP_Options{

	public $options;

	public function __construct(){
		//delete_option('atospp_plugin_options');
		$this->options = get_option('atospp_plugin_options');
		$this->register_settings_and_fields();
	}

	public static function add_menu_page(){
		add_options_page('Auto TOS & PP Options', 'Auto TOS & PP', 'administrator', __FILE__, array('ATOSPP_Options', 'display_options_page'));
	}

	public static function display_options_page(){
		?>

		<div class="wrap">

			<h2>Auto Terms of Service and Privacy Policy Options</h2>

			<form action="options.php" method="post" enctype="multipart/form-data">
				<?php
					if ( function_exists('settings_fields') ) {
						settings_fields('atospp_plugin_options'); // Output nonce, action, and option_page fields for a settings page. This should match the group name used in register_setting()
					}
					do_settings_sections(__FILE__);					
				?>

				<p class="submit">
					<input name="submit" type="submit" class="button-primary" value="Save Changes">
				</p>
			</form>

		</div>

		<?php
	}
	
	public function settings_page_greetbox(){
		$tourkicklogo = plugins_url('images/tourkick-logo-square-300.png', __FILE__);
		$tourkicklogo = str_replace('http:', '', $tourkicklogo); //protocol-relative to make sure it works if wp-admin is HTTPS
		$tourkicklogo = sprintf('<a href="http://wpautoterms.com/" target="_blank"><img style="float: left; margin: 5px 40px 10px 10px;" width="100" height="100" src=\'%s\'></a>', $tourkicklogo);
		$displaytop = '<div style="width: 80%; padding: 20px; margin: 20px; background-color: #fff;">';
		$displaytop .= $tourkicklogo;
		$displaytop .= '<h2><a href="http://b.tourkick.com/atospp-rate-5-stars" target="_blank">Leave a Review</a>
		<br><br>
		<a href="http://b.tourkick.com/atospp-w-org-support-forum" target="_blank">Plugin Support and Feature Requests</a></h2>
		<br>
		<p>In the future, I plan to add additional features, including additional languages, ability to customize text, and to easily add a checkbox to signup forms.</p>
		<p>Some new features may be free and some may be paid, either via a separate premium version or add-ons. Monetizing the plugin should fund further development and further improvement to both free and paid versions.</p>
		<p>Please share your feedback (including feature requests) via one of the WordPress links, above.</p>
		<br>
		Find me online: <a href="http://b.tourkick.com/twitter-follow-tourkick" target="_blank">Twitter</a> | <a href="http://b.tourkick.com/facebook-tourkick" target="_blank">Facebook</a> | <a href="http://b.tourkick.com/cliffpaulick-w-org-profile-plugins" target="_blank">WordPress Profile</a> | <a href="http://bit.ly/tourkick-com" target="_blank">Website</a></p>
		</div>';
		$displaytop .= 'Settings<br><br><hr><span style="font-size: 80%;">Available shortcodes:<ul><li>[my_terms_of_service_and_privacy_policy]</li><li> [my_terms_of_service]</li><li>[my_privacy_policy]</li></ul></span><hr>';
		
		return $displaytop;
	}


	public function register_settings_and_fields(){
		register_setting('atospp_plugin_options', 'atospp_plugin_options'); // https://codex.wordpress.org/Function_Reference/register_setting
		$greetbox = $this->settings_page_greetbox();
		add_settings_section('atospp_section', $greetbox, array($this, 'atospp_section_callback'), __FILE__);

		add_settings_field('atospp_onoff', 'On/Off:<br><small><span style="color:darkred;">Enter all your info below, then Turn On so shortcodes can work.</span><br><span style="color:red;">Will not allow you to Turn On until you enter all required <span style="color:red;">(*)</span> fields.</span></small>', array($this, 'atospp_onoff_setting'), __FILE__, 'atospp_section');
		add_settings_field('atospp_tos_heading', '<span style="color:red;">(*)</span> TOS Heading:<br><small>e.g. Terms of Service, Terms of Use</small>', array($this, 'atospp_tos_heading_setting'), __FILE__, 'atospp_section');
		add_settings_field('atospp_pp_heading', '<span style="color:red;">(*)</span> PP Heading:<br><small>e.g. Privacy Policy</small>', array($this, 'atospp_pp_heading_setting'), __FILE__, 'atospp_section');
		add_settings_field('atospp_namefull', '<span style="color:red;">(*)</span> Full Name:<br><small>e.g. Automattic Inc.</small>', array($this, 'atospp_namefull_setting'), __FILE__, 'atospp_section');
		add_settings_field('atospp_name', '<span style="color:red;">(*)</span> Name:<br><small>e.g. Automattic</small>', array($this, 'atospp_name_setting'), __FILE__, 'atospp_section');
		add_settings_field('atospp_namepossessive', '<span style="color:red;">(*)</span> Possessive Name:<br><small>e.g. Automattic\'s</small>', array($this, 'atospp_namepossessive_setting'), __FILE__, 'atospp_section');
		add_settings_field('atospp_domainname', '<span style="color:red;">(*)</span> Domain Name:<br><small>e.g. Automattic.com</small>', array($this, 'atospp_domainname_setting'), __FILE__, 'atospp_section');
		add_settings_field('atospp_websiteurl', '<span style="color:red;">(*)</span> Official Website URL:<br><small>e.g. http://www.wordpress.com/</small>', array($this, 'atospp_websiteurl_setting'), __FILE__, 'atospp_section');
		add_settings_field('atospp_minage', '<span style="color:red;">(*)</span> Minimum Age:<br><small>e.g. 13</small>', array($this, 'atospp_minage_setting'), __FILE__, 'atospp_section');
		add_settings_field('atospp_time_feesnotifications', '<span style="color:red;">(*)</span> Time Period for changing fees and for notifications:<br><small>e.g. thirty (30) days</small>', array($this, 'atospp_time_feesnotifications_setting'), __FILE__, 'atospp_section');
		add_settings_field('atospp_time_replytopriorityemail', '<span style="color:red;">(*)</span> Time Period for replying to priority email:<br><small>e.g. one business day</small>', array($this, 'atospp_time_replytopriorityemail_setting'), __FILE__, 'atospp_section');
		add_settings_field('atospp_time_determiningmaxdamages', '<span style="color:red;">(*)</span> Time Period for determining maximum damages:<br><small>e.g. twelve (12) month<br><span style="color:darkred;">Notice no "S" on "month"</span></small>', array($this, 'atospp_time_determiningmaxdamages_setting'), __FILE__, 'atospp_section');
		add_settings_field('atospp_dmcanoticeurl', 'DMCA Notice URL:<br><small>e.g. http://automattic.com/dmca-notice/<br><span style="color:darkred;">If left blank, sentence about reporting DMCA violations will be shown but not hyperlinked.</span></small>', array($this, 'atospp_dmcanoticeurl_setting'), __FILE__, 'atospp_section');
		add_settings_field('atospp_venue', '<span style="color:red;">(*)</span> Venue:<br><small>e.g. state of California, U.S.A.</small>', array($this, 'atospp_venue_setting'), __FILE__, 'atospp_section');
		add_settings_field('atospp_courtlocation', '<span style="color:red;">(*)</span> Court Location:<br><small>e.g. San Francisco County, California</small>', array($this, 'atospp_courtlocation_setting'), __FILE__, 'atospp_section');
		add_settings_field('atospp_arbitrationlocation', '<span style="color:red;">(*)</span> Arbitration Location:<br><small>e.g. San Francisco, California</small>', array($this, 'atospp_arbitrationlocation_setting'), __FILE__, 'atospp_section');

	}

	public function atospp_section_callback(){
		//Optional
	}

  /*
      INPUTS
  */

	public function atospp_tos_heading_setting(){
		$tos_heading = "Terms of Service";
		if(!empty($this->options['atospp_tos_heading'])){
			$tos_heading = $this->options['atospp_tos_heading'];
		}
		printf( '<input name="atospp_plugin_options[atospp_tos_heading]" type="text" value="%s">', $tos_heading );
	}

	public function atospp_pp_heading_setting(){
		$pp_heading = "Privacy Policy";
		if(!empty($this->options['atospp_pp_heading'])){
			$pp_heading = $this->options['atospp_pp_heading'];
		}
		printf( '<input name="atospp_plugin_options[atospp_pp_heading]" type="text" value="%s">', $pp_heading );
	}

	public function atospp_namefull_setting(){
		$namefull = '';
		if(!empty($this->options['atospp_namefull'])){
			$namefull = $this->options['atospp_namefull'];
		}
		printf( '<input name="atospp_plugin_options[atospp_namefull]" type="text" value="%s">', $namefull );
	}

	public function atospp_name_setting(){
		$name = '';
		if(!empty($this->options['atospp_name'])){
			$name = $this->options['atospp_name'];
		}
		printf( '<input name="atospp_plugin_options[atospp_name]" type="text" value="%s">', $name );
	}

	public function atospp_namepossessive_setting(){
		$namepossessive = '';
		if(!empty($this->options['atospp_namepossessive'])){
			$namepossessive = $this->options['atospp_namepossessive'];
		}
		printf( '<input name="atospp_plugin_options[atospp_namepossessive]" type="text" value="%s">', $namepossessive );
	}

	public function atospp_domainname_setting(){
		$domainname = '';
		if(!empty($this->options['atospp_domainname'])){
			$domainname = $this->options['atospp_domainname'];
		}
		printf( '<input name="atospp_plugin_options[atospp_domainname]" type="text" value="%s">', $domainname );
	}

	public function atospp_websiteurl_setting(){
		$websiteurl = get_site_url();
		if(!empty($this->options['atospp_websiteurl'])){
			$websiteurl = $this->options['atospp_websiteurl'];
		}
		printf( '<input name="atospp_plugin_options[atospp_websiteurl]" type="text" value="%s">', $websiteurl );
	}

	public function atospp_minage_setting(){
		$minage = "13";
		if(!empty($this->options['atospp_minage'])){
			$minage = $this->options['atospp_minage'];
		}
		printf( '<input name="atospp_plugin_options[atospp_minage]" type="text" value="%s">', $minage );
	}

	public function atospp_time_feesnotifications_setting(){
		$timefeesnotifications = "thirty (30) days";
		if(!empty($this->options['atospp_time_feesnotifications'])){
			$timefeesnotifications = $this->options['atospp_time_feesnotifications'];
		}
		printf( '<input name="atospp_plugin_options[atospp_time_feesnotifications]" type="text" value="%s">', $timefeesnotifications );
	}

	public function atospp_time_replytopriorityemail_setting(){
		$timereplytopriorityemail = "one business day";
		if(!empty($this->options['atospp_time_replytopriorityemail'])){
			$timereplytopriorityemail = $this->options['atospp_time_replytopriorityemail'];
		}
		printf( '<input name="atospp_plugin_options[atospp_time_replytopriorityemail]" type="text" value="%s">', $timereplytopriorityemail );
	}

	public function atospp_time_determiningmaxdamages_setting(){
		$timedeterminingmaxdamages = "twelve (12) month";
		if(!empty($this->options['atospp_time_determiningmaxdamages'])){
			$timedeterminingmaxdamages = $this->options['atospp_time_determiningmaxdamages'];
		}
		printf( '<input name="atospp_plugin_options[atospp_time_determiningmaxdamages]" type="text" value="%s">', $timedeterminingmaxdamages );
	}

	public function atospp_dmcanoticeurl_setting(){
		$dmcanoticeurl = '';
		if(!empty($this->options['atospp_dmcanoticeurl'])){
			$dmcanoticeurl = $this->options['atospp_dmcanoticeurl'];
		}
		printf( '<input name="atospp_plugin_options[atospp_dmcanoticeurl]" type="text" value="%s">', $dmcanoticeurl );
	}

	public function atospp_venue_setting(){
		$venue = '';
		if(!empty($this->options['atospp_venue'])){
			$venue = $this->options['atospp_venue'];
		}
		printf( '<input name="atospp_plugin_options[atospp_venue]" type="text" value="%s">', $venue );
	}

	public function atospp_courtlocation_setting(){
		$courtlocation = '';
		if(!empty($this->options['atospp_courtlocation'])){
			$courtlocation = $this->options['atospp_courtlocation'];
		}
		printf( '<input name="atospp_plugin_options[atospp_courtlocation]" type="text" value="%s">', $courtlocation );
	}

	public function atospp_arbitrationlocation_setting(){
		$arbitrationlocation = '';
		if(!empty($this->options['atospp_arbitrationlocation'])){
			$arbitrationlocation = $this->options['atospp_arbitrationlocation'];
		}
		printf( '<input name="atospp_plugin_options[atospp_arbitrationlocation]" type="text" value="%s">', $arbitrationlocation );
	}



	// last so it can check required fields!
	public function atospp_onoff_setting(){
		$onoff = 'atospp_off';
		if(
			!empty($this->options['atospp_onoff'])
			&& !empty($this->options['atospp_tos_heading'])
			&& !empty($this->options['atospp_pp_heading'])
			&& !empty($this->options['atospp_namefull'])
			&& !empty($this->options['atospp_name'])
			&& !empty($this->options['atospp_namepossessive'])
			&& !empty($this->options['atospp_domainname'])
			&& !empty($this->options['atospp_websiteurl'])
			&& !empty($this->options['atospp_minage'])
			&& !empty($this->options['atospp_time_feesnotifications'])
			&& !empty($this->options['atospp_time_replytopriorityemail'])
			&& !empty($this->options['atospp_time_determiningmaxdamages'])
			&& !empty($this->options['atospp_venue'])
			&& !empty($this->options['atospp_courtlocation'])
			&& !empty($this->options['atospp_arbitrationlocation'])
		){
			$onoff = $this->options['atospp_onoff'];
		}

		$off = '';
		if($onoff == 'atospp_off'){
			$off = "selected='selected'";
		}

		$on = '';
		if($onoff == 'atospp_on'){
			$on = "selected='selected'";
		}

		echo "<select name='atospp_plugin_options[atospp_onoff]'>";
		echo "<option value='atospp_off' $off>Off / Coming Soon</option>";
		echo "<option value='atospp_on' $on>On / Displaying</option>";
		echo "</select>";
	}



}

add_action('admin_menu', 'initOptionsATOSPP');

function initOptionsATOSPP(){
	ATOSPP_Options::add_menu_page();
}

add_action('admin_init', 'initAdminATOSPP');

function initAdminATOSPP(){
	new ATOSPP_Options();
}



/* --------------------- END OPTIONS PAGE ------------------ */




/* --------------------- START SETUP ------------------ */

function atospp_settings_url() {
	$settingspage = admin_url('options-general.php?page=auto-terms-of-service-and-privacy-policy/auto-terms-of-service-privacy-policy.php');
	
	return $settingspage;
}


function atospp_back_to_top() {
	$backtotoptext = '<p><a class="auto-tos-pp-back-to-top" href="#atospp-toc">Back to top</a></p>';
	
	return $backtotoptext;
}


function atospp_separator() {
	$separator = '<div class="auto-tos-pp-separator" style="width: 100%; border-bottom: 1px black solid; margin: 20px 0 20px 0;"></div>';
	
	return $separator;
}


function atospp_plugin_version() { //from http://code.garyjones.co.uk/get-wordpress-plugin-version because get_plugin_data() only works in wp-admin
	if ( ! function_exists( 'get_plugins' ) ) {
		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	}
	$plugin_folder = get_plugins( '/' . plugin_basename( dirname( __FILE__ ) ) );
	$plugin_file = basename( ( __FILE__ ) );
	$plugin_version = $plugin_folder[$plugin_file]['Version'];
	
	return $plugin_version;
}	

function atospp_publish_on_off() {
	$options = get_option('atospp_plugin_options');

	if(
		empty($options['atospp_onoff'])
		|| empty($options['atospp_tos_heading'])
		|| empty($options['atospp_pp_heading'])
		|| empty($options['atospp_namefull'])
		|| empty($options['atospp_name'])
		|| empty($options['atospp_namepossessive'])
		|| empty($options['atospp_domainname'])
		|| empty($options['atospp_websiteurl'])
		|| empty($options['atospp_minage'])
		|| empty($options['atospp_time_feesnotifications'])
		|| empty($options['atospp_time_replytopriorityemail'])
		|| empty($options['atospp_time_determiningmaxdamages'])
		|| empty($options['atospp_venue'])
		|| empty($options['atospp_courtlocation'])
		|| empty($options['atospp_arbitrationlocation'])
	){
		$tcpp_publish = 'atospp_off';
	} else {
		$tcpp_publish = $options['atospp_onoff'];
	}
	
	return $tcpp_publish;
}



function atospp_create_tos() {

	$options = get_option('atospp_plugin_options');

	$tcpp_termsheading = $options['atospp_tos_heading'];
	$tcpp_privacypolicyheading = $options['atospp_pp_heading'];
	$tcpp_biznamefull = $options['atospp_namefull'];
	$tcpp_bizname = $options['atospp_name'];
	$tcpp_biznamepossessive = $options['atospp_namepossessive'];
	$tcpp_domainname = $options['atospp_domainname'];
	$tcpp_websiteurl = $options['atospp_websiteurl'];
	$tcpp_minimumage = $options['atospp_minage'];
	$tcpp_timeperiodforchangingfeesandfornotifications = $options['atospp_time_feesnotifications'];
	$tcpp_timeperiodtoreplytopriorityemail = $options['atospp_time_replytopriorityemail'];
	$tcpp_timeperiodfordeterminingmaximumdamages = $options['atospp_time_determiningmaxdamages'];

	$tcpp_dmcanoticeurl = $options['atospp_dmcanoticeurl'];

	if(!empty($tcpp_dmcanoticeurl)) {
		$tcpp_dmcaoutput = "$tcpp_bizname in accordance with <a href=\"$tcpp_dmcanoticeurl\">$tcpp_biznamepossessive Digital Millennium Copyright Act (&quot;DMCA&quot;) Policy</a>";
	} else {
		$tcpp_dmcaoutput = "$tcpp_bizname in accordance with $tcpp_biznamepossessive Digital Millennium Copyright Act (&quot;DMCA&quot;) Policy";
	}

	$tcpp_venue = $options['atospp_venue'];
	$tcpp_courtlocation = $options['atospp_courtlocation'];
	$tcpp_arbitrationlocation = $options['atospp_arbitrationlocation'];



$tcpp_tcond = '';
$tcpp_tcond .= apply_filters( 'atospp_tos_before_heading', '' );
$tcpp_tcond .= "<h2 id='atospp-terms' class='auto-tos-pp tosheading'>$tcpp_termsheading:</h2>";
$tcpp_tcond .= apply_filters( 'atospp_tos_after_heading', '' );
$tcpp_tcond .= "<p>The following terms and conditions govern all use of the $tcpp_domainname website and all content, services and products available at or through the website (taken together, the Website). The Website is owned and operated by $tcpp_biznamefull (&quot;$tcpp_bizname&quot;). The Website is offered subject to your acceptance without modification of all of the terms and conditions contained herein and all other operating rules, policies (including, without limitation, $tcpp_biznamepossessive $tcpp_privacypolicyheading) and procedures that may be published from time to time on this Site by $tcpp_bizname (collectively, the &quot;Agreement&quot;).</p>
<p>Please read this Agreement carefully before accessing or using the Website. By accessing or using any part of the web site, you agree to become bound by the terms and conditions of this agreement. If you do not agree to all the terms and conditions of this agreement, then you may not access the Website or use any services. If these terms and conditions are considered an offer by $tcpp_bizname, acceptance is expressly limited to these terms. The Website is available only to individuals who are at least $tcpp_minimumage years old.</p>
<ol>
<li><strong>Your $tcpp_domainname Account and Site.</strong> If you create a blog/site on the Website, you are responsible for maintaining the security of your account and blog, and you are fully responsible for all activities that occur under the account and any other actions taken in connection with the blog. You must not describe or assign keywords to your blog in a misleading or unlawful manner, including in a manner intended to trade on the name or reputation of others, and $tcpp_bizname may change or remove any description or keyword that it considers inappropriate or unlawful, or otherwise likely to cause $tcpp_bizname liability. You must immediately notify $tcpp_bizname of any unauthorized uses of your blog, your account or any other breaches of security. $tcpp_bizname will not be liable for any acts or omissions by You, including any damages of any kind incurred as a result of such acts or omissions.</li>
<li><strong>Responsibility of Contributors.</strong> If you operate a blog, comment on a blog, post material to the Website, post links on the Website, or otherwise make (or allow any third party to make) material available by means of the Website (any such material, &quot;Content&quot;), You are entirely responsible for the content of, and any harm resulting from, that Content. That is the case regardless of whether the Content in question constitutes text, graphics, an audio file, or computer software. By making Content available, you represent and warrant that:
  <ul>
<li>the downloading, copying and use of the Content will not infringe the proprietary rights, including but not limited to the copyright, patent, trademark or trade secret rights, of any third party;</li>
<li>if your employer has rights to intellectual property you create, you have either (i) received permission from your employer to post or make available the Content, including but not limited to any software, or (ii) secured from your employer a waiver as to all rights in or to the Content;</li>
<li>you have fully complied with any third-party licenses relating to the Content, and have done all things necessary to successfully pass through to end users any required terms;</li>
<li>the Content does not contain or install any viruses, worms, malware, Trojan horses or other harmful or destructive content;</li>
<li class='important'>the Content is not spam, is not machine- or randomly-generated, and does not contain unethical or unwanted commercial content designed to drive traffic to third party sites or boost the search engine rankings of third party sites, or to further unlawful acts (such as phishing) or mislead recipients as to the source of the material (such as spoofing);</li>
<li>the Content is not pornographic, does not contain threats or incite violence towards individuals or entities, and does not violate the privacy or publicity rights of any third party;</li>
<li>your blog is not getting advertised via unwanted electronic messages such as spam links on newsgroups, email lists, other blogs and web sites, and similar unsolicited promotional methods;</li>
<li>your blog is not named in a manner that misleads your readers into thinking that you are another person or company. For example, your blog's URL or name is not the name of a person other than yourself or company other than your own; and</li>
<li>you have, in the case of Content that includes computer code, accurately categorized and/or described the type, nature, uses and effects of the materials, whether requested to do so by $tcpp_bizname or otherwise.</li>
</ul>
<p>By submitting Content to $tcpp_bizname for inclusion on your Website, you grant $tcpp_bizname a world-wide, royalty-free, and non-exclusive license to reproduce, modify, adapt and publish the Content solely for the purpose of displaying, distributing and promoting your blog. If you delete Content, $tcpp_bizname will use reasonable efforts to remove it from the Website, but you acknowledge that caching or references to the Content may not be made immediately unavailable.</p>
<p>Without limiting any of those representations or warranties, $tcpp_bizname has the right (though not the obligation) to, in $tcpp_biznamepossessive sole discretion (i) refuse or remove any content that, in $tcpp_biznamepossessive reasonable opinion, violates any $tcpp_bizname policy or is in any way harmful or objectionable, or (ii) terminate or deny access to and use of the Website to any individual or entity for any reason, in $tcpp_biznamepossessive sole discretion. $tcpp_bizname will have no obligation to provide a refund of any amounts previously paid.</li>
<li><strong>Payment and Renewal.</strong>
<ul>
<li><strong>General Terms.</strong><br>
  By selecting a product or service, you agree to pay $tcpp_bizname the one-time and/or monthly or annual subscription fees indicated  (additional payment terms may be included in other communications). Subscription payments will be charged on a pre-pay basis on the day you sign up for an Upgrade and will cover the use of that service for a monthly or annual subscription period as indicated. Payments are not refundable.</li>
<li><strong>Automatic Renewal. </strong><br>
Unless you notify $tcpp_bizname before the end of the applicable subscription period that you want to cancel a subscription, your  subscription will automatically renew and you authorize us to collect the then-applicable annual or monthly subscription fee for such subscription (as well as any taxes) using any credit card or other payment mechanism we have on record for you. Upgrades can be canceled at any time by submitting your request to $tcpp_bizname in writing.</li>
</ul>
</li>
<li><strong>Services.</strong>
<ul>
<li><strong>Fees; Payment. </strong>By signing up for a Services account you agree to pay $tcpp_bizname the applicable setup fees and recurring fees. Applicable fees will be invoiced starting from the day your services are established and in advance of using such services. $tcpp_bizname reserves the right to change the payment terms and fees upon $tcpp_timeperiodforchangingfeesandfornotifications prior written notice to you. Services can be canceled by you at anytime on $tcpp_timeperiodforchangingfeesandfornotifications written notice to $tcpp_bizname.</li>
<li><strong>Support.</strong> If your service includes access to priority email support. &quot;Email support&quot; means the ability to make requests for technical support assistance by email at any time (with reasonable efforts by $tcpp_bizname to respond within $tcpp_timeperiodtoreplytopriorityemail) concerning the use of the VIP Services. &quot;Priority&quot; means that support takes priority over support for users of the standard or free $tcpp_domainname services. All support will be provided in accordance with $tcpp_bizname standard services practices, procedures and policies.</li>
</ul>
<li><strong>Responsibility of Website Visitors.</strong> $tcpp_bizname has not reviewed, and cannot review, all of the material, including computer software, posted to the Website, and cannot therefore be responsible for that material's content, use or effects. By operating the Website, $tcpp_bizname does not represent or imply that it endorses the material there posted, or that it believes such material to be accurate, useful or non-harmful. You are responsible for taking precautions as necessary to protect yourself and your computer systems from viruses, worms, Trojan horses, and other harmful or destructive content. The Website may contain content that is offensive, indecent, or otherwise objectionable, as well as content containing technical inaccuracies, typographical mistakes, and other errors. The Website may also contain material that violates the privacy or publicity rights, or infringes the intellectual property and other proprietary rights, of third parties, or the downloading, copying or use of which is subject to additional terms and conditions, stated or unstated. $tcpp_bizname disclaims any responsibility for any harm resulting from the use by visitors of the Website, or from any downloading by those visitors of content there posted.</li>
<li><strong>Content Posted on Other Websites.</strong> We have not reviewed, and cannot review, all of the material, including computer software, made available through the websites and webpages to which $tcpp_domainname links, and that link to $tcpp_domainname. $tcpp_bizname does not have any control over those non-$tcpp_bizname websites and webpages, and is not responsible for their contents or their use. By linking to a non-$tcpp_bizname website or webpage, $tcpp_bizname does not represent or imply that it endorses such website or webpage. You are responsible for taking precautions as necessary to protect yourself and your computer systems from viruses, worms, Trojan horses, and other harmful or destructive content. $tcpp_bizname disclaims any responsibility for any harm resulting from your use of non-$tcpp_bizname websites and webpages.</li>
<li><strong>Copyright Infringement and DMCA Policy.</strong> As $tcpp_bizname asks others to respect its intellectual property rights, it respects the intellectual property rights of others. If you believe that material located on or linked to by $tcpp_domainname violates your copyright, you are encouraged to notify $tcpp_dmcaoutput. $tcpp_bizname will respond to all such notices, including as required or appropriate by removing the infringing material or disabling all links to the infringing material. $tcpp_bizname will terminate a visitor's access to and use of the Website if, under appropriate circumstances, the visitor is determined to be a repeat infringer of the copyrights or other intellectual property rights of $tcpp_bizname or others. In the case of such termination, $tcpp_bizname will have no obligation to provide a refund of any amounts previously paid to $tcpp_bizname.</li>
<li><strong>Intellectual Property.</strong> This Agreement does not transfer from $tcpp_bizname to you any $tcpp_bizname or third party intellectual property, and all right, title and interest in and to such property will remain (as between the parties) solely with $tcpp_bizname. $tcpp_bizname, $tcpp_domainname, the $tcpp_domainname logo, and all other trademarks, service marks, graphics and logos used in connection with $tcpp_domainname, or the Website are trademarks or registered trademarks of $tcpp_bizname or $tcpp_biznamepossessive licensors. Other trademarks, service marks, graphics and logos used in connection with the Website may be the trademarks of other third parties. Your use of the Website grants you no right or license to reproduce or otherwise use any $tcpp_bizname or third-party trademarks.</li>
<li><strong>Advertisements.</strong> $tcpp_bizname reserves the right to display advertisements on your blog unless you have purchased an ad-free account.</li>
<li><strong>Attribution.</strong> $tcpp_bizname reserves the right to display attribution links such as 'Blog at $tcpp_domainname,' theme author, and font attribution in your blog footer or toolbar.</li>
<li><strong>Partner Products.</strong> By activating a partner product (e.g. theme) from one of our partners, you agree to that partner's terms of service. You can opt out of their terms of service at any time by de-activating the partner product.</li>
<li><strong>Domain Names.</strong> If you are registering a domain name, using or transferring a previously registered domain name, you acknowledge and agree that use of the domain name is also subject to the policies of the Internet Corporation for Assigned Names and Numbers (&quot;ICANN&quot;), including their <a href=\"http://www.icann.org/en/registrars/registrant-rights-responsibilities-en.htm\">Registration Rights and Responsibilities</a>.</li>
<li><strong>Changes. </strong>$tcpp_bizname reserves the right, at its sole discretion, to modify or replace any part of this Agreement. It is your responsibility to check this Agreement periodically for changes. Your continued use of or access to the Website following the posting of any changes to this Agreement constitutes acceptance of those changes. $tcpp_bizname may also, in the future, offer new services and/or features through the Website (including, the release of new tools and resources). Such new features and/or services shall be subject to the terms and conditions of this Agreement. <strong><br>
</strong></li>
<li><strong>Termination. </strong>$tcpp_bizname may terminate your access to all or any part of the Website at any time, with or without cause, with or without notice, effective immediately. If you wish to terminate this Agreement or your $tcpp_domainname account (if you have one), you may simply discontinue using the Website. Notwithstanding the foregoing, if you have a paid services account, such account can only be terminated by $tcpp_bizname if you materially breach this Agreement and fail to cure such breach within $tcpp_timeperiodforchangingfeesandfornotifications from $tcpp_biznamepossessive notice to you thereof; provided that, $tcpp_bizname can terminate the Website immediately as part of a general shut down of our service. All provisions of this Agreement which by their nature should survive termination shall survive termination, including, without limitation, ownership provisions, warranty disclaimers, indemnity and limitations of liability. <strong><br>
</strong></li>
<li class='important'><strong>Disclaimer of Warranties.</strong> The Website is provided &quot;as is&quot;. $tcpp_bizname and its suppliers and licensors hereby disclaim all warranties of any kind, express or implied, including, without limitation, the warranties of merchantability, fitness for a particular purpose and non-infringement. Neither $tcpp_bizname nor its suppliers and licensors, makes any warranty that the Website will be error free or that access thereto will be continuous or uninterrupted. You understand that you download from, or otherwise obtain content or services through, the Website at your own discretion and risk.</li>
<li class='important'><strong>Limitation of Liability.</strong> In no event will $tcpp_bizname, or its suppliers or licensors, be liable with respect to any subject matter of this agreement under any contract, negligence, strict liability or other legal or equitable theory for: (i) any special, incidental or consequential damages; (ii) the cost of procurement for substitute products or services; (iii) for interruption of use or loss or corruption of data; or (iv) for any amounts that exceed the fees paid by you to $tcpp_bizname under this agreement during the $tcpp_timeperiodfordeterminingmaximumdamages period prior to the cause of action. $tcpp_bizname shall have no liability for any failure or delay due to matters beyond their reasonable control. The foregoing shall not apply to the extent prohibited by applicable law.</li>
<li><strong>General Representation and Warranty.</strong> You represent and warrant that (i) your use of the Website will be in strict accordance with the $tcpp_bizname $tcpp_privacypolicyheading, with this Agreement and with all applicable laws and regulations (including without limitation any local laws or regulations in your country, state, city, or other governmental area, regarding online conduct and acceptable content, and including all applicable laws regarding the transmission of technical data exported from the United States or the country in which you reside) and (ii) your use of the Website will not infringe or misappropriate the intellectual property rights of any third party.</li>
<li><strong>Indemnification.</strong> You agree to indemnify and hold harmless $tcpp_bizname, its contractors, and its licensors, and their respective directors, officers, employees and agents from and against any and all claims and expenses, including attorneys' fees, arising out of your use of the Website, including but not limited to your violation of this Agreement.</li>
<li><strong>Miscellaneous.</strong> This Agreement constitutes the entire agreement between $tcpp_bizname and you concerning the subject matter hereof, and they may only be modified by a written amendment signed by an authorized executive of $tcpp_bizname, or by the posting by $tcpp_bizname of a revised version. Except to the extent applicable law, if any, provides otherwise, this Agreement, any access to or use of the Website will be governed by the laws of the $tcpp_venue, excluding its conflict of law provisions, and the proper venue for any disputes arising out of or relating to any of the same will be the state and federal courts located in $tcpp_courtlocation. Except for claims for injunctive or equitable relief or claims regarding intellectual property rights (which may be brought in any competent court without the posting of a bond), any dispute arising under this Agreement shall be finally settled in accordance with the Comprehensive Arbitration Rules of the Judicial Arbitration and Mediation Service, Inc. (&quot;JAMS&quot;) by three arbitrators appointed in accordance with such Rules. The arbitration shall take place in $tcpp_arbitrationlocation, in the English language and the arbitral decision may be enforced in any court. The prevailing party in any action or proceeding to enforce this Agreement shall be entitled to costs and attorneys' fees. If any part of this Agreement is held invalid or unenforceable, that part will be construed to reflect the parties' original intent, and the remaining portions will remain in full force and effect. A waiver by either party of any term or condition of this Agreement or any breach thereof, in any one instance, will not waive such term or condition or any subsequent breach thereof. You may assign your rights under this Agreement to any party that consents to, and agrees to be bound by, its terms and conditions; $tcpp_bizname may assign its rights under this Agreement without condition. This Agreement will be binding upon and will inure to the benefit of the parties, their successors and permitted assigns.</li>
</ol>";
$tcpp_tcond .= apply_filters( 'atospp_tos_after_end', '' );


	return $tcpp_tcond;
}



function atospp_create_pp() {

	$options = get_option('atospp_plugin_options');

	$tcpp_termsheading = $options['atospp_tos_heading'];
	$tcpp_privacypolicyheading = $options['atospp_pp_heading'];
	$tcpp_biznamefull = $options['atospp_namefull'];
	$tcpp_bizname = $options['atospp_name'];
	$tcpp_biznamepossessive = $options['atospp_namepossessive'];
	$tcpp_domainname = $options['atospp_domainname'];
	$tcpp_websiteurl = $options['atospp_websiteurl'];
	$tcpp_minimumage = $options['atospp_minage'];
	$tcpp_timeperiodforchangingfeesandfornotifications = $options['atospp_time_feesnotifications'];
	$tcpp_timeperiodtoreplytopriorityemail = $options['atospp_time_replytopriorityemail'];
	$tcpp_timeperiodfordeterminingmaximumdamages = $options['atospp_time_determiningmaxdamages'];
	
	//do not need DMCA options for PP
	
	$tcpp_venue = $options['atospp_venue'];
	$tcpp_courtlocation = $options['atospp_courtlocation'];
	$tcpp_arbitrationlocation = $options['atospp_arbitrationlocation'];




$tcpp_privacypolicy = '';
$tcpp_privacypolicy .= apply_filters( 'atospp_pp_before_heading', '' );
$tcpp_privacypolicy .= "<h2 id='atospp-privacy' class='auto-tos-pp ppheading'>$tcpp_privacypolicyheading:</h2>";
$tcpp_privacypolicy .= apply_filters( 'atospp_pp_after_heading', '' );
$tcpp_privacypolicy .= "<p>$tcpp_biznamefull (&quot;<strong>$tcpp_bizname</strong>&quot;) operates $tcpp_domainname and may operate other websites. It is $tcpp_biznamepossessive policy to respect your privacy regarding any information we may collect while operating our websites.</p>
<h3>Website Visitors</h3>
<p>Like most website operators, $tcpp_bizname collects non-personally-identifying information of the sort that web browsers and servers typically make available, such as the browser type, language preference, referring site, and the date and time of each visitor request. $tcpp_biznamepossessive purpose in collecting non-personally identifying information is to better understand how $tcpp_biznamepossessive visitors use its website. From time to time, $tcpp_bizname may release non-personally-identifying information in the aggregate, e.g., by publishing a report on trends in the usage of its website.</p>
<p>$tcpp_bizname also collects potentially personally-identifying information like Internet Protocol (IP) addresses for logged in users and for users leaving comments on $tcpp_domainname blogs/sites. $tcpp_bizname only discloses logged in user and commenter IP addresses under the same circumstances that it uses and discloses personally-identifying information as described below, except that commenter IP addresses and email addresses are visible and disclosed to the administrators of the blog/site where the comment was left.</p>
<h3>Gathering of Personally-Identifying Information</h3>
<p>Certain visitors to $tcpp_biznamepossessive websites choose to interact with $tcpp_bizname in ways that require $tcpp_bizname to gather personally-identifying information. The amount and type of information that $tcpp_bizname gathers depends on the nature of the interaction. For example, we ask visitors who sign up  at <a href=\"$tcpp_websiteurl\">$tcpp_domainname</a> to provide a username and email address. Those who engage in transactions with $tcpp_bizname are asked to provide additional information, including as necessary the personal and financial information required to process those transactions. In each case, $tcpp_bizname collects such information only insofar as is necessary or appropriate to fulfill the purpose of the visitor's interaction with $tcpp_bizname. $tcpp_bizname does not disclose personally-identifying information other than as described below. And visitors can always refuse to supply personally-identifying information, with the caveat that it may prevent them from engaging in certain website-related activities.</p>
<h3>Aggregated Statistics</h3>
<p>$tcpp_bizname may collect statistics about the behavior of visitors to its websites. $tcpp_bizname may display this information publicly or provide it to others. However, $tcpp_bizname does not disclose personally-identifying information other than as described below.</p>
<h3>Protection of Certain Personally-Identifying Information</h3>
<p>$tcpp_bizname discloses potentially personally-identifying and personally-identifying information only to those of its employees, contractors and affiliated organizations that (i) need to know that information in order to process it on $tcpp_biznamepossessive behalf or to provide services available at $tcpp_biznamepossessive websites, and (ii) that have agreed not to disclose it to others. Some of those employees, contractors and affiliated organizations may be located outside of your home country; by using $tcpp_biznamepossessive websites, you consent to the transfer of such information to them. $tcpp_bizname will not rent or sell potentially personally-identifying and personally-identifying information to anyone. Other than to its employees, contractors and affiliated organizations, as described above, $tcpp_bizname discloses potentially personally-identifying and personally-identifying information only in response to a subpoena, court order or other governmental request, or when $tcpp_bizname believes in good faith that disclosure is reasonably necessary to protect the property or rights of $tcpp_bizname, third parties or the public at large. If you are a registered user of an $tcpp_bizname website and have supplied your email address, $tcpp_bizname may occasionally send you an email to tell you about new features, solicit your feedback, or just keep you up to date with what's going on with $tcpp_bizname and our products.  If you send us a request (for example via email or via one of our feedback mechanisms), we reserve the right to publish it in order to help us clarify or respond to your request or to help us support other users. $tcpp_bizname takes all measures reasonably necessary to protect against the unauthorized access, use, alteration or destruction of potentially personally-identifying and personally-identifying information.</p>
<h3>Cookies</h3>
<p>A cookie is a string of information that a website stores on a visitor's computer, and that the visitor's browser provides to the website each time the visitor returns. $tcpp_bizname uses cookies to help $tcpp_bizname identify and track visitors, their usage of $tcpp_bizname website, and their website access preferences. $tcpp_bizname visitors who do not wish to have cookies placed on their computers should set their browsers to refuse cookies before using $tcpp_biznamepossessive websites, with the drawback that certain features of $tcpp_biznamepossessive websites may not function properly without the aid of cookies.</p>
<h3>Business Transfers</h3>
<p>If $tcpp_bizname, or substantially all of its assets, were acquired, or in the unlikely event that $tcpp_bizname goes out of business or enters bankruptcy, user information would be one of the assets that is transferred or acquired by a third party. You acknowledge that such transfers may occur, and that any acquirer of $tcpp_bizname may continue to use your personal information as set forth in this policy.</p>
<h3>Ads</h3>
<p>Ads appearing on any of our websites may be delivered to users by advertising partners, who may set cookies. These cookies allow the ad server to recognize your computer each time they send you an online advertisement to compile information about you or others who use your computer. This information allows ad networks to, among other things, deliver targeted advertisements that they believe will be of most interest to you. This Privacy Policy covers the use of cookies by $tcpp_bizname and does not cover the use of cookies by any advertisers.</p>
<h3>$tcpp_privacypolicyheading Changes</h3>
<p>Although most changes are likely to be minor, $tcpp_bizname may change its $tcpp_privacypolicyheading from time to time, and in $tcpp_biznamepossessive sole discretion. $tcpp_bizname encourages visitors to frequently check this page for any changes to its $tcpp_privacypolicyheading. If you have a $tcpp_domainname account, you might also receive an alert informing you of these changes. Your continued use of this site after any change in this $tcpp_privacypolicyheading will constitute your acceptance of such change.</p>";
$tcpp_privacypolicy .= apply_filters( 'atospp_pp_after_end', '' );


	return $tcpp_privacypolicy;
}




function atospp_create_tos_pp() {

	$options = get_option('atospp_plugin_options');

	$tcpp_termsheading = $options['atospp_tos_heading'];
	$tcpp_privacypolicyheading = $options['atospp_pp_heading'];
	// do not need the rest of this code block
	
	
	// Add Separators and Back To Top links ONLY when the Table of Contents exists, which only exists when both TOS and PP are displayed together
	// Using priority 9 so default priority of 10 is not needed in child theme functions.php
		add_filter('atospp_tos_before_heading', 'atospp_separator', 9);
		add_filter('atospp_pp_before_heading', 'atospp_separator', 9);
	
		add_filter('atospp_tos_after_end', 'atospp_back_to_top', 9);
		add_filter('atospp_pp_after_end', 'atospp_back_to_top', 9);
	
	
	$tcpp_combinedtermsandprivacy = sprintf('
	<h2 id="atospp-toc" class="auto-tos-pp tospptocheading">Contents:</h2>
	<ol class="auto-tos-pp tospptoc">
		<li><a href="#atospp-terms">%1$s</a></li>
		<li><a href="#atospp-privacy">%2$s</a></li>
	</ol>
	%3$s
	%4$s',
	$tcpp_termsheading,
	$tcpp_privacypolicyheading,
	atospp_create_tos(),
	atospp_create_pp()
	);
	
	
	return $tcpp_combinedtermsandprivacy;

	
}



/* --------------------- END SETUP ------------------ */




/* --------------------- START SHORTCODES ------------------ */



// shortcode [my_terms_of_service_and_privacy_policy]
function my_terms_of_service_and_privacy_policy_func() {
	
	$tcpp_combinedtermsandprivacy = atospp_create_tos_pp();
	
    $a = sprintf('<!-- ATOSPP version: %s, my_terms_of_service_and_privacy_policy -->', atospp_plugin_version() );
    if(!empty($tcpp_combinedtermsandprivacy) && atospp_publish_on_off() == 'atospp_on')
		{ $a .= $tcpp_combinedtermsandprivacy; }
	elseif( current_user_can('edit_plugins') ) {
		$a .= sprintf('<p>Terms and Privacy Policy are coming soon. <a href="%s">Configure this plugin\'s settings.</a></p>', atospp_settings_url() ); }
	else { $a .= '<p>Terms and Privacy Policy are coming soon.</p>'; }

	return $a;
}
add_shortcode('my_terms_of_service_and_privacy_policy', 'my_terms_of_service_and_privacy_policy_func');


// shortcode [my_terms_of_service]
function my_terms_of_service_func() {
	
	$tcpp_tcond = atospp_create_tos();
	
    $b = sprintf('<!-- ATOSPP version: %s, my_terms_of_service -->', atospp_plugin_version() );
    if(!empty($tcpp_tcond) && atospp_publish_on_off() == 'atospp_on')
		{ $b .= $tcpp_tcond; }
	elseif( current_user_can('edit_plugins') ) {
		$b .= sprintf('<p>Terms are coming soon. <a href="%s">Configure this plugin\'s settings.</a></p>', atospp_settings_url() ); }
	  else { $b .= '<p>Terms are coming soon.</p>'; }

	return $b;
}
add_shortcode('my_terms_of_service', 'my_terms_of_service_func');


// shortcode [my_privacy_policy]
function my_privacy_policy_func() {
	
	$tcpp_privacypolicy = atospp_create_pp();
	
    $c = sprintf('<!-- ATOSPP version: %s, my_privacy_policy -->', atospp_plugin_version() );
	if(!empty($tcpp_privacypolicy) && atospp_publish_on_off() == 'atospp_on')
		{ $c .= $tcpp_privacypolicy; }
	elseif( current_user_can('edit_plugins') ) {
		$c .= sprintf('<p>Privacy Policy is coming soon. <a href="%s">Configure this plugin\'s settings.</a></p>', atospp_settings_url() ); }
	else { $c .= '<p>Privacy Policy is coming soon.</p>'; }

	return $c;
}
add_shortcode('my_privacy_policy', 'my_privacy_policy_func');



/* --------------------- END SHORTCODES ------------------ */


// End of plugin
// Do not add closing PHP tag
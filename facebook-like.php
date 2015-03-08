<?php
/*
Plugin Name: Facebook Like Box
Plugin URI: http://xlab.biz
Description: This plugin can  increase facebook likes for your blog. Add this plugin to your website and a facebook like box will pop up with the lightbox effect when a user visits your site. This is a surefire way to dramatically increase your likes.
Version: 1.0
Author: Deepak Sihag
Author URI: http://xlab.biz/
License: GPLv2
*/

define('FBLIKE_PLUGIN_URL', plugin_dir_url(__FILE__));

/****************************** PLUGIN INSTALLATION_FUNCTION STARTS HERE *********************************************/ 

  function fblike_install() {
  
    update_option('fblike_height', '255');
    update_option('fblike_width', '402');
    update_option('fblike_show', 'always');
    update_option('fblike_show_time', '1');
    update_option('fblike_page_url', 'http://facebook.com/YOUR_PAGE_URL');
    update_option('fblike_color_scheme', 'light');
    update_option('fblike_show_faces', 'true');
    update_option('fblike_header', 'false');
    
   
    
    // global $wp_rewrite;
    // $wp_rewrite->flush_rules( false );
    
    
    
  }

  register_activation_hook(__FILE__,'fblike_install');
  
/****************************** PLUGIN INSTALLATION_FUNCTION END HERE *********************************************/ 


/****************************** PLUGIN  UNINSTALL FUNCTION STARTS HERE *********************************************/

function fblike_deactivate() {

      // delete_option( 'fblike_height' );
      // delete_option( 'fblike_width' );
      // delete_option( 'fblike_show' );
      // delete_option( 'fblike_show_time' );
      // delete_option( 'fblike_page_url' );
      // delete_option( 'fblike_color_scheme' );
      // delete_option( 'fblike_show_faces' );
      // delete_option( 'fblike_header' );

    
    
}
register_deactivation_hook( __FILE__, 'fblike_deactivate' );

/****************************** PLUGIN UNINSTALL FUNCTION END HERE ************************************************/ 

/****************************** PLUGIN DELETE DELETE ALL POSTS, POST META, ATTACHMENT CODE STARTS HERE  **********/

function fblike_delete() {
      delete_option( 'fblike_height' );
      delete_option( 'fblike_width' );
      delete_option( 'fblike_show' );
      delete_option( 'fblike_show_time' );
      delete_option( 'fblike_page_url' );
      delete_option( 'fblike_color_scheme' );
      delete_option( 'fblike_show_faces' );
      delete_option( 'fblike_header' );
}

register_uninstall_hook( __FILE__, 'fblike_delete' );

/*****************************   PLUGIN DELETE DELETE ALL POSTS, POST META, ATTACHMENT CODE END HERE  *************************/
  
  
/*****************************   ADD CUSTOME SUBMENU CODE STARTS HERE    ********************************************/

function fblike_setting_menu()  {


  add_menu_page( 'Facebook Like Settings', 'Facebook Like Settings', 'manage_options', 'facebook-like-settings', 'fblike_print_settings_page_content', plugin_dir_url(__FILE__).'/images/icon.png' , 99.54 );
    
}
add_action('admin_menu', 'fblike_setting_menu');
 
/*****************************   ADD CUSTOME SUBMENU CODE END HERE    ********************************************/ 
 
/*****************************   ADD CUSTOME CSS AND JS CODE STARTS HERE    *************************************/


    ##  CSS JS FOR FRONT END ##
function fblike_plugin_scripts() {
  
  global $post;
  wp_enqueue_style('fblike-front', FBLIKE_PLUGIN_URL . 'css/fblike-style.css', array(), '1.0', 'all');
  if(!wp_script_is('jquery')) {
    wp_enqueue_script('fblike-admin-jQuery', '//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js', array(), '1.0', true);
  }
  // wp_enqueue_script('fblike-front', FBLIKE_PLUGIN_URL . 'js/fblike.js', array('jquery'), '1.0', true);
}
    add_action('wp_enqueue_scripts', 'fblike_plugin_scripts');
    ##  CSS JS FOR FRONT END ##

    
    ##  CSS JS FOR BACK END ##
function fblike_plugin_backend_scripts() {

  wp_enqueue_style('fblike-backend', FBLIKE_PLUGIN_URL . 'css/admin.css', array(), '1.0', 'all');
  if(!wp_script_is('jquery')) {
    wp_enqueue_script('fblike-admin-jQuery', '//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js', array(), '1.0', true);
  }
    wp_enqueue_script('fblike-admin', FBLIKE_PLUGIN_URL . 'js/admin.js', array('jquery'), '1.0', true);
}


add_action( 'admin_enqueue_scripts', 'fblike_plugin_backend_scripts' );
    ##  CSS JS FOR BACK END ##

/*****************************   ADD CUSTOME CSS AND JS CODE END HERE    ********************************************/ 



/*****************************   SAVE FBLIKE SETTINGS CODE STARTS HERE  *****************************************/

function fblike_settings_page_action_hook(){
	
	// can proceed only for fblike settings page
  
  
  
	if( !isset($_GET['page']) || $_GET['page'] != 'facebook-like-settings' ) {
		return;
	}
	
	// check if "?fblike_action=" query string is present
	if( !isset($_REQUEST['fblike_action']) ) {
		return;
	}
			
	// check cabability
	if( !current_user_can('manage_options') ) {
		wp_die(__('You do not have permissions to perform this action.', 'facebook-like'));
	}
	
	// sanitize action and run hooked functions
  $action = sanitize_key($_REQUEST['fblike_action']);
	if( $action != '' ) {
		do_action( 'fblike_' . $action, $_REQUEST );
	}
 }

add_action('admin_init', 'fblike_settings_page_action_hook', 5);


function fblike_save_settings_action_handler($args) {

if( !wp_verify_nonce($args['_wpnonce'], basename(__FILE__)) ) {
		wp_die(__('You do not have permissions to perform this action.', 'facebook-like'));
	}
  update_option('fblike_height', $_POST['fblike_height']);
  update_option('fblike_width', $_POST['fblike_width']);
  update_option('fblike_show', $_POST['fblike_show']);
  update_option('fblike_show_time', $_POST['fblike_show_time']);
  update_option('fblike_page_url', $_POST['fblike_page_url']);
  update_option('fblike_color_scheme', $_POST['fblike_color_scheme']);
  update_option('fblike_show_faces', $_POST['fblike_show_faces']);
  update_option('fblike_header', $_POST['fblike_header']);
  
  
  if($_POST['fblike_show'] == "always") {
    $output .= "$.cookie('popup_user_login', 'no', { path: '/', expires: 7 });";
  }
}
add_action('fblike_save_settings', 'fblike_save_settings_action_handler');


/*****************************   SAVE FBLIKE SETTINGS CODE END HERE    *************************************/

/*****************************  FUNCTION TO OUTPUT "SETTINGS" PAGE CONTENT ********************************/

function fblike_print_settings_page_content() {

$fblike_height        = get_option( 'fblike_height', '' );
$fblike_width         = get_option( 'fblike_width', '' );
$fblike_show          = get_option( 'fblike_show', '' );
$fblike_show_time     = get_option( 'fblike_show_time', '' );
$fblike_page_url      = get_option( 'fblike_page_url', '' );
$fblike_color_scheme  = get_option( 'fblike_color_scheme', '' );
$fblike_show_faces    = get_option( 'fblike_show_faces', '' );
$fblike_header        = get_option( 'fblike_header', '' );



?>
 <div id="icon-edit" class="icon32 icon32-posts-fblike_item"><br></div>
        <h2><?php _e('Facebook Like Settings', 'fblike'); ?></h2>
        
        
        <?php settings_errors('fblike_plugin_notice'); ?>

        
        <p>
          <em>On this page you can manage facebook like settings for your website. </em>
        </p>
        <br />
  <div class="fb-container">
    <div  class="fblike_wrap" id="tabs">
		
			<ul>
		       <li><a class="tabs" id="sets"><span>Settings</span></a></li>
		       <li><a class="tabs" id="fblike-detail"><span>Detail</span></a></li>
		   </ul>
        
        
		 	<div id="tab-sets" class="tab_container">  

              <h2>Facebook Like Settings</h2>
          
            <div class="boxed">
                <form id="save_settings" method="post" action="">
                  <input type="hidden" name="fblike_action" value="save_settings">
                  <?php wp_nonce_field( basename(__FILE__) ); ?>
                  <table class="form-table">
                    <tbody>
                      <tr>
                        <th scope="row"><label for="blogname">Height</label></th>
                        <td><input type="text" class="fblike_regular-text" value="<?php echo !empty($fblike_height) ? $fblike_height : '255'; ?>" id="fblike_height" name="fblike_height"></td>
                        <th scope="row"><label for="blogname">Width</label></th>
                        <td><input type="text" class="fblike_regular-text" value="<?php echo !empty($fblike_width) ? $fblike_width : '402'; ?>" id="fblike_width" name="fblike_width"></td>
                      </tr>
                      <?php /* <tr>
                        <th scope="row"><label for="blogname">Width</label></th>
                        <td><input type="text" class="fblike_regular-text" value="<?php echo !empty($fblike_width) ? $fblike_width : '402'; ?>" id="fblike_width" name="fblike_width"></td>
                      </tr> */?>
                      <tr>
                        <th scope="row"><label for="blogname">Show</label></th>
                        <td>
                            <fieldset>
                             <label title="Always"><input type="radio" <?php if($fblike_show == 'always') echo "checked";if(empty($fblike_show)){echo "checked";}?>  value="always" name="fblike_show"> <span>Always</span></label><br />
                             <label title="Once"><input type="radio" <?php if($fblike_show == 'once') echo "checked";?>  value="once" name="fblike_show"> <span>Once</span></label>
                            </fieldset>
                        </td>
                        <th scope="row"><label for="blogname">Time after show popup</label></th>
                        <td><input type="text" class="fblike_regular-text" value="<?php echo !empty($fblike_show_time) ? $fblike_show_time : '1'; ?>" id="fblike_show_time" name="fblike_show_time"><br />
                        <i>1 second = 1000.  10 seconds = 10000. 60 seconds = 60000</i>
                        
                        </td>
                      </tr>
                      <?php /* 
                      <tr>
                        <th scope="row"><label for="blogname">Time after show popup</label></th>
                        <td><input type="text" class="fblike_regular-text" value="<?php echo !empty($fblike_show_time) ? $fblike_show_time : '1'; ?>" id="fblike_show_time" name="fblike_show_time"><br />
                        <i>1 second = 1000.  10 seconds = 10000. 60 seconds = 60000</i>
                        
                        </td>
                        
                      </tr> */ ?>
                      
                      <tr>
                      <th scope="row"><label for="blogname">Color Scheme</label></th>
                        <td>
                            <fieldset>
                             <label title="Light"><input type="radio" <?php if($fblike_color_scheme == 'light') echo "checked";if(empty($fblike_color_scheme)){echo "checked";}?>  value="light" name="fblike_color_scheme"> <span>Light</span></label><br />
                             <label title="Dark"><input type="radio" <?php if($fblike_color_scheme == 'dark') echo "checked";?>  value="dark" name="fblike_color_scheme"> <span>Dark</span></label>
                            </fieldset>
                        </td>
                        
                        <th scope="row"><label for="blogname">Facebook Page URL</label></th>
                        <td><input type="text" class="fblike_regular-text" value="<?php echo !empty($fblike_page_url) ? $fblike_page_url : 'http://facebook.com/YOUR_PAGE_URL'; ?>" id="fblike_page_url" name="fblike_page_url"></td>
                        
                        
                        
                      </tr>
                      
                      <tr>
                      <th scope="row"><label for="blogname">Show Faces</label></th>
                        <td>
                            <fieldset>
                             <label title="Yes"><input type="radio" <?php if($fblike_show_faces == 'true') echo "checked";if(empty($fblike_show_faces)){echo "checked";}?>  value="true" name="fblike_show_faces"> <span>Yes</span></label><br />
                             <label title="No"><input type="radio" <?php if($fblike_show_faces == 'false') echo "checked";?>  value="false" name="fblike_show_faces"> <span>No</span></label>
                            </fieldset>
                        </td>
                        
                        <th scope="row"><label for="blogname">Show Header</label></th>
                        <td>
                            <fieldset>
                             <label title="Yes"><input type="radio" <?php if($fblike_header == 'true') echo "checked";if(empty($fblike_header)){echo "checked";}?>  value="true" name="fblike_header"> <span>Yes</span></label><br />
                             <label title="No"><input type="radio" <?php if($fblike_header == 'false') echo "checked";?>  value="false" name="fblike_header"> <span>No</span></label>
                            </fieldset>
                        </td>
                        
                      </tr>
                      
                    </tbody>
                  </table>
                  <p class="submit"><input type="submit" value="Save Changes" class="button button-primary" id="submit" name="submit"></p>
                </form>
            </div>
        </div>
        
        <div id="tab-fblike-detail" class="tab_container">
            <h2>Detail</h2>
            <div class="boxed">
            </div>       
        </div>
        
        
     </div>   
     <div  class="postbox-container metabox-holder" id="side-container">
              <div class="postbox">
                <h3 style="cursor:default;"><span>Do you like this Plugin?</span></h3>
                <div class="inside">
                  <p>Please consider a donation.</p>
                  <div style="text-align:center">
                              <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                              <input name="cmd" value="_donations" type="hidden">
                              <input name="business" value="deepak@xlab.co.in" type="hidden">
                              <input name="lc" value="US" type="hidden">
                              <input name="item_name" value="Facebook Like Plugin " type="hidden">
                              <input name="no_note" value="0" type="hidden">
                              <input name="currency_code" value="USD" type="hidden">
                              <input name="bn" value="PP-DonationsBF:btn_donateCC_LG.gif:NonHostedGuest" type="hidden">
                              <input src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" name="submit" alt="PayPal - The safer, easier way to pay online!" border="0" type="image">
                              <img alt="" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" height="1" border="0" width="1">
                              </form>
                  </div>
                  <p>If you wish to help then contact <a href="https://twitter.com/deepaksihag">@deepaksihag</a> on Twitter or use that <a href="http://xlab.co.in/get-in-touch/">contact form</a>.</p>
                </div> <!-- .inside -->
              </div> <!-- .postbox -->
        </div>
      </div>
<?php }
/*****************************  FUNCTION TO OUTPUT "SETTINGS" PAGE CONTENT END HERE ********************************/

/*****************************  ADD FACEBOOK DIV VALUE FOR SHOW POPUP ********************************/

function fblike_show_popup() {

global $pagenow;

  if(!is_admin() && ($pagenow != 'wp-login.php')) {
    add_action('wp_footer', 'fblike_custom_popup',100);
  }



}
add_action ('init', 'fblike_show_popup');


function fblike_custom_popup() {
  $fblike_show      = get_option( 'fblike_show', '' );
  $fblike_show_time = get_option( 'fblike_show_time', '' );
  $fblike_page_url  = get_option( 'fblike_page_url', '' );
    
    $output = "";
    if(!empty($fblike_page_url)) {
    
    
    
      $fblike_height    = get_option( 'fblike_height', '' );
      $fblike_width     = get_option( 'fblike_width', '' );
      $fblike_page_url  = get_option( 'fblike_page_url', '' );
      $fblike_color_scheme  = get_option( 'fblike_color_scheme', '' );
      $fblike_show_faces  = get_option( 'fblike_show_faces', '' );
      $fblike_header  = get_option( 'fblike_header', '' );

        
        $output = "";
        
      if(!empty($fblike_page_url)) {
        $output .=  "<div id='fanback' class=".$fblike_color_scheme."><div id='fan-exit'></div><div id='JasperRoberts' class='JasperRoberts'><div id='TheBlogWidgets'></div><div class='remove-bordar'></div>";

        $output .=  "<iframe allowtransparency='true' frameborder='0' scrolling='no' src='//www.facebook.com/plugins/likebox.php? href=".$fblike_page_url."&width=".$fblike_width."&height=".$fblike_height."&colorscheme=".$fblike_color_scheme."&show_faces=".$fblike_show_faces."&show_border=false&stream=false&header=".$fblike_header."' style='border: none; overflow: hidden; margin-top: -19px; width: ".$fblike_width."px; height: ".$fblike_height."px;'></iframe>";
            $output .=  "</div></div>";
      }

      echo $output;
      $output = "";
        
    $output .= "
            <script type='text/javascript'>

          jQuery.cookie = function (key, value, options) {

          if (arguments.length > 1 && String(value) !== '[object Object]') {
          options = jQuery.extend({}, options);
          if (value === null || value === undefined) {
          options.expires = -1;
          }
          if (typeof options.expires === 'number') {
          var days = options.expires, t = options.expires = new Date();
          t.setDate(t.getDate() + days);
          }
          value = String(value);
          return (document.cookie = [
          encodeURIComponent(key), '=',
          options.raw ? value : encodeURIComponent(value),
          options.expires ? '; expires=' + options.expires.toUTCString() : '',
          options.path ? '; path=' + options.path : '',
          options.domain ? '; domain=' + options.domain : '',
          options.secure ? '; secure' : ''
          ].join(''));
          }

          options = value || {};
          var result, decode = options.raw ? function (s) { return s; } : decodeURIComponent;
          return (result = new RegExp('(?:^|; )' + encodeURIComponent(key) + '=([^;]*)').exec(document.cookie)) ? decode(result[1]) : null;
          };

          </script>";

          $output .= "<script type='text/javascript'>
          jQuery(document).ready(function($){";
          
          if($fblike_show == "always") {
            $output .= "$.cookie('popup_user_login', 'no', { path: '/', expires: 7 });";
          }
          $output .= "if($.cookie('popup_user_login') != 'yes'){
          $('#fanback').delay(".$fblike_show_time.").fadeIn('medium');
          $('#TheBlogWidgets, #fan-exit').click(function(){
          $('#fanback').stop().fadeOut('medium');
          });
          }";
          
          
          if($fblike_show == "once") {
            $output .= "$.cookie('popup_user_login', 'yes', { path: '/', expires: 7 });";
          }
          if($fblike_show == "always") {
            $output .= "$.cookie('popup_user_login', 'no', { path: '/', expires: 7 });";
          }
          $output .= "});;
          </script>";
          
          
          
}
echo $output;


}

/*****************************  ADD FACEBOOK DIV VALUE FOR SHOW POPUP END HERE ********************************/

?>
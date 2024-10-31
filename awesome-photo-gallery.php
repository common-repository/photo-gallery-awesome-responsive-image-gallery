<?php 
/*
Plugin Name: Awesome Responsive Photo Gallery
Plugin URI: http://code.realwebcare.com/awesome-responsive-photo-gallery/
Description: Create 100% responsive WordPress photo gallery within 10 seconds. Easy to customize and various views. The photo gallery have lot’s of style. As navigation you can use thumbnails. The most flexible and easy way to get your self hosted photos on your website.
Author: Crazy Coder
Author URI:http://code.realwebcare.com/item/awesome-responsive-photo-gallery/
Version: 1.0.2
*/


// This code enable for widget shortcode support
add_filter('widget_text', 'do_shortcode');

/* Adding Latest jQuery from WordPress */
function wp_photo_gallery_wp_jquery() {
	wp_enqueue_script('jquery');
}
add_action('init', 'wp_photo_gallery_wp_jquery');

/* Adding photo gallery js file*/
function include_gallery_file_js() {

	wp_enqueue_script( 'photo-gallery-jquery', plugins_url( 'js/lightGallery.min.js', __FILE__ ), array('jquery'));	
	wp_enqueue_script( 'photo-gallery-admintab', plugins_url( 'js/admin_tab.js', __FILE__ ), array('jquery'));
	
	
}
add_action('wp_enqueue_scripts', 'include_gallery_file_js');



/* Adding photo gallery main css and custom css file*/
function include_gallery_file_css() {
    
    wp_enqueue_style( 'photo-gallery-demo-css', plugins_url( '/css/lightGallery.css', __FILE__ ));
	wp_enqueue_style( 'photo-gallery-tab-css', plugins_url( '/css/tab.css', __FILE__ ));
    wp_enqueue_style( 'awesome-fontello-css', plugins_url( '/includes/fontello/fontello.css', __FILE__ ));
    
}
add_action('init', 'include_gallery_file_css');

/* Adding necessary scripts and css */
define('PHOTO_GALLERY', WP_PLUGIN_URL . '/' . plugin_basename( dirname(__FILE__) ) . '/' );

function admintab_function() {
	wp_enqueue_script('photo-gallery-admin-tab', PHOTO_GALLERY.'js/admin_tab.js', array('jquery'));
}
add_action('admin_head', 'admintab_function');


/* Active*/
function gallery_tab_active() {?>
    
<script type="text/javascript">
	jQuery(document).ready(function(){
	   jQuery('#tab-container').easytabs();
	});	
</script> 
 
<?php    
}
add_action('wp_footer', 'gallery_tab_active');

// Default options values
$photo_gallery_options = array(	
	'plugin_active_deactive' => 'block',
	'theme' => '#666',
	'margin' => '10px',
	'shadow_color' => '#ddd',
	'shadow' => '0px',
	'radius' => '0px',
	'effect' => 'slide',
	'caption' => 'true',
	'desc' => 'true',
	'size' => 'thumbnail',


);



// remove unnecessary data 
remove_filter('the_content', 'wptexturize');
remove_filter( 'the_content', 'wpautop' );

//add options framework
function add_photo_gallery_options_framework()  
{  
	add_options_page('Photo Gallery Options', 'Photo Gallery Options', 'manage_options', 'photo-gallery-settings','photo_gallery_options_framework');  
}  
add_action('admin_menu', 'add_photo_gallery_options_framework');

// add color picker
function photo_gallery_color_picker_function( $hook_suffix ) {
    // first check that $hook_suffix is appropriate for your admin page
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'my-script-handle', plugins_url('js/color-picker.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
}
add_action( 'admin_enqueue_scripts', 'photo_gallery_color_picker_function' );

if ( is_admin() ) : // Load only if we are viewing an admin page

function photo_gallery_register_settings() {
	// Register settings and call sanitation functions
	register_setting( 'photo_gallery_p_options', 'photo_gallery_options', 'photo_gallery_validate_options' );
}

add_action( 'admin_init', 'photo_gallery_register_settings' );


// Hide or show plugin
$plugin_active_deactive = array(
	'active_plugin_yes' => array(
		'value' => 'none',
		'label' => 'Hide plugin'
	)
);



// Function to generate options page
function photo_gallery_options_framework() {
	global $photo_gallery_options, $plugin_active_deactive, $effect, $caption, $desc, $size;

	if ( ! isset( $_REQUEST['updated'] ) )
		$_REQUEST['updated'] = false; // This checks whether the form has just been submitted. ?>

<div class="wrap admin_panel">
	
	
<h3 style="font-style:italic;color:red"> <a href="http://code.realwebcare.com/item/awesome-responsive-photo-gallery/">See our awesome responsive image gallery wordpress plugin pro version <br><span style="color:red">50% off for this month</span></a></h4>	

<h4 style="font-style:italic;"> For create photo gallery go to Page > Add New. Click on "Add Media" then you can see media uploader. Now click on "Create Gallery" then click "Upload Files" and upload some photos there then click "Create a New Gallery" (below button) then click "Insert Gallery" (below button) then publish. Click "view page" then you can see an awesome image gallery :). If you want to another gallery just input unique id in shortcode like id="id number" ) | [gallery id="1" ids="506,505,502,503"]
[gallery id="2" ids="506,505,502,503"]</h4>

<h4><a href="https://www.youtube.com/watch?v=mEcgXniSwaY">Watch Video tutorial how to install & Configuration</a></h4>
	
	
	<h2>Awesome Photo Gallery</h2>

	<?php if ( false !== $_REQUEST['updated'] ) : ?>
	<div class="updated fade"><p><strong><?php _e( 'Options saved' ); ?></strong></p></div>
	<?php endif; // If the form has just been submitted, this shows the notification ?>

	<form method="post" action="options.php">

	<?php $settings = get_option( 'photo_gallery_options', $photo_gallery_options ); ?>
	
	<?php settings_fields( 'photo_gallery_p_options' );
	/* This function outputs some hidden fields required by the form,
	including a nonce, a unique number used to ensure the form has been submitted from the admin page
	and not somewhere else, very important for security */ ?>


	
<div id="tab-container" class='tab-container rasel_option_panel'>
 <ul class='etabs'>
   <li class='tab'><a href="#basic_settings">Basic Settings</a></li>
   <li class='tab'><a href="#advance_settings">Advance Settings</a></li>
 </ul>
 <div class='panel-container'>
  <div id="basic_settings">
   <h2>Basic Settings</h2>	
	
	
	<table class="form-table margin_top"><!-- Grab a hot cup of coffee, yes we're using tables! -->
	
		<tr>
			<td align="center"><input type="submit" style="font-size:14px;font-style:italic;margin-top:69px" class="button-secondary default_settings_button" name="photo_gallery_options[default_settings]" value="Default settings" /><p style="font-style:italic;font-size:13px" class="font_size">If you want to default settings of plugin just click default settings button.</p></td>
			<td colspan="2"><input type="submit" class="button-primary" value="Save Options" /></td>
		</tr>		

		
		<tr valign="top">
			<th scope="row"><label for="theme">Thumbnail Overlay Color</label></th>
			<td>
				<input id="theme" type="text" class="my-color-field" name="photo_gallery_options[theme]" value="<?php echo isset($settings['theme']) ? stripslashes($settings['theme']) : ''; ?>" /><p class="description">Choose your thumbnail overlay color. Default color is #666. <span>Or choose color from <a href="http://flatuicolors.com/">here<a/></span></p>
			</td>
		</tr>
		
		<tr valign="top">
			<th scope="row"><label for="margin">Thumbnail Margin</label></th>
			<td>
				<input id="margin" type="number" style="width:80px!important;height:30px;padding-left:3px" name="photo_gallery_options[margin]" value="<?php echo isset($settings['margin']) ? stripslashes($settings['margin']) : ''; ?>" /><span style="padding-left:3px"><strong>px</strong></span><p class="description">You can select margin between other thumbnails. Default margin is 10px.</p>
			</td>
		</tr>
	
		
		
		
	</table>
  </div>		

  
  
  <div id="advance_settings">
	
	<table class="form-table margin_top">
		<h2>Advance Settings</h2> 
		
		<tr>
			<td align="center"><input type="submit" style="font-size:14px;font-style:italic;margin-top:69px" class="button-secondary default_settings_button" name="photo_gallery_options[default_settings]" value="Default settings" /><p style="font-style:italic;font-size:13px" class="font_size">If you want to default settings of plugin just click default settings button.</p></td>
			<td colspan="2"><input type="submit" class="button-primary" value="Save Options" /></td>
		</tr>			


		<tr valign="top">
			<th scope="row"><label for="default_role">Here is 2 types effect <br/>1.Slide &nbsp; 2. Fade.</label></th>
				<td>
				
				<?php
				global $photo_gallery_options; $photo_gallery_settings = get_option( 'photo_gallery_options', $photo_gallery_options );
				?>
				<select id="default_role" name="photo_gallery_options[effect]">
				<?php
					// storing drop down value in a array 
					$effect = array ('slide','fade');
					foreach( $effect as $item ):?>
					<option value="<?php echo $item; ?>" <?php if($photo_gallery_settings['effect'] == $item){ echo 'selected="selected"'; } ?>><?php echo $item; ?></option>
				<?php endforeach; ?>	
				</select>
				<p class="description">If you select slide style your navigation will be slide, if you select fade your navigation will be fade.</p>
			</td>
		</tr>



		
		<tr valign="top">
			<th scope="row"><label for="plugin_active_deactive">Hide plugin</label></th>
			<td>
				<?php foreach( $plugin_active_deactive as $activate ) : ?>
				<input type="checkbox" id="<?php echo $activate['value']; ?>" name="photo_gallery_options[plugin_active_deactive]" 
				value="<?php esc_attr_e( $activate['value'] ); ?>" <?php checked( $settings['plugin_active_deactive'], $activate['value'] ); ?> />
				<label for="<?php echo $activate['value']; ?>"><?php echo $activate['label']; ?></label><br />
				<?php endforeach; ?>
			<p class="description">You can Hide or show your plugin. If you select it your plugin will be hide if you deselect it your plugin will be show.</p>
			</td>
		</tr>		
		
	</table>
  </div>
 </div>
</div>
		
	<p class="submit"><input type="submit" class="button-primary" value="Save Options" /></p>			

	</form>

</div>

	<?php
}

function photo_gallery_validate_options( $input ) {
	global $photo_gallery_options, $plugin_active_deactive, $effect, $caption, $desc, $size;

	$settings = get_option( 'photo_gallery_options', $photo_gallery_options );
	
	// We strip all tags from the text field, to avoid vulnerablilties like XSS

	
	$input['theme'] = isset( $input['default_settings'] ) ? '#666' : wp_filter_post_kses( $input['theme'] );
	$input['margin'] = isset( $input['default_settings'] ) ? '#ddd' : wp_filter_post_kses( $input['margin'] );
	$input['shadow_color'] = isset( $input['default_settings'] ) ? '#ddd' : wp_filter_post_kses( $input['shadow_color'] );
	$input['radius'] = isset( $input['default_settings'] ) ? '0px' : wp_filter_post_kses( $input['radius'] );
	$input['shadow'] = isset( $input['default_settings'] ) ? '0px' : wp_filter_post_kses( $input['shadow'] );
	$input['effect'] = isset( $input['default_settings'] ) ? 'slide' : wp_filter_post_kses( $input['effect'] );
	$input['caption'] = isset( $input['default_settings'] ) ? 'true' : wp_filter_post_kses( $input['caption'] );
	$input['desc'] = isset( $input['default_settings'] ) ? 'true' : wp_filter_post_kses( $input['desc'] );
	$input['size'] = isset( $input['default_settings'] ) ? 'thumbnail' : wp_filter_post_kses( $input['size'] );
	$input['plugin_active_deactive'] = isset( $input['default_settings'] ) ? 'block' : wp_filter_post_kses( $input['plugin_active_deactive'] );

	
	// We select the previous value of the field, to restore it in case an invalid entry has been given
	$prev = $settings['layout_only'];
	// We verify if the given value exists in the layouts array
	if ( !array_key_exists( $input['layout_only'], $plugin_active_deactive ) )
		$input['layout_only'] = $prev;
		


	return $input;
}

endif;  // EndIf is_admin()



// Photo Gallery Some CSS
function photo_gallery_css() {?>

<?php global $photo_gallery_options; $photo_gallery_settings = get_option( 'photo_gallery_options', $photo_gallery_options ); ?>

<style type="text/css">

	   
		<!-- Plugin active & deactive -->
		<?php if ( $photo_gallery_settings['plugin_active_deactive'] =='block' ) : ?>
			<?php wp_enqueue_style( 'photo-gallery-plugin-active', plugins_url( 'css/plugin-active.css', __FILE__ ));  ?>
		<?php endif; ?>	
		<!-- Plugin active & deactive -->
		<?php if ( $photo_gallery_settings['plugin_active_deactive'] =='none' ) : ?>
			<?php wp_enqueue_style( 'photo-gallery-plugin-deactive', plugins_url( 'css/plugin-deactive.css', __FILE__ ));  ?>
		<?php endif; ?>	



html body ul.easy_gallery_wp li {margin-left:-<?php echo $photo_gallery_settings['margin']; ?>px}
		html body ul.easy_gallery_wp li {margin-left:<?php echo $photo_gallery_settings['margin']; ?>px;margin-bottom:<?php echo $photo_gallery_settings['margin']; ?>px}

html body ul.easy_gallery_wp li img{border-radius:<?php echo $photo_gallery_settings['radius']; ?>px;box-shadow:<?php echo $photo_gallery_settings['shadow']; ?>px <?php echo $photo_gallery_settings['shadow']; ?>px <?php echo $photo_gallery_settings['shadow']; ?>px <?php echo $photo_gallery_settings['shadow_color']; ?>}
		
#lightGallery-Gallery .thumb_cont .thumb_inner {
    display: none;
    margin-left: -12px;
    max-height: 290px;
    overflow-y: auto;
    padding: 12px;
	
}



#lightGallery-Gallery .thumb_cont .thumb_info {
    background-color: #333;
    display: none;
    padding: 7px 20px;
	
}

#lightGallery-slider .info span {
    display: none;
    line-height: 1;
}


#lightGallery-action a.cLthumb::after {
  bottom: 4px;
  content: "";
  display: block;
  font-family: "Slide-icons";
  font-size: 16px;
  left: 6px;
  position: absolute;
}


</style> 

<?php
}
add_action('wp_head', 'photo_gallery_css');	



// Registering Shortcode
function awesome_gallery_shortcode( $attr ) {
	$post = get_post();

	static $instance = 0;
	$instance++;

	if ( ! empty( $attr['ids'] ) ) {
		if ( empty( $attr['orderby'] ) )
			$attr['orderby'] = 'post__in';
		$attr['include'] = $attr['ids'];
	}

	$output = apply_filters( 'post_gallery', '', $attr );
	if ( $output != '' )
		return $output;

	if ( isset( $attr['orderby'] ) ) {
		$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
		if ( !$attr['orderby'] )
			unset( $attr['orderby'] );
	}

	$html5 = current_theme_supports( 'html5', 'gallery' );
	
	
	global $photo_gallery_options; $photo_gallery_settings = get_option( 'photo_gallery_options', $photo_gallery_options );
	
	extract(shortcode_atts(array(
		'id'         => '',
		'include'    => '',
		'exclude'    => '',
		'link'       => '',
		'theme' =>$photo_gallery_settings['theme'],
		'shadow_color' =>$photo_gallery_settings['shadow_color'],
		'effect' =>$photo_gallery_settings['effect'],
		'caption' =>$photo_gallery_settings['caption'],
		'desc' =>$photo_gallery_settings['desc'],
		'size' =>$photo_gallery_settings['size'],
		
		
		
	), $attr, 'gallery'));

	$id = intval($id);
	if ( 'RAND' == $order )
		$orderby = 'none';

	if ( !empty($include) ) {
		$_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );

		$attachments = array();
		foreach ( $_attachments as $key => $val ) {
			$attachments[$val->ID] = $_attachments[$key];
		}
	} elseif ( !empty($exclude) ) {
		$attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	} else {
		
	}

	if ( empty($attachments) )
		return '';

	if ( is_feed() ) {
		$output = "\n";
		foreach ( $attachments as $att_id => $attachment )
			$output .= wp_get_attachment_link($att_id, $size, true) . "\n";
		return $output;
	}


	$gallery_style = $gallery_div = '';



	$size_class = sanitize_html_class( $size );
	$gallery_div = "
	
	<style type='text/css'>
		
		
		html body ul#lightGallery$id li .overlay_easy{background-color:$theme;}
	</style>
	
    <script type='text/javascript'>
    jQuery(document).ready(function() {
		jQuery('#lightGallery$id').lightGallery({
			desc        : $desc,
			caption     : $caption,
			mode   : '$effect',
			size   : '$size',
			
			
			
		});
    });
    </script>
	
	<ul id='lightGallery$id' class='easy_gallery_wp'>";

	$output = apply_filters( 'gallery_style', $gallery_style . $gallery_div );

	$i = 0;
	foreach ( $attachments as $id => $attachment ) {
		if ( ! empty( $link ) && 'file' === $link )
			$image_output = wp_get_attachment_link( $id, $size, false, false );
		elseif ( ! empty( $link ) && 'none' === $link )
			$image_output = wp_get_attachment_image( $id, $size, false );
		else
			$image_output = wp_get_attachment_link( $id, $size, true, false );

		$image_meta  = wp_get_attachment_metadata( $id );
		
		$easy_gallery_big_image  = wp_get_attachment_image_src( $id, 'large', false);
		$easy_gallery_medium_image  = wp_get_attachment_image_src( $id, 'medium', false);
		
		$easy_gallery_title = $attachment->post_title;
		$easy_gallery_description = $attachment->post_excerpt;
		
		$easy_gallery_caption = $attachment->post_content;

		$orientation = '';
		if ( isset( $image_meta['height'], $image_meta['width'] ) )
			$orientation = ( $image_meta['height'] > $image_meta['width'] ) ? 'portrait' : 'landscape';

			
		if ($easy_gallery_caption) {
		
		$output .= "
			<li data-title='$easy_gallery_title' data-desc='$easy_gallery_description' data-responsive-src='$easy_gallery_caption' data-src='$easy_gallery_caption'>
				<div class='overlay_easy'></div>
				<div class='easy_icon_holder'>
					<span class='icon icon-awesome-play'></span>
				</div>				
				$image_output
			</li>";	
			
		}
		
		else {
		
		$output .= "
			<li data-title='$easy_gallery_title' data-desc='$easy_gallery_description' data-responsive-src='$easy_gallery_medium_image[0]' data-src='$easy_gallery_big_image[0]'>
				<div class='overlay_easy'></div>
				<div class='easy_icon_holder'>
					<span class='icon icon-awesome-plus'></span>
				</div>
				$image_output
			</li>";	
			
		}


	}

	$output .= "
		</ul>\n";

	return $output;
}


add_shortcode('gallery', 'awesome_gallery_shortcode');




?>
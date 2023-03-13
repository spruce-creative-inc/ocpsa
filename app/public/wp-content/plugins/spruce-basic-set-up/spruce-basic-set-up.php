<?php
/*
Plugin Name: Spruce Basic Set Up
Description: Basic WP set up for Spruce Creative websites
Author: Spruce Creative Inc.
Author URI: https://sprucecreative.ca
Version: 1.0.0
*/

// disable direct file access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function register_hooks() {
  add_action('admin_menu', 'remove_posts_menu');
  add_filter('walker_nav_menu_start_el', 'wpse_226884_replace_hash', 999);
  add_filter( 'nav_menu_link_attributes', 'wp_add_aria_haspopup_atts', 10, 3 );

  /*add HTML instructions to featured image box*/
  add_filter( 'admin_post_thumbnail_html', 'add_featured_image_html');
  if (!is_admin()) {
    add_filter('redirect_canonical', 'spruce_block_user_enumeration_attempts', 10, 2);
  }
  /*disable emoji's*/
  remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
  remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
  remove_action( 'wp_print_styles', 'print_emoji_styles' );
  remove_action( 'admin_print_styles', 'print_emoji_styles' );
  remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
  remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
  remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
  add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );

  /*Chang login error message*/
  add_filter('login_errors', 'no_wordpress_errors');

  /*Detect The Visitor’s Browser*/
  add_filter('body_class','browser_body_class');

  /*SHORTCODES*/
  add_shortcode('menu', 'display_menu_shortcode');
  add_shortcode('button', 'custom_button_shortcode');
  add_shortcode('accent_line', 'accent_line_shortcode');

  /*DISABLE COMMENTS*/
  add_action('admin_init', 'df_disable_comments_post_types_support');
  add_filter('comments_open', 'df_disable_comments_status', 20, 2);
  add_filter('pings_open', 'df_disable_comments_status', 20, 2);
  add_filter('comments_array', 'df_disable_comments_hide_existing_comments', 10, 2);

  /*Remove NODES from admin bar*/
  add_action( 'admin_bar_menu', 'remove_some_nodes_from_admin_top_bar_menu', 999 );


  /*ACF SHORTCODE FOR TOOLSET VIEW*/
  add_shortcode( 'wpv-acf-publication-fileupload', 'wpv_display_acf_publication_fileupload' );

  // Custom Scripts
  wp_enqueue_script( 'ar-custom-script', plugin_dir_url( __FILE__ ) . 'dist/index.js', array(), false, true );

}
add_action( 'init', 'register_hooks' );


/*ACF - PUBLICATION File Upload */
function wpv_display_acf_publication_fileupload( $atts ) {
  $atts = shortcode_atts(
    array(
      'field' => '',
    ), $atts );

  $field = sanitize_text_field( $atts['field'] );
  if ( '' === $field ) {
    return;
  }

  if ( function_exists( 'get_field' ) ) {
    $file = get_field( $field );
    $url ='';

    if( is_numeric( $file ) ) {
      $url = $file['url'];

    } elseif( isset( $file )) {
      $url = $file['url'];
    }
  }
  return $url;
}

/*==DISABLE COMMENTS==*/
// First, this will disable support for comments and trackbacks in post types
function df_disable_comments_post_types_support() {
  $post_types = get_post_types();
  foreach ($post_types as $post_type) {
    if(post_type_supports($post_type, 'comments')) {
    remove_post_type_support($post_type, 'comments');
    remove_post_type_support($post_type, 'trackbacks');
    }
  }
}
// Then close any comments open comments on the front-end just in case
function df_disable_comments_status() {
  return false;
}

// Finally, hide any existing comments that are on the site.
function df_disable_comments_hide_existing_comments($comments) {
  $comments = array();
  return $comments;
}

/*==END OF DISABLE COMMENTS==*/

/*Add HTML instructions to featured image box*/
function add_featured_image_html( $html ) {
  if ( 'post' === get_post_type() ) {
    $html .= '<p>Recommended dimension, 400 x 518(px).</p>';
  } elseif ('regional-partner' === get_post_type()) {
    $html .= '<p>Recommended dimension, 200 x 150(px).</p>';
  } elseif ('board-of-directors' === get_post_type()) {
    $html .= '<p>Recommended dimension, 400 x 400(px).</p>';
  }
  return $html;
}


/*Detect The Visitor’s Browser, and add it to body class*/
function browser_body_class($classes) {
  global $is_lynx, $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone;
  if($is_lynx) $classes[] = 'lynx';
  elseif($is_gecko) $classes[] = 'firefox';
  elseif($is_opera) $classes[] = 'opera';
  elseif($is_NS4) $classes[] = 'ns4';
  elseif($is_safari) $classes[] = 'safari';
  elseif($is_chrome) $classes[] = 'chrome';
  elseif($is_IE) $classes[] = 'ie';
  else $classes[] = 'unknown';
  if($is_iphone) $classes[] = 'iphone';
  return $classes;
}

/* Filter function used to remove the tinymce emoji plugin.*/
function disable_emojis_tinymce( $plugins ) {  if ( is_array( $plugins ) ) { return array_diff( $plugins, array( 'wpemoji' ) ); } else { return array(); } }

/*Hide Login Errors*/
function no_wordpress_errors(){ return 'Something is wrong!'; }

/* Block User Enumeration */
function spruce_block_user_enumeration_attempts($redirect, $request) {
  if ( is_admin() ) return;
  $author_by_id = ( isset( $_REQUEST['author'] ) && is_numeric( $_REQUEST['author'] ) );
  if ( $author_by_id ){ wp_safe_redirect( get_home_url(), 301 ); exit;  }
}

/*Use this shortcode to call a navigation menu [menu name="slug-of-your-menu"]*/
function display_menu_shortcode($atts, $content = null) {
  extract(shortcode_atts(array( 'name' => null, ), $atts));
  return "<nav role='navigation'>".wp_nav_menu( array( 'menu' => $name, 'menu_id' => $name, 'menu_class' => 'menu cf', 'container' => '','echo' => false ) )."</nav>";
}

/* Replacing hashtag(#) with javascript:void(0)*/
function wpse_226884_replace_hash($menu_item) {
  if (strpos($menu_item, 'href="#"') !== false) {$menu_item = str_replace('href="#"', 'href="javascript:void(0);"', $menu_item); } return $menu_item;
}

/* ADD ARIA to menu item with children*/
function wp_add_aria_haspopup_atts( $atts, $item, $args ) { if (in_array('menu-item-has-children', $item->classes)) { $atts['aria-haspopup'] = 'true'; $atts['data-toggle'] = 'dropdown'; } return $atts; }

/* HIDE Default 'POSTS' Post Type in the admin sidebar menu */
function remove_posts_menu() {
  remove_menu_page('edit.php');
  remove_menu_page( 'edit-comments.php' );
}
/*Remove NODES FROM admin bar*/
function remove_some_nodes_from_admin_top_bar_menu( $wp_admin_bar ) {
  $wp_admin_bar->remove_menu( 'updates' );
  $wp_admin_bar->remove_menu('comments');
}

require_once plugin_dir_path( __FILE__ ) . 'lib/helpers.php';
require_once plugin_dir_path( __FILE__ ) . 'lib/nav-menu.php';
require_once plugin_dir_path( __FILE__ ) . 'lib/custom-wp-query.php';

?>

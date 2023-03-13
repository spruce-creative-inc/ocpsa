<?
/**
 * spruce functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package spruce
 */

if ( ! function_exists( 'spruce_setup' ) ) :

  /**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function spruce_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on spruce, use a find and replace
		 * to change 'spruce' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'spruce', get_template_directory() . '/languages' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails', array( 'post', 'page' ) );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'primary-menu' => esc_html__( 'Primary Menu', 'spruce' ),
			'footer-menu' => esc_html__( 'Footer Menu', 'spruce' ),
			'social-menu' => esc_html__( 'Social Menu', 'spruce' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		) );

	}

endif;
add_action( 'after_setup_theme', 'spruce_setup' );


function spruce_scripts() {
  // Add CSS
  wp_enqueue_style( 'spruce-style', get_stylesheet_uri() );
  // Add JS
  wp_enqueue_script( 'custom-script-entry', get_template_directory_uri() . '/dist/index.js', array('jquery'), false, true );
}
add_action( 'wp_enqueue_scripts', 'spruce_scripts' );

// Adds classes to nav items for animation
function nav_menu_add_class( $atts, $item, $args ) {
  if ( $args->theme_location == 'primary-menu') {
    $class = 'masthead__nav-item animate out';
    $atts['class'] = $class;
  }
  return $atts;
}
add_filter( 'nav_menu_link_attributes', 'nav_menu_add_class', 10, 3 );

// Adds custom html menu item for Be Boundless
function add_be_boundless_to_menu( $items, $args ) {
  if ( $args->theme_location == 'primary-menu') {
    $items .= '<li class="masthead__menu-item masthead__be-boundless">';
    $items .= '<a class="" href="/be-boundless/">';
    $items .= '<div class="be-boundless__img-wrap">';
    $items .= '<img class="be-boundless__img be-boundless__img--flag" src="/wp-content/themes/ocpsa/images/be-boundless-flag.png" alt="Be Boundless" />';
    $items .= '<img class="be-boundless__img be-boundless__img--rectangle" src="/wp-content/themes/ocpsa/images/be-boundless-rectangle.png" alt="Be Boundless" />';
    $items .= '<span class="srt">Be Boundless</span>';
    $items .= '</div>';
    $items .= '</a>';
    $items .= '</li>';
  }
  return $items;
}
add_filter( 'wp_nav_menu_items', 'add_be_boundless_to_menu', 10, 2 );

function addItemToList($item) {
  $new_item = "<li";
  if ($item['li_class']) {
    $new_item .= ' class="' . $item['li_class'] . '"';
  }
  $new_item .= ">";
  if ($item['href']) {
    $new_item .= "<a href='" . $item['href'] . "'";
    if ($item['a_class']) {
      $new_item .= ' class="' . $item['a_class'] . '"';
    }
    $new_item .= ">";
  }
  $new_item .= $item['label'];
  if ($item['href']) {
    $new_item .= "</a>";
  }
  $new_item .= "</li>";

  return $new_item;
}

// Adds custom html menu items to footer menu
function add_menu_items( $items, $args ) {

  $new_items = '';

  $items_to_add = $args->items_to_add;

  foreach ($items_to_add as $item) {
    if (!$item['after']) {
      $new_items .= addItemToList($item);
    }
  }

  $new_items .= $items;

  foreach ($items_to_add as $item) {
    if ($item['after']) {
      $new_items .= addItemToList($item);
    }
  }

  return $new_items;
}
add_filter( 'wp_nav_menu_items', 'add_menu_items', 10, 2 );

if( function_exists('acf_add_options_page') ) {
  acf_add_options_page(array(
    'page_title' 	=> 'Theme General Settings',
    'menu_title'	=> 'Theme Settings',
    'menu_slug' 	=> 'theme-general-settings',
    'capability'	=> 'edit_posts',
    'redirect'		=> false,
    'position'    => false,
    'icon_url'    => false,
  ));
}
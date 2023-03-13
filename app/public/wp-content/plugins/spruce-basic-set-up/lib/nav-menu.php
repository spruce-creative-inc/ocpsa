<?

// disable direct file access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function add_menu_parent_title( $item_output, $item, $depth, $args ) {
    global $parent_title;
    $parent_title = __( $item->title );
    return $item_output;
}
add_filter( 'walker_nav_menu_start_el', 'add_menu_parent_title', 10, 4);

class Primary_Walker_Nav_Menu extends Walker_Nav_Menu {

  function start_lvl( &$output, $depth = 0, $args = array() ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = str_repeat( $t, $depth );

		// Default class.
		$classes = array( 'masthead__sub-menu' );

		/**
		 * Filters the CSS class(es) applied to a menu list element.
		 *
		 * @since 4.8.0
		 *
		 * @param string[] $classes Array of the CSS classes that are applied to the menu `<ul>` element.
		 * @param stdClass $args    An object of `wp_nav_menu()` arguments.
		 * @param int      $depth   Depth of menu item. Used for padding.
		 */
		$class_names = implode( ' ', apply_filters( 'nav_menu_submenu_css_class', $classes, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . ' masthead__sub-menu--level-' . esc_attr( $depth + 1 ) . '"' : '';

		$output .= '<button class="masthead__sub-menu__toggle" aria-expanded="false">';
    $output .= '<i class="icon">';
    $output .= '<svg class="icon__chevron" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 400"><path d="m259.2,199.4h0c-.34-.4-.68-.8-1.01-1.2l.26-.21-2.99-4.35c-.87-1.43-2.01-3.29-3.22-5.14l-.83-1.27-1.97-2.78c-1.09-1.5-2.21-3.04-3.31-4.72-.57-1.55-1.72-4.13-4.6-5.17-.19-.37-.41-.76-.64-1.19-.71-1.3-1.66-3.01-2.61-4.71-.12-.21-.23-.42-.35-.63-.02-.03-.03-.06-.05-.09l.04-.02-.04.02c-.04-.07-.08-.14-.12-.21-1.71-3.06-3.29-5.84-3.32-5.89-.1-.17-2.44-4.17-4.28-6.68l-2.91-4.01-.22-.22c-.58-.59-.95-1.2-1.45-2.05-.28-.46-.6-1-.98-1.57-.1-.42-.25-.84-.44-1.26l-.23-.46c-3.61-6.36-7.8-12.46-11.78-18.17l-1.81-2.6c-3.35-4.8-6.82-9.77-9.94-14.67-7.74-12.22-16.09-24.77-24.14-36.3-8.52-12.23-17.32-24.16-25.09-34.69l-3.02-4.09-4.13,2.05c-3.09,1.54-4.02,3.68-4.27,5.14l-1.52.91,1.79,3.51c.14.27.27.52.4.77l-.19.09,1.75,3.75c.11.23.22.46.34.68l.62,1.83c.75,2.21,1.43,3.2,2.11,4.09.03.04.07.08.1.13.55.89,1.17,1.52,1.8,1.95.21.51.46,1.12.61,1.54l.29.8-2.48-.35,4.62,7.83-1.54-1.1,10.64,15.98,5.03,9.29.12-.34,2.47,4.77-.09.57-.4-.11.31.67-.16,1.02.84.41,2.35,4.98.22,2.05c-.29,1.31-.17,3.12,1.44,5.1l.32.4.03.07c.39.83.68,1.36.9,1.72.17.45.35.85.5,1.18.4.89.95,2.07,1.5,3.26.46.99.93,1.99,1.31,2.8l-1.09,2.16,1.33,2.04.4.6c.45.61.99,1.12,1.6,1.5.45.75.85,1.32,1.26,1.79.41.6.78,1.08,1.14,1.51l1.96,2.76c.02.26.05.54.1.82l-2.47.71,3.39,2.22.84.91c.57.59,1.27,1.23,2.12,1.74l.77,1.4c-.35,1.9.29,3.63.58,4.42l.27.75.47.63c.48.65,1.02,1.18,1.64,1.6l.43,1.01,3.03.75c.15.15.31.29.47.42l1.24,1.03c1.64,2.25,3.21,3.12,4.68,3.66.14.2.38.57.72,1.2l1.84,3.38,2.83-1.21c.5.72,1.06,1.45,1.62,2.19.95,1.25,2.02,2.64,2.72,3.88.01.08.03.16.05.24l.77,4.36,1.09.92c.05.06.11.13.16.19.37.44.88,1.05,1.52,1.62l-.21,2.36,3.64,1.34c1.03.84,3.29,3.86,4,5.29-.05.21-.1.45-.14.72-1.4.85-2.39,2.28-2.79,4.12l-.55,2.51,1.57,2.03c.11.14.22.27.33.4l.02,1.09,2.1,1.52c.16.15.36.37.57.58.62.65,1.3,1.35,2.1,1.94.36.67.74,1.28,1.1,1.85.3.48.75,1.2.82,1.4l.58,2.78,2.09.38c.15,1.82.27,3.45.32,4.25-.12.63-.16,1.3-.14,1.91-.49.71-1.06,1.44-1.28,1.63-.56.41-1.25.93-2.06,1.61l-.72.61c-.43.11-.86.26-1.29.46l-3.6,1.64.04.36c-1.5.64-2.52,1.75-3.23,2.91l-2.9-1.47-1.33,7.2c-.5,2.67.18,4.44,1.05,5.57-.14.27-.27.53-.39.78l-7.6,15.83-.39-.13-1.54,3.54c-1.01,2.32-1.27,3.23-1.54,4.27-.12.48-.24.93-.84,2.41l-1.54,3.81.6.26c-.79.78-1.54,1.86-2.15,3.41l-.81,2.07-1.38.9c-1.71,1.12-3.24,2.76-4.31,4.62l-.34.7c-1.02,2.56-1.83,4.97-2.63,7.34-.37,1.11-.74,2.19-1.11,3.27l-1.37,1.58c-1.06.29-2.06.9-2.91,1.8l-1.68,1.79-.3-.53-1.42,2.35-.28.3.06.06-3.23,5.35c-1.04,1.72-1.39,2.6-1.8,3.93-.11.38-.24.81-.52,1.51l-.77,2c-.46.89-.7,1.44-.92,1.92-.25.57-.47,1.06-1.3,2.55l-.72,1.29v.47s-2.22-.02-2.22-.02l2.15,3.4c-.01.31,0,.61.04.89-.19.65-.33,1.37-.34,2.16l-1.8,3.74-.03.03c-.91.94-1.41,1.48-1.76,2l-1.13,1.57c-.5.68-2.39,3.38-5.21,7.42-1.82,2.6-3.69,5.29-4.19,5.98-.64.88-3.48,5.42-4.69,8.16l-2.37,3.16.55.37c-.56,1.01-1.13,2.03-1.51,2.71-.88,1.59-1.33,2.39-1.52,2.74-1.61,2.75-1.01,5.08-.51,6.22.48,1.09,1.5,2.59,3.66,3.49l1.37.45.33.09c.21.05.41.09.6.12l.66.13,4.92-.63,1.06-1.74c.09-.16.18-.31.26-.45h2.27l.14-4.07,1.39-3.19c.61-1.01,1.17-1.86,1.57-2.37l.7-.77c.68-.71.96-1.45,1.61-3.31l.15-.27c.47-.65,1.19-1.63,1.9-2.58l7.08-7.86.31-.69c.29-.51,1.23-1.8,1.76-2.4l.79-.38.83-1.21c.79-1.15,1.3-2.11,1.68-2.96l.24-.22c.75-.69,1.33-1.51,1.94-2.38.05-.07.09-.13.13-.19l.76-.12,1.32-2.22c1.19-2.01,1.7-3.57,1.85-4.99.27-.37.48-.73.65-1.09l.85-.15.96-3.31c.11-.37.28-.97.38-1.7l.67-.85,1.39-.11-.36-1.18.11-.14c2.06-2.61,3.58-4.83,4.61-6.76l7.8-7.22,6.36-8.68.27-.98c.11-.42.19-.82.22-1.2,1.57-1.8,2.97-3.83,4.35-5.84.77-1.11,1.56-2.27,2.32-3.28l8.37-13.16.05-.08c.17-.28,4.11-6.83,5.58-9.47,1.45-2.59,2.94-5.27,4.55-7.8l.13-.21.21-.02,1.05-1.91,3.13-4.96-.33-.05.55-.82c1.03-1.54,1.67-2.9,2.12-4.03l.61-.45.59-1.28c.98-2.12,2.03-4.52,2.98-6.7.25-.56.48-1.11.71-1.64l.89-.16.85-1.5.03.02,2.76-4.61c.32-.56.64-1.13.94-1.69.52-.97,1.05-2,1.67-3.25l.78-1.04c1.69-2.24,3.07-4.72,4.24-6.86l1.79-3.3-2.58-3.05Zm-53.19,47.73l-.13.34-.29-.1.42-.24Z"/></svg>';
    $output .= '<span class="icon__label srt">Expand child menu</span>';
    $output .= '</i>';
    $output .= '</button></div>';
		$output .= "{$n}{$indent}<ul$class_names role='menu' aria-expanded='false'>{$n}";
		
    global $parent_title;
    $output .= "<li class='masthead__menu-item masthead__sub-menu__title'>";
    $output .= '<button class="masthead__sub-menu__back-btn" aria-label="Close ' . $parent_title . ' sub menu">';
    $output .= '<i class="icon">';
    $output .= '<svg class="icon__chevron" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 400"><path d="m140.8,200.6h0c.34.4.68.8,1.01,1.2l-.26.21,2.99,4.35c.87,1.43,2.01,3.29,3.22,5.14l.83,1.27,1.97,2.78c1.09,1.5,2.21,3.04,3.31,4.72.57,1.55,1.72,4.13,4.6,5.17.19.37.41.76.64,1.19.71,1.3,1.66,3.01,2.61,4.71.12.21.23.42.35.63.02.03.03.06.05.09l-.04.02.04-.02c.04.07.08.14.12.21,1.71,3.06,3.29,5.84,3.32,5.89.1.17,2.44,4.17,4.28,6.68l2.91,4.01.22.22c.58.59.95,1.2,1.45,2.05.28.46.6,1,.98,1.57.1.42.25.84.44,1.26l.23.46c3.61,6.36,7.8,12.46,11.78,18.17l1.81,2.6c3.35,4.8,6.82,9.77,9.94,14.67,7.74,12.22,16.09,24.77,24.14,36.3,8.52,12.23,17.32,24.16,25.09,34.69l3.02,4.09,4.13-2.05c3.09-1.54,4.02-3.68,4.27-5.14l1.52-.91-1.79-3.51c-.14-.27-.27-.52-.4-.77l.19-.09-1.75-3.75c-.11-.23-.22-.46-.34-.68l-.62-1.83c-.75-2.21-1.43-3.2-2.11-4.09-.03-.04-.07-.08-.1-.13-.55-.89-1.17-1.52-1.8-1.95-.21-.51-.46-1.12-.61-1.54l-.29-.8,2.48.35-4.62-7.83,1.54,1.1-10.64-15.98-5.03-9.29-.12.34-2.47-4.77.09-.57.4.11-.31-.67.16-1.02-.84-.41-2.35-4.98-.22-2.05c.29-1.31.17-3.12-1.44-5.1l-.32-.4-.03-.07c-.39-.83-.68-1.36-.9-1.72-.17-.45-.35-.85-.5-1.18-.4-.89-.95-2.07-1.5-3.26-.46-.99-.93-1.99-1.31-2.8l1.09-2.16-1.33-2.04-.4-.6c-.45-.61-.99-1.12-1.6-1.5-.45-.75-.85-1.32-1.26-1.79-.41-.6-.78-1.08-1.14-1.51l-1.96-2.76c-.02-.26-.05-.54-.1-.82l2.47-.71-3.39-2.22-.84-.91c-.57-.59-1.27-1.23-2.12-1.74l-.77-1.4c.35-1.9-.29-3.63-.58-4.42l-.27-.75-.47-.63c-.48-.65-1.02-1.18-1.64-1.6l-.43-1.01-3.03-.75c-.15-.15-.31-.29-.47-.42l-1.24-1.03c-1.64-2.25-3.21-3.12-4.68-3.66-.14-.2-.38-.57-.72-1.2l-1.84-3.38-2.83,1.21c-.5-.72-1.06-1.45-1.62-2.19-.95-1.25-2.02-2.64-2.72-3.88-.01-.08-.03-.16-.05-.24l-.77-4.36-1.09-.92c-.05-.06-.11-.13-.16-.19-.37-.44-.88-1.05-1.52-1.62l.21-2.36-3.64-1.34c-1.03-.84-3.29-3.86-4-5.29.05-.21.1-.45.14-.72,1.4-.85,2.39-2.28,2.79-4.12l.55-2.51-1.57-2.03c-.11-.14-.22-.27-.33-.4l-.02-1.09-2.1-1.52c-.16-.15-.36-.37-.57-.58-.62-.65-1.3-1.35-2.1-1.94-.36-.67-.74-1.28-1.1-1.85-.3-.48-.75-1.2-.82-1.4l-.58-2.78-2.09-.38c-.15-1.82-.27-3.45-.32-4.25.12-.63.16-1.3.14-1.91.49-.71,1.06-1.44,1.28-1.63.56-.41,1.25-.93,2.06-1.61l.72-.61c.43-.11.86-.26,1.29-.46l3.6-1.64-.04-.36c1.5-.64,2.52-1.75,3.23-2.91l2.9,1.47,1.33-7.2c.5-2.67-.18-4.44-1.05-5.57.14-.27.27-.53.39-.78l7.6-15.83.39.13,1.54-3.54c1.01-2.32,1.27-3.23,1.54-4.27.12-.48.24-.93.84-2.41l1.54-3.81-.6-.26c.79-.78,1.54-1.86,2.15-3.41l.81-2.07,1.38-.9c1.71-1.12,3.24-2.76,4.31-4.62l.34-.7c1.02-2.56,1.83-4.97,2.63-7.34.37-1.11.74-2.19,1.11-3.27l1.37-1.58c1.06-.29,2.06-.9,2.91-1.8l1.68-1.79.3.53,1.42-2.35.28-.3-.06-.06,3.23-5.35c1.04-1.72,1.39-2.6,1.8-3.93.11-.38.24-.81.52-1.51l.77-2c.46-.89.7-1.44.92-1.92.25-.57.47-1.06,1.3-2.55l.72-1.29v-.47s2.22.02,2.22.02l-2.15-3.4c.01-.31,0-.61-.04-.89.19-.65.33-1.37.34-2.16l1.8-3.74.03-.03c.91-.94,1.41-1.48,1.76-2l1.13-1.57c.5-.68,2.39-3.38,5.21-7.42,1.82-2.6,3.69-5.29,4.19-5.98.64-.88,3.48-5.42,4.69-8.16l2.37-3.16-.55-.37c.56-1.01,1.13-2.03,1.51-2.71.88-1.59,1.33-2.39,1.52-2.74,1.61-2.75,1.01-5.08.51-6.22-.48-1.09-1.5-2.59-3.66-3.49l-1.37-.45-.33-.09c-.21-.05-.41-.09-.6-.12l-.66-.13-4.92.63-1.06,1.74c-.09.16-.18.31-.26.45h-2.27l-.14,4.07-1.39,3.19c-.61,1.01-1.17,1.86-1.57,2.37l-.7.77c-.68.71-.96,1.45-1.61,3.31l-.15.27c-.47.65-1.19,1.63-1.9,2.58l-7.08,7.86-.31.69c-.29.51-1.23,1.8-1.76,2.4l-.79.38-.83,1.21c-.79,1.15-1.3,2.11-1.68,2.96l-.24.22c-.75.69-1.33,1.51-1.94,2.38-.05.07-.09.13-.13.19l-.76.12-1.32,2.22c-1.19,2.01-1.7,3.57-1.85,4.99-.27.37-.48.73-.65,1.09l-.85.15-.96,3.31c-.11.37-.28.97-.38,1.7l-.67.85-1.39.11.36,1.18-.11.14c-2.06,2.61-3.58,4.83-4.61,6.76l-7.8,7.22-6.36,8.68-.27.98c-.11.42-.19.82-.22,1.2-1.57,1.8-2.97,3.83-4.35,5.84-.77,1.11-1.56,2.27-2.32,3.28l-8.37,13.16-.05.08c-.17.28-4.11,6.83-5.58,9.47-1.45,2.59-2.94,5.27-4.55,7.8l-.13.21-.21.02-1.05,1.91-3.13,4.96.33.05-.55.82c-1.03,1.54-1.67,2.9-2.12,4.03l-.61.45-.59,1.28c-.98,2.12-2.03,4.52-2.98,6.7-.25.56-.48,1.11-.71,1.64l-.89.16-.85,1.5-.03-.02-2.76,4.61c-.32.56-.64,1.13-.94,1.69-.52.97-1.05,2-1.67,3.25l-.78,1.04c-1.69,2.24-3.07,4.72-4.24,6.86l-1.79,3.3,2.58,3.05Zm53.19-47.73l.13-.34.29.1-.42.24Z"/></svg>';
    $output .= '</i>';
    $output .= $parent_title;
    $output .= "</button>";
    $output .= "</li>";
	}

  function start_el( &$output, $data_object, $depth = 0, $args = null, $current_object_id = 0 ) {
		// Restores the more descriptive, specific name for use within this method.
		$menu_item = $data_object;

		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = ( $depth ) ? str_repeat( $t, $depth ) : '';

		$classes   = empty( $menu_item->classes ) ? array() : (array) $menu_item->classes;
		$classes[] = 'masthead__menu-item';
		$classes[] = 'menu-item-' . $menu_item->ID;

		/**
		 * Filters the arguments for a single nav menu item.
		 *
		 * @since 4.4.0
		 *
		 * @param stdClass $args      An object of wp_nav_menu() arguments.
		 * @param WP_Post  $menu_item Menu item data object.
		 * @param int      $depth     Depth of menu item. Used for padding.
		 */
		$args = apply_filters( 'nav_menu_item_args', $args, $menu_item, $depth );

		/**
		 * Filters the CSS classes applied to a menu item's list item element.
		 *
		 * @since 3.0.0
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param string[] $classes   Array of the CSS classes that are applied to the menu item's `<li>` element.
		 * @param WP_Post  $menu_item The current menu item object.
		 * @param stdClass $args      An object of wp_nav_menu() arguments.
		 * @param int      $depth     Depth of menu item. Used for padding.
		 */
		$class_names = implode( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $menu_item, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		/**
		 * Filters the ID attribute applied to a menu item's list item element.
		 *
		 * @since 3.0.1
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param string   $menu_item_id The ID attribute applied to the menu item's `<li>` element.
		 * @param WP_Post  $menu_item    The current menu item.
		 * @param stdClass $args         An object of wp_nav_menu() arguments.
		 * @param int      $depth        Depth of menu item. Used for padding.
		 */
		$id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $menu_item->ID, $menu_item, $args, $depth );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		$output .= $indent . '<li' . $id . $class_names . '>';

		$atts           = array();
		$atts['title']  = ! empty( $menu_item->attr_title ) ? $menu_item->attr_title : '';
		$atts['target'] = ! empty( $menu_item->target ) ? $menu_item->target : '';
		if ( '_blank' === $menu_item->target && empty( $menu_item->xfn ) ) {
			$atts['rel'] = 'noopener';
		} else {
			$atts['rel'] = $menu_item->xfn;
		}
		$atts['href']         = ! empty( $menu_item->url ) ? $menu_item->url : '';
		$atts['aria-current'] = $menu_item->current ? 'page' : '';

		/**
		 * Filters the HTML attributes applied to a menu item's anchor element.
		 *
		 * @since 3.6.0
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param array $atts {
		 *     The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
		 *
		 *     @type string $title        Title attribute.
		 *     @type string $target       Target attribute.
		 *     @type string $rel          The rel attribute.
		 *     @type string $href         The href attribute.
		 *     @type string $aria-current The aria-current attribute.
		 * }
		 * @param WP_Post  $menu_item The current menu item object.
		 * @param stdClass $args      An object of wp_nav_menu() arguments.
		 * @param int      $depth     Depth of menu item. Used for padding.
		 */
		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $menu_item, $args, $depth );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( is_scalar( $value ) && '' !== $value && false !== $value ) {
				$value       = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		/** This filter is documented in wp-includes/post-template.php */
		$title = apply_filters( 'the_title', $menu_item->title, $menu_item->ID );

		/**
		 * Filters a menu item's title.
		 *
		 * @since 4.4.0
		 *
		 * @param string   $title     The menu item's title.
		 * @param WP_Post  $menu_item The current menu item object.
		 * @param stdClass $args      An object of wp_nav_menu() arguments.
		 * @param int      $depth     Depth of menu item. Used for padding.
		 */
		$title = apply_filters( 'nav_menu_item_title', $title, $menu_item, $args, $depth );

		$item_output  = $args->before;

    if (in_array('menu-item-has-children', $classes)) {
      $item_output .= '<div class="masthead__drop-wrap">';
    }

		$item_output .= '<a' . $attributes . ' role="menuitem">';
		$item_output .= $args->link_before . $title . $args->link_after;
		$item_output .= '</a>';
		$item_output .= $args->after;

		/**
		 * Filters a menu item's starting output.
		 *
		 * The menu item's starting output only includes `$args->before`, the opening `<a>`,
		 * the menu item's title, the closing `</a>`, and `$args->after`. Currently, there is
		 * no filter for modifying the opening and closing `<li>` for a menu item.
		 *
		 * @since 3.0.0
		 *
		 * @param string   $item_output The menu item's starting HTML output.
		 * @param WP_Post  $menu_item   Menu item data object.
		 * @param int      $depth       Depth of menu item. Used for padding.
		 * @param stdClass $args        An object of wp_nav_menu() arguments.
		 */
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $menu_item, $depth, $args );
	}

}
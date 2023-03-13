<? 

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function include_post_custom_fields( $posts ) {

    for ( $i = 0; $i < count($posts); $i++ ) {

        $custom_fields = get_post_custom( $posts[$i]->ID );
        $posts[$i]->custom_fields = $custom_fields;

    }

    return $posts;

}

add_filter( 'the_posts', 'include_post_custom_fields' );
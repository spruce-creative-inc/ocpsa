<?

/**
 * The template for displaying all pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package spruce
*/

get_header();

?>

<div id="primary" class="content-area">
  <main id="main" class="site-main">

    <?

			while ( have_posts() ) : the_post();

				get_template_part(
          'template-parts/banners/banner',
          get_field('banner_type')
        );

				get_template_part(
          'template-parts/content/content',
          'sections'
        );

			endwhile; // End of the loop.

    ?>


  </main><!-- #main -->
</div><!-- #primary -->

<? get_footer(); ?>
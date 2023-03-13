<?

/**
 * The template for displaying News Posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package spruce
*/

get_header();

?>

<div id="primary" class="content-area">
  <main id="main" class="site-main news-post">

    <?

			while ( have_posts() ) : the_post();

				get_template_part(
          'template-parts/content/content',
          'single'
        );
			
      endwhile; // End of the loop.

    ?>

  </main><!-- #main -->
</div><!-- #primary -->

<? get_footer(); ?>
<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package spruce
 */

get_header();

?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main search-page">

      <header
        class="pad-t-3"
      >
        <div class="container">
          <h1>
            <span>Search Results for:</span>
            <? echo get_search_query(); ?>
          </h1>
        </div>
      </header>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
	get_footer();
?>

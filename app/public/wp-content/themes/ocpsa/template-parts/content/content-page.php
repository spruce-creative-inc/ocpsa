<?
/**
 * Template part for displaying page content in page.php
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package spruce
 */
?>

<article id="post-<? the_ID(); ?>" <? post_class(); ?>>

	<section class="basic-content">
		<div class="container">
      <div class="contained-narrow-left">
        <? the_content(); ?>
      </div>
		</div><!-- .entry-content -->
	</section>

</article><!-- #post-<? the_ID(); ?> -->

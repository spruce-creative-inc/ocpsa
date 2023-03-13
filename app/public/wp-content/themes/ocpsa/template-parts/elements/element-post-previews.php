<?

$found_posts = new WP_Query([
  'post_type'         => $args['post_type'],
  'posts_per_page'    => $args['posts_per_page'],
]);

if ($found_posts->have_posts()) :
  $i = 0;
  while ($found_posts->have_posts()) :
    $found_posts->the_post();

    get_template_part(
      'template-parts/post-previews/pp',
      'basic',
      [
        'post_type' => $args['post_type'],
        'posts_per_page' => $args['posts_per_page'],
        'index' => $i
      ]
    );
    
    $i++;
  endwhile;
endif;
wp_reset_postdata();

?>
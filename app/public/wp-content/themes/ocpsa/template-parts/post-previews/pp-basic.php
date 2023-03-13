<?

$post_link = get_post_permalink();
$post_title = get_the_title();
$post_excerpt = get_the_excerpt();
$post_thumbnail_id = get_post_thumbnail_id();

if ($post_thumbnail_id) {
  $post_image_src = wp_get_attachment_image_src($post_thumbnail_id, 'full')[0];
  $post_image_alt = get_post_meta($post_thumbnail_id, '_wp_attachment_image_alt', TRUE);
}

$id = sanitize_title($post_title);
$classes .= 'post-preview post-preview--' . $args['post_type'];

$post_col_span = 4;

if (
  $args['posts_per_page'] == 1
  || $args['posts_per_page'] == 2
) {
  $post_col_span = 6;
}

?>

<a
  id="<? echo $id ?>"
  class="<? echo $classes ?>"
  href="<? echo $post_link ?>"
  style="--col-span-large: <? echo $post_col_span ?>"
>
  <div class="post-preview__image">
    <div class="embed embed-1by1 image-mask">
      <img src="<? echo $post_image_src ?>" alt="<? echo $post_image_alt ?>">
    </div>
  </div>
  <h3 class="post-preview__title"><? echo $post_title ?></h3>
  <p class="post-preview__excerpt"><? echo $post_excerpt ?></p>
  <div class="post-preview__more">
    <? get_template_part(
      'template-parts/icons/icon',
      'chevron-right',
      [
        'label' => 'Read More',
        'label_first' => true,
        'label_visible' => true,
        'size' => '1em'
      ]
    ); ?>
  </div>
</a>
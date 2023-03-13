<?

include(TEMPLATEPATH . '/template-parts/sections/section-settings.php');

$post_type = get_sub_field('post_type');
$post_count = get_sub_field('post_count');

?>

<section
  id="<? echo $id ?>"
  class="<? echo $classes ?>"
  style="<? echo merge_styles_array($styles_array); ?>"
>
  <div class="container">
    <? get_template_part(
      'template-parts/graphics/decorations/decoration',
      'squiggle-x'
    ); ?>
    <div class="section-flexible__content">
      <h2 class="section-flexible__title"><? echo $section_title ?></h2>
      <div class="<? echo $text_classes ?>">
        <? echo $section_text; ?>
      </div>
      <? get_template_part(
        'template-parts/elements/element',
        'btn-loop',
        [
          'field' => 'section_buttons',
          'wrap_classes' => 'section-flexible__btns'
        ]
      ); ?>
    </div>
    <? get_template_part(
        'template-parts/elements/element',
        'post-previews',
        [
          'post_type' => [ $post_type ],
          'posts_per_page' => $post_count
        ]
      ); ?>
  </div>
</section>
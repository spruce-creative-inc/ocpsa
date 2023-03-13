<?

include(TEMPLATEPATH . '/template-parts/sections/section-settings.php');

$text_classes = 'section-flexible__text';

// Check section_text length and add appropriate classes
if (under_word_count($section_text, 20)) {
  $text_classes .= ' section-flexible__text--large';
} else if (under_word_count($section_text, 40)) {
  $text_classes .= ' section-flexible__text--medium';
} else if (above_word_count($section_text, 100)) {
  $classes .= ' regular-content--large-text-block';
}

?>

<section
  id="<? echo $id ?>"
  class="<? echo $classes ?>"
  style="<? echo merge_styles_array($styles_array); ?>"
>
  <div class="container">
    <? if ($section_image) : ?>
      <div class="section-flexible__image">
        <?
          // Display section_decoration if located with image
          if (
            $section_decoration
            && $section_decoration != 'none' 
            && str_contains($section_decoration_location, 'image')
          ) {
            get_template_part(
              'template-parts/graphics/decorations/decoration',
              $section_decoration,
              [
                'location' => $section_decoration_location
              ]
            );
          }

          if ($section_image_shape != 'auto') :
            get_template_part(
              'template-parts/elements/element',
              'image-masked',
              [
                'src' => $section_image['url'],
                'alt' => $section_image['alt'],
                'mask' => $section_image_shape
              ]
            );
          else :
            if ($section_image['caption']):
        ?> 
            <figure>
        <?
            endif;
        ?>
          <img src="<? echo $section_image['url'] ?>" alt="<? echo $section_image['alt'] ?>">
        <?   
            if ($section_image['caption']):
        ?>
              <figcaption><? echo $section_image['caption'] ?></figcaption>
            </figure>
        <?
            endif;
          endif;
        ?>
      </div>
    <? endif; ?>
    <div class="section-flexible__content">
      <?
        // Display section_decoration if located with text or in middle
        if (
          $section_decoration
          && $section_decoration != 'none' 
          && !str_contains($section_decoration_location, 'image')
        ) {
          get_template_part(
            'template-parts/graphics/decorations/decoration',
            $section_decoration,
            [
              'location' => $section_decoration_location
            ]
          );
        }
      ?>
      <h2 class="section-flexible__title"><? echo $section_title ?></h2>
      <div class="<? echo $text_classes ?>">
        <? echo $section_text; ?>
      </div>
      <?
        get_template_part(
          'template-parts/elements/element',
          'btn-loop',
          [
            'field' => 'section_buttons',
            'wrap_classes' => 'section-flexible__btns'
          ]
        );
      ?>
    </div>
  </div>
</section>
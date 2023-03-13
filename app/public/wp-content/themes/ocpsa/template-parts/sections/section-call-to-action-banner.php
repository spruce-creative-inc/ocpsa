<?
include(TEMPLATEPATH . '/template-parts/sections/section-settings.php');
?>

<section
  id="<? echo $id ?>"
  class="<? echo $classes ?>"
  style="<? echo merge_styles_array($styles_array); ?>"
>
  <div class="container">
    <div
      class="
        call-to-action-banner__wrapper
        <? echo str_contains($section_style, 'outline') ? 'call-to-action-banner__wrapper--' . $section_style : '' ?>
      "
    >
      <? if ($section_image) : ?>
        <div class="section-flexible__image">
          <img src="<? echo $section_image['url'] ?>" alt="<? echo $section_image['alt'] ?>">
        </div>
      <? endif; ?>
      <div class="section-flexible__content">
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
  </div>
</section>
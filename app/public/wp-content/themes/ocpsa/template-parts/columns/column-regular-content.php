<?

include(TEMPLATEPATH . '/template-parts/columns/column-settings.php');

?>

<div
  id="<? echo $id ?>"
  class="<? echo $classes ?>"
>
  <? if ($column_image) : ?>
    <div class="column__image">
      <?
        if ($column_image_shape != 'auto') :
          get_template_part(
            'template-parts/elements/element',
            'image-masked',
            [
              'src' => $column_image['url'],
              'alt' => $column_image['alt'],
              'mask' => $column_image_shape
            ]
          );
        else :
      ?>
        <img src="<? echo $column_image['url'] ?>" alt="<? echo $column_image['alt'] ?>">
      <? endif; ?>
    </div>
  <? endif; ?>
  <div class="column__content">
    <h2 class="column__title"><? echo $column_title ?></h2>
    <div class="column__text">
      <? echo $column_text ?>
    </div>
    <? get_template_part(
      'template-parts/elements/element',
      'btn-loop',
      [
        'field' => 'column_buttons',
        'wrap_classes' => 'column__btns'
      ]
    ); ?>
  </div>
</div>
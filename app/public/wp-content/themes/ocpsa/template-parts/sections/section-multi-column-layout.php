<?

include(TEMPLATEPATH . '/template-parts/sections/section-settings.php');

$text_classes = 'section-flexible__text';

// Check section_text length and add appropriate classes
if (under_word_count($section_text, 20)) {
  $text_classes .= ' section-flexible__text--large';
} else if (under_word_count($section_text, 40)) {
  $text_classes .= ' section-flexible__text--medium';
}

$section_columns_width = get_sub_field('section_columns_width');
$column_count = count(get_sub_field('columns'));

$styles_array['--cols'] = $column_count;

?>

<section
  id="<? echo $id ?>"
  class="<? echo $classes ?>"
  style="<? echo merge_styles_array($styles_array); ?>"
>
  <div class="multi-column-layout__wrap <? echo $section_columns_width ?>">
    <?
      $column_index = 1;
      if ( have_rows('columns') ):

        while ( have_rows('columns') ): the_row();

          $column_layout = str_replace('column-', '', underscore_to_hyphen(get_row_layout()));

          $column_args = [
            'column_count'    => $column_count,
            'column_index'    => $column_index,
            'section_index'   => $section_index
          ];

          get_template_part(
            'template-parts/columns/column',
            $column_layout,
            $column_args
          );

          $column_index++;

        endwhile;
      endif;
    ?>
  </div>
</section>
<?

  if( have_rows('sections') ):
    $section_count = count( get_field('sections') );
    $section_index = 1;
    // loop through the rows of data
    while ( have_rows('sections') ) : the_row();
      $disabled = get_sub_field('disable_section');
      if (!$disabled) {

        $section_layout = underscore_to_hyphen(get_row_layout());

        $section_args = [
          'section_count'    => $section_count,
          'section_index'    => $section_index
        ];

        get_template_part(
          'template-parts/sections/section',
          $section_layout,
          $section_args
        );
        
        $section_index++;
      }
    endwhile;
  endif;

?>
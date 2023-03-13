<?
include(TEMPLATEPATH . '/template-parts/sections/section-settings.php');

$panel_count = count( get_sub_field('panels') );

?>

<section
  id="<? echo $id ?>"
  class="<? echo $classes ?>"
  style="<? echo merge_styles_array($styles_array); ?>"
>
  <? get_template_part(
    'template-parts/graphics/decorations/decoration',
    'squiggle-flag'
  ); ?>
  <ul
    class="link-panels__list"
    style="--cols: <? echo $panel_count; ?>;"
  >
    <?
      if ( have_rows('panels') ) :
        while ( have_rows('panels') ) : the_row();
          $panel_image = get_sub_field('panel_image');
          $panel_link = get_sub_field('panel_link');
          $panel_index = get_row_index();
          
          if ($panel_index == 2) {
            $panel_btn_style = 'secondary';
          } else if ($panel_index == 3) {
            $panel_btn_style = 'accent';
          }
    ?>
      <li
        style="background-image: url('<? echo $panel_image['url'] ?>');"
        class="link-panels__panel"
      >
        <a
          href="<? echo $panel_link['url']; ?>"
          class="link-panels__link"
        >
          <span class="btn<? echo $panel_btn_style ? ' btn--' . $panel_btn_style : '' ?>">
            <? echo $panel_link['title']; ?>
          </span>
        </a>
      </li>
    <?
        endwhile;
      endif;
    ?>
  </ul>
</section>
<?

include(TEMPLATEPATH . '/template-parts/sections/section-settings.php');

$classes .= ' pad-t-2 pad-b-2';

$found_partner_logos = new WP_Query([
  'post_type' => [ 'partner-logo' ],
  'order'     => 'ASC'
]);

$partner_logos_count = $found_partner_logos->post_count;

$as_slider = $partner_logos_count > 5;

if ($as_slider) {
  $classes .= ' logo-bar--slider';
}

?>

<section
  id="<? echo $id ?>"
  class="<? echo $classes ?>"
  style="<? echo merge_styles_array($styles_array); ?>"
>
  <div class="container<? echo !$as_slider ? '--flex' : '' ?>">
    <? if ($as_slider) : ?>
      <button class="logo-bar__button logo-bar__button--prev">
        <? get_template_part(
          'template-parts/icons/icon',
          'chevron-left',
          [
            'label' => 'Move logo slider back',
            'size' => '2em'
            ]
          ); ?>
      </button>
      <div class="logo-bar__wrap">
    <? 
      endif;
      if ( $found_partner_logos->have_posts() ) :
        while ( $found_partner_logos->have_posts() ) :
          $found_partner_logos->the_post();
          $partner_index = $found_partner_logos->current_post + 1;
          $partner_title = get_the_title();
          $partner_url = get_field('partner_url');
          $partner_logo = get_field('partner_logo');

          $partner_logo_classes = '';

          if ($partner_index < 5) {
            $partner_logo_classes .= ' slot-' . $partner_index;
          } else if ($partner_index == $partner_logos_count) {
            $partner_logo_classes .= ' off-left';
          } else if ($partner_index == 5) {
            $partner_logo_classes .= ' off-right';
          }

          if ($partner_url) :
    ?>
      <a
        class="
          logo-bar__item
          <?
            echo $partner_logo_classes && $as_slider
            ? $partner_logo_classes
            : '';
          ?>
        "
        href="<? echo $partner_url; ?>"
      >
        <div class="embed embed-3by2">
          <img
            src="<? echo $partner_logo['url']; ?>"
            alt="<? echo $partner_logo['alt']; ?>"
          >
        </div>
      </a>
    <?    else : ?>
      <div
        class="logo-bar__item"
      >
        <div class="embed embed-3by2">
          <img
            src="<? echo $partner_logo['url']; ?>"
            alt="<? echo $partner_logo['alt']; ?>"
          >
        </div>
      </div>
    <?
          endif;
        endwhile;
      endif;
      wp_reset_postdata();
      if ($as_slider) :
    ?>
      </div>
      <button class="logo-bar__button logo-bar__button--next">
        <? get_template_part(
          'template-parts/icons/icon',
          'chevron-right',
          [
            'label' => 'Move logo slider forwards',
            'size' => '2em'
          ]
        ); ?>
      </button>
    <? endif; ?>
  </div>
</section>
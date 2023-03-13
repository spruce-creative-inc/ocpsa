<?

include(TEMPLATEPATH . '/template-parts/banners/banner-settings.php');

?>

<header
  id="<? echo $id ?>"
  class="<? echo $banner_classes ?>"
  <? if ($slide_interval) : ?>
  data-interval="<? echo $slide_interval ?>"
  <? endif; ?>
>
  <?
    if ( have_rows('slides') ) :
      $slide_count = count(get_field('slides'));
      while ( have_rows('slides') ) : the_row();
        $slide_headline = format_title(get_sub_field("slide_headline"));
        $slide_short_title = get_sub_field("slide_short_title");
        $slide_byline = get_sub_field("slide_byline");
        // $slide_buttons = get_sub_field("slide_buttons");
        $slide_image = get_sub_field("slide_image");
        $extra_classes = get_sub_field("extra_classes");
        $slide_index = get_row_index();
        $slide_id = get_sub_field("slide_id") ? get_sub_field("slide_id") : 'banner-slide-' . $slide_index;

        $slide_btn_style = 'tertiary';
        if ($slide_index == 2) {
          $slide_btn_style = 'secondary';
        } else if ($slide_index == 3) {
          $slide_btn_style = 'accent';
        }
        // $added_classes = $slide_index == 1 ? 'active ' . $extra_classes : $extra_classes;
  ?>
    <div
      id="<? echo $slide_id ?>"
      class="
        banner-slider__item
        parallax parallax--shaded
        <? echo $extra_classes ?>
      "
      data-img="<? echo $slide_image['url'] ?>"
      style="
        background-image:
          linear-gradient(-135deg, #0000, #000c),
          url('<? echo $slide_image['url'] ?>');
      "
      aria-label="Slide <? echo $slide_index ?> of <? echo $slide_count ?>"
      aria-current="<? echo $slide_index == 1 ? 'true' : 'false' ?>"
      aria-hidden="<? echo $slide_index == 1 ? 'false' : 'true' ?>"
      role="group"
      aria-roledescription="slide"
    >
      <div class="container">
        <div class="banner-slider__decos banner-slider__decos--slide-<? echo $slide_index ?>">
          <img class="banner-slider__deco banner-slider__deco-main banner-slider__deco-main--slide-<? echo $slide_index ?>" src="/wp-content/themes/ocpsa/images/slide-deco-<? echo $slide_index ?>.png" alt="" role="presentation">
          <img class="banner-slider__deco banner-slider__deco-squiggle banner-slider__deco-squiggle--slide-<? echo $slide_index ?>" src="/wp-content/themes/ocpsa/images/slide-deco-<? echo $slide_index ?>-squiggle.png" alt="" role="presentation">
        </div>
        <h2 class="banner-slider__headline">
          <? echo $slide_headline ?>
        </h2>
        <? if ($slide_byline) : ?>
          <div class="banner-slider__byline">
            <? echo $slide_byline ?>
          </div>
        <?
          endif;
          
          get_template_part(
            'template-parts/elements/element',
            'btn-loop',
            [
              'field' => 'slide_buttons',
              'wrap_classes' => 'banner-slider__btns',
              'btn_style' => $slide_btn_style
            ]
          );
        ?>
      </div>
    </div>
  <?
      endwhile;
    endif;
  ?>
  <?
    if ( have_rows('slides') ) :
  ?>
    <ul class="banner-slider__controls container">
      <?
        while ( have_rows('slides') ) : the_row();
          $slide_short_title = get_sub_field("slide_short_title");
          $slide_index = get_row_index();
          $slide_id = get_sub_field("slide_id") ? get_sub_field("slide_id") : 'banner-slide-' . $slide_index;
      ?>
        <li class="banner-slider__controls-item">
          <a
            class="banner-slider__control"
            href="#<? echo $slide_id ?>"
            aria-current="<? echo $slide_index == 1 ? 'true' : 'false' ?>"
            tabindex="<? echo $slide_index == 1 ? '0' : '-1' ?>"
            aria-controls="<? echo $slide_id ?>"
          >
            <span class="banner-slider__control-label">
              <? echo $slide_short_title ?>
            </span>
          </a>
        </li>
      <?
        endwhile;
      ?>
    </ul>
  <?
    endif;
  ?>
</header>
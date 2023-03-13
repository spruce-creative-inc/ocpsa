<?

include(TEMPLATEPATH . '/template-parts/banners/banner-settings.php');

$banner_classes .= ' pad-b-2';

$byline_classes = 'banner-basic__byline';

// Check section_text length and add appropriate classes
if (under_word_count($banner_byline, 20)) {
  $byline_classes .= ' banner-basic__byline--large';
} else if (under_word_count($banner_byline, 40)) {
  $byline_classes .= ' banner-basic__byline--medium';
}

?>

<header
  id="<? echo $id ?>"
  class="<? echo $banner_classes ?>"
>
  <? if ($banner_image) : ?>
    <div
      class="banner-basic__image parallax"
      data-img="<?php echo $banner_image['url'] ?>"
      <?/* style="background-image: url('<? echo $banner_image['url'] ?>')" */?>
    >
    </div>
  <? endif; ?>
  <div class="container">
    <? get_template_part(
      'template-parts/elements/element',
      'breadcrumbs',
      [
        'hide_home' => true
      ]
    ); ?>
    <div class="banner-basic__content">
      <h2 class="banner-basic__headline">
        <? echo $banner_headline ?>
      </h2>
      <? if ($banner_byline) : ?>
          <div class="<? echo $byline_classes ?>">
            <? echo $banner_byline ?>
          </div>
        <?
          endif;
          
          get_template_part(
            'template-parts/elements/element',
            'btn-loop',
            [
              'field' => 'banner_buttons',
              'wrap_classes' => 'banner-basic__btns',
            ]
          );
        ?>
    </div>
    <div class="banner-basic__content-image">
      <?
        if (
          $banner_content_image_shape
          && $banner_content_image_shape != 'auto'
        ) :
          get_template_part(
            'template-parts/graphics/decorations/decoration',
            'squiggle-x'
          );
          get_template_part(
            'template-parts/elements/element',
            'image-masked',
            [
              'src' => $banner_content_image['url'],
              'alt' => $banner_content_image['alt'],
              'mask' => $banner_content_image_shape
            ]
          );
        else :
      ?>
        <img
          src="
            <?
              echo $banner_content_image
              ? $banner_content_image['url']
              : '/wp-content/themes/ocpsa/images/banner-placeholder.png'
            ?>
          "
          alt="
            <?
              echo $banner_content_image
              ? $banner_content_image['alt']
              : ''
            ?>
          "
        >
      <? endif; ?>
    </div>
  </div>
</header>
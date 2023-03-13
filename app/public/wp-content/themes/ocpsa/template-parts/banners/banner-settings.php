<?

$banner_type = get_field('banner_type');
$slide_interval = get_field('slide_interval');

// Content
$banner_headline = get_field('banner_headline') ? get_field('banner_headline') : get_the_title();
$banner_byline = get_field('banner_byline');
$banner_buttons = get_field('banner_buttons');
$banner_content_image = get_field('banner_content_image');
$banner_content_image_shape = get_field('banner_content_image_shape');

// Design
$banner_image = get_field('banner_image');
$banner_color = get_field('banner_color');

// Settings
$extra_classes = get_field('extra_classes');
$section_id = get_field('section_id');

// Set Banner classes
$banner_classes = 'banner banner-' . $banner_type;

if ($extra_classes) {
  $banner_classes .= ' ' . $extra_classes;
}

// Set Banner ID
$id = $section_id ? $section_id : 'banner';
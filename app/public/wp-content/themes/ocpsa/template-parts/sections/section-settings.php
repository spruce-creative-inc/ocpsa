<?
// Content
$section_title = get_sub_field('section_title');
$section_text = get_sub_field('section_text');
$section_image = get_sub_field('section_image');

// Design
$section_background_color = get_sub_field('section_background_color');
$section_padding_top = get_sub_field('section_padding_top');
$section_padding_bottom = get_sub_field('section_padding_bottom');
$section_image = get_sub_field('section_image');
$section_image_shape = get_sub_field('section_image_shape');
$section_image_side_override = get_sub_field('section_image_side_override');
$section_style = get_sub_field('section_style');
$section_decoration = get_sub_field('section_decoration');
$section_decoration_location = get_sub_field('section_decoration_location');

// Settings
$section_classes = get_sub_field('extra_classes');
$section_id = get_sub_field('section_id');

$section_count = $args['section_count'];
$section_index = $args['section_index'];

$section_layout = underscore_to_hyphen(get_row_layout());

$id = $section_title ? sanitize_title($section_title) : 'section-' . $section_index;
if ($section_id) {
  $id = $section_title;
}

$classes = $section_classes ? 'section-flexible ' . $section_layout . ' ' . $section_classes : 'section-flexible ' . $section_layout;
$text_classes = 'section-flexible__text';

// Add padding classes
if ($section_padding_top) {
  $classes .= $section_padding_top == 1 ? ' pad-t' : ' pad-t-' . $section_padding_top;
}

if ($section_padding_bottom) {
  $classes .= $section_padding_bottom == 1 ? ' pad-b' : ' pad-b-' . $section_padding_bottom;
}

// Check for image and image shape and apply applicable classes
if ($section_image) {
  $classes .= ' section-flexible--with-image';
}

// Check for image side override and apply applicable classes
if (
  $section_image_side_override
  && $section_image_side_override != 'auto'
) {
  $classes .= ' section-flexible--image-' . $section_image_side_override;
}

// Apply section background color
if (
  $section_background_color
  && $section_background_color != 'fff'
) {
  $classes .= ' bg-' . $section_background_color;

  if (
    $section_background_color == 'primary'
    || $section_background_color == 'secondary'
  ) {
    $classes .= ' text-fff';
  }
}

// Apply section style
if (
  !str_contains($section_style, 'outline')
  && $section_style != 'basic'
  && $section_style
) {
  $classes .= ' bg-' . $section_style;

  if (
    $section_style == 'primary'
    || $section_style == 'secondary'
  ) {
    $classes .= ' text-fff';
  }
}

// Check if section decoration and apply classes
if ($section_decoration && $section_decoration != 'none') {
  $classes .= ' section-flexible--with-decoration section-flexible--decoration-' . $section_decoration_location;
}

// Label as first or last section

if ($section_index == 1) {
  $classes .= ' section-flexible--first-section';
}

if ($section_index == $section_count) {
  $classes .= ' section-flexible--last-section';
}

// Style Attributes
$styles_array = [
  '--zIndex' => $section_index
];

?>
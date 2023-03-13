<?

// Content
$column_title = get_sub_field('column_title');
$column_text = get_sub_field('column_text');
$column_buttons = get_sub_field('column_buttons');

// Design
$column_background_color = get_sub_field('column_background_color');
$column_text_align = get_sub_field('column_text_align');
$column_image = get_sub_field('column_image');
$column_image_shape = get_sub_field('column_image_shape');

// Settings
$column_classes = get_sub_field('column_classes');
$column_id = get_sub_field('column_id');

$column_layout = underscore_to_hyphen(get_row_layout());
$column_index = $args['column_index'];
$column_count = $args['column_count'];

// Column Id
$id = $column_title ? sanitize_title($column_title) : 'section-' . $args['section_index'] . '-column-' . $column_index;
if ($column_id) {
  $id = $column_title;
}

// Column Classes
$classes = $column_classes
  ? 'column ' . str_replace('column-', 'column--', $column_layout) . ' ' . $column_classes
  : 'column ' . str_replace('column-', 'column--', $column_layout);

  // Label as first or last column
if ($column_index == 1) {
  $classes .= ' column--first-column';
}

if ($column_index == $column_count) {
  $classes .= ' column--last-column';
}

// Background-color
if (
  $column_background_color
  && $column_background_color != 'fff'
) {
  $classes .= ' bg-' . $column_background_color;
}

if (
  $column_text_align
  && $column_text_align != 'left'
) {
  $classes .= ' text-' . $column_text_align;
}

?>
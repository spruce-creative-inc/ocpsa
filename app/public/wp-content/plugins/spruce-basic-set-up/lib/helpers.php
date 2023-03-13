<?

// disable direct file access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Preview data
function clean_print($data, $title = false) {
  if ($title) {
    print("<pre>".$title.' --> '.print_r($data, true)."</pre>");
  } else {
    print("<pre>".print_r($data, true)."</pre>");
  }
}

// Limit length of string Echo
function limit_string($x, $length) {
  if( strlen($x) <= $length) {
    return $x;
  } else {
    $y = substr( $x, 0, $length ) . ' ...';
    return $y;
  }
}

function add_break($string) {
  return str_replace('//', '<span class="break"></span>', $string);
}

function underscore_to_hyphen($string) {
  return str_replace('_', '-', $string);
}

function format_title($title) {
  $formatted_title = add_break($title);
  return $formatted_title;
}


function word_count($text) {
  $clean_text = strip_tags($text);

  $breaks = [
    "\r\n",
    "\n",
    "\n\n"
  ];
  
  $formatted_text = str_replace( $breaks, ' ', $clean_text );
  $formatted_text = str_replace( ' :', '&nbsp;:', $formatted_text );
  $formatted_text = str_replace( '  ', ' ', $formatted_text );

  $text_as_array = explode(' ', $formatted_text);

  $cleaned_array = [];

  foreach ($text_as_array as $item) {
    if ($item) {
      array_push($cleaned_array, $item);
    }
  }

  $count = count($cleaned_array);
  return $count;
}

function under_word_count($text, $max) {
  $count = word_count($text);
  if ($max > $count) {
    return true;
  }
  return false;
}

function above_word_count($text, $min) {
  $count = word_count($text);
  if ($min < $count) {
    return true;
  }
  return false;
}

function merge_styles_array($styles_array) {
  $styles_string = '';

  foreach ($styles_array as $prop => $value) {
    $styles_string .= $prop . ': ' . $value . ';';
  }

  return $styles_string;
}
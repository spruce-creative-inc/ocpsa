<?

include(TEMPLATEPATH . '/template-parts/columns/column-settings.php');

$classes .= ' text-center';

$found_bod = new WP_Query([
  'post_type' => [ 'board-member' ]
]);

$bod_positions = [
  'President',
  'Secretary',
  'Vice-President',
  'Director of Athletics',
  'Treasurer',
  'Director of Boccia',
  'Director at Large'
];

$bod_members = $found_bod->posts;

$bod_ordered = [];

foreach ($bod_positions as $pos) {
  
  $bod_matched = array_filter(
    $bod_members,
    function ($value) use ($pos) {
      return $value->custom_fields['title'][0] == $pos;
    }
  );

  foreach ($bod_matched as $director) {
    $bod_ordered[] = $director;
  }

}

?>

<div
  id="<? echo $id ?>"
  class="<? echo $classes ?>"
>
  <div class="column__content">
    <h2 class="column__title"><? echo $column_title ?></h2>
    <dl>
      <?
        foreach ($bod_ordered as $key => $director) :
          $director_title = $director->custom_fields['title'][0];
          $director_name = $director->post_title;
      ?>
        <div class="dl__unit">
          <dt><? echo $director_title ?></dt>
          <dd><? echo $director_name ?></dd>
        </div>
      <? endforeach; ?>
    </dl>
  </div>
</div>
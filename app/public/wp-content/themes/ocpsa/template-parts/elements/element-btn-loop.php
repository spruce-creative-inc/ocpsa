<? if ( have_rows($args['field']) ) : ?>
  <div
    class="
      btn-loop
      <? echo $args['wrap_classes'] ?>
    "
  >
  <?
    while ( have_rows($args['field']) ) : the_row();
      $button = get_sub_field('button');
      $button_style = get_sub_field('button_style') ? get_sub_field('button_style') : $args['btn_style'];
      // clean_print($button);
  ?>
    <a
      href="<? echo $button['url'] ?>"
      class="
        btn
        <? echo $button_style ? 'btn--' . $button_style : '' ?>
      "
      <? if ($button['target']) : ?>
        target="_blank"
        rel="nofollow noreferrer"
      <? endif; ?>
    >
      <? echo $button['title'] ?>
    </a>
  <? endwhile; ?>
  </div>
<? endif; ?>
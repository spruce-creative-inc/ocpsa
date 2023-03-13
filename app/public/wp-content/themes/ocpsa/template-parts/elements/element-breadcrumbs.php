<?php

$pageId = get_the_ID();

$ancestors = array_reverse(get_post_ancestors( $pageID ));

$slug = '/';

$postSlug = get_post_field( 'post_name', get_post() );

?>
<nav class="breadcrumbs" aria-label="Breadcrumbs">
  <ol>
    <? if (!$args['hide_home']) : ?>
      <li>
        <a href="/">Home</a>
      </li>
    <?php
      endif;
      foreach ($ancestors as $a) :
        $parent = get_post($a);
        $parentTitle = $parent->post_title;

        $slug .= $parent->post_name . '/';
    ?>
      <li>
        <a href="<?php echo $slug ?>">
          <?php echo $parentTitle ?>
        </a>
      </li>
    <?php
      endforeach;
    ?>
    <li>
      <a href="<?php echo $slug . $postSlug ?>" aria-current="page">
        <?php the_title(); ?>
      </a>
    </li>
  </ol>

</nav>

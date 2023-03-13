<?

$embed_ratio = '1by1';

if (
  $args['mask'] == 'pentagon'
  || $args['mask'] == 'flag'
) {
  $embed_ratio = $args['mask'];
}

?>

<div class="embed embed-<? echo $embed_ratio ?> image-mask image-mask--<? echo $args['mask'] ?>">
  <img src="<? echo $args['src'] ?>" alt="<? echo $args['alt'] ?>">
</div>
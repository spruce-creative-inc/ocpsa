<?
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package spruce
 */

$newsletter_title = get_field('footer_newsletter_title', 'options');
$newsletter_content = get_field('footer_newsletter_content', 'options');
$contact_address = get_field('contact_address', 'options');

?>
      <footer id="footer" class="footer pad-t-3 pad-b-3" tabindex="0">

        <div class="footer__mailing-list">
          <div class="container">
            <div class="footer__mailing-list-intro">
              <h2><? echo $newsletter_title ?></h2>
              <div class="footer__mailing-list-content">
                <? echo $newsletter_content ?>
              </div>
            </div>
            <form class="footer__mailing-list-form" action="<? the_permalink() ?>">
              <input class="textured-border" type="email" name="email" id="email" placeholder="Enter email address">
              <label for="email" class="srt">Enter email address</label>
              <button type="submit">
                <? get_template_part(
                  'template-parts/icons/icon',
                  'plus',
                  [
                    'label' => 'Submit'
                  ]
                ); ?>
              </button>
            </form>
            <hr />
          </div>
        </div>

        <div class="footer__main">
          <div class="container">
            
            <?
              wp_nav_menu( array(
                'theme_location' => 'social-menu',
                'menu_id'        => 'footer__social',
                'menu_class'     => 'footer__social',
                'container'      => 'ul',
                'link_before'    => '<span class="srt">',
                'link_after'     => '</span>'
              ) );
            ?>

            <div class="footer__logo">
              <a class="footer__logo-link" href="/">
                <? get_template_part( 'template-parts/graphics/graphic', 'logo' ); ?>
              </a>
            </div>

            <div class="footer__connect">
              <a class="footer__connect-link" href="#">
                <? get_template_part(
                  'template-parts/icons/icon',
                  'chevron-right',
                  [
                    'label' => 'Connect with us',
                    'label_first' => true,
                    'label_visible' => true,
                    'size' => '1em'
                  ]
                ); ?>
              </a>
            </div>

          </div>
        </div>

        <div class="footer__sub">
          <?
            wp_nav_menu( array(
              'theme_location' => 'footer-menu',
              'menu_id'        => 'footer__menu',
              'menu_class'     => 'footer__menu container--flex',
              'container'      => 'ul',
              'items_to_add'   => [
                [
                  'label' => '&copy; Copyright 2005 - ' . date("Y")
                ],
                [
                  'label' => 'All Rights Reserved'
                ],
                [
                  'label' => $contact_address,
                  'after' => true
                ]
              ]
            ) );
          ?>
        </div>

        <a class="to-top" href="#page">
          <i class="fas fa-chevron-up"></i>
          <span class="screen-reader-text">To Top</span>
        </a>

      </footer>

    </div>
  <? wp_footer(); ?>

</body>
</html>
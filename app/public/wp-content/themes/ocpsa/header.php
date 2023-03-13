<?
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package spruce
 */

  global $current_user; wp_get_current_user();

?>
<!DOCTYPE html>
<html <? language_attributes(); ?>>
<head>
  <meta charset="<? bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="profile" href="http://gmpg.org/xfn/11">

  <!--FAVICON-->
  <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />

  <!-- Adobe Font -->
  <link rel="stylesheet" href="https://use.typekit.net/oig4mwf.css">

  <? wp_head(); ?>
</head>

<body <? body_class(); ?>>
  <? if ($current_user->user_login == 'spruce_admin') : ?>
    <div class="breakpoint-display"></div>
  <? endif ?>
  
  <ul class="skip-links">
    <li><a href="#nav">Skip to main navigation</a></li>
    <li><a href="#main">Skip to main content</a></li>
  </ul>

  <div id="page" class="site">


    <header id="masthead" class="masthead">

      <div class="masthead__pre-bar">
        <div class="container--flex">
          <button class="masthead__search">
            <i class="fa-solid fa-magnifying-glass"></i>
            Search
          </button>
          <a class="masthead__donate" href="/donate">
            <div>Give the gift of sport. <strong>Donate today</strong></div>
          </a>

          <?
            wp_nav_menu( array(
              'theme_location' => 'social-menu',
              'menu_id'        => 'masthead__social',
              'menu_class'     => 'masthead__social',
              'container'      => 'ul',
              'link_before'    => '<span class="srt">',
              'link_after'     => '</span>'
            ) );
          ?>

        </div>
      </div>

      <div class="masthead__main-bar">
        <div class="container--flex">
          
          <a class="masthead__logo" href="/">
            <? if ( is_front_page() && !is_home() ) : ?>
              <h1 class="masthead__title">
            <? else : ?>
              <strong class="masthead__title">
            <?
              endif;
              
              get_template_part( 'template-parts/graphics/graphic', 'logo', ['acronym' => true] );

              if ( is_front_page() && !is_home() ) :
            ?>
              </h1>
            <? else : ?>
              </strong>
            <? endif; ?>
          </a>

          <div class="nav-toggle-wrap">
            <a class="nav-toggle" href="#nav" aria-label="Click to open navigation" data-animate-links="true">
              <span class="tog top"></span>
              <span class="tog middle"></span>
              <span class="tog bottom"></span>
            </a>
          </div>

          <nav id="nav" class="masthead__nav" aria-label="Main menu">

            <?

              wp_nav_menu( array(
                'theme_location' => 'primary-menu',
                'menu_id'        => 'primary-menu',
                'menu_class'     => 'masthead__primary',
                'container'      => 'ul',
                'walker'         => new Primary_Walker_Nav_Menu
              ) );

            ?>
          </nav>

        </div>
      </div>

      <?php get_search_form(); ?>
      
    </header>
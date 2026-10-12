<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); $other = infotechs_lang() === 'ar' ? 'en' : 'ar'; $h = is_front_page() ? '' : home_url('/'); ?>
<header class="site-header" id="siteHeader">
  <div class="wrap">
    <a class="brand" href="<?php echo esc_url(home_url('/')); ?>">
      <img src="<?php echo esc_url(infotechs_asset('img/logo.webp')); ?>" alt="" width="34" height="31">
      <span>INFOTECHS</span>
    </a>
    <nav class="site-nav" id="siteNav" aria-label="Primary">
      <ul>
        <li><a href="<?php echo esc_url($h . '#services'); ?>"><?php echo __t('nav.services'); ?></a></li>
        <li><a href="<?php echo esc_url(home_url('/events/')); ?>"><?php echo __t('nav.events'); ?></a></li>
        <li><a href="<?php echo esc_url($h . '#approach'); ?>"><?php echo __t('nav.approach'); ?></a></li>
        <li><a href="<?php echo esc_url($h . '#about'); ?>"><?php echo __t('nav.about'); ?></a></li>
        <li><a href="<?php echo esc_url(home_url('/gallery/')); ?>"><?php echo __t('nav.gallery'); ?></a></li>
        <li><a href="<?php echo esc_url(home_url('/join-our-team/')); ?>"><?php echo __t('nav.careers'); ?></a></li>
        <li><a href="<?php echo esc_url(home_url('/contacts/')); ?>"><?php echo __t('nav.contact'); ?></a></li>
        <li class="nav-mobile-only"><a class="lang" href="<?php echo esc_url(infotechs_lang_url($other)); ?>"><?php echo __t('lang.switch'); ?></a></li>
        <li class="nav-mobile-only"><a class="btn btn-cyan" href="<?php echo esc_url(home_url('/contacts/')); ?>"><?php echo __t('cta.plan'); ?></a></li>
      </ul>
    </nav>
    <div class="header-actions">
      <a class="lang" href="<?php echo esc_url(infotechs_lang_url($other)); ?>"><?php echo __t('lang.switch'); ?></a>
      <a class="btn btn-cyan" href="<?php echo esc_url(home_url('/contacts/')); ?>"><?php echo __t('cta.plan'); ?></a>
      <button class="burger" id="burger" type="button" aria-expanded="false" aria-controls="siteNav" aria-label="<?php echo __t('menu'); ?>"><span></span><span></span><span></span></button>
    </div>
  </div>
</header>

<?php
/**
 * Front page. Each section has its own scroll mechanic:
 * hero (word mask + logo that flies into the header) → floor plan intro → stats →
 * services (stacking cards) → partners (velocity marquee) → events (horizontal scroll) →
 * approach (scrollytelling with a pinned visual) → about (word-by-word reveal) → CTA.
 */
get_header();
$img = fn($p) => esc_url(infotechs_asset('img/' . $p));
?>

<section class="hero" id="hero">
  <div class="hero-pin">
    <video class="hero-video" autoplay muted loop playsinline preload="metadata" poster="<?php echo $img('hero-poster.jpg'); ?>">
      <source src="<?php echo $img('hero.mp4'); ?>" type="video/mp4">
    </video>
    <svg class="hero-mask" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
      <defs><mask id="wordMask"><rect width="100%" height="100%" fill="#fff"/><text id="maskWord" x="55%" y="52%" text-anchor="middle" dominant-baseline="middle" fill="#000">INFOTECHS</text></mask></defs>
      <rect class="hero-cover" width="100%" height="100%" mask="url(#wordMask)"/>
    </svg>
    <span class="hero-logo-wrap" id="heroLogoWrap"><img class="hero-logo" id="heroLogo" src="<?php echo $img('logo.webp'); ?>" alt=""></span>
    <div class="hero-copy">
      <div class="wrap">
        <span class="eyebrow"><?php echo __t('hero.eyebrow'); ?></span>
        <h1><?php echo __t('hero.h1a'); ?><br><span><?php echo __t('hero.h1b'); ?></span></h1>
        <p><?php echo __t('hero.p'); ?></p>
        <div class="btns"><a class="btn btn-cyan magnetic" href="#contact"><?php echo __t('cta.plan'); ?></a><a class="btn btn-ghost magnetic" href="#events"><?php echo __t('cta.events'); ?></a></div>
      </div>
    </div>
    <div class="hero-hint"><span><?php echo __t('hero.scroll'); ?></span></div>
  </div>
</section>

<section class="plan" id="plan">
  <div class="plan-pin">
    <img class="plan-bg" src="<?php echo $img('plan-bg-poster.jpg'); ?>" alt="">
    <canvas id="planCanvas" aria-label="Exhibition floor plan assembling into the Infotechs mark"></canvas>
    <div class="hud"><span><?php echo __t('plan.hall'); ?></span><span id="hudLine"><?php echo __t('plan.drawing'); ?></span></div>
    <div class="tip" id="planTip"></div>
    <div class="plan-copy">
      <span class="eyebrow"><?php echo __t('plan.eyebrow'); ?></span>
      <h2><?php echo __t('plan.h2'); ?></h2>
      <span class="hint-mono plan-hint" id="planHint"><?php echo __t('plan.hint'); ?> ↓</span>
    </div>
  </div>
</section>

<div class="stats" id="stats"><img class="stats-bg" id="statsBg" src="<?php echo $img('saam-2024-23.webp'); ?>" alt="">
  <div class="wrap">
    <div class="stat"><b data-count="60">0</b><span><?php echo __t('stat.events'); ?></span></div>
    <div class="stat"><b data-count="2500" data-prefix="+">0</b><span><?php echo __t('stat.participants'); ?></span></div>
    <div class="stat"><b data-count="40" data-prefix="+">0</b><span><?php echo __t('stat.exhibitors'); ?></span></div>
    <div class="stat"><b data-count="19">0</b><span><?php echo __t('stat.cme'); ?></span></div>
  </div>
</div>

<section class="section" id="services">
  <div class="wrap">
    <div class="sec-head reveal"><span class="eyebrow"><?php echo __t('svc.eyebrow'); ?></span><h2><?php echo __t('svc.h2'); ?></h2><p><?php echo __t('svc.p'); ?></p></div>
    <div class="stack">
      <?php
      $services = [
        [__t('svc1.t'), __t('svc1.p'), 'svc-conf', explode('|', __t('svc1.tags'))],
        [__t('svc2.t'), __t('svc2.p'), 'svc-expo', explode('|', __t('svc2.tags'))],
        [__t('svc3.t'), __t('svc3.p'), 'svc-virtual', explode('|', __t('svc3.tags'))],
      ];
      foreach ($services as $i => [$title, $text, $clip, $tags]) : ?>
      <article class="card stack-card" style="--i:<?php echo $i; ?>" tabindex="0">
        <video class="card-video" muted loop playsinline preload="metadata" poster="<?php echo $img($clip . '-poster.jpg'); ?>"><source src="<?php echo $img($clip . '.mp4'); ?>" type="video/mp4"></video>
        <span class="num">0<?php echo $i + 1; ?> / 03</span>
        <div class="body">
          <h3><?php echo esc_html($title); ?></h3>
          <p><?php echo esc_html($text); ?></p>
          <ul class="chips"><?php foreach ($tags as $t) echo '<li>' . esc_html($t) . '</li>'; ?></ul>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php get_template_part('parts/services-index'); ?>

<div class="clients" id="clients">
  <div class="wrap"><span class="eyebrow"><?php echo __t('partners'); ?></span><span class="hint-mono"><?php echo __t('partners.hint'); ?></span></div>
  <div class="marquee"><div class="track" id="logoTrack">
    <?php foreach (['c-merz.webp' => 'Merz', 'c-galderma.webp' => 'Galderma', 'c-dermacity.webp' => 'Dermacity', 'c-ame.webp' => 'AME', 'c-saslt.png' => 'SASLT', 'c-ssdds.png' => 'SSDDS', 'c-1.png' => 'Partner', 'c-2.png' => 'Partner'] as $f => $alt) : ?>
      <span class="logo-card"><img src="<?php echo $img($f); ?>" alt="<?php echo esc_attr($alt); ?>" loading="lazy"></span>
    <?php endforeach; ?>
  </div></div>
</div>

<section class="events-h" id="events">
  <div class="events-pin">
    <div class="wrap events-head">
      <div class="sec-head reveal"><span class="eyebrow"><?php echo __t('ev.eyebrow'); ?></span><h2><?php echo __t('ev.h2'); ?></h2></div>
      <div class="events-progress"><span id="evCount">01 / 12</span><div class="bar"><i id="evBar"></i></div></div>
    </div>
    <div class="events-track" id="evTrack">
      <?php
      $q = new WP_Query(['post_type' => 'event', 'posts_per_page' => 12, 'orderby' => 'date', 'order' => 'DESC']);
      $n = 0; $total = wp_count_posts('event')->publish;
      while ($q->have_posts()) : $q->the_post(); $id = get_the_ID(); $n++;
        $date = get_post_meta($id, 'event_date', true); preg_match('/\d{4}/', $date, $yr); ?>
      <article class="ev reveal" tabindex="0"
        data-title="<?php echo esc_attr(infotechs_ev($id, 'title')); ?>" data-type="<?php echo esc_attr(infotechs_ev($id, 'event_type')); ?>"
        data-date="<?php echo esc_attr(infotechs_ev($id, 'event_date')); ?>" data-venue="<?php echo esc_attr(infotechs_ev($id, 'event_venue')); ?>"
        data-scale="<?php echo esc_attr(infotechs_ev($id, 'event_scale')); ?>" data-desc="<?php echo esc_attr(infotechs_ev($id, 'excerpt')); ?>"
        data-img="<?php echo esc_url(get_the_post_thumbnail_url($id, 'large')); ?>" data-link="<?php echo esc_url(get_permalink()); ?>">
        <span class="year"><?php echo esc_html($yr[0] ?? ''); ?></span>
        <span class="tag"><?php echo esc_html(infotechs_ev($id, 'event_type')); ?></span>
        <?php the_post_thumbnail('medium_large', ['loading' => 'eager']); ?>
        <div class="meta"><b><?php echo esc_html(infotechs_ev($id, 'title')); ?></b><small><?php echo esc_html(infotechs_ev($id, 'event_date') . ' · ' . infotechs_ev($id, 'event_venue')); ?></small></div>
      </article>
      <?php endwhile; wp_reset_postdata(); ?>
      <a class="ev ev-more" href="<?php echo esc_url(get_post_type_archive_link('event')); ?>"><span><?php echo __t('ev.all', $total); ?></span></a>
    </div>
  </div>
</section>

<section class="section approach" id="approach">
  <div class="wrap approach-grid">
    <div class="approach-visual">
      <div class="visual-card">
        <div class="phase-imgs">
          <img class="ph ph-0 on" src="<?php echo $img('saam-2024-24.webp'); ?>" alt="">
          <img class="ph ph-1" src="<?php echo $img('saam-2024-07.webp'); ?>" alt="">
          <img class="ph ph-2" src="<?php echo $img('saam-2024-17.webp'); ?>" alt="">
          <img class="ph ph-3" src="<?php echo $img('saam-2024-23.webp'); ?>" alt="">
        </div>
        <div class="visual-label"><span class="k" id="phaseK"><?php echo __t('phase'); ?> 1</span><b id="phaseT"><?php echo __t('ap1.t'); ?></b></div>
      </div>
    </div>
    <div class="approach-steps">
      <div class="sec-head reveal"><span class="eyebrow"><?php echo __t('ap.eyebrow'); ?></span><h2><?php echo __t('ap.h2'); ?></h2></div>
      <div class="step" data-phase="0"><span class="k"><?php echo __t('phase'); ?> 1</span><h3><?php echo __t('ap1.t'); ?></h3><p><?php echo __t('ap1.p'); ?></p></div>
      <div class="step" data-phase="1"><span class="k"><?php echo __t('phase'); ?> 2</span><h3><?php echo __t('ap2.t'); ?></h3><p><?php echo __t('ap2.p'); ?></p></div>
      <div class="step" data-phase="2"><span class="k"><?php echo __t('phase'); ?> 3</span><h3><?php echo __t('ap3.t'); ?></h3><p><?php echo __t('ap3.p'); ?></p></div>
      <div class="step" data-phase="3"><span class="k"><?php echo __t('phase'); ?> 4</span><h3><?php echo __t('ap4.t'); ?></h3><p><?php echo __t('ap4.p'); ?></p></div>
    </div>
  </div>
</section>


<section class="gallery" id="gallery">
  <div class="wrap"><div class="sec-head reveal"><span class="eyebrow"><?php echo __t('g.eyebrow'); ?></span><h2><?php echo __t('g.h2'); ?></h2><p><?php echo __t('g.p'); ?></p></div></div>
  <div class="gallery-cols">
    <?php
    $cols = [
      ['saam-2022-05.webp', 'saam-2024-04.webp', 'saam-2022-02.webp', 'saam-2024-21.webp'],
      ['saam-2024-16.webp', 'saam-2023-12.webp', 'saam-2024-09.webp', 'saam-2022-04.webp'],
      ['saam-2024-11.webp', 'saam-2023-05.webp', 'saam-2024-26.webp', 'saam-2023-16.webp'],
    ];
    foreach ($cols as $ci => $col) : ?>
    <div class="gcol" data-speed="<?php echo [0.8, 1.25, 0.95][$ci]; ?>">
      <?php foreach ($col as $f) : ?><figure><img src="<?php echo $img($f); ?>" alt="SAAM conference, Riyadh" loading="lazy"></figure><?php endforeach; ?>
    </div>
    <?php endforeach; ?>
  </div>
  <div class="wrap gallery-more reveal"><a class="btn btn-ghost-dark magnetic" href="<?php echo esc_url(home_url('/gallery/')); ?>"><?php echo __t('g.more'); ?></a></div>
</section>

<?php get_template_part('parts/about'); ?>

<div class="final" id="contact">
  <canvas id="ctaCanvas" aria-hidden="true"></canvas>
  <div class="wrap"><span class="eyebrow"><?php echo __t('cta.eyebrow'); ?></span><h2><?php echo __t('cta.h2'); ?></h2>
    <p><?php echo __t('cta.p'); ?></p>
    <div class="btns"><a class="btn btn-cyan magnetic" href="<?php echo esc_url(home_url('/contacts/')); ?>"><?php echo __t('cta.plan'); ?></a><a class="btn btn-ghost magnetic" href="tel:+966535140007">+966 53 514 0007</a></div>
    <p class="cta-addr hint-mono"><?php echo __t('ct.address'); ?></p></div>
</div>

<?php get_footer();

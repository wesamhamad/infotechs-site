<?php get_header(); the_post(); $id = get_the_ID(); ?>
<main class="page-content event-single">
  <div class="wrap grid-2">
    <?php if (has_post_thumbnail()) the_post_thumbnail('large', ['class' => 'poster']); ?>
    <div>
      <span class="eyebrow"><?php echo esc_html(infotechs_ev($id, 'event_type')); ?></span>
      <h1><?php echo esc_html(infotechs_ev($id, 'title')); ?></h1>
      <dl class="facts">
        <dt><?php echo __t('ev.date'); ?></dt><dd><?php echo esc_html(infotechs_ev($id, 'event_date')); ?></dd>
        <dt><?php echo __t('ev.venue'); ?></dt><dd><?php echo esc_html(infotechs_ev($id, 'event_venue')); ?></dd>
        <dt><?php echo __t('ev.scale'); ?></dt><dd><?php echo esc_html(infotechs_ev($id, 'event_scale')); ?></dd>
      </dl>
      <div class="prose"><p><?php echo esc_html(infotechs_ev($id, 'excerpt')); ?></p><?php if (infotechs_lang() === 'en') the_content(); ?></div>
      <a class="btn btn-ghost-dark" href="<?php echo esc_url(home_url('/#events')); ?>"><?php echo __t('ev.back'); ?></a>
    </div>
  </div>
</main>
<?php get_footer(); ?>

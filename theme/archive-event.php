<?php get_header(); $total = wp_count_posts('event')->publish; ?>
<main class="page-content events-archive">
  <div class="wrap page-head">
    <span class="eyebrow"><?php echo __t('eva.eyebrow'); ?></span>
    <h1 class="page-title"><?php echo __t('eva.h1'); ?></h1>
    <div class="filters" id="evFilters" role="group">
      <button class="chip on" type="button" data-f="*"><?php echo __t('eva.all'); ?> <i><?php echo (int) $total; ?></i></button>
      <?php
      $types = []; $q = new WP_Query(['post_type' => 'event', 'posts_per_page' => -1, 'fields' => 'ids', 'no_found_rows' => true]);
      foreach ($q->posts as $id) { $t = infotechs_ev($id, 'event_type'); if ($t) $types[$t] = ($types[$t] ?? 0) + 1; }
      arsort($types);
      foreach ($types as $t => $c) echo '<button class="chip" type="button" data-f="' . esc_attr($t) . '">' . esc_html($t) . ' <i>' . $c . '</i></button>';
      ?>
    </div>
  </div>
  <div class="wrap">
    <div class="ev-grid" id="evGrid">
      <?php $q = new WP_Query(['post_type' => 'event', 'posts_per_page' => -1, 'orderby' => 'date', 'order' => 'DESC']);
      while ($q->have_posts()) : $q->the_post(); $id = get_the_ID(); $type = infotechs_ev($id, 'event_type'); ?>
      <article class="ev ev-cell" data-type="<?php echo esc_attr($type); ?>" tabindex="0"
        data-title="<?php echo esc_attr(infotechs_ev($id, 'title')); ?>" data-date="<?php echo esc_attr(infotechs_ev($id, 'event_date')); ?>" data-venue="<?php echo esc_attr(infotechs_ev($id, 'event_venue')); ?>"
        data-scale="<?php echo esc_attr(infotechs_ev($id, 'event_scale')); ?>" data-desc="<?php echo esc_attr(infotechs_ev($id, 'excerpt')); ?>"
        data-img="<?php echo esc_url(get_the_post_thumbnail_url($id, 'large')); ?>" data-link="<?php echo esc_url(get_permalink()); ?>">
        <span class="tag"><?php echo esc_html($type); ?></span>
        <?php the_post_thumbnail('medium_large', ['loading' => 'lazy']); ?>
        <div class="meta"><b><?php echo esc_html(infotechs_ev($id, 'title')); ?></b><?php if ($d = infotechs_ev($id, 'event_date')) echo '<small>' . esc_html($d) . '</small>'; ?></div>
      </article>
      <?php endwhile; wp_reset_postdata(); ?>
    </div>
  </div>
</main>
<?php get_footer(); ?>

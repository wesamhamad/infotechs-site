<?php get_header(); $files = glob(get_template_directory() . '/img/gallery/g-*.webp'); sort($files); $n = count($files);
$u = fn($f) => esc_url(infotechs_asset('img/gallery/' . basename($f)));
/* Mosaic pattern: [column span, row span] repeated every 9 tiles. */
$pattern = [[4, 2], [2, 1], [2, 1], [2, 2], [2, 1], [2, 1], [3, 1], [3, 2], [3, 1]];
?>
<main class="page-content gallery-page">
  <div class="wrap gal-head">
    <div class="gal-head-copy">
      <span class="eyebrow"><?php echo __t('gp.eyebrow'); ?></span>
      <h1 class="page-title gal-title"><?php echo __t('gp.h1'); ?></h1>
      <p class="lead"><?php echo __t('gp.p', $n); ?></p>
    </div>
    <div class="gal-counter" aria-hidden="true"><b id="galSeen">001</b><span>/ <?php echo str_pad($n, 3, '0', STR_PAD_LEFT); ?></span><small><?php echo __t('gp.seen'); ?></small></div>
  </div>
  <div class="gal-strip" aria-hidden="true"><div class="gal-strip-track" id="galStrip">
    <?php foreach (array_slice($files, 0, 14) as $f) : ?><img src="<?php echo $u($f); ?>" alt="" loading="lazy"><?php endforeach; ?>
  </div></div>
  <div class="wrap">
    <div class="mosaic" id="mosaic">
      <?php foreach ($files as $i => $f) : [$cs, $rs] = $pattern[$i % count($pattern)]; ?>
      <figure class="tile" style="--cs:<?php echo $cs; ?>;--rs:<?php echo $rs; ?>" data-i="<?php echo $i; ?>">
        <a href="<?php echo $u($f); ?>" data-light><img src="<?php echo $u($f); ?>" alt="Infotechs event photo <?php echo $i + 1; ?>" loading="lazy"><span class="tn"><?php echo str_pad($i + 1, 3, '0', STR_PAD_LEFT); ?></span></a>
      </figure>
      <?php endforeach; ?>
    </div>
  </div>
  <div class="gal-cursor" id="galCursor" aria-hidden="true"><?php echo __t('gp.view'); ?></div>
</main>
<div class="lightbox" id="lightbox" hidden>
  <img src="" alt="">
  <button class="lb-nav prev" id="lbPrev" type="button" aria-label="Previous">←</button><button class="lb-nav next" id="lbNext" type="button" aria-label="Next">→</button>
  <button class="close" id="lbClose" type="button" aria-label="<?php echo __t('modal.close'); ?>">×</button><span class="lb-count" id="lbCount"></span>
</div>
<?php get_footer(); ?>

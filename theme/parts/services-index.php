<?php
/* Expertise (4 wide tiles) + services (11 compact tiles). Hovering a tile reveals a client photo behind it. */
$img = fn($p) => esc_url(infotechs_asset('img/' . $p));
$photos = ['saam-2024-01','saam-2023-04','saam-2024-08','saam-2022-06','saam-2024-12','saam-2023-09','saam-2024-18','saam-2023-14','saam-2024-20','saam-2022-01','saam-2024-25','saam-2023-02','saam-2024-05','saam-2023-17','saam-2024-14'];
$n = 0;
?>
<section class="section sindex" id="services-all">
  <div class="wrap">
    <div class="sec-head reveal"><span class="eyebrow"><?php echo __t('sx.eyebrow'); ?></span><h2><?php echo __t('sx.h2'); ?></h2><p><?php echo __t('sx.p2'); ?></p></div>
    <span class="k reveal sgrid-k"><?php echo __t('sx.g1'); ?></span>
    <div class="sgrid sgrid-x">
      <?php foreach (['x1', 'x2', 'x3', 'x4'] as $k) : $n++; ?>
      <article class="stile reveal" style="--img:url(<?php echo $img($photos[$n - 1] . '.webp'); ?>)" tabindex="0">
        <span class="sn"><?php echo str_pad($n, 2, '0', STR_PAD_LEFT); ?></span><b><?php echo __t("$k.t"); ?></b><p><?php echo __t("$k.p"); ?></p>
      </article>
      <?php endforeach; ?>
    </div>
    <span class="k reveal sgrid-k"><?php echo __t('sx.g2'); ?></span>
    <div class="sgrid sgrid-s">
      <?php for ($i = 1; $i <= 11; $i++) : $n++; ?>
      <article class="stile reveal" style="--img:url(<?php echo $img($photos[($n - 1) % count($photos)] . '.webp'); ?>)" tabindex="0">
        <span class="sn"><?php echo str_pad($n, 2, '0', STR_PAD_LEFT); ?></span><b><?php echo __t("s$i.t"); ?></b><p><?php echo __t("s$i.p"); ?></p>
      </article>
      <?php endfor; ?>
      <a class="stile stile-cta reveal" href="<?php echo esc_url(home_url('/contacts/')); ?>"><b><?php echo __t('cta.plan'); ?> →</b></a>
    </div>
  </div>
</section>

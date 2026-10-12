<?php
/**
 * About block: intro, three photo pillars (mission / vision / team), team portraits, "Why Infotechs" grid.
 * Shared by the front page and the About us page.
 */
$img = fn($p) => esc_url(infotechs_asset('img/' . $p));
$team = [
  ['tm1', 'team/alkhowailed.webp'], ['tm2', 'team/altalhab.webp'], ['tm3', 'team/abass.webp'], ['tm4', 'team/zainab.webp'],
  ['tm5', null], ['tm6', null], ['tm7', 'team/bazil.webp'], ['tm8', 'team/alatwi.webp'],
];
?>
<section class="section about" id="about">
  <div class="wrap">
    <div class="about-top">
      <div class="reveal"><span class="eyebrow"><?php echo __t('ab.eyebrow'); ?></span><h2><?php echo __t('ab.h2'); ?></h2></div>
      <p class="words" id="aboutWords"><?php echo __t('ab.intro'); ?></p>
    </div>

    <div class="about-pillars" id="pillars">
      <?php foreach ([['ab.mission', 'saam-2024-23.webp'], ['ab.vision', 'saam-2024-24.webp'], ['ab.team', 'saam-2022-03.webp']] as $i => [$k, $f]) : ?>
      <article class="pillar" style="--i:<?php echo $i; ?>" tabindex="0">
        <div class="pillar-img"><img src="<?php echo $img($f); ?>" alt="" loading="lazy"></div>
        <span class="pillar-n">0<?php echo $i + 1; ?></span>
        <div class="pillar-body"><span class="k"><?php echo __t("$k.k"); ?></span><p><?php echo __t($k); ?></p></div>
      </article>
      <?php endforeach; ?>
    </div>

    <div class="team" id="team">
      <div class="sec-head reveal"><span class="eyebrow"><?php echo __t('tm.eyebrow'); ?></span><h2><?php echo __t('tm.h2'); ?></h2></div>
      <div class="team-row">
        <?php foreach ($team as $i => [$k, $f]) : $name = __t("$k.n"); ?>
        <figure class="member" style="--i:<?php echo $i; ?>">
          <div class="member-img">
            <?php if ($f) : ?><img src="<?php echo $img($f); ?>" alt="<?php echo esc_attr($name); ?>" loading="lazy">
            <?php else : ?><span class="initials"><?php echo esc_html(mb_substr($name, 0, 1)); ?></span><?php endif; ?>
          </div>
          <figcaption><b><?php echo esc_html($name); ?></b><span><?php echo __t("$k.r"); ?></span></figcaption>
        </figure>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="values" id="why">
      <div class="values-head reveal"><span class="eyebrow"><?php echo __t('ab.values.k'); ?></span><span class="hint-mono"><?php echo __t('ab.based'); ?></span></div>
      <ul class="values-grid" id="valuesGrid">
        <?php for ($i = 1; $i <= 6; $i++) : ?>
        <li class="value" style="--i:<?php echo $i - 1; ?>"><span class="vn">0<?php echo $i; ?></span><b><?php echo __t("v$i.t"); ?></b><p><?php echo __t("v$i.p"); ?></p><i class="vline"></i></li>
        <?php endfor; ?>
      </ul>
    </div>
  </div>
</section>

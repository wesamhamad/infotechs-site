<?php get_header(); $sent = $_GET['sent'] ?? ''; $img = fn($p) => esc_url(infotechs_asset('img/' . $p));
$roles = ['jn.p1' => 'jn.d1', 'jn.p2' => 'jn.d2', 'jn.p3' => 'jn.d3', 'jn.p4' => 'jn.d4']; ?>
<main class="join-page">
  <section class="join-hero">
    <div class="join-marquee" aria-hidden="true"><div class="join-marquee-track" id="joinMarquee"><?php for ($i = 0; $i < 6; $i++) echo '<span>' . __t('jn.h1') . ' <i>✦</i> </span>'; ?></div></div>
    <div class="wrap join-hero-grid">
      <div class="join-hero-copy">
        <span class="eyebrow"><?php echo __t('jn.eyebrow'); ?></span>
        <h1 class="page-title"><?php echo __t('jn.h1'); ?></h1>
        <p class="lead"><?php echo __t('jn.p'); ?></p>
        <div class="join-facts">
          <div><b data-count="60">0</b><span><?php echo __t('stat.events'); ?></span></div>
          <div><b data-count="8">0</b><span><?php echo __t('jn.f.team'); ?></span></div>
          <div><b data-count="4">0</b><span><?php echo __t('jn.f.roles'); ?></span></div>
        </div>
      </div>
      <div class="join-collage" id="collage">
        <img src="<?php echo $img('saam-2024-24.webp'); ?>" alt="" data-depth=".6">
        <img src="<?php echo $img('saam-2023-12.webp'); ?>" alt="" data-depth="1.2">
        <img src="<?php echo $img('saam-2022-03.webp'); ?>" alt="" data-depth=".9">
      </div>
    </div>
  </section>

  <section class="section roles" id="roles">
    <div class="wrap">
      <div class="sec-head reveal"><span class="eyebrow"><?php echo __t('jn.open'); ?></span><h2><?php echo __t('jn.open.h2'); ?></h2><p><?php echo __t('jn.open.p'); ?></p></div>
      <ul class="role-list">
        <?php $i = 0; foreach ($roles as $k => $d) : $i++; ?>
        <li class="role" tabindex="0" data-role="<?php echo esc_attr(__t($k)); ?>">
          <span class="rn">0<?php echo $i; ?></span>
          <div class="role-main"><b><?php echo __t($k); ?></b><p><?php echo __t($d); ?></p></div>
          <span class="role-meta hint-mono">Riyadh · <?php echo __t('jn.fulltime'); ?></span>
          <span class="role-cta"><?php echo __t('jn.apply'); ?> →</span>
        </li>
        <?php endforeach; ?>
        <li class="role" tabindex="0" data-role="<?php echo esc_attr(__t('jn.p5')); ?>">
          <span class="rn">05</span>
          <div class="role-main"><b><?php echo __t('jn.p5'); ?></b><p><?php echo __t('jn.d5'); ?></p></div>
          <span class="role-meta hint-mono">Riyadh</span>
          <span class="role-cta"><?php echo __t('jn.apply'); ?> →</span>
        </li>
      </ul>
    </div>
  </section>

  <section class="section apply" id="apply">
    <div class="wrap apply-grid">
      <div class="apply-side">
        <span class="eyebrow"><?php echo __t('jn.apply.k'); ?></span>
        <h2 id="applyTitle"><?php echo __t('jn.apply.h2'); ?></h2>
        <div class="apply-progress"><div class="ring"><svg viewBox="0 0 80 80"><circle cx="40" cy="40" r="34"/><circle cx="40" cy="40" r="34" id="ringFill"/></svg><b id="ringPct">0%</b></div><p><?php echo __t('jn.progress'); ?></p></div>
        <ol class="apply-steps hint-mono"><li><?php echo __t('jn.st1'); ?></li><li><?php echo __t('jn.st2'); ?></li><li><?php echo __t('jn.st3'); ?></li></ol>
      </div>
      <form class="form apply-form" id="applyForm" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" enctype="multipart/form-data">
        <?php if ($sent === 'ok') echo '<p class="ok">' . __t('jn.sent') . '</p>'; elseif ($sent === 'err') echo '<p class="err">' . __t('f.error') . '</p>'; ?>
        <?php wp_nonce_field('infotechs_form', '_inf'); ?><input type="hidden" name="action" value="infotechs_apply"><input type="hidden" name="redirect" value="<?php echo esc_url(get_permalink()); ?>">
        <div class="row2"><label class="fl"><input type="text" name="first" required placeholder=" "><span><?php echo __t('jn.first'); ?></span></label><label class="fl"><input type="text" name="last" required placeholder=" "><span><?php echo __t('jn.last'); ?></span></label></div>
        <div class="row2"><label class="fl"><input type="email" name="email" required placeholder=" "><span><?php echo __t('f.email'); ?></span></label><label class="fl"><input type="tel" name="phone" required placeholder=" "><span><?php echo __t('f.phone'); ?></span></label></div>
        <label class="fl sel"><select name="position" id="positionSel" required><option value=""></option><?php for ($i = 1; $i <= 5; $i++) echo '<option>' . __t("jn.p$i") . '</option>'; ?></select><span><?php echo __t('jn.position'); ?></span></label>
        <label class="fl"><textarea name="cover" rows="5" placeholder=" "></textarea><span><?php echo __t('jn.cover'); ?></span></label>
        <label class="drop" id="drop"><input type="file" name="cv" id="cvInput" accept="application/pdf" required>
          <span class="drop-icon">PDF</span><b id="dropLabel"><?php echo __t('jn.drop'); ?></b><small><?php echo __t('jn.cv'); ?></small></label>
        <button class="btn btn-cyan magnetic" type="submit"><?php echo __t('jn.submit'); ?></button>
      </form>
    </div>
  </section>
</main>
<?php get_footer(); ?>

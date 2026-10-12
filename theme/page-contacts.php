<?php get_header(); $sent = $_GET['sent'] ?? ''; ?>
<main class="contact-page">
  <section class="cdesk">
    <div class="cdesk-left">
      <canvas id="deskCanvas" aria-hidden="true"></canvas>
      <div class="cdesk-copy">
        <span class="eyebrow"><?php echo __t('ct.eyebrow'); ?></span>
        <h1 class="cdesk-title"><?php echo __t('ct.h1'); ?></h1>
        <div class="riyadh-clock" id="riyadhClock"><span class="dot"></span><b id="clockTime">--:--</b><span id="clockState"><?php echo __t('ct.hours.k'); ?></span></div>
        <dl class="coords" id="coords">
          <div class="coord"><dt><?php echo __t('ct.address.k'); ?></dt><dd><?php echo __t('ct.address'); ?></dd></div>
          <div class="coord"><dt><?php echo __t('ct.phone.k'); ?></dt><dd><a href="tel:+966535140007" dir="ltr">+966 53 514 0007</a><button class="copy" type="button" data-copy="+966535140007"><?php echo __t('ct.copy'); ?></button></dd></div>
          <div class="coord"><dt><?php echo __t('ct.email.k'); ?></dt><dd><a href="mailto:info@infotechs.co">info@infotechs.co</a><button class="copy" type="button" data-copy="info@infotechs.co"><?php echo __t('ct.copy'); ?></button></dd></div>
          <div class="coord"><dt><?php echo __t('ct.hours.k'); ?></dt><dd><?php echo __t('ct.hours1'); ?><br><?php echo __t('ct.hours2'); ?></dd></div>
        </dl>
      </div>
    </div>
    <div class="cdesk-right">
      <form class="brief" id="brief" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
        <?php if ($sent === 'ok') echo '<p class="ok">' . __t('f.sent') . '</p>'; elseif ($sent === 'err') echo '<p class="err">' . __t('f.error') . '</p>'; ?>
        <?php wp_nonce_field('infotechs_form', '_inf'); ?><input type="hidden" name="action" value="infotechs_contact"><input type="hidden" name="redirect" value="<?php echo esc_url(get_permalink()); ?>">
        <input type="hidden" name="subject" id="briefSubject" value="Event brief">
        <div class="brief-head"><span class="k" id="briefStep">01 / 03</span><div class="brief-bar"><i id="briefBar"></i></div></div>

        <fieldset class="bstep on" data-step="0">
          <h2><?php echo __t('bf.s1'); ?></h2>
          <div class="pick" id="pickType">
            <?php foreach (['conf' => 'bf.t1', 'expo' => 'bf.t2', 'work' => 'bf.t3', 'virt' => 'bf.t4'] as $v => $k) : ?>
            <label class="pick-card"><input type="radio" name="type" value="<?php echo __t($k); ?>" required><span><?php echo __t($k); ?></span></label>
            <?php endforeach; ?>
          </div>
          <label class="range"><span><?php echo __t('bf.size'); ?> <b id="sizeOut">500</b></span><input type="range" name="attendees" id="sizeIn" min="50" max="3000" step="50" value="500"><small id="sizeHint"></small></label>
          <div class="bnav"><button class="btn btn-cyan" type="button" data-next><?php echo __t('bf.next'); ?></button></div>
        </fieldset>

        <fieldset class="bstep" data-step="1">
          <h2><?php echo __t('bf.s2'); ?></h2>
          <div class="row2"><label><span><?php echo __t('bf.when'); ?> *</span><input type="month" name="when" required></label><label><span><?php echo __t('bf.city'); ?> *</span><input type="text" name="city" placeholder="Riyadh" required></label></div>
          <label><span><?php echo __t('bf.needs'); ?> *</span><textarea name="message" rows="4" required placeholder="<?php echo esc_attr(__t('bf.needs.ph')); ?>"></textarea></label>
          <div class="bnav"><button class="btn btn-ghost-dark" type="button" data-prev><?php echo __t('bf.back'); ?></button><button class="btn btn-cyan" type="button" data-next><?php echo __t('bf.next'); ?></button></div>
        </fieldset>

        <fieldset class="bstep" data-step="2">
          <h2><?php echo __t('bf.s3'); ?></h2>
          <label><span><?php echo __t('f.name'); ?> *</span><input type="text" name="name" required></label>
          <div class="row2"><label><span><?php echo __t('f.email'); ?> *</span><input type="email" name="email" required></label><label><span><?php echo __t('f.phone'); ?> *</span><input type="tel" name="phone" required></label></div>
          <p class="brief-sum hint-mono" id="briefSum"></p>
          <div class="bnav"><button class="btn btn-ghost-dark" type="button" data-prev><?php echo __t('bf.back'); ?></button><button class="btn btn-cyan magnetic" type="submit"><?php echo __t('bf.send'); ?></button></div>
        </fieldset>
      </form>
    </div>
  </section>
  <section class="cmap">
    <iframe title="Infotechs office map" src="https://www.google.com/maps?q=Nawat+Investment+Building,+Imam+Saud+Bin+Abdulaziz+Rd,+Riyadh+12211&z=15&output=embed" loading="lazy" allowfullscreen referrerpolicy="no-referrer-when-downgrade"></iframe>
    <div class="cmap-card"><span class="k"><?php echo __t('ct.address.k'); ?></span><b><?php echo __t('ct.address'); ?></b><a class="btn btn-cyan" target="_blank" rel="noopener" href="https://www.google.com/maps/search/?api=1&query=Nawat+Investment+Building+Imam+Saud+Bin+Abdulaziz+Rd+Riyadh+12211"><?php echo __t('ct.map'); ?> ↗</a></div>
  </section>
</main>
<?php get_footer(); ?>

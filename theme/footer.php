<footer class="site-footer">
  <div class="wrap footer-grid">
    <div class="f-brand">
      <a class="brand" href="<?php echo esc_url(home_url('/')); ?>"><img src="<?php echo esc_url(infotechs_asset('img/logo.webp')); ?>" alt="" width="34" height="31"><span>INFOTECHS</span></a>
      <p><?php echo __t('footer.tag'); ?><br><?php echo __t('footer.city'); ?></p>
    </div>
    <div class="f-col"><span class="k"><?php echo __t('footer.pages'); ?></span>
      <a href="<?php echo esc_url(home_url('/')); ?>"><?php echo __t('nav.home'); ?></a>
      <a href="<?php echo esc_url(home_url('/#about')); ?>"><?php echo __t('nav.about'); ?></a>
      <a href="<?php echo esc_url(home_url('/events/')); ?>"><?php echo __t('nav.events'); ?></a>
      <a href="<?php echo esc_url(home_url('/gallery/')); ?>"><?php echo __t('nav.gallery'); ?></a>
      <a href="<?php echo esc_url(home_url('/join-our-team/')); ?>"><?php echo __t('nav.careers'); ?></a>
      <a href="<?php echo esc_url(home_url('/contacts/')); ?>"><?php echo __t('nav.contact'); ?></a>
    </div>
    <div class="f-col"><span class="k"><?php echo __t('footer.contact'); ?></span>
      <a href="tel:+966535140007" dir="ltr">+966 53 514 0007</a>
      <a href="mailto:info@infotechs.co">info@infotechs.co</a>
      <span><?php echo __t('ct.hours1'); ?></span>
    </div>
    <form class="f-news" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
      <span class="k"><?php echo __t('nl.k'); ?></span><p><?php echo __t('nl.p'); ?></p>
      <?php wp_nonce_field('infotechs_form', '_inf'); ?><input type="hidden" name="action" value="infotechs_newsletter"><input type="hidden" name="redirect" value="<?php echo esc_url(add_query_arg('sent', 'nl')); ?>">
      <div class="news-row"><input type="email" name="email" required placeholder="<?php echo esc_attr(__t('f.email')); ?>"><button class="btn btn-cyan" type="submit"><?php echo __t('nl.btn'); ?></button></div>
      <?php if (($_GET['sent'] ?? '') === 'nl') echo '<small class="ok">' . __t('f.sent') . '</small>'; ?>
    </form>
  </div>
  <div class="wrap footer-bottom"><span>© <?php echo date('Y'); ?> Infotechs</span><span><?php echo __t('footer.tag'); ?></span></div>
</footer>
<div class="modal" id="evModal" role="dialog" aria-modal="true" hidden>
  <div class="sheet">
    <img id="mImg" src="" alt="">
    <div class="info"><span class="eyebrow" id="mTag"></span><h3 id="mTitle"></h3><dl id="mDl"></dl><p id="mDesc"></p><a id="mLink" class="btn btn-cyan" href="#"><?php echo __t('ev.page'); ?></a></div>
    <button class="close" id="mClose" type="button" aria-label="<?php echo __t('modal.close'); ?>">×</button>
  </div>
</div>
<script>window.INFOTECHS_C = {open: <?php echo json_encode(__t('ct.open')); ?>, closed: <?php echo json_encode(__t('ct.closed')); ?>, copied: <?php echo json_encode(__t('ct.copied')); ?>, sum: <?php echo json_encode(__t('bf.sum')); ?>, booths: <?php echo json_encode(__t('bf.booths')); ?>, hallS: <?php echo json_encode(__t('bf.hallS')); ?>, hallM: <?php echo json_encode(__t('bf.hallM')); ?>, hallL: <?php echo json_encode(__t('bf.hallL')); ?>};
window.INFOTECHS_L = {date: <?php echo json_encode(__t('ev.date')); ?>, venue: <?php echo json_encode(__t('ev.venue')); ?>, scale: <?php echo json_encode(__t('ev.scale')); ?>};</script>
<?php wp_footer(); ?>
</body>
</html>

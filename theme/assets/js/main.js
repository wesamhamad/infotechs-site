/* Infotechs front-end. One mechanic per section, all driven by GSAP ScrollTrigger + Lenis smooth scroll. */
(function () {
  'use strict';
  const reduce = matchMedia('(prefers-reduced-motion: reduce)').matches;
  const clamp = (v, a, b) => Math.max(a, Math.min(b, v));
  const ease = t => t < .5 ? 4 * t * t * t : 1 - Math.pow(-2 * t + 2, 3) / 2;
  gsap.registerPlugin(ScrollTrigger);
  const RTL = document.documentElement.dir === 'rtl', L = window.INFOTECHS_L || {};
  const hdrEl = document.getElementById('siteHeader'), burger = document.getElementById('burger');
  if (burger) { burger.addEventListener('click', () => { const open = hdrEl.classList.toggle('menu-open'); burger.setAttribute('aria-expanded', open); document.documentElement.style.overflow = open ? 'hidden' : ''; });
    document.querySelectorAll('#siteNav a').forEach(a => a.addEventListener('click', () => { hdrEl.classList.remove('menu-open'); burger.setAttribute('aria-expanded', 'false'); document.documentElement.style.overflow = ''; })); }

  /* ---------- Smooth scroll ---------- */
  let lenis = null;
  if (window.Lenis && !reduce) {
    lenis = new Lenis({ lerp: 0.12, smoothWheel: true }); window.__lenis = lenis;
    lenis.on('scroll', ScrollTrigger.update);
    gsap.ticker.add(t => lenis.raf(t * 1000)); gsap.ticker.lagSmoothing(0);
    document.querySelectorAll('a[href^="#"]').forEach(a => a.addEventListener('click', e => { const el = document.querySelector(a.getAttribute('href')); if (el) { e.preventDefault(); lenis.scrollTo(el, { offset: -70 }); } }));
  }

  /* ---------- Cursor + magnetic buttons ---------- */
  const cur = document.createElement('div'); cur.className = 'cursor'; document.body.appendChild(cur);
  addEventListener('pointermove', e => { cur.classList.add('on'); gsap.to(cur, { x: e.clientX, y: e.clientY, duration: .18, ease: 'power2.out' }); });
  document.querySelectorAll('a,button,.ev,.card').forEach(el => { el.addEventListener('pointerenter', () => cur.classList.add('big')); el.addEventListener('pointerleave', () => cur.classList.remove('big')); });
  document.querySelectorAll('.magnetic').forEach(b => {
    b.addEventListener('pointermove', e => { const r = b.getBoundingClientRect(); gsap.to(b, { x: (e.clientX - r.left - r.width / 2) * .35, y: (e.clientY - r.top - r.height / 2) * .35, duration: .3 }); });
    b.addEventListener('pointerleave', () => gsap.to(b, { x: 0, y: 0, duration: .5, ease: 'elastic.out(1,.4)' }));
  });

  /* ---------- Hero: word mask + logo that flies into the header ---------- */
  const hero = document.getElementById('hero'), hdr = document.getElementById('siteHeader');
  if (hero) {
    const word = document.getElementById('maskWord'), copy = hero.querySelector('.hero-copy'), hint = hero.querySelector('.hero-hint');
    const cover = hero.querySelector('.hero-cover'), video = hero.querySelector('.hero-video'), logo = document.getElementById('heroLogo'), wrap = document.getElementById('heroLogoWrap'), hdrLogo = hdr.querySelector('.brand img');
    const shade = document.createElement('div'); shade.className = 'hero-shade'; hero.querySelector('.hero-pin').insertBefore(shade, copy);
    let floatTw = null;
    function placeLogo() { // sit the logo just left of the word, aligned to its cap height
      const narrow = innerWidth < 700; word.setAttribute('x', narrow ? '50%' : '55%'); word.setAttribute('y', narrow ? '56%' : '52%');
      const b = word.getBBox(), gap = b.height * .14, w = narrow ? b.height * 1.1 : b.height * .82;
      if (narrow) { wrap.dataset.left = b.x + b.width / 2 - w / 2; wrap.dataset.top = b.y - w - gap; }
      else { wrap.dataset.left = b.x - w - gap; wrap.dataset.top = b.y + b.height * .1; }
      wrap.dataset.w = w;
      if (!hero._flying) gsap.set(wrap, { left: +wrap.dataset.left, top: +wrap.dataset.top, width: w });
    }
    document.fonts.ready.then(() => { placeLogo(); logo.classList.add('in');
      if (!reduce) floatTw = gsap.to(logo, { y: -10, rotate: -6, duration: 2.2, yoyo: true, repeat: -1, ease: 'sine.inOut' }); });
    addEventListener('resize', () => { placeLogo(); ScrollTrigger.refresh(); });
    gsap.set(hdrLogo, { opacity: 0 });
    const tl = gsap.timeline({ scrollTrigger: { trigger: hero, start: 'top top', end: 'bottom bottom', scrub: 0.5, invalidateOnRefresh: true } });
    tl.to(word, { scale: 14, ease: 'power2.in', duration: 1 }, 0)
      .fromTo(video, { scale: 1.18 }, { scale: 1, ease: 'none', duration: 1 }, 0)
      .to(hint, { opacity: 0, duration: .15 }, 0)
      // logo: fly from beside the word into the header brand slot
      .fromTo(wrap, { left: () => +wrap.dataset.left, top: () => +wrap.dataset.top, width: () => +wrap.dataset.w },
        { left: () => hdrLogo.getBoundingClientRect().left, top: () => hdrLogo.getBoundingClientRect().top, width: () => hdrLogo.getBoundingClientRect().width, ease: 'power2.inOut', duration: .45, immediateRender: false }, 0)
      .to(logo, { y: 0, rotate: 0, duration: .2, onStart: () => floatTw && floatTw.pause() }, 0)
      .to(wrap, { opacity: 0, duration: .03 }, .45)
      .to(hdrLogo, { opacity: 1, duration: .03 }, .47)
      .to(cover, { opacity: 0, ease: 'power1.inOut', duration: .35 }, .35)
      .to(shade, { opacity: 1, duration: .3 }, .55)
      .to(copy, { opacity: 1, y: 0, duration: .3 }, .62);
    if (reduce) { gsap.set(cover, { opacity: 0 }); gsap.set([shade, copy], { opacity: 1, y: 0 }); gsap.set(hdrLogo, { opacity: 1 }); }
  } else gsap.set(hdr.querySelector('.brand img'), { opacity: 1 });

  /* ---------- Floor plan → exact logo (pixels sampled from logo.webp). Faster timings. ---------- */
  const plan = document.getElementById('plan');
  if (plan) {
    const cv = document.getElementById('planCanvas'), ctx = cv.getContext('2d'), hud = document.getElementById('hudLine'), tip = document.getElementById('planTip');
    let W, H, booths = [], t0 = 0, prog = 0, raf, mouse = { x: -1e4, y: -1e4 }, hover = null, logoPts = [], logoImg = new Image(), logoReady = false, running = false;
    const T_DRAW = 1100, T_HOLD = 200, T_FLY = 900;
    const names = ['Merz', 'Galderma', 'Dermacity', "L'Oréal", 'AME', 'Allergan', 'Avalon', 'Dermazone', 'Medica', 'Curestem', 'Imdad', 'Leader', 'Amjad', 'Ego', 'Teoxane', 'Lilly', 'Hikma', 'Ducray'];
    logoImg.src = INFOTECHS.logo;
    logoImg.onload = () => { const o = document.createElement('canvas'); o.width = logoImg.width; o.height = logoImg.height; const oc = o.getContext('2d'); oc.drawImage(logoImg, 0, 0);
      const d = oc.getImageData(0, 0, o.width, o.height).data;
      for (let y = 0; y < o.height; y += 2) for (let x = 0; x < o.width; x += 2) { const i = (y * o.width + x) * 4; if (d[i + 3] > 120) logoPts.push({ x, y, r: d[i], g: d[i + 1], b: d[i + 2] }); }
      logoReady = true; build(); };
    function size() { W = plan.clientWidth; H = plan.clientHeight; const dpr = Math.min(devicePixelRatio || 1, 2); cv.width = W * dpr; cv.height = H * dpr; ctx.setTransform(dpr, 0, 0, dpr, 0, 0); build(); }
    function build() {
      booths = [];
      const cols = W < 700 ? 6 : 10, rows = W < 700 ? 7 : 6, cw = Math.min(W * .78 / cols, 92), ch = Math.min(H * .42 / rows, 64), ox = (W - cols * cw) / 2, oy = H * .12;
      let i = 0;
      for (let r = 0; r < rows; r++) for (let c = 0; c < cols; c++) {
        if (c === Math.floor(cols / 2) - 1 && r !== 0 && r !== rows - 1) continue;
        if (r === Math.floor(rows / 2) && c % 3 !== 0) continue;
        const big = (r === 0 || r === rows - 1) && c % 4 === 0;
        booths.push({ x: ox + c * cw + 4, y: oy + r * ch + 4, w: cw - 8, h: ch - 8, big, id: 'A' + String(i + 1).padStart(2, '0'), name: names[i % names.length] }); i++;
      }
      if (!logoReady) return;
      const S = Math.min(W, H) * .0065, lx = W / 2 - logoImg.width * S / 2, ly = H * .40 - logoImg.height * S / 2;
      booths.forEach((b, k) => { const p = logoPts[Math.floor(k * logoPts.length / booths.length)]; b.tx = lx + p.x * S; b.ty = ly + p.y * S; b.ts = S * 2.4; b.col = `rgba(${p.r},${p.g},${p.b},`; });
      plan._logo = { S, lx, ly };
    }
    function frame(now) {
      if (!t0) t0 = now;
      const el = reduce ? 1e9 : prog * (T_DRAW + T_HOLD + T_FLY);
      ctx.clearRect(0, 0, W, H);
      ctx.save(); ctx.strokeStyle = 'rgba(120,110,190,.14)'; ctx.lineWidth = 1;
      const g = 48, dx = (mouse.x - W / 2) / 80, dy = (mouse.y - H / 2) / 80;
      for (let x = dx % g; x < W; x += g) { ctx.beginPath(); ctx.moveTo(x, 0); ctx.lineTo(x, H); ctx.stroke(); }
      for (let y = dy % g; y < H; y += g) { ctx.beginPath(); ctx.moveTo(0, y); ctx.lineTo(W, y); ctx.stroke(); }
      ctx.restore();
      const drawP = clamp(el / T_DRAW, 0, 1), flyP = clamp((el - T_DRAW - T_HOLD) / T_FLY, 0, 1), fly = ease(flyP);
      const P = INFOTECHS.plan || {}; hud.innerHTML = el < T_DRAW + T_HOLD ? `${P.drawing || 'DRAWING FLOOR PLAN'} <b>${String(Math.round(drawP * 100)).padStart(2, '0')}%</b>` : flyP < 1 ? `${P.assembling || 'ASSEMBLING'} <b>${Math.round(fly * 100)}%</b>` : `${P.ready || 'READY'} · <b>${booths.length} ${P.booths || 'BOOTHS'}</b>`;
      if (!booths.length) { if (running) { cancelAnimationFrame(raf); raf = requestAnimationFrame(frame); } return; }
      const bx = booths[0].x - 24, by = booths[0].y - 24, bw = Math.max(...booths.map(b => b.x + b.w)) - bx + 24, bh = Math.max(...booths.map(b => b.y + b.h)) - by + 24;
      if (fly < 1) {
        ctx.save(); ctx.globalAlpha = 1 - fly;
        ctx.setLineDash([bw * 2 + bh * 2, 1e6]); ctx.lineDashOffset = -(1 - clamp(drawP / .35, 0, 1)) * (bw * 2 + bh * 2);
        ctx.strokeStyle = 'rgba(88,199,238,.9)'; ctx.lineWidth = 1.5; ctx.strokeRect(bx, by, bw, bh); ctx.setLineDash([]);
        ctx.fillStyle = 'rgba(143,135,196,.9)'; ctx.font = '11px JetBrains Mono, monospace'; ctx.textAlign = 'center';
        if (drawP > .35) { ctx.fillText('120 m', bx + bw / 2, by - 10); ctx.save(); ctx.translate(bx - 12, by + bh / 2); ctx.rotate(-Math.PI / 2); ctx.fillText('64 m', 0, 0); ctx.restore(); ctx.fillText('ENTRANCE', bx + bw / 2, by + bh + 18); }
        if (drawP > .5) { ctx.strokeStyle = 'rgba(88,199,238,.7)'; ctx.beginPath(); ctx.moveTo(bx + bw / 2, by + bh + 30); ctx.lineTo(bx + bw / 2, by + bh + 4); ctx.stroke(); }
        ctx.restore();
      }
      const n = booths.length;
      booths.forEach((b, k) => {
        const appear = clamp((drawP * n * 1.15 - k) / 4, 0, 1); if (appear <= 0) return;
        const x = b.x + (b.tx - b.w / 2 - b.x) * fly, y = b.y + (b.ty - b.h / 2 - b.y) * fly, w = b.w + (b.ts - b.w) * fly, h = b.h + (b.ts - b.h) * fly;
        const base = b.big ? 'rgba(88,199,238,' : 'rgba(122,70,182,', colour = fly < .5 ? base : b.col, hov = hover === b && fly < .5;
        ctx.save(); ctx.globalAlpha = appear * (1 - clamp((flyP - .7) / .3, 0, 1));
        ctx.fillStyle = colour + (hov ? .75 : .18 + fly * .72) + ')'; ctx.strokeStyle = colour + '.95)'; ctx.lineWidth = hov ? 2 : 1;
        ctx.beginPath(); ctx.roundRect(x, y, w, h, 3 + fly * (b.ts / 2 - 3)); ctx.fill(); ctx.stroke();
        if (fly < .3 && w > 40) { ctx.globalAlpha = appear * (1 - fly / .3); ctx.fillStyle = 'rgba(230,226,255,.85)'; ctx.font = '10px JetBrains Mono, monospace'; ctx.textAlign = 'left'; ctx.fillText(b.id, x + 6, y + 14); if (b.big) { ctx.fillStyle = 'rgba(88,199,238,.9)'; ctx.fillText('SPONSOR', x + 6, y + h - 7); } }
        ctx.restore();
      });
      if (drawP > .6 && fly < 1) {
        ctx.save(); ctx.globalAlpha = (1 - fly) * clamp((drawP - .6) / .4, 0, 1);
        const mid = booths[0].y + (booths[n - 1].y - booths[0].y) / 2 + 10, left = bx + 14, right = bx + bw - 14, p = new Path2D();
        p.moveTo(W / 2, by + bh + 30); p.lineTo(W / 2, mid); p.lineTo(left, mid); p.lineTo(left, by + 14); p.lineTo(right, by + 14); p.lineTo(right, mid); p.lineTo(W / 2, mid);
        ctx.setLineDash([6, 8]); ctx.lineDashOffset = -el / 40; ctx.strokeStyle = 'rgba(255,255,255,.55)'; ctx.lineWidth = 1.5; ctx.stroke(p); ctx.restore();
      }
      if (flyP > .6 && plan._logo) {
        const a = clamp((flyP - .6) / .4, 0, 1), { S, lx, ly } = plan._logo;
        ctx.save(); ctx.globalAlpha = a; ctx.imageSmoothingEnabled = true; ctx.imageSmoothingQuality = 'high';
        ctx.shadowColor = 'rgba(88,199,238,.6)'; ctx.shadowBlur = 40;
        ctx.drawImage(logoImg, lx, ly, logoImg.width * S, logoImg.height * S); ctx.restore();
        plan.classList.toggle('formed', a > .9);
      } else plan.classList.remove('formed');
      if (running) { cancelAnimationFrame(raf); raf = requestAnimationFrame(frame); }
    }
    cv.addEventListener('pointermove', e => {
      const r = cv.getBoundingClientRect(); mouse.x = e.clientX - r.left; mouse.y = e.clientY - r.top;
      hover = booths.find(b => mouse.x > b.x && mouse.x < b.x + b.w && mouse.y > b.y && mouse.y < b.y + b.h) || null;
      if (hover && prog < .55) { tip.textContent = `${hover.id} · ${hover.name} · ${hover.big ? '6×6 m' : '3×3 m'}`; tip.style.left = hover.x + hover.w / 2 + 'px'; tip.style.top = hover.y + 'px'; tip.classList.add('on'); } else tip.classList.remove('on');
    });
    cv.addEventListener('pointerleave', () => { mouse.x = mouse.y = -1e4; tip.classList.remove('on'); });
    addEventListener('resize', size); size();
    // The whole intro is driven by scroll: the section pins and the plan draws, then flies into the logo, as you scroll.
    plan._debug = { frame: () => frame(performance.now()), state: () => ({ prog, running, booths: booths.length, logoReady }) };
    const run = on => { cancelAnimationFrame(raf); running = on; if (on) raf = requestAnimationFrame(frame); };
    ScrollTrigger.create({ trigger: plan, start: 'top bottom', end: 'top top', onToggle: self => run(self.isActive) });
    ScrollTrigger.create({ trigger: plan, start: 'top top', end: '+=180%', pin: true, scrub: .4, onUpdate: self => { prog = self.progress; frame(performance.now()); }, onToggle: self => run(self.isActive) });
  }

  /* ---------- Header, reveals, counters, parallax ---------- */
  const stats = document.getElementById('stats');
  if (!stats) hdr.classList.add('solid');
  if (stats) ScrollTrigger.create({ trigger: stats, start: 'top 70px', end: 'max', toggleClass: { targets: hdr, className: 'solid' } });
  else hdr.classList.add('solid');
  // header turns translucent-dark while it floats over dark sections
  gsap.utils.toArray('.plan, .events-h, .final').forEach(sec => ScrollTrigger.create({ trigger: sec, start: 'top 60px', end: sec.id === 'plan' ? '+=280%' : 'bottom 60px', toggleClass: { targets: hdr, className: 'over-dark' } }));
  gsap.utils.toArray('.reveal').forEach((el, i) => gsap.to(el, { opacity: 1, y: 0, duration: .9, ease: 'power3.out', delay: (i % 4) * .08, scrollTrigger: { trigger: el, start: 'top 88%', once: true } }));
  document.querySelectorAll('[data-count]').forEach(el => ScrollTrigger.create({ trigger: el, start: 'top 90%', once: true, onEnter: () => {
    const end = +el.dataset.count, pre = el.dataset.prefix || '', start = performance.now();
    (function tick(n) { const p = clamp((n - start) / 1600, 0, 1); el.textContent = pre + Math.round(end * ease(p)).toLocaleString(); if (p < 1) requestAnimationFrame(tick); })(start);
  } }));
  const statsBg = document.getElementById('statsBg'); if (statsBg && !reduce) gsap.to(statsBg, { yPercent: 18, ease: 'none', scrollTrigger: { trigger: stats, start: 'top bottom', end: 'bottom top', scrub: true } });
  const aboutPhoto = document.querySelector('#aboutPhoto img'); if (aboutPhoto && !reduce) gsap.fromTo(aboutPhoto, { yPercent: -12 }, { yPercent: 0, ease: 'none', scrollTrigger: { trigger: '#aboutPhoto', start: 'top bottom', end: 'bottom top', scrub: true } });

  /* ---------- Services: stacking cards, each one shrinks as the next covers it ---------- */
  const cards = gsap.utils.toArray('.stack-card');
  cards.forEach((c, i) => {
    const v = c.querySelector('.card-video');
    ScrollTrigger.create({ trigger: c, start: 'top 60%', end: 'bottom 40%', onToggle: self => { if (!v) return; self.isActive ? v.play().catch(() => {}) : v.pause(); } });
    if (i < cards.length - 1 && !reduce) gsap.to(c, { scale: .92 - (cards.length - 2 - i) * .02, filter: 'brightness(.6)', ease: 'none', scrollTrigger: { trigger: cards[i + 1], start: 'top 80%', end: 'top 25%', scrub: true } });
  });

  /* ---------- Partners: belt speed follows scroll velocity ---------- */
  const track = document.getElementById('logoTrack');
  if (track) {
    track.innerHTML += track.innerHTML;
    const belt = gsap.to(track, { xPercent: -50, repeat: -1, ease: 'none', duration: 32 });
    let ts = 1;
    ScrollTrigger.create({ onUpdate: self => { const v = clamp(self.getVelocity() / 250, -6, 6); ts = 1 + Math.abs(v) * (v < 0 ? -1 : 1); gsap.to(belt, { timeScale: ts, duration: .4, overwrite: true }); gsap.to(belt, { timeScale: 1, duration: 1.2, delay: .4, overwrite: false }); } });
    track.closest('.marquee').addEventListener('pointerenter', () => belt.pause()); track.closest('.marquee').addEventListener('pointerleave', () => belt.play());
  }

  /* ---------- Events: vertical scroll drives a horizontal track ---------- */
  const evTrack = document.getElementById('evTrack'), evSec = document.querySelector('.events-h');
  if (evTrack && evSec) {
    const items = evTrack.querySelectorAll('.ev'), count = document.getElementById('evCount'), bar = document.getElementById('evBar');
    const dist = () => evTrack.scrollWidth - innerWidth;
    gsap.to(evTrack, { x: () => -dist(), ease: 'none', scrollTrigger: { trigger: evSec, start: 'top top', end: () => '+=' + dist(), pin: '.events-pin', scrub: 0.6, invalidateOnRefresh: true, anticipatePin: 1,
      onUpdate: self => { const idx = Math.min(items.length - 1, Math.round(self.progress * (items.length - 1))); count.textContent = String(idx + 1).padStart(2, '0') + ' / ' + String(items.length - 1).padStart(2, '0'); bar.style.width = (self.progress * 100) + '%'; } } });
    items.forEach(c => { c.style.opacity = 1; c.style.transform = 'none'; }); // reveals are handled by the track
  }

  /* ---------- Approach: steps activate a pinned photo ---------- */
  const steps = gsap.utils.toArray('.approach-steps .step');
  if (steps.length) {
    const imgs = document.querySelectorAll('.phase-imgs img'), k = document.getElementById('phaseK'), t = document.getElementById('phaseT');
    steps.forEach((s, i) => ScrollTrigger.create({ trigger: s, start: 'top 55%', end: 'bottom 55%', onToggle: self => { if (!self.isActive) return;
      steps.forEach(x => x.classList.toggle('active', x === s)); imgs.forEach((im, j) => im.classList.toggle('on', j === i));
      k.textContent = s.querySelector('.k').textContent; t.textContent = s.querySelector('h3').textContent; } }));
    steps[0].classList.add('active');
  }


  /* ---------- Gallery: columns drift at different speeds ---------- */
  gsap.utils.toArray('.gcol').forEach(col => { if (reduce) return; const sp = +col.dataset.speed || 1;
    gsap.fromTo(col, { y: () => (sp - 1) * 260 }, { y: () => -(sp - 1) * 260 - (col.scrollHeight - col.parentElement.clientHeight) * (sp > 1 ? 1 : .6), ease: 'none', scrollTrigger: { trigger: '.gallery', start: 'top bottom', end: 'bottom top', scrub: true, invalidateOnRefresh: true } }); });

  /* ---------- About: words light up as you scroll ---------- */
  const words = document.getElementById('aboutWords');
  if (words) {
    words.innerHTML = words.textContent.split(' ').map(w => `<span class="w">${w}</span>`).join(' ');
    const ws = words.querySelectorAll('.w');
    ScrollTrigger.create({ trigger: words, start: 'top 80%', end: 'bottom 45%', scrub: true, onUpdate: self => { const n = Math.round(self.progress * ws.length); ws.forEach((w, i) => w.classList.toggle('on', i < n)); } });
    if (reduce) ws.forEach(w => w.classList.add('on'));
  }

  /* ---------- CTA: booths drift toward the cursor ---------- */
  const cta = document.getElementById('ctaCanvas');
  if (cta && !reduce) {
    const c = cta.getContext('2d'); let W, H, pts = [], m = { x: -1e4, y: -1e4 }, on = false;
    const sz = () => { W = cta.clientWidth; H = cta.clientHeight; cta.width = W; cta.height = H; pts = Array.from({ length: Math.round(W / 26) }, (_, i) => ({ x: (i + .5) * 26, y: H * .2 + Math.random() * H * .6, bx: 0, by: 0 })); };
    sz(); addEventListener('resize', sz);
    cta.parentElement.addEventListener('pointermove', e => { const r = cta.getBoundingClientRect(); m.x = e.clientX - r.left; m.y = e.clientY - r.top; });
    cta.parentElement.addEventListener('pointerleave', () => { m.x = m.y = -1e4; });
    ScrollTrigger.create({ trigger: cta, start: 'top bottom', end: 'bottom top', onToggle: s => { on = s.isActive; if (on) requestAnimationFrame(draw); } });
    function draw() { if (!on) return; c.clearRect(0, 0, W, H);
      pts.forEach(p => { const dx = m.x - p.x, dy = m.y - p.y, d = Math.hypot(dx, dy), f = d < 220 ? (1 - d / 220) : 0; p.bx += (dx * f * .12 - p.bx) * .08; p.by += (dy * f * .12 - p.by) * .08;
        c.fillStyle = `rgba(88,199,238,${.08 + f * .6})`; c.beginPath(); c.roundRect(p.x + p.bx - 6, p.y + p.by - 4, 12, 8, 2); c.fill(); });
      requestAnimationFrame(draw); }
  }

  /* ---------- Services index: a photo follows the cursor along the rows ---------- */
  const sFloat = document.getElementById('sFloat');
  if (sFloat) {
    const im = sFloat.querySelector('img'), rows = gsap.utils.toArray('.srow');
    rows.forEach((r, i) => ScrollTrigger.create({ trigger: r, start: 'top 94%', once: true, onEnter: () => setTimeout(() => r.classList.add('in'), (i % 5) * 70) }));
    if (!reduce && matchMedia('(hover:hover)').matches) {
      const qx = gsap.quickTo(sFloat, 'x', { duration: .55, ease: 'power3' }), qy = gsap.quickTo(sFloat, 'y', { duration: .55, ease: 'power3' }); let lx = 0;
      rows.forEach(r => {
        r.addEventListener('pointerenter', () => { im.src = r.dataset.img; gsap.to(sFloat, { opacity: 1, scale: 1, duration: .45, ease: 'power3.out', overwrite: true }); });
        r.addEventListener('pointerleave', () => gsap.to(sFloat, { opacity: 0, scale: .6, duration: .3, overwrite: true }));
        r.addEventListener('pointermove', e => { qx(RTL ? e.clientX - 304 : e.clientX + 24); qy(e.clientY - 95); gsap.to(sFloat, { rotation: clamp((e.clientX - lx) * .35, -9, 9), duration: .5 }); lx = e.clientX; });
      });
    }
  }

  /* ---------- About pillars: clip reveal, parallax photo, cursor spotlight + tilt ---------- */
  document.querySelectorAll('.pillar').forEach(p => {
    const spot = document.createElement('i'); spot.className = 'spot'; p.appendChild(spot);
    const img = p.querySelector('.pillar-img img');
    ScrollTrigger.create({ trigger: p, start: 'top 85%', once: true, onEnter: () => p.classList.add('in') });
    if (reduce) { p.classList.add('in'); return; }
    gsap.fromTo(img, { yPercent: -16 }, { yPercent: 0, ease: 'none', scrollTrigger: { trigger: p, start: 'top bottom', end: 'bottom top', scrub: true } });
    p.addEventListener('pointermove', e => { const r = p.getBoundingClientRect(), x = (e.clientX - r.left) / r.width, y = (e.clientY - r.top) / r.height;
      p.style.setProperty('--mx', x * 100 + '%'); p.style.setProperty('--my', y * 100 + '%');
      gsap.to(p, { rotateY: (x - .5) * 7, rotateX: -(y - .5) * 7, y: -6, transformPerspective: 900, duration: .5, ease: 'power2.out' }); });
    p.addEventListener('pointerleave', () => gsap.to(p, { rotateX: 0, rotateY: 0, y: 0, duration: .8, ease: 'power3.out' }));
  });

  /* ---------- Team + "Why Infotechs": staggered entrance, spotlight follows the cursor on the grid ---------- */
  const teamRow = document.querySelector('.team-row');
  if (teamRow) ScrollTrigger.create({ trigger: teamRow, start: 'top 85%', once: true, onEnter: () => teamRow.querySelectorAll('.member').forEach(m => m.classList.add('in')) });
  const vg = document.getElementById('valuesGrid');
  if (vg) {
    ScrollTrigger.create({ trigger: vg, start: 'top 85%', once: true, onEnter: () => vg.querySelectorAll('.value').forEach(v => v.classList.add('in')) });
    vg.addEventListener('pointermove', e => { const r = vg.getBoundingClientRect(); vg.style.setProperty('--mx', e.clientX - r.left + 'px'); vg.style.setProperty('--my', e.clientY - r.top + 'px'); });
    vg.addEventListener('pointerleave', () => { vg.style.setProperty('--mx', '-999px'); vg.style.setProperty('--my', '-999px'); });
  }

  /* ---------- Events archive: filter chips + staggered grid ---------- */
  const evGrid = document.getElementById('evGrid');
  if (evGrid) {
    const cells = gsap.utils.toArray('.ev-cell');
    const show = () => cells.filter(c => !c.classList.contains('hide')).forEach((c, i) => { c.classList.remove('in'); void c.offsetWidth; c.style.animationDelay = (i % 8) * 60 + 'ms'; c.classList.add('in'); });
    ScrollTrigger.create({ trigger: evGrid, start: 'top 90%', once: true, onEnter: show });
    document.querySelectorAll('#evFilters .chip').forEach(ch => ch.addEventListener('click', () => {
      document.querySelectorAll('#evFilters .chip').forEach(x => x.classList.toggle('on', x === ch));
      cells.forEach(c => c.classList.toggle('hide', ch.dataset.f !== '*' && c.dataset.type !== ch.dataset.f)); show(); ScrollTrigger.refresh(); }));
  }

  /* ---------- Gallery page: mosaic with velocity skew, cursor label, strip, lightbox ---------- */
  const lb = document.getElementById('lightbox');
  if (lb) {
    const tiles = gsap.utils.toArray('.tile'), lbImg = lb.querySelector('img'), cnt = document.getElementById('lbCount'), seen = document.getElementById('galSeen'), mosaic = document.getElementById('mosaic'); let cur = -1;
    tiles.forEach((t, i) => ScrollTrigger.create({ trigger: t, start: 'top 92%', once: true, onEnter: () => { setTimeout(() => t.classList.add('in'), (i % 4) * 70); if (seen) seen.textContent = String(Math.max(+seen.textContent, i + 1)).padStart(3, '0'); } }));
    if (!reduce) {
      ScrollTrigger.create({ onUpdate: self => { const v = clamp(self.getVelocity() / 900, -4, 4); gsap.to(mosaic, { skewY: v, duration: .5, overwrite: true }); gsap.to(mosaic, { skewY: 0, duration: 1, delay: .3, overwrite: false, ease: 'power3.out' }); } });
      tiles.forEach(t => gsap.to(t.querySelector('img'), { yPercent: 0, ease: 'none' }));
      const strip = document.getElementById('galStrip'); if (strip) { strip.innerHTML += strip.innerHTML; const w = strip.scrollWidth / 2; const belt = gsap.to(strip, { x: RTL ? w : -w, duration: 40, ease: 'none', repeat: -1 }); ScrollTrigger.create({ onUpdate: self => { gsap.to(belt, { timeScale: 1 + Math.min(Math.abs(self.getVelocity()) / 300, 6), duration: .3, overwrite: true }); gsap.to(belt, { timeScale: 1, duration: 1, delay: .3 }); } }); }
      const gc = document.getElementById('galCursor');
      if (gc && matchMedia('(hover:hover)').matches) { const qx = gsap.quickTo(gc, 'x', { duration: .3, ease: 'power3' }), qy = gsap.quickTo(gc, 'y', { duration: .3, ease: 'power3' });
        mosaic.addEventListener('pointermove', e => { qx(e.clientX); qy(e.clientY); });
        mosaic.addEventListener('pointerenter', () => gsap.to(gc, { opacity: 1, scale: 1, duration: .3 })); mosaic.addEventListener('pointerleave', () => gsap.to(gc, { opacity: 0, scale: 0, duration: .3 })); }
    } else tiles.forEach(t => t.classList.add('in'));
    const open = i => { cur = (i + tiles.length) % tiles.length; lbImg.src = tiles[cur].querySelector('a').href; cnt.textContent = String(cur + 1).padStart(3, '0') + ' / ' + tiles.length; lb.hidden = false; lenis && lenis.stop(); };
    const close = () => { lb.hidden = true; lenis && lenis.start(); };
    tiles.forEach((t, i) => t.querySelector('a').addEventListener('click', e => { e.preventDefault(); open(i); }));
    document.getElementById('lbClose').addEventListener('click', close);
    document.getElementById('lbPrev').addEventListener('click', () => open(cur - 1)); document.getElementById('lbNext').addEventListener('click', () => open(cur + 1));
    lb.addEventListener('click', e => { if (e.target === lb) close(); });
    addEventListener('keydown', e => { if (lb.hidden) return; if (e.key === 'Escape') close(); if (e.key === 'ArrowRight') open(cur + 1); if (e.key === 'ArrowLeft') open(cur - 1); });
  }

  /* ---------- Contacts: booking desk (grid canvas, Riyadh clock, copy buttons, 3-step brief) ---------- */
  const desk = document.getElementById('deskCanvas');
  if (desk) {
    const c = desk.getContext('2d'); let W, H, m = { x: -1e4, y: -1e4 }, t = 0;
    const sz = () => { W = desk.clientWidth; H = desk.clientHeight; desk.width = W; desk.height = H; }; sz(); addEventListener('resize', sz);
    desk.parentElement.addEventListener('pointermove', e => { const r = desk.getBoundingClientRect(); m.x = e.clientX - r.left; m.y = e.clientY - r.top; });
    (function draw() { t += .01; c.clearRect(0, 0, W, H); const g = 44;
      for (let x = 0; x < W + g; x += g) for (let y = 0; y < H + g; y += g) { const d = Math.hypot(m.x - x, m.y - y), f = Math.max(0, 1 - d / 260), s = 2 + f * 10 + Math.sin(t * 2 + x * .02 + y * .02) * .6;
        c.fillStyle = `rgba(88,199,238,${.08 + f * .7})`; c.beginPath(); c.roundRect(x - s / 2, y - s / 2, s, s, 2); c.fill(); }
      if (!reduce) requestAnimationFrame(draw); })();
    const C = window.INFOTECHS_C || {};
    const ct = document.getElementById('clockTime'), cs = document.getElementById('clockState'), cw = document.getElementById('riyadhClock');
    const tick = () => { const d = new Date(Date.now() + (3 * 60 + new Date().getTimezoneOffset()) * 60000); const h = d.getHours(), day = d.getDay();
      ct.textContent = String(h).padStart(2, '0') + ':' + String(d.getMinutes()).padStart(2, '0'); const open = day !== 5 && day !== 6 && h >= 9 && h < 18;
      cs.textContent = open ? C.open : C.closed; cw.classList.toggle('closed', !open); }; tick(); setInterval(tick, 30000);
    document.querySelectorAll('.copy').forEach(b => b.addEventListener('click', () => { navigator.clipboard.writeText(b.dataset.copy).then(() => { const o = b.textContent; b.textContent = C.copied; b.classList.add('done'); setTimeout(() => { b.textContent = o; b.classList.remove('done'); }, 1500); }).catch(() => {}); }));
    document.querySelectorAll('.coord').forEach((el, i) => setTimeout(() => el.classList.add('in'), 300 + i * 140));
    const steps = gsap.utils.toArray('.bstep'), bar = document.getElementById('briefBar'), lab = document.getElementById('briefStep'), si = document.getElementById('sizeIn'), so = document.getElementById('sizeOut'), sh = document.getElementById('sizeHint'); let st = 0;
    const go = n => { st = clamp(n, 0, steps.length - 1); steps.forEach((s, i) => s.classList.toggle('on', i === st)); bar.style.width = ((st + 1) / steps.length * 100) + '%'; lab.textContent = '0' + (st + 1) + ' / 0' + steps.length;
      if (st === 2) { const ty = document.querySelector('[name=type]:checked'); document.getElementById('briefSum').textContent = (C.sum || '%s · %s').replace('%s', ty ? ty.value : '—').replace('%s', (+si.value).toLocaleString()); document.getElementById('briefSubject').value = 'Event brief: ' + (ty ? ty.value : '') + ' · ' + si.value + ' attendees'; } };
    document.querySelectorAll('[data-next]').forEach(b => b.addEventListener('click', () => { if (st === 0 && !document.querySelector('[name=type]:checked')) { document.getElementById('pickType').animate([{ transform: 'translateX(-6px)' }, { transform: 'translateX(6px)' }, { transform: 'none' }], 240); return; } go(st + 1); }));
    document.querySelectorAll('[data-prev]').forEach(b => b.addEventListener('click', () => go(st - 1)));
    const upd = () => { const n = +si.value; so.textContent = n.toLocaleString(); sh.textContent = (C.booths || '≈ %d booths · %s').replace('%d', Math.round(n / 55)).replace('%s', n < 400 ? C.hallS : n < 1500 ? C.hallM : C.hallL); }; si.addEventListener('input', upd); upd();
  }

  /* ---------- Join: marquee, collage parallax, role → form, progress ring, CV drop zone ---------- */
  const jm = document.getElementById('joinMarquee');
  if (jm && !reduce) { const w = jm.scrollWidth / 2; gsap.to(jm, { x: RTL ? w : -w, duration: 36, ease: 'none', repeat: -1 }); }
  const collage = document.getElementById('collage');
  if (collage && !reduce) collage.querySelectorAll('img').forEach(im => gsap.fromTo(im, { y: () => 40 * +im.dataset.depth }, { y: () => -60 * +im.dataset.depth, ease: 'none', scrollTrigger: { trigger: collage, start: 'top bottom', end: 'bottom top', scrub: true } }));
  const applyForm = document.getElementById('applyForm');
  if (applyForm) {
    const sel = document.getElementById('positionSel'), title = document.getElementById('applyTitle');
    document.querySelectorAll('.role').forEach(r => { const pick = () => { sel.value = r.dataset.role; sel.dispatchEvent(new Event('change')); lenis ? lenis.scrollTo('#apply', { offset: -80 }) : document.getElementById('apply').scrollIntoView({ behavior: 'smooth' }); setTimeout(() => applyForm.querySelector('[name=first]').focus({ preventScroll: true }), 900); };
      r.addEventListener('click', pick); r.addEventListener('keydown', e => { if (e.key === 'Enter') pick(); }); });
    sel.addEventListener('change', () => { if (sel.value) title.textContent = sel.value; });
    const req = applyForm.querySelectorAll('[required]'), ring = document.getElementById('ringFill'), pct = document.getElementById('ringPct');
    const prog = () => { let n = 0; req.forEach(el => { if (el.type === 'file' ? el.files.length : el.value.trim()) n++; }); const p = n / req.length; ring.style.strokeDashoffset = 213.6 * (1 - p); pct.textContent = Math.round(p * 100) + '%'; };
    applyForm.addEventListener('input', prog); applyForm.addEventListener('change', prog); prog();
    const drop = document.getElementById('drop'), cv = document.getElementById('cvInput'), dl = document.getElementById('dropLabel'), dl0 = dl.textContent;
    ['dragenter', 'dragover'].forEach(ev => drop.addEventListener(ev, e => { e.preventDefault(); drop.classList.add('over'); }));
    ['dragleave', 'drop'].forEach(ev => drop.addEventListener(ev, () => drop.classList.remove('over')));
    cv.addEventListener('change', () => { const f = cv.files[0]; if (f) { dl.textContent = f.name + ' · ' + (f.size / 1024 / 1024).toFixed(1) + ' MB'; drop.classList.add('has'); } else { dl.textContent = dl0; drop.classList.remove('has'); } prog(); });
  }

  /* ---------- Events modal + tilt ---------- */
  const modal = document.getElementById('evModal');
  function openEv(d) {
    document.getElementById('mImg').src = d.img; document.getElementById('mTag').textContent = d.type; document.getElementById('mTitle').textContent = d.title;
    document.getElementById('mDl').innerHTML = [[L.date || 'Date', d.date], [L.venue || 'Venue', d.venue], [L.scale || 'Scale', d.scale]].filter(x => x[1]).map(x => `<dt>${x[0]}</dt><dd>${x[1]}</dd>`).join('');
    document.getElementById('mDesc').textContent = d.desc; document.getElementById('mLink').href = d.link;
    modal.hidden = false; requestAnimationFrame(() => modal.classList.add('open')); lenis && lenis.stop();
  }
  function closeEv() { modal.classList.remove('open'); setTimeout(() => { modal.hidden = true; }, 300); lenis && lenis.start(); }
  document.querySelectorAll('.ev[data-title]').forEach(c => { c.addEventListener('click', () => openEv(c.dataset)); c.addEventListener('keydown', k => { if (k.key === 'Enter') openEv(c.dataset); });
    if (!reduce) { c.addEventListener('pointermove', e => { const r = c.getBoundingClientRect(), x = (e.clientX - r.left) / r.width - .5, y = (e.clientY - r.top) / r.height - .5; c.style.transform = `translateY(-6px) rotateX(${-y * 8}deg) rotateY(${x * 8}deg)`; }); c.addEventListener('pointerleave', () => { c.style.transform = ''; }); } });
  document.getElementById('mClose').addEventListener('click', closeEv);
  modal.addEventListener('click', e => { if (e.target === modal) closeEv(); });
  addEventListener('keydown', e => { if (e.key === 'Escape' && !modal.hidden) closeEv(); });

  addEventListener('load', () => { if (document.activeElement && document.activeElement.closest('.events-track')) document.activeElement.blur(); ScrollTrigger.refresh(); });
})();

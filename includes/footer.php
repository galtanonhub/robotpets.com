</main>
<footer class="site-footer">
  <div class="container" style="padding:1.6rem 0;border-bottom:1px solid rgba(255,255,255,0.08);margin-bottom:1.6rem;">
    <h4 style="margin:0 0 .25rem;">Get new companions &amp; guides in your inbox</h4>
    <p style="font-size:.85rem;opacity:.7;margin:0 0 .8rem;">Occasional updates. No spam, unsubscribe anytime.</p>
    <form id="newsletterForm" style="display:flex;gap:.5rem;flex-wrap:wrap;max-width:440px;">
      <input type="text" name="website" tabindex="-1" autocomplete="off" aria-hidden="true" style="display:none;">
      <input type="email" name="email" required placeholder="you@example.com" aria-label="Email address" style="flex:1;min-width:200px;padding:.7rem .9rem;border-radius:8px;border:1px solid rgba(255,255,255,0.18);background:rgba(255,255,255,0.06);color:#fff;font-size:.95rem;">
      <button type="submit" class="btn btn-primary">Subscribe</button>
    </form>
    <p id="nlStatus" role="status" aria-live="polite" style="font-size:.85rem;margin:.6rem 0 0;min-height:1.1em;"></p>
  </div>
  <div class="container footer-grid">
    <div>
      <div class="logo">ROBOT<span>PETS</span></div>
      <p>Lifelike robotic companions, delivered to your door. No feeding. No fur. All love.</p>
    </div>
    <div>
      <h4>Shop</h4>
      <a href="/shop.php?category=robot-dogs">Robot Dogs</a>
      <a href="/shop.php?category=robot-cats">Robot Cats</a>
      <a href="/shop.php?category=robot-birds-exotics">Birds &amp; Exotics</a>
      <a href="/shop.php?category=accessories-parts">Accessories</a>
    </div>
    <div>
      <h4>Support</h4>
      <a href="/faq.php">FAQ</a>
      <a href="/contact.php">Contact</a>
      <a href="/disclosure.php">Affiliate Disclosure</a>
      <a href="mailto:support@robotpets.com">support@robotpets.com</a>
    </div>
  </div>
  <div class="footer-bottom">
    <p style="font-size:.8rem;opacity:.75;max-width:720px;margin:0 auto 0.7rem;line-height:1.6;">RobotPets is reader-supported. When you buy through links on our site, we may earn an affiliate commission at no extra cost to you. <a href="/disclosure.php">Learn more</a>.</p>
    © <?= date('Y') ?> RobotPets.com — All rights reserved.
  </div>
</footer>
<script src="/js/main.js"></script>
<script>
(function(){
  var f = document.getElementById('newsletterForm');
  if (!f) return;
  var s = document.getElementById('nlStatus');
  f.addEventListener('submit', function(e){
    e.preventDefault();
    s.style.color = '';
    s.textContent = 'Subscribing…';
    fetch('/newsletter.php', { method: 'POST', body: new FormData(f) })
      .then(function(r){ return r.json(); })
      .then(function(j){
        if (j.ok) { f.reset(); s.textContent = j.message || 'Thanks for subscribing!'; }
        else { s.textContent = j.error || 'Something went wrong.'; }
      })
      .catch(function(){ s.textContent = 'Something went wrong. Please try again.'; });
  });
})();
</script>
</body>
</html>

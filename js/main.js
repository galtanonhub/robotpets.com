// Add-to-cart via fetch + toast feedback; hero video fallback handling.
(function () {
  // If hero.mp4 is missing or fails, the animated gradient fallback stays visible.
  const video = document.getElementById('heroVideo');
  if (video) {
    video.addEventListener('error', () => video.remove(), true);
    video.addEventListener('loadeddata', () => {
      const fb = document.getElementById('heroFallback');
      if (fb) fb.style.display = 'none';
    });
  }

  function showToast(message) {
    let toast = document.querySelector('.toast');
    if (!toast) {
      toast = document.createElement('div');
      toast.className = 'toast';
      document.body.appendChild(toast);
    }
    toast.textContent = message;
    requestAnimationFrame(() => toast.classList.add('show'));
    clearTimeout(toast._t);
    toast._t = setTimeout(() => toast.classList.remove('show'), 2400);
  }

  document.addEventListener('click', async (e) => {
    const btn = e.target.closest('.add-to-cart');
    if (!btn) return;
    e.preventDefault();
    const productId = btn.dataset.productId;
    let qty = 1;
    if (btn.dataset.qtyInput) {
      const input = document.getElementById(btn.dataset.qtyInput);
      if (input) qty = Math.max(1, Number(input.value) || 1);
    }
    btn.disabled = true;
    try {
      const res = await fetch('/cart-action.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: 'add', productId, qty })
      });
      if (!res.ok) throw new Error('Failed');
      const data = await res.json();
      const count = document.getElementById('cartCount');
      if (count) count.textContent = data.count;
      showToast('Added to cart ✓');
    } catch {
      showToast('Could not add to cart');
    } finally {
      btn.disabled = false;
    }
  });
})();

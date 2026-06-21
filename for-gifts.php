<?php
require_once __DIR__ . '/includes/functions.php';
$title = 'Robotic Pets as Gifts';
$description = 'The perfect unique gift — robotic companions for any budget, any age, any occasion. Free shipping and easy returns.';
include __DIR__ . '/includes/header.php';
?>

<section class="audience-hero">
  <div class="container">
    <p class="hero-kicker">Gift Guide</p>
    <h1>The Gift They'll Never Forget</h1>
    <p class="hero-sub">A robotic companion is one of the most personal, surprising, and lasting gifts you can give. For kids, for grandparents, for the person who says they have everything.</p>
    <div class="hero-cta">
      <a href="/shop.php" class="btn btn-primary btn-lg">Shop All Pets</a>
      <a href="/contact.php" class="btn btn-ghost btn-lg">Need Help Choosing?</a>
    </div>
  </div>
</section>

<section class="section container">
  <div class="section-head"><h2>Who Are You Shopping For?</h2></div>
  <div class="gift-grid">
    <a href="/for-kids.php" class="gift-card">
      <span class="gift-icon">🌟</span>
      <h3>A Child</h3>
      <p>Interactive, tactile, and built to last. Kids bond fast and deeply with robotic companions.</p>
      <span class="link-arrow">See options →</span>
    </a>
    <a href="/for-seniors.php" class="gift-card">
      <span class="gift-icon">🤝</span>
      <h3>A Parent or Grandparent</h3>
      <p>One of the most meaningful gifts you can give — companionship that requires no care or upkeep from them.</p>
      <span class="link-arrow">See options →</span>
    </a>
    <a href="/for-allergies.php" class="gift-card">
      <span class="gift-icon">🌿</span>
      <h3>Someone With Allergies</h3>
      <p>Finally give the pet lover in your life what they've always wanted, without the sneezing.</p>
      <span class="link-arrow">See options →</span>
    </a>
    <a href="/shop.php" class="gift-card">
      <span class="gift-icon">🤖</span>
      <h3>A Tech Enthusiast</h3>
      <p>AI personality engines, voice recognition, app integration. This isn't your grandma's toy robot.</p>
      <span class="link-arrow">Shop all →</span>
    </a>
  </div>
</section>

<section class="section bg-soft">
  <div class="container">
    <div class="section-head"><h2>Why a Robotic Pet Makes a Great Gift</h2></div>
    <div class="benefit-grid">
      <div class="benefit-card">
        <div class="benefit-icon">😲</div>
        <h3>Genuinely Surprising</h3>
        <p>Nobody expects it. And when they open the box and it responds to them for the first time, the reaction is unforgettable.</p>
      </div>
      <div class="benefit-card">
        <div class="benefit-icon">💝</div>
        <h3>Deeply Personal</h3>
        <p>You're giving someone a companion — not a gadget. It says "I thought about what would make your daily life better."</p>
      </div>
      <div class="benefit-card">
        <div class="benefit-icon">📅</div>
        <h3>Lasts for Years</h3>
        <p>Unlike most gifts that get shelved after a week, a robotic companion becomes part of daily life. It's a gift that keeps giving.</p>
      </div>
      <div class="benefit-card">
        <div class="benefit-icon">💌</div>
        <h3>Gift Messaging Included</h3>
        <p>Add a note at checkout and we'll include a handwritten gift message. Great for birthdays, holidays, or just because.</p>
      </div>
    </div>
  </div>
</section>

<section class="section container">
  <div class="section-head"><h2>Shop by Occasion</h2></div>
  <div class="occasion-grid">
    <a href="/shop.php" class="occasion-card">🎂 Birthday</a>
    <a href="/shop.php" class="occasion-card">🎄 Holiday</a>
    <a href="/shop.php" class="occasion-card">💝 Valentine's Day</a>
    <a href="/shop.php" class="occasion-card">👩 Mother's Day</a>
    <a href="/shop.php" class="occasion-card">👨 Father's Day</a>
    <a href="/shop.php" class="occasion-card">🎓 Graduation</a>
  </div>
</section>

<section class="section bg-soft">
  <div class="container">
    <div class="section-head"><h2>What Gift-Givers Say</h2></div>
    <div class="testimonial-grid">
      <div class="testimonial-card">
        <p>"I got one for my dad who lives alone. He called me an hour after he opened it and he was laughing. Said he hadn't laughed like that in months. Best gift I ever gave him."</p>
        <cite>— Marco</cite>
      </div>
      <div class="testimonial-card">
        <p>"My niece is 7 and obsessed with animals but my sister's lease doesn't allow pets. She absolutely lost her mind when she opened it. I was the best aunt in the world for a day."</p>
        <cite>— Jenna</cite>
      </div>
      <div class="testimonial-card">
        <p>"I bought one for my wife as a joke gift and she ended up loving it more than I expected. It sits on her desk and she talks to it while she works. Genuinely did not see that coming."</p>
        <cite>— Derek</cite>
      </div>
    </div>
  </div>
</section>

<section class="cta-band">
  <div class="container">
    <h2>Not sure which one to pick?</h2>
    <p style="color:var(--text-dim);margin-bottom:1.6rem;">Tell us who it's for and we'll point you to the right companion.</p>
    <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
      <a href="/contact.php" class="btn btn-primary btn-lg">Ask for a Recommendation</a>
      <a href="/shop.php" class="btn btn-ghost btn-lg">Browse All Pets</a>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>

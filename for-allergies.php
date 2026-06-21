<?php
require_once __DIR__ . '/includes/functions.php';
$title = 'Hypoallergenic Robotic Pets';
$description = 'Love pets but have allergies? Our hypoallergenic robotic companions give you all the joy of pet ownership with none of the dander.';
include __DIR__ . '/includes/header.php';
?>

<section class="audience-hero">
  <div class="container">
    <p class="hero-kicker">For Allergy Sufferers</p>
    <h1>All the Love.<br>None of the Sneezing.</h1>
    <p class="hero-sub">Pet allergies affect 1 in 3 people. Robotic companions give you the presence, warmth, and daily companionship of a pet — with zero fur, zero dander, and zero histamines.</p>
    <div class="hero-cta">
      <a href="/shop.php" class="btn btn-primary btn-lg">Browse Companions</a>
      <a href="/faq.php#allergies" class="btn btn-ghost btn-lg">Allergy FAQ</a>
    </div>
  </div>
</section>

<section class="section container">
  <div class="compare-table-wrap">
    <h2 style="text-align:center;font-family:var(--font-display);margin-bottom:2rem;">Robotic Pet vs. Real Pet</h2>
    <table class="compare-table">
      <thead>
        <tr>
          <th></th>
          <th class="col-robot"><span class="glow">Robotic Pet</span></th>
          <th class="col-real">Real Pet</th>
        </tr>
      </thead>
      <tbody>
        <tr><td>Fur &amp; dander</td><td class="yes">✓ None</td><td class="no">✗ Yes</td></tr>
        <tr><td>Allergic reactions</td><td class="yes">✓ Zero risk</td><td class="no">✗ Common</td></tr>
        <tr><td>Vet bills</td><td class="yes">✓ Never</td><td class="no">✗ Ongoing</td></tr>
        <tr><td>Feeding required</td><td class="yes">✓ No</td><td class="no">✗ Daily</td></tr>
        <tr><td>Companionship</td><td class="yes">✓ Always on</td><td class="yes">✓ Yes</td></tr>
        <tr><td>Responds to you</td><td class="yes">✓ Yes (AI-powered)</td><td class="yes">✓ Yes</td></tr>
        <tr><td>Allowed in apartments</td><td class="yes">✓ Everywhere</td><td class="no">✗ Often no</td></tr>
        <tr><td>Safe for asthma</td><td class="yes">✓ Completely</td><td class="no">✗ Risk</td></tr>
      </tbody>
    </table>
  </div>
</section>

<section class="section bg-soft">
  <div class="container">
    <div class="benefit-grid">
      <div class="benefit-card">
        <div class="benefit-icon">🌬️</div>
        <h3>Asthma Safe</h3>
        <p>No airborne allergens, no pet hair on furniture, no dander on your pillow. Safe for people with asthma, rhinitis, and severe animal allergies alike.</p>
      </div>
      <div class="benefit-card">
        <div class="benefit-icon">🧹</div>
        <h3>No Mess. Ever.</h3>
        <p>No litter boxes, no accidents, no muddy paws. Just a wipe-down when you want one — robotic companions stay clean and clean to be around.</p>
      </div>
      <div class="benefit-card">
        <div class="benefit-icon">🏡</div>
        <h3>Landlord Approved</h3>
        <p>No-pet clauses don't apply to robotic companions. Live anywhere, keep your deposit, and still have the companionship you want.</p>
      </div>
    </div>
  </div>
</section>

<section class="section bg-soft">
  <div class="container">
    <div class="section-head"><h2>From Our Customers</h2></div>
    <div class="testimonial-grid">
      <div class="testimonial-card">
        <p>"I've wanted a cat my entire life but I'm severely allergic. This is genuinely the best alternative I've found. I know it's not the same, but it scratches the itch. No pun intended."</p>
        <cite>— Diane, lifelong cat lover</cite>
      </div>
      <div class="testimonial-card">
        <p>"My husband is allergic and I'm a dog person. This was the compromise that actually worked. I got my 'dog,' he stopped sneezing. We're both happy."</p>
        <cite>— Tara</cite>
      </div>
    </div>
  </div>
</section>

<section class="section container" style="text-align:center;">
  <h2 style="font-family:var(--font-display);margin-bottom:1rem;">Ready for a pet that loves you back — without the allergy risk?</h2>
  <a href="/shop.php" class="btn btn-primary btn-lg">Shop All Companions</a>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>

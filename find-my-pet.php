<?php
require_once __DIR__ . '/includes/functions.php';
$title = 'Find My Pet';
include __DIR__ . '/includes/header.php';
?>

<section class="page-hero">
  <div class="container">
    <p class="hero-kicker">Pet Finder</p>
    <h1>Find Your Perfect Companion</h1>
    <p>Answer 4 quick questions and we'll match you with the right robotic pet.</p>
  </div>
</section>

<section class="section container">
  <div class="quiz-wrap">

    <div class="quiz-progress">
      <div class="quiz-progress-bar"><div class="quiz-progress-fill" id="progressFill"></div></div>
      <span class="quiz-progress-label" id="progressLabel">Question 1 of 4</span>
    </div>

    <form id="quizForm">

      <!-- Step 1 -->
      <div class="quiz-step active" data-step="1">
        <h2>Who is this companion for?</h2>
        <div class="quiz-options">
          <label class="quiz-option"><input type="radio" name="q1" value="self"> <span>👤 For me (adult)</span></label>
          <label class="quiz-option"><input type="radio" name="q1" value="child"> <span>🌟 A child</span></label>
          <label class="quiz-option"><input type="radio" name="q1" value="senior"> <span>🤝 A senior or elderly person</span></label>
          <label class="quiz-option"><input type="radio" name="q1" value="gift"> <span>🎁 It's a gift (not sure who for)</span></label>
        </div>
        <button type="button" class="btn btn-primary quiz-next" data-next="2">Next →</button>
      </div>

      <!-- Step 2 -->
      <div class="quiz-step" data-step="2">
        <h2>What kind of animal?</h2>
        <div class="quiz-options">
          <label class="quiz-option"><input type="radio" name="q2" value="dog"> <span>🐕 A dog — loyal, active, playful</span></label>
          <label class="quiz-option"><input type="radio" name="q2" value="cat"> <span>🐈 A cat — calm, independent, curious</span></label>
          <label class="quiz-option"><input type="radio" name="q2" value="exotic"> <span>🦜 Something exotic — bird, dino, or unusual</span></label>
          <label class="quiz-option"><input type="radio" name="q2" value="any"> <span>🎲 Surprise me — best overall pick</span></label>
        </div>
        <button type="button" class="btn btn-ghost quiz-back" data-back="1">← Back</button>
        <button type="button" class="btn btn-primary quiz-next" data-next="3">Next →</button>
      </div>

      <!-- Step 3 -->
      <div class="quiz-step" data-step="3">
        <h2>How interactive do you want it?</h2>
        <div class="quiz-options">
          <label class="quiz-option"><input type="radio" name="q3" value="calm"> <span>🌙 Calm and gentle — mostly soothing presence</span></label>
          <label class="quiz-option"><input type="radio" name="q3" value="moderate"> <span>😊 Moderately playful — responds to touch and sound</span></label>
          <label class="quiz-option"><input type="radio" name="q3" value="active"> <span>⚡ Highly interactive — learns commands, has personality</span></label>
        </div>
        <button type="button" class="btn btn-ghost quiz-back" data-back="2">← Back</button>
        <button type="button" class="btn btn-primary quiz-next" data-next="4">Next →</button>
      </div>

      <!-- Step 4 -->
      <div class="quiz-step" data-step="4">
        <h2>What's your budget?</h2>
        <div class="quiz-options">
          <label class="quiz-option"><input type="radio" name="q4" value="low"> <span>💚 Under $75 — starter companion</span></label>
          <label class="quiz-option"><input type="radio" name="q4" value="mid"> <span>💛 $75–$200 — solid mid-range</span></label>
          <label class="quiz-option"><input type="radio" name="q4" value="high"> <span>🧡 $200–$400 — premium features</span></label>
          <label class="quiz-option"><input type="radio" name="q4" value="top"> <span>❤️ No limit — give me the best</span></label>
        </div>
        <button type="button" class="btn btn-ghost quiz-back" data-back="3">← Back</button>
        <button type="button" class="btn btn-primary" id="quizSubmit">Find My Match →</button>
      </div>

    </form>

    <!-- Result -->
    <div class="quiz-result" id="quizResult" style="display:none;">
      <div class="quiz-result-icon" id="resultIcon"></div>
      <h2 id="resultTitle"></h2>
      <p id="resultDesc"></p>
      <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;margin-top:1.8rem;">
        <a href="#" class="btn btn-primary btn-lg" id="resultCta">Shop This Category →</a>
        <button type="button" class="btn btn-ghost" onclick="resetQuiz()">Start Over</button>
      </div>
    </div>

  </div>
</section>

<script>
(function() {
  var steps = document.querySelectorAll('.quiz-step');
  var fill  = document.getElementById('progressFill');
  var label = document.getElementById('progressLabel');

  function showStep(n) {
    steps.forEach(function(s) { s.classList.remove('active'); });
    document.querySelector('[data-step="' + n + '"]').classList.add('active');
    fill.style.width = ((n / 4) * 100) + '%';
    label.textContent = 'Question ' + n + ' of 4';
  }

  document.querySelectorAll('.quiz-next').forEach(function(btn) {
    btn.addEventListener('click', function() {
      var step = btn.closest('.quiz-step');
      var name = step.querySelector('input[type=radio]').name;
      if (!document.querySelector('input[name="' + name + '"]:checked')) {
        alert('Please select an option to continue.');
        return;
      }
      showStep(parseInt(btn.dataset.next));
    });
  });

  document.querySelectorAll('.quiz-back').forEach(function(btn) {
    btn.addEventListener('click', function() { showStep(parseInt(btn.dataset.back)); });
  });

  document.getElementById('quizSubmit').addEventListener('click', function() {
    var q4 = document.querySelector('input[name="q4"]:checked');
    if (!q4) { alert('Please select a budget to continue.'); return; }
    showResult();
  });

  function val(name) {
    var el = document.querySelector('input[name="' + name + '"]:checked');
    return el ? el.value : '';
  }

  function showResult() {
    var q1 = val('q1'), q2 = val('q2'), q3 = val('q3'), q4 = val('q4');
    var category = 'shop.php';
    var icon = '🤖', title = '', desc = '';

    // Logic tree
    if (q1 === 'senior' || (q1 === 'self' && q3 === 'calm')) {
      category = 'shop.php?category=robot-cats';
      icon = '🐈'; title = 'A Robotic Cat';
      desc = 'Calm, soothing, and endlessly comforting. Our robotic cats are the ideal companion for quiet moments — responsive to gentle touch with no demands in return.';
    } else if (q2 === 'dog' || (q1 === 'child' && q2 !== 'cat' && q2 !== 'exotic')) {
      category = 'shop.php?category=robot-dogs';
      icon = '🐕'; title = 'A Robotic Dog';
      desc = 'Loyal, energetic, and full of personality. Our robotic dogs love to play, respond to commands, and make fast friends with kids and adults alike.';
    } else if (q2 === 'cat') {
      category = 'shop.php?category=robot-cats';
      icon = '🐈'; title = 'A Robotic Cat';
      desc = 'Independent yet affectionate — the perfect companion that comes to you on its own terms. Our robotic cats purr, stretch, and respond beautifully to touch.';
    } else if (q2 === 'exotic') {
      category = 'shop.php?category=robot-birds-exotics';
      icon = '🦜'; title = 'A Bird or Exotic Companion';
      desc = 'Not your average pet — our exotic companions turn heads and start conversations. From robotic birds to dinosaurs, these are for the boldly curious.';
    } else if (q1 === 'gift') {
      category = 'for-gifts.php';
      icon = '🎁'; title = 'A Gift They\'ll Never Forget';
      desc = 'We\'ve got a full gift guide to help you choose the perfect companion based on who you\'re buying for. Easy returns, handwritten notes included.';
    } else if (q3 === 'active' || q4 === 'high' || q4 === 'top') {
      category = 'shop.php';
      icon = '⚡'; title = 'An AI-Enhanced Companion';
      desc = 'You\'re ready for the real thing — a pet with personality, learning ability, and a growing set of responses. Browse our full collection for premium AI companions.';
    } else {
      category = 'shop.php';
      icon = '🤖'; title = 'Browse Our Full Collection';
      desc = 'You\'ve got great taste and an open mind. Browse everything we carry and find the companion that speaks to you.';
    }

    document.getElementById('quizForm').style.display = 'none';
    document.querySelector('.quiz-progress').style.display = 'none';
    var result = document.getElementById('quizResult');
    result.style.display = 'block';
    document.getElementById('resultIcon').textContent = icon;
    document.getElementById('resultTitle').textContent = 'We Recommend: ' + title;
    document.getElementById('resultDesc').textContent = desc;
    document.getElementById('resultCta').href = '/' + category;
  }

  window.resetQuiz = function() {
    document.getElementById('quizForm').style.display = '';
    document.querySelector('.quiz-progress').style.display = '';
    document.getElementById('quizResult').style.display = 'none';
    document.querySelectorAll('input[type=radio]').forEach(function(r) { r.checked = false; });
    showStep(1);
  };
})();
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>

/* Truong Group — /am-i-too-skinny-for-a-bbl/ self-assessment.
   Client-side only: answers never leave this script. See
   Architecture/01-tecnico/02-formulario-spec.md, "La autoevaluación NO envía
   sus respuestas". */
(function () {
  var root = document.getElementById('sbbl-assessment');
  if (!root) return;

  var steps = Array.prototype.slice.call(root.querySelectorAll('.sbbl-assessment__step'));
  var resultEl = root.querySelector('[data-el="result"]');
  var progressEl = root.querySelector('[data-el="progress"]');
  var backBtn = root.querySelector('[data-back]');
  var restartBtn = root.querySelector('[data-restart]');

  if (!steps.length || !resultEl) return;

  var totalSteps = steps.length;
  var currentStep = 1;
  var started = false;

  function trackStart() {
    if (started) return;
    started = true;
    if (typeof window.gtag === 'function') {
      window.gtag('event', 'form_start', { form_name: 'am_i_too_skinny_assessment' });
    }
  }

  function showStep(n) {
    steps.forEach(function (step) {
      step.classList.toggle('is-active', parseInt(step.dataset.step, 10) === n);
    });
    resultEl.classList.remove('is-active');
    if (progressEl) progressEl.textContent = 'Question ' + n + ' of ' + totalSteps;
    if (backBtn) backBtn.hidden = n <= 1;
  }

  function showResult() {
    steps.forEach(function (step) { step.classList.remove('is-active'); });
    resultEl.classList.add('is-active');
    if (progressEl) progressEl.textContent = '';
    if (backBtn) backBtn.hidden = true;
  }

  function goNext() {
    if (currentStep >= totalSteps) {
      showResult();
      return;
    }
    currentStep += 1;
    showStep(currentStep);
  }

  steps.forEach(function (step) {
    var optionsWrap = step.querySelector('.sbbl-assessment__options');
    if (!optionsWrap) return;

    var mode = optionsWrap.dataset.select;
    var options = Array.prototype.slice.call(step.querySelectorAll('.sbbl-assessment__option'));
    var continueBtn = step.querySelector('[data-continue]');

    options.forEach(function (option) {
      option.addEventListener('click', function () {
        trackStart();

        if (mode === 'multi') {
          option.classList.toggle('is-selected');
          if (continueBtn) {
            continueBtn.disabled = !options.some(function (o) { return o.classList.contains('is-selected'); });
          }
        } else {
          options.forEach(function (o) { o.classList.remove('is-selected'); });
          option.classList.add('is-selected');
          window.setTimeout(goNext, 150);
        }
      });
    });

    if (continueBtn) {
      continueBtn.addEventListener('click', goNext);
    }
  });

  if (backBtn) {
    backBtn.addEventListener('click', function () {
      if (currentStep > 1) {
        currentStep -= 1;
        showStep(currentStep);
      }
    });
  }

  if (restartBtn) {
    restartBtn.addEventListener('click', function () {
      steps.forEach(function (step) {
        step.querySelectorAll('.sbbl-assessment__option').forEach(function (o) {
          o.classList.remove('is-selected');
        });
      });
      root.querySelectorAll('[data-continue]').forEach(function (btn) { btn.disabled = true; });
      currentStep = 1;
      showStep(currentStep);
    });
  }

  showStep(currentStep);
})();

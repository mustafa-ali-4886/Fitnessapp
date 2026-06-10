/**
 * zf-animations.js — ZeroFitness Universal Animation Library
 * ============================================================
 * Drop this single <script> tag into ANY page and it handles:
 *
 *  1. SCROLL REVEAL  — Add class="reveal" (or "reveal-stagger") to any element.
 *  2. BMI SLOT MACHINE — Build the widget once with buildBMISlot(containerEl),
 *     or let it auto-init any <div data-bmi-slot> on the page.
 *
 * No modifications needed per-page. No global name collisions.
 * ============================================================
 */

(function (global) {
  'use strict';

  /* ─────────────────────────────────────────────
   * 1. SCROLL REVEAL
   * Add  class="reveal"          → fade + rise on scroll
   *      class="reveal-stagger"  → children stagger in
   * ───────────────────────────────────────────── */
  function initScrollReveal() {
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add('visible');
            observer.unobserve(entry.target); // fire once only
          }
        });
      },
      { threshold: 0.12 }
    );

    document.querySelectorAll('.reveal, .reveal-stagger').forEach((el) =>
      observer.observe(el)
    );
  }

  /* ─────────────────────────────────────────────
   * 2. BMI SLOT MACHINE
   *
   * AUTO-INIT:  Add  data-bmi-slot  to a wrapper div.
   *             Optionally add  data-bmi-prefix="mypage"
   *             to avoid ID clashes when multiple slots exist.
   *
   * MANUAL:     ZFAnim.buildBMISlot(containerEl, prefix)
   *             ZFAnim.calcBMI(prefix)
   *             ZFAnim.setGender(gender, prefix)
   *
   * Required child IDs (auto-created if using auto-init):
   *   <prefix>-weightInput   <prefix>-heightInput
   *   <prefix>-slotWrapper   <prefix>-slotResult
   *   <prefix>-catBadge      <prefix>-bmiFoot
   *   <prefix>-scaleWrap     <prefix>-scaleDot
   *   <prefix>-gFemale       <prefix>-gMale       <prefix>-gOther
   * ───────────────────────────────────────────── */

  const DIGITS = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

  function getItemH(slotWrapper) {
    const el = slotWrapper ? slotWrapper.querySelector('.zf-digit-item') : null;
    return el ? el.getBoundingClientRect().height || 130 : 130;
  }

  function buildDigitCol(id, isDot, prefix) {
    const col = document.createElement('div');
    col.className = isDot ? 'zf-digit-col zf-dot-col' : 'zf-digit-col';

    if (isDot) {
      const dot = document.createElement('div');
      dot.className = 'zf-dot-char';
      dot.textContent = '.';
      col.appendChild(dot);
    } else {
      const strip = document.createElement('div');
      strip.className = 'zf-digit-strip';
      strip.id = prefix + 'strip_' + id;

      DIGITS.forEach((d, i) => {
        const item = document.createElement('div');
        item.className = 'zf-digit-item digit-item'; // keep 'digit-item' for CSS compat
        item.id = prefix + 'digit_' + id + '_' + i;
        item.textContent = d;
        strip.appendChild(item);
      });
      col.appendChild(strip);
    }
    return col;
  }

  /**
   * Inject the slot columns into a wrapper element.
   * @param {HTMLElement} wrapper   The element with id="<prefix>-slotWrapper"
   * @param {string}      prefix    e.g. "hp-"
   */
  function buildSlotInWrapper(wrapper, prefix) {
    wrapper.innerHTML = ''; // clear if re-called
    ['d0', 'd1', 'dot', 'd2'].forEach((id) =>
      wrapper.appendChild(buildDigitCol(id, id === 'dot', prefix))
    );
    // Set all to 0 silently after a tick (layout needs to settle)
    setTimeout(() => {
      setSlot('d0', 0, false, wrapper, prefix);
      setSlot('d1', 0, false, wrapper, prefix);
      setSlot('d2', 0, false, wrapper, prefix);
    }, 50);
  }

  function setSlot(id, digit, animate, wrapper, prefix) {
    const strip = document.getElementById(prefix + 'strip_' + id);
    if (!strip) return;

    // Highlight active digit
    DIGITS.forEach((_, i) => {
      const el = document.getElementById(prefix + 'digit_' + id + '_' + i);
      if (el) el.classList.toggle('active-digit', i === digit);
    });

    const H = getItemH(wrapper);
    const targetTop = -(digit * H);

    if (animate) {
      // spin-up illusion: jump far above, then ease into position
      const spinStart = targetTop - 2 * DIGITS.length * H - H * 3;
      strip.style.transition = 'none';
      strip.style.top = spinStart + 'px';
      requestAnimationFrame(() =>
        requestAnimationFrame(() => {
          strip.style.transition = 'top 0.6s cubic-bezier(0.22,1,0.36,1)';
          strip.style.top = targetTop + 'px';
        })
      );
    } else {
      strip.style.transition = 'none';
      strip.style.top = targetTop + 'px';
    }
  }

  /**
   * Run BMI calculation for a given prefix.
   * Reads: <prefix>-weightInput, <prefix>-heightInput
   * Writes: slot digits, <prefix>-catBadge, <prefix>-bmiFoot,
   *         <prefix>-scaleWrap, <prefix>-scaleDot, <prefix>-slotResult
   */
  function calcBMI(prefix) {
    prefix = prefix || 'hp-';
    const wrapper = document.getElementById(prefix + 'slotWrapper');
    const w = parseFloat(document.getElementById(prefix + 'weightInput').value);
    const h = parseFloat(document.getElementById(prefix + 'heightInput').value);

    if (!w || w < 1 || w > 450 || !h || h < 1 || h > 300) {
      alert('Please enter valid weight (1–450 kg) and height (1–300 cm).');
      return;
    }

    const bmi = w / Math.pow(h / 100, 2);
    const str = (Math.round(bmi * 10) / 10).toFixed(1);
    const [intPart, decPart] = str.split('.');
    const padded = intPart.padStart(2, '0');

    if (wrapper) wrapper.classList.remove('idle');

    // Stagger each digit for a "rolling" look
    setTimeout(() => setSlot('d0', parseInt(padded[0]),   true, wrapper, prefix), 0);
    setTimeout(() => setSlot('d1', parseInt(padded[1]),   true, wrapper, prefix), 90);
    setTimeout(() => setSlot('d2', parseInt(decPart[0]),  true, wrapper, prefix), 180);

    // Category badge + scale dot
    const badge  = document.getElementById(prefix + 'catBadge');
    const foot   = document.getElementById(prefix + 'bmiFoot');
    const scale  = document.getElementById(prefix + 'scaleWrap');
    const dot    = document.getElementById(prefix + 'scaleDot');
    const result = document.getElementById(prefix + 'slotResult');

    let cat, cls, tip, pct;
    if (bmi < 18.5) {
      cat = 'Underweight';   cls = 'cat-badge show cat-under';
      tip = 'Your BMI suggests you may be underweight.';
      pct = Math.max(2, (bmi / 18.5) * 25);
    } else if (bmi < 25) {
      cat = 'Normal weight'; cls = 'cat-badge show cat-normal';
      tip = 'Great! Your BMI is within the healthy range.';
      pct = 25 + ((bmi - 18.5) / 6.5) * 25;
    } else if (bmi < 30) {
      cat = 'Overweight';    cls = 'cat-badge show cat-over';
      tip = 'A balanced diet and regular exercise can help.';
      pct = 50 + ((bmi - 25) / 5) * 25;
    } else {
      cat = 'Obese';         cls = 'cat-badge show cat-obese';
      tip = 'Please consult a healthcare professional.';
      pct = Math.min(98, 75 + ((bmi - 30) / 10) * 25);
    }

    if (badge)  { badge.textContent = cat; badge.className = cls; }
    if (foot)   { foot.textContent = tip; }
    if (result) { result.classList.add('show'); }
    if (scale)  { scale.classList.add('show'); }
    if (dot)    { setTimeout(() => { dot.style.left = pct + '%'; }, 60); }
  }

  /**
   * Highlight a gender button.
   * @param {'female'|'male'|'other'} gender
   * @param {string} prefix
   */
  function setGender(gender, prefix) {
    prefix = prefix || 'hp-';
    const map = { female: 'gFemale', male: 'gMale', other: 'gOther' };
    Object.values(map).forEach((key) => {
      const el = document.getElementById(prefix + key);
      if (el) el.classList.remove('active');
    });
    const target = document.getElementById(prefix + (map[gender] || 'gOther'));
    if (target) target.classList.add('active');
  }

  /* ─────────────────────────────────────────────
   * AUTO-INIT on DOMContentLoaded
   * ───────────────────────────────────────────── */
  function autoInit() {
    // 1. Scroll reveal
    initScrollReveal();

    // 2. Auto-build any [data-bmi-slot] wrapper
    document.querySelectorAll('[data-bmi-slot]').forEach((wrapper) => {
      const prefix = (wrapper.dataset.bmiPrefix || 'hp') + '-';
      buildSlotInWrapper(wrapper, prefix);
    });

    // 3. Wire onclick helpers for pages that use data-bmi-calc / data-bmi-gender buttons
    //    <button data-bmi-calc="hp">Calculate</button>
    //    <button data-bmi-gender="female" data-bmi-prefix="hp">Female</button>
    document.querySelectorAll('[data-bmi-calc]').forEach((btn) => {
      btn.addEventListener('click', () => calcBMI(btn.dataset.bmiCalc + '-'));
    });
    document.querySelectorAll('[data-bmi-gender]').forEach((btn) => {
      btn.addEventListener('click', () =>
        setGender(btn.dataset.bmiGender, (btn.dataset.bmiPrefix || 'hp') + '-')
      );
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', autoInit);
  } else {
    autoInit(); // already parsed
  }

  /* ─────────────────────────────────────────────
   * PUBLIC API  (window.ZFAnim)
   * ───────────────────────────────────────────── */
  global.ZFAnim = {
    /** Manually (re-)build slot columns inside a wrapper element */
    buildBMISlot: function (wrapperEl, prefix) {
      buildSlotInWrapper(wrapperEl, (prefix || 'hp') + '-');
    },
    /** Trigger a BMI calculation for a given prefix */
    calcBMI: function (prefix) {
      calcBMI((prefix || 'hp') + '-');
    },
    /** Highlight a gender button */
    setGender: function (gender, prefix) {
      setGender(gender, (prefix || 'hp') + '-');
    },
    /** Re-run scroll-reveal observer (useful after dynamic content injection) */
    refreshReveal: initScrollReveal,
  };
})(window);

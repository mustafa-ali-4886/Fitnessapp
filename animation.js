document.addEventListener('DOMContentLoaded', () => {
  // 1. Define the animation settings
  const config = {
    distance: 40,    // Pixels to slide up
    duration: 800,   // Animation duration in milliseconds
    threshold: 0.15  // Trigger when 15% of the element is visible
  };

  // 2. Setup the Intersection Observer
  const observer = new IntersectionObserver((entries, observer) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        const el = entry.target;

        // Trigger the web animation API smoothly
        el.animate([
          { 
            opacity: 0, 
            transform: `translateY(${config.distance}px)` 
          },
          { 
            opacity: 1, 
            transform: 'translateY(0)' 
          }
        ], {
          duration: config.duration,
          easing: 'cubic-bezier(0.25, 1, 0.5, 1)', // Smooth "out-quint" easing
          fill: 'forwards' // Keeps the final state (visible) after animation finishes
        });

        // Stop watching this element so it only animates once
        observer.unobserve(el);
      }
    });
  }, { threshold: config.threshold });

  // 3. Initialize elements found on the page
  const elementsToReveal = document.querySelectorAll('.reveal');
  
  elementsToReveal.forEach(el => {
    // Hide them immediately via JS before they scroll into view
    el.style.opacity = '0';
    
    // Start tracking the element
    observer.observe(el);
  });
});

// Only execute the slot builder if the slot wrapper container exists on the active page
if (hpSlot) {
  ['d0','d1','dot','d2'].forEach(id => hpSlot.appendChild(hpBuildSlot(id, id==='dot')));
  setTimeout(() => { hpSetSlot('d0',0,false); hpSetSlot('d1',0,false); hpSetSlot('d2',0,false); }, 50);
}

function hpSetSlot(id, digit, animate) {
  const strip = document.getElementById('hpstrip_' + id);
  if (!strip) return;
  HP_DIGITS.forEach((_,i) => {
    const el = document.getElementById('hpdigit_' + id + '_' + i);
    if (el) el.classList.toggle('active-digit', i === digit);
  });
  const H = hpGetItemH();
  const targetTop = -(digit * H);
  if (animate) {
    const spinStart = targetTop - (2 * HP_DIGITS.length * H) - (H * 3);
    strip.style.transition = 'none';
    strip.style.top = spinStart + 'px';
    requestAnimationFrame(() => requestAnimationFrame(() => {
      strip.style.transition = 'top 0.6s cubic-bezier(0.22,1,0.36,1)';
      strip.style.top = targetTop + 'px';
    }));
  } else {
    strip.style.transition = 'none';
    strip.style.top = targetTop + 'px';
  }
}

function hpSetGender(g) {
  ['hp-gFemale','hp-gMale','hp-gOther'].forEach(id => document.getElementById(id).classList.remove('active'));
  const target = document.getElementById({female:'hp-gFemale', male:'hp-gMale', other:'hp-gOther'}[g]);
  if(target) target.classList.add('active');
}

function hpCalcBMI() {
  const w = parseFloat(document.getElementById('hp-weightInput').value);
  const h = parseFloat(document.getElementById('hp-heightInput').value);
  if (!w || w < 1 || w > 450 || !h || h < 1 || h > 300) {
    alert('Please enter valid weight (1-450 kg) and height (1-300 cm).');
    return;
  }
  const hm = h / 100;
  const bmi = w / (hm * hm);
  const rounded = Math.round(bmi * 10) / 10;
  const str = rounded.toFixed(1);
  const [intPart, decPart] = str.split('.');
  const padded = intPart.padStart(2,'0');

  if (hpSlot) hpSlot.classList.remove('idle');
  setTimeout(() => hpSetSlot('d0', parseInt(padded[0]), true), 0);
  setTimeout(() => hpSetSlot('d1', parseInt(padded[1]), true), 90);
  setTimeout(() => hpSetSlot('d2', parseInt(decPart[0]), true), 180);

  const badge = document.getElementById('hp-catBadge');
  const foot  = document.getElementById('hp-bmiFoot');
  const scale = document.getElementById('hp-scaleWrap');
  const dot   = document.getElementById('hp-scaleDot');
  const result = document.getElementById('hp-slotResult');

  let cat, cls, tip, pct;
  if (bmi < 18.5)      { cat='Underweight'; cls='cat-badge show cat-under'; tip='Your BMI suggests you may be underweight.'; pct=Math.max(2,(bmi/18.5)*25); }
  else if (bmi < 25)   { cat='Normal weight'; cls='cat-badge show cat-normal'; tip='Great! Your BMI is within the healthy range.'; pct=25+((bmi-18.5)/6.5)*25; }
  else if (bmi < 30)   { cat='Overweight'; cls='cat-badge show cat-over'; tip='A balanced diet and regular exercise can help.'; pct=50+((bmi-25)/5)*25; }
  else                 { cat='Obese'; cls='cat-badge show cat-obese'; tip='Please consult a healthcare professional.'; pct=Math.min(98,75+((bmi-30)/10)*25); }

  if(badge) { badge.textContent = cat; badge.className = cls; }
  if(foot) foot.textContent = tip;
  if(result) result.classList.add('show');
  if(scale) scale.classList.add('show');
  if(dot) setTimeout(() => { dot.style.left = pct + '%'; }, 60);
}
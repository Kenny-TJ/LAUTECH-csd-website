/**
 * LAUTECH CS Department — Scroll Animations
 * Uses IntersectionObserver to trigger CSS transitions
 * as elements enter the viewport.
 */

(function () {
  'use strict';

  // ── 1. Reveal elements on scroll ──────────────────────
  function initReveal() {
    var elements = document.querySelectorAll('.reveal, .stagger-children');

    if (!elements.length) return;

    // Fallback for old browsers (IE etc.) — show everything immediately
    if (!('IntersectionObserver' in window)) {
      for (var i = 0; i < elements.length; i++) {
        elements[i].classList.add('visible');
      }
      return;
    }

    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('visible');
          observer.unobserve(entry.target); // animate once only
        }
      });
    }, {
      threshold: 0.12,       // trigger when 12% of element is visible
      rootMargin: '0px 0px -40px 0px'  // slightly before it fully enters
    });

    elements.forEach(function (el) {
      observer.observe(el);
    });
  }

  // ── 2. Animated counter for stat numbers ──────────────
  function animateCounter(el, target, duration) {
    var start     = 0;
    var startTime = null;
    // Strip non-numeric suffix (e.g. "500+" → target=500, suffix="+")
    var suffix    = target.toString().replace(/[0-9]/g, '');
    var numTarget = parseInt(target, 10);

    function step(timestamp) {
      if (!startTime) startTime = timestamp;
      var progress = Math.min((timestamp - startTime) / duration, 1);
      // Ease out cubic
      var eased = 1 - Math.pow(1 - progress, 3);
      el.textContent = Math.floor(eased * numTarget) + suffix;
      if (progress < 1) {
        window.requestAnimationFrame(step);
      } else {
        el.textContent = numTarget + suffix;
      }
    }
    window.requestAnimationFrame(step);
  }

  function initCounters() {
    var counters = document.querySelectorAll('.stat-number');
    if (!counters.length) return;

    if (!('IntersectionObserver' in window)) return;

    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          var el     = entry.target;
          var target = el.getAttribute('data-target') || el.textContent.trim();
          el.setAttribute('data-target', target);
          animateCounter(el, target, 1800);
          observer.unobserve(el);
        }
      });
    }, { threshold: 0.5 });

    counters.forEach(function (el) {
      observer.observe(el);
    });
  }

  // ── 3. Navbar shrink on scroll ────────────────────────
  function initNavbarShrink() {
    var navbar = document.querySelector('.navbar');
    if (!navbar) return;

    function onScroll() {
      if (window.scrollY > 80) {
        navbar.style.padding = '4px 0';
        navbar.style.transition = 'padding 0.3s ease';
      } else {
        navbar.style.padding = '';
      }
    }
    window.addEventListener('scroll', onScroll, { passive: true });
  }

  // ── 4. Smooth scroll for anchor links ────────────────
  function initSmoothScroll() {
    var links = document.querySelectorAll('a[href^="#"]');
    links.forEach(function (link) {
      link.addEventListener('click', function (e) {
        var target = document.querySelector(this.getAttribute('href'));
        if (target) {
          e.preventDefault();
          target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
      });
    });
  }

  // ── 5. Back-to-top button ─────────────────────────────
  function initBackToTop() {
    var btn = document.createElement('button');
    btn.id        = 'backToTop';
    btn.innerHTML = '<i class="fas fa-arrow-up"></i>';
    btn.title     = 'Back to top';
    btn.style.cssText = [
      'position:fixed',
      'bottom:28px',
      'right:24px',
      'width:44px',
      'height:44px',
      'border-radius:50%',
      'border:none',
      'background:var(--lautech-red)',
      'color:white',
      'font-size:1rem',
      'cursor:pointer',
      'opacity:0',
      'transform:translateY(20px)',
      'transition:opacity 0.3s ease, transform 0.3s ease',
      'z-index:9999',
      'box-shadow:0 4px 16px rgba(218,37,29,0.35)',
    ].join(';');

    document.body.appendChild(btn);

    window.addEventListener('scroll', function () {
      if (window.scrollY > 400) {
        btn.style.opacity  = '1';
        btn.style.transform = 'translateY(0)';
      } else {
        btn.style.opacity  = '0';
        btn.style.transform = 'translateY(20px)';
      }
    }, { passive: true });

    btn.addEventListener('click', function () {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

  // ── Run everything after DOM is ready ─────────────────
  document.addEventListener('DOMContentLoaded', function () {
    initReveal();
    initCounters();
    initNavbarShrink();
    initSmoothScroll();
    initBackToTop();
  });

}());

// JavaScript Utilities cho Shoe Store
// Dark mode, animations, và micro-interactions

(function() {
  'use strict';

  // ============================================
  // Dark Mode Toggle
  // ============================================
  const DarkMode = {
    init() {
      this.checkSystemPreference();
      this.checkLocalStorage();
      this.setupToggle();
    },

    checkSystemPreference() {
      if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
        document.documentElement.classList.add('dark');
      }
    },

    checkLocalStorage() {
      const stored = localStorage.getItem('darkMode');
      if (stored !== null) {
        if (stored === 'true') {
          document.documentElement.classList.add('dark');
        } else {
          document.documentElement.classList.remove('dark');
        }
      }
    },

    setupToggle() {
      const toggle = document.getElementById('dark-mode-toggle');
      if (toggle) {
        toggle.addEventListener('click', () => this.toggle());
      }
    },

    toggle() {
      document.documentElement.classList.toggle('dark');
      const isDark = document.documentElement.classList.contains('dark');
      localStorage.setItem('darkMode', isDark);
    }
  };

  // ============================================
  // Scroll Animations (Intersection Observer)
  // ============================================
  const ScrollAnimations = {
    init() {
      this.observer = new IntersectionObserver(
        (entries) => {
          entries.forEach(entry => {
            if (entry.isIntersecting) {
              entry.target.classList.add('visible');
              this.observer.unobserve(entry.target);
            }
          });
        },
        {
          threshold: 0.1,
          rootMargin: '0px 0px -50px 0px'
        }
      );

      this.observeElements();
    },

    observeElements() {
      const elements = document.querySelectorAll('.animate-on-scroll');
      elements.forEach(el => this.observer.observe(el));
    }
  };

  // ============================================
  // Button Ripple Effect
  // ============================================
  const RippleEffect = {
    init() {
      document.addEventListener('click', (e) => {
        const btn = e.target.closest('.btn-ripple');
        if (btn) {
          this.createRipple(e, btn);
        }
      });
    },

    createRipple(event, button) {
      const circle = document.createElement('span');
      const diameter = Math.max(button.clientWidth, button.clientHeight);
      const radius = diameter / 2;

      const rect = button.getBoundingClientRect();
      circle.style.width = circle.style.height = `${diameter}px`;
      circle.style.left = `${event.clientX - rect.left - radius}px`;
      circle.style.top = `${event.clientY - rect.top - radius}px`;
      circle.classList.add('ripple');

      const existing = button.querySelector('.ripple');
      if (existing) {
        existing.remove();
      }

      button.appendChild(circle);
      setTimeout(() => circle.remove(), 600);
    }
  };

  // ============================================
  // Mobile Menu Toggle
  // ============================================
  const MobileMenu = {
    init() {
      this.toggle = document.getElementById('mobile-menu-toggle');
      this.menu = document.getElementById('mobile-menu');
      this.overlay = document.getElementById('mobile-menu-overlay');

      if (this.toggle) {
        this.toggle.addEventListener('click', () => this.toggleMenu());
      }

      if (this.overlay) {
        this.overlay.addEventListener('click', () => this.closeMenu());
      }

      // Close menu on escape key
      document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') this.closeMenu();
      });
    },

    toggleMenu() {
      this.menu.classList.toggle('open');
      this.overlay.classList.toggle('hidden');
      document.body.classList.toggle('overflow-hidden');
    },

    closeMenu() {
      this.menu.classList.remove('open');
      this.overlay.classList.add('hidden');
      document.body.classList.remove('overflow-hidden');
    }
  };

  // ============================================
  // Smooth Scroll to Top
  // ============================================
  const ScrollToTop = {
    init() {
      this.btn = document.getElementById('scroll-to-top');
      if (!this.btn) return;

      window.addEventListener('scroll', () => this.handleScroll());
      this.btn.addEventListener('click', () => this.scrollToTop());
    },

    handleScroll() {
      if (window.scrollY > 300) {
        this.btn.classList.remove('opacity-0', 'pointer-events-none');
      } else {
        this.btn.classList.add('opacity-0', 'pointer-events-none');
      }
    },

    scrollToTop() {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    }
  };

  // ============================================
  // Toast Notifications
  // ============================================
  const Toast = {
    show(message, type = 'info', duration = 3000) {
      const toast = document.createElement('div');
      toast.className = `toast fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg ${
        type === 'success' ? 'bg-green-500' :
        type === 'error' ? 'bg-red-500' :
        'bg-blue-500'
      } text-white font-medium`;
      toast.textContent = message;

      document.body.appendChild(toast);

      setTimeout(() => {
        toast.classList.add('hide');
        setTimeout(() => toast.remove(), 500);
      }, duration);
    }
  };

  // ============================================
  // Quantity Selector
  // ============================================
  const QuantitySelector = {
    init() {
      document.querySelectorAll('.quantity-selector').forEach(selector => {
        this.setupSelector(selector);
      });
    },

    setupSelector(selector) {
      const input = selector.querySelector('input[type="number"]');
      const minus = selector.querySelector('.quantity-minus');
      const plus = selector.querySelector('.quantity-plus');

      if (minus && input) {
        minus.addEventListener('click', () => {
          const min = parseInt(input.min) || 1;
          const val = Math.max(min, parseInt(input.value) - 1);
          input.value = val;
          input.dispatchEvent(new Event('change'));
        });
      }

      if (plus && input) {
        plus.addEventListener('click', () => {
          const max = parseInt(input.max) || 999;
          const val = Math.min(max, parseInt(input.value) + 1);
          input.value = val;
          input.dispatchEvent(new Event('change'));
        });
      }
    }
  };

  // ============================================
  // Debounce Utility
  // ============================================
  function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
      const later = () => {
        clearTimeout(timeout);
        func(...args);
      };
      clearTimeout(timeout);
      timeout = setTimeout(later, wait);
    };
  }

  // ============================================
  // Throttle Utility
  // ============================================
  function throttle(func, limit) {
    let inThrottle;
    return function(...args) {
      if (!inThrottle) {
        func.apply(this, args);
        inThrottle = true;
        setTimeout(() => inThrottle = false, limit);
      }
    };
  }

  // ============================================
  // Initialize all utilities
  // ============================================
  document.addEventListener('DOMContentLoaded', () => {
    DarkMode.init();
    ScrollAnimations.init();
    RippleEffect.init();
    MobileMenu.init();
    ScrollToTop.init();
    QuantitySelector.init();

    // Expose utilities globally
    window.AppUtils = {
      Toast,
      debounce,
      throttle
    };
  });

})();

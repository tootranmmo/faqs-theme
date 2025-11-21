/**
 * FAQs Theme Main JavaScript
 * Vanilla JavaScript (no jQuery)
 *
 * @package FAQs_Theme
 * @since 1.0.0
 */

(function () {
  'use strict';

  /**
   * Dark Mode Toggle
   */
  function initDarkMode() {
    const darkModeToggle = document.getElementById('dark-mode-toggle');
    const mobileDarkModeToggle = document.getElementById('mobile-dark-mode-toggle');

    function toggleDarkMode() {
      const html = document.documentElement;
      const isDark = html.classList.contains('dark');

      if (isDark) {
        html.classList.remove('dark');
        localStorage.setItem('darkMode', 'disabled');
        updateAriaPressed(false);
      } else {
        html.classList.add('dark');
        localStorage.setItem('darkMode', 'enabled');
        updateAriaPressed(true);
      }
    }

    function updateAriaPressed(isDark) {
      if (darkModeToggle) {
        darkModeToggle.setAttribute('aria-pressed', isDark ? 'true' : 'false');
      }
      if (mobileDarkModeToggle) {
        mobileDarkModeToggle.setAttribute('aria-pressed', isDark ? 'true' : 'false');
      }
    }

    if (darkModeToggle) {
      darkModeToggle.addEventListener('click', toggleDarkMode);
    }

    if (mobileDarkModeToggle) {
      mobileDarkModeToggle.addEventListener('click', toggleDarkMode);
    }

    // Set initial aria-pressed state
    const isDark = document.documentElement.classList.contains('dark');
    updateAriaPressed(isDark);
  }

  /**
   * Mobile Menu Toggle
   */
  function initMobileMenu() {
    const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');

    if (!mobileMenuToggle || !mobileMenu) return;

    mobileMenuToggle.addEventListener('click', function () {
      const isExpanded = this.getAttribute('aria-expanded') === 'true';

      if (isExpanded) {
        mobileMenu.classList.add('hidden');
        this.setAttribute('aria-expanded', 'false');
      } else {
        mobileMenu.classList.remove('hidden');
        this.setAttribute('aria-expanded', 'true');
      }
    });

    // Close mobile menu when clicking outside
    document.addEventListener('click', function (event) {
      const isClickInside = mobileMenuToggle.contains(event.target) || mobileMenu.contains(event.target);

      if (!isClickInside && !mobileMenu.classList.contains('hidden')) {
        mobileMenu.classList.add('hidden');
        mobileMenuToggle.setAttribute('aria-expanded', 'false');
      }
    });
  }

  /**
   * Search Modal
   */
  function initSearchModal() {
    const searchToggle = document.getElementById('search-toggle');
    const mobileSearchToggle = document.getElementById('mobile-search-toggle');
    const searchModal = document.getElementById('search-modal');
    const closeSearchModal = document.getElementById('close-search-modal');
    const searchInput = document.getElementById('search-input');

    function openSearchModal() {
      if (!searchModal) return;

      searchModal.classList.remove('hidden');
      searchModal.setAttribute('aria-hidden', 'false');

      // Focus on search input
      if (searchInput) {
        setTimeout(() => searchInput.focus(), 100);
      }

      // Prevent body scroll
      document.body.style.overflow = 'hidden';

      // Update aria-expanded
      if (searchToggle) searchToggle.setAttribute('aria-expanded', 'true');
    }

    function closeModal() {
      if (!searchModal) return;

      searchModal.classList.add('hidden');
      searchModal.setAttribute('aria-hidden', 'true');

      // Restore body scroll
      document.body.style.overflow = '';

      // Update aria-expanded
      if (searchToggle) searchToggle.setAttribute('aria-expanded', 'false');
    }

    if (searchToggle) {
      searchToggle.addEventListener('click', openSearchModal);
    }

    if (mobileSearchToggle) {
      mobileSearchToggle.addEventListener('click', openSearchModal);
    }

    if (closeSearchModal) {
      closeSearchModal.addEventListener('click', closeModal);
    }

    // Close on Escape key
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && searchModal && !searchModal.classList.contains('hidden')) {
        closeModal();
      }
    });

    // Close when clicking on backdrop
    if (searchModal) {
      searchModal.addEventListener('click', function (e) {
        if (e.target === searchModal) {
          closeModal();
        }
      });
    }
  }

  /**
   * Live AJAX Search
   */
  function initLiveSearch() {
    const searchInput = document.getElementById('search-input');
    const heroSearchInput = document.getElementById('hero-search-input');
    const searchResults = document.getElementById('search-results');
    const heroSearchSuggestions = document.getElementById('hero-search-suggestions');

    let searchTimeout;

    function performSearch(input, resultsContainer) {
      const query = input.value.trim();

      if (query.length < 2) {
        resultsContainer.innerHTML = '';
        resultsContainer.classList.add('hidden');
        return;
      }

      clearTimeout(searchTimeout);
      searchTimeout = setTimeout(() => {
        const formData = new FormData();
        formData.append('action', 'faqs_search');
        formData.append('nonce', faqsTheme.nonce);
        formData.append('query', query);

        fetch(faqsTheme.ajaxUrl, {
          method: 'POST',
          body: formData,
        })
          .then((response) => response.json())
          .then((data) => {
            if (data.success && data.data.results.length > 0) {
              displaySearchResults(data.data.results, resultsContainer);
            } else {
              resultsContainer.innerHTML = '<div class="p-4 text-gray-600 dark:text-gray-400">' + faqsTheme.searchPlaceholder + '</div>';
              resultsContainer.classList.remove('hidden');
            }
          })
          .catch((error) => {
            console.error('Search error:', error);
          });
      }, 300);
    }

    function displaySearchResults(results, container) {
      let html = '<div class="divide-y divide-gray-200 dark:divide-gray-700">';

      results.forEach((result) => {
        html += '<a href="' + result.url + '" class="block p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">';
        html += '<h4 class="font-semibold text-gray-900 dark:text-white mb-1">' + result.title + '</h4>';

        if (result.category) {
          html += '<span class="inline-block px-2 py-1 text-xs bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400 rounded-full mr-2 mb-2">' + result.category + '</span>';
        }

        html += '<p class="text-sm text-gray-600 dark:text-gray-400">' + result.excerpt + '</p>';

        if (result.rating) {
          html += '<div class="flex items-center gap-1 mt-2 text-yellow-400 text-sm">';
          html += '⭐ ' + parseFloat(result.rating).toFixed(1);
          html += '</div>';
        }

        html += '</a>';
      });

      html += '</div>';

      container.innerHTML = html;
      container.classList.remove('hidden');
    }

    if (searchInput) {
      searchInput.addEventListener('input', () => performSearch(searchInput, searchResults));
    }

    if (heroSearchInput) {
      heroSearchInput.addEventListener('input', () => performSearch(heroSearchInput, heroSearchSuggestions));
    }
  }

  /**
   * Rating System
   */
  function initRating() {
    const ratingInputs = document.querySelectorAll('[id^="rating-input-"]');

    ratingInputs.forEach((ratingInput) => {
      const postId = ratingInput.dataset.postId;
      const starButtons = ratingInput.querySelectorAll('.star-button');

      starButtons.forEach((button, index) => {
        // Hover effect
        button.addEventListener('mouseenter', () => {
          highlightStars(starButtons, index + 1);
        });

        // Click to rate
        button.addEventListener('click', () => {
          submitRating(postId, index + 1, starButtons);
        });
      });

      // Reset on mouse leave
      ratingInput.addEventListener('mouseleave', () => {
        resetStars(starButtons);
      });
    });

    function highlightStars(buttons, count) {
      buttons.forEach((btn, idx) => {
        const svg = btn.querySelector('svg');
        if (idx < count) {
          svg.classList.remove('text-gray-300', 'dark:text-gray-600');
          svg.classList.add('text-yellow-400');
        } else {
          svg.classList.add('text-gray-300', 'dark:text-gray-600');
          svg.classList.remove('text-yellow-400');
        }
      });
    }

    function resetStars(buttons) {
      buttons.forEach((btn) => {
        const svg = btn.querySelector('svg');
        svg.classList.add('text-gray-300', 'dark:text-gray-600');
        svg.classList.remove('text-yellow-400');
      });
    }

    function submitRating(postId, rating, buttons) {
      const formData = new FormData();
      formData.append('action', 'faqs_submit_rating');
      formData.append('nonce', faqsTheme.nonce);
      formData.append('post_id', postId);
      formData.append('rating', rating);

      fetch(faqsTheme.ajaxUrl, {
        method: 'POST',
        body: formData,
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            // Update rating display
            const ratingDisplay = document.querySelector('#post-' + postId + ' .rating-display');
            if (ratingDisplay) {
              ratingDisplay.innerHTML = data.data.html;
            }

            // Disable rating buttons
            buttons.forEach((btn) => {
              btn.disabled = true;
              btn.classList.add('cursor-not-allowed', 'opacity-50');
            });

            // Show success message
            showNotification(data.data.message, 'success');
          } else {
            showNotification(data.data.message, 'error');
          }
        })
        .catch((error) => {
          console.error('Rating error:', error);
          showNotification('An error occurred. Please try again.', 'error');
        });
    }
  }

  /**
   * Back to Top Button
   */
  function initBackToTop() {
    const backToTopButton = document.getElementById('back-to-top');

    if (!backToTopButton) return;

    window.addEventListener('scroll', () => {
      if (window.pageYOffset > 300) {
        backToTopButton.classList.add('visible');
      } else {
        backToTopButton.classList.remove('visible');
      }
    });

    backToTopButton.addEventListener('click', () => {
      window.scrollTo({
        top: 0,
        behavior: 'smooth',
      });
    });
  }

  /**
   * Notification System
   */
  function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed bottom-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg transform transition-all duration-300 ${
      type === 'success'
        ? 'bg-green-500 text-white'
        : type === 'error'
        ? 'bg-red-500 text-white'
        : 'bg-blue-500 text-white'
    }`;
    notification.textContent = message;
    notification.setAttribute('role', 'alert');
    notification.setAttribute('aria-live', 'polite');

    document.body.appendChild(notification);

    // Animate in
    setTimeout(() => {
      notification.style.transform = 'translateY(0)';
    }, 10);

    // Remove after 3 seconds
    setTimeout(() => {
      notification.style.opacity = '0';
      notification.style.transform = 'translateY(20px)';

      setTimeout(() => {
        notification.remove();
      }, 300);
    }, 3000);
  }

  /**
   * Helpful Counter
   */
  function initHelpfulCounter() {
    const helpfulCounters = document.querySelectorAll('.helpful-counter');

    helpfulCounters.forEach((counter) => {
      const postId = counter.dataset.postId;
      const helpfulBtn = counter.querySelector('.helpful-btn');
      const notHelpfulBtn = counter.querySelector('.not-helpful-btn');

      if (helpfulBtn) {
        helpfulBtn.addEventListener('click', () => submitHelpfulVote(postId, 'helpful', counter));
      }

      if (notHelpfulBtn) {
        notHelpfulBtn.addEventListener('click', () => submitHelpfulVote(postId, 'not_helpful', counter));
      }
    });

    function submitHelpfulVote(postId, voteType, counter) {
      const formData = new FormData();
      formData.append('action', 'faqs_helpful_vote');
      formData.append('nonce', faqsTheme.nonce);
      formData.append('post_id', postId);
      formData.append('vote_type', voteType);

      fetch(faqsTheme.ajaxUrl, {
        method: 'POST',
        body: formData,
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            // Update counter display
            const helpfulStats = counter.querySelector('.helpful-stats');
            if (helpfulStats && data.data.helpful) {
              const helpful = data.data.helpful;
              const notHelpful = data.data.not_helpful || 0;
              const total = helpful + notHelpful;
              const percentage = total > 0 ? Math.round((helpful / total) * 100) : 0;

              helpfulStats.innerHTML = `
                <div class="flex items-center justify-between text-sm text-gray-600 dark:text-gray-400 mb-2">
                  <span>${helpful} people found this helpful</span>
                  <span>${percentage}%</span>
                </div>
                <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                  <div class="bg-green-500 h-2 rounded-full transition-all duration-300" style="width: ${percentage}%"></div>
                </div>
              `;
            }

            // Hide buttons and show thank you message
            const buttons = counter.querySelector('.helpful-buttons');
            if (buttons) {
              buttons.innerHTML = '<p class="text-center text-gray-600 dark:text-gray-400">' + data.data.message + '</p>';
            }

            showNotification(data.data.message, 'success');
          } else {
            showNotification(data.data.message, 'error');
          }
        })
        .catch((error) => {
          console.error('Helpful vote error:', error);
          showNotification('An error occurred. Please try again.', 'error');
        });
    }
  }

  /**
   * Initialize all features on DOM ready
   */
  function init() {
    initDarkMode();
    initMobileMenu();
    initSearchModal();
    initLiveSearch();
    initRating();
    initBackToTop();
    initHelpfulCounter();

    // Announce to screen readers that page is loaded
    const liveRegion = document.createElement('div');
    liveRegion.setAttribute('role', 'status');
    liveRegion.setAttribute('aria-live', 'polite');
    liveRegion.className = 'sr-only';
    liveRegion.textContent = 'Page loaded';
    document.body.appendChild(liveRegion);
  }

  // Initialize when DOM is ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();

/**
 * Knowledge Commons - WordPress Theme JavaScript
 */

document.addEventListener('DOMContentLoaded', function() {
	// Mobile Menu Toggle
	const mobileMenuBtn = document.getElementById('mobileMenuBtn');
	const mobileMenu = document.getElementById('mobileMenu');
	const mobileOverlay = document.getElementById('mobileOverlay');

	if (mobileMenuBtn && mobileMenu && mobileOverlay) {
		const hamburgerIcon = mobileMenuBtn.querySelector('.hamburger-icon');
		const closeIcon = mobileMenuBtn.querySelector('.close-icon');

		function toggleMobileMenu() {
			const isOpen = mobileMenu.classList.contains('active');

			if (isOpen) {
				mobileMenu.classList.remove('active');
				mobileOverlay.classList.remove('active');
				if (hamburgerIcon) hamburgerIcon.style.display = 'inline';
				if (closeIcon) closeIcon.style.display = 'none';
				document.body.style.overflow = '';
			} else {
				mobileMenu.classList.add('active');
				mobileOverlay.classList.add('active');
				if (hamburgerIcon) hamburgerIcon.style.display = 'none';
				if (closeIcon) closeIcon.style.display = 'inline';
				document.body.style.overflow = 'hidden';
			}
		}

		mobileMenuBtn.addEventListener('click', toggleMobileMenu);
		mobileOverlay.addEventListener('click', toggleMobileMenu);

		// Close mobile menu when clicking a link
		const mobileNavLinks = mobileMenu.querySelectorAll('a');
		mobileNavLinks.forEach(function(link) {
			link.addEventListener('click', function() {
				if (mobileMenu.classList.contains('active')) {
					toggleMobileMenu();
				}
			});
		});
	}

	// Header background on scroll + hide on scroll down (mobile only)
	const header = document.querySelector('.header');
	if (header) {
		let lastScroll = 0;
		const scrollThreshold = 10; // Minimum scroll amount to trigger hide/show
		const hasAdminBar = document.body.classList.contains('admin-bar');
		const adminBarHeight = window.innerWidth <= 782 ? 46 : 32;

		window.addEventListener('scroll', function() {
			const currentScroll = window.pageYOffset;

			// Add shadow when scrolled
			if (currentScroll > 50) {
				header.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.1)';
			} else {
				header.style.boxShadow = 'none';
			}

			// Mobile only behaviors
			if (window.innerWidth < 1024) {
				// Move header to top when admin bar scrolls out of view
				if (hasAdminBar) {
					if (currentScroll >= adminBarHeight) {
						header.classList.add('header-at-top');
					} else {
						header.classList.remove('header-at-top');
					}
				}

				// Don't hide if we're near the top
				if (currentScroll <= 100) {
					header.classList.remove('header-hidden');
					lastScroll = currentScroll;
					return;
				}

				// Check scroll direction with threshold
				if (currentScroll > lastScroll + scrollThreshold) {
					// Scrolling down - hide header
					header.classList.add('header-hidden');
				} else if (currentScroll < lastScroll - scrollThreshold) {
					// Scrolling up - show header
					header.classList.remove('header-hidden');
				}
			} else {
				// Always show on desktop, remove mobile classes
				header.classList.remove('header-hidden');
				header.classList.remove('header-at-top');
			}

			lastScroll = currentScroll;
		});
	}

	// Smooth scroll for anchor links
	document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
		anchor.addEventListener('click', function(e) {
			const href = this.getAttribute('href');
			if (href !== '#') {
				e.preventDefault();
				const target = document.querySelector(href);
				if (target) {
					target.scrollIntoView({
						behavior: 'smooth',
						block: 'start'
					});
				}
			}
		});
	});

	// Search Modal
	const searchToggle = document.getElementById('searchToggle');
	const searchToggleMobile = document.getElementById('searchToggleMobile');
	const searchModal = document.getElementById('searchModal');
	const searchOverlay = document.getElementById('searchOverlay');
	const searchClose = document.getElementById('searchClose');
	const searchInput = document.getElementById('searchInput');
	const searchForm = document.getElementById('searchForm');

	function openSearchModal() {
		if (searchModal && searchOverlay) {
			searchModal.classList.add('active');
			searchOverlay.classList.add('active');
			document.body.classList.add('search-modal-open');

			// Focus the input after animation
			setTimeout(function() {
				if (searchInput) {
					searchInput.focus();
				}
			}, 100);

			// Close mobile menu if open
			if (mobileMenu && mobileMenu.classList.contains('active')) {
				mobileMenu.classList.remove('active');
				mobileOverlay.classList.remove('active');
				const hamburgerIcon = mobileMenuBtn ? mobileMenuBtn.querySelector('.hamburger-icon') : null;
				const closeIcon = mobileMenuBtn ? mobileMenuBtn.querySelector('.close-icon') : null;
				if (hamburgerIcon) hamburgerIcon.style.display = 'inline';
				if (closeIcon) closeIcon.style.display = 'none';
			}
		}
	}

	function closeSearchModal() {
		if (searchModal && searchOverlay) {
			searchModal.classList.remove('active');
			searchOverlay.classList.remove('active');
			document.body.classList.remove('search-modal-open');

			// Clear input
			if (searchInput) {
				searchInput.value = '';
			}
		}
	}

	// Open search modal on button click
	if (searchToggle) {
		searchToggle.addEventListener('click', function(e) {
			e.preventDefault();
			openSearchModal();
		});
	}

	if (searchToggleMobile) {
		searchToggleMobile.addEventListener('click', function(e) {
			e.preventDefault();
			openSearchModal();
		});
	}

	// Close search modal
	if (searchClose) {
		searchClose.addEventListener('click', closeSearchModal);
	}

	if (searchOverlay) {
		searchOverlay.addEventListener('click', closeSearchModal);
	}

	// Close on Escape key
	document.addEventListener('keydown', function(e) {
		if (e.key === 'Escape' && searchModal && searchModal.classList.contains('active')) {
			closeSearchModal();
		}
	});

	// Handle form submission
	if (searchForm) {
		searchForm.addEventListener('submit', function(e) {
			const query = searchInput ? searchInput.value.trim() : '';
			if (!query) {
				e.preventDefault();
				return;
			}
			// Form will submit naturally to /search/?s=query
		});
	}
});

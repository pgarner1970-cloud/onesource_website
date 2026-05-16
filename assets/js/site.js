document.querySelector('.menu-toggle')?.addEventListener('click', () => {
  document.querySelector('.main-nav')?.classList.toggle('open');
});

const filters = document.querySelectorAll('.filter');
const projects = document.querySelectorAll('.project');

filters.forEach((button) => {
  button.addEventListener('click', () => {
    filters.forEach((b) => b.classList.remove('active'));
    button.classList.add('active');
    const filter = button.dataset.filter;
    projects.forEach((project) => {
      const show = filter === 'all' || project.dataset.category === filter;
      project.classList.toggle('hidden', !show);
    });
  });
});


// Load editable default service/category images set in admin
fetch('category-images-data.php')
  .then(response => response.ok ? response.json() : null)
  .then(images => {
    if (!images) return;

    document.querySelectorAll('[data-service-image]').forEach((img) => {
      const key = img.getAttribute('data-service-image');
      if (images[key]) {
        img.src = images[key];
      }
    });
  })
  .catch(() => {});


// Mobile navigation repair
document.addEventListener('DOMContentLoaded', function () {
  const menuToggle = document.querySelector('.menu-toggle');
  const mainNav = document.querySelector('.main-nav');
  const serviceDropdown = document.querySelector('.main-nav .nav-dropdown');

  if (menuToggle && mainNav) {
    menuToggle.setAttribute('aria-expanded', 'false');

    menuToggle.addEventListener('click', function () {
      const isOpen = mainNav.classList.toggle('is-open');
      document.body.classList.toggle('nav-open', isOpen);
      menuToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    });
  }

  if (serviceDropdown) {
    const serviceLink = serviceDropdown.querySelector(':scope > a');

    if (serviceLink) {
      serviceLink.addEventListener('click', function (event) {
        if (window.matchMedia('(max-width: 860px)').matches) {
          event.preventDefault();
          serviceDropdown.classList.toggle('is-open');
        }
      });
    }
  }
});

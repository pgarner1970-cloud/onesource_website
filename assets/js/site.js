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
fetch('data/category-images.json')
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

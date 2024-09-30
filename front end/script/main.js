document.addEventListener('DOMContentLoaded', function() {
    const items = document.querySelectorAll('.fitur-item');
    window.addEventListener('scroll', function() {
      const scrollPos = window.scrollY;
      items.forEach(item => {
        const itemTop = item.offsetTop;
        if (scrollPos > itemTop - window.innerHeight / 1.3) {
          item.style.opacity = 1;
          item.style.transform = 'translateY(0)';
        } else {
          item.style.opacity = 0;
          item.style.transform = 'translateY(20px)';
        }
      });
    });
  });
// Script for transparent header on scroll
window.addEventListener('scroll', function () {
  const header = document.querySelector('header');
  header.classList.toggle('scrolled', window.scrollY > 50);
});

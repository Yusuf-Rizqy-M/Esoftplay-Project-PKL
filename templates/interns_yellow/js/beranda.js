
let slides = document.querySelectorAll('.bg-slide');
let dots = document.querySelectorAll('.nav-dot');
let current = 0;
let slideInterval;
function cycleSlide(n) {
  slides.forEach(s => s.classList.remove('active'));
  dots.forEach(d => d.classList.remove('active'));
  if (slides[n]) slides[n].classList.add('active');
  if (dots[n]) dots[n].classList.add('active');
}
function startInterval() {
  slideInterval = setInterval(() => {
    current = (current + 1) % slides.length;
    cycleSlide(current);
  }, 7000);
}
if (slides.length > 0) {
  startInterval();
  dots.forEach((dot, i) => {
    dot.addEventListener('click', () => {
      current = i;
      cycleSlide(current);
      clearInterval(slideInterval);
      startInterval();
    });
  });
}
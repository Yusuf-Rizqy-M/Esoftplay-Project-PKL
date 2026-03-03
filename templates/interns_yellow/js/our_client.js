
function openZoom(imgSrc) {
  const modal = document.getElementById('zoomModal');
  const modalImg = document.getElementById('modalImg');
  if (!modal || !modalImg) return;
  modalImg.src = imgSrc;
  modal.style.display = 'flex';
  setTimeout(() => {
    modal.classList.add('active');
  }, 10);
  document.body.style.overflow = 'hidden';
}

function closeZoom() {
  const modal = document.getElementById('zoomModal');
  if (!modal) return;
  modal.classList.remove('active');
  document.body.style.overflow = '';
  setTimeout(() => {
    modal.style.display = 'none';
  }, 300);
}
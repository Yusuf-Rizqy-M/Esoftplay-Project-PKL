<?php if (!defined('_VALID_BBC')) exit('No direct script access allowed'); ?>
<style>
*{margin:0;padding:0;box-sizing:border-box}
html,body{width:100%;height:100%;margin:0;padding:0;overflow-x:hidden;font-family:'Segoe UI',Roboto,Helvetica,Arial,sans-serif}
.client-says-section{position:relative;width:100vw;height:100vh;background:#000;overflow:hidden}
.bg-image-container{position:absolute;inset:0;width:100%;height:100%}
.bg-image-container img{
    position:absolute;
    inset:0;
    width:100%;
    height:100%;
    object-fit:cover;
    object-position:center center;
    transform:scale(1.1);
    opacity:0;
    transition:opacity 1s ease-in-out;
}
.bg-image-container img.active{opacity:1}
.overlay-dark{position:absolute;inset:0;background:linear-gradient(90deg,rgba(0,0,0,0.85) 0%,rgba(0,0,0,0.5) 50%,rgba(0,0,0,0.1) 100%);z-index:1}
.content-wrapper{position:absolute;top:50%;left:18%;transform:translateY(-50%);max-width:480px;z-index:10;color:#fff}
.client-heading{font-size:22px;font-weight:700;text-transform:uppercase;letter-spacing:2px;margin-bottom:20px}
.styled-quote-mark{font-size:50px;color:#FFD700;line-height:1;margin-bottom:15px;font-family:serif}
.client-paragraph{font-size:15px;line-height:1.8;color:#d0d0d0;margin-bottom:40px}
.nav-dots{display:flex;gap:12px}
.nav-dot{width:10px;height:10px;border-radius:50%;background:rgba(255,255,255,0.3);cursor:pointer;transition:all .3s ease}
.nav-dot.active{background:#FFD700;transform:scale(1.2)}
@media (max-width:768px){
    .content-wrapper{left:10%;right:10%;max-width:none}
    .client-heading{font-size:20px}
    .client-paragraph{font-size:14px}
}
</style>

<div class="client-says-section">
    <div class="bg-image-container">
        <img src="<?php echo $config['image1']; ?>" class="bg-slide active">
        <img src="<?php echo $config['image2']; ?>" class="bg-slide">
        <img src="<?php echo $config['image3']; ?>" class="bg-slide">
    </div>
    <div class="overlay-dark"></div>
    <div class="content-wrapper">
        <h2 class="client-heading"><?php echo $config['Tagline']; ?></h2>
        <div class="styled-quote-mark">“</div>
        <p class="client-paragraph"><?php echo $config['Paragraf']; ?></p>
        <div class="nav-dots">
            <span class="nav-dot active"></span>
            <span class="nav-dot"></span>
            <span class="nav-dot"></span>
        </div>
    </div>
</div>

<script>
let slides = document.querySelectorAll('.bg-slide');
let dots = document.querySelectorAll('.nav-dot');
let current = 0;
let slideInterval;

function cycleSlide(n) {
    slides.forEach(s => s.classList.remove('active'));
    dots.forEach(d => d.classList.remove('active'));
    slides[n].classList.add('active');
    dots[n].classList.add('active');
}

function startInterval() {
    slideInterval = setInterval(() => {
        current = (current + 1) % slides.length;
        cycleSlide(current);
    }, 7000);
}

startInterval();

dots.forEach((dot, i) => {
    dot.addEventListener('click', () => {
        current = i;
        cycleSlide(current);
        clearInterval(slideInterval);
        startInterval();
    });
});
</script>
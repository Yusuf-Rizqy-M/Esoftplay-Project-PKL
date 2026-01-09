<?php if (!defined('_VALID_BBC')) exit('No direct script access allowed'); ?>

<style>
:root {
    --primary: #6D81F6;
    --primary-soft: rgba(109,129,246,.15);
    --dark: #0f172a;
    --muted: #64748b;
    --bg: #f8fafc;
}

.esoft-hero {
    max-width: 1200px;
    margin: 120px auto;
    padding: 0 40px;
    font-family: 'Inter', system-ui, sans-serif;
}

.esoft-grid {
    display: grid;
    grid-template-columns: 1.1fr 0.9fr;
    gap: 90px;
    align-items: center;
}

.esoft-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 18px;
    border-radius: 999px;
    background: var(--primary-soft);
    color: var(--primary);
    font-weight: 600;
    font-size: 12px;
    letter-spacing: 1.4px;
    margin-bottom: 26px;
}

.esoft-title {
    font-size: 54px;
    line-height: 1.1;
    font-weight: 300;
    color: var(--dark);
    margin-bottom: 32px;
}

.esoft-title b {
    font-weight: 800;
    display: block;
}

.esoft-desc {
    font-size: 18px;
    line-height: 1.9;
    color: var(--muted);
    max-width: 520px;
}

.esoft-visual {
    position: relative;
}

.esoft-frame {
    position: relative;
    aspect-ratio: 1/1;
    border-radius: 36px;
    overflow: hidden;
    background: linear-gradient(145deg,#eaeefe,#ffffff);
    box-shadow:
        0 40px 80px -25px rgba(0,0,0,.18),
        inset 0 0 0 1px rgba(255,255,255,.4);
}

.esoft-frame img {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    opacity: 0;
    transform: scale(1.08);
    transition: opacity 1.2s ease, transform 1.2s ease;
}

.esoft-frame img.active {
    opacity: 1;
    transform: scale(1);
}

.esoft-indicator {
    position: absolute;
    bottom: -42px;
    right: 0;
    display: flex;
    gap: 10px;
}

.esoft-dot {
    width: 42px;
    height: 4px;
    border-radius: 4px;
    background: #e5e7eb;
    overflow: hidden;
}

.esoft-dot span {
    display: block;
    height: 100%;
    width: 0;
    background: linear-gradient(90deg,var(--primary),#9aa8ff);
}

.esoft-dot.active span {
    width: 100%;
    transition: width 4s linear;
}

@media(max-width: 992px) {
    .esoft-grid {
        grid-template-columns: 1fr;
        text-align: center;
    }
    .esoft-desc {
        margin: 0 auto;
    }
    .esoft-indicator {
        right: 50%;
        transform: translateX(50%);
    }
}
</style>

<div class="esoft-hero">
    <div class="esoft-grid">

        <div>
            <div class="esoft-badge">OVERVIEW</div>
            <h1 class="esoft-title">
                <b>Apa Itu</b>
                Laporan Harian?
            </h1>
            <p class="esoft-desc">
                <?php echo $config['intro']; ?>
            </p>
        </div>

        <div class="esoft-visual">
            <div class="esoft-frame" id="esoftSlider">
                <img src="<?php echo $config['hero1']; ?>" class="active">
                <img src="<?php echo $config['hero2']; ?>">
                <img src="<?php echo $config['hero3']; ?>">
            </div>

            <div class="esoft-indicator" id="esoftDots">
                <div class="esoft-dot active"><span></span></div>
                <div class="esoft-dot"><span></span></div>
                <div class="esoft-dot"><span></span></div>
            </div>
        </div>

    </div>
</div>

<script>
(function(){
    const slides = document.querySelectorAll('#esoftSlider img');
    const dots = document.querySelectorAll('#esoftDots .esoft-dot');
    let i = 0;

    function slide(){
        slides[i].classList.remove('active');
        dots[i].classList.remove('active');
        i = (i + 1) % slides.length;
        slides[i].classList.add('active');
        dots[i].classList.add('active');
    }

    setInterval(slide, 4000);
})();
</script>
